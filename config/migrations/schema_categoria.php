<?php
require_once __DIR__ . '/../config/database.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conta VARCHAR(20) NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    tipo VARCHAR(20) NOT NULL,
    ativo TINYINT DEFAULT 1
)");

echo "<p>Tabela <strong>categoria</strong> garantida.</p>";
