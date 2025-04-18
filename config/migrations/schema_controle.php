<?php
require_once __DIR__ . '/../config/database.php';

// Tabelas são criadas na ordem correta para respeitar dependências

$pdo->exec("CREATE TABLE IF NOT EXISTS grupo_controle (
    id INT NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    ativo TINYINT(1) DEFAULT 1,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS controle (
    id INT NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    ativo TINYINT(1) DEFAULT 1,
    grupo_id INT DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (grupo_id) REFERENCES grupo_controle(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS lancamentos (
    id INT NOT NULL,
    controle_id INT DEFAULT NULL,
    data DATE NOT NULL,
    descricao VARCHAR(100) NOT NULL,
    valor DECIMAL(12,2) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (controle_id) REFERENCES controle(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

echo "<p>Tabelas <strong>grupo_controle</strong>, <strong>controle</strong> e <strong>lancamentos</strong> garantidas.</p>";
