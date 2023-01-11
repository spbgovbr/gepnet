DROP TABLE agepnet200.tb_assinadocumento;
CREATE TABLE agepnet200.tb_assinadocumento
(
  id integer NOT NULL, -- Coluna de identificação do registro.
  idprojeto integer NOT NULL, -- Coluna que identifica o projeto que o documento faz parte.
  idpessoa integer NOT NULL, -- Coluna que identifica a parte interessada do projeto que assinou o documento.
  assinado timestamp with time zone NOT NULL, -- Data e hora que foi assinado o documento pela parte interessada.
  tipodoc integer NOT NULL, -- Coluna que define o tipo de documento assinado pela parte interessada. Valores possíveis:...
  hashdoc character(100) NOT NULL, -- Coluna que define o hash de autenticação do documento.
  situacao character varying(1) NOT NULL, -- Coluna que defina a situação da assinatura. Valores possíveis:...
  nomfuncao character varying(300), -- Coluna que define o papel que o usuário exercia no projeto.
  idaceite integer, -- Coluna que define o identificador do termo de aceite que esta sendo assinado.
  CONSTRAINT pk_assinadocumento PRIMARY KEY (id),
  CONSTRAINT fk_assinadocumento_pessoa FOREIGN KEY (idpessoa)
      REFERENCES agepnet200.tb_pessoa (idpessoa) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT fk_assinadocumento_projeto FOREIGN KEY (idprojeto)
      REFERENCES agepnet200.tb_projeto (idprojeto) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT fk_assinadocumento_termoaceite FOREIGN KEY (id)
      REFERENCES agepnet200.tb_aceite (idaceite) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_assinadocumento
  OWNER TO postgres;
GRANT ALL ON TABLE agepnet200.tb_assinadocumento TO postgres;
COMMENT ON TABLE agepnet200.tb_assinadocumento
  IS 'Tabela que registra para as partes interessada do projeto os documentos assinados.';
COMMENT ON COLUMN agepnet200.tb_assinadocumento.id IS 'Coluna de identificação do registro.';
COMMENT ON COLUMN agepnet200.tb_assinadocumento.idprojeto IS 'Coluna que identifica o projeto que o documento faz parte.';
COMMENT ON COLUMN agepnet200.tb_assinadocumento.idpessoa IS 'Coluna que identifica a parte interessada do projeto que assinou o documento.';
COMMENT ON COLUMN agepnet200.tb_assinadocumento.assinado IS 'Data e hora que foi assinado o documento pela parte interessada.';
COMMENT ON COLUMN agepnet200.tb_assinadocumento.tipodoc IS 'Coluna que define o tipo de documento assinado pela parte interessada. Valores possíveis:
1 - TAP - Termo de abertura
2 - PGP - Plano Geral de Projeto
3 - TA - Termo de aceite.
4 - TEP - Termo de encerramento de projeto';
COMMENT ON COLUMN agepnet200.tb_assinadocumento.hashdoc IS 'Coluna que define o hash de autenticação do documento.';
COMMENT ON COLUMN agepnet200.tb_assinadocumento.situacao IS 'Coluna que defina a situação da assinatura. Valores possíveis:
I - Inativo
A - Ativo';
COMMENT ON COLUMN agepnet200.tb_assinadocumento.nomfuncao IS 'Coluna que define o papel que o usuário exercia no projeto.';
COMMENT ON COLUMN agepnet200.tb_assinadocumento.idaceite IS 'Coluna que define o identificador do termo de aceite que esta sendo assinado.';



GRANT SELECT, INSERT, UPDATE, DELETE  ON TABLE agepnet200.tb_assinadocumento to postgres;