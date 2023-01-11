/**
 * PACOTE#007
 */
DROP TABLE agepnet200.tb_vincula_questionario;

CREATE TABLE agepnet200.tb_vincula_questionario
(
  idquestionario bigint NOT NULL, -- identificador do questionario.
  iddiagnostico bigint NOT NULL, -- Identificador do diagnostico
  disponivel character(1) NOT NULL DEFAULT 2, -- Identifica se o questionario esta liberado para ser respondido ou não....
  dtdisponibilidade date NOT NULL, -- Data que foi disponibilizado o questionario para respostas.
  dtencerrramento date, -- Data de encerramento da disponibilidade do questionario.
  idpesdisponibiliza integer NOT NULL, -- Pessoa que disponibilizou o questionario.
  idpesencerrou integer, -- Pessoa que encerrou o questionario.
  CONSTRAINT pk_vincula_questionario PRIMARY KEY (idquestionario, iddiagnostico),
  CONSTRAINT fk_diagnostico_vinculaquestionario FOREIGN KEY (iddiagnostico)
      REFERENCES agepnet200.tb_diagnostico (iddiagnostico) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT fk_pessoa_vinculaquestionario FOREIGN KEY (idpesdisponibiliza)
      REFERENCES agepnet200.tb_pessoa (idpessoa) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT,
  CONSTRAINT fk_pessoaencerra_vinculaquestionario FOREIGN KEY (idpesencerrou)
      REFERENCES agepnet200.tb_pessoa (idpessoa) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT,
  CONSTRAINT fk_questionario_vinculaquestionario FOREIGN KEY (idquestionario)
      REFERENCES agepnet200.tb_questionario_diagnostico (idquestionariodiagnostico) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_vincula_questionario
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_vincula_questionario
  IS 'Tabela de questionarios vinculados a diagnosticos.';
COMMENT ON COLUMN agepnet200.tb_vincula_questionario.idquestionario IS 'identificador do questionario.';
COMMENT ON COLUMN agepnet200.tb_vincula_questionario.iddiagnostico IS 'Identificador do diagnostico';
COMMENT ON COLUMN agepnet200.tb_vincula_questionario.disponivel IS 'Identifica se o questionario esta liberado para ser respondido ou não.
1 - Disponível
2 - Indisponível';
COMMENT ON COLUMN agepnet200.tb_vincula_questionario.dtdisponibilidade IS 'Data que foi disponibilizado o questionario para respostas.';
COMMENT ON COLUMN agepnet200.tb_vincula_questionario.dtencerrramento IS 'Data de encerramento da disponibilidade do questionario.';
COMMENT ON COLUMN agepnet200.tb_vincula_questionario.idpesdisponibiliza IS 'Pessoa que disponibilizou o questionario.';
COMMENT ON COLUMN agepnet200.tb_vincula_questionario.idpesencerrou IS 'Pessoa que encerrou o questionario.';

