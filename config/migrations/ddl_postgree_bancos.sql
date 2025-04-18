CREATE TABLE bancos (
	id serial4 NOT NULL,
	descricao varchar(20) NOT NULL,
	numero varchar(10) NOT NULL,
	agencia varchar(10) NOT NULL,
	conta varchar(20) NOT NULL,
	ativo bool NOT NULL,
	CONSTRAINT bancos_pkey PRIMARY KEY (id)
);