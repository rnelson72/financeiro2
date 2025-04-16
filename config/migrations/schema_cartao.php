<?php
$pdo->exec("
    CREATE TABLE IF NOT EXISTS cartao (
        id INT AUTO_INCREMENT PRIMARY KEY,
        descricao VARCHAR(50) NOT NULL,
        bandeira VARCHAR(10),
        dia_vencimento INT,
        dia_fechamento INT,
        linha_credito DECIMAL(10,2),
        banco_id INT,
        ativo TINYINT DEFAULT 1,
        FOREIGN KEY (banco_id) REFERENCES bancos(id) ON DELETE CASCADE
    );
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS final_cartao (
        id INT AUTO_INCREMENT PRIMARY KEY,
        final VARCHAR(4) NOT NULL,
        cartao_id INT NOT NULL,
        virtual TINYINT DEFAULT 0,
        titular VARCHAR(100),
        ativo TINYINT DEFAULT 1,
        FOREIGN KEY (cartao_id) REFERENCES cartao(id) ON DELETE CASCADE
    );
");

echo "<p>Tabelas cartao e final_cartao garantidas.</p>";
