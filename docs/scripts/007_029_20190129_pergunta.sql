/**
 * PACOTE#007
 */
DROP TABLE agepnet200.tb_pergunta;

CREATE TABLE agepnet200.tb_pergunta
(
  idpergunta bigint NOT NULL, -- Identificador do registro de perguntas.
  dspergunta character varying(300), -- Descrição da pergunta.
  tipopergunta numeric(1,0) NOT NULL, -- Tipo de pergunta com as seguintes opções:...
  ativa boolean NOT NULL DEFAULT false, -- Pergunta obrigatoria:...
  idquestionario bigint NOT NULL, -- Identificador do questionario criado.
  posicao integer, -- Posição que a pergunta será apresentada no questionário.
  id_secao bigint NOT NULL, -- Define a qual seção ou subseção pertence a pergunta.
  tiporegistro numeric(1,0), -- Tipo de registro da resposta em banco de dados:...
  dstitulo character varying(200), -- Título da pergunta
  CONSTRAINT pk_pergunta PRIMARY KEY (idpergunta),
  CONSTRAINT fk_pergunta_secao FOREIGN KEY (id_secao)
      REFERENCES agepnet200.tb_secao (id_secao) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT,
  CONSTRAINT fk_questionario_pergunta FOREIGN KEY (idquestionario)
      REFERENCES agepnet200.tb_questionario_diagnostico (idquestionariodiagnostico) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_pergunta
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_pergunta
  IS 'Tabela de perguntas para o questionario criado.';
COMMENT ON COLUMN agepnet200.tb_pergunta.idpergunta IS 'Identificador do registro de perguntas.';
COMMENT ON COLUMN agepnet200.tb_pergunta.dspergunta IS 'Descrição da pergunta.';
COMMENT ON COLUMN agepnet200.tb_pergunta.tipopergunta IS 'Tipo de pergunta com as seguintes opções:
1 - Descritiva
2 - Multipla escolha
3 - Única escolha';
COMMENT ON COLUMN agepnet200.tb_pergunta.ativa IS 'Pergunta obrigatoria:
true = Sim
false = Não.';
COMMENT ON COLUMN agepnet200.tb_pergunta.idquestionario IS 'Identificador do questionario criado.';
COMMENT ON COLUMN agepnet200.tb_pergunta.posicao IS 'Posição que a pergunta será apresentada no questionário.';
COMMENT ON COLUMN agepnet200.tb_pergunta.id_secao IS 'Define a qual seção ou subseção pertence a pergunta.';
COMMENT ON COLUMN agepnet200.tb_pergunta.tiporegistro IS 'Tipo de registro da resposta em banco de dados:
1 - Numério
2 - Textual';
COMMENT ON COLUMN agepnet200.tb_pergunta.dstitulo IS 'Título da pergunta';


