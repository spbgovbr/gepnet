/**
 * PACOTE#007
 */
DROP TABLE agepnet200.tb_resposta_questionariordiagnostico;

CREATE TABLE agepnet200.tb_resposta_questionariordiagnostico
(
  id_resposta_pergunta bigint NOT NULL, -- Coluna que identifica a resposta cadastrada para pergunta do questionario.
  idquestionario bigint NOT NULL, -- Coluna que identifica o questionario que a resposta faz parte.
  iddiagnostico bigint NOT NULL, -- Coluna que identifica o diagnostico que o questionario faz parte.
  numero bigint NOT NULL, -- Coluna que identifica o número do questionario respondido.
  CONSTRAINT pk_resposta_questionariorespondido PRIMARY KEY (id_resposta_pergunta, idquestionario, iddiagnostico, numero),
  CONSTRAINT fk_questionariorespondido_respostaquestionariorespondido FOREIGN KEY (idquestionario, iddiagnostico, numero)
      REFERENCES agepnet200.tb_questionariodiagnostico_respondido (idquestionario, iddiagnostico, numero) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT fk_respostapergunta_respostaquestionariorespondido FOREIGN KEY (id_resposta_pergunta)
      REFERENCES agepnet200.tb_resposta_pergunta (id_resposta_pergunta) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_resposta_questionariordiagnostico
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_resposta_questionariordiagnostico
  IS 'Tabela que registro as respostas dos questionarios.';
COMMENT ON COLUMN agepnet200.tb_resposta_questionariordiagnostico.id_resposta_pergunta IS 'Coluna que identifica a resposta cadastrada para pergunta do questionario.';
COMMENT ON COLUMN agepnet200.tb_resposta_questionariordiagnostico.idquestionario IS 'Coluna que identifica o questionario que a resposta faz parte.';
COMMENT ON COLUMN agepnet200.tb_resposta_questionariordiagnostico.iddiagnostico IS 'Coluna que identifica o diagnostico que o questionario faz parte.';
COMMENT ON COLUMN agepnet200.tb_resposta_questionariordiagnostico.numero IS 'Coluna que identifica o número do questionario respondido.';

