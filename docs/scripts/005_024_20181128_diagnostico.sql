/**
 * PACOTE#005
 */

-- Table: agepnet200.tb_permissaodiagnostico
-- DROP TABLE agepnet200.tb_permissaodiagnostico;
CREATE TABLE agepnet200.tb_permissaodiagnostico
(
  idpartediagnostico integer NOT NULL, -- Identificação das pessoas que fazem parte do diagnostico como parte interessada.
  iddiagnostico integer NOT NULL, -- Identificador do diagnostico a ser configurado
  idrecurso integer NOT NULL, -- Identificador do recurso a ser dada a permissão
  idpermissao integer NOT NULL, -- Identificador da pemrissão dada ao recurso.
  idpessoa integer NOT NULL, -- Identificador de pessoa que manipulou a permissão da parte interessada no diagnostico.
  data date NOT NULL, -- Data que foi realizada a manipulação do dado.
  ativo character(1) NOT NULL DEFAULT 'S'::bpchar, -- Situação da permissão cadastrada: S - Sim ativa N - Não
  CONSTRAINT pk_tb_permissaodiagnostico PRIMARY KEY (idpermissao, iddiagnostico, idpartediagnostico),
  CONSTRAINT fk_permpdiagnostico_diagnostico FOREIGN KEY (iddiagnostico)
    REFERENCES agepnet200.tb_diagnostico (iddiagnostico) MATCH SIMPLE
    ON UPDATE RESTRICT ON DELETE CASCADE,
  CONSTRAINT fk_permpdiagnostico_partediagnostico FOREIGN KEY (idpartediagnostico)
    REFERENCES agepnet200.tb_partediagnostico (idpartediagnostico) MATCH SIMPLE
    ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT fk_permpdiagnostico_permissao FOREIGN KEY (idpermissao)
    REFERENCES agepnet200.tb_permissao (idpermissao) MATCH SIMPLE
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_permpdiagnostico_pesmanipula FOREIGN KEY (idpessoa)
    REFERENCES agepnet200.tb_pessoa (idpessoa) MATCH SIMPLE
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT fk_permpdiagnostico_recurso FOREIGN KEY (idrecurso)
    REFERENCES agepnet200.tb_recurso (idrecurso) MATCH SIMPLE
    ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT ckc_ativo_ CHECK ((ativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])) AND ativo::text = upper(ativo::text))
  )
  WITH (
    OIDS=FALSE
    );
ALTER TABLE agepnet200.tb_permissaodiagnostico
  OWNER TO postgres;
GRANT ALL ON TABLE agepnet200.tb_permissaodiagnostico TO postgres;
COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.idpartediagnostico IS 'Identificação das pessoas que fazem parte do diagnostico como parte interessada.';
COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.iddiagnostico IS 'Identificador do diagnostico a ser configurado';
COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.idrecurso IS 'Identificador do recurso a ser dada a permissão';
COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.idpermissao IS 'Identificador da pemrissão dada ao recurso.';
COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.idpessoa IS 'Identificador de pessoa que manipulou a permissão da parte interessada no diagnostico.';
COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.data IS 'Data que foi realizada a manipulação do dado.';
COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.ativo IS 'Situação da permissão cadastrada: S - Sim ativa N - Não';

/*********************/

-- Table: agepnet200.tb_diagnostico
-- DROP TABLE agepnet200.tb_diagnostico;
CREATE TABLE agepnet200.tb_diagnostico
(
  iddiagnostico integer NOT NULL, -- Identificador do diagnostico.
  dsdiagnostico character varying(400) NOT NULL, -- Descreve o nome do diagnostico.
  idunidadeprincipal integer NOT NULL, -- Identificador da unidade do DPF que será a unidade principal para o diagnóstico.
  dtinicio date, -- Data de inicio do diagnóstico.
  dtencerramento date, -- Data de encerramento do diagnóstico.
  idcadastrador integer NOT NULL, -- Pessoa que cadastrou o diagnostico.
  dtcadastro date NOT NULL, -- Data que foi cadastrado o diagnóstico.
  ativo boolean NOT NULL DEFAULT true, -- Inativa ou ativa o diagnóstico
  CONSTRAINT pk_diagnostico PRIMARY KEY (iddiagnostico),
  CONSTRAINT pk_diagnostico_cadastrador FOREIGN KEY (idcadastrador)
    REFERENCES agepnet200.tb_pessoa (idpessoa) MATCH SIMPLE
    ON UPDATE NO ACTION ON DELETE RESTRICT
)
  WITH (
  OIDS=FALSE
       );
ALTER TABLE agepnet200.tb_diagnostico
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_diagnostico
IS 'Tela que retgistra os diagnosticos.';
COMMENT ON COLUMN agepnet200.tb_diagnostico.iddiagnostico IS 'Identificador do diagnostico.';
COMMENT ON COLUMN agepnet200.tb_diagnostico.dsdiagnostico IS 'Descreve o nome do diagnostico.';
COMMENT ON COLUMN agepnet200.tb_diagnostico.idunidadeprincipal IS 'Identificador da unidade do DPF que será a unidade principal para o diagnóstico.';
COMMENT ON COLUMN agepnet200.tb_diagnostico.dtinicio IS 'Data de inicio do diagnóstico.';
COMMENT ON COLUMN agepnet200.tb_diagnostico.dtencerramento IS 'Data de encerramento do diagnóstico.';
COMMENT ON COLUMN agepnet200.tb_diagnostico.idcadastrador IS 'Pessoa que cadastrou o diagnostico.';
COMMENT ON COLUMN agepnet200.tb_diagnostico.dtcadastro IS 'Data que foi cadastrado o diagnóstico.';
COMMENT ON COLUMN agepnet200.tb_diagnostico.ativo IS 'Inativa ou ativa o diagnóstico';


/*************************/

-- Table: agepnet200.tb_partediagnostico
-- DROP TABLE agepnet200.tb_partediagnostico;
CREATE TABLE agepnet200.tb_partediagnostico
(
  idpartediagnostico integer NOT NULL,
  iddiagnostico integer NOT NULL,
  qualificacao character varying(1) DEFAULT '1'::character varying, -- Combobox de qualificação com as opções: ...
  idcadastrador integer,
  datcadastro timestamp with time zone,
  idpessoa integer NOT NULL,
  tppermissao character varying(1) DEFAULT '1'::character varying, -- Combobox(Permissão) com as opções: ...
  CONSTRAINT pk_partediagnostico PRIMARY KEY (idpartediagnostico),
  CONSTRAINT fk_partediagnostico_diagnostico FOREIGN KEY (iddiagnostico)
    REFERENCES agepnet200.tb_diagnostico (iddiagnostico) MATCH SIMPLE
    ON UPDATE NO ACTION ON DELETE CASCADE
)
  WITH (
  OIDS=FALSE
       );
ALTER TABLE agepnet200.tb_partediagnostico
  OWNER TO postgres;
GRANT ALL ON TABLE agepnet200.tb_partediagnostico TO postgres;
COMMENT ON COLUMN agepnet200.tb_partediagnostico.qualificacao IS 'Combobox de qualificação com as opções:
1 - Chefe da Unidade Diagnosticada,
2 - Ponto focal da Unidade Diagnosticada,
3 - Equipe do Diagnóstico';
COMMENT ON COLUMN agepnet200.tb_partediagnostico.tppermissao IS 'Combobox(Permissão) com as opções:
1 - Editar,
2 - Visualizar';


/***************************/


-- Table: agepnet200.tb_unidade_vinculada
-- DROP TABLE agepnet200.tb_unidade_vinculada;
CREATE TABLE agepnet200.tb_unidade_vinculada
(
  idunidade integer NOT NULL, -- Identificador da unidade vinculada a unidade principal
  id_unidadeprincipal integer NOT NULL, -- Identificador da unidade principal do diagnostico.
  iddiagnostico integer NOT NULL, -- Identificador do diagnostico.
  CONSTRAINT pk_unidadevinculada PRIMARY KEY (idunidade, id_unidadeprincipal, iddiagnostico),
  CONSTRAINT fk_unidadevinculada_diagnostico FOREIGN KEY (iddiagnostico)
    REFERENCES agepnet200.tb_diagnostico (iddiagnostico) MATCH SIMPLE
    ON UPDATE NO ACTION ON DELETE CASCADE
)
  WITH (
  OIDS=FALSE
       );
ALTER TABLE agepnet200.tb_unidade_vinculada
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_unidade_vinculada
IS 'Tabela de unidade que estão vinculadas a unidade principal dos diagnosticos criados.';
COMMENT ON COLUMN agepnet200.tb_unidade_vinculada.idunidade IS 'Identificador da unidade vinculada a unidade principal';
COMMENT ON COLUMN agepnet200.tb_unidade_vinculada.id_unidadeprincipal IS 'Identificador da unidade principal do diagnostico.';
COMMENT ON COLUMN agepnet200.tb_unidade_vinculada.iddiagnostico IS 'Identificador do diagnostico.';


/********************/

INSERT INTO agepnet200.tb_recurso(
  idrecurso, ds_recurso, descricao)
VALUES ((select max(idrecurso)+1 from agepnet200.tb_recurso), 'diagnostico:diagnostico', '');

