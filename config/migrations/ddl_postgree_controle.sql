CREATE TABLE controle (
	id serial4 NOT NULL,
	descricao varchar(100) NOT NULL,
	ativo bool DEFAULT true NULL,
	grupo_id int4 NULL,
	CONSTRAINT controle_pkey PRIMARY KEY (id)
);

CREATE TABLE grupo_controle (
	id serial4 NOT NULL,
	descricao varchar(255) NOT NULL,
	ativo bool DEFAULT true NULL,
	CONSTRAINT grupo_controle_pkey PRIMARY KEY (id)
);

CREATE TABLE lancamentos (
	id serial4 NOT NULL,
	controle_id int4 NULL,
	"data" date NOT NULL,
	descricao varchar(100) NOT NULL,
	valor numeric(12, 2) NOT NULL,
	CONSTRAINT lancamentos_pkey PRIMARY KEY (id)
);
