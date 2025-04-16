<?php
// config/migrations/migrate_categoria.php
require_once '../config/database.php';

// 1. Cria a tabela nova 'categoria' se ainda não existir
$pdo->exec("CREATE TABLE IF NOT EXISTS categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conta VARCHAR(20) NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    ativo TINYINT DEFAULT 1
)");

// 2. Copia os dados da antiga 'contas_contabeis', se existir
if (tabelaExiste($pdo, 'contas_contabeis')) {
    $rows = $pdo->query("SELECT * FROM contas_contabeis")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $linha) {
        $stmt = $pdo->prepare("INSERT INTO categoria (id, conta, descricao, tipo, ativo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $linha['id'],
            $linha['conta'],
            $linha['descricao'],
            $linha['tipo'],
            $linha['ativo'] ?? 1
        ]);
    }
    echo "Dados migrados de contas_contabeis para categoria.\n";
} else {
    echo "Tabela contas_contabeis não encontrada. Nenhum dado migrado.\n";
}

function tabelaExiste($pdo, $nome) {
    $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
    $stmt->execute([$nome]);
    return $stmt->fetch() !== false;
}
