-- Estrutura original da tabela cartoes_credito no PostgreSQL

CREATE TABLE cartoes_credito ( 
	id serial4 NOT NULL,
	descricao varchar(20) NOT NULL,
	bandeira varchar(10) NOT NULL,
	dia_vencimento int4 NOT NULL,
	dia_fechamento int4 NOT NULL,
	linha_credito float4 NOT NULL,
	finais varchar(100) NOT NULL,
	virtuais varchar(100) NOT NULL,
	ativo bool NOT NULL,
	CONSTRAINT cartoes_credito_pkey PRIMARY KEY (id)
);
