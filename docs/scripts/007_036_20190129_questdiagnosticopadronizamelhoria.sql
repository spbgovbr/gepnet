-- Table: agepnet200.tb_questdiagnosticopadronizamelhoria

-- DROP TABLE agepnet200.tb_questdiagnosticopadronizamelhoria;

CREATE TABLE agepnet200.tb_questdiagnosticopadronizamelhoria
(
  idpadronizacaomelhoria bigint NOT NULL, -- Sequencial gerado automaticamente para padronização da sugestão de melhoria.
  idmelhoria bigint, -- Número da sugestão de melhoria padronizada.
  desrevisada text NOT NULL, -- Revisão da descrição de melhoria já cadastrada na tabela de melhoria do diagnóstico.
  idprazo integer NOT NULL, -- Prazo da padronização da melhoria: 1-Baixo/2-Médio/3-Alto/4-Até 6 meses.
  idimpacto integer NOT NULL, -- Impacto da padronização da melhoria: 1-Baixo/2-Médio/3-Alto.
  idesforco integer NOT NULL, -- Esforço da padronização da melhoria: 4-Alto/3-Médio/2-Baixo/1-Irrelevante.
  numpontuacao numeric(5,2), -- Pontuação da padronização da melhoria: (Valor da seleção do prazo* Valor da seleção do impacto* Valor da seleção do esforço) /48).
  numincidencia numeric(5,2), -- Incidência da padronização da melhoria que apresenta a quantidade de melhorias que possuem a mesma agrupadora. Só será apresentado caso seja selecionado melhoria agrupadora.
  numvotacao integer, -- Votação padronização melhoria: campo numérico e editável.
  flaagrupadora boolean, -- Agrupadora da padronização da melhoria: Sim/Não.
  destitulogrupo text, -- Título do Grupo (Só será apresentado caso selecionado “Sim” no campo Agrupadora, campo obrigatório se apresentado).
  desinformacoescomplementares text, -- Informações complementares para a padronização da melhoria.
  desmelhoriaagrupadora bigint, -- Melhoria Agrupadora (Só será apresentada se a situação da melhoria for “Agrupadora”, campo obrigatório se apresentado. Apresenta todos os títulos de grupo cadastrados em outras melhorias).
  CONSTRAINT pk_questdiagnosticopadronizamelhoria PRIMARY KEY (idpadronizacaomelhoria), -- Chave primária da tabela sequencial.
  CONSTRAINT fk_questionariodiagnosticomelhoria_questdiagnosticopadronizamel FOREIGN KEY (idmelhoria)
      REFERENCES agepnet200.tb_questionariodiagnosticomelhoria (idmelhoria) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_questdiagnosticopadronizamelhoria
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_questdiagnosticopadronizamelhoria
  IS 'Padronizações realizadas para as melhorias dos diagnósticos.';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.idpadronizacaomelhoria IS 'Sequencial gerado automaticamente para padronização da sugestão de melhoria.';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.idmelhoria IS 'Número da sugestão de melhoria padronizada.';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.desrevisada IS 'Revisão da descrição de melhoria já cadastrada na tabela de melhoria do diagnóstico.';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.idprazo IS 'Prazo da padronização da melhoria: 1-Baixo/2-Médio/3-Alto/4-Até 6 meses.';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.idimpacto IS 'Impacto da padronização da melhoria: 1-Baixo/2-Médio/3-Alto.';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.idesforco IS 'Esforço da padronização da melhoria: 4-Alto/3-Médio/2-Baixo/1-Irrelevante.';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.numpontuacao IS 'Pontuação da padronização da melhoria: (Valor da seleção do prazo* Valor da seleção do impacto* Valor da seleção do esforço) /48).';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.numincidencia IS 'Incidência da padronização da melhoria que apresenta a quantidade de melhorias que possuem a mesma agrupadora. Só será apresentado caso seja selecionado melhoria agrupadora.';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.numvotacao IS 'Votação padronização melhoria: campo numérico e editável.';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.flaagrupadora IS 'Agrupadora da padronização da melhoria: Sim/Não.';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.destitulogrupo IS 'Título do Grupo (Só será apresentado caso selecionado “Sim” no campo Agrupadora, campo obrigatório se apresentado).';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.desinformacoescomplementares IS 'Informações complementares para a padronização da melhoria.';
COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.desmelhoriaagrupadora IS 'Melhoria Agrupadora (Só será apresentada se a situação da melhoria for “Agrupadora”, campo obrigatório se apresentado. Apresenta todos os títulos de grupo cadastrados em outras melhorias).';

COMMENT ON CONSTRAINT pk_questdiagnosticopadronizamelhoria ON agepnet200.tb_questdiagnosticopadronizamelhoria IS 'Chave primária da tabela sequencial.';


-- Index: agepnet200.fki_questionariodiagnosticomelhoria_questdiagnosticopadronizame

-- DROP INDEX agepnet200.fki_questionariodiagnosticomelhoria_questdiagnosticopadronizame;

CREATE INDEX fki_questionariodiagnosticomelhoria_questdiagnosticopadronizame
  ON agepnet200.tb_questdiagnosticopadronizamelhoria
  USING btree
  (idmelhoria);