-- Table: agepnet200.tb_questionariodiagnosticomelhoria

-- DROP TABLE agepnet200.tb_questionariodiagnosticomelhoria;

CREATE TABLE agepnet200.tb_questionariodiagnosticomelhoria
(
  idmelhoria bigint NOT NULL, -- Sequencial gerado automaticamente para a sugestão de melhoria do diagnóstico.
  datmelhoria date NOT NULL, -- Data da melhoria do diagnóstico.
  desmelhoria text NOT NULL, -- Descrição da melhoria do diagnóstico.
  idmacroprocessotrabalho integer NOT NULL, -- Macroprocesso de trabalho para a melhoria do diagnóstico (recupera do banco de dados).
  idmacroprocessomelhorar integer NOT NULL, -- Macroprocesso a ser melhorado para a melhoria do diagnóstico (recupera do banco de dados).
  idunidaderesponsavelproposta integer NOT NULL, -- Unidade responsável pela proposta de melhria do diagnóstico (recupera todas as unidades vinculadas a unidade principal do diagnóstico).
  flaabrangencia "char" NOT NULL, -- Abrangência: Local(L)/Nacional(N).
  idunidaderesponsavelimplantacao integer NOT NULL, -- Se a abrangência for local apresenta Unidades vinculadas a unidade principaldo diagnóstico. Caso a abrangência seja Nacional apresenta todas as delegacias.
  idobjetivoinstitucional integer, -- Objetivos institucionais existentes no banco de dados.
  idacaoestrategica integer, -- Ações estratégicas vinculadas ao objetivo institucional anteriormente escolhido.
  idareamelhoria integer, -- Áreas de melhorias: Simplificação/Normatização/Gerenciamento/Automação/Capacitação/Interfaces/Estrutura/Inovação.
  idsituacao integer, -- Situações de melhorias: Registrada/Validada/Priorizada/Implantada/Suspensa/Agrupada.
  iddiagnostico bigint, -- Número do diagnóstico referente a sugestão de melhorias oriundo da tabela tb_diagnostico.
  idunidadeprincipal integer NOT NULL, -- Coluna identificadora da unidade principal do diagnostico.
  matriculaproponente integer, -- Matrícula do cadastrador da melhoria para o diagnóstico.
  CONSTRAINT pk_questionariodiagnosticomelhoria PRIMARY KEY (idmelhoria), -- Chave primária da tabela sequencial.
  CONSTRAINT fk_acaoestrategica_questionariodiagnosticomelhoria FOREIGN KEY (idacaoestrategica)
      REFERENCES agepnet200.tb_acao (idacao) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT,
  CONSTRAINT fk_diagnostico_questionariodiagnosticomelhoria FOREIGN KEY (iddiagnostico)
      REFERENCES agepnet200.tb_diagnostico (iddiagnostico) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE, -- Chave com a tabela de diagnóstico.
  CONSTRAINT fk_objetivoinstitucional_questionariodiagnosticomelhoria FOREIGN KEY (idobjetivoinstitucional)
      REFERENCES agepnet200.tb_objetivo (idobjetivo) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT,
  CONSTRAINT fk_processomelhorar_questionariodiagnosticomelhoria FOREIGN KEY (idmacroprocessomelhorar)
      REFERENCES agepnet200.tb_processo (idprocesso) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT,
  CONSTRAINT fk_processotrabalho_questionariodiagnosticomelhoria FOREIGN KEY (idmacroprocessotrabalho)
      REFERENCES agepnet200.tb_processo (idprocesso) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT,
  CONSTRAINT fk_undiaderesponsavelimplantacao_questionariodiagnosticomelhori FOREIGN KEY (idunidaderesponsavelimplantacao, idunidadeprincipal, iddiagnostico)
      REFERENCES agepnet200.tb_unidade_vinculada (idunidade, id_unidadeprincipal, iddiagnostico) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT,
  CONSTRAINT fk_unidaderesponsavelproposta_questionariodiagnosticomelhoria FOREIGN KEY (idunidaderesponsavelproposta, idunidadeprincipal, iddiagnostico)
      REFERENCES agepnet200.tb_unidade_vinculada (idunidade, id_unidadeprincipal, iddiagnostico) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_questionariodiagnosticomelhoria
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_questionariodiagnosticomelhoria
  IS 'Sugestões de melhorias para os diagnósticos.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idmelhoria IS 'Sequencial gerado automaticamente para a sugestão de melhoria do diagnóstico.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.datmelhoria IS 'Data da melhoria do diagnóstico.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.desmelhoria IS 'Descrição da melhoria do diagnóstico.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idmacroprocessotrabalho IS 'Macroprocesso de trabalho para a melhoria do diagnóstico (recupera do banco de dados).';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idmacroprocessomelhorar IS 'Macroprocesso a ser melhorado para a melhoria do diagnóstico (recupera do banco de dados).';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idunidaderesponsavelproposta IS 'Unidade responsável pela proposta de melhria do diagnóstico (recupera todas as unidades vinculadas a unidade principal do diagnóstico).';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.flaabrangencia IS 'Abrangência: Local(L)/Nacional(N).';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idunidaderesponsavelimplantacao IS 'Se a abrangência for local apresenta Unidades vinculadas a unidade principaldo diagnóstico. Caso a abrangência seja Nacional apresenta todas as delegacias.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idobjetivoinstitucional IS 'Objetivos institucionais existentes no banco de dados.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idacaoestrategica IS 'Ações estratégicas vinculadas ao objetivo institucional anteriormente escolhido.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idareamelhoria IS 'Áreas de melhorias: Simplificação/Normatização/Gerenciamento/Automação/Capacitação/Interfaces/Estrutura/Inovação.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idsituacao IS 'Situações de melhorias: Registrada/Validada/Priorizada/Implantada/Suspensa/Agrupada.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.iddiagnostico IS 'Número do diagnóstico referente a sugestão de melhorias oriundo da tabela tb_diagnostico.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idunidadeprincipal IS 'Coluna identificadora da unidade principal do diagnostico.';
COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.matriculaproponente IS 'Matrícula do cadastrador da melhoria para o diagnóstico.';

COMMENT ON CONSTRAINT pk_questionariodiagnosticomelhoria ON agepnet200.tb_questionariodiagnosticomelhoria IS 'Chave primária da tabela sequencial.';
COMMENT ON CONSTRAINT fk_diagnostico_questionariodiagnosticomelhoria ON agepnet200.tb_questionariodiagnosticomelhoria IS 'Chave com a tabela de diagnóstico.';


-- Index: agepnet200.fki_acaoestrategica

-- DROP INDEX agepnet200.fki_acaoestrategica;

CREATE INDEX fki_acaoestrategica
  ON agepnet200.tb_questionariodiagnosticomelhoria
  USING btree
  (idacaoestrategica);

-- Index: agepnet200.fki_diagnostico

-- DROP INDEX agepnet200.fki_diagnostico;

CREATE INDEX fki_diagnostico
  ON agepnet200.tb_questionariodiagnosticomelhoria
  USING btree
  (iddiagnostico);

CREATE SEQUENCE agepnet200.sq_melhoria
  INCREMENT 1
  MINVALUE 1
  MAXVALUE 999999999
  START 1
  CACHE 20;
ALTER TABLE agepnet200.sq_melhoria
  OWNER TO postgres;
