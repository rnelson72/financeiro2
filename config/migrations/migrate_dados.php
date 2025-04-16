<?php
require_once '../database.php';

function nullIfEmpty($value) {
    return trim($value) === '' ? null : $value;
}

function limparFinal($item) {
    $item = trim($item);
    if ($item === '' || $item === '0' || $item === '---') return null;
    return $item;
}

function extrairFinalTitular($item) {
    $item = strtoupper(trim($item));
    $final = substr($item, 0, 4);
    $titular = strlen($item) > 4 ? substr($item, 4, 1) : null;
    return [$final, $titular];
}

function finaisParaArray($campo) {
    if (!$campo || in_array(trim($campo), ['0', '---'])) return [];
    return array_filter(array_map('limparFinal', explode(';', $campo)));
}

// Selecionar registros antigos
$cartoes_antigos = $pdo->query("SELECT * FROM cartoes_credito")->fetchAll(PDO::FETCH_ASSOC);

foreach ($cartoes_antigos as $registro) {
    $finais_raw = trim($registro['finais'] ?? '');
    $virtuais_raw = trim($registro['virtuais'] ?? '');

    // Se ambos forem 0 ou vazios, ignorar inserção em final_cartao
    $ignorar_finais = in_array($finais_raw, ['0', '---', '', null]);
    $ignorar_virtuais = in_array($virtuais_raw, ['0', '---', '', null]);

    // Inserir no novo 'cartao'
    $stmt = $pdo->prepare("INSERT INTO cartao (descricao, bandeira, dia_vencimento, dia_fechamento, linha_credito, banco_id, ativo)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $registro['descricao'],
        nullIfEmpty($registro['bandeira']),
        nullIfEmpty($registro['dia_vencimento']),
        nullIfEmpty($registro['dia_fechamento']),
        nullIfEmpty($registro['linha_credito']),
        null, // banco_id ainda não existia
        $registro['ativo'] ?? 1
    ]);
    $novo_cartao_id = $pdo->lastInsertId();

    // Ignora finais/virtuais se ambos não tiverem valor útil
    if ($ignorar_finais && $ignorar_virtuais) continue;

    // Processa os finais
    $finais_array = finaisParaArray($finais_raw);
    $virtuais_array = array_map('trim', explode(';', $virtuais_raw));

    foreach ($finais_array as $item) {
        list($final, $titular) = extrairFinalTitular($item);
        if (!$final || !is_numeric($final)) continue;

        $virtual = in_array($final, $virtuais_array) ? 1 : 0;

        $stmt = $pdo->prepare("INSERT INTO final_cartao (final, cartao_id, virtual, titular, ativo)
                               VALUES (?, ?, ?, ?, 1)");
        $stmt->execute([
            $final,
            $novo_cartao_id,
            $virtual,
            $titular
        ]);
    }
}

echo "Migração executada com sucesso.\n";
