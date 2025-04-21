<?php
require_once __DIR__ . '/../../config/database.php';
// require_once __DIR__ . '/../../config/legado.php';
require_once __DIR__ . '/schema_movimentacao.php';

echo "<h3>Iniciando migração de movimentações e faturas...</h3>";

// Consulta todos os registros do legado
// $stmt = $pdoLegado->query("
$stmt = $pdo->query("
    SELECT id, data AS data_pagamento, data_compra, valor, descricao, cartao_id, codigo_pagamento, 
           conta_id AS categoria_id, banco_id AS conta_id
    FROM movimentacao_financeira
    ORDER BY codigo_pagamento, cartao_id, data_compra
");

$buffer_fatura = [];
$cartao_atual = null;
$codigo_atual = null;

while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($linha['cartao_id'] === null) {
        // Registro sem cartão → movimentação direta
        inserirMovimentacaoDireta($pdo, $linha);
    } else {
        $grupo = $linha['cartao_id'] . '-' . $linha['codigo_pagamento'];

        if (!empty($buffer_fatura) && (
            $linha['cartao_id'] != $cartao_atual ||
            $linha['codigo_pagamento'] != $codigo_atual
        )) {
            processarGrupoFatura($buffer_fatura, $pdo);
            $buffer_fatura = [];
        }

        $buffer_fatura[] = $linha;
        $cartao_atual = $linha['cartao_id'];
        $codigo_atual = $linha['codigo_pagamento'];
    }
}

// Finaliza o último grupo (caso exista)
if (!empty($buffer_fatura)) {
    processarGrupoFatura($buffer_fatura, $pdo);
}

echo "<p><strong>Reconstrução concluída com sucesso!</strong></p>";

// === Funções auxiliares ===

function inserirMovimentacaoDireta($pdo, $linha) {
    $stmt = $pdo->prepare("INSERT INTO movimentacao 
        (id, data, descricao, valor, categoria_id, conta_id, codigo_pagamento)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $linha['id'],
        $linha['data_pagamento'],
        $linha['descricao'],
        $linha['valor'],
        $linha['categoria_id'],
        $linha['conta_id'],
        $linha['codigo_pagamento']
    ]);

    echo "<p>➡️ Movimentação direta ID {$linha['id']} copiada: R$ " . number_format($linha['valor'], 2, ',', '.') . "</p>";
}

function processarGrupoFatura($compras, $pdo) {
    $cartao_id = $compras[0]['cartao_id'];
    $codigo_pagamento = $compras[0]['codigo_pagamento'];
    $data_pagamento = $compras[0]['data_pagamento'];
    $id_movimentacao = $compras[0]['id'];
    $categoria_id = $compras[0]['categoria_id'];
    $conta_id = $compras[0]['conta_id'];

    $valor_total = 0;
    foreach ($compras as &$c) {
        $descricao = $c['descricao'];
        $c['parcela_atual'] = 1;
        $c['parcelas'] = 1;

        if (preg_match('/(\d{1,2})\/(\d{1,2})/', $descricao, $match)) {
            $c['parcela_atual'] = intval($match[1]);
            $c['parcelas'] = intval($match[2]);
            $descricao = preg_replace('/\(?\b\d{1,2}\/\d{1,2}\)?/', '', $descricao, 1);
            $descricao = trim($descricao);
        }
        
        $c['descricao_limpa'] = $descricao;
        $valor_total += floatval($c['valor']);
    }

    // 1. Cria fatura
    $stmt = $pdo->prepare("INSERT INTO fatura 
        (cartao_id, data_fechamento, data_vencimento, valor_total, valor_pago, status)
        VALUES (?, ?, ?, ?, ?, 'paga')");
    $data_fechamento = date('Y-m-d', strtotime($data_pagamento . ' +30 days'));
    $stmt->execute([$cartao_id, $data_fechamento, $data_pagamento, $valor_total, $valor_total]);
    $fatura_id = $pdo->lastInsertId();

    // 2. Cria compras
    $stmt = $pdo->prepare("INSERT INTO compras 
        (cartao_id, data, valor, descricao, parcelas, parcela_atual, fatura_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($compras as $c) {
        $stmt->execute([
            $cartao_id,
            $c['data_compra'],
            $c['valor'],
            $c['descricao_limpa'],
            $c['parcelas'],
            $c['parcela_atual'],
            $fatura_id
        ]);
    }

    // 3. Cria movimentação "FATURA CARTÃO" com ID original
    $stmt = $pdo->prepare("INSERT INTO movimentacao 
        (id, data, descricao, valor, categoria_id, conta_id, fatura_id, codigo_pagamento)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $id_movimentacao,
        $data_pagamento,
        'FATURA CARTÃO',
        $valor_total,
        $categoria_id,
        $conta_id,
        $fatura_id,
        $codigo_pagamento
    ]);

    // 4. Atualiza fatura com o ID da movimentação
    $pdo->prepare("UPDATE fatura SET movimentacao_id = ? WHERE id = ?")
        ->execute([$id_movimentacao, $fatura_id]);

    echo "<p>📦 Fatura $fatura_id (Cartão $cartao_id / Código $codigo_pagamento): R$ " . number_format($valor_total, 2, ',', '.') . "</p>";
}
