/**
 * PACOTE#007
 */
DROP TABLE agepnet200.tb_resposta_pergunta;

CREATE TABLE agepnet200.tb_resposta_pergunta
(
  id_resposta_pergunta bigint NOT NULL, -- Identificador da tabela
  ds_resposta_descritiva text, -- Resposta de perguntas descritivas
  idpergunta bigint NOT NULL, -- Identifica a pergunta que pertence a resposta.
  idresposta bigint, -- identificador da opção de resposta pré definida.
  nrquestionario bigint NOT NULL, -- Coluna que define o numero do questionario que esta sendo respondido.
  idquestionario bigint NOT NULL, -- Coluna que identifica o questionario que esta sendo respondido.
  iddiagnostico bigint NOT NULL, -- Coluna que identifica o diagnostico.
  CONSTRAINT pk_resposta_pergunta PRIMARY KEY (id_resposta_pergunta),
  CONSTRAINT fk_opcaoresposta_resposta_pergunta FOREIGN KEY (idresposta)
      REFERENCES agepnet200.tb_opcao_resposta (idresposta) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT fk_pergunta_resposta_pergunta FOREIGN KEY (idpergunta)
      REFERENCES agepnet200.tb_pergunta (idpergunta) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT fk_questionariorespondido_respostapergunta FOREIGN KEY (idquestionario, nrquestionario, iddiagnostico)
      REFERENCES agepnet200.tb_questionariodiagnostico_respondido (idquestionario, numero, iddiagnostico) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_resposta_pergunta
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_resposta_pergunta
  IS 'Tabela que armazena as respostas das perguntas dos questionarios.';
COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.id_resposta_pergunta IS 'Identificador da tabela';
COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.ds_resposta_descritiva IS 'Resposta de perguntas descritivas';
COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.idpergunta IS 'Identifica a pergunta que pertence a resposta.';
COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.idresposta IS 'identificador da opção de resposta pré definida.';
COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.nrquestionario IS 'Coluna que define o numero do questionario que esta sendo respondido.';
COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.idquestionario IS 'Coluna que identifica o questionario que esta sendo respondido.';
COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.iddiagnostico IS 'Coluna que identifica o diagnostico.';

