<?php
require_once __DIR__ . '/../config/database.php';

$pdo->exec("DROP TABLE IF EXISTS compras");
$pdo->exec("DROP TABLE IF EXISTS fatura");
$pdo->exec("DROP TABLE IF EXISTS movimentacao");

$pdo->exec("CREATE TABLE movimentacao (
    id INT PRIMARY KEY,
    data DATE NOT NULL,
    descricao VARCHAR(255),
    valor DECIMAL(12,2),
    categoria_id INT,
    conta_id INT,
    codigo_pagamento INT,
    fatura_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categoria(id),
    FOREIGN KEY (conta_id) REFERENCES conta(id),
    FOREIGN KEY (fatura_id) REFERENCES fatura(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

$pdo->exec("CREATE TABLE fatura (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cartao_id INT NOT NULL,
    data_fechamento DATE NOT NULL,
    data_vencimento DATE NOT NULL,
    valor_total DECIMAL(12,2),
    valor_pago DECIMAL(12,2),
    status VARCHAR(10) DEFAULT 'aberta',
    movimentacao_id INT,
    FOREIGN KEY (cartao_id) REFERENCES cartao(id),
    FOREIGN KEY (movimentacao_id) REFERENCES movimentacao(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

$pdo->exec("CREATE TABLE compras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cartao_id INT NOT NULL,
    final_cartao_id INT,
    data DATE NOT NULL,
    descricao VARCHAR(255),
    valor DECIMAL(12,2) NOT NULL,
    parcelas INT DEFAULT 1,
    parcela_atual INT DEFAULT 1,
    categoria_id INT,
    fatura_id INT,
    FOREIGN KEY (cartao_id) REFERENCES cartao(id),
    FOREIGN KEY (final_cartao_id) REFERENCES final_cartao(id),
    FOREIGN KEY (fatura_id) REFERENCES fatura(id),
    FOREIGN KEY (categoria_id) REFERENCES categoria(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

echo "<p>Tabelas <strong>movimentacao</strong>, <strong>fatura</strong> e <strong>compras</strong> garantidas.</p>";
