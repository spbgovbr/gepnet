--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: agepnet200; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA agepnet200;


ALTER SCHEMA agepnet200 OWNER TO postgres;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: adminpack; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS adminpack WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION adminpack; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION adminpack IS 'administrative functions for PostgreSQL';


SET search_path = agepnet200, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: tb_acao; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_acao (
    idacao integer NOT NULL,
    idobjetivo integer NOT NULL,
    nomacao character varying(100) NOT NULL,
    idcadastrador integer,
    datcadastro date,
    flaativo character(1) DEFAULT 's'::bpchar,
    desacao text,
    idescritorio integer DEFAULT 0,
    numseq integer DEFAULT 0
);


ALTER TABLE tb_acao OWNER TO postgres;

--
-- Name: tb_aceite; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_aceite (
    idaceite integer NOT NULL,
    desprodutoservico text,
    desparecerfinal text,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL
);


ALTER TABLE tb_aceite OWNER TO postgres;

--
-- Name: tb_aceiteatividadecronograma; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_aceiteatividadecronograma (
    idaceiteativcronograma integer NOT NULL,
    identrega integer NOT NULL,
    idprojeto integer NOT NULL,
    idaceite integer NOT NULL,
    idmarco integer,
    aceito character(1) NOT NULL,
    idpesaceitou integer,
    dataceitacao date,
    CONSTRAINT cc_aceito CHECK ((aceito = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE tb_aceiteatividadecronograma OWNER TO postgres;

--
-- Name: COLUMN tb_aceiteatividadecronograma.idaceiteativcronograma; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN tb_aceiteatividadecronograma.idaceiteativcronograma IS 'codigo de controle da tabela.';


--
-- Name: COLUMN tb_aceiteatividadecronograma.identrega; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN tb_aceiteatividadecronograma.identrega IS 'Codigo da entrega selecionada.';


--
-- Name: tb_acordo; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_acordo (
    idacordo integer NOT NULL,
    idacordopai integer,
    idtipoacordo integer,
    nomacordo character varying(100),
    idresponsavelinterno integer NOT NULL,
    destelefoneresponsavelinterno character varying(30),
    idsetor integer NOT NULL,
    idfiscal integer NOT NULL,
    destelefonefiscal character varying(30),
    despalavrachave character varying(100),
    desobjeto text,
    desobservacao text,
    datassinatura date,
    datiniciovigencia date,
    datfimvigencia date,
    numprazovigencia integer,
    datatualizacao date,
    datcadastro date,
    idcadastrador integer,
    flarescindido character(1) DEFAULT 'n'::bpchar,
    flasituacaoatual numeric(1,0),
    numsiapro character varying(25),
    descontatoexterno text,
    idfiscal2 integer,
    idfiscal3 integer,
    idacordoespecieinstrumento integer,
    datpublicacao date,
    descargofiscal character varying(100),
    descaminho character varying(100),
    CONSTRAINT ckc_flarescindido_tb_acord CHECK (((flarescindido IS NULL) OR (flarescindido = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))),
    CONSTRAINT ckc_flasituacaoatual_tb_acord CHECK (((flasituacaoatual IS NULL) OR (flasituacaoatual = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric, (4)::numeric]))))
);


ALTER TABLE tb_acordo OWNER TO postgres;

--
-- Name: tb_acordoentidadeexterna; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_acordoentidadeexterna (
    idacordo integer NOT NULL,
    identidadeexterna integer NOT NULL
);


ALTER TABLE tb_acordoentidadeexterna OWNER TO postgres;

--
-- Name: tb_acordoespecieinstrumento; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_acordoespecieinstrumento (
    idacordoespecieinstrumento integer NOT NULL,
    nomacordoespecieinstrumento character varying(200) NOT NULL,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    flaativo character(1) NOT NULL,
    CONSTRAINT ckc_flaativo_tb_acord CHECK ((flaativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE tb_acordoespecieinstrumento OWNER TO postgres;

--
-- Name: tb_agenda; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_agenda (
    idagenda integer NOT NULL,
    desassunto character varying(100) NOT NULL,
    datagenda date,
    desagenda text,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    hragendada timestamp without time zone,
    deslocal character varying(30),
    flaenviaemail numeric(1,0),
    idescritorio integer NOT NULL,
    CONSTRAINT ckc_flaenviaemail_tb_agend CHECK (((flaenviaemail IS NULL) OR (flaenviaemail = ANY (ARRAY[(1)::numeric, (2)::numeric]))))
);


ALTER TABLE tb_agenda OWNER TO postgres;

--
-- Name: tb_aquisicao; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_aquisicao (
    idaquisicao integer NOT NULL,
    idprojeto integer NOT NULL,
    identrega integer NOT NULL,
    descontrato character varying(100),
    desfornecedor character varying(100),
    numvalor bigint NOT NULL,
    datprazoaquisicao date,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    numquantidade character varying(20),
    desaquisicao character varying(100)
);


ALTER TABLE tb_aquisicao OWNER TO postgres;

--
-- Name: tb_ata; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_ata (
    idata integer NOT NULL,
    idprojeto integer NOT NULL,
    desassunto character varying(100) NOT NULL,
    datata date NOT NULL,
    deslocal character varying(100) NOT NULL,
    desparticipante text NOT NULL,
    despontodiscutido text NOT NULL,
    desdecisao text NOT NULL,
    despontoatencao text NOT NULL,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    desproximopasso text NOT NULL,
    hrreuniao character varying(8) NOT NULL
);


ALTER TABLE tb_ata OWNER TO postgres;

--
-- Name: tb_atividade; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_atividade (
    idatividade integer NOT NULL,
    nomatividade character varying(100),
    desatividade text,
    idcadastrador integer NOT NULL,
    idresponsavel integer NOT NULL,
    datcadastro date,
    datatualizacao date,
    datinicio date,
    datfimmeta date,
    datfimreal date,
    flacontinua numeric(1,0),
    numpercentualconcluido integer,
    flacancelada numeric(1,0),
    idescritorio integer NOT NULL,
    CONSTRAINT ckc_flacancelada_tb_ativi CHECK (((flacancelada IS NULL) OR (flacancelada = ANY (ARRAY[(1)::numeric, (2)::numeric])))),
    CONSTRAINT ckc_flacontinua_tb_ativi CHECK (((flacontinua IS NULL) OR (flacontinua = ANY (ARRAY[(1)::numeric, (2)::numeric]))))
);


ALTER TABLE tb_atividade OWNER TO postgres;

--
-- Name: tb_atividadecronograma; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_atividadecronograma (
    idatividadecronograma bigint NOT NULL,
    idprojeto integer NOT NULL,
    idgrupo bigint,
    nomatividadecronograma character varying(255) NOT NULL,
    domtipoatividade numeric(1,0) NOT NULL,
    desobs text,
    datcadastro timestamp with time zone,
    idmarcoanterior bigint,
    numdias integer,
    vlratividadebaseline bigint DEFAULT (0)::bigint,
    vlratividade bigint DEFAULT (0)::bigint,
    numfolga integer DEFAULT 0,
    descriterioaceitacao text,
    idelementodespesa integer,
    idcadastrador integer,
    idparteinteressada integer,
    datiniciobaseline date,
    datfimbaseline date,
    flaaquisicao character(1),
    flainformatica character(1),
    flacancelada character(1),
    datinicio date,
    datfim date,
    numpercentualconcluido numeric(5,2),
    numdiasbaseline integer,
    numdiasrealizados integer DEFAULT 0,
    CONSTRAINT ckc_domtipoatividade CHECK ((domtipoatividade = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric, (4)::numeric]))),
    CONSTRAINT ckc_elementodespesa CHECK ((((flainformatica = 'S'::bpchar) AND (idelementodespesa IS NOT NULL)) OR ((flainformatica = 'N'::bpchar) AND (idelementodespesa IS NULL)))),
    CONSTRAINT ckc_flaelementodespesa CHECK ((((flainformatica = 'S'::bpchar) AND (idelementodespesa IS NOT NULL)) OR ((flainformatica = 'N'::bpchar) AND (idelementodespesa IS NULL))))
);


ALTER TABLE tb_atividadecronograma OWNER TO postgres;

--
-- Name: tb_atividadepredecessora; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_atividadepredecessora (
    idatividadepredecessora bigint NOT NULL,
    idprojeto integer NOT NULL,
    idatividade bigint NOT NULL
);


ALTER TABLE tb_atividadepredecessora OWNER TO postgres;

--
-- Name: tb_comunicacao; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_comunicacao (
    idcomunicacao integer NOT NULL,
    idprojeto integer NOT NULL,
    desinformacao character varying(255),
    desinformado character varying(255),
    desorigem character varying(255),
    desfrequencia character varying(255),
    destransmissao character varying(255),
    desarmazenamento character varying(255),
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    nomresponsavel character varying(100),
    idresponsavel integer
);


ALTER TABLE tb_comunicacao OWNER TO postgres;

--
-- Name: tb_contramedida; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_contramedida (
    idcontramedida integer NOT NULL,
    idrisco integer,
    descontramedida text,
    datprazocontramedida date,
    datprazocontramedidaatraso date,
    domstatuscontramedida numeric(1,0),
    flacontramedidaefetiva numeric(1,0),
    desresponsavel character varying(100),
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    idtipocontramedida integer NOT NULL,
    nocontramedida character varying(100),
    CONSTRAINT cc_domstatuscontramedida CHECK (((domstatuscontramedida IS NULL) OR (domstatuscontramedida = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric, (4)::numeric, (5)::numeric, (6)::numeric])))),
    CONSTRAINT cc_flacontramedidaefetiva CHECK (((flacontramedidaefetiva IS NULL) OR (flacontramedidaefetiva = ANY (ARRAY[(1)::numeric, (2)::numeric]))))
);


ALTER TABLE tb_contramedida OWNER TO postgres;

--
-- Name: tb_diariobordo; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_diariobordo (
    iddiariobordo integer NOT NULL,
    idprojeto integer NOT NULL,
    datdiariobordo date,
    domreferencia character varying(20),
    domsemafaro numeric(1,0),
    desdiariobordo text,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    idalterador integer,
    CONSTRAINT ckc_domsemafaro_tb_diari CHECK (((domsemafaro IS NULL) OR (domsemafaro = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric]))))
);


ALTER TABLE tb_diariobordo OWNER TO postgres;

--
-- Name: tb_documento; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_documento (
    iddocumento integer NOT NULL,
    idescritorio integer,
    nomdocumento character varying(100),
    idtipodocumento integer,
    descaminho character varying(50),
    datdocumento date,
    desobs text,
    idcadastrador integer,
    datcadastro date,
    flaativo character varying(1),
    CONSTRAINT ckc_flaativo CHECK (((flaativo)::bpchar = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE tb_documento OWNER TO postgres;

--
-- Name: tb_elementodespesa; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_elementodespesa (
    idelementodespesa integer NOT NULL,
    idoficial integer NOT NULL,
    nomelementodespesa character varying(100),
    idcadastrador integer,
    datcadastro date,
    numseq integer
);


ALTER TABLE tb_elementodespesa OWNER TO postgres;

--
-- Name: tb_entidadeexterna; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_entidadeexterna (
    identidadeexterna integer NOT NULL,
    nomentidadeexterna character varying(100) NOT NULL,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL
);


ALTER TABLE tb_entidadeexterna OWNER TO postgres;

--
-- Name: tb_escritorio; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_escritorio (
    idescritorio integer NOT NULL,
    nomescritorio character varying(100) NOT NULL,
    idcadastrador integer NOT NULL,
    datcadastro date,
    flaativo character(1) NOT NULL,
    idresponsavel1 integer,
    idresponsavel2 integer,
    idescritoriope integer DEFAULT 0,
    nomescritorio2 character varying(100),
    desemail character varying(100),
    numfone character varying(16),
    CONSTRAINT ckc_flaativo CHECK ((flaativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE tb_escritorio OWNER TO postgres;

--
-- Name: tb_etapa; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_etapa (
    idetapa integer NOT NULL,
    dsetapa character varying(30) NOT NULL,
    idcadastrador integer NOT NULL,
    dtcadastro date NOT NULL
);


ALTER TABLE tb_etapa OWNER TO postgres;

--
-- Name: tb_evento; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_evento (
    idevento integer NOT NULL,
    nomevento character varying(100),
    desevento text,
    desobs text,
    idcadastrador integer,
    idresponsavel integer,
    datcadastro date,
    datinicio date,
    datfim date,
    uf character varying(2)
);


ALTER TABLE tb_evento OWNER TO postgres;

--
-- Name: tb_eventoavaliacao; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_eventoavaliacao (
    ideventoavaliacao integer NOT NULL,
    idevento integer NOT NULL,
    desdestaqueservidor text,
    desobs text,
    idavaliador integer,
    idavaliado integer,
    datcadastro date,
    numpontualidade integer,
    numordens integer,
    numrespeitochefia integer,
    numrespeitocolega integer,
    numurbanidade integer,
    numequilibrio integer,
    numcomprometimento integer,
    numesforco integer,
    numtrabalhoequipe integer,
    numauxiliouequipe integer,
    numaceitousugestao integer,
    numconhecimentonorma integer,
    numalternativaproblema integer,
    numiniciativa integer,
    numtarefacomplexa integer,
    numnotaavaliador integer,
    nummedia double precision,
    nummediafinal double precision,
    numtotalavaliado integer,
    idtipoavaliacao integer
);


ALTER TABLE tb_eventoavaliacao OWNER TO postgres;

--
-- Name: tb_frase; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_frase (
    idfrase integer NOT NULL,
    domtipofrase numeric(1,0),
    flaativo character varying(1),
    datcadastro date NOT NULL,
    idescritorio integer NOT NULL,
    idcadastrador integer NOT NULL,
    desfrase character varying(255),
    CONSTRAINT ckc_domtipofrase_tb_frase CHECK (((domtipofrase IS NULL) OR (domtipofrase = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric, (4)::numeric, (5)::numeric, (6)::numeric, (7)::numeric])))),
    CONSTRAINT ckc_flaativo_tb_frase CHECK (((flaativo IS NULL) OR ((flaativo)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text]))))
);


ALTER TABLE tb_frase OWNER TO postgres;

--
-- Name: tb_frase_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_frase_pesquisa (
    idfrasepesquisa integer NOT NULL,
    idcadastrador integer NOT NULL,
    domtipofrase numeric(1,0),
    flaativo character varying(1),
    datcadastro date NOT NULL,
    idescritorio integer NOT NULL,
    desfrase character varying(255) NOT NULL,
    CONSTRAINT cc_flaativofrase CHECK (((flaativo IS NULL) OR ((flaativo)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text])))),
    CONSTRAINT ckc_domtipofrase_tb_frase CHECK (((domtipofrase IS NULL) OR (domtipofrase = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric, (4)::numeric, (5)::numeric, (6)::numeric, (7)::numeric]))))
);


ALTER TABLE tb_frase_pesquisa OWNER TO postgres;

--
-- Name: tb_hst_publicacao; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_hst_publicacao (
    idhistoricopublicacao integer NOT NULL,
    idpesquisa integer NOT NULL,
    datpublicacao timestamp without time zone,
    datencerramento timestamp without time zone,
    idpespublicou integer,
    idpesencerrou integer
);


ALTER TABLE tb_hst_publicacao OWNER TO postgres;

--
-- Name: tb_licao; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_licao (
    idlicao integer NOT NULL,
    idprojeto integer NOT NULL,
    identrega integer,
    desresultadosobtidos text,
    despontosfortes text,
    despontosfracos text,
    dessugestoes text,
    datcadastro date NOT NULL
);


ALTER TABLE tb_licao OWNER TO postgres;

--
-- Name: tb_mudanca; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_mudanca (
    idmudanca integer NOT NULL,
    idprojeto integer NOT NULL,
    nomsolicitante character varying(100),
    datsolicitacao date,
    datdecisao date,
    desmudanca text,
    desjustificativa text,
    despareceregp text,
    desaprovadores text,
    despareceraprovadores text,
    idcadastrador integer NOT NULL,
    idtipomudanca integer NOT NULL,
    datcadastro date NOT NULL,
    flaaprovada character(1),
    CONSTRAINT ckc_flaaprovada CHECK (((flaaprovada IS NULL) OR ((flaaprovada)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text]))))
);


ALTER TABLE tb_mudanca OWNER TO postgres;

--
-- Name: tb_natureza; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_natureza (
    idnatureza integer NOT NULL,
    nomnatureza character varying(100) NOT NULL,
    idcadastrador integer,
    datcadastro date,
    flaativo character(1) DEFAULT 's'::bpchar,
    CONSTRAINT ckc_flaativo CHECK (((flaativo IS NULL) OR ((flaativo)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text]))))
);


ALTER TABLE tb_natureza OWNER TO postgres;

--
-- Name: tb_objetivo; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_objetivo (
    idobjetivo integer NOT NULL,
    nomobjetivo character varying(100) NOT NULL,
    idcadastrador integer,
    datcadastro date,
    flaativo character(1) DEFAULT 's'::bpchar,
    desobjetivo text,
    codescritorio integer DEFAULT 0,
    numseq integer DEFAULT 0,
    CONSTRAINT ckc_flaativo_tb_objet CHECK (((flaativo IS NULL) OR (flaativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar]))))
);


ALTER TABLE tb_objetivo OWNER TO postgres;

--
-- Name: tb_origemrisco; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_origemrisco (
    idorigemrisco integer NOT NULL,
    desorigemrisco character varying(30),
    idcadastrador integer,
    dtcadastro date
);


ALTER TABLE tb_origemrisco OWNER TO postgres;

--
-- Name: tb_p_acao; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_p_acao (
    id_p_acao integer NOT NULL,
    idprojetoprocesso integer NOT NULL,
    nom_p_acao character varying(100),
    des_p_acao text,
    datinicioprevisto date,
    datinicioreal date,
    datterminoprevisto date,
    datterminoreal date,
    idsetorresponsavel integer DEFAULT 0,
    flacancelada numeric(1,0) NOT NULL,
    idcadastrador integer NOT NULL,
    datcadastro timestamp with time zone NOT NULL,
    numseq character varying(10) NOT NULL,
    idresponsavel integer,
    CONSTRAINT ckc_flacancelada_tb_p_aca CHECK ((flacancelada = ANY (ARRAY[(1)::numeric, (2)::numeric])))
);


ALTER TABLE tb_p_acao OWNER TO postgres;

--
-- Name: tb_parteinteressada; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_parteinteressada (
    idparteinteressada integer NOT NULL,
    idprojeto integer NOT NULL,
    nomparteinteressada character varying(100),
    nomfuncao character varying(50),
    destelefone character varying(50),
    desemail character varying(50),
    domnivelinfluencia character varying(10),
    idcadastrador integer,
    datcadastro timestamp with time zone,
    idpessoainterna integer,
    observacao character(200)
);


ALTER TABLE tb_parteinteressada OWNER TO postgres;

--
-- Name: tb_perfil; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_perfil (
    idperfil integer NOT NULL,
    nomperfil character varying(50) NOT NULL,
    flaativo character(1) NOT NULL,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    CONSTRAINT ckc_flaativo_tb_perfi CHECK ((flaativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE tb_perfil OWNER TO postgres;

--
-- Name: tb_perfilpessoa; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_perfilpessoa (
    idpessoa integer NOT NULL,
    idperfil integer NOT NULL,
    idescritorio integer NOT NULL,
    flaativo character(1) NOT NULL,
    idcadastrador integer,
    datcadastro date,
    idperfilpessoa integer NOT NULL,
    CONSTRAINT ckc_flaativo CHECK ((flaativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE tb_perfilpessoa OWNER TO postgres;

--
-- Name: tb_permissao; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_permissao (
    idpermissao integer NOT NULL,
    idrecurso integer NOT NULL,
    ds_permissao character varying(200),
    no_permissao character varying(50)
);


ALTER TABLE tb_permissao OWNER TO postgres;

--
-- Name: tb_permissaoperfil; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_permissaoperfil (
    idpermissaoperfil integer NOT NULL,
    idperfil integer NOT NULL,
    idpermissao integer NOT NULL
);


ALTER TABLE tb_permissaoperfil OWNER TO postgres;

--
-- Name: tb_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_pesquisa (
    idpesquisa integer NOT NULL,
    situacao numeric(1,0) NOT NULL,
    idcadastrador integer NOT NULL,
    datcadastro timestamp without time zone NOT NULL,
    datpublicacao timestamp without time zone,
    idpespublica integer,
    idpesencerra integer,
    idquestionario integer NOT NULL,
    dtencerramento timestamp without time zone,
    CONSTRAINT cc_situacao CHECK ((situacao = ANY (ARRAY[(1)::numeric, (2)::numeric])))
);


ALTER TABLE tb_pesquisa OWNER TO postgres;

--
-- Name: tb_pessoa; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_pessoa (
    idpessoa integer NOT NULL,
    nompessoa character varying(100) NOT NULL,
    desobs text,
    numfone character varying(16),
    numcelular character varying(16),
    desemail character varying(100),
    idcadastrador integer,
    datcadastro date,
    nummatricula integer,
    desfuncao character varying(50),
    id_unidade integer,
    domcargo character varying(50) NOT NULL,
    id_servidor integer,
    flaagenda character varying(1) DEFAULT 'S'::character varying,
    numcpf numeric(11,0),
    numsiape bigint,
    token character(255),
    lotacao character(100)
);


ALTER TABLE tb_pessoa OWNER TO postgres;

--
-- Name: tb_pessoaagenda; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_pessoaagenda (
    idagenda integer NOT NULL,
    idpessoa integer NOT NULL
);


ALTER TABLE tb_pessoaagenda OWNER TO postgres;

--
-- Name: tb_portfolio; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_portfolio (
    idportfolio integer NOT NULL,
    noportfolio character varying(100) NOT NULL,
    idportfoliopai integer,
    ativo character(1) NOT NULL,
    tipo numeric(1,0) NOT NULL,
    idresponsavel integer NOT NULL,
    idescritorio integer NOT NULL,
    CONSTRAINT ckc_ativo_tb_portf CHECK ((ativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar]))),
    CONSTRAINT ckc_tipo_tb_portf CHECK ((tipo = ANY (ARRAY[(1)::numeric, (2)::numeric])))
);


ALTER TABLE tb_portfolio OWNER TO postgres;

--
-- Name: tb_portifolioprograma; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_portifolioprograma (
    idprograma integer NOT NULL,
    idportfolio integer NOT NULL
);


ALTER TABLE tb_portifolioprograma OWNER TO postgres;

--
-- Name: tb_processo; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_processo (
    idprocesso integer NOT NULL,
    idprocessopai integer,
    nomcodigo character varying(20),
    nomprocesso character varying(100),
    idsetor integer,
    desprocesso text,
    iddono integer NOT NULL,
    idexecutor integer NOT NULL,
    idgestor integer NOT NULL,
    idconsultor integer NOT NULL,
    numvalidade integer,
    datatualizacao date,
    idcadastrador integer,
    datcadastro date
);


ALTER TABLE tb_processo OWNER TO postgres;

--
-- Name: tb_programa; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_programa (
    idprograma integer NOT NULL,
    nomprograma character varying(100) NOT NULL,
    desprograma text,
    idcadastrador integer NOT NULL,
    datcadastro date,
    flaativo character(1) NOT NULL,
    idresponsavel integer,
    idsimpr integer,
    idsimpreixo integer,
    idsimprareatematica integer,
    CONSTRAINT ckc_flaativo CHECK ((flaativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE tb_programa OWNER TO postgres;

--
-- Name: tb_projeto; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_projeto (
    idprojeto integer NOT NULL,
    nomcodigo character varying(50),
    nomsigla character varying(20),
    nomprojeto character varying(100),
    idsetor integer,
    idgerenteprojeto integer NOT NULL,
    idgerenteadjunto integer NOT NULL,
    desprojeto text,
    desobjetivo text,
    datinicio date,
    datfim date,
    numperiodicidadeatualizacao integer,
    numcriteriofarol integer,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    domtipoprojeto character varying(20),
    flapublicado character varying(1),
    flaaprovado character varying(1),
    desresultadosobtidos text,
    despontosfortes text,
    despontosfracos text,
    dessugestoes text,
    idescritorio integer,
    flaaltagestao character varying(1),
    idobjetivo integer,
    idacao integer,
    flacopa character varying(1),
    idnatureza integer,
    vlrorcamentodisponivel bigint DEFAULT (0)::bigint,
    desjustificativa text,
    iddemandante integer DEFAULT 0,
    idpatrocinador integer DEFAULT 0,
    datinicioplano date,
    datfimplano date,
    desescopo text,
    desnaoescopo text,
    despremissa text,
    desrestricao text,
    numseqprojeto integer,
    numanoprojeto integer,
    desconsideracaofinal text,
    datenviouemailatualizacao date,
    idprograma integer DEFAULT 0,
    nomproponente character varying(100),
    domstatusprojeto integer NOT NULL,
    ano numeric(4,0),
    idportfolio integer,
    CONSTRAINT ckc_flaaltagestao CHECK (((flaaltagestao IS NULL) OR ((flaaltagestao)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text])))),
    CONSTRAINT ckc_flaaprovado CHECK (((flaaprovado IS NULL) OR ((flaaprovado)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text])))),
    CONSTRAINT ckc_flacopa CHECK (((flacopa IS NULL) OR ((flacopa)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text])))),
    CONSTRAINT ckc_flapublicado CHECK (((flapublicado IS NULL) OR ((flapublicado)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text]))))
);


ALTER TABLE tb_projeto OWNER TO postgres;

--
-- Name: tb_projetoprocesso; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_projetoprocesso (
    idprojetoprocesso integer NOT NULL,
    idprocesso integer NOT NULL,
    numano numeric(4,0),
    domsituacao numeric(1,0),
    datsituacao date,
    idresponsavel integer,
    desprojetoprocesso text,
    datinicioprevisto date,
    datterminoprevisto date,
    vlrorcamento bigint NOT NULL,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    CONSTRAINT ckc_domsituacao CHECK (((domsituacao IS NULL) OR (domsituacao = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric, (4)::numeric]))))
);


ALTER TABLE tb_projetoprocesso OWNER TO postgres;

--
-- Name: tb_questionario; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_questionario (
    idquestionario integer NOT NULL,
    nomquestionario character varying(255),
    desobservacao text,
    tipoquestionario numeric(1,0),
    idcadastrador integer,
    datcadastro date,
    idescritorio integer NOT NULL,
    disponivel numeric(1,0) DEFAULT 0,
    CONSTRAINT cc_disponivel CHECK (((disponivel IS NULL) OR (disponivel = ANY (ARRAY[(0)::numeric, (1)::numeric])))),
    CONSTRAINT ckc_tipoquestionario_tb_quest CHECK (((tipoquestionario IS NULL) OR (tipoquestionario = ANY (ARRAY[(1)::numeric, (2)::numeric]))))
);


ALTER TABLE tb_questionario OWNER TO postgres;

--
-- Name: tb_questionario_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_questionario_pesquisa (
    idquestionariopesquisa integer NOT NULL,
    idpesquisa integer NOT NULL,
    nomquestionario character varying(255),
    desobservacao text,
    tipoquestionario numeric(1,0),
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    idescritorio integer NOT NULL,
    CONSTRAINT cc_tipoquestionario CHECK (((tipoquestionario IS NULL) OR (tipoquestionario = ANY (ARRAY[(1)::numeric, (2)::numeric]))))
);


ALTER TABLE tb_questionario_pesquisa OWNER TO postgres;

--
-- Name: tb_questionariofrase; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_questionariofrase (
    idfrase integer NOT NULL,
    idquestionario integer NOT NULL,
    numordempergunta integer NOT NULL,
    idcadastrador integer,
    datcadastro date,
    obrigatoriedade character(1) NOT NULL,
    CONSTRAINT cc_obrigatoriedade CHECK (((obrigatoriedade IS NULL) OR (obrigatoriedade = ANY (ARRAY['S'::bpchar, 'N'::bpchar]))))
);


ALTER TABLE tb_questionariofrase OWNER TO postgres;

--
-- Name: tb_questionariofrase_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_questionariofrase_pesquisa (
    idquestionariopesquisa integer NOT NULL,
    idfrasepesquisa integer NOT NULL,
    numordempergunta integer NOT NULL,
    datcadastro date,
    idcadastrador integer NOT NULL,
    obrigatoriedade character(1) DEFAULT 'N'::bpchar NOT NULL,
    CONSTRAINT cc_obrigatoriedade CHECK ((obrigatoriedade = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE tb_questionariofrase_pesquisa OWNER TO postgres;

--
-- Name: tb_r3g; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_r3g (
    idr3g integer NOT NULL,
    idprojeto integer NOT NULL,
    datdeteccao date,
    desplanejado text,
    desrealizado text,
    descausa text,
    desconsequencia text,
    descontramedida text,
    datprazocontramedida date,
    datprazocontramedidaatraso date,
    idcadastrador integer,
    datcadastro date,
    desresponsavel character varying(100),
    desobs text,
    domtipo numeric(1,0),
    domcorprazoprojeto numeric(1,0),
    domstatuscontramedida numeric(1,0),
    flacontramedidaefetiva numeric(1,0),
    CONSTRAINT cc_domcorprazoprojeto CHECK (((domcorprazoprojeto IS NULL) OR (domcorprazoprojeto = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric])))),
    CONSTRAINT cc_domstatuscontramedida CHECK (((domstatuscontramedida IS NULL) OR (domstatuscontramedida = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric, (4)::numeric, (5)::numeric, (6)::numeric])))),
    CONSTRAINT cc_domtipo CHECK (((domtipo IS NULL) OR (domtipo = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric, (4)::numeric])))),
    CONSTRAINT cc_flacontramedida CHECK (((flacontramedidaefetiva IS NULL) OR (flacontramedidaefetiva = ANY (ARRAY[(1)::numeric, (2)::numeric]))))
);


ALTER TABLE tb_r3g OWNER TO postgres;

--
-- Name: tb_recurso; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_recurso (
    idrecurso integer NOT NULL,
    ds_recurso character varying(50) NOT NULL
);


ALTER TABLE tb_recurso OWNER TO postgres;

--
-- Name: tb_resposta; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_resposta (
    idresposta integer NOT NULL,
    numordem numeric(2,0),
    flaativo character varying(1),
    datcadastro date NOT NULL,
    idcadastrador integer NOT NULL,
    desresposta character varying(255),
    CONSTRAINT ckc_flaativo_tb_respo CHECK (((flaativo IS NULL) OR ((flaativo)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text]))))
);


ALTER TABLE tb_resposta OWNER TO postgres;

--
-- Name: tb_resposta_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_resposta_pesquisa (
    idrespostapesquisa integer NOT NULL,
    desresposta character varying(255),
    numordem numeric(2,0),
    flaativo character varying(1),
    datcadastro date NOT NULL,
    idcadastrador integer NOT NULL,
    CONSTRAINT cc_flaativo CHECK (((flaativo IS NULL) OR ((flaativo)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text]))))
);


ALTER TABLE tb_resposta_pesquisa OWNER TO postgres;

--
-- Name: tb_respostafrase; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_respostafrase (
    idfrase integer NOT NULL,
    idresposta integer NOT NULL
);


ALTER TABLE tb_respostafrase OWNER TO postgres;

--
-- Name: tb_respostafrase_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_respostafrase_pesquisa (
    idfrasepesquisa integer NOT NULL,
    idrespostapesquisa integer NOT NULL
);


ALTER TABLE tb_respostafrase_pesquisa OWNER TO postgres;

--
-- Name: tb_resultado_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_resultado_pesquisa (
    id integer NOT NULL,
    idresultado integer NOT NULL,
    idfrasepesquisa integer NOT NULL,
    idquestionariopesquisa integer NOT NULL,
    desresposta text,
    datcadastro timestamp without time zone NOT NULL,
    cpf character varying(11)
);


ALTER TABLE tb_resultado_pesquisa OWNER TO postgres;

--
-- Name: tb_risco; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_risco (
    idrisco integer NOT NULL,
    idprojeto integer NOT NULL,
    idorigemrisco integer,
    idetapa integer,
    idtiporisco integer,
    datdeteccao date,
    desrisco text,
    domcorprobabilidade numeric(1,0),
    domcorimpacto numeric(1,0),
    domcorrisco numeric(1,0),
    descausa text,
    desconsequencia text,
    flariscoativo numeric(1,0),
    datencerramentorisco date,
    idcadastrador integer,
    datcadastro date,
    domtratamento numeric(1,0),
    norisco character varying(50),
    flaaprovado numeric(1,0),
    datinatividade date,
    CONSTRAINT cc_domcorimpacto CHECK (((domcorimpacto IS NULL) OR (domcorimpacto = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric])))),
    CONSTRAINT cc_domcorprobabilida CHECK (((domcorprobabilidade IS NULL) OR (domcorprobabilidade = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric])))),
    CONSTRAINT cc_domcorrisco CHECK (((domcorrisco IS NULL) OR (domcorrisco = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric])))),
    CONSTRAINT cc_domtratamento CHECK (((domtratamento IS NULL) OR (domtratamento = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric, (4)::numeric])))),
    CONSTRAINT cc_flaaprovado CHECK (((flaaprovado IS NULL) OR (flaaprovado = ANY (ARRAY[(1)::numeric, (2)::numeric])))),
    CONSTRAINT cc_flariscoativo CHECK (((flariscoativo IS NULL) OR (flariscoativo = ANY (ARRAY[(1)::numeric, (2)::numeric]))))
);


ALTER TABLE tb_risco OWNER TO postgres;

--
-- Name: tb_setor; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_setor (
    idsetor integer NOT NULL,
    nomsetor character varying(100) NOT NULL,
    idcadastrador integer,
    datcadastro date,
    flaativo character(1) DEFAULT 'S'::bpchar
);


ALTER TABLE tb_setor OWNER TO postgres;

--
-- Name: tb_statusreport; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_statusreport (
    idstatusreport integer NOT NULL,
    idprojeto integer NOT NULL,
    datacompanhamento date,
    desatividadeconcluida text,
    desatividadeandamento text,
    desmotivoatraso text,
    desirregularidade text,
    idmarco integer NOT NULL,
    datmarcotendencia date,
    datfimprojetotendencia date,
    idcadastrador integer,
    datcadastro date,
    flaaprovado numeric(1,0),
    domcorrisco numeric(1,0),
    descontramedida text,
    desrisco text,
    domstatusprojeto integer NOT NULL,
    dataprovacao date,
    numpercentualconcluido numeric(5,2) DEFAULT 0,
    numpercentualprevisto numeric(5,2) DEFAULT 0,
    CONSTRAINT ckc_aprovacao CHECK ((((flaaprovado = (1)::numeric) AND (dataprovacao IS NOT NULL)) OR ((flaaprovado = (2)::numeric) AND (dataprovacao IS NULL)))),
    CONSTRAINT ckc_domcorrisco CHECK (((domcorrisco IS NULL) OR (domcorrisco = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric])))),
    CONSTRAINT ckc_flaaprovado CHECK (((flaaprovado IS NULL) OR (flaaprovado = ANY (ARRAY[(1)::numeric, (2)::numeric])))),
    CONSTRAINT ckc_statusreportprojeto CHECK (((domstatusprojeto IS NULL) OR (domstatusprojeto = ANY (ARRAY[1, 2, 3, 4, 5, 6, 7]))))
);


ALTER TABLE tb_statusreport OWNER TO postgres;

--
-- Name: tb_tipoacordo; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_tipoacordo (
    idtipoacordo integer NOT NULL,
    dsacordo character varying,
    idcadastrador integer,
    dtcadastro date
);


ALTER TABLE tb_tipoacordo OWNER TO postgres;

--
-- Name: tb_tipoavaliacao; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_tipoavaliacao (
    idtipoavaliacao integer NOT NULL,
    noavaliacao character varying(100)
);


ALTER TABLE tb_tipoavaliacao OWNER TO postgres;

--
-- Name: tb_tipocontramedida; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_tipocontramedida (
    idtipocontramedida integer NOT NULL,
    notipocontramedida character varying(50) NOT NULL,
    dstipocontramedida character varying(200)
);


ALTER TABLE tb_tipocontramedida OWNER TO postgres;

--
-- Name: tb_tipodocumento; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_tipodocumento (
    idtipodocumento integer NOT NULL,
    nomtipodocumento character varying(30),
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    flaativo character varying(1)
);


ALTER TABLE tb_tipodocumento OWNER TO postgres;

--
-- Name: tb_tipomudanca; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_tipomudanca (
    idtipomudanca integer NOT NULL,
    dsmudanca character varying(50) NOT NULL,
    idcadastrador integer NOT NULL,
    dtcadastro date NOT NULL
);


ALTER TABLE tb_tipomudanca OWNER TO postgres;

--
-- Name: tb_tiporisco; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_tiporisco (
    idtiporisco integer NOT NULL,
    dstiporisco character varying(40) NOT NULL,
    idcadastrador integer NOT NULL,
    dtcadastro date NOT NULL
);


ALTER TABLE tb_tiporisco OWNER TO postgres;

--
-- Name: tb_tiposituacaoprojeto; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_tiposituacaoprojeto (
    idtipo integer NOT NULL,
    nomtipo character(80) NOT NULL,
    desctipo text,
    flatiposituacao integer NOT NULL
);


ALTER TABLE tb_tiposituacaoprojeto OWNER TO postgres;

--
-- Name: tb_tratamento; Type: TABLE; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE TABLE tb_tratamento (
    idtratamento integer NOT NULL,
    dstratamento character varying(40) NOT NULL,
    idcadastrador integer NOT NULL,
    dtcadastro date NOT NULL
);


ALTER TABLE tb_tratamento OWNER TO postgres;

--
-- Data for Name: tb_acao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_acao (idacao, idobjetivo, nomacao, idcadastrador, datcadastro, flaativo, desacao, idescritorio, numseq) FROM stdin;
1	1	Ação estratégica/iniciativa 01	1	2015-03-05	S	Implementar a cultura de gestão e planejamento estratégicos	0	1
2	1	Ação estratégica/iniciativa 02	1	2015-03-05	S	Implementar a cultura de gestão de processos	0	2
3	1	Ação estratégica/iniciativa 03	1	2015-03-05	S	Implementar a cultura de gestão de projetos	0	3
4	1	Ação estratégica/iniciativa 04	1	2015-03-05	S	Desenvolver, sistematizar e implantar sistemas.	0	4
\.


--
-- Data for Name: tb_aceite; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_aceite (idaceite, desprodutoservico, desparecerfinal, idcadastrador, datcadastro) FROM stdin;
1	Descrição do serviço ou produto entregue e demais informações.	Declaração do(s) aprovador(res) da entrega pela aceitabilidade ou não do produto ou serviço entregue.	1	2015-12-30
2	produto ok	entrega ok	1	2016-01-21
\.


--
-- Data for Name: tb_aceiteatividadecronograma; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_aceiteatividadecronograma (idaceiteativcronograma, identrega, idprojeto, idaceite, idmarco, aceito, idpesaceitou, dataceitacao) FROM stdin;
1	22	1	1	\N	S	\N	\N
2	66	1	2	69	S	\N	\N
\.


--
-- Data for Name: tb_acordo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_acordo (idacordo, idacordopai, idtipoacordo, nomacordo, idresponsavelinterno, destelefoneresponsavelinterno, idsetor, idfiscal, destelefonefiscal, despalavrachave, desobjeto, desobservacao, datassinatura, datiniciovigencia, datfimvigencia, numprazovigencia, datatualizacao, datcadastro, idcadastrador, flarescindido, flasituacaoatual, numsiapro, descontatoexterno, idfiscal2, idfiscal3, idacordoespecieinstrumento, datpublicacao, descargofiscal, descaminho) FROM stdin;
1	1	1	Primeiro Acordo teste	1	(61) 9999-9999	1	1	(61) 9999-9999	palavra chave teste	adfajldjaljç		2015-07-01	2015-07-06	2015-12-31	120	2015-10-13	2015-07-10	1	N	1	08200009472201578	lgbd bo fgboh	2	3	1	2015-07-03	fiscal	ins_6f11ef461099f83fe6c21c815b6a7c1d.pdf
\.


--
-- Data for Name: tb_acordoentidadeexterna; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_acordoentidadeexterna (idacordo, identidadeexterna) FROM stdin;
1	1
\.


--
-- Data for Name: tb_acordoespecieinstrumento; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_acordoespecieinstrumento (idacordoespecieinstrumento, nomacordoespecieinstrumento, idcadastrador, datcadastro, flaativo) FROM stdin;
1	Acordo de cooperação	1	2015-07-10	S
\.


--
-- Data for Name: tb_agenda; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_agenda (idagenda, desassunto, datagenda, desagenda, idcadastrador, datcadastro, hragendada, deslocal, flaenviaemail, idescritorio) FROM stdin;
1	reuniao de planejamento estrategico	2016-02-03	reuniao de planejamento estrategico com a presença de partes interessadas do órgão. 1	1	2016-02-03	1969-12-31 21:00:00	Brasilia	2	0
\.


--
-- Data for Name: tb_aquisicao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_aquisicao (idaquisicao, idprojeto, identrega, descontrato, desfornecedor, numvalor, datprazoaquisicao, idcadastrador, datcadastro, numquantidade, desaquisicao) FROM stdin;
\.


--
-- Data for Name: tb_ata; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_ata (idata, idprojeto, desassunto, datata, deslocal, desparticipante, despontodiscutido, desdecisao, despontoatencao, idcadastrador, datcadastro, desproximopasso, hrreuniao) FROM stdin;
22	1	Reunião com consultoria	2015-08-05	PMO01	Participante A\r\nParticipante B\r\nParticpante C	Uso dos formulários da MGP-PF e artefatos Gepnet para condução do monitoramento do planejamento estratégico	Aguardar recursos	Ponto de atenção	1	2015-08-05	Próximos passos	14:30:00
23	1	Dias de finados	2015-11-02	Local 2	Fulano 1\r\nFulano 2\r\nFulano 3\r\nFulano 4	Ponto 1\r\nPonto 2\r\nPonto 3	Decisão ponto 1\r\nDecisão ponto 2\r\nDecisão ponto 3 ....	Ponto de atenção 1 ...	1	2015-11-01	Próximo passo 1\r\nPróximo passo 2\r\nPróximo passo 3	23:59:00
\.


--
-- Data for Name: tb_atividade; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_atividade (idatividade, nomatividade, desatividade, idcadastrador, idresponsavel, datcadastro, datatualizacao, datinicio, datfimmeta, datfimreal, flacontinua, numpercentualconcluido, flacancelada, idescritorio) FROM stdin;
1	Atividade 1	j lk\nf jdfg pjo okjo ojb	1	1	2015-03-07	2015-03-07	2015-01-01	2015-01-15	2015-01-22	2	100	2	0
\.


--
-- Data for Name: tb_atividadecronograma; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_atividadecronograma (idatividadecronograma, idprojeto, idgrupo, nomatividadecronograma, domtipoatividade, desobs, datcadastro, idmarcoanterior, numdias, vlratividadebaseline, vlratividade, numfolga, descriterioaceitacao, idelementodespesa, idcadastrador, idparteinteressada, datiniciobaseline, datfimbaseline, flaaquisicao, flainformatica, flacancelada, datinicio, datfim, numpercentualconcluido, numdiasbaseline, numdiasrealizados) FROM stdin;
76	1	75	2.1.1 Atividade inicial	3	\N	2016-01-21 10:07:09.953417+00	\N	\N	0	0	1	\N	\N	\N	367	2016-03-11	2016-03-21	N	\N	N	2016-03-04	2016-03-14	10.00	\N	10
7	2	2	1.1.5 Atividade	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	372	2016-06-21	2016-06-23	N	\N	N	2016-06-21	2016-06-23	100.00	\N	2
8	2	2	1.1.6 Atividade marco final	4	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	368	2016-06-24	2016-06-25	N	\N	N	2016-06-24	2016-06-25	0.00	\N	1
10	2	9	1.2.1 Elaborar minuta de Termo de Encerramento - TEP	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	368	2016-06-26	2016-07-06	N	\N	N	2016-06-26	2016-07-06	20.00	\N	10
75	1	74	2.1 Logistica	2	Atividades de monitoramento e medição do desempenho do projeto (custos, tempo, qualidade) consolidadas através de reuniões e relatórios de acompanhamento do andamento do projeto. Utilizar diário do gerente, atas de reunião, relatórios de situação (acompanhamento periódico), registro de lições aprendidas e arquivamento de documentos digitais no RUD do projeto. Acompanhar e relatar a situação e evolução dos riscos do projeto.	2016-01-21 10:07:09.953417+00	\N	\N	0	0	0	Uso adequado das ferramentas do GEPNET2. Relatórios de situação gravados no GEPNET2. Atas de reunião gravadas. Riscos monitorados na aba riscos do GEPNET2.	\N	\N	367	2016-03-11	2016-03-29	\N	\N	N	2016-03-04	2016-03-29	6.00	\N	0
11	2	9	1.2.2 TEP validado e assinado	4	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	368	2016-07-07	2016-07-12	N	\N	N	2016-07-07	2016-07-12	0.00	\N	5
14	2	13	2.1.1 Atividade inicial	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	367	2016-07-13	2016-07-23	N	\N	N	2016-07-13	2016-07-23	0.00	\N	10
15	2	13	2.1.2 Atividade	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	367	2016-07-24	2016-07-29	N	\N	N	2016-07-24	2016-07-29	0.00	\N	5
5	2	2	1.1.3 Atividade marco 1	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	372	2016-06-20	2016-06-20	N	\N	N	2016-06-20	2016-06-20	100.00	\N	10
6	2	2	1.1.4 Atividade	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	372	2016-06-20	2016-06-20	N	\N	S	2016-06-20	2016-06-20	10.00	\N	5
4	2	2	1.1.2 Atividade	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	372	2016-06-20	2016-06-20	N	\N	N	2016-06-20	2016-06-20	100.00	\N	7
77	1	75	2.1.2 Atividade	3	\N	2016-01-21 10:07:09.953417+00	\N	\N	0	0	1	\N	\N	\N	367	2016-03-22	2016-03-27	N	\N	N	2016-03-15	2016-03-20	0.00	\N	5
78	1	75	2.1.3 Atividade marco	3	\N	2016-01-21 10:07:09.953417+00	\N	\N	0	0	1	\N	\N	\N	367	2016-03-28	2016-03-29	N	\N	N	2016-03-21	2016-03-22	0.00	\N	1
2	2	1	1.1 Monitoramento	2	Atividades de monitoramento e medição do desempenho do projeto (custos, tempo, qualidade) consolidadas através de reuniões e relatórios de acompanhamento do andamento do projeto. Utilizar diário do gerente, atas de reunião, relatórios de situação (acompanhamento periódico), registro de lições aprendidas e arquivamento de documentos digitais no RUD do projeto. Acompanhar e relatar a situação e evolução dos riscos do projeto.	2015-12-30 16:57:08.431855+00	\N	\N	0	0	0	Uso adequado das ferramentas do GEPNET2. Relatórios de situação gravados no GEPNET2. Atas de reunião gravadas. Riscos monitorados na aba riscos do GEPNET2.	\N	\N	371	2016-06-10	2016-06-25	\N	\N	N	2016-06-10	2016-06-25	93.00	\N	0
3	2	2	1.1.1 Atividade inicial	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	371	2016-06-10	2016-06-20	N	\N	N	2016-06-10	2016-06-20	100.00	\N	10
9	2	1	1.2 Encerramento	2	Atividades de encerramento do projeto que envolvem: finalização dos termos de aceite, registro e revisão das lições aprendidas, encerramento dos contratos de aquisição e prestação de serviços utilizados para o projeto, arquivamento dos documentos em formato digital no RUD, elaboração, revisão e assinatura do TEP.	2015-12-30 16:57:08.431855+00	\N	\N	0	0	0	Termos de aceite emitidos, assinados, digitalizados e arquivados no RUD do projeto.\r\nLições aprendidas registradas, revisadas e aceitas pelo Patrocinador.\r\nTermo de Encerramento do Projeto (TEP) aprovado, assinado, digitalizado e arquivado no RUD do projeto.	\N	\N	367	2016-06-26	2016-07-12	\N	\N	N	2016-06-26	2016-07-12	13.00	\N	0
1	2	\N	1. GERENCIAMENTO DO PROJETO	1	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	0	\N	\N	\N	\N	2016-06-10	2016-07-12	\N	\N	\N	2016-06-10	2016-07-12	53.00	\N	0
81	1	79	2.2.2 Atividade	3	\N	2016-01-21 10:07:09.953417+00	\N	\N	0	0	1	\N	\N	\N	367	2016-04-15	2016-04-20	N	\N	N	2016-04-15	2016-04-20	10.00	\N	5
79	1	74	2.2 Logistica	2	Atividades de monitoramento e medição do desempenho do projeto (custos, tempo, qualidade) consolidadas através de reuniões e relatórios de acompanhamento do andamento do projeto. Utilizar diário do gerente, atas de reunião, relatórios de situação (acompanhamento periódico), registro de lições aprendidas e arquivamento de documentos digitais no RUD do projeto. Acompanhar e relatar a situação e evolução dos riscos do projeto.	2016-01-21 10:07:09.953417+00	\N	\N	0	0	0	Uso adequado das ferramentas do GEPNET2. Relatórios de situação gravados no GEPNET2. Atas de reunião gravadas. Riscos monitorados na aba riscos do GEPNET2.	\N	\N	367	2016-04-04	2016-05-01	\N	\N	N	2016-04-04	2016-05-01	26.00	\N	0
82	1	79	2.2.3 Atividade marco	4	\N	2016-01-21 10:07:09.953417+00	\N	\N	0	0	1	\N	\N	\N	367	2016-04-21	2016-05-01	N	\N	N	2016-04-21	2016-05-01	0.00	\N	10
74	1	\N	2. LOGISTICA	1	\N	2016-01-21 10:07:09.953417+00	\N	\N	0	0	0	\N	\N	\N	\N	2016-03-11	2016-05-01	\N	\N	\N	2016-03-04	2016-05-01	16.00	\N	0
18	2	17	2.2.1 Atividade inicial	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	367	2016-08-04	2016-08-14	N	\N	N	2016-08-04	2016-08-14	60.00	\N	10
19	2	17	2.2.2 Atividade	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	367	2016-08-15	2016-08-20	N	\N	N	2016-08-15	2016-08-20	10.00	\N	5
22	2	21	3.2 Entrega	2	Atividades de monitoramento e medição do desempenho do projeto (custos, tempo, qualidade) consolidadas através de reuniões e relatórios de acompanhamento do andamento do projeto. Utilizar diário do gerente, atas de reunião, relatórios de situação (acompanhamento periódico), registro de lições aprendidas e arquivamento de documentos digitais no RUD do projeto. Acompanhar e relatar a situação e evolução dos riscos do projeto.	2015-12-30 16:57:08.431855+00	\N	\N	0	0	0	Uso adequado das ferramentas do GEPNET2. Relatórios de situação gravados no GEPNET2. Atas de reunião gravadas. Riscos monitorados na aba riscos do GEPNET2.	\N	\N	371	2016-10-14	2016-10-14	\N	\N	N	2016-10-14	2016-10-14	14.00	\N	0
24	2	22	3.2.2 Atividade	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	371	2016-10-15	2016-10-18	N	\N	N	2016-10-15	2016-10-18	10.00	\N	3
25	2	22	3.2.3 Atividade marco	4	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	371	2016-10-19	2016-10-29	N	\N	N	2016-10-19	2016-10-29	0.00	\N	10
80	1	79	2.2.1 Atividade inicial	3	\N	2016-01-21 10:07:09.953417+00	\N	\N	0	0	1	\N	\N	\N	367	2016-04-04	2016-04-14	N	\N	N	2016-04-04	2016-04-14	60.00	\N	10
23	2	22	3.2.1 Atividade inicial	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	371	2016-10-04	2016-10-14	N	\N	N	2016-10-04	2016-10-14	30.00	\N	10
20	2	17	2.2.3 Atividade marco	4	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	367	2016-08-21	2016-08-31	N	\N	N	2016-08-21	2016-08-31	0.00	\N	10
42	1	\N	3. GRUPO EXECUÇÃO	1	\N	2015-10-31 12:56:21.075123+00	\N	\N	0	0	0	\N	\N	\N	\N	2016-03-11	2016-07-09	\N	\N	\N	2016-03-11	2016-07-09	25.50	\N	0
17	2	12	2.2 Logistica	2	Atividades de monitoramento e medição do desempenho do projeto (custos, tempo, qualidade) consolidadas através de reuniões e relatórios de acompanhamento do andamento do projeto. Utilizar diário do gerente, atas de reunião, relatórios de situação (acompanhamento periódico), registro de lições aprendidas e arquivamento de documentos digitais no RUD do projeto. Acompanhar e relatar a situação e evolução dos riscos do projeto.	2015-12-30 16:57:08.431855+00	\N	\N	0	0	0	Uso adequado das ferramentas do GEPNET2. Relatórios de situação gravados no GEPNET2. Atas de reunião gravadas. Riscos monitorados na aba riscos do GEPNET2.	\N	\N	367	2016-08-29	2016-09-25	\N	\N	N	2016-08-29	2016-09-25	26.00	\N	0
12	2	\N	2. LOGISTICA	1	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	0	\N	\N	\N	\N	2016-08-07	2016-09-25	\N	\N	\N	2016-08-07	2016-09-25	\N	\N	0
27	2	26	3.1.1 Atividade inicial	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	371	2016-08-31	2016-09-10	N	\N	N	2016-08-31	2016-09-10	100.00	\N	10
67	1	66	1.1.1 Atividade inicial	3	\N	2015-11-11 01:28:51.892594+00	\N	\N	0	0	1	\N	\N	\N	368	2016-01-11	2016-01-21	N	\N	N	2016-01-11	2016-02-05	100.00	\N	25
63	1	61	3.1.2 Atividade	3	\N	2015-11-01 20:34:52.68004+00	\N	\N	0	0	1	\N	\N	\N	368	2016-06-02	2016-06-08	N	\N	N	2016-06-02	2016-06-08	0.00	\N	6
26	2	21	3.1 Entrega	2	Atividades de monitoramento e medição do desempenho do projeto (custos, tempo, qualidade) consolidadas através de reuniões e relatórios de acompanhamento do andamento do projeto. Utilizar diário do gerente, atas de reunião, relatórios de situação (acompanhamento periódico), registro de lições aprendidas e arquivamento de documentos digitais no RUD do projeto. Acompanhar e relatar a situação e evolução dos riscos do projeto.	2015-12-30 16:57:08.431855+00	\N	\N	0	0	0	Uso adequado das ferramentas do GEPNET2. Relatórios de situação gravados no GEPNET2. Atas de reunião gravadas. Riscos monitorados na aba riscos do GEPNET2.	\N	\N	371	2016-08-31	2016-09-28	\N	\N	N	2016-08-31	2016-09-28	38.00	\N	0
62	1	61	3.1.1 Atividade inicial	3	\N	2015-11-01 20:34:52.68004+00	\N	\N	0	0	1	\N	\N	\N	367	2016-05-22	2016-06-01	N	\N	N	2016-05-22	2016-06-01	100.00	\N	10
21	2	\N	3. GRUPO EXECUÇÃO	1	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	0	\N	\N	\N	\N	2016-08-31	2016-10-14	\N	\N	\N	2016-08-31	2016-10-14	26.00	\N	0
16	2	13	2.1.3 Atividade marco	4	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	367	2016-07-30	2016-08-03	N	\N	N	2016-07-30	2016-08-03	0.00	\N	4
64	1	61	3.1.3 Atividade marco	4	\N	2015-11-01 20:34:52.68004+00	\N	\N	0	0	1	\N	\N	\N	368	2016-06-09	2016-06-19	N	\N	N	2016-06-09	2016-06-19	0.00	\N	10
28	2	26	3.1.2 Atividade	3	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	368	2016-09-11	2016-09-17	N	\N	N	2016-09-11	2016-09-17	0.00	\N	6
29	2	26	3.1.3 Atividade marco	4	\N	2015-12-30 16:57:08.431855+00	\N	\N	0	0	1	\N	\N	\N	371	2016-09-18	2016-09-28	N	\N	N	2016-09-18	2016-09-28	0.00	\N	10
13	2	12	2.1 Logistica	2	Atividades de monitoramento e medição do desempenho do projeto (custos, tempo, qualidade) consolidadas através de reuniões e relatórios de acompanhamento do andamento do projeto. Utilizar diário do gerente, atas de reunião, relatórios de situação (acompanhamento periódico), registro de lições aprendidas e arquivamento de documentos digitais no RUD do projeto. Acompanhar e relatar a situação e evolução dos riscos do projeto.	2015-12-30 16:57:08.431855+00	\N	\N	0	0	0	Uso adequado das ferramentas do GEPNET2. Relatórios de situação gravados no GEPNET2. Atas de reunião gravadas. Riscos monitorados na aba riscos do GEPNET2.	\N	\N	367	2016-08-07	2016-08-28	\N	\N	N	2016-08-07	2016-08-28	0.00	\N	0
68	1	66	1.1.2 Atividade	3	\N	2015-11-11 01:28:51.892594+00	\N	\N	0	0	2	\N	\N	\N	367	2016-01-23	2016-01-28	N	\N	N	2016-02-07	2016-02-12	0.00	\N	5
69	1	66	1.1.3 Atividade marco	4	\N	2015-11-11 01:28:51.892594+00	\N	\N	0	0	1	\N	\N	\N	367	2016-01-29	2016-01-30	N	\N	N	2016-02-13	2016-02-14	0.00	\N	1
71	1	70	1.2.1 Atividade inicial	3	\N	2015-11-11 01:28:51.892594+00	\N	\N	0	0	1	\N	\N	\N	367	2016-02-05	2016-02-15	N	\N	N	2016-02-05	2016-02-15	60.00	\N	10
66	1	65	1.1 Entrega 1	2	Atividades de monitoramento e medição do desempenho do projeto (custos, tempo, qualidade) consolidadas através de reuniões e relatórios de acompanhamento do andamento do projeto. Utilizar diário do gerente, atas de reunião, relatórios de situação (acompanhamento periódico), registro de lições aprendidas e arquivamento de documentos digitais no RUD do projeto. Acompanhar e relatar a situação e evolução dos riscos do projeto.	2015-11-11 01:28:51.892594+00	\N	\N	0	0	0	Uso adequado das ferramentas do GEPNET2. Relatórios de situação gravados no GEPNET2. Atas de reunião gravadas. Riscos monitorados na aba riscos do GEPNET2.	\N	\N	367	2016-01-11	2016-01-30	\N	\N	N	2016-01-11	2016-02-05	80.00	\N	0
65	1	\N	1. INICIAÇÃO	1	\N	2015-11-11 01:28:51.892594+00	\N	\N	0	0	0	\N	\N	\N	\N	2016-01-11	2016-02-15	\N	\N	\N	2016-02-05	2016-02-05	53.00	\N	0
72	1	70	1.2.2 Atividade	3	\N	2015-11-11 01:28:51.892594+00	\N	\N	0	0	1	\N	\N	\N	367	2016-02-16	2016-02-21	N	\N	N	2016-02-16	2016-02-21	10.00	\N	5
73	1	70	1.2.3 Atividade marco	4	\N	2015-11-11 01:28:51.892594+00	\N	\N	0	0	1	\N	\N	\N	367	2016-02-22	2016-03-03	N	\N	N	2016-02-22	2016-03-03	0.00	\N	10
44	1	43	3.2.1 Atividade inicial	3	\N	2015-10-31 12:56:21.075123+00	\N	\N	0	0	1	\N	\N	\N	367	2016-06-19	2016-07-09	N	\N	N	2016-06-19	2016-07-09	20.00	\N	20
70	1	65	1.2 Entrega 2	2	Atividades de monitoramento e medição do desempenho do projeto (custos, tempo, qualidade) consolidadas através de reuniões e relatórios de acompanhamento do andamento do projeto. Utilizar diário do gerente, atas de reunião, relatórios de situação (acompanhamento periódico), registro de lições aprendidas e arquivamento de documentos digitais no RUD do projeto. Acompanhar e relatar a situação e evolução dos riscos do projeto.	2015-11-11 01:28:51.892594+00	\N	\N	0	0	0	Uso adequado das ferramentas do GEPNET2. Relatórios de situação gravados no GEPNET2. Atas de reunião gravadas. Riscos monitorados na aba riscos do GEPNET2.	\N	\N	367	2016-02-15	2016-02-15	\N	\N	N	2016-02-15	2016-02-15	26.00	\N	0
45	1	43	3.2.2 Atividade	3	\N	2015-10-31 12:56:21.075123+00	\N	\N	0	0	1	\N	\N	\N	367	2016-07-10	2016-07-13	N	\N	N	2016-07-10	2016-07-13	10.00	\N	3
46	1	43	3.2.3 Atividade marco	4	\N	2015-10-31 12:56:21.075123+00	\N	\N	0	0	1	\N	\N	\N	367	2016-07-14	2016-07-24	N	\N	N	2016-07-14	2016-07-24	0.00	\N	10
43	1	42	3.2 Entrega	2	Atividades de monitoramento e medição do desempenho do projeto (custos, tempo, qualidade) consolidadas através de reuniões e relatórios de acompanhamento do andamento do projeto. Utilizar diário do gerente, atas de reunião, relatórios de situação (acompanhamento periódico), registro de lições aprendidas e arquivamento de documentos digitais no RUD do projeto. Acompanhar e relatar a situação e evolução dos riscos do projeto.	2015-10-31 12:56:21.075123+00	\N	\N	0	0	0	Uso adequado das ferramentas do GEPNET2. Relatórios de situação gravados no GEPNET2. Atas de reunião gravadas. Riscos monitorados na aba riscos do GEPNET2.	\N	\N	368	2016-03-11	2016-07-09	\N	\N	N	2016-03-11	2016-07-09	13.00	\N	0
61	1	42	3.1 Entrega	2	Atividades de monitoramento e medição do desempenho do projeto (custos, tempo, qualidade) consolidadas através de reuniões e relatórios de acompanhamento do andamento do projeto. Utilizar diário do gerente, atas de reunião, relatórios de situação (acompanhamento periódico), registro de lições aprendidas e arquivamento de documentos digitais no RUD do projeto. Acompanhar e relatar a situação e evolução dos riscos do projeto.	2015-11-01 20:34:52.68004+00	\N	\N	0	0	0	Uso adequado das ferramentas do GEPNET2. Relatórios de situação gravados no GEPNET2. Atas de reunião gravadas. Riscos monitorados na aba riscos do GEPNET2.	\N	\N	367	2016-05-22	2016-06-19	\N	\N	N	2016-05-22	2016-06-19	38.00	\N	0
\.


--
-- Data for Name: tb_atividadepredecessora; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_atividadepredecessora (idatividadepredecessora, idprojeto, idatividade) FROM stdin;
21	1	67
62	1	63
63	1	64
44	1	45
45	1	46
6	2	7
7	2	8
8	2	10
10	2	11
11	2	14
14	2	15
15	2	16
16	2	18
18	2	19
27	2	28
67	1	65
67	1	68
68	1	69
71	1	70
71	1	72
72	1	73
76	1	77
77	1	78
80	1	81
81	1	82
28	2	29
23	2	22
23	2	24
24	2	25
\.


--
-- Data for Name: tb_comunicacao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_comunicacao (idcomunicacao, idprojeto, desinformacao, desinformado, desorigem, desfrequencia, destransmissao, desarmazenamento, idcadastrador, datcadastro, nomresponsavel, idresponsavel) FROM stdin;
3	2	Relatórios periódicos do andamento do projeto	Equipe do projeto	Status report Gepnet	Mensal	E-mail e videoconferência	RUD e e-mail	1	2015-11-01	Usuario 02	372
2	1	Termo de abertura do projeto - TAP	Demandante e Patrocinador e Gerente	Formulário TAP da MGP-PF ou impressão Gepnet2	Na formulação e sempre que houver alteração	E-mail, Gepnet2 e presencial	RUD projeto	1	2015-08-05	Usuario 01	367
\.


--
-- Data for Name: tb_contramedida; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_contramedida (idcontramedida, idrisco, descontramedida, datprazocontramedida, datprazocontramedidaatraso, domstatuscontramedida, flacontramedidaefetiva, desresponsavel, idcadastrador, datcadastro, idtipocontramedida, nocontramedida) FROM stdin;
1	1	Realizar reunião de alinhamento de prioridades reduzir a probabilidade de cortes orçamentários no projeto.	2015-08-05	2015-08-14	5	2	Patrocinador do projeto	1	2015-08-05	3	Reunião com financiadores
2	2	Executar capacitação de pessoal no local da obra	2015-11-27	2015-11-27	4	2	Gestor do projeto	1	2015-11-01	2	Executar capacitação de pessoal no local da obra
\.


--
-- Data for Name: tb_diariobordo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_diariobordo (iddiariobordo, idprojeto, datdiariobordo, domreferencia, domsemafaro, desdiariobordo, idcadastrador, datcadastro, idalterador) FROM stdin;
1	1	2015-04-01	Observação	2	CONSIDERANDO a necessidade de atualizar o Plano Estratégico 2010/2022, de modo a adequá-lo à nova re	1	2015-04-08	\N
2	1	2015-04-01	Ponto de Atenção	2	CONSIDERANDO a necessidade de direcionar as ações estratégicas ao alcance dos objetivos instituciona	1	2015-04-08	\N
3	1	2015-04-06	Reunião	3	CONSIDERANDO o esforço conjunto de se praticar gestão moderna, dinâmica e participativa, de forma qu	1	2015-04-08	\N
\.


--
-- Data for Name: tb_documento; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_documento (iddocumento, idescritorio, nomdocumento, idtipodocumento, descaminho, datdocumento, desobs, idcadastrador, datcadastro, flaativo) FROM stdin;
1	\N	Documento de teste 1	6	file_pdf_509cdc5accb7363a7ea2ec4d9cf8343b.pdf	2015-11-02	dafafa	\N	2015-11-02	S
\.


--
-- Data for Name: tb_elementodespesa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_elementodespesa (idelementodespesa, idoficial, nomelementodespesa, idcadastrador, datcadastro, numseq) FROM stdin;
17	17	Outras despesas variáveis - pessoal militar	1	2015-06-02	16
29	29	Distribuição de resultado de empresas estatais dependentes	1	2015-06-02	28
1	1	Aposentadorias, Reserva Remunerada e Reformas	1	2015-06-02	1
3	3	Pensões	1	2015-06-02	2
4	4	Contratação por tempo determinado	1	2015-06-02	3
5	5	Outros benefícios previdenciários	1	2015-06-02	4
6	6	Benefício mensal ao deficiente e ao idoso	1	2015-06-02	5
7	7	Outros benefícios assistenciais	1	2015-06-02	6
8	8	Contribuição a entidades fechadas de previdência	1	2015-06-02	7
9	9	Salário-família	1	2015-06-02	8
10	10	Outros benefícios de natureza social	1	2015-06-02	9
11	11	Vencimentos e Vantagens fixas - pessoal civil	1	2015-06-02	10
12	12	Vencimentos e vantagens fixas - pessoal militar	1	2015-06-02	11
13	13	Obrigações patronais	1	2015-06-02	12
14	14	Diárias - civil	1	2015-06-02	13
15	15	Diárias - militar	1	2015-06-02	14
16	16	Outras despesas variáveis - pessoal civil	1	2015-06-02	15
18	18	Auxílio financeiro a estudantes	1	2015-06-02	17
19	19	Auxílio fardamento	1	2015-06-02	18
20	20	Auxílio financeiro a pesquisadores	1	2015-06-02	19
21	21	Juros sobre a dívida por contrato	1	2015-06-02	20
22	22	Outros encargos sobrea dívida por contrato	1	2015-06-02	21
23	23	Juros, deságios e descontos da dívida mobiliária	1	2015-06-02	22
24	24	Outros encargos sobre a dívida mobiliária	1	2015-06-02	23
25	25	Encargos sobre operações de crédito por antecipação da receita	1	2015-06-02	24
26	26	Obrigações decorrentes de política monetária	1	2015-06-02	25
27	27	Encargos pela honra de avais, garantias, seguros e similares	1	2015-06-02	26
28	28	Remuneração de cotas de fundos autáquicos	1	2015-06-02	27
39	39	Outros serviços de terceiros - pessoa jurídica	1	2015-06-02	38
30	30	Material de consumo	1	2015-06-02	29
31	31	Premiações culturais, artísticas, científicas, desportivas e outras	1	2015-06-02	30
32	32	Material, bem ou serviço para distribuição gratuita	1	2015-06-02	31
33	33	Passagens e despesas com locomoção	1	2015-06-02	32
34	34	Outras despesas de pessoal decorrentes de contratos de terceirização	1	2015-06-02	33
35	35	Serviços de consultoria	1	2015-06-02	34
36	36	Outros serviços de terceiros - pessoa física	1	2015-06-02	35
37	37	Locação de mão-de-obra	1	2015-06-02	36
38	38	Arrendamento mercantil	1	2015-06-02	37
41	41	Contribuições	1	2015-06-02	39
42	42	Auxílios	1	2015-06-02	40
43	43	Subvenções sociais	1	2015-06-02	41
45	45	Subvenções econômicas	1	2015-06-02	42
46	46	Auxílio-alimentação	1	2015-06-02	43
47	47	Obrigações tributárias e contributivas	1	2015-06-02	44
48	48	Outros auxílios financeiros a pessoas físicas	1	2015-06-02	45
49	49	Auxílio-transporte	1	2015-06-02	46
51	51	Obras e instalações	1	2015-06-02	47
52	52	Equipamentos e material permanente	1	2015-06-02	48
61	61	Aquisição de imóveis	1	2015-06-02	49
62	62	Aquisição de produtos para revenda	1	2015-06-02	50
63	63	Aquisição de títulos de crédito	1	2015-06-02	51
64	64	Aquisição de títulos representativos de capital já integralizado	1	2015-06-02	52
65	65	Constituição ou aumento de capital de empresas	1	2015-06-02	53
66	66	Concessão de empréstimos e financiamentos	1	2015-06-02	54
67	67	Depósitos compulsórios	1	2015-06-02	55
71	71	Principal da dívida contratual resgatado	1	2015-06-02	56
72	72	Principal da dívida mobiliária resgatado	1	2015-06-02	57
73	73	Correção monetária ou cambial da dívida contratual resgatada	1	2015-06-02	58
74	74	Correção monetária ou cambial da dívida mobiliária resgatada	1	2015-06-02	59
75	75	Correção monetária da dívida de operações de crédito por antecipação da receita	1	2015-06-02	60
76	76	Principal corrigido da dívida mobiliária refinanciado	1	2015-06-02	61
77	77	Principal corrigido da dívida contratual refinanciado	1	2015-06-02	62
81	81	Distribuição constitucional ou legal de receitas	1	2015-06-02	63
91	91	Sentenças judiciais	1	2015-06-02	64
92	92	Despesas de exercícios anteriores	1	2015-06-02	65
93	93	Indenizações e restituições	1	2015-06-02	66
94	94	Indenizações e restituições trabalhistas	1	2015-06-02	67
95	95	Indenização pela execução de trabalhos de campo	1	2015-06-02	68
96	96	Ressarcimento de despesas de pessoal requisitado	1	2015-06-02	69
97	97	Aporte para cobertura do déficit atuarial do RPPS	1	2015-06-02	70
99	99	A classificar	1	2015-06-02	71
\.


--
-- Data for Name: tb_entidadeexterna; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_entidadeexterna (identidadeexterna, nomentidadeexterna, idcadastrador, datcadastro) FROM stdin;
1	MJ - Ministério da Justiça	1	2015-07-10
2	Ministerio da Agricultura	1	2015-11-18
\.


--
-- Data for Name: tb_escritorio; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_escritorio (idescritorio, nomescritorio, idcadastrador, datcadastro, flaativo, idresponsavel1, idresponsavel2, idescritoriope, nomescritorio2, desemail, numfone) FROM stdin;
0	PMO 0	1	2010-02-01	S	1	2	0	PMO 0	pmo0@gepnet2.gov	(61) 9999-99999
1	PMO 1	1	2010-03-08	S	1	2	0	PMO 1	pmo1@projetos.com	(61) 9999-99999
2	PMO 2	1	2010-03-08	S	1	2	0	PMO 2	pmo2@gepnet2.gov	(61) 9999-99999
\.


--
-- Data for Name: tb_etapa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_etapa (idetapa, dsetapa, idcadastrador, dtcadastro) FROM stdin;
1	Planejamento	1	2014-01-29
2	Formalização	1	2014-01-29
3	Execução	1	2014-01-29
\.


--
-- Data for Name: tb_evento; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_evento (idevento, nomevento, desevento, desobs, idcadastrador, idresponsavel, datcadastro, datinicio, datfim, uf) FROM stdin;
1	FCC FIFA 2014 - MG	f kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdf f kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff	kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff kjhbd blsdbhdh bh sdlkhslkbjhsdkljhdlksddf lddljlkdklj  hdff	1	1	2015-06-10	2014-06-08	2015-07-18	MG
\.


--
-- Data for Name: tb_eventoavaliacao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_eventoavaliacao (ideventoavaliacao, idevento, desdestaqueservidor, desobs, idavaliador, idavaliado, datcadastro, numpontualidade, numordens, numrespeitochefia, numrespeitocolega, numurbanidade, numequilibrio, numcomprometimento, numesforco, numtrabalhoequipe, numauxiliouequipe, numaceitousugestao, numconhecimentonorma, numalternativaproblema, numiniciativa, numtarefacomplexa, numnotaavaliador, nummedia, nummediafinal, numtotalavaliado, idtipoavaliacao) FROM stdin;
1	1	asdfçoaj açlj açlv çlkv	asfasl hlvhaviha hah jh	1	1	2015-07-10	10	10	10	10	10	10	10	10	10	10	10	10	10	10	10	10	10	10	15	1
2	1			11	5	2015-11-14	10	8	8	10	10	8	10	8	8	10	10	10	10	8	10	10	9.19999999999999929	9.59999999999999964	15	3
\.


--
-- Data for Name: tb_frase; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_frase (idfrase, domtipofrase, flaativo, datcadastro, idescritorio, idcadastrador, desfrase) FROM stdin;
1	1	S	2014-03-18	0	1	2015.1 - Informe a classe do seu cargo.
5	5	S	2014-03-18	0	1	2016.1 Informe o seu número favorito, de 1 a 1000:
11	1	S	2014-08-22	0	1	2014.P1.Q2 - Informa a sua LOTAÇÃO ATUAL.
10	1	S	2014-08-22	0	1	2014.P1.Q1 - Informe há quanto tempo você atua no Órgão.
12	2	S	2014-08-28	0	1	2014.P1.Q3 - Informe a cidade-sede do evento Copa do Mundo FIFA 2014 em que você atuou.
13	1	S	2014-08-28	0	1	2014.P1.Q4 - Informe a sua área de atuação no evento Copa do Mundo FIFA 2014.
8	3	S	2014-04-14	0	1	2015.2 O que você entende por "Planejamento estratégico"? Descreva
3	1	S	2014-03-18	0	1	2015.3 Pergunta - O que é, o que é? Tem olhos vermelhos, orelhas grandes e é branquinho como a neve?
16	1	S	2015-04-01	0	1	2015.4 - Informe a sua área de atuação nas OLIMPÍADAS 2016.
14	4	S	2014-10-02	0	1	2015.7 Informe o nome do último livro que você leu?
7	1	S	2014-04-14	0	1	2016.2 Informe o objetivo estratégico do Órgão que você considera mais relevante.
2	3	S	2014-03-18	0	1	2016.3 Opine sobre a eficiência dos processos organizacionais de sua unidade.
9	1	S	2014-07-21	0	1	D1.F6 - Atribua uma AVALIAÇÃO GERAL em relação aos serviços utilizados por você no Órgão.
15	1	S	2015-02-20	0	1	D1.F6 - Avalia a qualidade das INSTALAÇÕES FÍSICAS dessa Unidade do Órgão.
6	1	S	2014-04-02	0	1	D1.F6 - Avalie a CONSERVAÇÃO E LIMPEZA do local de atendimento ao público de seu Órgão.
4	7	S	2014-03-18	0	1	D1.F7 - Qual a sua avaliação da comunicação interna no Órgão?
\.


--
-- Data for Name: tb_frase_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_frase_pesquisa (idfrasepesquisa, idcadastrador, domtipofrase, flaativo, datcadastro, idescritorio, desfrase) FROM stdin;
53	1	1	S	2015-04-01	0	2015.4 - Informe a sua área de atuação nas OLIMPÍADAS 2016.
1	1	3	S	2014-03-18	0	Pergunta - Teste 2
2	1	2	S	2014-03-18	0	Pergunta - Teste 3
3	1	5	S	2014-03-18	0	Pergunta 2
4	1	1	S	2014-04-02	0	A CARGA HORÁRIA TOTAL utilizada para o desenvolvimento do evento de capacitação foi:
5	1	1	S	2014-04-14	0	Qual a sua impressão sobre a CGTI
6	1	3	S	2014-04-14	0	O que vc achou sobre o parque de servidores da CGTI? Descreva
7	1	2	S	2014-03-18	0	Pergunta - Teste 3
8	1	1	S	2014-04-02	0	A CARGA HORÁRIA TOTAL utilizada para o desenvolvimento do evento de capacitação foi:
9	1	1	S	2014-04-02	0	A CARGA HORÁRIA TOTAL utilizada para o desenvolvimento do evento de capacitação foi:
10	1	3	S	2014-03-18	0	Pergunta - Teste 2
11	1	2	S	2014-03-18	0	Pergunta - Teste 3
12	1	5	S	2014-03-18	0	Pergunta 2
13	1	1	S	2014-04-02	0	A CARGA HORÁRIA TOTAL utilizada para o desenvolvimento do evento de capacitação foi:
14	1	3	S	2014-04-14	0	O que vc achou sobre o parque de servidores da CGTI? Descreva
15	1	3	S	2014-03-18	0	Pergunta - Teste 2
16	1	2	S	2014-03-18	0	Pergunta - Teste 3
17	1	7	S	2014-03-18	0	Teste 1
18	1	5	S	2014-03-18	0	Pergunta 2
19	1	1	S	2014-04-02	0	A CARGA HORÁRIA TOTAL utilizada para o desenvolvimento do evento de capacitação foi:
20	1	1	S	2014-04-14	0	Qual a sua impressão sobre a CGTI
21	1	3	S	2014-04-14	0	O que vc achou sobre o parque de servidores da CGTI? Descreva
22	1	1	S	2014-04-02	0	A CARGA HORÁRIA TOTAL utilizada para o desenvolvimento do evento de capacitação foi:
23	1	1	S	2014-04-14	0	Qual a sua impressão sobre a CGTI
24	1	3	S	2014-04-14	0	O que vc achou sobre o parque de servidores da CGTI? Descreva
25	1	3	S	2014-03-18	0	Pergunta - Teste 2
26	1	2	S	2014-03-18	0	Pergunta - Teste 3
27	1	1	S	2014-04-02	0	A CARGA HORÁRIA TOTAL utilizada para o desenvolvimento do evento de capacitação foi:
28	1	1	S	2014-04-14	0	Qual a sua impressão sobre a CGTI
29	1	7	S	2014-07-21	0	Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscingyttsss elitis. Pra lá , depois divoltisorris, pdis. Paisis, filhis, espiritis santis. Mé faiz elementum girarzis, nisi eros vermeio, in elementis mé pra quem é amistosis quis leo. Mandhkjh
30	1	3	S	2014-03-18	0	Pergunta - Teste 2
31	1	2	S	2014-03-18	0	Pergunta - Teste 3
32	1	7	S	2014-03-18	0	Teste 1
33	1	1	S	2014-04-02	0	A CARGA HORÁRIA TOTAL utilizada para o desenvolvimento do evento de capacitação foi:
34	1	1	S	2014-04-14	0	Qual a sua impressão sobre a CGTI
35	1	3	S	2014-04-14	0	O que vc achou sobre o parque de servidores da CGTI? Descreva
36	1	1	S	2014-08-22	0	2014.P1.Q1 - Informe há quanto tempo você atua na Polícia Federal.  Informe, conforme a escala, o tempo total em anos de sua atuação na Polícia Federal
37	1	1	S	2014-08-22	0	2014.P1.Q2 - Informa a sua LOTAÇÃO ATUAL.
38	1	1	S	2014-08-22	0	2014.P1.Q1 - Informe há quanto tempo você atua na Polícia Federal.  Informe, conforme a escala, o tempo total em anos de sua atuação na Polícia Federal
39	1	1	S	2014-08-22	0	2014.P1.Q2 - Informa a sua LOTAÇÃO ATUAL.
40	1	1	S	2014-08-22	0	2014.P1.Q1 - Informe há quanto tempo você atua na Polícia Federal.  Informe, conforme a escala, o tempo total em anos de sua atuação na Polícia Federal
41	1	1	S	2014-08-22	0	2014.P1.Q2 - Informa a sua LOTAÇÃO ATUAL.
42	1	1	S	2014-08-28	0	2014.P1.Q3 - Informe a cidade-sede do evento Copa do Mundo FIFA 2014 em que você atuou. Para fins de resposta à esta questão, considere a cidade-sede onde você atuou na maior parte do tempo durante a mobilização para a Copa do Mundo FIFA 2014.
43	1	1	S	2014-08-28	0	2014.P1.Q4 - Informe a sua área de atuação no evento Copa do Mundo FIFA 2014. Selecione a sua área de atuação (coordenação) no evento Copa do Mundo FIFA 2014.
44	1	2	S	2014-10-02	0	Qual o nome da rosa?
45	1	1	S	2014-10-02	0	Qual o nome da rosa?
46	1	5	S	2014-03-18	0	2016.1 Informe o seu número favorito, de 1 a 1000:
47	1	1	S	2014-08-22	0	2014.P1.Q1 - Informe há quanto tempo você atua na Polícia Federal.  Informe, conforme a escala, o tempo total em anos de sua atuação na Polícia Federal
48	1	1	S	2014-08-22	0	2014.P1.Q2 - Informa a sua LOTAÇÃO ATUAL.
49	1	2	S	2014-08-28	0	2014.P1.Q3 - Informe a cidade-sede do evento Copa do Mundo FIFA 2014 em que você atuou. Para fins de resposta à esta questão, considere a cidade-sede onde você atuou na maior parte do tempo durante a mobilização para a Copa do Mundo FIFA 2014.
50	1	1	S	2014-08-28	0	2014.P1.Q4 - Informe a sua área de atuação no evento Copa do Mundo FIFA 2014. Selecione a sua área de atuação (coordenação) no evento Copa do Mundo FIFA 2014.
51	1	1	S	2014-07-21	0	D1.F6 - Atribua uma AVALIAÇÃO GERAL em relação ao serviço utilizado por você nessa Unidade.
52	1	1	S	2014-08-22	0	2014.P1.Q1 - Informe há quanto tempo você atua na Polícia Federal.  Informe, conforme a escala, o tempo total em anos de sua atuação na Polícia Federal
54	1	1	S	2014-04-02	0	D1.F6 - Avalie a CONSERVAÇÃO E LIMPEZA do local de atendimento ao público dessa Unidade.
55	1	1	S	2014-04-14	0	2016.2 Informe o objetivo institucional que você considera mais relevante.
56	1	3	S	2014-04-14	0	2015.2 O que você entende por "Planejamento estratégico"? Descreva
57	1	1	S	2014-08-22	0	2014.P1.Q1 - Informe há quanto tempo você atua no Órgão.
58	1	1	S	2014-08-22	0	2014.P1.Q2 - Informa a sua LOTAÇÃO ATUAL.
\.


--
-- Data for Name: tb_hst_publicacao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_hst_publicacao (idhistoricopublicacao, idpesquisa, datpublicacao, datencerramento, idpespublicou, idpesencerrou) FROM stdin;
1	1	2015-11-02 01:51:46.640831	\N	1	\N
2	1	2015-11-02 01:51:46.640831	2015-11-02 01:53:49.104275	1	1
\.


--
-- Data for Name: tb_licao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_licao (idlicao, idprojeto, identrega, desresultadosobtidos, despontosfortes, despontosfracos, dessugestoes, datcadastro) FROM stdin;
1	1	53	vfo aj ovja vaoajvçfjlçajlv	avaas da ff s		fdsa dsad dfsad 	2015-11-01
2	1	37	fgsd faf 	s fdfsa	df daf asa fsa	 sffdf aasa as	2015-11-01
3	1	22	Descrever resultados obtidos.	Descrever/relacionar os pontos fortes aprendidos.	Descrever/relacionar os pontos fracos/dificuldades aprendidas.	Sugerir medidas para um projeto futuro semelhante.	2015-12-30
4	2	2	80% do monitoramento realizado.	Equipe motivada e infra-estrutura apropriada.	Baixa maturidade da organização na condução de projetos.	Implementar esforço de capacitação em gerenciamento de projetos na organização.	2016-01-21
5	1	66	bla bla	pontos strong fortes	pedras no caminho	fazer promessas ao santo do orçamento público.	2016-02-03
\.


--
-- Data for Name: tb_mudanca; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_mudanca (idmudanca, idprojeto, nomsolicitante, datsolicitacao, datdecisao, desmudanca, desjustificativa, despareceregp, desaprovadores, despareceraprovadores, idcadastrador, idtipomudanca, datcadastro, flaaprovada) FROM stdin;
1	1	Patrocinador	2015-10-19	2015-10-30	Melhorar a qualidade com o aperfeiçoamento das comunicações e troca do Supervisor do Grupo X	Baixa qualidade das entregas e atraso considerável no prazo do projeto	Favorável à mudança proposta		Pela aprovação e execução imediata.	1	4	2015-10-31	S
2	1	Gerente do projeto	2015-11-02	2015-11-13	Paralisação do projeto	Falta de pessoal	Pela aprovação		Pela rejeição. Aguardar aporte de pessoal.	1	5	2015-11-01	N
\.


--
-- Data for Name: tb_natureza; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_natureza (idnatureza, nomnatureza, idcadastrador, datcadastro, flaativo) FROM stdin;
9	OUTROS	1	2015-05-26	S
1	ROTINA ADMINISTRATIVA	1	2014-03-18	S
2	ROTINA OPERACIONAL	1	2014-03-18	S
3	INFRAESTRUTURA E OBRAS	1	2014-03-18	S
4	CAPACITAÇÃO	1	2014-03-18	S
5	AQUISIÇÃO	1	2014-03-18	S
6	ESTRUTURAÇÃO ORGANIZACIONAL	1	2014-03-18	S
7	CRIAÇÃO/MELHORIA PROCESSO DE TRABALHO	1	2015-02-13	S
8	TI E COMUNICAÇÕES	1	2015-02-13	S
\.


--
-- Data for Name: tb_objetivo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_objetivo (idobjetivo, nomobjetivo, idcadastrador, datcadastro, flaativo, desobjetivo, codescritorio, numseq) FROM stdin;
1	Objetivo estratégico 01	1	2015-03-05	S	Implantar uma cultura permanente de gestão estratégica	0	1
\.


--
-- Data for Name: tb_origemrisco; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_origemrisco (idorigemrisco, desorigemrisco, idcadastrador, dtcadastro) FROM stdin;
1	Acao Gerencial	1	2014-01-30
2	Custo	1	2014-01-30
3	Escopo	1	2014-01-30
4	Prazo	1	2014-01-30
5	Premissa	1	2014-01-30
6	Qualidade	1	2014-01-30
7	Partes Interessadas	1	2014-02-06
8	Requisito	1	2014-02-06
9	Restricao	1	2014-02-06
10	Outros	1	2014-02-06
\.


--
-- Data for Name: tb_p_acao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_p_acao (id_p_acao, idprojetoprocesso, nom_p_acao, des_p_acao, datinicioprevisto, datinicioreal, datterminoprevisto, datterminoreal, idsetorresponsavel, flacancelada, idcadastrador, datcadastro, numseq, idresponsavel) FROM stdin;
1	1	Elaborar minuta de portaria	sdb sdfsd bsdf db alka kla lkllkf kjhbd blsdbhdh bh sdlkhslkbjhsdk	2015-06-10	2015-06-12	2015-06-12	2015-06-12	1	2	1	2015-06-10 19:32:36.958511+00	1	1
4	2	hgk gkjhgkhv	hg hukkjh gkjhg kjg kjh gjh	2015-06-01	2015-06-02	2015-06-26	2015-06-30	1	2	1	2015-06-12 19:59:07.093382+00	4	1
3	2	Elaborar estudo de processo	svaad  ashah  sd ç saçdsadçlasd asçld asdçlj sçdj sdjasd  sljs sdssd  sddj sdslçjç  svaad	2015-06-01	2015-06-03	2015-06-19	2015-06-19	1	2	1	2015-06-11 12:43:00.720716+00	3	1
2	1	Enviar comunicação para partes interessadas	kajhf opioaso aoop  po po poasad oasdoasd psodsadoksdaop pos  dsd as asdo	2015-06-03	2015-06-08	2015-06-26	2015-06-22	1	2	1	2015-06-11 12:41:07.471993+00	2	1
\.


--
-- Data for Name: tb_parteinteressada; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_parteinteressada (idparteinteressada, idprojeto, nomparteinteressada, nomfuncao, destelefone, desemail, domnivelinfluencia, idcadastrador, datcadastro, idpessoainterna, observacao) FROM stdin;
367	1	Usuario 01	Gerente	6199999999	usuario01@gepnet2.gov	Alto	1	2015-10-31 12:46:46.152966+00	1	bla bla                                                                                                                                                                                                 
368	1	Usuario 02	Supervisor	0000000000	usuario02@gepnet.gov	Médio	1	2015-10-31 12:48:51.924043+00	2	bla bla                                                                                                                                                                                                 
370	1	Usuario 04	Assistente de execução	(61) 9999-9999	usuario04@gepnet2.gov	Médio	1	2015-10-31 12:50:08.038469+00	4	bli bli                                                                                                                                                                                                 
371	2	Usuario 01	Patrocinador	6199999999	usuario01@gepnet2.gov	Alto	1	2015-10-31 23:05:58.954984+00	1	                                                                                                                                                                                                        
372	2	Usuario 02	Demandante	0000000000	usuario02@gepnet.gov	Alto	1	2015-10-31 23:06:17.919748+00	2	                                                                                                                                                                                                        
373	2	Usuario 03	Gerente	(61) 9999-9999	usuario03@gepnet2.gov	Médio	1	2015-10-31 23:06:58.234279+00	3	                                                                                                                                                                                                        
382	1	usuario externo 02	externo	(99) 9999-9999	usuario_externo02@projetos.com	Baixo	1	2015-11-08 18:38:41.125789+00	\N	                                                                                                                                                                                                        
369	1	Usuario 03	Gerente Adjunto	(61) 9999-9999	usuario03@gepnet2.gov	Médio	1	2015-10-31 12:49:03.663004+00	3	blu blu                                                                                                                                                                                                 
365	1	PAPAI NOEL	Externo	(61) 9999-9999	NOEL@NATAL.COM	Médio	1	2015-04-10 19:30:04.604138+00	\N	Papai Noel entrega presente só no Natal.                                                                                                                                                                
\.


--
-- Data for Name: tb_perfil; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_perfil (idperfil, nomperfil, flaativo, idcadastrador, datcadastro) FROM stdin;
1	Admin GEPnet	S	1	2015-06-08
2	Administrador Setorial	S	1	2015-06-08
3	Escritorio de Projetos	S	1	2015-06-08
4	Gerente de Projeto	S	1	2015-06-08
10	Escritorio de Processos	S	1	2015-06-08
12	Pesquisa	S	1	2015-06-08
13	Coordenador Grandes Eventos	S	1	2015-06-08
14	Avaliador Grandes Eventos	S	1	2015-06-08
6	Assistente de cronograma	S	1	2015-06-08
5	Assistente de projeto	S	1	2015-06-08
7	Status Report	S	1	2015-06-08
11	Acordo de Cooperacao	S	1	2015-06-08
15	Assistente Grandes Eventos	S	1	2015-06-08
8	Assistente de riscos	S	1	2015-06-08
9	Consultor de processos	S	1	2015-06-08
\.


--
-- Data for Name: tb_perfilpessoa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_perfilpessoa (idpessoa, idperfil, idescritorio, flaativo, idcadastrador, datcadastro, idperfilpessoa) FROM stdin;
1	1	0	S	1	2015-05-26	1
2	2	0	S	1	2016-02-05	2
3	3	0	S	1	2016-02-05	3
4	4	0	S	1	2016-02-05	4
5	7	0	S	1	2016-02-05	5
1	1	1	S	1	2016-02-05	6
1	1	2	S	1	2016-02-05	7
\.


--
-- Data for Name: tb_permissao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_permissao (idpermissao, idrecurso, ds_permissao, no_permissao) FROM stdin;
1	1	\N	sair
2	1	\N	logout
3	1	\N	index
4	1	\N	perfil
5	1	\N	mudar-perfil
6	2	\N	error
7	2	\N	acl
8	2	\N	login
9	3	\N	index
10	3	\N	out
11	5	\N	index
12	5	\N	gerenciar
13	5	\N	retorna-por-perfil
14	5	\N	novos-recursos
15	5	\N	pesquisar
16	5	\N	detalhar
17	5	\N	cadastrar
18	6	\N	detalhar
19	6	\N	pesquisar
20	6	\N	retorna-por-recurso
21	6	\N	editar
22	6	\N	index
23	9	\N	index
24	9	\N	pesquisarjson
25	9	\N	pesquisar-sem-unidade
26	9	\N	detalhar
27	9	\N	add
28	9	\N	edit
29	9	\N	importarjson
30	9	\N	grid
31	9	\N	buscar
32	4	\N	index
33	7	\N	conceder-permissao
34	7	\N	revogar-permissao
35	7	\N	permissao
36	8	\N	index
37	8	\N	pesquisarjson
38	8	\N	associarperfil
39	8	\N	trocarsituacao
40	13	\N	index
41	13	\N	detalhar
42	13	\N	add
43	13	\N	editar
44	13	\N	pesquisarjson
45	11	\N	index
46	11	\N	pesquisarjson
47	11	\N	detalhar
48	11	\N	add
49	11	\N	editar
50	11	\N	buscar-escritorio
51	11	\N	pesquisarviewcomumjson
52	11	\N	importarjson
53	12	\N	index
54	12	\N	pesquisarjson
55	12	\N	detalhar
56	12	\N	add
57	12	\N	editar
58	12	\N	pesquisarviewcomumjson
59	12	\N	importarjson
60	10	\N	index
61	10	\N	pesquisarjson
62	10	\N	detalhar
63	10	\N	cadastrar
64	10	\N	editar
65	10	\N	editar-arquivo
66	10	\N	excluir
67	10	\N	abrir
68	14	\N	editar
69	14	\N	pesquisarjson
70	14	\N	add
71	14	\N	detalhar
72	14	\N	index
73	15	\N	add
74	15	\N	detalhar
75	15	\N	index
76	15	\N	download
77	15	\N	editar
78	15	\N	pesquisarjson
79	16	\N	excluir
80	16	\N	excluirparticipante
81	16	\N	participantes
82	16	\N	pesquisarjson
83	16	\N	edit
84	16	\N	add
85	16	\N	detalhar
86	16	\N	index
87	16	\N	retorna-dias-com-eventos
88	27	\N	index
89	27	\N	detalhar
90	27	\N	add
91	27	\N	edit
92	27	\N	buscarjson
93	27	\N	pesquisarjson
94	28	\N	index
95	28	\N	detalhar
96	28	\N	add
97	28	\N	edit
98	28	\N	buscarjson
99	28	\N	pesquisarjson
100	29	\N	index
101	29	\N	portfolioestrategico
102	29	\N	cadastrar
103	29	\N	editar
104	29	\N	detalhar
105	29	\N	pesquisarportfoliojson
106	29	\N	pesquisarprojeto
107	29	\N	chartorcamentarioprojetosprogramajson
108	29	\N	chartprojetosprogramajson
109	29	\N	chartprojetosnaturezajson
110	29	\N	pesquisarprojetojson
111	54	\N	index
112	17	\N	index
113	17	\N	detalhar
114	17	\N	cadastrar
115	17	\N	editar
116	17	\N	pesquisarjson
117	18	\N	index
118	18	\N	detalhar
119	18	\N	add
120	18	\N	editar
121	18	\N	pesquisarjson
122	19	\N	listar
123	19	\N	pesquisar
124	20	\N	listar
125	20	\N	cadastrar
126	20	\N	editar
127	20	\N	detalhar
128	20	\N	pesquisar
129	21	\N	listar
130	21	\N	pesquisar
131	21	\N	publicar
132	21	\N	pesquisa-duplicada
133	21	\N	gerenciar-pesquisas
134	21	\N	listar-publicadas
135	21	\N	publicar-encerrar
136	21	\N	pesquisas-respondidas
137	21	\N	listar-respostas-pesquisa
138	21	\N	resposta-pesquisa
139	21	\N	detalhar-pesquisa
140	22	\N	listar
141	22	\N	pesquisar
142	22	\N	cadastrar
143	22	\N	editar
144	22	\N	detalhar
145	22	\N	listar-perguntas
146	22	\N	pesquisar-perguntas
147	22	\N	vincular-pergunta
148	22	\N	editar-vinculo-pergunta
149	22	\N	desvincular-pergunta
150	22	\N	detalhar-vinculo-pergunta
151	22	\N	alterar-disponibilidade
152	22	\N	status-questionario
153	23	\N	listar
154	23	\N	pesquisar
155	23	\N	relatorio-percentual
156	23	\N	relatorio-tabelado
157	23	\N	imprimir-relatorio
158	23	\N	imprimir-tabelado
159	24	\N	listar
160	24	\N	pesquisar
161	24	\N	responder-pesquisa
162	24	\N	autenticar
163	24	\N	responder-externa
164	25	\N	listar
165	25	\N	cadastrar
166	25	\N	editar
167	25	\N	detalhar
168	25	\N	pesquisar
169	33	\N	imprimir
170	33	\N	listar
171	33	\N	cadastrar
172	33	\N	editar
173	33	\N	excluir
174	33	\N	detalhar
175	33	\N	grid-ata
176	34	\N	listar
177	34	\N	grid-parte-interessada
178	34	\N	grid-comunicacao
179	34	\N	add
180	34	\N	edit
181	34	\N	excluir
182	34	\N	detalhar
183	35	\N	listar
184	35	\N	cadastrar
185	35	\N	editar
186	35	\N	excluir
187	35	\N	detalhar
188	35	\N	pesquisar
189	36	\N	index
190	36	\N	retorna-projeto
191	36	\N	cadastrar-grupo
192	36	\N	editar-grupo
193	36	\N	editar-entrega
194	36	\N	cadastrar-entrega
195	36	\N	cadastrar-atividade
196	36	\N	editar-atividade
197	36	\N	retorna-inicio-base-line
198	36	\N	retorna-inicio-real
199	36	\N	adicionar-predecessora
200	36	\N	excluir-predecessora
201	36	\N	atividade-atualizar-percentual
202	36	\N	atualizar-dom-tipo-atividade
203	36	\N	excluir-grupo
204	36	\N	excluir-entrega
205	36	\N	excluir-atividade
206	36	\N	pesquisar
207	36	\N	clonar-entrega
208	36	\N	clonar-grupo
209	36	\N	pesquisarprojetojson
210	36	\N	pesquisar-projeto
211	36	\N	copiar-cronograma
212	36	\N	detalhar
213	36	\N	atualizar-baseline-atividade
214	36	\N	atualizar-baseline
215	36	\N	imprimir
216	36	\N	imprimir-pdf
217	36	\N	relatorio-cronograma
218	36	\N	resultado-relatorio-cronograma
219	36	\N	buscarprojetos
220	37	\N	listar
221	37	\N	cadastrar
222	37	\N	editar
223	37	\N	excluir
224	37	\N	detalhar
225	37	\N	pesquisar
226	38	\N	index
227	38	\N	cadastrar-grupo
228	38	\N	cadastrar-entrega
229	38	\N	editar-entrega
230	38	\N	excluir-grupo
231	38	\N	excluir-entrega
232	38	\N	visualizar-impressao
233	38	\N	imprimir-pdf
234	39	\N	visualizar
235	40	\N	index
236	40	\N	resumo
237	40	\N	pesquisarjson
238	40	\N	detalhar
239	40	\N	add
240	40	\N	editar
241	40	\N	pesquisarviewcomumjson
242	40	\N	importarjson
243	41	\N	index
244	41	\N	retornalicoesjson
245	41	\N	cadastrar
246	41	\N	editar
247	41	\N	detalhar
248	41	\N	excluir
249	41	\N	imprimir
250	42	\N	imprimir
251	43	\N	index
252	43	\N	pesquisarjson
253	43	\N	add
254	43	\N	editar
255	43	\N	excluir
256	44	\N	index
257	44	\N	relatoriojson
258	44	\N	add
259	44	\N	editar
260	44	\N	excluir
261	44	\N	detalhar
262	45	\N	listar
263	45	\N	grid-rh
264	45	\N	addinterno
265	45	\N	addexterno
266	45	\N	editarinterno
267	45	\N	editarexterno
268	45	\N	excluirparte
269	45	\N	detalhar
270	46	\N	listar
271	46	\N	cadastrar
272	46	\N	editar
273	46	\N	excluir
274	46	\N	detalhar
275	46	\N	pesquisar
276	46	\N	imprimir
277	47	\N	index
278	47	\N	pesquisarjson
279	47	\N	file-tree
280	47	\N	add
281	47	\N	addpasta
282	47	\N	delete
283	47	\N	download
284	48	\N	index
285	48	\N	add
286	48	\N	editar
287	48	\N	excluir
288	48	\N	detalhar
289	48	\N	pesquisarjson
290	48	\N	imprimir
291	48	\N	imprimirtodos
292	49	\N	index
293	49	\N	pesquisarjson
294	49	\N	detalhar
295	49	\N	chartplanejadorealizadojson
296	49	\N	chartatrasojson
297	49	\N	chartprazojson
298	49	\N	chartmarcojson
299	49	\N	imprimir-pdf
300	50	\N	index
301	50	\N	add
302	50	\N	informacoesiniciais
303	50	\N	informacoestecnicas
304	50	\N	resumodoprojeto
305	50	\N	partesinteressadas
306	50	\N	partesinteressadasexterno
307	50	\N	excluirparte
308	50	\N	imprimir
309	50	\N	acao
310	51	\N	editar
311	52	\N	index
312	52	\N	retornaaceitesjson
313	52	\N	add
314	52	\N	editar
315	52	\N	detalhar
316	52	\N	excluir
317	52	\N	buscar-entrega
318	52	\N	buscar-marcos
319	52	\N	imprimir
320	52	\N	imprimir-todos
321	53	\N	imprimir
322	30	\N	index
323	30	\N	detalhar
324	30	\N	add
325	30	\N	edit
326	30	\N	buscarjson
327	30	\N	pesquisarjson
328	31	\N	index
329	31	\N	detalhar
330	31	\N	add
331	31	\N	edit
332	31	\N	buscarjson
333	31	\N	pesquisarjson
334	32	\N	index
335	32	\N	detalhar
336	32	\N	add
337	32	\N	edit
338	32	\N	buscarjson
339	32	\N	pesquisarjson
340	26	\N	index
341	26	\N	detalhar
342	26	\N	add
343	26	\N	edit
344	26	\N	pesquisarjson
345	26	\N	relatorio
346	26	\N	imprimir
347	1	\N	boas-vindas
348	4	\N	default
349	34	\N	pesquisar-parte-interessada
350	38	\N	retorna-projeto
351	42	\N	index
352	51	\N	index
353	53	\N	index
\.


--
-- Data for Name: tb_permissaoperfil; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_permissaoperfil (idpermissaoperfil, idperfil, idpermissao) FROM stdin;
1	1	346
2	1	345
3	1	344
4	1	343
5	1	342
6	1	341
7	1	340
8	1	339
9	1	338
10	1	337
11	1	336
12	1	335
13	1	334
14	1	333
15	1	332
16	1	331
17	1	330
18	1	329
19	1	328
20	1	327
21	1	326
22	1	325
23	1	324
24	1	323
25	1	322
26	1	321
27	1	320
28	1	319
29	1	318
30	1	317
31	1	316
32	1	315
33	1	314
34	1	313
35	1	312
36	1	311
37	1	310
38	1	309
39	1	308
40	1	307
41	1	306
42	1	305
43	1	304
44	1	303
45	1	302
46	1	301
47	1	300
48	1	299
49	1	298
50	1	297
51	1	296
52	1	295
53	1	294
54	1	293
55	1	292
56	1	291
57	1	290
58	1	289
59	1	288
60	1	287
61	1	286
62	1	285
63	1	284
64	1	283
65	1	282
66	1	281
67	1	280
68	1	279
69	1	278
70	1	277
71	1	276
72	1	275
73	1	274
74	1	273
75	1	272
76	1	271
77	1	270
78	1	269
79	1	268
80	1	267
81	1	266
82	1	265
83	1	264
84	1	263
85	1	262
86	1	261
87	1	260
88	1	259
89	1	258
90	1	257
91	1	256
92	1	255
93	1	254
94	1	253
95	1	252
96	1	251
97	1	250
98	1	249
99	1	248
100	1	247
101	1	246
102	1	245
103	1	244
104	1	243
105	1	242
106	1	241
107	1	240
108	1	239
109	1	238
110	1	237
111	1	236
112	1	235
113	1	234
114	1	233
115	1	232
116	1	231
117	1	230
118	1	229
119	1	228
120	1	227
121	1	226
122	1	225
123	1	224
124	1	223
125	1	222
126	1	221
127	1	220
128	1	219
129	1	218
130	1	217
131	1	216
132	1	215
133	1	214
134	1	213
135	1	212
136	1	211
137	1	210
138	1	209
139	1	208
140	1	207
141	1	206
142	1	205
143	1	204
144	1	203
145	1	202
146	1	201
147	1	200
148	1	199
149	1	198
150	1	197
151	1	196
152	1	195
153	1	194
154	1	193
155	1	192
156	1	191
157	1	190
158	1	189
159	1	188
160	1	187
161	1	186
162	1	185
163	1	184
164	1	183
165	1	182
166	1	181
167	1	180
168	1	179
169	1	178
170	1	177
171	1	176
172	1	175
173	1	174
174	1	173
175	1	172
176	1	171
177	1	170
178	1	169
179	1	168
180	1	167
181	1	166
182	1	165
183	1	164
184	1	163
185	1	162
186	1	161
187	1	160
188	1	159
189	1	158
190	1	157
191	1	156
192	1	155
193	1	154
194	1	153
195	1	152
196	1	151
197	1	150
198	1	149
199	1	148
200	1	147
201	1	146
202	1	145
203	1	144
204	1	143
205	1	142
206	1	141
207	1	140
208	1	139
209	1	138
210	1	137
211	1	136
212	1	135
213	1	134
214	1	133
215	1	132
216	1	131
217	1	130
218	1	129
219	1	128
220	1	127
221	1	126
222	1	125
223	1	124
224	1	123
225	1	122
226	1	121
227	1	120
228	1	119
229	1	118
230	1	117
231	1	116
232	1	115
233	1	114
234	1	113
235	1	112
236	1	111
237	1	110
238	1	109
239	1	108
240	1	107
241	1	106
242	1	105
243	1	104
244	1	103
245	1	102
246	1	101
247	1	100
248	1	99
249	1	98
250	1	97
251	1	96
252	1	95
253	1	94
254	1	93
255	1	92
256	1	91
257	1	90
258	1	89
259	1	88
260	1	87
261	1	86
262	1	85
263	1	84
264	1	83
265	1	82
266	1	81
267	1	80
268	1	79
269	1	78
270	1	77
271	1	76
272	1	75
273	1	74
274	1	73
275	1	72
276	1	71
277	1	70
278	1	69
279	1	68
280	1	67
281	1	66
282	1	65
283	1	64
284	1	63
285	1	62
286	1	61
287	1	60
288	1	59
289	1	58
290	1	57
291	1	56
292	1	55
293	1	54
294	1	53
295	1	52
296	1	51
297	1	50
298	1	49
299	1	48
300	1	47
301	1	46
302	1	45
303	1	44
304	1	43
305	1	42
306	1	41
307	1	40
308	1	39
309	1	38
310	1	37
311	1	36
312	1	35
313	1	34
314	1	33
315	1	32
316	1	31
317	1	30
318	1	29
319	1	28
320	1	27
321	1	26
322	1	25
323	1	24
324	1	23
325	1	22
326	1	21
327	1	20
328	1	19
329	1	18
330	1	17
331	1	16
332	1	15
333	1	14
334	1	13
335	1	12
336	1	11
337	1	10
338	1	9
339	1	5
340	1	4
341	1	3
342	1	2
343	1	1
344	2	87
345	2	86
346	2	85
347	2	84
348	2	83
349	2	82
350	2	81
351	2	80
352	2	79
353	2	32
354	2	31
355	2	30
356	2	29
357	2	28
358	2	27
359	2	26
360	2	25
361	2	24
362	2	23
363	2	5
364	2	4
365	2	3
366	2	2
367	2	1
368	2	10
369	2	9
370	2	346
371	2	345
372	2	344
373	2	343
374	2	342
375	2	341
376	2	340
377	2	94
378	2	95
379	2	96
380	2	97
381	2	98
382	2	99
383	2	93
384	2	92
385	2	91
386	2	90
387	2	89
388	2	88
389	2	110
390	2	109
391	2	108
392	2	107
393	2	106
394	2	105
395	2	104
396	2	103
397	2	102
398	2	101
399	2	100
400	2	111
401	2	242
402	2	241
405	2	238
406	2	237
407	2	236
408	2	235
409	2	67
410	2	66
411	2	65
412	2	64
413	2	63
414	2	62
415	2	61
416	2	60
417	2	33
419	2	35
420	2	39
421	2	38
422	2	37
423	2	36
424	2	53
425	2	54
426	2	55
427	2	56
428	2	57
429	2	58
430	2	59
431	2	44
432	2	43
433	2	42
434	2	41
435	2	40
436	2	159
437	2	160
438	2	161
439	2	162
440	2	163
441	2	164
442	2	123
443	2	122
444	2	128
445	2	127
446	2	126
447	2	125
448	2	124
449	2	139
450	2	133
451	2	138
452	2	137
453	2	136
454	2	135
455	2	134
456	2	132
457	2	131
458	2	130
459	2	129
467	2	167
468	2	166
469	2	165
470	2	168
471	2	158
472	2	157
473	2	156
474	2	155
475	2	154
476	2	153
477	1	347
478	2	347
479	3	347
480	4	347
481	3	87
482	3	86
483	3	85
484	3	84
485	3	83
486	3	82
487	3	81
488	3	80
489	3	79
490	3	67
492	3	65
493	3	64
494	3	63
495	3	62
496	3	61
497	3	60
498	3	52
499	3	51
500	3	50
501	3	47
502	3	46
503	3	45
504	3	32
505	3	35
506	3	33
507	3	38
508	3	37
509	3	36
510	3	23
511	3	24
512	3	25
513	3	26
514	3	27
515	3	28
516	3	29
517	3	30
518	3	31
519	3	59
520	3	58
521	3	55
522	3	54
523	3	53
524	3	56
525	3	57
526	3	44
527	3	41
528	3	40
529	3	5
530	3	4
531	3	3
532	3	2
533	3	1
534	3	10
535	3	9
536	3	163
537	3	162
538	3	161
539	3	160
540	3	159
541	3	129
542	3	130
543	3	131
544	3	132
545	3	133
546	3	134
547	3	135
548	3	136
549	3	137
550	3	138
551	3	139
552	3	346
553	3	345
554	3	344
555	3	343
556	3	342
557	3	341
558	3	340
559	3	93
560	3	92
561	3	89
562	3	88
563	3	99
564	3	98
565	3	95
566	3	94
567	3	110
568	3	109
569	3	108
570	3	107
571	3	106
572	3	105
573	3	104
574	3	101
575	3	100
576	3	339
577	3	338
578	3	337
579	3	336
580	3	335
581	3	334
582	3	327
583	3	326
584	3	325
585	3	324
586	3	323
587	3	322
588	3	333
589	3	332
590	3	331
591	3	330
592	3	329
593	3	328
594	3	175
595	3	174
596	3	172
597	3	171
598	3	170
599	3	169
600	3	182
601	3	180
602	3	179
603	3	178
604	3	177
605	3	176
606	3	188
607	3	187
608	3	185
609	3	184
610	3	183
611	3	219
612	3	218
613	3	217
614	3	216
615	3	215
616	3	214
617	3	213
618	3	212
619	3	211
620	3	210
621	3	209
622	3	208
623	3	207
624	3	206
625	3	205
626	3	204
627	3	203
628	3	202
629	3	201
630	3	200
631	3	199
632	3	198
633	3	197
634	3	196
635	3	195
636	3	194
637	3	193
638	3	192
639	3	191
640	3	190
641	3	189
642	3	225
643	3	224
645	3	220
646	3	233
647	3	232
648	3	231
649	3	230
650	3	229
651	3	228
652	3	227
653	3	226
654	3	234
655	3	242
656	3	241
657	3	238
658	3	237
659	3	236
660	3	235
661	3	239
662	3	240
663	3	249
664	3	248
665	3	247
666	3	246
667	3	245
668	3	244
669	3	243
670	3	250
671	3	255
672	3	254
673	3	253
674	3	252
675	3	251
676	3	261
677	3	259
678	3	258
679	3	257
680	3	256
681	3	269
682	3	267
683	3	266
684	3	265
685	3	264
686	3	263
687	3	262
688	3	276
689	3	275
690	3	274
691	3	272
692	3	271
693	3	270
694	3	283
695	3	281
696	3	280
697	3	279
698	3	278
699	3	277
700	3	291
701	3	290
702	3	289
703	3	288
704	3	286
705	3	285
706	3	284
707	3	299
708	3	298
709	3	297
710	3	296
711	3	295
712	3	294
713	3	293
714	3	292
715	4	87
716	4	86
717	4	85
718	4	84
719	4	83
720	4	82
721	4	81
722	4	80
723	4	79
724	4	67
725	4	66
726	4	65
727	4	64
728	4	63
729	4	62
730	4	61
731	4	60
732	4	52
733	4	51
734	4	50
735	4	47
736	4	46
737	4	45
738	4	32
739	4	36
740	4	37
741	4	31
742	4	30
743	4	29
744	4	26
745	4	25
746	4	24
747	4	23
748	4	59
749	4	58
750	4	55
751	4	54
752	4	53
753	4	44
754	4	41
755	4	40
756	4	5
757	4	4
758	4	3
759	4	2
760	4	1
761	4	10
762	4	9
763	4	163
764	4	162
765	4	161
766	4	160
767	4	159
768	4	129
769	4	346
770	4	345
771	4	344
772	4	343
773	4	342
774	4	341
775	4	340
776	4	93
777	4	92
778	4	89
779	4	88
780	4	99
781	4	98
782	4	95
783	4	94
784	4	110
785	4	109
786	4	108
787	4	107
788	4	106
789	4	105
790	4	104
791	4	101
792	4	100
793	4	175
794	4	174
795	4	173
796	4	172
797	4	171
798	4	170
799	4	169
800	4	182
801	4	181
802	4	180
803	4	179
804	4	178
805	4	177
806	4	176
807	4	188
808	4	187
809	4	185
810	4	184
811	4	183
812	4	219
813	4	218
814	4	217
815	4	216
816	4	215
817	4	214
818	4	213
819	4	212
820	4	211
821	4	210
822	4	209
823	4	208
824	4	207
825	4	206
826	4	205
827	4	204
828	4	203
829	4	202
830	4	201
831	4	200
832	4	199
833	4	198
834	4	197
835	4	196
836	4	195
837	4	194
838	4	193
839	4	192
840	4	191
841	4	190
842	4	189
843	4	225
844	4	224
845	4	223
846	4	222
847	4	221
848	4	220
849	4	233
850	4	232
851	4	231
852	4	230
853	4	229
854	4	228
855	4	227
856	4	226
857	4	234
858	4	242
859	4	241
860	4	240
861	4	239
862	4	238
863	4	237
864	4	236
865	4	235
866	4	249
867	4	247
868	4	246
869	4	245
870	4	244
871	4	243
872	4	250
873	4	254
874	4	253
875	4	252
876	4	251
877	4	261
879	4	259
880	4	258
881	4	257
882	4	256
883	4	269
884	4	268
885	4	267
886	4	266
887	4	265
888	4	264
889	4	263
890	4	262
891	4	276
892	4	275
893	4	274
894	4	272
895	4	271
896	4	270
897	4	283
898	4	282
899	4	281
900	4	280
901	4	279
902	4	278
903	4	277
904	4	291
905	4	290
906	4	289
907	4	288
908	4	286
909	4	285
910	4	284
911	4	299
912	4	298
913	4	297
914	4	296
915	4	295
916	4	294
917	4	293
918	4	292
919	4	309
920	4	308
921	4	307
922	4	306
923	4	305
924	4	304
925	4	303
926	4	302
927	4	301
928	4	300
929	4	310
930	4	320
931	4	319
932	4	318
933	4	317
934	4	316
935	4	315
936	4	314
937	4	313
938	4	312
939	4	311
940	4	321
941	4	111
942	1	353
943	1	352
944	1	351
945	1	350
946	1	349
947	1	348
951	2	350
952	2	349
953	2	348
954	7	32
955	7	52
956	7	51
957	7	50
958	7	47
959	7	46
960	7	45
961	7	347
962	7	5
963	7	4
964	7	3
965	7	2
966	7	1
967	7	10
968	7	9
969	7	163
970	7	162
971	7	161
972	7	160
973	7	159
975	7	139
976	7	129
977	7	256
978	7	257
979	7	292
980	7	293
981	7	294
982	7	295
983	7	296
984	7	297
985	7	298
986	7	299
989	7	308
991	7	291
992	7	321
993	7	320
994	7	290
995	7	250
996	7	169
997	2	169
998	2	250
999	2	291
1000	2	292
1001	2	293
1002	2	294
1003	2	295
1004	2	296
1005	2	297
1006	2	298
1007	2	299
1008	2	308
1009	2	320
1010	2	319
1011	2	321
1012	2	290
1013	10	87
1014	10	86
1015	10	85
1016	10	84
1017	10	83
1018	10	82
1019	10	81
1020	10	80
1021	10	79
1022	10	52
1023	10	51
1024	10	50
1025	10	47
1026	10	46
1027	10	45
1028	10	32
1029	10	36
1030	10	37
1031	10	31
1032	10	30
1033	10	29
1034	10	28
1035	10	27
1036	10	26
1037	10	25
1038	10	24
1039	10	23
1040	10	59
1041	10	58
1042	10	55
1043	10	54
1044	10	53
1050	10	44
1051	10	43
1052	10	42
1053	10	41
1054	10	40
1055	10	347
1056	10	5
1057	10	4
1058	10	3
1059	10	2
1060	10	1
1061	10	10
1062	10	9
1063	10	163
1064	10	162
1065	10	161
1066	10	160
1067	10	159
1068	10	129
1069	10	346
1070	10	345
1071	10	344
1072	10	343
1073	10	342
1074	10	341
1075	10	340
1076	10	99
1077	10	98
1078	10	95
1079	10	94
1080	10	110
1081	10	109
1082	10	108
1083	10	107
1084	10	106
1085	10	105
1086	10	104
1087	10	101
1088	10	100
1089	10	93
1090	10	92
1091	10	89
1092	10	88
1093	10	327
1094	10	326
1095	10	325
1096	10	324
1097	10	323
1098	10	322
1099	10	333
1100	10	332
1101	10	331
1102	10	330
1103	10	329
1104	10	328
1105	10	339
1106	10	338
1107	10	337
1108	10	336
1109	10	335
1110	10	334
1111	9	87
1112	9	86
1113	9	85
1114	9	84
1115	9	83
1116	9	82
1117	9	81
1118	9	80
1119	9	79
1120	10	67
1122	10	65
1123	10	64
1124	10	63
1125	10	62
1126	10	61
1127	10	60
1128	9	67
1129	9	65
1130	9	64
1131	9	63
1132	9	62
1133	9	61
1134	9	60
1135	9	32
1136	9	31
1137	9	30
1138	9	29
1139	9	27
1140	9	26
1141	9	25
1142	9	24
1143	9	23
1144	9	346
1145	9	345
1146	9	344
1147	9	343
1148	9	342
1149	9	341
1150	9	340
1151	9	28
1152	9	347
1153	9	5
1154	9	4
1155	9	3
1156	9	2
1157	9	1
1158	9	163
1159	9	162
1160	9	161
1161	9	160
1162	9	159
1163	9	129
1164	9	99
1165	9	98
1166	9	95
1167	9	94
1168	9	110
1169	9	109
1170	9	108
1171	9	107
1172	9	106
1173	9	105
1174	9	104
1175	9	101
1176	9	100
1177	9	93
1178	9	92
1179	9	89
1180	9	88
1181	9	327
1182	9	326
1183	9	325
1184	9	324
1185	9	323
1186	9	322
1187	9	333
1188	9	332
1189	9	331
1190	9	330
1191	9	329
1192	9	328
1193	9	339
1194	9	338
1195	9	337
1196	9	336
1197	9	335
1198	9	334
1199	9	292
1200	9	293
1201	9	294
1202	9	295
1203	9	296
1204	9	297
1205	9	298
1206	9	299
1207	9	169
1208	9	235
1209	9	236
1210	9	237
1211	9	238
1212	9	241
1213	9	242
1214	9	250
1215	9	308
1216	9	291
1217	9	290
1218	9	320
1219	9	319
1220	9	321
1221	11	72
1222	11	71
1223	11	70
1224	11	69
1225	11	68
1226	11	78
1227	11	77
1228	11	76
1229	11	75
1230	11	74
1231	11	73
1232	11	87
1233	11	86
1234	11	85
1235	11	84
1236	11	83
1237	11	82
1238	11	81
1239	11	80
1240	11	79
1241	11	31
1242	11	29
1243	11	30
1244	11	28
1245	11	27
1246	11	26
1247	11	25
1248	11	24
1249	11	23
1250	11	347
1251	11	5
1252	11	4
1253	11	3
1254	11	2
1255	11	1
1256	11	163
1257	11	162
1258	11	161
1259	11	160
1260	11	159
1261	11	129
1262	11	346
1263	11	345
1264	11	344
1265	11	343
1266	11	342
1267	11	341
1268	11	340
1269	12	87
1270	12	86
1271	12	85
1272	12	84
1273	12	83
1274	12	82
1275	12	81
1276	12	80
1277	12	79
1278	12	67
1279	12	65
1280	12	64
1281	12	63
1282	12	62
1283	12	61
1284	12	60
1285	12	32
1286	12	31
1287	12	30
1288	12	29
1289	12	26
1290	12	25
1291	12	24
1292	12	23
1293	12	123
1294	12	122
1295	12	128
1296	12	127
1297	12	126
1298	12	125
1299	12	124
1300	12	139
1301	12	138
1302	12	137
1303	12	136
1304	12	135
1305	12	134
1306	12	133
1307	12	132
1308	12	131
1309	12	130
1310	12	129
1311	12	152
1312	12	151
1313	12	150
1314	12	149
1315	12	148
1316	12	147
1317	12	146
1318	12	145
1319	12	144
1320	12	143
1321	12	142
1322	12	141
1323	12	140
1324	12	158
1325	12	157
1326	12	156
1327	12	155
1328	12	154
1329	12	153
1330	12	163
1331	12	162
1332	12	161
1333	12	160
1334	12	159
1335	12	168
1336	12	167
1337	12	166
1338	12	165
1339	12	164
1340	12	346
1341	12	345
1342	12	344
1343	12	343
1344	12	342
1345	12	341
1346	12	340
1347	13	87
1348	13	86
1349	13	85
1350	13	84
1351	13	83
1352	13	82
1353	13	81
1354	13	80
1355	13	79
1356	13	67
1357	13	66
1358	13	65
1359	13	64
1360	13	63
1361	13	62
1362	13	61
1363	13	60
1364	13	31
1365	13	30
1366	13	29
1367	13	28
1368	13	27
1369	13	26
1370	13	25
1371	13	24
1372	13	23
1373	13	347
1374	13	5
1375	13	4
1376	13	3
1377	13	2
1378	13	1
1379	13	346
1380	13	345
1381	13	344
1382	13	343
1383	13	342
1384	13	341
1385	13	340
1386	13	235
1387	13	169
1388	13	250
1389	13	291
1390	13	290
1391	13	292
1392	13	293
1393	13	294
1394	13	295
1395	13	296
1396	13	297
1397	13	298
1398	13	299
1399	13	308
1400	13	320
1401	13	319
1402	13	321
1403	13	111
1404	7	87
1405	7	86
1406	7	85
1407	7	82
1408	7	81
1409	7	235
1410	10	235
1411	10	236
1412	10	237
1413	10	238
1414	10	241
1415	10	242
1416	11	235
\.


--
-- Data for Name: tb_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_pesquisa (idpesquisa, situacao, idcadastrador, datcadastro, datpublicacao, idpespublica, idpesencerra, idquestionario, dtencerramento) FROM stdin;
1	2	1	2015-11-02 01:51:46.640831	2015-11-02 01:51:46.640831	1	1	1	2015-11-02 01:53:49.104275
\.


--
-- Data for Name: tb_pessoa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_pessoa (idpessoa, nompessoa, desobs, numfone, numcelular, desemail, idcadastrador, datcadastro, nummatricula, desfuncao, id_unidade, domcargo, id_servidor, flaagenda, numcpf, numsiape, token, lotacao) FROM stdin;
1	Usuario 01		6199999999	61999999999	usuario01@gepnet2.gov	1	2015-03-03	9999	Consultor Sênior	12002	AAA	19920	S	22355653100	\N	0e1177622dc1d5506b5add8829b504a7                                                                                                                                                                                                                               	PMO 1                                                                                               
3	Usuario 03		6199999999	61999999999	usuario03@gepnet2.gov	1	2015-03-03	9997	Consultor Sênior	12002	PPP	20160	S	29882752977	\N	87c1a2e5cb8f3213c4a438609635360d                                                                                                                                                                                                                               	PMO 1                                                                                               
4	Usuario 04		6199999999	61999990909	usuario04@gepnet2.gov	1	2015-03-03	9996	Consultor	12002	PPP	5275	S	95881811607	\N	2e19c5b7a1c312aeb4e27f986dcdfa80                                                                                                                                                                                                                               	PMO 1                                                                                               
5	Usuario 05		6199999999	61999999999	usuario05@gepnet2.gov	1	2015-03-04	9995	CCC	3260	PPP	16883	S	57469156887	\N	3af73846ef1d25d3cb29e3d469b0c413                                                                                                                                                                                                                               	PMO01                                                                                               
11	Usuario 11		0000000000	61909009090	usuario11@gepnet2.gov	1	2015-03-03	5000650	Apoio	1210	COL	30601	S	71721815490	\N	845d8a113b175ebbc6f79979088c139b                                                                                                                                                                                                                               	PMO 0                                                                                               
2	Usuario 02		0000000000	61999999999	usuario02@gepnet2.gov	1	2015-03-03	9998	Consultor Sênior	12002	AAA	13828	S	44030489516	\N	a2baaf0f83b59aac824aa705d86cd550                                                                                                                                                                                                                               	PMO 1                                                                                               
\.


--
-- Data for Name: tb_pessoaagenda; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_pessoaagenda (idagenda, idpessoa) FROM stdin;
1	1
1	2
1	3
1	4
1	5
1	11
\.


--
-- Data for Name: tb_portfolio; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_portfolio (idportfolio, noportfolio, idportfoliopai, ativo, tipo, idresponsavel, idescritorio) FROM stdin;
32	4 PORTFÓLIO GERAL (2015)	\N	S	1	1	0
33	2 PORTFÓLIO MELHORIAS NACIONAIS (2015)	\N	S	1	1	0
34	3 PDTI (2015-2017)	\N	S	1	1	2
31	1 PORTFÓLIO ESTRATÉGICO (2015)	\N	S	2	1	0
\.


--
-- Data for Name: tb_portifolioprograma; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_portifolioprograma (idprograma, idportfolio) FROM stdin;
1	32
10	33
5	34
9	31
10	31
\.


--
-- Data for Name: tb_processo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_processo (idprocesso, idprocessopai, nomcodigo, nomprocesso, idsetor, desprocesso, iddono, idexecutor, idgestor, idconsultor, numvalidade, datatualizacao, idcadastrador, datcadastro) FROM stdin;
2	1	2/2015	MACROPROCESSO 2	1	AFAL ALJALÇ AAA AJ AJ f kjhbd blsdbhdh bh sdlkhslkbjhg rfg	1	3	2	4	24	2015-10-13	1	2015-06-10
1	1	1/2015	MACROPROCESSO 1	1	BSJ BSBSBSOBSFOBSFOSFOSFJBOSSRSDHBI	1	2	3	4	36	2015-10-13	1	2015-06-10
5	1	4/2015	processo 4	1	dfhhoof  o kfksdfh ksbhskbh sklb dfgsd	1	3	2	4	12	2015-10-13	30605	2015-06-10
4	3	3/2015	Processo 2	1	lsd hbçsbohao oha o fofçh klaehfgbs	1	3	2	4	48	2015-10-13	1	2015-06-10
3	2	3/2015	Processo 1	1	sg sdgbsg sss	1	3	2	4	48	2015-10-13	1	2015-06-10
\.


--
-- Data for Name: tb_programa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_programa (idprograma, nomprograma, desprograma, idcadastrador, datcadastro, flaativo, idresponsavel, idsimpr, idsimpreixo, idsimprareatematica) FROM stdin;
1	0. SEM PROGRAMA	Valor padrão para projetos que não estão vinculados a nenhum programa.	1	2012-05-17	S	1	1	1	1
5	3. PLANO DIRETOR DE TI	Programa que reúne os projetos coordenados pela Coordenação de Tecnologia	1	2014-01-27	S	1	1	1	1
10	2. MELHORIAS NACIONAIS	Planos de ação e projetos para melhorias nacionais	1	2014-06-10	S	1	1	1	1
9	1. PROJETOS ESTRATÉGICOS	Programa que reúne os projetos elencados como estratégicos pela Direção Geral	1	2014-05-27	S	2	1	1	1
\.


--
-- Data for Name: tb_projeto; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_projeto (idprojeto, nomcodigo, nomsigla, nomprojeto, idsetor, idgerenteprojeto, idgerenteadjunto, desprojeto, desobjetivo, datinicio, datfim, numperiodicidadeatualizacao, numcriteriofarol, idcadastrador, datcadastro, domtipoprojeto, flapublicado, flaaprovado, desresultadosobtidos, despontosfortes, despontosfracos, dessugestoes, idescritorio, flaaltagestao, idobjetivo, idacao, flacopa, idnatureza, vlrorcamentodisponivel, desjustificativa, iddemandante, idpatrocinador, datinicioplano, datfimplano, desescopo, desnaoescopo, despremissa, desrestricao, numseqprojeto, numanoprojeto, desconsideracaofinal, datenviouemailatualizacao, idprograma, nomproponente, domstatusprojeto, ano, idportfolio) FROM stdin;
1	001/2015/PMO 0	\N	01-PROJETO TESTE 01	1	3	4	O que será feito no projeto.	Objetivos do projeto.	2013-04-26	2014-09-02	15	15	1	2015-03-05	Estratégico	S	S	\N	\N	\N	\N	1	\N	1	1	S	5	10000	Justificativa do projeto.	1	2	2013-05-06	2013-05-31	INICIAÇÃO:\n1) TAP - Termo de Abertura do Projeto\nPLANEJAMENTO:\n1) Plano de Projeto\nEXECUÇÃO:\n1) DESENVOLVIMENTO dos seguintes módulos: Cadastro; Projetos; Planejamento; Segurança; Agenda; Atividade; Acordo de Cooperação; Grandes Eventos; Pesquisa de Opinião; Relatórios; Status Report\n2) HOMOLOGAÇÃO EM DESENVOLVIMENTO\n3) HOMOLOGAÇÃO EM AMBIENTE DE HOMOLOGAÇÃO\n4) Implantação em ambiente de PRODUÇÃO\nMONITORAMENTO E CONTROLE:\n1) Registros de projeto (Atas, cronograma, termo de aceite, etc)\nENCERRAMENTO:\n1) TEP - Termo de Encerramento do Projeto	1) Capacitação para os usuários da nova versão do sistema GEPnet.\n2) Aquisição de recursos de tecnologia da informação.\n3) Outras demandas estranhas ao projeto.	1) Disponibilidade dos envolvidos para atuar conforme plano de trabalho;\n2) Disponibilidade dos recursos tecnológicos de acordo com as necessidades do projeto;\n3) Disponibilidade de recursos orçamentários e financeiros para atividades de desenvolvimento e homologação;	1) Seguir os métodos e as práticas de desenvolvimento de projetos de sistemas da SLTI/MPOG.	\N	\N	Obrigado pelas considerações.	\N	1	\N	2	2015	31
2	002/2015/PMO 0	\N	02-PROJETO TESTE 02	1	3	4	Objetivo de exemplo - limite 4000 caracteres	Objetivo de exemplo - limite 4000 caracteres	2015-08-03	2016-04-04	30	30	1	2015-10-31	Normal	S	S	\N	\N	\N	\N	0	\N	1	2	S	4	100000	Justificativa de exemplo - limite 4000 caracteres	1	2	2015-08-03	2015-09-03	Escopo teste	Não escopo de teste	Premissas de exemplo	restrições de exemplo	\N	\N	Considerações finais.	\N	1	\N	2	2015	32
\.


--
-- Data for Name: tb_projetoprocesso; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_projetoprocesso (idprojetoprocesso, idprocesso, numano, domsituacao, datsituacao, idresponsavel, desprojetoprocesso, datinicioprevisto, datterminoprevisto, vlrorcamento, idcadastrador, datcadastro) FROM stdin;
2	4	2015	2	2015-06-01	1	kfjbhnskl bsbsbhbfhoshshsdoojsdodf v fvdflsdd f dlddçl d sds sdçlsdçsds d dbjdl dçlsdçldçl	2015-06-12	2015-12-31	17000	1	2015-06-11
1	5	2015	2	2015-06-10	1	bkljhb ahbh fbh akçbh adçkbhdçb hçb dçlçf kjhbd blsdbhdh	2015-06-11	2015-06-30	200000	1	2015-06-10
\.


--
-- Data for Name: tb_questionario; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_questionario (idquestionario, nomquestionario, desobservacao, tipoquestionario, idcadastrador, datcadastro, idescritorio, disponivel) FROM stdin;
1	Questionario 01	Observação de teste	2	1	2015-11-02	0	1
\.


--
-- Data for Name: tb_questionario_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_questionario_pesquisa (idquestionariopesquisa, idpesquisa, nomquestionario, desobservacao, tipoquestionario, idcadastrador, datcadastro, idescritorio) FROM stdin;
1	1	Questionario 01	Observação de teste	2	1	2015-11-02	0
\.


--
-- Data for Name: tb_questionariofrase; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_questionariofrase (idfrase, idquestionario, numordempergunta, idcadastrador, datcadastro, obrigatoriedade) FROM stdin;
10	1	1	1	2015-11-02	S
11	1	2	1	2015-11-02	N
\.


--
-- Data for Name: tb_questionariofrase_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_questionariofrase_pesquisa (idquestionariopesquisa, idfrasepesquisa, numordempergunta, datcadastro, idcadastrador, obrigatoriedade) FROM stdin;
1	57	1	2015-11-02	1	S
1	58	2	2015-11-02	1	N
\.


--
-- Data for Name: tb_r3g; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_r3g (idr3g, idprojeto, datdeteccao, desplanejado, desrealizado, descausa, desconsequencia, descontramedida, datprazocontramedida, datprazocontramedidaatraso, idcadastrador, datcadastro, desresponsavel, desobs, domtipo, domcorprazoprojeto, domstatuscontramedida, flacontramedidaefetiva) FROM stdin;
1	1	2015-10-31	o que foi planejado na qualidade	o que se conseguiu até o momento no quesito qualidade	falha de comunicação e acompanhamento deficiente da execução das atividades do projeto	atraso considerável no prazo do projeto	Reforçar os meios de comunicação e substituir o líder do grupo de trabalho X.	2015-11-05	2015-01-05	1	2015-10-31	Patrocinador	\N	3	2	1	1
\.


--
-- Data for Name: tb_recurso; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_recurso (idrecurso, ds_recurso) FROM stdin;
1	default:index
2	default:error
3	default:log
4	cadastro:index
5	cadastro:recurso
6	cadastro:permissao
7	cadastro:perfil
8	cadastro:perfilpessoa
9	cadastro:pessoa
10	cadastro:documento
11	cadastro:escritorio
12	cadastro:programa
13	cadastro:setor
14	acordocooperacao:entidadeexterna
15	acordocooperacao:instrumentocooperacao
16	agenda:index
17	evento:avaliacaoservidor
18	evento:grandeseventos
19	pesquisa:historico
20	pesquisa:pergunta
21	pesquisa:pesquisa
22	pesquisa:questionario
23	pesquisa:relatorio
24	pesquisa:responder
25	pesquisa:resposta
26	pessoal:atividade
27	planejamento:acao
28	planejamento:index
29	planejamento:portfolio
30	processo:index
31	processo:pacao
32	processo:projeto
33	projeto:atareuniao
34	projeto:comunicacao
35	projeto:contramedida
36	projeto:cronograma
37	projeto:diario
38	projeto:eap
39	projeto:gantt
40	projeto:gerencia
41	projeto:licao
42	projeto:planoprojeto
43	projeto:r3g
44	projeto:relatorio
45	projeto:rh
46	projeto:risco
47	projeto:rud
48	projeto:solicitacaomudanca
49	projeto:statusreport
50	projeto:tap
51	projeto:tep
52	projeto:termoaceite
53	projeto:termoencerramento
54	relatorio:risco
\.


--
-- Data for Name: tb_resposta; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_resposta (idresposta, numordem, flaativo, datcadastro, idcadastrador, desresposta) FROM stdin;
1	1	S	2015-11-02	1	Até um ano
3	3	S	2015-11-02	1	De três a cinco anos
4	4	S	2015-11-02	1	De cinco a 10 anos
5	5	S	2015-11-02	1	Mais de 10 anos
2	2	S	2015-11-02	1	De um a três anos
6	1	S	2015-11-02	1	Setor A
7	2	S	2015-11-02	1	Setor B
8	3	S	2015-11-02	1	Setor C
9	4	S	2015-11-02	1	Setor D
\.


--
-- Data for Name: tb_resposta_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_resposta_pesquisa (idrespostapesquisa, desresposta, numordem, flaativo, datcadastro, idcadastrador) FROM stdin;
1	Até um ano	1	S	2015-11-02	1
2	De um a três anos	2	S	2015-11-02	1
3	De três a cinco anos	3	S	2015-11-02	1
4	De cinco a 10 anos	4	S	2015-11-02	1
5	Mais de 10 anos	5	S	2015-11-02	1
6	Setor A	1	S	2015-11-02	1
7	Setor B	2	S	2015-11-02	1
8	Setor C	3	S	2015-11-02	1
9	Setor D	4	S	2015-11-02	1
\.


--
-- Data for Name: tb_respostafrase; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_respostafrase (idfrase, idresposta) FROM stdin;
10	1
10	2
10	3
10	4
10	5
11	6
11	7
11	8
11	9
\.


--
-- Data for Name: tb_respostafrase_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_respostafrase_pesquisa (idfrasepesquisa, idrespostapesquisa) FROM stdin;
57	1
57	2
57	3
57	4
57	5
58	6
58	7
58	8
58	9
\.


--
-- Data for Name: tb_resultado_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_resultado_pesquisa (id, idresultado, idfrasepesquisa, idquestionariopesquisa, desresposta, datcadastro, cpf) FROM stdin;
1	1	57	1	1	2015-11-01 23:52:20	\N
2	1	58	1	7	2015-11-01 23:52:20	\N
3	2	57	1	2	2015-11-01 23:52:28	\N
4	2	58	1	6	2015-11-01 23:52:28	\N
5	3	57	1	1	2015-11-01 23:52:34	\N
6	3	58	1	9	2015-11-01 23:52:34	\N
\.


--
-- Data for Name: tb_risco; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_risco (idrisco, idprojeto, idorigemrisco, idetapa, idtiporisco, datdeteccao, desrisco, domcorprobabilidade, domcorimpacto, domcorrisco, descausa, desconsequencia, flariscoativo, datencerramentorisco, idcadastrador, datcadastro, domtratamento, norisco, flaaprovado, datinatividade) FROM stdin;
1	1	9	2	1	2015-08-03	Indisponibilidade orçamentária em virtude de cortes no orçamento federal.	1	1	1	Plano de ajuste fiscal do Governo Federal	Paralisação do projeto	1	\N	1	2015-08-05	2	Indisponibilidade orçamentária	1	\N
2	2	9	3	1	2015-11-02	Falta de pessoal qualificado para executar operações do projeto	1	1	1	Baixa qualificação profissional	Impossibilidade de entrega do projeto	1	\N	1	2015-11-01	2	Falta de pessoal	1	\N
\.


--
-- Data for Name: tb_setor; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_setor (idsetor, nomsetor, idcadastrador, datcadastro, flaativo) FROM stdin;
2	SETOR 02	1	2010-02-01	S
3	SETOR 03	1	2010-02-01	S
5	SETOR 05	1	2010-02-01	S
1	SETOR 01	1	2010-02-01	S
4	SETOR 04	1	2010-02-01	S
\.


--
-- Data for Name: tb_statusreport; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_statusreport (idstatusreport, idprojeto, datacompanhamento, desatividadeconcluida, desatividadeandamento, desmotivoatraso, desirregularidade, idmarco, datmarcotendencia, datfimprojetotendencia, idcadastrador, datcadastro, flaaprovado, domcorrisco, descontramedida, desrisco, domstatusprojeto, dataprovacao, numpercentualconcluido, numpercentualprevisto) FROM stdin;
3	2	2015-10-31	Projeto sem acompanhamento cadastrado.	Projeto sem acompanhamento cadastrado.	Projeto sem acompanhamento cadastrado.	Projeto sem acompanhamento cadastrado.	1	\N	2015-10-31	1	2015-10-31	2	1	Projeto sem acompanhamento cadastrado.	Projeto sem acompanhamento cadastrado.	1	\N	0.00	0.00
4	1	2015-12-30	Não existem atividades.	15/11/2016 - 18/11/2016 - 3.2.2 Atividade\r\n25/10/2016 - 14/11/2016 - 3.2.1 Atividade inicial\r\n09/09/2016 - 14/09/2016 - 2.2.2 Atividade\r\n29/08/2016 - 08/09/2016 - 2.2.1 Atividade inicial\r\n21/07/2016 - 31/07/2016 - 1.2.1 Elaborar minuta de T...	Não há atraso.	1	21	\N	2016-11-29	1	2015-12-30	2	1	Reforçar os meios de comunicação e substituir o líder do grupo de trabalho X.	Descriçãoo: Indisponibilidade orçamentária em virtude de cortes no orçamento federal.\r\nCausa: Plano de ajuste fiscal do Governo Federal\r\nConsequência: Paralisação do projeto\r\n\r\n - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	2	\N	24.00	0.00
2	1	2015-08-25	Não existem atividades.	28/11/2015 - 08/12/2015 - 1.3.3 Atividade marco\r\n24/11/2015 - 27/11/2015 - 1.3.2 Atividade\r\n13/11/2015 - 23/11/2015 - 1.3.1 Atividade inicial\r\n31/10/2015 - 10/11/2015 - 1.2.1 Elaborar minuta de Termo de Encerramento - TEP\r\n16/10/2015 - 19/1...	Não há atraso.	Não há irregularidades.	21	\N	2015-12-08	11	2015-08-25	2	1	Não há contramedidas em andamento.	Descriçãoo: Indisponibilidade orçamentária em virtude de cortes no orçamento federal.\r\nCausa: Plano de ajuste fiscal do Governo Federal\r\nConsequência: Paralisação do projeto\r\n\r\n - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	4	\N	12.00	0.00
1	1	2015-08-06	Não existem atividades.	28/11/2015 - 08/12/2015 - 1.3.3 Atividade marco\r\n24/11/2015 - 27/11/2015 - 1.3.2 Atividade\r\n13/11/2015 - 23/11/2015 - 1.3.1 Atividade inicial\r\n31/10/2015 - 10/11/2015 - 1.2.1 Elaborar minuta de Termo de Encerramento - TEP\r\n16/10/2015 - 19/1...	Não há atraso.	Não há irregularidades.	21	\N	2015-12-08	1	2015-08-06	2	1	Não há contramedidas em andamento.	Descriçãoo: Indisponibilidade orçamentária em virtude de cortes no orçamento federal.\r\nCausa: Plano de ajuste fiscal do Governo Federal\r\nConsequência: Paralisação do projeto\r\n\r\n - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	2	\N	12.00	0.00
5	1	2016-01-21	11/01/2016 - 21/01/2016 - 1.1.1 Atividade inicial...	10/07/2016 - 13/07/2016 - 3.2.2 Atividade\r\n19/06/2016 - 09/07/2016 - 3.2.1 Atividade inicial\r\n15/04/2016 - 20/04/2016 - 2.2.2 Atividade\r\n04/04/2016 - 14/04/2016 - 2.2.1 Atividade inicial\r\n04/03/2016 - 14/03/2016 - 2.1.1 Atividade inicial	Não há atraso.	Não há irregularidades.	82	\N	2016-07-24	1	2016-01-21	2	1	Reforçar os meios de comunicação e substituir o líder do grupo de trabalho X.	Descriçãoo: Indisponibilidade orçamentária em virtude de cortes no orçamento federal.\r\nCausa: Plano de ajuste fiscal do Governo Federal\r\nConsequência: Paralisação do projeto\r\n\r\n - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	2	\N	27.00	7.00
6	2	2016-01-21	Não existem atividades.	15/10/2016 - 18/10/2016 - 3.2.2 Atividade\r\n04/10/2016 - 14/10/2016 - 3.2.1 Atividade inicial\r\n15/08/2016 - 20/08/2016 - 2.2.2 Atividade\r\n04/08/2016 - 14/08/2016 - 2.2.1 Atividade inicial\r\n26/06/2016 - 06/07/2016 - 1.2.1 Elaborar minuta de T...	Analisar.	Não há irregularidades.	8	\N	2016-10-29	1	2016-01-21	2	1	Não há contramedidas em andamento.	Descriçãoo: Falta de pessoal qualificado para executar operações do projeto\r\nCausa: Baixa qualificação profissional\r\nConsequência: Impossibilidade de entrega do projeto\r\n\r\n - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	2	\N	29.00	0.00
7	1	2016-02-03	Não existem atividades.	10/07/2016 - 13/07/2016 - 3.2.2 Atividade\r\n19/06/2016 - 09/07/2016 - 3.2.1 Atividade inicial\r\n15/04/2016 - 20/04/2016 - 2.2.2 Atividade\r\n04/04/2016 - 14/04/2016 - 2.2.1 Atividade inicial\r\n04/03/2016 - 14/03/2016 - 2.1.1 Atividade inicial	Não há atraso.	Não há irregularidades.	82	\N	2016-07-24	1	2016-02-03	2	1	Reforçar os meios de comunicação e substituir o líder do grupo de trabalho X.	Descriçãoo: Indisponibilidade orçamentária em virtude de cortes no orçamento federal.\r\nCausa: Plano de ajuste fiscal do Governo Federal\r\nConsequência: Paralisação do projeto\r\n\r\n - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	2	\N	34.00	7.00
\.


--
-- Data for Name: tb_tipoacordo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_tipoacordo (idtipoacordo, dsacordo, idcadastrador, dtcadastro) FROM stdin;
1	Acordo de cooperação técnica	1	2015-07-10
\.


--
-- Data for Name: tb_tipoavaliacao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_tipoavaliacao (idtipoavaliacao, noavaliacao) FROM stdin;
1	Segurança de Dignitários
2	Cooperação Policial Internacional
3	Polícia Judiciária (Plantão)
4	Polícia de Imigração
5	Inteligência
6	Segurança Aeroportuária
7	Polícia Marítima
8	Controle de Segurança Privada (Ativo)
9	Perícias externas
10	Comunicação Social
11	Planejamento, Coordenação, Administração e Logística
\.


--
-- Data for Name: tb_tipocontramedida; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_tipocontramedida (idtipocontramedida, notipocontramedida, dstipocontramedida) FROM stdin;
3	Transferir risco	Contramedida que visa transferir as consequencias do(s) risco(s) para terceiro(s).
4	Aceitar risco	Contramedida que visa aceitar os efeitos do(s) risco(s).
6	Compartilhar risco	Contramedida que visa compartilhar os efeitos do(s) risco(s) com terceiro(s). 
7	Melhorar risco (Potencializar efeitos)	Contramedida que visa potencializar os efeitos de um risco oportunidade.
5	Explorar risco (Potencializar ocorrência)	Contramedida que visa potencializar a ocorrência de um risco oportunidade.
2	Mitigar (Reduzir efeitos)	Contramedida que visa reduzir os efeitos do(s) risco(s).
1	Neutralizar (Eliminar risco)	Contra medida que procura eliminar o(s) risco(s).
\.


--
-- Data for Name: tb_tipodocumento; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_tipodocumento (idtipodocumento, nomtipodocumento, idcadastrador, datcadastro, flaativo) FROM stdin;
1	Ata de Reuniao 	1	2010-07-05	S
5	Manual	1	2010-07-15	S
7	Relatorio	1	2010-07-15	S
6	Apresentacao	1	2010-07-15	S
4	Memorando	1	2010-07-05	S
3	Artigo academico	1	2010-07-05	S
2	E-mail	1	2010-07-05	S
8	Folder	1	2010-08-02	S
10	Informação	1	2015-06-10	S
11	Termo Referência	1	2015-06-10	S
12	Mapa	1	2015-06-10	S
13	Modelo BPM	1	2015-06-10	S
9	Oficio	1	2010-08-05	S
14	Outro	1	2015-06-10	S
\.


--
-- Data for Name: tb_tipomudanca; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_tipomudanca (idtipomudanca, dsmudanca, idcadastrador, dtcadastro) FROM stdin;
1	Escopo	1	2014-02-14
2	Prazo	1	2014-02-14
3	Custo	1	2014-02-14
4	Qualidade	1	2014-02-14
5	Paralisacao	1	2014-02-14
6	Cancelamento	1	2015-05-27
\.


--
-- Data for Name: tb_tiporisco; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_tiporisco (idtiporisco, dstiporisco, idcadastrador, dtcadastro) FROM stdin;
1	Risco Ameaca	1	2014-01-30
2	Risco Oportunidade	1	2014-01-30
\.


--
-- Data for Name: tb_tiposituacaoprojeto; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_tiposituacaoprojeto (idtipo, nomtipo, desctipo, flatiposituacao) FROM stdin;
1	Proposta                                                                        	Informa que o projeto ainda não foi aprovado pelo patrocinador.	1
2	Em andamento                                                                    	Informa que o projeto foi aprovado pelo patrocionador e está em andamento. 	1
3	Concluido                                                                       	Informa que o projeto foi concluído e aprovado pelo patrocinador.	1
4	Paralisado                                                                      	Informa que o projeto encontra-se paralisado.	1
5	Cancelado                                                                       	Informa que o projeto foi cancelado sem ser concluído.	1
6	Bloqueado                                                                       	Informa que o projeto foi bloqueado por falta de acompanhamento.	2
\.


--
-- Data for Name: tb_tratamento; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_tratamento (idtratamento, dstratamento, idcadastrador, dtcadastro) FROM stdin;
1	Neutralizar	1	2015-06-10
2	Mitigar	1	2015-06-10
3	Aceitar	1	2015-06-10
4	Transferir	1	2015-06-10
5	Potencializar	1	2015-06-10
\.


--
-- Name: fk_licao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_licao
    ADD CONSTRAINT fk_licao PRIMARY KEY (idlicao);


--
-- Name: pk_acao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_acao
    ADD CONSTRAINT pk_acao PRIMARY KEY (idacao);


--
-- Name: pk_aceite; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_aceite
    ADD CONSTRAINT pk_aceite PRIMARY KEY (idaceite);


--
-- Name: pk_aceiteatividadecronograma; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_aceiteatividadecronograma
    ADD CONSTRAINT pk_aceiteatividadecronograma PRIMARY KEY (idaceiteativcronograma);


--
-- Name: pk_acordo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_acordo
    ADD CONSTRAINT pk_acordo PRIMARY KEY (idacordo);


--
-- Name: pk_acordoentidadeexterna; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_acordoentidadeexterna
    ADD CONSTRAINT pk_acordoentidadeexterna PRIMARY KEY (idacordo, identidadeexterna);


--
-- Name: pk_agenda; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_agenda
    ADD CONSTRAINT pk_agenda PRIMARY KEY (idagenda);


--
-- Name: pk_aquisicao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_aquisicao
    ADD CONSTRAINT pk_aquisicao PRIMARY KEY (idaquisicao);


--
-- Name: pk_ata; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_ata
    ADD CONSTRAINT pk_ata PRIMARY KEY (idata);


--
-- Name: pk_atividade; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_atividade
    ADD CONSTRAINT pk_atividade PRIMARY KEY (idatividade);


--
-- Name: pk_atividadecronograma; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_atividadecronograma
    ADD CONSTRAINT pk_atividadecronograma PRIMARY KEY (idatividadecronograma, idprojeto);


--
-- Name: pk_comunicacao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_comunicacao
    ADD CONSTRAINT pk_comunicacao PRIMARY KEY (idcomunicacao);


--
-- Name: pk_contramedida; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_contramedida
    ADD CONSTRAINT pk_contramedida PRIMARY KEY (idcontramedida);


--
-- Name: pk_diariobordo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_diariobordo
    ADD CONSTRAINT pk_diariobordo PRIMARY KEY (iddiariobordo);


--
-- Name: pk_documento; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_documento
    ADD CONSTRAINT pk_documento PRIMARY KEY (iddocumento);


--
-- Name: pk_elementodespesa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_elementodespesa
    ADD CONSTRAINT pk_elementodespesa PRIMARY KEY (idelementodespesa);


--
-- Name: pk_entidadeexterna; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_entidadeexterna
    ADD CONSTRAINT pk_entidadeexterna PRIMARY KEY (identidadeexterna);


--
-- Name: pk_escritorio; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_escritorio
    ADD CONSTRAINT pk_escritorio PRIMARY KEY (idescritorio);


--
-- Name: pk_etapa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_etapa
    ADD CONSTRAINT pk_etapa PRIMARY KEY (idetapa);


--
-- Name: pk_evento; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_evento
    ADD CONSTRAINT pk_evento PRIMARY KEY (idevento);


--
-- Name: pk_eventoavaliacao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_eventoavaliacao
    ADD CONSTRAINT pk_eventoavaliacao PRIMARY KEY (ideventoavaliacao);


--
-- Name: pk_frase; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_frase
    ADD CONSTRAINT pk_frase PRIMARY KEY (idfrase);


--
-- Name: pk_frasepesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_frase_pesquisa
    ADD CONSTRAINT pk_frasepesquisa PRIMARY KEY (idfrasepesquisa);


--
-- Name: pk_hstpublicacao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_hst_publicacao
    ADD CONSTRAINT pk_hstpublicacao PRIMARY KEY (idhistoricopublicacao);


--
-- Name: pk_mudanca; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_mudanca
    ADD CONSTRAINT pk_mudanca PRIMARY KEY (idmudanca);


--
-- Name: pk_natureza; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_natureza
    ADD CONSTRAINT pk_natureza PRIMARY KEY (idnatureza);


--
-- Name: pk_objetivo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_objetivo
    ADD CONSTRAINT pk_objetivo PRIMARY KEY (idobjetivo);


--
-- Name: pk_origemrisco; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_origemrisco
    ADD CONSTRAINT pk_origemrisco PRIMARY KEY (idorigemrisco);


--
-- Name: pk_p_acao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_p_acao
    ADD CONSTRAINT pk_p_acao PRIMARY KEY (id_p_acao);


--
-- Name: pk_perfil; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_perfil
    ADD CONSTRAINT pk_perfil PRIMARY KEY (idperfil);


--
-- Name: pk_perfilpessoa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_perfilpessoa
    ADD CONSTRAINT pk_perfilpessoa PRIMARY KEY (idperfilpessoa);


--
-- Name: pk_permissao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_permissao
    ADD CONSTRAINT pk_permissao PRIMARY KEY (idpermissao);


--
-- Name: pk_permissaoperfil; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_permissaoperfil
    ADD CONSTRAINT pk_permissaoperfil PRIMARY KEY (idpermissaoperfil);


--
-- Name: pk_pesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_pesquisa
    ADD CONSTRAINT pk_pesquisa PRIMARY KEY (idpesquisa);


--
-- Name: pk_pessoa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_pessoa
    ADD CONSTRAINT pk_pessoa PRIMARY KEY (idpessoa);


--
-- Name: pk_pessoaagenda; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_pessoaagenda
    ADD CONSTRAINT pk_pessoaagenda PRIMARY KEY (idagenda, idpessoa);


--
-- Name: pk_portfolio; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_portfolio
    ADD CONSTRAINT pk_portfolio PRIMARY KEY (idportfolio);


--
-- Name: pk_portifolioprograma; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_portifolioprograma
    ADD CONSTRAINT pk_portifolioprograma PRIMARY KEY (idprograma, idportfolio);


--
-- Name: pk_processo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_processo
    ADD CONSTRAINT pk_processo PRIMARY KEY (idprocesso);


--
-- Name: pk_programa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_programa
    ADD CONSTRAINT pk_programa PRIMARY KEY (idprograma);


--
-- Name: pk_projeto; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_projeto
    ADD CONSTRAINT pk_projeto PRIMARY KEY (idprojeto);


--
-- Name: pk_projetoprocesso; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_projetoprocesso
    ADD CONSTRAINT pk_projetoprocesso PRIMARY KEY (idprojetoprocesso);


--
-- Name: pk_questionario; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_questionario
    ADD CONSTRAINT pk_questionario PRIMARY KEY (idquestionario);


--
-- Name: pk_questionariofrase; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_questionariofrase
    ADD CONSTRAINT pk_questionariofrase PRIMARY KEY (idfrase, idquestionario);


--
-- Name: pk_questionariofrase_pesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_questionariofrase_pesquisa
    ADD CONSTRAINT pk_questionariofrase_pesquisa PRIMARY KEY (idquestionariopesquisa, idfrasepesquisa);


--
-- Name: pk_questionariopesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_questionario_pesquisa
    ADD CONSTRAINT pk_questionariopesquisa PRIMARY KEY (idquestionariopesquisa);


--
-- Name: pk_r3g; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_r3g
    ADD CONSTRAINT pk_r3g PRIMARY KEY (idr3g);


--
-- Name: pk_resposta; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_resposta
    ADD CONSTRAINT pk_resposta PRIMARY KEY (idresposta);


--
-- Name: pk_respostafrase_pesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_respostafrase_pesquisa
    ADD CONSTRAINT pk_respostafrase_pesquisa PRIMARY KEY (idfrasepesquisa, idrespostapesquisa);


--
-- Name: pk_respostapesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_resposta_pesquisa
    ADD CONSTRAINT pk_respostapesquisa PRIMARY KEY (idrespostapesquisa);


--
-- Name: pk_resultadopesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_resultado_pesquisa
    ADD CONSTRAINT pk_resultadopesquisa PRIMARY KEY (id, idresultado, idfrasepesquisa, idquestionariopesquisa);


--
-- Name: pk_risco; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_risco
    ADD CONSTRAINT pk_risco PRIMARY KEY (idrisco);


--
-- Name: pk_setor; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_setor
    ADD CONSTRAINT pk_setor PRIMARY KEY (idsetor);


--
-- Name: pk_statusreport; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_statusreport
    ADD CONSTRAINT pk_statusreport PRIMARY KEY (idstatusreport);


--
-- Name: pk_tb_acordoespecieinstrumento; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_acordoespecieinstrumento
    ADD CONSTRAINT pk_tb_acordoespecieinstrumento PRIMARY KEY (idacordoespecieinstrumento);


--
-- Name: pk_tb_atividadepredecessora; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_atividadepredecessora
    ADD CONSTRAINT pk_tb_atividadepredecessora PRIMARY KEY (idatividadepredecessora, idprojeto, idatividade);


--
-- Name: pk_tb_parteinteressada; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_parteinteressada
    ADD CONSTRAINT pk_tb_parteinteressada PRIMARY KEY (idparteinteressada);


--
-- Name: pk_tb_recurso; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_recurso
    ADD CONSTRAINT pk_tb_recurso PRIMARY KEY (idrecurso);


--
-- Name: pk_tb_tipoavaliacao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_tipoavaliacao
    ADD CONSTRAINT pk_tb_tipoavaliacao PRIMARY KEY (idtipoavaliacao);


--
-- Name: pk_tb_tipocontramedida; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_tipocontramedida
    ADD CONSTRAINT pk_tb_tipocontramedida PRIMARY KEY (idtipocontramedida);


--
-- Name: pk_tipoacordo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_tipoacordo
    ADD CONSTRAINT pk_tipoacordo PRIMARY KEY (idtipoacordo);


--
-- Name: pk_tipodocumento; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_tipodocumento
    ADD CONSTRAINT pk_tipodocumento PRIMARY KEY (idtipodocumento);


--
-- Name: pk_tipomudanca; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_tipomudanca
    ADD CONSTRAINT pk_tipomudanca PRIMARY KEY (idtipomudanca);


--
-- Name: pk_tiporisco; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_tiporisco
    ADD CONSTRAINT pk_tiporisco PRIMARY KEY (idtiporisco);


--
-- Name: pk_tratamento; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_tratamento
    ADD CONSTRAINT pk_tratamento PRIMARY KEY (idtratamento);


--
-- Name: tb_tiposituacaoprojeto_pkey; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tb_tiposituacaoprojeto
    ADD CONSTRAINT tb_tiposituacaoprojeto_pkey PRIMARY KEY (idtipo);


--
-- Name: id_escritorio; Type: INDEX; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX id_escritorio ON tb_escritorio USING btree (nomescritorio2);


--
-- Name: id_perfil_pessoa; Type: INDEX; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX id_perfil_pessoa ON tb_perfilpessoa USING btree (idpessoa, idperfil, idescritorio);


--
-- Name: id_permissaoperfil; Type: INDEX; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX id_permissaoperfil ON tb_permissaoperfil USING btree (idpermissaoperfil);


--
-- Name: id_recurso; Type: INDEX; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX id_recurso ON tb_permissao USING btree (idrecurso, idpermissao);


--
-- Name: idx_codprojeto_domtipoatividade; Type: INDEX; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_codprojeto_domtipoatividade ON tb_atividadecronograma USING btree (idprojeto, domtipoatividade);


--
-- Name: idx_cpf; Type: INDEX; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX idx_cpf ON tb_pessoa USING btree (numcpf);


--
-- Name: idx_escritorio; Type: INDEX; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX idx_escritorio ON tb_documento USING btree (iddocumento, idescritorio);


--
-- Name: idx_grupo; Type: INDEX; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE INDEX idx_grupo ON tb_atividadecronograma USING btree (idprojeto, idgrupo);


--
-- Name: idx_permissaoperfil; Type: INDEX; Schema: agepnet200; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX idx_permissaoperfil ON tb_permissaoperfil USING btree (idpermissao, idperfil);


--
-- Name: fk_acao_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_acao
    ADD CONSTRAINT fk_acao_escritorio FOREIGN KEY (idescritorio) REFERENCES tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acao_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_acao
    ADD CONSTRAINT fk_acao_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acao_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_projeto
    ADD CONSTRAINT fk_acao_projeto FOREIGN KEY (idacao) REFERENCES tb_acao(idacao) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acao_projetoprocesso; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_p_acao
    ADD CONSTRAINT fk_acao_projetoprocesso FOREIGN KEY (idprojetoprocesso) REFERENCES tb_projetoprocesso(idprojetoprocesso) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acao_setorresponsavel; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_p_acao
    ADD CONSTRAINT fk_acao_setorresponsavel FOREIGN KEY (idsetorresponsavel) REFERENCES tb_setor(idsetor) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_aceiteativcronograma_aceite; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_aceiteatividadecronograma
    ADD CONSTRAINT fk_aceiteativcronograma_aceite FOREIGN KEY (idaceite) REFERENCES tb_aceite(idaceite) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acordo_acordoespecieinstrumento; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_acordo
    ADD CONSTRAINT fk_acordo_acordoespecieinstrumento FOREIGN KEY (idacordoespecieinstrumento) REFERENCES tb_acordoespecieinstrumento(idacordoespecieinstrumento) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acordo_acordopai; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_acordo
    ADD CONSTRAINT fk_acordo_acordopai FOREIGN KEY (idacordopai) REFERENCES tb_acordo(idacordo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acordo_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_acordo
    ADD CONSTRAINT fk_acordo_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acordo_pesfiscal; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_acordo
    ADD CONSTRAINT fk_acordo_pesfiscal FOREIGN KEY (idfiscal) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acordo_pesfiscal2; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_acordo
    ADD CONSTRAINT fk_acordo_pesfiscal2 FOREIGN KEY (idfiscal2) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acordo_pesfiscal3; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_acordo
    ADD CONSTRAINT fk_acordo_pesfiscal3 FOREIGN KEY (idfiscal3) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acordo_pesresponsavelinterino; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_acordo
    ADD CONSTRAINT fk_acordo_pesresponsavelinterino FOREIGN KEY (idresponsavelinterno) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acordo_setor; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_acordo
    ADD CONSTRAINT fk_acordo_setor FOREIGN KEY (idsetor) REFERENCES tb_setor(idsetor) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acordo_tipoacordo; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_acordo
    ADD CONSTRAINT fk_acordo_tipoacordo FOREIGN KEY (idtipoacordo) REFERENCES tb_tipoacordo(idtipoacordo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_acordoespecieinstrumento_cadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_acordoespecieinstrumento
    ADD CONSTRAINT fk_acordoespecieinstrumento_cadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_agenda_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_agenda
    ADD CONSTRAINT fk_agenda_escritorio FOREIGN KEY (idescritorio) REFERENCES tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_alterador_diariobordo; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_diariobordo
    ADD CONSTRAINT fk_alterador_diariobordo FOREIGN KEY (idalterador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_aquisicao_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_aquisicao
    ADD CONSTRAINT fk_aquisicao_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_atividade_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_atividade
    ADD CONSTRAINT fk_atividade_escritorio FOREIGN KEY (idescritorio) REFERENCES tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_atividade_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_atividade
    ADD CONSTRAINT fk_atividade_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_atividade_pesresponsavel; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_atividade
    ADD CONSTRAINT fk_atividade_pesresponsavel FOREIGN KEY (idresponsavel) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_atividadecrono_elementodespesa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_atividadecronograma
    ADD CONSTRAINT fk_atividadecrono_elementodespesa FOREIGN KEY (idelementodespesa) REFERENCES tb_elementodespesa(idelementodespesa) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: fk_atividadecrono_marcoanterior; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_atividadecronograma
    ADD CONSTRAINT fk_atividadecrono_marcoanterior FOREIGN KEY (idmarcoanterior, idprojeto) REFERENCES tb_atividadecronograma(idatividadecronograma, idprojeto) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_atividadecrono_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_atividadecronograma
    ADD CONSTRAINT fk_atividadecrono_projeto FOREIGN KEY (idprojeto) REFERENCES tb_projeto(idprojeto) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_cadastrador_diariobordo; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_diariobordo
    ADD CONSTRAINT fk_cadastrador_diariobordo FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_cominicacao_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_comunicacao
    ADD CONSTRAINT fk_cominicacao_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_contramedida_risco; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_contramedida
    ADD CONSTRAINT fk_contramedida_risco FOREIGN KEY (idrisco) REFERENCES tb_risco(idrisco) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: fk_conunicacao_parteinteressada; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_comunicacao
    ADD CONSTRAINT fk_conunicacao_parteinteressada FOREIGN KEY (idresponsavel) REFERENCES tb_parteinteressada(idparteinteressada) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_diariobordo_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_diariobordo
    ADD CONSTRAINT fk_diariobordo_projeto FOREIGN KEY (idprojeto) REFERENCES tb_projeto(idprojeto) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_documento_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_documento
    ADD CONSTRAINT fk_documento_escritorio FOREIGN KEY (idescritorio) REFERENCES tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_documento_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_documento
    ADD CONSTRAINT fk_documento_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_documento_tipodocumento; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_documento
    ADD CONSTRAINT fk_documento_tipodocumento FOREIGN KEY (idtipodocumento) REFERENCES tb_tipodocumento(idtipodocumento) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_entidadeexterna_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_entidadeexterna
    ADD CONSTRAINT fk_entidadeexterna_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_escritorio_escritoriopai; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_escritorio
    ADD CONSTRAINT fk_escritorio_escritoriopai FOREIGN KEY (idescritoriope) REFERENCES tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_eventoavaliacao_evento; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_eventoavaliacao
    ADD CONSTRAINT fk_eventoavaliacao_evento FOREIGN KEY (idevento) REFERENCES tb_evento(idevento) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_eventoavaliacao_tipoavaliacao; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_eventoavaliacao
    ADD CONSTRAINT fk_eventoavaliacao_tipoavaliacao FOREIGN KEY (idtipoavaliacao) REFERENCES tb_tipoavaliacao(idtipoavaliacao) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_frase_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_frase
    ADD CONSTRAINT fk_frase_escritorio FOREIGN KEY (idescritorio) REFERENCES tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_frase_pergunta; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_respostafrase
    ADD CONSTRAINT fk_frase_pergunta FOREIGN KEY (idfrase) REFERENCES tb_frase(idfrase) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_frasepesquisa_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_frase_pesquisa
    ADD CONSTRAINT fk_frasepesquisa_escritorio FOREIGN KEY (idescritorio) REFERENCES tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_frasepesquisa_frase; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_resultado_pesquisa
    ADD CONSTRAINT fk_frasepesquisa_frase FOREIGN KEY (idfrasepesquisa) REFERENCES tb_frase_pesquisa(idfrasepesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_fraseresultadopesquisa_frase; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_respostafrase_pesquisa
    ADD CONSTRAINT fk_fraseresultadopesquisa_frase FOREIGN KEY (idfrasepesquisa) REFERENCES tb_frase_pesquisa(idfrasepesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_fraseresultadopesquisa_resultado; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_respostafrase_pesquisa
    ADD CONSTRAINT fk_fraseresultadopesquisa_resultado FOREIGN KEY (idrespostapesquisa) REFERENCES tb_resposta_pesquisa(idrespostapesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_grupo_atividade; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_atividadecronograma
    ADD CONSTRAINT fk_grupo_atividade FOREIGN KEY (idgrupo, idprojeto) REFERENCES tb_atividadecronograma(idatividadecronograma, idprojeto) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: fk_historicopesquisa_pesquisa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_hst_publicacao
    ADD CONSTRAINT fk_historicopesquisa_pesquisa FOREIGN KEY (idpesquisa) REFERENCES tb_pesquisa(idpesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_idatividadecronograma; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_atividadecronograma
    ADD CONSTRAINT fk_idatividadecronograma FOREIGN KEY (idatividadecronograma, idprojeto) REFERENCES tb_atividadecronograma(idatividadecronograma, idprojeto) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: fk_mudanca_tipomudanca; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_mudanca
    ADD CONSTRAINT fk_mudanca_tipomudanca FOREIGN KEY (idtipomudanca) REFERENCES tb_tipomudanca(idtipomudanca) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_objetivo_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_objetivo
    ADD CONSTRAINT fk_objetivo_escritorio FOREIGN KEY (codescritorio) REFERENCES tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_objetivo_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_objetivo
    ADD CONSTRAINT fk_objetivo_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_parteinteressada_atividadecronograma; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_atividadecronograma
    ADD CONSTRAINT fk_parteinteressada_atividadecronograma FOREIGN KEY (idparteinteressada) REFERENCES tb_parteinteressada(idparteinteressada) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_perfil_permissaoperfil; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_permissaoperfil
    ADD CONSTRAINT fk_perfil_permissaoperfil FOREIGN KEY (idperfil) REFERENCES tb_perfil(idperfil) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_perfilpessoa_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_perfilpessoa
    ADD CONSTRAINT fk_perfilpessoa_escritorio FOREIGN KEY (idescritorio) REFERENCES tb_escritorio(idescritorio);


--
-- Name: fk_pergunta_frase; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_respostafrase
    ADD CONSTRAINT fk_pergunta_frase FOREIGN KEY (idresposta) REFERENCES tb_resposta(idresposta) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_permissao_permissaoperfil; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_permissaoperfil
    ADD CONSTRAINT fk_permissao_permissaoperfil FOREIGN KEY (idpermissao) REFERENCES tb_permissao(idpermissao) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_pesquisaquestionario_questionario; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_pesquisa
    ADD CONSTRAINT fk_pesquisaquestionario_questionario FOREIGN KEY (idquestionario) REFERENCES tb_questionario(idquestionario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_pessoa_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_pessoa
    ADD CONSTRAINT fk_pessoa_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_pessoaagenda_agenda; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_pessoaagenda
    ADD CONSTRAINT fk_pessoaagenda_agenda FOREIGN KEY (idagenda) REFERENCES tb_agenda(idagenda) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_pessoaperfil_perfil; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_perfilpessoa
    ADD CONSTRAINT fk_pessoaperfil_perfil FOREIGN KEY (idperfil) REFERENCES tb_perfil(idperfil) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_portfolio_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_portfolio
    ADD CONSTRAINT fk_portfolio_escritorio FOREIGN KEY (idescritorio) REFERENCES tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_portfolio_portfoliopai; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_portfolio
    ADD CONSTRAINT fk_portfolio_portfoliopai FOREIGN KEY (idportfoliopai) REFERENCES tb_portfolio(idportfolio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_portifolioprograma_portifolio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_portifolioprograma
    ADD CONSTRAINT fk_portifolioprograma_portifolio FOREIGN KEY (idportfolio) REFERENCES tb_portfolio(idportfolio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_portifolioprograma_programa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_portifolioprograma
    ADD CONSTRAINT fk_portifolioprograma_programa FOREIGN KEY (idprograma) REFERENCES tb_programa(idprograma) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_processo_setor; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_processo
    ADD CONSTRAINT fk_processo_setor FOREIGN KEY (idsetor) REFERENCES tb_setor(idsetor) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_projeto_natureza; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_projeto
    ADD CONSTRAINT fk_projeto_natureza FOREIGN KEY (idnatureza) REFERENCES tb_natureza(idnatureza) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_projeto_objetivo; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_projeto
    ADD CONSTRAINT fk_projeto_objetivo FOREIGN KEY (idobjetivo) REFERENCES tb_objetivo(idobjetivo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_projeto_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_projeto
    ADD CONSTRAINT fk_projeto_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_projeto_pesdemandante; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_projeto
    ADD CONSTRAINT fk_projeto_pesdemandante FOREIGN KEY (iddemandante) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_projeto_pesgeradjunto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_projeto
    ADD CONSTRAINT fk_projeto_pesgeradjunto FOREIGN KEY (idgerenteadjunto) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_projeto_pespatrocinador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_projeto
    ADD CONSTRAINT fk_projeto_pespatrocinador FOREIGN KEY (idpatrocinador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_projeto_pessoagerente; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_projeto
    ADD CONSTRAINT fk_projeto_pessoagerente FOREIGN KEY (idgerenteprojeto) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_projeto_portfolio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_projeto
    ADD CONSTRAINT fk_projeto_portfolio FOREIGN KEY (idportfolio) REFERENCES tb_portfolio(idportfolio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_projeto_programa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_projeto
    ADD CONSTRAINT fk_projeto_programa FOREIGN KEY (idprograma) REFERENCES tb_programa(idprograma) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_projeto_setor; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_projeto
    ADD CONSTRAINT fk_projeto_setor FOREIGN KEY (idsetor) REFERENCES tb_setor(idsetor) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_projetoprocesso_processo; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_projetoprocesso
    ADD CONSTRAINT fk_projetoprocesso_processo FOREIGN KEY (idprocesso) REFERENCES tb_processo(idprocesso) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_questionario_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_questionario
    ADD CONSTRAINT fk_questionario_escritorio FOREIGN KEY (idescritorio) REFERENCES tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_questionariofrase_frase; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_questionariofrase
    ADD CONSTRAINT fk_questionariofrase_frase FOREIGN KEY (idfrase) REFERENCES tb_frase(idfrase) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_questionariofrase_questionario; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_questionariofrase
    ADD CONSTRAINT fk_questionariofrase_questionario FOREIGN KEY (idquestionario) REFERENCES tb_questionario(idquestionario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_questionariofrasepesquisa_frase; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_questionariofrase_pesquisa
    ADD CONSTRAINT fk_questionariofrasepesquisa_frase FOREIGN KEY (idfrasepesquisa) REFERENCES tb_frase_pesquisa(idfrasepesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_questionariofrasepesquisa_quest; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_questionariofrase_pesquisa
    ADD CONSTRAINT fk_questionariofrasepesquisa_quest FOREIGN KEY (idquestionariopesquisa) REFERENCES tb_questionario_pesquisa(idquestionariopesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_questionariopesquisa_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_questionario_pesquisa
    ADD CONSTRAINT fk_questionariopesquisa_escritorio FOREIGN KEY (idescritorio) REFERENCES tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_questionariopesquisa_pesquisa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_questionario_pesquisa
    ADD CONSTRAINT fk_questionariopesquisa_pesquisa FOREIGN KEY (idpesquisa) REFERENCES tb_pesquisa(idpesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_questionariopesquisa_questionario; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_resultado_pesquisa
    ADD CONSTRAINT fk_questionariopesquisa_questionario FOREIGN KEY (idquestionariopesquisa) REFERENCES tb_questionario_pesquisa(idquestionariopesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_recurso_permissao; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_permissao
    ADD CONSTRAINT fk_recurso_permissao FOREIGN KEY (idrecurso) REFERENCES tb_recurso(idrecurso) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_risco_etapa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_risco
    ADD CONSTRAINT fk_risco_etapa FOREIGN KEY (idetapa) REFERENCES tb_etapa(idetapa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_risco_origemrisco; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_risco
    ADD CONSTRAINT fk_risco_origemrisco FOREIGN KEY (idorigemrisco) REFERENCES tb_origemrisco(idorigemrisco) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_risco_tiporisco; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_risco
    ADD CONSTRAINT fk_risco_tiporisco FOREIGN KEY (idtiporisco) REFERENCES tb_tiporisco(idtiporisco) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_statusreport_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_statusreport
    ADD CONSTRAINT fk_statusreport_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_statusreport_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_statusreport
    ADD CONSTRAINT fk_statusreport_projeto FOREIGN KEY (idprojeto) REFERENCES tb_projeto(idprojeto) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_tipoacordo_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_tipoacordo
    ADD CONSTRAINT fk_tipoacordo_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: fk_tratamento_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY tb_tratamento
    ADD CONSTRAINT fk_tratamento_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- Name: agepnet200; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA agepnet200 FROM PUBLIC;
REVOKE ALL ON SCHEMA agepnet200 FROM postgres;
GRANT ALL ON SCHEMA agepnet200 TO postgres;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- Name: tb_acao; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_acao FROM PUBLIC;
REVOKE ALL ON TABLE tb_acao FROM postgres;
GRANT ALL ON TABLE tb_acao TO postgres;


--
-- Name: tb_aceite; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_aceite FROM PUBLIC;
REVOKE ALL ON TABLE tb_aceite FROM postgres;
GRANT ALL ON TABLE tb_aceite TO postgres;


--
-- Name: tb_aceiteatividadecronograma; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_aceiteatividadecronograma FROM PUBLIC;
REVOKE ALL ON TABLE tb_aceiteatividadecronograma FROM postgres;
GRANT ALL ON TABLE tb_aceiteatividadecronograma TO postgres;


--
-- Name: tb_acordo; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_acordo FROM PUBLIC;
REVOKE ALL ON TABLE tb_acordo FROM postgres;
GRANT ALL ON TABLE tb_acordo TO postgres;


--
-- Name: tb_acordoentidadeexterna; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_acordoentidadeexterna FROM PUBLIC;
REVOKE ALL ON TABLE tb_acordoentidadeexterna FROM postgres;
GRANT ALL ON TABLE tb_acordoentidadeexterna TO postgres;


--
-- Name: tb_acordoespecieinstrumento; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_acordoespecieinstrumento FROM PUBLIC;
REVOKE ALL ON TABLE tb_acordoespecieinstrumento FROM postgres;
GRANT ALL ON TABLE tb_acordoespecieinstrumento TO postgres;


--
-- Name: tb_agenda; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_agenda FROM PUBLIC;
REVOKE ALL ON TABLE tb_agenda FROM postgres;
GRANT ALL ON TABLE tb_agenda TO postgres;


--
-- Name: tb_aquisicao; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_aquisicao FROM PUBLIC;
REVOKE ALL ON TABLE tb_aquisicao FROM postgres;
GRANT ALL ON TABLE tb_aquisicao TO postgres;


--
-- Name: tb_ata; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_ata FROM PUBLIC;
REVOKE ALL ON TABLE tb_ata FROM postgres;
GRANT ALL ON TABLE tb_ata TO postgres;


--
-- Name: tb_atividadecronograma; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_atividadecronograma FROM PUBLIC;
REVOKE ALL ON TABLE tb_atividadecronograma FROM postgres;
GRANT ALL ON TABLE tb_atividadecronograma TO postgres;


--
-- Name: tb_atividadepredecessora; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_atividadepredecessora FROM PUBLIC;
REVOKE ALL ON TABLE tb_atividadepredecessora FROM postgres;
GRANT ALL ON TABLE tb_atividadepredecessora TO postgres;


--
-- Name: tb_comunicacao; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_comunicacao FROM PUBLIC;
REVOKE ALL ON TABLE tb_comunicacao FROM postgres;
GRANT ALL ON TABLE tb_comunicacao TO postgres;


--
-- Name: tb_diariobordo; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_diariobordo FROM PUBLIC;
REVOKE ALL ON TABLE tb_diariobordo FROM postgres;
GRANT ALL ON TABLE tb_diariobordo TO postgres;


--
-- Name: tb_documento; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_documento FROM PUBLIC;
REVOKE ALL ON TABLE tb_documento FROM postgres;
GRANT ALL ON TABLE tb_documento TO postgres;


--
-- Name: tb_elementodespesa; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_elementodespesa FROM PUBLIC;
REVOKE ALL ON TABLE tb_elementodespesa FROM postgres;
GRANT ALL ON TABLE tb_elementodespesa TO postgres;


--
-- Name: tb_entidadeexterna; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_entidadeexterna FROM PUBLIC;
REVOKE ALL ON TABLE tb_entidadeexterna FROM postgres;
GRANT ALL ON TABLE tb_entidadeexterna TO postgres;


--
-- Name: tb_escritorio; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_escritorio FROM PUBLIC;
REVOKE ALL ON TABLE tb_escritorio FROM postgres;
GRANT ALL ON TABLE tb_escritorio TO postgres;


--
-- Name: tb_etapa; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_etapa FROM PUBLIC;
REVOKE ALL ON TABLE tb_etapa FROM postgres;
GRANT ALL ON TABLE tb_etapa TO postgres;


--
-- Name: tb_evento; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_evento FROM PUBLIC;
REVOKE ALL ON TABLE tb_evento FROM postgres;
GRANT ALL ON TABLE tb_evento TO postgres;


--
-- Name: tb_eventoavaliacao; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_eventoavaliacao FROM PUBLIC;
REVOKE ALL ON TABLE tb_eventoavaliacao FROM postgres;
GRANT ALL ON TABLE tb_eventoavaliacao TO postgres;


--
-- Name: tb_hst_publicacao; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_hst_publicacao FROM PUBLIC;
REVOKE ALL ON TABLE tb_hst_publicacao FROM postgres;
GRANT ALL ON TABLE tb_hst_publicacao TO postgres;


--
-- Name: tb_licao; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_licao FROM PUBLIC;
REVOKE ALL ON TABLE tb_licao FROM postgres;
GRANT ALL ON TABLE tb_licao TO postgres;


--
-- Name: tb_mudanca; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_mudanca FROM PUBLIC;
REVOKE ALL ON TABLE tb_mudanca FROM postgres;
GRANT ALL ON TABLE tb_mudanca TO postgres;


--
-- Name: tb_natureza; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_natureza FROM PUBLIC;
REVOKE ALL ON TABLE tb_natureza FROM postgres;
GRANT ALL ON TABLE tb_natureza TO postgres;


--
-- Name: tb_objetivo; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_objetivo FROM PUBLIC;
REVOKE ALL ON TABLE tb_objetivo FROM postgres;
GRANT ALL ON TABLE tb_objetivo TO postgres;


--
-- Name: tb_origemrisco; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_origemrisco FROM PUBLIC;
REVOKE ALL ON TABLE tb_origemrisco FROM postgres;
GRANT ALL ON TABLE tb_origemrisco TO postgres;


--
-- Name: tb_p_acao; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_p_acao FROM PUBLIC;
REVOKE ALL ON TABLE tb_p_acao FROM postgres;
GRANT ALL ON TABLE tb_p_acao TO postgres;


--
-- Name: tb_parteinteressada; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_parteinteressada FROM PUBLIC;
REVOKE ALL ON TABLE tb_parteinteressada FROM postgres;
GRANT ALL ON TABLE tb_parteinteressada TO postgres;


--
-- Name: tb_perfil; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_perfil FROM PUBLIC;
REVOKE ALL ON TABLE tb_perfil FROM postgres;
GRANT ALL ON TABLE tb_perfil TO postgres;


--
-- Name: tb_perfilpessoa; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_perfilpessoa FROM PUBLIC;
REVOKE ALL ON TABLE tb_perfilpessoa FROM postgres;
GRANT ALL ON TABLE tb_perfilpessoa TO postgres;


--
-- Name: tb_permissao; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_permissao FROM PUBLIC;
REVOKE ALL ON TABLE tb_permissao FROM postgres;
GRANT ALL ON TABLE tb_permissao TO postgres;


--
-- Name: tb_permissaoperfil; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_permissaoperfil FROM PUBLIC;
REVOKE ALL ON TABLE tb_permissaoperfil FROM postgres;
GRANT ALL ON TABLE tb_permissaoperfil TO postgres;


--
-- Name: tb_pessoa; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_pessoa FROM PUBLIC;
REVOKE ALL ON TABLE tb_pessoa FROM postgres;
GRANT ALL ON TABLE tb_pessoa TO postgres;


--
-- Name: tb_pessoaagenda; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_pessoaagenda FROM PUBLIC;
REVOKE ALL ON TABLE tb_pessoaagenda FROM postgres;
GRANT ALL ON TABLE tb_pessoaagenda TO postgres;


--
-- Name: tb_portfolio; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_portfolio FROM PUBLIC;
REVOKE ALL ON TABLE tb_portfolio FROM postgres;
GRANT ALL ON TABLE tb_portfolio TO postgres;


--
-- Name: tb_portifolioprograma; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_portifolioprograma FROM PUBLIC;
REVOKE ALL ON TABLE tb_portifolioprograma FROM postgres;
GRANT ALL ON TABLE tb_portifolioprograma TO postgres;


--
-- Name: tb_processo; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_processo FROM PUBLIC;
REVOKE ALL ON TABLE tb_processo FROM postgres;
GRANT ALL ON TABLE tb_processo TO postgres;


--
-- Name: tb_programa; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_programa FROM PUBLIC;
REVOKE ALL ON TABLE tb_programa FROM postgres;
GRANT ALL ON TABLE tb_programa TO postgres;


--
-- Name: tb_projeto; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_projeto FROM PUBLIC;
REVOKE ALL ON TABLE tb_projeto FROM postgres;
GRANT ALL ON TABLE tb_projeto TO postgres;


--
-- Name: tb_projetoprocesso; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_projetoprocesso FROM PUBLIC;
REVOKE ALL ON TABLE tb_projetoprocesso FROM postgres;
GRANT ALL ON TABLE tb_projetoprocesso TO postgres;


--
-- Name: tb_r3g; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_r3g FROM PUBLIC;
REVOKE ALL ON TABLE tb_r3g FROM postgres;
GRANT ALL ON TABLE tb_r3g TO postgres;


--
-- Name: tb_recurso; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_recurso FROM PUBLIC;
REVOKE ALL ON TABLE tb_recurso FROM postgres;
GRANT ALL ON TABLE tb_recurso TO postgres;


--
-- Name: tb_resultado_pesquisa; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_resultado_pesquisa FROM PUBLIC;
REVOKE ALL ON TABLE tb_resultado_pesquisa FROM postgres;
GRANT ALL ON TABLE tb_resultado_pesquisa TO postgres;


--
-- Name: tb_risco; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_risco FROM PUBLIC;
REVOKE ALL ON TABLE tb_risco FROM postgres;
GRANT ALL ON TABLE tb_risco TO postgres;


--
-- Name: tb_setor; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_setor FROM PUBLIC;
REVOKE ALL ON TABLE tb_setor FROM postgres;
GRANT ALL ON TABLE tb_setor TO postgres;


--
-- Name: tb_statusreport; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_statusreport FROM PUBLIC;
REVOKE ALL ON TABLE tb_statusreport FROM postgres;
GRANT ALL ON TABLE tb_statusreport TO postgres;


--
-- Name: tb_tipoacordo; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_tipoacordo FROM PUBLIC;
REVOKE ALL ON TABLE tb_tipoacordo FROM postgres;
GRANT ALL ON TABLE tb_tipoacordo TO postgres;


--
-- Name: tb_tipoavaliacao; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_tipoavaliacao FROM PUBLIC;
REVOKE ALL ON TABLE tb_tipoavaliacao FROM postgres;
GRANT ALL ON TABLE tb_tipoavaliacao TO postgres;


--
-- Name: tb_tipodocumento; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_tipodocumento FROM PUBLIC;
REVOKE ALL ON TABLE tb_tipodocumento FROM postgres;
GRANT ALL ON TABLE tb_tipodocumento TO postgres;


--
-- Name: tb_tipomudanca; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_tipomudanca FROM PUBLIC;
REVOKE ALL ON TABLE tb_tipomudanca FROM postgres;
GRANT ALL ON TABLE tb_tipomudanca TO postgres;


--
-- Name: tb_tiporisco; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_tiporisco FROM PUBLIC;
REVOKE ALL ON TABLE tb_tiporisco FROM postgres;
GRANT ALL ON TABLE tb_tiporisco TO postgres;


--
-- Name: tb_tratamento; Type: ACL; Schema: agepnet200; Owner: postgres
--

REVOKE ALL ON TABLE tb_tratamento FROM PUBLIC;
REVOKE ALL ON TABLE tb_tratamento FROM postgres;
GRANT ALL ON TABLE tb_tratamento TO postgres;


--
-- PostgreSQL database dump complete
--

