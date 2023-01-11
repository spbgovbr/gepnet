/**
 * PACOTE#007
 */
DROP TABLE agepnet200.tb_secao;

CREATE TABLE agepnet200.tb_secao
(
  id_secao bigint NOT NULL, -- Identificador das seções do questionario.
  ds_secao character varying(200), -- Descrição das seções
  id_secao_pai bigint, -- Identificador da seção pai da seção criada.
  ativa boolean NOT NULL DEFAULT true, -- Define se a seção esta ativa ou não para apresentação no questionário....
  tp_questionario character(1) NOT NULL, -- Define o tipo de questionario ao qual pertence a seção criada....
  macroprocesso boolean NOT NULL DEFAULT false, -- Define se a seção é um macroprocesso com os seguintes valores:...
  CONSTRAINT pk_secao PRIMARY KEY (id_secao),
  CONSTRAINT fk_secao_secaopai FOREIGN KEY (id_secao_pai)
      REFERENCES agepnet200.tb_secao (id_secao) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_secao
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_secao
  IS 'Tabela que define as seções que o questionario deverá conter.';
COMMENT ON COLUMN agepnet200.tb_secao.id_secao IS 'Identificador das seções do questionario.';
COMMENT ON COLUMN agepnet200.tb_secao.ds_secao IS 'Descrição das seções ';
COMMENT ON COLUMN agepnet200.tb_secao.id_secao_pai IS 'Identificador da seção pai da seção criada.';
COMMENT ON COLUMN agepnet200.tb_secao.ativa IS 'Define se a seção esta ativa ou não para apresentação no questionário.
true - ativa
false - inativa.';
COMMENT ON COLUMN agepnet200.tb_secao.tp_questionario IS 'Define o tipo de questionario ao qual pertence a seção criada.
S - Questionario pesquisa de satisfação de servidores
C - Questionario pesquisa de satisfação de cidadãos.';
COMMENT ON COLUMN agepnet200.tb_secao.macroprocesso IS 'Define se a seção é um macroprocesso com os seguintes valores:
1 - Sim
2 - Não';

/*
CARGA DA TABELA
 */

INSERT INTO agepnet200.tb_secao (id_secao, ds_secao, id_secao_pai, ativa, tp_questionario)
VALUES (1, 'Cargo do Servidor', 1, TRUE, 'S');
INSERT INTO agepnet200.tb_secao (id_secao, ds_secao, id_secao_pai, ativa, tp_questionario, macroprocesso)
VALUES (2, 'Principal área de atuação (Macroprocesso)', 2, TRUE, 'S', TRUE);
INSERT INTO agepnet200.tb_secao (id_secao, ds_secao, id_secao_pai, ativa, tp_questionario)
VALUES (3, 'Processos Internos (atividades e rotinas de trabalho)', 3, TRUE, 'S');
INSERT INTO agepnet200.tb_secao (id_secao, ds_secao, id_secao_pai, ativa, tp_questionario)
VALUES (4, 'Comunicação Interna', 4, TRUE, 'S');
INSERT INTO agepnet200.tb_secao (id_secao, ds_secao, id_secao_pai, ativa, tp_questionario)
VALUES (5, 'Recursos e Infraestrutura', 5, TRUE, 'S');
INSERT INTO agepnet200.tb_secao (id_secao, ds_secao, id_secao_pai, ativa, tp_questionario)
VALUES (6, 'Gestão Organizacional', 6, TRUE, 'S');
INSERT INTO agepnet200.tb_secao (id_secao, ds_secao, id_secao_pai, ativa, tp_questionario)
VALUES (7, 'Satisfação Pessoal', 7, TRUE, 'S');
INSERT INTO agepnet200.tb_secao (id_secao, ds_secao, id_secao_pai, ativa, tp_questionario)
VALUES (8, 'Local de Atendimento', 8, TRUE, 'C');
INSERT INTO agepnet200.tb_secao (id_secao, ds_secao, id_secao_pai, ativa, tp_questionario)
VALUES (9, 'Serviço Utilizado', 9, TRUE, 'C');
INSERT INTO agepnet200.tb_secao (id_secao, ds_secao, id_secao_pai, ativa, tp_questionario)
VALUES (10, 'Avaliação', 10, TRUE, 'C');
INSERT INTO agepnet200.tb_secao (id_secao, ds_secao, id_secao_pai, ativa, tp_questionario)
VALUES (11, 'Informações Estatísticas (opcionais)', 11, TRUE, 'C');