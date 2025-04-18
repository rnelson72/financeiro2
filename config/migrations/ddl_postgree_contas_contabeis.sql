CREATE TABLE contas_contabeis (
	id serial4 NOT NULL,
	conta varchar(10) NOT NULL,
	descricao varchar(50) NOT NULL,
	tipo varchar(10) NOT NULL,
	ativo bool NOT NULL,
	CONSTRAINT contas_contabeis_pkey PRIMARY KEY (id)
);