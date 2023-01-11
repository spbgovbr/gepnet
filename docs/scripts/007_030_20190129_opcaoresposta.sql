/**
 * PACOTE#007
 */
DROP TABLE agepnet200.tb_opcao_resposta;

CREATE TABLE agepnet200.tb_opcao_resposta
(
  idresposta bigint NOT NULL,
  idpergunta bigint NOT NULL,
  desresposta character varying(300),
  escala integer, -- Define um valor para resposta. Esse campo será necessário para a contagem na escala de Likert.
  ordenacao integer, -- Define a ordenão das respostas
  idquestionario bigint NOT NULL, -- Coluna identificadora do questionario.
  CONSTRAINT pk_opcao_resposta PRIMARY KEY (idresposta),
  CONSTRAINT fk_pergunta FOREIGN KEY (idpergunta)
      REFERENCES agepnet200.tb_pergunta (idpergunta) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE CASCADE,
  CONSTRAINT fk_questionario_opcaoresposta FOREIGN KEY (idquestionario)
      REFERENCES agepnet200.tb_questionario_diagnostico (idquestionariodiagnostico) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_opcao_resposta
  OWNER TO postgres;
GRANT ALL ON TABLE agepnet200.tb_opcao_resposta TO postgres;
COMMENT ON COLUMN agepnet200.tb_opcao_resposta.escala IS 'Define um valor para resposta. Esse campo será necessário para a contagem na escala de Likert.';
COMMENT ON COLUMN agepnet200.tb_opcao_resposta.ordenacao IS 'Define a ordenão das respostas';
COMMENT ON COLUMN agepnet200.tb_opcao_resposta.idquestionario IS 'Coluna identificadora do questionario.';

