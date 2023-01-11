/**
 * PACOTE#007
 */
DROP TABLE agepnet200.tb_item_secao;

CREATE TABLE agepnet200.tb_item_secao
(
  id_item integer NOT NULL, -- Identificador de itens da seção.
  ds_item character varying(200) NOT NULL, -- Descrição do item da seção.
  id_secao integer NOT NULL, -- Identificador da seção ao qual o item pertence.
  ativo boolean NOT NULL DEFAULT true, -- Define se o item esta ativo para apresentação....
  idquestionariodiagnostico integer,
  CONSTRAINT pk_item PRIMARY KEY (id_item),
  CONSTRAINT fk_idquestionariodiagnostico FOREIGN KEY (idquestionariodiagnostico)
      REFERENCES agepnet200.tb_questionario_diagnostico (idquestionariodiagnostico) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT fk_item_secao FOREIGN KEY (id_secao)
      REFERENCES agepnet200.tb_secao (id_secao) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_item_secao
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_item_secao
  IS 'Tabela que define os itens da seção.';
COMMENT ON COLUMN agepnet200.tb_item_secao.id_item IS 'Identificador de itens da seção.';
COMMENT ON COLUMN agepnet200.tb_item_secao.ds_item IS 'Descrição do item da seção.';
COMMENT ON COLUMN agepnet200.tb_item_secao.id_secao IS 'Identificador da seção ao qual o item pertence.';
COMMENT ON COLUMN agepnet200.tb_item_secao.ativo IS 'Define se o item esta ativo para apresentação.
true - ativo
false - inativo.';


