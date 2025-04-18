<?php
require_once __DIR__ . '/../config/database.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS banco (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao VARCHAR(20) NOT NULL,
    numero VARCHAR(10) NOT NULL,
    agencia VARCHAR(10) NOT NULL,
    conta VARCHAR(20) NOT NULL,
    titular VARCHAR(100),
    pix VARCHAR(100),
    ativo TINYINT(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

echo "<p>Tabela <strong>banco</strong> garantida.</p>";
