-- Criação do banco de dados
CREATE DATABASE aps
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'Portuguese_Brazil.1252'
    LC_CTYPE = 'Portuguese_Brazil.1252'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1;
	
-- Criação da tabela produtos
CREATE TABLE produtos
(
    nomeproduto character varying(250) COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT pk_tb_departamento PRIMARY KEY (nomeproduto)
)
-- Alteração para o SGBD administrar a tabela
ALTER TABLE produtos
    OWNER to postgres;

-- Criação da tabela de preços
CREATE TABLE public.preco_produtos
(
    nomeproduto character varying(250) COLLATE pg_catalog."default" NOT NULL,
    data_preco date NOT NULL,
    preco numeric,
    CONSTRAINT pk_preco_produtos PRIMARY KEY (nomeproduto, data_preco),
    CONSTRAINT fk_preco_produtos_produtos FOREIGN KEY (nomeproduto)
        REFERENCES public.produtos (nomeproduto) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

-- Alteração para o SGBD administrar a tabela
ALTER TABLE public.preco_produtos
    OWNER to postgres;

-- Função para criação de controle de preços de produtos
CREATE OR REPLACE FUNCTION public.inserirdadosvalor(
	descricao character varying,
	valor numeric)
    RETURNS void
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE PARALLEL UNSAFE
AS $BODY$
Declare 
	v_controle produtos%ROWTYPE;
	v_controle_preco preco_produtos%ROWTYPE;
BEGIN 
	SELECT * INTO v_controle
	FROM produtos
	WHERE nomeproduto = descricao;
	
	IF (v_controle.nomeproduto IS NULL) THEN	
		INSERT INTO produtos(nomeproduto) values (descricao); 
	END IF;
	
	SELECT * INTO v_controle_preco
	FROM preco_produtos
	WHERE nomeproduto = descricao AND
	data_preco = to_date(to_char(current_timestamp, 'DD/MM/YYYY'), 'DD/MM/YYYY');
	
	IF (v_controle_preco.preco IS NULL) THEN
		INSERT INTO preco_produtos(nomeproduto, data_preco, preco) VALUES (descricao , to_date(to_char(current_timestamp, 'DD/MM/YYYY'), 'DD/MM/YYYY'), valor);
	ELSE 
		IF (valor < v_controle_preco.preco) THEN
			UPDATE preco_produtos set preco = valor WHERE nomeproduto = descricao AND data_preco = to_date(to_char(current_timestamp, 'DD/MM/YYYY'), 'DD/MM/YYYY');
		END IF;
	END IF;

END;
$BODY$;

-- Alteração para o SGBD administrar a função
ALTER FUNCTION public.inserirdadosvalor(character varying, numeric)
    OWNER TO postgres;

