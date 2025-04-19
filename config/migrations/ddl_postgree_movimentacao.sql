CREATE TABLE movimentacao (
    id serial4 NOT NULL,
    data DATE NOT NULL,
    descricao VARCHAR(255),
    valor DECIMAL(12,2),
    categoria_id INTEGER REFERENCES categoria(id),
    conta_id INTEGER REFERENCES conta(id),
    fatura_id INTEGER REFERENCES fatura(id),
    data_compra DATE,
    codigo_pagamento INT NOT NULL
);
