/**
 * PACOTE#007
 */

-- DROP TABLE agepnet200.tb_questionariodiagnostico_respondido;

CREATE TABLE agepnet200.tb_questionariodiagnostico_respondido
(
  idquestionario bigint NOT NULL, -- Coluna identificadora do questionario vinculado ao diagnostico.
  iddiagnostico bigint NOT NULL, -- Coluna identificadora do diagnostico vinculado ao questionario.
  numero bigint NOT NULL, -- Coluna que identifica o numéro do questionario respondido.
  dt_resposta date NOT NULL, -- Coluna que define a data e hora que foi respondido o questionario;
  idpessoaresposta integer NOT NULL, -- Coluna que identifica a pessoa que cadastrou o questionario.
  CONSTRAINT pk_historico_questionario PRIMARY KEY (idquestionario, iddiagnostico, numero),
  CONSTRAINT fk_questionariovinculado_questionariorespondido FOREIGN KEY (idquestionario, iddiagnostico)
      REFERENCES agepnet200.tb_vincula_questionario (idquestionario, iddiagnostico) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_questionariodiagnostico_respondido
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_questionariodiagnostico_respondido
  IS 'Tabela de historico de questionário respondido.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnostico_respondido.idquestionario IS 'Coluna identificadora do questionario vinculado ao diagnostico.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnostico_respondido.iddiagnostico IS 'Coluna identificadora do diagnostico vinculado ao questionario.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnostico_respondido.numero IS 'Coluna que identifica o numéro do questionario respondido.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnostico_respondido.dt_resposta IS 'Coluna que define a data e hora que foi respondido o questionario;';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnostico_respondido.idpessoaresposta IS 'Coluna que identifica a pessoa que cadastrou o questionario.';

