/**
 * PACOTE#004
 */

CREATE TABLE agepnet200.tb_linhatempo
(
  id integer NOT NULL, -- Coluna identificadora de registro
  idpessoa integer NOT NULL, -- Coluna que identifica pessoa que realizou a ação.
  dsfuncaoprojeto character varying(300) NOT NULL, -- Coluna que descreve a função que a pessoa desempenha no projeto.
  tpacao character(1) NOT NULL, -- Coluna que define o tipo de ação executada na funcionalidade:...
  dtacao timestamp with time zone NOT NULL, -- Coluna que descreve a data e hora que a ação foi executada.
  idprojeto integer NOT NULL, -- Coluna que define o projeto que sofreu a ação.
  idrecurso integer NOT NULL, -- Coluna que identifica o registro dos controles  de modulos.
  CONSTRAINT pk_linhatempo PRIMARY KEY (id),
  CONSTRAINT fk_linhatempo_pessoa FOREIGN KEY (idpessoa)
  REFERENCES agepnet200.tb_pessoa (idpessoa) MATCH SIMPLE
  ON UPDATE NO ACTION ON DELETE RESTRICT,
  CONSTRAINT fk_linhatempo_projeto FOREIGN KEY (idprojeto)
  REFERENCES agepnet200.tb_projeto (idprojeto) MATCH SIMPLE
  ON UPDATE NO ACTION ON DELETE RESTRICT,
  CONSTRAINT fk_linhatempo_recurso FOREIGN KEY (idrecurso)
  REFERENCES agepnet200.tb_recurso (idrecurso) MATCH SIMPLE
  ON UPDATE NO ACTION ON DELETE NO ACTION
)
WITH (
OIDS=FALSE
);
ALTER TABLE agepnet200.tb_linhatempo
OWNER TO postgres;
GRANT ALL ON TABLE agepnet200.tb_linhatempo TO postgres;
COMMENT ON COLUMN agepnet200.tb_linhatempo.id IS 'Coluna identificadora de registro';
COMMENT ON COLUMN agepnet200.tb_linhatempo.idpessoa IS 'Coluna que identifica pessoa que realizou a ação.';
COMMENT ON COLUMN agepnet200.tb_linhatempo.dsfuncaoprojeto IS 'Coluna que descreve a função que a pessoa desempenha no projeto.';
COMMENT ON COLUMN agepnet200.tb_linhatempo.tpacao IS 'Coluna que define o tipo de ação executada na funcionalidade:
N - Novo
A - Alteração
E - Exclusão';
COMMENT ON COLUMN agepnet200.tb_linhatempo.dtacao IS 'Coluna que descreve a data e hora que a ação foi executada.';
COMMENT ON COLUMN agepnet200.tb_linhatempo.idprojeto IS 'Coluna que define o projeto que sofreu a ação.';
COMMENT ON COLUMN agepnet200.tb_linhatempo.idrecurso IS 'Coluna que identifica o registro dos controles  de modulos.';

