<?php
require_once __DIR__ . '/../../database.php';
//require_once __DIR__ . '/../../config/legado.php';
require_once __DIR__ . '/schema_cartao.php';

function nullIfEmpty($value) {
    return trim($value) === '' ? null : $value;
}

function limparFinal($item) {
    $item = strtoupper(trim($item));
    if ($item === '' || $item === '0' || $item === '---') return null;
    return $item;
}

function extrairFinalETitular($item) {
    $item = strtoupper(trim($item));
    $final = substr($item, 0, 4);
    $titular = strlen($item) > 4 ? substr($item, 4, 1) : null;
    return [$final, $titular];
}

function finaisParaArray($campo) {
    if (!$campo || in_array(trim($campo), ['0', '---'])) return [];
    return array_filter(array_map('limparFinal', explode(';', $campo)));
}

// Limpa as tabelas de destino
$pdo->exec("SET FOREIGN_KEY_CHECKS=0");
$pdo->exec("DROP TABLE IF EXISTS final_cartao");
$pdo->exec("DROP TABLE IF EXISTS cartao");
$pdo->exec("SET FOREIGN_KEY_CHECKS=1");

// Recria estrutura
require_once __DIR__ . '/schema_cartao.php';

// Lê os cartões antigos do PostgreSQL
//$cartoes_antigos = $pdoLegado->query("SELECT * FROM cartoes_credito")->fetchAll(PDO::FETCH_ASSOC);
$cartoes_antigos = $pdo->query("SELECT * FROM cartoes_credito")->fetchAll(PDO::FETCH_ASSOC);

foreach ($cartoes_antigos as $registro) {
    $id = $registro['id'];

    try {
        $pdo->beginTransaction();

        // Inserir no novo 'cartao' com mesmo ID
        $stmt = $pdo->prepare("INSERT INTO cartao (id, descricao, bandeira, dia_vencimento, dia_fechamento, linha_credito, banco_id, ativo)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $id,
            $registro['descricao'],
            nullIfEmpty($registro['bandeira']),
            nullIfEmpty($registro['dia_vencimento']),
            nullIfEmpty($registro['dia_fechamento']),
            nullIfEmpty($registro['linha_credito']),
            null, // banco_id será associado manualmente depois
            $registro['ativo'] ?? 1
        ]);

        // Processar FINAIS como físicos (is_virtual = 0)
        $finais_array = finaisParaArray($registro['finais'] ?? '');
        foreach ($finais_array as $item) {
            list($final, $titular) = extrairFinalETitular($item);
            if (!is_numeric($final)) continue;

            $stmtFinal = $pdo->prepare("INSERT INTO final_cartao (final, cartao_id, is_virtual, titular, ativo)
                                        VALUES (?, ?, 0, ?, 1)");
            $stmtFinal->execute([$final, $id, $titular]);
        }

        // Processar VIRTUAIS separadamente (is_virtual = 1)
        $virtuais_array = finaisParaArray($registro['virtuais'] ?? '');
        foreach ($virtuais_array as $item) {
            $final = substr($item, 0, 4);
            if (!is_numeric($final)) continue;

            $stmtFinal = $pdo->prepare("INSERT INTO final_cartao (final, cartao_id, is_virtual, titular, ativo)
                                        VALUES (?, ?, 1, ?, 1)");
            $stmtFinal->execute([$final, $id, null]); // virtuais não têm titular
        }

        $pdo->commit();
        echo "<p>Cartão ID <strong>$id</strong> migrado com sucesso.</p>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p style='color:red'>Erro ao migrar cartão ID $id: {$e->getMessage()}</p>";
    }
}

echo "<p><strong>Migração finalizada com sucesso.</strong></p>";
