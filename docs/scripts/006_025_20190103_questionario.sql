/**
 * PACOTE#006
 */

--Fazer a carga do recurso de questionário----------------------------------------------------------------------------------------------------------------

--Conceder as permissões das novas funcionalidades na Segurança do Gepnet---------------------------------------------------------------------------------



--Criação da tabela tb_questionario_diagnostico----------------------------------------------------------------------------------------------------------
-- Table: agepnet200.tb_questionario_diagnostico

-- DROP TABLE agepnet200.tb_questionario_diagnostico;

CREATE TABLE agepnet200.tb_questionario_diagnostico
(
  idquestionariodiagnostico bigint NOT NULL DEFAULT nextval('agepnet200.sq_questionariodiagnostico'::regclass), -- Coluna de indentificação de registros do questionario.
  nomquestionario character varying(400) NOT NULL, -- Descrição do nome do questionario.
  tipo character(1) NOT NULL DEFAULT 1, -- Coluna que define o tipo do questionário com as seguintes opções:...
  observacao text, -- Coluna de observações do questionário.
  idpescadastrador integer NOT NULL, -- Pessoa que cadastrou ou questionario.
  dtcadastro date NOT NULL, -- Data do cadastramento do questionario.
  CONSTRAINT pk_questionario_diagnostico PRIMARY KEY (idquestionariodiagnostico),
  CONSTRAINT fk_pessoa_questionariodiagnostico FOREIGN KEY (idpescadastrador)
      REFERENCES agepnet200.tb_pessoa (idpessoa) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_questionario_diagnostico
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_questionario_diagnostico
  IS 'Tabela de questionarios para os diagnosticos';
COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.idquestionariodiagnostico IS 'Coluna de indentificação de registros do questionario.';
COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.nomquestionario IS 'Descrição do nome do questionario.';
COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.tipo IS 'Coluna que define o tipo do questionário com as seguintes opções:
1 - Servidor
2 - Cidadão';
COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.observacao IS 'Coluna de observações do questionário.';
COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.idpescadastrador IS 'Pessoa que cadastrou ou questionario.';
COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.dtcadastro IS 'Data do cadastramento do questionario.';


-- Index: agepnet200.fki_pessoa_questionariodiagnostico

-- DROP INDEX agepnet200.fki_pessoa_questionariodiagnostico;

CREATE INDEX fki_pessoa_questionariodiagnostico
  ON agepnet200.tb_questionario_diagnostico
  USING btree
  (idpescadastrador);


---------------------------------------------------------------------------------------------------------------------------------------------------------

-- Table: agepnet200.tb_vincula_questionario

-- DROP TABLE agepnet200.tb_vincula_questionario;

CREATE TABLE agepnet200.tb_vincula_questionario
(
  idquestionario integer NOT NULL, -- identificador do questionario.
  iddiagnostico integer NOT NULL, -- Identificador do diagnostico
  disponivel character(1) NOT NULL DEFAULT 2, -- Identifica se o questionario esta liberado para ser respondido ou não....
  dtdisponibilidade date, -- Data que foi disponibilizado o questionario para respostas.
  dtencerrramento date, -- Data de encerramento da disponibilidade do questionario.
  idpesdisponibiliza integer, -- Pessoa que disponibilizou o questionario.
  idpesencerrou integer, -- Pessoa que encerrou o questionario.
  CONSTRAINT pk_questionario_vinculado PRIMARY KEY (idquestionario, iddiagnostico)
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


---------------------------------------------------------------------------------------------------------------------------------------------------------

-- Table: agepnet200.tb_secao

-- DROP TABLE agepnet200.tb_secao;

CREATE TABLE agepnet200.tb_secao
(
  id_secao integer NOT NULL, -- Identificador das seções do questionario.
  ds_secao character varying(200), -- Descrição das seções
  id_secao_pai integer, -- Identificador da seção pai da seção criada.
  ativa boolean NOT NULL DEFAULT true, -- Define se a seção esta ativa ou não para apresentação no questionário....
  tp_questionario character(1) NOT NULL, -- Define o tipo de questionario ao qual pertence a seção criada....
  CONSTRAINT pk_secao PRIMARY KEY (id_secao),
  CONSTRAINT fk_secao_secaopai FOREIGN KEY (id_secao_pai)
      REFERENCES agepnet200.tb_secao (id_secao) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_secao
  OWNER TO postgres;
COMMENT ON TABLE agepnet200.tb_secao
  IS 'Tabela que define as seções que o questionario deverá conter.';
COMMENT ON COLUMN agepnet200.tb_secao.id_secao IS 'Identificador das seções do questionario.';
COMMENT ON COLUMN agepnet200.tb_secao.ds_secao IS 'Descrição das seções ';
COMMENT ON COLUMN agepnet200.tb_secao.id_secao_pai IS 'Identificador da seção pai da seção criada.';
COMMENT ON COLUMN agepnet200.tb_secao.ativa IS 'Define se a seção esta ativa ou não para apresentação no questionário.
true - ativa
false - inativa.';
COMMENT ON COLUMN agepnet200.tb_secao.tp_questionario IS 'Define o tipo de questionario ao qual pertence a seção criada.
S - Questionario pesquisa de satisfação de servidores
C - Questionario pesquisa de satisfação de cidadãos.';

---------------------------------------------------------------------------------------------------------------------------------------------------------

-- Table: agepnet200.tb_item_secao

-- DROP TABLE agepnet200.tb_item_secao;

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
      ON UPDATE NO ACTION ON DELETE NO ACTION,
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

-----------------------------------------------------------------------------------------------------------------------------------------------------------

--Carga inicial na tabela de seções: tb_secao--------------------------------------------------------------------------------------------------------------
--1;"Cargo do Servidor";1;t;"S"
--2;"Principal área de atuação (Macroprocesso)";2;t;"S"
--3;"Processos Internos (atividades e rotinas de trabalho)";3;t;"S"
--4;"Comunicação Interna";4;t;"S"
--6;"Gestão Organizacional";6;t;"S"
--5;"Recursos e Infraestrutura";5;t;"S"
--7;"Satisfação Pessoal";7;t;"S"
--9;"Serviço Utilizado";9;t;"C"
--8;"Local de Atendimento";8;t;"C"
--10;"Avaliação";10;t;"C"
--11;"Informações Estatísticas (opcionais)";11;t;"C"
------------------------------------------------------------------------------------------------------------------------------------------------------------

--Criação da tabela tb_pergunta--------------------------------------------------------------------------------------------------------------------------
-- Table: agepnet200.tb_pergunta

-- DROP TABLE agepnet200.tb_pergunta;

CREATE TABLE agepnet200.tb_pergunta
(
  idpergunta bigint NOT NULL, -- Identificador do registro de perguntas.
  dspergunta character varying(300) NOT NULL, -- Descrição da pergunta.
  tipopergunta numeric(1,0) NOT NULL, -- Tipo de pergunta com as seguintes opções:
  ativa boolean NOT NULL DEFAULT false, -- Pergunta ativa:...
  idquestionario integer NOT NULL, -- Identificador do questionario criado.
  posicao integer NOT NULL, -- Posição que a pergunta será apresentada no questionário.
  id_secao integer NOT NULL, -- Define a qual seção ou subseção pertence a pergunta.
  tiporegistro numeric(1,0), -- Tipo de registro da resposta em banco de dados:...
  dstitulo character varying(200), -- Título da pergunta
  CONSTRAINT pk_pergunta PRIMARY KEY (idpergunta),
  CONSTRAINT fk_pergunta_questionario FOREIGN KEY (idquestionario)
      REFERENCES agepnet200.tb_questionario_diagnostico (idquestionariodiagnostico) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT fk_pergunta_secao FOREIGN KEY (id_secao)
      REFERENCES agepnet200.tb_secao (id_secao) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE RESTRICT
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
COMMENT ON COLUMN agepnet200.tb_pergunta.tipopergunta IS 'Tipo de pergunta com as seguintes opções:';
COMMENT ON COLUMN agepnet200.tb_pergunta.ativa IS 'Pergunta ativa:
true = ativa
false = inativa.';
COMMENT ON COLUMN agepnet200.tb_pergunta.idquestionario IS 'Identificador do questionario criado.';
COMMENT ON COLUMN agepnet200.tb_pergunta.posicao IS 'Posição que a pergunta será apresentada no questionário.';
COMMENT ON COLUMN agepnet200.tb_pergunta.id_secao IS 'Define a qual seção ou subseção pertence a pergunta.';
COMMENT ON COLUMN agepnet200.tb_pergunta.tiporegistro IS 'Tipo de registro da resposta em banco de dados:
1 - Numério
2 - Textual';
COMMENT ON COLUMN agepnet200.tb_pergunta.dstitulo IS 'Título da pergunta';
---------------------------------------------------------------------------------------------------------------------------------------------------------

--Criação da tabela tb_resposta_questionario-------------------------------------------------------------------------------------------------------------
-- Table: agepnet200.tb_resposta_questionario

-- DROP TABLE agepnet200.tb_resposta_questionario;

CREATE TABLE agepnet200.tb_resposta_questionario
(
  idresposta integer NOT NULL,
  idpergunta integer NOT NULL,
  idquestionario integer NOT NULL,
  desresposta character varying(300),
  CONSTRAINT pk_resposta_questionario PRIMARY KEY (idresposta, idpergunta),
  CONSTRAINT fk_pergunta FOREIGN KEY (idpergunta)
      REFERENCES agepnet200.tb_pergunta (idpergunta) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
)
WITH (
  OIDS=FALSE
);
ALTER TABLE agepnet200.tb_resposta_questionario
  OWNER TO postgres;
GRANT ALL ON TABLE agepnet200.tb_resposta_questionario TO postgres;

---------------------------------------------------------------------------------------------------------------------------------------------------------
