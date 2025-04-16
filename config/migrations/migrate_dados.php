<?php
require_once __DIR__ . '/../database.php';

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

// Lê os cartões antigos
$cartoes_antigos = $pdo->query("SELECT * FROM cartoes_credito")->fetchAll(PDO::FETCH_ASSOC);

foreach ($cartoes_antigos as $registro) {
    $id = $registro['id'];

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
        null,
        $registro['ativo'] ?? 1
    ]);

    // Processar FINAIS como físicos (is_virtual = 0)
    $finais_array = finaisParaArray($registro['finais'] ?? '');
    foreach ($finais_array as $item) {
        list($final, $titular) = extrairFinalETitular($item);
        if (!is_numeric($final)) continue;

        $stmtFinal = $pdo->prepare("INSERT INTO final_cartao (final, cartao_id, is_virtual, titular, ativo)
                                    VALUES (?, ?, 0, ?, 1)");
        $stmtFinal->execute([
            $final,
            $id,
            $titular
        ]);
    }

    // Processar VIRTUAIS separadamente (is_virtual = 1)
    $virtuais_array = finaisParaArray($registro['virtuais'] ?? '');
    foreach ($virtuais_array as $item) {
        $final = substr($item, 0, 4);
        if (!is_numeric($final)) continue;

        $stmtFinal = $pdo->prepare("INSERT INTO final_cartao (final, cartao_id, is_virtual, titular, ativo)
                                    VALUES (?, ?, 1, ?, 1)");
        $stmtFinal->execute([
            $final,
            $id,
            null // virtuais não têm titular especificado
        ]);
    }
}

echo "<p>Migração corrigida e executada com sucesso.</p>";
