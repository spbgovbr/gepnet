/**
 * PACOTE#007
 */
DROP TABLE IF EXISTS agepnet200.tb_vincula_questionario CASCADE;

CREATE TABLE agepnet200.tb_questionario_diagnostico
(
  idquestionariodiagnostico bigint NOT NULL DEFAULT nextval('agepnet200.sq_questionariodiagnostico'::regclass), -- Coluna de indentificação de registros do questionario.
  nomquestionario character varying(400) NOT NULL, -- Descrição do nome do questionario.
  tipo character(1) NOT NULL DEFAULT 1, -- Coluna que define o tipo do questionário com as seguintes opções:...
  observacao text, -- Coluna de observações do questionário.
  idpescadastrador integer NOT NULL, -- Pessoa que cadastrou ou questionario.
  dtcadastro date NOT NULL, -- Data do cadastramento do questionario.
  CONSTRAINT pk_questionario_diagnostico PRIMARY KEY (idquestionariodiagnostico),
  CONSTRAINT fk_pessoa_questionariodiagnostico FOREIGN KEY (idpescadastrador)
      REFERENCES agepnet200.tb_pessoa (idpessoa) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_questionario_diagnostico
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_questionario_diagnostico
  IS 'Tabela de questionarios para os diagnosticos';
COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.idquestionariodiagnostico IS 'Coluna de indentificação de registros do questionario.';
COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.nomquestionario IS 'Descrição do nome do questionario.';
COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.tipo IS 'Coluna que define o tipo do questionário com as seguintes opções:
1 - Servidor
2 - Cidadão';
COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.observacao IS 'Coluna de observações do questionário.';
COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.idpescadastrador IS 'Pessoa que cadastrou ou questionario.';
COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.dtcadastro IS 'Data do cadastramento do questionario.';


-- Index: agepnet200.fki_pessoa_questionariodiagnostico

-- DROP INDEX agepnet200.fki_pessoa_questionariodiagnostico;

CREATE INDEX fki_pessoa_questionariodiagnostico
  ON agepnet200.tb_questionario_diagnostico
  USING btree
  (idpescadastrador);

