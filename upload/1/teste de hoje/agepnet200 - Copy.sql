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
    domcargo character varying(5) NOT NULL,
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
42	1	\N	3. GRUPO EXECUÇÃO	1	\N	2015-10-31 12:56:21.075123+00	\N	\N	0	0	0	\N	\N	\N	\N	2016-03-11	2016-07-09	\N	\N	\N	2016-03-11	2016-07-09	25.50	\N	0
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
\.


--
-- Data for Name: tb_permissao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_permissao (idpermissao, idrecurso, ds_permissao, no_permissao) FROM stdin;
\.


--
-- Data for Name: tb_permissaoperfil; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY tb_permissaoperfil (idpermissaoperfil, idperfil, idpermissao) FROM stdin;
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
4	Usuario 04		6199999999	61999990909	usuario04@gepnet2.gov	1	2015-03-03	9996	Consultor	12002	PPP	5275	S	95881811607	\N	2e19c5b7a1c312aeb4e27f986dcdfa80                                                                                                                                                                                                                               	PMO 1                                                                                               
1	Usuario 01		6199999999	61999999999	usuario01@gepnet2.gov	1	2015-03-03	9999	Consultor Sênior	12002	AAA	19920	S	22355653100	\N	0e1177622dc1d5506b5add8829b504a7                                                                                                                                                                                                                               	PMO 1                                                                                               
2	Usuario 02		0000000000	61999999999	usuario02@gepnet.gov	1	2015-03-03	9998	Consultor Sênior	12002	AAA	13828	S	44030489516	\N	a2baaf0f83b59aac824aa705d86cd550                                                                                                                                                                                                                               	PMO 1                                                                                               
3	Usuario 03		6199999999	61999999999	usuario03@gepnet2.gov	1	2015-03-03	9997	Consultor Sênior	12002	PPP	20160	S	29882752977	\N	87c1a2e5cb8f3213c4a438609635360d                                                                                                                                                                                                                               	PMO 1                                                                                               
5	Usuario 05		6199999999	61999999999	usuario05@gepnet2.gov	1	2015-03-04	9995	CCC	3260	PPP	16883	S	57469156887	\N	3af73846ef1d25d3cb29e3d469b0c413                                                                                                                                                                                                                               	PMO01                                                                                               
11	Usuario 11		0000000000	61909009090	usuario11@gepnet2.gov	1	2015-03-03	5000650	Fábrica de Software	1210	COL	30601	S	71721815490	\N	62bf43e2db266caa78d4f0bd18fb5f7e                                                                                                                                                                                                                               	PMO 0                                                                                               
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
2	002/2015/PMO 0	\N	02-PROJETO TESTE 02	1	3	4	Objetivo de exemplo - limite 4000 caracteres	Objetivo de exemplo - limite 4000 caracteres	2015-08-03	2016-04-04	30	30	1	2015-10-31	Normal	S	S	\N	\N	\N	\N	1	\N	1	2	S	4	100000	Justificativa de exemplo - limite 4000 caracteres	1	2	2015-08-03	2015-09-03	Escopo teste	Não escopo de teste	Premissas de exemplo	restrições de exemplo	\N	\N	Considerações finais.	\N	1	\N	2	2015	32
1	001/2015/PMO 0	\N	01-PROJETO TESTE 01	1	3	4	O que será feito no projeto.	Objetivos do projeto.	2013-04-26	2014-09-02	15	15	1	2015-03-05	Estratégico	S	S	\N	\N	\N	\N	1	\N	1	1	S	5	10000	Justificativa do projeto.	1	2	2013-05-06	2013-05-31	INICIAÇÃO:\n1) TAP - Termo de Abertura do Projeto\nPLANEJAMENTO:\n1) Plano de Projeto\nEXECUÇÃO:\n1) DESENVOLVIMENTO dos seguintes módulos: Cadastro; Projetos; Planejamento; Segurança; Agenda; Atividade; Acordo de Cooperação; Grandes Eventos; Pesquisa de Opinião; Relatórios; Status Report\n2) HOMOLOGAÇÃO EM DESENVOLVIMENTO\n3) HOMOLOGAÇÃO EM AMBIENTE DE HOMOLOGAÇÃO\n4) Implantação em ambiente de PRODUÇÃO\nMONITORAMENTO E CONTROLE:\n1) Registros de projeto (Atas, cronograma, termo de aceite, etc)\nENCERRAMENTO:\n1) TEP - Termo de Encerramento do Projeto	1) Capacitação para os usuários da nova versão do sistema GEPnet.\n2) Aquisição de recursos de tecnologia da informação.\n3) Outras demandas estranhas ao projeto.	1) Disponibilidade dos envolvidos para atuar conforme plano de trabalho;\n2) Disponibilidade dos recursos tecnológicos de acordo com as necessidades do projeto;\n3) Disponibilidade de recursos orçamentários e financeiros para atividades de desenvolvimento e homologação;	1) Seguir os métodos e as práticas de desenvolvimento de projetos de sistemas da SLTI/MPOG.	\N	\N	Obrigado pelas considerações.	\N	1	\N	2	2015	31
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

