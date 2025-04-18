<?php
require_once '../config/database.php';
require_once '../config/legado.php';

// Apaga as tabelas (ordem correta: filho → pai)
$pdo->exec("SET FOREIGN_KEY_CHECKS=0");
$pdo->exec("DROP TABLE IF EXISTS lancamentos");
$pdo->exec("DROP TABLE IF EXISTS controle");
$pdo->exec("DROP TABLE IF EXISTS grupo_controle");
$pdo->exec("SET FOREIGN_KEY_CHECKS=1");

require_once 'schema_controle.php';

// Migra GRUPOS
$grupos = $pdoLegado->query("SELECT * FROM grupo_controle")->fetchAll(PDO::FETCH_ASSOC);
foreach ($grupos as $grupo) {
    $stmt = $pdo->prepare("INSERT INTO grupo_controle (id, descricao, ativo) VALUES (?, ?, ?)");
    $stmt->execute([
        $grupo['id'],
        $grupo['descricao'],
        $grupo['ativo'] ?? 1
    ]);
}

// Migra CONTROLES
$controles = $pdoLegado->query("SELECT * FROM controle")->fetchAll(PDO::FETCH_ASSOC);
foreach ($controles as $ctrl) {
    $stmt = $pdo->prepare("INSERT INTO controle (id, descricao, ativo, grupo_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $ctrl['id'],
        $ctrl['descricao'],
        $ctrl['ativo'] ?? 1,
        $ctrl['grupo_id'] ?? null
    ]);
}

// Migra LANCAMENTOS
$lancamentos = $pdoLegado->query("SELECT * FROM lancamentos")->fetchAll(PDO::FETCH_ASSOC);
foreach ($lancamentos as $lanc) {
    $stmt = $pdo->prepare("INSERT INTO lancamentos (id, controle_id, data, descricao, valor) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $lanc['id'],
        $lanc['controle_id'],
        $lanc['data'],
        $lanc['descricao'],
        $lanc['valor']
    ]);
}

echo "<p><strong>Grupo, Controle e Lançamentos migrados com sucesso!</strong></p>";
