--
-- PostgreSQL database dump
--

-- Dumped from database version 12.6
-- Dumped by pg_dump version 14.1

-- Started on 2022-11-16 18:36:50

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 4167 (class 1262 OID 10420530)
-- Name: GEPNET2; Type: DATABASE; Schema: -; Owner: PCCM7702
--

CREATE DATABASE "agepnet200" WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE = 'C';


ALTER DATABASE "agepnet200" OWNER TO "POSTGRES";

\connect "agepnet200"

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 4168 (class 0 OID 0)
-- Name: GEPNET2; Type: DATABASE PROPERTIES; Schema: -; Owner: POSTGRES
--

ALTER DATABASE "agepnet200" SET "TimeZone" TO 'America/Sao_Paulo';


\connect "agepnet200"

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 18 (class 2615 OID 10422221)
-- Name: agepnet200; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA agepnet200;


ALTER SCHEMA agepnet200 OWNER TO postgres;

--
-- TOC entry 2 (class 3079 OID 10362961)
-- Name: dblink; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS dblink WITH SCHEMA public;


--
-- TOC entry 4171 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION dblink; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION dblink IS 'connect to other PostgreSQL databases from within a database';


--
-- TOC entry 424 (class 1255 OID 10422881)
-- Name: AtividadeCronogramaRecursivo(integer); Type: FUNCTION; Schema: agepnet200; Owner: postgres
--

CREATE FUNCTION agepnet200."AtividadeCronogramaRecursivo"(idprojeto integer) RETURNS TABLE(idatividadecronograma bigint, numseq numeric, idprojeto integer, idgrupo bigint, domtipoatividade numeric, datinicio date, datfim date, datiniciobaseline date, datfimbaseline date, numpercentualconcluido numeric, pai bigint, ordenacao bigint[], nivel integer)
    LANGUAGE sql
    AS $_$
	WITH RECURSIVE atividade(idatividadecronograma, numseq, idprojeto, idgrupo, domtipoatividade,
				 datinicio, datfim, datiniciobaseline, datfimbaseline, numpercentualconcluido, 
				 pai, ordenacao, nivel) AS (
                                 SELECT cron.idatividadecronograma,
                                        cron.numseq,
                                        cron.idprojeto,
                                        cron.idgrupo,
                                        cron.domtipoatividade,
                                        cron.datinicio,
                                        cron.datfim,
                                        cron.datiniciobaseline,
                                        cron.datfimbaseline,
                                        cron.numpercentualconcluido,
                                        cron.idatividadecronograma AS pai,
					ARRAY[ROW_NUMBER() OVER (ORDER BY COALESCE(cron.datfim, TO_DATE(CURRENT_TIMESTAMP::VARCHAR,'YYYY-MM-DD')), COALESCE(cron.datinicio, TO_DATE(CURRENT_TIMESTAMP::VARCHAR,'YYYY-MM-DD')), cron.idatividadecronograma)] AS ordenacao,
                                        1 AS nivel
                                   FROM agepnet200.tb_atividadecronograma cron
                                   JOIN agepnet200.tb_projeto tbp
                                     ON tbp.idprojeto = cron.idprojeto
                                    AND tbp.idtipoiniciativa = 1
                                   LEFT JOIN agepnet200.tb_parteinteressada pig
                                     ON pig.idparteinteressada = cron.idparteinteressada
                                    AND pig.idprojeto = cron.idprojeto
                                  WHERE cron.idprojeto = $1
                                    AND cron.domtipoatividade = 1

                                  UNION ALL

                                 SELECT cron.idatividadecronograma,
                                        cron.numseq,
                                        cron.idprojeto,
                                        cron.idgrupo,
                                        cron.domtipoatividade,
                                        cron.datinicio,
                                        cron.datfim,
					cron.datiniciobaseline,
                                        cron.datfimbaseline,
                                        cron.numpercentualconcluido,
                                        ati.pai,
                                        ati.ordenacao || (ROW_NUMBER() OVER (ORDER BY COALESCE(cron.datfim, TO_DATE(CURRENT_TIMESTAMP::VARCHAR,'YYYY-MM-DD')), COALESCE(cron.datinicio, TO_DATE(CURRENT_TIMESTAMP::VARCHAR,'YYYY-MM-DD')), cron.domtipoatividade, cron.idatividadecronograma)) AS ordenacao,
                                        ati.nivel + 1 AS nivel
                                   FROM agepnet200.tb_atividadecronograma cron
                                   JOIN atividade ati
                                     ON ati.idatividadecronograma = cron.idgrupo
                                   LEFT JOIN agepnet200.tb_parteinteressada pi
                                     ON pi.idparteinteressada = cron.idparteinteressada
                                    AND pi.idprojeto = cron.idprojeto
                                  WHERE cron.idprojeto = $1
			     )
                      SELECT a.*
                        FROM atividade a
                       ORDER BY a.ordenacao

  $_$;


ALTER FUNCTION agepnet200."AtividadeCronogramaRecursivo"(idprojeto integer) OWNER TO postgres;

--
-- TOC entry 430 (class 1255 OID 10422882)
-- Name: AtividadeSucessorasRecursivo(integer); Type: FUNCTION; Schema: agepnet200; Owner: postgres
--

CREATE FUNCTION agepnet200."AtividadeSucessorasRecursivo"(idprojeto integer) RETURNS TABLE(idatividadepredecessora bigint, idprojeto integer, datinicio date, datfim date, datinicionovo date, datfimnovo date, pai bigint, arvore bigint[], ordenacao bigint[], nivel integer)
    LANGUAGE sql
    AS $_$
    WITH RECURSIVE recursivaSucessoras(idatividadecronograma, idprojeto, idatividade, numfolga, datinicio, datfim, 
                       pai, datiniciogrupo, datfimgrupo, arvore, ordenacao, nivel) AS (
            SELECT DISTINCT
                   ac.idatividadecronograma, 
                   ac.idprojeto,
                   NULL::BIGINT AS idatividade, 
                   ac.numfolga, 
                   ac.datinicio,
                   ac.datfim, 
                   ac.idatividadecronograma AS pai,
                   ac.datinicio AS datiniciogrupo,
                   ac.datfim AS datfimgrupo,
                   ARRAY[ac.idatividadecronograma] AS arvore,
                   ARRAY[RANK() OVER (ORDER BY ac.idprojeto, ac.idatividadecronograma)] AS ordenacao,
                   1 AS nivel
              FROM agepnet200.tb_atividadecronograma ac 
              JOIN agepnet200.tb_atividadecronopredecessora acp 
                ON acp.idatividadepredecessora = ac.idatividadecronograma 
               AND acp.idprojetocronograma = ac.idprojeto
              LEFT JOIN agepnet200.tb_atividadecronopredecessora acps 
                ON acps.idatividadecronograma = ac.idatividadecronograma 
               AND acps.idprojetocronograma = ac.idprojeto
             WHERE (acps.idatividadecronograma IS NULL 
               AND acps.idprojetocronograma IS NULL 
               AND acps.idatividadepredecessora IS NULL)
               AND ac.idprojeto = $1

             UNION ALL  

            SELECT ac.idatividadecronograma, 
                   ac.idprojeto,
                   acp.idatividadecronograma AS idatividade, 
                   ac.numfolga, 
                   ac.datinicio,
                   ac.datfim, 
                   rec.pai,
                   agepnet200."CalcularNovaDataFimCronograma"(rec.datfimgrupo, (ac.numfolga + (CASE WHEN ac.domtipoatividade = 4 THEN 0 ELSE 1 END))) AS datiniciogrupo,
                   agepnet200."CalcularNovaDataFimCronograma"(agepnet200."CalcularNovaDataFimCronograma"(rec.datfimgrupo, (ac.numfolga + (CASE WHEN ac.domtipoatividade = 4 THEN 0 ELSE 1 END))), 
                CASE WHEN COALESCE(
                        (CASE WHEN ac.datfim IS NULL OR ac.datinicio IS NULL OR ac.domtipoatividade = 4 THEN 0
                 ELSE
                    (SELECT COUNT(*) AS diasuteis
                       FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(ac.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                TO_TIMESTAMP(TO_CHAR(ac.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                      WHERE EXTRACT('ISODOW' FROM the_day) < 6
                        AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                            LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0') AS ddmm
                                           FROM agepnet200.tb_feriado tf
                                          WHERE tf.flaativo = 'S'
                                            AND tf.tipoferiado = '1')
                        AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0') || '/' ||
                                                 LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2,'0') || '/' ||
                                                 LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4,'0') AS ddmmaaaa

                            FROM agepnet200.tb_feriado tf
                                               WHERE tf.flaativo = 'S'
                                             AND tf.tipoferiado = '2'))
                   END)::INTEGER, 0) > 0 THEN (

                   CASE WHEN ac.datfim IS NULL OR ac.datinicio IS NULL OR ac.domtipoatividade = 4 THEN 0
                 ELSE
                    (SELECT COUNT(*) AS diasuteis
                       FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(ac.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                TO_TIMESTAMP(TO_CHAR(ac.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day') the_day
                      WHERE EXTRACT('ISODOW' FROM the_day) < 6
                        AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
                                            LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0') AS ddmm
                                           FROM agepnet200.tb_feriado tf
                                          WHERE tf.flaativo = 'S'
                                            AND tf.tipoferiado = '1')
                        AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0') || '/' ||
                                                 LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2,'0') || '/' ||
                                                 LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4,'0') AS ddmmaaaa

                            FROM agepnet200.tb_feriado tf
                                               WHERE tf.flaativo = 'S'
                                             AND tf.tipoferiado = '2'))
                   END

                   )::INTEGER - 1 ELSE 0 END) AS datfimgrupo,
                   arvore || ac.idatividadecronograma AS arvore,
                   ordenacao || ROW_NUMBER() OVER (ORDER BY acp.idprojetocronograma, acp.idatividadepredecessora, acp.idatividadecronograma) AS ordenacao,
                   rec.nivel + 1 AS nivel
                FROM agepnet200.tb_atividadecronopredecessora acp
               JOIN recursivaSucessoras rec 
                 ON rec.idatividadecronograma = acp.idatividadepredecessora 
                AND rec.idprojeto = acp.idprojetocronograma 
               JOIN agepnet200.tb_atividadecronograma ac 
                 ON ac.idatividadecronograma = acp.idatividadecronograma
                AND ac.idprojeto = acp.idprojetocronograma
         )

        SELECT a.idatividadecronograma, 
           a.idprojeto,
           a.datinicio, 
           a.datfim,
           a.datiniciogrupo AS datinicionovo,
           a.datfimgrupo AS datfimnovo, 
           a.pai,
           a.arvore,
           a.ordenacao, 
           a.nivel
          FROM recursivaSucessoras a 
         ORDER BY a.idprojeto, a.ordenacao;
  $_$;


ALTER FUNCTION agepnet200."AtividadeSucessorasRecursivo"(idprojeto integer) OWNER TO postgres;

--
-- TOC entry 406 (class 1255 OID 10422883)
-- Name: AtualizarAtividadeSucessora(integer, integer); Type: FUNCTION; Schema: agepnet200; Owner: postgres
--

CREATE FUNCTION agepnet200."AtualizarAtividadeSucessora"(numidprojeto integer, numidatividade integer) RETURNS void
    LANGUAGE plpgsql
    AS $$

DECLARE   
    recAtividadeSucessora   RECORD;
    queryAtividadeSucessora TEXT;
    
    recGrupos   RECORD;
    queryGrupos TEXT;
    
    recEntregas   RECORD;
    queryEntregas TEXT;

    dataInicioNovo DATE;
    dataFinalNovo DATE;
  BEGIN
  
	DROP TABLE IF EXISTS tb_temp_atividadesucessora;
	CREATE TEMPORARY TABLE tb_temp_atividadesucessora (
		idatividadepredecessora BIGINT, 
		idprojeto INTEGER, 
		datinicio DATE, 
		datfim DATE, 
		datinicionovo DATE, 
		datfimnovo DATE, 
		pai BIGINT, 
		arvore BIGINT[], 
		ordenacao BIGINT[], 
		nivel INTEGER
	) ON COMMIT DROP;

	INSERT INTO tb_temp_atividadesucessora 
	SELECT * 
	  FROM agepnet200."AtividadeSucessorasRecursivo"(numidprojeto);


	----- Atividade Sucessoras Recursivas ------
	queryAtividadeSucessora := 'WITH RECURSIVE atividadeRecursivaSucessoras(idatividadecronograma, idprojeto, ordenacao, nivel) AS (
					    SELECT cron.idatividadecronograma, 
						   cron.idprojeto,
						   ARRAY[cron.idatividadecronograma] AS ordenacao,
						   1 AS nivel
					      FROM agepnet200.tb_atividadecronograma cron
					      JOIN agepnet200.tb_projeto tbp
					        ON tbp.idprojeto = cron.idprojeto
					     WHERE cron.idprojeto = ' || numidprojeto || '
					       AND cron.idatividadecronograma = ' || numidatividade || '

					     UNION ALL

					    SELECT cron.idatividadecronograma, 
						   cron.idprojeto,
						   ordenacao || cron.idatividadecronograma AS ordenacao,
						   rs.nivel + 1 AS nivel
					      FROM agepnet200.tb_atividadecronopredecessora ass
					      JOIN atividadeRecursivaSucessoras rs 
					        ON rs.idatividadecronograma = ass.idatividadepredecessora 
					       AND rs.idprojeto = ass.idprojetocronograma
					      JOIN agepnet200.tb_atividadecronograma cron 
					        ON cron.idatividadecronograma = ass.idatividadecronograma
					       AND cron.idprojeto = ass.idprojetocronograma
				        )

				    SELECT x.idatividadecronograma,
					   x.idprojeto,
					   x.idgrupopredecessoraprincipal
				      FROM (SELECT a.idatividadecronograma, 
						   a.idprojeto,
						   a.ordenacao,  
						   (SELECT pai 
						      FROM tb_temp_atividadesucessora 
						     WHERE idatividadepredecessora = a.idatividadecronograma
						       AND datfimnovo IS NOT NULL 
						     ORDER BY datfimnovo DESC 
						     LIMIT 1) AS idgrupopredecessoraprincipal
					      FROM atividadeRecursivaSucessoras a 
					     WHERE a.nivel <> 1 ) x
				     ORDER BY x.ordenacao';

	----- Atualizando as datas das sucessoras pela recursividade -----
	FOR recAtividadeSucessora IN EXECUTE queryAtividadeSucessora
	LOOP

		-- Consultando o novo calculo das datas, baseadas 
		-- pelas sua predecessosas.
		-- Caso haja mais de uma, tras a que tem maior datfim. 
		SELECT datinicionovo, datfimnovo 
		  INTO dataInicioNovo, dataFinalNovo
		  FROM tb_temp_atividadesucessora
		 WHERE pai = recAtividadeSucessora.idgrupopredecessoraprincipal 
		   AND idatividadepredecessora = recAtividadeSucessora.idatividadecronograma
		 ORDER BY datfimnovo DESC
		 LIMIT 1;
    
		UPDATE agepnet200.tb_atividadecronograma
		   SET datinicio = dataInicioNovo,
		       datfim    = dataFinalNovo
		 WHERE idatividadecronograma = recAtividadeSucessora.idatividadecronograma
		   AND idprojeto = recAtividadeSucessora.idprojeto;

		-- RAISE NOTICE '% datinicio - % | datfim - %', recAtividadeSucessora.idatividadecronograma, dataInicioNovo, dataFinalNovo;
	END LOOP;

	----- Todos os Grupos do projeto ------
	queryGrupos := 'SELECT idatividadecronograma,
			       idprojeto 
		          FROM agepnet200.tb_atividadecronograma 
		         WHERE idgrupo IS NULL AND idprojeto = ' || numidprojeto || '
		         ORDER BY numseq';

	
	FOR recGrupos IN EXECUTE queryGrupos
	LOOP
		----- Todas AS Entregas do grupos no loop ------
		queryEntregas := 'SELECT idatividadecronograma,
					 idprojeto  
				    FROM agepnet200.tb_atividadecronograma 
				   WHERE idgrupo = ' || recGrupos.idatividadecronograma || '
				     AND idprojeto = ' || recGrupos.idprojeto || '
			           ORDER BY numseq';

		
		FOR recEntregas IN EXECUTE queryEntregas
		LOOP
			----- Atualizando as datas das entregas do projeto -----
			UPDATE agepnet200.tb_atividadecronograma
			   SET datinicio	      = datasAtividades.mindatinicio,
			       datfim    	      = datasAtividades.maxdatfim,
			       datiniciobaseline      = datasAtividades.mindatiniciobaseline,
			       datfimbaseline         = datasAtividades.maxdatfimbaseline, 
			       numpercentualconcluido = datasAtividades.numpercentualconcluido
			  FROM (SELECT MIN(acr.datinicio) AS mindatinicio,
				       MAX(acr.datfim) AS maxdatfim,
				       MIN(acr.datiniciobaseline) AS mindatiniciobaseline,
				       MAX(acr.datfimbaseline) AS maxdatfimbaseline,
				       CASE 
					    WHEN COUNT(cron.idatividadecronograma) > 0 THEN
						  ROUND((SUM(cron.numpercentualconcluido) / COUNT(cron.idatividadecronograma)), 2)
					    ELSE 0::NUMERIC(5,2)
					END AS numpercentualconcluido
			          FROM agepnet200."AtividadeCronogramaRecursivo"(recEntregas.idprojeto) acr
			          LEFT JOIN agepnet200.tb_atividadecronograma cron
				    ON cron.idatividadecronograma = acr.idatividadecronograma
				   AND cron.idprojeto = acr.idprojeto
				   AND cron.domtipoatividade = 3
				 WHERE acr.idgrupo = recEntregas.idatividadecronograma) AS datasAtividades 
			 WHERE idatividadecronograma = recEntregas.idatividadecronograma
			   AND idprojeto = recEntregas.idprojeto;
		END LOOP;

		----- Atualizando as datas dos grupos do projeto -----
		UPDATE agepnet200.tb_atividadecronograma
		   SET datinicio	      = datasAtividades.mindatinicio,
		       datfim    	      = datasAtividades.maxdatfim,
		       datiniciobaseline      = datasAtividades.mindatiniciobaseline,
		       datfimbaseline         = datasAtividades.maxdatfimbaseline, 
		       numpercentualconcluido = datasAtividades.numpercentualconcluido
		  FROM (SELECT MIN(acr.datinicio) mindatinicio,
			       MAX(acr.datfim) maxdatfim,
			       MIN(acr.datiniciobaseline) AS mindatiniciobaseline,
			       MAX(acr.datfimbaseline) AS maxdatfimbaseline,
			       CASE 
				     WHEN COUNT(cron.idatividadecronograma) > 0 THEN
					   ROUND((SUM(cron.numpercentualconcluido) / COUNT(cron.idatividadecronograma)), 2)
				     ELSE 0::NUMERIC(5,2)
				END AS numpercentualconcluido
			  FROM agepnet200."AtividadeCronogramaRecursivo"(recGrupos.idprojeto) acr 
			  LEFT JOIN agepnet200.tb_atividadecronograma cron
			    ON cron.idatividadecronograma = acr.idatividadecronograma
			   AND cron.idprojeto = acr.idprojeto
			   AND cron.domtipoatividade = 3
			 WHERE acr.pai = recGrupos.idatividadecronograma
			   AND acr.nivel > 1) AS datasAtividades 
		 WHERE idatividadecronograma = recGrupos.idatividadecronograma
		   AND idprojeto = recGrupos.idprojeto;
	END LOOP;
  END;

  $$;


ALTER FUNCTION agepnet200."AtualizarAtividadeSucessora"(numidprojeto integer, numidatividade integer) OWNER TO postgres;

--
-- TOC entry 407 (class 1255 OID 10422886)
-- Name: AtualizarFuncaoRhProjeto(integer, integer, integer, integer, integer); Type: FUNCTION; Schema: agepnet200; Owner: postgres
--

CREATE FUNCTION agepnet200."AtualizarFuncaoRhProjeto"(numidprojeto integer, idcadastrador integer, idpessoaantiga integer, idpessoanova integer, idparteinteressadafuncao integer) RETURNS void
    LANGUAGE plpgsql
    AS $_$

DECLARE 
	queryRhAntigo         TEXT;
	queryRhNovo           TEXT;
	queryPer              TEXT;
	projeto               RECORD;
	pessoaNova            RECORD;
	rhAntigo              RECORD;
	rhNovo                RECORD;
	recordPer             RECORD;
	contAntigo            INTEGER;
	contNovo              INTEGER;
	maxIdParteInteressada INTEGER;
	
BEGIN
	IF $3 <> $4 THEN
		contAntigo := 0;
		contNovo   := 0;
		maxIdParteInteressada := 0;

		-- Recordset de Projeto
		SELECT * 
		  INTO projeto 
		  FROM agepnet200.tb_projeto 
		 WHERE idprojeto = $1;

		-- Recordset de Pessoa Nova
		SELECT * 
		  INTO pessoaNova 
		  FROM agepnet200.tb_pessoa
		 WHERE idpessoa = $4;
			 
		-- Query que retorna a Parte Interessada Antiga do Projeto
		queryRhAntigo := 'SELECT pin.idparteinteressada,
					 pin.idprojeto,
					 pin.nomparteinteressada AS nomparteinteressada,
					 ARRAY_TO_STRING(ARRAY_AGG(DISTINCT pif.nomfuncao), '', '', '''') AS nomfuncao, 
					 pin.destelefone,
					 pin.desemail, 
					 pin.domnivelinfluencia,
					 pin.tppermissao, 
					 pin.idcadastrador, 
					 pin.datcadastro, 
					 pin.idpessoainterna, 
					 pin.observacao,
					 ARRAY_TO_STRING(ARRAY_AGG(DISTINCT pif.idparteinteressadafuncao), '','', '''') AS idparteinteressadafuncao,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao NOT IN (5, '|| $5 ||') THEN 1 ELSE 0 END) > 0 AS is_parteinteressadafuncao,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao = 1 THEN 1 ELSE 0 END) > 0 AS is_gerenteprojeto,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao = 2 THEN 1 ELSE 0 END) > 0 AS is_gerenteadjunto,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao = 3 THEN 1 ELSE 0 END) > 0 AS is_demandante,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao = 4 THEN 1 ELSE 0 END) > 0 AS is_patrocinador,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao = 5 THEN 1 ELSE 0 END) > 0 AS is_parteinteressada,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao = 6 THEN 1 ELSE 0 END) > 0 AS is_equipeprojeto
				    FROM agepnet200.tb_parteinteressada pin 
				    JOIN agepnet200.tb_projeto p 
				      ON p.idprojeto = pin.idprojeto
				    LEFT JOIN agepnet200.tb_parteinteressada_funcoes piff
				      ON piff.idparteinteressada = pin.idparteinteressada
				    LEFT JOIN agepnet200.tb_parteinteressadafuncao pif
				      ON pif.idparteinteressadafuncao = piff.idparteinteressadafuncao  
				    LEFT JOIN agepnet200.tb_parteinteressada_funcoes pifv
				      ON pifv.idparteinteressada = pin.idparteinteressada
				    LEFT JOIN agepnet200.tb_pessoa pes 
				      ON pin.idpessoainterna = pes.idpessoa 
				   WHERE p.idprojeto = '|| $1 ||'
				     AND pin.idpessoainterna = '|| $3 ||' 
				     AND pif.idparteinteressadafuncao = '|| $5 ||'
				   GROUP BY pin.nomparteinteressada, 
					    pin.desemail, 
					    pin.destelefone, 
					    pin.domnivelinfluencia, 
					    pin.idparteinteressada';

		-- Query que retorna a Parte Interessada Nova do Projeto
		queryRhNovo   := 'SELECT pin.idparteinteressada,
					 pin.idprojeto,
					 pin.nomparteinteressada AS nomparteinteressada,
					 ARRAY_TO_STRING(ARRAY_AGG(DISTINCT pif.nomfuncao), '', '', '''') AS nomfuncao, 
					 pin.destelefone,
					 pin.desemail, 
					 pin.domnivelinfluencia,
					 pin.tppermissao, 
					 pin.idcadastrador, 
					 pin.datcadastro, 
					 pin.idpessoainterna, 
					 pin.observacao,
					 ARRAY_TO_STRING(ARRAY_AGG(DISTINCT pif.idparteinteressadafuncao), '','', '''') AS idparteinteressadafuncao,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao = 1 THEN 1 ELSE 0 END) > 0 AS is_gerenteprojeto,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao = 2 THEN 1 ELSE 0 END) > 0 AS is_gerenteadjunto,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao = 3 THEN 1 ELSE 0 END) > 0 AS is_demandante,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao = 4 THEN 1 ELSE 0 END) > 0 AS is_patrocinador,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao = 5 THEN 1 ELSE 0 END) > 0 AS is_parteinteressada,
					 SUM(CASE WHEN pifv.idparteinteressadafuncao = 6 THEN 1 ELSE 0 END) > 0 AS is_equipeprojeto
				    FROM agepnet200.tb_parteinteressada pin 
				    JOIN agepnet200.tb_projeto p 
				      ON p.idprojeto = pin.idprojeto
				    LEFT JOIN agepnet200.tb_parteinteressada_funcoes piff
				      ON piff.idparteinteressada = pin.idparteinteressada
				    LEFT JOIN agepnet200.tb_parteinteressadafuncao pif
				      ON pif.idparteinteressadafuncao = piff.idparteinteressadafuncao  
				    LEFT JOIN agepnet200.tb_parteinteressada_funcoes pifv
				      ON pifv.idparteinteressada = pin.idparteinteressada
				    LEFT JOIN agepnet200.tb_pessoa pes 
				      ON pin.idpessoainterna = pes.idpessoa 
				   WHERE p.idprojeto = '|| $1 ||'
				     AND pin.idpessoainterna = '|| $4 ||' 
				   GROUP BY pin.nomparteinteressada, 
					    pin.desemail, 
					    pin.destelefone, 
					    pin.domnivelinfluencia, 
					    pin.idparteinteressada';

		FOR rhAntigo IN EXECUTE queryRhAntigo 
		LOOP
			contAntigo := contAntigo + 1;
		END LOOP;

		FOR rhNovo IN EXECUTE queryRhNovo 
		LOOP
			maxIdParteInteressada := rhNovo.idparteinteressada;
			contNovo := contNovo + 1;
		END LOOP;

		IF contAntigo = 1 THEN 
			-- Removendo a Funcao do Projeto
			--RAISE NOTICE 'DELETE agepnet200.tb_parteinteressada idparteinteressada: (%); idparteinteressadafuncao: (%)', rhAntigo.idparteinteressada, $5;
			DELETE 
			  FROM agepnet200.tb_parteinteressada_funcoes 
			 WHERE tb_parteinteressada_funcoes.idparteinteressada = rhAntigo.idparteinteressada
			   AND tb_parteinteressada_funcoes.idparteinteressadafuncao = $5;

			-- Atribuindo a Funcao do Projeto de Parte Interessada
			IF rhAntigo.is_parteinteressadafuncao = FALSE AND rhAntigo.is_parteinteressada = FALSE THEN
				INSERT 
				  INTO agepnet200.tb_parteinteressada_funcoes 
				VALUES (rhAntigo.idparteinteressada, 5);
			END IF;   
		END IF;

		IF contNovo = 0 AND pessoaNova.idpessoa IS NOT NULL THEN	 
			SELECT MAX(idparteinteressada) + 1 INTO maxIdParteInteressada
			  FROM agepnet200.tb_parteinteressada;

			-- Criando a parte interessada
			INSERT 
			  INTO agepnet200.tb_parteinteressada (idparteinteressada, idprojeto, nomparteinteressada, destelefone, desemail, 
							       domnivelinfluencia, idcadastrador, datcadastro, idpessoainterna, tppermissao)
			VALUES (maxIdParteInteressada, projeto.idprojeto, pessoaNova.nompessoa, pessoaNova.numfone, pessoaNova.desemail,
				'Alto', $2, CURRENT_TIMESTAMP, pessoaNova.idpessoa, '1');
		END IF;

		IF pessoaNova.idpessoa IS NOT NULL THEN	 
			-- Removendo a Funcao do Projeto Parte Interessada ao Novo 
			DELETE 
			  FROM agepnet200.tb_parteinteressada_funcoes 
			 WHERE idparteinteressada = maxIdParteInteressada
			   AND tb_parteinteressada_funcoes.idparteinteressadafuncao IN (5, 6);

			-- Atribuindo a Funcao do Projeto ao Novo 
			DELETE 
			  FROM agepnet200.tb_parteinteressada_funcoes 
			 WHERE idparteinteressada = maxIdParteInteressada
			   AND tb_parteinteressada_funcoes.idparteinteressadafuncao = $5;
			
			INSERT 
			  INTO agepnet200.tb_parteinteressada_funcoes 
			VALUES (maxIdParteInteressada, $5);

			-- Atualiza AS permissoes de uma parte interessada
			UPDATE agepnet200.tb_parteinteressada
			   SET tppermissao = '1', status = TRUE 
			 WHERE tb_parteinteressada.idparteinteressada = maxIdParteInteressada;

			-- Removendo AS Permissoes da Parte Interessada no Projeto
			DELETE 
			  FROM agepnet200.tb_permissaoprojeto 
			 WHERE tb_permissaoprojeto.idparteinteressada = maxIdParteInteressada
			   AND tb_permissaoprojeto.idprojeto = projeto.idprojeto;

			-- Atualizando AS Permissoes da Parte Interessada no Projeto
			queryPer := 'SELECT pe.idrecurso, 
					    pe.idpermissao 
				       FROM agepnet200.tb_permissao pe
				      WHERE pe.visualizar = TRUE';

			FOR recordPer IN EXECUTE queryPer
			LOOP 
				INSERT 
				  INTO agepnet200.tb_permissaoprojeto(idparteinteressada, idprojeto, idrecurso, idpermissao, idpessoa, data)
				VALUES (maxIdParteInteressada, projeto.idprojeto, recordPer.idrecurso, recordPer.idpermissao, pessoaNova.idpessoa, CURRENT_TIMESTAMP);
			END LOOP;
		END IF;

		-- Atualizando Gerente do Projeto
		IF $5 = 1 THEN
			UPDATE agepnet200.tb_projeto 
			   SET idgerenteprojeto = pessoaNova.idpessoa
			 WHERE idprojeto = $1;
			 
		-- Atualizando Gerente Adjunto do Projeto	 
		ELSIF $5 = 2 THEN
			UPDATE agepnet200.tb_projeto 
			   SET idgerenteadjunto = pessoaNova.idpessoa
			 WHERE idprojeto = $1;
			 
		-- Atualizando Demandante do Projeto
		ELSIF $5 = 3 THEN
			UPDATE agepnet200.tb_projeto 
			   SET iddemandante = pessoaNova.idpessoa
			 WHERE idprojeto = $1;

		-- Atualizando Patrocinador do Projeto	 
		ELSIF $5 = 4 THEN
			UPDATE agepnet200.tb_projeto 
			   SET idpatrocinador = pessoaNova.idpessoa
			 WHERE idprojeto = $1;
		END IF; 
	END IF; 
 END;

$_$;


ALTER FUNCTION agepnet200."AtualizarFuncaoRhProjeto"(numidprojeto integer, idcadastrador integer, idpessoaantiga integer, idpessoanova integer, idparteinteressadafuncao integer) OWNER TO postgres;

--
-- TOC entry 401 (class 1255 OID 10422889)
-- Name: AtualizarNumseqAtividade(integer); Type: FUNCTION; Schema: agepnet200; Owner: postgres
--

CREATE FUNCTION agepnet200."AtualizarNumseqAtividade"(numidprojeto integer) RETURNS void
    LANGUAGE plpgsql
    AS $$

  DECLARE
    recAtividadecronograma   RECORD;
    queryAtividadecronograma TEXT;

  BEGIN
    queryAtividadecronograma := 'WITH RECURSIVE atividade(idprojeto, idatividadecronograma, pai, ordenacao, nivel) AS (
					 SELECT cron.idprojeto,
						cron.idatividadecronograma,
						cron.idatividadecronograma AS pai,
						array[ROW_NUMBER() OVER (ORDER BY COALESCE(cron.datfim, TO_DATE(CURRENT_TIMESTAMP::VARCHAR,''yyyy-mm-dd'')), COALESCE(cron.datinicio, TO_DATE(CURRENT_TIMESTAMP::VARCHAR,''yyyy-mm-dd'')), cron.idatividadecronograma)] AS ordenacao,
						1 AS nivel
					   FROM agepnet200.tb_atividadecronograma cron
					   JOIN agepnet200.tb_projeto tbp
					     ON tbp.idprojeto = cron.idprojeto
					    AND tbp.idtipoiniciativa = 1
					  WHERE cron.idprojeto = ' || numidprojeto || '
					    AND cron.domtipoatividade = 1

					  UNION ALL

					 SELECT cron.idprojeto,
						cron.idatividadecronograma,
						ati.pai,
						ati.ordenacao || (ROW_NUMBER() OVER (ORDER BY COALESCE(cron.datfim, TO_DATE(CURRENT_TIMESTAMP::VARCHAR,''yyyy-mm-dd'')), COALESCE(cron.datinicio, TO_DATE(CURRENT_TIMESTAMP::VARCHAR,''yyyy-mm-dd'')), cron.domtipoatividade, cron.idatividadecronograma)) AS ordenacao,
						ati.nivel + 1 AS nivel
					   FROM agepnet200.tb_atividadecronograma cron
					   JOIN atividade ati
					     ON ati.idatividadecronograma = cron.idgrupo
					   LEFT JOIN agepnet200.tb_parteinteressada pi
					     ON pi.idparteinteressada = cron.idparteinteressada
					    AND pi.idprojeto = cron.idprojeto
					  WHERE cron.idprojeto = ' || numidprojeto || ' 
					)

				SELECT a.idprojeto,
				       a.idatividadecronograma, 
				       a.ordenacao, 
				       ROW_NUMBER() OVER (ORDER BY a.ordenacao) AS numseqnovo
				  FROM atividade a
				 ORDER BY a.ordenacao';

	FOR recAtividadecronograma IN EXECUTE queryAtividadecronograma
	LOOP
		UPDATE agepnet200.tb_atividadecronograma
		   SET numseq = recAtividadecronograma.numseqnovo
		 WHERE idatividadecronograma = recAtividadecronograma.idatividadecronograma
		  AND idprojeto = recAtividadecronograma.idprojeto;
	END LOOP;
  END;

  $$;


ALTER FUNCTION agepnet200."AtualizarNumseqAtividade"(numidprojeto integer) OWNER TO postgres;

--
-- TOC entry 402 (class 1255 OID 10422890)
-- Name: AtualizarPercentualProjeto(integer); Type: FUNCTION; Schema: agepnet200; Owner: postgres
--

CREATE FUNCTION agepnet200."AtualizarPercentualProjeto"(numidprojeto integer) RETURNS void
    LANGUAGE plpgsql
    AS $$

BEGIN
	----- Atualizando status do projeto -----
	UPDATE agepnet200.tb_projeto
	   SET atraso 			       = dadosProjeto.atraso::VARCHAR,
	       numpercentualconcluidomarco     = dadosProjeto.numpercentualconcluidomarco::NUMERIC(5,2),
	       domcoratraso                    = dadosProjeto.domcoratraso::VARCHAR,
	       qtdeatividadeiniciada 	       = dadosProjeto.qtdeatividadeiniciada::NUMERIC(5,2),
	       numpercentualiniciado 	       = dadosProjeto.numpercentualiniciado::NUMERIC(5,2),
	       qtdeatividadenaoiniciada        = dadosProjeto.qtdeatividadenaoiniciada::NUMERIC(5,2),
	       numpercentualnaoiniciado        = dadosProjeto.numpercentualnaoiniciado::NUMERIC(5,2),
	       qtdeatividadeconcluida          = dadosProjeto.qtdeatividadeconcluida::NUMERIC(5,2),
	       numpercentualatividadeconcluido = dadosProjeto.numpercentualatividadeconcluido::NUMERIC(5,2),
	       numpercentualprevisto           = dadosProjeto.numpercentualprevisto::NUMERIC(5,2),
	       numpercentualconcluido          = dadosProjeto.numpercentualconcluido::NUMERIC(5,2)
	       
	  FROM ( SELECT dadosProjeto.*, 
			CASE WHEN dadosProjeto.projetoatrasado = TRUE AND dadosProjeto.atraso > dadosProjeto.numcriteriofarol THEN 'important'
			     WHEN dadosProjeto.projetoatrasado = TRUE AND dadosProjeto.atraso <= dadosProjeto.numcriteriofarol THEN 'warning' 
			     ELSE 'success'
			 END AS domcoratraso, 
                        CASE WHEN dadosProjeto.totalatividadeporprojeto = 0 THEN 0::NUMERIC
			     ELSE ROUND((dadosProjeto.qtdeatividadeiniciada::NUMERIC / dadosProjeto.totalatividadeporprojeto) * 100, 2) 
			 END::NUMERIC(5, 2) AS numpercentualiniciado,
			CASE WHEN dadosProjeto.totalatividadeporprojeto = 0 THEN 0::NUMERIC
			     ELSE ROUND((dadosProjeto.qtdeatividadenaoiniciada::NUMERIC / dadosProjeto.totalatividadeporprojeto) * 100, 2) 
			 END::NUMERIC(5, 2) AS numpercentualnaoiniciado,
			CASE WHEN dadosProjeto.totalatividadeporprojeto = 0 THEN 0::NUMERIC
			     ELSE ROUND((dadosProjeto.qtdeatividadeconcluida::NUMERIC / dadosProjeto.totalatividadeporprojeto) * 100, 2) 
			 END::NUMERIC(5, 2) AS numpercentualatividadeconcluido,
			CASE WHEN dadosProjeto.numdiasbaseline = 0 THEN 0::NUMERIC 
			     ELSE ROUND((dadosProjeto.numdiascompletos::NUMERIC / dadosProjeto.numdiasbaseline) * 100, 2) 
			 END::NUMERIC(5, 2) AS numpercentualprevisto
		  FROM (SELECT dadoscronogramas.*, 
			       p.numcriteriofarol,
			       CASE
				    WHEN dadoscronogramas.maxdatfim IS NULL OR dadoscronogramas.maxdatfimbaseline IS NULL OR dadoscronogramas.maxdatfim = dadoscronogramas.maxdatfimbaseline THEN 0 
				    ELSE (SELECT COUNT(*) AS diasuteis
					FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(mindatmarco, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP,
						     TO_TIMESTAMP(TO_CHAR(maxdatmarco, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP, INTERVAL  '1 day') the_day
					   WHERE EXTRACT('ISODOW' FROM the_day) < 6
					 AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
								  LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0')
							     FROM agepnet200.tb_feriado tf
							    WHERE tf.flaativo = 'S'
							      AND tf.tipoferiado = '1')
					 AND TO_CHAR(the_day, 'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
								   LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
								   LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
								  FROM agepnet200.tb_feriado tf
								 WHERE tf.flaativo = 'S' AND tf.tipoferiado = '2')
					) - (CASE WHEN projetoatrasado THEN 1 ELSE (-1) END) 
				 END AS atraso
				
			  FROM (SELECT numidprojeto AS idprojeto, 
				       MIN(acr.datinicio) AS mindatinicio,
				       MAX(acr.datfim) AS maxdatfim,
				       MIN(acr.datiniciobaseline) AS mindatiniciobaseline,
				       MAX(acr.datfimbaseline) AS maxdatfimbaseline, 
				       (CASE WHEN MAX(acr.datfimbaseline) < MAX(acr.datfim) THEN MAX(acr.datfimbaseline)
					     ELSE MAX(acr.datfim) END) AS mindatmarco, 
				       (CASE WHEN MAX(acr.datfimbaseline) > MAX(acr.datfim) THEN MAX(acr.datfimbaseline)
					     ELSE MAX(acr.datfim) END) AS maxdatmarco, 
				       MAX(acr.datfimbaseline) < MAX(acr.datfim) AS projetoatrasado, 
				       CASE
					    WHEN MAX(acr.datfimbaseline) IS NULL OR MIN(acr.datiniciobaseline) IS NULL THEN 0 
					    ELSE (SELECT COUNT(*) AS diasuteis
						FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(MIN(acr.datiniciobaseline), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP,
							     TO_TIMESTAMP(TO_CHAR(MAX(acr.datfimbaseline), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP, INTERVAL  '1 day') the_day
						   WHERE EXTRACT('ISODOW' FROM the_day) < 6
						 AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
									  LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0')
								     FROM agepnet200.tb_feriado tf
								    WHERE tf.flaativo = 'S'
								      AND tf.tipoferiado = '1')
						 AND TO_CHAR(the_day, 'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
									   LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
									   LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
									  FROM agepnet200.tb_feriado tf
									 WHERE tf.flaativo = 'S' AND tf.tipoferiado = '2'))
					 END AS numdiasbaseline,
					CASE
					    WHEN MAX(acr.datfimbaseline) IS NULL OR MIN(acr.datiniciobaseline) IS NULL THEN 0 
					    WHEN TO_DATE(TO_CHAR(MIN(acr.datiniciobaseline), 'DD/MM/YYYY'), 'DD/MM/YYYY') > TO_DATE(TO_CHAR(now(), 'DD/MM/YYYY'), 'DD/MM/YYYY') THEN 0 
					    WHEN TO_CHAR(MIN(acr.datiniciobaseline), 'DD/MM/YYYY') = TO_CHAR(now(), 'DD/MM/YYYY') THEN 0 
					    WHEN TO_DATE(TO_CHAR(MAX(acr.datfimbaseline), 'DD/MM/YYYY'), 'DD/MM/YYYY') > TO_DATE(TO_CHAR(now(), 'DD/MM/YYYY'), 'DD/MM/YYYY') THEN
						(SELECT COUNT(*) AS diasuteis
						  FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(MIN(acr.datiniciobaseline), 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP,
							       TO_TIMESTAMP(TO_CHAR(now(), 'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP, '24 hours') the_day
						 WHERE EXTRACT('ISODOW' FROM the_day) < 6
						   AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
									   LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')),2,'0') AS ddmm
								      FROM agepnet200.tb_feriado tf
								     WHERE tf.flaativo = 'S'
								       AND tf.tipoferiado = '1')
						   AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   ,2, '0') || '/' ||
									    LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2, '0') || '/' ||
									    LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4, '0') AS ddmmaaaa
									   FROM agepnet200.tb_feriado tf
									  WHERE tf.flaativo = 'S'
									AND tf.tipoferiado = '2'))
					    WHEN TO_DATE(TO_CHAR(MAX(acr.datfimbaseline), 'DD/MM/YYYY'),'DD/MM/YYYY') < TO_DATE(TO_CHAR(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') THEN
						(SELECT COUNT(*) AS diasuteis
						   FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(MIN(acr.datiniciobaseline), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP,
							    TO_TIMESTAMP(TO_CHAR(MAX(acr.datfimbaseline), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP, INTERVAL  '1 day') the_day
						  WHERE EXTRACT('ISODOW' FROM the_day) < 6
						    AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
									LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')),2,'0') AS ddmm
								       FROM agepnet200.tb_feriado tf
								      WHERE tf.flaativo = 'S'
									AND tf.tipoferiado = '1')
						    AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0')|| '/' ||
									     LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   , 2, '0')|| '/' ||
									     LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) , 4, '0') AS ddmmaaaa
									FROM agepnet200.tb_feriado tf
									   WHERE tf.flaativo = 'S'
									   AND tf.tipoferiado = '2'))
					    ELSE
						(SELECT COUNT(*) AS diasuteis
						   FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(MIN(acr.datiniciobaseline), 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP,
							    TO_TIMESTAMP(TO_CHAR(MAX(acr.datfimbaseline),    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP, '24 hours') the_day
						  WHERE EXTRACT('ISODOW' FROM the_day) < 6
						    AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')),2,'0') || '/' ||
									LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')),2,'0') AS ddmm
								       FROM agepnet200.tb_feriado tf
								      WHERE tf.flaativo = 'S'
								       AND tf.tipoferiado = '1')
						    AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0') || '/' ||
									     LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   , 2, '0') || '/' ||
									     LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) , 4, '0') AS ddmmaaaa
									FROM agepnet200.tb_feriado tf
									   WHERE tf.flaativo = 'S'
									 AND tf.tipoferiado = '2'))
					  END AS numdiascompletos,
					 CASE WHEN MAX(acr.datfim) IS NULL OR MIN(acr.datinicio) IS NULL THEN 0 
					      ELSE
					    (SELECT COUNT(*) AS diasuteis
					       FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(MIN(acr.datinicio), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP,
							TO_TIMESTAMP(TO_CHAR(MAX(acr.datfim), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP, INTERVAL  '1 day') the_day
					      WHERE EXTRACT('ISODOW' FROM the_day) < 6
						AND TO_CHAR(the_day,'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
								    LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0') AS ddmm
								   FROM agepnet200.tb_feriado tf
								  WHERE tf.flaativo = 'S'
								    AND tf.tipoferiado = '1')
						AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0') || '/' ||
									 LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   ,2,'0') || '/' ||
									 LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) ,4,'0') AS ddmmaaaa

						    FROM agepnet200.tb_feriado tf
								       WHERE tf.flaativo = 'S'
								     AND tf.tipoferiado = '2'))
					 END AS numdiasrealizados,
					 COALESCE(ROUND(CASE
					    WHEN MAX(acr.datfim) IS NULL OR MIN(acr.datinicio) IS NULL THEN 0::NUMERIC(5,2) 
					    ELSE
						(((SELECT COUNT(*) AS diasuteis
						     FROM GENERATE_SERIES(TO_TIMESTAMP(TO_CHAR(MIN(acr.datinicio), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP,
							      TO_TIMESTAMP(TO_CHAR(MAX(acr.datfim), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::TIMESTAMP, INTERVAL  '1 day') the_day
						    WHERE EXTRACT('ISODOW' FROM the_day) < 6
						      AND TO_CHAR(the_day, 'dd/mm') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99')), 2, '0') || '/' ||
									   LPAD(TRIM(TO_CHAR(tf.mesferiado, '99')), 2, '0') AS ddmm
									  FROM agepnet200.tb_feriado tf
									 WHERE tf.flaativo = 'S'
									   AND tf.tipoferiado = '1')
						      AND TO_CHAR(the_day,'dd/mm/yyyy') NOT IN (SELECT LPAD(TRIM(TO_CHAR(tf.diaferiado, '99'))   , 2, '0')||'/'||
									       LPAD(TRIM(TO_CHAR(tf.mesferiado, '99'))   , 2, '0')||'/'||
									       LPAD(TRIM(TO_CHAR(tf.anoferiado, '9999')) , 4, '0') AS ddmmaaaa

						  FROM agepnet200.tb_feriado tf
								     WHERE tf.flaativo = 'S'
								      AND tf.tipoferiado = '2'))
					     * COALESCE((CASE 
							    WHEN COUNT(ac.idatividadecronograma) > 0 THEN
								 ROUND((SUM(ac.numpercentualconcluido)::NUMERIC / COUNT(ac.idatividadecronograma)), 2)
							    ELSE 0::NUMERIC
							END), 0)) / 100)
					END), 1) AS numdiasrealizadosreal,
				       CASE 
					    WHEN COUNT(acm.idatividadecronograma) > 0 THEN
						 ROUND((SUM(acm.numpercentualconcluido)::NUMERIC / COUNT(acm.idatividadecronograma)), 2)
					    ELSE 0::NUMERIC
					END AS numpercentualconcluidomarco,
				       CASE 
					    WHEN COUNT(ac.idatividadecronograma) > 0 THEN
						 ROUND((SUM(ac.numpercentualconcluido)::NUMERIC / COUNT(ac.idatividadecronograma)), 2)
					    ELSE 0::NUMERIC
					END AS numpercentualconcluido,
					COUNT(ac.idatividadecronograma) AS totalatividadeporprojeto,
					SUM(CASE WHEN ac.numpercentualconcluido > 0 AND ac.numpercentualconcluido < 100 THEN 1 ELSE 0 END) AS qtdeatividadeiniciada,
					SUM(CASE WHEN ac.numpercentualconcluido = 0 THEN 1 ELSE 0 END) AS qtdeatividadenaoiniciada,
					SUM(CASE WHEN ac.numpercentualconcluido = 100 THEN 1 ELSE 0 END) AS qtdeatividadeconcluida
					  FROM agepnet200."AtividadeCronogramaRecursivo"(numidprojeto) acr
					  LEFT JOIN agepnet200.tb_atividadecronograma ac 
					    ON ac.idatividadecronograma = acr.idatividadecronograma
					   AND ac.idprojeto = acr.idprojeto 
					   AND ac.domtipoatividade = 3
					  LEFT JOIN agepnet200.tb_atividadecronograma acm 
					    ON acm.idatividadecronograma = acr.idatividadecronograma
					   AND acm.idprojeto = acr.idprojeto 
					   AND acm.domtipoatividade = 4) dadoscronogramas 
				  JOIN agepnet200.tb_projeto p 
				    ON p.idprojeto = dadoscronogramas.idprojeto
				) dadosProjeto
			) dadosProjeto 
		 WHERE tb_projeto.idprojeto = dadosProjeto.idprojeto;
  END;

  $$;


ALTER FUNCTION agepnet200."AtualizarPercentualProjeto"(numidprojeto integer) OWNER TO postgres;

--
-- TOC entry 429 (class 1255 OID 10422893)
-- Name: CalcularDataAnterior(character, integer); Type: FUNCTION; Schema: agepnet200; Owner: postgres
--

CREATE FUNCTION agepnet200."CalcularDataAnterior"(datainicio character, numdias integer) RETURNS text
    LANGUAGE plpgsql
    AS $$--*********************************************************************************
-- FUNCTION DE CALCULO DA DATA ANTERIOR COM BASE NA DATA INICIAL                 **
-- E O NUMERO DE DIAS DESEJADO                                                   **
-- PARA ATENDIMENTO DO REDMINE #14346                                            **
-- CRIADA EM 02/02/2018 POR MAURICIO GOMES PEREIRA                               **
-- ********************************************************************************
DECLARE
	rec RECORD;
	cont INTEGER;
	count INTEGER;
	diaFeriado INTEGER;
	CtDataFeriado INTEGER;
	dias INTEGER;
	query text;
	data text;
	novaData text;
BEGIN
        
	data 	   := datainicio;
	dias       := numdias;
	count      := 1;
	cont       := 1;
	diaFeriado := 0;
	--************ VERIFCA SE A DATA INICIAL E VALIDA ********************
	SELECT count(*) 
	    FROM generate_series( 
		    to_timestamp(data||' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, 
		    to_timestamp(data||' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
  	    ) the_day
	    WHERE  extract('ISODOW' FROM the_day) >=6
	    OR to_char(the_day,'dd/mm') in(                               
		   SELECT 
			lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado,'99')),2,'0') as ddmm
		   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
	    )
	    or to_char(the_day,'dd/mm/yyyy') in(
		   SELECT 
		     lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| 
		     lpad(trim(to_char(tf.mesferiado, '99')),2,'0')||'/'|| 
		     lpad(trim(to_char(tf.anoferiado, '9999')),4,'0') as ddmmaaaa
		   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
	    ) into CtDataFeriado;
	IF(CtDataFeriado>0) then
		return data;
	END IF;
	LOOP
            IF count > 1 THEN
		select to_char(to_date(data || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS') - 1,'DD/MM/YYYY') INTO novaData;
	    ELSE
	        select to_char(to_date(data || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS') ,'DD/MM/YYYY') INTO novaData;
            END IF;
	    --RAISE NOTICE ' - ';
	    --RAISE NOTICE ' - %', 'novaData:'   || novaData;
            --RAISE NOTICE ' - ';
	    SELECT count(*)  AS feriados
	    FROM generate_series( 
		    to_timestamp(novaData||' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, 
		    to_timestamp(novaData||' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
  	    ) the_day
	    WHERE  extract('ISODOW' FROM the_day) >=6
	    OR to_char(the_day,'dd/mm') in(                               
		   SELECT 
			lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado,'99')),2,'0') as ddmm
		   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
	    )
	    or to_char(the_day,'dd/mm/yyyy') in(
		   SELECT 
		     lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| 
		     lpad(trim(to_char(tf.mesferiado, '99')),2,'0')||'/'|| 
		     lpad(trim(to_char(tf.anoferiado, '9999')),4,'0') as ddmmaaaa
		   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
	    ) into diaFeriado;

	    --RAISE NOTICE ' - ';
	    --RAISE NOTICE ' - %', 'DIAS:'       || dias;
	    --RAISE NOTICE ' - %', 'count:'      || count;
	    --RAISE NOTICE ' - %', 'diaFeriado:' || diaFeriado;
	    IF(diaFeriado<1) THEN
		count := count + 1;
	    END IF; 
	    IF count > dias AND diaFeriado=0 THEN
		EXIT;  -- exit loop
	    ELSE
		data := novaData;
	    END IF;

	END LOOP; 
        RETURN novaData; 
        --RAISE NOTICE ' - ';
	--RAISE NOTICE ' - %', 'novaData - SAIDA:'   || novaData;
	--RAISE NOTICE ' SAIU ';
        --RAISE NOTICE ' - ';
        --RAISE NOTICE '% - %', rec.release_year, rec.title;
end;$$;


ALTER FUNCTION agepnet200."CalcularDataAnterior"(datainicio character, numdias integer) OWNER TO postgres;

--
-- TOC entry 431 (class 1255 OID 10422894)
-- Name: CalcularDataFim(character, integer); Type: FUNCTION; Schema: agepnet200; Owner: postgres
--

CREATE FUNCTION agepnet200."CalcularDataFim"(datainicio character, numdias integer) RETURNS text
    LANGUAGE plpgsql
    AS $$--*********************************************************************************
-- FUNCTION DE CALCULO DA DATA DE FIM COM BASE NA DATA INICIAL                   **
-- E O NUMERO DE DIAS DESEJADO                                                   **
-- PARA ATENDIMENTO DO REDMINE #14346                                            **
-- CRIADA EM 16/01/2018 POR MAURICIO GOMES PEREIRA                               **
-- ********************************************************************************
DECLARE
	rec RECORD;
	cont INTEGER;
	count INTEGER;
	diaFeriado INTEGER;
	CtDataFeriado INTEGER;
	dias INTEGER;
	query text;
	data text;
	novaData text;
BEGIN
        
	data 	   := datainicio;
	dias       := numdias;
	count      := 1;
	cont       := 1;
	diaFeriado := 0;
	--************ VERIFCA SE A DATA INICIAL E VALIDA ********************
	SELECT count(*) 
	    FROM generate_series( 
		    to_timestamp(data||' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, 
		    to_timestamp(data||' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
  	    ) the_day
	    WHERE  extract('ISODOW' FROM the_day) >=6
	    OR to_char(the_day,'dd/mm') in(                               
		   SELECT 
			lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado,'99')),2,'0') as ddmm
		   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
	    )
	    or to_char(the_day,'dd/mm/yyyy') in(
		   SELECT 
		     lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| 
		     lpad(trim(to_char(tf.mesferiado, '99')),2,'0')||'/'|| 
		     lpad(trim(to_char(tf.anoferiado, '9999')),4,'0') as ddmmaaaa
		   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
	    ) into CtDataFeriado;
	IF(CtDataFeriado>0) then
		return data;
	END IF;
	LOOP
            IF count > 1 THEN
		select to_char(to_date(data || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS') + 1,'DD/MM/YYYY') INTO novaData;
	    ELSE
	        select to_char(to_date(data || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS') ,'DD/MM/YYYY') INTO novaData;
            END IF;
	    --RAISE NOTICE ' - ';
	    --RAISE NOTICE ' - %', 'novaData:'   || novaData;
            --RAISE NOTICE ' - ';
	    SELECT count(*)  AS feriados
	    FROM generate_series( 
		    to_timestamp(novaData||' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, 
		    to_timestamp(novaData||' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
  	    ) the_day
	    WHERE  extract('ISODOW' FROM the_day) >=6
	    OR to_char(the_day,'dd/mm') in(                               
		   SELECT 
			lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado,'99')),2,'0') as ddmm
		   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
	    )
	    or to_char(the_day,'dd/mm/yyyy') in(
		   SELECT 
		     lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| 
		     lpad(trim(to_char(tf.mesferiado, '99')),2,'0')||'/'|| 
		     lpad(trim(to_char(tf.anoferiado, '9999')),4,'0') as ddmmaaaa
		   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
	    ) into diaFeriado;

	    --RAISE NOTICE ' - ';
	    --RAISE NOTICE ' - %', 'DIAS:'       || dias;
	    --RAISE NOTICE ' - %', 'count:'      || count;
	    --RAISE NOTICE ' - %', 'diaFeriado:' || diaFeriado;
	    IF(diaFeriado<1) THEN
		count := count + 1;
	    END IF; 
	    IF count > dias AND diaFeriado=0 THEN
		EXIT;  -- exit loop
	    ELSE
		data := novaData;
	    END IF;

	END LOOP; 
        RETURN novaData; 
        --RAISE NOTICE ' - ';
	--RAISE NOTICE ' - %', 'novaData - SAIDA:'   || novaData;
	--RAISE NOTICE ' SAIU ';
        --RAISE NOTICE ' - ';
        --RAISE NOTICE '% - %', rec.release_year, rec.title;
end;$$;


ALTER FUNCTION agepnet200."CalcularDataFim"(datainicio character, numdias integer) OWNER TO postgres;

--
-- TOC entry 400 (class 1255 OID 10422895)
-- Name: CalcularNovaDataFimCronograma(date, integer); Type: FUNCTION; Schema: agepnet200; Owner: postgres
--

CREATE FUNCTION agepnet200."CalcularNovaDataFimCronograma"(dataantiga date, numdias integer) RETURNS date
    LANGUAGE plpgsql
    AS $$

DECLARE
	dataNova DATE;
	idDataAntiga INTEGER;
BEGIN
	SELECT iddiautil 
	  INTO idDataAntiga 
	  FROM agepnet200.tb_diautil
	 WHERE datautil = dataantiga;

	SELECT datautil 
	  INTO dataNova 
	  FROM agepnet200.tb_diautil
	 WHERE iddiautil = (idDataAntiga + numdias);

	RETURN dataNova;
END;

$$;


ALTER FUNCTION agepnet200."CalcularNovaDataFimCronograma"(dataantiga date, numdias integer) OWNER TO postgres;

--
-- TOC entry 432 (class 1255 OID 10422896)
-- Name: ClonarCronograma(integer, integer, integer); Type: FUNCTION; Schema: agepnet200; Owner: postgres
--

CREATE FUNCTION agepnet200."ClonarCronograma"(idprojetobase integer, idprojetonovo integer, idcadastradornovo integer) RETURNS void
    LANGUAGE plpgsql
    AS $$

  DECLARE
    recCronogramaGrupo   RECORD;
    queryCronogramaGrupo TEXT;

    recCronogramaEntrega   RECORD;
    queryCronogramaEntrega TEXT;

    recCronogramaAtividade   RECORD;
    queryCronogramaAtividade TEXT;
   
    idAtividadeCronogramaNovo BIGINT;
    idAtividadeCronogramaGrupo BIGINT;
    idAtividadeCronogramaEntrega BIGINT;
  BEGIN

  queryCronogramaGrupo := 'SELECT ac.* 
			     FROM agepnet200."AtividadeCronogramaRecursivo"(' || idprojetobase || ') acr
			     JOIN agepnet200.tb_atividadecronograma ac 
			       ON ac.idprojeto = acr.idprojeto
			      AND ac.idatividadecronograma = acr.idatividadecronograma
			    WHERE ac.idprojeto = ' || idprojetobase || '
			      AND acr.idgrupo IS NULL
			    ORDER BY acr.ordenacao';

	idAtividadeCronogramaNovo := 0;

    FOR recCronogramaGrupo IN EXECUTE queryCronogramaGrupo LOOP
    
	idAtividadeCronogramaNovo := idAtividadeCronogramaNovo + 1;	
	
	-- Cadastrando o grupo 
	INSERT INTO agepnet200.tb_atividadecronograma(
		    idatividadecronograma, idprojeto, idgrupo, nomatividadecronograma, 
		    domtipoatividade, desobs, datcadastro, idmarcoanterior, numdias, 
		    vlratividadebaseline, vlratividade, numfolga, descriterioaceitacao, 
		    idelementodespesa, idcadastrador, idparteinteressada, datiniciobaseline, 
		    datfimbaseline, flaaquisicao, flainformatica, flacancelada, datinicio, 
		    datfim, numpercentualconcluido, numdiasbaseline, numdiasrealizados, 
		    numseq, flaordenacao, idresponsavel)
	    VALUES (idAtividadeCronogramaNovo, idprojetonovo, NULL, recCronogramaGrupo.nomatividadecronograma, 
		    recCronogramaGrupo.domtipoatividade, recCronogramaGrupo.desobs, CURRENT_TIMESTAMP, recCronogramaGrupo.idmarcoanterior, recCronogramaGrupo.numdias, 
		    recCronogramaGrupo.vlratividadebaseline, recCronogramaGrupo.vlratividade, recCronogramaGrupo.numfolga, recCronogramaGrupo.descriterioaceitacao, 
		    recCronogramaGrupo.idelementodespesa, idcadastradornovo, NULL, recCronogramaGrupo.datiniciobaseline, 
		    recCronogramaGrupo.datfimbaseline, recCronogramaGrupo.flaaquisicao, recCronogramaGrupo.flainformatica, recCronogramaGrupo.flacancelada, recCronogramaGrupo.datinicio, 
		    recCronogramaGrupo.datfim, recCronogramaGrupo.numpercentualconcluido, recCronogramaGrupo.numdiasbaseline, recCronogramaGrupo.numdiasrealizados, 
		    recCronogramaGrupo.numseq, recCronogramaGrupo.flaordenacao, NULL);

	queryCronogramaEntrega := 'SELECT ac.* 
				     FROM agepnet200."AtividadeCronogramaRecursivo"(' || idprojetobase || ') acr
				     JOIN agepnet200.tb_atividadecronograma ac 
				       ON ac.idprojeto = acr.idprojeto
				      AND ac.idatividadecronograma = acr.idatividadecronograma
				    WHERE ac.idprojeto = ' || idprojetobase || '
				      AND acr.idgrupo = '|| recCronogramaGrupo.idatividadecronograma ||'
				    ORDER BY acr.ordenacao';

	idAtividadeCronogramaGrupo := idAtividadeCronogramaNovo;

	FOR recCronogramaEntrega IN EXECUTE queryCronogramaEntrega LOOP	
	
		idAtividadeCronogramaNovo := idAtividadeCronogramaNovo + 1;	
		
		-- Cadastrando a entrega do grupo 
		INSERT INTO agepnet200.tb_atividadecronograma(
			    idatividadecronograma, idprojeto, idgrupo, nomatividadecronograma, 
			    domtipoatividade, desobs, datcadastro, idmarcoanterior, numdias, 
			    vlratividadebaseline, vlratividade, numfolga, descriterioaceitacao, 
			    idelementodespesa, idcadastrador, idparteinteressada, datiniciobaseline, 
			    datfimbaseline, flaaquisicao, flainformatica, flacancelada, datinicio, 
			    datfim, numpercentualconcluido, numdiasbaseline, numdiasrealizados, 
			    numseq, flaordenacao, idresponsavel)
		    VALUES (idAtividadeCronogramaNovo, idprojetonovo, idAtividadeCronogramaGrupo, recCronogramaEntrega.nomatividadecronograma, 
			    recCronogramaEntrega.domtipoatividade, recCronogramaEntrega.desobs, CURRENT_TIMESTAMP, recCronogramaEntrega.idmarcoanterior, recCronogramaEntrega.numdias, 
			    recCronogramaEntrega.vlratividadebaseline, recCronogramaEntrega.vlratividade, recCronogramaEntrega.numfolga, recCronogramaEntrega.descriterioaceitacao, 
			    recCronogramaEntrega.idelementodespesa, idcadastradornovo, recCronogramaEntrega.idparteinteressada, recCronogramaEntrega.datiniciobaseline, 
			    recCronogramaEntrega.datfimbaseline, recCronogramaEntrega.flaaquisicao, recCronogramaEntrega.flainformatica, recCronogramaEntrega.flacancelada, recCronogramaEntrega.datinicio, 
			    recCronogramaEntrega.datfim, recCronogramaEntrega.numpercentualconcluido, recCronogramaEntrega.numdiasbaseline, recCronogramaEntrega.numdiasrealizados, 
			    recCronogramaEntrega.numseq, recCronogramaEntrega.flaordenacao, NULL);


			queryCronogramaAtividade := 'SELECT ac.* 
						       FROM agepnet200."AtividadeCronogramaRecursivo"(' || idprojetobase || ') acr
						       JOIN agepnet200.tb_atividadecronograma ac 
						         ON ac.idprojeto = acr.idprojeto
						        AND ac.idatividadecronograma = acr.idatividadecronograma
						      WHERE ac.idprojeto = ' || idprojetobase || '
						        AND acr.idgrupo = '|| recCronogramaEntrega.idatividadecronograma ||'
						      ORDER BY acr.ordenacao';

			idAtividadeCronogramaEntrega := idAtividadeCronogramaNovo;
			    

			    FOR recCronogramaAtividade IN EXECUTE queryCronogramaAtividade LOOP	
	
				idAtividadeCronogramaNovo := idAtividadeCronogramaNovo + 1;	
				
				-- Cadastrando a entrega do grupo 
				INSERT INTO agepnet200.tb_atividadecronograma(
					    idatividadecronograma, idprojeto, idgrupo, nomatividadecronograma, 
					    domtipoatividade, desobs, datcadastro, idmarcoanterior, numdias, 
					    vlratividadebaseline, vlratividade, numfolga, descriterioaceitacao, 
					    idelementodespesa, idcadastrador, idparteinteressada, datiniciobaseline, 
					    datfimbaseline, flaaquisicao, flainformatica, flacancelada, datinicio, 
					    datfim, numpercentualconcluido, numdiasbaseline, numdiasrealizados, 
					    numseq, flaordenacao, idresponsavel)
				    VALUES (idAtividadeCronogramaNovo, idprojetonovo, idAtividadeCronogramaEntrega, recCronogramaAtividade.nomatividadecronograma, 
					    recCronogramaAtividade.domtipoatividade, recCronogramaAtividade.desobs, CURRENT_TIMESTAMP, recCronogramaAtividade.idmarcoanterior, recCronogramaAtividade.numdias, 
					    recCronogramaAtividade.vlratividadebaseline, recCronogramaAtividade.vlratividade, recCronogramaAtividade.numfolga, recCronogramaAtividade.descriterioaceitacao, 
					    recCronogramaAtividade.idelementodespesa, idcadastradornovo, recCronogramaAtividade.idparteinteressada, recCronogramaAtividade.datiniciobaseline, 
					    recCronogramaAtividade.datfimbaseline, recCronogramaAtividade.flaaquisicao, recCronogramaAtividade.flainformatica, recCronogramaAtividade.flacancelada, recCronogramaAtividade.datinicio, 
					    recCronogramaAtividade.datfim, recCronogramaAtividade.numpercentualconcluido, recCronogramaAtividade.numdiasbaseline, recCronogramaAtividade.numdiasrealizados, 
					    recCronogramaAtividade.numseq, recCronogramaAtividade.flaordenacao, NULL);
			END LOOP;
	END LOOP;	    

    END LOOP;
 
  END;

  $$;


ALTER FUNCTION agepnet200."ClonarCronograma"(idprojetobase integer, idprojetonovo integer, idcadastradornovo integer) OWNER TO postgres;

--
-- TOC entry 419 (class 1255 OID 10422897)
-- Name: ClonarProjeto(integer, integer, integer, character varying); Type: FUNCTION; Schema: agepnet200; Owner: postgres
--

CREATE FUNCTION agepnet200."ClonarProjeto"(idprojetobase integer, idcadastradornovo integer, idescritoriobase integer, nomcodigonovo character varying) RETURNS bigint
    LANGUAGE plpgsql
    AS $$

  DECLARE
	idProjetoNovo          BIGINT;
	recordProjetoBase      RECORD;
 
	queryParteInteressada  TEXT;
	recordParteInteressada RECORD;
	idParteInteressadaNovo INTEGER;

	queryParteFuncao       TEXT;
	recordParteFuncao      RECORD;
	idStatusReportNovo     INTEGER;     

	queryRisco             TEXT;
	recordRisco            RECORD;
	idRiscoNovo            INTEGER;    

	queryContraMedida      TEXT;
	recordContraMedida     RECORD;
	idContraMedidaNovo     INTEGER;     

	hoje		       TIMESTAMP := NOW();
	dataHoje               DATE      := NOW()::DATE;
  BEGIN

	-- CLONAGEM DO PROJETO  
	SELECT COALESCE(MAX(idprojeto), 0) + 1 INTO idProjetoNovo
	  FROM agepnet200.tb_projeto;
  
	SELECT * INTO recordProjetoBase
	  FROM agepnet200.tb_projeto
	 WHERE idprojeto = idprojetobase;

	INSERT INTO agepnet200.tb_projeto(
		    idprojeto, nomcodigo, nomsigla, nomprojeto, idsetor, idgerenteprojeto, 
		    idgerenteadjunto, desprojeto, desobjetivo, datinicio, datfim, 
		    numperiodicidadeatualizacao, numcriteriofarol, idcadastrador, 
		    datcadastro, domtipoprojeto, flapublicado, flaaprovado, desresultadosobtidos, 
		    despontosfortes, despontosfracos, dessugestoes, idescritorio, 
		    flaaltagestao, idobjetivo, idacao, flacopa, idnatureza, vlrorcamentodisponivel, 
		    desjustificativa, iddemandante, idpatrocinador, datinicioplano, 
		    datfimplano, desescopo, desnaoescopo, despremissa, desrestricao, 
		    numseqprojeto, numanoprojeto, desconsideracaofinal, datenviouemailatualizacao, 
		    idprograma, nomproponente, domstatusprojeto, ano, idportfolio, 
		    idtipoiniciativa, numpercentualconcluido, numpercentualprevisto, 
		    numprocessosei, atraso, numpercentualconcluidomarco, domcoratraso, 
		    qtdeatividadeiniciada, numpercentualiniciado, qtdeatividadenaoiniciada, 
		    numpercentualnaoiniciado, qtdeatividadeconcluida, numpercentualatividadeconcluido)
	VALUES (idProjetoNovo, nomCodigoNovo, NULL, SUBSTRING(recordProjetoBase.nomprojeto from 0 for 80) || ' (Projeto Clonado)', recordProjetoBase.idsetor, recordProjetoBase.idgerenteprojeto, 
		recordProjetoBase.idgerenteadjunto, recordProjetoBase.desprojeto, recordProjetoBase.desobjetivo, recordProjetoBase.datinicio, recordProjetoBase.datfim, 
		recordProjetoBase.numperiodicidadeatualizacao, recordProjetoBase.numcriteriofarol, idcadastradornovo, 
		dataHoje, recordProjetoBase.domtipoprojeto, recordProjetoBase.flapublicado, recordProjetoBase.flaaprovado, recordProjetoBase.desresultadosobtidos, 
		recordProjetoBase.despontosfortes, recordProjetoBase.despontosfracos, recordProjetoBase.dessugestoes, idEscritorioBase, 
		recordProjetoBase.flaaltagestao, recordProjetoBase.idobjetivo, recordProjetoBase.idacao, recordProjetoBase.flacopa, recordProjetoBase.idnatureza, recordProjetoBase.vlrorcamentodisponivel, 
		recordProjetoBase.desjustificativa, recordProjetoBase.iddemandante, recordProjetoBase.idpatrocinador, recordProjetoBase.datinicioplano, 
		recordProjetoBase.datfimplano, recordProjetoBase.desescopo, recordProjetoBase.desnaoescopo, recordProjetoBase.despremissa, recordProjetoBase.desrestricao, 
		recordProjetoBase.numseqprojeto, recordProjetoBase.numanoprojeto, recordProjetoBase.desconsideracaofinal, recordProjetoBase.datenviouemailatualizacao, 
		recordProjetoBase.idprograma, recordProjetoBase.nomproponente, 1, TO_CHAR(dataHoje, 'YYYY')::NUMERIC, recordProjetoBase.idportfolio, 
		recordProjetoBase.idtipoiniciativa, recordProjetoBase.numpercentualconcluido, recordProjetoBase.numpercentualprevisto, 
		recordProjetoBase.numprocessosei, recordProjetoBase.atraso, recordProjetoBase.numpercentualconcluidomarco, recordProjetoBase.domcoratraso, 
		recordProjetoBase.qtdeatividadeiniciada, recordProjetoBase.numpercentualiniciado, recordProjetoBase.qtdeatividadenaoiniciada, 
		recordProjetoBase.numpercentualnaoiniciado, recordProjetoBase.qtdeatividadeconcluida, recordProjetoBase.numpercentualatividadeconcluido);

	-- CLONAGEM  RH / TAP 
	-- ATUALIZANDO GERENTE DO PROJETO  
	UPDATE agepnet200.tb_projeto 
	   SET idgerenteprojeto = idcadastradornovo 
	 WHERE idprojeto = idProjetoNovo;

	-- CADASTRANDO OS PAPEIS DO TAP - Gerente do Projeto
	-- IMPORTANTE! => Rotina necessaria para a migracao/clonagem de gerente do projeto
	EXECUTE agepnet200."AtualizarFuncaoRhProjeto"(idProjetoNovo::INTEGER, idcadastradornovo, 0, recordProjetoBase.idgerenteprojeto, 1);
	EXECUTE agepnet200."AtualizarFuncaoRhProjeto"(idProjetoNovo::INTEGER, idcadastradornovo, recordProjetoBase.idgerenteprojeto, idcadastradornovo, 1);
	
	-- CADASTRANDO OS PAPEIS DO TAP - Adjunto do Projeto
	EXECUTE agepnet200."AtualizarFuncaoRhProjeto"(idProjetoNovo::INTEGER, idcadastradornovo, 0, recordProjetoBase.idgerenteadjunto, 2);
	
	-- CADASTRANDO OS PAPEIS DO TAP - Demandante do Projeto
	EXECUTE agepnet200."AtualizarFuncaoRhProjeto"(idProjetoNovo::INTEGER, idcadastradornovo, 0, recordProjetoBase.iddemandante, 3);
	
	-- CADASTRANDO OS PAPEIS DO TAP - Patrocinador do Projeto
	EXECUTE agepnet200."AtualizarFuncaoRhProjeto"(idProjetoNovo::INTEGER, idcadastradornovo, 0, recordProjetoBase.idpatrocinador, 4);

	-- CADASTRANDO Partes Interessadas e Equipe do Projeto 
	queryParteInteressada := 'SELECT pi.* 
				    FROM agepnet200.tb_parteinteressada pi 
				    JOIN agepnet200.tb_parteinteressada_funcoes pif 
				      ON pif.idparteinteressada = pi.idparteinteressada 
				     AND pif.idparteinteressadafuncao IN (5, 6)
				   WHERE pi.idprojeto = ' || idprojetobase || ' 
				     AND pi.idpessoainterna <> ' || idcadastradornovo ;

	SELECT COALESCE(MAX(idparteinteressada), 0) + 1 INTO idParteInteressadaNovo
	  FROM agepnet200.tb_parteinteressada;

	FOR recordParteInteressada IN EXECUTE queryParteInteressada
	LOOP
		INSERT INTO agepnet200.tb_parteinteressada(
			    idparteinteressada, idprojeto, nomparteinteressada, nomfuncao, 
			    destelefone, desemail, domnivelinfluencia, idcadastrador, datcadastro, 
			    idpessoainterna, observacao, tppermissao)
		VALUES (idParteInteressadaNovo, idProjetoNovo, recordParteInteressada.nomparteinteressada, NULL, 
			recordParteInteressada.destelefone, recordParteInteressada.desemail, recordParteInteressada.domnivelinfluencia, idcadastradornovo, hoje, 
			recordParteInteressada.idpessoainterna, recordParteInteressada.observacao, recordParteInteressada.tppermissao);

		queryParteFuncao := 'SELECT idparteinteressadafuncao 
				       FROM agepnet200.tb_parteinteressada_funcoes 
				      WHERE idparteinteressada = ' || recordParteInteressada.idparteinteressada;

			FOR recordParteFuncao IN EXECUTE queryParteFuncao
			LOOP
				INSERT INTO agepnet200.tb_parteinteressada_funcoes 
				VALUES (idParteInteressadaNovo, recordParteFuncao.idparteinteressadafuncao);

			END LOOP;
			
		idParteInteressadaNovo := idParteInteressadaNovo + 1;

	END LOOP;

	-- CLONANDO CRONOGRAMAS
	EXECUTE agepnet200."ClonarCronograma"(idprojetobase::INTEGER, idProjetoNovo::INTEGER, idcadastradornovo::INTEGER);

	-- CRIANDO STATUS REPORT INICIAL
	SELECT COALESCE(MAX(idstatusreport), 0) + 1 INTO idStatusReportNovo
	  FROM agepnet200.tb_statusreport;

	INSERT INTO agepnet200.tb_statusreport(
		idstatusreport, idprojeto, datacompanhamento, desatividadeconcluida, 
		desatividadeandamento, desmotivoatraso, desirregularidade, idmarco, 
		datmarcotendencia, datfimprojetotendencia, idcadastrador, datcadastro, 
		flaaprovado, domcorrisco, descontramedida, desrisco, domstatusprojeto, 
		dataprovacao, numpercentualconcluido, numpercentualprevisto, 
		numdiasprojeto, numpercentualmarcos, numpercentualdiferenca, 
		numpercentualcustoreal, numcustorealtotal, idresponsavelaceitacao, 
		pgpassinado, tepassinado, desandamentoprojeto, numpercentualconcluidomarco, 
		diaatraso, domcoratraso, numcriteriofarol, datfimprojeto)
	VALUES (idStatusReportNovo, idProjetoNovo, dataHoje, 'Projeto sem acompanhamento cadastrado.', 
		'Projeto sem acompanhamento cadastrado.', 'Projeto sem acompanhamento cadastrado.', 'Projeto sem acompanhamento cadastrado.', 1, 
		recordProjetoBase.datfim, recordProjetoBase.datfim, idcadastradornovo, dataHoje, 
		2::NUMERIC, 1::NUMERIC, 'Projeto sem acompanhamento cadastrado.', 'Projeto sem acompanhamento cadastrado.', 1, 
		NULL, 0::NUMERIC, 0::NUMERIC, 
		0, 0::NUMERIC, 0::NUMERIC, 
		0::NUMERIC, 0::BIGINT, 0, 
		'N', 'N', NULL, 0::NUMERIC, 
		0, 'success', recordProjetoBase.numcriteriofarol, recordProjetoBase.datfim);

	-- CLONANDO RISCO / CONTRA-MEDIDA 
	SELECT COALESCE(MAX(idrisco), 0) + 1 INTO idRiscoNovo
	  FROM agepnet200.tb_risco;

	SELECT COALESCE(MAX(idcontramedida), 0) + 1 INTO idContraMedidaNovo
	  FROM agepnet200.tb_contramedida;

	queryRisco := 'SELECT *
			 FROM agepnet200.tb_risco
                        WHERE idprojeto = ' || idprojetobase;

	FOR recordRisco IN EXECUTE queryRisco
	LOOP
		INSERT INTO agepnet200.tb_risco(
			idrisco, idprojeto, idorigemrisco, idetapa, idtiporisco, datdeteccao, 
			desrisco, domcorprobabilidade, domcorimpacto, domcorrisco, descausa, 
			desconsequencia, flariscoativo, datencerramentorisco, idcadastrador, 
			datcadastro, domtratamento, norisco, flaaprovado, datinatividade)
		VALUES (idRiscoNovo, idProjetoNovo, recordRisco.idorigemrisco, recordRisco.idetapa, recordRisco.idtiporisco, recordRisco.datdeteccao, 
		        recordRisco.desrisco, recordRisco.domcorprobabilidade, recordRisco.domcorimpacto, recordRisco.domcorrisco, recordRisco.descausa, 
		        recordRisco.desconsequencia, recordRisco.flariscoativo, recordRisco.datencerramentorisco, idcadastradornovo, 
		        dataHoje, recordRisco.domtratamento, recordRisco.norisco, 1::NUMERIC, recordRisco.datinatividade);

		queryContraMedida := 'SELECT *
				        FROM agepnet200.tb_contramedida 
				       WHERE idrisco = ' || recordRisco.idrisco;

		FOR recordContraMedida IN EXECUTE queryContraMedida
		LOOP
			INSERT INTO agepnet200.tb_contramedida(
				idcontramedida, idrisco, descontramedida, datprazocontramedida, 
				datprazocontramedidaatraso, domstatuscontramedida, flacontramedidaefetiva, 
				desresponsavel, idcadastrador, datcadastro, idtipocontramedida, 
				nocontramedida)
			VALUES (idContraMedidaNovo, idRiscoNovo, recordContraMedida.descontramedida, recordContraMedida.datprazocontramedida, 
				recordContraMedida.datprazocontramedidaatraso, recordContraMedida.domstatuscontramedida, recordContraMedida.flacontramedidaefetiva, 
				recordContraMedida.desresponsavel, idcadastradornovo, dataHoje, recordContraMedida.idtipocontramedida, 
				recordContraMedida.nocontramedida);

			idContraMedidaNovo := idContraMedidaNovo + 1;

		END LOOP;
			
		idRiscoNovo := idRiscoNovo + 1;

	END LOOP;
	
	RETURN idProjetoNovo;
	
  END;

  $$;


ALTER FUNCTION agepnet200."ClonarProjeto"(idprojetobase integer, idcadastradornovo integer, idescritoriobase integer, nomcodigonovo character varying) OWNER TO postgres;

--
-- TOC entry 399 (class 1255 OID 10421092)
-- Name: ultimoid_gatilho(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.ultimoid_gatilho() RETURNS integer
    LANGUAGE plpgsql
    AS $_$ 
declare
     r record;
begin
     select max(r.idtipo) from agepnet200.tb_tiposituacaoprojeto where id = $1;
     if not found then
         r.idtipo=1;
     end if;
     return r.idtipo;
end;
$_$;


ALTER FUNCTION public.ultimoid_gatilho() OWNER TO postgres;

--
-- TOC entry 277 (class 1259 OID 10422900)
-- Name: sq_diagnostico; Type: SEQUENCE; Schema: agepnet200; Owner: postgres
--

CREATE SEQUENCE agepnet200.sq_diagnostico
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 999999999
    CACHE 1;


ALTER TABLE agepnet200.sq_diagnostico OWNER TO postgres;

--
-- TOC entry 278 (class 1259 OID 10422902)
-- Name: sq_melhoria; Type: SEQUENCE; Schema: agepnet200; Owner: postgres
--

CREATE SEQUENCE agepnet200.sq_melhoria
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 999999999
    CACHE 1;


ALTER TABLE agepnet200.sq_melhoria OWNER TO postgres;

--
-- TOC entry 279 (class 1259 OID 10422904)
-- Name: sq_questionariodiagnostico; Type: SEQUENCE; Schema: agepnet200; Owner: postgres
--

CREATE SEQUENCE agepnet200.sq_questionariodiagnostico
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 999999999
    CACHE 1;


ALTER TABLE agepnet200.sq_questionariodiagnostico OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 280 (class 1259 OID 10422906)
-- Name: tb_acao; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_acao (
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


ALTER TABLE agepnet200.tb_acao OWNER TO postgres;

--
-- TOC entry 281 (class 1259 OID 10422915)
-- Name: tb_aceite; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_aceite (
    idaceite integer NOT NULL,
    desprodutoservico text,
    desparecerfinal text,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL
);


ALTER TABLE agepnet200.tb_aceite OWNER TO postgres;

--
-- TOC entry 282 (class 1259 OID 10422921)
-- Name: tb_aceiteatividadecronograma; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_aceiteatividadecronograma (
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


ALTER TABLE agepnet200.tb_aceiteatividadecronograma OWNER TO postgres;

--
-- TOC entry 4216 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN tb_aceiteatividadecronograma.idaceiteativcronograma; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_aceiteatividadecronograma.idaceiteativcronograma IS 'codigo de controle da tabela.';


--
-- TOC entry 4217 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN tb_aceiteatividadecronograma.identrega; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_aceiteatividadecronograma.identrega IS 'Codigo da entrega selecionada.';


--
-- TOC entry 283 (class 1259 OID 10422925)
-- Name: tb_acordo; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_acordo (
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


ALTER TABLE agepnet200.tb_acordo OWNER TO postgres;

--
-- TOC entry 284 (class 1259 OID 10422934)
-- Name: tb_acordoentidadeexterna; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_acordoentidadeexterna (
    idacordo integer NOT NULL,
    identidadeexterna integer NOT NULL
);


ALTER TABLE agepnet200.tb_acordoentidadeexterna OWNER TO postgres;

--
-- TOC entry 285 (class 1259 OID 10422937)
-- Name: tb_acordoespecieinstrumento; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_acordoespecieinstrumento (
    idacordoespecieinstrumento integer NOT NULL,
    nomacordoespecieinstrumento character varying(200) NOT NULL,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    flaativo character(1) NOT NULL,
    CONSTRAINT ckc_flaativo_tb_acord CHECK ((flaativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE agepnet200.tb_acordoespecieinstrumento OWNER TO postgres;

--
-- TOC entry 286 (class 1259 OID 10422941)
-- Name: tb_agenda; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_agenda (
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


ALTER TABLE agepnet200.tb_agenda OWNER TO postgres;

--
-- TOC entry 287 (class 1259 OID 10422948)
-- Name: tb_aquisicao; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_aquisicao (
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


ALTER TABLE agepnet200.tb_aquisicao OWNER TO postgres;

--
-- TOC entry 288 (class 1259 OID 10422951)
-- Name: tb_assinadocumento; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_assinadocumento (
    id integer NOT NULL,
    idprojeto integer NOT NULL,
    idpessoa integer NOT NULL,
    assinado timestamp with time zone NOT NULL,
    tipodoc integer NOT NULL,
    hashdoc character(100) NOT NULL,
    situacao character varying(1) NOT NULL,
    nomfuncao character varying(300),
    idaceite integer
);


ALTER TABLE agepnet200.tb_assinadocumento OWNER TO postgres;

--
-- TOC entry 4224 (class 0 OID 0)
-- Dependencies: 288
-- Name: TABLE tb_assinadocumento; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_assinadocumento IS 'Tabela que registra para as partes interessada do projeto os documentos assinados.';


--
-- TOC entry 4225 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN tb_assinadocumento.id; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_assinadocumento.id IS 'Coluna de identificacao do registro.';


--
-- TOC entry 4226 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN tb_assinadocumento.idprojeto; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_assinadocumento.idprojeto IS 'Coluna que identifica o projeto que o documento faz parte.';


--
-- TOC entry 4227 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN tb_assinadocumento.idpessoa; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_assinadocumento.idpessoa IS 'Coluna que identifica a parte interessada do projeto que assinou o documento.';


--
-- TOC entry 4228 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN tb_assinadocumento.assinado; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_assinadocumento.assinado IS 'Data e hora que foi assinado o documento pela parte interessada.';


--
-- TOC entry 4229 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN tb_assinadocumento.tipodoc; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_assinadocumento.tipodoc IS 'Coluna que define o tipo de documento assinado pela parte interessada. Valores possi�veis:
1 - TAP - Termo de abertura
2 - PGP - Plano Geral de Projeto
3 - TA - Termo de aceite.
4 - TEP - Termo de encerramento de projeto';


--
-- TOC entry 4230 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN tb_assinadocumento.hashdoc; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_assinadocumento.hashdoc IS 'Coluna que define o hash de autenticacao do documento.';


--
-- TOC entry 4231 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN tb_assinadocumento.situacao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_assinadocumento.situacao IS 'Coluna que defina a situacao da assinatura. Valores possiveis:
I - Inativo
A - Ativo';


--
-- TOC entry 4232 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN tb_assinadocumento.nomfuncao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_assinadocumento.nomfuncao IS 'Coluna que define o papel que o usuario exercia no projeto.';


--
-- TOC entry 4233 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN tb_assinadocumento.idaceite; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_assinadocumento.idaceite IS 'Coluna que define o identificador do termo de aceite que esta sendo assinado.';


--
-- TOC entry 289 (class 1259 OID 10422954)
-- Name: tb_ata; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_ata (
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


ALTER TABLE agepnet200.tb_ata OWNER TO postgres;

--
-- TOC entry 290 (class 1259 OID 10422960)
-- Name: tb_atividade; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_atividade (
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


ALTER TABLE agepnet200.tb_atividade OWNER TO postgres;

--
-- TOC entry 291 (class 1259 OID 10422968)
-- Name: tb_atividadecronograma; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_atividadecronograma (
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
    numseq numeric(4,0) DEFAULT 1 NOT NULL,
    flaordenacao character(1) DEFAULT 'S'::bpchar NOT NULL,
    idresponsavel integer,
    datatividadeconcluida date,
    CONSTRAINT ckc_domtipoatividade CHECK ((domtipoatividade = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric, (4)::numeric]))),
    CONSTRAINT ckc_flaordenacao CHECK ((flaordenacao = ANY (ARRAY['S'::bpchar, 'N'::bpchar]))),
    CONSTRAINT ckc_flashowhide CHECK ((flaordenacao = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE agepnet200.tb_atividadecronograma OWNER TO postgres;

--
-- TOC entry 4237 (class 0 OID 0)
-- Dependencies: 291
-- Name: COLUMN tb_atividadecronograma.datatividadeconcluida; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_atividadecronograma.datatividadeconcluida IS 'Data da atividade concluida.';


--
-- TOC entry 292 (class 1259 OID 10422983)
-- Name: tb_atividadecronopredecessora; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_atividadecronopredecessora (
    idatividadecronograma bigint NOT NULL,
    idprojetocronograma integer NOT NULL,
    idatividadepredecessora bigint NOT NULL
);


ALTER TABLE agepnet200.tb_atividadecronopredecessora OWNER TO postgres;

--
-- TOC entry 4239 (class 0 OID 0)
-- Dependencies: 292
-- Name: TABLE tb_atividadecronopredecessora; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_atividadecronopredecessora IS 'Tabela de relacionamento de predecessoras/sucessoras nos cronograma.';


--
-- TOC entry 4240 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN tb_atividadecronopredecessora.idatividadecronograma; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_atividadecronopredecessora.idatividadecronograma IS 'Chave da atividade que recebe a predecessora.';


--
-- TOC entry 4241 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN tb_atividadecronopredecessora.idprojetocronograma; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_atividadecronopredecessora.idprojetocronograma IS 'Chave do projeto.';


--
-- TOC entry 4242 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN tb_atividadecronopredecessora.idatividadepredecessora; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_atividadecronopredecessora.idatividadepredecessora IS 'Chave da predecessora.';


--
-- TOC entry 293 (class 1259 OID 10422986)
-- Name: tb_atividadeocultar; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_atividadeocultar (
    idprojeto integer NOT NULL,
    idatividadecronograma bigint NOT NULL,
    idpessoa integer NOT NULL,
    dtcadastro date DEFAULT ('now'::text)::date
);


ALTER TABLE agepnet200.tb_atividadeocultar OWNER TO postgres;

--
-- TOC entry 4244 (class 0 OID 0)
-- Dependencies: 293
-- Name: TABLE tb_atividadeocultar; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_atividadeocultar IS 'Tabela para registrar as atividades que devem ser ocultadas por pessoa no cronograma';


--
-- TOC entry 294 (class 1259 OID 10422990)
-- Name: tb_bloqueioprojeto; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_bloqueioprojeto (
    idbloqueioprojeto integer NOT NULL,
    idpessoa integer,
    datbloqueio date NOT NULL,
    datdesbloqueio date,
    desjustificativa text,
    idprojeto integer NOT NULL
);


ALTER TABLE agepnet200.tb_bloqueioprojeto OWNER TO postgres;

--
-- TOC entry 271 (class 1259 OID 10422337)
-- Name: tb_cargo; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_cargo (
    idcargo integer NOT NULL,
    dsdenominacao character varying NOT NULL,
    dssigla character varying(10) NOT NULL,
    ativo boolean DEFAULT true NOT NULL
);


ALTER TABLE agepnet200.tb_cargo OWNER TO postgres;

--
-- TOC entry 4247 (class 0 OID 0)
-- Dependencies: 271
-- Name: TABLE tb_cargo; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_cargo IS 'Tabela para informar os cargos dos sistema.';


--
-- TOC entry 272 (class 1259 OID 10422344)
-- Name: tb_cargo_idcargo_seq; Type: SEQUENCE; Schema: agepnet200; Owner: postgres
--

CREATE SEQUENCE agepnet200.tb_cargo_idcargo_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE agepnet200.tb_cargo_idcargo_seq OWNER TO postgres;

--
-- TOC entry 4249 (class 0 OID 0)
-- Dependencies: 272
-- Name: tb_cargo_idcargo_seq; Type: SEQUENCE OWNED BY; Schema: agepnet200; Owner: postgres
--

ALTER SEQUENCE agepnet200.tb_cargo_idcargo_seq OWNED BY agepnet200.tb_cargo.idcargo;


--
-- TOC entry 295 (class 1259 OID 10422996)
-- Name: tb_comentario; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_comentario (
    idcomentario integer NOT NULL,
    idprojeto integer NOT NULL,
    idatividadecronograma integer NOT NULL,
    dscomentario character varying(400) NOT NULL,
    dtcomentario timestamp with time zone NOT NULL,
    idpessoa integer NOT NULL
);


ALTER TABLE agepnet200.tb_comentario OWNER TO postgres;

--
-- TOC entry 4251 (class 0 OID 0)
-- Dependencies: 295
-- Name: TABLE tb_comentario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_comentario IS 'Tabela de comentarios dos grupos, entregas, atividades e marcos do cronograma.';


--
-- TOC entry 4252 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN tb_comentario.idcomentario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_comentario.idcomentario IS 'Coluna identificadora do registro.';


--
-- TOC entry 4253 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN tb_comentario.idprojeto; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_comentario.idprojeto IS 'Coluna idefinficadora do projeto que o comentario pertence';


--
-- TOC entry 4254 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN tb_comentario.idatividadecronograma; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_comentario.idatividadecronograma IS 'Coluna identificadora do grupo ou entrega ou atividade ou marco do cronograma.';


--
-- TOC entry 4255 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN tb_comentario.dscomentario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_comentario.dscomentario IS 'Coluna que descreve o comentario.';


--
-- TOC entry 4256 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN tb_comentario.dtcomentario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_comentario.dtcomentario IS 'Data e hora que foi adicionado o comentario pela parte interessada.';


--
-- TOC entry 4257 (class 0 OID 0)
-- Dependencies: 295
-- Name: COLUMN tb_comentario.idpessoa; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_comentario.idpessoa IS 'Coluna que identifica a parte interessada que adicionou o comentario.';


--
-- TOC entry 296 (class 1259 OID 10422999)
-- Name: tb_comunicacao; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_comunicacao (
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


ALTER TABLE agepnet200.tb_comunicacao OWNER TO postgres;

--
-- TOC entry 297 (class 1259 OID 10423005)
-- Name: tb_contramedida; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_contramedida (
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


ALTER TABLE agepnet200.tb_contramedida OWNER TO postgres;

--
-- TOC entry 298 (class 1259 OID 10423013)
-- Name: tb_diagnostico; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_diagnostico (
    iddiagnostico bigint NOT NULL,
    dsdiagnostico character varying(400) NOT NULL,
    idunidadeprincipal integer NOT NULL,
    dtinicio date,
    dtencerramento date,
    idcadastrador integer NOT NULL,
    dtcadastro date NOT NULL,
    ativo boolean DEFAULT true NOT NULL,
    sq_diagnostico integer NOT NULL,
    ano integer
);


ALTER TABLE agepnet200.tb_diagnostico OWNER TO postgres;

--
-- TOC entry 4261 (class 0 OID 0)
-- Dependencies: 298
-- Name: TABLE tb_diagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_diagnostico IS 'Tela que retgistra os diagnosticos.';


--
-- TOC entry 4262 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN tb_diagnostico.iddiagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_diagnostico.iddiagnostico IS 'Identificador do diagnostico.';


--
-- TOC entry 4263 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN tb_diagnostico.dsdiagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_diagnostico.dsdiagnostico IS 'Descreve o nome do diagnostico.';


--
-- TOC entry 4264 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN tb_diagnostico.idunidadeprincipal; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_diagnostico.idunidadeprincipal IS 'Identificador da unidade do DPF que sera a unidade principal para o diagnostico.';


--
-- TOC entry 4265 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN tb_diagnostico.dtinicio; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_diagnostico.dtinicio IS 'Data de inicio do diagnostico.';


--
-- TOC entry 4266 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN tb_diagnostico.dtencerramento; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_diagnostico.dtencerramento IS 'Data de encerramento do diagnostico.';


--
-- TOC entry 4267 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN tb_diagnostico.idcadastrador; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_diagnostico.idcadastrador IS 'Pessoa que cadastrou o diagnostico.';


--
-- TOC entry 4268 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN tb_diagnostico.dtcadastro; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_diagnostico.dtcadastro IS 'Data que foi cadastrado o diagnostico.';


--
-- TOC entry 4269 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN tb_diagnostico.ativo; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_diagnostico.ativo IS 'Inativa ou ativa o diagnostico';


--
-- TOC entry 299 (class 1259 OID 10423017)
-- Name: tb_diagnostico_sq_diagnostico_seq; Type: SEQUENCE; Schema: agepnet200; Owner: postgres
--

CREATE SEQUENCE agepnet200.tb_diagnostico_sq_diagnostico_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE agepnet200.tb_diagnostico_sq_diagnostico_seq OWNER TO postgres;

--
-- TOC entry 4271 (class 0 OID 0)
-- Dependencies: 299
-- Name: tb_diagnostico_sq_diagnostico_seq; Type: SEQUENCE OWNED BY; Schema: agepnet200; Owner: postgres
--

ALTER SEQUENCE agepnet200.tb_diagnostico_sq_diagnostico_seq OWNED BY agepnet200.tb_diagnostico.sq_diagnostico;


--
-- TOC entry 300 (class 1259 OID 10423019)
-- Name: tb_diariobordo; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_diariobordo (
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


ALTER TABLE agepnet200.tb_diariobordo OWNER TO postgres;

--
-- TOC entry 301 (class 1259 OID 10423026)
-- Name: tb_diautil; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_diautil (
    iddiautil integer NOT NULL,
    datautil date NOT NULL,
    ano integer NOT NULL
);


ALTER TABLE agepnet200.tb_diautil OWNER TO postgres;

--
-- TOC entry 4273 (class 0 OID 0)
-- Dependencies: 301
-- Name: COLUMN tb_diautil.iddiautil; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_diautil.iddiautil IS 'Id da tabela';


--
-- TOC entry 4274 (class 0 OID 0)
-- Dependencies: 301
-- Name: COLUMN tb_diautil.datautil; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_diautil.datautil IS 'Data valida';


--
-- TOC entry 4275 (class 0 OID 0)
-- Dependencies: 301
-- Name: COLUMN tb_diautil.ano; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_diautil.ano IS 'Ano';


--
-- TOC entry 302 (class 1259 OID 10423029)
-- Name: tb_diautil_iddiautil_seq; Type: SEQUENCE; Schema: agepnet200; Owner: postgres
--

CREATE SEQUENCE agepnet200.tb_diautil_iddiautil_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE agepnet200.tb_diautil_iddiautil_seq OWNER TO postgres;

--
-- TOC entry 4277 (class 0 OID 0)
-- Dependencies: 302
-- Name: tb_diautil_iddiautil_seq; Type: SEQUENCE OWNED BY; Schema: agepnet200; Owner: postgres
--

ALTER SEQUENCE agepnet200.tb_diautil_iddiautil_seq OWNED BY agepnet200.tb_diautil.iddiautil;


--
-- TOC entry 303 (class 1259 OID 10423031)
-- Name: tb_documento; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_documento (
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


ALTER TABLE agepnet200.tb_documento OWNER TO postgres;

--
-- TOC entry 304 (class 1259 OID 10423038)
-- Name: tb_elementodespesa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_elementodespesa (
    idelementodespesa integer NOT NULL,
    idoficial integer NOT NULL,
    nomelementodespesa character varying(100),
    idcadastrador integer,
    datcadastro date,
    numseq integer
);


ALTER TABLE agepnet200.tb_elementodespesa OWNER TO postgres;

--
-- TOC entry 305 (class 1259 OID 10423041)
-- Name: tb_entidadeexterna; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_entidadeexterna (
    identidadeexterna integer NOT NULL,
    nomentidadeexterna character varying(100) NOT NULL,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL
);


ALTER TABLE agepnet200.tb_entidadeexterna OWNER TO postgres;

--
-- TOC entry 306 (class 1259 OID 10423044)
-- Name: tb_escritorio; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_escritorio (
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


ALTER TABLE agepnet200.tb_escritorio OWNER TO postgres;

--
-- TOC entry 307 (class 1259 OID 10423049)
-- Name: tb_etapa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_etapa (
    idetapa integer NOT NULL,
    dsetapa character varying(100) NOT NULL,
    idcadastrador integer NOT NULL,
    dtcadastro date NOT NULL,
    pgpassinado character varying(1)
);


ALTER TABLE agepnet200.tb_etapa OWNER TO postgres;

--
-- TOC entry 308 (class 1259 OID 10423052)
-- Name: tb_evento; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_evento (
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


ALTER TABLE agepnet200.tb_evento OWNER TO postgres;

--
-- TOC entry 309 (class 1259 OID 10423058)
-- Name: tb_eventoavaliacao; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_eventoavaliacao (
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


ALTER TABLE agepnet200.tb_eventoavaliacao OWNER TO postgres;

--
-- TOC entry 310 (class 1259 OID 10423064)
-- Name: tb_feriado; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_feriado (
    idferiado integer NOT NULL,
    diaferiado integer NOT NULL,
    mesferiado integer NOT NULL,
    anoferiado integer NOT NULL,
    tipoferiado character(1) NOT NULL,
    desferiado character varying(200) NOT NULL,
    dtcadastro date DEFAULT ('now'::text)::date,
    flaativo character(1) DEFAULT 'S'::bpchar NOT NULL,
    CONSTRAINT ckc_flaativo_tb_fer CHECK ((flaativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar]))),
    CONSTRAINT ckc_tipoferiado_tb_fer CHECK ((tipoferiado = ANY (ARRAY['1'::bpchar, '2'::bpchar])))
);


ALTER TABLE agepnet200.tb_feriado OWNER TO postgres;

--
-- TOC entry 4285 (class 0 OID 0)
-- Dependencies: 310
-- Name: COLUMN tb_feriado.tipoferiado; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_feriado.tipoferiado IS '1-Fixo; 2-Variavel';


--
-- TOC entry 311 (class 1259 OID 10423071)
-- Name: tb_frase; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_frase (
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


ALTER TABLE agepnet200.tb_frase OWNER TO postgres;

--
-- TOC entry 312 (class 1259 OID 10423076)
-- Name: tb_frase_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_frase_pesquisa (
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


ALTER TABLE agepnet200.tb_frase_pesquisa OWNER TO postgres;

--
-- TOC entry 313 (class 1259 OID 10423081)
-- Name: tb_funcionalidade; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_funcionalidade (
    idfuncionalidade integer NOT NULL,
    no_funcionalidade character varying(80) NOT NULL,
    ds_funcionalidade character varying(255) NOT NULL,
    dtcadastro date NOT NULL
);


ALTER TABLE agepnet200.tb_funcionalidade OWNER TO postgres;

--
-- TOC entry 314 (class 1259 OID 10423084)
-- Name: tb_hst_publicacao; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_hst_publicacao (
    idhistoricopublicacao integer NOT NULL,
    idpesquisa integer NOT NULL,
    datpublicacao timestamp without time zone,
    datencerramento timestamp without time zone,
    idpespublicou integer,
    idpesencerrou integer
);


ALTER TABLE agepnet200.tb_hst_publicacao OWNER TO postgres;

--
-- TOC entry 315 (class 1259 OID 10423087)
-- Name: tb_item_secao; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_item_secao (
    id_item integer NOT NULL,
    ds_item character varying(200) NOT NULL,
    id_secao integer NOT NULL,
    ativo boolean DEFAULT true NOT NULL,
    idquestionariodiagnostico integer
);


ALTER TABLE agepnet200.tb_item_secao OWNER TO postgres;

--
-- TOC entry 4291 (class 0 OID 0)
-- Dependencies: 315
-- Name: TABLE tb_item_secao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_item_secao IS 'Tabela que define os itens da secao.';


--
-- TOC entry 4292 (class 0 OID 0)
-- Dependencies: 315
-- Name: COLUMN tb_item_secao.id_item; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_item_secao.id_item IS 'Identificador de itens da secao.';


--
-- TOC entry 4293 (class 0 OID 0)
-- Dependencies: 315
-- Name: COLUMN tb_item_secao.ds_item; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_item_secao.ds_item IS 'Descricao do item da secao.';


--
-- TOC entry 4294 (class 0 OID 0)
-- Dependencies: 315
-- Name: COLUMN tb_item_secao.id_secao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_item_secao.id_secao IS 'Identificador da secao ao qual o item pertence.';


--
-- TOC entry 4295 (class 0 OID 0)
-- Dependencies: 315
-- Name: COLUMN tb_item_secao.ativo; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_item_secao.ativo IS 'Define se o item esta ativo para apresentacao.
true - ativo
false - inativo.';


--
-- TOC entry 316 (class 1259 OID 10423091)
-- Name: tb_licao; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_licao (
    idlicao integer NOT NULL,
    idprojeto integer NOT NULL,
    identrega integer,
    desresultadosobtidos text,
    despontosfortes text,
    despontosfracos text,
    dessugestoes text,
    datcadastro date NOT NULL,
    idassociada integer
);


ALTER TABLE agepnet200.tb_licao OWNER TO postgres;

--
-- TOC entry 317 (class 1259 OID 10423097)
-- Name: tb_linhatempo; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_linhatempo (
    id integer NOT NULL,
    idpessoa integer NOT NULL,
    dsfuncaoprojeto character varying(300) NOT NULL,
    tpacao character(1) NOT NULL,
    dtacao timestamp with time zone NOT NULL,
    idprojeto integer NOT NULL,
    idrecurso integer NOT NULL
);


ALTER TABLE agepnet200.tb_linhatempo OWNER TO postgres;

--
-- TOC entry 4298 (class 0 OID 0)
-- Dependencies: 317
-- Name: COLUMN tb_linhatempo.id; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_linhatempo.id IS 'Coluna identificadora de registro';


--
-- TOC entry 4299 (class 0 OID 0)
-- Dependencies: 317
-- Name: COLUMN tb_linhatempo.idpessoa; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_linhatempo.idpessoa IS 'Coluna que identifica pessoa que realizou a acao.';


--
-- TOC entry 4300 (class 0 OID 0)
-- Dependencies: 317
-- Name: COLUMN tb_linhatempo.dsfuncaoprojeto; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_linhatempo.dsfuncaoprojeto IS 'Coluna que descreve a funcao que a pessoa desempenha no projeto.';


--
-- TOC entry 4301 (class 0 OID 0)
-- Dependencies: 317
-- Name: COLUMN tb_linhatempo.tpacao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_linhatempo.tpacao IS 'Coluna que define o tipo de acao executada na funcionalidade:
N - Novo
A - Alteracao
E - Exclusao';


--
-- TOC entry 4302 (class 0 OID 0)
-- Dependencies: 317
-- Name: COLUMN tb_linhatempo.dtacao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_linhatempo.dtacao IS 'Coluna que descreve a data e hora que a acao foi executada.';


--
-- TOC entry 4303 (class 0 OID 0)
-- Dependencies: 317
-- Name: COLUMN tb_linhatempo.idprojeto; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_linhatempo.idprojeto IS 'Coluna que define o projeto que sofreu a acao.';


--
-- TOC entry 4304 (class 0 OID 0)
-- Dependencies: 317
-- Name: COLUMN tb_linhatempo.idrecurso; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_linhatempo.idrecurso IS 'Coluna que identifica o registro dos controles  de modulos.';


--
-- TOC entry 318 (class 1259 OID 10423100)
-- Name: tb_logacesso; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_logacesso (
    idmodulo integer NOT NULL,
    idperfilpessoa integer,
    datacesso timestamp with time zone NOT NULL
);


ALTER TABLE agepnet200.tb_logacesso OWNER TO postgres;

--
-- TOC entry 319 (class 1259 OID 10423103)
-- Name: tb_manutencaogepnet; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_manutencaogepnet (
    idmanutencaogepnet integer NOT NULL,
    numprioridade integer,
    datfimmeta date,
    datfimreal date,
    desmanutencaogepnet text,
    desobs text,
    idcadastrador integer,
    datcadastro date,
    despaginaphp character varying(30),
    domtipomanutencao character varying(30),
    domsituacao character varying(30)
);


ALTER TABLE agepnet200.tb_manutencaogepnet OWNER TO postgres;

--
-- TOC entry 320 (class 1259 OID 10423109)
-- Name: tb_marco; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_marco (
    idmarco integer NOT NULL,
    idprojeto integer NOT NULL,
    numseq integer,
    nommarco character varying(100),
    datplanejado date,
    datprevisto date,
    datencerrado date,
    idcadastrador integer,
    datcadastro timestamp with time zone,
    idresponsavel integer DEFAULT 0
);


ALTER TABLE agepnet200.tb_marco OWNER TO postgres;

--
-- TOC entry 321 (class 1259 OID 10423113)
-- Name: tb_modulo; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_modulo (
    idmodulo integer NOT NULL,
    idmodulopai integer,
    numsequencial integer,
    nomitemmenu character varying(30) NOT NULL,
    deslink character varying(50),
    flaativo character(1) NOT NULL,
    flaitemmenu character(1) NOT NULL
);


ALTER TABLE agepnet200.tb_modulo OWNER TO postgres;

--
-- TOC entry 322 (class 1259 OID 10423116)
-- Name: tb_mudanca; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_mudanca (
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


ALTER TABLE agepnet200.tb_mudanca OWNER TO postgres;

--
-- TOC entry 323 (class 1259 OID 10423123)
-- Name: tb_natureza; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_natureza (
    idnatureza integer NOT NULL,
    nomnatureza character varying(100) NOT NULL,
    idcadastrador integer,
    datcadastro date,
    flaativo character(1) DEFAULT 's'::bpchar,
    CONSTRAINT ckc_flaativo CHECK (((flaativo IS NULL) OR ((flaativo)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text]))))
);


ALTER TABLE agepnet200.tb_natureza OWNER TO postgres;

--
-- TOC entry 324 (class 1259 OID 10423128)
-- Name: tb_objetivo; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_objetivo (
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


ALTER TABLE agepnet200.tb_objetivo OWNER TO postgres;

--
-- TOC entry 325 (class 1259 OID 10423138)
-- Name: tb_opcao_resposta; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_opcao_resposta (
    idresposta bigint NOT NULL,
    idpergunta bigint NOT NULL,
    desresposta character varying(300),
    escala integer,
    ordenacao integer,
    idquestionario bigint NOT NULL
);


ALTER TABLE agepnet200.tb_opcao_resposta OWNER TO postgres;

--
-- TOC entry 4313 (class 0 OID 0)
-- Dependencies: 325
-- Name: COLUMN tb_opcao_resposta.escala; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_opcao_resposta.escala IS 'Define um valor para resposta. Esse campo sera necessario para a contagem na escala de Likert.';


--
-- TOC entry 4314 (class 0 OID 0)
-- Dependencies: 325
-- Name: COLUMN tb_opcao_resposta.ordenacao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_opcao_resposta.ordenacao IS 'Define a ordem das respostas';


--
-- TOC entry 4315 (class 0 OID 0)
-- Dependencies: 325
-- Name: COLUMN tb_opcao_resposta.idquestionario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_opcao_resposta.idquestionario IS 'Coluna identificadora do questionario.';


--
-- TOC entry 326 (class 1259 OID 10423141)
-- Name: tb_origemrisco; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_origemrisco (
    idorigemrisco integer NOT NULL,
    desorigemrisco character varying(30),
    idcadastrador integer,
    dtcadastro date
);


ALTER TABLE agepnet200.tb_origemrisco OWNER TO postgres;

--
-- TOC entry 327 (class 1259 OID 10423144)
-- Name: tb_p_acao; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_p_acao (
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


ALTER TABLE agepnet200.tb_p_acao OWNER TO postgres;

--
-- TOC entry 328 (class 1259 OID 10423152)
-- Name: tb_partediagnostico; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_partediagnostico (
    idpartediagnostico integer NOT NULL,
    iddiagnostico integer NOT NULL,
    qualificacao character varying(1) DEFAULT '1'::character varying,
    idcadastrador integer,
    datcadastro timestamp with time zone,
    idpessoa integer NOT NULL,
    tppermissao character varying(1) DEFAULT '1'::character varying
);


ALTER TABLE agepnet200.tb_partediagnostico OWNER TO postgres;

--
-- TOC entry 4319 (class 0 OID 0)
-- Dependencies: 328
-- Name: COLUMN tb_partediagnostico.qualificacao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_partediagnostico.qualificacao IS 'Combobox de qualificacao com as opcoes: 
1 - Chefe da Unidade Diagnosticada,
2 - Ponto focal da Unidade Diagnosticada,
3 - Equipe do diagnostico';


--
-- TOC entry 4320 (class 0 OID 0)
-- Dependencies: 328
-- Name: COLUMN tb_partediagnostico.tppermissao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_partediagnostico.tppermissao IS 'Combobox(Permissao) com as opcoes: 
1 - Editar,
2 - Visualizar';


--
-- TOC entry 329 (class 1259 OID 10423157)
-- Name: tb_parteinteressada; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_parteinteressada (
    idparteinteressada integer NOT NULL,
    idprojeto integer NOT NULL,
    nomparteinteressada character varying(100),
    nomfuncao character varying(300),
    destelefone character varying(50),
    desemail character varying(50),
    domnivelinfluencia character varying(10),
    idcadastrador integer,
    datcadastro timestamp with time zone,
    idpessoainterna integer,
    observacao character(200),
    tppermissao character varying(1) DEFAULT '1'::character varying,
    status boolean DEFAULT true NOT NULL
);


ALTER TABLE agepnet200.tb_parteinteressada OWNER TO postgres;

--
-- TOC entry 4322 (class 0 OID 0)
-- Dependencies: 329
-- Name: COLUMN tb_parteinteressada.tppermissao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_parteinteressada.tppermissao IS 'Combobox(Permissao) com as opcoes: 1 - Editar, 2 - Visualizar';


--
-- TOC entry 4323 (class 0 OID 0)
-- Dependencies: 329
-- Name: COLUMN tb_parteinteressada.status; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_parteinteressada.status IS 'Define a situacao do registro para uma delecao logica.
True - Ativo
False - Inativo.';


--
-- TOC entry 330 (class 1259 OID 10423165)
-- Name: tb_parteinteressada_funcoes; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_parteinteressada_funcoes (
    idparteinteressada integer NOT NULL,
    idparteinteressadafuncao integer NOT NULL
);


ALTER TABLE agepnet200.tb_parteinteressada_funcoes OWNER TO postgres;

--
-- TOC entry 4325 (class 0 OID 0)
-- Dependencies: 330
-- Name: COLUMN tb_parteinteressada_funcoes.idparteinteressada; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_parteinteressada_funcoes.idparteinteressada IS 'Chave estrangeira com a tb_parteinteressada.';


--
-- TOC entry 4326 (class 0 OID 0)
-- Dependencies: 330
-- Name: COLUMN tb_parteinteressada_funcoes.idparteinteressadafuncao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_parteinteressada_funcoes.idparteinteressadafuncao IS 'Chave estrangeira com tb_parteinteressadafuncao.';


--
-- TOC entry 331 (class 1259 OID 10423168)
-- Name: tb_parteinteressadafuncao; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_parteinteressadafuncao (
    idparteinteressadafuncao integer NOT NULL,
    nomfuncao character varying(100) NOT NULL,
    numordem integer DEFAULT 0 NOT NULL
);


ALTER TABLE agepnet200.tb_parteinteressadafuncao OWNER TO postgres;

--
-- TOC entry 4328 (class 0 OID 0)
-- Dependencies: 331
-- Name: TABLE tb_parteinteressadafuncao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_parteinteressadafuncao IS 'Tabela com AS funcoes pre-definidas dos papeis/funcoes dentro do projeto.';


--
-- TOC entry 4329 (class 0 OID 0)
-- Dependencies: 331
-- Name: COLUMN tb_parteinteressadafuncao.idparteinteressadafuncao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_parteinteressadafuncao.idparteinteressadafuncao IS 'Chave primaria.';


--
-- TOC entry 332 (class 1259 OID 10423172)
-- Name: tb_parteinteressadafuncao_idparteinteressadafuncao_seq; Type: SEQUENCE; Schema: agepnet200; Owner: postgres
--

CREATE SEQUENCE agepnet200.tb_parteinteressadafuncao_idparteinteressadafuncao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE agepnet200.tb_parteinteressadafuncao_idparteinteressadafuncao_seq OWNER TO postgres;

--
-- TOC entry 4331 (class 0 OID 0)
-- Dependencies: 332
-- Name: tb_parteinteressadafuncao_idparteinteressadafuncao_seq; Type: SEQUENCE OWNED BY; Schema: agepnet200; Owner: postgres
--

ALTER SEQUENCE agepnet200.tb_parteinteressadafuncao_idparteinteressadafuncao_seq OWNED BY agepnet200.tb_parteinteressadafuncao.idparteinteressadafuncao;


--
-- TOC entry 333 (class 1259 OID 10423174)
-- Name: tb_perfil; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_perfil (
    idperfil integer NOT NULL,
    nomperfil character varying(50) NOT NULL,
    flaativo character(1) NOT NULL,
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    CONSTRAINT ckc_flaativo_tb_perfi CHECK ((flaativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE agepnet200.tb_perfil OWNER TO postgres;

--
-- TOC entry 334 (class 1259 OID 10423178)
-- Name: tb_perfilmodulo; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_perfilmodulo (
    idperfil integer NOT NULL,
    idmodulo integer NOT NULL
);


ALTER TABLE agepnet200.tb_perfilmodulo OWNER TO postgres;

--
-- TOC entry 335 (class 1259 OID 10423181)
-- Name: tb_perfilpessoa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_perfilpessoa (
    idpessoa integer NOT NULL,
    idperfil integer NOT NULL,
    idescritorio integer NOT NULL,
    flaativo character(1) NOT NULL,
    idcadastrador integer,
    datcadastro date,
    idperfilpessoa integer NOT NULL,
    CONSTRAINT ckc_flaativo CHECK ((flaativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE agepnet200.tb_perfilpessoa OWNER TO postgres;

--
-- TOC entry 336 (class 1259 OID 10423185)
-- Name: tb_pergunta; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_pergunta (
    idpergunta bigint NOT NULL,
    dspergunta character varying(300),
    tipopergunta numeric(1,0) NOT NULL,
    ativa boolean DEFAULT false NOT NULL,
    idquestionario bigint NOT NULL,
    posicao integer,
    id_secao bigint NOT NULL,
    tiporegistro numeric(1,0),
    dstitulo character varying(200)
);


ALTER TABLE agepnet200.tb_pergunta OWNER TO postgres;

--
-- TOC entry 4335 (class 0 OID 0)
-- Dependencies: 336
-- Name: TABLE tb_pergunta; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_pergunta IS 'Tabela de perguntas para o questionario criado.';


--
-- TOC entry 4336 (class 0 OID 0)
-- Dependencies: 336
-- Name: COLUMN tb_pergunta.idpergunta; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_pergunta.idpergunta IS 'Identificador do registro de perguntas.';


--
-- TOC entry 4337 (class 0 OID 0)
-- Dependencies: 336
-- Name: COLUMN tb_pergunta.dspergunta; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_pergunta.dspergunta IS 'Descricao da pergunta.';


--
-- TOC entry 4338 (class 0 OID 0)
-- Dependencies: 336
-- Name: COLUMN tb_pergunta.tipopergunta; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_pergunta.tipopergunta IS 'Tipo de pergunta com as seguintes opcoes:
1 - Descritiva
2 - Multipla escolha
3 - Unica escolha';


--
-- TOC entry 4339 (class 0 OID 0)
-- Dependencies: 336
-- Name: COLUMN tb_pergunta.ativa; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_pergunta.ativa IS 'Pergunta obrigatoria:
true = Sim
false = Nao.';


--
-- TOC entry 4340 (class 0 OID 0)
-- Dependencies: 336
-- Name: COLUMN tb_pergunta.idquestionario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_pergunta.idquestionario IS 'Identificador do questionario criado.';


--
-- TOC entry 4341 (class 0 OID 0)
-- Dependencies: 336
-- Name: COLUMN tb_pergunta.posicao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_pergunta.posicao IS 'Posicao que a pergunta sera apresentada no questionario.';


--
-- TOC entry 4342 (class 0 OID 0)
-- Dependencies: 336
-- Name: COLUMN tb_pergunta.id_secao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_pergunta.id_secao IS 'Define a qual secao ou subsecao pertence a pergunta.';


--
-- TOC entry 4343 (class 0 OID 0)
-- Dependencies: 336
-- Name: COLUMN tb_pergunta.tiporegistro; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_pergunta.tiporegistro IS 'Tipo de registro da resposta em banco de dados:
1 - Numerio
2 - Textual';


--
-- TOC entry 4344 (class 0 OID 0)
-- Dependencies: 336
-- Name: COLUMN tb_pergunta.dstitulo; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_pergunta.dstitulo IS 'Titulo da pergunta';


--
-- TOC entry 337 (class 1259 OID 10423192)
-- Name: tb_perm_funcionalidade; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_perm_funcionalidade (
    idpermissao integer NOT NULL,
    idfuncionalidade integer NOT NULL,
    principal character(1) NOT NULL,
    publicada character(1) NOT NULL,
    dtpublicada date NOT NULL,
    CONSTRAINT cc_principal CHECK ((principal = ANY (ARRAY['S'::bpchar, 'N'::bpchar]))),
    CONSTRAINT cc_publicada CHECK ((publicada = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE agepnet200.tb_perm_funcionalidade OWNER TO postgres;

--
-- TOC entry 338 (class 1259 OID 10423197)
-- Name: tb_permissao; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_permissao (
    idpermissao integer NOT NULL,
    idrecurso integer NOT NULL,
    ds_permissao character varying(200),
    no_permissao character varying(50),
    visualizar boolean DEFAULT false,
    tipo character(1)
);


ALTER TABLE agepnet200.tb_permissao OWNER TO postgres;

--
-- TOC entry 4347 (class 0 OID 0)
-- Dependencies: 338
-- Name: COLUMN tb_permissao.tipo; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissao.tipo IS 'Define o tipo de permissao:
G - Geral - Este dominio determina que a permissao possa ser atribuida tanto para quem possa visualizar e/ou editar o projeto;
E - Especifica - Este dominio determina que a permissao possa ser atribuida somente para quem possa editar o projeto.';


--
-- TOC entry 339 (class 1259 OID 10423201)
-- Name: tb_permissaodiagnostico; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_permissaodiagnostico (
    idpartediagnostico integer NOT NULL,
    iddiagnostico integer NOT NULL,
    idrecurso integer NOT NULL,
    idpermissao integer NOT NULL,
    idpessoa integer NOT NULL,
    data date NOT NULL,
    ativo character(1) DEFAULT 'S'::bpchar NOT NULL,
    CONSTRAINT ckc_ativo_ CHECK (((ativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])) AND ((ativo)::text = upper((ativo)::text))))
);


ALTER TABLE agepnet200.tb_permissaodiagnostico OWNER TO postgres;

--
-- TOC entry 4349 (class 0 OID 0)
-- Dependencies: 339
-- Name: COLUMN tb_permissaodiagnostico.idpartediagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.idpartediagnostico IS 'Identificacao das pessoas que fazem parte do diagnostico como parte interessada.';


--
-- TOC entry 4350 (class 0 OID 0)
-- Dependencies: 339
-- Name: COLUMN tb_permissaodiagnostico.iddiagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.iddiagnostico IS 'Identificador do diagnostico a ser configurado';


--
-- TOC entry 4351 (class 0 OID 0)
-- Dependencies: 339
-- Name: COLUMN tb_permissaodiagnostico.idrecurso; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.idrecurso IS 'Identificador do recurso a ser dada a permissao';


--
-- TOC entry 4352 (class 0 OID 0)
-- Dependencies: 339
-- Name: COLUMN tb_permissaodiagnostico.idpermissao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.idpermissao IS 'Identificador da pemrissao dada ao recurso.';


--
-- TOC entry 4353 (class 0 OID 0)
-- Dependencies: 339
-- Name: COLUMN tb_permissaodiagnostico.idpessoa; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.idpessoa IS 'Identificador de pessoa que manipulou a permissao da parte interessada no diagnostico.';


--
-- TOC entry 4354 (class 0 OID 0)
-- Dependencies: 339
-- Name: COLUMN tb_permissaodiagnostico.data; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.data IS 'Data que foi realizada a manipulacao do dado.';


--
-- TOC entry 4355 (class 0 OID 0)
-- Dependencies: 339
-- Name: COLUMN tb_permissaodiagnostico.ativo; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaodiagnostico.ativo IS 'Situacao da permissao cadastrada: S - Sim ativa N - Nao';


--
-- TOC entry 340 (class 1259 OID 10423206)
-- Name: tb_permissaoperfil; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_permissaoperfil (
    idpermissaoperfil integer NOT NULL,
    idperfil integer NOT NULL,
    idpermissao integer NOT NULL
);


ALTER TABLE agepnet200.tb_permissaoperfil OWNER TO postgres;

--
-- TOC entry 341 (class 1259 OID 10423209)
-- Name: tb_permissaoprojeto; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_permissaoprojeto (
    idparteinteressada integer NOT NULL,
    idprojeto integer NOT NULL,
    idrecurso integer NOT NULL,
    idpermissao integer NOT NULL,
    idpessoa integer NOT NULL,
    data date NOT NULL,
    ativo character(1) DEFAULT 'S'::bpchar NOT NULL,
    CONSTRAINT ckc_ativo_ CHECK (((ativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])) AND ((ativo)::text = upper((ativo)::text))))
);


ALTER TABLE agepnet200.tb_permissaoprojeto OWNER TO postgres;

--
-- TOC entry 4358 (class 0 OID 0)
-- Dependencies: 341
-- Name: COLUMN tb_permissaoprojeto.idparteinteressada; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaoprojeto.idparteinteressada IS 'Identificacao das pessoas que fazem parte do projeto como parte interessada interna.';


--
-- TOC entry 4359 (class 0 OID 0)
-- Dependencies: 341
-- Name: COLUMN tb_permissaoprojeto.idprojeto; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaoprojeto.idprojeto IS 'Identificador do projeto a ser configurado';


--
-- TOC entry 4360 (class 0 OID 0)
-- Dependencies: 341
-- Name: COLUMN tb_permissaoprojeto.idrecurso; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaoprojeto.idrecurso IS 'Identificador do recurso a ser dada a permissao';


--
-- TOC entry 4361 (class 0 OID 0)
-- Dependencies: 341
-- Name: COLUMN tb_permissaoprojeto.idpermissao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaoprojeto.idpermissao IS 'Identificador da pemrissao dada ao recurso.';


--
-- TOC entry 4362 (class 0 OID 0)
-- Dependencies: 341
-- Name: COLUMN tb_permissaoprojeto.idpessoa; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaoprojeto.idpessoa IS 'Identificador de pessoa que manipulou a permissao da parte interessada no projeto.';


--
-- TOC entry 4363 (class 0 OID 0)
-- Dependencies: 341
-- Name: COLUMN tb_permissaoprojeto.data; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaoprojeto.data IS 'Data que foi realizada a manipulacao do dado.';


--
-- TOC entry 4364 (class 0 OID 0)
-- Dependencies: 341
-- Name: COLUMN tb_permissaoprojeto.ativo; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_permissaoprojeto.ativo IS 'Situacao da permissao cadastrada: S - Sim ativa N - Nao';


--
-- TOC entry 342 (class 1259 OID 10423214)
-- Name: tb_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_pesquisa (
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


ALTER TABLE agepnet200.tb_pesquisa OWNER TO postgres;

--
-- TOC entry 343 (class 1259 OID 10423218)
-- Name: tb_pessoa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_pessoa (
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
    domcargo character varying(10),
    id_servidor integer,
    flaagenda character varying(1) DEFAULT 'S'::character varying,
    numcpf character varying(11),
    numsiape bigint,
    versaosistema character varying(10),
    token character varying(255)
);


ALTER TABLE agepnet200.tb_pessoa OWNER TO postgres;

--
-- TOC entry 4367 (class 0 OID 0)
-- Dependencies: 343
-- Name: COLUMN tb_pessoa.versaosistema; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_pessoa.versaosistema IS 'Define a ultima versao visualizada pelo usuario.';


--
-- TOC entry 344 (class 1259 OID 10423225)
-- Name: tb_pessoaagenda; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_pessoaagenda (
    idagenda integer NOT NULL,
    idpessoa integer NOT NULL
);


ALTER TABLE agepnet200.tb_pessoaagenda OWNER TO postgres;

--
-- TOC entry 345 (class 1259 OID 10423228)
-- Name: tb_portfolio; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_portfolio (
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


ALTER TABLE agepnet200.tb_portfolio OWNER TO postgres;

--
-- TOC entry 346 (class 1259 OID 10423233)
-- Name: tb_portifolioprograma; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_portifolioprograma (
    idprograma integer NOT NULL,
    idportfolio integer NOT NULL
);


ALTER TABLE agepnet200.tb_portifolioprograma OWNER TO postgres;

--
-- TOC entry 347 (class 1259 OID 10423236)
-- Name: tb_processo; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_processo (
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


ALTER TABLE agepnet200.tb_processo OWNER TO postgres;

--
-- TOC entry 348 (class 1259 OID 10423242)
-- Name: tb_programa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_programa (
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


ALTER TABLE agepnet200.tb_programa OWNER TO postgres;

--
-- TOC entry 349 (class 1259 OID 10423249)
-- Name: tb_projeto; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_projeto (
    idprojeto integer NOT NULL,
    nomcodigo character varying(50),
    nomsigla character varying(20),
    nomprojeto character varying(100),
    idsetor integer,
    idgerenteprojeto integer NOT NULL,
    idgerenteadjunto integer,
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
    iddemandante integer,
    idpatrocinador integer,
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
    idtipoiniciativa integer DEFAULT 1 NOT NULL,
    numpercentualconcluido numeric(5,2) DEFAULT 0,
    numpercentualprevisto numeric(5,2) DEFAULT 0,
    numprocessosei character varying(20),
    atraso character varying(20) DEFAULT 0,
    numpercentualconcluidomarco numeric(5,2),
    domcoratraso character varying(10),
    qtdeatividadeiniciada numeric(5,2) DEFAULT 0,
    numpercentualiniciado numeric(5,2) DEFAULT 0,
    qtdeatividadenaoiniciada numeric(5,2) DEFAULT 0,
    numpercentualnaoiniciado numeric(5,2) DEFAULT 0,
    qtdeatividadeconcluida numeric(5,2) DEFAULT 0,
    numpercentualatividadeconcluido numeric(5,2) DEFAULT 0,
    CONSTRAINT ckc_flaaltagestao CHECK (((flaaltagestao IS NULL) OR ((flaaltagestao)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text])))),
    CONSTRAINT ckc_flaaprovado CHECK (((flaaprovado IS NULL) OR ((flaaprovado)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text])))),
    CONSTRAINT ckc_flacopa CHECK (((flacopa IS NULL) OR ((flacopa)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text])))),
    CONSTRAINT ckc_flapublicado CHECK (((flapublicado IS NULL) OR ((flapublicado)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text])))),
    CONSTRAINT ckc_statusprojeto CHECK (((domstatusprojeto IS NULL) OR (domstatusprojeto = ANY (ARRAY[1, 2, 3, 4, 5, 6, 7, 8]))))
);


ALTER TABLE agepnet200.tb_projeto OWNER TO postgres;

--
-- TOC entry 4374 (class 0 OID 0)
-- Dependencies: 349
-- Name: COLUMN tb_projeto.idtipoiniciativa; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_projeto.idtipoiniciativa IS 'Id Tipo Iniciativa';


--
-- TOC entry 4375 (class 0 OID 0)
-- Dependencies: 349
-- Name: COLUMN tb_projeto.atraso; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_projeto.atraso IS 'Coluna que define a quantidade de dias de atraso do projeto.';


--
-- TOC entry 4376 (class 0 OID 0)
-- Dependencies: 349
-- Name: COLUMN tb_projeto.numpercentualconcluidomarco; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_projeto.numpercentualconcluidomarco IS 'Coluna que define o numero de percentual concluido dos marcos do projeto.';


--
-- TOC entry 4377 (class 0 OID 0)
-- Dependencies: 349
-- Name: COLUMN tb_projeto.domcoratraso; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_projeto.domcoratraso IS 'Coluna que define a cor dos dias de atraso do projeto. default - atraso 0 inicio do projeto
success - no praso ou adiantada warning - fora do prazo mais dentro da margem de criterio farol important - fora do prado e do criterio farol.';


--
-- TOC entry 350 (class 1259 OID 10423272)
-- Name: tb_projetoprocesso; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_projetoprocesso (
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


ALTER TABLE agepnet200.tb_projetoprocesso OWNER TO postgres;

--
-- TOC entry 351 (class 1259 OID 10423279)
-- Name: tb_questdiagnosticopadronizamelhoria; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_questdiagnosticopadronizamelhoria (
    idpadronizacaomelhoria bigint NOT NULL,
    idmelhoria bigint,
    desrevisada text NOT NULL,
    idprazo integer NOT NULL,
    idimpacto integer NOT NULL,
    idesforco integer NOT NULL,
    numpontuacao numeric(5,2),
    numincidencia numeric(5,2),
    numvotacao integer,
    flaagrupadora boolean,
    destitulogrupo text,
    desinformacoescomplementares text,
    desmelhoriaagrupadora bigint
);


ALTER TABLE agepnet200.tb_questdiagnosticopadronizamelhoria OWNER TO postgres;

--
-- TOC entry 4380 (class 0 OID 0)
-- Dependencies: 351
-- Name: TABLE tb_questdiagnosticopadronizamelhoria; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_questdiagnosticopadronizamelhoria IS 'Padronizacoes realizadas para as melhorias dos diagnosticos.';


--
-- TOC entry 4381 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.idpadronizacaomelhoria; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.idpadronizacaomelhoria IS 'Sequencial gerado automaticamente para padronizacao da sugestao de melhoria.';


--
-- TOC entry 4382 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.idmelhoria; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.idmelhoria IS 'Numero da sugestao de melhoria padronizada.';


--
-- TOC entry 4383 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.desrevisada; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.desrevisada IS 'Revisao da descricao de melhoria ja cadastrada na tabela de melhoria do diagnostico.';


--
-- TOC entry 4384 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.idprazo; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.idprazo IS 'Prazo da padronizacao da melhoria: 1-Baixo/2-Medio/3-Alto/4-Ate 6 meses.';


--
-- TOC entry 4385 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.idimpacto; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.idimpacto IS 'Impacto da padronizacao da melhoria: 1-Baixo/2-Medio/3-Alto.';


--
-- TOC entry 4386 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.idesforco; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.idesforco IS 'Esforco da padronizacao da melhoria: 4-Alto/3-Medio/2-Baixo/1-Irrelevante.';


--
-- TOC entry 4387 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.numpontuacao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.numpontuacao IS 'Pontuacao da padronizacao da melhoria: (Valor da selecao do prazo* Valor da selecao do impacto* Valor da selecao do esforco) /48).';


--
-- TOC entry 4388 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.numincidencia; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.numincidencia IS 'Incidencia da padronizacao da melhoria que apresenta a quantidade de melhorias que possuem a mesma agrupadora. So sera apresentado caso seja selecionado melhoria agrupadora.';


--
-- TOC entry 4389 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.numvotacao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.numvotacao IS 'Votacao padronizacao melhoria: campo numerico e editavel.';


--
-- TOC entry 4390 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.flaagrupadora; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.flaagrupadora IS 'Agrupadora da padronizacao da melhoria: Sim/Nao.';


--
-- TOC entry 4391 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.destitulogrupo; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.destitulogrupo IS 'Titulo do Grupo (So sera apresentado caso selecionado “Sim” no campo Agrupadora, campo obrigatorio se apresentado).';


--
-- TOC entry 4392 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.desinformacoescomplementares; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.desinformacoescomplementares IS 'Informacoes complementares para a padronizacao da melhoria.';


--
-- TOC entry 4393 (class 0 OID 0)
-- Dependencies: 351
-- Name: COLUMN tb_questdiagnosticopadronizamelhoria.desmelhoriaagrupadora; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questdiagnosticopadronizamelhoria.desmelhoriaagrupadora IS 'Melhoria Agrupadora (So sera apresentada se a situacao da melhoria for “Agrupadora”, campo obrigatorio se apresentado. Apresenta todos os titulos de grupo cadastrados em outras melhorias).';


--
-- TOC entry 352 (class 1259 OID 10423285)
-- Name: tb_questionario; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_questionario (
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


ALTER TABLE agepnet200.tb_questionario OWNER TO postgres;

--
-- TOC entry 353 (class 1259 OID 10423294)
-- Name: tb_questionario_diagnostico; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_questionario_diagnostico (
    idquestionariodiagnostico bigint DEFAULT nextval('agepnet200.sq_questionariodiagnostico'::regclass) NOT NULL,
    nomquestionario character varying(400) NOT NULL,
    tipo character(1) DEFAULT 1 NOT NULL,
    observacao text,
    idpescadastrador integer NOT NULL,
    dtcadastro date NOT NULL
);


ALTER TABLE agepnet200.tb_questionario_diagnostico OWNER TO postgres;

--
-- TOC entry 4396 (class 0 OID 0)
-- Dependencies: 353
-- Name: TABLE tb_questionario_diagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_questionario_diagnostico IS 'Tabela de questionarios para os diagnosticos';


--
-- TOC entry 4397 (class 0 OID 0)
-- Dependencies: 353
-- Name: COLUMN tb_questionario_diagnostico.idquestionariodiagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.idquestionariodiagnostico IS 'Coluna de identificacao de registros do questionario.';


--
-- TOC entry 4398 (class 0 OID 0)
-- Dependencies: 353
-- Name: COLUMN tb_questionario_diagnostico.nomquestionario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.nomquestionario IS 'Descricao do nome do questionario.';


--
-- TOC entry 4399 (class 0 OID 0)
-- Dependencies: 353
-- Name: COLUMN tb_questionario_diagnostico.tipo; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.tipo IS 'Coluna que define o tipo do questionario com as seguintes opcoes:
1 - Servidor
2 - Cidadao';


--
-- TOC entry 4400 (class 0 OID 0)
-- Dependencies: 353
-- Name: COLUMN tb_questionario_diagnostico.observacao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.observacao IS 'Coluna de observacoes do questionario.';


--
-- TOC entry 4401 (class 0 OID 0)
-- Dependencies: 353
-- Name: COLUMN tb_questionario_diagnostico.idpescadastrador; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.idpescadastrador IS 'Pessoa que cadastrou ou questionario.';


--
-- TOC entry 4402 (class 0 OID 0)
-- Dependencies: 353
-- Name: COLUMN tb_questionario_diagnostico.dtcadastro; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionario_diagnostico.dtcadastro IS 'Data do cadastramento do questionario.';


--
-- TOC entry 354 (class 1259 OID 10423302)
-- Name: tb_questionario_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_questionario_pesquisa (
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


ALTER TABLE agepnet200.tb_questionario_pesquisa OWNER TO postgres;

--
-- TOC entry 355 (class 1259 OID 10423309)
-- Name: tb_questionariodiagnostico_respondido; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_questionariodiagnostico_respondido (
    idquestionario bigint NOT NULL,
    iddiagnostico bigint NOT NULL,
    numero bigint NOT NULL,
    dt_resposta date NOT NULL,
    idpessoaresposta integer NOT NULL
);


ALTER TABLE agepnet200.tb_questionariodiagnostico_respondido OWNER TO postgres;

--
-- TOC entry 4405 (class 0 OID 0)
-- Dependencies: 355
-- Name: TABLE tb_questionariodiagnostico_respondido; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_questionariodiagnostico_respondido IS 'Tabela de historico de questionario respondido.';


--
-- TOC entry 4406 (class 0 OID 0)
-- Dependencies: 355
-- Name: COLUMN tb_questionariodiagnostico_respondido.idquestionario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnostico_respondido.idquestionario IS 'Coluna identificadora do questionario vinculado ao diagnostico.';


--
-- TOC entry 4407 (class 0 OID 0)
-- Dependencies: 355
-- Name: COLUMN tb_questionariodiagnostico_respondido.iddiagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnostico_respondido.iddiagnostico IS 'Coluna identificadora do diagnostico vinculado ao questionario.';


--
-- TOC entry 4408 (class 0 OID 0)
-- Dependencies: 355
-- Name: COLUMN tb_questionariodiagnostico_respondido.numero; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnostico_respondido.numero IS 'Coluna que identifica o numero do questionario respondido.';


--
-- TOC entry 4409 (class 0 OID 0)
-- Dependencies: 355
-- Name: COLUMN tb_questionariodiagnostico_respondido.dt_resposta; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnostico_respondido.dt_resposta IS 'Coluna que define a data e hora que foi respondido o questionario;';


--
-- TOC entry 4410 (class 0 OID 0)
-- Dependencies: 355
-- Name: COLUMN tb_questionariodiagnostico_respondido.idpessoaresposta; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnostico_respondido.idpessoaresposta IS 'Coluna que identifica a pessoa que cadastrou o questionario.';


--
-- TOC entry 356 (class 1259 OID 10423312)
-- Name: tb_questionariodiagnosticomelhoria; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_questionariodiagnosticomelhoria (
    idmelhoria bigint NOT NULL,
    datmelhoria date NOT NULL,
    desmelhoria text NOT NULL,
    idmacroprocessotrabalho integer NOT NULL,
    idmacroprocessomelhorar integer NOT NULL,
    idunidaderesponsavelproposta integer NOT NULL,
    flaabrangencia "char" NOT NULL,
    idunidaderesponsavelimplantacao integer NOT NULL,
    idobjetivoinstitucional integer,
    idacaoestrategica integer,
    idareamelhoria integer,
    idsituacao integer,
    iddiagnostico bigint,
    idunidadeprincipal integer NOT NULL,
    matriculaproponente integer
);


ALTER TABLE agepnet200.tb_questionariodiagnosticomelhoria OWNER TO postgres;

--
-- TOC entry 4412 (class 0 OID 0)
-- Dependencies: 356
-- Name: TABLE tb_questionariodiagnosticomelhoria; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_questionariodiagnosticomelhoria IS 'Sugestoes de melhorias para os diagnosticos.';


--
-- TOC entry 4413 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.idmelhoria; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idmelhoria IS 'Sequencial gerado automaticamente para a sugestao de melhoria do diagnostico.';


--
-- TOC entry 4414 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.datmelhoria; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.datmelhoria IS 'Data da melhoria do diagnostico.';


--
-- TOC entry 4415 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.desmelhoria; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.desmelhoria IS 'Descricao da melhoria do diagnostico.';


--
-- TOC entry 4416 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.idmacroprocessotrabalho; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idmacroprocessotrabalho IS 'Macroprocesso de trabalho para a melhoria do diagnostico (recupera do banco de dados).';


--
-- TOC entry 4417 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.idmacroprocessomelhorar; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idmacroprocessomelhorar IS 'Macroprocesso a ser melhorado para a melhoria do diagnostico (recupera do banco de dados).';


--
-- TOC entry 4418 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.idunidaderesponsavelproposta; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idunidaderesponsavelproposta IS 'Unidade responsavel pela proposta de melhoria do diagnostico (recupera todas as unidades vinculadas a unidade principal do diagnostico).';


--
-- TOC entry 4419 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.flaabrangencia; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.flaabrangencia IS 'Abrangencia: Local(L)/Nacional(N).';


--
-- TOC entry 4420 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.idunidaderesponsavelimplantacao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idunidaderesponsavelimplantacao IS 'Se a abrangencia for local apresenta Unidades vinculadas a unidade principaldo diagnostico. Caso a abrangencia seja Nacional apresenta todas as delegacias.';


--
-- TOC entry 4421 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.idobjetivoinstitucional; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idobjetivoinstitucional IS 'Objetivos institucionais existentes no banco de dados.';


--
-- TOC entry 4422 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.idacaoestrategica; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idacaoestrategica IS 'Acoes estrategicas vinculadas ao objetivo institucional anteriormente escolhido.';


--
-- TOC entry 4423 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.idareamelhoria; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idareamelhoria IS 'Areas de melhorias: Simplificacao/Normatizacao/Gerenciamento/Automacao/Capacitacao/Interfaces/Estrutura/Inovacao.';


--
-- TOC entry 4424 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.idsituacao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idsituacao IS 'Situacoes de melhorias: Registrada/Validada/Priorizada/Implantada/Suspensa/Agrupada.';


--
-- TOC entry 4425 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.iddiagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.iddiagnostico IS 'Numero do diagnostico referente a sugestao de melhorias oriundo da tabela tb_diagnostico.';


--
-- TOC entry 4426 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.idunidadeprincipal; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.idunidadeprincipal IS 'Coluna identificadora da unidade principal do diagnostico.';


--
-- TOC entry 4427 (class 0 OID 0)
-- Dependencies: 356
-- Name: COLUMN tb_questionariodiagnosticomelhoria.matriculaproponente; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_questionariodiagnosticomelhoria.matriculaproponente IS 'Matricula do cadastrador da melhoria para o diagnostico.';


--
-- TOC entry 357 (class 1259 OID 10423318)
-- Name: tb_questionariofrase; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_questionariofrase (
    idfrase integer NOT NULL,
    idquestionario integer NOT NULL,
    numordempergunta integer NOT NULL,
    idcadastrador integer,
    datcadastro date,
    obrigatoriedade character(1) NOT NULL,
    CONSTRAINT cc_obrigatoriedade CHECK (((obrigatoriedade IS NULL) OR (obrigatoriedade = ANY (ARRAY['S'::bpchar, 'N'::bpchar]))))
);


ALTER TABLE agepnet200.tb_questionariofrase OWNER TO postgres;

--
-- TOC entry 358 (class 1259 OID 10423322)
-- Name: tb_questionariofrase_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_questionariofrase_pesquisa (
    idquestionariopesquisa integer NOT NULL,
    idfrasepesquisa integer NOT NULL,
    numordempergunta integer NOT NULL,
    datcadastro date,
    idcadastrador integer NOT NULL,
    obrigatoriedade character(1) DEFAULT 'N'::bpchar NOT NULL,
    CONSTRAINT cc_obrigatoriedade CHECK ((obrigatoriedade = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE agepnet200.tb_questionariofrase_pesquisa OWNER TO postgres;

--
-- TOC entry 359 (class 1259 OID 10423327)
-- Name: tb_r3g; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_r3g (
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


ALTER TABLE agepnet200.tb_r3g OWNER TO postgres;

--
-- TOC entry 360 (class 1259 OID 10423337)
-- Name: tb_recurso; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_recurso (
    idrecurso integer NOT NULL,
    ds_recurso character varying(50) NOT NULL,
    descricao character varying(300)
);


ALTER TABLE agepnet200.tb_recurso OWNER TO postgres;

--
-- TOC entry 4432 (class 0 OID 0)
-- Dependencies: 360
-- Name: COLUMN tb_recurso.descricao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_recurso.descricao IS 'Coluna que descreve o controle do modulo';


--
-- TOC entry 361 (class 1259 OID 10423340)
-- Name: tb_resposta; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_resposta (
    idresposta integer NOT NULL,
    numordem numeric(2,0),
    flaativo character varying(1),
    datcadastro date NOT NULL,
    idcadastrador integer NOT NULL,
    desresposta character varying(255),
    CONSTRAINT ckc_flaativo_tb_respo CHECK (((flaativo IS NULL) OR ((flaativo)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text]))))
);


ALTER TABLE agepnet200.tb_resposta OWNER TO postgres;

--
-- TOC entry 362 (class 1259 OID 10423344)
-- Name: tb_resposta_pergunta; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_resposta_pergunta (
    id_resposta_pergunta bigint NOT NULL,
    ds_resposta_descritiva text,
    idpergunta bigint NOT NULL,
    idresposta bigint,
    nrquestionario bigint NOT NULL,
    idquestionario bigint NOT NULL,
    iddiagnostico bigint NOT NULL
);


ALTER TABLE agepnet200.tb_resposta_pergunta OWNER TO postgres;

--
-- TOC entry 4435 (class 0 OID 0)
-- Dependencies: 362
-- Name: TABLE tb_resposta_pergunta; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_resposta_pergunta IS 'Tabela que armazena as respostas das perguntas dos questionarios.';


--
-- TOC entry 4436 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN tb_resposta_pergunta.id_resposta_pergunta; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.id_resposta_pergunta IS 'Identificador da tabela';


--
-- TOC entry 4437 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN tb_resposta_pergunta.ds_resposta_descritiva; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.ds_resposta_descritiva IS 'Resposta de perguntas descritivas';


--
-- TOC entry 4438 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN tb_resposta_pergunta.idpergunta; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.idpergunta IS 'Identifica a pergunta que pertence a resposta.';


--
-- TOC entry 4439 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN tb_resposta_pergunta.idresposta; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.idresposta IS 'identificador da opcao de resposta pre definida.';


--
-- TOC entry 4440 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN tb_resposta_pergunta.nrquestionario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.nrquestionario IS 'Coluna que define o numero do questionario que esta sendo respondido.';


--
-- TOC entry 4441 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN tb_resposta_pergunta.idquestionario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.idquestionario IS 'Coluna que identifica o questionario que esta sendo respondido.';


--
-- TOC entry 4442 (class 0 OID 0)
-- Dependencies: 362
-- Name: COLUMN tb_resposta_pergunta.iddiagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_resposta_pergunta.iddiagnostico IS 'Coluna que identifica o diagnostico.';


--
-- TOC entry 363 (class 1259 OID 10423350)
-- Name: tb_resposta_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_resposta_pesquisa (
    idrespostapesquisa integer NOT NULL,
    desresposta character varying(255),
    numordem numeric(2,0),
    flaativo character varying(1),
    datcadastro date NOT NULL,
    idcadastrador integer NOT NULL,
    CONSTRAINT cc_flaativo CHECK (((flaativo IS NULL) OR ((flaativo)::text = ANY (ARRAY[('S'::character varying)::text, ('N'::character varying)::text]))))
);


ALTER TABLE agepnet200.tb_resposta_pesquisa OWNER TO postgres;

--
-- TOC entry 364 (class 1259 OID 10423354)
-- Name: tb_resposta_questionariordiagnostico; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_resposta_questionariordiagnostico (
    id_resposta_pergunta bigint NOT NULL,
    idquestionario bigint NOT NULL,
    iddiagnostico bigint NOT NULL,
    numero bigint NOT NULL
);


ALTER TABLE agepnet200.tb_resposta_questionariordiagnostico OWNER TO postgres;

--
-- TOC entry 4445 (class 0 OID 0)
-- Dependencies: 364
-- Name: TABLE tb_resposta_questionariordiagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_resposta_questionariordiagnostico IS 'Tabela que registro as respostas dos questionarios.';


--
-- TOC entry 4446 (class 0 OID 0)
-- Dependencies: 364
-- Name: COLUMN tb_resposta_questionariordiagnostico.id_resposta_pergunta; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_resposta_questionariordiagnostico.id_resposta_pergunta IS 'Coluna que identifica a resposta cadastrada para pergunta do questionario.';


--
-- TOC entry 4447 (class 0 OID 0)
-- Dependencies: 364
-- Name: COLUMN tb_resposta_questionariordiagnostico.idquestionario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_resposta_questionariordiagnostico.idquestionario IS 'Coluna que identifica o questionario que a resposta faz parte.';


--
-- TOC entry 4448 (class 0 OID 0)
-- Dependencies: 364
-- Name: COLUMN tb_resposta_questionariordiagnostico.iddiagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_resposta_questionariordiagnostico.iddiagnostico IS 'Coluna que identifica o diagnostico que o questionario faz parte.';


--
-- TOC entry 4449 (class 0 OID 0)
-- Dependencies: 364
-- Name: COLUMN tb_resposta_questionariordiagnostico.numero; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_resposta_questionariordiagnostico.numero IS 'Coluna que identifica o numero do questionario respondido.';


--
-- TOC entry 365 (class 1259 OID 10423357)
-- Name: tb_respostafrase; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_respostafrase (
    idfrase integer NOT NULL,
    idresposta integer NOT NULL
);


ALTER TABLE agepnet200.tb_respostafrase OWNER TO postgres;

--
-- TOC entry 366 (class 1259 OID 10423360)
-- Name: tb_respostafrase_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_respostafrase_pesquisa (
    idfrasepesquisa integer NOT NULL,
    idrespostapesquisa integer NOT NULL
);


ALTER TABLE agepnet200.tb_respostafrase_pesquisa OWNER TO postgres;

--
-- TOC entry 367 (class 1259 OID 10423363)
-- Name: tb_resultado_pesquisa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_resultado_pesquisa (
    id integer NOT NULL,
    idresultado integer NOT NULL,
    idfrasepesquisa integer NOT NULL,
    idquestionariopesquisa integer NOT NULL,
    desresposta text,
    datcadastro timestamp without time zone NOT NULL,
    cpf character varying(11)
);


ALTER TABLE agepnet200.tb_resultado_pesquisa OWNER TO postgres;

--
-- TOC entry 368 (class 1259 OID 10423369)
-- Name: tb_risco; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_risco (
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
    domtratamento numeric(2,0),
    norisco character varying(50),
    flaaprovado numeric(1,0),
    datinatividade date,
    CONSTRAINT cc_domcorimpacto CHECK (((domcorimpacto IS NULL) OR (domcorimpacto = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric])))),
    CONSTRAINT cc_domcorprobabilida CHECK (((domcorprobabilidade IS NULL) OR (domcorprobabilidade = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric])))),
    CONSTRAINT cc_domcorrisco CHECK (((domcorrisco IS NULL) OR (domcorrisco = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric])))),
    CONSTRAINT cc_domtratamento CHECK (((domtratamento IS NULL) OR (domtratamento = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric, (4)::numeric, (5)::numeric, (9)::numeric, (10)::numeric, (11)::numeric, (12)::numeric, (13)::numeric, (14)::numeric, (15)::numeric, (16)::numeric, (17)::numeric, (18)::numeric])))),
    CONSTRAINT cc_flaaprovado CHECK (((flaaprovado IS NULL) OR (flaaprovado = ANY (ARRAY[(1)::numeric, (2)::numeric])))),
    CONSTRAINT cc_flariscoativo CHECK (((flariscoativo IS NULL) OR (flariscoativo = ANY (ARRAY[(1)::numeric, (2)::numeric]))))
);


ALTER TABLE agepnet200.tb_risco OWNER TO postgres;

--
-- TOC entry 369 (class 1259 OID 10423381)
-- Name: tb_secao; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_secao (
    id_secao bigint NOT NULL,
    ds_secao character varying(200),
    id_secao_pai bigint,
    ativa boolean DEFAULT true NOT NULL,
    tp_questionario character(1) NOT NULL,
    macroprocesso boolean DEFAULT false NOT NULL
);


ALTER TABLE agepnet200.tb_secao OWNER TO postgres;

--
-- TOC entry 4455 (class 0 OID 0)
-- Dependencies: 369
-- Name: TABLE tb_secao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_secao IS 'Tabela que define as secoes que o questionario devera conter.';


--
-- TOC entry 4456 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN tb_secao.id_secao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_secao.id_secao IS 'Identificador das secoes do questionario.';


--
-- TOC entry 4457 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN tb_secao.ds_secao; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_secao.ds_secao IS 'Descricao das secoes ';


--
-- TOC entry 4458 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN tb_secao.id_secao_pai; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_secao.id_secao_pai IS 'Identificador da secao pai da secao criada.';


--
-- TOC entry 4459 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN tb_secao.ativa; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_secao.ativa IS 'Define se a secao esta ativa ou nao para apresentacao no questionario.
true - ativa
false - inativa.';


--
-- TOC entry 4460 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN tb_secao.tp_questionario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_secao.tp_questionario IS 'Define o tipo de questionario ao qual pertence a secao criada.
S - Questionario pesquisa de satisfacao de servidores
C - Questionario pesquisa de satisfacao de cidadaos.';


--
-- TOC entry 4461 (class 0 OID 0)
-- Dependencies: 369
-- Name: COLUMN tb_secao.macroprocesso; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_secao.macroprocesso IS 'Define se a secao e um macroprocesso com os seguintes valores:
1 - Sim
2 - Nao';


--
-- TOC entry 370 (class 1259 OID 10423386)
-- Name: tb_setor; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_setor (
    idsetor integer NOT NULL,
    nomsetor character varying(100) NOT NULL,
    idcadastrador integer,
    datcadastro date,
    flaativo character(1) DEFAULT 'S'::bpchar
);


ALTER TABLE agepnet200.tb_setor OWNER TO postgres;

--
-- TOC entry 371 (class 1259 OID 10423390)
-- Name: tb_statusreport; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_statusreport (
    idstatusreport integer NOT NULL,
    idprojeto integer NOT NULL,
    datacompanhamento date,
    desatividadeconcluida text,
    desatividadeandamento text,
    desmotivoatraso text,
    desirregularidade text,
    idmarco integer,
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
    numdiasprojeto integer DEFAULT 0,
    numpercentualmarcos numeric(5,2) DEFAULT 0,
    numpercentualdiferenca numeric(5,2) DEFAULT 0,
    numpercentualcustoreal numeric(5,2) DEFAULT 0,
    numcustorealtotal bigint DEFAULT (0)::bigint,
    idresponsavelaceitacao integer DEFAULT 0,
    pgpassinado character varying(1) DEFAULT 'N'::character varying,
    tepassinado character varying(1) DEFAULT 'N'::character varying,
    desandamentoprojeto text,
    numpercentualconcluidomarco numeric(5,2),
    diaatraso integer,
    domcoratraso character varying(10),
    numcriteriofarol integer,
    datfimprojeto date,
    CONSTRAINT ckc_aprovacao CHECK ((((flaaprovado = (1)::numeric) AND (dataprovacao IS NOT NULL)) OR ((flaaprovado = (2)::numeric) AND (dataprovacao IS NULL)))),
    CONSTRAINT ckc_domcorrisco CHECK (((domcorrisco IS NULL) OR (domcorrisco = ANY (ARRAY[(1)::numeric, (2)::numeric, (3)::numeric])))),
    CONSTRAINT ckc_flaaprovado CHECK (((flaaprovado IS NULL) OR (flaaprovado = ANY (ARRAY[(1)::numeric, (2)::numeric])))),
    CONSTRAINT ckc_statusreportprojeto CHECK (((domstatusprojeto IS NULL) OR (domstatusprojeto = ANY (ARRAY[1, 2, 3, 4, 5, 6, 7, 8]))))
);


ALTER TABLE agepnet200.tb_statusreport OWNER TO postgres;

--
-- TOC entry 4464 (class 0 OID 0)
-- Dependencies: 371
-- Name: COLUMN tb_statusreport.desandamentoprojeto; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_statusreport.desandamentoprojeto IS 'Consideracoes gerais sobre o andamento do projeto.';


--
-- TOC entry 4465 (class 0 OID 0)
-- Dependencies: 371
-- Name: COLUMN tb_statusreport.numpercentualconcluidomarco; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_statusreport.numpercentualconcluidomarco IS 'Apresenta o numero de percentual concluido do marco neste acompanhamento.';


--
-- TOC entry 4466 (class 0 OID 0)
-- Dependencies: 371
-- Name: COLUMN tb_statusreport.diaatraso; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_statusreport.diaatraso IS 'Apresenta a quantidade de dias do projeto em atraso para o acompanhamento gerado.';


--
-- TOC entry 4467 (class 0 OID 0)
-- Dependencies: 371
-- Name: COLUMN tb_statusreport.domcoratraso; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_statusreport.domcoratraso IS 'Apresenta a cor do farol referente aos dias de atraso de acordo com cada acompanhamento gerado.';


--
-- TOC entry 4468 (class 0 OID 0)
-- Dependencies: 371
-- Name: COLUMN tb_statusreport.datfimprojeto; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_statusreport.datfimprojeto IS 'Apresenta a data fim do projeto para o acompanhamento gerado.';


--
-- TOC entry 372 (class 1259 OID 10423410)
-- Name: tb_tipoacordo; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_tipoacordo (
    idtipoacordo integer NOT NULL,
    dsacordo character varying,
    idcadastrador integer,
    dtcadastro date
);


ALTER TABLE agepnet200.tb_tipoacordo OWNER TO postgres;

--
-- TOC entry 373 (class 1259 OID 10423416)
-- Name: tb_tipoavaliacao; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_tipoavaliacao (
    idtipoavaliacao integer NOT NULL,
    noavaliacao character varying(100)
);


ALTER TABLE agepnet200.tb_tipoavaliacao OWNER TO postgres;

--
-- TOC entry 374 (class 1259 OID 10423419)
-- Name: tb_tipocontramedida; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_tipocontramedida (
    idtipocontramedida integer NOT NULL,
    notipocontramedida character varying(50) NOT NULL,
    dstipocontramedida character varying(200),
    idstatustipocontramedida integer
);


ALTER TABLE agepnet200.tb_tipocontramedida OWNER TO postgres;

--
-- TOC entry 375 (class 1259 OID 10423422)
-- Name: tb_tipodocumento; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_tipodocumento (
    idtipodocumento integer NOT NULL,
    nomtipodocumento character varying(30),
    idcadastrador integer NOT NULL,
    datcadastro date NOT NULL,
    flaativo character varying(1)
);


ALTER TABLE agepnet200.tb_tipodocumento OWNER TO postgres;

--
-- TOC entry 376 (class 1259 OID 10423425)
-- Name: tb_tipoiniciativa; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_tipoiniciativa (
    idtipoiniciativa integer NOT NULL,
    nomtipoiniciativa character varying(100),
    destipoiniciativa text,
    flaativo character(1) DEFAULT 'S'::bpchar NOT NULL,
    CONSTRAINT ckc_flaativo_tb_perfi CHECK ((flaativo = ANY (ARRAY['S'::bpchar, 'N'::bpchar])))
);


ALTER TABLE agepnet200.tb_tipoiniciativa OWNER TO postgres;

--
-- TOC entry 377 (class 1259 OID 10423433)
-- Name: tb_tipomudanca; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_tipomudanca (
    idtipomudanca integer NOT NULL,
    dsmudanca character varying(50) NOT NULL,
    idcadastrador integer NOT NULL,
    dtcadastro date NOT NULL
);


ALTER TABLE agepnet200.tb_tipomudanca OWNER TO postgres;

--
-- TOC entry 378 (class 1259 OID 10423436)
-- Name: tb_tiporisco; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_tiporisco (
    idtiporisco integer NOT NULL,
    dstiporisco character varying(40) NOT NULL,
    idcadastrador integer NOT NULL,
    dtcadastro date NOT NULL
);


ALTER TABLE agepnet200.tb_tiporisco OWNER TO postgres;

--
-- TOC entry 379 (class 1259 OID 10423439)
-- Name: tb_tiposituacaoprojeto; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_tiposituacaoprojeto (
    idtipo integer NOT NULL,
    nomtipo character(80) NOT NULL,
    desctipo text,
    flatiposituacao integer NOT NULL
);


ALTER TABLE agepnet200.tb_tiposituacaoprojeto OWNER TO postgres;

--
-- TOC entry 380 (class 1259 OID 10423445)
-- Name: tb_tratamento; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_tratamento (
    idtratamento integer NOT NULL,
    dstratamento character varying(40) NOT NULL,
    idcadastrador integer NOT NULL,
    dtcadastro date NOT NULL,
    idtiporisco integer
);


ALTER TABLE agepnet200.tb_tratamento OWNER TO postgres;

--
-- TOC entry 273 (class 1259 OID 10422799)
-- Name: tb_unidade; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_unidade (
    idunidade integer NOT NULL,
    idunidadeprincipal integer,
    sigla character varying(50) NOT NULL,
    nome character varying(100) NOT NULL,
    ativo smallint DEFAULT 1 NOT NULL
);


ALTER TABLE agepnet200.tb_unidade OWNER TO postgres;

--
-- TOC entry 274 (class 1259 OID 10422803)
-- Name: tb_unidade_idunidade_seq; Type: SEQUENCE; Schema: agepnet200; Owner: postgres
--

CREATE SEQUENCE agepnet200.tb_unidade_idunidade_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE agepnet200.tb_unidade_idunidade_seq OWNER TO postgres;

--
-- TOC entry 4480 (class 0 OID 0)
-- Dependencies: 274
-- Name: tb_unidade_idunidade_seq; Type: SEQUENCE OWNED BY; Schema: agepnet200; Owner: postgres
--

ALTER SEQUENCE agepnet200.tb_unidade_idunidade_seq OWNED BY agepnet200.tb_unidade.idunidade;


--
-- TOC entry 381 (class 1259 OID 10423448)
-- Name: tb_unidade_vinculada; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_unidade_vinculada (
    idunidade integer NOT NULL,
    id_unidadeprincipal integer NOT NULL,
    iddiagnostico bigint NOT NULL
);


ALTER TABLE agepnet200.tb_unidade_vinculada OWNER TO postgres;

--
-- TOC entry 4482 (class 0 OID 0)
-- Dependencies: 381
-- Name: TABLE tb_unidade_vinculada; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_unidade_vinculada IS 'Tabela de unidade que estao vinculadas a unidade principal dos diagnosticos criados.';


--
-- TOC entry 4483 (class 0 OID 0)
-- Dependencies: 381
-- Name: COLUMN tb_unidade_vinculada.idunidade; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_unidade_vinculada.idunidade IS 'Identificador da unidade vinculada a unidade principal';


--
-- TOC entry 4484 (class 0 OID 0)
-- Dependencies: 381
-- Name: COLUMN tb_unidade_vinculada.id_unidadeprincipal; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_unidade_vinculada.id_unidadeprincipal IS 'Identificador da unidade principal do diagnostico.';


--
-- TOC entry 4485 (class 0 OID 0)
-- Dependencies: 381
-- Name: COLUMN tb_unidade_vinculada.iddiagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_unidade_vinculada.iddiagnostico IS 'Identificador do diagnostico.';


--
-- TOC entry 382 (class 1259 OID 10423451)
-- Name: tb_vincula_questionario; Type: TABLE; Schema: agepnet200; Owner: postgres
--

CREATE TABLE agepnet200.tb_vincula_questionario (
    idquestionario bigint NOT NULL,
    iddiagnostico bigint NOT NULL,
    disponivel character(1) DEFAULT 2 NOT NULL,
    dtdisponibilidade date NOT NULL,
    dtencerrramento date,
    idpesdisponibiliza integer NOT NULL,
    idpesencerrou integer
);


ALTER TABLE agepnet200.tb_vincula_questionario OWNER TO postgres;

--
-- TOC entry 4487 (class 0 OID 0)
-- Dependencies: 382
-- Name: TABLE tb_vincula_questionario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON TABLE agepnet200.tb_vincula_questionario IS 'Tabela de questionarios vinculados a diagnosticos.';


--
-- TOC entry 4488 (class 0 OID 0)
-- Dependencies: 382
-- Name: COLUMN tb_vincula_questionario.idquestionario; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_vincula_questionario.idquestionario IS 'identificador do questionario.';


--
-- TOC entry 4489 (class 0 OID 0)
-- Dependencies: 382
-- Name: COLUMN tb_vincula_questionario.iddiagnostico; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_vincula_questionario.iddiagnostico IS 'Identificador do diagnostico';


--
-- TOC entry 4490 (class 0 OID 0)
-- Dependencies: 382
-- Name: COLUMN tb_vincula_questionario.disponivel; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_vincula_questionario.disponivel IS 'Identifica se o questionario esta liberado para ser respondido ou nao.
1 - Disponivel
2 - Indisponivel';


--
-- TOC entry 4491 (class 0 OID 0)
-- Dependencies: 382
-- Name: COLUMN tb_vincula_questionario.dtdisponibilidade; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_vincula_questionario.dtdisponibilidade IS 'Data que foi disponibilizado o questionario para respostas.';


--
-- TOC entry 4492 (class 0 OID 0)
-- Dependencies: 382
-- Name: COLUMN tb_vincula_questionario.dtencerrramento; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_vincula_questionario.dtencerrramento IS 'Data de encerramento da disponibilidade do questionario.';


--
-- TOC entry 4493 (class 0 OID 0)
-- Dependencies: 382
-- Name: COLUMN tb_vincula_questionario.idpesdisponibiliza; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_vincula_questionario.idpesdisponibiliza IS 'Pessoa que disponibilizou o questionario.';


--
-- TOC entry 4494 (class 0 OID 0)
-- Dependencies: 382
-- Name: COLUMN tb_vincula_questionario.idpesencerrou; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON COLUMN agepnet200.tb_vincula_questionario.idpesencerrou IS 'Pessoa que encerrou o questionario.';


--
-- TOC entry 383 (class 1259 OID 10425425)
-- Name: vw_comum_pessoa; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.vw_comum_pessoa AS
 SELECT t1.id_pessoa,
    t1.nome,
    t1.cpf_cnpj,
    t1.telefone,
    t1.celular,
    t1.email,
    t1.tipo
   FROM public.dblink('hostaddr=10.10.10.10 port=1010 dbname=1010 user=1010 password=1010'::text, 'select id_pessoa, nome, cpf_cnpj, telefone, celular, email, tipo from comum.pessoa'::text) t1(id_pessoa integer, nome character varying, cpf_cnpj bigint, telefone character varying, celular character varying, email character varying, tipo character(1));


ALTER TABLE public.vw_comum_pessoa OWNER TO postgres;

--
-- TOC entry 275 (class 1259 OID 10422812)
-- Name: vw_comum_unidade; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.vw_comum_unidade AS
 WITH RECURSIVE comum_unidade(id_unidade, sigla, nome, unidade_responsavel, tipo, ativo, telefones, hierarquia_organizacional, id_tipo_organizacional, pai, nivel, ordenacao) AS (
         SELECT vwu.idunidade AS id_unidade,
            vwu.sigla,
            vwu.nome,
            vwu.idunidadeprincipal AS unidade_responsavel,
            NULL::text AS tipo,
                CASE
                    WHEN (vwu.ativo = 1) THEN true
                    ELSE false
                END AS ativo,
            NULL::text AS telefones,
            NULL::text AS hierarquia_organizacional,
            NULL::text AS id_tipo_organizacional,
            vwu.idunidade AS pai,
            1 AS nivel,
            ARRAY[vwu.idunidade] AS ordenacao
           FROM agepnet200.tb_unidade vwu
          WHERE ((vwu.idunidadeprincipal = vwu.idunidade) OR (vwu.idunidadeprincipal IS NULL))
        UNION ALL
         SELECT vwu.idunidade AS id_unidade,
            vwu.sigla,
            vwu.nome,
            vwu.idunidadeprincipal AS unidade_responsavel,
            NULL::text AS tipo,
                CASE
                    WHEN (vwu.ativo = 1) THEN true
                    ELSE false
                END AS ativo,
            NULL::text AS telefones,
            NULL::text AS hierarquia_organizacional,
            NULL::text AS id_tipo_organizacional,
            c.pai,
            (c.nivel + 1) AS nivel,
            (c.ordenacao || vwu.idunidade) AS ordenacao
           FROM (agepnet200.tb_unidade vwu
             JOIN comum_unidade c ON ((c.id_unidade = vwu.idunidadeprincipal)))
          WHERE (vwu.idunidadeprincipal <> vwu.idunidade)
        )
 SELECT comum_unidade.id_unidade,
    comum_unidade.sigla,
    comum_unidade.nome,
    comum_unidade.unidade_responsavel,
    comum_unidade.tipo,
    comum_unidade.ativo,
    comum_unidade.telefones,
    comum_unidade.hierarquia_organizacional,
    comum_unidade.id_tipo_organizacional,
    comum_unidade.pai,
    comum_unidade.nivel,
    comum_unidade.ordenacao
   FROM comum_unidade
  ORDER BY comum_unidade.ordenacao;


ALTER TABLE public.vw_comum_unidade OWNER TO postgres;

--
-- TOC entry 276 (class 1259 OID 10422817)
-- Name: vw_rh_cargo; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.vw_rh_cargo AS
 SELECT t1.id,
    t1.denominacao,
    t1.sigla,
    t1.inativo
   FROM public.dblink('hostaddr=10.10.10.10 port=1010 dbname=1010 user=1010 password=1010'::text, 'select id, denominacao, sigla, inativo from rh.cargo'::text) t1(id integer, denominacao character varying, sigla character varying(10), inativo boolean);


ALTER TABLE public.vw_rh_cargo OWNER TO postgres;

--
-- TOC entry 384 (class 1259 OID 10425429)
-- Name: vw_rh_servidor; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW public.vw_rh_servidor AS
 SELECT t1.id_servidor,
    t1.id_pessoa,
    t1.matricula_interna,
    t1.id_cargo AS cd_cargo,
    t1.id_unidade AS cd_lotacao,
    t1.id_ativo AS cd_status,
    t1.siape AS numsiape
   FROM public.dblink('hostaddr=10.10.10.10 port=1010 dbname=1010 user=1010 password=1010'::text, 'select id_servidor, id_pessoa, matricula_interna, id_cargo, id_unidade, id_ativo, siape from rh.servidor WHERE (id_ativo = ANY (ARRAY[1, 5, 7])) AND data_desligamento IS NULL'::text) t1(id_servidor integer, id_pessoa integer, matricula_interna integer, id_cargo integer, id_unidade integer, id_ativo integer, siape bigint);


ALTER TABLE public.vw_rh_servidor OWNER TO postgres;

--
-- TOC entry 3416 (class 2604 OID 10422821)
-- Name: tb_cargo idcargo; Type: DEFAULT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_cargo ALTER COLUMN idcargo SET DEFAULT nextval('agepnet200.tb_cargo_idcargo_seq'::regclass);


--
-- TOC entry 3444 (class 2604 OID 10423455)
-- Name: tb_diagnostico sq_diagnostico; Type: DEFAULT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_diagnostico ALTER COLUMN sq_diagnostico SET DEFAULT nextval('agepnet200.tb_diagnostico_sq_diagnostico_seq'::regclass);


--
-- TOC entry 3446 (class 2604 OID 10423456)
-- Name: tb_diautil iddiautil; Type: DEFAULT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_diautil ALTER COLUMN iddiautil SET DEFAULT nextval('agepnet200.tb_diautil_iddiautil_seq'::regclass);


--
-- TOC entry 3473 (class 2604 OID 10423457)
-- Name: tb_parteinteressadafuncao idparteinteressadafuncao; Type: DEFAULT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_parteinteressadafuncao ALTER COLUMN idparteinteressadafuncao SET DEFAULT nextval('agepnet200.tb_parteinteressadafuncao_idparteinteressadafuncao_seq'::regclass);


--
-- TOC entry 3418 (class 2604 OID 10422825)
-- Name: tb_unidade idunidade; Type: DEFAULT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_unidade ALTER COLUMN idunidade SET DEFAULT nextval('agepnet200.tb_unidade_idunidade_seq'::regclass);


--
-- TOC entry 4059 (class 0 OID 10422906)
-- Dependencies: 280
-- Data for Name: tb_acao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_acao (idacao, idobjetivo, nomacao, idcadastrador, datcadastro, flaativo, desacao, idescritorio, numseq) FROM stdin;
1	1	Acao de Planejamento Estrategico	1	2015-03-05	S	Implantar a cultura de planejamento estrategico	0	1
2	2	Acao de Gestao de Projetos	1	2015-03-05	S	Implantar a cultura de gestao de projetos	0	2
\.


--
-- TOC entry 4060 (class 0 OID 10422915)
-- Dependencies: 281
-- Data for Name: tb_aceite; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_aceite (idaceite, desprodutoservico, desparecerfinal, idcadastrador, datcadastro) FROM stdin;
1	produto ou servico planejado para ser executado na entrega 1	O resultado da entrega foi recebido dentro das especificacoes de qualidade estabelecidas. Resultado ok.	1	2022-10-13
\.


--
-- TOC entry 4061 (class 0 OID 10422921)
-- Dependencies: 282
-- Data for Name: tb_aceiteatividadecronograma; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_aceiteatividadecronograma (idaceiteativcronograma, identrega, idprojeto, idaceite, idmarco, aceito, idpesaceitou, dataceitacao) FROM stdin;
1	2	1	1	\N	S	\N	\N
\.


--
-- TOC entry 4062 (class 0 OID 10422925)
-- Dependencies: 283
-- Data for Name: tb_acordo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_acordo (idacordo, idacordopai, idtipoacordo, nomacordo, idresponsavelinterno, destelefoneresponsavelinterno, idsetor, idfiscal, destelefonefiscal, despalavrachave, desobjeto, desobservacao, datassinatura, datiniciovigencia, datfimvigencia, numprazovigencia, datatualizacao, datcadastro, idcadastrador, flarescindido, flasituacaoatual, numsiapro, descontatoexterno, idfiscal2, idfiscal3, idacordoespecieinstrumento, datpublicacao, descargofiscal, descaminho) FROM stdin;
\.


--
-- TOC entry 4063 (class 0 OID 10422934)
-- Dependencies: 284
-- Data for Name: tb_acordoentidadeexterna; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_acordoentidadeexterna (idacordo, identidadeexterna) FROM stdin;
\.


--
-- TOC entry 4064 (class 0 OID 10422937)
-- Dependencies: 285
-- Data for Name: tb_acordoespecieinstrumento; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_acordoespecieinstrumento (idacordoespecieinstrumento, nomacordoespecieinstrumento, idcadastrador, datcadastro, flaativo) FROM stdin;
3	Termo de Cooperacao	1	2015-08-06	S
4	Contrato de Repasse	1	2015-06-08	S
2	Convenio	1	2015-08-06	S
1	Acordo de Cooperacao	1	2015-07-10	S
5	Termo de Parceria	1	2015-06-08	S
6	Termo de Ajustamento de Conduta	1	2015-06-08	S
\.


--
-- TOC entry 4065 (class 0 OID 10422941)
-- Dependencies: 286
-- Data for Name: tb_agenda; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_agenda (idagenda, desassunto, datagenda, desagenda, idcadastrador, datcadastro, hragendada, deslocal, flaenviaemail, idescritorio) FROM stdin;
2	reuniao de alinhamento	2015-06-01	khg fd  oo ohf  fdfdf fohfoj	1	2015-06-01	2015-06-01 14:00:00	universidade corporativa	2	2
\.


--
-- TOC entry 4066 (class 0 OID 10422948)
-- Dependencies: 287
-- Data for Name: tb_aquisicao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_aquisicao (idaquisicao, idprojeto, identrega, descontrato, desfornecedor, numvalor, datprazoaquisicao, idcadastrador, datcadastro, numquantidade, desaquisicao) FROM stdin;
\.


--
-- TOC entry 4067 (class 0 OID 10422951)
-- Dependencies: 288
-- Data for Name: tb_assinadocumento; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_assinadocumento (id, idprojeto, idpessoa, assinado, tipodoc, hashdoc, situacao, nomfuncao, idaceite) FROM stdin;
1	1	1	2022-11-16 16:55:22.575542-03	1	abd45fa6b21d302980cab122e6ad816fb41c05bb                                                            	A	Gerente do Projeto	\N
\.


--
-- TOC entry 4068 (class 0 OID 10422954)
-- Dependencies: 289
-- Data for Name: tb_ata; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_ata (idata, idprojeto, desassunto, datata, deslocal, desparticipante, despontodiscutido, desdecisao, despontoatencao, idcadastrador, datcadastro, desproximopasso, hrreuniao) FROM stdin;
1	1	Reuniao teste	2015-09-10	edificio central	Usuario01, usuario01@gepnet2.gov;\nusuario02, usuario02@gepnet2.gov;\nUsuario03, usuario03@gepnet2.gov;\nEquipe do projeto;\nColaboradores do projeto	situacao do projeto	decisao a\ndecisao b\ndecisao c	ponto de atencao a\nponto de atencao b\nponto de atencao c	1	2015-09-10	proximos passos	10:00:00
\.


--
-- TOC entry 4069 (class 0 OID 10422960)
-- Dependencies: 290
-- Data for Name: tb_atividade; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_atividade (idatividade, nomatividade, desatividade, idcadastrador, idresponsavel, datcadastro, datatualizacao, datinicio, datfimmeta, datfimreal, flacontinua, numpercentualconcluido, flacancelada, idescritorio) FROM stdin;
1	Atividade 1	j lk\r\nf jdfg pjo okjo ojb	1	1	2015-03-07	2016-01-13	2015-01-01	2015-01-15	2015-01-22	2	100	2	0
\.


--
-- TOC entry 4070 (class 0 OID 10422968)
-- Dependencies: 291
-- Data for Name: tb_atividadecronograma; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_atividadecronograma (idatividadecronograma, idprojeto, idgrupo, nomatividadecronograma, domtipoatividade, desobs, datcadastro, idmarcoanterior, numdias, vlratividadebaseline, vlratividade, numfolga, descriterioaceitacao, idelementodespesa, idcadastrador, idparteinteressada, datiniciobaseline, datfimbaseline, flaaquisicao, flainformatica, flacancelada, datinicio, datfim, numpercentualconcluido, numdiasbaseline, numdiasrealizados, numseq, flaordenacao, idresponsavel, datatividadeconcluida) FROM stdin;
2	2	\N	PLANEJAMENTO	1	\N	2022-10-18 11:20:34.057348-03	\N	\N	0	0	0	\N	\N	\N	\N	2022-10-20	2022-10-20	\N	\N	\N	2022-10-20	2022-10-27	0.00	\N	0	5	S	\N	\N
11	2	2	Plano do projeto	2	Documento que detalha o projeto.	2022-10-18 11:31:41.61136-03	\N	\N	0	0	0	Conformidade com a metodologia de projetos da PF e com a finalidade do projeto.	\N	\N	4	2022-10-20	2022-10-20	\N	\N	N	2022-10-20	2022-10-27	0.00	\N	0	6	S	5	\N
13	2	11	Plano do projeto aprovado	4	\N	2022-10-18 11:33:07.695825-03	\N	\N	0	0	0	\N	\N	\N	4	2022-10-20	2022-10-20	N	\N	N	2022-10-27	2022-10-27	0.00	\N	0	8	S	\N	\N
12	1	11	atividade 5	3	teste	2022-10-11 18:08:30.528161-03	\N	120	20000	20000	0	\N	\N	\N	2	2021-01-04	2021-02-12	N	N	N	2021-10-29	2022-04-19	100.00	30	120	10	S	\N	2022-10-11
14	1	10	entrega 4	2	teste	2022-10-11 18:08:30.528161-03	\N	190	0	0	0	teste	\N	\N	2	2021-01-04	2022-03-31	\N	\N	N	2022-04-22	2022-11-04	100.00	315	190	12	S	3	\N
15	2	14	Mapear e modelar o processo de definicao de requisitos	3		2022-10-18 11:39:50.548675-03	\N	1	0	0	0	\N	\N	\N	4	2022-10-24	2022-10-24	N	N	N	2022-10-27	2022-10-27	0.00	1	1	15	S	\N	\N
1	1	\N	GRUPO DE ENTREGAS 1	1	\N	2022-10-11 16:59:42.541193-03	\N	\N	0	0	0	\N	\N	\N	\N	2021-01-04	2022-03-31	\N	\N	\N	2021-01-04	2022-03-31	100.00	\N	0	1	S	\N	\N
2	1	1	entrega 1	2	teste	2022-10-11 17:58:02.557122-03	\N	\N	0	0	0	teste	\N	\N	2	2021-01-04	2022-03-31	\N	\N	N	2021-01-04	2022-03-31	100.00	\N	0	2	S	3	\N
5	1	2	atividade 1	3	teste	2022-10-11 18:01:58.546821-03	\N	30	20000	20000	0	\N	30	\N	2	2021-01-04	2021-02-12	S	S	N	2021-01-04	2021-02-12	100.00	30	30	3	S	\N	2022-10-11
6	1	2	atividade 2 - marco de entrega	4	teste	2022-10-11 18:03:58.431346-03	\N	\N	20000	20000	0	\N	30	\N	2	2022-03-31	2022-03-31	N	S	N	2022-03-31	2022-03-31	100.00	\N	0	4	S	\N	2022-10-11
7	1	1	entrega 2	2	teste	2022-10-11 18:05:08.43973-03	\N	315	0	0	0	teste	\N	\N	2	2021-01-04	2022-03-31	\N	\N	N	2021-07-01	2022-03-31	100.00	315	315	5	S	3	\N
6	2	\N	IMPLANTACAO	1	\N	2022-10-18 11:21:32.54149-03	\N	\N	0	0	0	\N	\N	\N	\N	2022-11-07	2022-11-17	\N	\N	\N	2022-11-07	2022-11-17	0.00	\N	0	26	S	\N	\N
40	2	3	Definicao de requisitos	2	Esta entrega tem a finalidade de identificar as necessidades do usuario, modelar o processo de trabalho, mapear as funcionalidades requeridas em forma de documento de visao, criar backlog dos requisitos do produto e definir plano de sprints.	2022-10-19 12:36:43.86151-03	\N	6	0	0	0	Conformidade tecnica com MDS Agil PF	\N	\N	4	2022-12-05	2022-12-12	\N	\N	N	2022-12-05	2022-12-12	0.00	6	6	40	S	4	\N
8	1	7	atividade 3	3	teste	2022-10-11 18:05:08.43973-03	\N	90	20000	120000	0	\N	39	\N	2	2021-01-04	2021-02-12	S	N	N	2021-07-01	2021-11-09	100.00	30	90	6	S	\N	2022-10-11
9	1	7	atividade 4 - marco de entrega	4	teste	2022-10-11 18:05:08.43973-03	\N	0	20000	20000	0	\N	30	\N	2	2022-03-31	2022-03-31	N	S	N	2022-03-31	2022-03-31	100.00	0	0	7	S	\N	2022-10-11
10	1	\N	GRUPO DE ENTREGAS 2	1	\N	2022-10-11 18:08:30.528161-03	\N	315	0	0	0	\N	\N	\N	\N	2021-01-04	2022-03-31	\N	\N	\N	2021-10-29	2022-11-04	100.00	315	315	8	S	\N	\N
8	2	1	TAP	2	Documento que formaliza a abertura do projeto.	2022-10-18 11:27:56.082039-03	\N	\N	0	0	0	Conformidade com a MGP-PF e com a necessidade demandada.	\N	\N	4	2022-10-18	2022-10-18	\N	\N	N	2022-10-18	2022-10-18	100.00	\N	0	2	S	5	\N
11	1	10	entrega 3	2	teste	2022-10-11 18:08:30.528161-03	\N	315	0	0	0	teste	\N	\N	2	2021-01-04	2022-03-31	\N	\N	N	2021-10-29	2022-04-19	100.00	315	315	9	S	3	\N
9	2	8	Elaborar TAP	3		2022-10-18 11:29:38.339416-03	\N	1	0	0	0	\N	\N	\N	4	2022-10-18	2022-10-18	N	N	N	2022-10-18	2022-10-18	100.00	1	1	3	S	\N	2022-10-20
18	2	7	Processo demandas de sistemas descentralizados	2	Desenho do fluxo das atividades, descricao dessas atividades, definicao de seus responsaveis, definicao das ferramentas utilizadas e outras caracteristicas do processo, registrados em notacao BPMN.	2022-10-18 11:55:31.492879-03	\N	2	0	0	0	Conformidade tecnica e funcional.	\N	\N	4	2022-10-24	2022-10-25	\N	\N	N	2022-10-24	2022-10-25	0.00	2	2	10	S	4	\N
19	2	18	Mapear e modelar o processo de demandas	3		2022-10-18 11:55:31.492879-03	\N	1	0	0	0	\N	\N	\N	4	2022-10-24	2022-10-24	N	N	N	2022-10-24	2022-10-24	0.00	1	1	11	S	\N	\N
20	2	18	Homologar o processo de demandas	3		2022-10-18 11:55:31.492879-03	\N	1	0	0	0	\N	\N	\N	4	2022-10-25	2022-10-25	N	N	N	2022-10-25	2022-10-25	0.00	1	1	12	S	\N	\N
54	2	6	Criar estrutura organizacional na DTI	2	Formalizar a criacao da estrutura organizacional responsavel pelo processo Desenvolvimento Descentralizado de Software. Normatizar a governanca do processo entre a unidade central e as unidades descentralizadas regionais executoras.	2022-10-21 13:03:19.890309-03	\N	\N	0	0	0	Portaria DG de criacao da estrutura organizacional aprovada e publicada.	\N	\N	4	2022-11-14	2022-11-17	\N	\N	N	2022-11-14	2022-11-17	0.00	\N	0	31	S	4	\N
49	2	\N	EXECUCAO - Implantacao do sistema piloto	1	\N	2022-10-20 12:08:53.124036-03	\N	\N	0	0	0	\N	\N	\N	\N	2023-01-02	2023-01-06	\N	\N	\N	2023-01-02	2023-01-06	0.00	\N	0	34	S	\N	\N
52	2	50	Treinar usuarios do novo sistema	3	\N	2022-10-20 12:29:45.518855-03	\N	\N	0	0	0	\N	\N	\N	4	2023-01-04	2023-01-06	N	\N	N	2023-01-04	2023-01-06	0.00	3	3	38	S	\N	\N
3	2	\N	EXECUCAO - construcao do sistema piloto	1	\N	2022-10-18 11:20:46.453367-03	\N	28	0	0	0	\N	\N	\N	\N	2022-12-05	2023-01-11	\N	\N	\N	2022-12-05	2023-01-11	0.00	28	28	39	S	\N	\N
1	2	\N	INICIACAO	1	\N	2022-10-18 11:20:21.564424-03	\N	\N	0	0	0	\N	\N	\N	\N	2022-10-18	2022-10-18	\N	\N	\N	2022-10-18	2022-10-18	100.00	\N	0	1	S	\N	\N
10	2	8	TAP elaborado	4		2022-10-18 11:30:05.155372-03	\N	0	0	0	0	\N	\N	\N	4	2022-10-18	2022-10-18	N	N	N	2022-10-18	2022-10-18	100.00	0	0	4	S	\N	2022-10-20
41	2	40	Elaborar documento de visao	3	\N	2022-10-19 12:37:45.158749-03	\N	\N	0	0	0	\N	\N	\N	4	2022-12-05	2022-12-07	N	\N	N	2022-12-05	2022-12-07	0.00	3	3	41	S	\N	\N
12	2	11	Elaborar plano do projeto	3		2022-10-18 11:32:28.581485-03	\N	6	0	0	0	\N	\N	\N	4	2022-10-20	2022-10-20	N	N	N	2022-10-20	2022-10-27	0.00	1	6	7	S	\N	\N
7	2	\N	EXECUCAO - mapeamento e modelagem do processo	1	\N	2022-10-18 11:23:00.497254-03	\N	\N	0	0	0	\N	\N	\N	\N	2022-10-24	2022-10-25	\N	\N	\N	2022-10-24	2022-11-04	0.00	\N	0	9	S	\N	\N
45	2	44	Detalhar especificacao de funcionalidade X ou modulo XPTO	3		2022-10-19 12:50:33.616939-03	\N	10	0	0	0	\N	\N	\N	4	2022-12-13	2022-12-26	N	N	N	2022-12-13	2022-12-26	0.00	10	10	45	S	\N	\N
4	2	\N	MONITORAMENTO	1	\N	2022-10-18 11:20:55.23111-03	\N	\N	0	0	0	\N	\N	\N	\N	2022-10-17	2023-01-12	\N	\N	\N	2022-10-17	2023-01-12	0.00	\N	0	49	S	\N	\N
22	2	7	Processo de construcao da solucao de software descentralizado	2	Desenho do fluxo das atividades, descricao dessas atividades, definicao de seus responsaveis, definicao das ferramentas utilizadas e outras caracteristicas do processo, registrados em notacao BPMN.	2022-10-18 12:01:11.931245-03	\N	2	0	0	0	Conformidade tecnica e funcional.	\N	\N	4	2022-10-24	2022-10-25	\N	\N	N	2022-11-03	2022-11-04	0.00	2	2	18	S	4	\N
23	2	22	Mapear e modelar o processo de construcao da solucao	3		2022-10-18 12:01:11.931245-03	\N	1	0	0	0	\N	\N	\N	4	2022-10-24	2022-10-24	N	N	N	2022-11-03	2022-11-03	0.00	1	1	19	S	\N	\N
24	2	22	Homologar o processo de construcao da solucao	3		2022-10-18 12:01:11.931245-03	\N	1	0	0	0	\N	\N	\N	4	2022-10-25	2022-10-25	N	N	N	2022-11-04	2022-11-04	0.00	1	1	20	S	\N	\N
25	2	22	Conclusao do processo de construcao da solucao	4		2022-10-18 12:01:11.931245-03	\N	0	0	0	0	\N	\N	\N	4	2022-10-25	2022-10-25	N	N	N	2022-11-04	2022-11-04	0.00	0	0	21	S	\N	\N
26	2	7	Processo de implantacao da solucao de desenvolvimento descentralizado	2	Desenho do fluxo das atividades, descricao dessas atividades, definicao de seus responsaveis, definicao das ferramentas utilizadas e outras caracteristicas do processo, registrados em notacao BPMN.	2022-10-18 12:30:04.271994-03	\N	2	0	0	0	Conformidade tecnica e funcional.	\N	\N	4	2022-10-24	2022-10-25	\N	\N	N	2022-11-03	2022-11-04	0.00	2	2	22	S	4	\N
27	2	26	Mapear e modelar o processo de implantacao	3		2022-10-18 12:30:04.271994-03	\N	1	0	0	0	\N	\N	\N	4	2022-10-24	2022-10-24	N	N	N	2022-11-03	2022-11-03	0.00	1	1	23	S	\N	\N
28	2	26	Homologar o processo de implantacao	3		2022-10-18 12:30:04.271994-03	\N	1	0	0	0	\N	\N	\N	4	2022-10-25	2022-10-25	N	N	N	2022-11-04	2022-11-04	0.00	1	1	24	S	\N	\N
29	2	26	Conclusao do processo de implantacao da solucao	4		2022-10-18 12:30:04.271994-03	\N	0	0	0	0	\N	\N	\N	4	2022-10-25	2022-10-25	N	N	N	2022-11-04	2022-11-04	0.00	0	0	25	S	\N	\N
50	2	49	Implantacao do sistema piloto	2	Sistema piloto instalado para uso.	2022-10-20 12:21:52.397251-03	\N	\N	0	0	0	Sistema piloto instalado e funcional.	\N	\N	4	2023-01-02	2023-01-06	\N	\N	N	2023-01-02	2023-01-06	0.00	\N	0	35	S	5	\N
53	2	50	Normatizar a operacao do novo sistema	3	\N	2022-10-20 12:34:12.94597-03	\N	\N	0	0	0	\N	\N	\N	4	2023-01-03	2023-01-05	N	\N	N	2023-01-03	2023-01-04	0.00	2	2	37	S	\N	\N
42	2	40	Elaborar plano de sprints	3	\N	2022-10-19 12:38:49.779037-03	\N	\N	0	0	0	\N	\N	\N	4	2022-12-09	2022-12-12	N	\N	N	2022-12-09	2022-12-12	0.00	2	2	42	S	\N	\N
46	2	44	Construir release	3		2022-10-19 12:51:49.905697-03	\N	10	0	0	0	\N	\N	\N	4	2022-12-27	2023-01-09	N	N	N	2022-12-27	2023-01-09	0.00	10	10	46	S	\N	\N
48	2	44	Homologar release	3	\N	2022-10-20 11:38:48.068354-03	\N	\N	0	0	0	\N	\N	\N	4	2023-01-10	2023-01-11	N	\N	N	2023-01-10	2023-01-11	0.00	2	2	47	S	\N	\N
30	2	4	Registros do projeto	2	Documentos referentes ao acompanhamento do projeto: atas de reuniao; status report; diario de bordo; comunicados; etc.	2022-10-18 12:36:40.824355-03	\N	\N	0	0	0	Conformidade com os objetivos do projeto e com a metodologia de gerenciamento de projetos da PF.	\N	\N	4	2022-10-17	2023-01-12	\N	\N	N	2022-10-17	2023-01-12	0.00	\N	0	50	S	5	\N
31	2	30	Monitorar e controlar o projeto	3	\N	2022-10-18 12:38:00.26321-03	\N	\N	0	0	0	\N	\N	\N	4	2022-10-17	2023-01-11	N	\N	N	2022-10-17	2023-01-11	0.00	60	60	51	S	\N	\N
33	2	5	Termo de Encerramento (TEP)	2	Documento de encerramento do projeto.	2022-10-18 12:44:03.859287-03	\N	\N	0	0	0	Conformidade com a MGP.	\N	\N	4	2023-01-13	2023-01-13	\N	\N	N	2023-01-13	2023-01-13	0.00	\N	0	54	S	5	\N
34	2	33	Elaborar TEP	3	\N	2022-10-18 12:45:37.400547-03	\N	\N	0	0	0	\N	\N	\N	4	2023-01-13	2023-01-13	N	\N	N	2023-01-13	2023-01-13	0.00	1	1	55	S	\N	\N
21	2	18	Conclusao do processo de demandas	4		2022-10-18 11:55:31.492879-03	\N	0	0	0	0	\N	\N	\N	4	2022-10-25	2022-10-25	N	N	N	2022-10-25	2022-10-25	0.00	0	0	13	S	\N	\N
14	2	7	Processo de definicao de requisitos de sistemas descentralizados	2	Desenho do fluxo das atividades, descricao dessas atividades, definicao de seus responsaveis, definicao das ferramentas utilizadas e outras caracteristicas do processo, registrados em notacao BPMN.	2022-10-18 11:38:51.739913-03	\N	2	0	0	0	Conformidade tecnica e funcional.	\N	\N	4	2022-10-24	2022-10-25	\N	\N	N	2022-10-27	2022-10-31	0.00	2	2	14	S	4	\N
16	2	14	Homologar o processo de definicao de requisitos	3	\N	2022-10-18 11:42:35.271568-03	\N	\N	0	0	0	\N	\N	\N	4	2022-10-25	2022-10-25	N	\N	N	2022-10-31	2022-10-31	0.00	1	1	16	S	\N	\N
17	2	14	Conclusao do processo de definicao de requisitos	4	\N	2022-10-18 11:48:03.410692-03	\N	\N	0	0	0	\N	\N	\N	4	2022-10-25	2022-10-25	N	\N	N	2022-10-31	2022-10-31	0.00	\N	0	17	S	\N	\N
36	2	6	Implantacao do processo de desenvolvimento descentralizado	2	Preparacao dos usuarios para a pratica das melhorias definidas, normatizacao do novo processo de trabalho e operacao assistida. Definir as ferramentas de operacao e suporte do processo, implantar o fluxo das atividades, compromissar seus responsaveis e efetivar a gestao diaria do processo	2022-10-18 12:48:20.806596-03	\N	4	0	0	0	Conformidade funcional e tecnica.	\N	\N	4	2022-11-07	2022-11-11	\N	\N	N	2022-11-07	2022-11-09	0.00	5	4	27	S	4	\N
37	2	36	Treinar usuarios na nova pratica de trabalho	3	\N	2022-10-18 12:58:42.436155-03	\N	\N	0	0	0	\N	\N	\N	4	2022-11-07	2022-11-09	N	\N	N	2022-11-07	2022-11-09	0.00	3	3	28	S	\N	\N
39	2	36	Conclusao da implantacao	3	\N	2022-10-19 12:00:08.422935-03	\N	\N	0	0	0	\N	\N	\N	4	2022-11-09	2022-11-09	N	\N	N	2022-11-09	2022-11-09	0.00	1	1	29	S	\N	\N
38	2	36	Normatizar o novo processo de trabalho	4		2022-10-19 11:58:29.260153-03	\N	0	0	0	0	\N	\N	\N	4	2022-11-07	2022-11-11	N	N	N	2022-11-09	2022-11-09	0.00	0	0	30	S	\N	\N
55	2	54	Elaborar minuta de normativo	3	Reunir partes interessadas e elaborar minuta de ato normativo de criacao da estrutura organizacional de execucao e suporte do processo de desenvolvimento descentralizado de software.	2022-10-21 13:06:10.555622-03	\N	\N	0	0	0	\N	\N	\N	4	2022-11-14	2022-11-17	N	\N	N	2022-11-14	2022-11-17	0.00	3	3	32	S	\N	\N
56	2	54	Ato normativo publicado em BS	4	\N	2022-10-21 13:07:16.456316-03	\N	\N	0	0	0	\N	\N	\N	4	2022-11-17	2022-11-17	N	\N	N	2022-11-17	2022-11-17	0.00	\N	0	33	S	\N	\N
51	2	50	Compromissar as partes interessadas no uso do sistema	3	\N	2022-10-20 12:28:25.879402-03	\N	\N	0	0	0	\N	\N	\N	4	2023-01-02	2023-01-03	N	\N	N	2023-01-02	2023-01-03	0.00	2	2	36	S	\N	\N
43	2	40	Conclusao do plano de sprints e documento de visao	4	\N	2022-10-19 12:40:33.140373-03	\N	\N	0	0	0	\N	\N	\N	4	2022-12-12	2022-12-12	N	\N	N	2022-12-12	2022-12-12	0.00	\N	0	43	S	\N	\N
44	2	3	Release 1.0	2	Modelagem parcial em bpm e entrega de codigo e telas para o usuario do processo de trabalho objeto de automacao via APEX Oracle. Producao de release.	2022-10-19 12:48:37.828635-03	\N	20	0	0	0	Conformidade tecnica com requisitos BPM da PF e MDS Agil DTI PF.	\N	\N	4	2022-12-13	2023-01-11	\N	\N	N	2022-12-13	2023-01-11	0.00	20	20	44	S	4	\N
47	2	44	Release 1 entregue	4		2022-10-19 12:53:28.361527-03	\N	0	0	0	0	\N	\N	\N	4	2023-01-09	2023-01-09	N	N	N	2023-01-11	2023-01-11	0.00	0	0	48	S	\N	\N
32	2	30	Conclusao dos registros	4	\N	2022-10-18 12:42:06.749435-03	\N	\N	0	0	0	\N	\N	\N	4	2023-01-12	2023-01-12	N	\N	N	2023-01-12	2023-01-12	0.00	\N	0	52	S	\N	\N
5	2	\N	ENCERRAMENTO	1	\N	2022-10-18 11:21:03.398296-03	\N	\N	0	0	0	\N	\N	\N	\N	2023-01-13	2023-01-13	\N	\N	\N	2023-01-13	2023-01-13	0.00	\N	0	53	S	\N	\N
35	2	33	Encerramento do projeto	4	\N	2022-10-18 12:46:34.125634-03	\N	\N	0	0	0	\N	\N	\N	4	2023-01-13	2023-01-13	N	\N	N	2023-01-13	2023-01-13	0.00	\N	0	56	S	\N	\N
13	1	11	atividade 6 - marco de entrega	4	teste	2022-10-11 18:08:30.528161-03	\N	0	20000	20000	0	\N	30	\N	2	2022-03-31	2022-03-31	N	S	N	2022-04-19	2022-04-19	100.00	0	0	11	S	\N	2022-10-11
15	1	14	atividade 7	3	teste	2022-10-11 18:08:30.528161-03	\N	134	20000	120000	1	\N	39	\N	2	2021-01-04	2021-02-12	S	N	N	2022-04-22	2022-11-01	100.00	30	134	13	S	\N	2022-10-13
16	1	14	atividade 8 - marco de entrega	4	teste	2022-10-11 18:08:30.528161-03	\N	0	20000	20000	2	\N	30	\N	2	2022-03-31	2022-03-31	N	S	N	2022-11-04	2022-11-04	100.00	0	0	14	S	\N	2022-11-16
\.


--
-- TOC entry 4071 (class 0 OID 10422983)
-- Dependencies: 292
-- Data for Name: tb_atividadecronopredecessora; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_atividadecronopredecessora (idatividadecronograma, idprojetocronograma, idatividadepredecessora) FROM stdin;
13	1	12
15	1	13
13	2	12
16	2	15
17	2	16
20	2	19
21	2	20
24	2	23
25	2	24
32	2	31
35	2	34
43	2	42
45	2	43
46	2	45
48	2	46
47	2	48
10	2	9
53	2	51
56	2	55
38	2	39
16	1	15
\.


--
-- TOC entry 4072 (class 0 OID 10422986)
-- Dependencies: 293
-- Data for Name: tb_atividadeocultar; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_atividadeocultar (idprojeto, idatividadecronograma, idpessoa, dtcadastro) FROM stdin;
\.


--
-- TOC entry 4073 (class 0 OID 10422990)
-- Dependencies: 294
-- Data for Name: tb_bloqueioprojeto; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_bloqueioprojeto (idbloqueioprojeto, idpessoa, datbloqueio, datdesbloqueio, desjustificativa, idprojeto) FROM stdin;
\.


--
-- TOC entry 4052 (class 0 OID 10422337)
-- Dependencies: 271
-- Data for Name: tb_cargo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_cargo (idcargo, dsdenominacao, dssigla, ativo) FROM stdin;
1	ADMIN EGP	ADMINEGP	t
2	ADMINISTRADOR	ADM	t
3	ADVOGADO DA UNIAO	AU	t
4	AG TELECOMUNIC E ELETRICIDADE	ATE	t
5	AGENTE ADMINISTRATIVO	AGADM	t
6	AGENTE CINEFOTOGRAFIA E MICROFILMAGEM	AGCIMIC	t
7	AGENTE DE COMUNICACAO SOCIAL	ACS	t
8	AGENTE	APF	t
9	ESCRIVAO	EPF	t
10	ASSISTENTE ADMINISTRATIVO	ASSADM	t
11	ASSISTENTE JURIDICO	AJ	t
12	DELEGADO	DPF	t
13	PAPILOSCOPISTA	PPF	t
14	PERITO	PCF	t
\.


--
-- TOC entry 4074 (class 0 OID 10422996)
-- Dependencies: 295
-- Data for Name: tb_comentario; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_comentario (idcomentario, idprojeto, idatividadecronograma, dscomentario, dtcomentario, idpessoa) FROM stdin;
1	1	1	comentario teste	2022-10-13 11:51:13.029821-03	1
\.


--
-- TOC entry 4075 (class 0 OID 10422999)
-- Dependencies: 296
-- Data for Name: tb_comunicacao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_comunicacao (idcomunicacao, idprojeto, desinformacao, desinformado, desorigem, desfrequencia, destransmissao, desarmazenamento, idcadastrador, datcadastro, nomresponsavel, idresponsavel) FROM stdin;
1	1	Apresentacao periodica do andamento do projeto xx	PMO, Patrocinador, Equipe do projeto	Relatorio de Status Report do Gepnet2	A cada 30 dias	Reuniao online	RUD do Gepnet2	1	2022-10-13	Usuario01	3
\.


--
-- TOC entry 4076 (class 0 OID 10423005)
-- Dependencies: 297
-- Data for Name: tb_contramedida; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_contramedida (idcontramedida, idrisco, descontramedida, datprazocontramedida, datprazocontramedidaatraso, domstatuscontramedida, flacontramedidaefetiva, desresponsavel, idcadastrador, datcadastro, idtipocontramedida, nocontramedida) FROM stdin;
5	1	Agendar reunioes com  as areas participantes do Comite Gestor PDTI	2016-09-29	2016-10-28	4	2	Usuario01	1	2016-09-29	1	Reuniao de apresentacao do Projeto
3	2	Contratar de forma emergencial empresa de courier para as entregas de documentos urgentes do projeto, de modo a evitar que os tramites do projeto atrasem.	2015-11-23	2015-11-23	4	2	Usuario01	1	2015-11-13	2	\N
4	2	Efetuar oficinas no local de trabalho voltado para potencializar os valores do servidor no uso de suas potencialidades e talentos.	2016-09-29	2016-10-15	5	2	Usuario01	1	2016-09-29	4	Medida Motivacional
6	2	Efetuar contato com outras empresas de plataforma de ensino publico ou privado (ENAP, SENASP, etc)	2016-09-29	2016-10-28	5	1	Usuario01	1	2016-09-29	1	Trocar instituicao da parceria
\.


--
-- TOC entry 4077 (class 0 OID 10423013)
-- Dependencies: 298
-- Data for Name: tb_diagnostico; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_diagnostico (iddiagnostico, dsdiagnostico, idunidadeprincipal, dtinicio, dtencerramento, idcadastrador, dtcadastro, ativo, sq_diagnostico, ano) FROM stdin;
\.


--
-- TOC entry 4079 (class 0 OID 10423019)
-- Dependencies: 300
-- Data for Name: tb_diariobordo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_diariobordo (iddiariobordo, idprojeto, datdiariobordo, domreferencia, domsemafaro, desdiariobordo, idcadastrador, datcadastro, idalterador) FROM stdin;
1	1	2022-10-13	Reuniao	3	Foi realizada reuniao de alinhamento e comunicacao do status report do projeto na data de hoje.\r\nO usuario05 nao participou da reuniao.\r\nO orcamento do projeto sera votado na semana que vem no plenario da casa branca. O usuario01 foi encarregado de acompanhar a votacao.	1	2022-10-13	\N
\.


--
-- TOC entry 4080 (class 0 OID 10423026)
-- Dependencies: 301
-- Data for Name: tb_diautil; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_diautil (iddiautil, datautil, ano) FROM stdin;
14897	2010-01-04	2010
14898	2010-01-05	2010
14899	2010-01-06	2010
14900	2010-01-07	2010
14901	2010-01-08	2010
14902	2010-01-11	2010
14903	2010-01-12	2010
14904	2010-01-13	2010
14905	2010-01-14	2010
14906	2010-01-15	2010
14907	2010-01-18	2010
14908	2010-01-19	2010
14909	2010-01-20	2010
14910	2010-01-21	2010
14911	2010-01-22	2010
14912	2010-01-25	2010
14913	2010-01-26	2010
14914	2010-01-27	2010
14915	2010-01-28	2010
14916	2010-01-29	2010
14917	2010-02-01	2010
14918	2010-02-02	2010
14919	2010-02-03	2010
14920	2010-02-04	2010
14921	2010-02-05	2010
14922	2010-02-08	2010
14923	2010-02-09	2010
14924	2010-02-10	2010
14925	2010-02-11	2010
14926	2010-02-12	2010
14927	2010-02-15	2010
14928	2010-02-17	2010
14929	2010-02-18	2010
14930	2010-02-19	2010
14931	2010-02-22	2010
14932	2010-02-23	2010
14933	2010-02-24	2010
14934	2010-02-25	2010
14935	2010-02-26	2010
14936	2010-03-01	2010
14937	2010-03-02	2010
14938	2010-03-03	2010
14939	2010-03-04	2010
14940	2010-03-05	2010
14941	2010-03-08	2010
14942	2010-03-09	2010
14943	2010-03-10	2010
14944	2010-03-11	2010
14945	2010-03-12	2010
14946	2010-03-15	2010
14947	2010-03-16	2010
14948	2010-03-17	2010
14949	2010-03-18	2010
14950	2010-03-19	2010
14951	2010-03-22	2010
14952	2010-03-23	2010
14953	2010-03-24	2010
14954	2010-03-25	2010
14955	2010-03-26	2010
14956	2010-03-29	2010
14957	2010-03-30	2010
14958	2010-03-31	2010
14959	2010-04-01	2010
14960	2010-04-02	2010
14961	2010-04-05	2010
14962	2010-04-06	2010
14963	2010-04-07	2010
14964	2010-04-08	2010
14965	2010-04-09	2010
14966	2010-04-12	2010
14967	2010-04-13	2010
14968	2010-04-14	2010
14969	2010-04-15	2010
14970	2010-04-16	2010
14971	2010-04-19	2010
14972	2010-04-20	2010
14973	2010-04-22	2010
14974	2010-04-23	2010
14975	2010-04-26	2010
14976	2010-04-27	2010
14977	2010-04-28	2010
14978	2010-04-29	2010
14979	2010-04-30	2010
14980	2010-05-03	2010
14981	2010-05-04	2010
14982	2010-05-05	2010
14983	2010-05-06	2010
14984	2010-05-07	2010
14985	2010-05-10	2010
14986	2010-05-11	2010
14987	2010-05-12	2010
14988	2010-05-13	2010
14989	2010-05-14	2010
14990	2010-05-17	2010
14991	2010-05-18	2010
14992	2010-05-19	2010
14993	2010-05-20	2010
14994	2010-05-21	2010
14995	2010-05-24	2010
14996	2010-05-25	2010
14997	2010-05-26	2010
14998	2010-05-27	2010
14999	2010-05-28	2010
15000	2010-05-31	2010
15001	2010-06-01	2010
15002	2010-06-02	2010
15003	2010-06-04	2010
15004	2010-06-07	2010
15005	2010-06-08	2010
15006	2010-06-09	2010
15007	2010-06-10	2010
15008	2010-06-11	2010
15009	2010-06-14	2010
15010	2010-06-15	2010
15011	2010-06-16	2010
15012	2010-06-17	2010
15013	2010-06-18	2010
15014	2010-06-21	2010
15015	2010-06-22	2010
15016	2010-06-23	2010
15017	2010-06-24	2010
15018	2010-06-25	2010
15019	2010-06-28	2010
15020	2010-06-29	2010
15021	2010-06-30	2010
15022	2010-07-01	2010
15023	2010-07-02	2010
15024	2010-07-05	2010
15025	2010-07-06	2010
15026	2010-07-07	2010
15027	2010-07-08	2010
15028	2010-07-09	2010
15029	2010-07-12	2010
15030	2010-07-13	2010
15031	2010-07-14	2010
15032	2010-07-15	2010
15033	2010-07-16	2010
15034	2010-07-19	2010
15035	2010-07-20	2010
15036	2010-07-21	2010
15037	2010-07-22	2010
15038	2010-07-23	2010
15039	2010-07-26	2010
15040	2010-07-27	2010
15041	2010-07-28	2010
15042	2010-07-29	2010
15043	2010-07-30	2010
15044	2010-08-02	2010
15045	2010-08-03	2010
15046	2010-08-04	2010
15047	2010-08-05	2010
15048	2010-08-06	2010
15049	2010-08-09	2010
15050	2010-08-10	2010
15051	2010-08-11	2010
15052	2010-08-12	2010
15053	2010-08-13	2010
15054	2010-08-16	2010
15055	2010-08-17	2010
15056	2010-08-18	2010
15057	2010-08-19	2010
15058	2010-08-20	2010
15059	2010-08-23	2010
15060	2010-08-24	2010
15061	2010-08-25	2010
15062	2010-08-26	2010
15063	2010-08-27	2010
15064	2010-08-30	2010
15065	2010-08-31	2010
15066	2010-09-01	2010
15067	2010-09-02	2010
15068	2010-09-03	2010
15069	2010-09-06	2010
15070	2010-09-08	2010
15071	2010-09-09	2010
15072	2010-09-10	2010
15073	2010-09-13	2010
15074	2010-09-14	2010
15075	2010-09-15	2010
15076	2010-09-16	2010
15077	2010-09-17	2010
15078	2010-09-20	2010
15079	2010-09-21	2010
15080	2010-09-22	2010
15081	2010-09-23	2010
15082	2010-09-24	2010
15083	2010-09-27	2010
15084	2010-09-28	2010
15085	2010-09-29	2010
15086	2010-09-30	2010
15087	2010-10-01	2010
15088	2010-10-04	2010
15089	2010-10-05	2010
15090	2010-10-06	2010
15091	2010-10-07	2010
15092	2010-10-08	2010
15093	2010-10-11	2010
15094	2010-10-13	2010
15095	2010-10-14	2010
15096	2010-10-15	2010
15097	2010-10-18	2010
15098	2010-10-19	2010
15099	2010-10-20	2010
15100	2010-10-21	2010
15101	2010-10-22	2010
15102	2010-10-25	2010
15103	2010-10-26	2010
15104	2010-10-27	2010
15105	2010-10-29	2010
15106	2010-11-01	2010
15107	2010-11-03	2010
15108	2010-11-04	2010
15109	2010-11-05	2010
15110	2010-11-08	2010
15111	2010-11-09	2010
15112	2010-11-10	2010
15113	2010-11-11	2010
15114	2010-11-12	2010
15115	2010-11-16	2010
15116	2010-11-17	2010
15117	2010-11-18	2010
15118	2010-11-19	2010
15119	2010-11-22	2010
15120	2010-11-23	2010
15121	2010-11-24	2010
15122	2010-11-25	2010
15123	2010-11-26	2010
15124	2010-11-29	2010
15125	2010-11-30	2010
15126	2010-12-01	2010
15127	2010-12-02	2010
15128	2010-12-03	2010
15129	2010-12-06	2010
15130	2010-12-07	2010
15131	2010-12-08	2010
15132	2010-12-09	2010
15133	2010-12-10	2010
15134	2010-12-13	2010
15135	2010-12-14	2010
15136	2010-12-15	2010
15137	2010-12-16	2010
15138	2010-12-17	2010
15139	2010-12-20	2010
15140	2010-12-21	2010
15141	2010-12-22	2010
15142	2010-12-23	2010
15143	2010-12-24	2010
15144	2010-12-27	2010
15145	2010-12-28	2010
15146	2010-12-29	2010
15147	2010-12-30	2010
15148	2010-12-31	2010
15149	2011-01-03	2011
15150	2011-01-04	2011
15151	2011-01-05	2011
15152	2011-01-06	2011
15153	2011-01-07	2011
15154	2011-01-10	2011
15155	2011-01-11	2011
15156	2011-01-12	2011
15157	2011-01-13	2011
15158	2011-01-14	2011
15159	2011-01-17	2011
15160	2011-01-18	2011
15161	2011-01-19	2011
15162	2011-01-20	2011
15163	2011-01-21	2011
15164	2011-01-24	2011
15165	2011-01-25	2011
15166	2011-01-26	2011
15167	2011-01-27	2011
15168	2011-01-28	2011
15169	2011-01-31	2011
15170	2011-02-01	2011
15171	2011-02-02	2011
15172	2011-02-03	2011
15173	2011-02-04	2011
15174	2011-02-07	2011
15175	2011-02-08	2011
15176	2011-02-09	2011
15177	2011-02-10	2011
15178	2011-02-11	2011
15179	2011-02-14	2011
15180	2011-02-15	2011
15181	2011-02-16	2011
15182	2011-02-17	2011
15183	2011-02-18	2011
15184	2011-02-21	2011
15185	2011-02-22	2011
15186	2011-02-23	2011
15187	2011-02-24	2011
15188	2011-02-25	2011
15189	2011-02-28	2011
15190	2011-03-01	2011
15191	2011-03-02	2011
15192	2011-03-03	2011
15193	2011-03-04	2011
15194	2011-03-07	2011
15195	2011-03-09	2011
15196	2011-03-10	2011
15197	2011-03-11	2011
15198	2011-03-14	2011
15199	2011-03-15	2011
15200	2011-03-16	2011
15201	2011-03-17	2011
15202	2011-03-18	2011
15203	2011-03-21	2011
15204	2011-03-22	2011
15205	2011-03-23	2011
15206	2011-03-24	2011
15207	2011-03-25	2011
15208	2011-03-28	2011
15209	2011-03-29	2011
15210	2011-03-30	2011
15211	2011-03-31	2011
15212	2011-04-01	2011
15213	2011-04-04	2011
15214	2011-04-05	2011
15215	2011-04-06	2011
15216	2011-04-07	2011
15217	2011-04-08	2011
15218	2011-04-11	2011
15219	2011-04-12	2011
15220	2011-04-13	2011
15221	2011-04-14	2011
15222	2011-04-15	2011
15223	2011-04-18	2011
15224	2011-04-19	2011
15225	2011-04-20	2011
15226	2011-04-22	2011
15227	2011-04-25	2011
15228	2011-04-26	2011
15229	2011-04-27	2011
15230	2011-04-28	2011
15231	2011-04-29	2011
15232	2011-05-02	2011
15233	2011-05-03	2011
15234	2011-05-04	2011
15235	2011-05-05	2011
15236	2011-05-06	2011
15237	2011-05-09	2011
15238	2011-05-10	2011
15239	2011-05-11	2011
15240	2011-05-12	2011
15241	2011-05-13	2011
15242	2011-05-16	2011
15243	2011-05-17	2011
15244	2011-05-18	2011
15245	2011-05-19	2011
15246	2011-05-20	2011
15247	2011-05-23	2011
15248	2011-05-24	2011
15249	2011-05-25	2011
15250	2011-05-26	2011
15251	2011-05-27	2011
15252	2011-05-30	2011
15253	2011-05-31	2011
15254	2011-06-01	2011
15255	2011-06-02	2011
15256	2011-06-03	2011
15257	2011-06-06	2011
15258	2011-06-07	2011
15259	2011-06-08	2011
15260	2011-06-09	2011
15261	2011-06-10	2011
15262	2011-06-13	2011
15263	2011-06-14	2011
15264	2011-06-15	2011
15265	2011-06-16	2011
15266	2011-06-17	2011
15267	2011-06-20	2011
15268	2011-06-21	2011
15269	2011-06-22	2011
15270	2011-06-24	2011
15271	2011-06-27	2011
15272	2011-06-28	2011
15273	2011-06-29	2011
15274	2011-06-30	2011
15275	2011-07-01	2011
15276	2011-07-04	2011
15277	2011-07-05	2011
15278	2011-07-06	2011
15279	2011-07-07	2011
15280	2011-07-08	2011
15281	2011-07-11	2011
15282	2011-07-12	2011
15283	2011-07-13	2011
15284	2011-07-14	2011
15285	2011-07-15	2011
15286	2011-07-18	2011
15287	2011-07-19	2011
15288	2011-07-20	2011
15289	2011-07-21	2011
15290	2011-07-22	2011
15291	2011-07-25	2011
15292	2011-07-26	2011
15293	2011-07-27	2011
15294	2011-07-28	2011
15295	2011-07-29	2011
15296	2011-08-01	2011
15297	2011-08-02	2011
15298	2011-08-03	2011
15299	2011-08-04	2011
15300	2011-08-05	2011
15301	2011-08-08	2011
15302	2011-08-09	2011
15303	2011-08-10	2011
15304	2011-08-11	2011
15305	2011-08-12	2011
15306	2011-08-15	2011
15307	2011-08-16	2011
15308	2011-08-17	2011
15309	2011-08-18	2011
15310	2011-08-19	2011
15311	2011-08-22	2011
15312	2011-08-23	2011
15313	2011-08-24	2011
15314	2011-08-25	2011
15315	2011-08-26	2011
15316	2011-08-29	2011
15317	2011-08-30	2011
15318	2011-08-31	2011
15319	2011-09-01	2011
15320	2011-09-02	2011
15321	2011-09-05	2011
15322	2011-09-06	2011
15323	2011-09-08	2011
15324	2011-09-09	2011
15325	2011-09-12	2011
15326	2011-09-13	2011
15327	2011-09-14	2011
15328	2011-09-15	2011
15329	2011-09-16	2011
15330	2011-09-19	2011
15331	2011-09-20	2011
15332	2011-09-21	2011
15333	2011-09-22	2011
15334	2011-09-23	2011
15335	2011-09-26	2011
15336	2011-09-27	2011
15337	2011-09-28	2011
15338	2011-09-29	2011
15339	2011-09-30	2011
15340	2011-10-03	2011
15341	2011-10-04	2011
15342	2011-10-05	2011
15343	2011-10-06	2011
15344	2011-10-07	2011
15345	2011-10-10	2011
15346	2011-10-11	2011
15347	2011-10-13	2011
15348	2011-10-14	2011
15349	2011-10-17	2011
15350	2011-10-18	2011
15351	2011-10-19	2011
15352	2011-10-20	2011
15353	2011-10-21	2011
15354	2011-10-24	2011
15355	2011-10-25	2011
15356	2011-10-26	2011
15357	2011-10-27	2011
15358	2011-10-31	2011
15359	2011-11-01	2011
15360	2011-11-03	2011
15361	2011-11-04	2011
15362	2011-11-07	2011
15363	2011-11-08	2011
15364	2011-11-09	2011
15365	2011-11-10	2011
15366	2011-11-11	2011
15367	2011-11-14	2011
15368	2011-11-16	2011
15369	2011-11-17	2011
15370	2011-11-18	2011
15371	2011-11-21	2011
15372	2011-11-22	2011
15373	2011-11-23	2011
15374	2011-11-24	2011
15375	2011-11-25	2011
15376	2011-11-28	2011
15377	2011-11-29	2011
15378	2011-11-30	2011
15379	2011-12-01	2011
15380	2011-12-02	2011
15381	2011-12-05	2011
15382	2011-12-06	2011
15383	2011-12-07	2011
15384	2011-12-08	2011
15385	2011-12-09	2011
15386	2011-12-12	2011
15387	2011-12-13	2011
15388	2011-12-14	2011
15389	2011-12-15	2011
15390	2011-12-16	2011
15391	2011-12-19	2011
15392	2011-12-20	2011
15393	2011-12-21	2011
15394	2011-12-22	2011
15395	2011-12-23	2011
15396	2011-12-26	2011
15397	2011-12-27	2011
15398	2011-12-28	2011
15399	2011-12-29	2011
15400	2011-12-30	2011
15401	2012-01-02	2012
15402	2012-01-03	2012
15403	2012-01-04	2012
15404	2012-01-05	2012
15405	2012-01-06	2012
15406	2012-01-09	2012
15407	2012-01-10	2012
15408	2012-01-11	2012
15409	2012-01-12	2012
15410	2012-01-13	2012
15411	2012-01-16	2012
15412	2012-01-17	2012
15413	2012-01-18	2012
15414	2012-01-19	2012
15415	2012-01-20	2012
15416	2012-01-23	2012
15417	2012-01-24	2012
15418	2012-01-25	2012
15419	2012-01-26	2012
15420	2012-01-27	2012
15421	2012-01-30	2012
15422	2012-01-31	2012
15423	2012-02-01	2012
15424	2012-02-02	2012
15425	2012-02-03	2012
15426	2012-02-06	2012
15427	2012-02-07	2012
15428	2012-02-08	2012
15429	2012-02-09	2012
15430	2012-02-10	2012
15431	2012-02-13	2012
15432	2012-02-14	2012
15433	2012-02-15	2012
15434	2012-02-16	2012
15435	2012-02-17	2012
15436	2012-02-20	2012
15437	2012-02-22	2012
15438	2012-02-23	2012
15439	2012-02-24	2012
15440	2012-02-27	2012
15441	2012-02-28	2012
15442	2012-02-29	2012
15443	2012-03-01	2012
15444	2012-03-02	2012
15445	2012-03-05	2012
15446	2012-03-06	2012
15447	2012-03-07	2012
15448	2012-03-08	2012
15449	2012-03-09	2012
15450	2012-03-12	2012
15451	2012-03-13	2012
15452	2012-03-14	2012
15453	2012-03-15	2012
15454	2012-03-16	2012
15455	2012-03-19	2012
15456	2012-03-20	2012
15457	2012-03-21	2012
15458	2012-03-22	2012
15459	2012-03-23	2012
15460	2012-03-26	2012
15461	2012-03-27	2012
15462	2012-03-28	2012
15463	2012-03-29	2012
15464	2012-03-30	2012
15465	2012-04-02	2012
15466	2012-04-03	2012
15467	2012-04-04	2012
15468	2012-04-05	2012
15469	2012-04-06	2012
15470	2012-04-09	2012
15471	2012-04-10	2012
15472	2012-04-11	2012
15473	2012-04-12	2012
15474	2012-04-13	2012
15475	2012-04-16	2012
15476	2012-04-17	2012
15477	2012-04-18	2012
15478	2012-04-19	2012
15479	2012-04-20	2012
15480	2012-04-23	2012
15481	2012-04-24	2012
15482	2012-04-25	2012
15483	2012-04-26	2012
15484	2012-04-27	2012
15485	2012-04-30	2012
15486	2012-05-02	2012
15487	2012-05-03	2012
15488	2012-05-04	2012
15489	2012-05-07	2012
15490	2012-05-08	2012
15491	2012-05-09	2012
15492	2012-05-10	2012
15493	2012-05-11	2012
15494	2012-05-14	2012
15495	2012-05-15	2012
15496	2012-05-16	2012
15497	2012-05-17	2012
15498	2012-05-18	2012
15499	2012-05-21	2012
15500	2012-05-22	2012
15501	2012-05-23	2012
15502	2012-05-24	2012
15503	2012-05-25	2012
15504	2012-05-28	2012
15505	2012-05-29	2012
15506	2012-05-30	2012
15507	2012-05-31	2012
15508	2012-06-01	2012
15509	2012-06-04	2012
15510	2012-06-05	2012
15511	2012-06-06	2012
15512	2012-06-08	2012
15513	2012-06-11	2012
15514	2012-06-12	2012
15515	2012-06-13	2012
15516	2012-06-14	2012
15517	2012-06-15	2012
15518	2012-06-18	2012
15519	2012-06-19	2012
15520	2012-06-20	2012
15521	2012-06-21	2012
15522	2012-06-22	2012
15523	2012-06-25	2012
15524	2012-06-26	2012
15525	2012-06-27	2012
15526	2012-06-28	2012
15527	2012-06-29	2012
15528	2012-07-02	2012
15529	2012-07-03	2012
15530	2012-07-04	2012
15531	2012-07-05	2012
15532	2012-07-06	2012
15533	2012-07-09	2012
15534	2012-07-10	2012
15535	2012-07-11	2012
15536	2012-07-12	2012
15537	2012-07-13	2012
15538	2012-07-16	2012
15539	2012-07-17	2012
15540	2012-07-18	2012
15541	2012-07-19	2012
15542	2012-07-20	2012
15543	2012-07-23	2012
15544	2012-07-24	2012
15545	2012-07-25	2012
15546	2012-07-26	2012
15547	2012-07-27	2012
15548	2012-07-30	2012
15549	2012-07-31	2012
15550	2012-08-01	2012
15551	2012-08-02	2012
15552	2012-08-03	2012
15553	2012-08-06	2012
15554	2012-08-07	2012
15555	2012-08-08	2012
15556	2012-08-09	2012
15557	2012-08-10	2012
15558	2012-08-13	2012
15559	2012-08-14	2012
15560	2012-08-15	2012
15561	2012-08-16	2012
15562	2012-08-17	2012
15563	2012-08-20	2012
15564	2012-08-21	2012
15565	2012-08-22	2012
15566	2012-08-23	2012
15567	2012-08-24	2012
15568	2012-08-27	2012
15569	2012-08-28	2012
15570	2012-08-29	2012
15571	2012-08-30	2012
15572	2012-08-31	2012
15573	2012-09-03	2012
15574	2012-09-04	2012
15575	2012-09-05	2012
15576	2012-09-06	2012
15577	2012-09-10	2012
15578	2012-09-11	2012
15579	2012-09-12	2012
15580	2012-09-13	2012
15581	2012-09-14	2012
15582	2012-09-17	2012
15583	2012-09-18	2012
15584	2012-09-19	2012
15585	2012-09-20	2012
15586	2012-09-21	2012
15587	2012-09-24	2012
15588	2012-09-25	2012
15589	2012-09-26	2012
15590	2012-09-27	2012
15591	2012-09-28	2012
15592	2012-10-01	2012
15593	2012-10-02	2012
15594	2012-10-03	2012
15595	2012-10-04	2012
15596	2012-10-05	2012
15597	2012-10-08	2012
15598	2012-10-09	2012
15599	2012-10-10	2012
15600	2012-10-11	2012
15601	2012-10-15	2012
15602	2012-10-16	2012
15603	2012-10-17	2012
15604	2012-10-18	2012
15605	2012-10-19	2012
15606	2012-10-22	2012
15607	2012-10-23	2012
15608	2012-10-24	2012
15609	2012-10-25	2012
15610	2012-10-26	2012
15611	2012-10-29	2012
15612	2012-10-30	2012
15613	2012-10-31	2012
15614	2012-11-01	2012
15615	2012-11-05	2012
15616	2012-11-06	2012
15617	2012-11-07	2012
15618	2012-11-08	2012
15619	2012-11-09	2012
15620	2012-11-12	2012
15621	2012-11-13	2012
15622	2012-11-14	2012
15623	2012-11-16	2012
15624	2012-11-19	2012
15625	2012-11-20	2012
15626	2012-11-21	2012
15627	2012-11-22	2012
15628	2012-11-23	2012
15629	2012-11-26	2012
15630	2012-11-27	2012
15631	2012-11-28	2012
15632	2012-11-29	2012
15633	2012-11-30	2012
15634	2012-12-03	2012
15635	2012-12-04	2012
15636	2012-12-05	2012
15637	2012-12-06	2012
15638	2012-12-07	2012
15639	2012-12-10	2012
15640	2012-12-11	2012
15641	2012-12-12	2012
15642	2012-12-13	2012
15643	2012-12-14	2012
15644	2012-12-17	2012
15645	2012-12-18	2012
15646	2012-12-19	2012
15647	2012-12-20	2012
15648	2012-12-21	2012
15649	2012-12-24	2012
15650	2012-12-26	2012
15651	2012-12-27	2012
15652	2012-12-28	2012
15653	2012-12-31	2012
15654	2013-01-02	2013
15655	2013-01-03	2013
15656	2013-01-04	2013
15657	2013-01-07	2013
15658	2013-01-08	2013
15659	2013-01-09	2013
15660	2013-01-10	2013
15661	2013-01-11	2013
15662	2013-01-14	2013
15663	2013-01-15	2013
15664	2013-01-16	2013
15665	2013-01-17	2013
15666	2013-01-18	2013
15667	2013-01-21	2013
15668	2013-01-22	2013
15669	2013-01-23	2013
15670	2013-01-24	2013
15671	2013-01-25	2013
15672	2013-01-28	2013
15673	2013-01-29	2013
15674	2013-01-30	2013
15675	2013-01-31	2013
15676	2013-02-01	2013
15677	2013-02-04	2013
15678	2013-02-05	2013
15679	2013-02-06	2013
15680	2013-02-07	2013
15681	2013-02-08	2013
15682	2013-02-11	2013
15683	2013-02-13	2013
15684	2013-02-14	2013
15685	2013-02-15	2013
15686	2013-02-18	2013
15687	2013-02-19	2013
15688	2013-02-20	2013
15689	2013-02-21	2013
15690	2013-02-22	2013
15691	2013-02-25	2013
15692	2013-02-26	2013
15693	2013-02-27	2013
15694	2013-02-28	2013
15695	2013-03-01	2013
15696	2013-03-04	2013
15697	2013-03-05	2013
15698	2013-03-06	2013
15699	2013-03-07	2013
15700	2013-03-08	2013
15701	2013-03-11	2013
15702	2013-03-12	2013
15703	2013-03-13	2013
15704	2013-03-14	2013
15705	2013-03-15	2013
15706	2013-03-18	2013
15707	2013-03-19	2013
15708	2013-03-20	2013
15709	2013-03-21	2013
15710	2013-03-22	2013
15711	2013-03-25	2013
15712	2013-03-26	2013
15713	2013-03-27	2013
15714	2013-03-28	2013
15715	2013-03-29	2013
15716	2013-04-01	2013
15717	2013-04-02	2013
15718	2013-04-03	2013
15719	2013-04-04	2013
15720	2013-04-05	2013
15721	2013-04-08	2013
15722	2013-04-09	2013
15723	2013-04-10	2013
15724	2013-04-11	2013
15725	2013-04-12	2013
15726	2013-04-15	2013
15727	2013-04-16	2013
15728	2013-04-17	2013
15729	2013-04-18	2013
15730	2013-04-19	2013
15731	2013-04-22	2013
15732	2013-04-23	2013
15733	2013-04-24	2013
15734	2013-04-25	2013
15735	2013-04-26	2013
15736	2013-04-29	2013
15737	2013-04-30	2013
15738	2013-05-02	2013
15739	2013-05-03	2013
15740	2013-05-06	2013
15741	2013-05-07	2013
15742	2013-05-08	2013
15743	2013-05-09	2013
15744	2013-05-10	2013
15745	2013-05-13	2013
15746	2013-05-14	2013
15747	2013-05-15	2013
15748	2013-05-16	2013
15749	2013-05-17	2013
15750	2013-05-20	2013
15751	2013-05-21	2013
15752	2013-05-22	2013
15753	2013-05-23	2013
15754	2013-05-24	2013
15755	2013-05-27	2013
15756	2013-05-28	2013
15757	2013-05-29	2013
15758	2013-05-31	2013
15759	2013-06-03	2013
15760	2013-06-04	2013
15761	2013-06-05	2013
15762	2013-06-06	2013
15763	2013-06-07	2013
15764	2013-06-10	2013
15765	2013-06-11	2013
15766	2013-06-12	2013
15767	2013-06-13	2013
15768	2013-06-14	2013
15769	2013-06-17	2013
15770	2013-06-18	2013
15771	2013-06-19	2013
15772	2013-06-20	2013
15773	2013-06-21	2013
15774	2013-06-24	2013
15775	2013-06-25	2013
15776	2013-06-26	2013
15777	2013-06-27	2013
15778	2013-06-28	2013
15779	2013-07-01	2013
15780	2013-07-02	2013
15781	2013-07-03	2013
15782	2013-07-04	2013
15783	2013-07-05	2013
15784	2013-07-08	2013
15785	2013-07-09	2013
15786	2013-07-10	2013
15787	2013-07-11	2013
15788	2013-07-12	2013
15789	2013-07-15	2013
15790	2013-07-16	2013
15791	2013-07-17	2013
15792	2013-07-18	2013
15793	2013-07-19	2013
15794	2013-07-22	2013
15795	2013-07-23	2013
15796	2013-07-24	2013
15797	2013-07-25	2013
15798	2013-07-26	2013
15799	2013-07-29	2013
15800	2013-07-30	2013
15801	2013-07-31	2013
15802	2013-08-01	2013
15803	2013-08-02	2013
15804	2013-08-05	2013
15805	2013-08-06	2013
15806	2013-08-07	2013
15807	2013-08-08	2013
15808	2013-08-09	2013
15809	2013-08-12	2013
15810	2013-08-13	2013
15811	2013-08-14	2013
15812	2013-08-15	2013
15813	2013-08-16	2013
15814	2013-08-19	2013
15815	2013-08-20	2013
15816	2013-08-21	2013
15817	2013-08-22	2013
15818	2013-08-23	2013
15819	2013-08-26	2013
15820	2013-08-27	2013
15821	2013-08-28	2013
15822	2013-08-29	2013
15823	2013-08-30	2013
15824	2013-09-02	2013
15825	2013-09-03	2013
15826	2013-09-04	2013
15827	2013-09-05	2013
15828	2013-09-06	2013
15829	2013-09-09	2013
15830	2013-09-10	2013
15831	2013-09-11	2013
15832	2013-09-12	2013
15833	2013-09-13	2013
15834	2013-09-16	2013
15835	2013-09-17	2013
15836	2013-09-18	2013
15837	2013-09-19	2013
15838	2013-09-20	2013
15839	2013-09-23	2013
15840	2013-09-24	2013
15841	2013-09-25	2013
15842	2013-09-26	2013
15843	2013-09-27	2013
15844	2013-09-30	2013
15845	2013-10-01	2013
15846	2013-10-02	2013
15847	2013-10-03	2013
15848	2013-10-04	2013
15849	2013-10-07	2013
15850	2013-10-08	2013
15851	2013-10-09	2013
15852	2013-10-10	2013
15853	2013-10-11	2013
15854	2013-10-14	2013
15855	2013-10-15	2013
15856	2013-10-16	2013
15857	2013-10-17	2013
15858	2013-10-18	2013
15859	2013-10-21	2013
15860	2013-10-22	2013
15861	2013-10-23	2013
15862	2013-10-24	2013
15863	2013-10-25	2013
15864	2013-10-29	2013
15865	2013-10-30	2013
15866	2013-10-31	2013
15867	2013-11-01	2013
15868	2013-11-04	2013
15869	2013-11-05	2013
15870	2013-11-06	2013
15871	2013-11-07	2013
15872	2013-11-08	2013
15873	2013-11-11	2013
15874	2013-11-12	2013
15875	2013-11-13	2013
15876	2013-11-14	2013
15877	2013-11-18	2013
15878	2013-11-19	2013
15879	2013-11-20	2013
15880	2013-11-21	2013
15881	2013-11-22	2013
15882	2013-11-25	2013
15883	2013-11-26	2013
15884	2013-11-27	2013
15885	2013-11-28	2013
15886	2013-11-29	2013
15887	2013-12-02	2013
15888	2013-12-03	2013
15889	2013-12-04	2013
15890	2013-12-05	2013
15891	2013-12-06	2013
15892	2013-12-09	2013
15893	2013-12-10	2013
15894	2013-12-11	2013
15895	2013-12-12	2013
15896	2013-12-13	2013
15897	2013-12-16	2013
15898	2013-12-17	2013
15899	2013-12-18	2013
15900	2013-12-19	2013
15901	2013-12-20	2013
15902	2013-12-23	2013
15903	2013-12-24	2013
15904	2013-12-26	2013
15905	2013-12-27	2013
15906	2013-12-30	2013
15907	2013-12-31	2013
15908	2014-01-02	2014
15909	2014-01-03	2014
15910	2014-01-06	2014
15911	2014-01-07	2014
15912	2014-01-08	2014
15913	2014-01-09	2014
15914	2014-01-10	2014
15915	2014-01-13	2014
15916	2014-01-14	2014
15917	2014-01-15	2014
15918	2014-01-16	2014
15919	2014-01-17	2014
15920	2014-01-20	2014
15921	2014-01-21	2014
15922	2014-01-22	2014
15923	2014-01-23	2014
15924	2014-01-24	2014
15925	2014-01-27	2014
15926	2014-01-28	2014
15927	2014-01-29	2014
15928	2014-01-30	2014
15929	2014-01-31	2014
15930	2014-02-03	2014
15931	2014-02-04	2014
15932	2014-02-05	2014
15933	2014-02-06	2014
15934	2014-02-07	2014
15935	2014-02-10	2014
15936	2014-02-11	2014
15937	2014-02-12	2014
15938	2014-02-13	2014
15939	2014-02-14	2014
15940	2014-02-17	2014
15941	2014-02-18	2014
15942	2014-02-19	2014
15943	2014-02-20	2014
15944	2014-02-21	2014
15945	2014-02-24	2014
15946	2014-02-25	2014
15947	2014-02-26	2014
15948	2014-02-27	2014
15949	2014-02-28	2014
15950	2014-03-03	2014
15951	2014-03-05	2014
15952	2014-03-06	2014
15953	2014-03-07	2014
15954	2014-03-10	2014
15955	2014-03-11	2014
15956	2014-03-12	2014
15957	2014-03-13	2014
15958	2014-03-14	2014
15959	2014-03-17	2014
15960	2014-03-18	2014
15961	2014-03-19	2014
15962	2014-03-20	2014
15963	2014-03-21	2014
15964	2014-03-24	2014
15965	2014-03-25	2014
15966	2014-03-26	2014
15967	2014-03-27	2014
15968	2014-03-28	2014
15969	2014-03-31	2014
15970	2014-04-01	2014
15971	2014-04-02	2014
15972	2014-04-03	2014
15973	2014-04-04	2014
15974	2014-04-07	2014
15975	2014-04-08	2014
15976	2014-04-09	2014
15977	2014-04-10	2014
15978	2014-04-11	2014
15979	2014-04-14	2014
15980	2014-04-15	2014
15981	2014-04-16	2014
15982	2014-04-17	2014
15983	2014-04-18	2014
15984	2014-04-22	2014
15985	2014-04-23	2014
15986	2014-04-24	2014
15987	2014-04-25	2014
15988	2014-04-28	2014
15989	2014-04-29	2014
15990	2014-04-30	2014
15991	2014-05-02	2014
15992	2014-05-05	2014
15993	2014-05-06	2014
15994	2014-05-07	2014
15995	2014-05-08	2014
15996	2014-05-09	2014
15997	2014-05-12	2014
15998	2014-05-13	2014
15999	2014-05-14	2014
16000	2014-05-15	2014
16001	2014-05-16	2014
16002	2014-05-19	2014
16003	2014-05-20	2014
16004	2014-05-21	2014
16005	2014-05-22	2014
16006	2014-05-23	2014
16007	2014-05-26	2014
16008	2014-05-27	2014
16009	2014-05-28	2014
16010	2014-05-29	2014
16011	2014-05-30	2014
16012	2014-06-02	2014
16013	2014-06-03	2014
16014	2014-06-04	2014
16015	2014-06-05	2014
16016	2014-06-06	2014
16017	2014-06-09	2014
16018	2014-06-10	2014
16019	2014-06-11	2014
16020	2014-06-12	2014
16021	2014-06-13	2014
16022	2014-06-16	2014
16023	2014-06-17	2014
16024	2014-06-18	2014
16025	2014-06-20	2014
16026	2014-06-23	2014
16027	2014-06-24	2014
16028	2014-06-25	2014
16029	2014-06-26	2014
16030	2014-06-27	2014
16031	2014-06-30	2014
16032	2014-07-01	2014
16033	2014-07-02	2014
16034	2014-07-03	2014
16035	2014-07-04	2014
16036	2014-07-07	2014
16037	2014-07-08	2014
16038	2014-07-09	2014
16039	2014-07-10	2014
16040	2014-07-11	2014
16041	2014-07-14	2014
16042	2014-07-15	2014
16043	2014-07-16	2014
16044	2014-07-17	2014
16045	2014-07-18	2014
16046	2014-07-21	2014
16047	2014-07-22	2014
16048	2014-07-23	2014
16049	2014-07-24	2014
16050	2014-07-25	2014
16051	2014-07-28	2014
16052	2014-07-29	2014
16053	2014-07-30	2014
16054	2014-07-31	2014
16055	2014-08-01	2014
16056	2014-08-04	2014
16057	2014-08-05	2014
16058	2014-08-06	2014
16059	2014-08-07	2014
16060	2014-08-08	2014
16061	2014-08-11	2014
16062	2014-08-12	2014
16063	2014-08-13	2014
16064	2014-08-14	2014
16065	2014-08-15	2014
16066	2014-08-18	2014
16067	2014-08-19	2014
16068	2014-08-20	2014
16069	2014-08-21	2014
16070	2014-08-22	2014
16071	2014-08-25	2014
16072	2014-08-26	2014
16073	2014-08-27	2014
16074	2014-08-28	2014
16075	2014-08-29	2014
16076	2014-09-01	2014
16077	2014-09-02	2014
16078	2014-09-03	2014
16079	2014-09-04	2014
16080	2014-09-05	2014
16081	2014-09-08	2014
16082	2014-09-09	2014
16083	2014-09-10	2014
16084	2014-09-11	2014
16085	2014-09-12	2014
16086	2014-09-15	2014
16087	2014-09-16	2014
16088	2014-09-17	2014
16089	2014-09-18	2014
16090	2014-09-19	2014
16091	2014-09-22	2014
16092	2014-09-23	2014
16093	2014-09-24	2014
16094	2014-09-25	2014
16095	2014-09-26	2014
16096	2014-09-29	2014
16097	2014-09-30	2014
16098	2014-10-01	2014
16099	2014-10-02	2014
16100	2014-10-03	2014
16101	2014-10-06	2014
16102	2014-10-07	2014
16103	2014-10-08	2014
16104	2014-10-09	2014
16105	2014-10-10	2014
16106	2014-10-13	2014
16107	2014-10-14	2014
16108	2014-10-15	2014
16109	2014-10-16	2014
16110	2014-10-17	2014
16111	2014-10-20	2014
16112	2014-10-21	2014
16113	2014-10-22	2014
16114	2014-10-23	2014
16115	2014-10-24	2014
16116	2014-10-27	2014
16117	2014-10-29	2014
16118	2014-10-30	2014
16119	2014-10-31	2014
16120	2014-11-03	2014
16121	2014-11-04	2014
16122	2014-11-05	2014
16123	2014-11-06	2014
16124	2014-11-07	2014
16125	2014-11-10	2014
16126	2014-11-11	2014
16127	2014-11-12	2014
16128	2014-11-13	2014
16129	2014-11-14	2014
16130	2014-11-17	2014
16131	2014-11-18	2014
16132	2014-11-19	2014
16133	2014-11-20	2014
16134	2014-11-21	2014
16135	2014-11-24	2014
16136	2014-11-25	2014
16137	2014-11-26	2014
16138	2014-11-27	2014
16139	2014-11-28	2014
16140	2014-12-01	2014
16141	2014-12-02	2014
16142	2014-12-03	2014
16143	2014-12-04	2014
16144	2014-12-05	2014
16145	2014-12-08	2014
16146	2014-12-09	2014
16147	2014-12-10	2014
16148	2014-12-11	2014
16149	2014-12-12	2014
16150	2014-12-15	2014
16151	2014-12-16	2014
16152	2014-12-17	2014
16153	2014-12-18	2014
16154	2014-12-19	2014
16155	2014-12-22	2014
16156	2014-12-23	2014
16157	2014-12-24	2014
16158	2014-12-26	2014
16159	2014-12-29	2014
16160	2014-12-30	2014
16161	2014-12-31	2014
16162	2015-01-02	2015
16163	2015-01-05	2015
16164	2015-01-06	2015
16165	2015-01-07	2015
16166	2015-01-08	2015
16167	2015-01-09	2015
16168	2015-01-12	2015
16169	2015-01-13	2015
16170	2015-01-14	2015
16171	2015-01-15	2015
16172	2015-01-16	2015
16173	2015-01-19	2015
16174	2015-01-20	2015
16175	2015-01-21	2015
16176	2015-01-22	2015
16177	2015-01-23	2015
16178	2015-01-26	2015
16179	2015-01-27	2015
16180	2015-01-28	2015
16181	2015-01-29	2015
16182	2015-01-30	2015
16183	2015-02-02	2015
16184	2015-02-03	2015
16185	2015-02-04	2015
16186	2015-02-05	2015
16187	2015-02-06	2015
16188	2015-02-09	2015
16189	2015-02-10	2015
16190	2015-02-11	2015
16191	2015-02-12	2015
16192	2015-02-13	2015
16193	2015-02-16	2015
16194	2015-02-18	2015
16195	2015-02-19	2015
16196	2015-02-20	2015
16197	2015-02-23	2015
16198	2015-02-24	2015
16199	2015-02-25	2015
16200	2015-02-26	2015
16201	2015-02-27	2015
16202	2015-03-02	2015
16203	2015-03-03	2015
16204	2015-03-04	2015
16205	2015-03-05	2015
16206	2015-03-06	2015
16207	2015-03-09	2015
16208	2015-03-10	2015
16209	2015-03-11	2015
16210	2015-03-12	2015
16211	2015-03-13	2015
16212	2015-03-16	2015
16213	2015-03-17	2015
16214	2015-03-18	2015
16215	2015-03-19	2015
16216	2015-03-20	2015
16217	2015-03-23	2015
16218	2015-03-24	2015
16219	2015-03-25	2015
16220	2015-03-26	2015
16221	2015-03-27	2015
16222	2015-03-30	2015
16223	2015-03-31	2015
16224	2015-04-01	2015
16225	2015-04-02	2015
16226	2015-04-03	2015
16227	2015-04-06	2015
16228	2015-04-07	2015
16229	2015-04-08	2015
16230	2015-04-09	2015
16231	2015-04-10	2015
16232	2015-04-13	2015
16233	2015-04-14	2015
16234	2015-04-15	2015
16235	2015-04-16	2015
16236	2015-04-17	2015
16237	2015-04-20	2015
16238	2015-04-22	2015
16239	2015-04-23	2015
16240	2015-04-24	2015
16241	2015-04-27	2015
16242	2015-04-28	2015
16243	2015-04-29	2015
16244	2015-04-30	2015
16245	2015-05-04	2015
16246	2015-05-05	2015
16247	2015-05-06	2015
16248	2015-05-07	2015
16249	2015-05-08	2015
16250	2015-05-11	2015
16251	2015-05-12	2015
16252	2015-05-13	2015
16253	2015-05-14	2015
16254	2015-05-15	2015
16255	2015-05-18	2015
16256	2015-05-19	2015
16257	2015-05-20	2015
16258	2015-05-21	2015
16259	2015-05-22	2015
16260	2015-05-25	2015
16261	2015-05-26	2015
16262	2015-05-27	2015
16263	2015-05-28	2015
16264	2015-05-29	2015
16265	2015-06-01	2015
16266	2015-06-02	2015
16267	2015-06-03	2015
16268	2015-06-05	2015
16269	2015-06-08	2015
16270	2015-06-09	2015
16271	2015-06-10	2015
16272	2015-06-11	2015
16273	2015-06-12	2015
16274	2015-06-15	2015
16275	2015-06-16	2015
16276	2015-06-17	2015
16277	2015-06-18	2015
16278	2015-06-19	2015
16279	2015-06-22	2015
16280	2015-06-23	2015
16281	2015-06-24	2015
16282	2015-06-25	2015
16283	2015-06-26	2015
16284	2015-06-29	2015
16285	2015-06-30	2015
16286	2015-07-01	2015
16287	2015-07-02	2015
16288	2015-07-03	2015
16289	2015-07-06	2015
16290	2015-07-07	2015
16291	2015-07-08	2015
16292	2015-07-09	2015
16293	2015-07-10	2015
16294	2015-07-13	2015
16295	2015-07-14	2015
16296	2015-07-15	2015
16297	2015-07-16	2015
16298	2015-07-17	2015
16299	2015-07-20	2015
16300	2015-07-21	2015
16301	2015-07-22	2015
16302	2015-07-23	2015
16303	2015-07-24	2015
16304	2015-07-27	2015
16305	2015-07-28	2015
16306	2015-07-29	2015
16307	2015-07-30	2015
16308	2015-07-31	2015
16309	2015-08-03	2015
16310	2015-08-04	2015
16311	2015-08-05	2015
16312	2015-08-06	2015
16313	2015-08-07	2015
16314	2015-08-10	2015
16315	2015-08-11	2015
16316	2015-08-12	2015
16317	2015-08-13	2015
16318	2015-08-14	2015
16319	2015-08-17	2015
16320	2015-08-18	2015
16321	2015-08-19	2015
16322	2015-08-20	2015
16323	2015-08-21	2015
16324	2015-08-24	2015
16325	2015-08-25	2015
16326	2015-08-26	2015
16327	2015-08-27	2015
16328	2015-08-28	2015
16329	2015-08-31	2015
16330	2015-09-01	2015
16331	2015-09-02	2015
16332	2015-09-03	2015
16333	2015-09-04	2015
16334	2015-09-08	2015
16335	2015-09-09	2015
16336	2015-09-10	2015
16337	2015-09-11	2015
16338	2015-09-14	2015
16339	2015-09-15	2015
16340	2015-09-16	2015
16341	2015-09-17	2015
16342	2015-09-18	2015
16343	2015-09-21	2015
16344	2015-09-22	2015
16345	2015-09-23	2015
16346	2015-09-24	2015
16347	2015-09-25	2015
16348	2015-09-28	2015
16349	2015-09-29	2015
16350	2015-09-30	2015
16351	2015-10-01	2015
16352	2015-10-02	2015
16353	2015-10-05	2015
16354	2015-10-06	2015
16355	2015-10-07	2015
16356	2015-10-08	2015
16357	2015-10-09	2015
16358	2015-10-13	2015
16359	2015-10-14	2015
16360	2015-10-15	2015
16361	2015-10-16	2015
16362	2015-10-19	2015
16363	2015-10-20	2015
16364	2015-10-21	2015
16365	2015-10-22	2015
16366	2015-10-23	2015
16367	2015-10-26	2015
16368	2015-10-27	2015
16369	2015-10-29	2015
16370	2015-10-30	2015
16371	2015-11-03	2015
16372	2015-11-04	2015
16373	2015-11-05	2015
16374	2015-11-06	2015
16375	2015-11-09	2015
16376	2015-11-10	2015
16377	2015-11-11	2015
16378	2015-11-12	2015
16379	2015-11-13	2015
16380	2015-11-16	2015
16381	2015-11-17	2015
16382	2015-11-18	2015
16383	2015-11-19	2015
16384	2015-11-20	2015
16385	2015-11-23	2015
16386	2015-11-24	2015
16387	2015-11-25	2015
16388	2015-11-26	2015
16389	2015-11-27	2015
16390	2015-11-30	2015
16391	2015-12-01	2015
16392	2015-12-02	2015
16393	2015-12-03	2015
16394	2015-12-04	2015
16395	2015-12-07	2015
16396	2015-12-08	2015
16397	2015-12-09	2015
16398	2015-12-10	2015
16399	2015-12-11	2015
16400	2015-12-14	2015
16401	2015-12-15	2015
16402	2015-12-16	2015
16403	2015-12-17	2015
16404	2015-12-18	2015
16405	2015-12-21	2015
16406	2015-12-22	2015
16407	2015-12-23	2015
16408	2015-12-24	2015
16409	2015-12-28	2015
16410	2015-12-29	2015
16411	2015-12-30	2015
16412	2015-12-31	2015
16413	2016-01-04	2016
16414	2016-01-05	2016
16415	2016-01-06	2016
16416	2016-01-07	2016
16417	2016-01-08	2016
16418	2016-01-11	2016
16419	2016-01-12	2016
16420	2016-01-13	2016
16421	2016-01-14	2016
16422	2016-01-15	2016
16423	2016-01-18	2016
16424	2016-01-19	2016
16425	2016-01-20	2016
16426	2016-01-21	2016
16427	2016-01-22	2016
16428	2016-01-25	2016
16429	2016-01-26	2016
16430	2016-01-27	2016
16431	2016-01-28	2016
16432	2016-01-29	2016
16433	2016-02-01	2016
16434	2016-02-02	2016
16435	2016-02-03	2016
16436	2016-02-04	2016
16437	2016-02-05	2016
16438	2016-02-08	2016
16439	2016-02-10	2016
16440	2016-02-11	2016
16441	2016-02-12	2016
16442	2016-02-15	2016
16443	2016-02-16	2016
16444	2016-02-17	2016
16445	2016-02-18	2016
16446	2016-02-19	2016
16447	2016-02-22	2016
16448	2016-02-23	2016
16449	2016-02-24	2016
16450	2016-02-25	2016
16451	2016-02-26	2016
16452	2016-02-29	2016
16453	2016-03-01	2016
16454	2016-03-02	2016
16455	2016-03-03	2016
16456	2016-03-04	2016
16457	2016-03-07	2016
16458	2016-03-08	2016
16459	2016-03-09	2016
16460	2016-03-10	2016
16461	2016-03-11	2016
16462	2016-03-14	2016
16463	2016-03-15	2016
16464	2016-03-16	2016
16465	2016-03-17	2016
16466	2016-03-18	2016
16467	2016-03-21	2016
16468	2016-03-22	2016
16469	2016-03-23	2016
16470	2016-03-24	2016
16471	2016-03-25	2016
16472	2016-03-28	2016
16473	2016-03-29	2016
16474	2016-03-30	2016
16475	2016-03-31	2016
16476	2016-04-01	2016
16477	2016-04-04	2016
16478	2016-04-05	2016
16479	2016-04-06	2016
16480	2016-04-07	2016
16481	2016-04-08	2016
16482	2016-04-11	2016
16483	2016-04-12	2016
16484	2016-04-13	2016
16485	2016-04-14	2016
16486	2016-04-15	2016
16487	2016-04-18	2016
16488	2016-04-19	2016
16489	2016-04-20	2016
16490	2016-04-22	2016
16491	2016-04-25	2016
16492	2016-04-26	2016
16493	2016-04-27	2016
16494	2016-04-28	2016
16495	2016-04-29	2016
16496	2016-05-02	2016
16497	2016-05-03	2016
16498	2016-05-04	2016
16499	2016-05-05	2016
16500	2016-05-06	2016
16501	2016-05-09	2016
16502	2016-05-10	2016
16503	2016-05-11	2016
16504	2016-05-12	2016
16505	2016-05-13	2016
16506	2016-05-16	2016
16507	2016-05-17	2016
16508	2016-05-18	2016
16509	2016-05-19	2016
16510	2016-05-20	2016
16511	2016-05-23	2016
16512	2016-05-24	2016
16513	2016-05-25	2016
16514	2016-05-27	2016
16515	2016-05-30	2016
16516	2016-05-31	2016
16517	2016-06-01	2016
16518	2016-06-02	2016
16519	2016-06-03	2016
16520	2016-06-06	2016
16521	2016-06-07	2016
16522	2016-06-08	2016
16523	2016-06-09	2016
16524	2016-06-10	2016
16525	2016-06-13	2016
16526	2016-06-14	2016
16527	2016-06-15	2016
16528	2016-06-16	2016
16529	2016-06-17	2016
16530	2016-06-20	2016
16531	2016-06-21	2016
16532	2016-06-22	2016
16533	2016-06-23	2016
16534	2016-06-24	2016
16535	2016-06-27	2016
16536	2016-06-28	2016
16537	2016-06-29	2016
16538	2016-06-30	2016
16539	2016-07-01	2016
16540	2016-07-04	2016
16541	2016-07-05	2016
16542	2016-07-06	2016
16543	2016-07-07	2016
16544	2016-07-08	2016
16545	2016-07-11	2016
16546	2016-07-12	2016
16547	2016-07-13	2016
16548	2016-07-14	2016
16549	2016-07-15	2016
16550	2016-07-18	2016
16551	2016-07-19	2016
16552	2016-07-20	2016
16553	2016-07-21	2016
16554	2016-07-22	2016
16555	2016-07-25	2016
16556	2016-07-26	2016
16557	2016-07-27	2016
16558	2016-07-28	2016
16559	2016-07-29	2016
16560	2016-08-01	2016
16561	2016-08-02	2016
16562	2016-08-03	2016
16563	2016-08-04	2016
16564	2016-08-05	2016
16565	2016-08-08	2016
16566	2016-08-09	2016
16567	2016-08-10	2016
16568	2016-08-11	2016
16569	2016-08-12	2016
16570	2016-08-15	2016
16571	2016-08-16	2016
16572	2016-08-17	2016
16573	2016-08-18	2016
16574	2016-08-19	2016
16575	2016-08-22	2016
16576	2016-08-23	2016
16577	2016-08-24	2016
16578	2016-08-25	2016
16579	2016-08-26	2016
16580	2016-08-29	2016
16581	2016-08-30	2016
16582	2016-08-31	2016
16583	2016-09-01	2016
16584	2016-09-02	2016
16585	2016-09-05	2016
16586	2016-09-06	2016
16587	2016-09-08	2016
16588	2016-09-09	2016
16589	2016-09-12	2016
16590	2016-09-13	2016
16591	2016-09-14	2016
16592	2016-09-15	2016
16593	2016-09-16	2016
16594	2016-09-19	2016
16595	2016-09-20	2016
16596	2016-09-21	2016
16597	2016-09-22	2016
16598	2016-09-23	2016
16599	2016-09-26	2016
16600	2016-09-27	2016
16601	2016-09-28	2016
16602	2016-09-29	2016
16603	2016-09-30	2016
16604	2016-10-03	2016
16605	2016-10-04	2016
16606	2016-10-05	2016
16607	2016-10-06	2016
16608	2016-10-07	2016
16609	2016-10-10	2016
16610	2016-10-11	2016
16611	2016-10-13	2016
16612	2016-10-14	2016
16613	2016-10-17	2016
16614	2016-10-18	2016
16615	2016-10-19	2016
16616	2016-10-20	2016
16617	2016-10-21	2016
16618	2016-10-24	2016
16619	2016-10-25	2016
16620	2016-10-26	2016
16621	2016-10-27	2016
16622	2016-10-31	2016
16623	2016-11-01	2016
16624	2016-11-03	2016
16625	2016-11-04	2016
16626	2016-11-07	2016
16627	2016-11-08	2016
16628	2016-11-09	2016
16629	2016-11-10	2016
16630	2016-11-11	2016
16631	2016-11-14	2016
16632	2016-11-16	2016
16633	2016-11-17	2016
16634	2016-11-18	2016
16635	2016-11-21	2016
16636	2016-11-22	2016
16637	2016-11-23	2016
16638	2016-11-24	2016
16639	2016-11-25	2016
16640	2016-11-28	2016
16641	2016-11-29	2016
16642	2016-11-30	2016
16643	2016-12-01	2016
16644	2016-12-02	2016
16645	2016-12-05	2016
16646	2016-12-06	2016
16647	2016-12-07	2016
16648	2016-12-08	2016
16649	2016-12-09	2016
16650	2016-12-12	2016
16651	2016-12-13	2016
16652	2016-12-14	2016
16653	2016-12-15	2016
16654	2016-12-16	2016
16655	2016-12-19	2016
16656	2016-12-20	2016
16657	2016-12-21	2016
16658	2016-12-22	2016
16659	2016-12-23	2016
16660	2016-12-26	2016
16661	2016-12-27	2016
16662	2016-12-28	2016
16663	2016-12-29	2016
16664	2016-12-30	2016
16665	2017-01-02	2017
16666	2017-01-03	2017
16667	2017-01-04	2017
16668	2017-01-05	2017
16669	2017-01-06	2017
16670	2017-01-09	2017
16671	2017-01-10	2017
16672	2017-01-11	2017
16673	2017-01-12	2017
16674	2017-01-13	2017
16675	2017-01-16	2017
16676	2017-01-17	2017
16677	2017-01-18	2017
16678	2017-01-19	2017
16679	2017-01-20	2017
16680	2017-01-23	2017
16681	2017-01-24	2017
16682	2017-01-25	2017
16683	2017-01-26	2017
16684	2017-01-27	2017
16685	2017-01-30	2017
16686	2017-01-31	2017
16687	2017-02-01	2017
16688	2017-02-02	2017
16689	2017-02-03	2017
16690	2017-02-06	2017
16691	2017-02-07	2017
16692	2017-02-08	2017
16693	2017-02-09	2017
16694	2017-02-10	2017
16695	2017-02-13	2017
16696	2017-02-14	2017
16697	2017-02-15	2017
16698	2017-02-16	2017
16699	2017-02-17	2017
16700	2017-02-20	2017
16701	2017-02-21	2017
16702	2017-02-22	2017
16703	2017-02-23	2017
16704	2017-02-24	2017
16705	2017-02-27	2017
16706	2017-03-01	2017
16707	2017-03-02	2017
16708	2017-03-03	2017
16709	2017-03-06	2017
16710	2017-03-07	2017
16711	2017-03-08	2017
16712	2017-03-09	2017
16713	2017-03-10	2017
16714	2017-03-13	2017
16715	2017-03-14	2017
16716	2017-03-15	2017
16717	2017-03-16	2017
16718	2017-03-17	2017
16719	2017-03-20	2017
16720	2017-03-21	2017
16721	2017-03-22	2017
16722	2017-03-23	2017
16723	2017-03-24	2017
16724	2017-03-27	2017
16725	2017-03-28	2017
16726	2017-03-29	2017
16727	2017-03-30	2017
16728	2017-03-31	2017
16729	2017-04-03	2017
16730	2017-04-04	2017
16731	2017-04-05	2017
16732	2017-04-06	2017
16733	2017-04-07	2017
16734	2017-04-10	2017
16735	2017-04-11	2017
16736	2017-04-12	2017
16737	2017-04-13	2017
16738	2017-04-14	2017
16739	2017-04-17	2017
16740	2017-04-18	2017
16741	2017-04-19	2017
16742	2017-04-20	2017
16743	2017-04-24	2017
16744	2017-04-25	2017
16745	2017-04-26	2017
16746	2017-04-27	2017
16747	2017-04-28	2017
16748	2017-05-02	2017
16749	2017-05-03	2017
16750	2017-05-04	2017
16751	2017-05-05	2017
16752	2017-05-08	2017
16753	2017-05-09	2017
16754	2017-05-10	2017
16755	2017-05-11	2017
16756	2017-05-12	2017
16757	2017-05-15	2017
16758	2017-05-16	2017
16759	2017-05-17	2017
16760	2017-05-18	2017
16761	2017-05-19	2017
16762	2017-05-22	2017
16763	2017-05-23	2017
16764	2017-05-24	2017
16765	2017-05-25	2017
16766	2017-05-26	2017
16767	2017-05-29	2017
16768	2017-05-30	2017
16769	2017-05-31	2017
16770	2017-06-01	2017
16771	2017-06-02	2017
16772	2017-06-05	2017
16773	2017-06-06	2017
16774	2017-06-07	2017
16775	2017-06-08	2017
16776	2017-06-09	2017
16777	2017-06-12	2017
16778	2017-06-13	2017
16779	2017-06-14	2017
16780	2017-06-16	2017
16781	2017-06-19	2017
16782	2017-06-20	2017
16783	2017-06-21	2017
16784	2017-06-22	2017
16785	2017-06-23	2017
16786	2017-06-26	2017
16787	2017-06-27	2017
16788	2017-06-28	2017
16789	2017-06-29	2017
16790	2017-06-30	2017
16791	2017-07-03	2017
16792	2017-07-04	2017
16793	2017-07-05	2017
16794	2017-07-06	2017
16795	2017-07-07	2017
16796	2017-07-10	2017
16797	2017-07-11	2017
16798	2017-07-12	2017
16799	2017-07-13	2017
16800	2017-07-14	2017
16801	2017-07-17	2017
16802	2017-07-18	2017
16803	2017-07-19	2017
16804	2017-07-20	2017
16805	2017-07-21	2017
16806	2017-07-24	2017
16807	2017-07-25	2017
16808	2017-07-26	2017
16809	2017-07-27	2017
16810	2017-07-28	2017
16811	2017-07-31	2017
16812	2017-08-01	2017
16813	2017-08-02	2017
16814	2017-08-03	2017
16815	2017-08-04	2017
16816	2017-08-07	2017
16817	2017-08-08	2017
16818	2017-08-09	2017
16819	2017-08-10	2017
16820	2017-08-11	2017
16821	2017-08-14	2017
16822	2017-08-15	2017
16823	2017-08-16	2017
16824	2017-08-17	2017
16825	2017-08-18	2017
16826	2017-08-21	2017
16827	2017-08-22	2017
16828	2017-08-23	2017
16829	2017-08-24	2017
16830	2017-08-25	2017
16831	2017-08-28	2017
16832	2017-08-29	2017
16833	2017-08-30	2017
16834	2017-08-31	2017
16835	2017-09-01	2017
16836	2017-09-04	2017
16837	2017-09-05	2017
16838	2017-09-06	2017
16839	2017-09-08	2017
16840	2017-09-11	2017
16841	2017-09-12	2017
16842	2017-09-13	2017
16843	2017-09-14	2017
16844	2017-09-15	2017
16845	2017-09-18	2017
16846	2017-09-19	2017
16847	2017-09-20	2017
16848	2017-09-21	2017
16849	2017-09-22	2017
16850	2017-09-25	2017
16851	2017-09-26	2017
16852	2017-09-27	2017
16853	2017-09-28	2017
16854	2017-09-29	2017
16855	2017-10-02	2017
16856	2017-10-03	2017
16857	2017-10-04	2017
16858	2017-10-05	2017
16859	2017-10-06	2017
16860	2017-10-09	2017
16861	2017-10-10	2017
16862	2017-10-11	2017
16863	2017-10-13	2017
16864	2017-10-16	2017
16865	2017-10-17	2017
16866	2017-10-18	2017
16867	2017-10-19	2017
16868	2017-10-20	2017
16869	2017-10-23	2017
16870	2017-10-24	2017
16871	2017-10-25	2017
16872	2017-10-26	2017
16873	2017-10-27	2017
16874	2017-10-30	2017
16875	2017-10-31	2017
16876	2017-11-01	2017
16877	2017-11-03	2017
16878	2017-11-06	2017
16879	2017-11-07	2017
16880	2017-11-08	2017
16881	2017-11-09	2017
16882	2017-11-10	2017
16883	2017-11-13	2017
16884	2017-11-14	2017
16885	2017-11-16	2017
16886	2017-11-17	2017
16887	2017-11-20	2017
16888	2017-11-21	2017
16889	2017-11-22	2017
16890	2017-11-23	2017
16891	2017-11-24	2017
16892	2017-11-27	2017
16893	2017-11-28	2017
16894	2017-11-29	2017
16895	2017-11-30	2017
16896	2017-12-01	2017
16897	2017-12-04	2017
16898	2017-12-05	2017
16899	2017-12-06	2017
16900	2017-12-07	2017
16901	2017-12-08	2017
16902	2017-12-11	2017
16903	2017-12-12	2017
16904	2017-12-13	2017
16905	2017-12-14	2017
16906	2017-12-15	2017
16907	2017-12-18	2017
16908	2017-12-19	2017
16909	2017-12-20	2017
16910	2017-12-21	2017
16911	2017-12-22	2017
16912	2017-12-26	2017
16913	2017-12-27	2017
16914	2017-12-28	2017
16915	2017-12-29	2017
16916	2018-01-02	2018
16917	2018-01-03	2018
16918	2018-01-04	2018
16919	2018-01-05	2018
16920	2018-01-08	2018
16921	2018-01-09	2018
16922	2018-01-10	2018
16923	2018-01-11	2018
16924	2018-01-12	2018
16925	2018-01-15	2018
16926	2018-01-16	2018
16927	2018-01-17	2018
16928	2018-01-18	2018
16929	2018-01-19	2018
16930	2018-01-22	2018
16931	2018-01-23	2018
16932	2018-01-24	2018
16933	2018-01-25	2018
16934	2018-01-26	2018
16935	2018-01-29	2018
16936	2018-01-30	2018
16937	2018-01-31	2018
16938	2018-02-01	2018
16939	2018-02-02	2018
16940	2018-02-05	2018
16941	2018-02-06	2018
16942	2018-02-07	2018
16943	2018-02-08	2018
16944	2018-02-09	2018
16945	2018-02-12	2018
16946	2018-02-14	2018
16947	2018-02-15	2018
16948	2018-02-16	2018
16949	2018-02-19	2018
16950	2018-02-20	2018
16951	2018-02-21	2018
16952	2018-02-22	2018
16953	2018-02-23	2018
16954	2018-02-26	2018
16955	2018-02-27	2018
16956	2018-02-28	2018
16957	2018-03-01	2018
16958	2018-03-02	2018
16959	2018-03-05	2018
16960	2018-03-06	2018
16961	2018-03-07	2018
16962	2018-03-08	2018
16963	2018-03-09	2018
16964	2018-03-12	2018
16965	2018-03-13	2018
16966	2018-03-14	2018
16967	2018-03-15	2018
16968	2018-03-16	2018
16969	2018-03-19	2018
16970	2018-03-20	2018
16971	2018-03-21	2018
16972	2018-03-22	2018
16973	2018-03-23	2018
16974	2018-03-26	2018
16975	2018-03-27	2018
16976	2018-03-28	2018
16977	2018-03-29	2018
16978	2018-03-30	2018
16979	2018-04-02	2018
16980	2018-04-03	2018
16981	2018-04-04	2018
16982	2018-04-05	2018
16983	2018-04-06	2018
16984	2018-04-09	2018
16985	2018-04-10	2018
16986	2018-04-11	2018
16987	2018-04-12	2018
16988	2018-04-13	2018
16989	2018-04-16	2018
16990	2018-04-17	2018
16991	2018-04-18	2018
16992	2018-04-19	2018
16993	2018-04-20	2018
16994	2018-04-23	2018
16995	2018-04-24	2018
16996	2018-04-25	2018
16997	2018-04-26	2018
16998	2018-04-27	2018
16999	2018-04-30	2018
17000	2018-05-02	2018
17001	2018-05-03	2018
17002	2018-05-04	2018
17003	2018-05-07	2018
17004	2018-05-08	2018
17005	2018-05-09	2018
17006	2018-05-10	2018
17007	2018-05-11	2018
17008	2018-05-14	2018
17009	2018-05-15	2018
17010	2018-05-16	2018
17011	2018-05-17	2018
17012	2018-05-18	2018
17013	2018-05-21	2018
17014	2018-05-22	2018
17015	2018-05-23	2018
17016	2018-05-24	2018
17017	2018-05-25	2018
17018	2018-05-28	2018
17019	2018-05-29	2018
17020	2018-05-30	2018
17021	2018-06-01	2018
17022	2018-06-04	2018
17023	2018-06-05	2018
17024	2018-06-06	2018
17025	2018-06-07	2018
17026	2018-06-08	2018
17027	2018-06-11	2018
17028	2018-06-12	2018
17029	2018-06-13	2018
17030	2018-06-14	2018
17031	2018-06-15	2018
17032	2018-06-18	2018
17033	2018-06-19	2018
17034	2018-06-20	2018
17035	2018-06-21	2018
17036	2018-06-22	2018
17037	2018-06-25	2018
17038	2018-06-26	2018
17039	2018-06-27	2018
17040	2018-06-28	2018
17041	2018-06-29	2018
17042	2018-07-02	2018
17043	2018-07-03	2018
17044	2018-07-04	2018
17045	2018-07-05	2018
17046	2018-07-06	2018
17047	2018-07-09	2018
17048	2018-07-10	2018
17049	2018-07-11	2018
17050	2018-07-12	2018
17051	2018-07-13	2018
17052	2018-07-16	2018
17053	2018-07-17	2018
17054	2018-07-18	2018
17055	2018-07-19	2018
17056	2018-07-20	2018
17057	2018-07-23	2018
17058	2018-07-24	2018
17059	2018-07-25	2018
17060	2018-07-26	2018
17061	2018-07-27	2018
17062	2018-07-30	2018
17063	2018-07-31	2018
17064	2018-08-01	2018
17065	2018-08-02	2018
17066	2018-08-03	2018
17067	2018-08-06	2018
17068	2018-08-07	2018
17069	2018-08-08	2018
17070	2018-08-09	2018
17071	2018-08-10	2018
17072	2018-08-13	2018
17073	2018-08-14	2018
17074	2018-08-15	2018
17075	2018-08-16	2018
17076	2018-08-17	2018
17077	2018-08-20	2018
17078	2018-08-21	2018
17079	2018-08-22	2018
17080	2018-08-23	2018
17081	2018-08-24	2018
17082	2018-08-27	2018
17083	2018-08-28	2018
17084	2018-08-29	2018
17085	2018-08-30	2018
17086	2018-08-31	2018
17087	2018-09-03	2018
17088	2018-09-04	2018
17089	2018-09-05	2018
17090	2018-09-06	2018
17091	2018-09-10	2018
17092	2018-09-11	2018
17093	2018-09-12	2018
17094	2018-09-13	2018
17095	2018-09-14	2018
17096	2018-09-17	2018
17097	2018-09-18	2018
17098	2018-09-19	2018
17099	2018-09-20	2018
17100	2018-09-21	2018
17101	2018-09-24	2018
17102	2018-09-25	2018
17103	2018-09-26	2018
17104	2018-09-27	2018
17105	2018-09-28	2018
17106	2018-10-01	2018
17107	2018-10-02	2018
17108	2018-10-03	2018
17109	2018-10-04	2018
17110	2018-10-05	2018
17111	2018-10-08	2018
17112	2018-10-09	2018
17113	2018-10-10	2018
17114	2018-10-11	2018
17115	2018-10-15	2018
17116	2018-10-16	2018
17117	2018-10-17	2018
17118	2018-10-18	2018
17119	2018-10-19	2018
17120	2018-10-22	2018
17121	2018-10-23	2018
17122	2018-10-24	2018
17123	2018-10-25	2018
17124	2018-10-26	2018
17125	2018-10-29	2018
17126	2018-10-30	2018
17127	2018-10-31	2018
17128	2018-11-01	2018
17129	2018-11-05	2018
17130	2018-11-06	2018
17131	2018-11-07	2018
17132	2018-11-08	2018
17133	2018-11-09	2018
17134	2018-11-12	2018
17135	2018-11-13	2018
17136	2018-11-14	2018
17137	2018-11-16	2018
17138	2018-11-19	2018
17139	2018-11-20	2018
17140	2018-11-21	2018
17141	2018-11-22	2018
17142	2018-11-23	2018
17143	2018-11-26	2018
17144	2018-11-27	2018
17145	2018-11-28	2018
17146	2018-11-29	2018
17147	2018-11-30	2018
17148	2018-12-03	2018
17149	2018-12-04	2018
17150	2018-12-05	2018
17151	2018-12-06	2018
17152	2018-12-07	2018
17153	2018-12-10	2018
17154	2018-12-11	2018
17155	2018-12-12	2018
17156	2018-12-13	2018
17157	2018-12-14	2018
17158	2018-12-17	2018
17159	2018-12-18	2018
17160	2018-12-19	2018
17161	2018-12-20	2018
17162	2018-12-21	2018
17163	2018-12-24	2018
17164	2018-12-26	2018
17165	2018-12-27	2018
17166	2018-12-28	2018
17167	2018-12-31	2018
17168	2019-01-02	2019
17169	2019-01-03	2019
17170	2019-01-04	2019
17171	2019-01-07	2019
17172	2019-01-08	2019
17173	2019-01-09	2019
17174	2019-01-10	2019
17175	2019-01-11	2019
17176	2019-01-14	2019
17177	2019-01-15	2019
17178	2019-01-16	2019
17179	2019-01-17	2019
17180	2019-01-18	2019
17181	2019-01-21	2019
17182	2019-01-22	2019
17183	2019-01-23	2019
17184	2019-01-24	2019
17185	2019-01-25	2019
17186	2019-01-28	2019
17187	2019-01-29	2019
17188	2019-01-30	2019
17189	2019-01-31	2019
17190	2019-02-01	2019
17191	2019-02-04	2019
17192	2019-02-05	2019
17193	2019-02-06	2019
17194	2019-02-07	2019
17195	2019-02-08	2019
17196	2019-02-11	2019
17197	2019-02-12	2019
17198	2019-02-13	2019
17199	2019-02-14	2019
17200	2019-02-15	2019
17201	2019-02-18	2019
17202	2019-02-19	2019
17203	2019-02-20	2019
17204	2019-02-21	2019
17205	2019-02-22	2019
17206	2019-02-25	2019
17207	2019-02-26	2019
17208	2019-02-27	2019
17209	2019-02-28	2019
17210	2019-03-01	2019
17211	2019-03-04	2019
17212	2019-03-06	2019
17213	2019-03-07	2019
17214	2019-03-08	2019
17215	2019-03-11	2019
17216	2019-03-12	2019
17217	2019-03-13	2019
17218	2019-03-14	2019
17219	2019-03-15	2019
17220	2019-03-18	2019
17221	2019-03-19	2019
17222	2019-03-20	2019
17223	2019-03-21	2019
17224	2019-03-22	2019
17225	2019-03-25	2019
17226	2019-03-26	2019
17227	2019-03-27	2019
17228	2019-03-28	2019
17229	2019-03-29	2019
17230	2019-04-01	2019
17231	2019-04-02	2019
17232	2019-04-03	2019
17233	2019-04-04	2019
17234	2019-04-05	2019
17235	2019-04-08	2019
17236	2019-04-09	2019
17237	2019-04-10	2019
17238	2019-04-11	2019
17239	2019-04-12	2019
17240	2019-04-15	2019
17241	2019-04-16	2019
17242	2019-04-17	2019
17243	2019-04-18	2019
17244	2019-04-19	2019
17245	2019-04-22	2019
17246	2019-04-23	2019
17247	2019-04-24	2019
17248	2019-04-25	2019
17249	2019-04-26	2019
17250	2019-04-29	2019
17251	2019-04-30	2019
17252	2019-05-02	2019
17253	2019-05-03	2019
17254	2019-05-06	2019
17255	2019-05-07	2019
17256	2019-05-08	2019
17257	2019-05-09	2019
17258	2019-05-10	2019
17259	2019-05-13	2019
17260	2019-05-14	2019
17261	2019-05-15	2019
17262	2019-05-16	2019
17263	2019-05-17	2019
17264	2019-05-20	2019
17265	2019-05-21	2019
17266	2019-05-22	2019
17267	2019-05-23	2019
17268	2019-05-24	2019
17269	2019-05-27	2019
17270	2019-05-28	2019
17271	2019-05-29	2019
17272	2019-05-30	2019
17273	2019-05-31	2019
17274	2019-06-03	2019
17275	2019-06-04	2019
17276	2019-06-05	2019
17277	2019-06-06	2019
17278	2019-06-07	2019
17279	2019-06-10	2019
17280	2019-06-11	2019
17281	2019-06-12	2019
17282	2019-06-13	2019
17283	2019-06-14	2019
17284	2019-06-17	2019
17285	2019-06-18	2019
17286	2019-06-19	2019
17287	2019-06-21	2019
17288	2019-06-24	2019
17289	2019-06-25	2019
17290	2019-06-26	2019
17291	2019-06-27	2019
17292	2019-06-28	2019
17293	2019-07-01	2019
17294	2019-07-02	2019
17295	2019-07-03	2019
17296	2019-07-04	2019
17297	2019-07-05	2019
17298	2019-07-08	2019
17299	2019-07-09	2019
17300	2019-07-10	2019
17301	2019-07-11	2019
17302	2019-07-12	2019
17303	2019-07-15	2019
17304	2019-07-16	2019
17305	2019-07-17	2019
17306	2019-07-18	2019
17307	2019-07-19	2019
17308	2019-07-22	2019
17309	2019-07-23	2019
17310	2019-07-24	2019
17311	2019-07-25	2019
17312	2019-07-26	2019
17313	2019-07-29	2019
17314	2019-07-30	2019
17315	2019-07-31	2019
17316	2019-08-01	2019
17317	2019-08-02	2019
17318	2019-08-05	2019
17319	2019-08-06	2019
17320	2019-08-07	2019
17321	2019-08-08	2019
17322	2019-08-09	2019
17323	2019-08-12	2019
17324	2019-08-13	2019
17325	2019-08-14	2019
17326	2019-08-15	2019
17327	2019-08-16	2019
17328	2019-08-19	2019
17329	2019-08-20	2019
17330	2019-08-21	2019
17331	2019-08-22	2019
17332	2019-08-23	2019
17333	2019-08-26	2019
17334	2019-08-27	2019
17335	2019-08-28	2019
17336	2019-08-29	2019
17337	2019-08-30	2019
17338	2019-09-02	2019
17339	2019-09-03	2019
17340	2019-09-04	2019
17341	2019-09-05	2019
17342	2019-09-06	2019
17343	2019-09-09	2019
17344	2019-09-10	2019
17345	2019-09-11	2019
17346	2019-09-12	2019
17347	2019-09-13	2019
17348	2019-09-16	2019
17349	2019-09-17	2019
17350	2019-09-18	2019
17351	2019-09-19	2019
17352	2019-09-20	2019
17353	2019-09-23	2019
17354	2019-09-24	2019
17355	2019-09-25	2019
17356	2019-09-26	2019
17357	2019-09-27	2019
17358	2019-09-30	2019
17359	2019-10-01	2019
17360	2019-10-02	2019
17361	2019-10-03	2019
17362	2019-10-04	2019
17363	2019-10-07	2019
17364	2019-10-08	2019
17365	2019-10-09	2019
17366	2019-10-10	2019
17367	2019-10-11	2019
17368	2019-10-14	2019
17369	2019-10-15	2019
17370	2019-10-16	2019
17371	2019-10-17	2019
17372	2019-10-18	2019
17373	2019-10-21	2019
17374	2019-10-22	2019
17375	2019-10-23	2019
17376	2019-10-24	2019
17377	2019-10-25	2019
17378	2019-10-29	2019
17379	2019-10-30	2019
17380	2019-10-31	2019
17381	2019-11-01	2019
17382	2019-11-04	2019
17383	2019-11-05	2019
17384	2019-11-06	2019
17385	2019-11-07	2019
17386	2019-11-08	2019
17387	2019-11-11	2019
17388	2019-11-12	2019
17389	2019-11-13	2019
17390	2019-11-14	2019
17391	2019-11-18	2019
17392	2019-11-19	2019
17393	2019-11-20	2019
17394	2019-11-21	2019
17395	2019-11-22	2019
17396	2019-11-25	2019
17397	2019-11-26	2019
17398	2019-11-27	2019
17399	2019-11-28	2019
17400	2019-11-29	2019
17401	2019-12-02	2019
17402	2019-12-03	2019
17403	2019-12-04	2019
17404	2019-12-05	2019
17405	2019-12-06	2019
17406	2019-12-09	2019
17407	2019-12-10	2019
17408	2019-12-11	2019
17409	2019-12-12	2019
17410	2019-12-13	2019
17411	2019-12-16	2019
17412	2019-12-17	2019
17413	2019-12-18	2019
17414	2019-12-19	2019
17415	2019-12-20	2019
17416	2019-12-23	2019
17417	2019-12-24	2019
17418	2019-12-26	2019
17419	2019-12-27	2019
17420	2019-12-30	2019
17421	2019-12-31	2019
17422	2020-01-02	2020
17423	2020-01-03	2020
17424	2020-01-06	2020
17425	2020-01-07	2020
17426	2020-01-08	2020
17427	2020-01-09	2020
17428	2020-01-10	2020
17429	2020-01-13	2020
17430	2020-01-14	2020
17431	2020-01-15	2020
17432	2020-01-16	2020
17433	2020-01-17	2020
17434	2020-01-20	2020
17435	2020-01-21	2020
17436	2020-01-22	2020
17437	2020-01-23	2020
17438	2020-01-24	2020
17439	2020-01-27	2020
17440	2020-01-28	2020
17441	2020-01-29	2020
17442	2020-01-30	2020
17443	2020-01-31	2020
17444	2020-02-03	2020
17445	2020-02-04	2020
17446	2020-02-05	2020
17447	2020-02-06	2020
17448	2020-02-07	2020
17449	2020-02-10	2020
17450	2020-02-11	2020
17451	2020-02-12	2020
17452	2020-02-13	2020
17453	2020-02-14	2020
17454	2020-02-17	2020
17455	2020-02-18	2020
17456	2020-02-19	2020
17457	2020-02-20	2020
17458	2020-02-21	2020
17459	2020-02-24	2020
17460	2020-02-26	2020
17461	2020-02-27	2020
17462	2020-02-28	2020
17463	2020-03-02	2020
17464	2020-03-03	2020
17465	2020-03-04	2020
17466	2020-03-05	2020
17467	2020-03-06	2020
17468	2020-03-09	2020
17469	2020-03-10	2020
17470	2020-03-11	2020
17471	2020-03-12	2020
17472	2020-03-13	2020
17473	2020-03-16	2020
17474	2020-03-17	2020
17475	2020-03-18	2020
17476	2020-03-19	2020
17477	2020-03-20	2020
17478	2020-03-23	2020
17479	2020-03-24	2020
17480	2020-03-25	2020
17481	2020-03-26	2020
17482	2020-03-27	2020
17483	2020-03-30	2020
17484	2020-03-31	2020
17485	2020-04-01	2020
17486	2020-04-02	2020
17487	2020-04-03	2020
17488	2020-04-06	2020
17489	2020-04-07	2020
17490	2020-04-08	2020
17491	2020-04-09	2020
17492	2020-04-10	2020
17493	2020-04-13	2020
17494	2020-04-14	2020
17495	2020-04-15	2020
17496	2020-04-16	2020
17497	2020-04-17	2020
17498	2020-04-20	2020
17499	2020-04-22	2020
17500	2020-04-23	2020
17501	2020-04-24	2020
17502	2020-04-27	2020
17503	2020-04-28	2020
17504	2020-04-29	2020
17505	2020-04-30	2020
17506	2020-05-04	2020
17507	2020-05-05	2020
17508	2020-05-06	2020
17509	2020-05-07	2020
17510	2020-05-08	2020
17511	2020-05-11	2020
17512	2020-05-12	2020
17513	2020-05-13	2020
17514	2020-05-14	2020
17515	2020-05-15	2020
17516	2020-05-18	2020
17517	2020-05-19	2020
17518	2020-05-20	2020
17519	2020-05-21	2020
17520	2020-05-22	2020
17521	2020-05-25	2020
17522	2020-05-26	2020
17523	2020-05-27	2020
17524	2020-05-28	2020
17525	2020-05-29	2020
17526	2020-06-01	2020
17527	2020-06-02	2020
17528	2020-06-03	2020
17529	2020-06-04	2020
17530	2020-06-05	2020
17531	2020-06-08	2020
17532	2020-06-09	2020
17533	2020-06-10	2020
17534	2020-06-12	2020
17535	2020-06-15	2020
17536	2020-06-16	2020
17537	2020-06-17	2020
17538	2020-06-18	2020
17539	2020-06-19	2020
17540	2020-06-22	2020
17541	2020-06-23	2020
17542	2020-06-24	2020
17543	2020-06-25	2020
17544	2020-06-26	2020
17545	2020-06-29	2020
17546	2020-06-30	2020
17547	2020-07-01	2020
17548	2020-07-02	2020
17549	2020-07-03	2020
17550	2020-07-06	2020
17551	2020-07-07	2020
17552	2020-07-08	2020
17553	2020-07-09	2020
17554	2020-07-10	2020
17555	2020-07-13	2020
17556	2020-07-14	2020
17557	2020-07-15	2020
17558	2020-07-16	2020
17559	2020-07-17	2020
17560	2020-07-20	2020
17561	2020-07-21	2020
17562	2020-07-22	2020
17563	2020-07-23	2020
17564	2020-07-24	2020
17565	2020-07-27	2020
17566	2020-07-28	2020
17567	2020-07-29	2020
17568	2020-07-30	2020
17569	2020-07-31	2020
17570	2020-08-03	2020
17571	2020-08-04	2020
17572	2020-08-05	2020
17573	2020-08-06	2020
17574	2020-08-07	2020
17575	2020-08-10	2020
17576	2020-08-11	2020
17577	2020-08-12	2020
17578	2020-08-13	2020
17579	2020-08-14	2020
17580	2020-08-17	2020
17581	2020-08-18	2020
17582	2020-08-19	2020
17583	2020-08-20	2020
17584	2020-08-21	2020
17585	2020-08-24	2020
17586	2020-08-25	2020
17587	2020-08-26	2020
17588	2020-08-27	2020
17589	2020-08-28	2020
17590	2020-08-31	2020
17591	2020-09-01	2020
17592	2020-09-02	2020
17593	2020-09-03	2020
17594	2020-09-04	2020
17595	2020-09-08	2020
17596	2020-09-09	2020
17597	2020-09-10	2020
17598	2020-09-11	2020
17599	2020-09-14	2020
17600	2020-09-15	2020
17601	2020-09-16	2020
17602	2020-09-17	2020
17603	2020-09-18	2020
17604	2020-09-21	2020
17605	2020-09-22	2020
17606	2020-09-23	2020
17607	2020-09-24	2020
17608	2020-09-25	2020
17609	2020-09-28	2020
17610	2020-09-29	2020
17611	2020-09-30	2020
17612	2020-10-01	2020
17613	2020-10-02	2020
17614	2020-10-05	2020
17615	2020-10-06	2020
17616	2020-10-07	2020
17617	2020-10-08	2020
17618	2020-10-09	2020
17619	2020-10-13	2020
17620	2020-10-14	2020
17621	2020-10-15	2020
17622	2020-10-16	2020
17623	2020-10-19	2020
17624	2020-10-20	2020
17625	2020-10-21	2020
17626	2020-10-22	2020
17627	2020-10-23	2020
17628	2020-10-26	2020
17629	2020-10-27	2020
17630	2020-10-29	2020
17631	2020-10-30	2020
17632	2020-11-03	2020
17633	2020-11-04	2020
17634	2020-11-05	2020
17635	2020-11-06	2020
17636	2020-11-09	2020
17637	2020-11-10	2020
17638	2020-11-11	2020
17639	2020-11-12	2020
17640	2020-11-13	2020
17641	2020-11-16	2020
17642	2020-11-17	2020
17643	2020-11-18	2020
17644	2020-11-19	2020
17645	2020-11-20	2020
17646	2020-11-23	2020
17647	2020-11-24	2020
17648	2020-11-25	2020
17649	2020-11-26	2020
17650	2020-11-27	2020
17651	2020-11-30	2020
17652	2020-12-01	2020
17653	2020-12-02	2020
17654	2020-12-03	2020
17655	2020-12-04	2020
17656	2020-12-07	2020
17657	2020-12-08	2020
17658	2020-12-09	2020
17659	2020-12-10	2020
17660	2020-12-11	2020
17661	2020-12-14	2020
17662	2020-12-15	2020
17663	2020-12-16	2020
17664	2020-12-17	2020
17665	2020-12-18	2020
17666	2020-12-21	2020
17667	2020-12-22	2020
17668	2020-12-23	2020
17669	2020-12-24	2020
17670	2020-12-28	2020
17671	2020-12-29	2020
17672	2020-12-30	2020
17673	2020-12-31	2020
17674	2021-01-04	2021
17675	2021-01-05	2021
17676	2021-01-06	2021
17677	2021-01-07	2021
17678	2021-01-08	2021
17679	2021-01-11	2021
17680	2021-01-12	2021
17681	2021-01-13	2021
17682	2021-01-14	2021
17683	2021-01-15	2021
17684	2021-01-18	2021
17685	2021-01-19	2021
17686	2021-01-20	2021
17687	2021-01-21	2021
17688	2021-01-22	2021
17689	2021-01-25	2021
17690	2021-01-26	2021
17691	2021-01-27	2021
17692	2021-01-28	2021
17693	2021-01-29	2021
17694	2021-02-01	2021
17695	2021-02-02	2021
17696	2021-02-03	2021
17697	2021-02-04	2021
17698	2021-02-05	2021
17699	2021-02-08	2021
17700	2021-02-09	2021
17701	2021-02-10	2021
17702	2021-02-11	2021
17703	2021-02-12	2021
17704	2021-02-15	2021
17705	2021-02-17	2021
17706	2021-02-18	2021
17707	2021-02-19	2021
17708	2021-02-22	2021
17709	2021-02-23	2021
17710	2021-02-24	2021
17711	2021-02-25	2021
17712	2021-02-26	2021
17713	2021-03-01	2021
17714	2021-03-02	2021
17715	2021-03-03	2021
17716	2021-03-04	2021
17717	2021-03-05	2021
17718	2021-03-08	2021
17719	2021-03-09	2021
17720	2021-03-10	2021
17721	2021-03-11	2021
17722	2021-03-12	2021
17723	2021-03-15	2021
17724	2021-03-16	2021
17725	2021-03-17	2021
17726	2021-03-18	2021
17727	2021-03-19	2021
17728	2021-03-22	2021
17729	2021-03-23	2021
17730	2021-03-24	2021
17731	2021-03-25	2021
17732	2021-03-26	2021
17733	2021-03-29	2021
17734	2021-03-30	2021
17735	2021-03-31	2021
17736	2021-04-01	2021
17737	2021-04-02	2021
17738	2021-04-05	2021
17739	2021-04-06	2021
17740	2021-04-07	2021
17741	2021-04-08	2021
17742	2021-04-09	2021
17743	2021-04-12	2021
17744	2021-04-13	2021
17745	2021-04-14	2021
17746	2021-04-15	2021
17747	2021-04-16	2021
17748	2021-04-19	2021
17749	2021-04-20	2021
17750	2021-04-22	2021
17751	2021-04-23	2021
17752	2021-04-26	2021
17753	2021-04-27	2021
17754	2021-04-28	2021
17755	2021-04-29	2021
17756	2021-04-30	2021
17757	2021-05-03	2021
17758	2021-05-04	2021
17759	2021-05-05	2021
17760	2021-05-06	2021
17761	2021-05-07	2021
17762	2021-05-10	2021
17763	2021-05-11	2021
17764	2021-05-12	2021
17765	2021-05-13	2021
17766	2021-05-14	2021
17767	2021-05-17	2021
17768	2021-05-18	2021
17769	2021-05-19	2021
17770	2021-05-20	2021
17771	2021-05-21	2021
17772	2021-05-24	2021
17773	2021-05-25	2021
17774	2021-05-26	2021
17775	2021-05-27	2021
17776	2021-05-28	2021
17777	2021-05-31	2021
17778	2021-06-01	2021
17779	2021-06-02	2021
17780	2021-06-04	2021
17781	2021-06-07	2021
17782	2021-06-08	2021
17783	2021-06-09	2021
17784	2021-06-10	2021
17785	2021-06-11	2021
17786	2021-06-14	2021
17787	2021-06-15	2021
17788	2021-06-16	2021
17789	2021-06-17	2021
17790	2021-06-18	2021
17791	2021-06-21	2021
17792	2021-06-22	2021
17793	2021-06-23	2021
17794	2021-06-24	2021
17795	2021-06-25	2021
17796	2021-06-28	2021
17797	2021-06-29	2021
17798	2021-06-30	2021
17799	2021-07-01	2021
17800	2021-07-02	2021
17801	2021-07-05	2021
17802	2021-07-06	2021
17803	2021-07-07	2021
17804	2021-07-08	2021
17805	2021-07-09	2021
17806	2021-07-12	2021
17807	2021-07-13	2021
17808	2021-07-14	2021
17809	2021-07-15	2021
17810	2021-07-16	2021
17811	2021-07-19	2021
17812	2021-07-20	2021
17813	2021-07-21	2021
17814	2021-07-22	2021
17815	2021-07-23	2021
17816	2021-07-26	2021
17817	2021-07-27	2021
17818	2021-07-28	2021
17819	2021-07-29	2021
17820	2021-07-30	2021
17821	2021-08-02	2021
17822	2021-08-03	2021
17823	2021-08-04	2021
17824	2021-08-05	2021
17825	2021-08-06	2021
17826	2021-08-09	2021
17827	2021-08-10	2021
17828	2021-08-11	2021
17829	2021-08-12	2021
17830	2021-08-13	2021
17831	2021-08-16	2021
17832	2021-08-17	2021
17833	2021-08-18	2021
17834	2021-08-19	2021
17835	2021-08-20	2021
17836	2021-08-23	2021
17837	2021-08-24	2021
17838	2021-08-25	2021
17839	2021-08-26	2021
17840	2021-08-27	2021
17841	2021-08-30	2021
17842	2021-08-31	2021
17843	2021-09-01	2021
17844	2021-09-02	2021
17845	2021-09-03	2021
17846	2021-09-06	2021
17847	2021-09-08	2021
17848	2021-09-09	2021
17849	2021-09-10	2021
17850	2021-09-13	2021
17851	2021-09-14	2021
17852	2021-09-15	2021
17853	2021-09-16	2021
17854	2021-09-17	2021
17855	2021-09-20	2021
17856	2021-09-21	2021
17857	2021-09-22	2021
17858	2021-09-23	2021
17859	2021-09-24	2021
17860	2021-09-27	2021
17861	2021-09-28	2021
17862	2021-09-29	2021
17863	2021-09-30	2021
17864	2021-10-01	2021
17865	2021-10-04	2021
17866	2021-10-05	2021
17867	2021-10-06	2021
17868	2021-10-07	2021
17869	2021-10-08	2021
17870	2021-10-11	2021
17871	2021-10-13	2021
17872	2021-10-14	2021
17873	2021-10-15	2021
17874	2021-10-18	2021
17875	2021-10-19	2021
17876	2021-10-20	2021
17877	2021-10-21	2021
17878	2021-10-22	2021
17879	2021-10-25	2021
17880	2021-10-26	2021
17881	2021-10-27	2021
17882	2021-10-29	2021
17883	2021-11-01	2021
17884	2021-11-03	2021
17885	2021-11-04	2021
17886	2021-11-05	2021
17887	2021-11-08	2021
17888	2021-11-09	2021
17889	2021-11-10	2021
17890	2021-11-11	2021
17891	2021-11-12	2021
17892	2021-11-16	2021
17893	2021-11-17	2021
17894	2021-11-18	2021
17895	2021-11-19	2021
17896	2021-11-22	2021
17897	2021-11-23	2021
17898	2021-11-24	2021
17899	2021-11-25	2021
17900	2021-11-26	2021
17901	2021-11-29	2021
17902	2021-11-30	2021
17903	2021-12-01	2021
17904	2021-12-02	2021
17905	2021-12-03	2021
17906	2021-12-06	2021
17907	2021-12-07	2021
17908	2021-12-08	2021
17909	2021-12-09	2021
17910	2021-12-10	2021
17911	2021-12-13	2021
17912	2021-12-14	2021
17913	2021-12-15	2021
17914	2021-12-16	2021
17915	2021-12-17	2021
17916	2021-12-20	2021
17917	2021-12-21	2021
17918	2021-12-22	2021
17919	2021-12-23	2021
17920	2021-12-24	2021
17921	2021-12-27	2021
17922	2021-12-28	2021
17923	2021-12-29	2021
17924	2021-12-30	2021
17925	2021-12-31	2021
17926	2022-01-03	2022
17927	2022-01-04	2022
17928	2022-01-05	2022
17929	2022-01-06	2022
17930	2022-01-07	2022
17931	2022-01-10	2022
17932	2022-01-11	2022
17933	2022-01-12	2022
17934	2022-01-13	2022
17935	2022-01-14	2022
17936	2022-01-17	2022
17937	2022-01-18	2022
17938	2022-01-19	2022
17939	2022-01-20	2022
17940	2022-01-21	2022
17941	2022-01-24	2022
17942	2022-01-25	2022
17943	2022-01-26	2022
17944	2022-01-27	2022
17945	2022-01-28	2022
17946	2022-01-31	2022
17947	2022-02-01	2022
17948	2022-02-02	2022
17949	2022-02-03	2022
17950	2022-02-04	2022
17951	2022-02-07	2022
17952	2022-02-08	2022
17953	2022-02-09	2022
17954	2022-02-10	2022
17955	2022-02-11	2022
17956	2022-02-14	2022
17957	2022-02-15	2022
17958	2022-02-16	2022
17959	2022-02-17	2022
17960	2022-02-18	2022
17961	2022-02-21	2022
17962	2022-02-22	2022
17963	2022-02-23	2022
17964	2022-02-24	2022
17965	2022-02-25	2022
17966	2022-02-28	2022
17967	2022-03-02	2022
17968	2022-03-03	2022
17969	2022-03-04	2022
17970	2022-03-07	2022
17971	2022-03-08	2022
17972	2022-03-09	2022
17973	2022-03-10	2022
17974	2022-03-11	2022
17975	2022-03-14	2022
17976	2022-03-15	2022
17977	2022-03-16	2022
17978	2022-03-17	2022
17979	2022-03-18	2022
17980	2022-03-21	2022
17981	2022-03-22	2022
17982	2022-03-23	2022
17983	2022-03-24	2022
17984	2022-03-25	2022
17985	2022-03-28	2022
17986	2022-03-29	2022
17987	2022-03-30	2022
17988	2022-03-31	2022
17989	2022-04-01	2022
17990	2022-04-04	2022
17991	2022-04-05	2022
17992	2022-04-06	2022
17993	2022-04-07	2022
17994	2022-04-08	2022
17995	2022-04-11	2022
17996	2022-04-12	2022
17997	2022-04-13	2022
17998	2022-04-14	2022
17999	2022-04-15	2022
18000	2022-04-18	2022
18001	2022-04-19	2022
18002	2022-04-20	2022
18003	2022-04-22	2022
18004	2022-04-25	2022
18005	2022-04-26	2022
18006	2022-04-27	2022
18007	2022-04-28	2022
18008	2022-04-29	2022
18009	2022-05-02	2022
18010	2022-05-03	2022
18011	2022-05-04	2022
18012	2022-05-05	2022
18013	2022-05-06	2022
18014	2022-05-09	2022
18015	2022-05-10	2022
18016	2022-05-11	2022
18017	2022-05-12	2022
18018	2022-05-13	2022
18019	2022-05-16	2022
18020	2022-05-17	2022
18021	2022-05-18	2022
18022	2022-05-19	2022
18023	2022-05-20	2022
18024	2022-05-23	2022
18025	2022-05-24	2022
18026	2022-05-25	2022
18027	2022-05-26	2022
18028	2022-05-27	2022
18029	2022-05-30	2022
18030	2022-05-31	2022
18031	2022-06-01	2022
18032	2022-06-02	2022
18033	2022-06-03	2022
18034	2022-06-06	2022
18035	2022-06-07	2022
18036	2022-06-08	2022
18037	2022-06-09	2022
18038	2022-06-10	2022
18039	2022-06-13	2022
18040	2022-06-14	2022
18041	2022-06-15	2022
18042	2022-06-17	2022
18043	2022-06-20	2022
18044	2022-06-21	2022
18045	2022-06-22	2022
18046	2022-06-23	2022
18047	2022-06-24	2022
18048	2022-06-27	2022
18049	2022-06-28	2022
18050	2022-06-29	2022
18051	2022-06-30	2022
18052	2022-07-01	2022
18053	2022-07-04	2022
18054	2022-07-05	2022
18055	2022-07-06	2022
18056	2022-07-07	2022
18057	2022-07-08	2022
18058	2022-07-11	2022
18059	2022-07-12	2022
18060	2022-07-13	2022
18061	2022-07-14	2022
18062	2022-07-15	2022
18063	2022-07-18	2022
18064	2022-07-19	2022
18065	2022-07-20	2022
18066	2022-07-21	2022
18067	2022-07-22	2022
18068	2022-07-25	2022
18069	2022-07-26	2022
18070	2022-07-27	2022
18071	2022-07-28	2022
18072	2022-07-29	2022
18073	2022-08-01	2022
18074	2022-08-02	2022
18075	2022-08-03	2022
18076	2022-08-04	2022
18077	2022-08-05	2022
18078	2022-08-08	2022
18079	2022-08-09	2022
18080	2022-08-10	2022
18081	2022-08-11	2022
18082	2022-08-12	2022
18083	2022-08-15	2022
18084	2022-08-16	2022
18085	2022-08-17	2022
18086	2022-08-18	2022
18087	2022-08-19	2022
18088	2022-08-22	2022
18089	2022-08-23	2022
18090	2022-08-24	2022
18091	2022-08-25	2022
18092	2022-08-26	2022
18093	2022-08-29	2022
18094	2022-08-30	2022
18095	2022-08-31	2022
18096	2022-09-01	2022
18097	2022-09-02	2022
18098	2022-09-05	2022
18099	2022-09-06	2022
18100	2022-09-08	2022
18101	2022-09-09	2022
18102	2022-09-12	2022
18103	2022-09-13	2022
18104	2022-09-14	2022
18105	2022-09-15	2022
18106	2022-09-16	2022
18107	2022-09-19	2022
18108	2022-09-20	2022
18109	2022-09-21	2022
18110	2022-09-22	2022
18111	2022-09-23	2022
18112	2022-09-26	2022
18113	2022-09-27	2022
18114	2022-09-28	2022
18115	2022-09-29	2022
18116	2022-09-30	2022
18117	2022-10-03	2022
18118	2022-10-04	2022
18119	2022-10-05	2022
18120	2022-10-06	2022
18121	2022-10-07	2022
18122	2022-10-10	2022
18123	2022-10-11	2022
18124	2022-10-13	2022
18125	2022-10-14	2022
18126	2022-10-17	2022
18127	2022-10-18	2022
18128	2022-10-19	2022
18129	2022-10-20	2022
18130	2022-10-21	2022
18131	2022-10-24	2022
18132	2022-10-25	2022
18133	2022-10-26	2022
18134	2022-10-27	2022
18135	2022-10-31	2022
18136	2022-11-01	2022
18137	2022-11-03	2022
18138	2022-11-04	2022
18139	2022-11-07	2022
18140	2022-11-08	2022
18141	2022-11-09	2022
18142	2022-11-10	2022
18143	2022-11-11	2022
18144	2022-11-14	2022
18145	2022-11-16	2022
18146	2022-11-17	2022
18147	2022-11-18	2022
18148	2022-11-21	2022
18149	2022-11-22	2022
18150	2022-11-23	2022
18151	2022-11-24	2022
18152	2022-11-25	2022
18153	2022-11-28	2022
18154	2022-11-29	2022
18155	2022-11-30	2022
18156	2022-12-01	2022
18157	2022-12-02	2022
18158	2022-12-05	2022
18159	2022-12-06	2022
18160	2022-12-07	2022
18161	2022-12-08	2022
18162	2022-12-09	2022
18163	2022-12-12	2022
18164	2022-12-13	2022
18165	2022-12-14	2022
18166	2022-12-15	2022
18167	2022-12-16	2022
18168	2022-12-19	2022
18169	2022-12-20	2022
18170	2022-12-21	2022
18171	2022-12-22	2022
18172	2022-12-23	2022
18173	2022-12-26	2022
18174	2022-12-27	2022
18175	2022-12-28	2022
18176	2022-12-29	2022
18177	2022-12-30	2022
18178	2023-01-02	2023
18179	2023-01-03	2023
18180	2023-01-04	2023
18181	2023-01-05	2023
18182	2023-01-06	2023
18183	2023-01-09	2023
18184	2023-01-10	2023
18185	2023-01-11	2023
18186	2023-01-12	2023
18187	2023-01-13	2023
18188	2023-01-16	2023
18189	2023-01-17	2023
18190	2023-01-18	2023
18191	2023-01-19	2023
18192	2023-01-20	2023
18193	2023-01-23	2023
18194	2023-01-24	2023
18195	2023-01-25	2023
18196	2023-01-26	2023
18197	2023-01-27	2023
18198	2023-01-30	2023
18199	2023-01-31	2023
18200	2023-02-01	2023
18201	2023-02-02	2023
18202	2023-02-03	2023
18203	2023-02-06	2023
18204	2023-02-07	2023
18205	2023-02-08	2023
18206	2023-02-09	2023
18207	2023-02-10	2023
18208	2023-02-13	2023
18209	2023-02-14	2023
18210	2023-02-15	2023
18211	2023-02-16	2023
18212	2023-02-17	2023
18213	2023-02-20	2023
18214	2023-02-22	2023
18215	2023-02-23	2023
18216	2023-02-24	2023
18217	2023-02-27	2023
18218	2023-02-28	2023
18219	2023-03-01	2023
18220	2023-03-02	2023
18221	2023-03-03	2023
18222	2023-03-06	2023
18223	2023-03-07	2023
18224	2023-03-08	2023
18225	2023-03-09	2023
18226	2023-03-10	2023
18227	2023-03-13	2023
18228	2023-03-14	2023
18229	2023-03-15	2023
18230	2023-03-16	2023
18231	2023-03-17	2023
18232	2023-03-20	2023
18233	2023-03-21	2023
18234	2023-03-22	2023
18235	2023-03-23	2023
18236	2023-03-24	2023
18237	2023-03-27	2023
18238	2023-03-28	2023
18239	2023-03-29	2023
18240	2023-03-30	2023
18241	2023-03-31	2023
18242	2023-04-03	2023
18243	2023-04-04	2023
18244	2023-04-05	2023
18245	2023-04-06	2023
18246	2023-04-07	2023
18247	2023-04-10	2023
18248	2023-04-11	2023
18249	2023-04-12	2023
18250	2023-04-13	2023
18251	2023-04-14	2023
18252	2023-04-17	2023
18253	2023-04-18	2023
18254	2023-04-19	2023
18255	2023-04-20	2023
18256	2023-04-24	2023
18257	2023-04-25	2023
18258	2023-04-26	2023
18259	2023-04-27	2023
18260	2023-04-28	2023
18261	2023-05-02	2023
18262	2023-05-03	2023
18263	2023-05-04	2023
18264	2023-05-05	2023
18265	2023-05-08	2023
18266	2023-05-09	2023
18267	2023-05-10	2023
18268	2023-05-11	2023
18269	2023-05-12	2023
18270	2023-05-15	2023
18271	2023-05-16	2023
18272	2023-05-17	2023
18273	2023-05-18	2023
18274	2023-05-19	2023
18275	2023-05-22	2023
18276	2023-05-23	2023
18277	2023-05-24	2023
18278	2023-05-25	2023
18279	2023-05-26	2023
18280	2023-05-29	2023
18281	2023-05-30	2023
18282	2023-05-31	2023
18283	2023-06-01	2023
18284	2023-06-02	2023
18285	2023-06-05	2023
18286	2023-06-06	2023
18287	2023-06-07	2023
18288	2023-06-09	2023
18289	2023-06-12	2023
18290	2023-06-13	2023
18291	2023-06-14	2023
18292	2023-06-15	2023
18293	2023-06-16	2023
18294	2023-06-19	2023
18295	2023-06-20	2023
18296	2023-06-21	2023
18297	2023-06-22	2023
18298	2023-06-23	2023
18299	2023-06-26	2023
18300	2023-06-27	2023
18301	2023-06-28	2023
18302	2023-06-29	2023
18303	2023-06-30	2023
18304	2023-07-03	2023
18305	2023-07-04	2023
18306	2023-07-05	2023
18307	2023-07-06	2023
18308	2023-07-07	2023
18309	2023-07-10	2023
18310	2023-07-11	2023
18311	2023-07-12	2023
18312	2023-07-13	2023
18313	2023-07-14	2023
18314	2023-07-17	2023
18315	2023-07-18	2023
18316	2023-07-19	2023
18317	2023-07-20	2023
18318	2023-07-21	2023
18319	2023-07-24	2023
18320	2023-07-25	2023
18321	2023-07-26	2023
18322	2023-07-27	2023
18323	2023-07-28	2023
18324	2023-07-31	2023
18325	2023-08-01	2023
18326	2023-08-02	2023
18327	2023-08-03	2023
18328	2023-08-04	2023
18329	2023-08-07	2023
18330	2023-08-08	2023
18331	2023-08-09	2023
18332	2023-08-10	2023
18333	2023-08-11	2023
18334	2023-08-14	2023
18335	2023-08-15	2023
18336	2023-08-16	2023
18337	2023-08-17	2023
18338	2023-08-18	2023
18339	2023-08-21	2023
18340	2023-08-22	2023
18341	2023-08-23	2023
18342	2023-08-24	2023
18343	2023-08-25	2023
18344	2023-08-28	2023
18345	2023-08-29	2023
18346	2023-08-30	2023
18347	2023-08-31	2023
18348	2023-09-01	2023
18349	2023-09-04	2023
18350	2023-09-05	2023
18351	2023-09-06	2023
18352	2023-09-08	2023
18353	2023-09-11	2023
18354	2023-09-12	2023
18355	2023-09-13	2023
18356	2023-09-14	2023
18357	2023-09-15	2023
18358	2023-09-18	2023
18359	2023-09-19	2023
18360	2023-09-20	2023
18361	2023-09-21	2023
18362	2023-09-22	2023
18363	2023-09-25	2023
18364	2023-09-26	2023
18365	2023-09-27	2023
18366	2023-09-28	2023
18367	2023-09-29	2023
18368	2023-10-02	2023
18369	2023-10-03	2023
18370	2023-10-04	2023
18371	2023-10-05	2023
18372	2023-10-06	2023
18373	2023-10-09	2023
18374	2023-10-10	2023
18375	2023-10-11	2023
18376	2023-10-13	2023
18377	2023-10-16	2023
18378	2023-10-17	2023
18379	2023-10-18	2023
18380	2023-10-19	2023
18381	2023-10-20	2023
18382	2023-10-23	2023
18383	2023-10-24	2023
18384	2023-10-25	2023
18385	2023-10-26	2023
18386	2023-10-27	2023
18387	2023-10-30	2023
18388	2023-10-31	2023
18389	2023-11-01	2023
18390	2023-11-03	2023
18391	2023-11-06	2023
18392	2023-11-07	2023
18393	2023-11-08	2023
18394	2023-11-09	2023
18395	2023-11-10	2023
18396	2023-11-13	2023
18397	2023-11-14	2023
18398	2023-11-16	2023
18399	2023-11-17	2023
18400	2023-11-20	2023
18401	2023-11-21	2023
18402	2023-11-22	2023
18403	2023-11-23	2023
18404	2023-11-24	2023
18405	2023-11-27	2023
18406	2023-11-28	2023
18407	2023-11-29	2023
18408	2023-11-30	2023
18409	2023-12-01	2023
18410	2023-12-04	2023
18411	2023-12-05	2023
18412	2023-12-06	2023
18413	2023-12-07	2023
18414	2023-12-08	2023
18415	2023-12-11	2023
18416	2023-12-12	2023
18417	2023-12-13	2023
18418	2023-12-14	2023
18419	2023-12-15	2023
18420	2023-12-18	2023
18421	2023-12-19	2023
18422	2023-12-20	2023
18423	2023-12-21	2023
18424	2023-12-22	2023
18425	2023-12-26	2023
18426	2023-12-27	2023
18427	2023-12-28	2023
18428	2023-12-29	2023
18429	2024-01-02	2024
18430	2024-01-03	2024
18431	2024-01-04	2024
18432	2024-01-05	2024
18433	2024-01-08	2024
18434	2024-01-09	2024
18435	2024-01-10	2024
18436	2024-01-11	2024
18437	2024-01-12	2024
18438	2024-01-15	2024
18439	2024-01-16	2024
18440	2024-01-17	2024
18441	2024-01-18	2024
18442	2024-01-19	2024
18443	2024-01-22	2024
18444	2024-01-23	2024
18445	2024-01-24	2024
18446	2024-01-25	2024
18447	2024-01-26	2024
18448	2024-01-29	2024
18449	2024-01-30	2024
18450	2024-01-31	2024
18451	2024-02-01	2024
18452	2024-02-02	2024
18453	2024-02-05	2024
18454	2024-02-06	2024
18455	2024-02-07	2024
18456	2024-02-08	2024
18457	2024-02-09	2024
18458	2024-02-12	2024
18459	2024-02-14	2024
18460	2024-02-15	2024
18461	2024-02-16	2024
18462	2024-02-19	2024
18463	2024-02-20	2024
18464	2024-02-21	2024
18465	2024-02-22	2024
18466	2024-02-23	2024
18467	2024-02-26	2024
18468	2024-02-27	2024
18469	2024-02-28	2024
18470	2024-02-29	2024
18471	2024-03-01	2024
18472	2024-03-04	2024
18473	2024-03-05	2024
18474	2024-03-06	2024
18475	2024-03-07	2024
18476	2024-03-08	2024
18477	2024-03-11	2024
18478	2024-03-12	2024
18479	2024-03-13	2024
18480	2024-03-14	2024
18481	2024-03-15	2024
18482	2024-03-18	2024
18483	2024-03-19	2024
18484	2024-03-20	2024
18485	2024-03-21	2024
18486	2024-03-22	2024
18487	2024-03-25	2024
18488	2024-03-26	2024
18489	2024-03-27	2024
18490	2024-03-28	2024
18491	2024-03-29	2024
18492	2024-04-01	2024
18493	2024-04-02	2024
18494	2024-04-03	2024
18495	2024-04-04	2024
18496	2024-04-05	2024
18497	2024-04-08	2024
18498	2024-04-09	2024
18499	2024-04-10	2024
18500	2024-04-11	2024
18501	2024-04-12	2024
18502	2024-04-15	2024
18503	2024-04-16	2024
18504	2024-04-17	2024
18505	2024-04-18	2024
18506	2024-04-19	2024
18507	2024-04-22	2024
18508	2024-04-23	2024
18509	2024-04-24	2024
18510	2024-04-25	2024
18511	2024-04-26	2024
18512	2024-04-29	2024
18513	2024-04-30	2024
18514	2024-05-02	2024
18515	2024-05-03	2024
18516	2024-05-06	2024
18517	2024-05-07	2024
18518	2024-05-08	2024
18519	2024-05-09	2024
18520	2024-05-10	2024
18521	2024-05-13	2024
18522	2024-05-14	2024
18523	2024-05-15	2024
18524	2024-05-16	2024
18525	2024-05-17	2024
18526	2024-05-20	2024
18527	2024-05-21	2024
18528	2024-05-22	2024
18529	2024-05-23	2024
18530	2024-05-24	2024
18531	2024-05-27	2024
18532	2024-05-28	2024
18533	2024-05-29	2024
18534	2024-05-31	2024
18535	2024-06-03	2024
18536	2024-06-04	2024
18537	2024-06-05	2024
18538	2024-06-06	2024
18539	2024-06-07	2024
18540	2024-06-10	2024
18541	2024-06-11	2024
18542	2024-06-12	2024
18543	2024-06-13	2024
18544	2024-06-14	2024
18545	2024-06-17	2024
18546	2024-06-18	2024
18547	2024-06-19	2024
18548	2024-06-20	2024
18549	2024-06-21	2024
18550	2024-06-24	2024
18551	2024-06-25	2024
18552	2024-06-26	2024
18553	2024-06-27	2024
18554	2024-06-28	2024
18555	2024-07-01	2024
18556	2024-07-02	2024
18557	2024-07-03	2024
18558	2024-07-04	2024
18559	2024-07-05	2024
18560	2024-07-08	2024
18561	2024-07-09	2024
18562	2024-07-10	2024
18563	2024-07-11	2024
18564	2024-07-12	2024
18565	2024-07-15	2024
18566	2024-07-16	2024
18567	2024-07-17	2024
18568	2024-07-18	2024
18569	2024-07-19	2024
18570	2024-07-22	2024
18571	2024-07-23	2024
18572	2024-07-24	2024
18573	2024-07-25	2024
18574	2024-07-26	2024
18575	2024-07-29	2024
18576	2024-07-30	2024
18577	2024-07-31	2024
18578	2024-08-01	2024
18579	2024-08-02	2024
18580	2024-08-05	2024
18581	2024-08-06	2024
18582	2024-08-07	2024
18583	2024-08-08	2024
18584	2024-08-09	2024
18585	2024-08-12	2024
18586	2024-08-13	2024
18587	2024-08-14	2024
18588	2024-08-15	2024
18589	2024-08-16	2024
18590	2024-08-19	2024
18591	2024-08-20	2024
18592	2024-08-21	2024
18593	2024-08-22	2024
18594	2024-08-23	2024
18595	2024-08-26	2024
18596	2024-08-27	2024
18597	2024-08-28	2024
18598	2024-08-29	2024
18599	2024-08-30	2024
18600	2024-09-02	2024
18601	2024-09-03	2024
18602	2024-09-04	2024
18603	2024-09-05	2024
18604	2024-09-06	2024
18605	2024-09-09	2024
18606	2024-09-10	2024
18607	2024-09-11	2024
18608	2024-09-12	2024
18609	2024-09-13	2024
18610	2024-09-16	2024
18611	2024-09-17	2024
18612	2024-09-18	2024
18613	2024-09-19	2024
18614	2024-09-20	2024
18615	2024-09-23	2024
18616	2024-09-24	2024
18617	2024-09-25	2024
18618	2024-09-26	2024
18619	2024-09-27	2024
18620	2024-09-30	2024
18621	2024-10-01	2024
18622	2024-10-02	2024
18623	2024-10-03	2024
18624	2024-10-04	2024
18625	2024-10-07	2024
18626	2024-10-08	2024
18627	2024-10-09	2024
18628	2024-10-10	2024
18629	2024-10-11	2024
18630	2024-10-14	2024
18631	2024-10-15	2024
18632	2024-10-16	2024
18633	2024-10-17	2024
18634	2024-10-18	2024
18635	2024-10-21	2024
18636	2024-10-22	2024
18637	2024-10-23	2024
18638	2024-10-24	2024
18639	2024-10-25	2024
18640	2024-10-29	2024
18641	2024-10-30	2024
18642	2024-10-31	2024
18643	2024-11-01	2024
18644	2024-11-04	2024
18645	2024-11-05	2024
18646	2024-11-06	2024
18647	2024-11-07	2024
18648	2024-11-08	2024
18649	2024-11-11	2024
18650	2024-11-12	2024
18651	2024-11-13	2024
18652	2024-11-14	2024
18653	2024-11-18	2024
18654	2024-11-19	2024
18655	2024-11-20	2024
18656	2024-11-21	2024
18657	2024-11-22	2024
18658	2024-11-25	2024
18659	2024-11-26	2024
18660	2024-11-27	2024
18661	2024-11-28	2024
18662	2024-11-29	2024
18663	2024-12-02	2024
18664	2024-12-03	2024
18665	2024-12-04	2024
18666	2024-12-05	2024
18667	2024-12-06	2024
18668	2024-12-09	2024
18669	2024-12-10	2024
18670	2024-12-11	2024
18671	2024-12-12	2024
18672	2024-12-13	2024
18673	2024-12-16	2024
18674	2024-12-17	2024
18675	2024-12-18	2024
18676	2024-12-19	2024
18677	2024-12-20	2024
18678	2024-12-23	2024
18679	2024-12-24	2024
18680	2024-12-26	2024
18681	2024-12-27	2024
18682	2024-12-30	2024
18683	2024-12-31	2024
18684	2025-01-02	2025
18685	2025-01-03	2025
18686	2025-01-06	2025
18687	2025-01-07	2025
18688	2025-01-08	2025
18689	2025-01-09	2025
18690	2025-01-10	2025
18691	2025-01-13	2025
18692	2025-01-14	2025
18693	2025-01-15	2025
18694	2025-01-16	2025
18695	2025-01-17	2025
18696	2025-01-20	2025
18697	2025-01-21	2025
18698	2025-01-22	2025
18699	2025-01-23	2025
18700	2025-01-24	2025
18701	2025-01-27	2025
18702	2025-01-28	2025
18703	2025-01-29	2025
18704	2025-01-30	2025
18705	2025-01-31	2025
18706	2025-02-03	2025
18707	2025-02-04	2025
18708	2025-02-05	2025
18709	2025-02-06	2025
18710	2025-02-07	2025
18711	2025-02-10	2025
18712	2025-02-11	2025
18713	2025-02-12	2025
18714	2025-02-13	2025
18715	2025-02-14	2025
18716	2025-02-17	2025
18717	2025-02-18	2025
18718	2025-02-19	2025
18719	2025-02-20	2025
18720	2025-02-21	2025
18721	2025-02-24	2025
18722	2025-02-25	2025
18723	2025-02-26	2025
18724	2025-02-27	2025
18725	2025-02-28	2025
18726	2025-03-03	2025
18727	2025-03-05	2025
18728	2025-03-06	2025
18729	2025-03-07	2025
18730	2025-03-10	2025
18731	2025-03-11	2025
18732	2025-03-12	2025
18733	2025-03-13	2025
18734	2025-03-14	2025
18735	2025-03-17	2025
18736	2025-03-18	2025
18737	2025-03-19	2025
18738	2025-03-20	2025
18739	2025-03-21	2025
18740	2025-03-24	2025
18741	2025-03-25	2025
18742	2025-03-26	2025
18743	2025-03-27	2025
18744	2025-03-28	2025
18745	2025-03-31	2025
18746	2025-04-01	2025
18747	2025-04-02	2025
18748	2025-04-03	2025
18749	2025-04-04	2025
18750	2025-04-07	2025
18751	2025-04-08	2025
18752	2025-04-09	2025
18753	2025-04-10	2025
18754	2025-04-11	2025
18755	2025-04-14	2025
18756	2025-04-15	2025
18757	2025-04-16	2025
18758	2025-04-17	2025
18759	2025-04-18	2025
18760	2025-04-22	2025
18761	2025-04-23	2025
18762	2025-04-24	2025
18763	2025-04-25	2025
18764	2025-04-28	2025
18765	2025-04-29	2025
18766	2025-04-30	2025
18767	2025-05-02	2025
18768	2025-05-05	2025
18769	2025-05-06	2025
18770	2025-05-07	2025
18771	2025-05-08	2025
18772	2025-05-09	2025
18773	2025-05-12	2025
18774	2025-05-13	2025
18775	2025-05-14	2025
18776	2025-05-15	2025
18777	2025-05-16	2025
18778	2025-05-19	2025
18779	2025-05-20	2025
18780	2025-05-21	2025
18781	2025-05-22	2025
18782	2025-05-23	2025
18783	2025-05-26	2025
18784	2025-05-27	2025
18785	2025-05-28	2025
18786	2025-05-29	2025
18787	2025-05-30	2025
18788	2025-06-02	2025
18789	2025-06-03	2025
18790	2025-06-04	2025
18791	2025-06-05	2025
18792	2025-06-06	2025
18793	2025-06-09	2025
18794	2025-06-10	2025
18795	2025-06-11	2025
18796	2025-06-12	2025
18797	2025-06-13	2025
18798	2025-06-16	2025
18799	2025-06-17	2025
18800	2025-06-18	2025
18801	2025-06-20	2025
18802	2025-06-23	2025
18803	2025-06-24	2025
18804	2025-06-25	2025
18805	2025-06-26	2025
18806	2025-06-27	2025
18807	2025-06-30	2025
18808	2025-07-01	2025
18809	2025-07-02	2025
18810	2025-07-03	2025
18811	2025-07-04	2025
18812	2025-07-07	2025
18813	2025-07-08	2025
18814	2025-07-09	2025
18815	2025-07-10	2025
18816	2025-07-11	2025
18817	2025-07-14	2025
18818	2025-07-15	2025
18819	2025-07-16	2025
18820	2025-07-17	2025
18821	2025-07-18	2025
18822	2025-07-21	2025
18823	2025-07-22	2025
18824	2025-07-23	2025
18825	2025-07-24	2025
18826	2025-07-25	2025
18827	2025-07-28	2025
18828	2025-07-29	2025
18829	2025-07-30	2025
18830	2025-07-31	2025
18831	2025-08-01	2025
18832	2025-08-04	2025
18833	2025-08-05	2025
18834	2025-08-06	2025
18835	2025-08-07	2025
18836	2025-08-08	2025
18837	2025-08-11	2025
18838	2025-08-12	2025
18839	2025-08-13	2025
18840	2025-08-14	2025
18841	2025-08-15	2025
18842	2025-08-18	2025
18843	2025-08-19	2025
18844	2025-08-20	2025
18845	2025-08-21	2025
18846	2025-08-22	2025
18847	2025-08-25	2025
18848	2025-08-26	2025
18849	2025-08-27	2025
18850	2025-08-28	2025
18851	2025-08-29	2025
18852	2025-09-01	2025
18853	2025-09-02	2025
18854	2025-09-03	2025
18855	2025-09-04	2025
18856	2025-09-05	2025
18857	2025-09-08	2025
18858	2025-09-09	2025
18859	2025-09-10	2025
18860	2025-09-11	2025
18861	2025-09-12	2025
18862	2025-09-15	2025
18863	2025-09-16	2025
18864	2025-09-17	2025
18865	2025-09-18	2025
18866	2025-09-19	2025
18867	2025-09-22	2025
18868	2025-09-23	2025
18869	2025-09-24	2025
18870	2025-09-25	2025
18871	2025-09-26	2025
18872	2025-09-29	2025
18873	2025-09-30	2025
18874	2025-10-01	2025
18875	2025-10-02	2025
18876	2025-10-03	2025
18877	2025-10-06	2025
18878	2025-10-07	2025
18879	2025-10-08	2025
18880	2025-10-09	2025
18881	2025-10-10	2025
18882	2025-10-13	2025
18883	2025-10-14	2025
18884	2025-10-15	2025
18885	2025-10-16	2025
18886	2025-10-17	2025
18887	2025-10-20	2025
18888	2025-10-21	2025
18889	2025-10-22	2025
18890	2025-10-23	2025
18891	2025-10-24	2025
18892	2025-10-27	2025
18893	2025-10-29	2025
18894	2025-10-30	2025
18895	2025-10-31	2025
18896	2025-11-03	2025
18897	2025-11-04	2025
18898	2025-11-05	2025
18899	2025-11-06	2025
18900	2025-11-07	2025
18901	2025-11-10	2025
18902	2025-11-11	2025
18903	2025-11-12	2025
18904	2025-11-13	2025
18905	2025-11-14	2025
18906	2025-11-17	2025
18907	2025-11-18	2025
18908	2025-11-19	2025
18909	2025-11-20	2025
18910	2025-11-21	2025
18911	2025-11-24	2025
18912	2025-11-25	2025
18913	2025-11-26	2025
18914	2025-11-27	2025
18915	2025-11-28	2025
18916	2025-12-01	2025
18917	2025-12-02	2025
18918	2025-12-03	2025
18919	2025-12-04	2025
18920	2025-12-05	2025
18921	2025-12-08	2025
18922	2025-12-09	2025
18923	2025-12-10	2025
18924	2025-12-11	2025
18925	2025-12-12	2025
18926	2025-12-15	2025
18927	2025-12-16	2025
18928	2025-12-17	2025
18929	2025-12-18	2025
18930	2025-12-19	2025
18931	2025-12-22	2025
18932	2025-12-23	2025
18933	2025-12-24	2025
18934	2025-12-26	2025
18935	2025-12-29	2025
18936	2025-12-30	2025
18937	2025-12-31	2025
18938	2026-01-02	2026
18939	2026-01-05	2026
18940	2026-01-06	2026
18941	2026-01-07	2026
18942	2026-01-08	2026
18943	2026-01-09	2026
18944	2026-01-12	2026
18945	2026-01-13	2026
18946	2026-01-14	2026
18947	2026-01-15	2026
18948	2026-01-16	2026
18949	2026-01-19	2026
18950	2026-01-20	2026
18951	2026-01-21	2026
18952	2026-01-22	2026
18953	2026-01-23	2026
18954	2026-01-26	2026
18955	2026-01-27	2026
18956	2026-01-28	2026
18957	2026-01-29	2026
18958	2026-01-30	2026
18959	2026-02-02	2026
18960	2026-02-03	2026
18961	2026-02-04	2026
18962	2026-02-05	2026
18963	2026-02-06	2026
18964	2026-02-09	2026
18965	2026-02-10	2026
18966	2026-02-11	2026
18967	2026-02-12	2026
18968	2026-02-13	2026
18969	2026-02-16	2026
18970	2026-02-18	2026
18971	2026-02-19	2026
18972	2026-02-20	2026
18973	2026-02-23	2026
18974	2026-02-24	2026
18975	2026-02-25	2026
18976	2026-02-26	2026
18977	2026-02-27	2026
18978	2026-03-02	2026
18979	2026-03-03	2026
18980	2026-03-04	2026
18981	2026-03-05	2026
18982	2026-03-06	2026
18983	2026-03-09	2026
18984	2026-03-10	2026
18985	2026-03-11	2026
18986	2026-03-12	2026
18987	2026-03-13	2026
18988	2026-03-16	2026
18989	2026-03-17	2026
18990	2026-03-18	2026
18991	2026-03-19	2026
18992	2026-03-20	2026
18993	2026-03-23	2026
18994	2026-03-24	2026
18995	2026-03-25	2026
18996	2026-03-26	2026
18997	2026-03-27	2026
18998	2026-03-30	2026
18999	2026-03-31	2026
19000	2026-04-01	2026
19001	2026-04-02	2026
19002	2026-04-03	2026
19003	2026-04-06	2026
19004	2026-04-07	2026
19005	2026-04-08	2026
19006	2026-04-09	2026
19007	2026-04-10	2026
19008	2026-04-13	2026
19009	2026-04-14	2026
19010	2026-04-15	2026
19011	2026-04-16	2026
19012	2026-04-17	2026
19013	2026-04-20	2026
19014	2026-04-22	2026
19015	2026-04-23	2026
19016	2026-04-24	2026
19017	2026-04-27	2026
19018	2026-04-28	2026
19019	2026-04-29	2026
19020	2026-04-30	2026
19021	2026-05-04	2026
19022	2026-05-05	2026
19023	2026-05-06	2026
19024	2026-05-07	2026
19025	2026-05-08	2026
19026	2026-05-11	2026
19027	2026-05-12	2026
19028	2026-05-13	2026
19029	2026-05-14	2026
19030	2026-05-15	2026
19031	2026-05-18	2026
19032	2026-05-19	2026
19033	2026-05-20	2026
19034	2026-05-21	2026
19035	2026-05-22	2026
19036	2026-05-25	2026
19037	2026-05-26	2026
19038	2026-05-27	2026
19039	2026-05-28	2026
19040	2026-05-29	2026
19041	2026-06-01	2026
19042	2026-06-02	2026
19043	2026-06-03	2026
19044	2026-06-05	2026
19045	2026-06-08	2026
19046	2026-06-09	2026
19047	2026-06-10	2026
19048	2026-06-11	2026
19049	2026-06-12	2026
19050	2026-06-15	2026
19051	2026-06-16	2026
19052	2026-06-17	2026
19053	2026-06-18	2026
19054	2026-06-19	2026
19055	2026-06-22	2026
19056	2026-06-23	2026
19057	2026-06-24	2026
19058	2026-06-25	2026
19059	2026-06-26	2026
19060	2026-06-29	2026
19061	2026-06-30	2026
19062	2026-07-01	2026
19063	2026-07-02	2026
19064	2026-07-03	2026
19065	2026-07-06	2026
19066	2026-07-07	2026
19067	2026-07-08	2026
19068	2026-07-09	2026
19069	2026-07-10	2026
19070	2026-07-13	2026
19071	2026-07-14	2026
19072	2026-07-15	2026
19073	2026-07-16	2026
19074	2026-07-17	2026
19075	2026-07-20	2026
19076	2026-07-21	2026
19077	2026-07-22	2026
19078	2026-07-23	2026
19079	2026-07-24	2026
19080	2026-07-27	2026
19081	2026-07-28	2026
19082	2026-07-29	2026
19083	2026-07-30	2026
19084	2026-07-31	2026
19085	2026-08-03	2026
19086	2026-08-04	2026
19087	2026-08-05	2026
19088	2026-08-06	2026
19089	2026-08-07	2026
19090	2026-08-10	2026
19091	2026-08-11	2026
19092	2026-08-12	2026
19093	2026-08-13	2026
19094	2026-08-14	2026
19095	2026-08-17	2026
19096	2026-08-18	2026
19097	2026-08-19	2026
19098	2026-08-20	2026
19099	2026-08-21	2026
19100	2026-08-24	2026
19101	2026-08-25	2026
19102	2026-08-26	2026
19103	2026-08-27	2026
19104	2026-08-28	2026
19105	2026-08-31	2026
19106	2026-09-01	2026
19107	2026-09-02	2026
19108	2026-09-03	2026
19109	2026-09-04	2026
19110	2026-09-08	2026
19111	2026-09-09	2026
19112	2026-09-10	2026
19113	2026-09-11	2026
19114	2026-09-14	2026
19115	2026-09-15	2026
19116	2026-09-16	2026
19117	2026-09-17	2026
19118	2026-09-18	2026
19119	2026-09-21	2026
19120	2026-09-22	2026
19121	2026-09-23	2026
19122	2026-09-24	2026
19123	2026-09-25	2026
19124	2026-09-28	2026
19125	2026-09-29	2026
19126	2026-09-30	2026
19127	2026-10-01	2026
19128	2026-10-02	2026
19129	2026-10-05	2026
19130	2026-10-06	2026
19131	2026-10-07	2026
19132	2026-10-08	2026
19133	2026-10-09	2026
19134	2026-10-13	2026
19135	2026-10-14	2026
19136	2026-10-15	2026
19137	2026-10-16	2026
19138	2026-10-19	2026
19139	2026-10-20	2026
19140	2026-10-21	2026
19141	2026-10-22	2026
19142	2026-10-23	2026
19143	2026-10-26	2026
19144	2026-10-27	2026
19145	2026-10-29	2026
19146	2026-10-30	2026
19147	2026-11-03	2026
19148	2026-11-04	2026
19149	2026-11-05	2026
19150	2026-11-06	2026
19151	2026-11-09	2026
19152	2026-11-10	2026
19153	2026-11-11	2026
19154	2026-11-12	2026
19155	2026-11-13	2026
19156	2026-11-16	2026
19157	2026-11-17	2026
19158	2026-11-18	2026
19159	2026-11-19	2026
19160	2026-11-20	2026
19161	2026-11-23	2026
19162	2026-11-24	2026
19163	2026-11-25	2026
19164	2026-11-26	2026
19165	2026-11-27	2026
19166	2026-11-30	2026
19167	2026-12-01	2026
19168	2026-12-02	2026
19169	2026-12-03	2026
19170	2026-12-04	2026
19171	2026-12-07	2026
19172	2026-12-08	2026
19173	2026-12-09	2026
19174	2026-12-10	2026
19175	2026-12-11	2026
19176	2026-12-14	2026
19177	2026-12-15	2026
19178	2026-12-16	2026
19179	2026-12-17	2026
19180	2026-12-18	2026
19181	2026-12-21	2026
19182	2026-12-22	2026
19183	2026-12-23	2026
19184	2026-12-24	2026
19185	2026-12-28	2026
19186	2026-12-29	2026
19187	2026-12-30	2026
19188	2026-12-31	2026
19189	2027-01-04	2027
19190	2027-01-05	2027
19191	2027-01-06	2027
19192	2027-01-07	2027
19193	2027-01-08	2027
19194	2027-01-11	2027
19195	2027-01-12	2027
19196	2027-01-13	2027
19197	2027-01-14	2027
19198	2027-01-15	2027
19199	2027-01-18	2027
19200	2027-01-19	2027
19201	2027-01-20	2027
19202	2027-01-21	2027
19203	2027-01-22	2027
19204	2027-01-25	2027
19205	2027-01-26	2027
19206	2027-01-27	2027
19207	2027-01-28	2027
19208	2027-01-29	2027
19209	2027-02-01	2027
19210	2027-02-02	2027
19211	2027-02-03	2027
19212	2027-02-04	2027
19213	2027-02-05	2027
19214	2027-02-08	2027
19215	2027-02-10	2027
19216	2027-02-11	2027
19217	2027-02-12	2027
19218	2027-02-15	2027
19219	2027-02-16	2027
19220	2027-02-17	2027
19221	2027-02-18	2027
19222	2027-02-19	2027
19223	2027-02-22	2027
19224	2027-02-23	2027
19225	2027-02-24	2027
19226	2027-02-25	2027
19227	2027-02-26	2027
19228	2027-03-01	2027
19229	2027-03-02	2027
19230	2027-03-03	2027
19231	2027-03-04	2027
19232	2027-03-05	2027
19233	2027-03-08	2027
19234	2027-03-09	2027
19235	2027-03-10	2027
19236	2027-03-11	2027
19237	2027-03-12	2027
19238	2027-03-15	2027
19239	2027-03-16	2027
19240	2027-03-17	2027
19241	2027-03-18	2027
19242	2027-03-19	2027
19243	2027-03-22	2027
19244	2027-03-23	2027
19245	2027-03-24	2027
19246	2027-03-25	2027
19247	2027-03-26	2027
19248	2027-03-29	2027
19249	2027-03-30	2027
19250	2027-03-31	2027
19251	2027-04-01	2027
19252	2027-04-02	2027
19253	2027-04-05	2027
19254	2027-04-06	2027
19255	2027-04-07	2027
19256	2027-04-08	2027
19257	2027-04-09	2027
19258	2027-04-12	2027
19259	2027-04-13	2027
19260	2027-04-14	2027
19261	2027-04-15	2027
19262	2027-04-16	2027
19263	2027-04-19	2027
19264	2027-04-20	2027
19265	2027-04-22	2027
19266	2027-04-23	2027
19267	2027-04-26	2027
19268	2027-04-27	2027
19269	2027-04-28	2027
19270	2027-04-29	2027
19271	2027-04-30	2027
19272	2027-05-03	2027
19273	2027-05-04	2027
19274	2027-05-05	2027
19275	2027-05-06	2027
19276	2027-05-07	2027
19277	2027-05-10	2027
19278	2027-05-11	2027
19279	2027-05-12	2027
19280	2027-05-13	2027
19281	2027-05-14	2027
19282	2027-05-17	2027
19283	2027-05-18	2027
19284	2027-05-19	2027
19285	2027-05-20	2027
19286	2027-05-21	2027
19287	2027-05-24	2027
19288	2027-05-25	2027
19289	2027-05-26	2027
19290	2027-05-28	2027
19291	2027-05-31	2027
19292	2027-06-01	2027
19293	2027-06-02	2027
19294	2027-06-03	2027
19295	2027-06-04	2027
19296	2027-06-07	2027
19297	2027-06-08	2027
19298	2027-06-09	2027
19299	2027-06-10	2027
19300	2027-06-11	2027
19301	2027-06-14	2027
19302	2027-06-15	2027
19303	2027-06-16	2027
19304	2027-06-17	2027
19305	2027-06-18	2027
19306	2027-06-21	2027
19307	2027-06-22	2027
19308	2027-06-23	2027
19309	2027-06-24	2027
19310	2027-06-25	2027
19311	2027-06-28	2027
19312	2027-06-29	2027
19313	2027-06-30	2027
19314	2027-07-01	2027
19315	2027-07-02	2027
19316	2027-07-05	2027
19317	2027-07-06	2027
19318	2027-07-07	2027
19319	2027-07-08	2027
19320	2027-07-09	2027
19321	2027-07-12	2027
19322	2027-07-13	2027
19323	2027-07-14	2027
19324	2027-07-15	2027
19325	2027-07-16	2027
19326	2027-07-19	2027
19327	2027-07-20	2027
19328	2027-07-21	2027
19329	2027-07-22	2027
19330	2027-07-23	2027
19331	2027-07-26	2027
19332	2027-07-27	2027
19333	2027-07-28	2027
19334	2027-07-29	2027
19335	2027-07-30	2027
19336	2027-08-02	2027
19337	2027-08-03	2027
19338	2027-08-04	2027
19339	2027-08-05	2027
19340	2027-08-06	2027
19341	2027-08-09	2027
19342	2027-08-10	2027
19343	2027-08-11	2027
19344	2027-08-12	2027
19345	2027-08-13	2027
19346	2027-08-16	2027
19347	2027-08-17	2027
19348	2027-08-18	2027
19349	2027-08-19	2027
19350	2027-08-20	2027
19351	2027-08-23	2027
19352	2027-08-24	2027
19353	2027-08-25	2027
19354	2027-08-26	2027
19355	2027-08-27	2027
19356	2027-08-30	2027
19357	2027-08-31	2027
19358	2027-09-01	2027
19359	2027-09-02	2027
19360	2027-09-03	2027
19361	2027-09-06	2027
19362	2027-09-08	2027
19363	2027-09-09	2027
19364	2027-09-10	2027
19365	2027-09-13	2027
19366	2027-09-14	2027
19367	2027-09-15	2027
19368	2027-09-16	2027
19369	2027-09-17	2027
19370	2027-09-20	2027
19371	2027-09-21	2027
19372	2027-09-22	2027
19373	2027-09-23	2027
19374	2027-09-24	2027
19375	2027-09-27	2027
19376	2027-09-28	2027
19377	2027-09-29	2027
19378	2027-09-30	2027
19379	2027-10-01	2027
19380	2027-10-04	2027
19381	2027-10-05	2027
19382	2027-10-06	2027
19383	2027-10-07	2027
19384	2027-10-08	2027
19385	2027-10-11	2027
19386	2027-10-13	2027
19387	2027-10-14	2027
19388	2027-10-15	2027
19389	2027-10-18	2027
19390	2027-10-19	2027
19391	2027-10-20	2027
19392	2027-10-21	2027
19393	2027-10-22	2027
19394	2027-10-25	2027
19395	2027-10-26	2027
19396	2027-10-27	2027
19397	2027-10-29	2027
19398	2027-11-01	2027
19399	2027-11-03	2027
19400	2027-11-04	2027
19401	2027-11-05	2027
19402	2027-11-08	2027
19403	2027-11-09	2027
19404	2027-11-10	2027
19405	2027-11-11	2027
19406	2027-11-12	2027
19407	2027-11-16	2027
19408	2027-11-17	2027
19409	2027-11-18	2027
19410	2027-11-19	2027
19411	2027-11-22	2027
19412	2027-11-23	2027
19413	2027-11-24	2027
19414	2027-11-25	2027
19415	2027-11-26	2027
19416	2027-11-29	2027
19417	2027-11-30	2027
19418	2027-12-01	2027
19419	2027-12-02	2027
19420	2027-12-03	2027
19421	2027-12-06	2027
19422	2027-12-07	2027
19423	2027-12-08	2027
19424	2027-12-09	2027
19425	2027-12-10	2027
19426	2027-12-13	2027
19427	2027-12-14	2027
19428	2027-12-15	2027
19429	2027-12-16	2027
19430	2027-12-17	2027
19431	2027-12-20	2027
19432	2027-12-21	2027
19433	2027-12-22	2027
19434	2027-12-23	2027
19435	2027-12-24	2027
19436	2027-12-27	2027
19437	2027-12-28	2027
19438	2027-12-29	2027
19439	2027-12-30	2027
19440	2027-12-31	2027
19441	2028-01-03	2028
19442	2028-01-04	2028
19443	2028-01-05	2028
19444	2028-01-06	2028
19445	2028-01-07	2028
19446	2028-01-10	2028
19447	2028-01-11	2028
19448	2028-01-12	2028
19449	2028-01-13	2028
19450	2028-01-14	2028
19451	2028-01-17	2028
19452	2028-01-18	2028
19453	2028-01-19	2028
19454	2028-01-20	2028
19455	2028-01-21	2028
19456	2028-01-24	2028
19457	2028-01-25	2028
19458	2028-01-26	2028
19459	2028-01-27	2028
19460	2028-01-28	2028
19461	2028-01-31	2028
19462	2028-02-01	2028
19463	2028-02-02	2028
19464	2028-02-03	2028
19465	2028-02-04	2028
19466	2028-02-07	2028
19467	2028-02-08	2028
19468	2028-02-09	2028
19469	2028-02-10	2028
19470	2028-02-11	2028
19471	2028-02-14	2028
19472	2028-02-15	2028
19473	2028-02-16	2028
19474	2028-02-17	2028
19475	2028-02-18	2028
19476	2028-02-21	2028
19477	2028-02-22	2028
19478	2028-02-23	2028
19479	2028-02-24	2028
19480	2028-02-25	2028
19481	2028-02-28	2028
19482	2028-03-01	2028
19483	2028-03-02	2028
19484	2028-03-03	2028
19485	2028-03-06	2028
19486	2028-03-07	2028
19487	2028-03-08	2028
19488	2028-03-09	2028
19489	2028-03-10	2028
19490	2028-03-13	2028
19491	2028-03-14	2028
19492	2028-03-15	2028
19493	2028-03-16	2028
19494	2028-03-17	2028
19495	2028-03-20	2028
19496	2028-03-21	2028
19497	2028-03-22	2028
19498	2028-03-23	2028
19499	2028-03-24	2028
19500	2028-03-27	2028
19501	2028-03-28	2028
19502	2028-03-29	2028
19503	2028-03-30	2028
19504	2028-03-31	2028
19505	2028-04-03	2028
19506	2028-04-04	2028
19507	2028-04-05	2028
19508	2028-04-06	2028
19509	2028-04-07	2028
19510	2028-04-10	2028
19511	2028-04-11	2028
19512	2028-04-12	2028
19513	2028-04-13	2028
19514	2028-04-14	2028
19515	2028-04-17	2028
19516	2028-04-18	2028
19517	2028-04-19	2028
19518	2028-04-20	2028
19519	2028-04-24	2028
19520	2028-04-25	2028
19521	2028-04-26	2028
19522	2028-04-27	2028
19523	2028-04-28	2028
19524	2028-05-02	2028
19525	2028-05-03	2028
19526	2028-05-04	2028
19527	2028-05-05	2028
19528	2028-05-08	2028
19529	2028-05-09	2028
19530	2028-05-10	2028
19531	2028-05-11	2028
19532	2028-05-12	2028
19533	2028-05-15	2028
19534	2028-05-16	2028
19535	2028-05-17	2028
19536	2028-05-18	2028
19537	2028-05-19	2028
19538	2028-05-22	2028
19539	2028-05-23	2028
19540	2028-05-24	2028
19541	2028-05-25	2028
19542	2028-05-26	2028
19543	2028-05-29	2028
19544	2028-05-30	2028
19545	2028-05-31	2028
19546	2028-06-01	2028
19547	2028-06-02	2028
19548	2028-06-05	2028
19549	2028-06-06	2028
19550	2028-06-07	2028
19551	2028-06-08	2028
19552	2028-06-09	2028
19553	2028-06-12	2028
19554	2028-06-13	2028
19555	2028-06-14	2028
19556	2028-06-16	2028
19557	2028-06-19	2028
19558	2028-06-20	2028
19559	2028-06-21	2028
19560	2028-06-22	2028
19561	2028-06-23	2028
19562	2028-06-26	2028
19563	2028-06-27	2028
19564	2028-06-28	2028
19565	2028-06-29	2028
19566	2028-06-30	2028
19567	2028-07-03	2028
19568	2028-07-04	2028
19569	2028-07-05	2028
19570	2028-07-06	2028
19571	2028-07-07	2028
19572	2028-07-10	2028
19573	2028-07-11	2028
19574	2028-07-12	2028
19575	2028-07-13	2028
19576	2028-07-14	2028
19577	2028-07-17	2028
19578	2028-07-18	2028
19579	2028-07-19	2028
19580	2028-07-20	2028
19581	2028-07-21	2028
19582	2028-07-24	2028
19583	2028-07-25	2028
19584	2028-07-26	2028
19585	2028-07-27	2028
19586	2028-07-28	2028
19587	2028-07-31	2028
19588	2028-08-01	2028
19589	2028-08-02	2028
19590	2028-08-03	2028
19591	2028-08-04	2028
19592	2028-08-07	2028
19593	2028-08-08	2028
19594	2028-08-09	2028
19595	2028-08-10	2028
19596	2028-08-11	2028
19597	2028-08-14	2028
19598	2028-08-15	2028
19599	2028-08-16	2028
19600	2028-08-17	2028
19601	2028-08-18	2028
19602	2028-08-21	2028
19603	2028-08-22	2028
19604	2028-08-23	2028
19605	2028-08-24	2028
19606	2028-08-25	2028
19607	2028-08-28	2028
19608	2028-08-29	2028
19609	2028-08-30	2028
19610	2028-08-31	2028
19611	2028-09-01	2028
19612	2028-09-04	2028
19613	2028-09-05	2028
19614	2028-09-06	2028
19615	2028-09-08	2028
19616	2028-09-11	2028
19617	2028-09-12	2028
19618	2028-09-13	2028
19619	2028-09-14	2028
19620	2028-09-15	2028
19621	2028-09-18	2028
19622	2028-09-19	2028
19623	2028-09-20	2028
19624	2028-09-21	2028
19625	2028-09-22	2028
19626	2028-09-25	2028
19627	2028-09-26	2028
19628	2028-09-27	2028
19629	2028-09-28	2028
19630	2028-09-29	2028
19631	2028-10-02	2028
19632	2028-10-03	2028
19633	2028-10-04	2028
19634	2028-10-05	2028
19635	2028-10-06	2028
19636	2028-10-09	2028
19637	2028-10-10	2028
19638	2028-10-11	2028
19639	2028-10-13	2028
19640	2028-10-16	2028
19641	2028-10-17	2028
19642	2028-10-18	2028
19643	2028-10-19	2028
19644	2028-10-20	2028
19645	2028-10-23	2028
19646	2028-10-24	2028
19647	2028-10-25	2028
19648	2028-10-26	2028
19649	2028-10-27	2028
19650	2028-10-30	2028
19651	2028-10-31	2028
19652	2028-11-01	2028
19653	2028-11-03	2028
19654	2028-11-06	2028
19655	2028-11-07	2028
19656	2028-11-08	2028
19657	2028-11-09	2028
19658	2028-11-10	2028
19659	2028-11-13	2028
19660	2028-11-14	2028
19661	2028-11-16	2028
19662	2028-11-17	2028
19663	2028-11-20	2028
19664	2028-11-21	2028
19665	2028-11-22	2028
19666	2028-11-23	2028
19667	2028-11-24	2028
19668	2028-11-27	2028
19669	2028-11-28	2028
19670	2028-11-29	2028
19671	2028-11-30	2028
19672	2028-12-01	2028
19673	2028-12-04	2028
19674	2028-12-05	2028
19675	2028-12-06	2028
19676	2028-12-07	2028
19677	2028-12-08	2028
19678	2028-12-11	2028
19679	2028-12-12	2028
19680	2028-12-13	2028
19681	2028-12-14	2028
19682	2028-12-15	2028
19683	2028-12-18	2028
19684	2028-12-19	2028
19685	2028-12-20	2028
19686	2028-12-21	2028
19687	2028-12-22	2028
19688	2028-12-26	2028
19689	2028-12-27	2028
19690	2028-12-28	2028
19691	2028-12-29	2028
19692	2029-01-02	2029
19693	2029-01-03	2029
19694	2029-01-04	2029
19695	2029-01-05	2029
19696	2029-01-08	2029
19697	2029-01-09	2029
19698	2029-01-10	2029
19699	2029-01-11	2029
19700	2029-01-12	2029
19701	2029-01-15	2029
19702	2029-01-16	2029
19703	2029-01-17	2029
19704	2029-01-18	2029
19705	2029-01-19	2029
19706	2029-01-22	2029
19707	2029-01-23	2029
19708	2029-01-24	2029
19709	2029-01-25	2029
19710	2029-01-26	2029
19711	2029-01-29	2029
19712	2029-01-30	2029
19713	2029-01-31	2029
19714	2029-02-01	2029
19715	2029-02-02	2029
19716	2029-02-05	2029
19717	2029-02-06	2029
19718	2029-02-07	2029
19719	2029-02-08	2029
19720	2029-02-09	2029
19721	2029-02-12	2029
19722	2029-02-14	2029
19723	2029-02-15	2029
19724	2029-02-16	2029
19725	2029-02-19	2029
19726	2029-02-20	2029
19727	2029-02-21	2029
19728	2029-02-22	2029
19729	2029-02-23	2029
19730	2029-02-26	2029
19731	2029-02-27	2029
19732	2029-02-28	2029
19733	2029-03-01	2029
19734	2029-03-02	2029
19735	2029-03-05	2029
19736	2029-03-06	2029
19737	2029-03-07	2029
19738	2029-03-08	2029
19739	2029-03-09	2029
19740	2029-03-12	2029
19741	2029-03-13	2029
19742	2029-03-14	2029
19743	2029-03-15	2029
19744	2029-03-16	2029
19745	2029-03-19	2029
19746	2029-03-20	2029
19747	2029-03-21	2029
19748	2029-03-22	2029
19749	2029-03-23	2029
19750	2029-03-26	2029
19751	2029-03-27	2029
19752	2029-03-28	2029
19753	2029-03-29	2029
19754	2029-03-30	2029
19755	2029-04-02	2029
19756	2029-04-03	2029
19757	2029-04-04	2029
19758	2029-04-05	2029
19759	2029-04-06	2029
19760	2029-04-09	2029
19761	2029-04-10	2029
19762	2029-04-11	2029
19763	2029-04-12	2029
19764	2029-04-13	2029
19765	2029-04-16	2029
19766	2029-04-17	2029
19767	2029-04-18	2029
19768	2029-04-19	2029
19769	2029-04-20	2029
19770	2029-04-23	2029
19771	2029-04-24	2029
19772	2029-04-25	2029
19773	2029-04-26	2029
19774	2029-04-27	2029
19775	2029-04-30	2029
19776	2029-05-02	2029
19777	2029-05-03	2029
19778	2029-05-04	2029
19779	2029-05-07	2029
19780	2029-05-08	2029
19781	2029-05-09	2029
19782	2029-05-10	2029
19783	2029-05-11	2029
19784	2029-05-14	2029
19785	2029-05-15	2029
19786	2029-05-16	2029
19787	2029-05-17	2029
19788	2029-05-18	2029
19789	2029-05-21	2029
19790	2029-05-22	2029
19791	2029-05-23	2029
19792	2029-05-24	2029
19793	2029-05-25	2029
19794	2029-05-28	2029
19795	2029-05-29	2029
19796	2029-05-30	2029
19797	2029-06-01	2029
19798	2029-06-04	2029
19799	2029-06-05	2029
19800	2029-06-06	2029
19801	2029-06-07	2029
19802	2029-06-08	2029
19803	2029-06-11	2029
19804	2029-06-12	2029
19805	2029-06-13	2029
19806	2029-06-14	2029
19807	2029-06-15	2029
19808	2029-06-18	2029
19809	2029-06-19	2029
19810	2029-06-20	2029
19811	2029-06-21	2029
19812	2029-06-22	2029
19813	2029-06-25	2029
19814	2029-06-26	2029
19815	2029-06-27	2029
19816	2029-06-28	2029
19817	2029-06-29	2029
19818	2029-07-02	2029
19819	2029-07-03	2029
19820	2029-07-04	2029
19821	2029-07-05	2029
19822	2029-07-06	2029
19823	2029-07-09	2029
19824	2029-07-10	2029
19825	2029-07-11	2029
19826	2029-07-12	2029
19827	2029-07-13	2029
19828	2029-07-16	2029
19829	2029-07-17	2029
19830	2029-07-18	2029
19831	2029-07-19	2029
19832	2029-07-20	2029
19833	2029-07-23	2029
19834	2029-07-24	2029
19835	2029-07-25	2029
19836	2029-07-26	2029
19837	2029-07-27	2029
19838	2029-07-30	2029
19839	2029-07-31	2029
19840	2029-08-01	2029
19841	2029-08-02	2029
19842	2029-08-03	2029
19843	2029-08-06	2029
19844	2029-08-07	2029
19845	2029-08-08	2029
19846	2029-08-09	2029
19847	2029-08-10	2029
19848	2029-08-13	2029
19849	2029-08-14	2029
19850	2029-08-15	2029
19851	2029-08-16	2029
19852	2029-08-17	2029
19853	2029-08-20	2029
19854	2029-08-21	2029
19855	2029-08-22	2029
19856	2029-08-23	2029
19857	2029-08-24	2029
19858	2029-08-27	2029
19859	2029-08-28	2029
19860	2029-08-29	2029
19861	2029-08-30	2029
19862	2029-08-31	2029
19863	2029-09-03	2029
19864	2029-09-04	2029
19865	2029-09-05	2029
19866	2029-09-06	2029
19867	2029-09-10	2029
19868	2029-09-11	2029
19869	2029-09-12	2029
19870	2029-09-13	2029
19871	2029-09-14	2029
19872	2029-09-17	2029
19873	2029-09-18	2029
19874	2029-09-19	2029
19875	2029-09-20	2029
19876	2029-09-21	2029
19877	2029-09-24	2029
19878	2029-09-25	2029
19879	2029-09-26	2029
19880	2029-09-27	2029
19881	2029-09-28	2029
19882	2029-10-01	2029
19883	2029-10-02	2029
19884	2029-10-03	2029
19885	2029-10-04	2029
19886	2029-10-05	2029
19887	2029-10-08	2029
19888	2029-10-09	2029
19889	2029-10-10	2029
19890	2029-10-11	2029
19891	2029-10-15	2029
19892	2029-10-16	2029
19893	2029-10-17	2029
19894	2029-10-18	2029
19895	2029-10-19	2029
19896	2029-10-22	2029
19897	2029-10-23	2029
19898	2029-10-24	2029
19899	2029-10-25	2029
19900	2029-10-26	2029
19901	2029-10-29	2029
19902	2029-10-30	2029
19903	2029-10-31	2029
19904	2029-11-01	2029
19905	2029-11-05	2029
19906	2029-11-06	2029
19907	2029-11-07	2029
19908	2029-11-08	2029
19909	2029-11-09	2029
19910	2029-11-12	2029
19911	2029-11-13	2029
19912	2029-11-14	2029
19913	2029-11-16	2029
19914	2029-11-19	2029
19915	2029-11-20	2029
19916	2029-11-21	2029
19917	2029-11-22	2029
19918	2029-11-23	2029
19919	2029-11-26	2029
19920	2029-11-27	2029
19921	2029-11-28	2029
19922	2029-11-29	2029
19923	2029-11-30	2029
19924	2029-12-03	2029
19925	2029-12-04	2029
19926	2029-12-05	2029
19927	2029-12-06	2029
19928	2029-12-07	2029
19929	2029-12-10	2029
19930	2029-12-11	2029
19931	2029-12-12	2029
19932	2029-12-13	2029
19933	2029-12-14	2029
19934	2029-12-17	2029
19935	2029-12-18	2029
19936	2029-12-19	2029
19937	2029-12-20	2029
19938	2029-12-21	2029
19939	2029-12-24	2029
19940	2029-12-26	2029
19941	2029-12-27	2029
19942	2029-12-28	2029
19943	2029-12-31	2029
19944	2030-01-02	2030
19945	2030-01-03	2030
19946	2030-01-04	2030
19947	2030-01-07	2030
19948	2030-01-08	2030
19949	2030-01-09	2030
19950	2030-01-10	2030
19951	2030-01-11	2030
19952	2030-01-14	2030
19953	2030-01-15	2030
19954	2030-01-16	2030
19955	2030-01-17	2030
19956	2030-01-18	2030
19957	2030-01-21	2030
19958	2030-01-22	2030
19959	2030-01-23	2030
19960	2030-01-24	2030
19961	2030-01-25	2030
19962	2030-01-28	2030
19963	2030-01-29	2030
19964	2030-01-30	2030
19965	2030-01-31	2030
19966	2030-02-01	2030
19967	2030-02-04	2030
19968	2030-02-05	2030
19969	2030-02-06	2030
19970	2030-02-07	2030
19971	2030-02-08	2030
19972	2030-02-11	2030
19973	2030-02-12	2030
19974	2030-02-13	2030
19975	2030-02-14	2030
19976	2030-02-15	2030
19977	2030-02-18	2030
19978	2030-02-19	2030
19979	2030-02-20	2030
19980	2030-02-21	2030
19981	2030-02-22	2030
19982	2030-02-25	2030
19983	2030-02-26	2030
19984	2030-02-27	2030
19985	2030-02-28	2030
19986	2030-03-01	2030
19987	2030-03-04	2030
19988	2030-03-06	2030
19989	2030-03-07	2030
19990	2030-03-08	2030
19991	2030-03-11	2030
19992	2030-03-12	2030
19993	2030-03-13	2030
19994	2030-03-14	2030
19995	2030-03-15	2030
19996	2030-03-18	2030
19997	2030-03-19	2030
19998	2030-03-20	2030
19999	2030-03-21	2030
20000	2030-03-22	2030
20001	2030-03-25	2030
20002	2030-03-26	2030
20003	2030-03-27	2030
20004	2030-03-28	2030
20005	2030-03-29	2030
20006	2030-04-01	2030
20007	2030-04-02	2030
20008	2030-04-03	2030
20009	2030-04-04	2030
20010	2030-04-05	2030
20011	2030-04-08	2030
20012	2030-04-09	2030
20013	2030-04-10	2030
20014	2030-04-11	2030
20015	2030-04-12	2030
20016	2030-04-15	2030
20017	2030-04-16	2030
20018	2030-04-17	2030
20019	2030-04-18	2030
20020	2030-04-19	2030
20021	2030-04-22	2030
20022	2030-04-23	2030
20023	2030-04-24	2030
20024	2030-04-25	2030
20025	2030-04-26	2030
20026	2030-04-29	2030
20027	2030-04-30	2030
20028	2030-05-02	2030
20029	2030-05-03	2030
20030	2030-05-06	2030
20031	2030-05-07	2030
20032	2030-05-08	2030
20033	2030-05-09	2030
20034	2030-05-10	2030
20035	2030-05-13	2030
20036	2030-05-14	2030
20037	2030-05-15	2030
20038	2030-05-16	2030
20039	2030-05-17	2030
20040	2030-05-20	2030
20041	2030-05-21	2030
20042	2030-05-22	2030
20043	2030-05-23	2030
20044	2030-05-24	2030
20045	2030-05-27	2030
20046	2030-05-28	2030
20047	2030-05-29	2030
20048	2030-05-30	2030
20049	2030-05-31	2030
20050	2030-06-03	2030
20051	2030-06-04	2030
20052	2030-06-05	2030
20053	2030-06-06	2030
20054	2030-06-07	2030
20055	2030-06-10	2030
20056	2030-06-11	2030
20057	2030-06-12	2030
20058	2030-06-13	2030
20059	2030-06-14	2030
20060	2030-06-17	2030
20061	2030-06-18	2030
20062	2030-06-19	2030
20063	2030-06-21	2030
20064	2030-06-24	2030
20065	2030-06-25	2030
20066	2030-06-26	2030
20067	2030-06-27	2030
20068	2030-06-28	2030
20069	2030-07-01	2030
20070	2030-07-02	2030
20071	2030-07-03	2030
20072	2030-07-04	2030
20073	2030-07-05	2030
20074	2030-07-08	2030
20075	2030-07-09	2030
20076	2030-07-10	2030
20077	2030-07-11	2030
20078	2030-07-12	2030
20079	2030-07-15	2030
20080	2030-07-16	2030
20081	2030-07-17	2030
20082	2030-07-18	2030
20083	2030-07-19	2030
20084	2030-07-22	2030
20085	2030-07-23	2030
20086	2030-07-24	2030
20087	2030-07-25	2030
20088	2030-07-26	2030
20089	2030-07-29	2030
20090	2030-07-30	2030
20091	2030-07-31	2030
20092	2030-08-01	2030
20093	2030-08-02	2030
20094	2030-08-05	2030
20095	2030-08-06	2030
20096	2030-08-07	2030
20097	2030-08-08	2030
20098	2030-08-09	2030
20099	2030-08-12	2030
20100	2030-08-13	2030
20101	2030-08-14	2030
20102	2030-08-15	2030
20103	2030-08-16	2030
20104	2030-08-19	2030
20105	2030-08-20	2030
20106	2030-08-21	2030
20107	2030-08-22	2030
20108	2030-08-23	2030
20109	2030-08-26	2030
20110	2030-08-27	2030
20111	2030-08-28	2030
20112	2030-08-29	2030
20113	2030-08-30	2030
20114	2030-09-02	2030
20115	2030-09-03	2030
20116	2030-09-04	2030
20117	2030-09-05	2030
20118	2030-09-06	2030
20119	2030-09-09	2030
20120	2030-09-10	2030
20121	2030-09-11	2030
20122	2030-09-12	2030
20123	2030-09-13	2030
20124	2030-09-16	2030
20125	2030-09-17	2030
20126	2030-09-18	2030
20127	2030-09-19	2030
20128	2030-09-20	2030
20129	2030-09-23	2030
20130	2030-09-24	2030
20131	2030-09-25	2030
20132	2030-09-26	2030
20133	2030-09-27	2030
20134	2030-09-30	2030
20135	2030-10-01	2030
20136	2030-10-02	2030
20137	2030-10-03	2030
20138	2030-10-04	2030
20139	2030-10-07	2030
20140	2030-10-08	2030
20141	2030-10-09	2030
20142	2030-10-10	2030
20143	2030-10-11	2030
20144	2030-10-14	2030
20145	2030-10-15	2030
20146	2030-10-16	2030
20147	2030-10-17	2030
20148	2030-10-18	2030
20149	2030-10-21	2030
20150	2030-10-22	2030
20151	2030-10-23	2030
20152	2030-10-24	2030
20153	2030-10-25	2030
20154	2030-10-29	2030
20155	2030-10-30	2030
20156	2030-10-31	2030
20157	2030-11-01	2030
20158	2030-11-04	2030
20159	2030-11-05	2030
20160	2030-11-06	2030
20161	2030-11-07	2030
20162	2030-11-08	2030
20163	2030-11-11	2030
20164	2030-11-12	2030
20165	2030-11-13	2030
20166	2030-11-14	2030
20167	2030-11-18	2030
20168	2030-11-19	2030
20169	2030-11-20	2030
20170	2030-11-21	2030
20171	2030-11-22	2030
20172	2030-11-25	2030
20173	2030-11-26	2030
20174	2030-11-27	2030
20175	2030-11-28	2030
20176	2030-11-29	2030
20177	2030-12-02	2030
20178	2030-12-03	2030
20179	2030-12-04	2030
20180	2030-12-05	2030
20181	2030-12-06	2030
20182	2030-12-09	2030
20183	2030-12-10	2030
20184	2030-12-11	2030
20185	2030-12-12	2030
20186	2030-12-13	2030
20187	2030-12-16	2030
20188	2030-12-17	2030
20189	2030-12-18	2030
20190	2030-12-19	2030
20191	2030-12-20	2030
20192	2030-12-23	2030
20193	2030-12-24	2030
20194	2030-12-26	2030
20195	2030-12-27	2030
20196	2030-12-30	2030
20197	2030-12-31	2030
20198	2031-01-02	2031
20199	2031-01-03	2031
20200	2031-01-06	2031
20201	2031-01-07	2031
20202	2031-01-08	2031
20203	2031-01-09	2031
20204	2031-01-10	2031
20205	2031-01-13	2031
20206	2031-01-14	2031
20207	2031-01-15	2031
20208	2031-01-16	2031
20209	2031-01-17	2031
20210	2031-01-20	2031
20211	2031-01-21	2031
20212	2031-01-22	2031
20213	2031-01-23	2031
20214	2031-01-24	2031
20215	2031-01-27	2031
20216	2031-01-28	2031
20217	2031-01-29	2031
20218	2031-01-30	2031
20219	2031-01-31	2031
20220	2031-02-03	2031
20221	2031-02-04	2031
20222	2031-02-05	2031
20223	2031-02-06	2031
20224	2031-02-07	2031
20225	2031-02-10	2031
20226	2031-02-11	2031
20227	2031-02-12	2031
20228	2031-02-13	2031
20229	2031-02-14	2031
20230	2031-02-17	2031
20231	2031-02-18	2031
20232	2031-02-19	2031
20233	2031-02-20	2031
20234	2031-02-21	2031
20235	2031-02-24	2031
20236	2031-02-26	2031
20237	2031-02-27	2031
20238	2031-02-28	2031
20239	2031-03-03	2031
20240	2031-03-04	2031
20241	2031-03-05	2031
20242	2031-03-06	2031
20243	2031-03-07	2031
20244	2031-03-10	2031
20245	2031-03-11	2031
20246	2031-03-12	2031
20247	2031-03-13	2031
20248	2031-03-14	2031
20249	2031-03-17	2031
20250	2031-03-18	2031
20251	2031-03-19	2031
20252	2031-03-20	2031
20253	2031-03-21	2031
20254	2031-03-24	2031
20255	2031-03-25	2031
20256	2031-03-26	2031
20257	2031-03-27	2031
20258	2031-03-28	2031
20259	2031-03-31	2031
20260	2031-04-01	2031
20261	2031-04-02	2031
20262	2031-04-03	2031
20263	2031-04-04	2031
20264	2031-04-07	2031
20265	2031-04-08	2031
20266	2031-04-09	2031
20267	2031-04-10	2031
20268	2031-04-11	2031
20269	2031-04-14	2031
20270	2031-04-15	2031
20271	2031-04-16	2031
20272	2031-04-17	2031
20273	2031-04-18	2031
20274	2031-04-22	2031
20275	2031-04-23	2031
20276	2031-04-24	2031
20277	2031-04-25	2031
20278	2031-04-28	2031
20279	2031-04-29	2031
20280	2031-04-30	2031
20281	2031-05-02	2031
20282	2031-05-05	2031
20283	2031-05-06	2031
20284	2031-05-07	2031
20285	2031-05-08	2031
20286	2031-05-09	2031
20287	2031-05-12	2031
20288	2031-05-13	2031
20289	2031-05-14	2031
20290	2031-05-15	2031
20291	2031-05-16	2031
20292	2031-05-19	2031
20293	2031-05-20	2031
20294	2031-05-21	2031
20295	2031-05-22	2031
20296	2031-05-23	2031
20297	2031-05-26	2031
20298	2031-05-27	2031
20299	2031-05-28	2031
20300	2031-05-29	2031
20301	2031-05-30	2031
20302	2031-06-02	2031
20303	2031-06-03	2031
20304	2031-06-04	2031
20305	2031-06-05	2031
20306	2031-06-06	2031
20307	2031-06-09	2031
20308	2031-06-10	2031
20309	2031-06-11	2031
20310	2031-06-13	2031
20311	2031-06-16	2031
20312	2031-06-17	2031
20313	2031-06-18	2031
20314	2031-06-19	2031
20315	2031-06-20	2031
20316	2031-06-23	2031
20317	2031-06-24	2031
20318	2031-06-25	2031
20319	2031-06-26	2031
20320	2031-06-27	2031
20321	2031-06-30	2031
20322	2031-07-01	2031
20323	2031-07-02	2031
20324	2031-07-03	2031
20325	2031-07-04	2031
20326	2031-07-07	2031
20327	2031-07-08	2031
20328	2031-07-09	2031
20329	2031-07-10	2031
20330	2031-07-11	2031
20331	2031-07-14	2031
20332	2031-07-15	2031
20333	2031-07-16	2031
20334	2031-07-17	2031
20335	2031-07-18	2031
20336	2031-07-21	2031
20337	2031-07-22	2031
20338	2031-07-23	2031
20339	2031-07-24	2031
20340	2031-07-25	2031
20341	2031-07-28	2031
20342	2031-07-29	2031
20343	2031-07-30	2031
20344	2031-07-31	2031
20345	2031-08-01	2031
20346	2031-08-04	2031
20347	2031-08-05	2031
20348	2031-08-06	2031
20349	2031-08-07	2031
20350	2031-08-08	2031
20351	2031-08-11	2031
20352	2031-08-12	2031
20353	2031-08-13	2031
20354	2031-08-14	2031
20355	2031-08-15	2031
20356	2031-08-18	2031
20357	2031-08-19	2031
20358	2031-08-20	2031
20359	2031-08-21	2031
20360	2031-08-22	2031
20361	2031-08-25	2031
20362	2031-08-26	2031
20363	2031-08-27	2031
20364	2031-08-28	2031
20365	2031-08-29	2031
20366	2031-09-01	2031
20367	2031-09-02	2031
20368	2031-09-03	2031
20369	2031-09-04	2031
20370	2031-09-05	2031
20371	2031-09-08	2031
20372	2031-09-09	2031
20373	2031-09-10	2031
20374	2031-09-11	2031
20375	2031-09-12	2031
20376	2031-09-15	2031
20377	2031-09-16	2031
20378	2031-09-17	2031
20379	2031-09-18	2031
20380	2031-09-19	2031
20381	2031-09-22	2031
20382	2031-09-23	2031
20383	2031-09-24	2031
20384	2031-09-25	2031
20385	2031-09-26	2031
20386	2031-09-29	2031
20387	2031-09-30	2031
20388	2031-10-01	2031
20389	2031-10-02	2031
20390	2031-10-03	2031
20391	2031-10-06	2031
20392	2031-10-07	2031
20393	2031-10-08	2031
20394	2031-10-09	2031
20395	2031-10-10	2031
20396	2031-10-13	2031
20397	2031-10-14	2031
20398	2031-10-15	2031
20399	2031-10-16	2031
20400	2031-10-17	2031
20401	2031-10-20	2031
20402	2031-10-21	2031
20403	2031-10-22	2031
20404	2031-10-23	2031
20405	2031-10-24	2031
20406	2031-10-27	2031
20407	2031-10-29	2031
20408	2031-10-30	2031
20409	2031-10-31	2031
20410	2031-11-03	2031
20411	2031-11-04	2031
20412	2031-11-05	2031
20413	2031-11-06	2031
20414	2031-11-07	2031
20415	2031-11-10	2031
20416	2031-11-11	2031
20417	2031-11-12	2031
20418	2031-11-13	2031
20419	2031-11-14	2031
20420	2031-11-17	2031
20421	2031-11-18	2031
20422	2031-11-19	2031
20423	2031-11-20	2031
20424	2031-11-21	2031
20425	2031-11-24	2031
20426	2031-11-25	2031
20427	2031-11-26	2031
20428	2031-11-27	2031
20429	2031-11-28	2031
20430	2031-12-01	2031
20431	2031-12-02	2031
20432	2031-12-03	2031
20433	2031-12-04	2031
20434	2031-12-05	2031
20435	2031-12-08	2031
20436	2031-12-09	2031
20437	2031-12-10	2031
20438	2031-12-11	2031
20439	2031-12-12	2031
20440	2031-12-15	2031
20441	2031-12-16	2031
20442	2031-12-17	2031
20443	2031-12-18	2031
20444	2031-12-19	2031
20445	2031-12-22	2031
20446	2031-12-23	2031
20447	2031-12-24	2031
20448	2031-12-26	2031
20449	2031-12-29	2031
20450	2031-12-30	2031
20451	2031-12-31	2031
20452	2032-01-02	2032
20453	2032-01-05	2032
20454	2032-01-06	2032
20455	2032-01-07	2032
20456	2032-01-08	2032
20457	2032-01-09	2032
20458	2032-01-12	2032
20459	2032-01-13	2032
20460	2032-01-14	2032
20461	2032-01-15	2032
20462	2032-01-16	2032
20463	2032-01-19	2032
20464	2032-01-20	2032
20465	2032-01-21	2032
20466	2032-01-22	2032
20467	2032-01-23	2032
20468	2032-01-26	2032
20469	2032-01-27	2032
20470	2032-01-28	2032
20471	2032-01-29	2032
20472	2032-01-30	2032
20473	2032-02-02	2032
20474	2032-02-03	2032
20475	2032-02-04	2032
20476	2032-02-05	2032
20477	2032-02-06	2032
20478	2032-02-09	2032
20479	2032-02-11	2032
20480	2032-02-12	2032
20481	2032-02-13	2032
20482	2032-02-16	2032
20483	2032-02-17	2032
20484	2032-02-18	2032
20485	2032-02-19	2032
20486	2032-02-20	2032
20487	2032-02-23	2032
20488	2032-02-24	2032
20489	2032-02-25	2032
20490	2032-02-26	2032
20491	2032-02-27	2032
20492	2032-03-01	2032
20493	2032-03-02	2032
20494	2032-03-03	2032
20495	2032-03-04	2032
20496	2032-03-05	2032
20497	2032-03-08	2032
20498	2032-03-09	2032
20499	2032-03-10	2032
20500	2032-03-11	2032
20501	2032-03-12	2032
20502	2032-03-15	2032
20503	2032-03-16	2032
20504	2032-03-17	2032
20505	2032-03-18	2032
20506	2032-03-19	2032
20507	2032-03-22	2032
20508	2032-03-23	2032
20509	2032-03-24	2032
20510	2032-03-25	2032
20511	2032-03-26	2032
20512	2032-03-29	2032
20513	2032-03-30	2032
20514	2032-03-31	2032
20515	2032-04-01	2032
20516	2032-04-02	2032
20517	2032-04-05	2032
20518	2032-04-06	2032
20519	2032-04-07	2032
20520	2032-04-08	2032
20521	2032-04-09	2032
20522	2032-04-12	2032
20523	2032-04-13	2032
20524	2032-04-14	2032
20525	2032-04-15	2032
20526	2032-04-16	2032
20527	2032-04-19	2032
20528	2032-04-20	2032
20529	2032-04-22	2032
20530	2032-04-23	2032
20531	2032-04-26	2032
20532	2032-04-27	2032
20533	2032-04-28	2032
20534	2032-04-29	2032
20535	2032-04-30	2032
20536	2032-05-03	2032
20537	2032-05-04	2032
20538	2032-05-05	2032
20539	2032-05-06	2032
20540	2032-05-07	2032
20541	2032-05-10	2032
20542	2032-05-11	2032
20543	2032-05-12	2032
20544	2032-05-13	2032
20545	2032-05-14	2032
20546	2032-05-17	2032
20547	2032-05-18	2032
20548	2032-05-19	2032
20549	2032-05-20	2032
20550	2032-05-21	2032
20551	2032-05-24	2032
20552	2032-05-25	2032
20553	2032-05-26	2032
20554	2032-05-28	2032
20555	2032-05-31	2032
20556	2032-06-01	2032
20557	2032-06-02	2032
20558	2032-06-03	2032
20559	2032-06-04	2032
20560	2032-06-07	2032
20561	2032-06-08	2032
20562	2032-06-09	2032
20563	2032-06-10	2032
20564	2032-06-11	2032
20565	2032-06-14	2032
20566	2032-06-15	2032
20567	2032-06-16	2032
20568	2032-06-17	2032
20569	2032-06-18	2032
20570	2032-06-21	2032
20571	2032-06-22	2032
20572	2032-06-23	2032
20573	2032-06-24	2032
20574	2032-06-25	2032
20575	2032-06-28	2032
20576	2032-06-29	2032
20577	2032-06-30	2032
20578	2032-07-01	2032
20579	2032-07-02	2032
20580	2032-07-05	2032
20581	2032-07-06	2032
20582	2032-07-07	2032
20583	2032-07-08	2032
20584	2032-07-09	2032
20585	2032-07-12	2032
20586	2032-07-13	2032
20587	2032-07-14	2032
20588	2032-07-15	2032
20589	2032-07-16	2032
20590	2032-07-19	2032
20591	2032-07-20	2032
20592	2032-07-21	2032
20593	2032-07-22	2032
20594	2032-07-23	2032
20595	2032-07-26	2032
20596	2032-07-27	2032
20597	2032-07-28	2032
20598	2032-07-29	2032
20599	2032-07-30	2032
20600	2032-08-02	2032
20601	2032-08-03	2032
20602	2032-08-04	2032
20603	2032-08-05	2032
20604	2032-08-06	2032
20605	2032-08-09	2032
20606	2032-08-10	2032
20607	2032-08-11	2032
20608	2032-08-12	2032
20609	2032-08-13	2032
20610	2032-08-16	2032
20611	2032-08-17	2032
20612	2032-08-18	2032
20613	2032-08-19	2032
20614	2032-08-20	2032
20615	2032-08-23	2032
20616	2032-08-24	2032
20617	2032-08-25	2032
20618	2032-08-26	2032
20619	2032-08-27	2032
20620	2032-08-30	2032
20621	2032-08-31	2032
20622	2032-09-01	2032
20623	2032-09-02	2032
20624	2032-09-03	2032
20625	2032-09-06	2032
20626	2032-09-08	2032
20627	2032-09-09	2032
20628	2032-09-10	2032
20629	2032-09-13	2032
20630	2032-09-14	2032
20631	2032-09-15	2032
20632	2032-09-16	2032
20633	2032-09-17	2032
20634	2032-09-20	2032
20635	2032-09-21	2032
20636	2032-09-22	2032
20637	2032-09-23	2032
20638	2032-09-24	2032
20639	2032-09-27	2032
20640	2032-09-28	2032
20641	2032-09-29	2032
20642	2032-09-30	2032
20643	2032-10-01	2032
20644	2032-10-04	2032
20645	2032-10-05	2032
20646	2032-10-06	2032
20647	2032-10-07	2032
20648	2032-10-08	2032
20649	2032-10-11	2032
20650	2032-10-13	2032
20651	2032-10-14	2032
20652	2032-10-15	2032
20653	2032-10-18	2032
20654	2032-10-19	2032
20655	2032-10-20	2032
20656	2032-10-21	2032
20657	2032-10-22	2032
20658	2032-10-25	2032
20659	2032-10-26	2032
20660	2032-10-27	2032
20661	2032-10-29	2032
20662	2032-11-01	2032
20663	2032-11-03	2032
20664	2032-11-04	2032
20665	2032-11-05	2032
20666	2032-11-08	2032
20667	2032-11-09	2032
20668	2032-11-10	2032
20669	2032-11-11	2032
20670	2032-11-12	2032
20671	2032-11-16	2032
20672	2032-11-17	2032
20673	2032-11-18	2032
20674	2032-11-19	2032
20675	2032-11-22	2032
20676	2032-11-23	2032
20677	2032-11-24	2032
20678	2032-11-25	2032
20679	2032-11-26	2032
20680	2032-11-29	2032
20681	2032-11-30	2032
20682	2032-12-01	2032
20683	2032-12-02	2032
20684	2032-12-03	2032
20685	2032-12-06	2032
20686	2032-12-07	2032
20687	2032-12-08	2032
20688	2032-12-09	2032
20689	2032-12-10	2032
20690	2032-12-13	2032
20691	2032-12-14	2032
20692	2032-12-15	2032
20693	2032-12-16	2032
20694	2032-12-17	2032
20695	2032-12-20	2032
20696	2032-12-21	2032
20697	2032-12-22	2032
20698	2032-12-23	2032
20699	2032-12-24	2032
20700	2032-12-27	2032
20701	2032-12-28	2032
20702	2032-12-29	2032
20703	2032-12-30	2032
20704	2032-12-31	2032
20705	2033-01-03	2033
20706	2033-01-04	2033
20707	2033-01-05	2033
20708	2033-01-06	2033
20709	2033-01-07	2033
20710	2033-01-10	2033
20711	2033-01-11	2033
20712	2033-01-12	2033
20713	2033-01-13	2033
20714	2033-01-14	2033
20715	2033-01-17	2033
20716	2033-01-18	2033
20717	2033-01-19	2033
20718	2033-01-20	2033
20719	2033-01-21	2033
20720	2033-01-24	2033
20721	2033-01-25	2033
20722	2033-01-26	2033
20723	2033-01-27	2033
20724	2033-01-28	2033
20725	2033-01-31	2033
20726	2033-02-01	2033
20727	2033-02-02	2033
20728	2033-02-03	2033
20729	2033-02-04	2033
20730	2033-02-07	2033
20731	2033-02-08	2033
20732	2033-02-09	2033
20733	2033-02-10	2033
20734	2033-02-11	2033
20735	2033-02-14	2033
20736	2033-02-15	2033
20737	2033-02-16	2033
20738	2033-02-17	2033
20739	2033-02-18	2033
20740	2033-02-21	2033
20741	2033-02-22	2033
20742	2033-02-23	2033
20743	2033-02-24	2033
20744	2033-02-25	2033
20745	2033-02-28	2033
20746	2033-03-02	2033
20747	2033-03-03	2033
20748	2033-03-04	2033
20749	2033-03-07	2033
20750	2033-03-08	2033
20751	2033-03-09	2033
20752	2033-03-10	2033
20753	2033-03-11	2033
20754	2033-03-14	2033
20755	2033-03-15	2033
20756	2033-03-16	2033
20757	2033-03-17	2033
20758	2033-03-18	2033
20759	2033-03-21	2033
20760	2033-03-22	2033
20761	2033-03-23	2033
20762	2033-03-24	2033
20763	2033-03-25	2033
20764	2033-03-28	2033
20765	2033-03-29	2033
20766	2033-03-30	2033
20767	2033-03-31	2033
20768	2033-04-01	2033
20769	2033-04-04	2033
20770	2033-04-05	2033
20771	2033-04-06	2033
20772	2033-04-07	2033
20773	2033-04-08	2033
20774	2033-04-11	2033
20775	2033-04-12	2033
20776	2033-04-13	2033
20777	2033-04-14	2033
20778	2033-04-15	2033
20779	2033-04-18	2033
20780	2033-04-19	2033
20781	2033-04-20	2033
20782	2033-04-22	2033
20783	2033-04-25	2033
20784	2033-04-26	2033
20785	2033-04-27	2033
20786	2033-04-28	2033
20787	2033-04-29	2033
20788	2033-05-02	2033
20789	2033-05-03	2033
20790	2033-05-04	2033
20791	2033-05-05	2033
20792	2033-05-06	2033
20793	2033-05-09	2033
20794	2033-05-10	2033
20795	2033-05-11	2033
20796	2033-05-12	2033
20797	2033-05-13	2033
20798	2033-05-16	2033
20799	2033-05-17	2033
20800	2033-05-18	2033
20801	2033-05-19	2033
20802	2033-05-20	2033
20803	2033-05-23	2033
20804	2033-05-24	2033
20805	2033-05-25	2033
20806	2033-05-26	2033
20807	2033-05-27	2033
20808	2033-05-30	2033
20809	2033-05-31	2033
20810	2033-06-01	2033
20811	2033-06-02	2033
20812	2033-06-03	2033
20813	2033-06-06	2033
20814	2033-06-07	2033
20815	2033-06-08	2033
20816	2033-06-09	2033
20817	2033-06-10	2033
20818	2033-06-13	2033
20819	2033-06-14	2033
20820	2033-06-15	2033
20821	2033-06-17	2033
20822	2033-06-20	2033
20823	2033-06-21	2033
20824	2033-06-22	2033
20825	2033-06-23	2033
20826	2033-06-24	2033
20827	2033-06-27	2033
20828	2033-06-28	2033
20829	2033-06-29	2033
20830	2033-06-30	2033
20831	2033-07-01	2033
20832	2033-07-04	2033
20833	2033-07-05	2033
20834	2033-07-06	2033
20835	2033-07-07	2033
20836	2033-07-08	2033
20837	2033-07-11	2033
20838	2033-07-12	2033
20839	2033-07-13	2033
20840	2033-07-14	2033
20841	2033-07-15	2033
20842	2033-07-18	2033
20843	2033-07-19	2033
20844	2033-07-20	2033
20845	2033-07-21	2033
20846	2033-07-22	2033
20847	2033-07-25	2033
20848	2033-07-26	2033
20849	2033-07-27	2033
20850	2033-07-28	2033
20851	2033-07-29	2033
20852	2033-08-01	2033
20853	2033-08-02	2033
20854	2033-08-03	2033
20855	2033-08-04	2033
20856	2033-08-05	2033
20857	2033-08-08	2033
20858	2033-08-09	2033
20859	2033-08-10	2033
20860	2033-08-11	2033
20861	2033-08-12	2033
20862	2033-08-15	2033
20863	2033-08-16	2033
20864	2033-08-17	2033
20865	2033-08-18	2033
20866	2033-08-19	2033
20867	2033-08-22	2033
20868	2033-08-23	2033
20869	2033-08-24	2033
20870	2033-08-25	2033
20871	2033-08-26	2033
20872	2033-08-29	2033
20873	2033-08-30	2033
20874	2033-08-31	2033
20875	2033-09-01	2033
20876	2033-09-02	2033
20877	2033-09-05	2033
20878	2033-09-06	2033
20879	2033-09-08	2033
20880	2033-09-09	2033
20881	2033-09-12	2033
20882	2033-09-13	2033
20883	2033-09-14	2033
20884	2033-09-15	2033
20885	2033-09-16	2033
20886	2033-09-19	2033
20887	2033-09-20	2033
20888	2033-09-21	2033
20889	2033-09-22	2033
20890	2033-09-23	2033
20891	2033-09-26	2033
20892	2033-09-27	2033
20893	2033-09-28	2033
20894	2033-09-29	2033
20895	2033-09-30	2033
20896	2033-10-03	2033
20897	2033-10-04	2033
20898	2033-10-05	2033
20899	2033-10-06	2033
20900	2033-10-07	2033
20901	2033-10-10	2033
20902	2033-10-11	2033
20903	2033-10-13	2033
20904	2033-10-14	2033
20905	2033-10-17	2033
20906	2033-10-18	2033
20907	2033-10-19	2033
20908	2033-10-20	2033
20909	2033-10-21	2033
20910	2033-10-24	2033
20911	2033-10-25	2033
20912	2033-10-26	2033
20913	2033-10-27	2033
20914	2033-10-31	2033
20915	2033-11-01	2033
20916	2033-11-03	2033
20917	2033-11-04	2033
20918	2033-11-07	2033
20919	2033-11-08	2033
20920	2033-11-09	2033
20921	2033-11-10	2033
20922	2033-11-11	2033
20923	2033-11-14	2033
20924	2033-11-16	2033
20925	2033-11-17	2033
20926	2033-11-18	2033
20927	2033-11-21	2033
20928	2033-11-22	2033
20929	2033-11-23	2033
20930	2033-11-24	2033
20931	2033-11-25	2033
20932	2033-11-28	2033
20933	2033-11-29	2033
20934	2033-11-30	2033
20935	2033-12-01	2033
20936	2033-12-02	2033
20937	2033-12-05	2033
20938	2033-12-06	2033
20939	2033-12-07	2033
20940	2033-12-08	2033
20941	2033-12-09	2033
20942	2033-12-12	2033
20943	2033-12-13	2033
20944	2033-12-14	2033
20945	2033-12-15	2033
20946	2033-12-16	2033
20947	2033-12-19	2033
20948	2033-12-20	2033
20949	2033-12-21	2033
20950	2033-12-22	2033
20951	2033-12-23	2033
20952	2033-12-26	2033
20953	2033-12-27	2033
20954	2033-12-28	2033
20955	2033-12-29	2033
20956	2033-12-30	2033
20957	2034-01-02	2034
20958	2034-01-03	2034
20959	2034-01-04	2034
20960	2034-01-05	2034
20961	2034-01-06	2034
20962	2034-01-09	2034
20963	2034-01-10	2034
20964	2034-01-11	2034
20965	2034-01-12	2034
20966	2034-01-13	2034
20967	2034-01-16	2034
20968	2034-01-17	2034
20969	2034-01-18	2034
20970	2034-01-19	2034
20971	2034-01-20	2034
20972	2034-01-23	2034
20973	2034-01-24	2034
20974	2034-01-25	2034
20975	2034-01-26	2034
20976	2034-01-27	2034
20977	2034-01-30	2034
20978	2034-01-31	2034
20979	2034-02-01	2034
20980	2034-02-02	2034
20981	2034-02-03	2034
20982	2034-02-06	2034
20983	2034-02-07	2034
20984	2034-02-08	2034
20985	2034-02-09	2034
20986	2034-02-10	2034
20987	2034-02-13	2034
20988	2034-02-14	2034
20989	2034-02-15	2034
20990	2034-02-16	2034
20991	2034-02-17	2034
20992	2034-02-20	2034
20993	2034-02-22	2034
20994	2034-02-23	2034
20995	2034-02-24	2034
20996	2034-02-27	2034
20997	2034-02-28	2034
20998	2034-03-01	2034
20999	2034-03-02	2034
21000	2034-03-03	2034
21001	2034-03-06	2034
21002	2034-03-07	2034
21003	2034-03-08	2034
21004	2034-03-09	2034
21005	2034-03-10	2034
21006	2034-03-13	2034
21007	2034-03-14	2034
21008	2034-03-15	2034
21009	2034-03-16	2034
21010	2034-03-17	2034
21011	2034-03-20	2034
21012	2034-03-21	2034
21013	2034-03-22	2034
21014	2034-03-23	2034
21015	2034-03-24	2034
21016	2034-03-27	2034
21017	2034-03-28	2034
21018	2034-03-29	2034
21019	2034-03-30	2034
21020	2034-03-31	2034
21021	2034-04-03	2034
21022	2034-04-04	2034
21023	2034-04-05	2034
21024	2034-04-06	2034
21025	2034-04-07	2034
21026	2034-04-10	2034
21027	2034-04-11	2034
21028	2034-04-12	2034
21029	2034-04-13	2034
21030	2034-04-14	2034
21031	2034-04-17	2034
21032	2034-04-18	2034
21033	2034-04-19	2034
21034	2034-04-20	2034
21035	2034-04-24	2034
21036	2034-04-25	2034
21037	2034-04-26	2034
21038	2034-04-27	2034
21039	2034-04-28	2034
21040	2034-05-02	2034
21041	2034-05-03	2034
21042	2034-05-04	2034
21043	2034-05-05	2034
21044	2034-05-08	2034
21045	2034-05-09	2034
21046	2034-05-10	2034
21047	2034-05-11	2034
21048	2034-05-12	2034
21049	2034-05-15	2034
21050	2034-05-16	2034
21051	2034-05-17	2034
21052	2034-05-18	2034
21053	2034-05-19	2034
21054	2034-05-22	2034
21055	2034-05-23	2034
21056	2034-05-24	2034
21057	2034-05-25	2034
21058	2034-05-26	2034
21059	2034-05-29	2034
21060	2034-05-30	2034
21061	2034-05-31	2034
21062	2034-06-01	2034
21063	2034-06-02	2034
21064	2034-06-05	2034
21065	2034-06-06	2034
21066	2034-06-07	2034
21067	2034-06-09	2034
21068	2034-06-12	2034
21069	2034-06-13	2034
21070	2034-06-14	2034
21071	2034-06-15	2034
21072	2034-06-16	2034
21073	2034-06-19	2034
21074	2034-06-20	2034
21075	2034-06-21	2034
21076	2034-06-22	2034
21077	2034-06-23	2034
21078	2034-06-26	2034
21079	2034-06-27	2034
21080	2034-06-28	2034
21081	2034-06-29	2034
21082	2034-06-30	2034
21083	2034-07-03	2034
21084	2034-07-04	2034
21085	2034-07-05	2034
21086	2034-07-06	2034
21087	2034-07-07	2034
21088	2034-07-10	2034
21089	2034-07-11	2034
21090	2034-07-12	2034
21091	2034-07-13	2034
21092	2034-07-14	2034
21093	2034-07-17	2034
21094	2034-07-18	2034
21095	2034-07-19	2034
21096	2034-07-20	2034
21097	2034-07-21	2034
21098	2034-07-24	2034
21099	2034-07-25	2034
21100	2034-07-26	2034
21101	2034-07-27	2034
21102	2034-07-28	2034
21103	2034-07-31	2034
21104	2034-08-01	2034
21105	2034-08-02	2034
21106	2034-08-03	2034
21107	2034-08-04	2034
21108	2034-08-07	2034
21109	2034-08-08	2034
21110	2034-08-09	2034
21111	2034-08-10	2034
21112	2034-08-11	2034
21113	2034-08-14	2034
21114	2034-08-15	2034
21115	2034-08-16	2034
21116	2034-08-17	2034
21117	2034-08-18	2034
21118	2034-08-21	2034
21119	2034-08-22	2034
21120	2034-08-23	2034
21121	2034-08-24	2034
21122	2034-08-25	2034
21123	2034-08-28	2034
21124	2034-08-29	2034
21125	2034-08-30	2034
21126	2034-08-31	2034
21127	2034-09-01	2034
21128	2034-09-04	2034
21129	2034-09-05	2034
21130	2034-09-06	2034
21131	2034-09-08	2034
21132	2034-09-11	2034
21133	2034-09-12	2034
21134	2034-09-13	2034
21135	2034-09-14	2034
21136	2034-09-15	2034
21137	2034-09-18	2034
21138	2034-09-19	2034
21139	2034-09-20	2034
21140	2034-09-21	2034
21141	2034-09-22	2034
21142	2034-09-25	2034
21143	2034-09-26	2034
21144	2034-09-27	2034
21145	2034-09-28	2034
21146	2034-09-29	2034
21147	2034-10-02	2034
21148	2034-10-03	2034
21149	2034-10-04	2034
21150	2034-10-05	2034
21151	2034-10-06	2034
21152	2034-10-09	2034
21153	2034-10-10	2034
21154	2034-10-11	2034
21155	2034-10-13	2034
21156	2034-10-16	2034
21157	2034-10-17	2034
21158	2034-10-18	2034
21159	2034-10-19	2034
21160	2034-10-20	2034
21161	2034-10-23	2034
21162	2034-10-24	2034
21163	2034-10-25	2034
21164	2034-10-26	2034
21165	2034-10-27	2034
21166	2034-10-30	2034
21167	2034-10-31	2034
21168	2034-11-01	2034
21169	2034-11-03	2034
21170	2034-11-06	2034
21171	2034-11-07	2034
21172	2034-11-08	2034
21173	2034-11-09	2034
21174	2034-11-10	2034
21175	2034-11-13	2034
21176	2034-11-14	2034
21177	2034-11-16	2034
21178	2034-11-17	2034
21179	2034-11-20	2034
21180	2034-11-21	2034
21181	2034-11-22	2034
21182	2034-11-23	2034
21183	2034-11-24	2034
21184	2034-11-27	2034
21185	2034-11-28	2034
21186	2034-11-29	2034
21187	2034-11-30	2034
21188	2034-12-01	2034
21189	2034-12-04	2034
21190	2034-12-05	2034
21191	2034-12-06	2034
21192	2034-12-07	2034
21193	2034-12-08	2034
21194	2034-12-11	2034
21195	2034-12-12	2034
21196	2034-12-13	2034
21197	2034-12-14	2034
21198	2034-12-15	2034
21199	2034-12-18	2034
21200	2034-12-19	2034
21201	2034-12-20	2034
21202	2034-12-21	2034
21203	2034-12-22	2034
21204	2034-12-26	2034
21205	2034-12-27	2034
21206	2034-12-28	2034
21207	2034-12-29	2034
21208	2035-01-02	2035
21209	2035-01-03	2035
21210	2035-01-04	2035
21211	2035-01-05	2035
21212	2035-01-08	2035
21213	2035-01-09	2035
21214	2035-01-10	2035
21215	2035-01-11	2035
21216	2035-01-12	2035
21217	2035-01-15	2035
21218	2035-01-16	2035
21219	2035-01-17	2035
21220	2035-01-18	2035
21221	2035-01-19	2035
21222	2035-01-22	2035
21223	2035-01-23	2035
21224	2035-01-24	2035
21225	2035-01-25	2035
21226	2035-01-26	2035
21227	2035-01-29	2035
21228	2035-01-30	2035
21229	2035-01-31	2035
21230	2035-02-01	2035
21231	2035-02-02	2035
21232	2035-02-05	2035
21233	2035-02-07	2035
21234	2035-02-08	2035
21235	2035-02-09	2035
21236	2035-02-12	2035
21237	2035-02-13	2035
21238	2035-02-14	2035
21239	2035-02-15	2035
21240	2035-02-16	2035
21241	2035-02-19	2035
21242	2035-02-20	2035
21243	2035-02-21	2035
21244	2035-02-22	2035
21245	2035-02-23	2035
21246	2035-02-26	2035
21247	2035-02-27	2035
21248	2035-02-28	2035
21249	2035-03-01	2035
21250	2035-03-02	2035
21251	2035-03-05	2035
21252	2035-03-06	2035
21253	2035-03-07	2035
21254	2035-03-08	2035
21255	2035-03-09	2035
21256	2035-03-12	2035
21257	2035-03-13	2035
21258	2035-03-14	2035
21259	2035-03-15	2035
21260	2035-03-16	2035
21261	2035-03-19	2035
21262	2035-03-20	2035
21263	2035-03-21	2035
21264	2035-03-22	2035
21265	2035-03-23	2035
21266	2035-03-26	2035
21267	2035-03-27	2035
21268	2035-03-28	2035
21269	2035-03-29	2035
21270	2035-03-30	2035
21271	2035-04-02	2035
21272	2035-04-03	2035
21273	2035-04-04	2035
21274	2035-04-05	2035
21275	2035-04-06	2035
21276	2035-04-09	2035
21277	2035-04-10	2035
21278	2035-04-11	2035
21279	2035-04-12	2035
21280	2035-04-13	2035
21281	2035-04-16	2035
21282	2035-04-17	2035
21283	2035-04-18	2035
21284	2035-04-19	2035
21285	2035-04-20	2035
21286	2035-04-23	2035
21287	2035-04-24	2035
21288	2035-04-25	2035
21289	2035-04-26	2035
21290	2035-04-27	2035
21291	2035-04-30	2035
21292	2035-05-02	2035
21293	2035-05-03	2035
21294	2035-05-04	2035
21295	2035-05-07	2035
21296	2035-05-08	2035
21297	2035-05-09	2035
21298	2035-05-10	2035
21299	2035-05-11	2035
21300	2035-05-14	2035
21301	2035-05-15	2035
21302	2035-05-16	2035
21303	2035-05-17	2035
21304	2035-05-18	2035
21305	2035-05-21	2035
21306	2035-05-22	2035
21307	2035-05-23	2035
21308	2035-05-25	2035
21309	2035-05-28	2035
21310	2035-05-29	2035
21311	2035-05-30	2035
21312	2035-05-31	2035
21313	2035-06-01	2035
21314	2035-06-04	2035
21315	2035-06-05	2035
21316	2035-06-06	2035
21317	2035-06-07	2035
21318	2035-06-08	2035
21319	2035-06-11	2035
21320	2035-06-12	2035
21321	2035-06-13	2035
21322	2035-06-14	2035
21323	2035-06-15	2035
21324	2035-06-18	2035
21325	2035-06-19	2035
21326	2035-06-20	2035
21327	2035-06-21	2035
21328	2035-06-22	2035
21329	2035-06-25	2035
21330	2035-06-26	2035
21331	2035-06-27	2035
21332	2035-06-28	2035
21333	2035-06-29	2035
21334	2035-07-02	2035
21335	2035-07-03	2035
21336	2035-07-04	2035
21337	2035-07-05	2035
21338	2035-07-06	2035
21339	2035-07-09	2035
21340	2035-07-10	2035
21341	2035-07-11	2035
21342	2035-07-12	2035
21343	2035-07-13	2035
21344	2035-07-16	2035
21345	2035-07-17	2035
21346	2035-07-18	2035
21347	2035-07-19	2035
21348	2035-07-20	2035
21349	2035-07-23	2035
21350	2035-07-24	2035
21351	2035-07-25	2035
21352	2035-07-26	2035
21353	2035-07-27	2035
21354	2035-07-30	2035
21355	2035-07-31	2035
21356	2035-08-01	2035
21357	2035-08-02	2035
21358	2035-08-03	2035
21359	2035-08-06	2035
21360	2035-08-07	2035
21361	2035-08-08	2035
21362	2035-08-09	2035
21363	2035-08-10	2035
21364	2035-08-13	2035
21365	2035-08-14	2035
21366	2035-08-15	2035
21367	2035-08-16	2035
21368	2035-08-17	2035
21369	2035-08-20	2035
21370	2035-08-21	2035
21371	2035-08-22	2035
21372	2035-08-23	2035
21373	2035-08-24	2035
21374	2035-08-27	2035
21375	2035-08-28	2035
21376	2035-08-29	2035
21377	2035-08-30	2035
21378	2035-08-31	2035
21379	2035-09-03	2035
21380	2035-09-04	2035
21381	2035-09-05	2035
21382	2035-09-06	2035
21383	2035-09-10	2035
21384	2035-09-11	2035
21385	2035-09-12	2035
21386	2035-09-13	2035
21387	2035-09-14	2035
21388	2035-09-17	2035
21389	2035-09-18	2035
21390	2035-09-19	2035
21391	2035-09-20	2035
21392	2035-09-21	2035
21393	2035-09-24	2035
21394	2035-09-25	2035
21395	2035-09-26	2035
21396	2035-09-27	2035
21397	2035-09-28	2035
21398	2035-10-01	2035
21399	2035-10-02	2035
21400	2035-10-03	2035
21401	2035-10-04	2035
21402	2035-10-05	2035
21403	2035-10-08	2035
21404	2035-10-09	2035
21405	2035-10-10	2035
21406	2035-10-11	2035
21407	2035-10-15	2035
21408	2035-10-16	2035
21409	2035-10-17	2035
21410	2035-10-18	2035
21411	2035-10-19	2035
21412	2035-10-22	2035
21413	2035-10-23	2035
21414	2035-10-24	2035
21415	2035-10-25	2035
21416	2035-10-26	2035
21417	2035-10-29	2035
21418	2035-10-30	2035
21419	2035-10-31	2035
21420	2035-11-01	2035
21421	2035-11-05	2035
21422	2035-11-06	2035
21423	2035-11-07	2035
21424	2035-11-08	2035
21425	2035-11-09	2035
21426	2035-11-12	2035
21427	2035-11-13	2035
21428	2035-11-14	2035
21429	2035-11-16	2035
21430	2035-11-19	2035
21431	2035-11-20	2035
21432	2035-11-21	2035
21433	2035-11-22	2035
21434	2035-11-23	2035
21435	2035-11-26	2035
21436	2035-11-27	2035
21437	2035-11-28	2035
21438	2035-11-29	2035
21439	2035-11-30	2035
21440	2035-12-03	2035
21441	2035-12-04	2035
21442	2035-12-05	2035
21443	2035-12-06	2035
21444	2035-12-07	2035
21445	2035-12-10	2035
21446	2035-12-11	2035
21447	2035-12-12	2035
21448	2035-12-13	2035
21449	2035-12-14	2035
21450	2035-12-17	2035
21451	2035-12-18	2035
21452	2035-12-19	2035
21453	2035-12-20	2035
21454	2035-12-21	2035
21455	2035-12-24	2035
21456	2035-12-26	2035
21457	2035-12-27	2035
21458	2035-12-28	2035
21459	2035-12-31	2035
21460	2036-01-02	2036
21461	2036-01-03	2036
21462	2036-01-04	2036
21463	2036-01-07	2036
21464	2036-01-08	2036
21465	2036-01-09	2036
21466	2036-01-10	2036
21467	2036-01-11	2036
21468	2036-01-14	2036
21469	2036-01-15	2036
21470	2036-01-16	2036
21471	2036-01-17	2036
21472	2036-01-18	2036
21473	2036-01-21	2036
21474	2036-01-22	2036
21475	2036-01-23	2036
21476	2036-01-24	2036
21477	2036-01-25	2036
21478	2036-01-28	2036
21479	2036-01-29	2036
21480	2036-01-30	2036
21481	2036-01-31	2036
21482	2036-02-01	2036
21483	2036-02-04	2036
21484	2036-02-05	2036
21485	2036-02-06	2036
21486	2036-02-07	2036
21487	2036-02-08	2036
21488	2036-02-11	2036
21489	2036-02-12	2036
21490	2036-02-13	2036
21491	2036-02-14	2036
21492	2036-02-15	2036
21493	2036-02-18	2036
21494	2036-02-19	2036
21495	2036-02-20	2036
21496	2036-02-21	2036
21497	2036-02-22	2036
21498	2036-02-25	2036
21499	2036-02-27	2036
21500	2036-02-28	2036
21501	2036-02-29	2036
21502	2036-03-03	2036
21503	2036-03-04	2036
21504	2036-03-05	2036
21505	2036-03-06	2036
21506	2036-03-07	2036
21507	2036-03-10	2036
21508	2036-03-11	2036
21509	2036-03-12	2036
21510	2036-03-13	2036
21511	2036-03-14	2036
21512	2036-03-17	2036
21513	2036-03-18	2036
21514	2036-03-19	2036
21515	2036-03-20	2036
21516	2036-03-21	2036
21517	2036-03-24	2036
21518	2036-03-25	2036
21519	2036-03-26	2036
21520	2036-03-27	2036
21521	2036-03-28	2036
21522	2036-03-31	2036
21523	2036-04-01	2036
21524	2036-04-02	2036
21525	2036-04-03	2036
21526	2036-04-04	2036
21527	2036-04-07	2036
21528	2036-04-08	2036
21529	2036-04-09	2036
21530	2036-04-10	2036
21531	2036-04-11	2036
21532	2036-04-14	2036
21533	2036-04-15	2036
21534	2036-04-16	2036
21535	2036-04-17	2036
21536	2036-04-18	2036
21537	2036-04-22	2036
21538	2036-04-23	2036
21539	2036-04-24	2036
21540	2036-04-25	2036
21541	2036-04-28	2036
21542	2036-04-29	2036
21543	2036-04-30	2036
21544	2036-05-02	2036
21545	2036-05-05	2036
21546	2036-05-06	2036
21547	2036-05-07	2036
21548	2036-05-08	2036
21549	2036-05-09	2036
21550	2036-05-12	2036
21551	2036-05-13	2036
21552	2036-05-14	2036
21553	2036-05-15	2036
21554	2036-05-16	2036
21555	2036-05-19	2036
21556	2036-05-20	2036
21557	2036-05-21	2036
21558	2036-05-22	2036
21559	2036-05-23	2036
21560	2036-05-26	2036
21561	2036-05-27	2036
21562	2036-05-28	2036
21563	2036-05-29	2036
21564	2036-05-30	2036
21565	2036-06-02	2036
21566	2036-06-03	2036
21567	2036-06-04	2036
21568	2036-06-05	2036
21569	2036-06-06	2036
21570	2036-06-09	2036
21571	2036-06-10	2036
21572	2036-06-11	2036
21573	2036-06-13	2036
21574	2036-06-16	2036
21575	2036-06-17	2036
21576	2036-06-18	2036
21577	2036-06-19	2036
21578	2036-06-20	2036
21579	2036-06-23	2036
21580	2036-06-24	2036
21581	2036-06-25	2036
21582	2036-06-26	2036
21583	2036-06-27	2036
21584	2036-06-30	2036
21585	2036-07-01	2036
21586	2036-07-02	2036
21587	2036-07-03	2036
21588	2036-07-04	2036
21589	2036-07-07	2036
21590	2036-07-08	2036
21591	2036-07-09	2036
21592	2036-07-10	2036
21593	2036-07-11	2036
21594	2036-07-14	2036
21595	2036-07-15	2036
21596	2036-07-16	2036
21597	2036-07-17	2036
21598	2036-07-18	2036
21599	2036-07-21	2036
21600	2036-07-22	2036
21601	2036-07-23	2036
21602	2036-07-24	2036
21603	2036-07-25	2036
21604	2036-07-28	2036
21605	2036-07-29	2036
21606	2036-07-30	2036
21607	2036-07-31	2036
21608	2036-08-01	2036
21609	2036-08-04	2036
21610	2036-08-05	2036
21611	2036-08-06	2036
21612	2036-08-07	2036
21613	2036-08-08	2036
21614	2036-08-11	2036
21615	2036-08-12	2036
21616	2036-08-13	2036
21617	2036-08-14	2036
21618	2036-08-15	2036
21619	2036-08-18	2036
21620	2036-08-19	2036
21621	2036-08-20	2036
21622	2036-08-21	2036
21623	2036-08-22	2036
21624	2036-08-25	2036
21625	2036-08-26	2036
21626	2036-08-27	2036
21627	2036-08-28	2036
21628	2036-08-29	2036
21629	2036-09-01	2036
21630	2036-09-02	2036
21631	2036-09-03	2036
21632	2036-09-04	2036
21633	2036-09-05	2036
21634	2036-09-08	2036
21635	2036-09-09	2036
21636	2036-09-10	2036
21637	2036-09-11	2036
21638	2036-09-12	2036
21639	2036-09-15	2036
21640	2036-09-16	2036
21641	2036-09-17	2036
21642	2036-09-18	2036
21643	2036-09-19	2036
21644	2036-09-22	2036
21645	2036-09-23	2036
21646	2036-09-24	2036
21647	2036-09-25	2036
21648	2036-09-26	2036
21649	2036-09-29	2036
21650	2036-09-30	2036
21651	2036-10-01	2036
21652	2036-10-02	2036
21653	2036-10-03	2036
21654	2036-10-06	2036
21655	2036-10-07	2036
21656	2036-10-08	2036
21657	2036-10-09	2036
21658	2036-10-10	2036
21659	2036-10-13	2036
21660	2036-10-14	2036
21661	2036-10-15	2036
21662	2036-10-16	2036
21663	2036-10-17	2036
21664	2036-10-20	2036
21665	2036-10-21	2036
21666	2036-10-22	2036
21667	2036-10-23	2036
21668	2036-10-24	2036
21669	2036-10-27	2036
21670	2036-10-29	2036
21671	2036-10-30	2036
21672	2036-10-31	2036
21673	2036-11-03	2036
21674	2036-11-04	2036
21675	2036-11-05	2036
21676	2036-11-06	2036
21677	2036-11-07	2036
21678	2036-11-10	2036
21679	2036-11-11	2036
21680	2036-11-12	2036
21681	2036-11-13	2036
21682	2036-11-14	2036
21683	2036-11-17	2036
21684	2036-11-18	2036
21685	2036-11-19	2036
21686	2036-11-20	2036
21687	2036-11-21	2036
21688	2036-11-24	2036
21689	2036-11-25	2036
21690	2036-11-26	2036
21691	2036-11-27	2036
21692	2036-11-28	2036
21693	2036-12-01	2036
21694	2036-12-02	2036
21695	2036-12-03	2036
21696	2036-12-04	2036
21697	2036-12-05	2036
21698	2036-12-08	2036
21699	2036-12-09	2036
21700	2036-12-10	2036
21701	2036-12-11	2036
21702	2036-12-12	2036
21703	2036-12-15	2036
21704	2036-12-16	2036
21705	2036-12-17	2036
21706	2036-12-18	2036
21707	2036-12-19	2036
21708	2036-12-22	2036
21709	2036-12-23	2036
21710	2036-12-24	2036
21711	2036-12-26	2036
21712	2036-12-29	2036
21713	2036-12-30	2036
21714	2036-12-31	2036
21715	2037-01-02	2037
21716	2037-01-05	2037
21717	2037-01-06	2037
21718	2037-01-07	2037
21719	2037-01-08	2037
21720	2037-01-09	2037
21721	2037-01-12	2037
21722	2037-01-13	2037
21723	2037-01-14	2037
21724	2037-01-15	2037
21725	2037-01-16	2037
21726	2037-01-19	2037
21727	2037-01-20	2037
21728	2037-01-21	2037
21729	2037-01-22	2037
21730	2037-01-23	2037
21731	2037-01-26	2037
21732	2037-01-27	2037
21733	2037-01-28	2037
21734	2037-01-29	2037
21735	2037-01-30	2037
21736	2037-02-02	2037
21737	2037-02-03	2037
21738	2037-02-04	2037
21739	2037-02-05	2037
21740	2037-02-06	2037
21741	2037-02-09	2037
21742	2037-02-10	2037
21743	2037-02-11	2037
21744	2037-02-12	2037
21745	2037-02-13	2037
21746	2037-02-16	2037
21747	2037-02-18	2037
21748	2037-02-19	2037
21749	2037-02-20	2037
21750	2037-02-23	2037
21751	2037-02-24	2037
21752	2037-02-25	2037
21753	2037-02-26	2037
21754	2037-02-27	2037
21755	2037-03-02	2037
21756	2037-03-03	2037
21757	2037-03-04	2037
21758	2037-03-05	2037
21759	2037-03-06	2037
21760	2037-03-09	2037
21761	2037-03-10	2037
21762	2037-03-11	2037
21763	2037-03-12	2037
21764	2037-03-13	2037
21765	2037-03-16	2037
21766	2037-03-17	2037
21767	2037-03-18	2037
21768	2037-03-19	2037
21769	2037-03-20	2037
21770	2037-03-23	2037
21771	2037-03-24	2037
21772	2037-03-25	2037
21773	2037-03-26	2037
21774	2037-03-27	2037
21775	2037-03-30	2037
21776	2037-03-31	2037
21777	2037-04-01	2037
21778	2037-04-02	2037
21779	2037-04-03	2037
21780	2037-04-06	2037
21781	2037-04-07	2037
21782	2037-04-08	2037
21783	2037-04-09	2037
21784	2037-04-10	2037
21785	2037-04-13	2037
21786	2037-04-14	2037
21787	2037-04-15	2037
21788	2037-04-16	2037
21789	2037-04-17	2037
21790	2037-04-20	2037
21791	2037-04-22	2037
21792	2037-04-23	2037
21793	2037-04-24	2037
21794	2037-04-27	2037
21795	2037-04-28	2037
21796	2037-04-29	2037
21797	2037-04-30	2037
21798	2037-05-04	2037
21799	2037-05-05	2037
21800	2037-05-06	2037
21801	2037-05-07	2037
21802	2037-05-08	2037
21803	2037-05-11	2037
21804	2037-05-12	2037
21805	2037-05-13	2037
21806	2037-05-14	2037
21807	2037-05-15	2037
21808	2037-05-18	2037
21809	2037-05-19	2037
21810	2037-05-20	2037
21811	2037-05-21	2037
21812	2037-05-22	2037
21813	2037-05-25	2037
21814	2037-05-26	2037
21815	2037-05-27	2037
21816	2037-05-28	2037
21817	2037-05-29	2037
21818	2037-06-01	2037
21819	2037-06-02	2037
21820	2037-06-03	2037
21821	2037-06-05	2037
21822	2037-06-08	2037
21823	2037-06-09	2037
21824	2037-06-10	2037
21825	2037-06-11	2037
21826	2037-06-12	2037
21827	2037-06-15	2037
21828	2037-06-16	2037
21829	2037-06-17	2037
21830	2037-06-18	2037
21831	2037-06-19	2037
21832	2037-06-22	2037
21833	2037-06-23	2037
21834	2037-06-24	2037
21835	2037-06-25	2037
21836	2037-06-26	2037
21837	2037-06-29	2037
21838	2037-06-30	2037
21839	2037-07-01	2037
21840	2037-07-02	2037
21841	2037-07-03	2037
21842	2037-07-06	2037
21843	2037-07-07	2037
21844	2037-07-08	2037
21845	2037-07-09	2037
21846	2037-07-10	2037
21847	2037-07-13	2037
21848	2037-07-14	2037
21849	2037-07-15	2037
21850	2037-07-16	2037
21851	2037-07-17	2037
21852	2037-07-20	2037
21853	2037-07-21	2037
21854	2037-07-22	2037
21855	2037-07-23	2037
21856	2037-07-24	2037
21857	2037-07-27	2037
21858	2037-07-28	2037
21859	2037-07-29	2037
21860	2037-07-30	2037
21861	2037-07-31	2037
21862	2037-08-03	2037
21863	2037-08-04	2037
21864	2037-08-05	2037
21865	2037-08-06	2037
21866	2037-08-07	2037
21867	2037-08-10	2037
21868	2037-08-11	2037
21869	2037-08-12	2037
21870	2037-08-13	2037
21871	2037-08-14	2037
21872	2037-08-17	2037
21873	2037-08-18	2037
21874	2037-08-19	2037
21875	2037-08-20	2037
21876	2037-08-21	2037
21877	2037-08-24	2037
21878	2037-08-25	2037
21879	2037-08-26	2037
21880	2037-08-27	2037
21881	2037-08-28	2037
21882	2037-08-31	2037
21883	2037-09-01	2037
21884	2037-09-02	2037
21885	2037-09-03	2037
21886	2037-09-04	2037
21887	2037-09-08	2037
21888	2037-09-09	2037
21889	2037-09-10	2037
21890	2037-09-11	2037
21891	2037-09-14	2037
21892	2037-09-15	2037
21893	2037-09-16	2037
21894	2037-09-17	2037
21895	2037-09-18	2037
21896	2037-09-21	2037
21897	2037-09-22	2037
21898	2037-09-23	2037
21899	2037-09-24	2037
21900	2037-09-25	2037
21901	2037-09-28	2037
21902	2037-09-29	2037
21903	2037-09-30	2037
21904	2037-10-01	2037
21905	2037-10-02	2037
21906	2037-10-05	2037
21907	2037-10-06	2037
21908	2037-10-07	2037
21909	2037-10-08	2037
21910	2037-10-09	2037
21911	2037-10-13	2037
21912	2037-10-14	2037
21913	2037-10-15	2037
21914	2037-10-16	2037
21915	2037-10-19	2037
21916	2037-10-20	2037
21917	2037-10-21	2037
21918	2037-10-22	2037
21919	2037-10-23	2037
21920	2037-10-26	2037
21921	2037-10-27	2037
21922	2037-10-29	2037
21923	2037-10-30	2037
21924	2037-11-03	2037
21925	2037-11-04	2037
21926	2037-11-05	2037
21927	2037-11-06	2037
21928	2037-11-09	2037
21929	2037-11-10	2037
21930	2037-11-11	2037
21931	2037-11-12	2037
21932	2037-11-13	2037
21933	2037-11-16	2037
21934	2037-11-17	2037
21935	2037-11-18	2037
21936	2037-11-19	2037
21937	2037-11-20	2037
21938	2037-11-23	2037
21939	2037-11-24	2037
21940	2037-11-25	2037
21941	2037-11-26	2037
21942	2037-11-27	2037
21943	2037-11-30	2037
21944	2037-12-01	2037
21945	2037-12-02	2037
21946	2037-12-03	2037
21947	2037-12-04	2037
21948	2037-12-07	2037
21949	2037-12-08	2037
21950	2037-12-09	2037
21951	2037-12-10	2037
21952	2037-12-11	2037
21953	2037-12-14	2037
21954	2037-12-15	2037
21955	2037-12-16	2037
21956	2037-12-17	2037
21957	2037-12-18	2037
21958	2037-12-21	2037
21959	2037-12-22	2037
21960	2037-12-23	2037
21961	2037-12-24	2037
21962	2037-12-28	2037
21963	2037-12-29	2037
21964	2037-12-30	2037
21965	2037-12-31	2037
21966	2038-01-04	2038
21967	2038-01-05	2038
21968	2038-01-06	2038
21969	2038-01-07	2038
21970	2038-01-08	2038
21971	2038-01-11	2038
21972	2038-01-12	2038
21973	2038-01-13	2038
21974	2038-01-14	2038
21975	2038-01-15	2038
21976	2038-01-18	2038
21977	2038-01-19	2038
21978	2038-01-20	2038
21979	2038-01-21	2038
21980	2038-01-22	2038
21981	2038-01-25	2038
21982	2038-01-26	2038
21983	2038-01-27	2038
21984	2038-01-28	2038
21985	2038-01-29	2038
21986	2038-02-01	2038
21987	2038-02-02	2038
21988	2038-02-03	2038
21989	2038-02-04	2038
21990	2038-02-05	2038
21991	2038-02-08	2038
21992	2038-02-09	2038
21993	2038-02-10	2038
21994	2038-02-11	2038
21995	2038-02-12	2038
21996	2038-02-15	2038
21997	2038-02-16	2038
21998	2038-02-17	2038
21999	2038-02-18	2038
22000	2038-02-19	2038
22001	2038-02-22	2038
22002	2038-02-23	2038
22003	2038-02-24	2038
22004	2038-02-25	2038
22005	2038-02-26	2038
22006	2038-03-01	2038
22007	2038-03-02	2038
22008	2038-03-03	2038
22009	2038-03-04	2038
22010	2038-03-05	2038
22011	2038-03-08	2038
22012	2038-03-10	2038
22013	2038-03-11	2038
22014	2038-03-12	2038
22015	2038-03-15	2038
22016	2038-03-16	2038
22017	2038-03-17	2038
22018	2038-03-18	2038
22019	2038-03-19	2038
22020	2038-03-22	2038
22021	2038-03-23	2038
22022	2038-03-24	2038
22023	2038-03-25	2038
22024	2038-03-26	2038
22025	2038-03-29	2038
22026	2038-03-30	2038
22027	2038-03-31	2038
22028	2038-04-01	2038
22029	2038-04-02	2038
22030	2038-04-05	2038
22031	2038-04-06	2038
22032	2038-04-07	2038
22033	2038-04-08	2038
22034	2038-04-09	2038
22035	2038-04-12	2038
22036	2038-04-13	2038
22037	2038-04-14	2038
22038	2038-04-15	2038
22039	2038-04-16	2038
22040	2038-04-19	2038
22041	2038-04-20	2038
22042	2038-04-22	2038
22043	2038-04-23	2038
22044	2038-04-26	2038
22045	2038-04-27	2038
22046	2038-04-28	2038
22047	2038-04-29	2038
22048	2038-04-30	2038
22049	2038-05-03	2038
22050	2038-05-04	2038
22051	2038-05-05	2038
22052	2038-05-06	2038
22053	2038-05-07	2038
22054	2038-05-10	2038
22055	2038-05-11	2038
22056	2038-05-12	2038
22057	2038-05-13	2038
22058	2038-05-14	2038
22059	2038-05-17	2038
22060	2038-05-18	2038
22061	2038-05-19	2038
22062	2038-05-20	2038
22063	2038-05-21	2038
22064	2038-05-24	2038
22065	2038-05-25	2038
22066	2038-05-26	2038
22067	2038-05-27	2038
22068	2038-05-28	2038
22069	2038-05-31	2038
22070	2038-06-01	2038
22071	2038-06-02	2038
22072	2038-06-03	2038
22073	2038-06-04	2038
22074	2038-06-07	2038
22075	2038-06-08	2038
22076	2038-06-09	2038
22077	2038-06-10	2038
22078	2038-06-11	2038
22079	2038-06-14	2038
22080	2038-06-15	2038
22081	2038-06-16	2038
22082	2038-06-17	2038
22083	2038-06-18	2038
22084	2038-06-21	2038
22085	2038-06-22	2038
22086	2038-06-23	2038
22087	2038-06-25	2038
22088	2038-06-28	2038
22089	2038-06-29	2038
22090	2038-06-30	2038
22091	2038-07-01	2038
22092	2038-07-02	2038
22093	2038-07-05	2038
22094	2038-07-06	2038
22095	2038-07-07	2038
22096	2038-07-08	2038
22097	2038-07-09	2038
22098	2038-07-12	2038
22099	2038-07-13	2038
22100	2038-07-14	2038
22101	2038-07-15	2038
22102	2038-07-16	2038
22103	2038-07-19	2038
22104	2038-07-20	2038
22105	2038-07-21	2038
22106	2038-07-22	2038
22107	2038-07-23	2038
22108	2038-07-26	2038
22109	2038-07-27	2038
22110	2038-07-28	2038
22111	2038-07-29	2038
22112	2038-07-30	2038
22113	2038-08-02	2038
22114	2038-08-03	2038
22115	2038-08-04	2038
22116	2038-08-05	2038
22117	2038-08-06	2038
22118	2038-08-09	2038
22119	2038-08-10	2038
22120	2038-08-11	2038
22121	2038-08-12	2038
22122	2038-08-13	2038
22123	2038-08-16	2038
22124	2038-08-17	2038
22125	2038-08-18	2038
22126	2038-08-19	2038
22127	2038-08-20	2038
22128	2038-08-23	2038
22129	2038-08-24	2038
22130	2038-08-25	2038
22131	2038-08-26	2038
22132	2038-08-27	2038
22133	2038-08-30	2038
22134	2038-08-31	2038
22135	2038-09-01	2038
22136	2038-09-02	2038
22137	2038-09-03	2038
22138	2038-09-06	2038
22139	2038-09-08	2038
22140	2038-09-09	2038
22141	2038-09-10	2038
22142	2038-09-13	2038
22143	2038-09-14	2038
22144	2038-09-15	2038
22145	2038-09-16	2038
22146	2038-09-17	2038
22147	2038-09-20	2038
22148	2038-09-21	2038
22149	2038-09-22	2038
22150	2038-09-23	2038
22151	2038-09-24	2038
22152	2038-09-27	2038
22153	2038-09-28	2038
22154	2038-09-29	2038
22155	2038-09-30	2038
22156	2038-10-01	2038
22157	2038-10-04	2038
22158	2038-10-05	2038
22159	2038-10-06	2038
22160	2038-10-07	2038
22161	2038-10-08	2038
22162	2038-10-11	2038
22163	2038-10-13	2038
22164	2038-10-14	2038
22165	2038-10-15	2038
22166	2038-10-18	2038
22167	2038-10-19	2038
22168	2038-10-20	2038
22169	2038-10-21	2038
22170	2038-10-22	2038
22171	2038-10-25	2038
22172	2038-10-26	2038
22173	2038-10-27	2038
22174	2038-10-29	2038
22175	2038-11-01	2038
22176	2038-11-03	2038
22177	2038-11-04	2038
22178	2038-11-05	2038
22179	2038-11-08	2038
22180	2038-11-09	2038
22181	2038-11-10	2038
22182	2038-11-11	2038
22183	2038-11-12	2038
22184	2038-11-16	2038
22185	2038-11-17	2038
22186	2038-11-18	2038
22187	2038-11-19	2038
22188	2038-11-22	2038
22189	2038-11-23	2038
22190	2038-11-24	2038
22191	2038-11-25	2038
22192	2038-11-26	2038
22193	2038-11-29	2038
22194	2038-11-30	2038
22195	2038-12-01	2038
22196	2038-12-02	2038
22197	2038-12-03	2038
22198	2038-12-06	2038
22199	2038-12-07	2038
22200	2038-12-08	2038
22201	2038-12-09	2038
22202	2038-12-10	2038
22203	2038-12-13	2038
22204	2038-12-14	2038
22205	2038-12-15	2038
22206	2038-12-16	2038
22207	2038-12-17	2038
22208	2038-12-20	2038
22209	2038-12-21	2038
22210	2038-12-22	2038
22211	2038-12-23	2038
22212	2038-12-24	2038
22213	2038-12-27	2038
22214	2038-12-28	2038
22215	2038-12-29	2038
22216	2038-12-30	2038
22217	2038-12-31	2038
22218	2039-01-03	2039
22219	2039-01-04	2039
22220	2039-01-05	2039
22221	2039-01-06	2039
22222	2039-01-07	2039
22223	2039-01-10	2039
22224	2039-01-11	2039
22225	2039-01-12	2039
22226	2039-01-13	2039
22227	2039-01-14	2039
22228	2039-01-17	2039
22229	2039-01-18	2039
22230	2039-01-19	2039
22231	2039-01-20	2039
22232	2039-01-21	2039
22233	2039-01-24	2039
22234	2039-01-25	2039
22235	2039-01-26	2039
22236	2039-01-27	2039
22237	2039-01-28	2039
22238	2039-01-31	2039
22239	2039-02-01	2039
22240	2039-02-02	2039
22241	2039-02-03	2039
22242	2039-02-04	2039
22243	2039-02-07	2039
22244	2039-02-08	2039
22245	2039-02-09	2039
22246	2039-02-10	2039
22247	2039-02-11	2039
22248	2039-02-14	2039
22249	2039-02-15	2039
22250	2039-02-16	2039
22251	2039-02-17	2039
22252	2039-02-18	2039
22253	2039-02-21	2039
22254	2039-02-23	2039
22255	2039-02-24	2039
22256	2039-02-25	2039
22257	2039-02-28	2039
22258	2039-03-01	2039
22259	2039-03-02	2039
22260	2039-03-03	2039
22261	2039-03-04	2039
22262	2039-03-07	2039
22263	2039-03-08	2039
22264	2039-03-09	2039
22265	2039-03-10	2039
22266	2039-03-11	2039
22267	2039-03-14	2039
22268	2039-03-15	2039
22269	2039-03-16	2039
22270	2039-03-17	2039
22271	2039-03-18	2039
22272	2039-03-21	2039
22273	2039-03-22	2039
22274	2039-03-23	2039
22275	2039-03-24	2039
22276	2039-03-25	2039
22277	2039-03-28	2039
22278	2039-03-29	2039
22279	2039-03-30	2039
22280	2039-03-31	2039
22281	2039-04-01	2039
22282	2039-04-04	2039
22283	2039-04-05	2039
22284	2039-04-06	2039
22285	2039-04-07	2039
22286	2039-04-08	2039
22287	2039-04-11	2039
22288	2039-04-12	2039
22289	2039-04-13	2039
22290	2039-04-14	2039
22291	2039-04-15	2039
22292	2039-04-18	2039
22293	2039-04-19	2039
22294	2039-04-20	2039
22295	2039-04-22	2039
22296	2039-04-25	2039
22297	2039-04-26	2039
22298	2039-04-27	2039
22299	2039-04-28	2039
22300	2039-04-29	2039
22301	2039-05-02	2039
22302	2039-05-03	2039
22303	2039-05-04	2039
22304	2039-05-05	2039
22305	2039-05-06	2039
22306	2039-05-09	2039
22307	2039-05-10	2039
22308	2039-05-11	2039
22309	2039-05-12	2039
22310	2039-05-13	2039
22311	2039-05-16	2039
22312	2039-05-17	2039
22313	2039-05-18	2039
22314	2039-05-19	2039
22315	2039-05-20	2039
22316	2039-05-23	2039
22317	2039-05-24	2039
22318	2039-05-25	2039
22319	2039-05-26	2039
22320	2039-05-27	2039
22321	2039-05-30	2039
22322	2039-05-31	2039
22323	2039-06-01	2039
22324	2039-06-02	2039
22325	2039-06-03	2039
22326	2039-06-06	2039
22327	2039-06-07	2039
22328	2039-06-08	2039
22329	2039-06-10	2039
22330	2039-06-13	2039
22331	2039-06-14	2039
22332	2039-06-15	2039
22333	2039-06-16	2039
22334	2039-06-17	2039
22335	2039-06-20	2039
22336	2039-06-21	2039
22337	2039-06-22	2039
22338	2039-06-23	2039
22339	2039-06-24	2039
22340	2039-06-27	2039
22341	2039-06-28	2039
22342	2039-06-29	2039
22343	2039-06-30	2039
22344	2039-07-01	2039
22345	2039-07-04	2039
22346	2039-07-05	2039
22347	2039-07-06	2039
22348	2039-07-07	2039
22349	2039-07-08	2039
22350	2039-07-11	2039
22351	2039-07-12	2039
22352	2039-07-13	2039
22353	2039-07-14	2039
22354	2039-07-15	2039
22355	2039-07-18	2039
22356	2039-07-19	2039
22357	2039-07-20	2039
22358	2039-07-21	2039
22359	2039-07-22	2039
22360	2039-07-25	2039
22361	2039-07-26	2039
22362	2039-07-27	2039
22363	2039-07-28	2039
22364	2039-07-29	2039
22365	2039-08-01	2039
22366	2039-08-02	2039
22367	2039-08-03	2039
22368	2039-08-04	2039
22369	2039-08-05	2039
22370	2039-08-08	2039
22371	2039-08-09	2039
22372	2039-08-10	2039
22373	2039-08-11	2039
22374	2039-08-12	2039
22375	2039-08-15	2039
22376	2039-08-16	2039
22377	2039-08-17	2039
22378	2039-08-18	2039
22379	2039-08-19	2039
22380	2039-08-22	2039
22381	2039-08-23	2039
22382	2039-08-24	2039
22383	2039-08-25	2039
22384	2039-08-26	2039
22385	2039-08-29	2039
22386	2039-08-30	2039
22387	2039-08-31	2039
22388	2039-09-01	2039
22389	2039-09-02	2039
22390	2039-09-05	2039
22391	2039-09-06	2039
22392	2039-09-08	2039
22393	2039-09-09	2039
22394	2039-09-12	2039
22395	2039-09-13	2039
22396	2039-09-14	2039
22397	2039-09-15	2039
22398	2039-09-16	2039
22399	2039-09-19	2039
22400	2039-09-20	2039
22401	2039-09-21	2039
22402	2039-09-22	2039
22403	2039-09-23	2039
22404	2039-09-26	2039
22405	2039-09-27	2039
22406	2039-09-28	2039
22407	2039-09-29	2039
22408	2039-09-30	2039
22409	2039-10-03	2039
22410	2039-10-04	2039
22411	2039-10-05	2039
22412	2039-10-06	2039
22413	2039-10-07	2039
22414	2039-10-10	2039
22415	2039-10-11	2039
22416	2039-10-13	2039
22417	2039-10-14	2039
22418	2039-10-17	2039
22419	2039-10-18	2039
22420	2039-10-19	2039
22421	2039-10-20	2039
22422	2039-10-21	2039
22423	2039-10-24	2039
22424	2039-10-25	2039
22425	2039-10-26	2039
22426	2039-10-27	2039
22427	2039-10-31	2039
22428	2039-11-01	2039
22429	2039-11-03	2039
22430	2039-11-04	2039
22431	2039-11-07	2039
22432	2039-11-08	2039
22433	2039-11-09	2039
22434	2039-11-10	2039
22435	2039-11-11	2039
22436	2039-11-14	2039
22437	2039-11-16	2039
22438	2039-11-17	2039
22439	2039-11-18	2039
22440	2039-11-21	2039
22441	2039-11-22	2039
22442	2039-11-23	2039
22443	2039-11-24	2039
22444	2039-11-25	2039
22445	2039-11-28	2039
22446	2039-11-29	2039
22447	2039-11-30	2039
22448	2039-12-01	2039
22449	2039-12-02	2039
22450	2039-12-05	2039
22451	2039-12-06	2039
22452	2039-12-07	2039
22453	2039-12-08	2039
22454	2039-12-09	2039
22455	2039-12-12	2039
22456	2039-12-13	2039
22457	2039-12-14	2039
22458	2039-12-15	2039
22459	2039-12-16	2039
22460	2039-12-19	2039
22461	2039-12-20	2039
22462	2039-12-21	2039
22463	2039-12-22	2039
22464	2039-12-23	2039
22465	2039-12-26	2039
22466	2039-12-27	2039
22467	2039-12-28	2039
22468	2039-12-29	2039
22469	2039-12-30	2039
22470	2040-01-02	2040
22471	2040-01-03	2040
22472	2040-01-04	2040
22473	2040-01-05	2040
22474	2040-01-06	2040
22475	2040-01-09	2040
22476	2040-01-10	2040
22477	2040-01-11	2040
22478	2040-01-12	2040
22479	2040-01-13	2040
22480	2040-01-16	2040
22481	2040-01-17	2040
22482	2040-01-18	2040
22483	2040-01-19	2040
22484	2040-01-20	2040
22485	2040-01-23	2040
22486	2040-01-24	2040
22487	2040-01-25	2040
22488	2040-01-26	2040
22489	2040-01-27	2040
22490	2040-01-30	2040
22491	2040-01-31	2040
22492	2040-02-01	2040
22493	2040-02-02	2040
22494	2040-02-03	2040
22495	2040-02-06	2040
22496	2040-02-07	2040
22497	2040-02-08	2040
22498	2040-02-09	2040
22499	2040-02-10	2040
22500	2040-02-13	2040
22501	2040-02-15	2040
22502	2040-02-16	2040
22503	2040-02-17	2040
22504	2040-02-20	2040
22505	2040-02-21	2040
22506	2040-02-22	2040
22507	2040-02-23	2040
22508	2040-02-24	2040
22509	2040-02-27	2040
22510	2040-02-28	2040
22511	2040-02-29	2040
22512	2040-03-01	2040
22513	2040-03-02	2040
22514	2040-03-05	2040
22515	2040-03-06	2040
22516	2040-03-07	2040
22517	2040-03-08	2040
22518	2040-03-09	2040
22519	2040-03-12	2040
22520	2040-03-13	2040
22521	2040-03-14	2040
22522	2040-03-15	2040
22523	2040-03-16	2040
22524	2040-03-19	2040
22525	2040-03-20	2040
22526	2040-03-21	2040
22527	2040-03-22	2040
22528	2040-03-23	2040
22529	2040-03-26	2040
22530	2040-03-27	2040
22531	2040-03-28	2040
22532	2040-03-29	2040
22533	2040-03-30	2040
22534	2040-04-02	2040
22535	2040-04-03	2040
22536	2040-04-04	2040
22537	2040-04-05	2040
22538	2040-04-06	2040
22539	2040-04-09	2040
22540	2040-04-10	2040
22541	2040-04-11	2040
22542	2040-04-12	2040
22543	2040-04-13	2040
22544	2040-04-16	2040
22545	2040-04-17	2040
22546	2040-04-18	2040
22547	2040-04-19	2040
22548	2040-04-20	2040
22549	2040-04-23	2040
22550	2040-04-24	2040
22551	2040-04-25	2040
22552	2040-04-26	2040
22553	2040-04-27	2040
22554	2040-04-30	2040
22555	2040-05-02	2040
22556	2040-05-03	2040
22557	2040-05-04	2040
22558	2040-05-07	2040
22559	2040-05-08	2040
22560	2040-05-09	2040
22561	2040-05-10	2040
22562	2040-05-11	2040
22563	2040-05-14	2040
22564	2040-05-15	2040
22565	2040-05-16	2040
22566	2040-05-17	2040
22567	2040-05-18	2040
22568	2040-05-21	2040
22569	2040-05-22	2040
22570	2040-05-23	2040
22571	2040-05-24	2040
22572	2040-05-25	2040
22573	2040-05-28	2040
22574	2040-05-29	2040
22575	2040-05-30	2040
22576	2040-06-01	2040
22577	2040-06-04	2040
22578	2040-06-05	2040
22579	2040-06-06	2040
22580	2040-06-07	2040
22581	2040-06-08	2040
22582	2040-06-11	2040
22583	2040-06-12	2040
22584	2040-06-13	2040
22585	2040-06-14	2040
22586	2040-06-15	2040
22587	2040-06-18	2040
22588	2040-06-19	2040
22589	2040-06-20	2040
22590	2040-06-21	2040
22591	2040-06-22	2040
22592	2040-06-25	2040
22593	2040-06-26	2040
22594	2040-06-27	2040
22595	2040-06-28	2040
22596	2040-06-29	2040
22597	2040-07-02	2040
22598	2040-07-03	2040
22599	2040-07-04	2040
22600	2040-07-05	2040
22601	2040-07-06	2040
22602	2040-07-09	2040
22603	2040-07-10	2040
22604	2040-07-11	2040
22605	2040-07-12	2040
22606	2040-07-13	2040
22607	2040-07-16	2040
22608	2040-07-17	2040
22609	2040-07-18	2040
22610	2040-07-19	2040
22611	2040-07-20	2040
22612	2040-07-23	2040
22613	2040-07-24	2040
22614	2040-07-25	2040
22615	2040-07-26	2040
22616	2040-07-27	2040
22617	2040-07-30	2040
22618	2040-07-31	2040
22619	2040-08-01	2040
22620	2040-08-02	2040
22621	2040-08-03	2040
22622	2040-08-06	2040
22623	2040-08-07	2040
22624	2040-08-08	2040
22625	2040-08-09	2040
22626	2040-08-10	2040
22627	2040-08-13	2040
22628	2040-08-14	2040
22629	2040-08-15	2040
22630	2040-08-16	2040
22631	2040-08-17	2040
22632	2040-08-20	2040
22633	2040-08-21	2040
22634	2040-08-22	2040
22635	2040-08-23	2040
22636	2040-08-24	2040
22637	2040-08-27	2040
22638	2040-08-28	2040
22639	2040-08-29	2040
22640	2040-08-30	2040
22641	2040-08-31	2040
22642	2040-09-03	2040
22643	2040-09-04	2040
22644	2040-09-05	2040
22645	2040-09-06	2040
22646	2040-09-10	2040
22647	2040-09-11	2040
22648	2040-09-12	2040
22649	2040-09-13	2040
22650	2040-09-14	2040
22651	2040-09-17	2040
22652	2040-09-18	2040
22653	2040-09-19	2040
22654	2040-09-20	2040
22655	2040-09-21	2040
22656	2040-09-24	2040
22657	2040-09-25	2040
22658	2040-09-26	2040
22659	2040-09-27	2040
22660	2040-09-28	2040
22661	2040-10-01	2040
22662	2040-10-02	2040
22663	2040-10-03	2040
22664	2040-10-04	2040
22665	2040-10-05	2040
22666	2040-10-08	2040
22667	2040-10-09	2040
22668	2040-10-10	2040
22669	2040-10-11	2040
22670	2040-10-15	2040
22671	2040-10-16	2040
22672	2040-10-17	2040
22673	2040-10-18	2040
22674	2040-10-19	2040
22675	2040-10-22	2040
22676	2040-10-23	2040
22677	2040-10-24	2040
22678	2040-10-25	2040
22679	2040-10-26	2040
22680	2040-10-29	2040
22681	2040-10-30	2040
22682	2040-10-31	2040
22683	2040-11-01	2040
22684	2040-11-05	2040
22685	2040-11-06	2040
22686	2040-11-07	2040
22687	2040-11-08	2040
22688	2040-11-09	2040
22689	2040-11-12	2040
22690	2040-11-13	2040
22691	2040-11-14	2040
22692	2040-11-16	2040
22693	2040-11-19	2040
22694	2040-11-20	2040
22695	2040-11-21	2040
22696	2040-11-22	2040
22697	2040-11-23	2040
22698	2040-11-26	2040
22699	2040-11-27	2040
22700	2040-11-28	2040
22701	2040-11-29	2040
22702	2040-11-30	2040
22703	2040-12-03	2040
22704	2040-12-04	2040
22705	2040-12-05	2040
22706	2040-12-06	2040
22707	2040-12-07	2040
22708	2040-12-10	2040
22709	2040-12-11	2040
22710	2040-12-12	2040
22711	2040-12-13	2040
22712	2040-12-14	2040
22713	2040-12-17	2040
22714	2040-12-18	2040
22715	2040-12-19	2040
22716	2040-12-20	2040
22717	2040-12-21	2040
22718	2040-12-24	2040
22719	2040-12-26	2040
22720	2040-12-27	2040
22721	2040-12-28	2040
22722	2040-12-31	2040
22723	2041-01-02	2041
22724	2041-01-03	2041
22725	2041-01-04	2041
22726	2041-01-07	2041
22727	2041-01-08	2041
22728	2041-01-09	2041
22729	2041-01-10	2041
22730	2041-01-11	2041
22731	2041-01-14	2041
22732	2041-01-15	2041
22733	2041-01-16	2041
22734	2041-01-17	2041
22735	2041-01-18	2041
22736	2041-01-21	2041
22737	2041-01-22	2041
22738	2041-01-23	2041
22739	2041-01-24	2041
22740	2041-01-25	2041
22741	2041-01-28	2041
22742	2041-01-29	2041
22743	2041-01-30	2041
22744	2041-01-31	2041
22745	2041-02-01	2041
22746	2041-02-04	2041
22747	2041-02-05	2041
22748	2041-02-06	2041
22749	2041-02-07	2041
22750	2041-02-08	2041
22751	2041-02-11	2041
22752	2041-02-12	2041
22753	2041-02-13	2041
22754	2041-02-14	2041
22755	2041-02-15	2041
22756	2041-02-18	2041
22757	2041-02-19	2041
22758	2041-02-20	2041
22759	2041-02-21	2041
22760	2041-02-22	2041
22761	2041-02-25	2041
22762	2041-02-26	2041
22763	2041-02-27	2041
22764	2041-02-28	2041
22765	2041-03-01	2041
22766	2041-03-04	2041
22767	2041-03-06	2041
22768	2041-03-07	2041
22769	2041-03-08	2041
22770	2041-03-11	2041
22771	2041-03-12	2041
22772	2041-03-13	2041
22773	2041-03-14	2041
22774	2041-03-15	2041
22775	2041-03-18	2041
22776	2041-03-19	2041
22777	2041-03-20	2041
22778	2041-03-21	2041
22779	2041-03-22	2041
22780	2041-03-25	2041
22781	2041-03-26	2041
22782	2041-03-27	2041
22783	2041-03-28	2041
22784	2041-03-29	2041
22785	2041-04-01	2041
22786	2041-04-02	2041
22787	2041-04-03	2041
22788	2041-04-04	2041
22789	2041-04-05	2041
22790	2041-04-08	2041
22791	2041-04-09	2041
22792	2041-04-10	2041
22793	2041-04-11	2041
22794	2041-04-12	2041
22795	2041-04-15	2041
22796	2041-04-16	2041
22797	2041-04-17	2041
22798	2041-04-18	2041
22799	2041-04-19	2041
22800	2041-04-22	2041
22801	2041-04-23	2041
22802	2041-04-24	2041
22803	2041-04-25	2041
22804	2041-04-26	2041
22805	2041-04-29	2041
22806	2041-04-30	2041
22807	2041-05-02	2041
22808	2041-05-03	2041
22809	2041-05-06	2041
22810	2041-05-07	2041
22811	2041-05-08	2041
22812	2041-05-09	2041
22813	2041-05-10	2041
22814	2041-05-13	2041
22815	2041-05-14	2041
22816	2041-05-15	2041
22817	2041-05-16	2041
22818	2041-05-17	2041
22819	2041-05-20	2041
22820	2041-05-21	2041
22821	2041-05-22	2041
22822	2041-05-23	2041
22823	2041-05-24	2041
22824	2041-05-27	2041
22825	2041-05-28	2041
22826	2041-05-29	2041
22827	2041-05-30	2041
22828	2041-05-31	2041
22829	2041-06-03	2041
22830	2041-06-04	2041
22831	2041-06-05	2041
22832	2041-06-06	2041
22833	2041-06-07	2041
22834	2041-06-10	2041
22835	2041-06-11	2041
22836	2041-06-12	2041
22837	2041-06-13	2041
22838	2041-06-14	2041
22839	2041-06-17	2041
22840	2041-06-18	2041
22841	2041-06-19	2041
22842	2041-06-21	2041
22843	2041-06-24	2041
22844	2041-06-25	2041
22845	2041-06-26	2041
22846	2041-06-27	2041
22847	2041-06-28	2041
22848	2041-07-01	2041
22849	2041-07-02	2041
22850	2041-07-03	2041
22851	2041-07-04	2041
22852	2041-07-05	2041
22853	2041-07-08	2041
22854	2041-07-09	2041
22855	2041-07-10	2041
22856	2041-07-11	2041
22857	2041-07-12	2041
22858	2041-07-15	2041
22859	2041-07-16	2041
22860	2041-07-17	2041
22861	2041-07-18	2041
22862	2041-07-19	2041
22863	2041-07-22	2041
22864	2041-07-23	2041
22865	2041-07-24	2041
22866	2041-07-25	2041
22867	2041-07-26	2041
22868	2041-07-29	2041
22869	2041-07-30	2041
22870	2041-07-31	2041
22871	2041-08-01	2041
22872	2041-08-02	2041
22873	2041-08-05	2041
22874	2041-08-06	2041
22875	2041-08-07	2041
22876	2041-08-08	2041
22877	2041-08-09	2041
22878	2041-08-12	2041
22879	2041-08-13	2041
22880	2041-08-14	2041
22881	2041-08-15	2041
22882	2041-08-16	2041
22883	2041-08-19	2041
22884	2041-08-20	2041
22885	2041-08-21	2041
22886	2041-08-22	2041
22887	2041-08-23	2041
22888	2041-08-26	2041
22889	2041-08-27	2041
22890	2041-08-28	2041
22891	2041-08-29	2041
22892	2041-08-30	2041
22893	2041-09-02	2041
22894	2041-09-03	2041
22895	2041-09-04	2041
22896	2041-09-05	2041
22897	2041-09-06	2041
22898	2041-09-09	2041
22899	2041-09-10	2041
22900	2041-09-11	2041
22901	2041-09-12	2041
22902	2041-09-13	2041
22903	2041-09-16	2041
22904	2041-09-17	2041
22905	2041-09-18	2041
22906	2041-09-19	2041
22907	2041-09-20	2041
22908	2041-09-23	2041
22909	2041-09-24	2041
22910	2041-09-25	2041
22911	2041-09-26	2041
22912	2041-09-27	2041
22913	2041-09-30	2041
22914	2041-10-01	2041
22915	2041-10-02	2041
22916	2041-10-03	2041
22917	2041-10-04	2041
22918	2041-10-07	2041
22919	2041-10-08	2041
22920	2041-10-09	2041
22921	2041-10-10	2041
22922	2041-10-11	2041
22923	2041-10-14	2041
22924	2041-10-15	2041
22925	2041-10-16	2041
22926	2041-10-17	2041
22927	2041-10-18	2041
22928	2041-10-21	2041
22929	2041-10-22	2041
22930	2041-10-23	2041
22931	2041-10-24	2041
22932	2041-10-25	2041
22933	2041-10-29	2041
22934	2041-10-30	2041
22935	2041-10-31	2041
22936	2041-11-01	2041
22937	2041-11-04	2041
22938	2041-11-05	2041
22939	2041-11-06	2041
22940	2041-11-07	2041
22941	2041-11-08	2041
22942	2041-11-11	2041
22943	2041-11-12	2041
22944	2041-11-13	2041
22945	2041-11-14	2041
22946	2041-11-18	2041
22947	2041-11-19	2041
22948	2041-11-20	2041
22949	2041-11-21	2041
22950	2041-11-22	2041
22951	2041-11-25	2041
22952	2041-11-26	2041
22953	2041-11-27	2041
22954	2041-11-28	2041
22955	2041-11-29	2041
22956	2041-12-02	2041
22957	2041-12-03	2041
22958	2041-12-04	2041
22959	2041-12-05	2041
22960	2041-12-06	2041
22961	2041-12-09	2041
22962	2041-12-10	2041
22963	2041-12-11	2041
22964	2041-12-12	2041
22965	2041-12-13	2041
22966	2041-12-16	2041
22967	2041-12-17	2041
22968	2041-12-18	2041
22969	2041-12-19	2041
22970	2041-12-20	2041
22971	2041-12-23	2041
22972	2041-12-24	2041
22973	2041-12-26	2041
22974	2041-12-27	2041
22975	2041-12-30	2041
22976	2041-12-31	2041
22977	2042-01-02	2042
22978	2042-01-03	2042
22979	2042-01-06	2042
22980	2042-01-07	2042
22981	2042-01-08	2042
22982	2042-01-09	2042
22983	2042-01-10	2042
22984	2042-01-13	2042
22985	2042-01-14	2042
22986	2042-01-15	2042
22987	2042-01-16	2042
22988	2042-01-17	2042
22989	2042-01-20	2042
22990	2042-01-21	2042
22991	2042-01-22	2042
22992	2042-01-23	2042
22993	2042-01-24	2042
22994	2042-01-27	2042
22995	2042-01-28	2042
22996	2042-01-29	2042
22997	2042-01-30	2042
22998	2042-01-31	2042
22999	2042-02-03	2042
23000	2042-02-04	2042
23001	2042-02-05	2042
23002	2042-02-06	2042
23003	2042-02-07	2042
23004	2042-02-10	2042
23005	2042-02-11	2042
23006	2042-02-12	2042
23007	2042-02-13	2042
23008	2042-02-14	2042
23009	2042-02-17	2042
23010	2042-02-19	2042
23011	2042-02-20	2042
23012	2042-02-21	2042
23013	2042-02-24	2042
23014	2042-02-25	2042
23015	2042-02-26	2042
23016	2042-02-27	2042
23017	2042-02-28	2042
23018	2042-03-03	2042
23019	2042-03-04	2042
23020	2042-03-05	2042
23021	2042-03-06	2042
23022	2042-03-07	2042
23023	2042-03-10	2042
23024	2042-03-11	2042
23025	2042-03-12	2042
23026	2042-03-13	2042
23027	2042-03-14	2042
23028	2042-03-17	2042
23029	2042-03-18	2042
23030	2042-03-19	2042
23031	2042-03-20	2042
23032	2042-03-21	2042
23033	2042-03-24	2042
23034	2042-03-25	2042
23035	2042-03-26	2042
23036	2042-03-27	2042
23037	2042-03-28	2042
23038	2042-03-31	2042
23039	2042-04-01	2042
23040	2042-04-02	2042
23041	2042-04-03	2042
23042	2042-04-04	2042
23043	2042-04-07	2042
23044	2042-04-08	2042
23045	2042-04-09	2042
23046	2042-04-10	2042
23047	2042-04-11	2042
23048	2042-04-14	2042
23049	2042-04-15	2042
23050	2042-04-16	2042
23051	2042-04-17	2042
23052	2042-04-18	2042
23053	2042-04-22	2042
23054	2042-04-23	2042
23055	2042-04-24	2042
23056	2042-04-25	2042
23057	2042-04-28	2042
23058	2042-04-29	2042
23059	2042-04-30	2042
23060	2042-05-02	2042
23061	2042-05-05	2042
23062	2042-05-06	2042
23063	2042-05-07	2042
23064	2042-05-08	2042
23065	2042-05-09	2042
23066	2042-05-12	2042
23067	2042-05-13	2042
23068	2042-05-14	2042
23069	2042-05-15	2042
23070	2042-05-16	2042
23071	2042-05-19	2042
23072	2042-05-20	2042
23073	2042-05-21	2042
23074	2042-05-22	2042
23075	2042-05-23	2042
23076	2042-05-26	2042
23077	2042-05-27	2042
23078	2042-05-28	2042
23079	2042-05-29	2042
23080	2042-05-30	2042
23081	2042-06-02	2042
23082	2042-06-03	2042
23083	2042-06-04	2042
23084	2042-06-06	2042
23085	2042-06-09	2042
23086	2042-06-10	2042
23087	2042-06-11	2042
23088	2042-06-12	2042
23089	2042-06-13	2042
23090	2042-06-16	2042
23091	2042-06-17	2042
23092	2042-06-18	2042
23093	2042-06-19	2042
23094	2042-06-20	2042
23095	2042-06-23	2042
23096	2042-06-24	2042
23097	2042-06-25	2042
23098	2042-06-26	2042
23099	2042-06-27	2042
23100	2042-06-30	2042
23101	2042-07-01	2042
23102	2042-07-02	2042
23103	2042-07-03	2042
23104	2042-07-04	2042
23105	2042-07-07	2042
23106	2042-07-08	2042
23107	2042-07-09	2042
23108	2042-07-10	2042
23109	2042-07-11	2042
23110	2042-07-14	2042
23111	2042-07-15	2042
23112	2042-07-16	2042
23113	2042-07-17	2042
23114	2042-07-18	2042
23115	2042-07-21	2042
23116	2042-07-22	2042
23117	2042-07-23	2042
23118	2042-07-24	2042
23119	2042-07-25	2042
23120	2042-07-28	2042
23121	2042-07-29	2042
23122	2042-07-30	2042
23123	2042-07-31	2042
23124	2042-08-01	2042
23125	2042-08-04	2042
23126	2042-08-05	2042
23127	2042-08-06	2042
23128	2042-08-07	2042
23129	2042-08-08	2042
23130	2042-08-11	2042
23131	2042-08-12	2042
23132	2042-08-13	2042
23133	2042-08-14	2042
23134	2042-08-15	2042
23135	2042-08-18	2042
23136	2042-08-19	2042
23137	2042-08-20	2042
23138	2042-08-21	2042
23139	2042-08-22	2042
23140	2042-08-25	2042
23141	2042-08-26	2042
23142	2042-08-27	2042
23143	2042-08-28	2042
23144	2042-08-29	2042
23145	2042-09-01	2042
23146	2042-09-02	2042
23147	2042-09-03	2042
23148	2042-09-04	2042
23149	2042-09-05	2042
23150	2042-09-08	2042
23151	2042-09-09	2042
23152	2042-09-10	2042
23153	2042-09-11	2042
23154	2042-09-12	2042
23155	2042-09-15	2042
23156	2042-09-16	2042
23157	2042-09-17	2042
23158	2042-09-18	2042
23159	2042-09-19	2042
23160	2042-09-22	2042
23161	2042-09-23	2042
23162	2042-09-24	2042
23163	2042-09-25	2042
23164	2042-09-26	2042
23165	2042-09-29	2042
23166	2042-09-30	2042
23167	2042-10-01	2042
23168	2042-10-02	2042
23169	2042-10-03	2042
23170	2042-10-06	2042
23171	2042-10-07	2042
23172	2042-10-08	2042
23173	2042-10-09	2042
23174	2042-10-10	2042
23175	2042-10-13	2042
23176	2042-10-14	2042
23177	2042-10-15	2042
23178	2042-10-16	2042
23179	2042-10-17	2042
23180	2042-10-20	2042
23181	2042-10-21	2042
23182	2042-10-22	2042
23183	2042-10-23	2042
23184	2042-10-24	2042
23185	2042-10-27	2042
23186	2042-10-29	2042
23187	2042-10-30	2042
23188	2042-10-31	2042
23189	2042-11-03	2042
23190	2042-11-04	2042
23191	2042-11-05	2042
23192	2042-11-06	2042
23193	2042-11-07	2042
23194	2042-11-10	2042
23195	2042-11-11	2042
23196	2042-11-12	2042
23197	2042-11-13	2042
23198	2042-11-14	2042
23199	2042-11-17	2042
23200	2042-11-18	2042
23201	2042-11-19	2042
23202	2042-11-20	2042
23203	2042-11-21	2042
23204	2042-11-24	2042
23205	2042-11-25	2042
23206	2042-11-26	2042
23207	2042-11-27	2042
23208	2042-11-28	2042
23209	2042-12-01	2042
23210	2042-12-02	2042
23211	2042-12-03	2042
23212	2042-12-04	2042
23213	2042-12-05	2042
23214	2042-12-08	2042
23215	2042-12-09	2042
23216	2042-12-10	2042
23217	2042-12-11	2042
23218	2042-12-12	2042
23219	2042-12-15	2042
23220	2042-12-16	2042
23221	2042-12-17	2042
23222	2042-12-18	2042
23223	2042-12-19	2042
23224	2042-12-22	2042
23225	2042-12-23	2042
23226	2042-12-24	2042
23227	2042-12-26	2042
23228	2042-12-29	2042
23229	2042-12-30	2042
23230	2042-12-31	2042
23231	2043-01-02	2043
23232	2043-01-05	2043
23233	2043-01-06	2043
23234	2043-01-07	2043
23235	2043-01-08	2043
23236	2043-01-09	2043
23237	2043-01-12	2043
23238	2043-01-13	2043
23239	2043-01-14	2043
23240	2043-01-15	2043
23241	2043-01-16	2043
23242	2043-01-19	2043
23243	2043-01-20	2043
23244	2043-01-21	2043
23245	2043-01-22	2043
23246	2043-01-23	2043
23247	2043-01-26	2043
23248	2043-01-27	2043
23249	2043-01-28	2043
23250	2043-01-29	2043
23251	2043-01-30	2043
23252	2043-02-02	2043
23253	2043-02-03	2043
23254	2043-02-04	2043
23255	2043-02-05	2043
23256	2043-02-06	2043
23257	2043-02-09	2043
23258	2043-02-11	2043
23259	2043-02-12	2043
23260	2043-02-13	2043
23261	2043-02-16	2043
23262	2043-02-17	2043
23263	2043-02-18	2043
23264	2043-02-19	2043
23265	2043-02-20	2043
23266	2043-02-23	2043
23267	2043-02-24	2043
23268	2043-02-25	2043
23269	2043-02-26	2043
23270	2043-02-27	2043
23271	2043-03-02	2043
23272	2043-03-03	2043
23273	2043-03-04	2043
23274	2043-03-05	2043
23275	2043-03-06	2043
23276	2043-03-09	2043
23277	2043-03-10	2043
23278	2043-03-11	2043
23279	2043-03-12	2043
23280	2043-03-13	2043
23281	2043-03-16	2043
23282	2043-03-17	2043
23283	2043-03-18	2043
23284	2043-03-19	2043
23285	2043-03-20	2043
23286	2043-03-23	2043
23287	2043-03-24	2043
23288	2043-03-25	2043
23289	2043-03-26	2043
23290	2043-03-27	2043
23291	2043-03-30	2043
23292	2043-03-31	2043
23293	2043-04-01	2043
23294	2043-04-02	2043
23295	2043-04-03	2043
23296	2043-04-06	2043
23297	2043-04-07	2043
23298	2043-04-08	2043
23299	2043-04-09	2043
23300	2043-04-10	2043
23301	2043-04-13	2043
23302	2043-04-14	2043
23303	2043-04-15	2043
23304	2043-04-16	2043
23305	2043-04-17	2043
23306	2043-04-20	2043
23307	2043-04-22	2043
23308	2043-04-23	2043
23309	2043-04-24	2043
23310	2043-04-27	2043
23311	2043-04-28	2043
23312	2043-04-29	2043
23313	2043-04-30	2043
23314	2043-05-04	2043
23315	2043-05-05	2043
23316	2043-05-06	2043
23317	2043-05-07	2043
23318	2043-05-08	2043
23319	2043-05-11	2043
23320	2043-05-12	2043
23321	2043-05-13	2043
23322	2043-05-14	2043
23323	2043-05-15	2043
23324	2043-05-18	2043
23325	2043-05-19	2043
23326	2043-05-20	2043
23327	2043-05-21	2043
23328	2043-05-22	2043
23329	2043-05-25	2043
23330	2043-05-26	2043
23331	2043-05-27	2043
23332	2043-05-29	2043
23333	2043-06-01	2043
23334	2043-06-02	2043
23335	2043-06-03	2043
23336	2043-06-04	2043
23337	2043-06-05	2043
23338	2043-06-08	2043
23339	2043-06-09	2043
23340	2043-06-10	2043
23341	2043-06-11	2043
23342	2043-06-12	2043
23343	2043-06-15	2043
23344	2043-06-16	2043
23345	2043-06-17	2043
23346	2043-06-18	2043
23347	2043-06-19	2043
23348	2043-06-22	2043
23349	2043-06-23	2043
23350	2043-06-24	2043
23351	2043-06-25	2043
23352	2043-06-26	2043
23353	2043-06-29	2043
23354	2043-06-30	2043
23355	2043-07-01	2043
23356	2043-07-02	2043
23357	2043-07-03	2043
23358	2043-07-06	2043
23359	2043-07-07	2043
23360	2043-07-08	2043
23361	2043-07-09	2043
23362	2043-07-10	2043
23363	2043-07-13	2043
23364	2043-07-14	2043
23365	2043-07-15	2043
23366	2043-07-16	2043
23367	2043-07-17	2043
23368	2043-07-20	2043
23369	2043-07-21	2043
23370	2043-07-22	2043
23371	2043-07-23	2043
23372	2043-07-24	2043
23373	2043-07-27	2043
23374	2043-07-28	2043
23375	2043-07-29	2043
23376	2043-07-30	2043
23377	2043-07-31	2043
23378	2043-08-03	2043
23379	2043-08-04	2043
23380	2043-08-05	2043
23381	2043-08-06	2043
23382	2043-08-07	2043
23383	2043-08-10	2043
23384	2043-08-11	2043
23385	2043-08-12	2043
23386	2043-08-13	2043
23387	2043-08-14	2043
23388	2043-08-17	2043
23389	2043-08-18	2043
23390	2043-08-19	2043
23391	2043-08-20	2043
23392	2043-08-21	2043
23393	2043-08-24	2043
23394	2043-08-25	2043
23395	2043-08-26	2043
23396	2043-08-27	2043
23397	2043-08-28	2043
23398	2043-08-31	2043
23399	2043-09-01	2043
23400	2043-09-02	2043
23401	2043-09-03	2043
23402	2043-09-04	2043
23403	2043-09-08	2043
23404	2043-09-09	2043
23405	2043-09-10	2043
23406	2043-09-11	2043
23407	2043-09-14	2043
23408	2043-09-15	2043
23409	2043-09-16	2043
23410	2043-09-17	2043
23411	2043-09-18	2043
23412	2043-09-21	2043
23413	2043-09-22	2043
23414	2043-09-23	2043
23415	2043-09-24	2043
23416	2043-09-25	2043
23417	2043-09-28	2043
23418	2043-09-29	2043
23419	2043-09-30	2043
23420	2043-10-01	2043
23421	2043-10-02	2043
23422	2043-10-05	2043
23423	2043-10-06	2043
23424	2043-10-07	2043
23425	2043-10-08	2043
23426	2043-10-09	2043
23427	2043-10-13	2043
23428	2043-10-14	2043
23429	2043-10-15	2043
23430	2043-10-16	2043
23431	2043-10-19	2043
23432	2043-10-20	2043
23433	2043-10-21	2043
23434	2043-10-22	2043
23435	2043-10-23	2043
23436	2043-10-26	2043
23437	2043-10-27	2043
23438	2043-10-29	2043
23439	2043-10-30	2043
23440	2043-11-03	2043
23441	2043-11-04	2043
23442	2043-11-05	2043
23443	2043-11-06	2043
23444	2043-11-09	2043
23445	2043-11-10	2043
23446	2043-11-11	2043
23447	2043-11-12	2043
23448	2043-11-13	2043
23449	2043-11-16	2043
23450	2043-11-17	2043
23451	2043-11-18	2043
23452	2043-11-19	2043
23453	2043-11-20	2043
23454	2043-11-23	2043
23455	2043-11-24	2043
23456	2043-11-25	2043
23457	2043-11-26	2043
23458	2043-11-27	2043
23459	2043-11-30	2043
23460	2043-12-01	2043
23461	2043-12-02	2043
23462	2043-12-03	2043
23463	2043-12-04	2043
23464	2043-12-07	2043
23465	2043-12-08	2043
23466	2043-12-09	2043
23467	2043-12-10	2043
23468	2043-12-11	2043
23469	2043-12-14	2043
23470	2043-12-15	2043
23471	2043-12-16	2043
23472	2043-12-17	2043
23473	2043-12-18	2043
23474	2043-12-21	2043
23475	2043-12-22	2043
23476	2043-12-23	2043
23477	2043-12-24	2043
23478	2043-12-28	2043
23479	2043-12-29	2043
23480	2043-12-30	2043
23481	2043-12-31	2043
23482	2044-01-04	2044
23483	2044-01-05	2044
23484	2044-01-06	2044
23485	2044-01-07	2044
23486	2044-01-08	2044
23487	2044-01-11	2044
23488	2044-01-12	2044
23489	2044-01-13	2044
23490	2044-01-14	2044
23491	2044-01-15	2044
23492	2044-01-18	2044
23493	2044-01-19	2044
23494	2044-01-20	2044
23495	2044-01-21	2044
23496	2044-01-22	2044
23497	2044-01-25	2044
23498	2044-01-26	2044
23499	2044-01-27	2044
23500	2044-01-28	2044
23501	2044-01-29	2044
23502	2044-02-01	2044
23503	2044-02-02	2044
23504	2044-02-03	2044
23505	2044-02-04	2044
23506	2044-02-05	2044
23507	2044-02-08	2044
23508	2044-02-09	2044
23509	2044-02-10	2044
23510	2044-02-11	2044
23511	2044-02-12	2044
23512	2044-02-15	2044
23513	2044-02-16	2044
23514	2044-02-17	2044
23515	2044-02-18	2044
23516	2044-02-19	2044
23517	2044-02-22	2044
23518	2044-02-23	2044
23519	2044-02-24	2044
23520	2044-02-25	2044
23521	2044-02-26	2044
23522	2044-02-29	2044
23523	2044-03-02	2044
23524	2044-03-03	2044
23525	2044-03-04	2044
23526	2044-03-07	2044
23527	2044-03-08	2044
23528	2044-03-09	2044
23529	2044-03-10	2044
23530	2044-03-11	2044
23531	2044-03-14	2044
23532	2044-03-15	2044
23533	2044-03-16	2044
23534	2044-03-17	2044
23535	2044-03-18	2044
23536	2044-03-21	2044
23537	2044-03-22	2044
23538	2044-03-23	2044
23539	2044-03-24	2044
23540	2044-03-25	2044
23541	2044-03-28	2044
23542	2044-03-29	2044
23543	2044-03-30	2044
23544	2044-03-31	2044
23545	2044-04-01	2044
23546	2044-04-04	2044
23547	2044-04-05	2044
23548	2044-04-06	2044
23549	2044-04-07	2044
23550	2044-04-08	2044
23551	2044-04-11	2044
23552	2044-04-12	2044
23553	2044-04-13	2044
23554	2044-04-14	2044
23555	2044-04-15	2044
23556	2044-04-18	2044
23557	2044-04-19	2044
23558	2044-04-20	2044
23559	2044-04-22	2044
23560	2044-04-25	2044
23561	2044-04-26	2044
23562	2044-04-27	2044
23563	2044-04-28	2044
23564	2044-04-29	2044
23565	2044-05-02	2044
23566	2044-05-03	2044
23567	2044-05-04	2044
23568	2044-05-05	2044
23569	2044-05-06	2044
23570	2044-05-09	2044
23571	2044-05-10	2044
23572	2044-05-11	2044
23573	2044-05-12	2044
23574	2044-05-13	2044
23575	2044-05-16	2044
23576	2044-05-17	2044
23577	2044-05-18	2044
23578	2044-05-19	2044
23579	2044-05-20	2044
23580	2044-05-23	2044
23581	2044-05-24	2044
23582	2044-05-25	2044
23583	2044-05-26	2044
23584	2044-05-27	2044
23585	2044-05-30	2044
23586	2044-05-31	2044
23587	2044-06-01	2044
23588	2044-06-02	2044
23589	2044-06-03	2044
23590	2044-06-06	2044
23591	2044-06-07	2044
23592	2044-06-08	2044
23593	2044-06-09	2044
23594	2044-06-10	2044
23595	2044-06-13	2044
23596	2044-06-14	2044
23597	2044-06-15	2044
23598	2044-06-17	2044
23599	2044-06-20	2044
23600	2044-06-21	2044
23601	2044-06-22	2044
23602	2044-06-23	2044
23603	2044-06-24	2044
23604	2044-06-27	2044
23605	2044-06-28	2044
23606	2044-06-29	2044
23607	2044-06-30	2044
23608	2044-07-01	2044
23609	2044-07-04	2044
23610	2044-07-05	2044
23611	2044-07-06	2044
23612	2044-07-07	2044
23613	2044-07-08	2044
23614	2044-07-11	2044
23615	2044-07-12	2044
23616	2044-07-13	2044
23617	2044-07-14	2044
23618	2044-07-15	2044
23619	2044-07-18	2044
23620	2044-07-19	2044
23621	2044-07-20	2044
23622	2044-07-21	2044
23623	2044-07-22	2044
23624	2044-07-25	2044
23625	2044-07-26	2044
23626	2044-07-27	2044
23627	2044-07-28	2044
23628	2044-07-29	2044
23629	2044-08-01	2044
23630	2044-08-02	2044
23631	2044-08-03	2044
23632	2044-08-04	2044
23633	2044-08-05	2044
23634	2044-08-08	2044
23635	2044-08-09	2044
23636	2044-08-10	2044
23637	2044-08-11	2044
23638	2044-08-12	2044
23639	2044-08-15	2044
23640	2044-08-16	2044
23641	2044-08-17	2044
23642	2044-08-18	2044
23643	2044-08-19	2044
23644	2044-08-22	2044
23645	2044-08-23	2044
23646	2044-08-24	2044
23647	2044-08-25	2044
23648	2044-08-26	2044
23649	2044-08-29	2044
23650	2044-08-30	2044
23651	2044-08-31	2044
23652	2044-09-01	2044
23653	2044-09-02	2044
23654	2044-09-05	2044
23655	2044-09-06	2044
23656	2044-09-08	2044
23657	2044-09-09	2044
23658	2044-09-12	2044
23659	2044-09-13	2044
23660	2044-09-14	2044
23661	2044-09-15	2044
23662	2044-09-16	2044
23663	2044-09-19	2044
23664	2044-09-20	2044
23665	2044-09-21	2044
23666	2044-09-22	2044
23667	2044-09-23	2044
23668	2044-09-26	2044
23669	2044-09-27	2044
23670	2044-09-28	2044
23671	2044-09-29	2044
23672	2044-09-30	2044
23673	2044-10-03	2044
23674	2044-10-04	2044
23675	2044-10-05	2044
23676	2044-10-06	2044
23677	2044-10-07	2044
23678	2044-10-10	2044
23679	2044-10-11	2044
23680	2044-10-13	2044
23681	2044-10-14	2044
23682	2044-10-17	2044
23683	2044-10-18	2044
23684	2044-10-19	2044
23685	2044-10-20	2044
23686	2044-10-21	2044
23687	2044-10-24	2044
23688	2044-10-25	2044
23689	2044-10-26	2044
23690	2044-10-27	2044
23691	2044-10-31	2044
23692	2044-11-01	2044
23693	2044-11-03	2044
23694	2044-11-04	2044
23695	2044-11-07	2044
23696	2044-11-08	2044
23697	2044-11-09	2044
23698	2044-11-10	2044
23699	2044-11-11	2044
23700	2044-11-14	2044
23701	2044-11-16	2044
23702	2044-11-17	2044
23703	2044-11-18	2044
23704	2044-11-21	2044
23705	2044-11-22	2044
23706	2044-11-23	2044
23707	2044-11-24	2044
23708	2044-11-25	2044
23709	2044-11-28	2044
23710	2044-11-29	2044
23711	2044-11-30	2044
23712	2044-12-01	2044
23713	2044-12-02	2044
23714	2044-12-05	2044
23715	2044-12-06	2044
23716	2044-12-07	2044
23717	2044-12-08	2044
23718	2044-12-09	2044
23719	2044-12-12	2044
23720	2044-12-13	2044
23721	2044-12-14	2044
23722	2044-12-15	2044
23723	2044-12-16	2044
23724	2044-12-19	2044
23725	2044-12-20	2044
23726	2044-12-21	2044
23727	2044-12-22	2044
23728	2044-12-23	2044
23729	2044-12-26	2044
23730	2044-12-27	2044
23731	2044-12-28	2044
23732	2044-12-29	2044
23733	2044-12-30	2044
23734	2045-01-02	2045
23735	2045-01-03	2045
23736	2045-01-04	2045
23737	2045-01-05	2045
23738	2045-01-06	2045
23739	2045-01-09	2045
23740	2045-01-10	2045
23741	2045-01-11	2045
23742	2045-01-12	2045
23743	2045-01-13	2045
23744	2045-01-16	2045
23745	2045-01-17	2045
23746	2045-01-18	2045
23747	2045-01-19	2045
23748	2045-01-20	2045
23749	2045-01-23	2045
23750	2045-01-24	2045
23751	2045-01-25	2045
23752	2045-01-26	2045
23753	2045-01-27	2045
23754	2045-01-30	2045
23755	2045-01-31	2045
23756	2045-02-01	2045
23757	2045-02-02	2045
23758	2045-02-03	2045
23759	2045-02-06	2045
23760	2045-02-07	2045
23761	2045-02-08	2045
23762	2045-02-09	2045
23763	2045-02-10	2045
23764	2045-02-13	2045
23765	2045-02-14	2045
23766	2045-02-15	2045
23767	2045-02-16	2045
23768	2045-02-17	2045
23769	2045-02-20	2045
23770	2045-02-22	2045
23771	2045-02-23	2045
23772	2045-02-24	2045
23773	2045-02-27	2045
23774	2045-02-28	2045
23775	2045-03-01	2045
23776	2045-03-02	2045
23777	2045-03-03	2045
23778	2045-03-06	2045
23779	2045-03-07	2045
23780	2045-03-08	2045
23781	2045-03-09	2045
23782	2045-03-10	2045
23783	2045-03-13	2045
23784	2045-03-14	2045
23785	2045-03-15	2045
23786	2045-03-16	2045
23787	2045-03-17	2045
23788	2045-03-20	2045
23789	2045-03-21	2045
23790	2045-03-22	2045
23791	2045-03-23	2045
23792	2045-03-24	2045
23793	2045-03-27	2045
23794	2045-03-28	2045
23795	2045-03-29	2045
23796	2045-03-30	2045
23797	2045-03-31	2045
23798	2045-04-03	2045
23799	2045-04-04	2045
23800	2045-04-05	2045
23801	2045-04-06	2045
23802	2045-04-07	2045
23803	2045-04-10	2045
23804	2045-04-11	2045
23805	2045-04-12	2045
23806	2045-04-13	2045
23807	2045-04-14	2045
23808	2045-04-17	2045
23809	2045-04-18	2045
23810	2045-04-19	2045
23811	2045-04-20	2045
23812	2045-04-24	2045
23813	2045-04-25	2045
23814	2045-04-26	2045
23815	2045-04-27	2045
23816	2045-04-28	2045
23817	2045-05-02	2045
23818	2045-05-03	2045
23819	2045-05-04	2045
23820	2045-05-05	2045
23821	2045-05-08	2045
23822	2045-05-09	2045
23823	2045-05-10	2045
23824	2045-05-11	2045
23825	2045-05-12	2045
23826	2045-05-15	2045
23827	2045-05-16	2045
23828	2045-05-17	2045
23829	2045-05-18	2045
23830	2045-05-19	2045
23831	2045-05-22	2045
23832	2045-05-23	2045
23833	2045-05-24	2045
23834	2045-05-25	2045
23835	2045-05-26	2045
23836	2045-05-29	2045
23837	2045-05-30	2045
23838	2045-05-31	2045
23839	2045-06-01	2045
23840	2045-06-02	2045
23841	2045-06-05	2045
23842	2045-06-06	2045
23843	2045-06-07	2045
23844	2045-06-09	2045
23845	2045-06-12	2045
23846	2045-06-13	2045
23847	2045-06-14	2045
23848	2045-06-15	2045
23849	2045-06-16	2045
23850	2045-06-19	2045
23851	2045-06-20	2045
23852	2045-06-21	2045
23853	2045-06-22	2045
23854	2045-06-23	2045
23855	2045-06-26	2045
23856	2045-06-27	2045
23857	2045-06-28	2045
23858	2045-06-29	2045
23859	2045-06-30	2045
23860	2045-07-03	2045
23861	2045-07-04	2045
23862	2045-07-05	2045
23863	2045-07-06	2045
23864	2045-07-07	2045
23865	2045-07-10	2045
23866	2045-07-11	2045
23867	2045-07-12	2045
23868	2045-07-13	2045
23869	2045-07-14	2045
23870	2045-07-17	2045
23871	2045-07-18	2045
23872	2045-07-19	2045
23873	2045-07-20	2045
23874	2045-07-21	2045
23875	2045-07-24	2045
23876	2045-07-25	2045
23877	2045-07-26	2045
23878	2045-07-27	2045
23879	2045-07-28	2045
23880	2045-07-31	2045
23881	2045-08-01	2045
23882	2045-08-02	2045
23883	2045-08-03	2045
23884	2045-08-04	2045
23885	2045-08-07	2045
23886	2045-08-08	2045
23887	2045-08-09	2045
23888	2045-08-10	2045
23889	2045-08-11	2045
23890	2045-08-14	2045
23891	2045-08-15	2045
23892	2045-08-16	2045
23893	2045-08-17	2045
23894	2045-08-18	2045
23895	2045-08-21	2045
23896	2045-08-22	2045
23897	2045-08-23	2045
23898	2045-08-24	2045
23899	2045-08-25	2045
23900	2045-08-28	2045
23901	2045-08-29	2045
23902	2045-08-30	2045
23903	2045-08-31	2045
23904	2045-09-01	2045
23905	2045-09-04	2045
23906	2045-09-05	2045
23907	2045-09-06	2045
23908	2045-09-08	2045
23909	2045-09-11	2045
23910	2045-09-12	2045
23911	2045-09-13	2045
23912	2045-09-14	2045
23913	2045-09-15	2045
23914	2045-09-18	2045
23915	2045-09-19	2045
23916	2045-09-20	2045
23917	2045-09-21	2045
23918	2045-09-22	2045
23919	2045-09-25	2045
23920	2045-09-26	2045
23921	2045-09-27	2045
23922	2045-09-28	2045
23923	2045-09-29	2045
23924	2045-10-02	2045
23925	2045-10-03	2045
23926	2045-10-04	2045
23927	2045-10-05	2045
23928	2045-10-06	2045
23929	2045-10-09	2045
23930	2045-10-10	2045
23931	2045-10-11	2045
23932	2045-10-13	2045
23933	2045-10-16	2045
23934	2045-10-17	2045
23935	2045-10-18	2045
23936	2045-10-19	2045
23937	2045-10-20	2045
23938	2045-10-23	2045
23939	2045-10-24	2045
23940	2045-10-25	2045
23941	2045-10-26	2045
23942	2045-10-27	2045
23943	2045-10-30	2045
23944	2045-10-31	2045
23945	2045-11-01	2045
23946	2045-11-03	2045
23947	2045-11-06	2045
23948	2045-11-07	2045
23949	2045-11-08	2045
23950	2045-11-09	2045
23951	2045-11-10	2045
23952	2045-11-13	2045
23953	2045-11-14	2045
23954	2045-11-16	2045
23955	2045-11-17	2045
23956	2045-11-20	2045
23957	2045-11-21	2045
23958	2045-11-22	2045
23959	2045-11-23	2045
23960	2045-11-24	2045
23961	2045-11-27	2045
23962	2045-11-28	2045
23963	2045-11-29	2045
23964	2045-11-30	2045
23965	2045-12-01	2045
23966	2045-12-04	2045
23967	2045-12-05	2045
23968	2045-12-06	2045
23969	2045-12-07	2045
23970	2045-12-08	2045
23971	2045-12-11	2045
23972	2045-12-12	2045
23973	2045-12-13	2045
23974	2045-12-14	2045
23975	2045-12-15	2045
23976	2045-12-18	2045
23977	2045-12-19	2045
23978	2045-12-20	2045
23979	2045-12-21	2045
23980	2045-12-22	2045
23981	2045-12-26	2045
23982	2045-12-27	2045
23983	2045-12-28	2045
23984	2045-12-29	2045
23985	2046-01-02	2046
23986	2046-01-03	2046
23987	2046-01-04	2046
23988	2046-01-05	2046
23989	2046-01-08	2046
23990	2046-01-09	2046
23991	2046-01-10	2046
23992	2046-01-11	2046
23993	2046-01-12	2046
23994	2046-01-15	2046
23995	2046-01-16	2046
23996	2046-01-17	2046
23997	2046-01-18	2046
23998	2046-01-19	2046
23999	2046-01-22	2046
24000	2046-01-23	2046
24001	2046-01-24	2046
24002	2046-01-25	2046
24003	2046-01-26	2046
24004	2046-01-29	2046
24005	2046-01-30	2046
24006	2046-01-31	2046
24007	2046-02-01	2046
24008	2046-02-02	2046
24009	2046-02-05	2046
24010	2046-02-07	2046
24011	2046-02-08	2046
24012	2046-02-09	2046
24013	2046-02-12	2046
24014	2046-02-13	2046
24015	2046-02-14	2046
24016	2046-02-15	2046
24017	2046-02-16	2046
24018	2046-02-19	2046
24019	2046-02-20	2046
24020	2046-02-21	2046
24021	2046-02-22	2046
24022	2046-02-23	2046
24023	2046-02-26	2046
24024	2046-02-27	2046
24025	2046-02-28	2046
24026	2046-03-01	2046
24027	2046-03-02	2046
24028	2046-03-05	2046
24029	2046-03-06	2046
24030	2046-03-07	2046
24031	2046-03-08	2046
24032	2046-03-09	2046
24033	2046-03-12	2046
24034	2046-03-13	2046
24035	2046-03-14	2046
24036	2046-03-15	2046
24037	2046-03-16	2046
24038	2046-03-19	2046
24039	2046-03-20	2046
24040	2046-03-21	2046
24041	2046-03-22	2046
24042	2046-03-23	2046
24043	2046-03-26	2046
24044	2046-03-27	2046
24045	2046-03-28	2046
24046	2046-03-29	2046
24047	2046-03-30	2046
24048	2046-04-02	2046
24049	2046-04-03	2046
24050	2046-04-04	2046
24051	2046-04-05	2046
24052	2046-04-06	2046
24053	2046-04-09	2046
24054	2046-04-10	2046
24055	2046-04-11	2046
24056	2046-04-12	2046
24057	2046-04-13	2046
24058	2046-04-16	2046
24059	2046-04-17	2046
24060	2046-04-18	2046
24061	2046-04-19	2046
24062	2046-04-20	2046
24063	2046-04-23	2046
24064	2046-04-24	2046
24065	2046-04-25	2046
24066	2046-04-26	2046
24067	2046-04-27	2046
24068	2046-04-30	2046
24069	2046-05-02	2046
24070	2046-05-03	2046
24071	2046-05-04	2046
24072	2046-05-07	2046
24073	2046-05-08	2046
24074	2046-05-09	2046
24075	2046-05-10	2046
24076	2046-05-11	2046
24077	2046-05-14	2046
24078	2046-05-15	2046
24079	2046-05-16	2046
24080	2046-05-17	2046
24081	2046-05-18	2046
24082	2046-05-21	2046
24083	2046-05-22	2046
24084	2046-05-23	2046
24085	2046-05-25	2046
24086	2046-05-28	2046
24087	2046-05-29	2046
24088	2046-05-30	2046
24089	2046-05-31	2046
24090	2046-06-01	2046
24091	2046-06-04	2046
24092	2046-06-05	2046
24093	2046-06-06	2046
24094	2046-06-07	2046
24095	2046-06-08	2046
24096	2046-06-11	2046
24097	2046-06-12	2046
24098	2046-06-13	2046
24099	2046-06-14	2046
24100	2046-06-15	2046
24101	2046-06-18	2046
24102	2046-06-19	2046
24103	2046-06-20	2046
24104	2046-06-21	2046
24105	2046-06-22	2046
24106	2046-06-25	2046
24107	2046-06-26	2046
24108	2046-06-27	2046
24109	2046-06-28	2046
24110	2046-06-29	2046
24111	2046-07-02	2046
24112	2046-07-03	2046
24113	2046-07-04	2046
24114	2046-07-05	2046
24115	2046-07-06	2046
24116	2046-07-09	2046
24117	2046-07-10	2046
24118	2046-07-11	2046
24119	2046-07-12	2046
24120	2046-07-13	2046
24121	2046-07-16	2046
24122	2046-07-17	2046
24123	2046-07-18	2046
24124	2046-07-19	2046
24125	2046-07-20	2046
24126	2046-07-23	2046
24127	2046-07-24	2046
24128	2046-07-25	2046
24129	2046-07-26	2046
24130	2046-07-27	2046
24131	2046-07-30	2046
24132	2046-07-31	2046
24133	2046-08-01	2046
24134	2046-08-02	2046
24135	2046-08-03	2046
24136	2046-08-06	2046
24137	2046-08-07	2046
24138	2046-08-08	2046
24139	2046-08-09	2046
24140	2046-08-10	2046
24141	2046-08-13	2046
24142	2046-08-14	2046
24143	2046-08-15	2046
24144	2046-08-16	2046
24145	2046-08-17	2046
24146	2046-08-20	2046
24147	2046-08-21	2046
24148	2046-08-22	2046
24149	2046-08-23	2046
24150	2046-08-24	2046
24151	2046-08-27	2046
24152	2046-08-28	2046
24153	2046-08-29	2046
24154	2046-08-30	2046
24155	2046-08-31	2046
24156	2046-09-03	2046
24157	2046-09-04	2046
24158	2046-09-05	2046
24159	2046-09-06	2046
24160	2046-09-10	2046
24161	2046-09-11	2046
24162	2046-09-12	2046
24163	2046-09-13	2046
24164	2046-09-14	2046
24165	2046-09-17	2046
24166	2046-09-18	2046
24167	2046-09-19	2046
24168	2046-09-20	2046
24169	2046-09-21	2046
24170	2046-09-24	2046
24171	2046-09-25	2046
24172	2046-09-26	2046
24173	2046-09-27	2046
24174	2046-09-28	2046
24175	2046-10-01	2046
24176	2046-10-02	2046
24177	2046-10-03	2046
24178	2046-10-04	2046
24179	2046-10-05	2046
24180	2046-10-08	2046
24181	2046-10-09	2046
24182	2046-10-10	2046
24183	2046-10-11	2046
24184	2046-10-15	2046
24185	2046-10-16	2046
24186	2046-10-17	2046
24187	2046-10-18	2046
24188	2046-10-19	2046
24189	2046-10-22	2046
24190	2046-10-23	2046
24191	2046-10-24	2046
24192	2046-10-25	2046
24193	2046-10-26	2046
24194	2046-10-29	2046
24195	2046-10-30	2046
24196	2046-10-31	2046
24197	2046-11-01	2046
24198	2046-11-05	2046
24199	2046-11-06	2046
24200	2046-11-07	2046
24201	2046-11-08	2046
24202	2046-11-09	2046
24203	2046-11-12	2046
24204	2046-11-13	2046
24205	2046-11-14	2046
24206	2046-11-16	2046
24207	2046-11-19	2046
24208	2046-11-20	2046
24209	2046-11-21	2046
24210	2046-11-22	2046
24211	2046-11-23	2046
24212	2046-11-26	2046
24213	2046-11-27	2046
24214	2046-11-28	2046
24215	2046-11-29	2046
24216	2046-11-30	2046
24217	2046-12-03	2046
24218	2046-12-04	2046
24219	2046-12-05	2046
24220	2046-12-06	2046
24221	2046-12-07	2046
24222	2046-12-10	2046
24223	2046-12-11	2046
24224	2046-12-12	2046
24225	2046-12-13	2046
24226	2046-12-14	2046
24227	2046-12-17	2046
24228	2046-12-18	2046
24229	2046-12-19	2046
24230	2046-12-20	2046
24231	2046-12-21	2046
24232	2046-12-24	2046
24233	2046-12-26	2046
24234	2046-12-27	2046
24235	2046-12-28	2046
24236	2046-12-31	2046
24237	2047-01-02	2047
24238	2047-01-03	2047
24239	2047-01-04	2047
24240	2047-01-07	2047
24241	2047-01-08	2047
24242	2047-01-09	2047
24243	2047-01-10	2047
24244	2047-01-11	2047
24245	2047-01-14	2047
24246	2047-01-15	2047
24247	2047-01-16	2047
24248	2047-01-17	2047
24249	2047-01-18	2047
24250	2047-01-21	2047
24251	2047-01-22	2047
24252	2047-01-23	2047
24253	2047-01-24	2047
24254	2047-01-25	2047
24255	2047-01-28	2047
24256	2047-01-29	2047
24257	2047-01-30	2047
24258	2047-01-31	2047
24259	2047-02-01	2047
24260	2047-02-04	2047
24261	2047-02-05	2047
24262	2047-02-06	2047
24263	2047-02-07	2047
24264	2047-02-08	2047
24265	2047-02-11	2047
24266	2047-02-12	2047
24267	2047-02-13	2047
24268	2047-02-14	2047
24269	2047-02-15	2047
24270	2047-02-18	2047
24271	2047-02-19	2047
24272	2047-02-20	2047
24273	2047-02-21	2047
24274	2047-02-22	2047
24275	2047-02-25	2047
24276	2047-02-27	2047
24277	2047-02-28	2047
24278	2047-03-01	2047
24279	2047-03-04	2047
24280	2047-03-05	2047
24281	2047-03-06	2047
24282	2047-03-07	2047
24283	2047-03-08	2047
24284	2047-03-11	2047
24285	2047-03-12	2047
24286	2047-03-13	2047
24287	2047-03-14	2047
24288	2047-03-15	2047
24289	2047-03-18	2047
24290	2047-03-19	2047
24291	2047-03-20	2047
24292	2047-03-21	2047
24293	2047-03-22	2047
24294	2047-03-25	2047
24295	2047-03-26	2047
24296	2047-03-27	2047
24297	2047-03-28	2047
24298	2047-03-29	2047
24299	2047-04-01	2047
24300	2047-04-02	2047
24301	2047-04-03	2047
24302	2047-04-04	2047
24303	2047-04-05	2047
24304	2047-04-08	2047
24305	2047-04-09	2047
24306	2047-04-10	2047
24307	2047-04-11	2047
24308	2047-04-12	2047
24309	2047-04-15	2047
24310	2047-04-16	2047
24311	2047-04-17	2047
24312	2047-04-18	2047
24313	2047-04-19	2047
24314	2047-04-22	2047
24315	2047-04-23	2047
24316	2047-04-24	2047
24317	2047-04-25	2047
24318	2047-04-26	2047
24319	2047-04-29	2047
24320	2047-04-30	2047
24321	2047-05-02	2047
24322	2047-05-03	2047
24323	2047-05-06	2047
24324	2047-05-07	2047
24325	2047-05-08	2047
24326	2047-05-09	2047
24327	2047-05-10	2047
24328	2047-05-13	2047
24329	2047-05-14	2047
24330	2047-05-15	2047
24331	2047-05-16	2047
24332	2047-05-17	2047
24333	2047-05-20	2047
24334	2047-05-21	2047
24335	2047-05-22	2047
24336	2047-05-23	2047
24337	2047-05-24	2047
24338	2047-05-27	2047
24339	2047-05-28	2047
24340	2047-05-29	2047
24341	2047-05-30	2047
24342	2047-05-31	2047
24343	2047-06-03	2047
24344	2047-06-04	2047
24345	2047-06-05	2047
24346	2047-06-06	2047
24347	2047-06-07	2047
24348	2047-06-10	2047
24349	2047-06-11	2047
24350	2047-06-12	2047
24351	2047-06-14	2047
24352	2047-06-17	2047
24353	2047-06-18	2047
24354	2047-06-19	2047
24355	2047-06-20	2047
24356	2047-06-21	2047
24357	2047-06-24	2047
24358	2047-06-25	2047
24359	2047-06-26	2047
24360	2047-06-27	2047
24361	2047-06-28	2047
24362	2047-07-01	2047
24363	2047-07-02	2047
24364	2047-07-03	2047
24365	2047-07-04	2047
24366	2047-07-05	2047
24367	2047-07-08	2047
24368	2047-07-09	2047
24369	2047-07-10	2047
24370	2047-07-11	2047
24371	2047-07-12	2047
24372	2047-07-15	2047
24373	2047-07-16	2047
24374	2047-07-17	2047
24375	2047-07-18	2047
24376	2047-07-19	2047
24377	2047-07-22	2047
24378	2047-07-23	2047
24379	2047-07-24	2047
24380	2047-07-25	2047
24381	2047-07-26	2047
24382	2047-07-29	2047
24383	2047-07-30	2047
24384	2047-07-31	2047
24385	2047-08-01	2047
24386	2047-08-02	2047
24387	2047-08-05	2047
24388	2047-08-06	2047
24389	2047-08-07	2047
24390	2047-08-08	2047
24391	2047-08-09	2047
24392	2047-08-12	2047
24393	2047-08-13	2047
24394	2047-08-14	2047
24395	2047-08-15	2047
24396	2047-08-16	2047
24397	2047-08-19	2047
24398	2047-08-20	2047
24399	2047-08-21	2047
24400	2047-08-22	2047
24401	2047-08-23	2047
24402	2047-08-26	2047
24403	2047-08-27	2047
24404	2047-08-28	2047
24405	2047-08-29	2047
24406	2047-08-30	2047
24407	2047-09-02	2047
24408	2047-09-03	2047
24409	2047-09-04	2047
24410	2047-09-05	2047
24411	2047-09-06	2047
24412	2047-09-09	2047
24413	2047-09-10	2047
24414	2047-09-11	2047
24415	2047-09-12	2047
24416	2047-09-13	2047
24417	2047-09-16	2047
24418	2047-09-17	2047
24419	2047-09-18	2047
24420	2047-09-19	2047
24421	2047-09-20	2047
24422	2047-09-23	2047
24423	2047-09-24	2047
24424	2047-09-25	2047
24425	2047-09-26	2047
24426	2047-09-27	2047
24427	2047-09-30	2047
24428	2047-10-01	2047
24429	2047-10-02	2047
24430	2047-10-03	2047
24431	2047-10-04	2047
24432	2047-10-07	2047
24433	2047-10-08	2047
24434	2047-10-09	2047
24435	2047-10-10	2047
24436	2047-10-11	2047
24437	2047-10-14	2047
24438	2047-10-15	2047
24439	2047-10-16	2047
24440	2047-10-17	2047
24441	2047-10-18	2047
24442	2047-10-21	2047
24443	2047-10-22	2047
24444	2047-10-23	2047
24445	2047-10-24	2047
24446	2047-10-25	2047
24447	2047-10-29	2047
24448	2047-10-30	2047
24449	2047-10-31	2047
24450	2047-11-01	2047
24451	2047-11-04	2047
24452	2047-11-05	2047
24453	2047-11-06	2047
24454	2047-11-07	2047
24455	2047-11-08	2047
24456	2047-11-11	2047
24457	2047-11-12	2047
24458	2047-11-13	2047
24459	2047-11-14	2047
24460	2047-11-18	2047
24461	2047-11-19	2047
24462	2047-11-20	2047
24463	2047-11-21	2047
24464	2047-11-22	2047
24465	2047-11-25	2047
24466	2047-11-26	2047
24467	2047-11-27	2047
24468	2047-11-28	2047
24469	2047-11-29	2047
24470	2047-12-02	2047
24471	2047-12-03	2047
24472	2047-12-04	2047
24473	2047-12-05	2047
24474	2047-12-06	2047
24475	2047-12-09	2047
24476	2047-12-10	2047
24477	2047-12-11	2047
24478	2047-12-12	2047
24479	2047-12-13	2047
24480	2047-12-16	2047
24481	2047-12-17	2047
24482	2047-12-18	2047
24483	2047-12-19	2047
24484	2047-12-20	2047
24485	2047-12-23	2047
24486	2047-12-24	2047
24487	2047-12-26	2047
24488	2047-12-27	2047
24489	2047-12-30	2047
24490	2047-12-31	2047
24491	2048-01-02	2048
24492	2048-01-03	2048
24493	2048-01-06	2048
24494	2048-01-07	2048
24495	2048-01-08	2048
24496	2048-01-09	2048
24497	2048-01-10	2048
24498	2048-01-13	2048
24499	2048-01-14	2048
24500	2048-01-15	2048
24501	2048-01-16	2048
24502	2048-01-17	2048
24503	2048-01-20	2048
24504	2048-01-21	2048
24505	2048-01-22	2048
24506	2048-01-23	2048
24507	2048-01-24	2048
24508	2048-01-27	2048
24509	2048-01-28	2048
24510	2048-01-29	2048
24511	2048-01-30	2048
24512	2048-01-31	2048
24513	2048-02-03	2048
24514	2048-02-04	2048
24515	2048-02-05	2048
24516	2048-02-06	2048
24517	2048-02-07	2048
24518	2048-02-10	2048
24519	2048-02-11	2048
24520	2048-02-12	2048
24521	2048-02-13	2048
24522	2048-02-14	2048
24523	2048-02-17	2048
24524	2048-02-19	2048
24525	2048-02-20	2048
24526	2048-02-21	2048
24527	2048-02-24	2048
24528	2048-02-25	2048
24529	2048-02-26	2048
24530	2048-02-27	2048
24531	2048-02-28	2048
24532	2048-03-02	2048
24533	2048-03-03	2048
24534	2048-03-04	2048
24535	2048-03-05	2048
24536	2048-03-06	2048
24537	2048-03-09	2048
24538	2048-03-10	2048
24539	2048-03-11	2048
24540	2048-03-12	2048
24541	2048-03-13	2048
24542	2048-03-16	2048
24543	2048-03-17	2048
24544	2048-03-18	2048
24545	2048-03-19	2048
24546	2048-03-20	2048
24547	2048-03-23	2048
24548	2048-03-24	2048
24549	2048-03-25	2048
24550	2048-03-26	2048
24551	2048-03-27	2048
24552	2048-03-30	2048
24553	2048-03-31	2048
24554	2048-04-01	2048
24555	2048-04-02	2048
24556	2048-04-03	2048
24557	2048-04-06	2048
24558	2048-04-07	2048
24559	2048-04-08	2048
24560	2048-04-09	2048
24561	2048-04-10	2048
24562	2048-04-13	2048
24563	2048-04-14	2048
24564	2048-04-15	2048
24565	2048-04-16	2048
24566	2048-04-17	2048
24567	2048-04-20	2048
24568	2048-04-22	2048
24569	2048-04-23	2048
24570	2048-04-24	2048
24571	2048-04-27	2048
24572	2048-04-28	2048
24573	2048-04-29	2048
24574	2048-04-30	2048
24575	2048-05-04	2048
24576	2048-05-05	2048
24577	2048-05-06	2048
24578	2048-05-07	2048
24579	2048-05-08	2048
24580	2048-05-11	2048
24581	2048-05-12	2048
24582	2048-05-13	2048
24583	2048-05-14	2048
24584	2048-05-15	2048
24585	2048-05-18	2048
24586	2048-05-19	2048
24587	2048-05-20	2048
24588	2048-05-21	2048
24589	2048-05-22	2048
24590	2048-05-25	2048
24591	2048-05-26	2048
24592	2048-05-27	2048
24593	2048-05-28	2048
24594	2048-05-29	2048
24595	2048-06-01	2048
24596	2048-06-02	2048
24597	2048-06-03	2048
24598	2048-06-05	2048
24599	2048-06-08	2048
24600	2048-06-09	2048
24601	2048-06-10	2048
24602	2048-06-11	2048
24603	2048-06-12	2048
24604	2048-06-15	2048
24605	2048-06-16	2048
24606	2048-06-17	2048
24607	2048-06-18	2048
24608	2048-06-19	2048
24609	2048-06-22	2048
24610	2048-06-23	2048
24611	2048-06-24	2048
24612	2048-06-25	2048
24613	2048-06-26	2048
24614	2048-06-29	2048
24615	2048-06-30	2048
24616	2048-07-01	2048
24617	2048-07-02	2048
24618	2048-07-03	2048
24619	2048-07-06	2048
24620	2048-07-07	2048
24621	2048-07-08	2048
24622	2048-07-09	2048
24623	2048-07-10	2048
24624	2048-07-13	2048
24625	2048-07-14	2048
24626	2048-07-15	2048
24627	2048-07-16	2048
24628	2048-07-17	2048
24629	2048-07-20	2048
24630	2048-07-21	2048
24631	2048-07-22	2048
24632	2048-07-23	2048
24633	2048-07-24	2048
24634	2048-07-27	2048
24635	2048-07-28	2048
24636	2048-07-29	2048
24637	2048-07-30	2048
24638	2048-07-31	2048
24639	2048-08-03	2048
24640	2048-08-04	2048
24641	2048-08-05	2048
24642	2048-08-06	2048
24643	2048-08-07	2048
24644	2048-08-10	2048
24645	2048-08-11	2048
24646	2048-08-12	2048
24647	2048-08-13	2048
24648	2048-08-14	2048
24649	2048-08-17	2048
24650	2048-08-18	2048
24651	2048-08-19	2048
24652	2048-08-20	2048
24653	2048-08-21	2048
24654	2048-08-24	2048
24655	2048-08-25	2048
24656	2048-08-26	2048
24657	2048-08-27	2048
24658	2048-08-28	2048
24659	2048-08-31	2048
24660	2048-09-01	2048
24661	2048-09-02	2048
24662	2048-09-03	2048
24663	2048-09-04	2048
24664	2048-09-08	2048
24665	2048-09-09	2048
24666	2048-09-10	2048
24667	2048-09-11	2048
24668	2048-09-14	2048
24669	2048-09-15	2048
24670	2048-09-16	2048
24671	2048-09-17	2048
24672	2048-09-18	2048
24673	2048-09-21	2048
24674	2048-09-22	2048
24675	2048-09-23	2048
24676	2048-09-24	2048
24677	2048-09-25	2048
24678	2048-09-28	2048
24679	2048-09-29	2048
24680	2048-09-30	2048
24681	2048-10-01	2048
24682	2048-10-02	2048
24683	2048-10-05	2048
24684	2048-10-06	2048
24685	2048-10-07	2048
24686	2048-10-08	2048
24687	2048-10-09	2048
24688	2048-10-13	2048
24689	2048-10-14	2048
24690	2048-10-15	2048
24691	2048-10-16	2048
24692	2048-10-19	2048
24693	2048-10-20	2048
24694	2048-10-21	2048
24695	2048-10-22	2048
24696	2048-10-23	2048
24697	2048-10-26	2048
24698	2048-10-27	2048
24699	2048-10-29	2048
24700	2048-10-30	2048
24701	2048-11-03	2048
24702	2048-11-04	2048
24703	2048-11-05	2048
24704	2048-11-06	2048
24705	2048-11-09	2048
24706	2048-11-10	2048
24707	2048-11-11	2048
24708	2048-11-12	2048
24709	2048-11-13	2048
24710	2048-11-16	2048
24711	2048-11-17	2048
24712	2048-11-18	2048
24713	2048-11-19	2048
24714	2048-11-20	2048
24715	2048-11-23	2048
24716	2048-11-24	2048
24717	2048-11-25	2048
24718	2048-11-26	2048
24719	2048-11-27	2048
24720	2048-11-30	2048
24721	2048-12-01	2048
24722	2048-12-02	2048
24723	2048-12-03	2048
24724	2048-12-04	2048
24725	2048-12-07	2048
24726	2048-12-08	2048
24727	2048-12-09	2048
24728	2048-12-10	2048
24729	2048-12-11	2048
24730	2048-12-14	2048
24731	2048-12-15	2048
24732	2048-12-16	2048
24733	2048-12-17	2048
24734	2048-12-18	2048
24735	2048-12-21	2048
24736	2048-12-22	2048
24737	2048-12-23	2048
24738	2048-12-24	2048
24739	2048-12-28	2048
24740	2048-12-29	2048
24741	2048-12-30	2048
24742	2048-12-31	2048
24743	2049-01-04	2049
24744	2049-01-05	2049
24745	2049-01-06	2049
24746	2049-01-07	2049
24747	2049-01-08	2049
24748	2049-01-11	2049
24749	2049-01-12	2049
24750	2049-01-13	2049
24751	2049-01-14	2049
24752	2049-01-15	2049
24753	2049-01-18	2049
24754	2049-01-19	2049
24755	2049-01-20	2049
24756	2049-01-21	2049
24757	2049-01-22	2049
24758	2049-01-25	2049
24759	2049-01-26	2049
24760	2049-01-27	2049
24761	2049-01-28	2049
24762	2049-01-29	2049
24763	2049-02-01	2049
24764	2049-02-02	2049
24765	2049-02-03	2049
24766	2049-02-04	2049
24767	2049-02-05	2049
24768	2049-02-08	2049
24769	2049-02-09	2049
24770	2049-02-10	2049
24771	2049-02-11	2049
24772	2049-02-12	2049
24773	2049-02-15	2049
24774	2049-02-16	2049
24775	2049-02-17	2049
24776	2049-02-18	2049
24777	2049-02-19	2049
24778	2049-02-22	2049
24779	2049-02-23	2049
24780	2049-02-24	2049
24781	2049-02-25	2049
24782	2049-02-26	2049
24783	2049-03-01	2049
24784	2049-03-03	2049
24785	2049-03-04	2049
24786	2049-03-05	2049
24787	2049-03-08	2049
24788	2049-03-09	2049
24789	2049-03-10	2049
24790	2049-03-11	2049
24791	2049-03-12	2049
24792	2049-03-15	2049
24793	2049-03-16	2049
24794	2049-03-17	2049
24795	2049-03-18	2049
24796	2049-03-19	2049
24797	2049-03-22	2049
24798	2049-03-23	2049
24799	2049-03-24	2049
24800	2049-03-25	2049
24801	2049-03-26	2049
24802	2049-03-29	2049
24803	2049-03-30	2049
24804	2049-03-31	2049
24805	2049-04-01	2049
24806	2049-04-02	2049
24807	2049-04-05	2049
24808	2049-04-06	2049
24809	2049-04-07	2049
24810	2049-04-08	2049
24811	2049-04-09	2049
24812	2049-04-12	2049
24813	2049-04-13	2049
24814	2049-04-14	2049
24815	2049-04-15	2049
24816	2049-04-16	2049
24817	2049-04-19	2049
24818	2049-04-20	2049
24819	2049-04-22	2049
24820	2049-04-23	2049
24821	2049-04-26	2049
24822	2049-04-27	2049
24823	2049-04-28	2049
24824	2049-04-29	2049
24825	2049-04-30	2049
24826	2049-05-03	2049
24827	2049-05-04	2049
24828	2049-05-05	2049
24829	2049-05-06	2049
24830	2049-05-07	2049
24831	2049-05-10	2049
24832	2049-05-11	2049
24833	2049-05-12	2049
24834	2049-05-13	2049
24835	2049-05-14	2049
24836	2049-05-17	2049
24837	2049-05-18	2049
24838	2049-05-19	2049
24839	2049-05-20	2049
24840	2049-05-21	2049
24841	2049-05-24	2049
24842	2049-05-25	2049
24843	2049-05-26	2049
24844	2049-05-27	2049
24845	2049-05-28	2049
24846	2049-05-31	2049
24847	2049-06-01	2049
24848	2049-06-02	2049
24849	2049-06-03	2049
24850	2049-06-04	2049
24851	2049-06-07	2049
24852	2049-06-08	2049
24853	2049-06-09	2049
24854	2049-06-10	2049
24855	2049-06-11	2049
24856	2049-06-14	2049
24857	2049-06-15	2049
24858	2049-06-16	2049
24859	2049-06-18	2049
24860	2049-06-21	2049
24861	2049-06-22	2049
24862	2049-06-23	2049
24863	2049-06-24	2049
24864	2049-06-25	2049
24865	2049-06-28	2049
24866	2049-06-29	2049
24867	2049-06-30	2049
24868	2049-07-01	2049
24869	2049-07-02	2049
24870	2049-07-05	2049
24871	2049-07-06	2049
24872	2049-07-07	2049
24873	2049-07-08	2049
24874	2049-07-09	2049
24875	2049-07-12	2049
24876	2049-07-13	2049
24877	2049-07-14	2049
24878	2049-07-15	2049
24879	2049-07-16	2049
24880	2049-07-19	2049
24881	2049-07-20	2049
24882	2049-07-21	2049
24883	2049-07-22	2049
24884	2049-07-23	2049
24885	2049-07-26	2049
24886	2049-07-27	2049
24887	2049-07-28	2049
24888	2049-07-29	2049
24889	2049-07-30	2049
24890	2049-08-02	2049
24891	2049-08-03	2049
24892	2049-08-04	2049
24893	2049-08-05	2049
24894	2049-08-06	2049
24895	2049-08-09	2049
24896	2049-08-10	2049
24897	2049-08-11	2049
24898	2049-08-12	2049
24899	2049-08-13	2049
24900	2049-08-16	2049
24901	2049-08-17	2049
24902	2049-08-18	2049
24903	2049-08-19	2049
24904	2049-08-20	2049
24905	2049-08-23	2049
24906	2049-08-24	2049
24907	2049-08-25	2049
24908	2049-08-26	2049
24909	2049-08-27	2049
24910	2049-08-30	2049
24911	2049-08-31	2049
24912	2049-09-01	2049
24913	2049-09-02	2049
24914	2049-09-03	2049
24915	2049-09-06	2049
24916	2049-09-08	2049
24917	2049-09-09	2049
24918	2049-09-10	2049
24919	2049-09-13	2049
24920	2049-09-14	2049
24921	2049-09-15	2049
24922	2049-09-16	2049
24923	2049-09-17	2049
24924	2049-09-20	2049
24925	2049-09-21	2049
24926	2049-09-22	2049
24927	2049-09-23	2049
24928	2049-09-24	2049
24929	2049-09-27	2049
24930	2049-09-28	2049
24931	2049-09-29	2049
24932	2049-09-30	2049
24933	2049-10-01	2049
24934	2049-10-04	2049
24935	2049-10-05	2049
24936	2049-10-06	2049
24937	2049-10-07	2049
24938	2049-10-08	2049
24939	2049-10-11	2049
24940	2049-10-13	2049
24941	2049-10-14	2049
24942	2049-10-15	2049
24943	2049-10-18	2049
24944	2049-10-19	2049
24945	2049-10-20	2049
24946	2049-10-21	2049
24947	2049-10-22	2049
24948	2049-10-25	2049
24949	2049-10-26	2049
24950	2049-10-27	2049
24951	2049-10-29	2049
24952	2049-11-01	2049
24953	2049-11-03	2049
24954	2049-11-04	2049
24955	2049-11-05	2049
24956	2049-11-08	2049
24957	2049-11-09	2049
24958	2049-11-10	2049
24959	2049-11-11	2049
24960	2049-11-12	2049
24961	2049-11-16	2049
24962	2049-11-17	2049
24963	2049-11-18	2049
24964	2049-11-19	2049
24965	2049-11-22	2049
24966	2049-11-23	2049
24967	2049-11-24	2049
24968	2049-11-25	2049
24969	2049-11-26	2049
24970	2049-11-29	2049
24971	2049-11-30	2049
24972	2049-12-01	2049
24973	2049-12-02	2049
24974	2049-12-03	2049
24975	2049-12-06	2049
24976	2049-12-07	2049
24977	2049-12-08	2049
24978	2049-12-09	2049
24979	2049-12-10	2049
24980	2049-12-13	2049
24981	2049-12-14	2049
24982	2049-12-15	2049
24983	2049-12-16	2049
24984	2049-12-17	2049
24985	2049-12-20	2049
24986	2049-12-21	2049
24987	2049-12-22	2049
24988	2049-12-23	2049
24989	2049-12-24	2049
24990	2049-12-27	2049
24991	2049-12-28	2049
24992	2049-12-29	2049
24993	2049-12-30	2049
24994	2049-12-31	2049
24995	2050-01-03	2050
24996	2050-01-04	2050
24997	2050-01-05	2050
24998	2050-01-06	2050
24999	2050-01-07	2050
25000	2050-01-10	2050
25001	2050-01-11	2050
25002	2050-01-12	2050
25003	2050-01-13	2050
25004	2050-01-14	2050
25005	2050-01-17	2050
25006	2050-01-18	2050
25007	2050-01-19	2050
25008	2050-01-20	2050
25009	2050-01-21	2050
25010	2050-01-24	2050
25011	2050-01-25	2050
25012	2050-01-26	2050
25013	2050-01-27	2050
25014	2050-01-28	2050
25015	2050-01-31	2050
25016	2050-02-01	2050
25017	2050-02-02	2050
25018	2050-02-03	2050
25019	2050-02-04	2050
25020	2050-02-07	2050
25021	2050-02-08	2050
25022	2050-02-09	2050
25023	2050-02-10	2050
25024	2050-02-11	2050
25025	2050-02-14	2050
25026	2050-02-15	2050
25027	2050-02-16	2050
25028	2050-02-17	2050
25029	2050-02-18	2050
25030	2050-02-21	2050
25031	2050-02-23	2050
25032	2050-02-24	2050
25033	2050-02-25	2050
25034	2050-02-28	2050
25035	2050-03-01	2050
25036	2050-03-02	2050
25037	2050-03-03	2050
25038	2050-03-04	2050
25039	2050-03-07	2050
25040	2050-03-08	2050
25041	2050-03-09	2050
25042	2050-03-10	2050
25043	2050-03-11	2050
25044	2050-03-14	2050
25045	2050-03-15	2050
25046	2050-03-16	2050
25047	2050-03-17	2050
25048	2050-03-18	2050
25049	2050-03-21	2050
25050	2050-03-22	2050
25051	2050-03-23	2050
25052	2050-03-24	2050
25053	2050-03-25	2050
25054	2050-03-28	2050
25055	2050-03-29	2050
25056	2050-03-30	2050
25057	2050-03-31	2050
25058	2050-04-01	2050
25059	2050-04-04	2050
25060	2050-04-05	2050
25061	2050-04-06	2050
25062	2050-04-07	2050
25063	2050-04-08	2050
25064	2050-04-11	2050
25065	2050-04-12	2050
25066	2050-04-13	2050
25067	2050-04-14	2050
25068	2050-04-15	2050
25069	2050-04-18	2050
25070	2050-04-19	2050
25071	2050-04-20	2050
25072	2050-04-22	2050
25073	2050-04-25	2050
25074	2050-04-26	2050
25075	2050-04-27	2050
25076	2050-04-28	2050
25077	2050-04-29	2050
25078	2050-05-02	2050
25079	2050-05-03	2050
25080	2050-05-04	2050
25081	2050-05-05	2050
25082	2050-05-06	2050
25083	2050-05-09	2050
25084	2050-05-10	2050
25085	2050-05-11	2050
25086	2050-05-12	2050
25087	2050-05-13	2050
25088	2050-05-16	2050
25089	2050-05-17	2050
25090	2050-05-18	2050
25091	2050-05-19	2050
25092	2050-05-20	2050
25093	2050-05-23	2050
25094	2050-05-24	2050
25095	2050-05-25	2050
25096	2050-05-26	2050
25097	2050-05-27	2050
25098	2050-05-30	2050
25099	2050-05-31	2050
25100	2050-06-01	2050
25101	2050-06-02	2050
25102	2050-06-03	2050
25103	2050-06-06	2050
25104	2050-06-07	2050
25105	2050-06-08	2050
25106	2050-06-10	2050
25107	2050-06-13	2050
25108	2050-06-14	2050
25109	2050-06-15	2050
25110	2050-06-16	2050
25111	2050-06-17	2050
25112	2050-06-20	2050
25113	2050-06-21	2050
25114	2050-06-22	2050
25115	2050-06-23	2050
25116	2050-06-24	2050
25117	2050-06-27	2050
25118	2050-06-28	2050
25119	2050-06-29	2050
25120	2050-06-30	2050
25121	2050-07-01	2050
25122	2050-07-04	2050
25123	2050-07-05	2050
25124	2050-07-06	2050
25125	2050-07-07	2050
25126	2050-07-08	2050
25127	2050-07-11	2050
25128	2050-07-12	2050
25129	2050-07-13	2050
25130	2050-07-14	2050
25131	2050-07-15	2050
25132	2050-07-18	2050
25133	2050-07-19	2050
25134	2050-07-20	2050
25135	2050-07-21	2050
25136	2050-07-22	2050
25137	2050-07-25	2050
25138	2050-07-26	2050
25139	2050-07-27	2050
25140	2050-07-28	2050
25141	2050-07-29	2050
25142	2050-08-01	2050
25143	2050-08-02	2050
25144	2050-08-03	2050
25145	2050-08-04	2050
25146	2050-08-05	2050
25147	2050-08-08	2050
25148	2050-08-09	2050
25149	2050-08-10	2050
25150	2050-08-11	2050
25151	2050-08-12	2050
25152	2050-08-15	2050
25153	2050-08-16	2050
25154	2050-08-17	2050
25155	2050-08-18	2050
25156	2050-08-19	2050
25157	2050-08-22	2050
25158	2050-08-23	2050
25159	2050-08-24	2050
25160	2050-08-25	2050
25161	2050-08-26	2050
25162	2050-08-29	2050
25163	2050-08-30	2050
25164	2050-08-31	2050
25165	2050-09-01	2050
25166	2050-09-02	2050
25167	2050-09-05	2050
25168	2050-09-06	2050
25169	2050-09-08	2050
25170	2050-09-09	2050
25171	2050-09-12	2050
25172	2050-09-13	2050
25173	2050-09-14	2050
25174	2050-09-15	2050
25175	2050-09-16	2050
25176	2050-09-19	2050
25177	2050-09-20	2050
25178	2050-09-21	2050
25179	2050-09-22	2050
25180	2050-09-23	2050
25181	2050-09-26	2050
25182	2050-09-27	2050
25183	2050-09-28	2050
25184	2050-09-29	2050
25185	2050-09-30	2050
25186	2050-10-03	2050
25187	2050-10-04	2050
25188	2050-10-05	2050
25189	2050-10-06	2050
25190	2050-10-07	2050
25191	2050-10-10	2050
25192	2050-10-11	2050
25193	2050-10-13	2050
25194	2050-10-14	2050
25195	2050-10-17	2050
25196	2050-10-18	2050
25197	2050-10-19	2050
25198	2050-10-20	2050
25199	2050-10-21	2050
25200	2050-10-24	2050
25201	2050-10-25	2050
25202	2050-10-26	2050
25203	2050-10-27	2050
25204	2050-10-31	2050
25205	2050-11-01	2050
25206	2050-11-03	2050
25207	2050-11-04	2050
25208	2050-11-07	2050
25209	2050-11-08	2050
25210	2050-11-09	2050
25211	2050-11-10	2050
25212	2050-11-11	2050
25213	2050-11-14	2050
25214	2050-11-16	2050
25215	2050-11-17	2050
25216	2050-11-18	2050
25217	2050-11-21	2050
25218	2050-11-22	2050
25219	2050-11-23	2050
25220	2050-11-24	2050
25221	2050-11-25	2050
25222	2050-11-28	2050
25223	2050-11-29	2050
25224	2050-11-30	2050
25225	2050-12-01	2050
25226	2050-12-02	2050
25227	2050-12-05	2050
25228	2050-12-06	2050
25229	2050-12-07	2050
25230	2050-12-08	2050
25231	2050-12-09	2050
25232	2050-12-12	2050
25233	2050-12-13	2050
25234	2050-12-14	2050
25235	2050-12-15	2050
25236	2050-12-16	2050
25237	2050-12-19	2050
25238	2050-12-20	2050
25239	2050-12-21	2050
25240	2050-12-22	2050
25241	2050-12-23	2050
25242	2050-12-26	2050
25243	2050-12-27	2050
25244	2050-12-28	2050
25245	2050-12-29	2050
25246	2050-12-30	2050
25247	2051-01-02	2051
25248	2051-01-03	2051
25249	2051-01-04	2051
25250	2051-01-05	2051
25251	2051-01-06	2051
25252	2051-01-09	2051
25253	2051-01-10	2051
25254	2051-01-11	2051
25255	2051-01-12	2051
25256	2051-01-13	2051
25257	2051-01-16	2051
25258	2051-01-17	2051
25259	2051-01-18	2051
25260	2051-01-19	2051
25261	2051-01-20	2051
25262	2051-01-23	2051
25263	2051-01-24	2051
25264	2051-01-25	2051
25265	2051-01-26	2051
25266	2051-01-27	2051
25267	2051-01-30	2051
25268	2051-01-31	2051
25269	2051-02-01	2051
25270	2051-02-02	2051
25271	2051-02-03	2051
25272	2051-02-06	2051
25273	2051-02-07	2051
25274	2051-02-08	2051
25275	2051-02-09	2051
25276	2051-02-10	2051
25277	2051-02-13	2051
25278	2051-02-15	2051
25279	2051-02-16	2051
25280	2051-02-17	2051
25281	2051-02-20	2051
25282	2051-02-21	2051
25283	2051-02-22	2051
25284	2051-02-23	2051
25285	2051-02-24	2051
25286	2051-02-27	2051
25287	2051-02-28	2051
25288	2051-03-01	2051
25289	2051-03-02	2051
25290	2051-03-03	2051
25291	2051-03-06	2051
25292	2051-03-07	2051
25293	2051-03-08	2051
25294	2051-03-09	2051
25295	2051-03-10	2051
25296	2051-03-13	2051
25297	2051-03-14	2051
25298	2051-03-15	2051
25299	2051-03-16	2051
25300	2051-03-17	2051
25301	2051-03-20	2051
25302	2051-03-21	2051
25303	2051-03-22	2051
25304	2051-03-23	2051
25305	2051-03-24	2051
25306	2051-03-27	2051
25307	2051-03-28	2051
25308	2051-03-29	2051
25309	2051-03-30	2051
25310	2051-03-31	2051
25311	2051-04-03	2051
25312	2051-04-04	2051
25313	2051-04-05	2051
25314	2051-04-06	2051
25315	2051-04-07	2051
25316	2051-04-10	2051
25317	2051-04-11	2051
25318	2051-04-12	2051
25319	2051-04-13	2051
25320	2051-04-14	2051
25321	2051-04-17	2051
25322	2051-04-18	2051
25323	2051-04-19	2051
25324	2051-04-20	2051
25325	2051-04-24	2051
25326	2051-04-25	2051
25327	2051-04-26	2051
25328	2051-04-27	2051
25329	2051-04-28	2051
25330	2051-05-02	2051
25331	2051-05-03	2051
25332	2051-05-04	2051
25333	2051-05-05	2051
25334	2051-05-08	2051
25335	2051-05-09	2051
25336	2051-05-10	2051
25337	2051-05-11	2051
25338	2051-05-12	2051
25339	2051-05-15	2051
25340	2051-05-16	2051
25341	2051-05-17	2051
25342	2051-05-18	2051
25343	2051-05-19	2051
25344	2051-05-22	2051
25345	2051-05-23	2051
25346	2051-05-24	2051
25347	2051-05-25	2051
25348	2051-05-26	2051
25349	2051-05-29	2051
25350	2051-05-30	2051
25351	2051-05-31	2051
25352	2051-06-02	2051
25353	2051-06-05	2051
25354	2051-06-06	2051
25355	2051-06-07	2051
25356	2051-06-08	2051
25357	2051-06-09	2051
25358	2051-06-12	2051
25359	2051-06-13	2051
25360	2051-06-14	2051
25361	2051-06-15	2051
25362	2051-06-16	2051
25363	2051-06-19	2051
25364	2051-06-20	2051
25365	2051-06-21	2051
25366	2051-06-22	2051
25367	2051-06-23	2051
25368	2051-06-26	2051
25369	2051-06-27	2051
25370	2051-06-28	2051
25371	2051-06-29	2051
25372	2051-06-30	2051
25373	2051-07-03	2051
25374	2051-07-04	2051
25375	2051-07-05	2051
25376	2051-07-06	2051
25377	2051-07-07	2051
25378	2051-07-10	2051
25379	2051-07-11	2051
25380	2051-07-12	2051
25381	2051-07-13	2051
25382	2051-07-14	2051
25383	2051-07-17	2051
25384	2051-07-18	2051
25385	2051-07-19	2051
25386	2051-07-20	2051
25387	2051-07-21	2051
25388	2051-07-24	2051
25389	2051-07-25	2051
25390	2051-07-26	2051
25391	2051-07-27	2051
25392	2051-07-28	2051
25393	2051-07-31	2051
25394	2051-08-01	2051
25395	2051-08-02	2051
25396	2051-08-03	2051
25397	2051-08-04	2051
25398	2051-08-07	2051
25399	2051-08-08	2051
25400	2051-08-09	2051
25401	2051-08-10	2051
25402	2051-08-11	2051
25403	2051-08-14	2051
25404	2051-08-15	2051
25405	2051-08-16	2051
25406	2051-08-17	2051
25407	2051-08-18	2051
25408	2051-08-21	2051
25409	2051-08-22	2051
25410	2051-08-23	2051
25411	2051-08-24	2051
25412	2051-08-25	2051
25413	2051-08-28	2051
25414	2051-08-29	2051
25415	2051-08-30	2051
25416	2051-08-31	2051
25417	2051-09-01	2051
25418	2051-09-04	2051
25419	2051-09-05	2051
25420	2051-09-06	2051
25421	2051-09-08	2051
25422	2051-09-11	2051
25423	2051-09-12	2051
25424	2051-09-13	2051
25425	2051-09-14	2051
25426	2051-09-15	2051
25427	2051-09-18	2051
25428	2051-09-19	2051
25429	2051-09-20	2051
25430	2051-09-21	2051
25431	2051-09-22	2051
25432	2051-09-25	2051
25433	2051-09-26	2051
25434	2051-09-27	2051
25435	2051-09-28	2051
25436	2051-09-29	2051
25437	2051-10-02	2051
25438	2051-10-03	2051
25439	2051-10-04	2051
25440	2051-10-05	2051
25441	2051-10-06	2051
25442	2051-10-09	2051
25443	2051-10-10	2051
25444	2051-10-11	2051
25445	2051-10-13	2051
25446	2051-10-16	2051
25447	2051-10-17	2051
25448	2051-10-18	2051
25449	2051-10-19	2051
25450	2051-10-20	2051
25451	2051-10-23	2051
25452	2051-10-24	2051
25453	2051-10-25	2051
25454	2051-10-26	2051
25455	2051-10-27	2051
25456	2051-10-30	2051
25457	2051-10-31	2051
25458	2051-11-01	2051
25459	2051-11-03	2051
25460	2051-11-06	2051
25461	2051-11-07	2051
25462	2051-11-08	2051
25463	2051-11-09	2051
25464	2051-11-10	2051
25465	2051-11-13	2051
25466	2051-11-14	2051
25467	2051-11-16	2051
25468	2051-11-17	2051
25469	2051-11-20	2051
25470	2051-11-21	2051
25471	2051-11-22	2051
25472	2051-11-23	2051
25473	2051-11-24	2051
25474	2051-11-27	2051
25475	2051-11-28	2051
25476	2051-11-29	2051
25477	2051-11-30	2051
25478	2051-12-01	2051
25479	2051-12-04	2051
25480	2051-12-05	2051
25481	2051-12-06	2051
25482	2051-12-07	2051
25483	2051-12-08	2051
25484	2051-12-11	2051
25485	2051-12-12	2051
25486	2051-12-13	2051
25487	2051-12-14	2051
25488	2051-12-15	2051
25489	2051-12-18	2051
25490	2051-12-19	2051
25491	2051-12-20	2051
25492	2051-12-21	2051
25493	2051-12-22	2051
25494	2051-12-26	2051
25495	2051-12-27	2051
25496	2051-12-28	2051
25497	2051-12-29	2051
25498	2052-01-02	2052
25499	2052-01-03	2052
25500	2052-01-04	2052
25501	2052-01-05	2052
25502	2052-01-08	2052
25503	2052-01-09	2052
25504	2052-01-10	2052
25505	2052-01-11	2052
25506	2052-01-12	2052
25507	2052-01-15	2052
25508	2052-01-16	2052
25509	2052-01-17	2052
25510	2052-01-18	2052
25511	2052-01-19	2052
25512	2052-01-22	2052
25513	2052-01-23	2052
25514	2052-01-24	2052
25515	2052-01-25	2052
25516	2052-01-26	2052
25517	2052-01-29	2052
25518	2052-01-30	2052
25519	2052-01-31	2052
25520	2052-02-01	2052
25521	2052-02-02	2052
25522	2052-02-05	2052
25523	2052-02-06	2052
25524	2052-02-07	2052
25525	2052-02-08	2052
25526	2052-02-09	2052
25527	2052-02-12	2052
25528	2052-02-13	2052
25529	2052-02-14	2052
25530	2052-02-15	2052
25531	2052-02-16	2052
25532	2052-02-19	2052
25533	2052-02-20	2052
25534	2052-02-21	2052
25535	2052-02-22	2052
25536	2052-02-23	2052
25537	2052-02-26	2052
25538	2052-02-27	2052
25539	2052-02-28	2052
25540	2052-02-29	2052
25541	2052-03-01	2052
25542	2052-03-04	2052
25543	2052-03-06	2052
25544	2052-03-07	2052
25545	2052-03-08	2052
25546	2052-03-11	2052
25547	2052-03-12	2052
25548	2052-03-13	2052
25549	2052-03-14	2052
25550	2052-03-15	2052
25551	2052-03-18	2052
25552	2052-03-19	2052
25553	2052-03-20	2052
25554	2052-03-21	2052
25555	2052-03-22	2052
25556	2052-03-25	2052
25557	2052-03-26	2052
25558	2052-03-27	2052
25559	2052-03-28	2052
25560	2052-03-29	2052
25561	2052-04-01	2052
25562	2052-04-02	2052
25563	2052-04-03	2052
25564	2052-04-04	2052
25565	2052-04-05	2052
25566	2052-04-08	2052
25567	2052-04-09	2052
25568	2052-04-10	2052
25569	2052-04-11	2052
25570	2052-04-12	2052
25571	2052-04-15	2052
25572	2052-04-16	2052
25573	2052-04-17	2052
25574	2052-04-18	2052
25575	2052-04-19	2052
25576	2052-04-22	2052
25577	2052-04-23	2052
25578	2052-04-24	2052
25579	2052-04-25	2052
25580	2052-04-26	2052
25581	2052-04-29	2052
25582	2052-04-30	2052
25583	2052-05-02	2052
25584	2052-05-03	2052
25585	2052-05-06	2052
25586	2052-05-07	2052
25587	2052-05-08	2052
25588	2052-05-09	2052
25589	2052-05-10	2052
25590	2052-05-13	2052
25591	2052-05-14	2052
25592	2052-05-15	2052
25593	2052-05-16	2052
25594	2052-05-17	2052
25595	2052-05-20	2052
25596	2052-05-21	2052
25597	2052-05-22	2052
25598	2052-05-23	2052
25599	2052-05-24	2052
25600	2052-05-27	2052
25601	2052-05-28	2052
25602	2052-05-29	2052
25603	2052-05-30	2052
25604	2052-05-31	2052
25605	2052-06-03	2052
25606	2052-06-04	2052
25607	2052-06-05	2052
25608	2052-06-06	2052
25609	2052-06-07	2052
25610	2052-06-10	2052
25611	2052-06-11	2052
25612	2052-06-12	2052
25613	2052-06-13	2052
25614	2052-06-14	2052
25615	2052-06-17	2052
25616	2052-06-18	2052
25617	2052-06-19	2052
25618	2052-06-21	2052
25619	2052-06-24	2052
25620	2052-06-25	2052
25621	2052-06-26	2052
25622	2052-06-27	2052
25623	2052-06-28	2052
25624	2052-07-01	2052
25625	2052-07-02	2052
25626	2052-07-03	2052
25627	2052-07-04	2052
25628	2052-07-05	2052
25629	2052-07-08	2052
25630	2052-07-09	2052
25631	2052-07-10	2052
25632	2052-07-11	2052
25633	2052-07-12	2052
25634	2052-07-15	2052
25635	2052-07-16	2052
25636	2052-07-17	2052
25637	2052-07-18	2052
25638	2052-07-19	2052
25639	2052-07-22	2052
25640	2052-07-23	2052
25641	2052-07-24	2052
25642	2052-07-25	2052
25643	2052-07-26	2052
25644	2052-07-29	2052
25645	2052-07-30	2052
25646	2052-07-31	2052
25647	2052-08-01	2052
25648	2052-08-02	2052
25649	2052-08-05	2052
25650	2052-08-06	2052
25651	2052-08-07	2052
25652	2052-08-08	2052
25653	2052-08-09	2052
25654	2052-08-12	2052
25655	2052-08-13	2052
25656	2052-08-14	2052
25657	2052-08-15	2052
25658	2052-08-16	2052
25659	2052-08-19	2052
25660	2052-08-20	2052
25661	2052-08-21	2052
25662	2052-08-22	2052
25663	2052-08-23	2052
25664	2052-08-26	2052
25665	2052-08-27	2052
25666	2052-08-28	2052
25667	2052-08-29	2052
25668	2052-08-30	2052
25669	2052-09-02	2052
25670	2052-09-03	2052
25671	2052-09-04	2052
25672	2052-09-05	2052
25673	2052-09-06	2052
25674	2052-09-09	2052
25675	2052-09-10	2052
25676	2052-09-11	2052
25677	2052-09-12	2052
25678	2052-09-13	2052
25679	2052-09-16	2052
25680	2052-09-17	2052
25681	2052-09-18	2052
25682	2052-09-19	2052
25683	2052-09-20	2052
25684	2052-09-23	2052
25685	2052-09-24	2052
25686	2052-09-25	2052
25687	2052-09-26	2052
25688	2052-09-27	2052
25689	2052-09-30	2052
25690	2052-10-01	2052
25691	2052-10-02	2052
25692	2052-10-03	2052
25693	2052-10-04	2052
25694	2052-10-07	2052
25695	2052-10-08	2052
25696	2052-10-09	2052
25697	2052-10-10	2052
25698	2052-10-11	2052
25699	2052-10-14	2052
25700	2052-10-15	2052
25701	2052-10-16	2052
25702	2052-10-17	2052
25703	2052-10-18	2052
25704	2052-10-21	2052
25705	2052-10-22	2052
25706	2052-10-23	2052
25707	2052-10-24	2052
25708	2052-10-25	2052
25709	2052-10-29	2052
25710	2052-10-30	2052
25711	2052-10-31	2052
25712	2052-11-01	2052
25713	2052-11-04	2052
25714	2052-11-05	2052
25715	2052-11-06	2052
25716	2052-11-07	2052
25717	2052-11-08	2052
25718	2052-11-11	2052
25719	2052-11-12	2052
25720	2052-11-13	2052
25721	2052-11-14	2052
25722	2052-11-18	2052
25723	2052-11-19	2052
25724	2052-11-20	2052
25725	2052-11-21	2052
25726	2052-11-22	2052
25727	2052-11-25	2052
25728	2052-11-26	2052
25729	2052-11-27	2052
25730	2052-11-28	2052
25731	2052-11-29	2052
25732	2052-12-02	2052
25733	2052-12-03	2052
25734	2052-12-04	2052
25735	2052-12-05	2052
25736	2052-12-06	2052
25737	2052-12-09	2052
25738	2052-12-10	2052
25739	2052-12-11	2052
25740	2052-12-12	2052
25741	2052-12-13	2052
25742	2052-12-16	2052
25743	2052-12-17	2052
25744	2052-12-18	2052
25745	2052-12-19	2052
25746	2052-12-20	2052
25747	2052-12-23	2052
25748	2052-12-24	2052
25749	2052-12-26	2052
25750	2052-12-27	2052
25751	2052-12-30	2052
25752	2052-12-31	2052
25753	2053-01-02	2053
25754	2053-01-03	2053
25755	2053-01-06	2053
25756	2053-01-07	2053
25757	2053-01-08	2053
25758	2053-01-09	2053
25759	2053-01-10	2053
25760	2053-01-13	2053
25761	2053-01-14	2053
25762	2053-01-15	2053
25763	2053-01-16	2053
25764	2053-01-17	2053
25765	2053-01-20	2053
25766	2053-01-21	2053
25767	2053-01-22	2053
25768	2053-01-23	2053
25769	2053-01-24	2053
25770	2053-01-27	2053
25771	2053-01-28	2053
25772	2053-01-29	2053
25773	2053-01-30	2053
25774	2053-01-31	2053
25775	2053-02-03	2053
25776	2053-02-04	2053
25777	2053-02-05	2053
25778	2053-02-06	2053
25779	2053-02-07	2053
25780	2053-02-10	2053
25781	2053-02-11	2053
25782	2053-02-12	2053
25783	2053-02-13	2053
25784	2053-02-14	2053
25785	2053-02-17	2053
25786	2053-02-19	2053
25787	2053-02-20	2053
25788	2053-02-21	2053
25789	2053-02-24	2053
25790	2053-02-25	2053
25791	2053-02-26	2053
25792	2053-02-27	2053
25793	2053-02-28	2053
25794	2053-03-03	2053
25795	2053-03-04	2053
25796	2053-03-05	2053
25797	2053-03-06	2053
25798	2053-03-07	2053
25799	2053-03-10	2053
25800	2053-03-11	2053
25801	2053-03-12	2053
25802	2053-03-13	2053
25803	2053-03-14	2053
25804	2053-03-17	2053
25805	2053-03-18	2053
25806	2053-03-19	2053
25807	2053-03-20	2053
25808	2053-03-21	2053
25809	2053-03-24	2053
25810	2053-03-25	2053
25811	2053-03-26	2053
25812	2053-03-27	2053
25813	2053-03-28	2053
25814	2053-03-31	2053
25815	2053-04-01	2053
25816	2053-04-02	2053
25817	2053-04-03	2053
25818	2053-04-04	2053
25819	2053-04-07	2053
25820	2053-04-08	2053
25821	2053-04-09	2053
25822	2053-04-10	2053
25823	2053-04-11	2053
25824	2053-04-14	2053
25825	2053-04-15	2053
25826	2053-04-16	2053
25827	2053-04-17	2053
25828	2053-04-18	2053
25829	2053-04-22	2053
25830	2053-04-23	2053
25831	2053-04-24	2053
25832	2053-04-25	2053
25833	2053-04-28	2053
25834	2053-04-29	2053
25835	2053-04-30	2053
25836	2053-05-02	2053
25837	2053-05-05	2053
25838	2053-05-06	2053
25839	2053-05-07	2053
25840	2053-05-08	2053
25841	2053-05-09	2053
25842	2053-05-12	2053
25843	2053-05-13	2053
25844	2053-05-14	2053
25845	2053-05-15	2053
25846	2053-05-16	2053
25847	2053-05-19	2053
25848	2053-05-20	2053
25849	2053-05-21	2053
25850	2053-05-22	2053
25851	2053-05-23	2053
25852	2053-05-26	2053
25853	2053-05-27	2053
25854	2053-05-28	2053
25855	2053-05-29	2053
25856	2053-05-30	2053
25857	2053-06-02	2053
25858	2053-06-03	2053
25859	2053-06-04	2053
25860	2053-06-06	2053
25861	2053-06-09	2053
25862	2053-06-10	2053
25863	2053-06-11	2053
25864	2053-06-12	2053
25865	2053-06-13	2053
25866	2053-06-16	2053
25867	2053-06-17	2053
25868	2053-06-18	2053
25869	2053-06-19	2053
25870	2053-06-20	2053
25871	2053-06-23	2053
25872	2053-06-24	2053
25873	2053-06-25	2053
25874	2053-06-26	2053
25875	2053-06-27	2053
25876	2053-06-30	2053
25877	2053-07-01	2053
25878	2053-07-02	2053
25879	2053-07-03	2053
25880	2053-07-04	2053
25881	2053-07-07	2053
25882	2053-07-08	2053
25883	2053-07-09	2053
25884	2053-07-10	2053
25885	2053-07-11	2053
25886	2053-07-14	2053
25887	2053-07-15	2053
25888	2053-07-16	2053
25889	2053-07-17	2053
25890	2053-07-18	2053
25891	2053-07-21	2053
25892	2053-07-22	2053
25893	2053-07-23	2053
25894	2053-07-24	2053
25895	2053-07-25	2053
25896	2053-07-28	2053
25897	2053-07-29	2053
25898	2053-07-30	2053
25899	2053-07-31	2053
25900	2053-08-01	2053
25901	2053-08-04	2053
25902	2053-08-05	2053
25903	2053-08-06	2053
25904	2053-08-07	2053
25905	2053-08-08	2053
25906	2053-08-11	2053
25907	2053-08-12	2053
25908	2053-08-13	2053
25909	2053-08-14	2053
25910	2053-08-15	2053
25911	2053-08-18	2053
25912	2053-08-19	2053
25913	2053-08-20	2053
25914	2053-08-21	2053
25915	2053-08-22	2053
25916	2053-08-25	2053
25917	2053-08-26	2053
25918	2053-08-27	2053
25919	2053-08-28	2053
25920	2053-08-29	2053
25921	2053-09-01	2053
25922	2053-09-02	2053
25923	2053-09-03	2053
25924	2053-09-04	2053
25925	2053-09-05	2053
25926	2053-09-08	2053
25927	2053-09-09	2053
25928	2053-09-10	2053
25929	2053-09-11	2053
25930	2053-09-12	2053
25931	2053-09-15	2053
25932	2053-09-16	2053
25933	2053-09-17	2053
25934	2053-09-18	2053
25935	2053-09-19	2053
25936	2053-09-22	2053
25937	2053-09-23	2053
25938	2053-09-24	2053
25939	2053-09-25	2053
25940	2053-09-26	2053
25941	2053-09-29	2053
25942	2053-09-30	2053
25943	2053-10-01	2053
25944	2053-10-02	2053
25945	2053-10-03	2053
25946	2053-10-06	2053
25947	2053-10-07	2053
25948	2053-10-08	2053
25949	2053-10-09	2053
25950	2053-10-10	2053
25951	2053-10-13	2053
25952	2053-10-14	2053
25953	2053-10-15	2053
25954	2053-10-16	2053
25955	2053-10-17	2053
25956	2053-10-20	2053
25957	2053-10-21	2053
25958	2053-10-22	2053
25959	2053-10-23	2053
25960	2053-10-24	2053
25961	2053-10-27	2053
25962	2053-10-29	2053
25963	2053-10-30	2053
25964	2053-10-31	2053
25965	2053-11-03	2053
25966	2053-11-04	2053
25967	2053-11-05	2053
25968	2053-11-06	2053
25969	2053-11-07	2053
25970	2053-11-10	2053
25971	2053-11-11	2053
25972	2053-11-12	2053
25973	2053-11-13	2053
25974	2053-11-14	2053
25975	2053-11-17	2053
25976	2053-11-18	2053
25977	2053-11-19	2053
25978	2053-11-20	2053
25979	2053-11-21	2053
25980	2053-11-24	2053
25981	2053-11-25	2053
25982	2053-11-26	2053
25983	2053-11-27	2053
25984	2053-11-28	2053
25985	2053-12-01	2053
25986	2053-12-02	2053
25987	2053-12-03	2053
25988	2053-12-04	2053
25989	2053-12-05	2053
25990	2053-12-08	2053
25991	2053-12-09	2053
25992	2053-12-10	2053
25993	2053-12-11	2053
25994	2053-12-12	2053
25995	2053-12-15	2053
25996	2053-12-16	2053
25997	2053-12-17	2053
25998	2053-12-18	2053
25999	2053-12-19	2053
26000	2053-12-22	2053
26001	2053-12-23	2053
26002	2053-12-24	2053
26003	2053-12-26	2053
26004	2053-12-29	2053
26005	2053-12-30	2053
26006	2053-12-31	2053
26007	2054-01-02	2054
26008	2054-01-05	2054
26009	2054-01-06	2054
26010	2054-01-07	2054
26011	2054-01-08	2054
26012	2054-01-09	2054
26013	2054-01-12	2054
26014	2054-01-13	2054
26015	2054-01-14	2054
26016	2054-01-15	2054
26017	2054-01-16	2054
26018	2054-01-19	2054
26019	2054-01-20	2054
26020	2054-01-21	2054
26021	2054-01-22	2054
26022	2054-01-23	2054
26023	2054-01-26	2054
26024	2054-01-27	2054
26025	2054-01-28	2054
26026	2054-01-29	2054
26027	2054-01-30	2054
26028	2054-02-02	2054
26029	2054-02-03	2054
26030	2054-02-04	2054
26031	2054-02-05	2054
26032	2054-02-06	2054
26033	2054-02-09	2054
26034	2054-02-11	2054
26035	2054-02-12	2054
26036	2054-02-13	2054
26037	2054-02-16	2054
26038	2054-02-17	2054
26039	2054-02-18	2054
26040	2054-02-19	2054
26041	2054-02-20	2054
26042	2054-02-23	2054
26043	2054-02-24	2054
26044	2054-02-25	2054
26045	2054-02-26	2054
26046	2054-02-27	2054
26047	2054-03-02	2054
26048	2054-03-03	2054
26049	2054-03-04	2054
26050	2054-03-05	2054
26051	2054-03-06	2054
26052	2054-03-09	2054
26053	2054-03-10	2054
26054	2054-03-11	2054
26055	2054-03-12	2054
26056	2054-03-13	2054
26057	2054-03-16	2054
26058	2054-03-17	2054
26059	2054-03-18	2054
26060	2054-03-19	2054
26061	2054-03-20	2054
26062	2054-03-23	2054
26063	2054-03-24	2054
26064	2054-03-25	2054
26065	2054-03-26	2054
26066	2054-03-27	2054
26067	2054-03-30	2054
26068	2054-03-31	2054
26069	2054-04-01	2054
26070	2054-04-02	2054
26071	2054-04-03	2054
26072	2054-04-06	2054
26073	2054-04-07	2054
26074	2054-04-08	2054
26075	2054-04-09	2054
26076	2054-04-10	2054
26077	2054-04-13	2054
26078	2054-04-14	2054
26079	2054-04-15	2054
26080	2054-04-16	2054
26081	2054-04-17	2054
26082	2054-04-20	2054
26083	2054-04-22	2054
26084	2054-04-23	2054
26085	2054-04-24	2054
26086	2054-04-27	2054
26087	2054-04-28	2054
26088	2054-04-29	2054
26089	2054-04-30	2054
26090	2054-05-04	2054
26091	2054-05-05	2054
26092	2054-05-06	2054
26093	2054-05-07	2054
26094	2054-05-08	2054
26095	2054-05-11	2054
26096	2054-05-12	2054
26097	2054-05-13	2054
26098	2054-05-14	2054
26099	2054-05-15	2054
26100	2054-05-18	2054
26101	2054-05-19	2054
26102	2054-05-20	2054
26103	2054-05-21	2054
26104	2054-05-22	2054
26105	2054-05-25	2054
26106	2054-05-26	2054
26107	2054-05-27	2054
26108	2054-05-29	2054
26109	2054-06-01	2054
26110	2054-06-02	2054
26111	2054-06-03	2054
26112	2054-06-04	2054
26113	2054-06-05	2054
26114	2054-06-08	2054
26115	2054-06-09	2054
26116	2054-06-10	2054
26117	2054-06-11	2054
26118	2054-06-12	2054
26119	2054-06-15	2054
26120	2054-06-16	2054
26121	2054-06-17	2054
26122	2054-06-18	2054
26123	2054-06-19	2054
26124	2054-06-22	2054
26125	2054-06-23	2054
26126	2054-06-24	2054
26127	2054-06-25	2054
26128	2054-06-26	2054
26129	2054-06-29	2054
26130	2054-06-30	2054
26131	2054-07-01	2054
26132	2054-07-02	2054
26133	2054-07-03	2054
26134	2054-07-06	2054
26135	2054-07-07	2054
26136	2054-07-08	2054
26137	2054-07-09	2054
26138	2054-07-10	2054
26139	2054-07-13	2054
26140	2054-07-14	2054
26141	2054-07-15	2054
26142	2054-07-16	2054
26143	2054-07-17	2054
26144	2054-07-20	2054
26145	2054-07-21	2054
26146	2054-07-22	2054
26147	2054-07-23	2054
26148	2054-07-24	2054
26149	2054-07-27	2054
26150	2054-07-28	2054
26151	2054-07-29	2054
26152	2054-07-30	2054
26153	2054-07-31	2054
26154	2054-08-03	2054
26155	2054-08-04	2054
26156	2054-08-05	2054
26157	2054-08-06	2054
26158	2054-08-07	2054
26159	2054-08-10	2054
26160	2054-08-11	2054
26161	2054-08-12	2054
26162	2054-08-13	2054
26163	2054-08-14	2054
26164	2054-08-17	2054
26165	2054-08-18	2054
26166	2054-08-19	2054
26167	2054-08-20	2054
26168	2054-08-21	2054
26169	2054-08-24	2054
26170	2054-08-25	2054
26171	2054-08-26	2054
26172	2054-08-27	2054
26173	2054-08-28	2054
26174	2054-08-31	2054
26175	2054-09-01	2054
26176	2054-09-02	2054
26177	2054-09-03	2054
26178	2054-09-04	2054
26179	2054-09-08	2054
26180	2054-09-09	2054
26181	2054-09-10	2054
26182	2054-09-11	2054
26183	2054-09-14	2054
26184	2054-09-15	2054
26185	2054-09-16	2054
26186	2054-09-17	2054
26187	2054-09-18	2054
26188	2054-09-21	2054
26189	2054-09-22	2054
26190	2054-09-23	2054
26191	2054-09-24	2054
26192	2054-09-25	2054
26193	2054-09-28	2054
26194	2054-09-29	2054
26195	2054-09-30	2054
26196	2054-10-01	2054
26197	2054-10-02	2054
26198	2054-10-05	2054
26199	2054-10-06	2054
26200	2054-10-07	2054
26201	2054-10-08	2054
26202	2054-10-09	2054
26203	2054-10-13	2054
26204	2054-10-14	2054
26205	2054-10-15	2054
26206	2054-10-16	2054
26207	2054-10-19	2054
26208	2054-10-20	2054
26209	2054-10-21	2054
26210	2054-10-22	2054
26211	2054-10-23	2054
26212	2054-10-26	2054
26213	2054-10-27	2054
26214	2054-10-29	2054
26215	2054-10-30	2054
26216	2054-11-03	2054
26217	2054-11-04	2054
26218	2054-11-05	2054
26219	2054-11-06	2054
26220	2054-11-09	2054
26221	2054-11-10	2054
26222	2054-11-11	2054
26223	2054-11-12	2054
26224	2054-11-13	2054
26225	2054-11-16	2054
26226	2054-11-17	2054
26227	2054-11-18	2054
26228	2054-11-19	2054
26229	2054-11-20	2054
26230	2054-11-23	2054
26231	2054-11-24	2054
26232	2054-11-25	2054
26233	2054-11-26	2054
26234	2054-11-27	2054
26235	2054-11-30	2054
26236	2054-12-01	2054
26237	2054-12-02	2054
26238	2054-12-03	2054
26239	2054-12-04	2054
26240	2054-12-07	2054
26241	2054-12-08	2054
26242	2054-12-09	2054
26243	2054-12-10	2054
26244	2054-12-11	2054
26245	2054-12-14	2054
26246	2054-12-15	2054
26247	2054-12-16	2054
26248	2054-12-17	2054
26249	2054-12-18	2054
26250	2054-12-21	2054
26251	2054-12-22	2054
26252	2054-12-23	2054
26253	2054-12-24	2054
26254	2054-12-28	2054
26255	2054-12-29	2054
26256	2054-12-30	2054
26257	2054-12-31	2054
26258	2055-01-04	2055
26259	2055-01-05	2055
26260	2055-01-06	2055
26261	2055-01-07	2055
26262	2055-01-08	2055
26263	2055-01-11	2055
26264	2055-01-12	2055
26265	2055-01-13	2055
26266	2055-01-14	2055
26267	2055-01-15	2055
26268	2055-01-18	2055
26269	2055-01-19	2055
26270	2055-01-20	2055
26271	2055-01-21	2055
26272	2055-01-22	2055
26273	2055-01-25	2055
26274	2055-01-26	2055
26275	2055-01-27	2055
26276	2055-01-28	2055
26277	2055-01-29	2055
26278	2055-02-01	2055
26279	2055-02-02	2055
26280	2055-02-03	2055
26281	2055-02-04	2055
26282	2055-02-05	2055
26283	2055-02-08	2055
26284	2055-02-09	2055
26285	2055-02-10	2055
26286	2055-02-11	2055
26287	2055-02-12	2055
26288	2055-02-15	2055
26289	2055-02-16	2055
26290	2055-02-17	2055
26291	2055-02-18	2055
26292	2055-02-19	2055
26293	2055-02-22	2055
26294	2055-02-23	2055
26295	2055-02-24	2055
26296	2055-02-25	2055
26297	2055-02-26	2055
26298	2055-03-01	2055
26299	2055-03-03	2055
26300	2055-03-04	2055
26301	2055-03-05	2055
26302	2055-03-08	2055
26303	2055-03-09	2055
26304	2055-03-10	2055
26305	2055-03-11	2055
26306	2055-03-12	2055
26307	2055-03-15	2055
26308	2055-03-16	2055
26309	2055-03-17	2055
26310	2055-03-18	2055
26311	2055-03-19	2055
26312	2055-03-22	2055
26313	2055-03-23	2055
26314	2055-03-24	2055
26315	2055-03-25	2055
26316	2055-03-26	2055
26317	2055-03-29	2055
26318	2055-03-30	2055
26319	2055-03-31	2055
26320	2055-04-01	2055
26321	2055-04-02	2055
26322	2055-04-05	2055
26323	2055-04-06	2055
26324	2055-04-07	2055
26325	2055-04-08	2055
26326	2055-04-09	2055
26327	2055-04-12	2055
26328	2055-04-13	2055
26329	2055-04-14	2055
26330	2055-04-15	2055
26331	2055-04-16	2055
26332	2055-04-19	2055
26333	2055-04-20	2055
26334	2055-04-22	2055
26335	2055-04-23	2055
26336	2055-04-26	2055
26337	2055-04-27	2055
26338	2055-04-28	2055
26339	2055-04-29	2055
26340	2055-04-30	2055
26341	2055-05-03	2055
26342	2055-05-04	2055
26343	2055-05-05	2055
26344	2055-05-06	2055
26345	2055-05-07	2055
26346	2055-05-10	2055
26347	2055-05-11	2055
26348	2055-05-12	2055
26349	2055-05-13	2055
26350	2055-05-14	2055
26351	2055-05-17	2055
26352	2055-05-18	2055
26353	2055-05-19	2055
26354	2055-05-20	2055
26355	2055-05-21	2055
26356	2055-05-24	2055
26357	2055-05-25	2055
26358	2055-05-26	2055
26359	2055-05-27	2055
26360	2055-05-28	2055
26361	2055-05-31	2055
26362	2055-06-01	2055
26363	2055-06-02	2055
26364	2055-06-03	2055
26365	2055-06-04	2055
26366	2055-06-07	2055
26367	2055-06-08	2055
26368	2055-06-09	2055
26369	2055-06-10	2055
26370	2055-06-11	2055
26371	2055-06-14	2055
26372	2055-06-15	2055
26373	2055-06-16	2055
26374	2055-06-18	2055
26375	2055-06-21	2055
26376	2055-06-22	2055
26377	2055-06-23	2055
26378	2055-06-24	2055
26379	2055-06-25	2055
26380	2055-06-28	2055
26381	2055-06-29	2055
26382	2055-06-30	2055
26383	2055-07-01	2055
26384	2055-07-02	2055
26385	2055-07-05	2055
26386	2055-07-06	2055
26387	2055-07-07	2055
26388	2055-07-08	2055
26389	2055-07-09	2055
26390	2055-07-12	2055
26391	2055-07-13	2055
26392	2055-07-14	2055
26393	2055-07-15	2055
26394	2055-07-16	2055
26395	2055-07-19	2055
26396	2055-07-20	2055
26397	2055-07-21	2055
26398	2055-07-22	2055
26399	2055-07-23	2055
26400	2055-07-26	2055
26401	2055-07-27	2055
26402	2055-07-28	2055
26403	2055-07-29	2055
26404	2055-07-30	2055
26405	2055-08-02	2055
26406	2055-08-03	2055
26407	2055-08-04	2055
26408	2055-08-05	2055
26409	2055-08-06	2055
26410	2055-08-09	2055
26411	2055-08-10	2055
26412	2055-08-11	2055
26413	2055-08-12	2055
26414	2055-08-13	2055
26415	2055-08-16	2055
26416	2055-08-17	2055
26417	2055-08-18	2055
26418	2055-08-19	2055
26419	2055-08-20	2055
26420	2055-08-23	2055
26421	2055-08-24	2055
26422	2055-08-25	2055
26423	2055-08-26	2055
26424	2055-08-27	2055
26425	2055-08-30	2055
26426	2055-08-31	2055
26427	2055-09-01	2055
26428	2055-09-02	2055
26429	2055-09-03	2055
26430	2055-09-06	2055
26431	2055-09-08	2055
26432	2055-09-09	2055
26433	2055-09-10	2055
26434	2055-09-13	2055
26435	2055-09-14	2055
26436	2055-09-15	2055
26437	2055-09-16	2055
26438	2055-09-17	2055
26439	2055-09-20	2055
26440	2055-09-21	2055
26441	2055-09-22	2055
26442	2055-09-23	2055
26443	2055-09-24	2055
26444	2055-09-27	2055
26445	2055-09-28	2055
26446	2055-09-29	2055
26447	2055-09-30	2055
26448	2055-10-01	2055
26449	2055-10-04	2055
26450	2055-10-05	2055
26451	2055-10-06	2055
26452	2055-10-07	2055
26453	2055-10-08	2055
26454	2055-10-11	2055
26455	2055-10-13	2055
26456	2055-10-14	2055
26457	2055-10-15	2055
26458	2055-10-18	2055
26459	2055-10-19	2055
26460	2055-10-20	2055
26461	2055-10-21	2055
26462	2055-10-22	2055
26463	2055-10-25	2055
26464	2055-10-26	2055
26465	2055-10-27	2055
26466	2055-10-29	2055
26467	2055-11-01	2055
26468	2055-11-03	2055
26469	2055-11-04	2055
26470	2055-11-05	2055
26471	2055-11-08	2055
26472	2055-11-09	2055
26473	2055-11-10	2055
26474	2055-11-11	2055
26475	2055-11-12	2055
26476	2055-11-16	2055
26477	2055-11-17	2055
26478	2055-11-18	2055
26479	2055-11-19	2055
26480	2055-11-22	2055
26481	2055-11-23	2055
26482	2055-11-24	2055
26483	2055-11-25	2055
26484	2055-11-26	2055
26485	2055-11-29	2055
26486	2055-11-30	2055
26487	2055-12-01	2055
26488	2055-12-02	2055
26489	2055-12-03	2055
26490	2055-12-06	2055
26491	2055-12-07	2055
26492	2055-12-08	2055
26493	2055-12-09	2055
26494	2055-12-10	2055
26495	2055-12-13	2055
26496	2055-12-14	2055
26497	2055-12-15	2055
26498	2055-12-16	2055
26499	2055-12-17	2055
26500	2055-12-20	2055
26501	2055-12-21	2055
26502	2055-12-22	2055
26503	2055-12-23	2055
26504	2055-12-24	2055
26505	2055-12-27	2055
26506	2055-12-28	2055
26507	2055-12-29	2055
26508	2055-12-30	2055
26509	2055-12-31	2055
26510	2056-01-03	2056
26511	2056-01-04	2056
26512	2056-01-05	2056
26513	2056-01-06	2056
26514	2056-01-07	2056
26515	2056-01-10	2056
26516	2056-01-11	2056
26517	2056-01-12	2056
26518	2056-01-13	2056
26519	2056-01-14	2056
26520	2056-01-17	2056
26521	2056-01-18	2056
26522	2056-01-19	2056
26523	2056-01-20	2056
26524	2056-01-21	2056
26525	2056-01-24	2056
26526	2056-01-25	2056
26527	2056-01-26	2056
26528	2056-01-27	2056
26529	2056-01-28	2056
26530	2056-01-31	2056
26531	2056-02-01	2056
26532	2056-02-02	2056
26533	2056-02-03	2056
26534	2056-02-04	2056
26535	2056-02-07	2056
26536	2056-02-08	2056
26537	2056-02-09	2056
26538	2056-02-10	2056
26539	2056-02-11	2056
26540	2056-02-14	2056
26541	2056-02-16	2056
26542	2056-02-17	2056
26543	2056-02-18	2056
26544	2056-02-21	2056
26545	2056-02-22	2056
26546	2056-02-23	2056
26547	2056-02-24	2056
26548	2056-02-25	2056
26549	2056-02-28	2056
26550	2056-02-29	2056
26551	2056-03-01	2056
26552	2056-03-02	2056
26553	2056-03-03	2056
26554	2056-03-06	2056
26555	2056-03-07	2056
26556	2056-03-08	2056
26557	2056-03-09	2056
26558	2056-03-10	2056
26559	2056-03-13	2056
26560	2056-03-14	2056
26561	2056-03-15	2056
26562	2056-03-16	2056
26563	2056-03-17	2056
26564	2056-03-20	2056
26565	2056-03-21	2056
26566	2056-03-22	2056
26567	2056-03-23	2056
26568	2056-03-24	2056
26569	2056-03-27	2056
26570	2056-03-28	2056
26571	2056-03-29	2056
26572	2056-03-30	2056
26573	2056-03-31	2056
26574	2056-04-03	2056
26575	2056-04-04	2056
26576	2056-04-05	2056
26577	2056-04-06	2056
26578	2056-04-07	2056
26579	2056-04-10	2056
26580	2056-04-11	2056
26581	2056-04-12	2056
26582	2056-04-13	2056
26583	2056-04-14	2056
26584	2056-04-17	2056
26585	2056-04-18	2056
26586	2056-04-19	2056
26587	2056-04-20	2056
26588	2056-04-24	2056
26589	2056-04-25	2056
26590	2056-04-26	2056
26591	2056-04-27	2056
26592	2056-04-28	2056
26593	2056-05-02	2056
26594	2056-05-03	2056
26595	2056-05-04	2056
26596	2056-05-05	2056
26597	2056-05-08	2056
26598	2056-05-09	2056
26599	2056-05-10	2056
26600	2056-05-11	2056
26601	2056-05-12	2056
26602	2056-05-15	2056
26603	2056-05-16	2056
26604	2056-05-17	2056
26605	2056-05-18	2056
26606	2056-05-19	2056
26607	2056-05-22	2056
26608	2056-05-23	2056
26609	2056-05-24	2056
26610	2056-05-25	2056
26611	2056-05-26	2056
26612	2056-05-29	2056
26613	2056-05-30	2056
26614	2056-05-31	2056
26615	2056-06-02	2056
26616	2056-06-05	2056
26617	2056-06-06	2056
26618	2056-06-07	2056
26619	2056-06-08	2056
26620	2056-06-09	2056
26621	2056-06-12	2056
26622	2056-06-13	2056
26623	2056-06-14	2056
26624	2056-06-15	2056
26625	2056-06-16	2056
26626	2056-06-19	2056
26627	2056-06-20	2056
26628	2056-06-21	2056
26629	2056-06-22	2056
26630	2056-06-23	2056
26631	2056-06-26	2056
26632	2056-06-27	2056
26633	2056-06-28	2056
26634	2056-06-29	2056
26635	2056-06-30	2056
26636	2056-07-03	2056
26637	2056-07-04	2056
26638	2056-07-05	2056
26639	2056-07-06	2056
26640	2056-07-07	2056
26641	2056-07-10	2056
26642	2056-07-11	2056
26643	2056-07-12	2056
26644	2056-07-13	2056
26645	2056-07-14	2056
26646	2056-07-17	2056
26647	2056-07-18	2056
26648	2056-07-19	2056
26649	2056-07-20	2056
26650	2056-07-21	2056
26651	2056-07-24	2056
26652	2056-07-25	2056
26653	2056-07-26	2056
26654	2056-07-27	2056
26655	2056-07-28	2056
26656	2056-07-31	2056
26657	2056-08-01	2056
26658	2056-08-02	2056
26659	2056-08-03	2056
26660	2056-08-04	2056
26661	2056-08-07	2056
26662	2056-08-08	2056
26663	2056-08-09	2056
26664	2056-08-10	2056
26665	2056-08-11	2056
26666	2056-08-14	2056
26667	2056-08-15	2056
26668	2056-08-16	2056
26669	2056-08-17	2056
26670	2056-08-18	2056
26671	2056-08-21	2056
26672	2056-08-22	2056
26673	2056-08-23	2056
26674	2056-08-24	2056
26675	2056-08-25	2056
26676	2056-08-28	2056
26677	2056-08-29	2056
26678	2056-08-30	2056
26679	2056-08-31	2056
26680	2056-09-01	2056
26681	2056-09-04	2056
26682	2056-09-05	2056
26683	2056-09-06	2056
26684	2056-09-08	2056
26685	2056-09-11	2056
26686	2056-09-12	2056
26687	2056-09-13	2056
26688	2056-09-14	2056
26689	2056-09-15	2056
26690	2056-09-18	2056
26691	2056-09-19	2056
26692	2056-09-20	2056
26693	2056-09-21	2056
26694	2056-09-22	2056
26695	2056-09-25	2056
26696	2056-09-26	2056
26697	2056-09-27	2056
26698	2056-09-28	2056
26699	2056-09-29	2056
26700	2056-10-02	2056
26701	2056-10-03	2056
26702	2056-10-04	2056
26703	2056-10-05	2056
26704	2056-10-06	2056
26705	2056-10-09	2056
26706	2056-10-10	2056
26707	2056-10-11	2056
26708	2056-10-13	2056
26709	2056-10-16	2056
26710	2056-10-17	2056
26711	2056-10-18	2056
26712	2056-10-19	2056
26713	2056-10-20	2056
26714	2056-10-23	2056
26715	2056-10-24	2056
26716	2056-10-25	2056
26717	2056-10-26	2056
26718	2056-10-27	2056
26719	2056-10-30	2056
26720	2056-10-31	2056
26721	2056-11-01	2056
26722	2056-11-03	2056
26723	2056-11-06	2056
26724	2056-11-07	2056
26725	2056-11-08	2056
26726	2056-11-09	2056
26727	2056-11-10	2056
26728	2056-11-13	2056
26729	2056-11-14	2056
26730	2056-11-16	2056
26731	2056-11-17	2056
26732	2056-11-20	2056
26733	2056-11-21	2056
26734	2056-11-22	2056
26735	2056-11-23	2056
26736	2056-11-24	2056
26737	2056-11-27	2056
26738	2056-11-28	2056
26739	2056-11-29	2056
26740	2056-11-30	2056
26741	2056-12-01	2056
26742	2056-12-04	2056
26743	2056-12-05	2056
26744	2056-12-06	2056
26745	2056-12-07	2056
26746	2056-12-08	2056
26747	2056-12-11	2056
26748	2056-12-12	2056
26749	2056-12-13	2056
26750	2056-12-14	2056
26751	2056-12-15	2056
26752	2056-12-18	2056
26753	2056-12-19	2056
26754	2056-12-20	2056
26755	2056-12-21	2056
26756	2056-12-22	2056
26757	2056-12-26	2056
26758	2056-12-27	2056
26759	2056-12-28	2056
26760	2056-12-29	2056
26761	2057-01-02	2057
26762	2057-01-03	2057
26763	2057-01-04	2057
26764	2057-01-05	2057
26765	2057-01-08	2057
26766	2057-01-09	2057
26767	2057-01-10	2057
26768	2057-01-11	2057
26769	2057-01-12	2057
26770	2057-01-15	2057
26771	2057-01-16	2057
26772	2057-01-17	2057
26773	2057-01-18	2057
26774	2057-01-19	2057
26775	2057-01-22	2057
26776	2057-01-23	2057
26777	2057-01-24	2057
26778	2057-01-25	2057
26779	2057-01-26	2057
26780	2057-01-29	2057
26781	2057-01-30	2057
26782	2057-01-31	2057
26783	2057-02-01	2057
26784	2057-02-02	2057
26785	2057-02-05	2057
26786	2057-02-06	2057
26787	2057-02-07	2057
26788	2057-02-08	2057
26789	2057-02-09	2057
26790	2057-02-12	2057
26791	2057-02-13	2057
26792	2057-02-14	2057
26793	2057-02-15	2057
26794	2057-02-16	2057
26795	2057-02-19	2057
26796	2057-02-20	2057
26797	2057-02-21	2057
26798	2057-02-22	2057
26799	2057-02-23	2057
26800	2057-02-26	2057
26801	2057-02-27	2057
26802	2057-02-28	2057
26803	2057-03-01	2057
26804	2057-03-02	2057
26805	2057-03-05	2057
26806	2057-03-07	2057
26807	2057-03-08	2057
26808	2057-03-09	2057
26809	2057-03-12	2057
26810	2057-03-13	2057
26811	2057-03-14	2057
26812	2057-03-15	2057
26813	2057-03-16	2057
26814	2057-03-19	2057
26815	2057-03-20	2057
26816	2057-03-21	2057
26817	2057-03-22	2057
26818	2057-03-23	2057
26819	2057-03-26	2057
26820	2057-03-27	2057
26821	2057-03-28	2057
26822	2057-03-29	2057
26823	2057-03-30	2057
26824	2057-04-02	2057
26825	2057-04-03	2057
26826	2057-04-04	2057
26827	2057-04-05	2057
26828	2057-04-06	2057
26829	2057-04-09	2057
26830	2057-04-10	2057
26831	2057-04-11	2057
26832	2057-04-12	2057
26833	2057-04-13	2057
26834	2057-04-16	2057
26835	2057-04-17	2057
26836	2057-04-18	2057
26837	2057-04-19	2057
26838	2057-04-20	2057
26839	2057-04-23	2057
26840	2057-04-24	2057
26841	2057-04-25	2057
26842	2057-04-26	2057
26843	2057-04-27	2057
26844	2057-04-30	2057
26845	2057-05-02	2057
26846	2057-05-03	2057
26847	2057-05-04	2057
26848	2057-05-07	2057
26849	2057-05-08	2057
26850	2057-05-09	2057
26851	2057-05-10	2057
26852	2057-05-11	2057
26853	2057-05-14	2057
26854	2057-05-15	2057
26855	2057-05-16	2057
26856	2057-05-17	2057
26857	2057-05-18	2057
26858	2057-05-21	2057
26859	2057-05-22	2057
26860	2057-05-23	2057
26861	2057-05-24	2057
26862	2057-05-25	2057
26863	2057-05-28	2057
26864	2057-05-29	2057
26865	2057-05-30	2057
26866	2057-05-31	2057
26867	2057-06-01	2057
26868	2057-06-04	2057
26869	2057-06-05	2057
26870	2057-06-06	2057
26871	2057-06-07	2057
26872	2057-06-08	2057
26873	2057-06-11	2057
26874	2057-06-12	2057
26875	2057-06-13	2057
26876	2057-06-14	2057
26877	2057-06-15	2057
26878	2057-06-18	2057
26879	2057-06-19	2057
26880	2057-06-20	2057
26881	2057-06-22	2057
26882	2057-06-25	2057
26883	2057-06-26	2057
26884	2057-06-27	2057
26885	2057-06-28	2057
26886	2057-06-29	2057
26887	2057-07-02	2057
26888	2057-07-03	2057
26889	2057-07-04	2057
26890	2057-07-05	2057
26891	2057-07-06	2057
26892	2057-07-09	2057
26893	2057-07-10	2057
26894	2057-07-11	2057
26895	2057-07-12	2057
26896	2057-07-13	2057
26897	2057-07-16	2057
26898	2057-07-17	2057
26899	2057-07-18	2057
26900	2057-07-19	2057
26901	2057-07-20	2057
26902	2057-07-23	2057
26903	2057-07-24	2057
26904	2057-07-25	2057
26905	2057-07-26	2057
26906	2057-07-27	2057
26907	2057-07-30	2057
26908	2057-07-31	2057
26909	2057-08-01	2057
26910	2057-08-02	2057
26911	2057-08-03	2057
26912	2057-08-06	2057
26913	2057-08-07	2057
26914	2057-08-08	2057
26915	2057-08-09	2057
26916	2057-08-10	2057
26917	2057-08-13	2057
26918	2057-08-14	2057
26919	2057-08-15	2057
26920	2057-08-16	2057
26921	2057-08-17	2057
26922	2057-08-20	2057
26923	2057-08-21	2057
26924	2057-08-22	2057
26925	2057-08-23	2057
26926	2057-08-24	2057
26927	2057-08-27	2057
26928	2057-08-28	2057
26929	2057-08-29	2057
26930	2057-08-30	2057
26931	2057-08-31	2057
26932	2057-09-03	2057
26933	2057-09-04	2057
26934	2057-09-05	2057
26935	2057-09-06	2057
26936	2057-09-10	2057
26937	2057-09-11	2057
26938	2057-09-12	2057
26939	2057-09-13	2057
26940	2057-09-14	2057
26941	2057-09-17	2057
26942	2057-09-18	2057
26943	2057-09-19	2057
26944	2057-09-20	2057
26945	2057-09-21	2057
26946	2057-09-24	2057
26947	2057-09-25	2057
26948	2057-09-26	2057
26949	2057-09-27	2057
26950	2057-09-28	2057
26951	2057-10-01	2057
26952	2057-10-02	2057
26953	2057-10-03	2057
26954	2057-10-04	2057
26955	2057-10-05	2057
26956	2057-10-08	2057
26957	2057-10-09	2057
26958	2057-10-10	2057
26959	2057-10-11	2057
26960	2057-10-15	2057
26961	2057-10-16	2057
26962	2057-10-17	2057
26963	2057-10-18	2057
26964	2057-10-19	2057
26965	2057-10-22	2057
26966	2057-10-23	2057
26967	2057-10-24	2057
26968	2057-10-25	2057
26969	2057-10-26	2057
26970	2057-10-29	2057
26971	2057-10-30	2057
26972	2057-10-31	2057
26973	2057-11-01	2057
26974	2057-11-05	2057
26975	2057-11-06	2057
26976	2057-11-07	2057
26977	2057-11-08	2057
26978	2057-11-09	2057
26979	2057-11-12	2057
26980	2057-11-13	2057
26981	2057-11-14	2057
26982	2057-11-16	2057
26983	2057-11-19	2057
26984	2057-11-20	2057
26985	2057-11-21	2057
26986	2057-11-22	2057
26987	2057-11-23	2057
26988	2057-11-26	2057
26989	2057-11-27	2057
26990	2057-11-28	2057
26991	2057-11-29	2057
26992	2057-11-30	2057
26993	2057-12-03	2057
26994	2057-12-04	2057
26995	2057-12-05	2057
26996	2057-12-06	2057
26997	2057-12-07	2057
26998	2057-12-10	2057
26999	2057-12-11	2057
27000	2057-12-12	2057
27001	2057-12-13	2057
27002	2057-12-14	2057
27003	2057-12-17	2057
27004	2057-12-18	2057
27005	2057-12-19	2057
27006	2057-12-20	2057
27007	2057-12-21	2057
27008	2057-12-24	2057
27009	2057-12-26	2057
27010	2057-12-27	2057
27011	2057-12-28	2057
27012	2057-12-31	2057
27013	2058-01-02	2058
27014	2058-01-03	2058
27015	2058-01-04	2058
27016	2058-01-07	2058
27017	2058-01-08	2058
27018	2058-01-09	2058
27019	2058-01-10	2058
27020	2058-01-11	2058
27021	2058-01-14	2058
27022	2058-01-15	2058
27023	2058-01-16	2058
27024	2058-01-17	2058
27025	2058-01-18	2058
27026	2058-01-21	2058
27027	2058-01-22	2058
27028	2058-01-23	2058
27029	2058-01-24	2058
27030	2058-01-25	2058
27031	2058-01-28	2058
27032	2058-01-29	2058
27033	2058-01-30	2058
27034	2058-01-31	2058
27035	2058-02-01	2058
27036	2058-02-04	2058
27037	2058-02-05	2058
27038	2058-02-06	2058
27039	2058-02-07	2058
27040	2058-02-08	2058
27041	2058-02-11	2058
27042	2058-02-12	2058
27043	2058-02-13	2058
27044	2058-02-14	2058
27045	2058-02-15	2058
27046	2058-02-18	2058
27047	2058-02-19	2058
27048	2058-02-20	2058
27049	2058-02-21	2058
27050	2058-02-22	2058
27051	2058-02-25	2058
27052	2058-02-27	2058
27053	2058-02-28	2058
27054	2058-03-01	2058
27055	2058-03-04	2058
27056	2058-03-05	2058
27057	2058-03-06	2058
27058	2058-03-07	2058
27059	2058-03-08	2058
27060	2058-03-11	2058
27061	2058-03-12	2058
27062	2058-03-13	2058
27063	2058-03-14	2058
27064	2058-03-15	2058
27065	2058-03-18	2058
27066	2058-03-19	2058
27067	2058-03-20	2058
27068	2058-03-21	2058
27069	2058-03-22	2058
27070	2058-03-25	2058
27071	2058-03-26	2058
27072	2058-03-27	2058
27073	2058-03-28	2058
27074	2058-03-29	2058
27075	2058-04-01	2058
27076	2058-04-02	2058
27077	2058-04-03	2058
27078	2058-04-04	2058
27079	2058-04-05	2058
27080	2058-04-08	2058
27081	2058-04-09	2058
27082	2058-04-10	2058
27083	2058-04-11	2058
27084	2058-04-12	2058
27085	2058-04-15	2058
27086	2058-04-16	2058
27087	2058-04-17	2058
27088	2058-04-18	2058
27089	2058-04-19	2058
27090	2058-04-22	2058
27091	2058-04-23	2058
27092	2058-04-24	2058
27093	2058-04-25	2058
27094	2058-04-26	2058
27095	2058-04-29	2058
27096	2058-04-30	2058
27097	2058-05-02	2058
27098	2058-05-03	2058
27099	2058-05-06	2058
27100	2058-05-07	2058
27101	2058-05-08	2058
27102	2058-05-09	2058
27103	2058-05-10	2058
27104	2058-05-13	2058
27105	2058-05-14	2058
27106	2058-05-15	2058
27107	2058-05-16	2058
27108	2058-05-17	2058
27109	2058-05-20	2058
27110	2058-05-21	2058
27111	2058-05-22	2058
27112	2058-05-23	2058
27113	2058-05-24	2058
27114	2058-05-27	2058
27115	2058-05-28	2058
27116	2058-05-29	2058
27117	2058-05-30	2058
27118	2058-05-31	2058
27119	2058-06-03	2058
27120	2058-06-04	2058
27121	2058-06-05	2058
27122	2058-06-06	2058
27123	2058-06-07	2058
27124	2058-06-10	2058
27125	2058-06-11	2058
27126	2058-06-12	2058
27127	2058-06-14	2058
27128	2058-06-17	2058
27129	2058-06-18	2058
27130	2058-06-19	2058
27131	2058-06-20	2058
27132	2058-06-21	2058
27133	2058-06-24	2058
27134	2058-06-25	2058
27135	2058-06-26	2058
27136	2058-06-27	2058
27137	2058-06-28	2058
27138	2058-07-01	2058
27139	2058-07-02	2058
27140	2058-07-03	2058
27141	2058-07-04	2058
27142	2058-07-05	2058
27143	2058-07-08	2058
27144	2058-07-09	2058
27145	2058-07-10	2058
27146	2058-07-11	2058
27147	2058-07-12	2058
27148	2058-07-15	2058
27149	2058-07-16	2058
27150	2058-07-17	2058
27151	2058-07-18	2058
27152	2058-07-19	2058
27153	2058-07-22	2058
27154	2058-07-23	2058
27155	2058-07-24	2058
27156	2058-07-25	2058
27157	2058-07-26	2058
27158	2058-07-29	2058
27159	2058-07-30	2058
27160	2058-07-31	2058
27161	2058-08-01	2058
27162	2058-08-02	2058
27163	2058-08-05	2058
27164	2058-08-06	2058
27165	2058-08-07	2058
27166	2058-08-08	2058
27167	2058-08-09	2058
27168	2058-08-12	2058
27169	2058-08-13	2058
27170	2058-08-14	2058
27171	2058-08-15	2058
27172	2058-08-16	2058
27173	2058-08-19	2058
27174	2058-08-20	2058
27175	2058-08-21	2058
27176	2058-08-22	2058
27177	2058-08-23	2058
27178	2058-08-26	2058
27179	2058-08-27	2058
27180	2058-08-28	2058
27181	2058-08-29	2058
27182	2058-08-30	2058
27183	2058-09-02	2058
27184	2058-09-03	2058
27185	2058-09-04	2058
27186	2058-09-05	2058
27187	2058-09-06	2058
27188	2058-09-09	2058
27189	2058-09-10	2058
27190	2058-09-11	2058
27191	2058-09-12	2058
27192	2058-09-13	2058
27193	2058-09-16	2058
27194	2058-09-17	2058
27195	2058-09-18	2058
27196	2058-09-19	2058
27197	2058-09-20	2058
27198	2058-09-23	2058
27199	2058-09-24	2058
27200	2058-09-25	2058
27201	2058-09-26	2058
27202	2058-09-27	2058
27203	2058-09-30	2058
27204	2058-10-01	2058
27205	2058-10-02	2058
27206	2058-10-03	2058
27207	2058-10-04	2058
27208	2058-10-07	2058
27209	2058-10-08	2058
27210	2058-10-09	2058
27211	2058-10-10	2058
27212	2058-10-11	2058
27213	2058-10-14	2058
27214	2058-10-15	2058
27215	2058-10-16	2058
27216	2058-10-17	2058
27217	2058-10-18	2058
27218	2058-10-21	2058
27219	2058-10-22	2058
27220	2058-10-23	2058
27221	2058-10-24	2058
27222	2058-10-25	2058
27223	2058-10-29	2058
27224	2058-10-30	2058
27225	2058-10-31	2058
27226	2058-11-01	2058
27227	2058-11-04	2058
27228	2058-11-05	2058
27229	2058-11-06	2058
27230	2058-11-07	2058
27231	2058-11-08	2058
27232	2058-11-11	2058
27233	2058-11-12	2058
27234	2058-11-13	2058
27235	2058-11-14	2058
27236	2058-11-18	2058
27237	2058-11-19	2058
27238	2058-11-20	2058
27239	2058-11-21	2058
27240	2058-11-22	2058
27241	2058-11-25	2058
27242	2058-11-26	2058
27243	2058-11-27	2058
27244	2058-11-28	2058
27245	2058-11-29	2058
27246	2058-12-02	2058
27247	2058-12-03	2058
27248	2058-12-04	2058
27249	2058-12-05	2058
27250	2058-12-06	2058
27251	2058-12-09	2058
27252	2058-12-10	2058
27253	2058-12-11	2058
27254	2058-12-12	2058
27255	2058-12-13	2058
27256	2058-12-16	2058
27257	2058-12-17	2058
27258	2058-12-18	2058
27259	2058-12-19	2058
27260	2058-12-20	2058
27261	2058-12-23	2058
27262	2058-12-24	2058
27263	2058-12-26	2058
27264	2058-12-27	2058
27265	2058-12-30	2058
27266	2058-12-31	2058
27267	2059-01-02	2059
27268	2059-01-03	2059
27269	2059-01-06	2059
27270	2059-01-07	2059
27271	2059-01-08	2059
27272	2059-01-09	2059
27273	2059-01-10	2059
27274	2059-01-13	2059
27275	2059-01-14	2059
27276	2059-01-15	2059
27277	2059-01-16	2059
27278	2059-01-17	2059
27279	2059-01-20	2059
27280	2059-01-21	2059
27281	2059-01-22	2059
27282	2059-01-23	2059
27283	2059-01-24	2059
27284	2059-01-27	2059
27285	2059-01-28	2059
27286	2059-01-29	2059
27287	2059-01-30	2059
27288	2059-01-31	2059
27289	2059-02-03	2059
27290	2059-02-04	2059
27291	2059-02-05	2059
27292	2059-02-06	2059
27293	2059-02-07	2059
27294	2059-02-10	2059
27295	2059-02-12	2059
27296	2059-02-13	2059
27297	2059-02-14	2059
27298	2059-02-17	2059
27299	2059-02-18	2059
27300	2059-02-19	2059
27301	2059-02-20	2059
27302	2059-02-21	2059
27303	2059-02-24	2059
27304	2059-02-25	2059
27305	2059-02-26	2059
27306	2059-02-27	2059
27307	2059-02-28	2059
27308	2059-03-03	2059
27309	2059-03-04	2059
27310	2059-03-05	2059
27311	2059-03-06	2059
27312	2059-03-07	2059
27313	2059-03-10	2059
27314	2059-03-11	2059
27315	2059-03-12	2059
27316	2059-03-13	2059
27317	2059-03-14	2059
27318	2059-03-17	2059
27319	2059-03-18	2059
27320	2059-03-19	2059
27321	2059-03-20	2059
27322	2059-03-21	2059
27323	2059-03-24	2059
27324	2059-03-25	2059
27325	2059-03-26	2059
27326	2059-03-27	2059
27327	2059-03-28	2059
27328	2059-03-31	2059
27329	2059-04-01	2059
27330	2059-04-02	2059
27331	2059-04-03	2059
27332	2059-04-04	2059
27333	2059-04-07	2059
27334	2059-04-08	2059
27335	2059-04-09	2059
27336	2059-04-10	2059
27337	2059-04-11	2059
27338	2059-04-14	2059
27339	2059-04-15	2059
27340	2059-04-16	2059
27341	2059-04-17	2059
27342	2059-04-18	2059
27343	2059-04-22	2059
27344	2059-04-23	2059
27345	2059-04-24	2059
27346	2059-04-25	2059
27347	2059-04-28	2059
27348	2059-04-29	2059
27349	2059-04-30	2059
27350	2059-05-02	2059
27351	2059-05-05	2059
27352	2059-05-06	2059
27353	2059-05-07	2059
27354	2059-05-08	2059
27355	2059-05-09	2059
27356	2059-05-12	2059
27357	2059-05-13	2059
27358	2059-05-14	2059
27359	2059-05-15	2059
27360	2059-05-16	2059
27361	2059-05-19	2059
27362	2059-05-20	2059
27363	2059-05-21	2059
27364	2059-05-22	2059
27365	2059-05-23	2059
27366	2059-05-26	2059
27367	2059-05-27	2059
27368	2059-05-28	2059
27369	2059-05-30	2059
27370	2059-06-02	2059
27371	2059-06-03	2059
27372	2059-06-04	2059
27373	2059-06-05	2059
27374	2059-06-06	2059
27375	2059-06-09	2059
27376	2059-06-10	2059
27377	2059-06-11	2059
27378	2059-06-12	2059
27379	2059-06-13	2059
27380	2059-06-16	2059
27381	2059-06-17	2059
27382	2059-06-18	2059
27383	2059-06-19	2059
27384	2059-06-20	2059
27385	2059-06-23	2059
27386	2059-06-24	2059
27387	2059-06-25	2059
27388	2059-06-26	2059
27389	2059-06-27	2059
27390	2059-06-30	2059
27391	2059-07-01	2059
27392	2059-07-02	2059
27393	2059-07-03	2059
27394	2059-07-04	2059
27395	2059-07-07	2059
27396	2059-07-08	2059
27397	2059-07-09	2059
27398	2059-07-10	2059
27399	2059-07-11	2059
27400	2059-07-14	2059
27401	2059-07-15	2059
27402	2059-07-16	2059
27403	2059-07-17	2059
27404	2059-07-18	2059
27405	2059-07-21	2059
27406	2059-07-22	2059
27407	2059-07-23	2059
27408	2059-07-24	2059
27409	2059-07-25	2059
27410	2059-07-28	2059
27411	2059-07-29	2059
27412	2059-07-30	2059
27413	2059-07-31	2059
27414	2059-08-01	2059
27415	2059-08-04	2059
27416	2059-08-05	2059
27417	2059-08-06	2059
27418	2059-08-07	2059
27419	2059-08-08	2059
27420	2059-08-11	2059
27421	2059-08-12	2059
27422	2059-08-13	2059
27423	2059-08-14	2059
27424	2059-08-15	2059
27425	2059-08-18	2059
27426	2059-08-19	2059
27427	2059-08-20	2059
27428	2059-08-21	2059
27429	2059-08-22	2059
27430	2059-08-25	2059
27431	2059-08-26	2059
27432	2059-08-27	2059
27433	2059-08-28	2059
27434	2059-08-29	2059
27435	2059-09-01	2059
27436	2059-09-02	2059
27437	2059-09-03	2059
27438	2059-09-04	2059
27439	2059-09-05	2059
27440	2059-09-08	2059
27441	2059-09-09	2059
27442	2059-09-10	2059
27443	2059-09-11	2059
27444	2059-09-12	2059
27445	2059-09-15	2059
27446	2059-09-16	2059
27447	2059-09-17	2059
27448	2059-09-18	2059
27449	2059-09-19	2059
27450	2059-09-22	2059
27451	2059-09-23	2059
27452	2059-09-24	2059
27453	2059-09-25	2059
27454	2059-09-26	2059
27455	2059-09-29	2059
27456	2059-09-30	2059
27457	2059-10-01	2059
27458	2059-10-02	2059
27459	2059-10-03	2059
27460	2059-10-06	2059
27461	2059-10-07	2059
27462	2059-10-08	2059
27463	2059-10-09	2059
27464	2059-10-10	2059
27465	2059-10-13	2059
27466	2059-10-14	2059
27467	2059-10-15	2059
27468	2059-10-16	2059
27469	2059-10-17	2059
27470	2059-10-20	2059
27471	2059-10-21	2059
27472	2059-10-22	2059
27473	2059-10-23	2059
27474	2059-10-24	2059
27475	2059-10-27	2059
27476	2059-10-29	2059
27477	2059-10-30	2059
27478	2059-10-31	2059
27479	2059-11-03	2059
27480	2059-11-04	2059
27481	2059-11-05	2059
27482	2059-11-06	2059
27483	2059-11-07	2059
27484	2059-11-10	2059
27485	2059-11-11	2059
27486	2059-11-12	2059
27487	2059-11-13	2059
27488	2059-11-14	2059
27489	2059-11-17	2059
27490	2059-11-18	2059
27491	2059-11-19	2059
27492	2059-11-20	2059
27493	2059-11-21	2059
27494	2059-11-24	2059
27495	2059-11-25	2059
27496	2059-11-26	2059
27497	2059-11-27	2059
27498	2059-11-28	2059
27499	2059-12-01	2059
27500	2059-12-02	2059
27501	2059-12-03	2059
27502	2059-12-04	2059
27503	2059-12-05	2059
27504	2059-12-08	2059
27505	2059-12-09	2059
27506	2059-12-10	2059
27507	2059-12-11	2059
27508	2059-12-12	2059
27509	2059-12-15	2059
27510	2059-12-16	2059
27511	2059-12-17	2059
27512	2059-12-18	2059
27513	2059-12-19	2059
27514	2059-12-22	2059
27515	2059-12-23	2059
27516	2059-12-24	2059
27517	2059-12-26	2059
27518	2059-12-29	2059
27519	2059-12-30	2059
27520	2059-12-31	2059
27521	2060-01-02	2060
27522	2060-01-05	2060
27523	2060-01-06	2060
27524	2060-01-07	2060
27525	2060-01-08	2060
27526	2060-01-09	2060
27527	2060-01-12	2060
27528	2060-01-13	2060
27529	2060-01-14	2060
27530	2060-01-15	2060
27531	2060-01-16	2060
27532	2060-01-19	2060
27533	2060-01-20	2060
27534	2060-01-21	2060
27535	2060-01-22	2060
27536	2060-01-23	2060
27537	2060-01-26	2060
27538	2060-01-27	2060
27539	2060-01-28	2060
27540	2060-01-29	2060
27541	2060-01-30	2060
27542	2060-02-02	2060
27543	2060-02-03	2060
27544	2060-02-04	2060
27545	2060-02-05	2060
27546	2060-02-06	2060
27547	2060-02-09	2060
27548	2060-02-10	2060
27549	2060-02-11	2060
27550	2060-02-12	2060
27551	2060-02-13	2060
27552	2060-02-16	2060
27553	2060-02-17	2060
27554	2060-02-18	2060
27555	2060-02-19	2060
27556	2060-02-20	2060
27557	2060-02-23	2060
27558	2060-02-24	2060
27559	2060-02-25	2060
27560	2060-02-26	2060
27561	2060-02-27	2060
27562	2060-03-01	2060
27563	2060-03-03	2060
27564	2060-03-04	2060
27565	2060-03-05	2060
27566	2060-03-08	2060
27567	2060-03-09	2060
27568	2060-03-10	2060
27569	2060-03-11	2060
27570	2060-03-12	2060
27571	2060-03-15	2060
27572	2060-03-16	2060
27573	2060-03-17	2060
27574	2060-03-18	2060
27575	2060-03-19	2060
27576	2060-03-22	2060
27577	2060-03-23	2060
27578	2060-03-24	2060
27579	2060-03-25	2060
27580	2060-03-26	2060
27581	2060-03-29	2060
27582	2060-03-30	2060
27583	2060-03-31	2060
27584	2060-04-01	2060
27585	2060-04-02	2060
27586	2060-04-05	2060
27587	2060-04-06	2060
27588	2060-04-07	2060
27589	2060-04-08	2060
27590	2060-04-09	2060
27591	2060-04-12	2060
27592	2060-04-13	2060
27593	2060-04-14	2060
27594	2060-04-15	2060
27595	2060-04-16	2060
27596	2060-04-19	2060
27597	2060-04-20	2060
27598	2060-04-22	2060
27599	2060-04-23	2060
27600	2060-04-26	2060
27601	2060-04-27	2060
27602	2060-04-28	2060
27603	2060-04-29	2060
27604	2060-04-30	2060
27605	2060-05-03	2060
27606	2060-05-04	2060
27607	2060-05-05	2060
27608	2060-05-06	2060
27609	2060-05-07	2060
27610	2060-05-10	2060
27611	2060-05-11	2060
27612	2060-05-12	2060
27613	2060-05-13	2060
27614	2060-05-14	2060
27615	2060-05-17	2060
27616	2060-05-18	2060
27617	2060-05-19	2060
27618	2060-05-20	2060
27619	2060-05-21	2060
27620	2060-05-24	2060
27621	2060-05-25	2060
27622	2060-05-26	2060
27623	2060-05-27	2060
27624	2060-05-28	2060
27625	2060-05-31	2060
27626	2060-06-01	2060
27627	2060-06-02	2060
27628	2060-06-03	2060
27629	2060-06-04	2060
27630	2060-06-07	2060
27631	2060-06-08	2060
27632	2060-06-09	2060
27633	2060-06-10	2060
27634	2060-06-11	2060
27635	2060-06-14	2060
27636	2060-06-15	2060
27637	2060-06-16	2060
27638	2060-06-18	2060
27639	2060-06-21	2060
27640	2060-06-22	2060
27641	2060-06-23	2060
27642	2060-06-24	2060
27643	2060-06-25	2060
27644	2060-06-28	2060
27645	2060-06-29	2060
27646	2060-06-30	2060
27647	2060-07-01	2060
27648	2060-07-02	2060
27649	2060-07-05	2060
27650	2060-07-06	2060
27651	2060-07-07	2060
27652	2060-07-08	2060
27653	2060-07-09	2060
27654	2060-07-12	2060
27655	2060-07-13	2060
27656	2060-07-14	2060
27657	2060-07-15	2060
27658	2060-07-16	2060
27659	2060-07-19	2060
27660	2060-07-20	2060
27661	2060-07-21	2060
27662	2060-07-22	2060
27663	2060-07-23	2060
27664	2060-07-26	2060
27665	2060-07-27	2060
27666	2060-07-28	2060
27667	2060-07-29	2060
27668	2060-07-30	2060
27669	2060-08-02	2060
27670	2060-08-03	2060
27671	2060-08-04	2060
27672	2060-08-05	2060
27673	2060-08-06	2060
27674	2060-08-09	2060
27675	2060-08-10	2060
27676	2060-08-11	2060
27677	2060-08-12	2060
27678	2060-08-13	2060
27679	2060-08-16	2060
27680	2060-08-17	2060
27681	2060-08-18	2060
27682	2060-08-19	2060
27683	2060-08-20	2060
27684	2060-08-23	2060
27685	2060-08-24	2060
27686	2060-08-25	2060
27687	2060-08-26	2060
27688	2060-08-27	2060
27689	2060-08-30	2060
27690	2060-08-31	2060
27691	2060-09-01	2060
27692	2060-09-02	2060
27693	2060-09-03	2060
27694	2060-09-06	2060
27695	2060-09-08	2060
27696	2060-09-09	2060
27697	2060-09-10	2060
27698	2060-09-13	2060
27699	2060-09-14	2060
27700	2060-09-15	2060
27701	2060-09-16	2060
27702	2060-09-17	2060
27703	2060-09-20	2060
27704	2060-09-21	2060
27705	2060-09-22	2060
27706	2060-09-23	2060
27707	2060-09-24	2060
27708	2060-09-27	2060
27709	2060-09-28	2060
27710	2060-09-29	2060
27711	2060-09-30	2060
27712	2060-10-01	2060
27713	2060-10-04	2060
27714	2060-10-05	2060
27715	2060-10-06	2060
27716	2060-10-07	2060
27717	2060-10-08	2060
27718	2060-10-11	2060
27719	2060-10-13	2060
27720	2060-10-14	2060
27721	2060-10-15	2060
27722	2060-10-18	2060
27723	2060-10-19	2060
27724	2060-10-20	2060
27725	2060-10-21	2060
27726	2060-10-22	2060
27727	2060-10-25	2060
27728	2060-10-26	2060
27729	2060-10-27	2060
27730	2060-10-29	2060
27731	2060-11-01	2060
27732	2060-11-03	2060
27733	2060-11-04	2060
27734	2060-11-05	2060
27735	2060-11-08	2060
27736	2060-11-09	2060
27737	2060-11-10	2060
27738	2060-11-11	2060
27739	2060-11-12	2060
27740	2060-11-16	2060
27741	2060-11-17	2060
27742	2060-11-18	2060
27743	2060-11-19	2060
27744	2060-11-22	2060
27745	2060-11-23	2060
27746	2060-11-24	2060
27747	2060-11-25	2060
27748	2060-11-26	2060
27749	2060-11-29	2060
27750	2060-11-30	2060
27751	2060-12-01	2060
27752	2060-12-02	2060
27753	2060-12-03	2060
27754	2060-12-06	2060
27755	2060-12-07	2060
27756	2060-12-08	2060
27757	2060-12-09	2060
27758	2060-12-10	2060
27759	2060-12-13	2060
27760	2060-12-14	2060
27761	2060-12-15	2060
27762	2060-12-16	2060
27763	2060-12-17	2060
27764	2060-12-20	2060
27765	2060-12-21	2060
27766	2060-12-22	2060
27767	2060-12-23	2060
27768	2060-12-24	2060
27769	2060-12-27	2060
27770	2060-12-28	2060
27771	2060-12-29	2060
27772	2060-12-30	2060
27773	2060-12-31	2060
27774	2061-01-03	2061
27775	2061-01-04	2061
27776	2061-01-05	2061
27777	2061-01-06	2061
27778	2061-01-07	2061
27779	2061-01-10	2061
27780	2061-01-11	2061
27781	2061-01-12	2061
27782	2061-01-13	2061
27783	2061-01-14	2061
27784	2061-01-17	2061
27785	2061-01-18	2061
27786	2061-01-19	2061
27787	2061-01-20	2061
27788	2061-01-21	2061
27789	2061-01-24	2061
27790	2061-01-25	2061
27791	2061-01-26	2061
27792	2061-01-27	2061
27793	2061-01-28	2061
27794	2061-01-31	2061
27795	2061-02-01	2061
27796	2061-02-02	2061
27797	2061-02-03	2061
27798	2061-02-04	2061
27799	2061-02-07	2061
27800	2061-02-08	2061
27801	2061-02-09	2061
27802	2061-02-10	2061
27803	2061-02-11	2061
27804	2061-02-14	2061
27805	2061-02-15	2061
27806	2061-02-16	2061
27807	2061-02-17	2061
27808	2061-02-18	2061
27809	2061-02-21	2061
27810	2061-02-23	2061
27811	2061-02-24	2061
27812	2061-02-25	2061
27813	2061-02-28	2061
27814	2061-03-01	2061
27815	2061-03-02	2061
27816	2061-03-03	2061
27817	2061-03-04	2061
27818	2061-03-07	2061
27819	2061-03-08	2061
27820	2061-03-09	2061
27821	2061-03-10	2061
27822	2061-03-11	2061
27823	2061-03-14	2061
27824	2061-03-15	2061
27825	2061-03-16	2061
27826	2061-03-17	2061
27827	2061-03-18	2061
27828	2061-03-21	2061
27829	2061-03-22	2061
27830	2061-03-23	2061
27831	2061-03-24	2061
27832	2061-03-25	2061
27833	2061-03-28	2061
27834	2061-03-29	2061
27835	2061-03-30	2061
27836	2061-03-31	2061
27837	2061-04-01	2061
27838	2061-04-04	2061
27839	2061-04-05	2061
27840	2061-04-06	2061
27841	2061-04-07	2061
27842	2061-04-08	2061
27843	2061-04-11	2061
27844	2061-04-12	2061
27845	2061-04-13	2061
27846	2061-04-14	2061
27847	2061-04-15	2061
27848	2061-04-18	2061
27849	2061-04-19	2061
27850	2061-04-20	2061
27851	2061-04-22	2061
27852	2061-04-25	2061
27853	2061-04-26	2061
27854	2061-04-27	2061
27855	2061-04-28	2061
27856	2061-04-29	2061
27857	2061-05-02	2061
27858	2061-05-03	2061
27859	2061-05-04	2061
27860	2061-05-05	2061
27861	2061-05-06	2061
27862	2061-05-09	2061
27863	2061-05-10	2061
27864	2061-05-11	2061
27865	2061-05-12	2061
27866	2061-05-13	2061
27867	2061-05-16	2061
27868	2061-05-17	2061
27869	2061-05-18	2061
27870	2061-05-19	2061
27871	2061-05-20	2061
27872	2061-05-23	2061
27873	2061-05-24	2061
27874	2061-05-25	2061
27875	2061-05-26	2061
27876	2061-05-27	2061
27877	2061-05-30	2061
27878	2061-05-31	2061
27879	2061-06-01	2061
27880	2061-06-02	2061
27881	2061-06-03	2061
27882	2061-06-06	2061
27883	2061-06-07	2061
27884	2061-06-08	2061
27885	2061-06-10	2061
27886	2061-06-13	2061
27887	2061-06-14	2061
27888	2061-06-15	2061
27889	2061-06-16	2061
27890	2061-06-17	2061
27891	2061-06-20	2061
27892	2061-06-21	2061
27893	2061-06-22	2061
27894	2061-06-23	2061
27895	2061-06-24	2061
27896	2061-06-27	2061
27897	2061-06-28	2061
27898	2061-06-29	2061
27899	2061-06-30	2061
27900	2061-07-01	2061
27901	2061-07-04	2061
27902	2061-07-05	2061
27903	2061-07-06	2061
27904	2061-07-07	2061
27905	2061-07-08	2061
27906	2061-07-11	2061
27907	2061-07-12	2061
27908	2061-07-13	2061
27909	2061-07-14	2061
27910	2061-07-15	2061
27911	2061-07-18	2061
27912	2061-07-19	2061
27913	2061-07-20	2061
27914	2061-07-21	2061
27915	2061-07-22	2061
27916	2061-07-25	2061
27917	2061-07-26	2061
27918	2061-07-27	2061
27919	2061-07-28	2061
27920	2061-07-29	2061
27921	2061-08-01	2061
27922	2061-08-02	2061
27923	2061-08-03	2061
27924	2061-08-04	2061
27925	2061-08-05	2061
27926	2061-08-08	2061
27927	2061-08-09	2061
27928	2061-08-10	2061
27929	2061-08-11	2061
27930	2061-08-12	2061
27931	2061-08-15	2061
27932	2061-08-16	2061
27933	2061-08-17	2061
27934	2061-08-18	2061
27935	2061-08-19	2061
27936	2061-08-22	2061
27937	2061-08-23	2061
27938	2061-08-24	2061
27939	2061-08-25	2061
27940	2061-08-26	2061
27941	2061-08-29	2061
27942	2061-08-30	2061
27943	2061-08-31	2061
27944	2061-09-01	2061
27945	2061-09-02	2061
27946	2061-09-05	2061
27947	2061-09-06	2061
27948	2061-09-08	2061
27949	2061-09-09	2061
27950	2061-09-12	2061
27951	2061-09-13	2061
27952	2061-09-14	2061
27953	2061-09-15	2061
27954	2061-09-16	2061
27955	2061-09-19	2061
27956	2061-09-20	2061
27957	2061-09-21	2061
27958	2061-09-22	2061
27959	2061-09-23	2061
27960	2061-09-26	2061
27961	2061-09-27	2061
27962	2061-09-28	2061
27963	2061-09-29	2061
27964	2061-09-30	2061
27965	2061-10-03	2061
27966	2061-10-04	2061
27967	2061-10-05	2061
27968	2061-10-06	2061
27969	2061-10-07	2061
27970	2061-10-10	2061
27971	2061-10-11	2061
27972	2061-10-13	2061
27973	2061-10-14	2061
27974	2061-10-17	2061
27975	2061-10-18	2061
27976	2061-10-19	2061
27977	2061-10-20	2061
27978	2061-10-21	2061
27979	2061-10-24	2061
27980	2061-10-25	2061
27981	2061-10-26	2061
27982	2061-10-27	2061
27983	2061-10-31	2061
27984	2061-11-01	2061
27985	2061-11-03	2061
27986	2061-11-04	2061
27987	2061-11-07	2061
27988	2061-11-08	2061
27989	2061-11-09	2061
27990	2061-11-10	2061
27991	2061-11-11	2061
27992	2061-11-14	2061
27993	2061-11-16	2061
27994	2061-11-17	2061
27995	2061-11-18	2061
27996	2061-11-21	2061
27997	2061-11-22	2061
27998	2061-11-23	2061
27999	2061-11-24	2061
28000	2061-11-25	2061
28001	2061-11-28	2061
28002	2061-11-29	2061
28003	2061-11-30	2061
28004	2061-12-01	2061
28005	2061-12-02	2061
28006	2061-12-05	2061
28007	2061-12-06	2061
28008	2061-12-07	2061
28009	2061-12-08	2061
28010	2061-12-09	2061
28011	2061-12-12	2061
28012	2061-12-13	2061
28013	2061-12-14	2061
28014	2061-12-15	2061
28015	2061-12-16	2061
28016	2061-12-19	2061
28017	2061-12-20	2061
28018	2061-12-21	2061
28019	2061-12-22	2061
28020	2061-12-23	2061
28021	2061-12-26	2061
28022	2061-12-27	2061
28023	2061-12-28	2061
28024	2061-12-29	2061
28025	2061-12-30	2061
28026	2062-01-02	2062
28027	2062-01-03	2062
28028	2062-01-04	2062
28029	2062-01-05	2062
28030	2062-01-06	2062
28031	2062-01-09	2062
28032	2062-01-10	2062
28033	2062-01-11	2062
28034	2062-01-12	2062
28035	2062-01-13	2062
28036	2062-01-16	2062
28037	2062-01-17	2062
28038	2062-01-18	2062
28039	2062-01-19	2062
28040	2062-01-20	2062
28041	2062-01-23	2062
28042	2062-01-24	2062
28043	2062-01-25	2062
28044	2062-01-26	2062
28045	2062-01-27	2062
28046	2062-01-30	2062
28047	2062-01-31	2062
28048	2062-02-01	2062
28049	2062-02-02	2062
28050	2062-02-03	2062
28051	2062-02-06	2062
28052	2062-02-08	2062
28053	2062-02-09	2062
28054	2062-02-10	2062
28055	2062-02-13	2062
28056	2062-02-14	2062
28057	2062-02-15	2062
28058	2062-02-16	2062
28059	2062-02-17	2062
28060	2062-02-20	2062
28061	2062-02-21	2062
28062	2062-02-22	2062
28063	2062-02-23	2062
28064	2062-02-24	2062
28065	2062-02-27	2062
28066	2062-02-28	2062
28067	2062-03-01	2062
28068	2062-03-02	2062
28069	2062-03-03	2062
28070	2062-03-06	2062
28071	2062-03-07	2062
28072	2062-03-08	2062
28073	2062-03-09	2062
28074	2062-03-10	2062
28075	2062-03-13	2062
28076	2062-03-14	2062
28077	2062-03-15	2062
28078	2062-03-16	2062
28079	2062-03-17	2062
28080	2062-03-20	2062
28081	2062-03-21	2062
28082	2062-03-22	2062
28083	2062-03-23	2062
28084	2062-03-24	2062
28085	2062-03-27	2062
28086	2062-03-28	2062
28087	2062-03-29	2062
28088	2062-03-30	2062
28089	2062-03-31	2062
28090	2062-04-03	2062
28091	2062-04-04	2062
28092	2062-04-05	2062
28093	2062-04-06	2062
28094	2062-04-07	2062
28095	2062-04-10	2062
28096	2062-04-11	2062
28097	2062-04-12	2062
28098	2062-04-13	2062
28099	2062-04-14	2062
28100	2062-04-17	2062
28101	2062-04-18	2062
28102	2062-04-19	2062
28103	2062-04-20	2062
28104	2062-04-24	2062
28105	2062-04-25	2062
28106	2062-04-26	2062
28107	2062-04-27	2062
28108	2062-04-28	2062
28109	2062-05-02	2062
28110	2062-05-03	2062
28111	2062-05-04	2062
28112	2062-05-05	2062
28113	2062-05-08	2062
28114	2062-05-09	2062
28115	2062-05-10	2062
28116	2062-05-11	2062
28117	2062-05-12	2062
28118	2062-05-15	2062
28119	2062-05-16	2062
28120	2062-05-17	2062
28121	2062-05-18	2062
28122	2062-05-19	2062
28123	2062-05-22	2062
28124	2062-05-23	2062
28125	2062-05-24	2062
28126	2062-05-26	2062
28127	2062-05-29	2062
28128	2062-05-30	2062
28129	2062-05-31	2062
28130	2062-06-01	2062
28131	2062-06-02	2062
28132	2062-06-05	2062
28133	2062-06-06	2062
28134	2062-06-07	2062
28135	2062-06-08	2062
28136	2062-06-09	2062
28137	2062-06-12	2062
28138	2062-06-13	2062
28139	2062-06-14	2062
28140	2062-06-15	2062
28141	2062-06-16	2062
28142	2062-06-19	2062
28143	2062-06-20	2062
28144	2062-06-21	2062
28145	2062-06-22	2062
28146	2062-06-23	2062
28147	2062-06-26	2062
28148	2062-06-27	2062
28149	2062-06-28	2062
28150	2062-06-29	2062
28151	2062-06-30	2062
28152	2062-07-03	2062
28153	2062-07-04	2062
28154	2062-07-05	2062
28155	2062-07-06	2062
28156	2062-07-07	2062
28157	2062-07-10	2062
28158	2062-07-11	2062
28159	2062-07-12	2062
28160	2062-07-13	2062
28161	2062-07-14	2062
28162	2062-07-17	2062
28163	2062-07-18	2062
28164	2062-07-19	2062
28165	2062-07-20	2062
28166	2062-07-21	2062
28167	2062-07-24	2062
28168	2062-07-25	2062
28169	2062-07-26	2062
28170	2062-07-27	2062
28171	2062-07-28	2062
28172	2062-07-31	2062
28173	2062-08-01	2062
28174	2062-08-02	2062
28175	2062-08-03	2062
28176	2062-08-04	2062
28177	2062-08-07	2062
28178	2062-08-08	2062
28179	2062-08-09	2062
28180	2062-08-10	2062
28181	2062-08-11	2062
28182	2062-08-14	2062
28183	2062-08-15	2062
28184	2062-08-16	2062
28185	2062-08-17	2062
28186	2062-08-18	2062
28187	2062-08-21	2062
28188	2062-08-22	2062
28189	2062-08-23	2062
28190	2062-08-24	2062
28191	2062-08-25	2062
28192	2062-08-28	2062
28193	2062-08-29	2062
28194	2062-08-30	2062
28195	2062-08-31	2062
28196	2062-09-01	2062
28197	2062-09-04	2062
28198	2062-09-05	2062
28199	2062-09-06	2062
28200	2062-09-08	2062
28201	2062-09-11	2062
28202	2062-09-12	2062
28203	2062-09-13	2062
28204	2062-09-14	2062
28205	2062-09-15	2062
28206	2062-09-18	2062
28207	2062-09-19	2062
28208	2062-09-20	2062
28209	2062-09-21	2062
28210	2062-09-22	2062
28211	2062-09-25	2062
28212	2062-09-26	2062
28213	2062-09-27	2062
28214	2062-09-28	2062
28215	2062-09-29	2062
28216	2062-10-02	2062
28217	2062-10-03	2062
28218	2062-10-04	2062
28219	2062-10-05	2062
28220	2062-10-06	2062
28221	2062-10-09	2062
28222	2062-10-10	2062
28223	2062-10-11	2062
28224	2062-10-13	2062
28225	2062-10-16	2062
28226	2062-10-17	2062
28227	2062-10-18	2062
28228	2062-10-19	2062
28229	2062-10-20	2062
28230	2062-10-23	2062
28231	2062-10-24	2062
28232	2062-10-25	2062
28233	2062-10-26	2062
28234	2062-10-27	2062
28235	2062-10-30	2062
28236	2062-10-31	2062
28237	2062-11-01	2062
28238	2062-11-03	2062
28239	2062-11-06	2062
28240	2062-11-07	2062
28241	2062-11-08	2062
28242	2062-11-09	2062
28243	2062-11-10	2062
28244	2062-11-13	2062
28245	2062-11-14	2062
28246	2062-11-16	2062
28247	2062-11-17	2062
28248	2062-11-20	2062
28249	2062-11-21	2062
28250	2062-11-22	2062
28251	2062-11-23	2062
28252	2062-11-24	2062
28253	2062-11-27	2062
28254	2062-11-28	2062
28255	2062-11-29	2062
28256	2062-11-30	2062
28257	2062-12-01	2062
28258	2062-12-04	2062
28259	2062-12-05	2062
28260	2062-12-06	2062
28261	2062-12-07	2062
28262	2062-12-08	2062
28263	2062-12-11	2062
28264	2062-12-12	2062
28265	2062-12-13	2062
28266	2062-12-14	2062
28267	2062-12-15	2062
28268	2062-12-18	2062
28269	2062-12-19	2062
28270	2062-12-20	2062
28271	2062-12-21	2062
28272	2062-12-22	2062
28273	2062-12-26	2062
28274	2062-12-27	2062
28275	2062-12-28	2062
28276	2062-12-29	2062
28277	2063-01-02	2063
28278	2063-01-03	2063
28279	2063-01-04	2063
28280	2063-01-05	2063
28281	2063-01-08	2063
28282	2063-01-09	2063
28283	2063-01-10	2063
28284	2063-01-11	2063
28285	2063-01-12	2063
28286	2063-01-15	2063
28287	2063-01-16	2063
28288	2063-01-17	2063
28289	2063-01-18	2063
28290	2063-01-19	2063
28291	2063-01-22	2063
28292	2063-01-23	2063
28293	2063-01-24	2063
28294	2063-01-25	2063
28295	2063-01-26	2063
28296	2063-01-29	2063
28297	2063-01-30	2063
28298	2063-01-31	2063
28299	2063-02-01	2063
28300	2063-02-02	2063
28301	2063-02-05	2063
28302	2063-02-06	2063
28303	2063-02-07	2063
28304	2063-02-08	2063
28305	2063-02-09	2063
28306	2063-02-12	2063
28307	2063-02-13	2063
28308	2063-02-14	2063
28309	2063-02-15	2063
28310	2063-02-16	2063
28311	2063-02-19	2063
28312	2063-02-20	2063
28313	2063-02-21	2063
28314	2063-02-22	2063
28315	2063-02-23	2063
28316	2063-02-26	2063
28317	2063-02-28	2063
28318	2063-03-01	2063
28319	2063-03-02	2063
28320	2063-03-05	2063
28321	2063-03-06	2063
28322	2063-03-07	2063
28323	2063-03-08	2063
28324	2063-03-09	2063
28325	2063-03-12	2063
28326	2063-03-13	2063
28327	2063-03-14	2063
28328	2063-03-15	2063
28329	2063-03-16	2063
28330	2063-03-19	2063
28331	2063-03-20	2063
28332	2063-03-21	2063
28333	2063-03-22	2063
28334	2063-03-23	2063
28335	2063-03-26	2063
28336	2063-03-27	2063
28337	2063-03-28	2063
28338	2063-03-29	2063
28339	2063-03-30	2063
28340	2063-04-02	2063
28341	2063-04-03	2063
28342	2063-04-04	2063
28343	2063-04-05	2063
28344	2063-04-06	2063
28345	2063-04-09	2063
28346	2063-04-10	2063
28347	2063-04-11	2063
28348	2063-04-12	2063
28349	2063-04-13	2063
28350	2063-04-16	2063
28351	2063-04-17	2063
28352	2063-04-18	2063
28353	2063-04-19	2063
28354	2063-04-20	2063
28355	2063-04-23	2063
28356	2063-04-24	2063
28357	2063-04-25	2063
28358	2063-04-26	2063
28359	2063-04-27	2063
28360	2063-04-30	2063
28361	2063-05-02	2063
28362	2063-05-03	2063
28363	2063-05-04	2063
28364	2063-05-07	2063
28365	2063-05-08	2063
28366	2063-05-09	2063
28367	2063-05-10	2063
28368	2063-05-11	2063
28369	2063-05-14	2063
28370	2063-05-15	2063
28371	2063-05-16	2063
28372	2063-05-17	2063
28373	2063-05-18	2063
28374	2063-05-21	2063
28375	2063-05-22	2063
28376	2063-05-23	2063
28377	2063-05-24	2063
28378	2063-05-25	2063
28379	2063-05-28	2063
28380	2063-05-29	2063
28381	2063-05-30	2063
28382	2063-05-31	2063
28383	2063-06-01	2063
28384	2063-06-04	2063
28385	2063-06-05	2063
28386	2063-06-06	2063
28387	2063-06-07	2063
28388	2063-06-08	2063
28389	2063-06-11	2063
28390	2063-06-12	2063
28391	2063-06-13	2063
28392	2063-06-15	2063
28393	2063-06-18	2063
28394	2063-06-19	2063
28395	2063-06-20	2063
28396	2063-06-21	2063
28397	2063-06-22	2063
28398	2063-06-25	2063
28399	2063-06-26	2063
28400	2063-06-27	2063
28401	2063-06-28	2063
28402	2063-06-29	2063
28403	2063-07-02	2063
28404	2063-07-03	2063
28405	2063-07-04	2063
28406	2063-07-05	2063
28407	2063-07-06	2063
28408	2063-07-09	2063
28409	2063-07-10	2063
28410	2063-07-11	2063
28411	2063-07-12	2063
28412	2063-07-13	2063
28413	2063-07-16	2063
28414	2063-07-17	2063
28415	2063-07-18	2063
28416	2063-07-19	2063
28417	2063-07-20	2063
28418	2063-07-23	2063
28419	2063-07-24	2063
28420	2063-07-25	2063
28421	2063-07-26	2063
28422	2063-07-27	2063
28423	2063-07-30	2063
28424	2063-07-31	2063
28425	2063-08-01	2063
28426	2063-08-02	2063
28427	2063-08-03	2063
28428	2063-08-06	2063
28429	2063-08-07	2063
28430	2063-08-08	2063
28431	2063-08-09	2063
28432	2063-08-10	2063
28433	2063-08-13	2063
28434	2063-08-14	2063
28435	2063-08-15	2063
28436	2063-08-16	2063
28437	2063-08-17	2063
28438	2063-08-20	2063
28439	2063-08-21	2063
28440	2063-08-22	2063
28441	2063-08-23	2063
28442	2063-08-24	2063
28443	2063-08-27	2063
28444	2063-08-28	2063
28445	2063-08-29	2063
28446	2063-08-30	2063
28447	2063-08-31	2063
28448	2063-09-03	2063
28449	2063-09-04	2063
28450	2063-09-05	2063
28451	2063-09-06	2063
28452	2063-09-10	2063
28453	2063-09-11	2063
28454	2063-09-12	2063
28455	2063-09-13	2063
28456	2063-09-14	2063
28457	2063-09-17	2063
28458	2063-09-18	2063
28459	2063-09-19	2063
28460	2063-09-20	2063
28461	2063-09-21	2063
28462	2063-09-24	2063
28463	2063-09-25	2063
28464	2063-09-26	2063
28465	2063-09-27	2063
28466	2063-09-28	2063
28467	2063-10-01	2063
28468	2063-10-02	2063
28469	2063-10-03	2063
28470	2063-10-04	2063
28471	2063-10-05	2063
28472	2063-10-08	2063
28473	2063-10-09	2063
28474	2063-10-10	2063
28475	2063-10-11	2063
28476	2063-10-15	2063
28477	2063-10-16	2063
28478	2063-10-17	2063
28479	2063-10-18	2063
28480	2063-10-19	2063
28481	2063-10-22	2063
28482	2063-10-23	2063
28483	2063-10-24	2063
28484	2063-10-25	2063
28485	2063-10-26	2063
28486	2063-10-29	2063
28487	2063-10-30	2063
28488	2063-10-31	2063
28489	2063-11-01	2063
28490	2063-11-05	2063
28491	2063-11-06	2063
28492	2063-11-07	2063
28493	2063-11-08	2063
28494	2063-11-09	2063
28495	2063-11-12	2063
28496	2063-11-13	2063
28497	2063-11-14	2063
28498	2063-11-16	2063
28499	2063-11-19	2063
28500	2063-11-20	2063
28501	2063-11-21	2063
28502	2063-11-22	2063
28503	2063-11-23	2063
28504	2063-11-26	2063
28505	2063-11-27	2063
28506	2063-11-28	2063
28507	2063-11-29	2063
28508	2063-11-30	2063
28509	2063-12-03	2063
28510	2063-12-04	2063
28511	2063-12-05	2063
28512	2063-12-06	2063
28513	2063-12-07	2063
28514	2063-12-10	2063
28515	2063-12-11	2063
28516	2063-12-12	2063
28517	2063-12-13	2063
28518	2063-12-14	2063
28519	2063-12-17	2063
28520	2063-12-18	2063
28521	2063-12-19	2063
28522	2063-12-20	2063
28523	2063-12-21	2063
28524	2063-12-24	2063
28525	2063-12-26	2063
28526	2063-12-27	2063
28527	2063-12-28	2063
28528	2063-12-31	2063
28529	2064-01-02	2064
28530	2064-01-03	2064
28531	2064-01-04	2064
28532	2064-01-07	2064
28533	2064-01-08	2064
28534	2064-01-09	2064
28535	2064-01-10	2064
28536	2064-01-11	2064
28537	2064-01-14	2064
28538	2064-01-15	2064
28539	2064-01-16	2064
28540	2064-01-17	2064
28541	2064-01-18	2064
28542	2064-01-21	2064
28543	2064-01-22	2064
28544	2064-01-23	2064
28545	2064-01-24	2064
28546	2064-01-25	2064
28547	2064-01-28	2064
28548	2064-01-29	2064
28549	2064-01-30	2064
28550	2064-01-31	2064
28551	2064-02-01	2064
28552	2064-02-04	2064
28553	2064-02-05	2064
28554	2064-02-06	2064
28555	2064-02-07	2064
28556	2064-02-08	2064
28557	2064-02-11	2064
28558	2064-02-12	2064
28559	2064-02-13	2064
28560	2064-02-14	2064
28561	2064-02-15	2064
28562	2064-02-18	2064
28563	2064-02-20	2064
28564	2064-02-21	2064
28565	2064-02-22	2064
28566	2064-02-25	2064
28567	2064-02-26	2064
28568	2064-02-27	2064
28569	2064-02-28	2064
28570	2064-02-29	2064
28571	2064-03-03	2064
28572	2064-03-04	2064
28573	2064-03-05	2064
28574	2064-03-06	2064
28575	2064-03-07	2064
28576	2064-03-10	2064
28577	2064-03-11	2064
28578	2064-03-12	2064
28579	2064-03-13	2064
28580	2064-03-14	2064
28581	2064-03-17	2064
28582	2064-03-18	2064
28583	2064-03-19	2064
28584	2064-03-20	2064
28585	2064-03-21	2064
28586	2064-03-24	2064
28587	2064-03-25	2064
28588	2064-03-26	2064
28589	2064-03-27	2064
28590	2064-03-28	2064
28591	2064-03-31	2064
28592	2064-04-01	2064
28593	2064-04-02	2064
28594	2064-04-03	2064
28595	2064-04-04	2064
28596	2064-04-07	2064
28597	2064-04-08	2064
28598	2064-04-09	2064
28599	2064-04-10	2064
28600	2064-04-11	2064
28601	2064-04-14	2064
28602	2064-04-15	2064
28603	2064-04-16	2064
28604	2064-04-17	2064
28605	2064-04-18	2064
28606	2064-04-22	2064
28607	2064-04-23	2064
28608	2064-04-24	2064
28609	2064-04-25	2064
28610	2064-04-28	2064
28611	2064-04-29	2064
28612	2064-04-30	2064
28613	2064-05-02	2064
28614	2064-05-05	2064
28615	2064-05-06	2064
28616	2064-05-07	2064
28617	2064-05-08	2064
28618	2064-05-09	2064
28619	2064-05-12	2064
28620	2064-05-13	2064
28621	2064-05-14	2064
28622	2064-05-15	2064
28623	2064-05-16	2064
28624	2064-05-19	2064
28625	2064-05-20	2064
28626	2064-05-21	2064
28627	2064-05-22	2064
28628	2064-05-23	2064
28629	2064-05-26	2064
28630	2064-05-27	2064
28631	2064-05-28	2064
28632	2064-05-29	2064
28633	2064-05-30	2064
28634	2064-06-02	2064
28635	2064-06-03	2064
28636	2064-06-04	2064
28637	2064-06-06	2064
28638	2064-06-09	2064
28639	2064-06-10	2064
28640	2064-06-11	2064
28641	2064-06-12	2064
28642	2064-06-13	2064
28643	2064-06-16	2064
28644	2064-06-17	2064
28645	2064-06-18	2064
28646	2064-06-19	2064
28647	2064-06-20	2064
28648	2064-06-23	2064
28649	2064-06-24	2064
28650	2064-06-25	2064
28651	2064-06-26	2064
28652	2064-06-27	2064
28653	2064-06-30	2064
28654	2064-07-01	2064
28655	2064-07-02	2064
28656	2064-07-03	2064
28657	2064-07-04	2064
28658	2064-07-07	2064
28659	2064-07-08	2064
28660	2064-07-09	2064
28661	2064-07-10	2064
28662	2064-07-11	2064
28663	2064-07-14	2064
28664	2064-07-15	2064
28665	2064-07-16	2064
28666	2064-07-17	2064
28667	2064-07-18	2064
28668	2064-07-21	2064
28669	2064-07-22	2064
28670	2064-07-23	2064
28671	2064-07-24	2064
28672	2064-07-25	2064
28673	2064-07-28	2064
28674	2064-07-29	2064
28675	2064-07-30	2064
28676	2064-07-31	2064
28677	2064-08-01	2064
28678	2064-08-04	2064
28679	2064-08-05	2064
28680	2064-08-06	2064
28681	2064-08-07	2064
28682	2064-08-08	2064
28683	2064-08-11	2064
28684	2064-08-12	2064
28685	2064-08-13	2064
28686	2064-08-14	2064
28687	2064-08-15	2064
28688	2064-08-18	2064
28689	2064-08-19	2064
28690	2064-08-20	2064
28691	2064-08-21	2064
28692	2064-08-22	2064
28693	2064-08-25	2064
28694	2064-08-26	2064
28695	2064-08-27	2064
28696	2064-08-28	2064
28697	2064-08-29	2064
28698	2064-09-01	2064
28699	2064-09-02	2064
28700	2064-09-03	2064
28701	2064-09-04	2064
28702	2064-09-05	2064
28703	2064-09-08	2064
28704	2064-09-09	2064
28705	2064-09-10	2064
28706	2064-09-11	2064
28707	2064-09-12	2064
28708	2064-09-15	2064
28709	2064-09-16	2064
28710	2064-09-17	2064
28711	2064-09-18	2064
28712	2064-09-19	2064
28713	2064-09-22	2064
28714	2064-09-23	2064
28715	2064-09-24	2064
28716	2064-09-25	2064
28717	2064-09-26	2064
28718	2064-09-29	2064
28719	2064-09-30	2064
28720	2064-10-01	2064
28721	2064-10-02	2064
28722	2064-10-03	2064
28723	2064-10-06	2064
28724	2064-10-07	2064
28725	2064-10-08	2064
28726	2064-10-09	2064
28727	2064-10-10	2064
28728	2064-10-13	2064
28729	2064-10-14	2064
28730	2064-10-15	2064
28731	2064-10-16	2064
28732	2064-10-17	2064
28733	2064-10-20	2064
28734	2064-10-21	2064
28735	2064-10-22	2064
28736	2064-10-23	2064
28737	2064-10-24	2064
28738	2064-10-27	2064
28739	2064-10-29	2064
28740	2064-10-30	2064
28741	2064-10-31	2064
28742	2064-11-03	2064
28743	2064-11-04	2064
28744	2064-11-05	2064
28745	2064-11-06	2064
28746	2064-11-07	2064
28747	2064-11-10	2064
28748	2064-11-11	2064
28749	2064-11-12	2064
28750	2064-11-13	2064
28751	2064-11-14	2064
28752	2064-11-17	2064
28753	2064-11-18	2064
28754	2064-11-19	2064
28755	2064-11-20	2064
28756	2064-11-21	2064
28757	2064-11-24	2064
28758	2064-11-25	2064
28759	2064-11-26	2064
28760	2064-11-27	2064
28761	2064-11-28	2064
28762	2064-12-01	2064
28763	2064-12-02	2064
28764	2064-12-03	2064
28765	2064-12-04	2064
28766	2064-12-05	2064
28767	2064-12-08	2064
28768	2064-12-09	2064
28769	2064-12-10	2064
28770	2064-12-11	2064
28771	2064-12-12	2064
28772	2064-12-15	2064
28773	2064-12-16	2064
28774	2064-12-17	2064
28775	2064-12-18	2064
28776	2064-12-19	2064
28777	2064-12-22	2064
28778	2064-12-23	2064
28779	2064-12-24	2064
28780	2064-12-26	2064
28781	2064-12-29	2064
28782	2064-12-30	2064
28783	2064-12-31	2064
28784	2065-01-02	2065
28785	2065-01-05	2065
28786	2065-01-06	2065
28787	2065-01-07	2065
28788	2065-01-08	2065
28789	2065-01-09	2065
28790	2065-01-12	2065
28791	2065-01-13	2065
28792	2065-01-14	2065
28793	2065-01-15	2065
28794	2065-01-16	2065
28795	2065-01-19	2065
28796	2065-01-20	2065
28797	2065-01-21	2065
28798	2065-01-22	2065
28799	2065-01-23	2065
28800	2065-01-26	2065
28801	2065-01-27	2065
28802	2065-01-28	2065
28803	2065-01-29	2065
28804	2065-01-30	2065
28805	2065-02-02	2065
28806	2065-02-03	2065
28807	2065-02-04	2065
28808	2065-02-05	2065
28809	2065-02-06	2065
28810	2065-02-09	2065
28811	2065-02-11	2065
28812	2065-02-12	2065
28813	2065-02-13	2065
28814	2065-02-16	2065
28815	2065-02-17	2065
28816	2065-02-18	2065
28817	2065-02-19	2065
28818	2065-02-20	2065
28819	2065-02-23	2065
28820	2065-02-24	2065
28821	2065-02-25	2065
28822	2065-02-26	2065
28823	2065-02-27	2065
28824	2065-03-02	2065
28825	2065-03-03	2065
28826	2065-03-04	2065
28827	2065-03-05	2065
28828	2065-03-06	2065
28829	2065-03-09	2065
28830	2065-03-10	2065
28831	2065-03-11	2065
28832	2065-03-12	2065
28833	2065-03-13	2065
28834	2065-03-16	2065
28835	2065-03-17	2065
28836	2065-03-18	2065
28837	2065-03-19	2065
28838	2065-03-20	2065
28839	2065-03-23	2065
28840	2065-03-24	2065
28841	2065-03-25	2065
28842	2065-03-26	2065
28843	2065-03-27	2065
28844	2065-03-30	2065
28845	2065-03-31	2065
28846	2065-04-01	2065
28847	2065-04-02	2065
28848	2065-04-03	2065
28849	2065-04-06	2065
28850	2065-04-07	2065
28851	2065-04-08	2065
28852	2065-04-09	2065
28853	2065-04-10	2065
28854	2065-04-13	2065
28855	2065-04-14	2065
28856	2065-04-15	2065
28857	2065-04-16	2065
28858	2065-04-17	2065
28859	2065-04-20	2065
28860	2065-04-22	2065
28861	2065-04-23	2065
28862	2065-04-24	2065
28863	2065-04-27	2065
28864	2065-04-28	2065
28865	2065-04-29	2065
28866	2065-04-30	2065
28867	2065-05-04	2065
28868	2065-05-05	2065
28869	2065-05-06	2065
28870	2065-05-07	2065
28871	2065-05-08	2065
28872	2065-05-11	2065
28873	2065-05-12	2065
28874	2065-05-13	2065
28875	2065-05-14	2065
28876	2065-05-15	2065
28877	2065-05-18	2065
28878	2065-05-19	2065
28879	2065-05-20	2065
28880	2065-05-21	2065
28881	2065-05-22	2065
28882	2065-05-25	2065
28883	2065-05-26	2065
28884	2065-05-27	2065
28885	2065-05-29	2065
28886	2065-06-01	2065
28887	2065-06-02	2065
28888	2065-06-03	2065
28889	2065-06-04	2065
28890	2065-06-05	2065
28891	2065-06-08	2065
28892	2065-06-09	2065
28893	2065-06-10	2065
28894	2065-06-11	2065
28895	2065-06-12	2065
28896	2065-06-15	2065
28897	2065-06-16	2065
28898	2065-06-17	2065
28899	2065-06-18	2065
28900	2065-06-19	2065
28901	2065-06-22	2065
28902	2065-06-23	2065
28903	2065-06-24	2065
28904	2065-06-25	2065
28905	2065-06-26	2065
28906	2065-06-29	2065
28907	2065-06-30	2065
28908	2065-07-01	2065
28909	2065-07-02	2065
28910	2065-07-03	2065
28911	2065-07-06	2065
28912	2065-07-07	2065
28913	2065-07-08	2065
28914	2065-07-09	2065
28915	2065-07-10	2065
28916	2065-07-13	2065
28917	2065-07-14	2065
28918	2065-07-15	2065
28919	2065-07-16	2065
28920	2065-07-17	2065
28921	2065-07-20	2065
28922	2065-07-21	2065
28923	2065-07-22	2065
28924	2065-07-23	2065
28925	2065-07-24	2065
28926	2065-07-27	2065
28927	2065-07-28	2065
28928	2065-07-29	2065
28929	2065-07-30	2065
28930	2065-07-31	2065
28931	2065-08-03	2065
28932	2065-08-04	2065
28933	2065-08-05	2065
28934	2065-08-06	2065
28935	2065-08-07	2065
28936	2065-08-10	2065
28937	2065-08-11	2065
28938	2065-08-12	2065
28939	2065-08-13	2065
28940	2065-08-14	2065
28941	2065-08-17	2065
28942	2065-08-18	2065
28943	2065-08-19	2065
28944	2065-08-20	2065
28945	2065-08-21	2065
28946	2065-08-24	2065
28947	2065-08-25	2065
28948	2065-08-26	2065
28949	2065-08-27	2065
28950	2065-08-28	2065
28951	2065-08-31	2065
28952	2065-09-01	2065
28953	2065-09-02	2065
28954	2065-09-03	2065
28955	2065-09-04	2065
28956	2065-09-08	2065
28957	2065-09-09	2065
28958	2065-09-10	2065
28959	2065-09-11	2065
28960	2065-09-14	2065
28961	2065-09-15	2065
28962	2065-09-16	2065
28963	2065-09-17	2065
28964	2065-09-18	2065
28965	2065-09-21	2065
28966	2065-09-22	2065
28967	2065-09-23	2065
28968	2065-09-24	2065
28969	2065-09-25	2065
28970	2065-09-28	2065
28971	2065-09-29	2065
28972	2065-09-30	2065
28973	2065-10-01	2065
28974	2065-10-02	2065
28975	2065-10-05	2065
28976	2065-10-06	2065
28977	2065-10-07	2065
28978	2065-10-08	2065
28979	2065-10-09	2065
28980	2065-10-13	2065
28981	2065-10-14	2065
28982	2065-10-15	2065
28983	2065-10-16	2065
28984	2065-10-19	2065
28985	2065-10-20	2065
28986	2065-10-21	2065
28987	2065-10-22	2065
28988	2065-10-23	2065
28989	2065-10-26	2065
28990	2065-10-27	2065
28991	2065-10-29	2065
28992	2065-10-30	2065
28993	2065-11-03	2065
28994	2065-11-04	2065
28995	2065-11-05	2065
28996	2065-11-06	2065
28997	2065-11-09	2065
28998	2065-11-10	2065
28999	2065-11-11	2065
29000	2065-11-12	2065
29001	2065-11-13	2065
29002	2065-11-16	2065
29003	2065-11-17	2065
29004	2065-11-18	2065
29005	2065-11-19	2065
29006	2065-11-20	2065
29007	2065-11-23	2065
29008	2065-11-24	2065
29009	2065-11-25	2065
29010	2065-11-26	2065
29011	2065-11-27	2065
29012	2065-11-30	2065
29013	2065-12-01	2065
29014	2065-12-02	2065
29015	2065-12-03	2065
29016	2065-12-04	2065
29017	2065-12-07	2065
29018	2065-12-08	2065
29019	2065-12-09	2065
29020	2065-12-10	2065
29021	2065-12-11	2065
29022	2065-12-14	2065
29023	2065-12-15	2065
29024	2065-12-16	2065
29025	2065-12-17	2065
29026	2065-12-18	2065
29027	2065-12-21	2065
29028	2065-12-22	2065
29029	2065-12-23	2065
29030	2065-12-24	2065
29031	2065-12-28	2065
29032	2065-12-29	2065
29033	2065-12-30	2065
29034	2065-12-31	2065
29035	2066-01-04	2066
29036	2066-01-05	2066
29037	2066-01-06	2066
29038	2066-01-07	2066
29039	2066-01-08	2066
29040	2066-01-11	2066
29041	2066-01-12	2066
29042	2066-01-13	2066
29043	2066-01-14	2066
29044	2066-01-15	2066
29045	2066-01-18	2066
29046	2066-01-19	2066
29047	2066-01-20	2066
29048	2066-01-21	2066
29049	2066-01-22	2066
29050	2066-01-25	2066
29051	2066-01-26	2066
29052	2066-01-27	2066
29053	2066-01-28	2066
29054	2066-01-29	2066
29055	2066-02-01	2066
29056	2066-02-02	2066
29057	2066-02-03	2066
29058	2066-02-04	2066
29059	2066-02-05	2066
29060	2066-02-08	2066
29061	2066-02-09	2066
29062	2066-02-10	2066
29063	2066-02-11	2066
29064	2066-02-12	2066
29065	2066-02-15	2066
29066	2066-02-16	2066
29067	2066-02-17	2066
29068	2066-02-18	2066
29069	2066-02-19	2066
29070	2066-02-22	2066
29071	2066-02-24	2066
29072	2066-02-25	2066
29073	2066-02-26	2066
29074	2066-03-01	2066
29075	2066-03-02	2066
29076	2066-03-03	2066
29077	2066-03-04	2066
29078	2066-03-05	2066
29079	2066-03-08	2066
29080	2066-03-09	2066
29081	2066-03-10	2066
29082	2066-03-11	2066
29083	2066-03-12	2066
29084	2066-03-15	2066
29085	2066-03-16	2066
29086	2066-03-17	2066
29087	2066-03-18	2066
29088	2066-03-19	2066
29089	2066-03-22	2066
29090	2066-03-23	2066
29091	2066-03-24	2066
29092	2066-03-25	2066
29093	2066-03-26	2066
29094	2066-03-29	2066
29095	2066-03-30	2066
29096	2066-03-31	2066
29097	2066-04-01	2066
29098	2066-04-02	2066
29099	2066-04-05	2066
29100	2066-04-06	2066
29101	2066-04-07	2066
29102	2066-04-08	2066
29103	2066-04-09	2066
29104	2066-04-12	2066
29105	2066-04-13	2066
29106	2066-04-14	2066
29107	2066-04-15	2066
29108	2066-04-16	2066
29109	2066-04-19	2066
29110	2066-04-20	2066
29111	2066-04-22	2066
29112	2066-04-23	2066
29113	2066-04-26	2066
29114	2066-04-27	2066
29115	2066-04-28	2066
29116	2066-04-29	2066
29117	2066-04-30	2066
29118	2066-05-03	2066
29119	2066-05-04	2066
29120	2066-05-05	2066
29121	2066-05-06	2066
29122	2066-05-07	2066
29123	2066-05-10	2066
29124	2066-05-11	2066
29125	2066-05-12	2066
29126	2066-05-13	2066
29127	2066-05-14	2066
29128	2066-05-17	2066
29129	2066-05-18	2066
29130	2066-05-19	2066
29131	2066-05-20	2066
29132	2066-05-21	2066
29133	2066-05-24	2066
29134	2066-05-25	2066
29135	2066-05-26	2066
29136	2066-05-27	2066
29137	2066-05-28	2066
29138	2066-05-31	2066
29139	2066-06-01	2066
29140	2066-06-02	2066
29141	2066-06-03	2066
29142	2066-06-04	2066
29143	2066-06-07	2066
29144	2066-06-08	2066
29145	2066-06-09	2066
29146	2066-06-11	2066
29147	2066-06-14	2066
29148	2066-06-15	2066
29149	2066-06-16	2066
29150	2066-06-17	2066
29151	2066-06-18	2066
29152	2066-06-21	2066
29153	2066-06-22	2066
29154	2066-06-23	2066
29155	2066-06-24	2066
29156	2066-06-25	2066
29157	2066-06-28	2066
29158	2066-06-29	2066
29159	2066-06-30	2066
29160	2066-07-01	2066
29161	2066-07-02	2066
29162	2066-07-05	2066
29163	2066-07-06	2066
29164	2066-07-07	2066
29165	2066-07-08	2066
29166	2066-07-09	2066
29167	2066-07-12	2066
29168	2066-07-13	2066
29169	2066-07-14	2066
29170	2066-07-15	2066
29171	2066-07-16	2066
29172	2066-07-19	2066
29173	2066-07-20	2066
29174	2066-07-21	2066
29175	2066-07-22	2066
29176	2066-07-23	2066
29177	2066-07-26	2066
29178	2066-07-27	2066
29179	2066-07-28	2066
29180	2066-07-29	2066
29181	2066-07-30	2066
29182	2066-08-02	2066
29183	2066-08-03	2066
29184	2066-08-04	2066
29185	2066-08-05	2066
29186	2066-08-06	2066
29187	2066-08-09	2066
29188	2066-08-10	2066
29189	2066-08-11	2066
29190	2066-08-12	2066
29191	2066-08-13	2066
29192	2066-08-16	2066
29193	2066-08-17	2066
29194	2066-08-18	2066
29195	2066-08-19	2066
29196	2066-08-20	2066
29197	2066-08-23	2066
29198	2066-08-24	2066
29199	2066-08-25	2066
29200	2066-08-26	2066
29201	2066-08-27	2066
29202	2066-08-30	2066
29203	2066-08-31	2066
29204	2066-09-01	2066
29205	2066-09-02	2066
29206	2066-09-03	2066
29207	2066-09-06	2066
29208	2066-09-08	2066
29209	2066-09-09	2066
29210	2066-09-10	2066
29211	2066-09-13	2066
29212	2066-09-14	2066
29213	2066-09-15	2066
29214	2066-09-16	2066
29215	2066-09-17	2066
29216	2066-09-20	2066
29217	2066-09-21	2066
29218	2066-09-22	2066
29219	2066-09-23	2066
29220	2066-09-24	2066
29221	2066-09-27	2066
29222	2066-09-28	2066
29223	2066-09-29	2066
29224	2066-09-30	2066
29225	2066-10-01	2066
29226	2066-10-04	2066
29227	2066-10-05	2066
29228	2066-10-06	2066
29229	2066-10-07	2066
29230	2066-10-08	2066
29231	2066-10-11	2066
29232	2066-10-13	2066
29233	2066-10-14	2066
29234	2066-10-15	2066
29235	2066-10-18	2066
29236	2066-10-19	2066
29237	2066-10-20	2066
29238	2066-10-21	2066
29239	2066-10-22	2066
29240	2066-10-25	2066
29241	2066-10-26	2066
29242	2066-10-27	2066
29243	2066-10-29	2066
29244	2066-11-01	2066
29245	2066-11-03	2066
29246	2066-11-04	2066
29247	2066-11-05	2066
29248	2066-11-08	2066
29249	2066-11-09	2066
29250	2066-11-10	2066
29251	2066-11-11	2066
29252	2066-11-12	2066
29253	2066-11-16	2066
29254	2066-11-17	2066
29255	2066-11-18	2066
29256	2066-11-19	2066
29257	2066-11-22	2066
29258	2066-11-23	2066
29259	2066-11-24	2066
29260	2066-11-25	2066
29261	2066-11-26	2066
29262	2066-11-29	2066
29263	2066-11-30	2066
29264	2066-12-01	2066
29265	2066-12-02	2066
29266	2066-12-03	2066
29267	2066-12-06	2066
29268	2066-12-07	2066
29269	2066-12-08	2066
29270	2066-12-09	2066
29271	2066-12-10	2066
29272	2066-12-13	2066
29273	2066-12-14	2066
29274	2066-12-15	2066
29275	2066-12-16	2066
29276	2066-12-17	2066
29277	2066-12-20	2066
29278	2066-12-21	2066
29279	2066-12-22	2066
29280	2066-12-23	2066
29281	2066-12-24	2066
29282	2066-12-27	2066
29283	2066-12-28	2066
29284	2066-12-29	2066
29285	2066-12-30	2066
29286	2066-12-31	2066
29287	2067-01-03	2067
29288	2067-01-04	2067
29289	2067-01-05	2067
29290	2067-01-06	2067
29291	2067-01-07	2067
29292	2067-01-10	2067
29293	2067-01-11	2067
29294	2067-01-12	2067
29295	2067-01-13	2067
29296	2067-01-14	2067
29297	2067-01-17	2067
29298	2067-01-18	2067
29299	2067-01-19	2067
29300	2067-01-20	2067
29301	2067-01-21	2067
29302	2067-01-24	2067
29303	2067-01-25	2067
29304	2067-01-26	2067
29305	2067-01-27	2067
29306	2067-01-28	2067
29307	2067-01-31	2067
29308	2067-02-01	2067
29309	2067-02-02	2067
29310	2067-02-03	2067
29311	2067-02-04	2067
29312	2067-02-07	2067
29313	2067-02-08	2067
29314	2067-02-09	2067
29315	2067-02-10	2067
29316	2067-02-11	2067
29317	2067-02-14	2067
29318	2067-02-16	2067
29319	2067-02-17	2067
29320	2067-02-18	2067
29321	2067-02-21	2067
29322	2067-02-22	2067
29323	2067-02-23	2067
29324	2067-02-24	2067
29325	2067-02-25	2067
29326	2067-02-28	2067
29327	2067-03-01	2067
29328	2067-03-02	2067
29329	2067-03-03	2067
29330	2067-03-04	2067
29331	2067-03-07	2067
29332	2067-03-08	2067
29333	2067-03-09	2067
29334	2067-03-10	2067
29335	2067-03-11	2067
29336	2067-03-14	2067
29337	2067-03-15	2067
29338	2067-03-16	2067
29339	2067-03-17	2067
29340	2067-03-18	2067
29341	2067-03-21	2067
29342	2067-03-22	2067
29343	2067-03-23	2067
29344	2067-03-24	2067
29345	2067-03-25	2067
29346	2067-03-28	2067
29347	2067-03-29	2067
29348	2067-03-30	2067
29349	2067-03-31	2067
29350	2067-04-01	2067
29351	2067-04-04	2067
29352	2067-04-05	2067
29353	2067-04-06	2067
29354	2067-04-07	2067
29355	2067-04-08	2067
29356	2067-04-11	2067
29357	2067-04-12	2067
29358	2067-04-13	2067
29359	2067-04-14	2067
29360	2067-04-15	2067
29361	2067-04-18	2067
29362	2067-04-19	2067
29363	2067-04-20	2067
29364	2067-04-22	2067
29365	2067-04-25	2067
29366	2067-04-26	2067
29367	2067-04-27	2067
29368	2067-04-28	2067
29369	2067-04-29	2067
29370	2067-05-02	2067
29371	2067-05-03	2067
29372	2067-05-04	2067
29373	2067-05-05	2067
29374	2067-05-06	2067
29375	2067-05-09	2067
29376	2067-05-10	2067
29377	2067-05-11	2067
29378	2067-05-12	2067
29379	2067-05-13	2067
29380	2067-05-16	2067
29381	2067-05-17	2067
29382	2067-05-18	2067
29383	2067-05-19	2067
29384	2067-05-20	2067
29385	2067-05-23	2067
29386	2067-05-24	2067
29387	2067-05-25	2067
29388	2067-05-26	2067
29389	2067-05-27	2067
29390	2067-05-30	2067
29391	2067-05-31	2067
29392	2067-06-01	2067
29393	2067-06-03	2067
29394	2067-06-06	2067
29395	2067-06-07	2067
29396	2067-06-08	2067
29397	2067-06-09	2067
29398	2067-06-10	2067
29399	2067-06-13	2067
29400	2067-06-14	2067
29401	2067-06-15	2067
29402	2067-06-16	2067
29403	2067-06-17	2067
29404	2067-06-20	2067
29405	2067-06-21	2067
29406	2067-06-22	2067
29407	2067-06-23	2067
29408	2067-06-24	2067
29409	2067-06-27	2067
29410	2067-06-28	2067
29411	2067-06-29	2067
29412	2067-06-30	2067
29413	2067-07-01	2067
29414	2067-07-04	2067
29415	2067-07-05	2067
29416	2067-07-06	2067
29417	2067-07-07	2067
29418	2067-07-08	2067
29419	2067-07-11	2067
29420	2067-07-12	2067
29421	2067-07-13	2067
29422	2067-07-14	2067
29423	2067-07-15	2067
29424	2067-07-18	2067
29425	2067-07-19	2067
29426	2067-07-20	2067
29427	2067-07-21	2067
29428	2067-07-22	2067
29429	2067-07-25	2067
29430	2067-07-26	2067
29431	2067-07-27	2067
29432	2067-07-28	2067
29433	2067-07-29	2067
29434	2067-08-01	2067
29435	2067-08-02	2067
29436	2067-08-03	2067
29437	2067-08-04	2067
29438	2067-08-05	2067
29439	2067-08-08	2067
29440	2067-08-09	2067
29441	2067-08-10	2067
29442	2067-08-11	2067
29443	2067-08-12	2067
29444	2067-08-15	2067
29445	2067-08-16	2067
29446	2067-08-17	2067
29447	2067-08-18	2067
29448	2067-08-19	2067
29449	2067-08-22	2067
29450	2067-08-23	2067
29451	2067-08-24	2067
29452	2067-08-25	2067
29453	2067-08-26	2067
29454	2067-08-29	2067
29455	2067-08-30	2067
29456	2067-08-31	2067
29457	2067-09-01	2067
29458	2067-09-02	2067
29459	2067-09-05	2067
29460	2067-09-06	2067
29461	2067-09-08	2067
29462	2067-09-09	2067
29463	2067-09-12	2067
29464	2067-09-13	2067
29465	2067-09-14	2067
29466	2067-09-15	2067
29467	2067-09-16	2067
29468	2067-09-19	2067
29469	2067-09-20	2067
29470	2067-09-21	2067
29471	2067-09-22	2067
29472	2067-09-23	2067
29473	2067-09-26	2067
29474	2067-09-27	2067
29475	2067-09-28	2067
29476	2067-09-29	2067
29477	2067-09-30	2067
29478	2067-10-03	2067
29479	2067-10-04	2067
29480	2067-10-05	2067
29481	2067-10-06	2067
29482	2067-10-07	2067
29483	2067-10-10	2067
29484	2067-10-11	2067
29485	2067-10-13	2067
29486	2067-10-14	2067
29487	2067-10-17	2067
29488	2067-10-18	2067
29489	2067-10-19	2067
29490	2067-10-20	2067
29491	2067-10-21	2067
29492	2067-10-24	2067
29493	2067-10-25	2067
29494	2067-10-26	2067
29495	2067-10-27	2067
29496	2067-10-31	2067
29497	2067-11-01	2067
29498	2067-11-03	2067
29499	2067-11-04	2067
29500	2067-11-07	2067
29501	2067-11-08	2067
29502	2067-11-09	2067
29503	2067-11-10	2067
29504	2067-11-11	2067
29505	2067-11-14	2067
29506	2067-11-16	2067
29507	2067-11-17	2067
29508	2067-11-18	2067
29509	2067-11-21	2067
29510	2067-11-22	2067
29511	2067-11-23	2067
29512	2067-11-24	2067
29513	2067-11-25	2067
29514	2067-11-28	2067
29515	2067-11-29	2067
29516	2067-11-30	2067
29517	2067-12-01	2067
29518	2067-12-02	2067
29519	2067-12-05	2067
29520	2067-12-06	2067
29521	2067-12-07	2067
29522	2067-12-08	2067
29523	2067-12-09	2067
29524	2067-12-12	2067
29525	2067-12-13	2067
29526	2067-12-14	2067
29527	2067-12-15	2067
29528	2067-12-16	2067
29529	2067-12-19	2067
29530	2067-12-20	2067
29531	2067-12-21	2067
29532	2067-12-22	2067
29533	2067-12-23	2067
29534	2067-12-26	2067
29535	2067-12-27	2067
29536	2067-12-28	2067
29537	2067-12-29	2067
29538	2067-12-30	2067
29539	2068-01-02	2068
29540	2068-01-03	2068
29541	2068-01-04	2068
29542	2068-01-05	2068
29543	2068-01-06	2068
29544	2068-01-09	2068
29545	2068-01-10	2068
29546	2068-01-11	2068
29547	2068-01-12	2068
29548	2068-01-13	2068
29549	2068-01-16	2068
29550	2068-01-17	2068
29551	2068-01-18	2068
29552	2068-01-19	2068
29553	2068-01-20	2068
29554	2068-01-23	2068
29555	2068-01-24	2068
29556	2068-01-25	2068
29557	2068-01-26	2068
29558	2068-01-27	2068
29559	2068-01-30	2068
29560	2068-01-31	2068
29561	2068-02-01	2068
29562	2068-02-02	2068
29563	2068-02-03	2068
29564	2068-02-06	2068
29565	2068-02-07	2068
29566	2068-02-08	2068
29567	2068-02-09	2068
29568	2068-02-10	2068
29569	2068-02-13	2068
29570	2068-02-14	2068
29571	2068-02-15	2068
29572	2068-02-16	2068
29573	2068-02-17	2068
29574	2068-02-20	2068
29575	2068-02-21	2068
29576	2068-02-22	2068
29577	2068-02-23	2068
29578	2068-02-24	2068
29579	2068-02-27	2068
29580	2068-02-28	2068
29581	2068-02-29	2068
29582	2068-03-01	2068
29583	2068-03-02	2068
29584	2068-03-05	2068
29585	2068-03-07	2068
29586	2068-03-08	2068
29587	2068-03-09	2068
29588	2068-03-12	2068
29589	2068-03-13	2068
29590	2068-03-14	2068
29591	2068-03-15	2068
29592	2068-03-16	2068
29593	2068-03-19	2068
29594	2068-03-20	2068
29595	2068-03-21	2068
29596	2068-03-22	2068
29597	2068-03-23	2068
29598	2068-03-26	2068
29599	2068-03-27	2068
29600	2068-03-28	2068
29601	2068-03-29	2068
29602	2068-03-30	2068
29603	2068-04-02	2068
29604	2068-04-03	2068
29605	2068-04-04	2068
29606	2068-04-05	2068
29607	2068-04-06	2068
29608	2068-04-09	2068
29609	2068-04-10	2068
29610	2068-04-11	2068
29611	2068-04-12	2068
29612	2068-04-13	2068
29613	2068-04-16	2068
29614	2068-04-17	2068
29615	2068-04-18	2068
29616	2068-04-19	2068
29617	2068-04-20	2068
29618	2068-04-23	2068
29619	2068-04-24	2068
29620	2068-04-25	2068
29621	2068-04-26	2068
29622	2068-04-27	2068
29623	2068-04-30	2068
29624	2068-05-02	2068
29625	2068-05-03	2068
29626	2068-05-04	2068
29627	2068-05-07	2068
29628	2068-05-08	2068
29629	2068-05-09	2068
29630	2068-05-10	2068
29631	2068-05-11	2068
29632	2068-05-14	2068
29633	2068-05-15	2068
29634	2068-05-16	2068
29635	2068-05-17	2068
29636	2068-05-18	2068
29637	2068-05-21	2068
29638	2068-05-22	2068
29639	2068-05-23	2068
29640	2068-05-24	2068
29641	2068-05-25	2068
29642	2068-05-28	2068
29643	2068-05-29	2068
29644	2068-05-30	2068
29645	2068-05-31	2068
29646	2068-06-01	2068
29647	2068-06-04	2068
29648	2068-06-05	2068
29649	2068-06-06	2068
29650	2068-06-07	2068
29651	2068-06-08	2068
29652	2068-06-11	2068
29653	2068-06-12	2068
29654	2068-06-13	2068
29655	2068-06-14	2068
29656	2068-06-15	2068
29657	2068-06-18	2068
29658	2068-06-19	2068
29659	2068-06-20	2068
29660	2068-06-22	2068
29661	2068-06-25	2068
29662	2068-06-26	2068
29663	2068-06-27	2068
29664	2068-06-28	2068
29665	2068-06-29	2068
29666	2068-07-02	2068
29667	2068-07-03	2068
29668	2068-07-04	2068
29669	2068-07-05	2068
29670	2068-07-06	2068
29671	2068-07-09	2068
29672	2068-07-10	2068
29673	2068-07-11	2068
29674	2068-07-12	2068
29675	2068-07-13	2068
29676	2068-07-16	2068
29677	2068-07-17	2068
29678	2068-07-18	2068
29679	2068-07-19	2068
29680	2068-07-20	2068
29681	2068-07-23	2068
29682	2068-07-24	2068
29683	2068-07-25	2068
29684	2068-07-26	2068
29685	2068-07-27	2068
29686	2068-07-30	2068
29687	2068-07-31	2068
29688	2068-08-01	2068
29689	2068-08-02	2068
29690	2068-08-03	2068
29691	2068-08-06	2068
29692	2068-08-07	2068
29693	2068-08-08	2068
29694	2068-08-09	2068
29695	2068-08-10	2068
29696	2068-08-13	2068
29697	2068-08-14	2068
29698	2068-08-15	2068
29699	2068-08-16	2068
29700	2068-08-17	2068
29701	2068-08-20	2068
29702	2068-08-21	2068
29703	2068-08-22	2068
29704	2068-08-23	2068
29705	2068-08-24	2068
29706	2068-08-27	2068
29707	2068-08-28	2068
29708	2068-08-29	2068
29709	2068-08-30	2068
29710	2068-08-31	2068
29711	2068-09-03	2068
29712	2068-09-04	2068
29713	2068-09-05	2068
29714	2068-09-06	2068
29715	2068-09-10	2068
29716	2068-09-11	2068
29717	2068-09-12	2068
29718	2068-09-13	2068
29719	2068-09-14	2068
29720	2068-09-17	2068
29721	2068-09-18	2068
29722	2068-09-19	2068
29723	2068-09-20	2068
29724	2068-09-21	2068
29725	2068-09-24	2068
29726	2068-09-25	2068
29727	2068-09-26	2068
29728	2068-09-27	2068
29729	2068-09-28	2068
29730	2068-10-01	2068
29731	2068-10-02	2068
29732	2068-10-03	2068
29733	2068-10-04	2068
29734	2068-10-05	2068
29735	2068-10-08	2068
29736	2068-10-09	2068
29737	2068-10-10	2068
29738	2068-10-11	2068
29739	2068-10-15	2068
29740	2068-10-16	2068
29741	2068-10-17	2068
29742	2068-10-18	2068
29743	2068-10-19	2068
29744	2068-10-22	2068
29745	2068-10-23	2068
29746	2068-10-24	2068
29747	2068-10-25	2068
29748	2068-10-26	2068
29749	2068-10-29	2068
29750	2068-10-30	2068
29751	2068-10-31	2068
29752	2068-11-01	2068
29753	2068-11-05	2068
29754	2068-11-06	2068
29755	2068-11-07	2068
29756	2068-11-08	2068
29757	2068-11-09	2068
29758	2068-11-12	2068
29759	2068-11-13	2068
29760	2068-11-14	2068
29761	2068-11-16	2068
29762	2068-11-19	2068
29763	2068-11-20	2068
29764	2068-11-21	2068
29765	2068-11-22	2068
29766	2068-11-23	2068
29767	2068-11-26	2068
29768	2068-11-27	2068
29769	2068-11-28	2068
29770	2068-11-29	2068
29771	2068-11-30	2068
29772	2068-12-03	2068
29773	2068-12-04	2068
29774	2068-12-05	2068
29775	2068-12-06	2068
29776	2068-12-07	2068
29777	2068-12-10	2068
29778	2068-12-11	2068
29779	2068-12-12	2068
29780	2068-12-13	2068
29781	2068-12-14	2068
29782	2068-12-17	2068
29783	2068-12-18	2068
29784	2068-12-19	2068
29785	2068-12-20	2068
29786	2068-12-21	2068
29787	2068-12-24	2068
29788	2068-12-26	2068
29789	2068-12-27	2068
29790	2068-12-28	2068
29791	2068-12-31	2068
29792	2069-01-02	2069
29793	2069-01-03	2069
29794	2069-01-04	2069
29795	2069-01-07	2069
29796	2069-01-08	2069
29797	2069-01-09	2069
29798	2069-01-10	2069
29799	2069-01-11	2069
29800	2069-01-14	2069
29801	2069-01-15	2069
29802	2069-01-16	2069
29803	2069-01-17	2069
29804	2069-01-18	2069
29805	2069-01-21	2069
29806	2069-01-22	2069
29807	2069-01-23	2069
29808	2069-01-24	2069
29809	2069-01-25	2069
29810	2069-01-28	2069
29811	2069-01-29	2069
29812	2069-01-30	2069
29813	2069-01-31	2069
29814	2069-02-01	2069
29815	2069-02-04	2069
29816	2069-02-05	2069
29817	2069-02-06	2069
29818	2069-02-07	2069
29819	2069-02-08	2069
29820	2069-02-11	2069
29821	2069-02-12	2069
29822	2069-02-13	2069
29823	2069-02-14	2069
29824	2069-02-15	2069
29825	2069-02-18	2069
29826	2069-02-19	2069
29827	2069-02-20	2069
29828	2069-02-21	2069
29829	2069-02-22	2069
29830	2069-02-25	2069
29831	2069-02-27	2069
29832	2069-02-28	2069
29833	2069-03-01	2069
29834	2069-03-04	2069
29835	2069-03-05	2069
29836	2069-03-06	2069
29837	2069-03-07	2069
29838	2069-03-08	2069
29839	2069-03-11	2069
29840	2069-03-12	2069
29841	2069-03-13	2069
29842	2069-03-14	2069
29843	2069-03-15	2069
29844	2069-03-18	2069
29845	2069-03-19	2069
29846	2069-03-20	2069
29847	2069-03-21	2069
29848	2069-03-22	2069
29849	2069-03-25	2069
29850	2069-03-26	2069
29851	2069-03-27	2069
29852	2069-03-28	2069
29853	2069-03-29	2069
29854	2069-04-01	2069
29855	2069-04-02	2069
29856	2069-04-03	2069
29857	2069-04-04	2069
29858	2069-04-05	2069
29859	2069-04-08	2069
29860	2069-04-09	2069
29861	2069-04-10	2069
29862	2069-04-11	2069
29863	2069-04-12	2069
29864	2069-04-15	2069
29865	2069-04-16	2069
29866	2069-04-17	2069
29867	2069-04-18	2069
29868	2069-04-19	2069
29869	2069-04-22	2069
29870	2069-04-23	2069
29871	2069-04-24	2069
29872	2069-04-25	2069
29873	2069-04-26	2069
29874	2069-04-29	2069
29875	2069-04-30	2069
29876	2069-05-02	2069
29877	2069-05-03	2069
29878	2069-05-06	2069
29879	2069-05-07	2069
29880	2069-05-08	2069
29881	2069-05-09	2069
29882	2069-05-10	2069
29883	2069-05-13	2069
29884	2069-05-14	2069
29885	2069-05-15	2069
29886	2069-05-16	2069
29887	2069-05-17	2069
29888	2069-05-20	2069
29889	2069-05-21	2069
29890	2069-05-22	2069
29891	2069-05-23	2069
29892	2069-05-24	2069
29893	2069-05-27	2069
29894	2069-05-28	2069
29895	2069-05-29	2069
29896	2069-05-30	2069
29897	2069-05-31	2069
29898	2069-06-03	2069
29899	2069-06-04	2069
29900	2069-06-05	2069
29901	2069-06-06	2069
29902	2069-06-07	2069
29903	2069-06-10	2069
29904	2069-06-11	2069
29905	2069-06-12	2069
29906	2069-06-14	2069
29907	2069-06-17	2069
29908	2069-06-18	2069
29909	2069-06-19	2069
29910	2069-06-20	2069
29911	2069-06-21	2069
29912	2069-06-24	2069
29913	2069-06-25	2069
29914	2069-06-26	2069
29915	2069-06-27	2069
29916	2069-06-28	2069
29917	2069-07-01	2069
29918	2069-07-02	2069
29919	2069-07-03	2069
29920	2069-07-04	2069
29921	2069-07-05	2069
29922	2069-07-08	2069
29923	2069-07-09	2069
29924	2069-07-10	2069
29925	2069-07-11	2069
29926	2069-07-12	2069
29927	2069-07-15	2069
29928	2069-07-16	2069
29929	2069-07-17	2069
29930	2069-07-18	2069
29931	2069-07-19	2069
29932	2069-07-22	2069
29933	2069-07-23	2069
29934	2069-07-24	2069
29935	2069-07-25	2069
29936	2069-07-26	2069
29937	2069-07-29	2069
29938	2069-07-30	2069
29939	2069-07-31	2069
29940	2069-08-01	2069
29941	2069-08-02	2069
29942	2069-08-05	2069
29943	2069-08-06	2069
29944	2069-08-07	2069
29945	2069-08-08	2069
29946	2069-08-09	2069
29947	2069-08-12	2069
29948	2069-08-13	2069
29949	2069-08-14	2069
29950	2069-08-15	2069
29951	2069-08-16	2069
29952	2069-08-19	2069
29953	2069-08-20	2069
29954	2069-08-21	2069
29955	2069-08-22	2069
29956	2069-08-23	2069
29957	2069-08-26	2069
29958	2069-08-27	2069
29959	2069-08-28	2069
29960	2069-08-29	2069
29961	2069-08-30	2069
29962	2069-09-02	2069
29963	2069-09-03	2069
29964	2069-09-04	2069
29965	2069-09-05	2069
29966	2069-09-06	2069
29967	2069-09-09	2069
29968	2069-09-10	2069
29969	2069-09-11	2069
29970	2069-09-12	2069
29971	2069-09-13	2069
29972	2069-09-16	2069
29973	2069-09-17	2069
29974	2069-09-18	2069
29975	2069-09-19	2069
29976	2069-09-20	2069
29977	2069-09-23	2069
29978	2069-09-24	2069
29979	2069-09-25	2069
29980	2069-09-26	2069
29981	2069-09-27	2069
29982	2069-09-30	2069
29983	2069-10-01	2069
29984	2069-10-02	2069
29985	2069-10-03	2069
29986	2069-10-04	2069
29987	2069-10-07	2069
29988	2069-10-08	2069
29989	2069-10-09	2069
29990	2069-10-10	2069
29991	2069-10-11	2069
29992	2069-10-14	2069
29993	2069-10-15	2069
29994	2069-10-16	2069
29995	2069-10-17	2069
29996	2069-10-18	2069
29997	2069-10-21	2069
29998	2069-10-22	2069
29999	2069-10-23	2069
30000	2069-10-24	2069
30001	2069-10-25	2069
30002	2069-10-29	2069
30003	2069-10-30	2069
30004	2069-10-31	2069
30005	2069-11-01	2069
30006	2069-11-04	2069
30007	2069-11-05	2069
30008	2069-11-06	2069
30009	2069-11-07	2069
30010	2069-11-08	2069
30011	2069-11-11	2069
30012	2069-11-12	2069
30013	2069-11-13	2069
30014	2069-11-14	2069
30015	2069-11-18	2069
30016	2069-11-19	2069
30017	2069-11-20	2069
30018	2069-11-21	2069
30019	2069-11-22	2069
30020	2069-11-25	2069
30021	2069-11-26	2069
30022	2069-11-27	2069
30023	2069-11-28	2069
30024	2069-11-29	2069
30025	2069-12-02	2069
30026	2069-12-03	2069
30027	2069-12-04	2069
30028	2069-12-05	2069
30029	2069-12-06	2069
30030	2069-12-09	2069
30031	2069-12-10	2069
30032	2069-12-11	2069
30033	2069-12-12	2069
30034	2069-12-13	2069
30035	2069-12-16	2069
30036	2069-12-17	2069
30037	2069-12-18	2069
30038	2069-12-19	2069
30039	2069-12-20	2069
30040	2069-12-23	2069
30041	2069-12-24	2069
30042	2069-12-26	2069
30043	2069-12-27	2069
30044	2069-12-30	2069
30045	2069-12-31	2069
30046	2070-01-02	2070
30047	2070-01-03	2070
30048	2070-01-06	2070
30049	2070-01-07	2070
30050	2070-01-08	2070
30051	2070-01-09	2070
30052	2070-01-10	2070
30053	2070-01-13	2070
30054	2070-01-14	2070
30055	2070-01-15	2070
30056	2070-01-16	2070
30057	2070-01-17	2070
30058	2070-01-20	2070
30059	2070-01-21	2070
30060	2070-01-22	2070
30061	2070-01-23	2070
30062	2070-01-24	2070
30063	2070-01-27	2070
30064	2070-01-28	2070
30065	2070-01-29	2070
30066	2070-01-30	2070
30067	2070-01-31	2070
30068	2070-02-03	2070
30069	2070-02-04	2070
30070	2070-02-05	2070
30071	2070-02-06	2070
30072	2070-02-07	2070
30073	2070-02-10	2070
30074	2070-02-12	2070
30075	2070-02-13	2070
30076	2070-02-14	2070
30077	2070-02-17	2070
30078	2070-02-18	2070
30079	2070-02-19	2070
30080	2070-02-20	2070
30081	2070-02-21	2070
30082	2070-02-24	2070
30083	2070-02-25	2070
30084	2070-02-26	2070
30085	2070-02-27	2070
30086	2070-02-28	2070
30087	2070-03-03	2070
30088	2070-03-04	2070
30089	2070-03-05	2070
30090	2070-03-06	2070
30091	2070-03-07	2070
30092	2070-03-10	2070
30093	2070-03-11	2070
30094	2070-03-12	2070
30095	2070-03-13	2070
30096	2070-03-14	2070
30097	2070-03-17	2070
30098	2070-03-18	2070
30099	2070-03-19	2070
30100	2070-03-20	2070
30101	2070-03-21	2070
30102	2070-03-24	2070
30103	2070-03-25	2070
30104	2070-03-26	2070
30105	2070-03-27	2070
30106	2070-03-28	2070
30107	2070-03-31	2070
30108	2070-04-01	2070
30109	2070-04-02	2070
30110	2070-04-03	2070
30111	2070-04-04	2070
30112	2070-04-07	2070
30113	2070-04-08	2070
30114	2070-04-09	2070
30115	2070-04-10	2070
30116	2070-04-11	2070
30117	2070-04-14	2070
30118	2070-04-15	2070
30119	2070-04-16	2070
30120	2070-04-17	2070
30121	2070-04-18	2070
30122	2070-04-22	2070
30123	2070-04-23	2070
30124	2070-04-24	2070
30125	2070-04-25	2070
30126	2070-04-28	2070
30127	2070-04-29	2070
30128	2070-04-30	2070
30129	2070-05-02	2070
30130	2070-05-05	2070
30131	2070-05-06	2070
30132	2070-05-07	2070
30133	2070-05-08	2070
30134	2070-05-09	2070
30135	2070-05-12	2070
30136	2070-05-13	2070
30137	2070-05-14	2070
30138	2070-05-15	2070
30139	2070-05-16	2070
30140	2070-05-19	2070
30141	2070-05-20	2070
30142	2070-05-21	2070
30143	2070-05-22	2070
30144	2070-05-23	2070
30145	2070-05-26	2070
30146	2070-05-27	2070
30147	2070-05-28	2070
30148	2070-05-30	2070
30149	2070-06-02	2070
30150	2070-06-03	2070
30151	2070-06-04	2070
30152	2070-06-05	2070
30153	2070-06-06	2070
30154	2070-06-09	2070
30155	2070-06-10	2070
30156	2070-06-11	2070
30157	2070-06-12	2070
30158	2070-06-13	2070
30159	2070-06-16	2070
30160	2070-06-17	2070
30161	2070-06-18	2070
30162	2070-06-19	2070
30163	2070-06-20	2070
30164	2070-06-23	2070
30165	2070-06-24	2070
30166	2070-06-25	2070
30167	2070-06-26	2070
30168	2070-06-27	2070
30169	2070-06-30	2070
30170	2070-07-01	2070
30171	2070-07-02	2070
30172	2070-07-03	2070
30173	2070-07-04	2070
30174	2070-07-07	2070
30175	2070-07-08	2070
30176	2070-07-09	2070
30177	2070-07-10	2070
30178	2070-07-11	2070
30179	2070-07-14	2070
30180	2070-07-15	2070
30181	2070-07-16	2070
30182	2070-07-17	2070
30183	2070-07-18	2070
30184	2070-07-21	2070
30185	2070-07-22	2070
30186	2070-07-23	2070
30187	2070-07-24	2070
30188	2070-07-25	2070
30189	2070-07-28	2070
30190	2070-07-29	2070
30191	2070-07-30	2070
30192	2070-07-31	2070
30193	2070-08-01	2070
30194	2070-08-04	2070
30195	2070-08-05	2070
30196	2070-08-06	2070
30197	2070-08-07	2070
30198	2070-08-08	2070
30199	2070-08-11	2070
30200	2070-08-12	2070
30201	2070-08-13	2070
30202	2070-08-14	2070
30203	2070-08-15	2070
30204	2070-08-18	2070
30205	2070-08-19	2070
30206	2070-08-20	2070
30207	2070-08-21	2070
30208	2070-08-22	2070
30209	2070-08-25	2070
30210	2070-08-26	2070
30211	2070-08-27	2070
30212	2070-08-28	2070
30213	2070-08-29	2070
30214	2070-09-01	2070
30215	2070-09-02	2070
30216	2070-09-03	2070
30217	2070-09-04	2070
30218	2070-09-05	2070
30219	2070-09-08	2070
30220	2070-09-09	2070
30221	2070-09-10	2070
30222	2070-09-11	2070
30223	2070-09-12	2070
30224	2070-09-15	2070
30225	2070-09-16	2070
30226	2070-09-17	2070
30227	2070-09-18	2070
30228	2070-09-19	2070
30229	2070-09-22	2070
30230	2070-09-23	2070
30231	2070-09-24	2070
30232	2070-09-25	2070
30233	2070-09-26	2070
30234	2070-09-29	2070
30235	2070-09-30	2070
30236	2070-10-01	2070
30237	2070-10-02	2070
30238	2070-10-03	2070
30239	2070-10-06	2070
30240	2070-10-07	2070
30241	2070-10-08	2070
30242	2070-10-09	2070
30243	2070-10-10	2070
30244	2070-10-13	2070
30245	2070-10-14	2070
30246	2070-10-15	2070
30247	2070-10-16	2070
30248	2070-10-17	2070
30249	2070-10-20	2070
30250	2070-10-21	2070
30251	2070-10-22	2070
30252	2070-10-23	2070
30253	2070-10-24	2070
30254	2070-10-27	2070
30255	2070-10-29	2070
30256	2070-10-30	2070
30257	2070-10-31	2070
30258	2070-11-03	2070
30259	2070-11-04	2070
30260	2070-11-05	2070
30261	2070-11-06	2070
30262	2070-11-07	2070
30263	2070-11-10	2070
30264	2070-11-11	2070
30265	2070-11-12	2070
30266	2070-11-13	2070
30267	2070-11-14	2070
30268	2070-11-17	2070
30269	2070-11-18	2070
30270	2070-11-19	2070
30271	2070-11-20	2070
30272	2070-11-21	2070
30273	2070-11-24	2070
30274	2070-11-25	2070
30275	2070-11-26	2070
30276	2070-11-27	2070
30277	2070-11-28	2070
30278	2070-12-01	2070
30279	2070-12-02	2070
30280	2070-12-03	2070
30281	2070-12-04	2070
30282	2070-12-05	2070
30283	2070-12-08	2070
30284	2070-12-09	2070
30285	2070-12-10	2070
30286	2070-12-11	2070
30287	2070-12-12	2070
30288	2070-12-15	2070
30289	2070-12-16	2070
30290	2070-12-17	2070
30291	2070-12-18	2070
30292	2070-12-19	2070
30293	2070-12-22	2070
30294	2070-12-23	2070
30295	2070-12-24	2070
30296	2070-12-26	2070
30297	2070-12-29	2070
30298	2070-12-30	2070
30299	2070-12-31	2070
30300	2071-01-02	2071
30301	2071-01-05	2071
30302	2071-01-06	2071
30303	2071-01-07	2071
30304	2071-01-08	2071
30305	2071-01-09	2071
30306	2071-01-12	2071
30307	2071-01-13	2071
30308	2071-01-14	2071
30309	2071-01-15	2071
30310	2071-01-16	2071
30311	2071-01-19	2071
30312	2071-01-20	2071
30313	2071-01-21	2071
30314	2071-01-22	2071
30315	2071-01-23	2071
30316	2071-01-26	2071
30317	2071-01-27	2071
30318	2071-01-28	2071
30319	2071-01-29	2071
30320	2071-01-30	2071
30321	2071-02-02	2071
30322	2071-02-03	2071
30323	2071-02-04	2071
30324	2071-02-05	2071
30325	2071-02-06	2071
30326	2071-02-09	2071
30327	2071-02-10	2071
30328	2071-02-11	2071
30329	2071-02-12	2071
30330	2071-02-13	2071
30331	2071-02-16	2071
30332	2071-02-17	2071
30333	2071-02-18	2071
30334	2071-02-19	2071
30335	2071-02-20	2071
30336	2071-02-23	2071
30337	2071-02-24	2071
30338	2071-02-25	2071
30339	2071-02-26	2071
30340	2071-02-27	2071
30341	2071-03-02	2071
30342	2071-03-04	2071
30343	2071-03-05	2071
30344	2071-03-06	2071
30345	2071-03-09	2071
30346	2071-03-10	2071
30347	2071-03-11	2071
30348	2071-03-12	2071
30349	2071-03-13	2071
30350	2071-03-16	2071
30351	2071-03-17	2071
30352	2071-03-18	2071
30353	2071-03-19	2071
30354	2071-03-20	2071
30355	2071-03-23	2071
30356	2071-03-24	2071
30357	2071-03-25	2071
30358	2071-03-26	2071
30359	2071-03-27	2071
30360	2071-03-30	2071
30361	2071-03-31	2071
30362	2071-04-01	2071
30363	2071-04-02	2071
30364	2071-04-03	2071
30365	2071-04-06	2071
30366	2071-04-07	2071
30367	2071-04-08	2071
30368	2071-04-09	2071
30369	2071-04-10	2071
30370	2071-04-13	2071
30371	2071-04-14	2071
30372	2071-04-15	2071
30373	2071-04-16	2071
30374	2071-04-17	2071
30375	2071-04-20	2071
30376	2071-04-22	2071
30377	2071-04-23	2071
30378	2071-04-24	2071
30379	2071-04-27	2071
30380	2071-04-28	2071
30381	2071-04-29	2071
30382	2071-04-30	2071
30383	2071-05-04	2071
30384	2071-05-05	2071
30385	2071-05-06	2071
30386	2071-05-07	2071
30387	2071-05-08	2071
30388	2071-05-11	2071
30389	2071-05-12	2071
30390	2071-05-13	2071
30391	2071-05-14	2071
30392	2071-05-15	2071
30393	2071-05-18	2071
30394	2071-05-19	2071
30395	2071-05-20	2071
30396	2071-05-21	2071
30397	2071-05-22	2071
30398	2071-05-25	2071
30399	2071-05-26	2071
30400	2071-05-27	2071
30401	2071-05-28	2071
30402	2071-05-29	2071
30403	2071-06-01	2071
30404	2071-06-02	2071
30405	2071-06-03	2071
30406	2071-06-04	2071
30407	2071-06-05	2071
30408	2071-06-08	2071
30409	2071-06-09	2071
30410	2071-06-10	2071
30411	2071-06-11	2071
30412	2071-06-12	2071
30413	2071-06-15	2071
30414	2071-06-16	2071
30415	2071-06-17	2071
30416	2071-06-19	2071
30417	2071-06-22	2071
30418	2071-06-23	2071
30419	2071-06-24	2071
30420	2071-06-25	2071
30421	2071-06-26	2071
30422	2071-06-29	2071
30423	2071-06-30	2071
30424	2071-07-01	2071
30425	2071-07-02	2071
30426	2071-07-03	2071
30427	2071-07-06	2071
30428	2071-07-07	2071
30429	2071-07-08	2071
30430	2071-07-09	2071
30431	2071-07-10	2071
30432	2071-07-13	2071
30433	2071-07-14	2071
30434	2071-07-15	2071
30435	2071-07-16	2071
30436	2071-07-17	2071
30437	2071-07-20	2071
30438	2071-07-21	2071
30439	2071-07-22	2071
30440	2071-07-23	2071
30441	2071-07-24	2071
30442	2071-07-27	2071
30443	2071-07-28	2071
30444	2071-07-29	2071
30445	2071-07-30	2071
30446	2071-07-31	2071
30447	2071-08-03	2071
30448	2071-08-04	2071
30449	2071-08-05	2071
30450	2071-08-06	2071
30451	2071-08-07	2071
30452	2071-08-10	2071
30453	2071-08-11	2071
30454	2071-08-12	2071
30455	2071-08-13	2071
30456	2071-08-14	2071
30457	2071-08-17	2071
30458	2071-08-18	2071
30459	2071-08-19	2071
30460	2071-08-20	2071
30461	2071-08-21	2071
30462	2071-08-24	2071
30463	2071-08-25	2071
30464	2071-08-26	2071
30465	2071-08-27	2071
30466	2071-08-28	2071
30467	2071-08-31	2071
30468	2071-09-01	2071
30469	2071-09-02	2071
30470	2071-09-03	2071
30471	2071-09-04	2071
30472	2071-09-08	2071
30473	2071-09-09	2071
30474	2071-09-10	2071
30475	2071-09-11	2071
30476	2071-09-14	2071
30477	2071-09-15	2071
30478	2071-09-16	2071
30479	2071-09-17	2071
30480	2071-09-18	2071
30481	2071-09-21	2071
30482	2071-09-22	2071
30483	2071-09-23	2071
30484	2071-09-24	2071
30485	2071-09-25	2071
30486	2071-09-28	2071
30487	2071-09-29	2071
30488	2071-09-30	2071
30489	2071-10-01	2071
30490	2071-10-02	2071
30491	2071-10-05	2071
30492	2071-10-06	2071
30493	2071-10-07	2071
30494	2071-10-08	2071
30495	2071-10-09	2071
30496	2071-10-13	2071
30497	2071-10-14	2071
30498	2071-10-15	2071
30499	2071-10-16	2071
30500	2071-10-19	2071
30501	2071-10-20	2071
30502	2071-10-21	2071
30503	2071-10-22	2071
30504	2071-10-23	2071
30505	2071-10-26	2071
30506	2071-10-27	2071
30507	2071-10-29	2071
30508	2071-10-30	2071
30509	2071-11-03	2071
30510	2071-11-04	2071
30511	2071-11-05	2071
30512	2071-11-06	2071
30513	2071-11-09	2071
30514	2071-11-10	2071
30515	2071-11-11	2071
30516	2071-11-12	2071
30517	2071-11-13	2071
30518	2071-11-16	2071
30519	2071-11-17	2071
30520	2071-11-18	2071
30521	2071-11-19	2071
30522	2071-11-20	2071
30523	2071-11-23	2071
30524	2071-11-24	2071
30525	2071-11-25	2071
30526	2071-11-26	2071
30527	2071-11-27	2071
30528	2071-11-30	2071
30529	2071-12-01	2071
30530	2071-12-02	2071
30531	2071-12-03	2071
30532	2071-12-04	2071
30533	2071-12-07	2071
30534	2071-12-08	2071
30535	2071-12-09	2071
30536	2071-12-10	2071
30537	2071-12-11	2071
30538	2071-12-14	2071
30539	2071-12-15	2071
30540	2071-12-16	2071
30541	2071-12-17	2071
30542	2071-12-18	2071
30543	2071-12-21	2071
30544	2071-12-22	2071
30545	2071-12-23	2071
30546	2071-12-24	2071
30547	2071-12-28	2071
30548	2071-12-29	2071
30549	2071-12-30	2071
30550	2071-12-31	2071
30551	2072-01-04	2072
30552	2072-01-05	2072
30553	2072-01-06	2072
30554	2072-01-07	2072
30555	2072-01-08	2072
30556	2072-01-11	2072
30557	2072-01-12	2072
30558	2072-01-13	2072
30559	2072-01-14	2072
30560	2072-01-15	2072
30561	2072-01-18	2072
30562	2072-01-19	2072
30563	2072-01-20	2072
30564	2072-01-21	2072
30565	2072-01-22	2072
30566	2072-01-25	2072
30567	2072-01-26	2072
30568	2072-01-27	2072
30569	2072-01-28	2072
30570	2072-01-29	2072
30571	2072-02-01	2072
30572	2072-02-02	2072
30573	2072-02-03	2072
30574	2072-02-04	2072
30575	2072-02-05	2072
30576	2072-02-08	2072
30577	2072-02-09	2072
30578	2072-02-10	2072
30579	2072-02-11	2072
30580	2072-02-12	2072
30581	2072-02-15	2072
30582	2072-02-16	2072
30583	2072-02-17	2072
30584	2072-02-18	2072
30585	2072-02-19	2072
30586	2072-02-22	2072
30587	2072-02-24	2072
30588	2072-02-25	2072
30589	2072-02-26	2072
30590	2072-02-29	2072
30591	2072-03-01	2072
30592	2072-03-02	2072
30593	2072-03-03	2072
30594	2072-03-04	2072
30595	2072-03-07	2072
30596	2072-03-08	2072
30597	2072-03-09	2072
30598	2072-03-10	2072
30599	2072-03-11	2072
30600	2072-03-14	2072
30601	2072-03-15	2072
30602	2072-03-16	2072
30603	2072-03-17	2072
30604	2072-03-18	2072
30605	2072-03-21	2072
30606	2072-03-22	2072
30607	2072-03-23	2072
30608	2072-03-24	2072
30609	2072-03-25	2072
30610	2072-03-28	2072
30611	2072-03-29	2072
30612	2072-03-30	2072
30613	2072-03-31	2072
30614	2072-04-01	2072
30615	2072-04-04	2072
30616	2072-04-05	2072
30617	2072-04-06	2072
30618	2072-04-07	2072
30619	2072-04-08	2072
30620	2072-04-11	2072
30621	2072-04-12	2072
30622	2072-04-13	2072
30623	2072-04-14	2072
30624	2072-04-15	2072
30625	2072-04-18	2072
30626	2072-04-19	2072
30627	2072-04-20	2072
30628	2072-04-22	2072
30629	2072-04-25	2072
30630	2072-04-26	2072
30631	2072-04-27	2072
30632	2072-04-28	2072
30633	2072-04-29	2072
30634	2072-05-02	2072
30635	2072-05-03	2072
30636	2072-05-04	2072
30637	2072-05-05	2072
30638	2072-05-06	2072
30639	2072-05-09	2072
30640	2072-05-10	2072
30641	2072-05-11	2072
30642	2072-05-12	2072
30643	2072-05-13	2072
30644	2072-05-16	2072
30645	2072-05-17	2072
30646	2072-05-18	2072
30647	2072-05-19	2072
30648	2072-05-20	2072
30649	2072-05-23	2072
30650	2072-05-24	2072
30651	2072-05-25	2072
30652	2072-05-26	2072
30653	2072-05-27	2072
30654	2072-05-30	2072
30655	2072-05-31	2072
30656	2072-06-01	2072
30657	2072-06-02	2072
30658	2072-06-03	2072
30659	2072-06-06	2072
30660	2072-06-07	2072
30661	2072-06-08	2072
30662	2072-06-10	2072
30663	2072-06-13	2072
30664	2072-06-14	2072
30665	2072-06-15	2072
30666	2072-06-16	2072
30667	2072-06-17	2072
30668	2072-06-20	2072
30669	2072-06-21	2072
30670	2072-06-22	2072
30671	2072-06-23	2072
30672	2072-06-24	2072
30673	2072-06-27	2072
30674	2072-06-28	2072
30675	2072-06-29	2072
30676	2072-06-30	2072
30677	2072-07-01	2072
30678	2072-07-04	2072
30679	2072-07-05	2072
30680	2072-07-06	2072
30681	2072-07-07	2072
30682	2072-07-08	2072
30683	2072-07-11	2072
30684	2072-07-12	2072
30685	2072-07-13	2072
30686	2072-07-14	2072
30687	2072-07-15	2072
30688	2072-07-18	2072
30689	2072-07-19	2072
30690	2072-07-20	2072
30691	2072-07-21	2072
30692	2072-07-22	2072
30693	2072-07-25	2072
30694	2072-07-26	2072
30695	2072-07-27	2072
30696	2072-07-28	2072
30697	2072-07-29	2072
30698	2072-08-01	2072
30699	2072-08-02	2072
30700	2072-08-03	2072
30701	2072-08-04	2072
30702	2072-08-05	2072
30703	2072-08-08	2072
30704	2072-08-09	2072
30705	2072-08-10	2072
30706	2072-08-11	2072
30707	2072-08-12	2072
30708	2072-08-15	2072
30709	2072-08-16	2072
30710	2072-08-17	2072
30711	2072-08-18	2072
30712	2072-08-19	2072
30713	2072-08-22	2072
30714	2072-08-23	2072
30715	2072-08-24	2072
30716	2072-08-25	2072
30717	2072-08-26	2072
30718	2072-08-29	2072
30719	2072-08-30	2072
30720	2072-08-31	2072
30721	2072-09-01	2072
30722	2072-09-02	2072
30723	2072-09-05	2072
30724	2072-09-06	2072
30725	2072-09-08	2072
30726	2072-09-09	2072
30727	2072-09-12	2072
30728	2072-09-13	2072
30729	2072-09-14	2072
30730	2072-09-15	2072
30731	2072-09-16	2072
30732	2072-09-19	2072
30733	2072-09-20	2072
30734	2072-09-21	2072
30735	2072-09-22	2072
30736	2072-09-23	2072
30737	2072-09-26	2072
30738	2072-09-27	2072
30739	2072-09-28	2072
30740	2072-09-29	2072
30741	2072-09-30	2072
30742	2072-10-03	2072
30743	2072-10-04	2072
30744	2072-10-05	2072
30745	2072-10-06	2072
30746	2072-10-07	2072
30747	2072-10-10	2072
30748	2072-10-11	2072
30749	2072-10-13	2072
30750	2072-10-14	2072
30751	2072-10-17	2072
30752	2072-10-18	2072
30753	2072-10-19	2072
30754	2072-10-20	2072
30755	2072-10-21	2072
30756	2072-10-24	2072
30757	2072-10-25	2072
30758	2072-10-26	2072
30759	2072-10-27	2072
30760	2072-10-31	2072
30761	2072-11-01	2072
30762	2072-11-03	2072
30763	2072-11-04	2072
30764	2072-11-07	2072
30765	2072-11-08	2072
30766	2072-11-09	2072
30767	2072-11-10	2072
30768	2072-11-11	2072
30769	2072-11-14	2072
30770	2072-11-16	2072
30771	2072-11-17	2072
30772	2072-11-18	2072
30773	2072-11-21	2072
30774	2072-11-22	2072
30775	2072-11-23	2072
30776	2072-11-24	2072
30777	2072-11-25	2072
30778	2072-11-28	2072
30779	2072-11-29	2072
30780	2072-11-30	2072
30781	2072-12-01	2072
30782	2072-12-02	2072
30783	2072-12-05	2072
30784	2072-12-06	2072
30785	2072-12-07	2072
30786	2072-12-08	2072
30787	2072-12-09	2072
30788	2072-12-12	2072
30789	2072-12-13	2072
30790	2072-12-14	2072
30791	2072-12-15	2072
30792	2072-12-16	2072
30793	2072-12-19	2072
30794	2072-12-20	2072
30795	2072-12-21	2072
30796	2072-12-22	2072
30797	2072-12-23	2072
30798	2072-12-26	2072
30799	2072-12-27	2072
30800	2072-12-28	2072
30801	2072-12-29	2072
30802	2072-12-30	2072
30803	2073-01-02	2073
30804	2073-01-03	2073
30805	2073-01-04	2073
30806	2073-01-05	2073
30807	2073-01-06	2073
30808	2073-01-09	2073
30809	2073-01-10	2073
30810	2073-01-11	2073
30811	2073-01-12	2073
30812	2073-01-13	2073
30813	2073-01-16	2073
30814	2073-01-17	2073
30815	2073-01-18	2073
30816	2073-01-19	2073
30817	2073-01-20	2073
30818	2073-01-23	2073
30819	2073-01-24	2073
30820	2073-01-25	2073
30821	2073-01-26	2073
30822	2073-01-27	2073
30823	2073-01-30	2073
30824	2073-01-31	2073
30825	2073-02-01	2073
30826	2073-02-02	2073
30827	2073-02-03	2073
30828	2073-02-06	2073
30829	2073-02-08	2073
30830	2073-02-09	2073
30831	2073-02-10	2073
30832	2073-02-13	2073
30833	2073-02-14	2073
30834	2073-02-15	2073
30835	2073-02-16	2073
30836	2073-02-17	2073
30837	2073-02-20	2073
30838	2073-02-21	2073
30839	2073-02-22	2073
30840	2073-02-23	2073
30841	2073-02-24	2073
30842	2073-02-27	2073
30843	2073-02-28	2073
30844	2073-03-01	2073
30845	2073-03-02	2073
30846	2073-03-03	2073
30847	2073-03-06	2073
30848	2073-03-07	2073
30849	2073-03-08	2073
30850	2073-03-09	2073
30851	2073-03-10	2073
30852	2073-03-13	2073
30853	2073-03-14	2073
30854	2073-03-15	2073
30855	2073-03-16	2073
30856	2073-03-17	2073
30857	2073-03-20	2073
30858	2073-03-21	2073
30859	2073-03-22	2073
30860	2073-03-23	2073
30861	2073-03-24	2073
30862	2073-03-27	2073
30863	2073-03-28	2073
30864	2073-03-29	2073
30865	2073-03-30	2073
30866	2073-03-31	2073
30867	2073-04-03	2073
30868	2073-04-04	2073
30869	2073-04-05	2073
30870	2073-04-06	2073
30871	2073-04-07	2073
30872	2073-04-10	2073
30873	2073-04-11	2073
30874	2073-04-12	2073
30875	2073-04-13	2073
30876	2073-04-14	2073
30877	2073-04-17	2073
30878	2073-04-18	2073
30879	2073-04-19	2073
30880	2073-04-20	2073
30881	2073-04-24	2073
30882	2073-04-25	2073
30883	2073-04-26	2073
30884	2073-04-27	2073
30885	2073-04-28	2073
30886	2073-05-02	2073
30887	2073-05-03	2073
30888	2073-05-04	2073
30889	2073-05-05	2073
30890	2073-05-08	2073
30891	2073-05-09	2073
30892	2073-05-10	2073
30893	2073-05-11	2073
30894	2073-05-12	2073
30895	2073-05-15	2073
30896	2073-05-16	2073
30897	2073-05-17	2073
30898	2073-05-18	2073
30899	2073-05-19	2073
30900	2073-05-22	2073
30901	2073-05-23	2073
30902	2073-05-24	2073
30903	2073-05-26	2073
30904	2073-05-29	2073
30905	2073-05-30	2073
30906	2073-05-31	2073
30907	2073-06-01	2073
30908	2073-06-02	2073
30909	2073-06-05	2073
30910	2073-06-06	2073
30911	2073-06-07	2073
30912	2073-06-08	2073
30913	2073-06-09	2073
30914	2073-06-12	2073
30915	2073-06-13	2073
30916	2073-06-14	2073
30917	2073-06-15	2073
30918	2073-06-16	2073
30919	2073-06-19	2073
30920	2073-06-20	2073
30921	2073-06-21	2073
30922	2073-06-22	2073
30923	2073-06-23	2073
30924	2073-06-26	2073
30925	2073-06-27	2073
30926	2073-06-28	2073
30927	2073-06-29	2073
30928	2073-06-30	2073
30929	2073-07-03	2073
30930	2073-07-04	2073
30931	2073-07-05	2073
30932	2073-07-06	2073
30933	2073-07-07	2073
30934	2073-07-10	2073
30935	2073-07-11	2073
30936	2073-07-12	2073
30937	2073-07-13	2073
30938	2073-07-14	2073
30939	2073-07-17	2073
30940	2073-07-18	2073
30941	2073-07-19	2073
30942	2073-07-20	2073
30943	2073-07-21	2073
30944	2073-07-24	2073
30945	2073-07-25	2073
30946	2073-07-26	2073
30947	2073-07-27	2073
30948	2073-07-28	2073
30949	2073-07-31	2073
30950	2073-08-01	2073
30951	2073-08-02	2073
30952	2073-08-03	2073
30953	2073-08-04	2073
30954	2073-08-07	2073
30955	2073-08-08	2073
30956	2073-08-09	2073
30957	2073-08-10	2073
30958	2073-08-11	2073
30959	2073-08-14	2073
30960	2073-08-15	2073
30961	2073-08-16	2073
30962	2073-08-17	2073
30963	2073-08-18	2073
30964	2073-08-21	2073
30965	2073-08-22	2073
30966	2073-08-23	2073
30967	2073-08-24	2073
30968	2073-08-25	2073
30969	2073-08-28	2073
30970	2073-08-29	2073
30971	2073-08-30	2073
30972	2073-08-31	2073
30973	2073-09-01	2073
30974	2073-09-04	2073
30975	2073-09-05	2073
30976	2073-09-06	2073
30977	2073-09-08	2073
30978	2073-09-11	2073
30979	2073-09-12	2073
30980	2073-09-13	2073
30981	2073-09-14	2073
30982	2073-09-15	2073
30983	2073-09-18	2073
30984	2073-09-19	2073
30985	2073-09-20	2073
30986	2073-09-21	2073
30987	2073-09-22	2073
30988	2073-09-25	2073
30989	2073-09-26	2073
30990	2073-09-27	2073
30991	2073-09-28	2073
30992	2073-09-29	2073
30993	2073-10-02	2073
30994	2073-10-03	2073
30995	2073-10-04	2073
30996	2073-10-05	2073
30997	2073-10-06	2073
30998	2073-10-09	2073
30999	2073-10-10	2073
31000	2073-10-11	2073
31001	2073-10-13	2073
31002	2073-10-16	2073
31003	2073-10-17	2073
31004	2073-10-18	2073
31005	2073-10-19	2073
31006	2073-10-20	2073
31007	2073-10-23	2073
31008	2073-10-24	2073
31009	2073-10-25	2073
31010	2073-10-26	2073
31011	2073-10-27	2073
31012	2073-10-30	2073
31013	2073-10-31	2073
31014	2073-11-01	2073
31015	2073-11-03	2073
31016	2073-11-06	2073
31017	2073-11-07	2073
31018	2073-11-08	2073
31019	2073-11-09	2073
31020	2073-11-10	2073
31021	2073-11-13	2073
31022	2073-11-14	2073
31023	2073-11-16	2073
31024	2073-11-17	2073
31025	2073-11-20	2073
31026	2073-11-21	2073
31027	2073-11-22	2073
31028	2073-11-23	2073
31029	2073-11-24	2073
31030	2073-11-27	2073
31031	2073-11-28	2073
31032	2073-11-29	2073
31033	2073-11-30	2073
31034	2073-12-01	2073
31035	2073-12-04	2073
31036	2073-12-05	2073
31037	2073-12-06	2073
31038	2073-12-07	2073
31039	2073-12-08	2073
31040	2073-12-11	2073
31041	2073-12-12	2073
31042	2073-12-13	2073
31043	2073-12-14	2073
31044	2073-12-15	2073
31045	2073-12-18	2073
31046	2073-12-19	2073
31047	2073-12-20	2073
31048	2073-12-21	2073
31049	2073-12-22	2073
31050	2073-12-26	2073
31051	2073-12-27	2073
31052	2073-12-28	2073
31053	2073-12-29	2073
31054	2074-01-02	2074
31055	2074-01-03	2074
31056	2074-01-04	2074
31057	2074-01-05	2074
31058	2074-01-08	2074
31059	2074-01-09	2074
31060	2074-01-10	2074
31061	2074-01-11	2074
31062	2074-01-12	2074
31063	2074-01-15	2074
31064	2074-01-16	2074
31065	2074-01-17	2074
31066	2074-01-18	2074
31067	2074-01-19	2074
31068	2074-01-22	2074
31069	2074-01-23	2074
31070	2074-01-24	2074
31071	2074-01-25	2074
31072	2074-01-26	2074
31073	2074-01-29	2074
31074	2074-01-30	2074
31075	2074-01-31	2074
31076	2074-02-01	2074
31077	2074-02-02	2074
31078	2074-02-05	2074
31079	2074-02-06	2074
31080	2074-02-07	2074
31081	2074-02-08	2074
31082	2074-02-09	2074
31083	2074-02-12	2074
31084	2074-02-13	2074
31085	2074-02-14	2074
31086	2074-02-15	2074
31087	2074-02-16	2074
31088	2074-02-19	2074
31089	2074-02-20	2074
31090	2074-02-21	2074
31091	2074-02-22	2074
31092	2074-02-23	2074
31093	2074-02-26	2074
31094	2074-02-28	2074
31095	2074-03-01	2074
31096	2074-03-02	2074
31097	2074-03-05	2074
31098	2074-03-06	2074
31099	2074-03-07	2074
31100	2074-03-08	2074
31101	2074-03-09	2074
31102	2074-03-12	2074
31103	2074-03-13	2074
31104	2074-03-14	2074
31105	2074-03-15	2074
31106	2074-03-16	2074
31107	2074-03-19	2074
31108	2074-03-20	2074
31109	2074-03-21	2074
31110	2074-03-22	2074
31111	2074-03-23	2074
31112	2074-03-26	2074
31113	2074-03-27	2074
31114	2074-03-28	2074
31115	2074-03-29	2074
31116	2074-03-30	2074
31117	2074-04-02	2074
31118	2074-04-03	2074
31119	2074-04-04	2074
31120	2074-04-05	2074
31121	2074-04-06	2074
31122	2074-04-09	2074
31123	2074-04-10	2074
31124	2074-04-11	2074
31125	2074-04-12	2074
31126	2074-04-13	2074
31127	2074-04-16	2074
31128	2074-04-17	2074
31129	2074-04-18	2074
31130	2074-04-19	2074
31131	2074-04-20	2074
31132	2074-04-23	2074
31133	2074-04-24	2074
31134	2074-04-25	2074
31135	2074-04-26	2074
31136	2074-04-27	2074
31137	2074-04-30	2074
31138	2074-05-02	2074
31139	2074-05-03	2074
31140	2074-05-04	2074
31141	2074-05-07	2074
31142	2074-05-08	2074
31143	2074-05-09	2074
31144	2074-05-10	2074
31145	2074-05-11	2074
31146	2074-05-14	2074
31147	2074-05-15	2074
31148	2074-05-16	2074
31149	2074-05-17	2074
31150	2074-05-18	2074
31151	2074-05-21	2074
31152	2074-05-22	2074
31153	2074-05-23	2074
31154	2074-05-24	2074
31155	2074-05-25	2074
31156	2074-05-28	2074
31157	2074-05-29	2074
31158	2074-05-30	2074
31159	2074-05-31	2074
31160	2074-06-01	2074
31161	2074-06-04	2074
31162	2074-06-05	2074
31163	2074-06-06	2074
31164	2074-06-07	2074
31165	2074-06-08	2074
31166	2074-06-11	2074
31167	2074-06-12	2074
31168	2074-06-13	2074
31169	2074-06-15	2074
31170	2074-06-18	2074
31171	2074-06-19	2074
31172	2074-06-20	2074
31173	2074-06-21	2074
31174	2074-06-22	2074
31175	2074-06-25	2074
31176	2074-06-26	2074
31177	2074-06-27	2074
31178	2074-06-28	2074
31179	2074-06-29	2074
31180	2074-07-02	2074
31181	2074-07-03	2074
31182	2074-07-04	2074
31183	2074-07-05	2074
31184	2074-07-06	2074
31185	2074-07-09	2074
31186	2074-07-10	2074
31187	2074-07-11	2074
31188	2074-07-12	2074
31189	2074-07-13	2074
31190	2074-07-16	2074
31191	2074-07-17	2074
31192	2074-07-18	2074
31193	2074-07-19	2074
31194	2074-07-20	2074
31195	2074-07-23	2074
31196	2074-07-24	2074
31197	2074-07-25	2074
31198	2074-07-26	2074
31199	2074-07-27	2074
31200	2074-07-30	2074
31201	2074-07-31	2074
31202	2074-08-01	2074
31203	2074-08-02	2074
31204	2074-08-03	2074
31205	2074-08-06	2074
31206	2074-08-07	2074
31207	2074-08-08	2074
31208	2074-08-09	2074
31209	2074-08-10	2074
31210	2074-08-13	2074
31211	2074-08-14	2074
31212	2074-08-15	2074
31213	2074-08-16	2074
31214	2074-08-17	2074
31215	2074-08-20	2074
31216	2074-08-21	2074
31217	2074-08-22	2074
31218	2074-08-23	2074
31219	2074-08-24	2074
31220	2074-08-27	2074
31221	2074-08-28	2074
31222	2074-08-29	2074
31223	2074-08-30	2074
31224	2074-08-31	2074
31225	2074-09-03	2074
31226	2074-09-04	2074
31227	2074-09-05	2074
31228	2074-09-06	2074
31229	2074-09-10	2074
31230	2074-09-11	2074
31231	2074-09-12	2074
31232	2074-09-13	2074
31233	2074-09-14	2074
31234	2074-09-17	2074
31235	2074-09-18	2074
31236	2074-09-19	2074
31237	2074-09-20	2074
31238	2074-09-21	2074
31239	2074-09-24	2074
31240	2074-09-25	2074
31241	2074-09-26	2074
31242	2074-09-27	2074
31243	2074-09-28	2074
31244	2074-10-01	2074
31245	2074-10-02	2074
31246	2074-10-03	2074
31247	2074-10-04	2074
31248	2074-10-05	2074
31249	2074-10-08	2074
31250	2074-10-09	2074
31251	2074-10-10	2074
31252	2074-10-11	2074
31253	2074-10-15	2074
31254	2074-10-16	2074
31255	2074-10-17	2074
31256	2074-10-18	2074
31257	2074-10-19	2074
31258	2074-10-22	2074
31259	2074-10-23	2074
31260	2074-10-24	2074
31261	2074-10-25	2074
31262	2074-10-26	2074
31263	2074-10-29	2074
31264	2074-10-30	2074
31265	2074-10-31	2074
31266	2074-11-01	2074
31267	2074-11-05	2074
31268	2074-11-06	2074
31269	2074-11-07	2074
31270	2074-11-08	2074
31271	2074-11-09	2074
31272	2074-11-12	2074
31273	2074-11-13	2074
31274	2074-11-14	2074
31275	2074-11-16	2074
31276	2074-11-19	2074
31277	2074-11-20	2074
31278	2074-11-21	2074
31279	2074-11-22	2074
31280	2074-11-23	2074
31281	2074-11-26	2074
31282	2074-11-27	2074
31283	2074-11-28	2074
31284	2074-11-29	2074
31285	2074-11-30	2074
31286	2074-12-03	2074
31287	2074-12-04	2074
31288	2074-12-05	2074
31289	2074-12-06	2074
31290	2074-12-07	2074
31291	2074-12-10	2074
31292	2074-12-11	2074
31293	2074-12-12	2074
31294	2074-12-13	2074
31295	2074-12-14	2074
31296	2074-12-17	2074
31297	2074-12-18	2074
31298	2074-12-19	2074
31299	2074-12-20	2074
31300	2074-12-21	2074
31301	2074-12-24	2074
31302	2074-12-26	2074
31303	2074-12-27	2074
31304	2074-12-28	2074
31305	2074-12-31	2074
31306	2075-01-02	2075
31307	2075-01-03	2075
31308	2075-01-04	2075
31309	2075-01-07	2075
31310	2075-01-08	2075
31311	2075-01-09	2075
31312	2075-01-10	2075
31313	2075-01-11	2075
31314	2075-01-14	2075
31315	2075-01-15	2075
31316	2075-01-16	2075
31317	2075-01-17	2075
31318	2075-01-18	2075
31319	2075-01-21	2075
31320	2075-01-22	2075
31321	2075-01-23	2075
31322	2075-01-24	2075
31323	2075-01-25	2075
31324	2075-01-28	2075
31325	2075-01-29	2075
31326	2075-01-30	2075
31327	2075-01-31	2075
31328	2075-02-01	2075
31329	2075-02-04	2075
31330	2075-02-05	2075
31331	2075-02-06	2075
31332	2075-02-07	2075
31333	2075-02-08	2075
31334	2075-02-11	2075
31335	2075-02-12	2075
31336	2075-02-13	2075
31337	2075-02-14	2075
31338	2075-02-15	2075
31339	2075-02-18	2075
31340	2075-02-20	2075
31341	2075-02-21	2075
31342	2075-02-22	2075
31343	2075-02-25	2075
31344	2075-02-26	2075
31345	2075-02-27	2075
31346	2075-02-28	2075
31347	2075-03-01	2075
31348	2075-03-04	2075
31349	2075-03-05	2075
31350	2075-03-06	2075
31351	2075-03-07	2075
31352	2075-03-08	2075
31353	2075-03-11	2075
31354	2075-03-12	2075
31355	2075-03-13	2075
31356	2075-03-14	2075
31357	2075-03-15	2075
31358	2075-03-18	2075
31359	2075-03-19	2075
31360	2075-03-20	2075
31361	2075-03-21	2075
31362	2075-03-22	2075
31363	2075-03-25	2075
31364	2075-03-26	2075
31365	2075-03-27	2075
31366	2075-03-28	2075
31367	2075-03-29	2075
31368	2075-04-01	2075
31369	2075-04-02	2075
31370	2075-04-03	2075
31371	2075-04-04	2075
31372	2075-04-05	2075
31373	2075-04-08	2075
31374	2075-04-09	2075
31375	2075-04-10	2075
31376	2075-04-11	2075
31377	2075-04-12	2075
31378	2075-04-15	2075
31379	2075-04-16	2075
31380	2075-04-17	2075
31381	2075-04-18	2075
31382	2075-04-19	2075
31383	2075-04-22	2075
31384	2075-04-23	2075
31385	2075-04-24	2075
31386	2075-04-25	2075
31387	2075-04-26	2075
31388	2075-04-29	2075
31389	2075-04-30	2075
31390	2075-05-02	2075
31391	2075-05-03	2075
31392	2075-05-06	2075
31393	2075-05-07	2075
31394	2075-05-08	2075
31395	2075-05-09	2075
31396	2075-05-10	2075
31397	2075-05-13	2075
31398	2075-05-14	2075
31399	2075-05-15	2075
31400	2075-05-16	2075
31401	2075-05-17	2075
31402	2075-05-20	2075
31403	2075-05-21	2075
31404	2075-05-22	2075
31405	2075-05-23	2075
31406	2075-05-24	2075
31407	2075-05-27	2075
31408	2075-05-28	2075
31409	2075-05-29	2075
31410	2075-05-30	2075
31411	2075-05-31	2075
31412	2075-06-03	2075
31413	2075-06-04	2075
31414	2075-06-05	2075
31415	2075-06-07	2075
31416	2075-06-10	2075
31417	2075-06-11	2075
31418	2075-06-12	2075
31419	2075-06-13	2075
31420	2075-06-14	2075
31421	2075-06-17	2075
31422	2075-06-18	2075
31423	2075-06-19	2075
31424	2075-06-20	2075
31425	2075-06-21	2075
31426	2075-06-24	2075
31427	2075-06-25	2075
31428	2075-06-26	2075
31429	2075-06-27	2075
31430	2075-06-28	2075
31431	2075-07-01	2075
31432	2075-07-02	2075
31433	2075-07-03	2075
31434	2075-07-04	2075
31435	2075-07-05	2075
31436	2075-07-08	2075
31437	2075-07-09	2075
31438	2075-07-10	2075
31439	2075-07-11	2075
31440	2075-07-12	2075
31441	2075-07-15	2075
31442	2075-07-16	2075
31443	2075-07-17	2075
31444	2075-07-18	2075
31445	2075-07-19	2075
31446	2075-07-22	2075
31447	2075-07-23	2075
31448	2075-07-24	2075
31449	2075-07-25	2075
31450	2075-07-26	2075
31451	2075-07-29	2075
31452	2075-07-30	2075
31453	2075-07-31	2075
31454	2075-08-01	2075
31455	2075-08-02	2075
31456	2075-08-05	2075
31457	2075-08-06	2075
31458	2075-08-07	2075
31459	2075-08-08	2075
31460	2075-08-09	2075
31461	2075-08-12	2075
31462	2075-08-13	2075
31463	2075-08-14	2075
31464	2075-08-15	2075
31465	2075-08-16	2075
31466	2075-08-19	2075
31467	2075-08-20	2075
31468	2075-08-21	2075
31469	2075-08-22	2075
31470	2075-08-23	2075
31471	2075-08-26	2075
31472	2075-08-27	2075
31473	2075-08-28	2075
31474	2075-08-29	2075
31475	2075-08-30	2075
31476	2075-09-02	2075
31477	2075-09-03	2075
31478	2075-09-04	2075
31479	2075-09-05	2075
31480	2075-09-06	2075
31481	2075-09-09	2075
31482	2075-09-10	2075
31483	2075-09-11	2075
31484	2075-09-12	2075
31485	2075-09-13	2075
31486	2075-09-16	2075
31487	2075-09-17	2075
31488	2075-09-18	2075
31489	2075-09-19	2075
31490	2075-09-20	2075
31491	2075-09-23	2075
31492	2075-09-24	2075
31493	2075-09-25	2075
31494	2075-09-26	2075
31495	2075-09-27	2075
31496	2075-09-30	2075
31497	2075-10-01	2075
31498	2075-10-02	2075
31499	2075-10-03	2075
31500	2075-10-04	2075
31501	2075-10-07	2075
31502	2075-10-08	2075
31503	2075-10-09	2075
31504	2075-10-10	2075
31505	2075-10-11	2075
31506	2075-10-14	2075
31507	2075-10-15	2075
31508	2075-10-16	2075
31509	2075-10-17	2075
31510	2075-10-18	2075
31511	2075-10-21	2075
31512	2075-10-22	2075
31513	2075-10-23	2075
31514	2075-10-24	2075
31515	2075-10-25	2075
31516	2075-10-29	2075
31517	2075-10-30	2075
31518	2075-10-31	2075
31519	2075-11-01	2075
31520	2075-11-04	2075
31521	2075-11-05	2075
31522	2075-11-06	2075
31523	2075-11-07	2075
31524	2075-11-08	2075
31525	2075-11-11	2075
31526	2075-11-12	2075
31527	2075-11-13	2075
31528	2075-11-14	2075
31529	2075-11-18	2075
31530	2075-11-19	2075
31531	2075-11-20	2075
31532	2075-11-21	2075
31533	2075-11-22	2075
31534	2075-11-25	2075
31535	2075-11-26	2075
31536	2075-11-27	2075
31537	2075-11-28	2075
31538	2075-11-29	2075
31539	2075-12-02	2075
31540	2075-12-03	2075
31541	2075-12-04	2075
31542	2075-12-05	2075
31543	2075-12-06	2075
31544	2075-12-09	2075
31545	2075-12-10	2075
31546	2075-12-11	2075
31547	2075-12-12	2075
31548	2075-12-13	2075
31549	2075-12-16	2075
31550	2075-12-17	2075
31551	2075-12-18	2075
31552	2075-12-19	2075
31553	2075-12-20	2075
31554	2075-12-23	2075
31555	2075-12-24	2075
31556	2075-12-26	2075
31557	2075-12-27	2075
31558	2075-12-30	2075
31559	2075-12-31	2075
31560	2076-01-02	2076
31561	2076-01-03	2076
31562	2076-01-06	2076
31563	2076-01-07	2076
31564	2076-01-08	2076
31565	2076-01-09	2076
31566	2076-01-10	2076
31567	2076-01-13	2076
31568	2076-01-14	2076
31569	2076-01-15	2076
31570	2076-01-16	2076
31571	2076-01-17	2076
31572	2076-01-20	2076
31573	2076-01-21	2076
31574	2076-01-22	2076
31575	2076-01-23	2076
31576	2076-01-24	2076
31577	2076-01-27	2076
31578	2076-01-28	2076
31579	2076-01-29	2076
31580	2076-01-30	2076
31581	2076-01-31	2076
31582	2076-02-03	2076
31583	2076-02-04	2076
31584	2076-02-05	2076
31585	2076-02-06	2076
31586	2076-02-07	2076
31587	2076-02-10	2076
31588	2076-02-11	2076
31589	2076-02-12	2076
31590	2076-02-13	2076
31591	2076-02-14	2076
31592	2076-02-17	2076
31593	2076-02-18	2076
31594	2076-02-19	2076
31595	2076-02-20	2076
31596	2076-02-21	2076
31597	2076-02-24	2076
31598	2076-02-25	2076
31599	2076-02-26	2076
31600	2076-02-27	2076
31601	2076-02-28	2076
31602	2076-03-02	2076
31603	2076-03-04	2076
31604	2076-03-05	2076
31605	2076-03-06	2076
31606	2076-03-09	2076
31607	2076-03-10	2076
31608	2076-03-11	2076
31609	2076-03-12	2076
31610	2076-03-13	2076
31611	2076-03-16	2076
31612	2076-03-17	2076
31613	2076-03-18	2076
31614	2076-03-19	2076
31615	2076-03-20	2076
31616	2076-03-23	2076
31617	2076-03-24	2076
31618	2076-03-25	2076
31619	2076-03-26	2076
31620	2076-03-27	2076
31621	2076-03-30	2076
31622	2076-03-31	2076
31623	2076-04-01	2076
31624	2076-04-02	2076
31625	2076-04-03	2076
31626	2076-04-06	2076
31627	2076-04-07	2076
31628	2076-04-08	2076
31629	2076-04-09	2076
31630	2076-04-10	2076
31631	2076-04-13	2076
31632	2076-04-14	2076
31633	2076-04-15	2076
31634	2076-04-16	2076
31635	2076-04-17	2076
31636	2076-04-20	2076
31637	2076-04-22	2076
31638	2076-04-23	2076
31639	2076-04-24	2076
31640	2076-04-27	2076
31641	2076-04-28	2076
31642	2076-04-29	2076
31643	2076-04-30	2076
31644	2076-05-04	2076
31645	2076-05-05	2076
31646	2076-05-06	2076
31647	2076-05-07	2076
31648	2076-05-08	2076
31649	2076-05-11	2076
31650	2076-05-12	2076
31651	2076-05-13	2076
31652	2076-05-14	2076
31653	2076-05-15	2076
31654	2076-05-18	2076
31655	2076-05-19	2076
31656	2076-05-20	2076
31657	2076-05-21	2076
31658	2076-05-22	2076
31659	2076-05-25	2076
31660	2076-05-26	2076
31661	2076-05-27	2076
31662	2076-05-28	2076
31663	2076-05-29	2076
31664	2076-06-01	2076
31665	2076-06-02	2076
31666	2076-06-03	2076
31667	2076-06-04	2076
31668	2076-06-05	2076
31669	2076-06-08	2076
31670	2076-06-09	2076
31671	2076-06-10	2076
31672	2076-06-11	2076
31673	2076-06-12	2076
31674	2076-06-15	2076
31675	2076-06-16	2076
31676	2076-06-17	2076
31677	2076-06-19	2076
31678	2076-06-22	2076
31679	2076-06-23	2076
31680	2076-06-24	2076
31681	2076-06-25	2076
31682	2076-06-26	2076
31683	2076-06-29	2076
31684	2076-06-30	2076
31685	2076-07-01	2076
31686	2076-07-02	2076
31687	2076-07-03	2076
31688	2076-07-06	2076
31689	2076-07-07	2076
31690	2076-07-08	2076
31691	2076-07-09	2076
31692	2076-07-10	2076
31693	2076-07-13	2076
31694	2076-07-14	2076
31695	2076-07-15	2076
31696	2076-07-16	2076
31697	2076-07-17	2076
31698	2076-07-20	2076
31699	2076-07-21	2076
31700	2076-07-22	2076
31701	2076-07-23	2076
31702	2076-07-24	2076
31703	2076-07-27	2076
31704	2076-07-28	2076
31705	2076-07-29	2076
31706	2076-07-30	2076
31707	2076-07-31	2076
31708	2076-08-03	2076
31709	2076-08-04	2076
31710	2076-08-05	2076
31711	2076-08-06	2076
31712	2076-08-07	2076
31713	2076-08-10	2076
31714	2076-08-11	2076
31715	2076-08-12	2076
31716	2076-08-13	2076
31717	2076-08-14	2076
31718	2076-08-17	2076
31719	2076-08-18	2076
31720	2076-08-19	2076
31721	2076-08-20	2076
31722	2076-08-21	2076
31723	2076-08-24	2076
31724	2076-08-25	2076
31725	2076-08-26	2076
31726	2076-08-27	2076
31727	2076-08-28	2076
31728	2076-08-31	2076
31729	2076-09-01	2076
31730	2076-09-02	2076
31731	2076-09-03	2076
31732	2076-09-04	2076
31733	2076-09-08	2076
31734	2076-09-09	2076
31735	2076-09-10	2076
31736	2076-09-11	2076
31737	2076-09-14	2076
31738	2076-09-15	2076
31739	2076-09-16	2076
31740	2076-09-17	2076
31741	2076-09-18	2076
31742	2076-09-21	2076
31743	2076-09-22	2076
31744	2076-09-23	2076
31745	2076-09-24	2076
31746	2076-09-25	2076
31747	2076-09-28	2076
31748	2076-09-29	2076
31749	2076-09-30	2076
31750	2076-10-01	2076
31751	2076-10-02	2076
31752	2076-10-05	2076
31753	2076-10-06	2076
31754	2076-10-07	2076
31755	2076-10-08	2076
31756	2076-10-09	2076
31757	2076-10-13	2076
31758	2076-10-14	2076
31759	2076-10-15	2076
31760	2076-10-16	2076
31761	2076-10-19	2076
31762	2076-10-20	2076
31763	2076-10-21	2076
31764	2076-10-22	2076
31765	2076-10-23	2076
31766	2076-10-26	2076
31767	2076-10-27	2076
31768	2076-10-29	2076
31769	2076-10-30	2076
31770	2076-11-03	2076
31771	2076-11-04	2076
31772	2076-11-05	2076
31773	2076-11-06	2076
31774	2076-11-09	2076
31775	2076-11-10	2076
31776	2076-11-11	2076
31777	2076-11-12	2076
31778	2076-11-13	2076
31779	2076-11-16	2076
31780	2076-11-17	2076
31781	2076-11-18	2076
31782	2076-11-19	2076
31783	2076-11-20	2076
31784	2076-11-23	2076
31785	2076-11-24	2076
31786	2076-11-25	2076
31787	2076-11-26	2076
31788	2076-11-27	2076
31789	2076-11-30	2076
31790	2076-12-01	2076
31791	2076-12-02	2076
31792	2076-12-03	2076
31793	2076-12-04	2076
31794	2076-12-07	2076
31795	2076-12-08	2076
31796	2076-12-09	2076
31797	2076-12-10	2076
31798	2076-12-11	2076
31799	2076-12-14	2076
31800	2076-12-15	2076
31801	2076-12-16	2076
31802	2076-12-17	2076
31803	2076-12-18	2076
31804	2076-12-21	2076
31805	2076-12-22	2076
31806	2076-12-23	2076
31807	2076-12-24	2076
31808	2076-12-28	2076
31809	2076-12-29	2076
31810	2076-12-30	2076
31811	2076-12-31	2076
31812	2077-01-04	2077
31813	2077-01-05	2077
31814	2077-01-06	2077
31815	2077-01-07	2077
31816	2077-01-08	2077
31817	2077-01-11	2077
31818	2077-01-12	2077
31819	2077-01-13	2077
31820	2077-01-14	2077
31821	2077-01-15	2077
31822	2077-01-18	2077
31823	2077-01-19	2077
31824	2077-01-20	2077
31825	2077-01-21	2077
31826	2077-01-22	2077
31827	2077-01-25	2077
31828	2077-01-26	2077
31829	2077-01-27	2077
31830	2077-01-28	2077
31831	2077-01-29	2077
31832	2077-02-01	2077
31833	2077-02-02	2077
31834	2077-02-03	2077
31835	2077-02-04	2077
31836	2077-02-05	2077
31837	2077-02-08	2077
31838	2077-02-09	2077
31839	2077-02-10	2077
31840	2077-02-11	2077
31841	2077-02-12	2077
31842	2077-02-15	2077
31843	2077-02-16	2077
31844	2077-02-17	2077
31845	2077-02-18	2077
31846	2077-02-19	2077
31847	2077-02-22	2077
31848	2077-02-24	2077
31849	2077-02-25	2077
31850	2077-02-26	2077
31851	2077-03-01	2077
31852	2077-03-02	2077
31853	2077-03-03	2077
31854	2077-03-04	2077
31855	2077-03-05	2077
31856	2077-03-08	2077
31857	2077-03-09	2077
31858	2077-03-10	2077
31859	2077-03-11	2077
31860	2077-03-12	2077
31861	2077-03-15	2077
31862	2077-03-16	2077
31863	2077-03-17	2077
31864	2077-03-18	2077
31865	2077-03-19	2077
31866	2077-03-22	2077
31867	2077-03-23	2077
31868	2077-03-24	2077
31869	2077-03-25	2077
31870	2077-03-26	2077
31871	2077-03-29	2077
31872	2077-03-30	2077
31873	2077-03-31	2077
31874	2077-04-01	2077
31875	2077-04-02	2077
31876	2077-04-05	2077
31877	2077-04-06	2077
31878	2077-04-07	2077
31879	2077-04-08	2077
31880	2077-04-09	2077
31881	2077-04-12	2077
31882	2077-04-13	2077
31883	2077-04-14	2077
31884	2077-04-15	2077
31885	2077-04-16	2077
31886	2077-04-19	2077
31887	2077-04-20	2077
31888	2077-04-22	2077
31889	2077-04-23	2077
31890	2077-04-26	2077
31891	2077-04-27	2077
31892	2077-04-28	2077
31893	2077-04-29	2077
31894	2077-04-30	2077
31895	2077-05-03	2077
31896	2077-05-04	2077
31897	2077-05-05	2077
31898	2077-05-06	2077
31899	2077-05-07	2077
31900	2077-05-10	2077
31901	2077-05-11	2077
31902	2077-05-12	2077
31903	2077-05-13	2077
31904	2077-05-14	2077
31905	2077-05-17	2077
31906	2077-05-18	2077
31907	2077-05-19	2077
31908	2077-05-20	2077
31909	2077-05-21	2077
31910	2077-05-24	2077
31911	2077-05-25	2077
31912	2077-05-26	2077
31913	2077-05-27	2077
31914	2077-05-28	2077
31915	2077-05-31	2077
31916	2077-06-01	2077
31917	2077-06-02	2077
31918	2077-06-03	2077
31919	2077-06-04	2077
31920	2077-06-07	2077
31921	2077-06-08	2077
31922	2077-06-09	2077
31923	2077-06-11	2077
31924	2077-06-14	2077
31925	2077-06-15	2077
31926	2077-06-16	2077
31927	2077-06-17	2077
31928	2077-06-18	2077
31929	2077-06-21	2077
31930	2077-06-22	2077
31931	2077-06-23	2077
31932	2077-06-24	2077
31933	2077-06-25	2077
31934	2077-06-28	2077
31935	2077-06-29	2077
31936	2077-06-30	2077
31937	2077-07-01	2077
31938	2077-07-02	2077
31939	2077-07-05	2077
31940	2077-07-06	2077
31941	2077-07-07	2077
31942	2077-07-08	2077
31943	2077-07-09	2077
31944	2077-07-12	2077
31945	2077-07-13	2077
31946	2077-07-14	2077
31947	2077-07-15	2077
31948	2077-07-16	2077
31949	2077-07-19	2077
31950	2077-07-20	2077
31951	2077-07-21	2077
31952	2077-07-22	2077
31953	2077-07-23	2077
31954	2077-07-26	2077
31955	2077-07-27	2077
31956	2077-07-28	2077
31957	2077-07-29	2077
31958	2077-07-30	2077
31959	2077-08-02	2077
31960	2077-08-03	2077
31961	2077-08-04	2077
31962	2077-08-05	2077
31963	2077-08-06	2077
31964	2077-08-09	2077
31965	2077-08-10	2077
31966	2077-08-11	2077
31967	2077-08-12	2077
31968	2077-08-13	2077
31969	2077-08-16	2077
31970	2077-08-17	2077
31971	2077-08-18	2077
31972	2077-08-19	2077
31973	2077-08-20	2077
31974	2077-08-23	2077
31975	2077-08-24	2077
31976	2077-08-25	2077
31977	2077-08-26	2077
31978	2077-08-27	2077
31979	2077-08-30	2077
31980	2077-08-31	2077
31981	2077-09-01	2077
31982	2077-09-02	2077
31983	2077-09-03	2077
31984	2077-09-06	2077
31985	2077-09-08	2077
31986	2077-09-09	2077
31987	2077-09-10	2077
31988	2077-09-13	2077
31989	2077-09-14	2077
31990	2077-09-15	2077
31991	2077-09-16	2077
31992	2077-09-17	2077
31993	2077-09-20	2077
31994	2077-09-21	2077
31995	2077-09-22	2077
31996	2077-09-23	2077
31997	2077-09-24	2077
31998	2077-09-27	2077
31999	2077-09-28	2077
32000	2077-09-29	2077
32001	2077-09-30	2077
32002	2077-10-01	2077
32003	2077-10-04	2077
32004	2077-10-05	2077
32005	2077-10-06	2077
32006	2077-10-07	2077
32007	2077-10-08	2077
32008	2077-10-11	2077
32009	2077-10-13	2077
32010	2077-10-14	2077
32011	2077-10-15	2077
32012	2077-10-18	2077
32013	2077-10-19	2077
32014	2077-10-20	2077
32015	2077-10-21	2077
32016	2077-10-22	2077
32017	2077-10-25	2077
32018	2077-10-26	2077
32019	2077-10-27	2077
32020	2077-10-29	2077
32021	2077-11-01	2077
32022	2077-11-03	2077
32023	2077-11-04	2077
32024	2077-11-05	2077
32025	2077-11-08	2077
32026	2077-11-09	2077
32027	2077-11-10	2077
32028	2077-11-11	2077
32029	2077-11-12	2077
32030	2077-11-16	2077
32031	2077-11-17	2077
32032	2077-11-18	2077
32033	2077-11-19	2077
32034	2077-11-22	2077
32035	2077-11-23	2077
32036	2077-11-24	2077
32037	2077-11-25	2077
32038	2077-11-26	2077
32039	2077-11-29	2077
32040	2077-11-30	2077
32041	2077-12-01	2077
32042	2077-12-02	2077
32043	2077-12-03	2077
32044	2077-12-06	2077
32045	2077-12-07	2077
32046	2077-12-08	2077
32047	2077-12-09	2077
32048	2077-12-10	2077
32049	2077-12-13	2077
32050	2077-12-14	2077
32051	2077-12-15	2077
32052	2077-12-16	2077
32053	2077-12-17	2077
32054	2077-12-20	2077
32055	2077-12-21	2077
32056	2077-12-22	2077
32057	2077-12-23	2077
32058	2077-12-24	2077
32059	2077-12-27	2077
32060	2077-12-28	2077
32061	2077-12-29	2077
32062	2077-12-30	2077
32063	2077-12-31	2077
32064	2078-01-03	2078
32065	2078-01-04	2078
32066	2078-01-05	2078
32067	2078-01-06	2078
32068	2078-01-07	2078
32069	2078-01-10	2078
32070	2078-01-11	2078
32071	2078-01-12	2078
32072	2078-01-13	2078
32073	2078-01-14	2078
32074	2078-01-17	2078
32075	2078-01-18	2078
32076	2078-01-19	2078
32077	2078-01-20	2078
32078	2078-01-21	2078
32079	2078-01-24	2078
32080	2078-01-25	2078
32081	2078-01-26	2078
32082	2078-01-27	2078
32083	2078-01-28	2078
32084	2078-01-31	2078
32085	2078-02-01	2078
32086	2078-02-02	2078
32087	2078-02-03	2078
32088	2078-02-04	2078
32089	2078-02-07	2078
32090	2078-02-08	2078
32091	2078-02-09	2078
32092	2078-02-10	2078
32093	2078-02-11	2078
32094	2078-02-14	2078
32095	2078-02-16	2078
32096	2078-02-17	2078
32097	2078-02-18	2078
32098	2078-02-21	2078
32099	2078-02-22	2078
32100	2078-02-23	2078
32101	2078-02-24	2078
32102	2078-02-25	2078
32103	2078-02-28	2078
32104	2078-03-01	2078
32105	2078-03-02	2078
32106	2078-03-03	2078
32107	2078-03-04	2078
32108	2078-03-07	2078
32109	2078-03-08	2078
32110	2078-03-09	2078
32111	2078-03-10	2078
32112	2078-03-11	2078
32113	2078-03-14	2078
32114	2078-03-15	2078
32115	2078-03-16	2078
32116	2078-03-17	2078
32117	2078-03-18	2078
32118	2078-03-21	2078
32119	2078-03-22	2078
32120	2078-03-23	2078
32121	2078-03-24	2078
32122	2078-03-25	2078
32123	2078-03-28	2078
32124	2078-03-29	2078
32125	2078-03-30	2078
32126	2078-03-31	2078
32127	2078-04-01	2078
32128	2078-04-04	2078
32129	2078-04-05	2078
32130	2078-04-06	2078
32131	2078-04-07	2078
32132	2078-04-08	2078
32133	2078-04-11	2078
32134	2078-04-12	2078
32135	2078-04-13	2078
32136	2078-04-14	2078
32137	2078-04-15	2078
32138	2078-04-18	2078
32139	2078-04-19	2078
32140	2078-04-20	2078
32141	2078-04-22	2078
32142	2078-04-25	2078
32143	2078-04-26	2078
32144	2078-04-27	2078
32145	2078-04-28	2078
32146	2078-04-29	2078
32147	2078-05-02	2078
32148	2078-05-03	2078
32149	2078-05-04	2078
32150	2078-05-05	2078
32151	2078-05-06	2078
32152	2078-05-09	2078
32153	2078-05-10	2078
32154	2078-05-11	2078
32155	2078-05-12	2078
32156	2078-05-13	2078
32157	2078-05-16	2078
32158	2078-05-17	2078
32159	2078-05-18	2078
32160	2078-05-19	2078
32161	2078-05-20	2078
32162	2078-05-23	2078
32163	2078-05-24	2078
32164	2078-05-25	2078
32165	2078-05-26	2078
32166	2078-05-27	2078
32167	2078-05-30	2078
32168	2078-05-31	2078
32169	2078-06-01	2078
32170	2078-06-03	2078
32171	2078-06-06	2078
32172	2078-06-07	2078
32173	2078-06-08	2078
32174	2078-06-09	2078
32175	2078-06-10	2078
32176	2078-06-13	2078
32177	2078-06-14	2078
32178	2078-06-15	2078
32179	2078-06-16	2078
32180	2078-06-17	2078
32181	2078-06-20	2078
32182	2078-06-21	2078
32183	2078-06-22	2078
32184	2078-06-23	2078
32185	2078-06-24	2078
32186	2078-06-27	2078
32187	2078-06-28	2078
32188	2078-06-29	2078
32189	2078-06-30	2078
32190	2078-07-01	2078
32191	2078-07-04	2078
32192	2078-07-05	2078
32193	2078-07-06	2078
32194	2078-07-07	2078
32195	2078-07-08	2078
32196	2078-07-11	2078
32197	2078-07-12	2078
32198	2078-07-13	2078
32199	2078-07-14	2078
32200	2078-07-15	2078
32201	2078-07-18	2078
32202	2078-07-19	2078
32203	2078-07-20	2078
32204	2078-07-21	2078
32205	2078-07-22	2078
32206	2078-07-25	2078
32207	2078-07-26	2078
32208	2078-07-27	2078
32209	2078-07-28	2078
32210	2078-07-29	2078
32211	2078-08-01	2078
32212	2078-08-02	2078
32213	2078-08-03	2078
32214	2078-08-04	2078
32215	2078-08-05	2078
32216	2078-08-08	2078
32217	2078-08-09	2078
32218	2078-08-10	2078
32219	2078-08-11	2078
32220	2078-08-12	2078
32221	2078-08-15	2078
32222	2078-08-16	2078
32223	2078-08-17	2078
32224	2078-08-18	2078
32225	2078-08-19	2078
32226	2078-08-22	2078
32227	2078-08-23	2078
32228	2078-08-24	2078
32229	2078-08-25	2078
32230	2078-08-26	2078
32231	2078-08-29	2078
32232	2078-08-30	2078
32233	2078-08-31	2078
32234	2078-09-01	2078
32235	2078-09-02	2078
32236	2078-09-05	2078
32237	2078-09-06	2078
32238	2078-09-08	2078
32239	2078-09-09	2078
32240	2078-09-12	2078
32241	2078-09-13	2078
32242	2078-09-14	2078
32243	2078-09-15	2078
32244	2078-09-16	2078
32245	2078-09-19	2078
32246	2078-09-20	2078
32247	2078-09-21	2078
32248	2078-09-22	2078
32249	2078-09-23	2078
32250	2078-09-26	2078
32251	2078-09-27	2078
32252	2078-09-28	2078
32253	2078-09-29	2078
32254	2078-09-30	2078
32255	2078-10-03	2078
32256	2078-10-04	2078
32257	2078-10-05	2078
32258	2078-10-06	2078
32259	2078-10-07	2078
32260	2078-10-10	2078
32261	2078-10-11	2078
32262	2078-10-13	2078
32263	2078-10-14	2078
32264	2078-10-17	2078
32265	2078-10-18	2078
32266	2078-10-19	2078
32267	2078-10-20	2078
32268	2078-10-21	2078
32269	2078-10-24	2078
32270	2078-10-25	2078
32271	2078-10-26	2078
32272	2078-10-27	2078
32273	2078-10-31	2078
32274	2078-11-01	2078
32275	2078-11-03	2078
32276	2078-11-04	2078
32277	2078-11-07	2078
32278	2078-11-08	2078
32279	2078-11-09	2078
32280	2078-11-10	2078
32281	2078-11-11	2078
32282	2078-11-14	2078
32283	2078-11-16	2078
32284	2078-11-17	2078
32285	2078-11-18	2078
32286	2078-11-21	2078
32287	2078-11-22	2078
32288	2078-11-23	2078
32289	2078-11-24	2078
32290	2078-11-25	2078
32291	2078-11-28	2078
32292	2078-11-29	2078
32293	2078-11-30	2078
32294	2078-12-01	2078
32295	2078-12-02	2078
32296	2078-12-05	2078
32297	2078-12-06	2078
32298	2078-12-07	2078
32299	2078-12-08	2078
32300	2078-12-09	2078
32301	2078-12-12	2078
32302	2078-12-13	2078
32303	2078-12-14	2078
32304	2078-12-15	2078
32305	2078-12-16	2078
32306	2078-12-19	2078
32307	2078-12-20	2078
32308	2078-12-21	2078
32309	2078-12-22	2078
32310	2078-12-23	2078
32311	2078-12-26	2078
32312	2078-12-27	2078
32313	2078-12-28	2078
32314	2078-12-29	2078
32315	2078-12-30	2078
\.


--
-- TOC entry 4082 (class 0 OID 10423031)
-- Dependencies: 303
-- Data for Name: tb_documento; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_documento (iddocumento, idescritorio, nomdocumento, idtipodocumento, descaminho, datdocumento, desobs, idcadastrador, datcadastro, flaativo) FROM stdin;
1	1	Planilha de teste	7	file_doc_eb0bfab4f50e6797729b9235289987ad.doc	2015-06-01	.	1	2015-06-01	S
\.


--
-- TOC entry 4083 (class 0 OID 10423038)
-- Dependencies: 304
-- Data for Name: tb_elementodespesa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_elementodespesa (idelementodespesa, idoficial, nomelementodespesa, idcadastrador, datcadastro, numseq) FROM stdin;
17	17	Outras despesas variaveis - pessoal militar	1	2015-06-02	16
29	29	Distribuicao de resultado de empresas estatais dependentes	1	2015-06-02	28
1	1	Aposentadorias, Reserva Remunerada e Reformas	1	2015-06-02	1
3	3	Pensoes	1	2015-06-02	2
4	4	Contratacao por tempo determinado	1	2015-06-02	3
5	5	Outros beneficios previdenciarios	1	2015-06-02	4
6	6	Beneficio mensal ao deficiente e ao idoso	1	2015-06-02	5
7	7	Outros beneficios assistenciais	1	2015-06-02	6
8	8	Contribuicao a entidades fechadas de previdencia	1	2015-06-02	7
9	9	Salario-familia	1	2015-06-02	8
10	10	Outros beneficios de natureza social	1	2015-06-02	9
11	11	Vencimentos e Vantagens fixas - pessoal civil	1	2015-06-02	10
12	12	Vencimentos e vantagens fixas - pessoal militar	1	2015-06-02	11
13	13	Obrigacoes patronais	1	2015-06-02	12
14	14	Diarias - civil	1	2015-06-02	13
15	15	Diarias - militar	1	2015-06-02	14
16	16	Outras despesas variaveis - pessoal civil	1	2015-06-02	15
18	18	Auxilio financeiro a estudantes	1	2015-06-02	17
19	19	Auxilio fardamento	1	2015-06-02	18
20	20	Auxilio financeiro a pesquisadores	1	2015-06-02	19
21	21	Juros sobre a divida por contrato	1	2015-06-02	20
22	22	Outros encargos sobrea divida por contrato	1	2015-06-02	21
23	23	Juros, desagios e descontos da divida mobiliaria	1	2015-06-02	22
24	24	Outros encargos sobre a divida mobiliaria	1	2015-06-02	23
25	25	Encargos sobre operacoes de credito por antecipacao da receita	1	2015-06-02	24
26	26	Obrigacoes decorrentes de politica monetaria	1	2015-06-02	25
27	27	Encargos pela honra de avais, garantias, seguros e similares	1	2015-06-02	26
28	28	Remuneracao de cotas de fundos autaquicos	1	2015-06-02	27
39	39	Outros servicos de terceiros - pessoa juridica	1	2015-06-02	38
30	30	Material de consumo	1	2015-06-02	29
31	31	Premiacoes culturais, artisticas, cientificas, desportivas e outras	1	2015-06-02	30
32	32	Material, bem ou servico para distribuicao gratuita	1	2015-06-02	31
33	33	Passagens e despesas com locomocao	1	2015-06-02	32
34	34	Outras despesas de pessoal decorrentes de contratos de terceirizacao	1	2015-06-02	33
35	35	Servicos de consultoria	1	2015-06-02	34
36	36	Outros servicos de terceiros - pessoa fisica	1	2015-06-02	35
37	37	Locacao de mao-de-obra	1	2015-06-02	36
38	38	Arrendamento mercantil	1	2015-06-02	37
41	41	Contribuicoes	1	2015-06-02	39
42	42	Auxilios	1	2015-06-02	40
43	43	Subvencoes sociais	1	2015-06-02	41
45	45	Subvencoes economicas	1	2015-06-02	42
46	46	Auxilio-alimentacao	1	2015-06-02	43
47	47	Obrigacoes tributarias e contributivas	1	2015-06-02	44
48	48	Outros auxilios financeiros a pessoas fisicas	1	2015-06-02	45
49	49	Auxilio-transporte	1	2015-06-02	46
51	51	Obras e instalacoes	1	2015-06-02	47
52	52	Equipamentos e material permanente	1	2015-06-02	48
61	61	Aquisicao de imoveis	1	2015-06-02	49
62	62	Aquisicao de produtos para revenda	1	2015-06-02	50
63	63	Aquisicao de titulos de credito	1	2015-06-02	51
64	64	Aquisicao de titulos representativos de capital ja integralizado	1	2015-06-02	52
65	65	Constituicao ou aumento de capital de empresas	1	2015-06-02	53
66	66	Concessao de emprestimos e financiamentos	1	2015-06-02	54
67	67	Depositos compulsorios	1	2015-06-02	55
71	71	Principal da divida contratual resgatado	1	2015-06-02	56
72	72	Principal da divida mobiliaria resgatado	1	2015-06-02	57
73	73	Correcao monetaria ou cambial da divida contratual resgatada	1	2015-06-02	58
74	74	Correcao monetaria ou cambial da divida mobiliaria resgatada	1	2015-06-02	59
75	75	Correcao monetaria da divida de operacoes de credito por antecipacao da receita	1	2015-06-02	60
76	76	Principal corrigido da divida mobiliaria refinanciado	1	2015-06-02	61
77	77	Principal corrigido da divida contratual refinanciado	1	2015-06-02	62
81	81	Distribuicao constitucional ou legal de receitas	1	2015-06-02	63
91	91	Sentencas judiciais	1	2015-06-02	64
92	92	Despesas de exercicios anteriores	1	2015-06-02	65
93	93	Indenizacoes e restituicoes	1	2015-06-02	66
94	94	Indenizacoes e restituicoes trabalhistas	1	2015-06-02	67
95	95	Indenizacao pela execucao de trabalhos de campo	1	2015-06-02	68
96	96	Ressarcimento de despesas de pessoal requisitado	1	2015-06-02	69
97	97	Aporte para cobertura do deficit atuarial do RPPS	1	2015-06-02	70
99	99	A classificar	1	2015-06-02	71
\.


--
-- TOC entry 4084 (class 0 OID 10423041)
-- Dependencies: 305
-- Data for Name: tb_entidadeexterna; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_entidadeexterna (identidadeexterna, nomentidadeexterna, idcadastrador, datcadastro) FROM stdin;
1	Ministerio xxx	1	2015-07-10
\.


--
-- TOC entry 4085 (class 0 OID 10423044)
-- Dependencies: 306
-- Data for Name: tb_escritorio; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_escritorio (idescritorio, nomescritorio, idcadastrador, datcadastro, flaativo, idresponsavel1, idresponsavel2, idescritoriope, nomescritorio2, desemail, numfone) FROM stdin;
0	ESCRITORIO_0	1	2010-02-01	S	1	2	0	ESCRITORIO CENTRAL 0	escritorio_0@gepnet2.gov	(99) 9999-99999
1	ESCRITORIO_1	1	2010-03-08	S	1	2	0	ESCRITORIO CENTRAL 1	escritorio_1@gepnet2.gov	(99) 9999-99999
2	ESCRITORIO_2	1	2010-03-08	S	1	2	0	ESCRITORIO 2 - PROJETOS TI	escritorio_2@gepnet2.gov	(99) 9999-99999
\.


--
-- TOC entry 4086 (class 0 OID 10423049)
-- Dependencies: 307
-- Data for Name: tb_etapa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_etapa (idetapa, dsetapa, idcadastrador, dtcadastro, pgpassinado) FROM stdin;
8	Execucao e/ou Monitoramento e Controle	1	2018-08-16	S
7	Iniciacao e/ou Planejamento	1	2018-08-16	N
1	Analise de viabilidade	1	2014-01-29	N
2	Formalizacao	1	2014-01-29	N
3	Planejamento	1	2014-01-29	N
4	Execucao	1	2015-06-08	S
5	Monitoramento	1	2015-06-08	S
6	Encerramento	1	2015-06-08	S
\.


--
-- TOC entry 4087 (class 0 OID 10423052)
-- Dependencies: 308
-- Data for Name: tb_evento; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_evento (idevento, nomevento, desevento, desobs, idcadastrador, idresponsavel, datcadastro, datinicio, datfim, uf) FROM stdin;
1	FCC FIFA 2014 - MG			1	18	2015-06-10	2014-06-08	2015-07-18	MG
2	JOGOS OLIMPICOS 2016	Jogos Olimpicos de Verao 2016		1	1	2015-11-04	2016-07-05	2016-08-21	RJ
\.


--
-- TOC entry 4088 (class 0 OID 10423058)
-- Dependencies: 309
-- Data for Name: tb_eventoavaliacao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_eventoavaliacao (ideventoavaliacao, idevento, desdestaqueservidor, desobs, idavaliador, idavaliado, datcadastro, numpontualidade, numordens, numrespeitochefia, numrespeitocolega, numurbanidade, numequilibrio, numcomprometimento, numesforco, numtrabalhoequipe, numauxiliouequipe, numaceitousugestao, numconhecimentonorma, numalternativaproblema, numiniciativa, numtarefacomplexa, numnotaavaliador, nummedia, nummediafinal, numtotalavaliado, idtipoavaliacao) FROM stdin;
1	1	,	,	1	1	2015-07-10	10	10	10	10	10	10	10	10	10	10	10	10	10	10	10	10	10	10	15	1
\.


--
-- TOC entry 4089 (class 0 OID 10423064)
-- Dependencies: 310
-- Data for Name: tb_feriado; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_feriado (idferiado, diaferiado, mesferiado, anoferiado, tipoferiado, desferiado, dtcadastro, flaativo) FROM stdin;
1	1	1	0	1	Confraternizacao Universal	2018-01-10	S
2	21	4	0	1	Tiradentes	2018-01-10	S
3	1	5	0	1	Dia do Trabalho	2018-01-10	S
4	7	9	0	1	Independencia do Brasil	2018-01-10	S
5	12	10	0	1	Nossa Sr.a Aparecida - Padroeira do Brasil	2018-01-10	S
6	28	10	0	1	Dia do Servidor Publico	2018-01-10	S
7	2	11	0	1	Finados	2018-01-10	S
8	15	11	0	1	Proclamacao da Republica	2018-01-10	S
9	25	12	0	1	Natal	2018-01-10	S
10	23	4	2000	2	Pascoa	2018-05-24	S
11	15	4	2001	2	Pascoa	2018-05-24	S
12	31	3	2002	2	Pascoa	2018-05-24	S
13	20	4	2003	2	Pascoa	2018-05-24	S
14	11	4	2004	2	Pascoa	2018-05-24	S
15	27	3	2005	2	Pascoa	2018-05-24	S
16	16	4	2006	2	Pascoa	2018-05-24	S
17	8	4	2007	2	Pascoa	2018-05-24	S
18	23	3	2008	2	Pascoa	2018-05-24	S
19	12	4	2009	2	Pascoa	2018-05-24	S
20	4	4	2010	2	Pascoa	2018-05-24	S
21	24	4	2011	2	Pascoa	2018-05-24	S
22	8	4	2012	2	Pascoa	2018-05-24	S
23	31	3	2013	2	Pascoa	2018-05-24	S
24	20	4	2014	2	Pascoa	2018-05-24	S
25	5	4	2015	2	Pascoa	2018-05-24	S
26	27	3	2016	2	Pascoa	2018-05-24	S
27	16	4	2017	2	Pascoa	2018-05-24	S
28	1	4	2018	2	Pascoa	2018-05-24	S
29	21	4	2019	2	Pascoa	2018-05-24	S
30	12	4	2020	2	Pascoa	2018-05-24	S
31	4	4	2021	2	Pascoa	2018-05-24	S
32	17	4	2022	2	Pascoa	2018-05-24	S
33	9	4	2023	2	Pascoa	2018-05-24	S
34	31	3	2024	2	Pascoa	2018-05-24	S
35	20	4	2025	2	Pascoa	2018-05-24	S
36	5	4	2026	2	Pascoa	2018-05-24	S
37	7	3	2000	2	Carnaval	2018-05-24	S
38	27	2	2001	2	Carnaval	2018-05-24	S
39	12	2	2002	2	Carnaval	2018-05-24	S
40	4	3	2003	2	Carnaval	2018-05-24	S
41	24	2	2004	2	Carnaval	2018-05-24	S
42	8	2	2005	2	Carnaval	2018-05-24	S
43	28	2	2006	2	Carnaval	2018-05-24	S
44	20	2	2007	2	Carnaval	2018-05-24	S
45	5	2	2008	2	Carnaval	2018-05-24	S
46	24	2	2009	2	Carnaval	2018-05-24	S
47	16	2	2010	2	Carnaval	2018-05-24	S
48	8	3	2011	2	Carnaval	2018-05-24	S
49	21	2	2012	2	Carnaval	2018-05-24	S
50	12	2	2013	2	Carnaval	2018-05-24	S
51	4	3	2014	2	Carnaval	2018-05-24	S
52	17	2	2015	2	Carnaval	2018-05-24	S
53	9	2	2016	2	Carnaval	2018-05-24	S
54	28	2	2017	2	Carnaval	2018-05-24	S
55	13	2	2018	2	Carnaval	2018-05-24	S
56	5	3	2019	2	Carnaval	2018-05-24	S
57	25	2	2020	2	Carnaval	2018-05-24	S
58	16	2	2021	2	Carnaval	2018-05-24	S
59	1	3	2022	2	Carnaval	2018-05-24	S
60	21	2	2023	2	Carnaval	2018-05-24	S
61	13	2	2024	2	Carnaval	2018-05-24	S
62	4	3	2025	2	Carnaval	2018-05-24	S
63	17	2	2026	2	Carnaval	2018-05-24	S
91	28	3	2027	2	Pascoa	2018-05-24	S
92	16	4	2028	2	Pascoa	2018-05-24	S
93	1	4	2029	2	Pascoa	2018-05-24	S
94	21	4	2030	2	Pascoa	2018-05-24	S
95	13	4	2031	2	Pascoa	2018-05-24	S
96	28	3	2032	2	Pascoa	2018-05-24	S
97	17	4	2033	2	Pascoa	2018-05-24	S
98	9	4	2034	2	Pascoa	2018-05-24	S
99	25	3	2035	2	Pascoa	2018-05-24	S
100	13	4	2036	2	Pascoa	2018-05-24	S
101	5	4	2037	2	Pascoa	2018-05-24	S
102	25	4	2038	2	Pascoa	2018-05-24	S
103	10	4	2039	2	Pascoa	2018-05-24	S
104	1	4	2040	2	Pascoa	2018-05-24	S
105	21	4	2041	2	Pascoa	2018-05-24	S
106	6	4	2042	2	Pascoa	2018-05-24	S
107	29	3	2043	2	Pascoa	2018-05-24	S
108	17	4	2044	2	Pascoa	2018-05-24	S
109	9	4	2045	2	Pascoa	2018-05-24	S
110	25	3	2046	2	Pascoa	2018-05-24	S
111	14	4	2047	2	Pascoa	2018-05-24	S
112	5	4	2048	2	Pascoa	2018-05-24	S
113	18	4	2049	2	Pascoa	2018-05-24	S
114	10	4	2050	2	Pascoa	2018-05-24	S
115	2	4	2051	2	Pascoa	2018-05-24	S
116	21	4	2052	2	Pascoa	2018-05-24	S
117	6	4	2053	2	Pascoa	2018-05-24	S
118	29	3	2054	2	Pascoa	2018-05-24	S
119	18	4	2055	2	Pascoa	2018-05-24	S
120	2	4	2056	2	Pascoa	2018-05-24	S
121	22	4	2057	2	Pascoa	2018-05-24	S
122	14	4	2058	2	Pascoa	2018-05-24	S
123	30	3	2059	2	Pascoa	2018-05-24	S
124	18	4	2060	2	Pascoa	2018-05-24	S
125	10	4	2061	2	Pascoa	2018-05-24	S
126	26	3	2062	2	Pascoa	2018-05-24	S
127	15	4	2063	2	Pascoa	2018-05-24	S
128	6	4	2064	2	Pascoa	2018-05-24	S
129	29	3	2065	2	Pascoa	2018-05-24	S
130	11	4	2066	2	Pascoa	2018-05-24	S
131	3	4	2067	2	Pascoa	2018-05-24	S
132	22	4	2068	2	Pascoa	2018-05-24	S
133	14	4	2069	2	Pascoa	2018-05-24	S
134	30	3	2070	2	Pascoa	2018-05-24	S
135	19	4	2071	2	Pascoa	2018-05-24	S
136	10	4	2072	2	Pascoa	2018-05-24	S
137	26	3	2073	2	Pascoa	2018-05-24	S
138	15	4	2074	2	Pascoa	2018-05-24	S
139	7	4	2075	2	Pascoa	2018-05-24	S
140	19	4	2076	2	Pascoa	2018-05-24	S
141	11	4	2077	2	Pascoa	2018-05-24	S
142	3	4	2078	2	Pascoa	2018-05-24	S
247	25	3	1951	2	Pascoa	2018-05-24	S
248	13	4	1952	2	Pascoa	2018-05-24	S
249	5	4	1953	2	Pascoa	2018-05-24	S
250	18	4	1954	2	Pascoa	2018-05-24	S
251	10	4	1955	2	Pascoa	2018-05-24	S
252	1	4	1956	2	Pascoa	2018-05-24	S
253	21	4	1957	2	Pascoa	2018-05-24	S
254	6	4	1958	2	Pascoa	2018-05-24	S
255	29	3	1959	2	Pascoa	2018-05-24	S
256	17	4	1960	2	Pascoa	2018-05-24	S
257	2	4	1961	2	Pascoa	2018-05-24	S
258	22	4	1962	2	Pascoa	2018-05-24	S
259	14	4	1963	2	Pascoa	2018-05-24	S
260	29	3	1964	2	Pascoa	2018-05-24	S
261	18	4	1965	2	Pascoa	2018-05-24	S
262	10	4	1966	2	Pascoa	2018-05-24	S
263	26	3	1967	2	Pascoa	2018-05-24	S
264	14	4	1968	2	Pascoa	2018-05-24	S
265	6	4	1969	2	Pascoa	2018-05-24	S
266	29	3	1970	2	Pascoa	2018-05-24	S
267	11	4	1971	2	Pascoa	2018-05-24	S
268	2	4	1972	2	Pascoa	2018-05-24	S
269	22	4	1973	2	Pascoa	2018-05-24	S
270	14	4	1974	2	Pascoa	2018-05-24	S
271	30	3	1975	2	Pascoa	2018-05-24	S
272	18	4	1976	2	Pascoa	2018-05-24	S
273	10	4	1977	2	Pascoa	2018-05-24	S
274	26	3	1978	2	Pascoa	2018-05-24	S
275	15	4	1979	2	Pascoa	2018-05-24	S
276	6	4	1980	2	Pascoa	2018-05-24	S
277	19	4	1981	2	Pascoa	2018-05-24	S
278	11	4	1982	2	Pascoa	2018-05-24	S
279	3	4	1983	2	Pascoa	2018-05-24	S
280	22	4	1984	2	Pascoa	2018-05-24	S
281	7	4	1985	2	Pascoa	2018-05-24	S
282	30	3	1986	2	Pascoa	2018-05-24	S
283	19	4	1987	2	Pascoa	2018-05-24	S
284	3	4	1988	2	Pascoa	2018-05-24	S
285	26	3	1989	2	Pascoa	2018-05-24	S
286	15	4	1990	2	Pascoa	2018-05-24	S
64	22	6	2000	2	Corpus Christi	2018-05-24	S
65	14	6	2001	2	Corpus Christi	2018-05-24	S
66	30	5	2002	2	Corpus Christi	2018-05-24	S
67	19	6	2003	2	Corpus Christi	2018-05-24	S
68	10	6	2004	2	Corpus Christi	2018-05-24	S
69	26	5	2005	2	Corpus Christi	2018-05-24	S
70	15	6	2006	2	Corpus Christi	2018-05-24	S
71	7	6	2007	2	Corpus Christi	2018-05-24	S
72	22	5	2008	2	Corpus Christi	2018-05-24	S
73	11	6	2009	2	Corpus Christi	2018-05-24	S
74	3	6	2010	2	Corpus Christi	2018-05-24	S
75	23	6	2011	2	Corpus Christi	2018-05-24	S
76	7	6	2012	2	Corpus Christi	2018-05-24	S
77	30	5	2013	2	Corpus Christi	2018-05-24	S
78	19	6	2014	2	Corpus Christi	2018-05-24	S
79	4	6	2015	2	Corpus Christi	2018-05-24	S
80	26	5	2016	2	Corpus Christi	2018-05-24	S
81	15	6	2017	2	Corpus Christi	2018-05-24	S
82	31	5	2018	2	Corpus Christi	2018-05-24	S
83	20	6	2019	2	Corpus Christi	2018-05-24	S
84	11	6	2020	2	Corpus Christi	2018-05-24	S
85	3	6	2021	2	Corpus Christi	2018-05-24	S
86	16	6	2022	2	Corpus Christi	2018-05-24	S
87	8	6	2023	2	Corpus Christi	2018-05-24	S
88	30	5	2024	2	Corpus Christi	2018-05-24	S
89	19	6	2025	2	Corpus Christi	2018-05-24	S
90	4	6	2026	2	Corpus Christi	2018-05-24	S
287	31	3	1991	2	Pascoa	2018-05-24	S
288	19	4	1992	2	Pascoa	2018-05-24	S
289	11	4	1993	2	Pascoa	2018-05-24	S
290	3	4	1994	2	Pascoa	2018-05-24	S
291	16	4	1995	2	Pascoa	2018-05-24	S
292	7	4	1996	2	Pascoa	2018-05-24	S
293	30	3	1997	2	Pascoa	2018-05-24	S
294	12	4	1998	2	Pascoa	2018-05-24	S
295	4	4	1999	2	Pascoa	2018-05-24	S
296	6	2	1951	2	Carnaval	2018-05-24	S
297	26	2	1952	2	Carnaval	2018-05-24	S
298	17	2	1953	2	Carnaval	2018-05-24	S
299	2	3	1954	2	Carnaval	2018-05-24	S
300	22	2	1955	2	Carnaval	2018-05-24	S
301	14	2	1956	2	Carnaval	2018-05-24	S
302	5	3	1957	2	Carnaval	2018-05-24	S
303	18	2	1958	2	Carnaval	2018-05-24	S
304	10	2	1959	2	Carnaval	2018-05-24	S
305	1	3	1960	2	Carnaval	2018-05-24	S
306	14	2	1961	2	Carnaval	2018-05-24	S
307	6	3	1962	2	Carnaval	2018-05-24	S
308	26	2	1963	2	Carnaval	2018-05-24	S
309	11	2	1964	2	Carnaval	2018-05-24	S
310	2	3	1965	2	Carnaval	2018-05-24	S
311	22	2	1966	2	Carnaval	2018-05-24	S
312	7	2	1967	2	Carnaval	2018-05-24	S
313	27	2	1968	2	Carnaval	2018-05-24	S
314	18	2	1969	2	Carnaval	2018-05-24	S
315	10	2	1970	2	Carnaval	2018-05-24	S
316	23	2	1971	2	Carnaval	2018-05-24	S
317	15	2	1972	2	Carnaval	2018-05-24	S
318	6	3	1973	2	Carnaval	2018-05-24	S
319	26	2	1974	2	Carnaval	2018-05-24	S
320	11	2	1975	2	Carnaval	2018-05-24	S
321	2	3	1976	2	Carnaval	2018-05-24	S
322	22	2	1977	2	Carnaval	2018-05-24	S
323	7	2	1978	2	Carnaval	2018-05-24	S
324	27	2	1979	2	Carnaval	2018-05-24	S
325	19	2	1980	2	Carnaval	2018-05-24	S
326	3	3	1981	2	Carnaval	2018-05-24	S
327	23	2	1982	2	Carnaval	2018-05-24	S
328	15	2	1983	2	Carnaval	2018-05-24	S
329	6	3	1984	2	Carnaval	2018-05-24	S
330	19	2	1985	2	Carnaval	2018-05-24	S
331	11	2	1986	2	Carnaval	2018-05-24	S
332	3	3	1987	2	Carnaval	2018-05-24	S
333	16	2	1988	2	Carnaval	2018-05-24	S
334	7	2	1989	2	Carnaval	2018-05-24	S
335	27	2	1990	2	Carnaval	2018-05-24	S
336	12	2	1991	2	Carnaval	2018-05-24	S
337	3	3	1992	2	Carnaval	2018-05-24	S
338	23	2	1993	2	Carnaval	2018-05-24	S
339	15	2	1994	2	Carnaval	2018-05-24	S
340	28	2	1995	2	Carnaval	2018-05-24	S
341	20	2	1996	2	Carnaval	2018-05-24	S
342	11	2	1997	2	Carnaval	2018-05-24	S
343	24	2	1998	2	Carnaval	2018-05-24	S
344	16	2	1999	2	Carnaval	2018-05-24	S
195	27	5	2027	2	Corpus Christi	2018-05-24	S
196	15	6	2028	2	Corpus Christi	2018-05-24	S
197	31	5	2029	2	Corpus Christi	2018-05-24	S
198	20	6	2030	2	Corpus Christi	2018-05-24	S
199	12	6	2031	2	Corpus Christi	2018-05-24	S
200	27	5	2032	2	Corpus Christi	2018-05-24	S
201	16	6	2033	2	Corpus Christi	2018-05-24	S
202	8	6	2034	2	Corpus Christi	2018-05-24	S
203	24	5	2035	2	Corpus Christi	2018-05-24	S
204	12	6	2036	2	Corpus Christi	2018-05-24	S
205	4	6	2037	2	Corpus Christi	2018-05-24	S
206	24	6	2038	2	Corpus Christi	2018-05-24	S
207	9	6	2039	2	Corpus Christi	2018-05-24	S
208	31	5	2040	2	Corpus Christi	2018-05-24	S
209	20	6	2041	2	Corpus Christi	2018-05-24	S
210	5	6	2042	2	Corpus Christi	2018-05-24	S
211	28	5	2043	2	Corpus Christi	2018-05-24	S
212	16	6	2044	2	Corpus Christi	2018-05-24	S
213	8	6	2045	2	Corpus Christi	2018-05-24	S
214	24	5	2046	2	Corpus Christi	2018-05-24	S
215	13	6	2047	2	Corpus Christi	2018-05-24	S
216	4	6	2048	2	Corpus Christi	2018-05-24	S
217	17	6	2049	2	Corpus Christi	2018-05-24	S
218	9	6	2050	2	Corpus Christi	2018-05-24	S
219	1	6	2051	2	Corpus Christi	2018-05-24	S
220	20	6	2052	2	Corpus Christi	2018-05-24	S
221	5	6	2053	2	Corpus Christi	2018-05-24	S
222	28	5	2054	2	Corpus Christi	2018-05-24	S
223	17	6	2055	2	Corpus Christi	2018-05-24	S
224	1	6	2056	2	Corpus Christi	2018-05-24	S
225	21	6	2057	2	Corpus Christi	2018-05-24	S
226	13	6	2058	2	Corpus Christi	2018-05-24	S
227	29	5	2059	2	Corpus Christi	2018-05-24	S
228	17	6	2060	2	Corpus Christi	2018-05-24	S
229	9	6	2061	2	Corpus Christi	2018-05-24	S
230	25	5	2062	2	Corpus Christi	2018-05-24	S
231	14	6	2063	2	Corpus Christi	2018-05-24	S
232	5	6	2064	2	Corpus Christi	2018-05-24	S
233	28	5	2065	2	Corpus Christi	2018-05-24	S
234	10	6	2066	2	Corpus Christi	2018-05-24	S
235	2	6	2067	2	Corpus Christi	2018-05-24	S
236	21	6	2068	2	Corpus Christi	2018-05-24	S
237	13	6	2069	2	Corpus Christi	2018-05-24	S
238	29	5	2070	2	Corpus Christi	2018-05-24	S
239	18	6	2071	2	Corpus Christi	2018-05-24	S
240	9	6	2072	2	Corpus Christi	2018-05-24	S
241	25	5	2073	2	Corpus Christi	2018-05-24	S
242	14	6	2074	2	Corpus Christi	2018-05-24	S
243	6	6	2075	2	Corpus Christi	2018-05-24	S
244	18	6	2076	2	Corpus Christi	2018-05-24	S
245	10	6	2077	2	Corpus Christi	2018-05-24	S
246	2	6	2078	2	Corpus Christi	2018-05-24	S
345	24	5	1951	2	Corpus Christi	2018-05-24	S
346	12	6	1952	2	Corpus Christi	2018-05-24	S
347	4	6	1953	2	Corpus Christi	2018-05-24	S
348	17	6	1954	2	Corpus Christi	2018-05-24	S
349	9	6	1955	2	Corpus Christi	2018-05-24	S
350	31	5	1956	2	Corpus Christi	2018-05-24	S
351	20	6	1957	2	Corpus Christi	2018-05-24	S
352	5	6	1958	2	Corpus Christi	2018-05-24	S
353	28	5	1959	2	Corpus Christi	2018-05-24	S
354	16	6	1960	2	Corpus Christi	2018-05-24	S
355	1	6	1961	2	Corpus Christi	2018-05-24	S
356	21	6	1962	2	Corpus Christi	2018-05-24	S
357	13	6	1963	2	Corpus Christi	2018-05-24	S
358	28	5	1964	2	Corpus Christi	2018-05-24	S
359	17	6	1965	2	Corpus Christi	2018-05-24	S
360	9	6	1966	2	Corpus Christi	2018-05-24	S
361	25	5	1967	2	Corpus Christi	2018-05-24	S
362	13	6	1968	2	Corpus Christi	2018-05-24	S
363	5	6	1969	2	Corpus Christi	2018-05-24	S
364	28	5	1970	2	Corpus Christi	2018-05-24	S
365	10	6	1971	2	Corpus Christi	2018-05-24	S
366	1	6	1972	2	Corpus Christi	2018-05-24	S
367	21	6	1973	2	Corpus Christi	2018-05-24	S
368	13	6	1974	2	Corpus Christi	2018-05-24	S
369	29	5	1975	2	Corpus Christi	2018-05-24	S
370	17	6	1976	2	Corpus Christi	2018-05-24	S
371	9	6	1977	2	Corpus Christi	2018-05-24	S
372	25	5	1978	2	Corpus Christi	2018-05-24	S
373	14	6	1979	2	Corpus Christi	2018-05-24	S
374	5	6	1980	2	Corpus Christi	2018-05-24	S
375	18	6	1981	2	Corpus Christi	2018-05-24	S
376	10	6	1982	2	Corpus Christi	2018-05-24	S
377	2	6	1983	2	Corpus Christi	2018-05-24	S
378	21	6	1984	2	Corpus Christi	2018-05-24	S
379	6	6	1985	2	Corpus Christi	2018-05-24	S
380	29	5	1986	2	Corpus Christi	2018-05-24	S
381	18	6	1987	2	Corpus Christi	2018-05-24	S
382	2	6	1988	2	Corpus Christi	2018-05-24	S
383	25	5	1989	2	Corpus Christi	2018-05-24	S
384	14	6	1990	2	Corpus Christi	2018-05-24	S
385	30	5	1991	2	Corpus Christi	2018-05-24	S
386	18	6	1992	2	Corpus Christi	2018-05-24	S
387	10	6	1993	2	Corpus Christi	2018-05-24	S
388	2	6	1994	2	Corpus Christi	2018-05-24	S
389	15	6	1995	2	Corpus Christi	2018-05-24	S
390	6	6	1996	2	Corpus Christi	2018-05-24	S
391	29	5	1997	2	Corpus Christi	2018-05-24	S
392	11	6	1998	2	Corpus Christi	2018-05-24	S
393	3	6	1999	2	Corpus Christi	2018-05-24	S
143	9	2	2027	2	Carnaval	2018-05-24	S
144	29	2	2028	2	Carnaval	2018-05-24	S
145	13	2	2029	2	Carnaval	2018-05-24	S
146	5	3	2030	2	Carnaval	2018-05-24	S
147	25	2	2031	2	Carnaval	2018-05-24	S
148	10	2	2032	2	Carnaval	2018-05-24	S
149	1	3	2033	2	Carnaval	2018-05-24	S
150	21	2	2034	2	Carnaval	2018-05-24	S
151	6	2	2035	2	Carnaval	2018-05-24	S
152	26	2	2036	2	Carnaval	2018-05-24	S
153	17	2	2037	2	Carnaval	2018-05-24	S
154	9	3	2038	2	Carnaval	2018-05-24	S
155	22	2	2039	2	Carnaval	2018-05-24	S
156	14	2	2040	2	Carnaval	2018-05-24	S
157	5	3	2041	2	Carnaval	2018-05-24	S
158	18	2	2042	2	Carnaval	2018-05-24	S
159	10	2	2043	2	Carnaval	2018-05-24	S
160	1	3	2044	2	Carnaval	2018-05-24	S
161	21	2	2045	2	Carnaval	2018-05-24	S
162	6	2	2046	2	Carnaval	2018-05-24	S
163	26	2	2047	2	Carnaval	2018-05-24	S
164	18	2	2048	2	Carnaval	2018-05-24	S
165	2	3	2049	2	Carnaval	2018-05-24	S
166	22	2	2050	2	Carnaval	2018-05-24	S
167	14	2	2051	2	Carnaval	2018-05-24	S
168	5	3	2052	2	Carnaval	2018-05-24	S
169	18	2	2053	2	Carnaval	2018-05-24	S
170	10	2	2054	2	Carnaval	2018-05-24	S
171	2	3	2055	2	Carnaval	2018-05-24	S
172	15	2	2056	2	Carnaval	2018-05-24	S
173	6	3	2057	2	Carnaval	2018-05-24	S
174	26	2	2058	2	Carnaval	2018-05-24	S
175	11	2	2059	2	Carnaval	2018-05-24	S
176	2	3	2060	2	Carnaval	2018-05-24	S
177	22	2	2061	2	Carnaval	2018-05-24	S
178	7	2	2062	2	Carnaval	2018-05-24	S
179	27	2	2063	2	Carnaval	2018-05-24	S
180	19	2	2064	2	Carnaval	2018-05-24	S
181	10	2	2065	2	Carnaval	2018-05-24	S
182	23	2	2066	2	Carnaval	2018-05-24	S
183	15	2	2067	2	Carnaval	2018-05-24	S
184	6	3	2068	2	Carnaval	2018-05-24	S
185	26	2	2069	2	Carnaval	2018-05-24	S
186	11	2	2070	2	Carnaval	2018-05-24	S
187	3	3	2071	2	Carnaval	2018-05-24	S
188	23	2	2072	2	Carnaval	2018-05-24	S
189	7	2	2073	2	Carnaval	2018-05-24	S
190	27	2	2074	2	Carnaval	2018-05-24	S
191	19	2	2075	2	Carnaval	2018-05-24	S
192	3	3	2076	2	Carnaval	2018-05-24	S
193	23	2	2077	2	Carnaval	2018-05-24	S
194	15	2	2078	2	Carnaval	2018-05-24	S
\.


--
-- TOC entry 4090 (class 0 OID 10423071)
-- Dependencies: 311
-- Data for Name: tb_frase; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_frase (idfrase, domtipofrase, flaativo, datcadastro, idescritorio, idcadastrador, desfrase) FROM stdin;
1	1	S	2014-03-18	0	1	2015.1 - Informe a classe do seu cargo.
2	3	S	2014-03-18	0	1	2016.3 Opine sobre os processos organizacionais de sua unidades.
16	1	S	2015-04-01	0	1	2015.4 - Informe a sua area de atuacao nas OLIMPIADAS 2016.
3	1	S	2014-03-18	0	1	2015.3 Pergunta - O que e, o que e? Tem olhos vermelhos, orelhas grandes e branquinho como a neve?
5	5	S	2014-03-18	0	1	2016.1 Informe o seu numero favorito, de 1 a 1000:
6	1	S	2014-04-02	0	1	D1.F6 - Avalie a CONSERVACAO E LIMPEZA do local de atendimento ao publico dessa Unidade.
7	1	S	2014-04-14	0	1	2016.2 Informe o objetivo institucional que voce considera mais relevante.
8	3	S	2014-04-14	0	1	2015.2 O que voce entende por Planejamento estrategico? Descreva
9	1	S	2014-07-21	0	1	D1.F6 - Atribua uma AVALIACAO GERAL em relacao ao servico utilizado por voce nessa Unidade.
10	1	S	2014-08-22	0	1	2014.P1.Q1 - Informe ha quanto tempo voce atua na unidade.  Informe, conforme a escala, o tempo total em anos de sua atuacao na unidade
11	1	S	2014-08-22	0	1	2014.P1.Q2 - Informa a sua LOTACAO ATUAL.
12	2	S	2014-08-28	0	1	2014.P1.Q3 - Informe a cidade-sede do evento Copa do Mundo FIFA 2014 em que voce atuou. Para fins de resposta à esta questao, considere a cidade-sede onde voce atuou na maior parte do tempo durante a mobilizacao para a Copa do Mundo FIFA 2014.
13	1	S	2014-08-28	0	1	2014.P1.Q4 - Informe a sua area de atuacao no evento Copa do Mundo FIFA 2014. Selecione a sua area de atuacao (coordenacao) no evento Copa do Mundo FIFA 2014.
14	4	S	2014-10-02	0	1	2015.7 Qual o nome do ultimo livro que voce leu?
15	1	S	2015-02-20	0	1	D1.F6 - Avalia a qualidade das INSTALACOES FISICAS dessa Unidade.
4	7	N	2014-03-18	0	1	D1.F7 - Qual a sua avaliacao da comunicacao interna?
17	4	N	2018-03-27	0	1	teste1
18	3	N	2018-03-27	0	1	teste2
19	1	N	2018-03-27	0	1	teste
20	4	S	2018-10-05	0	1	D3.F03.01 - Informe sua Lotacao Atual
21	1	S	2018-10-05	0	1	D3.F03.02 - Informe o Cargo Atual
22	1	S	2018-10-05	0	1	D3.F03.03 - Informe sua Area de Atuacao
\.


--
-- TOC entry 4091 (class 0 OID 10423076)
-- Dependencies: 312
-- Data for Name: tb_frase_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_frase_pesquisa (idfrasepesquisa, idcadastrador, domtipofrase, flaativo, datcadastro, idescritorio, desfrase) FROM stdin;
53	1	1	S	2015-04-01	0	2015.4 - Informe a sua area de atuacao nas OLIMPIADAS 2016.
1	1	3	S	2014-03-18	0	Pergunta - Teste 2
2	1	2	S	2014-03-18	0	Pergunta - Teste 3
3	1	5	S	2014-03-18	0	Pergunta 2
4	1	1	S	2014-04-02	0	A CARGA HORARIA TOTAL utilizada para o desenvolvimento do evento de capacitacao foi:
5	1	1	S	2014-04-14	0	Qual a sua impressao sobre a unidade
6	1	3	S	2014-04-14	0	O que vc achou sobre o parque de servidores da unidade? Descreva
7	1	2	S	2014-03-18	0	Pergunta - Teste 3
8	1	1	S	2014-04-02	0	A CARGA HORARIA TOTAL utilizada para o desenvolvimento do evento de capacitacao foi:
9	1	1	S	2014-04-02	0	A CARGA HORARIA TOTAL utilizada para o desenvolvimento do evento de capacitacao foi:
10	1	3	S	2014-03-18	0	Pergunta - Teste 2
11	1	2	S	2014-03-18	0	Pergunta - Teste 3
12	1	5	S	2014-03-18	0	Pergunta 2
13	1	1	S	2014-04-02	0	A CARGA HORARIA TOTAL utilizada para o desenvolvimento do evento de capacitacao foi:
14	1	3	S	2014-04-14	0	O que vc achou sobre o parque de servidores da unidade? Descreva
15	1	3	S	2014-03-18	0	Pergunta - Teste 2
16	1	2	S	2014-03-18	0	Pergunta - Teste 3
17	1	7	S	2014-03-18	0	Teste 1
18	1	5	S	2014-03-18	0	Pergunta 2
19	1	1	S	2014-04-02	0	A CARGA HORARIA TOTAL utilizada para o desenvolvimento do evento de capacitacao foi:
20	1	1	S	2014-04-14	0	Qual a sua impressao sobre a unidade
21	1	3	S	2014-04-14	0	O que vc achou sobre o parque de servidores da unidade? Descreva
22	1	1	S	2014-04-02	0	A CARGA HORARIA TOTAL utilizada para o desenvolvimento do evento de capacitacao foi:
23	1	1	S	2014-04-14	0	Qual a sua impressao sobre a unidade
24	1	3	S	2014-04-14	0	O que vc achou sobre o parque de servidores da unidade? Descreva
25	1	3	S	2014-03-18	0	Pergunta - Teste 2
26	1	2	S	2014-03-18	0	Pergunta - Teste 3
27	1	1	S	2014-04-02	0	A CARGA HORARIA TOTAL utilizada para o desenvolvimento do evento de capacitacao foi:
28	1	1	S	2014-04-14	0	Qual a sua impressao sobre a unidade
29	1	7	S	2014-07-21	0	Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscingyttsss elitis. Pra la , depois divoltisorris, pdis. Paisis, filhis, espiritis santis. Me faiz elementum girarzis, nisi eros vermeio, in elementis me pra quem e amistosis quis leo. Mandhkjh
30	1	3	S	2014-03-18	0	Pergunta - Teste 2
31	1	2	S	2014-03-18	0	Pergunta - Teste 3
32	1	7	S	2014-03-18	0	Teste 1
33	1	1	S	2014-04-02	0	A CARGA HORARIA TOTAL utilizada para o desenvolvimento do evento de capacitacao foi:
34	1	1	S	2014-04-14	0	Qual a sua impressao sobre a unidade
35	1	3	S	2014-04-14	0	O que vc achou sobre o parque de servidores da unidade? Descreva
36	1	1	S	2014-08-22	0	2014.P1.Q1 - Informe ha quanto tempo voce atua na unidade.  Informe, conforme a escala, o tempo total em anos de sua atuacao na unidade
37	1	1	S	2014-08-22	0	2014.P1.Q2 - Informa a sua LOTACAO ATUAL.
38	1	1	S	2014-08-22	0	2014.P1.Q1 - Informe ha quanto tempo voce atua na unidade.  Informe, conforme a escala, o tempo total em anos de sua atuacao na unidade
39	1	1	S	2014-08-22	0	2014.P1.Q2 - Informa a sua LOTACAO ATUAL.
40	1	1	S	2014-08-22	0	2014.P1.Q1 - Informe ha quanto tempo voce atua na unidade.  Informe, conforme a escala, o tempo total em anos de sua atuacao na unidade
41	1	1	S	2014-08-22	0	2014.P1.Q2 - Informa a sua LOTACAO ATUAL.
42	1	1	S	2014-08-28	0	2014.P1.Q3 - Informe a cidade-sede do evento Copa do Mundo FIFA 2014 em que voce atuou. Para fins de resposta à esta questao, considere a cidade-sede onde voce atuou na maior parte do tempo durante a mobilizacao para a Copa do Mundo FIFA 2014.
43	1	1	S	2014-08-28	0	2014.P1.Q4 - Informe a sua area de atuacao no evento Copa do Mundo FIFA 2014. Selecione a sua area de atuacao (coordenacao) no evento Copa do Mundo FIFA 2014.
46	1	5	S	2014-03-18	0	2016.1 Informe o seu numero favorito, de 1 a 1000:
47	1	1	S	2014-08-22	0	2014.P1.Q1 - Informe ha quanto tempo voce atua na unidade.  Informe, conforme a escala, o tempo total em anos de sua atuacao na unidade
48	1	1	S	2014-08-22	0	2014.P1.Q2 - Informa a sua LOTACAO ATUAL.
49	1	2	S	2014-08-28	0	2014.P1.Q3 - Informe a cidade-sede do evento Copa do Mundo FIFA 2014 em que voce atuou. Para fins de resposta à esta questao, considere a cidade-sede onde voce atuou na maior parte do tempo durante a mobilizacao para a Copa do Mundo FIFA 2014.
50	1	1	S	2014-08-28	0	2014.P1.Q4 - Informe a sua area de atuacao no evento Copa do Mundo FIFA 2014. Selecione a sua area de atuacao (coordenacao) no evento Copa do Mundo FIFA 2014.
51	1	1	S	2014-07-21	0	D1.F6 - Atribua uma AVALIACAO GERAL em relacao ao servico utilizado por voce nessa Unidade.
52	1	1	S	2014-08-22	0	2014.P1.Q1 - Informe ha quanto tempo voce atua na unidade.  Informe, conforme a escala, o tempo total em anos de sua atuacao na unidade
54	1	1	S	2014-04-02	0	D1.F6 - Avalie a CONSERVACAO E LIMPEZA do local de atendimento ao publico dessa Unidade.
55	1	1	S	2014-04-14	0	2016.2 Informe o objetivo institucional que voce considera mais relevante.
56	1	3	S	2014-04-14	0	2015.2 O que voce entende por Planejamento estrategico? Descreva
45	1	1	S	2014-10-02	0	Informe seu e-mail.
44	1	2	S	2014-10-02	0	Informe seu telefone.
\.


--
-- TOC entry 4092 (class 0 OID 10423081)
-- Dependencies: 313
-- Data for Name: tb_funcionalidade; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_funcionalidade (idfuncionalidade, no_funcionalidade, ds_funcionalidade, dtcadastro) FROM stdin;
1	Cadastrar Pessoa	Cadastro de pessoas	2014-04-10
3	Cadastro de Pessoa	Cadastrar Pessoas	2014-07-02
4	Cadastro de Escritorio	Cadastro de Escritorio	2014-07-02
5	Cadastro de Agenda	Cadastro de Agenda	2014-07-02
2	Cadastrar Escritorio w	Cadastro de escritorios	2014-04-10
\.


--
-- TOC entry 4093 (class 0 OID 10423084)
-- Dependencies: 314
-- Data for Name: tb_hst_publicacao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_hst_publicacao (idhistoricopublicacao, idpesquisa, datpublicacao, datencerramento, idpespublicou, idpesencerrou) FROM stdin;
\.


--
-- TOC entry 4094 (class 0 OID 10423087)
-- Dependencies: 315
-- Data for Name: tb_item_secao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_item_secao (id_item, ds_item, id_secao, ativo, idquestionariodiagnostico) FROM stdin;
\.


--
-- TOC entry 4095 (class 0 OID 10423091)
-- Dependencies: 316
-- Data for Name: tb_licao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_licao (idlicao, idprojeto, identrega, desresultadosobtidos, despontosfortes, despontosfracos, dessugestoes, datcadastro, idassociada) FROM stdin;
1	1	2	entrega 1 - resultados obtidos	pontos fortes da entrega 1	pontos fracos da entrega 1\r\ndificuldades encontradas/enfrentadas para executar entrega1	seguir as licoes aprendidas deste projeto\r\ncomunicar a experiencia deste projeto ao PMO\r\npublicar resultados nos meios de comunicacao disponiveis	2015-06-08	1
\.


--
-- TOC entry 4096 (class 0 OID 10423097)
-- Dependencies: 317
-- Data for Name: tb_linhatempo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_linhatempo (id, idpessoa, dsfuncaoprojeto, tpacao, dtacao, idprojeto, idrecurso) FROM stdin;
1	1	Gerente do Projeto	A	2022-11-16 17:45:11.442732-03	1	44
2	1	Gerente do Projeto	A	2022-11-16 17:50:45.512122-03	1	44
\.


--
-- TOC entry 4097 (class 0 OID 10423100)
-- Dependencies: 318
-- Data for Name: tb_logacesso; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_logacesso (idmodulo, idperfilpessoa, datacesso) FROM stdin;
\.


--
-- TOC entry 4098 (class 0 OID 10423103)
-- Dependencies: 319
-- Data for Name: tb_manutencaogepnet; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_manutencaogepnet (idmanutencaogepnet, numprioridade, datfimmeta, datfimreal, desmanutencaogepnet, desobs, idcadastrador, datcadastro, despaginaphp, domtipomanutencao, domsituacao) FROM stdin;
\.


--
-- TOC entry 4099 (class 0 OID 10423109)
-- Dependencies: 320
-- Data for Name: tb_marco; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_marco (idmarco, idprojeto, numseq, nommarco, datplanejado, datprevisto, datencerrado, idcadastrador, datcadastro, idresponsavel) FROM stdin;
\.


--
-- TOC entry 4100 (class 0 OID 10423113)
-- Dependencies: 321
-- Data for Name: tb_modulo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_modulo (idmodulo, idmodulopai, numsequencial, nomitemmenu, deslink, flaativo, flaitemmenu) FROM stdin;
\.


--
-- TOC entry 4101 (class 0 OID 10423116)
-- Dependencies: 322
-- Data for Name: tb_mudanca; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_mudanca (idmudanca, idprojeto, nomsolicitante, datsolicitacao, datdecisao, desmudanca, desjustificativa, despareceregp, desaprovadores, despareceraprovadores, idcadastrador, idtipomudanca, datcadastro, flaaprovada) FROM stdin;
1	1	Patrocinador	2015-10-12	2015-10-14	teste	teste	teste		teste	1	2	2015-06-08	S
2	1	Patrocinador	2016-03-08	2016-03-11	TESTE	TESTE	TESTE		TESTE	1	3	2016-03-08	S
\.


--
-- TOC entry 4102 (class 0 OID 10423123)
-- Dependencies: 323
-- Data for Name: tb_natureza; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_natureza (idnatureza, nomnatureza, idcadastrador, datcadastro, flaativo) FROM stdin;
9	OUTROS	1	2015-05-26	S
1	ROTINA ADMINISTRATIVA	1	2014-03-18	S
2	ROTINA OPERACIONAL	1	2014-03-18	S
3	INFRAESTRUTURA E OBRAS	1	2014-03-18	S
4	CAPACITACAO	1	2014-03-18	S
5	AQUISICAO	1	2014-03-18	S
6	ESTRUTURACAO ORGANIZACIONAL	1	2014-03-18	S
7	CRIACAO/MELHORIA PROCESSO DE TRABALHO	1	2015-02-13	S
8	TI E COMUNICACOES	1	2015-02-13	S
\.


--
-- TOC entry 4103 (class 0 OID 10423128)
-- Dependencies: 324
-- Data for Name: tb_objetivo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_objetivo (idobjetivo, nomobjetivo, idcadastrador, datcadastro, flaativo, desobjetivo, codescritorio, numseq) FROM stdin;
2	Objetivo estrategico 2	1	2015-03-05	S	Descricao do objetivo estrategico	1	2
1	Objetivo estrategico 1	1	2015-03-05	S	Descricao do objetivo estrategico	1	1
\.


--
-- TOC entry 4104 (class 0 OID 10423138)
-- Dependencies: 325
-- Data for Name: tb_opcao_resposta; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_opcao_resposta (idresposta, idpergunta, desresposta, escala, ordenacao, idquestionario) FROM stdin;
\.


--
-- TOC entry 4105 (class 0 OID 10423141)
-- Dependencies: 326
-- Data for Name: tb_origemrisco; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_origemrisco (idorigemrisco, desorigemrisco, idcadastrador, dtcadastro) FROM stdin;
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
-- TOC entry 4106 (class 0 OID 10423144)
-- Dependencies: 327
-- Data for Name: tb_p_acao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_p_acao (id_p_acao, idprojetoprocesso, nom_p_acao, des_p_acao, datinicioprevisto, datinicioreal, datterminoprevisto, datterminoreal, idsetorresponsavel, flacancelada, idcadastrador, datcadastro, numseq, idresponsavel) FROM stdin;
1	1	Elaborar minuta de portaria	sdb sdfsd bsdf db alka kla lkllkf kjhbd blsdbhdh bh sdlkhslkbjhsdk	2015-06-10	2015-06-12	2015-06-12	2015-06-12	1	2	1	2015-06-10 16:32:36.958511-03	1	1
2	1	Enviar comunicacao para partes interessadas	kajhf opioaso aoop  po po poasad oasdoasd psodsadoksdaop pos  dsd as asdo	2015-06-03	2015-06-08	2015-06-26	2015-06-22	2	2	1	2015-06-11 09:41:07.471993-03	2	1
\.


--
-- TOC entry 4107 (class 0 OID 10423152)
-- Dependencies: 328
-- Data for Name: tb_partediagnostico; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_partediagnostico (idpartediagnostico, iddiagnostico, qualificacao, idcadastrador, datcadastro, idpessoa, tppermissao) FROM stdin;
\.


--
-- TOC entry 4108 (class 0 OID 10423157)
-- Dependencies: 329
-- Data for Name: tb_parteinteressada; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_parteinteressada (idparteinteressada, idprojeto, nomparteinteressada, nomfuncao, destelefone, desemail, domnivelinfluencia, idcadastrador, datcadastro, idpessoainterna, observacao, tppermissao, status) FROM stdin;
2	1	Usuario02	Patrocinador	(99) 9999-9999	usuario02@gepnet2.gov	Alto	1	2022-10-11 17:01:41.098136-03	2	teste                                                                                                                                                                                                   	1	t
3	1	Usuario01	Gerente do Projeto	(99) 9999-9999	usuario01@gepnet2.gov	Alto	1	2022-10-11 17:08:19.504639-03	1	teste                                                                                                                                                                                                   	1	t
4	2	Usuario01	\N	(99) 9999-9999	usuario01@gepnet2.gov	Alto	1	2022-10-11 17:13:31.786887-03	1	teste1                                                                                                                                                                                                  	1	t
5	2	Usuario03	\N	(99) 9999-9999	usuario03@gepnet2.gov	Alto	1	2022-10-11 17:14:25.423292-03	3	teste1                                                                                                                                                                                                  	1	t
6	2	Usuario04	\N	(99) 9999-9999	usuario04@gepnet2.gov	Alto	1	2022-10-11 17:15:09.066215-03	4	teste1                                                                                                                                                                                                  	1	t
7	2	Usuario05	\N	(99) 9999-9999	usuario05@gepnet2.gov	Alto	1	2022-10-11 17:26:57.009083-03	5	teste1                                                                                                                                                                                                  	1	t
8	1	Usuario03	\N	(99) 9999-9999	usuario03@gepnet2.gov	Medio	1	2022-10-13 12:00:19.311003-03	3	teste                                                                                                                                                                                                   	1	t
9	1	Usuario04	\N	(99) 9999-9999	usuario04@gepnet2.gov	Baixo	1	2022-10-13 12:01:01.708882-03	4	teste                                                                                                                                                                                                   	2	t
10	1	Usuario05	\N	(99) 9999-9999	user@net.com	Baixo	1	2022-10-13 12:01:38.487912-03	5	teste                                                                                                                                                                                                   	2	t
11	1	Parte interessada externa 1	\N	(99) 9999-9999	user1@net.com	Medio	1	2022-10-13 12:02:28.522337-03	\N	teste                                                                                                                                                                                                   	\N	t
\.


--
-- TOC entry 4109 (class 0 OID 10423165)
-- Dependencies: 330
-- Data for Name: tb_parteinteressada_funcoes; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_parteinteressada_funcoes (idparteinteressada, idparteinteressadafuncao) FROM stdin;
2	5
3	4
11	5
3	1
8	6
5	6
9	6
6	4
7	3
\.


--
-- TOC entry 4110 (class 0 OID 10423168)
-- Dependencies: 331
-- Data for Name: tb_parteinteressadafuncao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_parteinteressadafuncao (idparteinteressadafuncao, nomfuncao, numordem) FROM stdin;
1	Gerente do Projeto	3
2	Gerente Adjunto do Projeto	4
3	Demandante	5
4	Patrocinador	6
5	Parte Interessada	1
6	Equipe do Projeto	2
7	Colaborador	7
\.


--
-- TOC entry 4112 (class 0 OID 10423174)
-- Dependencies: 333
-- Data for Name: tb_perfil; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_perfil (idperfil, nomperfil, flaativo, idcadastrador, datcadastro) FROM stdin;
1	Admin GEPnet	S	1	2015-06-08
2	Administrador Setorial	S	1	2015-06-08
3	Escritorio de Projetos	S	1	2015-06-08
4	Gerente de Projeto	S	1	2015-06-08
10	Escritorio de Processos	S	1	2015-06-08
12	Pesquisa	S	1	2015-06-08
11	Acordo de Cooperacao	S	1	2015-06-08
9	Consultor de processos	S	1	2015-06-08
7	Consulta	S	1	2015-06-08
5	Assistente de projeto	S	1	2015-06-08
6	Assistente de cronograma	S	1	2015-06-08
8	Assistente de riscos	S	1	2015-06-08
13	Coordenador Grandes Eventos	S	1	2015-06-08
14	Avaliador Grandes Eventos	S	1	2015-06-08
15	Assistente Grandes Eventos	S	1	2015-06-08
16	Seguranca fabrica software	S	1	2018-10-01
\.


--
-- TOC entry 4113 (class 0 OID 10423178)
-- Dependencies: 334
-- Data for Name: tb_perfilmodulo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_perfilmodulo (idperfil, idmodulo) FROM stdin;
\.


--
-- TOC entry 4114 (class 0 OID 10423181)
-- Dependencies: 335
-- Data for Name: tb_perfilpessoa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_perfilpessoa (idpessoa, idperfil, idescritorio, flaativo, idcadastrador, datcadastro, idperfilpessoa) FROM stdin;
2	14	1	S	1	2015-09-10	30
3	4	1	S	1	2015-10-02	38
4	4	0	S	1	2015-10-02	39
4	4	1	S	1	2015-10-02	40
4	4	2	S	1	2015-10-02	41
5	7	0	S	1	2015-10-02	42
5	7	1	S	1	2015-10-02	43
5	7	2	S	1	2015-10-02	44
3	4	0	S	1	2015-10-02	35
2	1	1	S	1	2015-09-10	17
2	3	1	S	1	2015-09-10	19
2	4	1	S	1	2015-09-10	20
2	12	1	S	1	2015-09-10	28
2	15	1	S	1	2015-09-10	31
2	16	1	S	1	2015-12-11	32
3	2	0	S	1	2015-10-02	33
3	2	1	S	1	2015-10-02	36
3	3	1	S	1	2015-10-02	37
1	9	0	S	1	2015-11-12	9
1	10	0	S	1	2016-01-07	10
2	6	1	S	1	2015-11-16	22
2	7	1	S	1	2015-11-16	23
2	9	1	S	1	2015-11-16	25
2	11	1	S	1	2015-11-16	27
1	12	0	S	1	2016-06-21	12
1	13	0	S	1	2016-07-01	13
1	14	0	S	1	2016-08-30	14
2	2	1	S	1	2016-07-05	18
2	5	1	S	1	2016-06-01	21
1	15	0	S	1	2016-10-24	15
1	16	0	S	1	2017-05-11	16
2	8	1	S	1	2016-07-05	24
3	3	0	S	1	2016-07-05	34
1	2	0	S	1	2015-09-10	2
1	3	0	S	1	2015-09-10	3
1	4	0	S	1	2015-09-10	4
1	5	0	S	1	2015-09-10	5
1	6	0	S	1	2015-10-02	6
1	7	0	S	1	2015-10-02	7
1	8	0	S	1	2015-11-12	8
1	11	0	S	1	2016-04-29	11
1	1	0	S	1	2015-05-26	1
2	10	1	S	1	2015-11-16	26
2	13	1	S	1	2016-07-05	29
\.


--
-- TOC entry 4115 (class 0 OID 10423185)
-- Dependencies: 336
-- Data for Name: tb_pergunta; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_pergunta (idpergunta, dspergunta, tipopergunta, ativa, idquestionario, posicao, id_secao, tiporegistro, dstitulo) FROM stdin;
\.


--
-- TOC entry 4116 (class 0 OID 10423192)
-- Dependencies: 337
-- Data for Name: tb_perm_funcionalidade; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_perm_funcionalidade (idpermissao, idfuncionalidade, principal, publicada, dtpublicada) FROM stdin;
\.


--
-- TOC entry 4117 (class 0 OID 10423197)
-- Dependencies: 338
-- Data for Name: tb_permissao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_permissao (idpermissao, idrecurso, ds_permissao, no_permissao, visualizar, tipo) FROM stdin;
3	3	\N	login	f	\N
4	8	\N	sair	f	\N
5	8	\N	boasVindas	f	\N
6	8	\N	logout	f	\N
7	8	\N	index	f	\N
8	8	\N	perfil	f	\N
9	8	\N	mudarPerfil	f	\N
10	8	\N	generate	f	\N
11	8	\N	teste	f	\N
12	8	\N	siseg	f	\N
13	10	\N	acl	f	\N
14	9	\N	index	f	\N
15	9	\N	pesquisarjson	f	\N
16	9	\N	detalhar	f	\N
17	9	\N	cadastrar	f	\N
18	9	\N	editar	f	\N
19	9	\N	editarArquivo	f	\N
20	9	\N	excluir	f	\N
21	9	\N	abrir	f	\N
22	11	\N	index	f	\N
23	11	\N	pesquisarjson	f	\N
24	11	\N	detalhar	f	\N
25	11	\N	add	f	\N
26	11	\N	editar	f	\N
27	11	\N	pesquisarviewcomumjson	f	\N
28	11	\N	importarjson	f	\N
29	4	\N	default	f	\N
30	4	\N	index	f	\N
31	5	\N	concederPermissao	f	\N
32	5	\N	revogarPermissao	f	\N
33	5	\N	permissao	f	\N
34	2	\N	index	f	\N
35	2	\N	detalhar	f	\N
36	2	\N	pesquisar	f	\N
37	2	\N	editar	f	\N
38	12	\N	index	f	\N
39	12	\N	pesquisarjson	f	\N
40	12	\N	detalhar	f	\N
41	12	\N	add	f	\N
42	12	\N	edit	f	\N
43	12	\N	buscarjson	f	\N
44	12	\N	importarjson	f	\N
45	13	\N	index	f	\N
46	13	\N	pesquisarjson	f	\N
47	13	\N	detalhar	f	\N
48	13	\N	add	f	\N
49	13	\N	editar	f	\N
50	13	\N	pesquisarviewcomumjson	f	\N
51	13	\N	importarjson	f	\N
52	1	\N	index	f	\N
53	1	\N	gerenciar	f	\N
54	1	\N	retornaPorPerfil	f	\N
55	1	\N	novosRecursos	f	\N
56	1	\N	pesquisar	f	\N
57	1	\N	detalhar	f	\N
58	1	\N	cadastrar	f	\N
59	14	\N	index	f	\N
60	14	\N	detalhar	f	\N
61	14	\N	add	f	\N
62	14	\N	edit	f	\N
63	14	\N	buscarjson	f	\N
64	14	\N	pesquisarjson	f	\N
65	7	\N	index	f	\N
66	7	\N	detalhar	f	\N
67	7	\N	add	f	\N
68	7	\N	edit	f	\N
69	7	\N	buscarjson	f	\N
70	7	\N	pesquisarjson	f	\N
78	8	f asdf asd fasdf asfd asdf 	abc	f	\N
79	15	\N	index	f	\N
553	91	Incluir unidades	unidades-filhas	t	G
546	91	Listar diagnostico	index	t	G
87	17	\N	index	f	\N
88	17	\N	detalhar	f	\N
89	17	\N	add	f	\N
90	17	\N	edit	f	\N
91	17	\N	buscarjson	f	\N
1	3	\N	error	f	\N
2	3	\N	acl	f	\N
92	17	\N	pesquisarjson	f	\N
93	18	\N	index	f	\N
94	18	\N	add	f	\N
95	18	\N	edit	f	\N
96	18	\N	detalhar	f	\N
97	18	\N	buscarjson	f	\N
98	18	\N	pesquisarjson	f	\N
99	19	\N	index	f	\N
101	2	\N	retornaPorRecurso	f	\N
100	12	\N	pesquisar-sem-unidade	f	\N
103	5	\N	conceder-permissao	f	\N
104	5	\N	revogar-permissao	f	\N
105	2	\N	retorna-por-recurso	f	\N
106	8	\N	mudar-perfil	f	\N
108	9	\N	editar-arquivo	f	\N
109	1	\N	teste	f	\N
110	1	\N	retorna-por-perfil	f	\N
111	1	\N	novos-recursos	f	\N
112	20	\N	index	f	\N
115	20	\N	detalhar	f	\N
116	20	\N	add	f	\N
117	20	\N	edit	f	\N
118	20	\N	buscarjson	f	\N
119	20	\N	pesquisarjson	f	\N
122	15	\N	cadastrar	f	\N
123	15	\N	detalhar	f	\N
124	15	\N	pesquisarjson	f	\N
125	15	\N	form-excluir	f	\N
126	15	\N	generate	f	\N
127	22	\N	cadastrar	f	\N
128	22	\N	detalhar	f	\N
129	22	\N	pesquisarjson	f	\N
130	22	\N	form-excluir	f	\N
133	23	\N	index	f	\N
134	23	\N	add	f	\N
136	22	\N	pesquisar	f	\N
137	22	\N	form-editar	f	\N
138	22	\N	excluir	f	\N
139	22	\N	editar	f	\N
140	22	\N	index	f	\N
141	15	\N	excluir	f	\N
142	15	\N	form-editar	f	\N
143	15	\N	pesquisar	f	\N
144	15	\N	editar	f	\N
146	22	\N	generate	f	\N
147	24	\N	index	f	\N
148	24	\N	out	f	\N
150	23	\N	acompanhar	f	\N
157	25	\N	index	f	\N
159	25	\N	cadastro	f	\N
160	25	\N	consulta	f	\N
161	26	\N	index	f	\N
163	27	\N	index	f	\N
162	25	teste	update	f	\N
164	27	\N	imprimir	f	\N
71	6	\N	index	t	G
72	6	\N	pesquisarjson	t	G
73	6	\N	detalhar	t	G
74	6	\N	add	t	E
75	6	\N	editar	t	E
76	6	\N	pesquisarviewcomumjson	t	G
77	6	\N	importarjson	t	E
149	23	\N	pesquisarjson	t	G
151	23	\N	detalhar	t	G
152	23	\N	chartplanejadorealizadojson	t	G
155	23	\N	chartatrasojson	t	G
156	23	\N	chartprazojson	t	G
168	28	\N	_imprimir	f	\N
107	8	teste de texto	boas-vindas	f	\N
176	32	\N	add	f	\N
177	32	\N	index	f	\N
178	25	\N	delete	f	\N
179	25	\N	lista2	f	\N
180	26	\N	pesquisar	f	\N
181	26	\N	form-editar	f	\N
182	26	\N	excluir	f	\N
183	33	\N	index	f	\N
158	21	retorna-inicio-base-line	retorna-inicio-base-line	t	G
154	21	retorna-projeto	retorna-projeto	t	G
153	21	index2	index2	t	E
184	33	\N	del	f	\N
185	33	\N	consulta	f	\N
186	33	\N	delete	f	\N
187	33	\N	lista2	f	\N
188	25	\N	lista	f	\N
189	26	\N	pesquisarjson	f	\N
190	33	\N	lista	f	\N
191	33	\N	update	f	\N
192	33	\N	editar	f	\N
193	33	\N	cadastrar	f	\N
194	26	\N	generate	f	\N
195	26	\N	form-excluir	f	\N
196	26	\N	detalhar	f	\N
197	32	\N	pesquisarjson	f	\N
200	32	\N	edit	f	\N
554	91	Clonar diagnostico	clonar-add	t	E
204	32	\N	relatorio	f	\N
547	91	pesquisar diagnostico	pesquisar	t	G
203	32	\N	detalhar	f	\N
205	32	\N	imprimir	f	\N
206	12	\N	associarperfil	f	\N
207	34	\N	index	f	\N
208	34	\N	associarperfil	f	\N
209	34	\N	pesquisarjson	f	\N
210	34	\N	trocarsituacao	f	\N
211	23	\N	listar	f	\N
212	23	\N	listarjson	f	\N
215	23	\N	relatorio	f	\N
226	36	\N	index	f	\N
305	45	\N	index	f	\N
306	45	\N	add	f	\N
307	45	\N	editar	f	\N
308	45	\N	detalhar	f	\N
309	45	\N	pesquisarjson	f	\N
314	46	\N	pesquisarjson	f	\N
315	46	\N	editar	f	\N
316	46	\N	add	f	\N
317	46	\N	detalhar	f	\N
318	46	\N	index	f	\N
319	47	\N	listar	f	\N
320	47	\N	cadastrar	f	\N
321	47	\N	editar	f	\N
322	47	\N	excluir	f	\N
323	47	\N	detalhar	f	\N
324	47	\N	pesquisar	f	\N
325	48	\N	index	f	\N
326	48	\N	add	f	\N
249	38	Listar	listar	t	G
268	28	Salvar	cadastrar	t	E
300	44	Listar	listar	t	G
165	23	\N	chartmarcojson	t	G
246	38	\N	partesinteressadas	t	E
311	44	Editar	editar	t	E
217	35	Listar	index	t	G
275	29	Excluir	excluir	t	E
297	41	Excluir Entrega	excluir-entrega	t	E
286	41	\N	retorna-projeto	t	G
299	41	Excluir Grupo	excluir-grupo	t	E
198	21	Editar Entrega	editar-entrega	t	E
256	21	Atualizar Baseline	atualizar-baseline	t	E
270	21	Visualizar Impressao	imprimir	t	G
213	21	Atualizar Percentual	atividade-atualizar-percentual	t	E
222	21	Excluir Entrega	excluir-entrega	t	E
287	42	Visualizar	visualizar	t	G
237	21	Clonar Grupo	clonar-grupo	t	E
278	40	Excluir	excluir	t	E
280	40	Pesquisar	pesquisar	t	G
254	38	Cadastrar Externo	addexterno	t	E
228	37	\N	index	t	G
257	38	Editar Externo	editarexterno	t	E
253	38	Cadastrar Interno	addinterno	t	E
250	38	\N	grid-rh	t	G
227	6	\N	resumo	t	G
238	37	Excluir	excluir	t	E
236	37	\N	grid-comunicacao	t	G
232	37	Cadastrar	add	t	E
313	44	Detalhar	detalhar	t	G
312	44	Excluir	excluir	t	E
301	44	Cadastrar	cadastrar	t	E
295	43	Excluir	excluir	t	E
298	43	\N	pesquisarjson	t	G
171	30	Listar	index	t	G
225	35	Editar	editar	t	E
172	30	Imprimir	imprimir	t	G
229	35	Excluir	excluir	t	E
255	38	Editar Interno	editarinterno	t	E
282	29	\N	buscar-entrega	t	G
174	31	\N	index	t	G
170	29	Imprimir	imprimir	t	G
234	37	Editar	edit	t	E
292	43	\N	relatoriojson	t	G
245	16	\N	partesinteressadasexterno	t	E
285	41	Listar	index	t	G
290	41	Editar Entrega	editar-entrega	t	E
220	21	Excluir Grupo	excluir-grupo	t	E
233	21	Clonar Entrega	clonar-entrega	t	E
252	21	Atualizar Baseline da Atividade	atualizar-baseline-atividade	t	E
251	38	Detalhar	detalhar	t	G
247	38	\N	partesinteressadasexterno	t	E
248	38	Excluir Parte	excluirparte	t	E
230	37	\N	pesquisar-parte-interessada	t	G
231	37	\N	grid-parte-interessada	t	G
235	37	Listar	listar	t	G
241	37	Detalhar	detalhar	t	G
267	28	Excluir	excluir	t	E
294	43	Editar	editar	t	E
277	40	Editar	editar	t	E
219	35	Cadastrar	add	t	E
216	23	\N	relatoriojson	t	G
169	29	Listar	index	t	G
283	29	\N	retornaaceitesjson	t	G
284	29	Imprimir Todos	imprimir-todos	t	G
175	31	\N	imprimir	t	G
327	48	\N	detalhar	f	\N
328	48	\N	editar	f	\N
329	48	\N	pesquisarjson	f	\N
332	49	\N	pesquisarjson	f	\N
333	49	\N	file-tree	f	\N
334	49	\N	filetree	f	\N
555	92	error	forbidden	t	G
548	91	Visualizar dados do diagnostico	detalhar	t	G
345	50	\N	index	f	\N
346	50	\N	detalhar	f	\N
347	51	\N	listar	f	\N
348	51	\N	cadastrar	f	\N
349	51	\N	editar	f	\N
350	51	\N	detalhar	f	\N
351	51	\N	excluir	f	\N
352	51	\N	pesquisar	f	\N
353	52	\N	listar	f	\N
354	52	\N	pesquisar	f	\N
355	52	\N	detalhar	f	\N
356	52	\N	excluir	f	\N
357	52	\N	editar	f	\N
262	39	listar	listar	t	G
264	28	grid-ata	grid-ata	t	G
166	28	index	index	t	G
240	21	pesquisarprojetojson	pesquisarprojetojson	t	G
214	21	atualizar-dom-tipo-atividade	atualizar-dom-tipo-atividade	t	E
218	35	relatoriojson	relatoriojson	t	G
358	52	\N	cadastrar	f	\N
359	50	\N	chartorcamentarioprojetosprogramajson	f	\N
360	50	\N	chartprojetosprogramajson	f	\N
361	50	\N	chartprojetosnaturezajson	f	\N
362	50	\N	pesquisarprojeto	f	\N
363	50	\N	pesquisarprojetojson	f	\N
364	53	\N	index	f	\N
367	53	\N	add	f	\N
371	54	\N	listar	f	\N
372	54	\N	pesquisar	f	\N
373	54	\N	cadastrar	f	\N
374	53	\N	pesquisarjson	f	\N
375	54	\N	editar	f	\N
376	54	\N	detalhar	f	\N
377	54	\N	listar-perguntas	f	\N
378	54	\N	vincular-pergunta	f	\N
379	53	\N	edit	f	\N
380	53	\N	participantes	f	\N
381	54	\N	pesquisar-perguntas	f	\N
382	53	\N	excluirparticipante	f	\N
383	54	\N	editar-vinculo-pergunta	f	\N
384	54	\N	detalhar-vinculo-pergunta	f	\N
385	55	\N	add	f	\N
386	55	\N	index	f	\N
387	55	\N	pesquisar	f	\N
388	54	\N	desvincular-pergunta	f	\N
389	55	\N	detalhar	f	\N
390	55	\N	editar	f	\N
391	53	\N	detalhar	f	\N
392	53	\N	excluir	f	\N
393	12	\N	gridagenda	f	\N
394	12	\N	pesquisar-pessoa-agenda	f	\N
395	56	\N	index	f	\N
396	56	\N	cadastrar	f	\N
397	53	\N	retorna-dias-com-eventos	f	\N
398	57	\N	index	f	\N
401	56	\N	pesquisarjson	f	\N
402	59	\N	listar	f	\N
403	59	\N	pesquisar	f	\N
404	59	\N	publicar	f	\N
405	56	\N	editar	f	\N
407	56	\N	detalhar	f	\N
410	59	\N	gerenciar-pesquisas	f	\N
411	59	\N	listar-publicadas	f	\N
412	59	\N	publicar-encerrar	f	\N
413	59	\N	pesquisas-respondidas	f	\N
415	59	\N	listar-respostas-pesquisa	f	\N
416	59	\N	resposta-pesquisa	f	\N
418	50	\N	gerenciar	f	\N
419	50	\N	cadastrar	f	\N
420	50	\N	editar	f	\N
421	50	\N	portfolioestrategico	f	\N
422	59	\N	detalhar-pesquisa	f	\N
423	50	\N	pesquisarportfoliojson	f	\N
424	59	\N	pesquisa-duplicada	f	\N
425	61	\N	pesquisar	f	\N
426	61	\N	listar	f	\N
427	61	\N	responder-pesquisa	f	\N
428	62	\N	index	f	\N
429	62	\N	pesquisarjson	f	\N
430	62	\N	add	f	\N
431	11	\N	buscar-escritorio	f	\N
432	54	\N	alterar-disponibilidade	f	\N
434	54	\N	status-questionario	f	\N
436	55	\N	cadastrar	f	\N
437	63	\N	listar	f	\N
438	63	\N	pesquisar	f	\N
439	63	\N	relatorio-percentual	f	\N
440	62	\N	detalhar	f	\N
441	62	\N	editar	f	\N
442	63	\N	relatorio-tabelado	f	\N
445	64	\N	listar	f	\N
446	64	\N	pesquisar	f	\N
448	62	\N	download	f	\N
449	55	\N	gerenciar-permissoes	f	\N
450	6	\N	phpinfo	f	\N
453	6	\N	query	f	\N
454	61	\N	autenticar	f	\N
455	61	\N	responder-externa	f	\N
102	12	Permite visualizar o grid de pessoas do sistema.	grid	f	\N
114	12	Funcao de busca de pessoa no sistema.	buscar	f	\N
458	63	\N	imprimir-relatorio	f	\N
459	63	\N	imprimir-tabelado	f	\N
462	65	\N	add	f	\N
463	66	\N	index	f	\N
465	68	\N	listar	f	\N
466	69	\N	listar	f	\N
467	70	\N	listar	f	\N
468	71	\N	gerenciar-pesquisas	f	\N
469	72	\N	listar	f	\N
470	73	\N	listar	f	\N
471	74	\N	listar	f	\N
472	75	\N	index	f	\N
473	76	\N	index	f	\N
475	78	\N	index	f	\N
474	77	\N	risco	f	\N
478	67	\N	index	f	\N
477	81	\N	index	f	\N
444	60	Detalhar	detalhar	t	G
433	58	Listar	index	t	G
340	30	\N	pesquisarjson	t	G
338	30	Editar	editar	t	E
343	49	Excluir	delete	t	\N
344	49	Baixar Arquivo	download	t	\N
331	49	Listar	index	t	\N
335	49	Incluir	add	t	\N
400	6	\N	bloquearprojeto	t	E
414	6	\N	rotina-bloqueio-projetos	t	E
417	6	\N	desbloquear	t	E
337	30	Excluir	excluir	t	E
457	23	Imprimir o Status Report	imprimir	t	G
435	29	\N	buscar-marcos	t	G
443	60	Editar	editar	t	E
451	60	Imprimir	imprimir	t	G
341	49	Criar Pasta	addpasta	t	\N
80	16	Listar	index	t	G
81	16	Cadastrar	add	t	E
82	16	Informacoes Iniciais	informacoesiniciais	t	E
83	16	Informacoes Tecnicas	informacoestecnicas	t	E
84	16	Resumo do Projeto	resumodoprojeto	t	G
85	16	Partes Interessadas	partesinteressadas	t	G
86	16	Excluir Interessado	excluirparte	t	E
113	16	Imprimir	imprimir	t	G
131	16	Imprimir TAP	imprimirtap	t	G
132	16	Imprimir Plano de Projeto	imprimirplanoprojeto	t	G
304	16	Editar	acao	t	E
302	41	Visualizar Impressao	visualizar-impressao	t	G
303	41	Imprimir EAP PDF	imprimir-pdf	t	G
288	41	Cadastrar Grupo	cadastrar-grupo	t	E
289	41	Cadastrar Entrega	cadastrar-entrega	t	E
492	84	imprimir-pdf	imprimir-pdf	t	G
479	21	retorna-predecessora	retorna-predecessora	t	G
461	31	imprimir-todos	imprimir-todos	t	G
456	21	retorna-inicio-real	retorna-inicio-real	t	G
447	60	retornalicoesjson	retornalicoesjson	t	G
369	21	buscarprojetos	buscarprojetos	t	G
368	21	buscar-projetos	buscar-projetos	t	G
366	21	relatorio-cronograma	relatorio-cronograma	t	G
296	41	Excluir	excluir	t	E
120	21	Cadastrar Grupo	cadastrar-grupo	t	E
121	21	Listar	index	t	G
135	21	Cadastrar Entrega	cadastrar-entrega	t	E
145	21	Cadastrar Atividade	cadastrar-atividade	t	E
173	21	Editar Grupo	editar-grupo	t	E
199	21	Editar Atividade	editar-atividade	t	E
201	21	Adicionar Predecessora	adicionar-predecessora	t	E
202	21	Excluir cronograma	excluir-predecessora	t	E
221	21	Excluir Atividade	excluir-atividade	t	E
223	21	Pesquisar	pesquisar	t	G
239	21	Copiar Cronograma	copiar-cronograma	t	E
243	21	Detalhar	detalhar	t	G
244	21	Pesquisar Projeto	pesquisar-projeto	t	G
271	21	Imprimir Cronograma PDF	imprimir-pdf	t	G
310	44	Pesquisar	pesquisar	t	G
330	44	Imprimir	imprimir	t	G
167	28	Imprimir	imprimir	t	G
263	28	Listar	listar	t	G
265	28	Cadastrar	add	t	E
266	28	Detalhar	detalhar	t	G
269	28	Editar	editar	t	E
291	43	Listar	index	t	G
293	43	Cadastrar	add	t	E
276	40	Cadastrar	cadastrar	t	E
279	40	Detalhar	detalhar	t	G
281	40	Listar	listar	t	G
336	30	Cadastrar	add	t	E
339	30	Detalhar	detalhar	t	G
342	30	Imprimir Todos	imprimirtodos	t	G
272	29	Cadastrar	add	t	E
273	29	Editar	editar	t	E
274	29	Detalhar	detalhar	t	G
408	60	Listar	index	t	G
409	60	Cadastrar	cadastrar	t	E
452	60	Excluir	excluir	t	E
399	58	Editar	editar	t	E
549	91	Cadastrar diagnostico	add	t	E
539	8	\N	alterar-senha	f	\N
550	91	Alterar diagnostico	editar	t	E
540	8	\N	esqueci-senha	f	\N
551	91	Clonar diagnostico	clonar	t	E
552	91	Excluir diagnostico	excluir	t	E
562	97	responderquestionariocidadao	responderquestionariocidadao	t	E
564	97	buscaquestionariorespondidocidadao	buscaquestionariorespondidocidadao	t	G
587	95	 retornaperguntajson 	retornaperguntajson	t	E
566	97	sumariocidadao	sumariocidadao	t	G
586	95	perguntaeditar	perguntaeditar	t	E
567	98	vincularquestionario	vincularquestionario	t	E
583	95	opcaorespostaadd	opcaorespostaadd	t	E
568	98	listarquestionario	listarquestionario	t	G
582	95	secoes	secoes	t	E
580	95	dadosbasicos	dadosbasicos	t	E
581	95	 secoes-add 	secoes-add	t	E
579	95	editar	editar	t	E
571	98	responderquestionario	responderquestionario	t	E
578	95	add	add	t	E
577	95	pesquisar	pesquisar	t	G
576	95	index	index	t	G
573	98	buscaquestionariorespondido	buscaquestionariorespondido	t	G
575	98	sumario	sumario	t	E
556	91	gera-sequence	gera-sequence	t	E
559	97	buscaquestionariovinculadocidadao	buscaquestionariovinculadocidadao	t	G
557	96	resumo	resumo	t	E
560	97	listarquestionariocidadao	listarquestionariocidadao	t	G
545	90	pesquisar	pesquisar	t	G
544	90	index	index	t	G
538	89	autenticar	autenticar	t	E
537	31	retornaassinaturas	retornaassinaturas	t	G
534	29	autenticarassinatura	autenticarassinatura	t	E
535	29	retornaassinaturas	retornaassinaturas	t	G
531	16	cadastra-parte-rh-adjunto	cadastra-parte-rh-adjunto	t	E
532	16	autenticarassinatura	autenticarassinatura	t	E
529	6	clonar	clonar	t	E
528	88	index	index	t	G
527	23	chartmarcoreljson	chartmarcoreljson	t	G
526	35	atualizaacompanhamento	atualizaacompanhamento	t	G
524	21	verifica-atividades-predecessoras	verifica-atividades-predecessoras	t	G
523	44	combo-tratamento	combo-tratamento	t	G
521	8	statusreport	statusreport	t	G
517	21	alterar-visibilidade	alterar-visibilidade	t	G
516	35	imprimir-pdf	imprimir-pdf	t	G
514	35	atualizarcabecalhojson	atualizarcabecalhojson	t	G
513	21	valida-predecessora	valida-predecessora	t	G
519	86	index	index	t	G
520	8	gerencia	gerencia	t	G
509	31	imprimir-word	imprimir-word	t	G
510	21	retorna-data-fim-por-dias	retorna-data-fim-por-dias	t	G
508	29	imprimir-word	imprimir-word	t	G
507	27	imprimir-word	imprimir-word	t	G
506	16	imprimir-word	imprimir-word	t	G
406	35	Detalhar	detalhar	t	G
504	42	atividade	atividade	t	G
503	42	gerargantt	gerargantt	t	G
501	42	detalharentrega	detalharentrega	t	G
500	42	detalharatividade	detalharatividade	t	G
499	29	listaentregas	listaentregas	t	G
497	6	excluirprojeto	excluirprojeto	t	E
496	6	clonarprojeto	clonarprojeto	t	E
495	57	imprimir-pdf	imprimir-pdf	t	G
494	85	imprimir-pdf	imprimir-pdf	t	G
491	84	index	index	t	G
490	83	imprimir-pdf	imprimir-pdf	t	G
489	83	index	index	t	G
486	6	detalharpermissao	detalharpermissao	f	G
484	6	listapermissao	listapermissao	f	G
483	6	editarpermissao	editarpermissao	f	E
482	6	configurar	configurar	f	G
481	82	forbidden	forbidden	t	G
505	28	imprimir-word	imprimir-word	t	G
611	21	Gerar CSV de Cronograma	gerar-csv	t	G
610	21	Gerar PDF do cronograma	gerar-pdf	t	G
607	21	retorna-parte-interessada	retorna-parte-interessada	t	G
606	21	retornacronogramajson 	retornacronogramajson	t	G
605	99	quantidadeagrupadora 	quantidadeagrupadora	t	G
604	99	delegacias 	delegacias	t	G
603	99	unidades-vinculadas 	unidades-vinculadas	t	G
602	99	pesquisar 	pesquisar	t	G
601	99	detalhar 	detalhar	t	G
600	99	excluir 	excluir	t	E
599	99	editar 	editar	t	E
596	95	clonar-add	clonar-add	t	E
598	99	cadastrar 	cadastrar	t	E
597	99	listar 	listar	t	G
594	95	lista-clonar	lista-clonar	t	G
593	95	excluir-pergunta-questinario	excluir-pergunta-questinario	t	E
595	95	form-clonar 	form-clonar	t	E
591	95	excluir	excluir	t	E
592	95	excluir-resp-questinario 	excluir-resp-questinario	t	E
590	95	detalhar	detalhar	t	G
585	95	pergunta-add	pergunta-add	t	E
589	95	pergunta	pergunta	t	E
588	95	 retornaopcoesrespostajson 	retornaopcoesrespostajson	t	E
563	97	listarquestionariorespondidocidadao	listarquestionariorespondidocidadao	t	G
565	97	visualizarquestionariorespondidocidadao	visualizarquestionariorespondidocidadao	t	G
584	95	manipulaopcoesrespostajson	manipulaopcoesrespostajson	t	E
569	98	buscaquestionariovinculado	buscaquestionariovinculado	t	G
570	98	gerarnumeroquestionario	gerarnumeroquestionario	t	G
572	98	listarquestionariorespondido	listarquestionariorespondido	t	G
574	98	visualizarquestionariorespondido	visualizarquestionariorespondido	t	G
558	97	vincularquestionariocidadao	vincularquestionariocidadao	t	E
561	97	gerarnumeroquestionariocidadao	gerarnumeroquestionariocidadao	t	E
543	21	excluircomentariojson	excluircomentariojson	t	E
542	21	addcomentario	addcomentario	t	E
541	21	verificaparteinteressadajson	verificaparteinteressadajson	t	G
536	31	autenticarassinatura	autenticarassinatura	t	E
533	16	retornaassinaturas	retornaassinaturas	t	G
530	16	cadastra-parte-rh-demandante	cadastra-parte-rh-demandante	t	E
525	6	pesquisaracaojson	pesquisaracaojson	t	G
522	87	index	index	t	G
518	21	buscarnaturezas	buscarnaturezas	t	G
515	35	atualizarcabecalhojson	atualizarcabecalhojson	t	G
512	21	retorna-qtde-dias-uteis-entre-datas	retorna-qtde-dias-uteis-entre-datas	t	G
511	21	retorna-data-anterior-por-dias	retorna-data-anterior-por-dias	t	G
502	42	importar-grafico	importar-grafico	t	G
498	6	restaurarprojeto	restaurarprojeto	t	E
493	85	index	index	t	G
488	23	visualizarimpressao	visualizarimpressao	t	G
485	6	editapermissao	editapermissao	f	E
487	6	atualizapermissao	atualizapermissao	f	G
480	21	atualizar-cronograma	atualizar-cronograma	t	E
460	23	imprimir-pdf	imprimir-pdf	t	G
370	21	resultado-relatorio-cronograma	resultado-relatorio-cronograma	t	G
365	21	relatoriocronograma	relatoriocronograma	t	G
242	21	pesquisarjson	pesquisarjson	t	G
224	21	atualizar-base-line-atividade	atualizar-base-line-atividade	t	E
608	21	atualiza-grupo-in-line	atualiza-grupo-in-line	t	E
612	35	proximomarcojson 	proximomarcojson	t	G
609	21	Atualiza-entrega-in-line	atualiza-entrega-in-line	t	G
613	3	\N	acesso	\N	\N
\.


--
-- TOC entry 4118 (class 0 OID 10423201)
-- Dependencies: 339
-- Data for Name: tb_permissaodiagnostico; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_permissaodiagnostico (idpartediagnostico, iddiagnostico, idrecurso, idpermissao, idpessoa, data, ativo) FROM stdin;
\.


--
-- TOC entry 4119 (class 0 OID 10423206)
-- Dependencies: 340
-- Data for Name: tb_permissaoperfil; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_permissaoperfil (idpermissaoperfil, idperfil, idpermissao) FROM stdin;
3466	16	111
373	1	218
8	1	70
9	1	69
10	1	68
11	1	67
12	1	66
13	1	65
14	1	64
15	1	63
81	1	86
17	1	61
18	1	60
19	1	59
20	1	58
21	1	57
22	1	56
23	1	55
24	1	54
25	1	53
26	1	52
1548	1	95
1553	1	420
1558	1	361
1568	1	212
1572	1	155
1575	1	150
3481	16	37
3494	1	529
3508	3	537
3522	4	534
45	1	33
46	1	32
47	1	31
48	1	30
49	1	29
50	1	28
51	1	27
52	1	26
53	1	25
54	1	24
55	1	23
56	1	22
57	1	21
58	1	20
59	1	19
60	1	18
61	1	17
63	1	15
375	1	224
3536	7	531
82	1	85
3550	16	393
77	1	62
78	2	77
83	1	84
84	1	83
1035	2	318
86	1	81
87	1	80
1580	3	380
1590	5	397
1596	5	374
3570	7	505
1608	5	114
94	1	92
95	1	91
96	1	90
97	1	89
98	1	88
99	1	87
100	1	99
3583	2	541
105	1	110
106	1	109
107	1	108
3596	7	543
111	1	104
112	1	103
115	1	113
113	1	111
116	3	113
3608	1	546
118	3	108
119	3	106
120	3	107
3620	3	546
3631	7	547
124	3	102
126	3	100
128	3	98
129	3	97
130	3	96
1614	5	226
133	3	112
1043	2	428
136	3	77
137	3	76
138	3	75
139	3	74
142	3	82
144	3	80
145	3	84
146	3	83
147	3	85
149	3	87
150	3	88
155	3	93
156	3	73
157	3	72
158	3	71
159	3	70
160	3	69
163	3	66
164	3	65
165	3	64
166	3	63
1047	2	20
1049	2	21
169	3	60
170	3	59
1051	2	15
1053	2	17
1055	2	379
1057	2	397
1059	2	374
1061	2	367
1063	2	392
178	3	51
1065	2	22
179	3	50
1067	2	23
182	3	47
183	3	46
184	3	45
185	3	44
186	3	43
189	3	40
190	3	39
191	3	38
201	3	28
202	3	27
205	3	24
206	3	23
207	3	22
208	3	21
209	3	20
210	3	19
211	3	18
212	3	17
227	1	119
228	1	118
213	3	16
214	3	15
215	3	14
219	3	10
220	3	9
221	3	8
222	3	7
223	3	6
224	3	5
225	3	4
229	1	117
230	1	116
231	1	115
343	1	203
297	1	175
3467	16	110
3482	16	36
248	1	132
237	2	5
3495	1	528
3509	3	536
3523	4	533
3537	7	530
3551	16	206
3563	16	31
249	1	131
344	1	204
345	1	205
1581	3	379
3571	7	506
3584	3	545
254	1	148
255	1	147
3597	7	542
3609	2	555
3621	4	555
3632	7	546
1597	5	367
346	1	16
1603	5	22
269	1	14
1609	5	102
266	2	154
267	2	153
274	1	164
275	1	163
287	1	167
288	1	166
289	1	168
290	1	170
291	1	169
348	1	208
293	1	172
294	1	171
298	1	174
299	1	176
300	1	177
350	1	210
1622	5	107
356	1	202
374	1	223
376	1	225
353	1	214
354	1	213
357	1	201
1627	5	8
1632	5	9
362	1	219
1637	5	161
326	1	197
1642	5	426
328	1	173
329	1	198
330	1	199
331	1	158
332	1	154
333	1	153
334	1	145
335	1	135
336	1	121
337	1	120
338	1	200
367	1	222
368	1	221
369	1	220
371	1	82
372	1	217
377	1	226
378	1	228
380	1	229
381	1	230
382	1	231
383	1	232
384	1	233
385	1	234
386	1	235
387	1	236
388	1	237
389	1	238
390	1	239
391	1	240
392	1	241
395	1	244
396	1	245
394	1	243
407	1	256
408	1	257
399	1	248
400	1	249
401	1	250
402	1	251
403	1	252
404	1	253
405	1	254
406	1	255
409	1	263
412	1	266
410	1	264
416	1	270
413	1	267
414	1	268
415	1	269
417	1	271
418	1	272
419	1	273
420	1	274
421	1	275
422	1	276
423	1	277
424	1	278
425	1	279
426	1	280
427	1	281
428	1	282
429	1	283
430	1	284
431	1	285
432	1	287
433	1	288
434	1	289
435	1	286
436	1	290
437	1	295
438	1	294
439	1	293
440	1	292
441	1	291
446	1	299
443	1	297
448	1	301
445	1	298
447	1	300
449	1	302
450	1	303
451	1	304
452	1	305
453	1	306
454	1	309
455	1	308
456	1	307
457	1	310
458	1	311
459	1	312
460	1	313
466	1	319
467	1	320
468	1	321
469	1	322
470	1	323
471	1	324
472	1	329
473	1	328
474	1	327
475	1	326
476	1	325
477	1	330
478	1	331
479	1	332
480	1	333
481	1	334
482	1	335
483	1	339
484	1	338
485	1	337
486	1	336
487	1	340
488	1	341
489	1	342
490	1	343
491	1	344
1565	1	457
1569	1	211
494	1	347
495	1	348
496	1	349
497	1	350
498	1	351
499	1	352
500	1	353
501	1	354
502	1	355
503	1	356
504	1	357
505	1	358
1573	1	152
1576	1	149
1582	4	379
1592	5	391
511	1	364
514	1	367
513	1	366
515	1	368
516	1	369
517	1	370
518	1	371
519	1	372
520	1	373
521	1	374
522	1	375
523	1	376
524	1	377
525	1	378
526	1	379
527	1	380
528	1	381
529	1	382
530	1	383
531	1	384
532	1	385
533	1	386
534	1	387
535	1	388
536	1	389
537	1	390
538	1	391
539	1	392
542	1	365
543	1	395
544	1	396
545	1	397
546	1	398
547	1	399
549	1	401
551	1	403
550	1	402
552	1	404
553	1	405
554	1	406
555	1	407
1549	1	94
1554	1	419
1559	1	360
1598	5	364
3468	16	58
1610	5	394
3483	16	35
1623	5	106
1628	5	7
1524	11	107
1526	11	78
1528	11	11
1530	11	9
1532	11	7
1533	11	6
1534	11	5
1535	11	4
3496	2	538
1638	5	99
1643	5	425
1647	5	177
1650	5	97
1653	5	118
1656	5	262
1659	5	168
1662	5	265
1665	5	269
1668	5	231
1671	5	235
604	1	296
605	1	265
606	1	262
607	1	247
608	1	246
609	1	242
3510	3	535
3524	4	532
3538	7	528
622	3	227
623	3	431
3552	16	100
3572	7	495
3585	3	544
626	3	325
627	3	327
628	3	329
629	3	364
630	3	374
631	3	382
632	3	367
633	3	391
634	3	392
3598	7	541
3610	2	553
3622	4	553
3633	1	207
639	3	394
640	3	393
641	3	114
642	3	42
653	3	446
654	3	445
655	3	197
656	3	204
657	3	203
658	3	205
659	3	177
660	3	176
661	3	200
662	3	118
663	3	119
664	3	115
665	3	345
666	3	363
667	3	361
668	3	346
669	3	360
670	3	359
671	3	362
672	3	423
673	3	421
674	3	262
675	3	168
676	3	167
677	3	166
678	3	263
679	3	264
680	3	266
681	3	236
682	3	228
683	3	235
684	3	231
685	3	241
686	3	230
687	3	319
688	3	323
689	3	324
690	3	370
691	3	154
692	3	153
693	3	213
694	3	214
695	3	121
696	3	271
697	3	270
698	3	244
699	3	243
700	3	239
701	3	223
702	3	242
703	3	240
704	3	369
705	3	368
706	3	366
707	3	365
708	3	285
709	3	303
710	3	286
711	3	287
712	3	408
713	3	447
714	3	444
715	3	451
716	3	163
717	3	164
718	3	291
719	3	298
720	3	292
721	3	293
722	3	294
723	3	406
724	3	217
725	3	218
726	3	219
727	3	251
728	3	250
729	3	249
730	3	313
731	3	330
732	3	301
733	3	300
734	3	310
735	3	311
736	3	335
737	3	344
738	3	331
739	3	332
740	3	333
741	3	334
3469	16	57
743	3	336
744	3	172
745	3	339
746	3	340
747	3	171
748	3	342
749	3	149
750	3	216
751	3	215
752	3	212
753	3	211
754	3	165
755	3	156
756	3	155
757	3	152
758	3	151
759	3	150
760	3	133
761	3	131
762	3	132
764	3	170
765	3	284
766	3	169
767	3	274
768	3	175
769	3	174
770	3	398
771	4	397
772	4	364
773	4	374
774	4	391
775	4	380
776	4	367
777	4	27
778	4	24
779	4	23
3497	2	537
781	4	22
782	4	28
783	4	393
784	4	394
785	4	100
786	4	40
787	4	39
788	4	38
789	4	45
790	4	46
791	4	47
792	4	50
793	4	325
794	4	329
795	4	327
796	4	226
3511	3	534
3525	4	531
3573	7	524
3586	3	543
3611	2	550
805	4	6
806	4	107
807	4	106
808	4	9
809	4	5
811	4	12
812	4	7
813	4	4
3623	4	550
3634	1	209
816	4	196
817	4	180
818	4	161
819	4	189
820	4	194
823	4	99
824	4	93
825	4	98
826	4	96
827	4	112
828	4	115
829	4	345
830	4	363
831	4	361
832	4	346
833	4	360
834	4	359
835	4	421
836	4	423
837	4	362
838	4	262
839	4	265
840	4	267
841	4	268
842	4	269
843	4	168
844	4	167
845	4	166
846	4	263
847	4	264
848	4	266
849	4	238
850	4	236
851	4	228
852	4	235
853	4	232
854	4	231
855	4	234
856	4	241
857	4	230
858	4	319
859	4	320
860	4	321
861	4	323
862	4	324
863	4	370
864	4	154
865	4	153
866	4	198
867	4	199
868	4	201
869	4	202
870	4	145
871	4	135
872	4	213
873	4	214
874	4	121
875	4	120
876	4	271
877	4	270
878	4	220
879	4	173
3470	16	56
881	4	252
882	4	221
883	4	222
884	4	244
885	4	243
886	4	239
887	4	237
888	4	233
889	4	223
3484	16	34
891	4	242
892	4	240
893	4	369
894	4	368
895	4	158
896	4	366
897	4	365
898	4	281
899	4	276
900	4	277
901	4	278
902	4	279
903	4	280
904	4	285
905	4	303
906	4	302
907	4	299
908	4	297
909	4	296
910	4	290
911	4	289
912	4	288
913	4	286
914	4	287
3498	2	536
3512	3	533
3526	4	530
3540	16	102
3574	1	545
3587	3	542
3599	1	555
3612	2	548
924	4	408
925	4	452
926	4	447
927	4	444
928	4	451
929	4	443
930	4	409
931	4	163
932	4	164
1545	1	98
934	4	291
935	4	298
936	4	293
937	4	292
938	4	294
939	4	225
940	4	219
941	4	218
942	4	217
943	4	406
944	4	246
945	4	257
946	4	255
947	4	254
948	4	253
949	4	251
950	4	250
951	4	249
952	4	248
953	4	247
954	4	313
955	4	330
956	4	301
957	4	300
958	4	310
959	4	311
960	4	335
961	4	343
962	4	341
963	4	344
964	4	331
965	4	332
966	4	333
967	4	334
968	4	336
969	4	172
970	4	338
971	4	339
972	4	340
973	4	171
974	4	342
975	4	149
976	4	216
977	4	215
978	4	212
979	4	211
980	4	165
981	4	156
982	4	155
983	4	152
984	4	151
985	4	150
987	4	133
988	4	131
1550	1	93
990	4	80
1555	1	418
992	4	113
994	4	132
1560	1	359
996	4	84
997	4	83
998	4	82
1046	2	19
1000	4	399
1001	4	433
1002	4	282
1003	4	435
1004	4	170
1005	4	283
1006	4	284
1007	4	169
1008	4	272
1009	4	273
1010	4	274
1011	4	275
1012	4	175
1013	4	174
1014	4	398
1593	5	382
1599	5	14
1611	5	393
1624	5	78
1629	5	6
1639	5	455
1029	4	85
1031	4	86
1032	4	245
1033	4	304
1048	2	108
1050	2	14
1052	2	16
1054	2	18
1056	2	380
1058	2	364
1060	2	382
1062	2	391
1064	2	28
1066	2	24
1068	2	431
1070	2	27
3471	16	55
3485	1	538
3499	2	535
3513	3	532
3527	4	529
1194	2	415
1196	2	422
1198	2	403
3541	16	114
3575	1	544
3588	3	541
3600	1	554
1091	2	393
1092	2	394
1093	2	102
1094	2	100
1095	2	114
1096	2	44
1097	2	43
1098	2	40
1099	2	39
1100	2	206
1101	2	38
1102	2	51
1103	2	50
1104	2	47
1105	2	46
1106	2	45
3613	2	547
3624	4	548
1113	2	327
1114	2	325
1115	2	329
1116	2	226
1193	2	413
1120	2	209
1121	2	208
1122	2	210
1123	2	207
1195	2	416
1197	2	424
1199	2	402
1200	2	372
1201	2	371
1202	2	381
1203	2	376
1204	2	377
1210	2	455
1211	2	425
1212	2	427
1213	2	454
1214	2	426
1215	2	353
1216	2	354
1217	2	355
1218	2	197
1219	2	200
1220	2	204
1221	2	203
1222	2	205
1223	2	177
1146	2	6
1147	2	10
1148	2	107
1149	2	106
1150	2	9
1151	2	8
1153	2	7
1154	2	4
1156	2	147
1157	2	148
1224	2	176
1225	2	93
1226	2	98
1227	2	97
1228	2	96
1229	2	118
1230	2	119
1189	2	352
1190	2	347
1191	2	350
1192	2	411
1231	2	112
1232	2	115
1233	2	345
1234	2	363
1235	2	361
1236	2	346
1237	2	360
1238	2	359
1239	2	421
1240	2	418
1241	2	423
1242	2	362
1243	2	64
1244	2	61
1245	2	60
1246	2	63
1247	2	62
1248	2	59
1249	2	88
1250	2	92
1251	2	91
1252	2	90
1253	2	89
1254	2	87
1255	2	67
1256	2	65
1257	2	68
1258	2	66
1259	2	70
1260	2	69
1261	2	262
1262	2	265
1263	2	268
1264	2	269
1265	2	168
1266	2	167
1267	2	166
1268	2	263
1269	2	264
1270	2	266
1271	2	236
1272	2	228
1273	2	235
1274	2	232
1275	2	231
1276	2	234
1277	2	241
1278	2	230
1279	2	319
1280	2	320
1281	2	321
1282	2	323
1283	2	324
1284	2	370
1285	2	271
1286	2	270
1287	2	244
1288	2	243
1289	2	223
1290	2	242
1291	2	240
1292	2	369
1293	2	368
1294	2	366
1295	2	365
1296	2	281
1297	2	279
1298	2	280
1299	2	285
1300	2	303
1301	2	302
1302	2	286
1303	2	287
1304	2	227
1305	2	73
3472	16	54
1307	2	76
1308	2	71
3486	1	537
1310	2	72
3500	2	534
3514	3	531
3528	4	528
1314	2	408
1315	2	447
1316	2	444
1317	2	451
1318	2	443
1319	2	409
1320	2	163
1321	2	164
1322	2	291
1323	2	298
3542	16	42
1325	2	292
3564	16	32
1327	2	219
1328	2	218
1329	2	217
1330	2	406
1331	2	246
1332	2	251
1333	2	250
1334	2	249
1335	2	247
1336	2	313
1337	2	330
1338	2	301
1339	2	300
1340	2	310
1341	2	311
1342	2	335
3576	1	543
1344	2	344
1345	2	331
1346	2	332
1347	2	333
1348	2	334
3589	4	545
1350	2	172
3601	1	553
1352	2	339
1353	2	340
1354	2	171
1355	2	342
1356	2	149
1357	2	216
1358	2	215
1359	2	212
1360	2	211
1361	2	165
1362	2	156
1363	2	155
1364	2	152
1365	2	151
1366	2	150
3614	2	546
1368	2	133
1369	2	131
1370	2	245
1371	2	80
1372	2	304
1373	2	113
1374	2	132
1375	2	85
1376	2	84
1377	2	83
1378	2	82
3625	4	547
1382	2	282
1383	2	435
1384	2	170
1385	2	283
1386	2	284
1387	2	169
1390	2	274
1391	2	175
1392	2	174
1393	2	398
1546	1	97
1396	11	318
1397	11	317
1398	11	314
1399	11	315
1400	11	316
1594	5	380
1600	5	15
1612	5	45
1551	1	423
1408	4	118
1409	4	119
1410	4	204
1411	4	203
1412	4	197
1413	4	177
1414	4	176
1415	4	205
1416	4	200
1417	4	114
1418	4	102
1420	4	318
1422	4	428
1425	4	14
1461	1	112
1426	4	41
1428	4	44
1429	4	43
1431	4	16
1432	4	15
1583	4	382
1625	5	12
1630	5	5
1640	5	454
1556	1	363
1561	1	346
1566	1	216
1570	1	165
1574	1	151
1577	1	134
1648	5	93
1651	5	98
1654	5	115
1657	5	166
1660	5	263
1663	5	266
1666	5	228
1669	5	232
1672	5	236
1465	10	318
1466	10	317
1467	10	314
1468	13	318
1469	13	317
1470	13	314
1471	14	318
1472	14	317
1473	14	314
1474	1	408
1475	1	409
1476	1	410
1477	1	411
1478	1	412
1479	1	413
1481	1	415
1482	1	416
1488	1	422
1490	1	424
1491	1	425
1492	1	426
1493	1	427
1494	1	428
1495	1	429
1496	1	430
1497	1	431
1498	1	432
1499	1	433
1500	1	434
1501	1	435
1502	1	436
1503	1	437
1504	1	438
1505	1	439
1506	1	440
1507	1	441
1508	1	442
1509	1	443
1510	1	444
1511	1	445
1512	1	446
1513	1	447
1514	1	448
1515	1	449
1517	1	451
1518	1	452
1520	1	454
1521	1	455
1522	1	456
1525	11	106
1527	11	12
1529	11	10
1531	11	8
1547	1	96
1552	1	421
1557	1	362
1562	1	345
1563	1	458
1567	1	215
1571	1	156
1579	3	397
1578	1	133
3473	16	53
1595	5	379
1601	5	16
3487	1	536
1613	5	325
3501	2	533
1626	5	10
1631	5	4
3515	3	530
1641	5	427
1649	5	96
1652	5	119
1655	5	112
1658	5	167
1661	5	264
1664	5	268
1667	5	230
1670	5	234
1673	5	241
1538	1	51
1539	1	50
1540	1	49
1541	1	48
1542	1	47
1543	1	46
1544	1	45
1674	1	394
1675	1	393
1676	1	206
1677	1	114
1678	1	102
1679	1	100
1680	1	44
1681	1	43
1682	1	42
1683	1	41
1684	1	40
1685	1	39
1686	1	38
1687	1	461
3529	7	538
3543	16	41
3577	1	542
1696	7	47
1698	7	45
3590	4	544
3602	1	552
3615	3	555
3626	4	546
1704	7	107
1705	7	106
1706	7	12
1707	7	9
1708	7	8
1709	7	7
1710	7	6
1711	7	5
1712	7	4
1713	7	10
1715	7	99
1716	7	455
1717	7	454
1718	7	427
1719	7	426
1720	7	425
1734	7	421
1740	7	361
1741	7	360
1742	7	359
1744	7	345
1766	7	264
1768	7	166
1769	7	263
1771	7	167
1772	7	168
1774	7	216
1775	7	215
1776	7	149
1777	7	212
1778	7	211
1779	7	133
1782	7	151
1783	7	156
1784	7	152
1785	7	165
1786	7	155
1787	7	131
1788	7	132
1789	7	113
1790	7	284
1791	7	170
1792	7	461
1793	7	175
1794	1	453
1795	1	450
1796	1	417
1797	1	414
1798	1	400
1799	1	227
1800	1	77
1801	1	76
1802	1	75
1803	1	74
1804	1	73
1805	1	72
1806	1	71
1807	1	314
1808	1	315
1809	1	316
1810	1	317
1811	1	318
1812	4	461
1813	4	460
1814	4	21
1815	4	455
1816	4	454
1817	4	427
1818	4	426
1819	4	425
1820	4	97
1821	4	418
1822	1	107
1823	1	106
3474	16	52
3488	1	535
3502	2	532
1827	1	10
1828	1	9
1829	1	8
1830	1	7
1831	1	6
1832	1	5
1833	1	4
1834	3	455
1835	3	454
1836	3	427
1837	3	426
1838	3	425
1839	6	455
1840	6	454
1841	6	427
1842	6	426
1843	6	425
1844	8	455
1845	8	454
1846	8	427
1847	8	426
1848	8	425
1849	9	455
1850	9	454
1851	9	427
1852	9	426
1853	9	425
1854	10	455
1855	10	454
1856	10	427
1857	10	426
1858	10	425
1859	11	455
1860	11	454
1861	11	427
1862	11	426
1863	11	425
1864	13	455
1865	13	454
1866	13	427
1867	13	426
1868	13	425
1869	14	455
1870	14	454
1871	14	427
1872	14	426
1873	14	425
1874	15	455
1875	15	454
1876	15	427
1877	15	426
1878	15	425
1879	12	446
1880	12	445
1881	12	352
1882	12	351
1883	12	350
1884	12	349
1885	12	348
1886	12	347
1887	12	424
1888	12	422
1889	12	416
1890	12	415
1891	12	413
1892	12	412
1893	12	411
1894	12	410
1895	12	404
1896	12	403
1897	12	402
1898	12	434
1899	12	432
1900	12	388
1901	12	384
1902	12	383
1903	12	381
1904	12	378
1905	12	377
1906	12	376
1907	12	375
1908	12	373
1909	12	372
1910	12	371
1911	12	459
1912	12	458
1913	12	442
1914	12	439
1915	12	438
1916	12	437
1917	12	455
1918	12	454
1919	12	427
1920	12	426
1921	12	425
1922	12	358
1923	12	357
1924	12	356
1925	12	355
1926	12	354
1927	12	353
1928	3	418
1929	3	41
1930	3	206
1931	2	41
1932	2	42
1933	3	318
3516	3	529
3530	7	537
3544	16	43
1936	3	428
1937	9	428
1938	10	428
3554	2	81
1939	5	21
1940	6	14
1941	6	16
1942	7	115
1943	7	423
1944	7	150
1945	8	34
1946	8	35
1947	8	36
1948	8	37
1949	8	101
1950	8	105
1951	1	105
1952	1	101
1953	1	37
1954	1	36
1955	1	35
1956	1	34
1957	1	144
1958	1	143
1959	1	142
1960	1	141
1961	1	126
1962	1	125
1963	1	124
1964	1	123
1965	1	122
1966	1	79
3557	16	105
3565	2	517
3578	1	541
3591	4	543
3603	1	551
3616	3	553
3627	7	555
1991	3	456
1992	3	256
1993	3	252
1994	3	237
1995	3	233
1996	3	224
1997	3	222
1998	3	221
1999	3	220
2000	3	202
2001	3	201
2002	3	199
2003	3	198
2004	3	173
2005	3	158
2006	3	145
2007	3	135
2008	3	120
2009	4	456
2010	5	456
2011	5	370
2012	5	369
2013	5	368
2014	5	366
2015	5	365
2016	5	271
2017	5	270
3475	16	208
2019	5	252
2020	5	244
2021	5	243
2022	5	242
2023	5	240
2024	5	239
2025	5	237
2026	5	233
2027	5	224
2028	5	223
2029	5	222
2030	5	221
2031	5	220
2032	5	214
2033	5	213
2034	5	202
2035	5	201
2036	5	199
2037	5	198
2038	5	173
2039	5	158
2040	5	154
2041	5	153
2042	5	145
2043	5	135
2044	5	121
2045	5	120
2046	6	456
2047	6	370
2048	6	369
2049	6	368
2050	6	366
2051	6	365
2052	6	271
2053	6	270
3489	1	534
3503	2	531
2056	6	244
2057	6	243
2058	6	242
2059	6	240
2060	6	239
2061	6	237
2062	6	233
2063	6	224
2064	6	223
2065	6	222
2066	6	221
2067	6	220
2068	6	214
2069	6	213
2070	6	202
2071	6	201
2072	6	199
2073	6	198
2074	6	173
2075	6	158
2076	6	154
2077	6	153
2078	6	145
2079	6	135
2080	6	121
2081	6	120
2082	2	456
3517	3	528
3531	7	536
2085	2	239
2086	2	237
2087	2	233
3545	16	44
2089	2	222
2090	2	221
2091	2	220
2092	2	214
2093	2	213
2094	2	202
2095	2	201
2096	2	199
2097	2	198
2098	2	173
2099	2	158
2100	2	145
2101	2	135
2102	2	121
2103	2	120
2104	4	431
2105	5	23
2106	5	24
2107	5	27
2108	5	28
2109	5	431
2110	6	431
2111	6	28
2112	6	27
2113	6	24
2114	6	23
2115	6	22
2116	7	22
2117	7	23
2118	7	24
2119	7	27
2120	7	28
2121	7	431
2122	8	431
2123	8	28
2124	8	27
2125	8	24
2126	8	23
2127	8	22
2128	9	431
2129	9	28
2130	9	27
2131	9	24
2132	9	23
2133	9	22
2134	10	431
2135	10	28
2136	10	27
2137	10	24
2138	10	23
2139	10	22
2140	11	431
2141	11	28
2142	11	27
2143	11	24
2144	11	23
2145	11	22
2146	12	431
2147	12	28
2148	12	27
2149	12	24
2150	12	23
2151	12	22
2152	13	431
2153	13	28
2154	13	27
2155	13	24
2156	13	23
2157	13	22
2158	14	431
2159	14	28
2160	14	27
2161	14	24
2162	14	23
2163	14	22
2164	4	17
2165	5	17
2166	3	304
2167	3	245
3555	3	81
2169	6	132
2170	6	131
2171	6	113
2172	5	132
2173	5	131
2174	5	113
2175	5	84
2176	5	83
2177	5	82
2178	5	80
2179	5	304
2180	5	245
2181	5	433
2183	3	399
3566	3	517
3579	2	545
3592	4	542
3604	1	550
3617	3	550
3628	7	553
2192	1	459
3476	16	207
3490	1	533
2194	2	314
3504	2	530
2195	2	317
2196	2	429
2197	2	440
2198	2	448
3518	4	538
3532	7	535
3546	16	40
3556	4	81
3567	4	495
3580	2	544
3593	4	541
3605	1	549
3618	3	548
2206	2	401
2207	2	395
2208	2	384
2209	2	289
2210	2	288
2211	2	290
2212	2	75
2213	2	74
3629	7	550
2216	2	225
2217	2	254
2218	2	253
2219	2	257
2220	2	255
2221	2	461
2222	3	317
2223	3	314
2224	3	429
2225	3	440
2226	3	448
2227	10	103
2228	10	33
2229	10	31
2230	3	208
2231	3	207
2232	3	209
2233	3	226
2234	3	148
2235	3	147
2236	3	352
2237	3	350
2238	3	347
2239	3	402
2240	3	403
2242	3	411
2243	3	413
2244	3	415
2245	3	416
2246	3	424
2247	3	422
2248	3	377
2249	3	376
2250	3	371
2251	3	372
2252	3	381
2253	3	384
2256	3	442
2257	3	439
2258	3	438
2259	3	437
2260	3	355
2261	3	354
2262	3	353
2263	10	64
2264	10	63
2265	10	62
2266	10	61
2267	10	60
2268	10	59
2269	3	91
2270	3	92
2271	10	92
2272	10	91
2273	10	90
2274	10	89
2275	10	88
2276	10	87
2277	9	92
2278	9	91
2279	9	90
2280	9	89
2281	9	88
2282	9	87
2283	9	64
2284	9	63
2285	9	62
2286	9	61
2287	9	60
2288	9	59
2289	3	67
2290	3	68
2291	4	70
2292	4	69
2294	4	67
2295	4	66
2296	4	65
2297	4	68
2298	9	70
2299	9	69
2300	9	68
2301	9	67
2302	9	66
2303	9	65
2304	3	269
2305	3	268
2307	3	265
2308	3	234
2309	3	232
2310	3	281
2311	3	280
2312	3	279
2313	3	276
2314	3	302
2315	3	299
2316	3	297
2317	3	296
2318	3	290
2319	3	289
2320	3	288
2321	5	303
2322	5	302
2323	5	286
2324	5	285
2325	6	303
2326	6	302
2327	6	285
2328	6	286
2329	5	287
2330	6	287
2334	5	73
2335	5	72
2336	5	71
2337	5	75
2338	5	76
2339	5	77
2340	5	227
2343	6	71
2344	6	72
2345	6	73
2346	6	75
2347	6	76
2348	6	77
2349	6	227
2352	3	409
2353	3	443
2354	5	408
2355	5	409
2356	5	444
2357	5	447
2358	5	451
2359	5	443
2360	5	164
2361	5	163
2362	6	164
2363	6	163
2364	4	295
2365	5	291
2366	5	292
2367	5	293
2368	5	298
2369	5	294
2370	6	291
2371	6	292
2372	6	298
2373	5	217
2374	5	218
2375	5	219
2376	5	225
2377	5	406
2378	6	217
2379	6	406
2380	8	217
2381	8	218
2382	8	406
2383	6	218
2384	3	247
2385	3	246
2386	3	257
2387	3	255
2388	3	254
2389	3	253
2390	5	257
2391	5	255
2392	5	254
2393	5	253
2394	5	251
2395	5	250
2396	5	249
2397	5	247
2398	5	246
2399	6	254
2400	6	253
2401	6	251
2402	6	250
2403	6	249
2404	6	247
2405	6	246
2406	4	312
2407	5	330
2408	5	313
2409	5	310
2410	5	301
2411	5	300
2412	5	311
2413	8	300
2414	8	301
2415	8	310
2416	8	311
2417	8	313
2418	8	330
2419	5	331
2420	5	332
2421	5	333
2422	5	334
2423	5	335
2424	5	341
2425	5	344
2426	8	344
2427	8	341
2428	8	335
2429	8	334
2430	8	333
2431	8	332
2432	8	331
2433	3	338
2434	4	337
2435	5	342
2436	5	340
2437	5	339
2438	5	336
2439	5	172
2440	5	171
2441	6	171
2444	6	340
2445	5	338
2446	6	339
2448	8	340
2449	8	339
2450	8	172
2451	8	171
2453	8	336
2454	1	460
2455	5	133
2456	5	149
2457	5	150
2458	5	151
2459	5	152
2460	5	155
2461	5	156
2462	5	165
2463	5	211
2464	5	212
2465	5	215
2466	5	216
2467	6	216
2468	6	215
2469	6	212
2470	6	211
2471	6	165
2472	6	156
2473	6	155
2474	6	152
2475	6	151
2476	6	150
2477	6	149
2478	6	133
2479	8	216
2480	8	215
2481	8	212
2482	8	211
2483	8	165
2484	8	156
2485	8	460
2486	8	155
2487	8	152
2488	8	151
2489	8	150
2490	8	149
2491	8	133
2492	7	460
2493	6	460
2494	5	460
2495	3	460
2496	2	460
2497	9	460
2498	9	216
2499	9	215
2500	9	212
2501	9	211
2502	9	165
2503	9	156
2504	9	155
2505	9	152
2506	9	151
2507	9	150
2508	9	149
2509	9	133
2510	10	460
2511	10	216
2512	10	215
2513	10	212
2514	10	211
2515	10	165
2516	10	156
2517	10	155
2518	10	152
2519	10	151
2520	10	150
2521	10	149
2522	10	133
2523	11	460
2524	11	216
2525	11	215
2526	11	212
2527	11	211
2528	11	165
2529	11	156
2530	11	155
2531	11	152
2532	11	151
2533	11	150
2534	11	149
2535	11	133
2536	12	460
2537	12	216
2538	12	215
2539	12	212
2540	12	211
2541	12	165
2542	12	156
2543	12	155
2544	12	152
2545	12	151
2546	12	150
2547	12	149
2548	12	133
2549	13	460
2550	13	216
2551	13	215
2552	13	212
2553	13	211
2554	13	165
2555	13	156
2556	13	155
2557	13	152
2558	13	151
2559	13	150
2560	13	149
2561	13	133
2562	14	460
2563	14	216
2564	14	215
2565	14	212
2566	14	211
2567	14	165
2568	14	156
2569	14	155
2570	14	152
2571	14	151
2572	14	150
2573	14	149
2574	14	133
2575	15	460
2576	15	216
2577	15	215
2578	15	212
2579	15	211
2580	15	165
2581	15	156
2582	15	155
2583	15	152
2584	15	151
2585	15	150
2586	15	149
3477	16	209
2587	15	133
2588	5	85
2589	5	81
3491	1	532
3505	2	529
2591	8	132
2592	8	131
2593	8	113
2594	8	80
3519	4	537
2595	3	435
2596	3	283
2597	3	282
2598	5	435
2599	5	284
2600	5	283
2601	5	282
2602	5	274
2603	5	273
2604	5	170
2605	5	169
2606	5	272
2607	6	169
2608	6	274
2609	8	169
2610	8	274
2611	3	461
2612	5	174
2613	5	175
2614	5	461
2615	6	174
2616	8	174
2617	5	398
2618	8	398
2619	10	398
2620	9	398
2621	1	477
2622	1	478
2623	1	475
2624	1	474
2625	1	473
2626	1	472
2627	1	471
2628	1	470
2629	1	469
2630	1	468
2631	1	467
2632	1	466
2633	1	465
3533	7	534
2635	1	462
2636	1	12
2637	3	86
2638	3	248
2639	2	30
2640	2	103
2641	2	33
2642	2	31
3547	16	39
2644	2	446
2645	2	445
2646	2	467
2647	2	472
2648	2	475
2649	3	475
2650	4	475
2651	3	267
3560	16	104
2653	3	320
2654	3	321
2655	3	322
2656	3	452
2657	2	293
2658	2	294
2659	3	295
2660	3	225
3568	4	517
3581	2	543
2663	3	312
2664	2	341
2665	3	341
2666	3	343
2667	2	336
2668	2	338
2669	3	337
2671	2	86
3594	7	545
2673	2	272
2674	2	273
2675	3	272
2676	3	273
2677	3	275
2678	11	428
2679	11	429
2680	11	430
2681	11	440
2682	11	441
2683	11	448
2684	11	30
2685	11	394
2686	11	393
2687	11	114
2688	11	102
2689	11	100
2690	11	44
2691	11	43
2692	11	38
2693	11	39
2694	11	40
2695	11	41
2696	11	42
2697	11	176
2698	11	177
2699	11	197
2700	11	200
2701	11	203
2702	11	204
2703	11	205
2704	4	224
2705	7	364
2706	7	391
2707	7	397
2708	7	367
2709	7	374
2710	7	380
2711	7	112
2712	7	346
2713	7	362
2714	7	363
2715	7	93
2716	7	96
2717	7	97
2718	7	98
2719	7	366
2720	7	365
2721	7	370
2722	7	223
2723	7	244
2724	7	172
2725	7	342
2726	7	164
2727	7	240
2728	7	242
2729	7	368
2730	7	369
2731	7	243
2732	7	270
2733	7	271
2734	7	154
2735	7	249
2736	7	114
2737	7	102
2738	7	39
2739	7	43
2740	7	100
3606	1	548
2742	1	481
2743	1	480
2744	1	479
2745	2	480
2746	2	479
2747	3	480
2748	3	479
2749	4	479
2750	4	480
2751	5	480
2752	5	479
2753	6	479
2754	6	480
2755	2	32
2756	2	104
2757	3	30
2758	4	30
3478	16	210
3492	1	531
3506	2	528
3520	4	536
2759	4	71
2760	4	72
2761	4	73
2762	4	74
2763	4	75
2764	4	76
2765	4	77
2766	4	227
3534	7	533
2768	4	317
2769	4	314
2770	4	448
2771	4	429
2772	4	440
3548	16	38
2774	7	475
3561	16	103
2777	7	119
2778	7	118
2779	7	71
2780	7	72
2781	7	73
2782	7	76
2783	7	77
2784	7	227
2785	7	262
2786	7	266
2787	7	228
2788	7	230
2789	7	231
2790	7	235
2791	7	236
2792	7	241
2793	7	319
2794	7	323
2795	7	324
2796	7	121
2797	7	281
2798	7	280
2799	7	279
2800	7	285
2801	7	286
2802	7	302
2803	7	303
2804	7	287
2805	7	408
2806	7	444
2807	7	447
2808	7	451
2809	7	163
2810	7	291
2811	7	292
2812	7	298
2813	7	217
2814	7	218
2815	7	406
2816	7	250
2817	7	251
2818	7	300
2819	7	310
2820	7	313
2821	7	330
2822	7	331
2823	7	332
2824	7	333
2825	7	334
2826	7	344
2827	7	171
2828	7	339
2829	7	340
2834	7	433
2835	7	169
2836	7	274
2837	7	282
2838	7	283
2839	7	435
2840	7	174
2845	2	473
2846	3	473
2847	4	473
2848	5	473
2849	7	473
2850	7	398
2851	5	86
2853	7	80
2854	7	453
2855	6	98
2856	6	97
2857	6	96
2858	6	93
2859	8	98
2860	8	97
2861	8	96
2862	8	94
2863	8	93
2864	9	98
2865	9	97
2866	9	96
2867	9	93
2868	10	98
2869	10	97
2870	10	96
2871	10	93
2872	10	119
2873	10	118
2874	10	115
2875	10	112
2876	9	119
2877	9	118
2878	9	115
2879	9	112
2880	3	277
2882	2	276
2883	2	277
2885	5	319
2886	5	320
2887	5	321
2888	5	323
2889	5	324
2890	5	276
2891	5	277
2892	5	279
2893	5	280
2894	5	281
2895	5	288
2896	5	289
2897	5	290
2900	5	296
2901	5	297
2902	5	299
2903	5	74
2904	5	248
2905	5	399
2906	1	488
2907	1	487
2908	1	486
2909	1	485
2910	1	484
2911	1	483
2912	1	482
2913	1	499
2914	1	498
2915	1	497
2916	1	496
2917	1	495
2918	1	494
2919	1	493
2920	1	492
2921	1	491
2922	1	490
2923	1	489
2924	2	499
2925	2	498
2926	2	497
2927	2	496
2928	2	495
2929	2	494
2930	2	493
2931	2	492
2932	2	491
2933	2	490
2934	2	489
2935	2	488
2936	2	487
2937	2	486
2938	2	485
2939	2	484
2940	2	483
2941	2	482
2946	6	228
2947	6	241
2948	6	236
2949	6	235
2950	6	231
2951	6	230
2952	2	305
2953	2	309
2954	2	308
2955	6	276
2956	6	281
2957	6	280
2958	6	279
2959	6	289
2960	6	288
2961	6	290
2962	6	166
2963	6	167
2964	6	168
2965	6	263
2966	6	264
2967	6	266
2968	8	166
2969	8	167
2970	8	168
2971	8	263
2972	8	264
2973	8	266
2974	3	238
2975	8	228
2976	8	230
2977	8	231
2978	8	235
2979	8	236
2980	8	241
2981	4	322
2982	6	319
2983	6	323
2984	6	324
2985	8	319
2986	8	320
2987	8	321
2988	8	322
2989	8	323
2990	8	324
2991	6	300
2992	6	310
2993	6	313
2994	6	330
2995	4	256
2996	8	480
2997	8	479
2998	8	456
2999	8	370
3000	8	369
3001	8	368
3002	8	366
3003	8	365
3004	8	271
3005	8	270
3006	8	244
3007	8	243
3008	8	242
3009	8	240
3010	8	239
3011	8	237
3012	8	233
3013	8	223
3014	8	214
3015	8	213
3016	8	202
3017	8	201
3018	8	199
3019	8	198
3020	8	173
3021	8	158
3022	8	154
3023	8	145
3024	8	135
3025	8	121
3026	8	120
3027	7	456
3028	7	479
3029	7	480
3030	7	224
3031	7	158
3032	6	252
3033	5	278
3034	8	276
3035	8	279
3036	8	280
3037	8	281
3038	8	303
3039	8	302
3040	8	290
3041	8	289
3042	8	288
3043	8	286
3044	8	285
3045	8	287
3046	3	498
3047	3	497
3048	3	496
3049	3	487
3050	3	486
3051	3	485
3052	3	484
3053	3	483
3054	3	482
3055	4	496
3056	4	487
3057	4	486
3058	4	485
3059	4	484
3060	4	483
3061	4	482
3062	8	71
3063	8	72
3064	8	73
3065	8	76
3066	8	77
3067	8	227
3068	6	408
3069	6	409
3070	6	444
3071	6	447
3072	6	451
3073	8	164
3074	8	163
3075	6	293
3076	8	291
3077	8	292
3078	8	293
3079	8	298
3080	8	294
3081	6	219
3082	8	219
3083	7	247
3084	7	246
3085	8	246
3086	8	247
3087	8	249
3088	8	250
3089	8	251
3090	8	253
3091	8	254
3092	6	301
3093	8	312
3094	6	331
3095	6	332
3096	6	333
3097	6	334
3098	6	335
3099	6	341
3100	6	344
3101	6	336
3102	6	338
3103	6	172
3104	6	342
3105	8	342
3106	8	338
3107	2	457
3108	3	488
3109	3	457
3110	3	134
3111	4	488
3112	4	457
3113	4	134
3114	5	488
3115	5	457
3116	5	134
3117	6	488
3118	6	457
3119	6	134
3120	7	488
3121	7	457
3122	8	488
3123	8	457
3124	9	488
3125	9	457
3126	10	488
3127	10	457
3128	11	488
3129	11	457
3130	12	488
3131	12	457
3132	6	80
3133	6	433
3134	8	433
3135	3	499
3136	4	499
3137	5	499
3138	6	284
3139	6	170
3140	6	283
3141	6	282
3142	6	435
3143	6	499
3144	6	272
3145	7	499
3146	8	499
3147	8	435
3148	8	284
3149	8	283
3150	8	282
3151	8	170
3152	6	175
3153	6	461
3154	8	175
3155	8	461
3156	3	490
3157	3	489
3158	4	490
3159	4	489
3160	5	490
3161	5	489
3162	6	490
3163	6	489
3164	7	490
3165	7	489
3166	8	490
3167	8	489
3168	2	470
3169	3	470
3170	4	470
3171	5	470
3172	6	470
3174	8	470
3175	9	470
3176	10	470
3177	3	492
3178	3	491
3179	4	492
3180	4	491
3181	5	492
3182	5	491
3183	6	492
3184	6	491
3185	7	492
3186	7	491
3187	8	492
3188	8	491
3189	3	494
3190	3	493
3191	4	494
3192	4	493
3193	5	494
3194	5	493
3195	6	494
3196	6	493
3197	7	494
3198	7	493
3199	8	494
3200	8	493
3201	7	470
3202	6	473
3203	8	473
3204	2	256
3205	2	252
3206	2	224
3207	1	504
3208	1	503
3209	1	502
3210	1	501
3211	1	500
3212	2	504
3213	2	503
3214	2	502
3215	2	501
3216	2	500
3217	3	495
3218	3	500
3219	3	501
3220	3	502
3221	3	503
3222	3	504
3223	3	481
3224	3	474
3226	3	477
3230	2	316
3231	2	315
3232	2	441
3233	2	430
3234	4	18
3235	4	19
3236	4	20
3237	4	108
3238	5	18
3239	5	19
3240	5	20
3241	5	108
3242	6	15
3243	6	17
3244	6	18
3245	6	19
3246	6	20
3247	6	21
3248	6	108
3249	8	108
3250	8	21
3251	8	20
3252	8	19
3253	8	18
3254	8	17
3255	8	16
3256	8	15
3257	8	14
3258	9	108
3259	9	21
3260	9	20
3261	9	19
3262	9	18
3263	9	17
3264	9	16
3265	9	15
3266	9	14
3267	10	108
3268	10	21
3269	10	20
3270	10	19
3271	10	18
3272	10	17
3273	10	16
3274	10	15
3275	10	14
3276	11	108
3277	11	21
3278	11	20
3279	11	19
3280	11	18
3281	11	17
3282	11	16
3283	11	15
3284	11	14
3285	15	431
3286	15	28
3287	15	27
3288	15	24
3289	15	23
3290	15	22
3291	4	504
3292	4	503
3293	4	502
3294	4	501
3295	4	500
3296	5	504
3297	5	503
3298	5	502
3299	5	501
3300	5	500
3301	6	504
3302	6	503
3303	6	502
3304	6	501
3305	6	500
3306	7	504
3307	7	503
3308	7	502
3309	7	501
3310	7	500
3311	8	504
3312	8	503
3313	8	502
3314	8	501
3315	8	500
3316	6	262
3317	8	262
3318	2	267
3319	6	265
3320	6	269
3321	6	268
3322	8	265
3323	8	268
3324	8	269
3325	2	238
3480	16	101
3493	1	530
3326	2	322
3327	6	320
3328	6	321
3329	6	256
3330	2	278
3331	3	278
3332	8	277
3333	6	277
3334	2	299
3335	2	297
3336	2	296
3337	6	299
3338	6	297
3339	6	296
3340	2	481
3341	4	481
3342	5	481
3343	6	481
3344	7	481
3345	8	481
3346	4	498
3347	4	497
3348	5	496
3349	1	505
3350	1	506
3351	1	509
3352	1	508
3353	1	507
3354	3	505
3355	3	506
3507	3	538
3357	3	508
3358	3	507
3359	4	505
3360	4	506
3361	4	509
3362	4	508
3363	4	507
3364	2	248
3521	4	535
3365	2	399
3366	3	509
3367	2	506
3368	2	507
3535	7	532
3369	1	516
3370	1	515
3371	1	513
3372	1	512
3373	1	511
3374	1	510
3375	2	516
3376	2	515
3549	16	394
3377	2	513
3378	2	512
3379	2	511
3562	16	33
3380	2	510
3381	2	509
3382	2	508
3383	2	505
3384	3	516
3385	3	515
3386	3	513
3387	3	512
3388	3	511
3389	3	510
3390	4	516
3391	4	515
3392	4	513
3393	4	512
3394	4	511
3395	4	510
3396	7	516
3397	7	515
3398	7	513
3399	7	512
3400	7	511
3401	7	510
3402	7	509
3403	7	508
3404	7	507
3405	2	58
3406	2	57
3407	2	56
3408	2	55
3409	2	54
3410	2	53
3411	2	52
3412	2	110
3413	2	109
3414	2	111
3415	1	518
3416	1	517
3417	1	514
3418	4	518
3419	2	518
3420	3	518
3421	7	518
3422	1	523
3423	1	522
3424	1	521
3425	1	520
3426	1	519
3427	2	523
3428	2	522
3429	2	521
3430	2	520
3431	2	519
3432	3	523
3433	3	522
3434	3	521
3435	3	520
3436	3	519
3437	4	523
3438	4	522
3439	4	521
3440	4	520
3441	4	519
3442	7	523
3443	7	522
3444	7	521
3445	7	520
3446	7	519
3447	1	527
3448	1	526
3449	1	525
3450	1	524
3451	2	527
3452	2	526
3453	2	525
3454	2	524
3455	3	527
3456	3	526
3457	3	525
3458	3	524
3459	4	527
3460	4	526
3461	4	525
3462	4	524
3463	7	527
3464	7	526
3465	7	525
3569	7	517
3582	2	542
3595	7	544
3607	1	547
3619	3	547
3630	7	548
3635	1	611
3636	1	610
3637	1	609
3638	1	608
3639	1	607
3640	1	606
3641	1	605
3642	1	604
3643	1	603
3644	1	602
3645	1	601
3646	1	600
3647	1	599
3648	1	598
3649	1	597
3650	1	596
3651	1	595
3652	1	594
3653	1	593
3654	1	592
3655	1	591
3656	1	590
3657	1	589
3658	1	588
3659	1	587
3660	1	586
3661	1	585
3662	1	584
3663	1	583
3664	1	582
3665	1	581
3666	1	580
3667	1	579
3668	1	578
3669	1	577
3670	1	576
3671	1	575
3672	1	574
3673	1	573
3674	1	572
3675	1	571
3676	1	570
3677	1	569
3678	1	568
3679	1	567
3680	1	566
3681	1	565
3682	1	564
3683	1	563
3684	1	562
3685	2	611
3686	2	610
3687	2	609
3688	2	607
3689	2	608
3690	2	606
3691	2	605
3692	2	603
3693	2	604
3694	2	602
3695	2	601
3696	2	600
3697	2	599
3698	2	598
3699	2	597
3700	2	596
3701	2	595
3702	2	594
3703	2	593
3704	2	592
3705	2	591
3706	2	590
3707	2	589
3708	2	588
3709	2	587
3710	2	586
3711	2	585
3712	2	584
3713	2	583
3714	2	582
3715	2	581
3716	2	580
3717	2	579
3718	2	578
3719	2	577
3720	2	576
3721	2	574
3722	2	575
3723	2	573
3724	2	572
3725	2	571
3726	2	570
3727	2	569
3728	2	568
3729	2	567
3730	2	566
3731	2	565
3732	2	564
3733	2	563
3734	2	562
3735	3	611
3736	3	610
3737	3	609
3738	3	608
3739	3	607
3740	3	606
3741	3	605
3742	3	604
3743	3	603
3744	3	602
3745	3	601
3746	3	600
3747	3	599
3748	3	598
3749	3	597
3750	3	596
3751	3	595
3752	3	594
3753	3	593
3754	3	592
3755	3	591
3756	3	590
3757	3	589
3758	3	588
3759	3	587
3760	3	586
3761	3	585
3762	3	584
3763	3	583
3764	3	582
3765	3	581
3766	3	580
3767	3	579
3768	3	578
3769	3	577
3770	3	576
3771	3	575
3772	3	574
3773	3	573
3774	3	572
3775	3	571
3776	3	570
3777	3	569
3778	3	568
3779	3	567
3780	3	566
3781	3	565
3782	3	564
3783	3	563
3784	3	562
3785	4	611
3786	4	610
3787	4	609
3788	4	608
3789	4	607
3790	4	606
3791	4	605
3792	4	604
3793	4	603
3794	4	602
3795	4	601
3796	4	600
3797	4	599
3798	4	598
3799	4	597
3800	4	596
3801	4	595
3802	4	594
3803	4	593
3804	4	592
3805	4	591
3806	4	590
3807	4	589
3808	4	588
3809	4	587
3810	4	586
3811	4	585
3812	4	584
3813	4	583
3814	4	582
3815	4	581
3816	4	579
3817	4	580
3818	4	578
3819	4	577
3820	4	576
3821	4	575
3822	4	574
3823	4	573
3824	4	572
3825	4	571
3826	4	570
3827	4	569
3828	4	568
3829	4	566
3830	4	567
3831	4	565
3832	4	564
3833	4	563
3834	4	562
3835	7	611
3836	7	610
3837	7	609
3838	7	608
3839	7	607
3840	7	606
3841	7	604
3842	7	605
3843	7	603
3844	7	602
3845	7	601
3846	7	600
3847	7	599
3848	7	598
3849	7	597
3850	7	596
3851	7	595
3852	7	594
3853	7	593
3854	7	592
3855	7	591
3856	7	590
3857	7	589
3858	7	588
3859	7	587
3860	7	586
3861	7	585
3862	7	584
3863	7	583
3864	7	582
3865	7	581
3866	7	580
3867	7	579
3868	7	578
3869	7	577
3870	7	576
3871	7	575
3872	7	574
3873	7	573
3874	7	572
3875	7	571
3876	7	570
3877	7	569
3878	7	568
3879	7	567
3880	7	566
3881	7	565
3882	7	564
3883	7	563
3884	7	562
3885	2	343
3886	2	105
3887	2	101
3888	2	37
3889	2	36
3890	2	35
3891	2	34
3892	2	134
3893	2	514
3894	2	478
3895	2	477
3896	2	474
3897	2	229
3898	1	561
3899	1	560
3900	1	559
3901	1	558
3902	1	557
3903	1	556
3904	7	86
3905	7	85
3906	7	84
3907	7	83
3908	7	82
3909	7	81
3910	7	299
3911	7	297
3912	7	296
3913	7	290
3914	7	289
3915	7	288
3916	7	239
3917	7	237
3918	7	233
3919	7	222
3920	7	221
3921	7	220
3922	7	214
3923	7	213
3924	7	202
3925	7	201
3926	7	199
3927	7	198
3928	7	173
3929	7	145
3930	7	135
3931	7	120
3932	7	312
3933	7	311
3934	7	301
3935	7	238
3936	7	234
3937	7	232
3938	7	278
3939	7	277
3940	7	276
3941	7	343
3942	7	341
3943	7	335
3944	7	514
3945	7	229
3946	7	225
3947	7	219
3948	7	399
3949	7	275
3950	7	273
3951	7	272
3952	7	134
3953	7	295
3954	7	294
3955	7	293
3956	7	257
3957	7	255
3958	7	254
3959	7	253
3960	7	248
3961	7	452
3962	7	443
3963	7	409
3964	7	269
3965	7	268
3966	7	267
3967	7	265
3968	7	322
3969	7	321
3970	7	320
3971	1	612
3972	2	612
3973	3	612
3974	4	612
3975	7	612
3976	11	71
3977	11	72
3978	11	519
\.


--
-- TOC entry 4120 (class 0 OID 10423209)
-- Dependencies: 341
-- Data for Name: tb_permissaoprojeto; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_permissaoprojeto (idparteinteressada, idprojeto, idrecurso, idpermissao, idpessoa, data, ativo) FROM stdin;
2	1	91	553	2	2022-10-11	S
2	1	91	546	2	2022-10-11	S
2	1	6	71	2	2022-10-11	S
2	1	6	72	2	2022-10-11	S
2	1	6	73	2	2022-10-11	S
2	1	6	74	2	2022-10-11	S
2	1	6	75	2	2022-10-11	S
2	1	6	76	2	2022-10-11	S
2	1	6	77	2	2022-10-11	S
2	1	23	149	2	2022-10-11	S
2	1	23	151	2	2022-10-11	S
2	1	23	152	2	2022-10-11	S
2	1	23	155	2	2022-10-11	S
2	1	23	156	2	2022-10-11	S
2	1	21	158	2	2022-10-11	S
2	1	21	154	2	2022-10-11	S
2	1	21	153	2	2022-10-11	S
2	1	91	554	2	2022-10-11	S
2	1	91	547	2	2022-10-11	S
2	1	38	249	2	2022-10-11	S
2	1	28	268	2	2022-10-11	S
2	1	44	300	2	2022-10-11	S
2	1	23	165	2	2022-10-11	S
2	1	38	246	2	2022-10-11	S
2	1	44	311	2	2022-10-11	S
2	1	35	217	2	2022-10-11	S
2	1	29	275	2	2022-10-11	S
2	1	41	297	2	2022-10-11	S
2	1	41	286	2	2022-10-11	S
2	1	41	299	2	2022-10-11	S
2	1	21	198	2	2022-10-11	S
2	1	21	256	2	2022-10-11	S
2	1	21	270	2	2022-10-11	S
2	1	21	213	2	2022-10-11	S
2	1	21	222	2	2022-10-11	S
2	1	42	287	2	2022-10-11	S
2	1	21	237	2	2022-10-11	S
2	1	40	278	2	2022-10-11	S
2	1	40	280	2	2022-10-11	S
2	1	38	254	2	2022-10-11	S
2	1	37	228	2	2022-10-11	S
2	1	38	257	2	2022-10-11	S
2	1	38	253	2	2022-10-11	S
2	1	38	250	2	2022-10-11	S
2	1	6	227	2	2022-10-11	S
2	1	37	238	2	2022-10-11	S
2	1	37	236	2	2022-10-11	S
2	1	37	232	2	2022-10-11	S
2	1	44	313	2	2022-10-11	S
2	1	44	312	2	2022-10-11	S
2	1	44	301	2	2022-10-11	S
2	1	43	295	2	2022-10-11	S
2	1	43	298	2	2022-10-11	S
2	1	30	171	2	2022-10-11	S
2	1	35	225	2	2022-10-11	S
2	1	30	172	2	2022-10-11	S
2	1	35	229	2	2022-10-11	S
2	1	38	255	2	2022-10-11	S
2	1	29	282	2	2022-10-11	S
2	1	31	174	2	2022-10-11	S
2	1	29	170	2	2022-10-11	S
2	1	37	234	2	2022-10-11	S
2	1	43	292	2	2022-10-11	S
2	1	16	245	2	2022-10-11	S
2	1	41	285	2	2022-10-11	S
2	1	41	290	2	2022-10-11	S
2	1	21	220	2	2022-10-11	S
2	1	21	233	2	2022-10-11	S
2	1	21	252	2	2022-10-11	S
2	1	38	251	2	2022-10-11	S
2	1	38	247	2	2022-10-11	S
2	1	38	248	2	2022-10-11	S
2	1	37	230	2	2022-10-11	S
2	1	37	231	2	2022-10-11	S
2	1	37	235	2	2022-10-11	S
2	1	37	241	2	2022-10-11	S
2	1	28	267	2	2022-10-11	S
2	1	43	294	2	2022-10-11	S
2	1	40	277	2	2022-10-11	S
2	1	35	219	2	2022-10-11	S
2	1	23	216	2	2022-10-11	S
2	1	29	169	2	2022-10-11	S
2	1	29	283	2	2022-10-11	S
2	1	29	284	2	2022-10-11	S
2	1	31	175	2	2022-10-11	S
2	1	92	555	2	2022-10-11	S
2	1	91	548	2	2022-10-11	S
2	1	39	262	2	2022-10-11	S
2	1	28	264	2	2022-10-11	S
2	1	28	166	2	2022-10-11	S
2	1	21	240	2	2022-10-11	S
2	1	21	214	2	2022-10-11	S
2	1	35	218	2	2022-10-11	S
2	1	60	444	2	2022-10-11	S
2	1	58	433	2	2022-10-11	S
2	1	30	340	2	2022-10-11	S
2	1	30	338	2	2022-10-11	S
2	1	49	343	2	2022-10-11	S
2	1	49	344	2	2022-10-11	S
2	1	49	331	2	2022-10-11	S
2	1	49	335	2	2022-10-11	S
2	1	6	400	2	2022-10-11	S
2	1	6	414	2	2022-10-11	S
2	1	6	417	2	2022-10-11	S
2	1	30	337	2	2022-10-11	S
2	1	23	457	2	2022-10-11	S
2	1	29	435	2	2022-10-11	S
2	1	60	443	2	2022-10-11	S
2	1	60	451	2	2022-10-11	S
2	1	49	341	2	2022-10-11	S
2	1	16	80	2	2022-10-11	S
2	1	16	81	2	2022-10-11	S
2	1	16	82	2	2022-10-11	S
2	1	16	83	2	2022-10-11	S
2	1	16	84	2	2022-10-11	S
2	1	16	85	2	2022-10-11	S
2	1	16	86	2	2022-10-11	S
2	1	16	113	2	2022-10-11	S
2	1	16	131	2	2022-10-11	S
2	1	16	132	2	2022-10-11	S
2	1	16	304	2	2022-10-11	S
2	1	41	302	2	2022-10-11	S
2	1	41	303	2	2022-10-11	S
2	1	41	288	2	2022-10-11	S
2	1	41	289	2	2022-10-11	S
2	1	84	492	2	2022-10-11	S
2	1	21	479	2	2022-10-11	S
2	1	31	461	2	2022-10-11	S
2	1	21	456	2	2022-10-11	S
2	1	60	447	2	2022-10-11	S
2	1	21	369	2	2022-10-11	S
2	1	21	368	2	2022-10-11	S
2	1	21	366	2	2022-10-11	S
2	1	41	296	2	2022-10-11	S
2	1	21	120	2	2022-10-11	S
2	1	21	121	2	2022-10-11	S
2	1	21	135	2	2022-10-11	S
2	1	21	145	2	2022-10-11	S
2	1	21	173	2	2022-10-11	S
2	1	21	199	2	2022-10-11	S
2	1	21	201	2	2022-10-11	S
2	1	21	202	2	2022-10-11	S
2	1	21	221	2	2022-10-11	S
2	1	21	223	2	2022-10-11	S
2	1	21	239	2	2022-10-11	S
2	1	21	243	2	2022-10-11	S
2	1	21	244	2	2022-10-11	S
2	1	21	271	2	2022-10-11	S
2	1	44	310	2	2022-10-11	S
2	1	44	330	2	2022-10-11	S
2	1	28	167	2	2022-10-11	S
2	1	28	263	2	2022-10-11	S
2	1	28	265	2	2022-10-11	S
2	1	28	266	2	2022-10-11	S
2	1	28	269	2	2022-10-11	S
2	1	43	291	2	2022-10-11	S
2	1	43	293	2	2022-10-11	S
2	1	40	276	2	2022-10-11	S
2	1	40	279	2	2022-10-11	S
2	1	40	281	2	2022-10-11	S
2	1	30	336	2	2022-10-11	S
2	1	30	339	2	2022-10-11	S
2	1	30	342	2	2022-10-11	S
2	1	29	272	2	2022-10-11	S
2	1	29	273	2	2022-10-11	S
2	1	29	274	2	2022-10-11	S
2	1	60	408	2	2022-10-11	S
2	1	60	409	2	2022-10-11	S
2	1	60	452	2	2022-10-11	S
2	1	58	399	2	2022-10-11	S
2	1	91	549	2	2022-10-11	S
2	1	91	550	2	2022-10-11	S
2	1	91	551	2	2022-10-11	S
2	1	91	552	2	2022-10-11	S
2	1	97	562	2	2022-10-11	S
2	1	97	564	2	2022-10-11	S
2	1	95	587	2	2022-10-11	S
2	1	97	566	2	2022-10-11	S
2	1	95	586	2	2022-10-11	S
2	1	98	567	2	2022-10-11	S
2	1	95	583	2	2022-10-11	S
2	1	98	568	2	2022-10-11	S
2	1	95	582	2	2022-10-11	S
2	1	95	580	2	2022-10-11	S
2	1	95	581	2	2022-10-11	S
2	1	95	579	2	2022-10-11	S
2	1	98	571	2	2022-10-11	S
2	1	95	578	2	2022-10-11	S
2	1	95	577	2	2022-10-11	S
2	1	95	576	2	2022-10-11	S
2	1	98	573	2	2022-10-11	S
2	1	98	575	2	2022-10-11	S
2	1	91	556	2	2022-10-11	S
2	1	97	559	2	2022-10-11	S
2	1	96	557	2	2022-10-11	S
2	1	97	560	2	2022-10-11	S
2	1	90	545	2	2022-10-11	S
2	1	90	544	2	2022-10-11	S
2	1	89	538	2	2022-10-11	S
2	1	31	537	2	2022-10-11	S
2	1	29	534	2	2022-10-11	S
2	1	29	535	2	2022-10-11	S
2	1	16	531	2	2022-10-11	S
2	1	16	532	2	2022-10-11	S
2	1	6	529	2	2022-10-11	S
2	1	88	528	2	2022-10-11	S
2	1	23	527	2	2022-10-11	S
2	1	35	526	2	2022-10-11	S
2	1	21	524	2	2022-10-11	S
2	1	44	523	2	2022-10-11	S
2	1	8	521	2	2022-10-11	S
2	1	21	517	2	2022-10-11	S
2	1	35	516	2	2022-10-11	S
2	1	35	514	2	2022-10-11	S
2	1	21	513	2	2022-10-11	S
2	1	86	519	2	2022-10-11	S
2	1	8	520	2	2022-10-11	S
2	1	31	509	2	2022-10-11	S
2	1	21	510	2	2022-10-11	S
2	1	29	508	2	2022-10-11	S
2	1	27	507	2	2022-10-11	S
2	1	16	506	2	2022-10-11	S
2	1	35	406	2	2022-10-11	S
2	1	42	504	2	2022-10-11	S
2	1	42	503	2	2022-10-11	S
2	1	42	501	2	2022-10-11	S
2	1	42	500	2	2022-10-11	S
2	1	29	499	2	2022-10-11	S
2	1	6	497	2	2022-10-11	S
2	1	6	496	2	2022-10-11	S
2	1	57	495	2	2022-10-11	S
2	1	85	494	2	2022-10-11	S
2	1	84	491	2	2022-10-11	S
2	1	83	490	2	2022-10-11	S
2	1	83	489	2	2022-10-11	S
2	1	82	481	2	2022-10-11	S
2	1	28	505	2	2022-10-11	S
2	1	21	611	2	2022-10-11	S
2	1	21	610	2	2022-10-11	S
2	1	21	607	2	2022-10-11	S
2	1	21	606	2	2022-10-11	S
2	1	99	605	2	2022-10-11	S
2	1	99	604	2	2022-10-11	S
2	1	99	603	2	2022-10-11	S
2	1	99	602	2	2022-10-11	S
2	1	99	601	2	2022-10-11	S
2	1	99	600	2	2022-10-11	S
2	1	99	599	2	2022-10-11	S
2	1	95	596	2	2022-10-11	S
2	1	99	598	2	2022-10-11	S
2	1	99	597	2	2022-10-11	S
2	1	95	594	2	2022-10-11	S
2	1	95	593	2	2022-10-11	S
2	1	95	595	2	2022-10-11	S
2	1	95	591	2	2022-10-11	S
2	1	95	592	2	2022-10-11	S
2	1	95	590	2	2022-10-11	S
2	1	95	585	2	2022-10-11	S
2	1	95	589	2	2022-10-11	S
2	1	95	588	2	2022-10-11	S
2	1	97	563	2	2022-10-11	S
2	1	97	565	2	2022-10-11	S
2	1	95	584	2	2022-10-11	S
2	1	98	569	2	2022-10-11	S
2	1	98	570	2	2022-10-11	S
2	1	98	572	2	2022-10-11	S
2	1	98	574	2	2022-10-11	S
2	1	97	558	2	2022-10-11	S
2	1	97	561	2	2022-10-11	S
2	1	21	543	2	2022-10-11	S
2	1	21	542	2	2022-10-11	S
2	1	21	541	2	2022-10-11	S
2	1	31	536	2	2022-10-11	S
2	1	16	533	2	2022-10-11	S
2	1	16	530	2	2022-10-11	S
2	1	6	525	2	2022-10-11	S
2	1	87	522	2	2022-10-11	S
2	1	21	518	2	2022-10-11	S
2	1	35	515	2	2022-10-11	S
2	1	21	512	2	2022-10-11	S
2	1	21	511	2	2022-10-11	S
2	1	42	502	2	2022-10-11	S
2	1	6	498	2	2022-10-11	S
2	1	85	493	2	2022-10-11	S
2	1	23	488	2	2022-10-11	S
2	1	21	480	2	2022-10-11	S
2	1	23	460	2	2022-10-11	S
2	1	21	370	2	2022-10-11	S
2	1	21	365	2	2022-10-11	S
2	1	21	242	2	2022-10-11	S
2	1	21	224	2	2022-10-11	S
2	1	21	608	2	2022-10-11	S
2	1	35	612	2	2022-10-11	S
2	1	21	609	2	2022-10-11	S
5	2	91	553	1	2022-10-11	S
5	2	91	546	1	2022-10-11	S
5	2	6	71	1	2022-10-11	S
5	2	6	72	1	2022-10-11	S
5	2	6	73	1	2022-10-11	S
5	2	6	74	1	2022-10-11	S
5	2	6	75	1	2022-10-11	S
5	2	6	76	1	2022-10-11	S
5	2	6	77	1	2022-10-11	S
5	2	23	149	1	2022-10-11	S
5	2	23	151	1	2022-10-11	S
5	2	23	152	1	2022-10-11	S
5	2	23	155	1	2022-10-11	S
5	2	23	156	1	2022-10-11	S
5	2	21	158	1	2022-10-11	S
5	2	21	154	1	2022-10-11	S
5	2	21	153	1	2022-10-11	S
5	2	91	554	1	2022-10-11	S
5	2	91	547	1	2022-10-11	S
5	2	38	249	1	2022-10-11	S
5	2	28	268	1	2022-10-11	S
5	2	44	300	1	2022-10-11	S
5	2	23	165	1	2022-10-11	S
5	2	38	246	1	2022-10-11	S
5	2	44	311	1	2022-10-11	S
5	2	35	217	1	2022-10-11	S
5	2	29	275	1	2022-10-11	S
5	2	41	297	1	2022-10-11	S
5	2	41	286	1	2022-10-11	S
5	2	41	299	1	2022-10-11	S
5	2	21	198	1	2022-10-11	S
5	2	21	256	1	2022-10-11	S
5	2	21	270	1	2022-10-11	S
5	2	21	213	1	2022-10-11	S
5	2	21	222	1	2022-10-11	S
5	2	42	287	1	2022-10-11	S
5	2	21	237	1	2022-10-11	S
5	2	40	278	1	2022-10-11	S
5	2	40	280	1	2022-10-11	S
5	2	38	254	1	2022-10-11	S
5	2	37	228	1	2022-10-11	S
5	2	38	257	1	2022-10-11	S
5	2	38	253	1	2022-10-11	S
5	2	38	250	1	2022-10-11	S
5	2	6	227	1	2022-10-11	S
5	2	37	238	1	2022-10-11	S
5	2	37	236	1	2022-10-11	S
5	2	37	232	1	2022-10-11	S
5	2	44	313	1	2022-10-11	S
5	2	44	312	1	2022-10-11	S
5	2	44	301	1	2022-10-11	S
5	2	43	295	1	2022-10-11	S
5	2	43	298	1	2022-10-11	S
5	2	30	171	1	2022-10-11	S
5	2	35	225	1	2022-10-11	S
5	2	30	172	1	2022-10-11	S
5	2	35	229	1	2022-10-11	S
5	2	38	255	1	2022-10-11	S
5	2	29	282	1	2022-10-11	S
5	2	31	174	1	2022-10-11	S
5	2	29	170	1	2022-10-11	S
5	2	37	234	1	2022-10-11	S
5	2	43	292	1	2022-10-11	S
5	2	16	245	1	2022-10-11	S
5	2	41	285	1	2022-10-11	S
5	2	41	290	1	2022-10-11	S
5	2	21	220	1	2022-10-11	S
5	2	21	233	1	2022-10-11	S
5	2	21	252	1	2022-10-11	S
5	2	38	251	1	2022-10-11	S
5	2	38	247	1	2022-10-11	S
5	2	38	248	1	2022-10-11	S
5	2	37	230	1	2022-10-11	S
5	2	37	231	1	2022-10-11	S
5	2	37	235	1	2022-10-11	S
5	2	37	241	1	2022-10-11	S
5	2	28	267	1	2022-10-11	S
5	2	43	294	1	2022-10-11	S
5	2	40	277	1	2022-10-11	S
5	2	35	219	1	2022-10-11	S
5	2	23	216	1	2022-10-11	S
5	2	29	169	1	2022-10-11	S
5	2	29	283	1	2022-10-11	S
5	2	29	284	1	2022-10-11	S
5	2	31	175	1	2022-10-11	S
5	2	92	555	1	2022-10-11	S
5	2	91	548	1	2022-10-11	S
5	2	39	262	1	2022-10-11	S
5	2	28	264	1	2022-10-11	S
5	2	28	166	1	2022-10-11	S
5	2	21	240	1	2022-10-11	S
5	2	21	214	1	2022-10-11	S
5	2	35	218	1	2022-10-11	S
5	2	60	444	1	2022-10-11	S
5	2	58	433	1	2022-10-11	S
5	2	30	340	1	2022-10-11	S
5	2	30	338	1	2022-10-11	S
5	2	49	343	1	2022-10-11	S
5	2	49	344	1	2022-10-11	S
5	2	49	331	1	2022-10-11	S
5	2	49	335	1	2022-10-11	S
5	2	6	400	1	2022-10-11	S
5	2	6	414	1	2022-10-11	S
5	2	6	417	1	2022-10-11	S
5	2	30	337	1	2022-10-11	S
5	2	23	457	1	2022-10-11	S
5	2	29	435	1	2022-10-11	S
5	2	60	443	1	2022-10-11	S
5	2	60	451	1	2022-10-11	S
5	2	49	341	1	2022-10-11	S
5	2	16	80	1	2022-10-11	S
5	2	16	81	1	2022-10-11	S
5	2	16	82	1	2022-10-11	S
5	2	16	83	1	2022-10-11	S
5	2	16	84	1	2022-10-11	S
5	2	16	85	1	2022-10-11	S
5	2	16	86	1	2022-10-11	S
5	2	16	113	1	2022-10-11	S
5	2	16	131	1	2022-10-11	S
5	2	16	132	1	2022-10-11	S
5	2	16	304	1	2022-10-11	S
5	2	41	302	1	2022-10-11	S
5	2	41	303	1	2022-10-11	S
5	2	41	288	1	2022-10-11	S
5	2	41	289	1	2022-10-11	S
5	2	84	492	1	2022-10-11	S
5	2	21	479	1	2022-10-11	S
5	2	31	461	1	2022-10-11	S
5	2	21	456	1	2022-10-11	S
5	2	60	447	1	2022-10-11	S
5	2	21	369	1	2022-10-11	S
5	2	21	368	1	2022-10-11	S
5	2	21	366	1	2022-10-11	S
5	2	41	296	1	2022-10-11	S
5	2	21	120	1	2022-10-11	S
5	2	21	121	1	2022-10-11	S
5	2	21	135	1	2022-10-11	S
5	2	21	145	1	2022-10-11	S
5	2	21	173	1	2022-10-11	S
5	2	21	199	1	2022-10-11	S
5	2	21	201	1	2022-10-11	S
5	2	21	202	1	2022-10-11	S
5	2	21	221	1	2022-10-11	S
5	2	21	223	1	2022-10-11	S
5	2	21	239	1	2022-10-11	S
5	2	21	243	1	2022-10-11	S
5	2	21	244	1	2022-10-11	S
5	2	21	271	1	2022-10-11	S
5	2	44	310	1	2022-10-11	S
5	2	44	330	1	2022-10-11	S
5	2	28	167	1	2022-10-11	S
5	2	28	263	1	2022-10-11	S
5	2	28	265	1	2022-10-11	S
5	2	28	266	1	2022-10-11	S
5	2	28	269	1	2022-10-11	S
5	2	43	291	1	2022-10-11	S
5	2	43	293	1	2022-10-11	S
5	2	40	276	1	2022-10-11	S
5	2	40	279	1	2022-10-11	S
5	2	40	281	1	2022-10-11	S
5	2	30	336	1	2022-10-11	S
5	2	30	339	1	2022-10-11	S
5	2	30	342	1	2022-10-11	S
5	2	29	272	1	2022-10-11	S
5	2	29	273	1	2022-10-11	S
5	2	29	274	1	2022-10-11	S
5	2	60	408	1	2022-10-11	S
5	2	60	409	1	2022-10-11	S
5	2	60	452	1	2022-10-11	S
5	2	58	399	1	2022-10-11	S
5	2	91	549	1	2022-10-11	S
5	2	91	550	1	2022-10-11	S
5	2	91	551	1	2022-10-11	S
5	2	91	552	1	2022-10-11	S
5	2	97	562	1	2022-10-11	S
5	2	97	564	1	2022-10-11	S
5	2	95	587	1	2022-10-11	S
5	2	97	566	1	2022-10-11	S
5	2	95	586	1	2022-10-11	S
5	2	98	567	1	2022-10-11	S
5	2	95	583	1	2022-10-11	S
5	2	98	568	1	2022-10-11	S
5	2	95	582	1	2022-10-11	S
5	2	95	580	1	2022-10-11	S
5	2	95	581	1	2022-10-11	S
5	2	95	579	1	2022-10-11	S
5	2	98	571	1	2022-10-11	S
5	2	95	578	1	2022-10-11	S
5	2	95	577	1	2022-10-11	S
5	2	95	576	1	2022-10-11	S
5	2	98	573	1	2022-10-11	S
5	2	98	575	1	2022-10-11	S
5	2	91	556	1	2022-10-11	S
5	2	97	559	1	2022-10-11	S
5	2	96	557	1	2022-10-11	S
5	2	97	560	1	2022-10-11	S
5	2	90	545	1	2022-10-11	S
5	2	90	544	1	2022-10-11	S
5	2	89	538	1	2022-10-11	S
5	2	31	537	1	2022-10-11	S
5	2	29	534	1	2022-10-11	S
5	2	29	535	1	2022-10-11	S
5	2	16	531	1	2022-10-11	S
5	2	16	532	1	2022-10-11	S
5	2	6	529	1	2022-10-11	S
5	2	88	528	1	2022-10-11	S
5	2	23	527	1	2022-10-11	S
5	2	35	526	1	2022-10-11	S
5	2	21	524	1	2022-10-11	S
5	2	44	523	1	2022-10-11	S
5	2	8	521	1	2022-10-11	S
5	2	21	517	1	2022-10-11	S
5	2	35	516	1	2022-10-11	S
5	2	35	514	1	2022-10-11	S
5	2	21	513	1	2022-10-11	S
5	2	86	519	1	2022-10-11	S
5	2	8	520	1	2022-10-11	S
5	2	31	509	1	2022-10-11	S
5	2	21	510	1	2022-10-11	S
5	2	29	508	1	2022-10-11	S
5	2	27	507	1	2022-10-11	S
5	2	16	506	1	2022-10-11	S
5	2	35	406	1	2022-10-11	S
5	2	42	504	1	2022-10-11	S
5	2	42	503	1	2022-10-11	S
5	2	42	501	1	2022-10-11	S
5	2	42	500	1	2022-10-11	S
5	2	29	499	1	2022-10-11	S
5	2	6	497	1	2022-10-11	S
5	2	6	496	1	2022-10-11	S
5	2	57	495	1	2022-10-11	S
5	2	85	494	1	2022-10-11	S
5	2	84	491	1	2022-10-11	S
5	2	83	490	1	2022-10-11	S
5	2	83	489	1	2022-10-11	S
5	2	82	481	1	2022-10-11	S
5	2	28	505	1	2022-10-11	S
5	2	21	611	1	2022-10-11	S
5	2	21	610	1	2022-10-11	S
5	2	21	607	1	2022-10-11	S
5	2	21	606	1	2022-10-11	S
5	2	99	605	1	2022-10-11	S
5	2	99	604	1	2022-10-11	S
5	2	99	603	1	2022-10-11	S
5	2	99	602	1	2022-10-11	S
5	2	99	601	1	2022-10-11	S
5	2	99	600	1	2022-10-11	S
5	2	99	599	1	2022-10-11	S
5	2	95	596	1	2022-10-11	S
5	2	99	598	1	2022-10-11	S
5	2	99	597	1	2022-10-11	S
5	2	95	594	1	2022-10-11	S
5	2	95	593	1	2022-10-11	S
5	2	95	595	1	2022-10-11	S
5	2	95	591	1	2022-10-11	S
5	2	95	592	1	2022-10-11	S
5	2	95	590	1	2022-10-11	S
5	2	95	585	1	2022-10-11	S
5	2	95	589	1	2022-10-11	S
5	2	95	588	1	2022-10-11	S
5	2	97	563	1	2022-10-11	S
5	2	97	565	1	2022-10-11	S
5	2	95	584	1	2022-10-11	S
5	2	98	569	1	2022-10-11	S
5	2	98	570	1	2022-10-11	S
5	2	98	572	1	2022-10-11	S
5	2	98	574	1	2022-10-11	S
5	2	97	558	1	2022-10-11	S
5	2	97	561	1	2022-10-11	S
5	2	21	543	1	2022-10-11	S
5	2	21	542	1	2022-10-11	S
5	2	21	541	1	2022-10-11	S
5	2	31	536	1	2022-10-11	S
5	2	16	533	1	2022-10-11	S
5	2	16	530	1	2022-10-11	S
5	2	6	525	1	2022-10-11	S
5	2	87	522	1	2022-10-11	S
5	2	21	518	1	2022-10-11	S
5	2	35	515	1	2022-10-11	S
5	2	21	512	1	2022-10-11	S
5	2	21	511	1	2022-10-11	S
5	2	42	502	1	2022-10-11	S
5	2	6	498	1	2022-10-11	S
5	2	85	493	1	2022-10-11	S
5	2	23	488	1	2022-10-11	S
5	2	21	480	1	2022-10-11	S
5	2	23	460	1	2022-10-11	S
5	2	21	370	1	2022-10-11	S
5	2	21	365	1	2022-10-11	S
5	2	21	242	1	2022-10-11	S
5	2	21	224	1	2022-10-11	S
5	2	21	608	1	2022-10-11	S
5	2	35	612	1	2022-10-11	S
5	2	21	609	1	2022-10-11	S
6	2	91	553	4	2022-10-11	S
6	2	91	546	4	2022-10-11	S
6	2	6	71	4	2022-10-11	S
6	2	6	72	4	2022-10-11	S
6	2	6	73	4	2022-10-11	S
6	2	6	74	4	2022-10-11	S
6	2	6	75	4	2022-10-11	S
6	2	6	76	4	2022-10-11	S
6	2	6	77	4	2022-10-11	S
6	2	23	149	4	2022-10-11	S
6	2	23	151	4	2022-10-11	S
6	2	23	152	4	2022-10-11	S
6	2	23	155	4	2022-10-11	S
6	2	23	156	4	2022-10-11	S
6	2	21	158	4	2022-10-11	S
6	2	21	154	4	2022-10-11	S
6	2	21	153	4	2022-10-11	S
6	2	91	554	4	2022-10-11	S
6	2	91	547	4	2022-10-11	S
6	2	38	249	4	2022-10-11	S
6	2	28	268	4	2022-10-11	S
6	2	44	300	4	2022-10-11	S
6	2	23	165	4	2022-10-11	S
6	2	38	246	4	2022-10-11	S
6	2	44	311	4	2022-10-11	S
6	2	35	217	4	2022-10-11	S
6	2	29	275	4	2022-10-11	S
6	2	41	297	4	2022-10-11	S
6	2	41	286	4	2022-10-11	S
6	2	41	299	4	2022-10-11	S
6	2	21	198	4	2022-10-11	S
6	2	21	256	4	2022-10-11	S
6	2	21	270	4	2022-10-11	S
6	2	21	213	4	2022-10-11	S
6	2	21	222	4	2022-10-11	S
6	2	42	287	4	2022-10-11	S
6	2	21	237	4	2022-10-11	S
6	2	40	278	4	2022-10-11	S
6	2	40	280	4	2022-10-11	S
6	2	38	254	4	2022-10-11	S
6	2	37	228	4	2022-10-11	S
6	2	38	257	4	2022-10-11	S
6	2	38	253	4	2022-10-11	S
6	2	38	250	4	2022-10-11	S
6	2	6	227	4	2022-10-11	S
6	2	37	238	4	2022-10-11	S
6	2	37	236	4	2022-10-11	S
6	2	37	232	4	2022-10-11	S
6	2	44	313	4	2022-10-11	S
6	2	44	312	4	2022-10-11	S
6	2	44	301	4	2022-10-11	S
6	2	43	295	4	2022-10-11	S
6	2	43	298	4	2022-10-11	S
6	2	30	171	4	2022-10-11	S
6	2	35	225	4	2022-10-11	S
6	2	30	172	4	2022-10-11	S
6	2	35	229	4	2022-10-11	S
6	2	38	255	4	2022-10-11	S
6	2	29	282	4	2022-10-11	S
6	2	31	174	4	2022-10-11	S
6	2	29	170	4	2022-10-11	S
6	2	37	234	4	2022-10-11	S
6	2	43	292	4	2022-10-11	S
6	2	16	245	4	2022-10-11	S
6	2	41	285	4	2022-10-11	S
6	2	41	290	4	2022-10-11	S
6	2	21	220	4	2022-10-11	S
6	2	21	233	4	2022-10-11	S
6	2	21	252	4	2022-10-11	S
6	2	38	251	4	2022-10-11	S
6	2	38	247	4	2022-10-11	S
6	2	38	248	4	2022-10-11	S
6	2	37	230	4	2022-10-11	S
6	2	37	231	4	2022-10-11	S
6	2	37	235	4	2022-10-11	S
6	2	37	241	4	2022-10-11	S
6	2	28	267	4	2022-10-11	S
6	2	43	294	4	2022-10-11	S
6	2	40	277	4	2022-10-11	S
6	2	35	219	4	2022-10-11	S
6	2	23	216	4	2022-10-11	S
6	2	29	169	4	2022-10-11	S
6	2	29	283	4	2022-10-11	S
6	2	29	284	4	2022-10-11	S
6	2	31	175	4	2022-10-11	S
6	2	92	555	4	2022-10-11	S
6	2	91	548	4	2022-10-11	S
6	2	39	262	4	2022-10-11	S
6	2	28	264	4	2022-10-11	S
6	2	28	166	4	2022-10-11	S
6	2	21	240	4	2022-10-11	S
6	2	21	214	4	2022-10-11	S
6	2	35	218	4	2022-10-11	S
6	2	60	444	4	2022-10-11	S
6	2	58	433	4	2022-10-11	S
6	2	30	340	4	2022-10-11	S
6	2	30	338	4	2022-10-11	S
6	2	49	343	4	2022-10-11	S
6	2	49	344	4	2022-10-11	S
6	2	49	331	4	2022-10-11	S
6	2	49	335	4	2022-10-11	S
6	2	6	400	4	2022-10-11	S
6	2	6	414	4	2022-10-11	S
6	2	6	417	4	2022-10-11	S
6	2	30	337	4	2022-10-11	S
6	2	23	457	4	2022-10-11	S
6	2	29	435	4	2022-10-11	S
6	2	60	443	4	2022-10-11	S
6	2	60	451	4	2022-10-11	S
6	2	49	341	4	2022-10-11	S
6	2	16	80	4	2022-10-11	S
6	2	16	81	4	2022-10-11	S
6	2	16	82	4	2022-10-11	S
6	2	16	83	4	2022-10-11	S
6	2	16	84	4	2022-10-11	S
6	2	16	85	4	2022-10-11	S
6	2	16	86	4	2022-10-11	S
6	2	16	113	4	2022-10-11	S
6	2	16	131	4	2022-10-11	S
6	2	16	132	4	2022-10-11	S
6	2	16	304	4	2022-10-11	S
6	2	41	302	4	2022-10-11	S
6	2	41	303	4	2022-10-11	S
6	2	41	288	4	2022-10-11	S
6	2	41	289	4	2022-10-11	S
6	2	84	492	4	2022-10-11	S
6	2	21	479	4	2022-10-11	S
6	2	31	461	4	2022-10-11	S
6	2	21	456	4	2022-10-11	S
6	2	60	447	4	2022-10-11	S
6	2	21	369	4	2022-10-11	S
6	2	21	368	4	2022-10-11	S
6	2	21	366	4	2022-10-11	S
6	2	41	296	4	2022-10-11	S
6	2	21	120	4	2022-10-11	S
6	2	21	121	4	2022-10-11	S
6	2	21	135	4	2022-10-11	S
6	2	21	145	4	2022-10-11	S
6	2	21	173	4	2022-10-11	S
6	2	21	199	4	2022-10-11	S
6	2	21	201	4	2022-10-11	S
6	2	21	202	4	2022-10-11	S
6	2	21	221	4	2022-10-11	S
6	2	21	223	4	2022-10-11	S
6	2	21	239	4	2022-10-11	S
6	2	21	243	4	2022-10-11	S
6	2	21	244	4	2022-10-11	S
6	2	21	271	4	2022-10-11	S
6	2	44	310	4	2022-10-11	S
6	2	44	330	4	2022-10-11	S
6	2	28	167	4	2022-10-11	S
6	2	28	263	4	2022-10-11	S
6	2	28	265	4	2022-10-11	S
6	2	28	266	4	2022-10-11	S
6	2	28	269	4	2022-10-11	S
6	2	43	291	4	2022-10-11	S
6	2	43	293	4	2022-10-11	S
6	2	40	276	4	2022-10-11	S
6	2	40	279	4	2022-10-11	S
6	2	40	281	4	2022-10-11	S
6	2	30	336	4	2022-10-11	S
6	2	30	339	4	2022-10-11	S
6	2	30	342	4	2022-10-11	S
6	2	29	272	4	2022-10-11	S
6	2	29	273	4	2022-10-11	S
6	2	29	274	4	2022-10-11	S
6	2	60	408	4	2022-10-11	S
6	2	60	409	4	2022-10-11	S
6	2	60	452	4	2022-10-11	S
6	2	58	399	4	2022-10-11	S
6	2	91	549	4	2022-10-11	S
6	2	91	550	4	2022-10-11	S
6	2	91	551	4	2022-10-11	S
6	2	91	552	4	2022-10-11	S
6	2	97	562	4	2022-10-11	S
6	2	97	564	4	2022-10-11	S
6	2	95	587	4	2022-10-11	S
6	2	97	566	4	2022-10-11	S
6	2	95	586	4	2022-10-11	S
6	2	98	567	4	2022-10-11	S
6	2	95	583	4	2022-10-11	S
6	2	98	568	4	2022-10-11	S
6	2	95	582	4	2022-10-11	S
6	2	95	580	4	2022-10-11	S
6	2	95	581	4	2022-10-11	S
6	2	95	579	4	2022-10-11	S
6	2	98	571	4	2022-10-11	S
6	2	95	578	4	2022-10-11	S
6	2	95	577	4	2022-10-11	S
6	2	95	576	4	2022-10-11	S
6	2	98	573	4	2022-10-11	S
6	2	98	575	4	2022-10-11	S
6	2	91	556	4	2022-10-11	S
6	2	97	559	4	2022-10-11	S
6	2	96	557	4	2022-10-11	S
6	2	97	560	4	2022-10-11	S
6	2	90	545	4	2022-10-11	S
6	2	90	544	4	2022-10-11	S
6	2	89	538	4	2022-10-11	S
6	2	31	537	4	2022-10-11	S
6	2	29	534	4	2022-10-11	S
6	2	29	535	4	2022-10-11	S
6	2	16	531	4	2022-10-11	S
6	2	16	532	4	2022-10-11	S
6	2	6	529	4	2022-10-11	S
6	2	88	528	4	2022-10-11	S
6	2	23	527	4	2022-10-11	S
6	2	35	526	4	2022-10-11	S
6	2	21	524	4	2022-10-11	S
6	2	44	523	4	2022-10-11	S
6	2	8	521	4	2022-10-11	S
6	2	21	517	4	2022-10-11	S
6	2	35	516	4	2022-10-11	S
6	2	35	514	4	2022-10-11	S
6	2	21	513	4	2022-10-11	S
6	2	86	519	4	2022-10-11	S
6	2	8	520	4	2022-10-11	S
6	2	31	509	4	2022-10-11	S
6	2	21	510	4	2022-10-11	S
6	2	29	508	4	2022-10-11	S
6	2	27	507	4	2022-10-11	S
6	2	16	506	4	2022-10-11	S
6	2	35	406	4	2022-10-11	S
6	2	42	504	4	2022-10-11	S
6	2	42	503	4	2022-10-11	S
6	2	42	501	4	2022-10-11	S
6	2	42	500	4	2022-10-11	S
6	2	29	499	4	2022-10-11	S
6	2	6	497	4	2022-10-11	S
6	2	6	496	4	2022-10-11	S
6	2	57	495	4	2022-10-11	S
6	2	85	494	4	2022-10-11	S
6	2	84	491	4	2022-10-11	S
6	2	83	490	4	2022-10-11	S
6	2	83	489	4	2022-10-11	S
6	2	82	481	4	2022-10-11	S
6	2	28	505	4	2022-10-11	S
6	2	21	611	4	2022-10-11	S
6	2	21	610	4	2022-10-11	S
6	2	21	607	4	2022-10-11	S
6	2	21	606	4	2022-10-11	S
6	2	99	605	4	2022-10-11	S
6	2	99	604	4	2022-10-11	S
6	2	99	603	4	2022-10-11	S
6	2	99	602	4	2022-10-11	S
6	2	99	601	4	2022-10-11	S
6	2	99	600	4	2022-10-11	S
6	2	99	599	4	2022-10-11	S
6	2	95	596	4	2022-10-11	S
6	2	99	598	4	2022-10-11	S
6	2	99	597	4	2022-10-11	S
6	2	95	594	4	2022-10-11	S
6	2	95	593	4	2022-10-11	S
6	2	95	595	4	2022-10-11	S
6	2	95	591	4	2022-10-11	S
6	2	95	592	4	2022-10-11	S
6	2	95	590	4	2022-10-11	S
6	2	95	585	4	2022-10-11	S
6	2	95	589	4	2022-10-11	S
6	2	95	588	4	2022-10-11	S
6	2	97	563	4	2022-10-11	S
6	2	97	565	4	2022-10-11	S
6	2	95	584	4	2022-10-11	S
6	2	98	569	4	2022-10-11	S
6	2	98	570	4	2022-10-11	S
6	2	98	572	4	2022-10-11	S
6	2	98	574	4	2022-10-11	S
6	2	97	558	4	2022-10-11	S
6	2	97	561	4	2022-10-11	S
6	2	21	543	4	2022-10-11	S
6	2	21	542	4	2022-10-11	S
6	2	21	541	4	2022-10-11	S
6	2	31	536	4	2022-10-11	S
6	2	16	533	4	2022-10-11	S
6	2	16	530	4	2022-10-11	S
6	2	6	525	4	2022-10-11	S
6	2	87	522	4	2022-10-11	S
6	2	21	518	4	2022-10-11	S
6	2	35	515	4	2022-10-11	S
6	2	21	512	4	2022-10-11	S
6	2	21	511	4	2022-10-11	S
6	2	42	502	4	2022-10-11	S
6	2	6	498	4	2022-10-11	S
6	2	85	493	4	2022-10-11	S
6	2	23	488	4	2022-10-11	S
6	2	21	480	4	2022-10-11	S
6	2	23	460	4	2022-10-11	S
6	2	21	370	4	2022-10-11	S
6	2	21	365	4	2022-10-11	S
6	2	21	242	4	2022-10-11	S
6	2	21	224	4	2022-10-11	S
6	2	21	608	4	2022-10-11	S
6	2	35	612	4	2022-10-11	S
6	2	21	609	4	2022-10-11	S
7	2	91	553	5	2022-10-11	S
7	2	91	546	5	2022-10-11	S
7	2	6	71	5	2022-10-11	S
7	2	6	72	5	2022-10-11	S
7	2	6	73	5	2022-10-11	S
7	2	6	74	5	2022-10-11	S
7	2	6	75	5	2022-10-11	S
7	2	6	76	5	2022-10-11	S
7	2	6	77	5	2022-10-11	S
7	2	23	149	5	2022-10-11	S
7	2	23	151	5	2022-10-11	S
7	2	23	152	5	2022-10-11	S
7	2	23	155	5	2022-10-11	S
7	2	23	156	5	2022-10-11	S
7	2	21	158	5	2022-10-11	S
7	2	21	154	5	2022-10-11	S
7	2	21	153	5	2022-10-11	S
7	2	91	554	5	2022-10-11	S
7	2	91	547	5	2022-10-11	S
7	2	38	249	5	2022-10-11	S
7	2	28	268	5	2022-10-11	S
7	2	44	300	5	2022-10-11	S
7	2	23	165	5	2022-10-11	S
7	2	38	246	5	2022-10-11	S
7	2	44	311	5	2022-10-11	S
7	2	35	217	5	2022-10-11	S
7	2	29	275	5	2022-10-11	S
7	2	41	297	5	2022-10-11	S
7	2	41	286	5	2022-10-11	S
7	2	41	299	5	2022-10-11	S
7	2	21	198	5	2022-10-11	S
7	2	21	256	5	2022-10-11	S
7	2	21	270	5	2022-10-11	S
7	2	21	213	5	2022-10-11	S
7	2	21	222	5	2022-10-11	S
7	2	42	287	5	2022-10-11	S
7	2	21	237	5	2022-10-11	S
7	2	40	278	5	2022-10-11	S
7	2	40	280	5	2022-10-11	S
7	2	38	254	5	2022-10-11	S
7	2	37	228	5	2022-10-11	S
7	2	38	257	5	2022-10-11	S
7	2	38	253	5	2022-10-11	S
7	2	38	250	5	2022-10-11	S
7	2	6	227	5	2022-10-11	S
7	2	37	238	5	2022-10-11	S
7	2	37	236	5	2022-10-11	S
7	2	37	232	5	2022-10-11	S
7	2	44	313	5	2022-10-11	S
7	2	44	312	5	2022-10-11	S
7	2	44	301	5	2022-10-11	S
7	2	43	295	5	2022-10-11	S
7	2	43	298	5	2022-10-11	S
7	2	30	171	5	2022-10-11	S
7	2	35	225	5	2022-10-11	S
7	2	30	172	5	2022-10-11	S
7	2	35	229	5	2022-10-11	S
7	2	38	255	5	2022-10-11	S
7	2	29	282	5	2022-10-11	S
7	2	31	174	5	2022-10-11	S
7	2	29	170	5	2022-10-11	S
7	2	37	234	5	2022-10-11	S
7	2	43	292	5	2022-10-11	S
7	2	16	245	5	2022-10-11	S
7	2	41	285	5	2022-10-11	S
7	2	41	290	5	2022-10-11	S
7	2	21	220	5	2022-10-11	S
7	2	21	233	5	2022-10-11	S
7	2	21	252	5	2022-10-11	S
7	2	38	251	5	2022-10-11	S
7	2	38	247	5	2022-10-11	S
7	2	38	248	5	2022-10-11	S
7	2	37	230	5	2022-10-11	S
7	2	37	231	5	2022-10-11	S
7	2	37	235	5	2022-10-11	S
7	2	37	241	5	2022-10-11	S
7	2	28	267	5	2022-10-11	S
7	2	43	294	5	2022-10-11	S
7	2	40	277	5	2022-10-11	S
7	2	35	219	5	2022-10-11	S
7	2	23	216	5	2022-10-11	S
7	2	29	169	5	2022-10-11	S
7	2	29	283	5	2022-10-11	S
7	2	29	284	5	2022-10-11	S
7	2	31	175	5	2022-10-11	S
7	2	92	555	5	2022-10-11	S
7	2	91	548	5	2022-10-11	S
7	2	39	262	5	2022-10-11	S
7	2	28	264	5	2022-10-11	S
7	2	28	166	5	2022-10-11	S
7	2	21	240	5	2022-10-11	S
7	2	21	214	5	2022-10-11	S
7	2	35	218	5	2022-10-11	S
7	2	60	444	5	2022-10-11	S
7	2	58	433	5	2022-10-11	S
7	2	30	340	5	2022-10-11	S
7	2	30	338	5	2022-10-11	S
7	2	49	343	5	2022-10-11	S
7	2	49	344	5	2022-10-11	S
7	2	49	331	5	2022-10-11	S
7	2	49	335	5	2022-10-11	S
7	2	6	400	5	2022-10-11	S
7	2	6	414	5	2022-10-11	S
7	2	6	417	5	2022-10-11	S
7	2	30	337	5	2022-10-11	S
7	2	23	457	5	2022-10-11	S
7	2	29	435	5	2022-10-11	S
7	2	60	443	5	2022-10-11	S
7	2	60	451	5	2022-10-11	S
7	2	49	341	5	2022-10-11	S
7	2	16	80	5	2022-10-11	S
7	2	16	81	5	2022-10-11	S
7	2	16	82	5	2022-10-11	S
7	2	16	83	5	2022-10-11	S
7	2	16	84	5	2022-10-11	S
7	2	16	85	5	2022-10-11	S
7	2	16	86	5	2022-10-11	S
7	2	16	113	5	2022-10-11	S
7	2	16	131	5	2022-10-11	S
7	2	16	132	5	2022-10-11	S
7	2	16	304	5	2022-10-11	S
7	2	41	302	5	2022-10-11	S
7	2	41	303	5	2022-10-11	S
7	2	41	288	5	2022-10-11	S
7	2	41	289	5	2022-10-11	S
7	2	84	492	5	2022-10-11	S
7	2	21	479	5	2022-10-11	S
7	2	31	461	5	2022-10-11	S
7	2	21	456	5	2022-10-11	S
7	2	60	447	5	2022-10-11	S
7	2	21	369	5	2022-10-11	S
7	2	21	368	5	2022-10-11	S
7	2	21	366	5	2022-10-11	S
7	2	41	296	5	2022-10-11	S
7	2	21	120	5	2022-10-11	S
7	2	21	121	5	2022-10-11	S
7	2	21	135	5	2022-10-11	S
7	2	21	145	5	2022-10-11	S
7	2	21	173	5	2022-10-11	S
7	2	21	199	5	2022-10-11	S
7	2	21	201	5	2022-10-11	S
7	2	21	202	5	2022-10-11	S
7	2	21	221	5	2022-10-11	S
7	2	21	223	5	2022-10-11	S
7	2	21	239	5	2022-10-11	S
7	2	21	243	5	2022-10-11	S
7	2	21	244	5	2022-10-11	S
7	2	21	271	5	2022-10-11	S
7	2	44	310	5	2022-10-11	S
7	2	44	330	5	2022-10-11	S
7	2	28	167	5	2022-10-11	S
7	2	28	263	5	2022-10-11	S
7	2	28	265	5	2022-10-11	S
7	2	28	266	5	2022-10-11	S
7	2	28	269	5	2022-10-11	S
7	2	43	291	5	2022-10-11	S
7	2	43	293	5	2022-10-11	S
7	2	40	276	5	2022-10-11	S
7	2	40	279	5	2022-10-11	S
7	2	40	281	5	2022-10-11	S
7	2	30	336	5	2022-10-11	S
7	2	30	339	5	2022-10-11	S
7	2	30	342	5	2022-10-11	S
7	2	29	272	5	2022-10-11	S
7	2	29	273	5	2022-10-11	S
7	2	29	274	5	2022-10-11	S
7	2	60	408	5	2022-10-11	S
7	2	60	409	5	2022-10-11	S
7	2	60	452	5	2022-10-11	S
7	2	58	399	5	2022-10-11	S
7	2	91	549	5	2022-10-11	S
7	2	91	550	5	2022-10-11	S
7	2	91	551	5	2022-10-11	S
7	2	91	552	5	2022-10-11	S
7	2	97	562	5	2022-10-11	S
7	2	97	564	5	2022-10-11	S
7	2	95	587	5	2022-10-11	S
7	2	97	566	5	2022-10-11	S
7	2	95	586	5	2022-10-11	S
7	2	98	567	5	2022-10-11	S
7	2	95	583	5	2022-10-11	S
7	2	98	568	5	2022-10-11	S
7	2	95	582	5	2022-10-11	S
7	2	95	580	5	2022-10-11	S
7	2	95	581	5	2022-10-11	S
7	2	95	579	5	2022-10-11	S
7	2	98	571	5	2022-10-11	S
7	2	95	578	5	2022-10-11	S
7	2	95	577	5	2022-10-11	S
7	2	95	576	5	2022-10-11	S
7	2	98	573	5	2022-10-11	S
7	2	98	575	5	2022-10-11	S
7	2	91	556	5	2022-10-11	S
7	2	97	559	5	2022-10-11	S
7	2	96	557	5	2022-10-11	S
7	2	97	560	5	2022-10-11	S
7	2	90	545	5	2022-10-11	S
7	2	90	544	5	2022-10-11	S
7	2	89	538	5	2022-10-11	S
7	2	31	537	5	2022-10-11	S
7	2	29	534	5	2022-10-11	S
7	2	29	535	5	2022-10-11	S
7	2	16	531	5	2022-10-11	S
7	2	16	532	5	2022-10-11	S
7	2	6	529	5	2022-10-11	S
7	2	88	528	5	2022-10-11	S
7	2	23	527	5	2022-10-11	S
7	2	35	526	5	2022-10-11	S
7	2	21	524	5	2022-10-11	S
7	2	44	523	5	2022-10-11	S
7	2	8	521	5	2022-10-11	S
7	2	21	517	5	2022-10-11	S
7	2	35	516	5	2022-10-11	S
7	2	35	514	5	2022-10-11	S
7	2	21	513	5	2022-10-11	S
7	2	86	519	5	2022-10-11	S
7	2	8	520	5	2022-10-11	S
7	2	31	509	5	2022-10-11	S
7	2	21	510	5	2022-10-11	S
7	2	29	508	5	2022-10-11	S
7	2	27	507	5	2022-10-11	S
7	2	16	506	5	2022-10-11	S
7	2	35	406	5	2022-10-11	S
7	2	42	504	5	2022-10-11	S
7	2	42	503	5	2022-10-11	S
7	2	42	501	5	2022-10-11	S
7	2	42	500	5	2022-10-11	S
7	2	29	499	5	2022-10-11	S
7	2	6	497	5	2022-10-11	S
7	2	6	496	5	2022-10-11	S
7	2	57	495	5	2022-10-11	S
7	2	85	494	5	2022-10-11	S
7	2	84	491	5	2022-10-11	S
7	2	83	490	5	2022-10-11	S
7	2	83	489	5	2022-10-11	S
7	2	82	481	5	2022-10-11	S
7	2	28	505	5	2022-10-11	S
7	2	21	611	5	2022-10-11	S
7	2	21	610	5	2022-10-11	S
7	2	21	607	5	2022-10-11	S
7	2	21	606	5	2022-10-11	S
7	2	99	605	5	2022-10-11	S
7	2	99	604	5	2022-10-11	S
7	2	99	603	5	2022-10-11	S
7	2	99	602	5	2022-10-11	S
7	2	99	601	5	2022-10-11	S
7	2	99	600	5	2022-10-11	S
7	2	99	599	5	2022-10-11	S
7	2	95	596	5	2022-10-11	S
7	2	99	598	5	2022-10-11	S
7	2	99	597	5	2022-10-11	S
7	2	95	594	5	2022-10-11	S
7	2	95	593	5	2022-10-11	S
7	2	95	595	5	2022-10-11	S
7	2	95	591	5	2022-10-11	S
7	2	95	592	5	2022-10-11	S
7	2	95	590	5	2022-10-11	S
7	2	95	585	5	2022-10-11	S
7	2	95	589	5	2022-10-11	S
7	2	95	588	5	2022-10-11	S
7	2	97	563	5	2022-10-11	S
7	2	97	565	5	2022-10-11	S
7	2	95	584	5	2022-10-11	S
7	2	98	569	5	2022-10-11	S
7	2	98	570	5	2022-10-11	S
7	2	98	572	5	2022-10-11	S
7	2	98	574	5	2022-10-11	S
7	2	97	558	5	2022-10-11	S
7	2	97	561	5	2022-10-11	S
7	2	21	543	5	2022-10-11	S
7	2	21	542	5	2022-10-11	S
7	2	21	541	5	2022-10-11	S
7	2	31	536	5	2022-10-11	S
7	2	16	533	5	2022-10-11	S
7	2	16	530	5	2022-10-11	S
7	2	6	525	5	2022-10-11	S
7	2	87	522	5	2022-10-11	S
7	2	21	518	5	2022-10-11	S
7	2	35	515	5	2022-10-11	S
7	2	21	512	5	2022-10-11	S
7	2	21	511	5	2022-10-11	S
7	2	42	502	5	2022-10-11	S
7	2	6	498	5	2022-10-11	S
7	2	85	493	5	2022-10-11	S
7	2	23	488	5	2022-10-11	S
7	2	21	480	5	2022-10-11	S
7	2	23	460	5	2022-10-11	S
7	2	21	370	5	2022-10-11	S
7	2	21	365	5	2022-10-11	S
7	2	21	242	5	2022-10-11	S
7	2	21	224	5	2022-10-11	S
7	2	21	608	5	2022-10-11	S
7	2	35	612	5	2022-10-11	S
7	2	21	609	5	2022-10-11	S
3	1	91	553	1	2022-10-11	S
3	1	91	546	1	2022-10-11	S
3	1	6	71	1	2022-10-11	S
3	1	6	72	1	2022-10-11	S
3	1	6	73	1	2022-10-11	S
3	1	6	74	1	2022-10-11	S
3	1	6	75	1	2022-10-11	S
3	1	6	76	1	2022-10-11	S
3	1	6	77	1	2022-10-11	S
3	1	23	149	1	2022-10-11	S
3	1	23	151	1	2022-10-11	S
3	1	23	152	1	2022-10-11	S
3	1	23	155	1	2022-10-11	S
3	1	23	156	1	2022-10-11	S
3	1	21	158	1	2022-10-11	S
3	1	21	154	1	2022-10-11	S
3	1	21	153	1	2022-10-11	S
3	1	91	554	1	2022-10-11	S
3	1	91	547	1	2022-10-11	S
3	1	38	249	1	2022-10-11	S
3	1	28	268	1	2022-10-11	S
3	1	44	300	1	2022-10-11	S
3	1	23	165	1	2022-10-11	S
3	1	38	246	1	2022-10-11	S
3	1	44	311	1	2022-10-11	S
3	1	35	217	1	2022-10-11	S
3	1	29	275	1	2022-10-11	S
3	1	41	297	1	2022-10-11	S
3	1	41	286	1	2022-10-11	S
3	1	41	299	1	2022-10-11	S
3	1	21	198	1	2022-10-11	S
3	1	21	256	1	2022-10-11	S
3	1	21	270	1	2022-10-11	S
3	1	21	213	1	2022-10-11	S
3	1	21	222	1	2022-10-11	S
3	1	42	287	1	2022-10-11	S
3	1	21	237	1	2022-10-11	S
3	1	40	278	1	2022-10-11	S
3	1	40	280	1	2022-10-11	S
3	1	38	254	1	2022-10-11	S
3	1	37	228	1	2022-10-11	S
3	1	38	257	1	2022-10-11	S
3	1	38	253	1	2022-10-11	S
3	1	38	250	1	2022-10-11	S
3	1	6	227	1	2022-10-11	S
3	1	37	238	1	2022-10-11	S
3	1	37	236	1	2022-10-11	S
3	1	37	232	1	2022-10-11	S
3	1	44	313	1	2022-10-11	S
3	1	44	312	1	2022-10-11	S
3	1	44	301	1	2022-10-11	S
3	1	43	295	1	2022-10-11	S
3	1	43	298	1	2022-10-11	S
3	1	30	171	1	2022-10-11	S
3	1	35	225	1	2022-10-11	S
3	1	30	172	1	2022-10-11	S
3	1	35	229	1	2022-10-11	S
3	1	38	255	1	2022-10-11	S
3	1	29	282	1	2022-10-11	S
3	1	31	174	1	2022-10-11	S
3	1	29	170	1	2022-10-11	S
3	1	37	234	1	2022-10-11	S
3	1	43	292	1	2022-10-11	S
3	1	16	245	1	2022-10-11	S
3	1	41	285	1	2022-10-11	S
3	1	41	290	1	2022-10-11	S
3	1	21	220	1	2022-10-11	S
3	1	21	233	1	2022-10-11	S
3	1	21	252	1	2022-10-11	S
3	1	38	251	1	2022-10-11	S
3	1	38	247	1	2022-10-11	S
3	1	38	248	1	2022-10-11	S
3	1	37	230	1	2022-10-11	S
3	1	37	231	1	2022-10-11	S
3	1	37	235	1	2022-10-11	S
3	1	37	241	1	2022-10-11	S
3	1	28	267	1	2022-10-11	S
3	1	43	294	1	2022-10-11	S
3	1	40	277	1	2022-10-11	S
3	1	35	219	1	2022-10-11	S
3	1	23	216	1	2022-10-11	S
3	1	29	169	1	2022-10-11	S
3	1	29	283	1	2022-10-11	S
3	1	29	284	1	2022-10-11	S
3	1	31	175	1	2022-10-11	S
3	1	92	555	1	2022-10-11	S
3	1	91	548	1	2022-10-11	S
3	1	39	262	1	2022-10-11	S
3	1	28	264	1	2022-10-11	S
3	1	28	166	1	2022-10-11	S
3	1	21	240	1	2022-10-11	S
3	1	21	214	1	2022-10-11	S
3	1	35	218	1	2022-10-11	S
3	1	60	444	1	2022-10-11	S
3	1	58	433	1	2022-10-11	S
3	1	30	340	1	2022-10-11	S
3	1	30	338	1	2022-10-11	S
3	1	49	343	1	2022-10-11	S
3	1	49	344	1	2022-10-11	S
3	1	49	331	1	2022-10-11	S
3	1	49	335	1	2022-10-11	S
3	1	6	400	1	2022-10-11	S
3	1	6	414	1	2022-10-11	S
3	1	6	417	1	2022-10-11	S
3	1	30	337	1	2022-10-11	S
3	1	23	457	1	2022-10-11	S
3	1	29	435	1	2022-10-11	S
3	1	60	443	1	2022-10-11	S
3	1	60	451	1	2022-10-11	S
3	1	49	341	1	2022-10-11	S
3	1	16	80	1	2022-10-11	S
3	1	16	81	1	2022-10-11	S
3	1	16	82	1	2022-10-11	S
3	1	16	83	1	2022-10-11	S
3	1	16	84	1	2022-10-11	S
3	1	16	85	1	2022-10-11	S
3	1	16	86	1	2022-10-11	S
3	1	16	113	1	2022-10-11	S
3	1	16	131	1	2022-10-11	S
3	1	16	132	1	2022-10-11	S
3	1	16	304	1	2022-10-11	S
3	1	41	302	1	2022-10-11	S
3	1	41	303	1	2022-10-11	S
3	1	41	288	1	2022-10-11	S
3	1	41	289	1	2022-10-11	S
3	1	84	492	1	2022-10-11	S
3	1	21	479	1	2022-10-11	S
3	1	31	461	1	2022-10-11	S
3	1	21	456	1	2022-10-11	S
3	1	60	447	1	2022-10-11	S
3	1	21	369	1	2022-10-11	S
3	1	21	368	1	2022-10-11	S
3	1	21	366	1	2022-10-11	S
3	1	41	296	1	2022-10-11	S
3	1	21	120	1	2022-10-11	S
3	1	21	121	1	2022-10-11	S
3	1	21	135	1	2022-10-11	S
3	1	21	145	1	2022-10-11	S
3	1	21	173	1	2022-10-11	S
3	1	21	199	1	2022-10-11	S
3	1	21	201	1	2022-10-11	S
3	1	21	202	1	2022-10-11	S
3	1	21	221	1	2022-10-11	S
3	1	21	223	1	2022-10-11	S
3	1	21	239	1	2022-10-11	S
3	1	21	243	1	2022-10-11	S
3	1	21	244	1	2022-10-11	S
3	1	21	271	1	2022-10-11	S
3	1	44	310	1	2022-10-11	S
3	1	44	330	1	2022-10-11	S
3	1	28	167	1	2022-10-11	S
3	1	28	263	1	2022-10-11	S
3	1	28	265	1	2022-10-11	S
3	1	28	266	1	2022-10-11	S
3	1	28	269	1	2022-10-11	S
3	1	43	291	1	2022-10-11	S
3	1	43	293	1	2022-10-11	S
3	1	40	276	1	2022-10-11	S
3	1	40	279	1	2022-10-11	S
3	1	40	281	1	2022-10-11	S
3	1	30	336	1	2022-10-11	S
3	1	30	339	1	2022-10-11	S
3	1	30	342	1	2022-10-11	S
3	1	29	272	1	2022-10-11	S
3	1	29	273	1	2022-10-11	S
3	1	29	274	1	2022-10-11	S
3	1	60	408	1	2022-10-11	S
3	1	60	409	1	2022-10-11	S
3	1	60	452	1	2022-10-11	S
3	1	58	399	1	2022-10-11	S
3	1	91	549	1	2022-10-11	S
3	1	91	550	1	2022-10-11	S
3	1	91	551	1	2022-10-11	S
3	1	91	552	1	2022-10-11	S
3	1	97	562	1	2022-10-11	S
3	1	97	564	1	2022-10-11	S
3	1	95	587	1	2022-10-11	S
3	1	97	566	1	2022-10-11	S
3	1	95	586	1	2022-10-11	S
3	1	98	567	1	2022-10-11	S
3	1	95	583	1	2022-10-11	S
3	1	98	568	1	2022-10-11	S
3	1	95	582	1	2022-10-11	S
3	1	95	580	1	2022-10-11	S
3	1	95	581	1	2022-10-11	S
3	1	95	579	1	2022-10-11	S
3	1	98	571	1	2022-10-11	S
3	1	95	578	1	2022-10-11	S
3	1	95	577	1	2022-10-11	S
3	1	95	576	1	2022-10-11	S
3	1	98	573	1	2022-10-11	S
3	1	98	575	1	2022-10-11	S
3	1	91	556	1	2022-10-11	S
3	1	97	559	1	2022-10-11	S
3	1	96	557	1	2022-10-11	S
3	1	97	560	1	2022-10-11	S
3	1	90	545	1	2022-10-11	S
3	1	90	544	1	2022-10-11	S
3	1	89	538	1	2022-10-11	S
3	1	31	537	1	2022-10-11	S
3	1	29	534	1	2022-10-11	S
3	1	29	535	1	2022-10-11	S
3	1	16	531	1	2022-10-11	S
3	1	16	532	1	2022-10-11	S
3	1	6	529	1	2022-10-11	S
3	1	88	528	1	2022-10-11	S
3	1	23	527	1	2022-10-11	S
3	1	35	526	1	2022-10-11	S
3	1	21	524	1	2022-10-11	S
3	1	44	523	1	2022-10-11	S
3	1	8	521	1	2022-10-11	S
3	1	21	517	1	2022-10-11	S
3	1	35	516	1	2022-10-11	S
3	1	35	514	1	2022-10-11	S
3	1	21	513	1	2022-10-11	S
3	1	86	519	1	2022-10-11	S
3	1	8	520	1	2022-10-11	S
3	1	31	509	1	2022-10-11	S
3	1	21	510	1	2022-10-11	S
3	1	29	508	1	2022-10-11	S
3	1	27	507	1	2022-10-11	S
3	1	16	506	1	2022-10-11	S
3	1	35	406	1	2022-10-11	S
3	1	42	504	1	2022-10-11	S
3	1	42	503	1	2022-10-11	S
3	1	42	501	1	2022-10-11	S
3	1	42	500	1	2022-10-11	S
3	1	29	499	1	2022-10-11	S
3	1	6	497	1	2022-10-11	S
3	1	6	496	1	2022-10-11	S
3	1	57	495	1	2022-10-11	S
3	1	85	494	1	2022-10-11	S
3	1	84	491	1	2022-10-11	S
3	1	83	490	1	2022-10-11	S
3	1	83	489	1	2022-10-11	S
3	1	82	481	1	2022-10-11	S
3	1	28	505	1	2022-10-11	S
3	1	21	611	1	2022-10-11	S
3	1	21	610	1	2022-10-11	S
3	1	21	607	1	2022-10-11	S
3	1	21	606	1	2022-10-11	S
3	1	99	605	1	2022-10-11	S
3	1	99	604	1	2022-10-11	S
3	1	99	603	1	2022-10-11	S
3	1	99	602	1	2022-10-11	S
3	1	99	601	1	2022-10-11	S
3	1	99	600	1	2022-10-11	S
3	1	99	599	1	2022-10-11	S
3	1	95	596	1	2022-10-11	S
3	1	99	598	1	2022-10-11	S
3	1	99	597	1	2022-10-11	S
3	1	95	594	1	2022-10-11	S
3	1	95	593	1	2022-10-11	S
3	1	95	595	1	2022-10-11	S
3	1	95	591	1	2022-10-11	S
3	1	95	592	1	2022-10-11	S
3	1	95	590	1	2022-10-11	S
3	1	95	585	1	2022-10-11	S
3	1	95	589	1	2022-10-11	S
3	1	95	588	1	2022-10-11	S
3	1	97	563	1	2022-10-11	S
3	1	97	565	1	2022-10-11	S
3	1	95	584	1	2022-10-11	S
3	1	98	569	1	2022-10-11	S
3	1	98	570	1	2022-10-11	S
3	1	98	572	1	2022-10-11	S
3	1	98	574	1	2022-10-11	S
3	1	97	558	1	2022-10-11	S
3	1	97	561	1	2022-10-11	S
3	1	21	543	1	2022-10-11	S
3	1	21	542	1	2022-10-11	S
3	1	21	541	1	2022-10-11	S
3	1	31	536	1	2022-10-11	S
3	1	16	533	1	2022-10-11	S
3	1	16	530	1	2022-10-11	S
3	1	6	525	1	2022-10-11	S
3	1	87	522	1	2022-10-11	S
3	1	21	518	1	2022-10-11	S
3	1	35	515	1	2022-10-11	S
3	1	21	512	1	2022-10-11	S
3	1	21	511	1	2022-10-11	S
3	1	42	502	1	2022-10-11	S
3	1	6	498	1	2022-10-11	S
3	1	85	493	1	2022-10-11	S
3	1	23	488	1	2022-10-11	S
3	1	21	480	1	2022-10-11	S
3	1	23	460	1	2022-10-11	S
3	1	21	370	1	2022-10-11	S
3	1	21	365	1	2022-10-11	S
3	1	21	242	1	2022-10-11	S
3	1	21	224	1	2022-10-11	S
3	1	21	608	1	2022-10-11	S
3	1	35	612	1	2022-10-11	S
3	1	21	609	1	2022-10-11	S
8	1	91	553	1	2022-10-13	S
8	1	91	546	1	2022-10-13	S
8	1	6	71	1	2022-10-13	S
8	1	6	72	1	2022-10-13	S
8	1	6	73	1	2022-10-13	S
8	1	6	74	1	2022-10-13	S
8	1	6	75	1	2022-10-13	S
8	1	6	76	1	2022-10-13	S
8	1	6	77	1	2022-10-13	S
8	1	23	149	1	2022-10-13	S
8	1	23	151	1	2022-10-13	S
8	1	23	152	1	2022-10-13	S
8	1	23	155	1	2022-10-13	S
8	1	23	156	1	2022-10-13	S
8	1	21	158	1	2022-10-13	S
8	1	21	154	1	2022-10-13	S
8	1	21	153	1	2022-10-13	S
8	1	91	554	1	2022-10-13	S
8	1	91	547	1	2022-10-13	S
8	1	38	249	1	2022-10-13	S
8	1	28	268	1	2022-10-13	S
8	1	44	300	1	2022-10-13	S
8	1	23	165	1	2022-10-13	S
8	1	38	246	1	2022-10-13	S
8	1	44	311	1	2022-10-13	S
8	1	35	217	1	2022-10-13	S
8	1	29	275	1	2022-10-13	S
8	1	41	297	1	2022-10-13	S
8	1	41	286	1	2022-10-13	S
8	1	41	299	1	2022-10-13	S
8	1	21	198	1	2022-10-13	S
8	1	21	256	1	2022-10-13	S
8	1	21	270	1	2022-10-13	S
8	1	21	213	1	2022-10-13	S
8	1	21	222	1	2022-10-13	S
8	1	42	287	1	2022-10-13	S
8	1	21	237	1	2022-10-13	S
8	1	40	278	1	2022-10-13	S
8	1	40	280	1	2022-10-13	S
8	1	38	254	1	2022-10-13	S
8	1	37	228	1	2022-10-13	S
8	1	38	257	1	2022-10-13	S
8	1	38	253	1	2022-10-13	S
8	1	38	250	1	2022-10-13	S
8	1	6	227	1	2022-10-13	S
8	1	37	238	1	2022-10-13	S
8	1	37	236	1	2022-10-13	S
8	1	37	232	1	2022-10-13	S
8	1	44	313	1	2022-10-13	S
8	1	44	312	1	2022-10-13	S
8	1	44	301	1	2022-10-13	S
8	1	43	295	1	2022-10-13	S
8	1	43	298	1	2022-10-13	S
8	1	30	171	1	2022-10-13	S
8	1	35	225	1	2022-10-13	S
8	1	30	172	1	2022-10-13	S
8	1	35	229	1	2022-10-13	S
8	1	38	255	1	2022-10-13	S
8	1	29	282	1	2022-10-13	S
8	1	31	174	1	2022-10-13	S
8	1	29	170	1	2022-10-13	S
8	1	37	234	1	2022-10-13	S
8	1	43	292	1	2022-10-13	S
8	1	16	245	1	2022-10-13	S
8	1	41	285	1	2022-10-13	S
8	1	41	290	1	2022-10-13	S
8	1	21	220	1	2022-10-13	S
8	1	21	233	1	2022-10-13	S
8	1	21	252	1	2022-10-13	S
8	1	38	251	1	2022-10-13	S
8	1	38	247	1	2022-10-13	S
8	1	38	248	1	2022-10-13	S
8	1	37	230	1	2022-10-13	S
8	1	37	231	1	2022-10-13	S
8	1	37	235	1	2022-10-13	S
8	1	37	241	1	2022-10-13	S
8	1	28	267	1	2022-10-13	S
8	1	43	294	1	2022-10-13	S
8	1	40	277	1	2022-10-13	S
8	1	35	219	1	2022-10-13	S
8	1	23	216	1	2022-10-13	S
8	1	29	169	1	2022-10-13	S
8	1	29	283	1	2022-10-13	S
8	1	29	284	1	2022-10-13	S
8	1	31	175	1	2022-10-13	S
8	1	92	555	1	2022-10-13	S
8	1	91	548	1	2022-10-13	S
8	1	39	262	1	2022-10-13	S
8	1	28	264	1	2022-10-13	S
8	1	28	166	1	2022-10-13	S
8	1	21	240	1	2022-10-13	S
8	1	21	214	1	2022-10-13	S
8	1	35	218	1	2022-10-13	S
8	1	60	444	1	2022-10-13	S
8	1	58	433	1	2022-10-13	S
8	1	30	340	1	2022-10-13	S
8	1	30	338	1	2022-10-13	S
8	1	49	343	1	2022-10-13	S
8	1	49	344	1	2022-10-13	S
8	1	49	331	1	2022-10-13	S
8	1	49	335	1	2022-10-13	S
8	1	6	400	1	2022-10-13	S
8	1	6	414	1	2022-10-13	S
8	1	6	417	1	2022-10-13	S
8	1	30	337	1	2022-10-13	S
8	1	23	457	1	2022-10-13	S
8	1	29	435	1	2022-10-13	S
8	1	60	443	1	2022-10-13	S
8	1	60	451	1	2022-10-13	S
8	1	49	341	1	2022-10-13	S
8	1	16	80	1	2022-10-13	S
8	1	16	81	1	2022-10-13	S
8	1	16	82	1	2022-10-13	S
8	1	16	83	1	2022-10-13	S
8	1	16	84	1	2022-10-13	S
8	1	16	85	1	2022-10-13	S
8	1	16	86	1	2022-10-13	S
8	1	16	113	1	2022-10-13	S
8	1	16	131	1	2022-10-13	S
8	1	16	132	1	2022-10-13	S
8	1	16	304	1	2022-10-13	S
8	1	41	302	1	2022-10-13	S
8	1	41	303	1	2022-10-13	S
8	1	41	288	1	2022-10-13	S
8	1	41	289	1	2022-10-13	S
8	1	84	492	1	2022-10-13	S
8	1	21	479	1	2022-10-13	S
8	1	31	461	1	2022-10-13	S
8	1	21	456	1	2022-10-13	S
8	1	60	447	1	2022-10-13	S
8	1	21	369	1	2022-10-13	S
8	1	21	368	1	2022-10-13	S
8	1	21	366	1	2022-10-13	S
8	1	41	296	1	2022-10-13	S
8	1	21	120	1	2022-10-13	S
8	1	21	121	1	2022-10-13	S
8	1	21	135	1	2022-10-13	S
8	1	21	145	1	2022-10-13	S
8	1	21	173	1	2022-10-13	S
8	1	21	199	1	2022-10-13	S
8	1	21	201	1	2022-10-13	S
8	1	21	202	1	2022-10-13	S
8	1	21	221	1	2022-10-13	S
8	1	21	223	1	2022-10-13	S
8	1	21	239	1	2022-10-13	S
8	1	21	243	1	2022-10-13	S
8	1	21	244	1	2022-10-13	S
8	1	21	271	1	2022-10-13	S
8	1	44	310	1	2022-10-13	S
8	1	44	330	1	2022-10-13	S
8	1	28	167	1	2022-10-13	S
8	1	28	263	1	2022-10-13	S
8	1	28	265	1	2022-10-13	S
8	1	28	266	1	2022-10-13	S
8	1	28	269	1	2022-10-13	S
8	1	43	291	1	2022-10-13	S
8	1	43	293	1	2022-10-13	S
8	1	40	276	1	2022-10-13	S
8	1	40	279	1	2022-10-13	S
8	1	40	281	1	2022-10-13	S
8	1	30	336	1	2022-10-13	S
8	1	30	339	1	2022-10-13	S
8	1	30	342	1	2022-10-13	S
8	1	29	272	1	2022-10-13	S
8	1	29	273	1	2022-10-13	S
8	1	29	274	1	2022-10-13	S
8	1	60	408	1	2022-10-13	S
8	1	60	409	1	2022-10-13	S
8	1	60	452	1	2022-10-13	S
8	1	58	399	1	2022-10-13	S
8	1	91	549	1	2022-10-13	S
8	1	91	550	1	2022-10-13	S
8	1	91	551	1	2022-10-13	S
8	1	91	552	1	2022-10-13	S
8	1	97	562	1	2022-10-13	S
8	1	97	564	1	2022-10-13	S
8	1	95	587	1	2022-10-13	S
8	1	97	566	1	2022-10-13	S
8	1	95	586	1	2022-10-13	S
8	1	98	567	1	2022-10-13	S
8	1	95	583	1	2022-10-13	S
8	1	98	568	1	2022-10-13	S
8	1	95	582	1	2022-10-13	S
8	1	95	580	1	2022-10-13	S
8	1	95	581	1	2022-10-13	S
8	1	95	579	1	2022-10-13	S
8	1	98	571	1	2022-10-13	S
8	1	95	578	1	2022-10-13	S
8	1	95	577	1	2022-10-13	S
8	1	95	576	1	2022-10-13	S
8	1	98	573	1	2022-10-13	S
8	1	98	575	1	2022-10-13	S
8	1	91	556	1	2022-10-13	S
8	1	97	559	1	2022-10-13	S
8	1	96	557	1	2022-10-13	S
8	1	97	560	1	2022-10-13	S
8	1	90	545	1	2022-10-13	S
8	1	90	544	1	2022-10-13	S
8	1	89	538	1	2022-10-13	S
8	1	31	537	1	2022-10-13	S
8	1	29	534	1	2022-10-13	S
8	1	29	535	1	2022-10-13	S
8	1	16	531	1	2022-10-13	S
8	1	16	532	1	2022-10-13	S
8	1	6	529	1	2022-10-13	S
8	1	88	528	1	2022-10-13	S
8	1	23	527	1	2022-10-13	S
8	1	35	526	1	2022-10-13	S
8	1	21	524	1	2022-10-13	S
8	1	44	523	1	2022-10-13	S
8	1	8	521	1	2022-10-13	S
8	1	21	517	1	2022-10-13	S
8	1	35	516	1	2022-10-13	S
8	1	35	514	1	2022-10-13	S
8	1	21	513	1	2022-10-13	S
8	1	86	519	1	2022-10-13	S
8	1	8	520	1	2022-10-13	S
8	1	31	509	1	2022-10-13	S
8	1	21	510	1	2022-10-13	S
8	1	29	508	1	2022-10-13	S
8	1	27	507	1	2022-10-13	S
8	1	16	506	1	2022-10-13	S
8	1	35	406	1	2022-10-13	S
8	1	42	504	1	2022-10-13	S
8	1	42	503	1	2022-10-13	S
8	1	42	501	1	2022-10-13	S
8	1	42	500	1	2022-10-13	S
8	1	29	499	1	2022-10-13	S
8	1	6	497	1	2022-10-13	S
8	1	6	496	1	2022-10-13	S
8	1	57	495	1	2022-10-13	S
8	1	85	494	1	2022-10-13	S
8	1	84	491	1	2022-10-13	S
8	1	83	490	1	2022-10-13	S
8	1	83	489	1	2022-10-13	S
8	1	82	481	1	2022-10-13	S
8	1	28	505	1	2022-10-13	S
8	1	21	611	1	2022-10-13	S
8	1	21	610	1	2022-10-13	S
8	1	21	607	1	2022-10-13	S
8	1	21	606	1	2022-10-13	S
8	1	99	605	1	2022-10-13	S
8	1	99	604	1	2022-10-13	S
8	1	99	603	1	2022-10-13	S
8	1	99	602	1	2022-10-13	S
8	1	99	601	1	2022-10-13	S
8	1	99	600	1	2022-10-13	S
8	1	99	599	1	2022-10-13	S
8	1	95	596	1	2022-10-13	S
8	1	99	598	1	2022-10-13	S
8	1	99	597	1	2022-10-13	S
8	1	95	594	1	2022-10-13	S
8	1	95	593	1	2022-10-13	S
8	1	95	595	1	2022-10-13	S
8	1	95	591	1	2022-10-13	S
8	1	95	592	1	2022-10-13	S
8	1	95	590	1	2022-10-13	S
8	1	95	585	1	2022-10-13	S
8	1	95	589	1	2022-10-13	S
8	1	95	588	1	2022-10-13	S
8	1	97	563	1	2022-10-13	S
8	1	97	565	1	2022-10-13	S
8	1	95	584	1	2022-10-13	S
8	1	98	569	1	2022-10-13	S
8	1	98	570	1	2022-10-13	S
8	1	98	572	1	2022-10-13	S
8	1	98	574	1	2022-10-13	S
8	1	97	558	1	2022-10-13	S
8	1	97	561	1	2022-10-13	S
8	1	21	543	1	2022-10-13	S
8	1	21	542	1	2022-10-13	S
8	1	21	541	1	2022-10-13	S
8	1	31	536	1	2022-10-13	S
8	1	16	533	1	2022-10-13	S
8	1	16	530	1	2022-10-13	S
8	1	6	525	1	2022-10-13	S
8	1	87	522	1	2022-10-13	S
8	1	21	518	1	2022-10-13	S
8	1	35	515	1	2022-10-13	S
8	1	21	512	1	2022-10-13	S
8	1	21	511	1	2022-10-13	S
8	1	42	502	1	2022-10-13	S
8	1	6	498	1	2022-10-13	S
8	1	85	493	1	2022-10-13	S
8	1	23	488	1	2022-10-13	S
8	1	21	480	1	2022-10-13	S
8	1	23	460	1	2022-10-13	S
8	1	21	370	1	2022-10-13	S
8	1	21	365	1	2022-10-13	S
8	1	21	242	1	2022-10-13	S
8	1	21	224	1	2022-10-13	S
8	1	21	608	1	2022-10-13	S
8	1	35	612	1	2022-10-13	S
8	1	21	609	1	2022-10-13	S
9	1	91	553	1	2022-10-13	S
9	1	91	546	1	2022-10-13	S
9	1	6	71	1	2022-10-13	S
9	1	6	72	1	2022-10-13	S
9	1	6	73	1	2022-10-13	S
9	1	6	76	1	2022-10-13	S
9	1	23	149	1	2022-10-13	S
9	1	23	151	1	2022-10-13	S
9	1	23	152	1	2022-10-13	S
9	1	23	155	1	2022-10-13	S
9	1	23	156	1	2022-10-13	S
9	1	21	158	1	2022-10-13	S
9	1	21	154	1	2022-10-13	S
9	1	91	547	1	2022-10-13	S
9	1	38	249	1	2022-10-13	S
9	1	44	300	1	2022-10-13	S
9	1	23	165	1	2022-10-13	S
9	1	35	217	1	2022-10-13	S
9	1	41	286	1	2022-10-13	S
9	1	21	270	1	2022-10-13	S
9	1	42	287	1	2022-10-13	S
9	1	40	280	1	2022-10-13	S
9	1	37	228	1	2022-10-13	S
9	1	38	250	1	2022-10-13	S
9	1	6	227	1	2022-10-13	S
9	1	37	236	1	2022-10-13	S
9	1	44	313	1	2022-10-13	S
9	1	43	298	1	2022-10-13	S
9	1	30	171	1	2022-10-13	S
9	1	30	172	1	2022-10-13	S
9	1	29	282	1	2022-10-13	S
9	1	31	174	1	2022-10-13	S
9	1	29	170	1	2022-10-13	S
9	1	43	292	1	2022-10-13	S
9	1	41	285	1	2022-10-13	S
9	1	38	251	1	2022-10-13	S
9	1	37	230	1	2022-10-13	S
9	1	37	231	1	2022-10-13	S
9	1	37	235	1	2022-10-13	S
9	1	37	241	1	2022-10-13	S
9	1	23	216	1	2022-10-13	S
9	1	29	169	1	2022-10-13	S
9	1	29	283	1	2022-10-13	S
9	1	29	284	1	2022-10-13	S
9	1	31	175	1	2022-10-13	S
9	1	92	555	1	2022-10-13	S
9	1	91	548	1	2022-10-13	S
9	1	39	262	1	2022-10-13	S
9	1	28	264	1	2022-10-13	S
9	1	28	166	1	2022-10-13	S
9	1	21	240	1	2022-10-13	S
9	1	35	218	1	2022-10-13	S
9	1	60	444	1	2022-10-13	S
9	1	58	433	1	2022-10-13	S
9	1	30	340	1	2022-10-13	S
9	1	23	457	1	2022-10-13	S
9	1	29	435	1	2022-10-13	S
9	1	60	451	1	2022-10-13	S
9	1	16	80	1	2022-10-13	S
9	1	16	84	1	2022-10-13	S
9	1	16	85	1	2022-10-13	S
9	1	16	113	1	2022-10-13	S
9	1	16	131	1	2022-10-13	S
9	1	16	132	1	2022-10-13	S
9	1	41	302	1	2022-10-13	S
9	1	41	303	1	2022-10-13	S
9	1	84	492	1	2022-10-13	S
9	1	21	479	1	2022-10-13	S
9	1	31	461	1	2022-10-13	S
9	1	21	456	1	2022-10-13	S
9	1	60	447	1	2022-10-13	S
9	1	21	369	1	2022-10-13	S
9	1	21	368	1	2022-10-13	S
9	1	21	366	1	2022-10-13	S
9	1	21	121	1	2022-10-13	S
9	1	21	223	1	2022-10-13	S
9	1	21	243	1	2022-10-13	S
9	1	21	244	1	2022-10-13	S
9	1	21	271	1	2022-10-13	S
9	1	44	310	1	2022-10-13	S
9	1	44	330	1	2022-10-13	S
9	1	28	167	1	2022-10-13	S
9	1	28	263	1	2022-10-13	S
9	1	28	266	1	2022-10-13	S
9	1	43	291	1	2022-10-13	S
9	1	40	279	1	2022-10-13	S
9	1	40	281	1	2022-10-13	S
9	1	30	339	1	2022-10-13	S
9	1	30	342	1	2022-10-13	S
9	1	29	274	1	2022-10-13	S
9	1	60	408	1	2022-10-13	S
9	1	97	564	1	2022-10-13	S
9	1	97	566	1	2022-10-13	S
9	1	98	568	1	2022-10-13	S
9	1	95	577	1	2022-10-13	S
9	1	95	576	1	2022-10-13	S
9	1	98	573	1	2022-10-13	S
9	1	97	559	1	2022-10-13	S
9	1	97	560	1	2022-10-13	S
9	1	90	545	1	2022-10-13	S
9	1	90	544	1	2022-10-13	S
9	1	31	537	1	2022-10-13	S
9	1	29	535	1	2022-10-13	S
9	1	88	528	1	2022-10-13	S
9	1	23	527	1	2022-10-13	S
9	1	35	526	1	2022-10-13	S
9	1	21	524	1	2022-10-13	S
9	1	44	523	1	2022-10-13	S
9	1	8	521	1	2022-10-13	S
9	1	21	517	1	2022-10-13	S
9	1	35	516	1	2022-10-13	S
9	1	35	514	1	2022-10-13	S
9	1	21	513	1	2022-10-13	S
9	1	86	519	1	2022-10-13	S
9	1	8	520	1	2022-10-13	S
9	1	31	509	1	2022-10-13	S
9	1	21	510	1	2022-10-13	S
9	1	29	508	1	2022-10-13	S
9	1	27	507	1	2022-10-13	S
9	1	16	506	1	2022-10-13	S
9	1	35	406	1	2022-10-13	S
9	1	42	504	1	2022-10-13	S
9	1	42	503	1	2022-10-13	S
9	1	42	501	1	2022-10-13	S
9	1	42	500	1	2022-10-13	S
9	1	29	499	1	2022-10-13	S
9	1	57	495	1	2022-10-13	S
9	1	85	494	1	2022-10-13	S
9	1	84	491	1	2022-10-13	S
9	1	83	490	1	2022-10-13	S
9	1	83	489	1	2022-10-13	S
9	1	82	481	1	2022-10-13	S
9	1	28	505	1	2022-10-13	S
9	1	21	611	1	2022-10-13	S
9	1	21	610	1	2022-10-13	S
9	1	21	607	1	2022-10-13	S
9	1	21	606	1	2022-10-13	S
9	1	99	605	1	2022-10-13	S
9	1	99	604	1	2022-10-13	S
9	1	99	603	1	2022-10-13	S
9	1	99	602	1	2022-10-13	S
9	1	99	601	1	2022-10-13	S
9	1	99	597	1	2022-10-13	S
9	1	95	594	1	2022-10-13	S
9	1	95	590	1	2022-10-13	S
9	1	97	563	1	2022-10-13	S
9	1	97	565	1	2022-10-13	S
9	1	98	569	1	2022-10-13	S
9	1	98	570	1	2022-10-13	S
9	1	98	572	1	2022-10-13	S
9	1	98	574	1	2022-10-13	S
9	1	21	541	1	2022-10-13	S
9	1	16	533	1	2022-10-13	S
9	1	6	525	1	2022-10-13	S
9	1	87	522	1	2022-10-13	S
9	1	21	518	1	2022-10-13	S
9	1	35	515	1	2022-10-13	S
9	1	21	512	1	2022-10-13	S
9	1	21	511	1	2022-10-13	S
9	1	42	502	1	2022-10-13	S
9	1	85	493	1	2022-10-13	S
9	1	23	488	1	2022-10-13	S
9	1	23	460	1	2022-10-13	S
9	1	21	370	1	2022-10-13	S
9	1	21	365	1	2022-10-13	S
9	1	21	242	1	2022-10-13	S
9	1	35	612	1	2022-10-13	S
9	1	21	609	1	2022-10-13	S
10	1	91	553	1	2022-10-13	S
10	1	91	546	1	2022-10-13	S
10	1	6	71	1	2022-10-13	S
10	1	6	72	1	2022-10-13	S
10	1	6	73	1	2022-10-13	S
10	1	6	76	1	2022-10-13	S
10	1	23	149	1	2022-10-13	S
10	1	23	151	1	2022-10-13	S
10	1	23	152	1	2022-10-13	S
10	1	23	155	1	2022-10-13	S
10	1	23	156	1	2022-10-13	S
10	1	21	158	1	2022-10-13	S
10	1	21	154	1	2022-10-13	S
10	1	91	547	1	2022-10-13	S
10	1	38	249	1	2022-10-13	S
10	1	44	300	1	2022-10-13	S
10	1	23	165	1	2022-10-13	S
10	1	35	217	1	2022-10-13	S
10	1	41	286	1	2022-10-13	S
10	1	21	270	1	2022-10-13	S
10	1	42	287	1	2022-10-13	S
10	1	40	280	1	2022-10-13	S
10	1	37	228	1	2022-10-13	S
10	1	38	250	1	2022-10-13	S
10	1	6	227	1	2022-10-13	S
10	1	37	236	1	2022-10-13	S
10	1	44	313	1	2022-10-13	S
10	1	43	298	1	2022-10-13	S
10	1	30	171	1	2022-10-13	S
10	1	30	172	1	2022-10-13	S
10	1	29	282	1	2022-10-13	S
10	1	31	174	1	2022-10-13	S
10	1	29	170	1	2022-10-13	S
10	1	43	292	1	2022-10-13	S
10	1	41	285	1	2022-10-13	S
10	1	38	251	1	2022-10-13	S
10	1	37	230	1	2022-10-13	S
10	1	37	231	1	2022-10-13	S
10	1	37	235	1	2022-10-13	S
10	1	37	241	1	2022-10-13	S
10	1	23	216	1	2022-10-13	S
10	1	29	169	1	2022-10-13	S
10	1	29	283	1	2022-10-13	S
10	1	29	284	1	2022-10-13	S
10	1	31	175	1	2022-10-13	S
10	1	92	555	1	2022-10-13	S
10	1	91	548	1	2022-10-13	S
10	1	39	262	1	2022-10-13	S
10	1	28	264	1	2022-10-13	S
10	1	28	166	1	2022-10-13	S
10	1	21	240	1	2022-10-13	S
10	1	35	218	1	2022-10-13	S
10	1	60	444	1	2022-10-13	S
10	1	58	433	1	2022-10-13	S
10	1	30	340	1	2022-10-13	S
10	1	23	457	1	2022-10-13	S
10	1	29	435	1	2022-10-13	S
10	1	60	451	1	2022-10-13	S
10	1	16	80	1	2022-10-13	S
10	1	16	84	1	2022-10-13	S
10	1	16	85	1	2022-10-13	S
10	1	16	113	1	2022-10-13	S
10	1	16	131	1	2022-10-13	S
10	1	16	132	1	2022-10-13	S
10	1	41	302	1	2022-10-13	S
10	1	41	303	1	2022-10-13	S
10	1	84	492	1	2022-10-13	S
10	1	21	479	1	2022-10-13	S
10	1	31	461	1	2022-10-13	S
10	1	21	456	1	2022-10-13	S
10	1	60	447	1	2022-10-13	S
10	1	21	369	1	2022-10-13	S
10	1	21	368	1	2022-10-13	S
10	1	21	366	1	2022-10-13	S
10	1	21	121	1	2022-10-13	S
10	1	21	223	1	2022-10-13	S
10	1	21	243	1	2022-10-13	S
10	1	21	244	1	2022-10-13	S
10	1	21	271	1	2022-10-13	S
10	1	44	310	1	2022-10-13	S
10	1	44	330	1	2022-10-13	S
10	1	28	167	1	2022-10-13	S
10	1	28	263	1	2022-10-13	S
10	1	28	266	1	2022-10-13	S
10	1	43	291	1	2022-10-13	S
10	1	40	279	1	2022-10-13	S
10	1	40	281	1	2022-10-13	S
10	1	30	339	1	2022-10-13	S
10	1	30	342	1	2022-10-13	S
10	1	29	274	1	2022-10-13	S
10	1	60	408	1	2022-10-13	S
10	1	97	564	1	2022-10-13	S
10	1	97	566	1	2022-10-13	S
10	1	98	568	1	2022-10-13	S
10	1	95	577	1	2022-10-13	S
10	1	95	576	1	2022-10-13	S
10	1	98	573	1	2022-10-13	S
10	1	97	559	1	2022-10-13	S
10	1	97	560	1	2022-10-13	S
10	1	90	545	1	2022-10-13	S
10	1	90	544	1	2022-10-13	S
10	1	31	537	1	2022-10-13	S
10	1	29	535	1	2022-10-13	S
10	1	88	528	1	2022-10-13	S
10	1	23	527	1	2022-10-13	S
10	1	35	526	1	2022-10-13	S
10	1	21	524	1	2022-10-13	S
10	1	44	523	1	2022-10-13	S
10	1	8	521	1	2022-10-13	S
10	1	21	517	1	2022-10-13	S
10	1	35	516	1	2022-10-13	S
10	1	35	514	1	2022-10-13	S
10	1	21	513	1	2022-10-13	S
10	1	86	519	1	2022-10-13	S
10	1	8	520	1	2022-10-13	S
10	1	31	509	1	2022-10-13	S
10	1	21	510	1	2022-10-13	S
10	1	29	508	1	2022-10-13	S
10	1	27	507	1	2022-10-13	S
10	1	16	506	1	2022-10-13	S
10	1	35	406	1	2022-10-13	S
10	1	42	504	1	2022-10-13	S
10	1	42	503	1	2022-10-13	S
10	1	42	501	1	2022-10-13	S
10	1	42	500	1	2022-10-13	S
10	1	29	499	1	2022-10-13	S
10	1	57	495	1	2022-10-13	S
10	1	85	494	1	2022-10-13	S
10	1	84	491	1	2022-10-13	S
10	1	83	490	1	2022-10-13	S
10	1	83	489	1	2022-10-13	S
10	1	82	481	1	2022-10-13	S
10	1	28	505	1	2022-10-13	S
10	1	21	611	1	2022-10-13	S
10	1	21	610	1	2022-10-13	S
10	1	21	607	1	2022-10-13	S
10	1	21	606	1	2022-10-13	S
10	1	99	605	1	2022-10-13	S
10	1	99	604	1	2022-10-13	S
10	1	99	603	1	2022-10-13	S
10	1	99	602	1	2022-10-13	S
10	1	99	601	1	2022-10-13	S
10	1	99	597	1	2022-10-13	S
10	1	95	594	1	2022-10-13	S
10	1	95	590	1	2022-10-13	S
10	1	97	563	1	2022-10-13	S
10	1	97	565	1	2022-10-13	S
10	1	98	569	1	2022-10-13	S
10	1	98	570	1	2022-10-13	S
10	1	98	572	1	2022-10-13	S
10	1	98	574	1	2022-10-13	S
10	1	21	541	1	2022-10-13	S
10	1	16	533	1	2022-10-13	S
10	1	6	525	1	2022-10-13	S
10	1	87	522	1	2022-10-13	S
10	1	21	518	1	2022-10-13	S
10	1	35	515	1	2022-10-13	S
10	1	21	512	1	2022-10-13	S
10	1	21	511	1	2022-10-13	S
10	1	42	502	1	2022-10-13	S
10	1	85	493	1	2022-10-13	S
10	1	23	488	1	2022-10-13	S
10	1	23	460	1	2022-10-13	S
10	1	21	370	1	2022-10-13	S
10	1	21	365	1	2022-10-13	S
10	1	21	242	1	2022-10-13	S
10	1	35	612	1	2022-10-13	S
10	1	21	609	1	2022-10-13	S
\.


--
-- TOC entry 4121 (class 0 OID 10423214)
-- Dependencies: 342
-- Data for Name: tb_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_pesquisa (idpesquisa, situacao, idcadastrador, datcadastro, datpublicacao, idpespublica, idpesencerra, idquestionario, dtencerramento) FROM stdin;
1	2	1	2014-06-02 15:24:23.061829	2014-06-02 16:51:32.186046	1	1	1	2015-04-01 15:45:15.066466
2	1	1	2014-06-02 16:00:54.196066	2015-04-01 15:43:48.093833	1	1	2	2015-04-01 15:43:36.630525
\.


--
-- TOC entry 4122 (class 0 OID 10423218)
-- Dependencies: 343
-- Data for Name: tb_pessoa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_pessoa (idpessoa, nompessoa, desobs, numfone, numcelular, desemail, idcadastrador, datcadastro, nummatricula, desfuncao, id_unidade, domcargo, id_servidor, flaagenda, numcpf, numsiape, versaosistema, token) FROM stdin;
2	Usuario02	teste	9999999999	61999999999	usuario02@gepnet2.gov	1	2015-03-03	1002	Consultor Senior	1	EPF	2	S	33003016024	\N	3.4.3	a2baaf0f83b59aac824aa705d86cd550
5	Usuario05	teste	9999999999	99999999999	usuario05@gepnet2.gov	1	2015-03-03	1005	Consultora Senior	1	PCF	5	S	20564962082	\N	3.4.1	3af73846ef1d25d3cb29e3d469b0c413
4	Usuario04	teste	9999999999	61999990909	usuario04@gepnet2.gov	1	2015-03-03	1004	Coordenador	1	DPF	4	S	04794172028	\N	3.1.15	2e19c5b7a1c312aeb4e27f986dcdfa80
3	Usuario03	teste	9999999999	61899999999	usuario03@gepnet2.gov	1	2015-03-03	1003	Consultor Senior	1	PCF	3	S	80264313089	\N	3.4.3	87c1a2e5cb8f3213c4a438609635360d
1	Usuario01	teste	9999999999	61999999999	usuario01@gepnet2.gov	1	2015-03-03	1001	Coordenador	1	APF	1	S	47754594064	\N	3.4.3	0e1177622dc1d5506b5add8829b504a7
\.


--
-- TOC entry 4123 (class 0 OID 10423225)
-- Dependencies: 344
-- Data for Name: tb_pessoaagenda; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_pessoaagenda (idagenda, idpessoa) FROM stdin;
\.


--
-- TOC entry 4124 (class 0 OID 10423228)
-- Dependencies: 345
-- Data for Name: tb_portfolio; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_portfolio (idportfolio, noportfolio, idportfoliopai, ativo, tipo, idresponsavel, idescritorio) FROM stdin;
32	2 PORTFOLIO GERAL	\N	S	1	1	0
31	1 PORTFOLIO ESTRATEGICO	\N	S	2	1	0
\.


--
-- TOC entry 4125 (class 0 OID 10423233)
-- Dependencies: 346
-- Data for Name: tb_portifolioprograma; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_portifolioprograma (idprograma, idportfolio) FROM stdin;
5	31
\.


--
-- TOC entry 4126 (class 0 OID 10423236)
-- Dependencies: 347
-- Data for Name: tb_processo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_processo (idprocesso, idprocessopai, nomcodigo, nomprocesso, idsetor, desprocesso, iddono, idexecutor, idgestor, idconsultor, numvalidade, datatualizacao, idcadastrador, datcadastro) FROM stdin;
1	1	1/2015	MACROPROCESSO 1	1	.	1	2	4	5	36	2017-12-11	1	2015-06-10
2	1	2/2015	Processo 1	1	AFAL ALJALC AAA AJ AJ f kjhbd blsdbhdh bh sdlkhslkbjhg rfg	1	2	4	5	24	2015-10-15	1	2015-06-10
3	1	3/2015	Processo 2	1	.	1	2	4	5	48	2022-10-13	1	2015-06-10
\.


--
-- TOC entry 4127 (class 0 OID 10423242)
-- Dependencies: 348
-- Data for Name: tb_programa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_programa (idprograma, nomprograma, desprograma, idcadastrador, datcadastro, flaativo, idresponsavel, idsimpr, idsimpreixo, idsimprareatematica) FROM stdin;
1	0. SEM PROGRAMA	Valor default para projetos nao vinculados a nenhum programa.	1	2012-05-17	S	1	1	1	1
5	PROJETOS ESTRATEGICOS	Programa que agrupa os projetos estrategicos da instituicao.	1	2014-01-27	S	1	1	1	1
\.


--
-- TOC entry 4128 (class 0 OID 10423249)
-- Dependencies: 349
-- Data for Name: tb_projeto; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_projeto (idprojeto, nomcodigo, nomsigla, nomprojeto, idsetor, idgerenteprojeto, idgerenteadjunto, desprojeto, desobjetivo, datinicio, datfim, numperiodicidadeatualizacao, numcriteriofarol, idcadastrador, datcadastro, domtipoprojeto, flapublicado, flaaprovado, desresultadosobtidos, despontosfortes, despontosfracos, dessugestoes, idescritorio, flaaltagestao, idobjetivo, idacao, flacopa, idnatureza, vlrorcamentodisponivel, desjustificativa, iddemandante, idpatrocinador, datinicioplano, datfimplano, desescopo, desnaoescopo, despremissa, desrestricao, numseqprojeto, numanoprojeto, desconsideracaofinal, datenviouemailatualizacao, idprograma, nomproponente, domstatusprojeto, ano, idportfolio, idtipoiniciativa, numpercentualconcluido, numpercentualprevisto, numprocessosei, atraso, numpercentualconcluidomarco, domcoratraso, qtdeatividadeiniciada, numpercentualiniciado, qtdeatividadenaoiniciada, numpercentualnaoiniciado, qtdeatividadeconcluida, numpercentualatividadeconcluido) FROM stdin;
1	001/2022/ESCRITORIO_0	\N	1 TREINAMENTO - EXEMPLO PROJETO	1	1	2	descricao projeto xxxx	objetivo do projeto xxxx	2020-04-30	2022-04-29	30	30	1	2015-03-05	Normal	S	N	\N	\N	\N	\N	0	\N	2	2	N	8	1000000	justificativa do projeto xxx	1	1	2020-07-30	2020-10-30	escopo do projeto - principais entregas	descricao do nao escopo do projeto	premissas do projeto	restricoes do projeto	\N	\N	teste teste	\N	1	\N	3	2014	32	1	100.00	100.00	08200000001202232	150	100.00	important	0.00	0.00	0.00	0.00	4.00	100.00
2	002/2022/ESCRITORIO_0	\N	2 PROJETO PROCESSO DESENVOLVIMENTO DESCENTRALIZADO SOFTWARE PF	2	1	3	descricao projeto	objetivo do projeto	2022-01-03	2023-01-31	30	30	1	2015-10-29	Normal	S	S	\N	\N	\N	\N	0	\N	1	1	N	5	100000	justificativa do projeto	5	4	2022-01-31	2022-03-31	escopo do projeto	descricao do nao escopo do projeto	premissas do projeto	restricoes do projeto	\N	\N		\N	1	\N	2	2013	32	1	4.35	6.45	08200000000202222	0	8.33	success	0.00	0.00	22.00	95.65	1.00	4.35
\.


--
-- TOC entry 4129 (class 0 OID 10423272)
-- Dependencies: 350
-- Data for Name: tb_projetoprocesso; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_projetoprocesso (idprojetoprocesso, idprocesso, numano, domsituacao, datsituacao, idresponsavel, desprojetoprocesso, datinicioprevisto, datterminoprevisto, vlrorcamento, idcadastrador, datcadastro) FROM stdin;
1	1	2015	2	2015-06-10	1	TESTE	2015-06-11	2015-06-30	200000	1	2015-06-10
\.


--
-- TOC entry 4130 (class 0 OID 10423279)
-- Dependencies: 351
-- Data for Name: tb_questdiagnosticopadronizamelhoria; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_questdiagnosticopadronizamelhoria (idpadronizacaomelhoria, idmelhoria, desrevisada, idprazo, idimpacto, idesforco, numpontuacao, numincidencia, numvotacao, flaagrupadora, destitulogrupo, desinformacoescomplementares, desmelhoriaagrupadora) FROM stdin;
\.


--
-- TOC entry 4131 (class 0 OID 10423285)
-- Dependencies: 352
-- Data for Name: tb_questionario; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_questionario (idquestionario, nomquestionario, desobservacao, tipoquestionario, idcadastrador, datcadastro, idescritorio, disponivel) FROM stdin;
1	Questionario publicado com senha	Exemplo.	1	1	2014-04-03	0	0
2	Questionario publicado sem senha	Exemplo.	2	1	2014-04-07	0	0
\.


--
-- TOC entry 4132 (class 0 OID 10423294)
-- Dependencies: 353
-- Data for Name: tb_questionario_diagnostico; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_questionario_diagnostico (idquestionariodiagnostico, nomquestionario, tipo, observacao, idpescadastrador, dtcadastro) FROM stdin;
1	Questionario teste1	1	teste teste	1	2022-10-13
\.


--
-- TOC entry 4133 (class 0 OID 10423302)
-- Dependencies: 354
-- Data for Name: tb_questionario_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_questionario_pesquisa (idquestionariopesquisa, idpesquisa, nomquestionario, desobservacao, tipoquestionario, idcadastrador, datcadastro, idescritorio) FROM stdin;
1	1	Teste Final sem senha	teste sem senha	2	1	2014-06-02	0
2	2	Teste Final com senha	com senha	1	1	2014-06-02	0
\.


--
-- TOC entry 4134 (class 0 OID 10423309)
-- Dependencies: 355
-- Data for Name: tb_questionariodiagnostico_respondido; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_questionariodiagnostico_respondido (idquestionario, iddiagnostico, numero, dt_resposta, idpessoaresposta) FROM stdin;
\.


--
-- TOC entry 4135 (class 0 OID 10423312)
-- Dependencies: 356
-- Data for Name: tb_questionariodiagnosticomelhoria; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_questionariodiagnosticomelhoria (idmelhoria, datmelhoria, desmelhoria, idmacroprocessotrabalho, idmacroprocessomelhorar, idunidaderesponsavelproposta, flaabrangencia, idunidaderesponsavelimplantacao, idobjetivoinstitucional, idacaoestrategica, idareamelhoria, idsituacao, iddiagnostico, idunidadeprincipal, matriculaproponente) FROM stdin;
\.


--
-- TOC entry 4136 (class 0 OID 10423318)
-- Dependencies: 357
-- Data for Name: tb_questionariofrase; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_questionariofrase (idfrase, idquestionario, numordempergunta, idcadastrador, datcadastro, obrigatoriedade) FROM stdin;
1	1	1	1	2015-04-01	N
\.


--
-- TOC entry 4137 (class 0 OID 10423322)
-- Dependencies: 358
-- Data for Name: tb_questionariofrase_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_questionariofrase_pesquisa (idquestionariopesquisa, idfrasepesquisa, numordempergunta, datcadastro, idcadastrador, obrigatoriedade) FROM stdin;
1	1	4	2014-06-02	1	N
\.


--
-- TOC entry 4138 (class 0 OID 10423327)
-- Dependencies: 359
-- Data for Name: tb_r3g; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_r3g (idr3g, idprojeto, datdeteccao, desplanejado, desrealizado, descausa, desconsequencia, descontramedida, datprazocontramedida, datprazocontramedidaatraso, idcadastrador, datcadastro, desresponsavel, desobs, domtipo, domcorprazoprojeto, domstatuscontramedida, flacontramedidaefetiva) FROM stdin;
1	1	2015-04-06	TESTE	TESTE	TESTE	TESTE	TESTE	2015-04-06	2015-04-08	1	2015-04-06	USUARIO01	\N	3	2	1	1
2	1	2015-04-01	TESTE	TESTE	TESTE	TESTE	TESTE	2015-04-01	2015-04-30	1	2015-04-08	USUARIO01	\N	1	1	3	1
\.


--
-- TOC entry 4139 (class 0 OID 10423337)
-- Dependencies: 360
-- Data for Name: tb_recurso; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_recurso (idrecurso, ds_recurso, descricao) FROM stdin;
15	default:documento	\N
17	processo:pacao	\N
18	planejamento:acao	\N
19	default:print	\N
20	planejamento:index	\N
22	default:escritorio	\N
24	default:log	\N
25	default:os	\N
26	default:parecer	\N
1	cadastro:recurso	\N
2	cadastro:permissao	\N
3	default:error	\N
4	cadastro:index	\N
5	cadastro:perfil	\N
7	processo:projeto	\N
8	default:index	\N
9	cadastro:documento	\N
10	default:test	\N
11	cadastro:escritorio	\N
12	cadastro:pessoa	\N
13	cadastro:programa	\N
14	processo:index	\N
32	pessoal:atividade	\N
33	default:perfil	\N
34	cadastro:perfilpessoa	\N
36	comunicacao:index	\N
43	projeto:r3g	\N
45	evento:grandeseventos	\N
46	acordocooperacao:entidadeexterna	\N
48	cadastro:setor	\N
50	planejamento:portfolio	\N
51	pesquisa:pergunta	\N
52	pesquisa:resposta	\N
53	agenda:index	\N
54	pesquisa:questionario	\N
55	cadastro:funcionalidade	\N
56	evento:avaliacaoservidor	\N
57	relatorio:risco	\N
59	pesquisa:pesquisa	\N
61	pesquisa:responder	\N
62	acordocooperacao:instrumentocooperacao	\N
63	pesquisa:relatorio	\N
64	pesquisa:historico	\N
65	atividade:add	\N
66	atividade:index	\N
67	atividade:relatorio	\N
68	pergunta:listar	\N
69	questionario:listar	\N
70	pesquisa:listar	\N
71	pesquisa:gerenciar-pesquisas	\N
72	responder:listar	\N
73	relatorio:listar	\N
74	historico:listar	\N
75	pesquisa:index	\N
76	risco:index	\N
78	planejamento:index	\N
77	relatorio:risco	\N
81	gerencia:index	\N
88	default:autenticarcodigo	\N
83	relatorio:licao	\N
84	relatorio:diariobordo	\N
85	relatorio:aceite	\N
86	default:download	\N
87	default:versao	\N
6	projeto:gerencia	Gerencia de Projetos
16	projeto:tap	Termo de Abertura
21	projeto:cronograma	Cronograma
23	projeto:statusreport	Status Report
27	projeto:planoprojeto	Plano de Projeto
28	projeto:atareuniao	Ata de Reuniao
29	projeto:termoaceite	Termo de Aceite
30	projeto:solicitacaomudanca	Solicitacao de Mudanca
31	projeto:termoencerramento	Termo de Encerramento
35	projeto:relatorio	Relatorio de Acompanhamento
37	projeto:comunicacao	Comunicacao
38	projeto:rh	Recursos Humanos
39	projeto:ataprojeto	Ata de Reuniao
40	projeto:diario	Diario
41	projeto:eap	EAP
42	projeto:gantt	Gantt
44	projeto:risco	Risco
47	projeto:contramedida	Contramedida
49	projeto:rud	Repositorio de Arquivos
58	projeto:tep	Termo de Encerramento
60	projeto:licao	Licoes Aprendidas
82	projeto:error	Erros de aplicacao
89	projeto:validaassinatura	Assinatura de Documentos
90	projeto:linhatempo	Log de linha do tempo
91	diagnostico:diagnostico	
92	diagnostico:error	\N
93	diagnostico:diagnostico	Diagnostico
94	diagnostico:error	Erro de diagnostico
95	diagnostico:questionario	Questionarios de diagnostico
96	diagnostico:estatistica	Estatistica de diagnostico
97	diagnostico:pesquisacidadaos	Pesquisa de cidadaos em diagnostico
98	diagnostico:pesquisaservidores	Pesquisa de servidores em diagnostico
99	diagnostico:sugestaomelhoria	Sugestao de melhoria em diagnostico
\.


--
-- TOC entry 4140 (class 0 OID 10423340)
-- Dependencies: 361
-- Data for Name: tb_resposta; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_resposta (idresposta, numordem, flaativo, datcadastro, idcadastrador, desresposta) FROM stdin;
81	1	S	2015-04-01	1	Classe Especial
82	2	S	2015-04-01	1	Primeira Classe
83	3	S	2015-04-01	1	Segunda Classe
84	4	S	2015-04-01	1	Terceira Classe
\.


--
-- TOC entry 4141 (class 0 OID 10423344)
-- Dependencies: 362
-- Data for Name: tb_resposta_pergunta; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_resposta_pergunta (id_resposta_pergunta, ds_resposta_descritiva, idpergunta, idresposta, nrquestionario, idquestionario, iddiagnostico) FROM stdin;
\.


--
-- TOC entry 4142 (class 0 OID 10423350)
-- Dependencies: 363
-- Data for Name: tb_resposta_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_resposta_pesquisa (idrespostapesquisa, desresposta, numordem, flaativo, datcadastro, idcadastrador) FROM stdin;
1	Resposta teste	1	S	2014-03-18	1
\.


--
-- TOC entry 4143 (class 0 OID 10423354)
-- Dependencies: 364
-- Data for Name: tb_resposta_questionariordiagnostico; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_resposta_questionariordiagnostico (id_resposta_pergunta, idquestionario, iddiagnostico, numero) FROM stdin;
\.


--
-- TOC entry 4144 (class 0 OID 10423357)
-- Dependencies: 365
-- Data for Name: tb_respostafrase; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_respostafrase (idfrase, idresposta) FROM stdin;
1	81
1	82
1	83
1	84
1	81
1	82
1	83
1	84
1	81
1	82
1	83
1	84
1	81
1	82
1	83
1	84
1	81
1	82
1	83
1	84
\.


--
-- TOC entry 4145 (class 0 OID 10423360)
-- Dependencies: 366
-- Data for Name: tb_respostafrase_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_respostafrase_pesquisa (idfrasepesquisa, idrespostapesquisa) FROM stdin;
2	1
\.


--
-- TOC entry 4146 (class 0 OID 10423363)
-- Dependencies: 367
-- Data for Name: tb_resultado_pesquisa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_resultado_pesquisa (id, idresultado, idfrasepesquisa, idquestionariopesquisa, desresposta, datcadastro, cpf) FROM stdin;
1	1	1	1	1	2014-06-12 11:11:40	\N
\.


--
-- TOC entry 4147 (class 0 OID 10423369)
-- Dependencies: 368
-- Data for Name: tb_risco; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_risco (idrisco, idprojeto, idorigemrisco, idetapa, idtiporisco, datdeteccao, desrisco, domcorprobabilidade, domcorimpacto, domcorrisco, descausa, desconsequencia, flariscoativo, datencerramentorisco, idcadastrador, datcadastro, domtratamento, norisco, flaaprovado, datinatividade) FROM stdin;
2	2	2	4	1	2015-08-19	Contingenciamento de recursos orcamentarios	2	1	2	Necessidade de reequilibrio de orcamentario	a) atraso no projeto; \r\nb) reducao de escopo ou paralisacao do projeto.	1	\N	1	2015-10-26	2	Contingenciamento de recursos orcamentarios	1	\N
3	1	5	4	2	2022-10-14	Descricao teste	3	1	3	Causa teste	Consequencia teste	1	\N	1	2022-10-14	13	Risco teste 01	1	2022-10-14
1	1	5	3	1	2015-06-08	Falta de recursos orcamentarios para o projeto xx	1	1	1	Cortes orcamentarios do governo xx	Cancelamento do projeto xx	1	\N	1	2015-06-10	10	Indisponibilidade orcamentaria	1	\N
\.


--
-- TOC entry 4148 (class 0 OID 10423381)
-- Dependencies: 369
-- Data for Name: tb_secao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_secao (id_secao, ds_secao, id_secao_pai, ativa, tp_questionario, macroprocesso) FROM stdin;
1	Cargo do Servidor	1	t	S	f
2	Principal area de atuacao (Macroprocesso)	2	t	S	t
3	Processos Internos (atividades e rotinas de trabalho)	3	t	S	f
4	Comunicacao Interna	4	t	S	f
5	Recursos e Infraestrutura	5	t	S	f
6	Gestao Organizacional	6	t	S	f
7	Satisfacao Pessoal	7	t	S	f
8	Local de Atendimento	8	t	C	f
9	Servico Utilizado	9	t	C	f
10	Avaliacao	10	t	C	f
11	Informacoes Estatisticas (opcionais)	11	t	C	f
\.


--
-- TOC entry 4149 (class 0 OID 10423386)
-- Dependencies: 370
-- Data for Name: tb_setor; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_setor (idsetor, nomsetor, idcadastrador, datcadastro, flaativo) FROM stdin;
1	Comite estrategico	1	2010-02-01	S
2	Diretoria de logistica	1	2010-02-01	S
3	Diretoria cientifica	1	2010-02-01	S
4	Diretoria de orcamento	1	2010-02-01	S
5	Diretoria de operacoes	1	2010-02-01	S
6	Diretoria de TI	1	2010-02-01	S
7	Universidade corporativa	1	2010-02-01	S
8	Diretoria de governanca	1	2010-02-01	S
9	Gabinete geral	1	2010-02-01	S
10	Diretoria de pessoal	1	2010-02-01	S
\.


--
-- TOC entry 4150 (class 0 OID 10423390)
-- Dependencies: 371
-- Data for Name: tb_statusreport; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_statusreport (idstatusreport, idprojeto, datacompanhamento, desatividadeconcluida, desatividadeandamento, desmotivoatraso, desirregularidade, idmarco, datmarcotendencia, datfimprojetotendencia, idcadastrador, datcadastro, flaaprovado, domcorrisco, descontramedida, desrisco, domstatusprojeto, dataprovacao, numpercentualconcluido, numpercentualprevisto, numdiasprojeto, numpercentualmarcos, numpercentualdiferenca, numpercentualcustoreal, numcustorealtotal, idresponsavelaceitacao, pgpassinado, tepassinado, desandamentoprojeto, numpercentualconcluidomarco, diaatraso, domcoratraso, numcriteriofarol, datfimprojeto) FROM stdin;
1	1	2022-10-10	TESTE	TESTE	TESTE	TESTE	\N	\N	2022-11-10	1	2022-10-10	1	1	TESTE	TESTE	2	2022-10-10	10.00	10.00	15	10.00	0.00	10.00	10	1	S	N	TESTE	10.00	0	1	15	\N
2	2	2022-10-10	TESTE	TESTE	TESTE	TESTE	\N	\N	2022-10-10	1	2022-10-10	1	1	TESTE	TESTE	2	2022-10-10	10.00	10.00	15	10.00	0.00	10.00	10	1	S	N	TESTE	10.00	0	1	15	\N
5	1	2022-10-13	04/01/2021 - 12/02/2021 - atividade 1\n01/07/2021 - 09/11/2021 - atividade 3\n31/03/2022 - 31/03/2022 - atividade 2 - marco de entrega\n31/03/2022 - 31/03/2022 - atividade 4 - marco de entrega\n29/10/2021 - 19/04/2022 - atividade 5\n19/04/2022 - 19/04/2022 - atividade 6 - marco de entrega\n22/04/2022 - 01/11/2022 - atividade 7	Nao existem atividades em andamento neste periodo.	TESTE	Nao ha irregularidades.	\N	\N	2022-11-04	1	2022-10-13	1	2	TESTE\nTESTE\nTESTE\nTESTE\nTESTE\n - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	Titulo: Indisponibilidade orcamentaria\nDescricao: Falta de recursos orcamentarios para o projeto\nCausa: Cortes orcamentarios do governo\nConsequencia: Cancelamento do projeto\n\n - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -	2	2022-10-13	100.00	100.00	0	0.00	0.00	0.00	0	0	S	N	Nao existem consideracoes sobre o andamento do projeto.	0.00	150	important	\N	2021-11-30
\.


--
-- TOC entry 4151 (class 0 OID 10423410)
-- Dependencies: 372
-- Data for Name: tb_tipoacordo; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_tipoacordo (idtipoacordo, dsacordo, idcadastrador, dtcadastro) FROM stdin;
1	Acordo de cooperacao	1	2015-07-10
2	Convenio	1	2015-08-06
3	Termo de Cooperacao	1	2015-06-08
4	Contrato de Repasse	1	2015-06-08
5	Termo de Parceria	1	2015-06-08
6	Termo de Ajustamento de Conduta	1	2015-08-06
\.


--
-- TOC entry 4152 (class 0 OID 10423416)
-- Dependencies: 373
-- Data for Name: tb_tipoavaliacao; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_tipoavaliacao (idtipoavaliacao, noavaliacao) FROM stdin;
1	Seguranca de Dignitarios
2	Cooperacao Policial Internacional
3	Policia Judiciaria (Plantao)
6	Seguranca Aeroportuaria
7	Policia Maritima
10	Comunicacao Social
14	Identificacao de Vitimas de Desastres
15	Inteligencia e Antiterrorismo
16	Operacoes Especiais
17	Policia de Fronteiras
18	Seguranca Cibernetica
19	Telecomunicacoes
20	Vistorias e Contramedidas
21	Varredura Eletronica
4	Controle Migratorio
5	Corregedoria
8	Controle de Seguranca Privada
9	Comando e Controle
11	Logistica
12	Aviacao Operacional
13	Controle de Armas
\.


--
-- TOC entry 4153 (class 0 OID 10423419)
-- Dependencies: 374
-- Data for Name: tb_tipocontramedida; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_tipocontramedida (idtipocontramedida, notipocontramedida, dstipocontramedida, idstatustipocontramedida) FROM stdin;
9	Aceitar (Reter)	Contramedida que visa aceitar (reter) o risco.         	1
10	Mitigar	Contramedida que visa mitigar o risco.                 	1
11	Melhorar	Contramedida que visa melhorar o risco.                	2
13	Escalar	Contramedida que visa escalar o risco.                 	3
14	Explorar	Contramedida que visa explorar o risco.                	2
15	Compartilhar	Contramedida que visa compartilhar o risco.            	2
16	Prevenir (Evitar)	Contramedida que visa prevenir (evitar) o risco.       	1
12	Transferir(Compartilhar)	Contramedida que visa transferir o risco.	1
1	Neutralizar (Eliminar risco)	Contramedida que procura eliminar o(s) risco(s).	1
2	Mitigar (Reduzir efeitos)	Contramedida que visa reduzir os efeitos do(s) risco(s).	1
3	Transferir risco	Contramedida que visa transferir as consequencias do(s) risco(s) para terceiro(s).	1
4	Aceitar risco	Contramedida que visa aceitar os efeitos do(s) risco(s).	1
5	Explorar risco (Potencializar ocorrencia)	Contramedida que visa potencializar a ocorrencia de um risco oportunidade.	2
6	Compartilhar risco	Contramedida que visa compartilhar os efeitos do(s) risco(s) com terceiro(s). 	1
7	Melhorar risco (Potencializar efeitos)	Contramedida que visa potencializar os efeitos de um risco oportunidade.	2
8	Conviver com risco	Contramedida que visa conviver com o risco.	1
\.


--
-- TOC entry 4154 (class 0 OID 10423422)
-- Dependencies: 375
-- Data for Name: tb_tipodocumento; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_tipodocumento (idtipodocumento, nomtipodocumento, idcadastrador, datcadastro, flaativo) FROM stdin;
1	Ata de Reuniao 	1	2010-07-05	S
5	Manual	1	2010-07-15	S
7	Relatorio	1	2010-07-15	S
6	Apresentacao	1	2010-07-15	S
4	Memorando	1	2010-07-05	S
3	Artigo academico	1	2010-07-05	S
2	E-mail	1	2010-07-05	S
8	Folder	1	2010-08-02	S
10	Informacao	1	2015-06-10	S
11	Termo Referencia	1	2015-06-10	S
12	Mapa	1	2015-06-10	S
13	Modelo BPM	1	2015-06-10	S
9	Oficio	1	2010-08-05	S
14	Outro	1	2015-06-10	S
\.


--
-- TOC entry 4155 (class 0 OID 10423425)
-- Dependencies: 376
-- Data for Name: tb_tipoiniciativa; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_tipoiniciativa (idtipoiniciativa, nomtipoiniciativa, destipoiniciativa, flaativo) FROM stdin;
1	Projeto	Projeto	S
2	Plano de Acao	Plano de Acao	S
\.


--
-- TOC entry 4156 (class 0 OID 10423433)
-- Dependencies: 377
-- Data for Name: tb_tipomudanca; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_tipomudanca (idtipomudanca, dsmudanca, idcadastrador, dtcadastro) FROM stdin;
1	Escopo	1	2014-02-14
2	Prazo	1	2014-02-14
3	Custo	1	2014-02-14
4	Qualidade	1	2014-02-14
5	Paralisacao	1	2014-02-14
6	Cancelamento	1	2015-05-27
7	Partes interessadas	1	2015-08-06
8	Outro	1	2015-08-06
\.


--
-- TOC entry 4157 (class 0 OID 10423436)
-- Dependencies: 378
-- Data for Name: tb_tiporisco; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_tiporisco (idtiporisco, dstiporisco, idcadastrador, dtcadastro) FROM stdin;
1	Risco Ameaca	1	2014-01-30
2	Risco Oportunidade	1	2014-01-30
\.


--
-- TOC entry 4158 (class 0 OID 10423439)
-- Dependencies: 379
-- Data for Name: tb_tiposituacaoprojeto; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_tiposituacaoprojeto (idtipo, nomtipo, desctipo, flatiposituacao) FROM stdin;
1	Proposta                                                                        	Informa que o projeto ainda nao foi aprovado pelo patrocinador.	1
3	Concluido                                                                       	Informa que o projeto foi concluido e aprovado pelo patrocinador.	1
2	Em andamento                                                                    	Informa que o projeto foi aprovado pelo patrocionador e esta em andamento. 	1
5	Cancelado                                                                       	Informa que o projeto foi cancelado sem ser concluido.	1
6	Bloqueado                                                                       	Informa que o projeto foi bloqueado por falta de acompanhamento.	2
4	Paralisado                                                                      	Informa que o projeto encontra-se paralisado.	1
8	Excluido                                                                        	Informa que o projeto foi excluido.	1
\.


--
-- TOC entry 4159 (class 0 OID 10423445)
-- Dependencies: 380
-- Data for Name: tb_tratamento; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_tratamento (idtratamento, dstratamento, idcadastrador, dtcadastro, idtiporisco) FROM stdin;
2	Mitigar	1	2015-06-10	1
6	Aceitar (Reter)	1	2018-08-16	1
7	Melhorar	1	2018-08-16	2
10	Explorar	1	2018-08-16	2
11	Compartilhar	1	2018-08-16	2
12	Prevenir (Evitar)	1	2018-08-16	1
8	Transferir	1	2018-08-16	1
1	Neutralizar	1	2015-06-10	1
3	Aceitar	1	2015-06-10	2
4	Transferir	1	2015-06-10	1
5	Potencializar	1	2015-06-10	2
9	Escalar	1	2018-08-16	1
\.


--
-- TOC entry 4054 (class 0 OID 10422799)
-- Dependencies: 273
-- Data for Name: tb_unidade; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_unidade (idunidade, idunidadeprincipal, sigla, nome, ativo) FROM stdin;
\.


--
-- TOC entry 4160 (class 0 OID 10423448)
-- Dependencies: 381
-- Data for Name: tb_unidade_vinculada; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_unidade_vinculada (idunidade, id_unidadeprincipal, iddiagnostico) FROM stdin;
\.


--
-- TOC entry 4161 (class 0 OID 10423451)
-- Dependencies: 382
-- Data for Name: tb_vincula_questionario; Type: TABLE DATA; Schema: agepnet200; Owner: postgres
--

COPY agepnet200.tb_vincula_questionario (idquestionario, iddiagnostico, disponivel, dtdisponibilidade, dtencerrramento, idpesdisponibiliza, idpesencerrou) FROM stdin;
\.


--
-- TOC entry 4496 (class 0 OID 0)
-- Dependencies: 277
-- Name: sq_diagnostico; Type: SEQUENCE SET; Schema: agepnet200; Owner: postgres
--

SELECT pg_catalog.setval('agepnet200.sq_diagnostico', 1, true);


--
-- TOC entry 4497 (class 0 OID 0)
-- Dependencies: 278
-- Name: sq_melhoria; Type: SEQUENCE SET; Schema: agepnet200; Owner: postgres
--

SELECT pg_catalog.setval('agepnet200.sq_melhoria', 1, false);


--
-- TOC entry 4498 (class 0 OID 0)
-- Dependencies: 279
-- Name: sq_questionariodiagnostico; Type: SEQUENCE SET; Schema: agepnet200; Owner: postgres
--

SELECT pg_catalog.setval('agepnet200.sq_questionariodiagnostico', 1, true);


--
-- TOC entry 4499 (class 0 OID 0)
-- Dependencies: 272
-- Name: tb_cargo_idcargo_seq; Type: SEQUENCE SET; Schema: agepnet200; Owner: postgres
--

SELECT pg_catalog.setval('agepnet200.tb_cargo_idcargo_seq', 1, false);


--
-- TOC entry 4500 (class 0 OID 0)
-- Dependencies: 299
-- Name: tb_diagnostico_sq_diagnostico_seq; Type: SEQUENCE SET; Schema: agepnet200; Owner: postgres
--

SELECT pg_catalog.setval('agepnet200.tb_diagnostico_sq_diagnostico_seq', 1, false);


--
-- TOC entry 4501 (class 0 OID 0)
-- Dependencies: 302
-- Name: tb_diautil_iddiautil_seq; Type: SEQUENCE SET; Schema: agepnet200; Owner: postgres
--

SELECT pg_catalog.setval('agepnet200.tb_diautil_iddiautil_seq', 32315, true);


--
-- TOC entry 4502 (class 0 OID 0)
-- Dependencies: 332
-- Name: tb_parteinteressadafuncao_idparteinteressadafuncao_seq; Type: SEQUENCE SET; Schema: agepnet200; Owner: postgres
--

SELECT pg_catalog.setval('agepnet200.tb_parteinteressadafuncao_idparteinteressadafuncao_seq', 6, true);


--
-- TOC entry 4503 (class 0 OID 0)
-- Dependencies: 274
-- Name: tb_unidade_idunidade_seq; Type: SEQUENCE SET; Schema: agepnet200; Owner: postgres
--

SELECT pg_catalog.setval('agepnet200.tb_unidade_idunidade_seq', 1, false);


--
-- TOC entry 3656 (class 2606 OID 10424291)
-- Name: tb_parteinteressadafuncao PK_parteinteressadafuncao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_parteinteressadafuncao
    ADD CONSTRAINT "PK_parteinteressadafuncao" PRIMARY KEY (idparteinteressadafuncao);


--
-- TOC entry 3626 (class 2606 OID 10424293)
-- Name: tb_licao fk_licao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_licao
    ADD CONSTRAINT fk_licao PRIMARY KEY (idlicao);


--
-- TOC entry 3550 (class 2606 OID 10424295)
-- Name: tb_acao pk_acao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acao
    ADD CONSTRAINT pk_acao PRIMARY KEY (idacao);


--
-- TOC entry 3552 (class 2606 OID 10424297)
-- Name: tb_aceite pk_aceite; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_aceite
    ADD CONSTRAINT pk_aceite PRIMARY KEY (idaceite);


--
-- TOC entry 3554 (class 2606 OID 10424299)
-- Name: tb_aceiteatividadecronograma pk_aceiteatividadecronograma; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_aceiteatividadecronograma
    ADD CONSTRAINT pk_aceiteatividadecronograma PRIMARY KEY (idaceiteativcronograma);


--
-- TOC entry 3556 (class 2606 OID 10424301)
-- Name: tb_acordo pk_acordo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordo
    ADD CONSTRAINT pk_acordo PRIMARY KEY (idacordo);


--
-- TOC entry 3558 (class 2606 OID 10424303)
-- Name: tb_acordoentidadeexterna pk_acordoentidadeexterna; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordoentidadeexterna
    ADD CONSTRAINT pk_acordoentidadeexterna PRIMARY KEY (idacordo, identidadeexterna);


--
-- TOC entry 3562 (class 2606 OID 10424305)
-- Name: tb_agenda pk_agenda; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_agenda
    ADD CONSTRAINT pk_agenda PRIMARY KEY (idagenda);


--
-- TOC entry 3564 (class 2606 OID 10424307)
-- Name: tb_aquisicao pk_aquisicao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_aquisicao
    ADD CONSTRAINT pk_aquisicao PRIMARY KEY (idaquisicao);


--
-- TOC entry 3566 (class 2606 OID 10424309)
-- Name: tb_assinadocumento pk_assinadocumento; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_assinadocumento
    ADD CONSTRAINT pk_assinadocumento PRIMARY KEY (id);


--
-- TOC entry 3568 (class 2606 OID 10424311)
-- Name: tb_ata pk_ata; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_ata
    ADD CONSTRAINT pk_ata PRIMARY KEY (idata);


--
-- TOC entry 3570 (class 2606 OID 10424313)
-- Name: tb_atividade pk_atividade; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividade
    ADD CONSTRAINT pk_atividade PRIMARY KEY (idatividade);


--
-- TOC entry 3579 (class 2606 OID 10424315)
-- Name: tb_atividadeocultar pk_atividade_projeto_pessoa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadeocultar
    ADD CONSTRAINT pk_atividade_projeto_pessoa PRIMARY KEY (idatividadecronograma, idprojeto, idpessoa);


--
-- TOC entry 3574 (class 2606 OID 10424317)
-- Name: tb_atividadecronograma pk_atividadecronograma; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadecronograma
    ADD CONSTRAINT pk_atividadecronograma PRIMARY KEY (idatividadecronograma, idprojeto);


--
-- TOC entry 3581 (class 2606 OID 10424319)
-- Name: tb_bloqueioprojeto pk_bloqueio; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_bloqueioprojeto
    ADD CONSTRAINT pk_bloqueio PRIMARY KEY (idbloqueioprojeto);


--
-- TOC entry 3584 (class 2606 OID 10424321)
-- Name: tb_comentario pk_comentario; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_comentario
    ADD CONSTRAINT pk_comentario PRIMARY KEY (idcomentario);


--
-- TOC entry 3586 (class 2606 OID 10424323)
-- Name: tb_comunicacao pk_comunicacao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_comunicacao
    ADD CONSTRAINT pk_comunicacao PRIMARY KEY (idcomunicacao);


--
-- TOC entry 3588 (class 2606 OID 10424325)
-- Name: tb_contramedida pk_contramedida; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_contramedida
    ADD CONSTRAINT pk_contramedida PRIMARY KEY (idcontramedida);


--
-- TOC entry 3590 (class 2606 OID 10424327)
-- Name: tb_diagnostico pk_diagnostico; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_diagnostico
    ADD CONSTRAINT pk_diagnostico PRIMARY KEY (iddiagnostico);


--
-- TOC entry 3592 (class 2606 OID 10424329)
-- Name: tb_diariobordo pk_diariobordo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_diariobordo
    ADD CONSTRAINT pk_diariobordo PRIMARY KEY (iddiariobordo);


--
-- TOC entry 3594 (class 2606 OID 10424331)
-- Name: tb_diautil pk_diautil; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_diautil
    ADD CONSTRAINT pk_diautil PRIMARY KEY (iddiautil);


--
-- TOC entry 3597 (class 2606 OID 10424333)
-- Name: tb_documento pk_documento; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_documento
    ADD CONSTRAINT pk_documento PRIMARY KEY (iddocumento);


--
-- TOC entry 3599 (class 2606 OID 10424335)
-- Name: tb_elementodespesa pk_elementodespesa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_elementodespesa
    ADD CONSTRAINT pk_elementodespesa PRIMARY KEY (idelementodespesa);


--
-- TOC entry 3601 (class 2606 OID 10424337)
-- Name: tb_entidadeexterna pk_entidadeexterna; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_entidadeexterna
    ADD CONSTRAINT pk_entidadeexterna PRIMARY KEY (identidadeexterna);


--
-- TOC entry 3604 (class 2606 OID 10424339)
-- Name: tb_escritorio pk_escritorio; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_escritorio
    ADD CONSTRAINT pk_escritorio PRIMARY KEY (idescritorio);


--
-- TOC entry 3606 (class 2606 OID 10424341)
-- Name: tb_etapa pk_etapa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_etapa
    ADD CONSTRAINT pk_etapa PRIMARY KEY (idetapa);


--
-- TOC entry 3608 (class 2606 OID 10424343)
-- Name: tb_evento pk_evento; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_evento
    ADD CONSTRAINT pk_evento PRIMARY KEY (idevento);


--
-- TOC entry 3610 (class 2606 OID 10424345)
-- Name: tb_eventoavaliacao pk_eventoavaliacao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_eventoavaliacao
    ADD CONSTRAINT pk_eventoavaliacao PRIMARY KEY (ideventoavaliacao);


--
-- TOC entry 3612 (class 2606 OID 10424347)
-- Name: tb_feriado pk_feriados; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_feriado
    ADD CONSTRAINT pk_feriados PRIMARY KEY (diaferiado, mesferiado, anoferiado);


--
-- TOC entry 3616 (class 2606 OID 10424349)
-- Name: tb_frase pk_frase; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_frase
    ADD CONSTRAINT pk_frase PRIMARY KEY (idfrase);


--
-- TOC entry 3618 (class 2606 OID 10424351)
-- Name: tb_frase_pesquisa pk_frasepesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_frase_pesquisa
    ADD CONSTRAINT pk_frasepesquisa PRIMARY KEY (idfrasepesquisa);


--
-- TOC entry 3710 (class 2606 OID 10424353)
-- Name: tb_questionariodiagnostico_respondido pk_historico_questionario; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariodiagnostico_respondido
    ADD CONSTRAINT pk_historico_questionario PRIMARY KEY (idquestionario, iddiagnostico, numero);


--
-- TOC entry 3622 (class 2606 OID 10424355)
-- Name: tb_hst_publicacao pk_hstpublicacao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_hst_publicacao
    ADD CONSTRAINT pk_hstpublicacao PRIMARY KEY (idhistoricopublicacao);


--
-- TOC entry 3624 (class 2606 OID 10424357)
-- Name: tb_item_secao pk_item; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_item_secao
    ADD CONSTRAINT pk_item PRIMARY KEY (id_item);


--
-- TOC entry 3628 (class 2606 OID 10424359)
-- Name: tb_linhatempo pk_linhatempo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_linhatempo
    ADD CONSTRAINT pk_linhatempo PRIMARY KEY (id);


--
-- TOC entry 3630 (class 2606 OID 10424361)
-- Name: tb_logacesso pk_logacesso; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_logacesso
    ADD CONSTRAINT pk_logacesso PRIMARY KEY (idmodulo);


--
-- TOC entry 3634 (class 2606 OID 10424363)
-- Name: tb_marco pk_marco; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_marco
    ADD CONSTRAINT pk_marco PRIMARY KEY (idmarco);


--
-- TOC entry 3636 (class 2606 OID 10424365)
-- Name: tb_modulo pk_modulo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_modulo
    ADD CONSTRAINT pk_modulo PRIMARY KEY (idmodulo);


--
-- TOC entry 3638 (class 2606 OID 10424367)
-- Name: tb_mudanca pk_mudanca; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_mudanca
    ADD CONSTRAINT pk_mudanca PRIMARY KEY (idmudanca);


--
-- TOC entry 3640 (class 2606 OID 10424369)
-- Name: tb_natureza pk_natureza; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_natureza
    ADD CONSTRAINT pk_natureza PRIMARY KEY (idnatureza);


--
-- TOC entry 3642 (class 2606 OID 10424371)
-- Name: tb_objetivo pk_objetivo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_objetivo
    ADD CONSTRAINT pk_objetivo PRIMARY KEY (idobjetivo);


--
-- TOC entry 3644 (class 2606 OID 10424373)
-- Name: tb_opcao_resposta pk_opcao_resposta; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_opcao_resposta
    ADD CONSTRAINT pk_opcao_resposta PRIMARY KEY (idresposta);


--
-- TOC entry 3646 (class 2606 OID 10424375)
-- Name: tb_origemrisco pk_origemrisco; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_origemrisco
    ADD CONSTRAINT pk_origemrisco PRIMARY KEY (idorigemrisco);


--
-- TOC entry 3648 (class 2606 OID 10424377)
-- Name: tb_p_acao pk_p_acao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_p_acao
    ADD CONSTRAINT pk_p_acao PRIMARY KEY (id_p_acao);


--
-- TOC entry 3650 (class 2606 OID 10424379)
-- Name: tb_partediagnostico pk_partediagnostico; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_partediagnostico
    ADD CONSTRAINT pk_partediagnostico PRIMARY KEY (idpartediagnostico);


--
-- TOC entry 3654 (class 2606 OID 10424381)
-- Name: tb_parteinteressada_funcoes pk_parteinteressada_funcoes; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_parteinteressada_funcoes
    ADD CONSTRAINT pk_parteinteressada_funcoes PRIMARY KEY (idparteinteressada, idparteinteressadafuncao);


--
-- TOC entry 3658 (class 2606 OID 10424383)
-- Name: tb_perfil pk_perfil; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_perfil
    ADD CONSTRAINT pk_perfil PRIMARY KEY (idperfil);


--
-- TOC entry 3660 (class 2606 OID 10424385)
-- Name: tb_perfilmodulo pk_perfilmodulo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_perfilmodulo
    ADD CONSTRAINT pk_perfilmodulo PRIMARY KEY (idperfil, idmodulo);


--
-- TOC entry 3663 (class 2606 OID 10424387)
-- Name: tb_perfilpessoa pk_perfilpessoa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_perfilpessoa
    ADD CONSTRAINT pk_perfilpessoa PRIMARY KEY (idperfilpessoa);


--
-- TOC entry 3665 (class 2606 OID 10424389)
-- Name: tb_pergunta pk_pergunta; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_pergunta
    ADD CONSTRAINT pk_pergunta PRIMARY KEY (idpergunta);


--
-- TOC entry 3670 (class 2606 OID 10424391)
-- Name: tb_permissao pk_permissao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissao
    ADD CONSTRAINT pk_permissao PRIMARY KEY (idpermissao);


--
-- TOC entry 3676 (class 2606 OID 10424393)
-- Name: tb_permissaoperfil pk_permissaoperfil; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaoperfil
    ADD CONSTRAINT pk_permissaoperfil PRIMARY KEY (idpermissaoperfil);


--
-- TOC entry 3680 (class 2606 OID 10424395)
-- Name: tb_pesquisa pk_pesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_pesquisa
    ADD CONSTRAINT pk_pesquisa PRIMARY KEY (idpesquisa);


--
-- TOC entry 3683 (class 2606 OID 10424397)
-- Name: tb_pessoa pk_pessoa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_pessoa
    ADD CONSTRAINT pk_pessoa PRIMARY KEY (idpessoa);


--
-- TOC entry 3685 (class 2606 OID 10424399)
-- Name: tb_pessoaagenda pk_pessoaagenda; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_pessoaagenda
    ADD CONSTRAINT pk_pessoaagenda PRIMARY KEY (idagenda, idpessoa);


--
-- TOC entry 3687 (class 2606 OID 10424401)
-- Name: tb_portfolio pk_portfolio; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_portfolio
    ADD CONSTRAINT pk_portfolio PRIMARY KEY (idportfolio);


--
-- TOC entry 3689 (class 2606 OID 10424403)
-- Name: tb_portifolioprograma pk_portifolioprograma; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_portifolioprograma
    ADD CONSTRAINT pk_portifolioprograma PRIMARY KEY (idprograma, idportfolio);


--
-- TOC entry 3691 (class 2606 OID 10424405)
-- Name: tb_processo pk_processo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_processo
    ADD CONSTRAINT pk_processo PRIMARY KEY (idprocesso);


--
-- TOC entry 3693 (class 2606 OID 10424407)
-- Name: tb_programa pk_programa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_programa
    ADD CONSTRAINT pk_programa PRIMARY KEY (idprograma);


--
-- TOC entry 3696 (class 2606 OID 10424409)
-- Name: tb_projeto pk_projeto; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT pk_projeto PRIMARY KEY (idprojeto);


--
-- TOC entry 3698 (class 2606 OID 10424411)
-- Name: tb_projetoprocesso pk_projetoprocesso; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projetoprocesso
    ADD CONSTRAINT pk_projetoprocesso PRIMARY KEY (idprojetoprocesso);


--
-- TOC entry 3701 (class 2606 OID 10424413)
-- Name: tb_questdiagnosticopadronizamelhoria pk_questdiagnosticopadronizamelhoria; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questdiagnosticopadronizamelhoria
    ADD CONSTRAINT pk_questdiagnosticopadronizamelhoria PRIMARY KEY (idpadronizacaomelhoria);


--
-- TOC entry 4504 (class 0 OID 0)
-- Dependencies: 3701
-- Name: CONSTRAINT pk_questdiagnosticopadronizamelhoria ON tb_questdiagnosticopadronizamelhoria; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON CONSTRAINT pk_questdiagnosticopadronizamelhoria ON agepnet200.tb_questdiagnosticopadronizamelhoria IS 'Chave primaria da tabela sequencial.';


--
-- TOC entry 3703 (class 2606 OID 10424415)
-- Name: tb_questionario pk_questionario; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionario
    ADD CONSTRAINT pk_questionario PRIMARY KEY (idquestionario);


--
-- TOC entry 3706 (class 2606 OID 10424417)
-- Name: tb_questionario_diagnostico pk_questionario_diagnostico; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionario_diagnostico
    ADD CONSTRAINT pk_questionario_diagnostico PRIMARY KEY (idquestionariodiagnostico);


--
-- TOC entry 3714 (class 2606 OID 10424419)
-- Name: tb_questionariodiagnosticomelhoria pk_questionariodiagnosticomelhoria; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariodiagnosticomelhoria
    ADD CONSTRAINT pk_questionariodiagnosticomelhoria PRIMARY KEY (idmelhoria);


--
-- TOC entry 4505 (class 0 OID 0)
-- Dependencies: 3714
-- Name: CONSTRAINT pk_questionariodiagnosticomelhoria ON tb_questionariodiagnosticomelhoria; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON CONSTRAINT pk_questionariodiagnosticomelhoria ON agepnet200.tb_questionariodiagnosticomelhoria IS 'Chave primaria da tabela sequencial.';


--
-- TOC entry 3716 (class 2606 OID 10424421)
-- Name: tb_questionariofrase pk_questionariofrase; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariofrase
    ADD CONSTRAINT pk_questionariofrase PRIMARY KEY (idfrase, idquestionario);


--
-- TOC entry 3718 (class 2606 OID 10424423)
-- Name: tb_questionariofrase_pesquisa pk_questionariofrase_pesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariofrase_pesquisa
    ADD CONSTRAINT pk_questionariofrase_pesquisa PRIMARY KEY (idquestionariopesquisa, idfrasepesquisa);


--
-- TOC entry 3708 (class 2606 OID 10424425)
-- Name: tb_questionario_pesquisa pk_questionariopesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionario_pesquisa
    ADD CONSTRAINT pk_questionariopesquisa PRIMARY KEY (idquestionariopesquisa);


--
-- TOC entry 3720 (class 2606 OID 10424427)
-- Name: tb_r3g pk_r3g; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_r3g
    ADD CONSTRAINT pk_r3g PRIMARY KEY (idr3g);


--
-- TOC entry 3724 (class 2606 OID 10424429)
-- Name: tb_resposta pk_resposta; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_resposta
    ADD CONSTRAINT pk_resposta PRIMARY KEY (idresposta);


--
-- TOC entry 3726 (class 2606 OID 10424431)
-- Name: tb_resposta_pergunta pk_resposta_pergunta; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_resposta_pergunta
    ADD CONSTRAINT pk_resposta_pergunta PRIMARY KEY (id_resposta_pergunta);


--
-- TOC entry 3730 (class 2606 OID 10424433)
-- Name: tb_resposta_questionariordiagnostico pk_resposta_questionariorespondido; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_resposta_questionariordiagnostico
    ADD CONSTRAINT pk_resposta_questionariorespondido PRIMARY KEY (id_resposta_pergunta, idquestionario, iddiagnostico, numero);


--
-- TOC entry 3732 (class 2606 OID 10424435)
-- Name: tb_respostafrase_pesquisa pk_respostafrase_pesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_respostafrase_pesquisa
    ADD CONSTRAINT pk_respostafrase_pesquisa PRIMARY KEY (idfrasepesquisa, idrespostapesquisa);


--
-- TOC entry 3728 (class 2606 OID 10424437)
-- Name: tb_resposta_pesquisa pk_respostapesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_resposta_pesquisa
    ADD CONSTRAINT pk_respostapesquisa PRIMARY KEY (idrespostapesquisa);


--
-- TOC entry 3734 (class 2606 OID 10424439)
-- Name: tb_resultado_pesquisa pk_resultadopesquisa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_resultado_pesquisa
    ADD CONSTRAINT pk_resultadopesquisa PRIMARY KEY (id, idresultado, idfrasepesquisa, idquestionariopesquisa);


--
-- TOC entry 3736 (class 2606 OID 10424441)
-- Name: tb_risco pk_risco; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_risco
    ADD CONSTRAINT pk_risco PRIMARY KEY (idrisco);


--
-- TOC entry 3738 (class 2606 OID 10424443)
-- Name: tb_secao pk_secao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_secao
    ADD CONSTRAINT pk_secao PRIMARY KEY (id_secao);


--
-- TOC entry 3740 (class 2606 OID 10424445)
-- Name: tb_setor pk_setor; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_setor
    ADD CONSTRAINT pk_setor PRIMARY KEY (idsetor);


--
-- TOC entry 3742 (class 2606 OID 10424447)
-- Name: tb_statusreport pk_statusreport; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_statusreport
    ADD CONSTRAINT pk_statusreport PRIMARY KEY (idstatusreport);


--
-- TOC entry 3560 (class 2606 OID 10424449)
-- Name: tb_acordoespecieinstrumento pk_tb_acordoespecieinstrumento; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordoespecieinstrumento
    ADD CONSTRAINT pk_tb_acordoespecieinstrumento PRIMARY KEY (idacordoespecieinstrumento);


--
-- TOC entry 3576 (class 2606 OID 10424451)
-- Name: tb_atividadecronopredecessora pk_tb_atividadecronopredecessora; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadecronopredecessora
    ADD CONSTRAINT pk_tb_atividadecronopredecessora PRIMARY KEY (idatividadecronograma, idprojetocronograma, idatividadepredecessora);


--
-- TOC entry 3620 (class 2606 OID 10424453)
-- Name: tb_funcionalidade pk_tb_funcionalidade; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_funcionalidade
    ADD CONSTRAINT pk_tb_funcionalidade PRIMARY KEY (idfuncionalidade);


--
-- TOC entry 3632 (class 2606 OID 10424455)
-- Name: tb_manutencaogepnet pk_tb_manutencaogepnet; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_manutencaogepnet
    ADD CONSTRAINT pk_tb_manutencaogepnet PRIMARY KEY (idmanutencaogepnet);


--
-- TOC entry 3652 (class 2606 OID 10424457)
-- Name: tb_parteinteressada pk_tb_parteinteressada; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_parteinteressada
    ADD CONSTRAINT pk_tb_parteinteressada PRIMARY KEY (idparteinteressada);


--
-- TOC entry 3667 (class 2606 OID 10424459)
-- Name: tb_perm_funcionalidade pk_tb_perm_funcionalidade; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_perm_funcionalidade
    ADD CONSTRAINT pk_tb_perm_funcionalidade PRIMARY KEY (idpermissao, idfuncionalidade);


--
-- TOC entry 3672 (class 2606 OID 10424461)
-- Name: tb_permissaodiagnostico pk_tb_permissaodiagnostico; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaodiagnostico
    ADD CONSTRAINT pk_tb_permissaodiagnostico PRIMARY KEY (idpermissao, iddiagnostico, idpartediagnostico);


--
-- TOC entry 3678 (class 2606 OID 10424463)
-- Name: tb_permissaoprojeto pk_tb_permissaoprojeto; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaoprojeto
    ADD CONSTRAINT pk_tb_permissaoprojeto PRIMARY KEY (idpermissao, idprojeto, idparteinteressada);


--
-- TOC entry 3722 (class 2606 OID 10424465)
-- Name: tb_recurso pk_tb_recurso; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_recurso
    ADD CONSTRAINT pk_tb_recurso PRIMARY KEY (idrecurso);


--
-- TOC entry 3746 (class 2606 OID 10424467)
-- Name: tb_tipoavaliacao pk_tb_tipoavaliacao; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_tipoavaliacao
    ADD CONSTRAINT pk_tb_tipoavaliacao PRIMARY KEY (idtipoavaliacao);


--
-- TOC entry 3748 (class 2606 OID 10424469)
-- Name: tb_tipocontramedida pk_tb_tipocontramedida; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_tipocontramedida
    ADD CONSTRAINT pk_tb_tipocontramedida PRIMARY KEY (idtipocontramedida);


--
-- TOC entry 3744 (class 2606 OID 10424471)
-- Name: tb_tipoacordo pk_tipoacordo; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_tipoacordo
    ADD CONSTRAINT pk_tipoacordo PRIMARY KEY (idtipoacordo);


--
-- TOC entry 3750 (class 2606 OID 10424473)
-- Name: tb_tipodocumento pk_tipodocumento; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_tipodocumento
    ADD CONSTRAINT pk_tipodocumento PRIMARY KEY (idtipodocumento);


--
-- TOC entry 3752 (class 2606 OID 10424475)
-- Name: tb_tipoiniciativa pk_tipoiniciativa; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_tipoiniciativa
    ADD CONSTRAINT pk_tipoiniciativa PRIMARY KEY (idtipoiniciativa);


--
-- TOC entry 3754 (class 2606 OID 10424477)
-- Name: tb_tipomudanca pk_tipomudanca; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_tipomudanca
    ADD CONSTRAINT pk_tipomudanca PRIMARY KEY (idtipomudanca);


--
-- TOC entry 3756 (class 2606 OID 10424479)
-- Name: tb_tiporisco pk_tiporisco; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_tiporisco
    ADD CONSTRAINT pk_tiporisco PRIMARY KEY (idtiporisco);


--
-- TOC entry 3760 (class 2606 OID 10424481)
-- Name: tb_tratamento pk_tratamento; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_tratamento
    ADD CONSTRAINT pk_tratamento PRIMARY KEY (idtratamento);


--
-- TOC entry 3762 (class 2606 OID 10424483)
-- Name: tb_unidade_vinculada pk_unidadevinculada; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_unidade_vinculada
    ADD CONSTRAINT pk_unidadevinculada PRIMARY KEY (idunidade, id_unidadeprincipal, iddiagnostico);


--
-- TOC entry 3764 (class 2606 OID 10424485)
-- Name: tb_vincula_questionario pk_vincula_questionario; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_vincula_questionario
    ADD CONSTRAINT pk_vincula_questionario PRIMARY KEY (idquestionario, iddiagnostico);


--
-- TOC entry 3614 (class 2606 OID 10424487)
-- Name: tb_feriado tb_feriado_idferiado_key; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_feriado
    ADD CONSTRAINT tb_feriado_idferiado_key UNIQUE (idferiado);


--
-- TOC entry 3758 (class 2606 OID 10424489)
-- Name: tb_tiposituacaoprojeto tb_tiposituacaoprojeto_pkey; Type: CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_tiposituacaoprojeto
    ADD CONSTRAINT tb_tiposituacaoprojeto_pkey PRIMARY KEY (idtipo);


--
-- TOC entry 3711 (class 1259 OID 10424490)
-- Name: fki_acaoestrategica; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE INDEX fki_acaoestrategica ON agepnet200.tb_questionariodiagnosticomelhoria USING btree (idacaoestrategica);


--
-- TOC entry 3582 (class 1259 OID 10424491)
-- Name: fki_comentario_pessoa; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE INDEX fki_comentario_pessoa ON agepnet200.tb_comentario USING btree (idpessoa);


--
-- TOC entry 3712 (class 1259 OID 10424492)
-- Name: fki_diagnostico; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE INDEX fki_diagnostico ON agepnet200.tb_questionariodiagnosticomelhoria USING btree (iddiagnostico);


--
-- TOC entry 3704 (class 1259 OID 10424493)
-- Name: fki_pessoa_questionariodiagnostico; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE INDEX fki_pessoa_questionariodiagnostico ON agepnet200.tb_questionario_diagnostico USING btree (idpescadastrador);


--
-- TOC entry 3694 (class 1259 OID 10424494)
-- Name: fki_projeto_tipoiniciativa; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE INDEX fki_projeto_tipoiniciativa ON agepnet200.tb_projeto USING btree (idtipoiniciativa);


--
-- TOC entry 3699 (class 1259 OID 10424495)
-- Name: fki_questionariodiagnosticomelhoria_questdiagnosticopadronizame; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE INDEX fki_questionariodiagnosticomelhoria_questdiagnosticopadronizame ON agepnet200.tb_questdiagnosticopadronizamelhoria USING btree (idmelhoria);


--
-- TOC entry 3602 (class 1259 OID 10424496)
-- Name: id_escritorio; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE UNIQUE INDEX id_escritorio ON agepnet200.tb_escritorio USING btree (nomescritorio2);


--
-- TOC entry 3661 (class 1259 OID 10424497)
-- Name: id_perfil_pessoa; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE UNIQUE INDEX id_perfil_pessoa ON agepnet200.tb_perfilpessoa USING btree (idpessoa, idperfil, idescritorio);


--
-- TOC entry 3673 (class 1259 OID 10424498)
-- Name: id_permissaoperfil; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE UNIQUE INDEX id_permissaoperfil ON agepnet200.tb_permissaoperfil USING btree (idpermissaoperfil);


--
-- TOC entry 3668 (class 1259 OID 10424499)
-- Name: id_recurso; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE UNIQUE INDEX id_recurso ON agepnet200.tb_permissao USING btree (idrecurso, idpermissao);


--
-- TOC entry 3577 (class 1259 OID 10424500)
-- Name: idx_atividade_projeto_pessoa; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE INDEX idx_atividade_projeto_pessoa ON agepnet200.tb_atividadeocultar USING btree (idatividadecronograma, idprojeto, idpessoa);


--
-- TOC entry 3571 (class 1259 OID 10424501)
-- Name: idx_codprojeto_domtipoatividade; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE INDEX idx_codprojeto_domtipoatividade ON agepnet200.tb_atividadecronograma USING btree (idprojeto, domtipoatividade);


--
-- TOC entry 3681 (class 1259 OID 10424502)
-- Name: idx_cpf; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE UNIQUE INDEX idx_cpf ON agepnet200.tb_pessoa USING btree (numcpf);


--
-- TOC entry 3595 (class 1259 OID 10424503)
-- Name: idx_escritorio; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE UNIQUE INDEX idx_escritorio ON agepnet200.tb_documento USING btree (iddocumento, idescritorio);


--
-- TOC entry 3572 (class 1259 OID 10424504)
-- Name: idx_grupo; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE INDEX idx_grupo ON agepnet200.tb_atividadecronograma USING btree (idprojeto, idgrupo);


--
-- TOC entry 3674 (class 1259 OID 10424505)
-- Name: idx_permissaoperfil; Type: INDEX; Schema: agepnet200; Owner: postgres
--

CREATE UNIQUE INDEX idx_permissaoperfil ON agepnet200.tb_permissaoperfil USING btree (idpermissao, idperfil);


--
-- TOC entry 3765 (class 2606 OID 10424506)
-- Name: tb_acao fk_acao_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acao
    ADD CONSTRAINT fk_acao_escritorio FOREIGN KEY (idescritorio) REFERENCES agepnet200.tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3766 (class 2606 OID 10424511)
-- Name: tb_acao fk_acao_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acao
    ADD CONSTRAINT fk_acao_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3866 (class 2606 OID 10424516)
-- Name: tb_projeto fk_acao_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_acao_projeto FOREIGN KEY (idacao) REFERENCES agepnet200.tb_acao(idacao) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3829 (class 2606 OID 10424521)
-- Name: tb_p_acao fk_acao_projetoprocesso; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_p_acao
    ADD CONSTRAINT fk_acao_projetoprocesso FOREIGN KEY (idprojetoprocesso) REFERENCES agepnet200.tb_projetoprocesso(idprojetoprocesso) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3830 (class 2606 OID 10424526)
-- Name: tb_p_acao fk_acao_setorresponsavel; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_p_acao
    ADD CONSTRAINT fk_acao_setorresponsavel FOREIGN KEY (idsetorresponsavel) REFERENCES agepnet200.tb_setor(idsetor) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3887 (class 2606 OID 10424531)
-- Name: tb_questionariodiagnosticomelhoria fk_acaoestrategica_questionariodiagnosticomelhoria; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariodiagnosticomelhoria
    ADD CONSTRAINT fk_acaoestrategica_questionariodiagnosticomelhoria FOREIGN KEY (idacaoestrategica) REFERENCES agepnet200.tb_acao(idacao) ON DELETE RESTRICT;


--
-- TOC entry 3767 (class 2606 OID 10424536)
-- Name: tb_aceiteatividadecronograma fk_aceiteativcronograma_aceite; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_aceiteatividadecronograma
    ADD CONSTRAINT fk_aceiteativcronograma_aceite FOREIGN KEY (idaceite) REFERENCES agepnet200.tb_aceite(idaceite) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3768 (class 2606 OID 10424541)
-- Name: tb_acordo fk_acordo_acordoespecieinstrumento; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordo
    ADD CONSTRAINT fk_acordo_acordoespecieinstrumento FOREIGN KEY (idacordoespecieinstrumento) REFERENCES agepnet200.tb_acordoespecieinstrumento(idacordoespecieinstrumento) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3769 (class 2606 OID 10424546)
-- Name: tb_acordo fk_acordo_acordopai; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordo
    ADD CONSTRAINT fk_acordo_acordopai FOREIGN KEY (idacordopai) REFERENCES agepnet200.tb_acordo(idacordo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3770 (class 2606 OID 10424551)
-- Name: tb_acordo fk_acordo_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordo
    ADD CONSTRAINT fk_acordo_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3771 (class 2606 OID 10424556)
-- Name: tb_acordo fk_acordo_pesfiscal; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordo
    ADD CONSTRAINT fk_acordo_pesfiscal FOREIGN KEY (idfiscal) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3772 (class 2606 OID 10424561)
-- Name: tb_acordo fk_acordo_pesfiscal2; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordo
    ADD CONSTRAINT fk_acordo_pesfiscal2 FOREIGN KEY (idfiscal2) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3773 (class 2606 OID 10424566)
-- Name: tb_acordo fk_acordo_pesfiscal3; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordo
    ADD CONSTRAINT fk_acordo_pesfiscal3 FOREIGN KEY (idfiscal3) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3774 (class 2606 OID 10424571)
-- Name: tb_acordo fk_acordo_pesresponsavelinterino; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordo
    ADD CONSTRAINT fk_acordo_pesresponsavelinterino FOREIGN KEY (idresponsavelinterno) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3775 (class 2606 OID 10424576)
-- Name: tb_acordo fk_acordo_setor; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordo
    ADD CONSTRAINT fk_acordo_setor FOREIGN KEY (idsetor) REFERENCES agepnet200.tb_setor(idsetor) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3776 (class 2606 OID 10424581)
-- Name: tb_acordo fk_acordo_tipoacordo; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordo
    ADD CONSTRAINT fk_acordo_tipoacordo FOREIGN KEY (idtipoacordo) REFERENCES agepnet200.tb_tipoacordo(idtipoacordo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3777 (class 2606 OID 10424586)
-- Name: tb_acordoespecieinstrumento fk_acordoespecieinstrumento_cadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_acordoespecieinstrumento
    ADD CONSTRAINT fk_acordoespecieinstrumento_cadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3778 (class 2606 OID 10424591)
-- Name: tb_agenda fk_agenda_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_agenda
    ADD CONSTRAINT fk_agenda_escritorio FOREIGN KEY (idescritorio) REFERENCES agepnet200.tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3803 (class 2606 OID 10424596)
-- Name: tb_diariobordo fk_alterador_diariobordo; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_diariobordo
    ADD CONSTRAINT fk_alterador_diariobordo FOREIGN KEY (idalterador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3779 (class 2606 OID 10424601)
-- Name: tb_aquisicao fk_aquisicao_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_aquisicao
    ADD CONSTRAINT fk_aquisicao_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3780 (class 2606 OID 10424606)
-- Name: tb_assinadocumento fk_assinadocumento_pessoa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_assinadocumento
    ADD CONSTRAINT fk_assinadocumento_pessoa FOREIGN KEY (idpessoa) REFERENCES agepnet200.tb_pessoa(idpessoa) ON DELETE CASCADE;


--
-- TOC entry 3781 (class 2606 OID 10424611)
-- Name: tb_assinadocumento fk_assinadocumento_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_assinadocumento
    ADD CONSTRAINT fk_assinadocumento_projeto FOREIGN KEY (idprojeto) REFERENCES agepnet200.tb_projeto(idprojeto) ON DELETE CASCADE;


--
-- TOC entry 3782 (class 2606 OID 10424616)
-- Name: tb_assinadocumento fk_assinadocumento_termoaceite; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_assinadocumento
    ADD CONSTRAINT fk_assinadocumento_termoaceite FOREIGN KEY (idaceite) REFERENCES agepnet200.tb_aceite(idaceite) ON DELETE RESTRICT;


--
-- TOC entry 3783 (class 2606 OID 10424621)
-- Name: tb_atividade fk_atividade_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividade
    ADD CONSTRAINT fk_atividade_escritorio FOREIGN KEY (idescritorio) REFERENCES agepnet200.tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3784 (class 2606 OID 10424626)
-- Name: tb_atividade fk_atividade_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividade
    ADD CONSTRAINT fk_atividade_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3785 (class 2606 OID 10424631)
-- Name: tb_atividade fk_atividade_pesresponsavel; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividade
    ADD CONSTRAINT fk_atividade_pesresponsavel FOREIGN KEY (idresponsavel) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3794 (class 2606 OID 10424636)
-- Name: tb_atividadeocultar fk_atividade_projeto_visibilidade; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadeocultar
    ADD CONSTRAINT fk_atividade_projeto_visibilidade FOREIGN KEY (idprojeto, idatividadecronograma) REFERENCES agepnet200.tb_atividadecronograma(idprojeto, idatividadecronograma) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3786 (class 2606 OID 10424641)
-- Name: tb_atividadecronograma fk_atividadecrono_elementodespesa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadecronograma
    ADD CONSTRAINT fk_atividadecrono_elementodespesa FOREIGN KEY (idelementodespesa) REFERENCES agepnet200.tb_elementodespesa(idelementodespesa) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- TOC entry 3787 (class 2606 OID 10424646)
-- Name: tb_atividadecronograma fk_atividadecrono_marcoanterior; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadecronograma
    ADD CONSTRAINT fk_atividadecrono_marcoanterior FOREIGN KEY (idmarcoanterior, idprojeto) REFERENCES agepnet200.tb_atividadecronograma(idatividadecronograma, idprojeto) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3788 (class 2606 OID 10424651)
-- Name: tb_atividadecronograma fk_atividadecrono_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadecronograma
    ADD CONSTRAINT fk_atividadecrono_projeto FOREIGN KEY (idprojeto) REFERENCES agepnet200.tb_projeto(idprojeto) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3804 (class 2606 OID 10424656)
-- Name: tb_diariobordo fk_cadastrador_diariobordo; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_diariobordo
    ADD CONSTRAINT fk_cadastrador_diariobordo FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3796 (class 2606 OID 10424661)
-- Name: tb_comentario fk_comentario_atividadecronograma; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_comentario
    ADD CONSTRAINT fk_comentario_atividadecronograma FOREIGN KEY (idatividadecronograma, idprojeto) REFERENCES agepnet200.tb_atividadecronograma(idatividadecronograma, idprojeto) ON DELETE CASCADE;


--
-- TOC entry 3797 (class 2606 OID 10424666)
-- Name: tb_comentario fk_comentario_pessoa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_comentario
    ADD CONSTRAINT fk_comentario_pessoa FOREIGN KEY (idpessoa) REFERENCES agepnet200.tb_pessoa(idpessoa) ON DELETE CASCADE;


--
-- TOC entry 3798 (class 2606 OID 10424671)
-- Name: tb_comentario fk_comentario_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_comentario
    ADD CONSTRAINT fk_comentario_projeto FOREIGN KEY (idprojeto) REFERENCES agepnet200.tb_projeto(idprojeto) ON DELETE CASCADE;


--
-- TOC entry 3799 (class 2606 OID 10424676)
-- Name: tb_comunicacao fk_cominicacao_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_comunicacao
    ADD CONSTRAINT fk_cominicacao_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3801 (class 2606 OID 10424681)
-- Name: tb_contramedida fk_contramedida_risco; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_contramedida
    ADD CONSTRAINT fk_contramedida_risco FOREIGN KEY (idrisco) REFERENCES agepnet200.tb_risco(idrisco) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- TOC entry 3800 (class 2606 OID 10424686)
-- Name: tb_comunicacao fk_conunicacao_parteinteressada; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_comunicacao
    ADD CONSTRAINT fk_conunicacao_parteinteressada FOREIGN KEY (idresponsavel) REFERENCES agepnet200.tb_parteinteressada(idparteinteressada) ON DELETE RESTRICT;


--
-- TOC entry 3792 (class 2606 OID 10424691)
-- Name: tb_atividadecronopredecessora fk_cronpredecessora_cronograma; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadecronopredecessora
    ADD CONSTRAINT fk_cronpredecessora_cronograma FOREIGN KEY (idatividadecronograma, idprojetocronograma) REFERENCES agepnet200.tb_atividadecronograma(idatividadecronograma, idprojeto) ON DELETE CASCADE;


--
-- TOC entry 3793 (class 2606 OID 10424696)
-- Name: tb_atividadecronopredecessora fk_cronpredecessora_predecessora; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadecronopredecessora
    ADD CONSTRAINT fk_cronpredecessora_predecessora FOREIGN KEY (idatividadepredecessora, idprojetocronograma) REFERENCES agepnet200.tb_atividadecronograma(idatividadecronograma, idprojeto) ON DELETE CASCADE;


--
-- TOC entry 3888 (class 2606 OID 10424701)
-- Name: tb_questionariodiagnosticomelhoria fk_diagnostico_questionariodiagnosticomelhoria; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariodiagnosticomelhoria
    ADD CONSTRAINT fk_diagnostico_questionariodiagnosticomelhoria FOREIGN KEY (iddiagnostico) REFERENCES agepnet200.tb_diagnostico(iddiagnostico) ON DELETE CASCADE;


--
-- TOC entry 4506 (class 0 OID 0)
-- Dependencies: 3888
-- Name: CONSTRAINT fk_diagnostico_questionariodiagnosticomelhoria ON tb_questionariodiagnosticomelhoria; Type: COMMENT; Schema: agepnet200; Owner: postgres
--

COMMENT ON CONSTRAINT fk_diagnostico_questionariodiagnosticomelhoria ON agepnet200.tb_questionariodiagnosticomelhoria IS 'Chave com a tabela de diagnostico.';


--
-- TOC entry 3918 (class 2606 OID 10424706)
-- Name: tb_vincula_questionario fk_diagnostico_vinculaquestionario; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_vincula_questionario
    ADD CONSTRAINT fk_diagnostico_vinculaquestionario FOREIGN KEY (iddiagnostico) REFERENCES agepnet200.tb_diagnostico(iddiagnostico) ON DELETE CASCADE;


--
-- TOC entry 3805 (class 2606 OID 10424711)
-- Name: tb_diariobordo fk_diariobordo_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_diariobordo
    ADD CONSTRAINT fk_diariobordo_projeto FOREIGN KEY (idprojeto) REFERENCES agepnet200.tb_projeto(idprojeto) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3806 (class 2606 OID 10424716)
-- Name: tb_documento fk_documento_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_documento
    ADD CONSTRAINT fk_documento_escritorio FOREIGN KEY (idescritorio) REFERENCES agepnet200.tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3807 (class 2606 OID 10424721)
-- Name: tb_documento fk_documento_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_documento
    ADD CONSTRAINT fk_documento_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3808 (class 2606 OID 10424726)
-- Name: tb_documento fk_documento_tipodocumento; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_documento
    ADD CONSTRAINT fk_documento_tipodocumento FOREIGN KEY (idtipodocumento) REFERENCES agepnet200.tb_tipodocumento(idtipodocumento) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3809 (class 2606 OID 10424731)
-- Name: tb_entidadeexterna fk_entidadeexterna_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_entidadeexterna
    ADD CONSTRAINT fk_entidadeexterna_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3810 (class 2606 OID 10424736)
-- Name: tb_escritorio fk_escritorio_escritoriopai; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_escritorio
    ADD CONSTRAINT fk_escritorio_escritoriopai FOREIGN KEY (idescritoriope) REFERENCES agepnet200.tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3811 (class 2606 OID 10424741)
-- Name: tb_eventoavaliacao fk_eventoavaliacao_evento; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_eventoavaliacao
    ADD CONSTRAINT fk_eventoavaliacao_evento FOREIGN KEY (idevento) REFERENCES agepnet200.tb_evento(idevento) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3812 (class 2606 OID 10424746)
-- Name: tb_eventoavaliacao fk_eventoavaliacao_tipoavaliacao; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_eventoavaliacao
    ADD CONSTRAINT fk_eventoavaliacao_tipoavaliacao FOREIGN KEY (idtipoavaliacao) REFERENCES agepnet200.tb_tipoavaliacao(idtipoavaliacao) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3813 (class 2606 OID 10424751)
-- Name: tb_frase fk_frase_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_frase
    ADD CONSTRAINT fk_frase_escritorio FOREIGN KEY (idescritorio) REFERENCES agepnet200.tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3903 (class 2606 OID 10424756)
-- Name: tb_respostafrase fk_frase_pergunta; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_respostafrase
    ADD CONSTRAINT fk_frase_pergunta FOREIGN KEY (idfrase) REFERENCES agepnet200.tb_frase(idfrase) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3814 (class 2606 OID 10424761)
-- Name: tb_frase_pesquisa fk_frasepesquisa_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_frase_pesquisa
    ADD CONSTRAINT fk_frasepesquisa_escritorio FOREIGN KEY (idescritorio) REFERENCES agepnet200.tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3907 (class 2606 OID 10424766)
-- Name: tb_resultado_pesquisa fk_frasepesquisa_frase; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_resultado_pesquisa
    ADD CONSTRAINT fk_frasepesquisa_frase FOREIGN KEY (idfrasepesquisa) REFERENCES agepnet200.tb_frase_pesquisa(idfrasepesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3905 (class 2606 OID 10424771)
-- Name: tb_respostafrase_pesquisa fk_fraseresultadopesquisa_frase; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_respostafrase_pesquisa
    ADD CONSTRAINT fk_fraseresultadopesquisa_frase FOREIGN KEY (idfrasepesquisa) REFERENCES agepnet200.tb_frase_pesquisa(idfrasepesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3906 (class 2606 OID 10424776)
-- Name: tb_respostafrase_pesquisa fk_fraseresultadopesquisa_resultado; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_respostafrase_pesquisa
    ADD CONSTRAINT fk_fraseresultadopesquisa_resultado FOREIGN KEY (idrespostapesquisa) REFERENCES agepnet200.tb_resposta_pesquisa(idrespostapesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3789 (class 2606 OID 10424781)
-- Name: tb_atividadecronograma fk_grupo_atividade; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadecronograma
    ADD CONSTRAINT fk_grupo_atividade FOREIGN KEY (idgrupo, idprojeto) REFERENCES agepnet200.tb_atividadecronograma(idatividadecronograma, idprojeto) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- TOC entry 3815 (class 2606 OID 10424786)
-- Name: tb_hst_publicacao fk_historicopesquisa_pesquisa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_hst_publicacao
    ADD CONSTRAINT fk_historicopesquisa_pesquisa FOREIGN KEY (idpesquisa) REFERENCES agepnet200.tb_pesquisa(idpesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3816 (class 2606 OID 10424791)
-- Name: tb_item_secao fk_idquestionariodiagnostico; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_item_secao
    ADD CONSTRAINT fk_idquestionariodiagnostico FOREIGN KEY (idquestionariodiagnostico) REFERENCES agepnet200.tb_questionario_diagnostico(idquestionariodiagnostico) ON DELETE CASCADE;


--
-- TOC entry 3817 (class 2606 OID 10424796)
-- Name: tb_item_secao fk_item_secao; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_item_secao
    ADD CONSTRAINT fk_item_secao FOREIGN KEY (id_secao) REFERENCES agepnet200.tb_secao(id_secao) ON DELETE CASCADE;


--
-- TOC entry 3818 (class 2606 OID 10424801)
-- Name: tb_linhatempo fk_linhatempo_pessoa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_linhatempo
    ADD CONSTRAINT fk_linhatempo_pessoa FOREIGN KEY (idpessoa) REFERENCES agepnet200.tb_pessoa(idpessoa) ON DELETE RESTRICT;


--
-- TOC entry 3819 (class 2606 OID 10424806)
-- Name: tb_linhatempo fk_linhatempo_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_linhatempo
    ADD CONSTRAINT fk_linhatempo_projeto FOREIGN KEY (idprojeto) REFERENCES agepnet200.tb_projeto(idprojeto) ON DELETE RESTRICT;


--
-- TOC entry 3820 (class 2606 OID 10424811)
-- Name: tb_linhatempo fk_linhatempo_recurso; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_linhatempo
    ADD CONSTRAINT fk_linhatempo_recurso FOREIGN KEY (idrecurso) REFERENCES agepnet200.tb_recurso(idrecurso);


--
-- TOC entry 3821 (class 2606 OID 10424816)
-- Name: tb_marco fk_marco_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_marco
    ADD CONSTRAINT fk_marco_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3822 (class 2606 OID 10424821)
-- Name: tb_marco fk_marco_pesresponsavel; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_marco
    ADD CONSTRAINT fk_marco_pesresponsavel FOREIGN KEY (idresponsavel) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3823 (class 2606 OID 10424826)
-- Name: tb_modulo fk_modulo_modulopai; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_modulo
    ADD CONSTRAINT fk_modulo_modulopai FOREIGN KEY (idmodulopai) REFERENCES agepnet200.tb_modulo(idmodulo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3824 (class 2606 OID 10424831)
-- Name: tb_mudanca fk_mudanca_tipomudanca; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_mudanca
    ADD CONSTRAINT fk_mudanca_tipomudanca FOREIGN KEY (idtipomudanca) REFERENCES agepnet200.tb_tipomudanca(idtipomudanca) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3825 (class 2606 OID 10424836)
-- Name: tb_objetivo fk_objetivo_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_objetivo
    ADD CONSTRAINT fk_objetivo_escritorio FOREIGN KEY (codescritorio) REFERENCES agepnet200.tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3826 (class 2606 OID 10424841)
-- Name: tb_objetivo fk_objetivo_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_objetivo
    ADD CONSTRAINT fk_objetivo_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3889 (class 2606 OID 10424846)
-- Name: tb_questionariodiagnosticomelhoria fk_objetivoinstitucional_questionariodiagnosticomelhoria; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariodiagnosticomelhoria
    ADD CONSTRAINT fk_objetivoinstitucional_questionariodiagnosticomelhoria FOREIGN KEY (idobjetivoinstitucional) REFERENCES agepnet200.tb_objetivo(idobjetivo) ON DELETE RESTRICT;


--
-- TOC entry 3898 (class 2606 OID 10424851)
-- Name: tb_resposta_pergunta fk_opcaoresposta_resposta_pergunta; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_resposta_pergunta
    ADD CONSTRAINT fk_opcaoresposta_resposta_pergunta FOREIGN KEY (idresposta) REFERENCES agepnet200.tb_opcao_resposta(idresposta) ON DELETE CASCADE;


--
-- TOC entry 3831 (class 2606 OID 10424856)
-- Name: tb_partediagnostico fk_partediagnostico_diagnostico; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_partediagnostico
    ADD CONSTRAINT fk_partediagnostico_diagnostico FOREIGN KEY (iddiagnostico) REFERENCES agepnet200.tb_diagnostico(iddiagnostico) ON DELETE CASCADE;


--
-- TOC entry 3835 (class 2606 OID 10424861)
-- Name: tb_parteinteressada_funcoes fk_parteinteressada_funcoes_funcao; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_parteinteressada_funcoes
    ADD CONSTRAINT fk_parteinteressada_funcoes_funcao FOREIGN KEY (idparteinteressadafuncao) REFERENCES agepnet200.tb_parteinteressadafuncao(idparteinteressadafuncao);


--
-- TOC entry 3836 (class 2606 OID 10424866)
-- Name: tb_parteinteressada_funcoes fk_parteinteressada_funcoes_parte; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_parteinteressada_funcoes
    ADD CONSTRAINT fk_parteinteressada_funcoes_parte FOREIGN KEY (idparteinteressada) REFERENCES agepnet200.tb_parteinteressada(idparteinteressada) ON DELETE RESTRICT;


--
-- TOC entry 3851 (class 2606 OID 10424871)
-- Name: tb_permissaoperfil fk_perfil_permissaoperfil; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaoperfil
    ADD CONSTRAINT fk_perfil_permissaoperfil FOREIGN KEY (idperfil) REFERENCES agepnet200.tb_perfil(idperfil) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3837 (class 2606 OID 10424876)
-- Name: tb_perfilmodulo fk_perfilmodulo_modulo; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_perfilmodulo
    ADD CONSTRAINT fk_perfilmodulo_modulo FOREIGN KEY (idmodulo) REFERENCES agepnet200.tb_modulo(idmodulo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3838 (class 2606 OID 10424881)
-- Name: tb_perfilmodulo fk_perfilmodulo_perfil; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_perfilmodulo
    ADD CONSTRAINT fk_perfilmodulo_perfil FOREIGN KEY (idperfil) REFERENCES agepnet200.tb_perfil(idperfil) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3839 (class 2606 OID 10424886)
-- Name: tb_perfilpessoa fk_perfilpessoa_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_perfilpessoa
    ADD CONSTRAINT fk_perfilpessoa_escritorio FOREIGN KEY (idescritorio) REFERENCES agepnet200.tb_escritorio(idescritorio);


--
-- TOC entry 3827 (class 2606 OID 10424891)
-- Name: tb_opcao_resposta fk_pergunta; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_opcao_resposta
    ADD CONSTRAINT fk_pergunta FOREIGN KEY (idpergunta) REFERENCES agepnet200.tb_pergunta(idpergunta) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- TOC entry 3904 (class 2606 OID 10424896)
-- Name: tb_respostafrase fk_pergunta_frase; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_respostafrase
    ADD CONSTRAINT fk_pergunta_frase FOREIGN KEY (idresposta) REFERENCES agepnet200.tb_resposta(idresposta) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3899 (class 2606 OID 10424901)
-- Name: tb_resposta_pergunta fk_pergunta_resposta_pergunta; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_resposta_pergunta
    ADD CONSTRAINT fk_pergunta_resposta_pergunta FOREIGN KEY (idpergunta) REFERENCES agepnet200.tb_pergunta(idpergunta) ON DELETE CASCADE;


--
-- TOC entry 3841 (class 2606 OID 10424906)
-- Name: tb_pergunta fk_pergunta_secao; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_pergunta
    ADD CONSTRAINT fk_pergunta_secao FOREIGN KEY (id_secao) REFERENCES agepnet200.tb_secao(id_secao) ON DELETE RESTRICT;


--
-- TOC entry 3843 (class 2606 OID 10424911)
-- Name: tb_perm_funcionalidade fk_permfuncionalidade_funcionalidade; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_perm_funcionalidade
    ADD CONSTRAINT fk_permfuncionalidade_funcionalidade FOREIGN KEY (idfuncionalidade) REFERENCES agepnet200.tb_funcionalidade(idfuncionalidade) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3844 (class 2606 OID 10424916)
-- Name: tb_perm_funcionalidade fk_permfuncionalidade_permissao; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_perm_funcionalidade
    ADD CONSTRAINT fk_permfuncionalidade_permissao FOREIGN KEY (idpermissao) REFERENCES agepnet200.tb_permissao(idpermissao);


--
-- TOC entry 3852 (class 2606 OID 10424921)
-- Name: tb_permissaoperfil fk_permissao_permissaoperfil; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaoperfil
    ADD CONSTRAINT fk_permissao_permissaoperfil FOREIGN KEY (idpermissao) REFERENCES agepnet200.tb_permissao(idpermissao);


--
-- TOC entry 3846 (class 2606 OID 10424926)
-- Name: tb_permissaodiagnostico fk_permpdiagnostico_diagnostico; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaodiagnostico
    ADD CONSTRAINT fk_permpdiagnostico_diagnostico FOREIGN KEY (iddiagnostico) REFERENCES agepnet200.tb_diagnostico(iddiagnostico) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- TOC entry 3847 (class 2606 OID 10424931)
-- Name: tb_permissaodiagnostico fk_permpdiagnostico_partediagnostico; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaodiagnostico
    ADD CONSTRAINT fk_permpdiagnostico_partediagnostico FOREIGN KEY (idpartediagnostico) REFERENCES agepnet200.tb_partediagnostico(idpartediagnostico) ON DELETE CASCADE;


--
-- TOC entry 3848 (class 2606 OID 10424936)
-- Name: tb_permissaodiagnostico fk_permpdiagnostico_permissao; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaodiagnostico
    ADD CONSTRAINT fk_permpdiagnostico_permissao FOREIGN KEY (idpermissao) REFERENCES agepnet200.tb_permissao(idpermissao) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3849 (class 2606 OID 10424941)
-- Name: tb_permissaodiagnostico fk_permpdiagnostico_pesmanipula; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaodiagnostico
    ADD CONSTRAINT fk_permpdiagnostico_pesmanipula FOREIGN KEY (idpessoa) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3850 (class 2606 OID 10424946)
-- Name: tb_permissaodiagnostico fk_permpdiagnostico_recurso; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaodiagnostico
    ADD CONSTRAINT fk_permpdiagnostico_recurso FOREIGN KEY (idrecurso) REFERENCES agepnet200.tb_recurso(idrecurso) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3853 (class 2606 OID 10424951)
-- Name: tb_permissaoprojeto fk_permprojeto_parteinteressada; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaoprojeto
    ADD CONSTRAINT fk_permprojeto_parteinteressada FOREIGN KEY (idparteinteressada) REFERENCES agepnet200.tb_parteinteressada(idparteinteressada) ON DELETE CASCADE;


--
-- TOC entry 3854 (class 2606 OID 10424956)
-- Name: tb_permissaoprojeto fk_permprojeto_permissao; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaoprojeto
    ADD CONSTRAINT fk_permprojeto_permissao FOREIGN KEY (idpermissao) REFERENCES agepnet200.tb_permissao(idpermissao) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3855 (class 2606 OID 10424961)
-- Name: tb_permissaoprojeto fk_permprojeto_pesmanipula; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaoprojeto
    ADD CONSTRAINT fk_permprojeto_pesmanipula FOREIGN KEY (idpessoa) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3856 (class 2606 OID 10424966)
-- Name: tb_permissaoprojeto fk_permprojeto_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaoprojeto
    ADD CONSTRAINT fk_permprojeto_projeto FOREIGN KEY (idprojeto) REFERENCES agepnet200.tb_projeto(idprojeto) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3857 (class 2606 OID 10424971)
-- Name: tb_permissaoprojeto fk_permprojeto_recurso; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissaoprojeto
    ADD CONSTRAINT fk_permprojeto_recurso FOREIGN KEY (idrecurso) REFERENCES agepnet200.tb_recurso(idrecurso) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3858 (class 2606 OID 10424976)
-- Name: tb_pesquisa fk_pesquisaquestionario_questionario; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_pesquisa
    ADD CONSTRAINT fk_pesquisaquestionario_questionario FOREIGN KEY (idquestionario) REFERENCES agepnet200.tb_questionario(idquestionario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3832 (class 2606 OID 10424981)
-- Name: tb_parteinteressada fk_pessoaCadastra; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_parteinteressada
    ADD CONSTRAINT "fk_pessoaCadastra" FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa);


--
-- TOC entry 3795 (class 2606 OID 10424986)
-- Name: tb_atividadeocultar fk_pessoa_ocultar; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadeocultar
    ADD CONSTRAINT fk_pessoa_ocultar FOREIGN KEY (idpessoa) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3859 (class 2606 OID 10424991)
-- Name: tb_pessoa fk_pessoa_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_pessoa
    ADD CONSTRAINT fk_pessoa_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3883 (class 2606 OID 10424996)
-- Name: tb_questionario_diagnostico fk_pessoa_questionariodiagnostico; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionario_diagnostico
    ADD CONSTRAINT fk_pessoa_questionariodiagnostico FOREIGN KEY (idpescadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON DELETE RESTRICT;


--
-- TOC entry 3919 (class 2606 OID 10425001)
-- Name: tb_vincula_questionario fk_pessoa_vinculaquestionario; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_vincula_questionario
    ADD CONSTRAINT fk_pessoa_vinculaquestionario FOREIGN KEY (idpesdisponibiliza) REFERENCES agepnet200.tb_pessoa(idpessoa) ON DELETE RESTRICT;


--
-- TOC entry 3867 (class 2606 OID 10425006)
-- Name: tb_projeto fk_pessoaadjunto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_pessoaadjunto FOREIGN KEY (idgerenteadjunto) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3860 (class 2606 OID 10425011)
-- Name: tb_pessoaagenda fk_pessoaagenda_agenda; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_pessoaagenda
    ADD CONSTRAINT fk_pessoaagenda_agenda FOREIGN KEY (idagenda) REFERENCES agepnet200.tb_agenda(idagenda) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3868 (class 2606 OID 10425016)
-- Name: tb_projeto fk_pessoademandante; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_pessoademandante FOREIGN KEY (iddemandante) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3920 (class 2606 OID 10425021)
-- Name: tb_vincula_questionario fk_pessoaencerra_vinculaquestionario; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_vincula_questionario
    ADD CONSTRAINT fk_pessoaencerra_vinculaquestionario FOREIGN KEY (idpesencerrou) REFERENCES agepnet200.tb_pessoa(idpessoa) ON DELETE RESTRICT;


--
-- TOC entry 3869 (class 2606 OID 10425026)
-- Name: tb_projeto fk_pessoagerenteprojeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_pessoagerenteprojeto FOREIGN KEY (idgerenteprojeto) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3833 (class 2606 OID 10425031)
-- Name: tb_parteinteressada fk_pessoainterna; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_parteinteressada
    ADD CONSTRAINT fk_pessoainterna FOREIGN KEY (idpessoainterna) REFERENCES agepnet200.tb_pessoa(idpessoa);


--
-- TOC entry 3870 (class 2606 OID 10425036)
-- Name: tb_projeto fk_pessoapatrocinador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_pessoapatrocinador FOREIGN KEY (idpatrocinador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3840 (class 2606 OID 10425041)
-- Name: tb_perfilpessoa fk_pessoaperfil_perfil; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_perfilpessoa
    ADD CONSTRAINT fk_pessoaperfil_perfil FOREIGN KEY (idperfil) REFERENCES agepnet200.tb_perfil(idperfil) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3861 (class 2606 OID 10425046)
-- Name: tb_portfolio fk_portfolio_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_portfolio
    ADD CONSTRAINT fk_portfolio_escritorio FOREIGN KEY (idescritorio) REFERENCES agepnet200.tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3862 (class 2606 OID 10425051)
-- Name: tb_portfolio fk_portfolio_portfoliopai; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_portfolio
    ADD CONSTRAINT fk_portfolio_portfoliopai FOREIGN KEY (idportfoliopai) REFERENCES agepnet200.tb_portfolio(idportfolio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3863 (class 2606 OID 10425056)
-- Name: tb_portifolioprograma fk_portifolioprograma_portifolio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_portifolioprograma
    ADD CONSTRAINT fk_portifolioprograma_portifolio FOREIGN KEY (idportfolio) REFERENCES agepnet200.tb_portfolio(idportfolio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3864 (class 2606 OID 10425061)
-- Name: tb_portifolioprograma fk_portifolioprograma_programa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_portifolioprograma
    ADD CONSTRAINT fk_portifolioprograma_programa FOREIGN KEY (idprograma) REFERENCES agepnet200.tb_programa(idprograma) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3865 (class 2606 OID 10425066)
-- Name: tb_processo fk_processo_setor; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_processo
    ADD CONSTRAINT fk_processo_setor FOREIGN KEY (idsetor) REFERENCES agepnet200.tb_setor(idsetor) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3890 (class 2606 OID 10425071)
-- Name: tb_questionariodiagnosticomelhoria fk_processomelhorar_questionariodiagnosticomelhoria; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariodiagnosticomelhoria
    ADD CONSTRAINT fk_processomelhorar_questionariodiagnosticomelhoria FOREIGN KEY (idmacroprocessomelhorar) REFERENCES agepnet200.tb_processo(idprocesso) ON DELETE RESTRICT;


--
-- TOC entry 3891 (class 2606 OID 10425076)
-- Name: tb_questionariodiagnosticomelhoria fk_processotrabalho_questionariodiagnosticomelhoria; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariodiagnosticomelhoria
    ADD CONSTRAINT fk_processotrabalho_questionariodiagnosticomelhoria FOREIGN KEY (idmacroprocessotrabalho) REFERENCES agepnet200.tb_processo(idprocesso) ON DELETE RESTRICT;


--
-- TOC entry 3834 (class 2606 OID 10425081)
-- Name: tb_parteinteressada fk_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_parteinteressada
    ADD CONSTRAINT fk_projeto FOREIGN KEY (idprojeto) REFERENCES agepnet200.tb_projeto(idprojeto) ON DELETE RESTRICT;


--
-- TOC entry 3871 (class 2606 OID 10425086)
-- Name: tb_projeto fk_projeto_natureza; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_projeto_natureza FOREIGN KEY (idnatureza) REFERENCES agepnet200.tb_natureza(idnatureza) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3872 (class 2606 OID 10425091)
-- Name: tb_projeto fk_projeto_objetivo; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_projeto_objetivo FOREIGN KEY (idobjetivo) REFERENCES agepnet200.tb_objetivo(idobjetivo) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3873 (class 2606 OID 10425096)
-- Name: tb_projeto fk_projeto_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_projeto_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3874 (class 2606 OID 10425101)
-- Name: tb_projeto fk_projeto_pespatrocinador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_projeto_pespatrocinador FOREIGN KEY (idpatrocinador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3875 (class 2606 OID 10425106)
-- Name: tb_projeto fk_projeto_pessoagerente; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_projeto_pessoagerente FOREIGN KEY (idgerenteprojeto) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3876 (class 2606 OID 10425111)
-- Name: tb_projeto fk_projeto_portfolio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_projeto_portfolio FOREIGN KEY (idportfolio) REFERENCES agepnet200.tb_portfolio(idportfolio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3877 (class 2606 OID 10425116)
-- Name: tb_projeto fk_projeto_programa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_projeto_programa FOREIGN KEY (idprograma) REFERENCES agepnet200.tb_programa(idprograma) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3878 (class 2606 OID 10425121)
-- Name: tb_projeto fk_projeto_setor; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_projeto_setor FOREIGN KEY (idsetor) REFERENCES agepnet200.tb_setor(idsetor) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3879 (class 2606 OID 10425126)
-- Name: tb_projeto fk_projeto_tipoiniciativa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projeto
    ADD CONSTRAINT fk_projeto_tipoiniciativa FOREIGN KEY (idtipoiniciativa) REFERENCES agepnet200.tb_tipoiniciativa(idtipoiniciativa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3880 (class 2606 OID 10425131)
-- Name: tb_projetoprocesso fk_projetoprocesso_processo; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_projetoprocesso
    ADD CONSTRAINT fk_projetoprocesso_processo FOREIGN KEY (idprocesso) REFERENCES agepnet200.tb_processo(idprocesso) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3882 (class 2606 OID 10425136)
-- Name: tb_questionario fk_questionario_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionario
    ADD CONSTRAINT fk_questionario_escritorio FOREIGN KEY (idescritorio) REFERENCES agepnet200.tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3828 (class 2606 OID 10425141)
-- Name: tb_opcao_resposta fk_questionario_opcaoresposta; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_opcao_resposta
    ADD CONSTRAINT fk_questionario_opcaoresposta FOREIGN KEY (idquestionario) REFERENCES agepnet200.tb_questionario_diagnostico(idquestionariodiagnostico) ON DELETE CASCADE;


--
-- TOC entry 3842 (class 2606 OID 10425146)
-- Name: tb_pergunta fk_questionario_pergunta; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_pergunta
    ADD CONSTRAINT fk_questionario_pergunta FOREIGN KEY (idquestionario) REFERENCES agepnet200.tb_questionario_diagnostico(idquestionariodiagnostico) ON DELETE CASCADE;


--
-- TOC entry 3921 (class 2606 OID 10425151)
-- Name: tb_vincula_questionario fk_questionario_vinculaquestionario; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_vincula_questionario
    ADD CONSTRAINT fk_questionario_vinculaquestionario FOREIGN KEY (idquestionario) REFERENCES agepnet200.tb_questionario_diagnostico(idquestionariodiagnostico) ON DELETE CASCADE;


--
-- TOC entry 3881 (class 2606 OID 10425156)
-- Name: tb_questdiagnosticopadronizamelhoria fk_questionariodiagnosticomelhoria_questdiagnosticopadronizamel; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questdiagnosticopadronizamelhoria
    ADD CONSTRAINT fk_questionariodiagnosticomelhoria_questdiagnosticopadronizamel FOREIGN KEY (idmelhoria) REFERENCES agepnet200.tb_questionariodiagnosticomelhoria(idmelhoria) ON DELETE CASCADE;


--
-- TOC entry 3894 (class 2606 OID 10425161)
-- Name: tb_questionariofrase fk_questionariofrase_frase; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariofrase
    ADD CONSTRAINT fk_questionariofrase_frase FOREIGN KEY (idfrase) REFERENCES agepnet200.tb_frase(idfrase) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3895 (class 2606 OID 10425166)
-- Name: tb_questionariofrase fk_questionariofrase_questionario; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariofrase
    ADD CONSTRAINT fk_questionariofrase_questionario FOREIGN KEY (idquestionario) REFERENCES agepnet200.tb_questionario(idquestionario) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3896 (class 2606 OID 10425171)
-- Name: tb_questionariofrase_pesquisa fk_questionariofrasepesquisa_frase; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariofrase_pesquisa
    ADD CONSTRAINT fk_questionariofrasepesquisa_frase FOREIGN KEY (idfrasepesquisa) REFERENCES agepnet200.tb_frase_pesquisa(idfrasepesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3897 (class 2606 OID 10425176)
-- Name: tb_questionariofrase_pesquisa fk_questionariofrasepesquisa_quest; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariofrase_pesquisa
    ADD CONSTRAINT fk_questionariofrasepesquisa_quest FOREIGN KEY (idquestionariopesquisa) REFERENCES agepnet200.tb_questionario_pesquisa(idquestionariopesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3884 (class 2606 OID 10425181)
-- Name: tb_questionario_pesquisa fk_questionariopesquisa_escritorio; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionario_pesquisa
    ADD CONSTRAINT fk_questionariopesquisa_escritorio FOREIGN KEY (idescritorio) REFERENCES agepnet200.tb_escritorio(idescritorio) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3885 (class 2606 OID 10425186)
-- Name: tb_questionario_pesquisa fk_questionariopesquisa_pesquisa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionario_pesquisa
    ADD CONSTRAINT fk_questionariopesquisa_pesquisa FOREIGN KEY (idpesquisa) REFERENCES agepnet200.tb_pesquisa(idpesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3908 (class 2606 OID 10425191)
-- Name: tb_resultado_pesquisa fk_questionariopesquisa_questionario; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_resultado_pesquisa
    ADD CONSTRAINT fk_questionariopesquisa_questionario FOREIGN KEY (idquestionariopesquisa) REFERENCES agepnet200.tb_questionario_pesquisa(idquestionariopesquisa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3900 (class 2606 OID 10425196)
-- Name: tb_resposta_pergunta fk_questionariorespondido_respostapergunta; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_resposta_pergunta
    ADD CONSTRAINT fk_questionariorespondido_respostapergunta FOREIGN KEY (idquestionario, nrquestionario, iddiagnostico) REFERENCES agepnet200.tb_questionariodiagnostico_respondido(idquestionario, numero, iddiagnostico) ON DELETE CASCADE;


--
-- TOC entry 3901 (class 2606 OID 10425201)
-- Name: tb_resposta_questionariordiagnostico fk_questionariorespondido_respostaquestionariorespondido; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_resposta_questionariordiagnostico
    ADD CONSTRAINT fk_questionariorespondido_respostaquestionariorespondido FOREIGN KEY (idquestionario, iddiagnostico, numero) REFERENCES agepnet200.tb_questionariodiagnostico_respondido(idquestionario, iddiagnostico, numero) ON DELETE CASCADE;


--
-- TOC entry 3886 (class 2606 OID 10425206)
-- Name: tb_questionariodiagnostico_respondido fk_questionariovinculado_questionariorespondido; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariodiagnostico_respondido
    ADD CONSTRAINT fk_questionariovinculado_questionariorespondido FOREIGN KEY (idquestionario, iddiagnostico) REFERENCES agepnet200.tb_vincula_questionario(idquestionario, iddiagnostico) ON DELETE CASCADE;


--
-- TOC entry 3845 (class 2606 OID 10425211)
-- Name: tb_permissao fk_recurso_permissao; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_permissao
    ADD CONSTRAINT fk_recurso_permissao FOREIGN KEY (idrecurso) REFERENCES agepnet200.tb_recurso(idrecurso);


--
-- TOC entry 3790 (class 2606 OID 10425216)
-- Name: tb_atividadecronograma fk_responsavelaceitacao_atividadecronograma; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadecronograma
    ADD CONSTRAINT fk_responsavelaceitacao_atividadecronograma FOREIGN KEY (idresponsavel) REFERENCES agepnet200.tb_parteinteressada(idparteinteressada) ON DELETE SET NULL;


--
-- TOC entry 3791 (class 2606 OID 10425221)
-- Name: tb_atividadecronograma fk_responsavelentrega_atividadecronograma; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_atividadecronograma
    ADD CONSTRAINT fk_responsavelentrega_atividadecronograma FOREIGN KEY (idparteinteressada) REFERENCES agepnet200.tb_parteinteressada(idparteinteressada) ON DELETE SET NULL;


--
-- TOC entry 3902 (class 2606 OID 10425226)
-- Name: tb_resposta_questionariordiagnostico fk_respostapergunta_respostaquestionariorespondido; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_resposta_questionariordiagnostico
    ADD CONSTRAINT fk_respostapergunta_respostaquestionariorespondido FOREIGN KEY (id_resposta_pergunta) REFERENCES agepnet200.tb_resposta_pergunta(id_resposta_pergunta) ON DELETE CASCADE;


--
-- TOC entry 3909 (class 2606 OID 10425231)
-- Name: tb_risco fk_risco_etapa; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_risco
    ADD CONSTRAINT fk_risco_etapa FOREIGN KEY (idetapa) REFERENCES agepnet200.tb_etapa(idetapa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3910 (class 2606 OID 10425236)
-- Name: tb_risco fk_risco_origemrisco; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_risco
    ADD CONSTRAINT fk_risco_origemrisco FOREIGN KEY (idorigemrisco) REFERENCES agepnet200.tb_origemrisco(idorigemrisco) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3911 (class 2606 OID 10425241)
-- Name: tb_risco fk_risco_tiporisco; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_risco
    ADD CONSTRAINT fk_risco_tiporisco FOREIGN KEY (idtiporisco) REFERENCES agepnet200.tb_tiporisco(idtiporisco) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3912 (class 2606 OID 10425246)
-- Name: tb_secao fk_secao_secaopai; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_secao
    ADD CONSTRAINT fk_secao_secaopai FOREIGN KEY (id_secao_pai) REFERENCES agepnet200.tb_secao(id_secao) ON DELETE CASCADE;


--
-- TOC entry 3913 (class 2606 OID 10425251)
-- Name: tb_statusreport fk_statusreport_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_statusreport
    ADD CONSTRAINT fk_statusreport_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3914 (class 2606 OID 10425256)
-- Name: tb_statusreport fk_statusreport_projeto; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_statusreport
    ADD CONSTRAINT fk_statusreport_projeto FOREIGN KEY (idprojeto) REFERENCES agepnet200.tb_projeto(idprojeto) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3915 (class 2606 OID 10425261)
-- Name: tb_tipoacordo fk_tipoacordo_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_tipoacordo
    ADD CONSTRAINT fk_tipoacordo_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3916 (class 2606 OID 10425266)
-- Name: tb_tratamento fk_tratamento_pescadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_tratamento
    ADD CONSTRAINT fk_tratamento_pescadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON UPDATE RESTRICT ON DELETE RESTRICT;


--
-- TOC entry 3892 (class 2606 OID 10425271)
-- Name: tb_questionariodiagnosticomelhoria fk_undiaderesponsavelimplantacao_questionariodiagnosticomelhori; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariodiagnosticomelhoria
    ADD CONSTRAINT fk_undiaderesponsavelimplantacao_questionariodiagnosticomelhori FOREIGN KEY (idunidaderesponsavelimplantacao, idunidadeprincipal, iddiagnostico) REFERENCES agepnet200.tb_unidade_vinculada(idunidade, id_unidadeprincipal, iddiagnostico) ON DELETE RESTRICT;


--
-- TOC entry 3893 (class 2606 OID 10425276)
-- Name: tb_questionariodiagnosticomelhoria fk_unidaderesponsavelproposta_questionariodiagnosticomelhoria; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_questionariodiagnosticomelhoria
    ADD CONSTRAINT fk_unidaderesponsavelproposta_questionariodiagnosticomelhoria FOREIGN KEY (idunidaderesponsavelproposta, idunidadeprincipal, iddiagnostico) REFERENCES agepnet200.tb_unidade_vinculada(idunidade, id_unidadeprincipal, iddiagnostico) ON DELETE RESTRICT;


--
-- TOC entry 3917 (class 2606 OID 10425281)
-- Name: tb_unidade_vinculada fk_unidadevinculada_diagnostico; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_unidade_vinculada
    ADD CONSTRAINT fk_unidadevinculada_diagnostico FOREIGN KEY (iddiagnostico) REFERENCES agepnet200.tb_diagnostico(iddiagnostico) ON DELETE CASCADE;


--
-- TOC entry 3802 (class 2606 OID 10425286)
-- Name: tb_diagnostico pk_diagnostico_cadastrador; Type: FK CONSTRAINT; Schema: agepnet200; Owner: postgres
--

ALTER TABLE ONLY agepnet200.tb_diagnostico
    ADD CONSTRAINT pk_diagnostico_cadastrador FOREIGN KEY (idcadastrador) REFERENCES agepnet200.tb_pessoa(idpessoa) ON DELETE RESTRICT;


--
-- TOC entry 4169 (class 0 OID 0)
-- Dependencies: 18
-- Name: SCHEMA agepnet200; Type: ACL; Schema: -; Owner: postgres
--

GRANT USAGE ON SCHEMA agepnet200 TO postgres;


--
-- TOC entry 4170 (class 0 OID 0)
-- Dependencies: 23
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: usr_pos_prh_001
--

GRANT ALL ON SCHEMA public TO postgres;


--
-- TOC entry 4172 (class 0 OID 0)
-- Dependencies: 433
-- Name: FUNCTION dblink(text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink(text) TO postgres;


--
-- TOC entry 4173 (class 0 OID 0)
-- Dependencies: 434
-- Name: FUNCTION dblink(text, boolean); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink(text, boolean) TO postgres;


--
-- TOC entry 4174 (class 0 OID 0)
-- Dependencies: 435
-- Name: FUNCTION dblink(text, text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink(text, text) TO postgres;


--
-- TOC entry 4175 (class 0 OID 0)
-- Dependencies: 436
-- Name: FUNCTION dblink(text, text, boolean); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink(text, text, boolean) TO postgres;


--
-- TOC entry 4176 (class 0 OID 0)
-- Dependencies: 437
-- Name: FUNCTION dblink_build_sql_delete(text, int2vector, integer, text[]); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_build_sql_delete(text, int2vector, integer, text[]) TO postgres;


--
-- TOC entry 4177 (class 0 OID 0)
-- Dependencies: 438
-- Name: FUNCTION dblink_build_sql_insert(text, int2vector, integer, text[], text[]); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_build_sql_insert(text, int2vector, integer, text[], text[]) TO postgres;


--
-- TOC entry 4178 (class 0 OID 0)
-- Dependencies: 439
-- Name: FUNCTION dblink_build_sql_update(text, int2vector, integer, text[], text[]); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_build_sql_update(text, int2vector, integer, text[], text[]) TO postgres;


--
-- TOC entry 4179 (class 0 OID 0)
-- Dependencies: 440
-- Name: FUNCTION dblink_cancel_query(text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_cancel_query(text) TO postgres;


--
-- TOC entry 4180 (class 0 OID 0)
-- Dependencies: 441
-- Name: FUNCTION dblink_close(text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_close(text) TO postgres;


--
-- TOC entry 4181 (class 0 OID 0)
-- Dependencies: 442
-- Name: FUNCTION dblink_close(text, boolean); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_close(text, boolean) TO postgres;


--
-- TOC entry 4182 (class 0 OID 0)
-- Dependencies: 443
-- Name: FUNCTION dblink_close(text, text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_close(text, text) TO postgres;


--
-- TOC entry 4183 (class 0 OID 0)
-- Dependencies: 444
-- Name: FUNCTION dblink_close(text, text, boolean); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_close(text, text, boolean) TO postgres;


--
-- TOC entry 4184 (class 0 OID 0)
-- Dependencies: 445
-- Name: FUNCTION dblink_connect(text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_connect(text) TO postgres;


--
-- TOC entry 4185 (class 0 OID 0)
-- Dependencies: 446
-- Name: FUNCTION dblink_connect(text, text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_connect(text, text) TO postgres;


--
-- TOC entry 4186 (class 0 OID 0)
-- Dependencies: 385
-- Name: FUNCTION dblink_connect_u(text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_connect_u(text) TO postgres;


--
-- TOC entry 4187 (class 0 OID 0)
-- Dependencies: 447
-- Name: FUNCTION dblink_connect_u(text, text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_connect_u(text, text) TO postgres;


--
-- TOC entry 4188 (class 0 OID 0)
-- Dependencies: 448
-- Name: FUNCTION dblink_current_query(); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_current_query() TO postgres;


--
-- TOC entry 4189 (class 0 OID 0)
-- Dependencies: 449
-- Name: FUNCTION dblink_disconnect(); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_disconnect() TO postgres;


--
-- TOC entry 4190 (class 0 OID 0)
-- Dependencies: 403
-- Name: FUNCTION dblink_disconnect(text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_disconnect(text) TO postgres;


--
-- TOC entry 4191 (class 0 OID 0)
-- Dependencies: 404
-- Name: FUNCTION dblink_error_message(text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_error_message(text) TO postgres;


--
-- TOC entry 4192 (class 0 OID 0)
-- Dependencies: 405
-- Name: FUNCTION dblink_exec(text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_exec(text) TO postgres;


--
-- TOC entry 4193 (class 0 OID 0)
-- Dependencies: 408
-- Name: FUNCTION dblink_exec(text, boolean); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_exec(text, boolean) TO postgres;


--
-- TOC entry 4194 (class 0 OID 0)
-- Dependencies: 409
-- Name: FUNCTION dblink_exec(text, text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_exec(text, text) TO postgres;


--
-- TOC entry 4195 (class 0 OID 0)
-- Dependencies: 410
-- Name: FUNCTION dblink_exec(text, text, boolean); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_exec(text, text, boolean) TO postgres;


--
-- TOC entry 4196 (class 0 OID 0)
-- Dependencies: 386
-- Name: FUNCTION dblink_fdw_validator(options text[], catalog oid); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_fdw_validator(options text[], catalog oid) TO postgres;


--
-- TOC entry 4197 (class 0 OID 0)
-- Dependencies: 411
-- Name: FUNCTION dblink_fetch(text, integer); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_fetch(text, integer) TO postgres;


--
-- TOC entry 4198 (class 0 OID 0)
-- Dependencies: 412
-- Name: FUNCTION dblink_fetch(text, integer, boolean); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_fetch(text, integer, boolean) TO postgres;


--
-- TOC entry 4199 (class 0 OID 0)
-- Dependencies: 413
-- Name: FUNCTION dblink_fetch(text, text, integer); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_fetch(text, text, integer) TO postgres;


--
-- TOC entry 4200 (class 0 OID 0)
-- Dependencies: 414
-- Name: FUNCTION dblink_fetch(text, text, integer, boolean); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_fetch(text, text, integer, boolean) TO postgres;


--
-- TOC entry 4201 (class 0 OID 0)
-- Dependencies: 415
-- Name: FUNCTION dblink_get_connections(); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_get_connections() TO postgres;


--
-- TOC entry 4202 (class 0 OID 0)
-- Dependencies: 416
-- Name: FUNCTION dblink_get_notify(OUT notify_name text, OUT be_pid integer, OUT extra text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_get_notify(OUT notify_name text, OUT be_pid integer, OUT extra text) TO postgres;


--
-- TOC entry 4203 (class 0 OID 0)
-- Dependencies: 417
-- Name: FUNCTION dblink_get_notify(conname text, OUT notify_name text, OUT be_pid integer, OUT extra text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_get_notify(conname text, OUT notify_name text, OUT be_pid integer, OUT extra text) TO postgres;


--
-- TOC entry 4204 (class 0 OID 0)
-- Dependencies: 418
-- Name: FUNCTION dblink_get_pkey(text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_get_pkey(text) TO postgres;


--
-- TOC entry 4205 (class 0 OID 0)
-- Dependencies: 420
-- Name: FUNCTION dblink_get_result(text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_get_result(text) TO postgres;


--
-- TOC entry 4206 (class 0 OID 0)
-- Dependencies: 421
-- Name: FUNCTION dblink_get_result(text, boolean); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_get_result(text, boolean) TO postgres;


--
-- TOC entry 4207 (class 0 OID 0)
-- Dependencies: 422
-- Name: FUNCTION dblink_is_busy(text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_is_busy(text) TO postgres;


--
-- TOC entry 4208 (class 0 OID 0)
-- Dependencies: 423
-- Name: FUNCTION dblink_open(text, text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_open(text, text) TO postgres;


--
-- TOC entry 4209 (class 0 OID 0)
-- Dependencies: 425
-- Name: FUNCTION dblink_open(text, text, boolean); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_open(text, text, boolean) TO postgres;


--
-- TOC entry 4210 (class 0 OID 0)
-- Dependencies: 426
-- Name: FUNCTION dblink_open(text, text, text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_open(text, text, text) TO postgres;


--
-- TOC entry 4211 (class 0 OID 0)
-- Dependencies: 427
-- Name: FUNCTION dblink_open(text, text, text, boolean); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_open(text, text, text, boolean) TO postgres;


--
-- TOC entry 4212 (class 0 OID 0)
-- Dependencies: 428
-- Name: FUNCTION dblink_send_query(text, text); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.dblink_send_query(text, text) TO postgres;


--
-- TOC entry 4213 (class 0 OID 0)
-- Dependencies: 399
-- Name: FUNCTION ultimoid_gatilho(); Type: ACL; Schema: public; Owner: postgres
--

GRANT ALL ON FUNCTION public.ultimoid_gatilho() TO postgres;


--
-- TOC entry 4214 (class 0 OID 0)
-- Dependencies: 280
-- Name: TABLE tb_acao; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_acao TO postgres;


--
-- TOC entry 4215 (class 0 OID 0)
-- Dependencies: 281
-- Name: TABLE tb_aceite; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_aceite TO postgres;


--
-- TOC entry 4218 (class 0 OID 0)
-- Dependencies: 282
-- Name: TABLE tb_aceiteatividadecronograma; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_aceiteatividadecronograma TO postgres;


--
-- TOC entry 4219 (class 0 OID 0)
-- Dependencies: 283
-- Name: TABLE tb_acordo; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_acordo TO postgres;


--
-- TOC entry 4220 (class 0 OID 0)
-- Dependencies: 284
-- Name: TABLE tb_acordoentidadeexterna; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_acordoentidadeexterna TO postgres;


--
-- TOC entry 4221 (class 0 OID 0)
-- Dependencies: 285
-- Name: TABLE tb_acordoespecieinstrumento; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_acordoespecieinstrumento TO postgres;


--
-- TOC entry 4222 (class 0 OID 0)
-- Dependencies: 286
-- Name: TABLE tb_agenda; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_agenda TO postgres;


--
-- TOC entry 4223 (class 0 OID 0)
-- Dependencies: 287
-- Name: TABLE tb_aquisicao; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_aquisicao TO postgres;


--
-- TOC entry 4234 (class 0 OID 0)
-- Dependencies: 288
-- Name: TABLE tb_assinadocumento; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_assinadocumento TO postgres;


--
-- TOC entry 4235 (class 0 OID 0)
-- Dependencies: 289
-- Name: TABLE tb_ata; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_ata TO postgres;


--
-- TOC entry 4236 (class 0 OID 0)
-- Dependencies: 290
-- Name: TABLE tb_atividade; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_atividade TO postgres;


--
-- TOC entry 4238 (class 0 OID 0)
-- Dependencies: 291
-- Name: TABLE tb_atividadecronograma; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_atividadecronograma TO postgres;


--
-- TOC entry 4243 (class 0 OID 0)
-- Dependencies: 292
-- Name: TABLE tb_atividadecronopredecessora; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_atividadecronopredecessora TO postgres;


--
-- TOC entry 4245 (class 0 OID 0)
-- Dependencies: 293
-- Name: TABLE tb_atividadeocultar; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_atividadeocultar TO postgres;


--
-- TOC entry 4246 (class 0 OID 0)
-- Dependencies: 294
-- Name: TABLE tb_bloqueioprojeto; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_bloqueioprojeto TO postgres;


--
-- TOC entry 4248 (class 0 OID 0)
-- Dependencies: 271
-- Name: TABLE tb_cargo; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_cargo TO postgres;


--
-- TOC entry 4250 (class 0 OID 0)
-- Dependencies: 272
-- Name: SEQUENCE tb_cargo_idcargo_seq; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON SEQUENCE agepnet200.tb_cargo_idcargo_seq TO postgres;


--
-- TOC entry 4258 (class 0 OID 0)
-- Dependencies: 295
-- Name: TABLE tb_comentario; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_comentario TO postgres;


--
-- TOC entry 4259 (class 0 OID 0)
-- Dependencies: 296
-- Name: TABLE tb_comunicacao; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_comunicacao TO postgres;


--
-- TOC entry 4260 (class 0 OID 0)
-- Dependencies: 297
-- Name: TABLE tb_contramedida; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_contramedida TO postgres;


--
-- TOC entry 4270 (class 0 OID 0)
-- Dependencies: 298
-- Name: TABLE tb_diagnostico; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_diagnostico TO postgres;


--
-- TOC entry 4272 (class 0 OID 0)
-- Dependencies: 300
-- Name: TABLE tb_diariobordo; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_diariobordo TO postgres;


--
-- TOC entry 4276 (class 0 OID 0)
-- Dependencies: 301
-- Name: TABLE tb_diautil; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_diautil TO postgres;


--
-- TOC entry 4278 (class 0 OID 0)
-- Dependencies: 303
-- Name: TABLE tb_documento; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_documento TO postgres;


--
-- TOC entry 4279 (class 0 OID 0)
-- Dependencies: 304
-- Name: TABLE tb_elementodespesa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_elementodespesa TO postgres;


--
-- TOC entry 4280 (class 0 OID 0)
-- Dependencies: 305
-- Name: TABLE tb_entidadeexterna; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_entidadeexterna TO postgres;


--
-- TOC entry 4281 (class 0 OID 0)
-- Dependencies: 306
-- Name: TABLE tb_escritorio; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_escritorio TO postgres;


--
-- TOC entry 4282 (class 0 OID 0)
-- Dependencies: 307
-- Name: TABLE tb_etapa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_etapa TO postgres;


--
-- TOC entry 4283 (class 0 OID 0)
-- Dependencies: 308
-- Name: TABLE tb_evento; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_evento TO postgres;


--
-- TOC entry 4284 (class 0 OID 0)
-- Dependencies: 309
-- Name: TABLE tb_eventoavaliacao; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_eventoavaliacao TO postgres;


--
-- TOC entry 4286 (class 0 OID 0)
-- Dependencies: 310
-- Name: TABLE tb_feriado; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_feriado TO postgres;


--
-- TOC entry 4287 (class 0 OID 0)
-- Dependencies: 311
-- Name: TABLE tb_frase; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_frase TO postgres;


--
-- TOC entry 4288 (class 0 OID 0)
-- Dependencies: 312
-- Name: TABLE tb_frase_pesquisa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_frase_pesquisa TO postgres;


--
-- TOC entry 4289 (class 0 OID 0)
-- Dependencies: 313
-- Name: TABLE tb_funcionalidade; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_funcionalidade TO postgres;


--
-- TOC entry 4290 (class 0 OID 0)
-- Dependencies: 314
-- Name: TABLE tb_hst_publicacao; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_hst_publicacao TO postgres;


--
-- TOC entry 4296 (class 0 OID 0)
-- Dependencies: 315
-- Name: TABLE tb_item_secao; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_item_secao TO postgres;


--
-- TOC entry 4297 (class 0 OID 0)
-- Dependencies: 316
-- Name: TABLE tb_licao; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_licao TO postgres;


--
-- TOC entry 4305 (class 0 OID 0)
-- Dependencies: 317
-- Name: TABLE tb_linhatempo; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_linhatempo TO postgres;


--
-- TOC entry 4306 (class 0 OID 0)
-- Dependencies: 318
-- Name: TABLE tb_logacesso; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_logacesso TO postgres;


--
-- TOC entry 4307 (class 0 OID 0)
-- Dependencies: 319
-- Name: TABLE tb_manutencaogepnet; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_manutencaogepnet TO postgres;


--
-- TOC entry 4308 (class 0 OID 0)
-- Dependencies: 320
-- Name: TABLE tb_marco; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_marco TO postgres;


--
-- TOC entry 4309 (class 0 OID 0)
-- Dependencies: 321
-- Name: TABLE tb_modulo; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_modulo TO postgres;


--
-- TOC entry 4310 (class 0 OID 0)
-- Dependencies: 322
-- Name: TABLE tb_mudanca; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_mudanca TO postgres;


--
-- TOC entry 4311 (class 0 OID 0)
-- Dependencies: 323
-- Name: TABLE tb_natureza; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_natureza TO postgres;


--
-- TOC entry 4312 (class 0 OID 0)
-- Dependencies: 324
-- Name: TABLE tb_objetivo; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_objetivo TO postgres;


--
-- TOC entry 4316 (class 0 OID 0)
-- Dependencies: 325
-- Name: TABLE tb_opcao_resposta; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_opcao_resposta TO postgres;


--
-- TOC entry 4317 (class 0 OID 0)
-- Dependencies: 326
-- Name: TABLE tb_origemrisco; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_origemrisco TO postgres;


--
-- TOC entry 4318 (class 0 OID 0)
-- Dependencies: 327
-- Name: TABLE tb_p_acao; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_p_acao TO postgres;


--
-- TOC entry 4321 (class 0 OID 0)
-- Dependencies: 328
-- Name: TABLE tb_partediagnostico; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_partediagnostico TO postgres;


--
-- TOC entry 4324 (class 0 OID 0)
-- Dependencies: 329
-- Name: TABLE tb_parteinteressada; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_parteinteressada TO postgres;


--
-- TOC entry 4327 (class 0 OID 0)
-- Dependencies: 330
-- Name: TABLE tb_parteinteressada_funcoes; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_parteinteressada_funcoes TO postgres;


--
-- TOC entry 4330 (class 0 OID 0)
-- Dependencies: 331
-- Name: TABLE tb_parteinteressadafuncao; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_parteinteressadafuncao TO postgres;


--
-- TOC entry 4332 (class 0 OID 0)
-- Dependencies: 333
-- Name: TABLE tb_perfil; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_perfil TO postgres;


--
-- TOC entry 4333 (class 0 OID 0)
-- Dependencies: 334
-- Name: TABLE tb_perfilmodulo; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_perfilmodulo TO postgres;


--
-- TOC entry 4334 (class 0 OID 0)
-- Dependencies: 335
-- Name: TABLE tb_perfilpessoa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_perfilpessoa TO postgres;


--
-- TOC entry 4345 (class 0 OID 0)
-- Dependencies: 336
-- Name: TABLE tb_pergunta; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_pergunta TO postgres;


--
-- TOC entry 4346 (class 0 OID 0)
-- Dependencies: 337
-- Name: TABLE tb_perm_funcionalidade; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_perm_funcionalidade TO postgres;


--
-- TOC entry 4348 (class 0 OID 0)
-- Dependencies: 338
-- Name: TABLE tb_permissao; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_permissao TO postgres;


--
-- TOC entry 4356 (class 0 OID 0)
-- Dependencies: 339
-- Name: TABLE tb_permissaodiagnostico; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_permissaodiagnostico TO postgres;


--
-- TOC entry 4357 (class 0 OID 0)
-- Dependencies: 340
-- Name: TABLE tb_permissaoperfil; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_permissaoperfil TO postgres;


--
-- TOC entry 4365 (class 0 OID 0)
-- Dependencies: 341
-- Name: TABLE tb_permissaoprojeto; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_permissaoprojeto TO postgres;


--
-- TOC entry 4366 (class 0 OID 0)
-- Dependencies: 342
-- Name: TABLE tb_pesquisa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_pesquisa TO postgres;


--
-- TOC entry 4368 (class 0 OID 0)
-- Dependencies: 343
-- Name: TABLE tb_pessoa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_pessoa TO postgres;


--
-- TOC entry 4369 (class 0 OID 0)
-- Dependencies: 344
-- Name: TABLE tb_pessoaagenda; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_pessoaagenda TO postgres;


--
-- TOC entry 4370 (class 0 OID 0)
-- Dependencies: 345
-- Name: TABLE tb_portfolio; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_portfolio TO postgres;


--
-- TOC entry 4371 (class 0 OID 0)
-- Dependencies: 346
-- Name: TABLE tb_portifolioprograma; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_portifolioprograma TO postgres;


--
-- TOC entry 4372 (class 0 OID 0)
-- Dependencies: 347
-- Name: TABLE tb_processo; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_processo TO postgres;


--
-- TOC entry 4373 (class 0 OID 0)
-- Dependencies: 348
-- Name: TABLE tb_programa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_programa TO postgres;


--
-- TOC entry 4378 (class 0 OID 0)
-- Dependencies: 349
-- Name: TABLE tb_projeto; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_projeto TO postgres;


--
-- TOC entry 4379 (class 0 OID 0)
-- Dependencies: 350
-- Name: TABLE tb_projetoprocesso; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_projetoprocesso TO postgres;


--
-- TOC entry 4394 (class 0 OID 0)
-- Dependencies: 351
-- Name: TABLE tb_questdiagnosticopadronizamelhoria; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_questdiagnosticopadronizamelhoria TO postgres;


--
-- TOC entry 4395 (class 0 OID 0)
-- Dependencies: 352
-- Name: TABLE tb_questionario; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_questionario TO postgres;


--
-- TOC entry 4403 (class 0 OID 0)
-- Dependencies: 353
-- Name: TABLE tb_questionario_diagnostico; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_questionario_diagnostico TO postgres;


--
-- TOC entry 4404 (class 0 OID 0)
-- Dependencies: 354
-- Name: TABLE tb_questionario_pesquisa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_questionario_pesquisa TO postgres;


--
-- TOC entry 4411 (class 0 OID 0)
-- Dependencies: 355
-- Name: TABLE tb_questionariodiagnostico_respondido; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_questionariodiagnostico_respondido TO postgres;


--
-- TOC entry 4428 (class 0 OID 0)
-- Dependencies: 356
-- Name: TABLE tb_questionariodiagnosticomelhoria; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_questionariodiagnosticomelhoria TO postgres;


--
-- TOC entry 4429 (class 0 OID 0)
-- Dependencies: 357
-- Name: TABLE tb_questionariofrase; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_questionariofrase TO postgres;


--
-- TOC entry 4430 (class 0 OID 0)
-- Dependencies: 358
-- Name: TABLE tb_questionariofrase_pesquisa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_questionariofrase_pesquisa TO postgres;


--
-- TOC entry 4431 (class 0 OID 0)
-- Dependencies: 359
-- Name: TABLE tb_r3g; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_r3g TO postgres;


--
-- TOC entry 4433 (class 0 OID 0)
-- Dependencies: 360
-- Name: TABLE tb_recurso; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_recurso TO postgres;


--
-- TOC entry 4434 (class 0 OID 0)
-- Dependencies: 361
-- Name: TABLE tb_resposta; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_resposta TO postgres;


--
-- TOC entry 4443 (class 0 OID 0)
-- Dependencies: 362
-- Name: TABLE tb_resposta_pergunta; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_resposta_pergunta TO postgres;


--
-- TOC entry 4444 (class 0 OID 0)
-- Dependencies: 363
-- Name: TABLE tb_resposta_pesquisa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_resposta_pesquisa TO postgres;


--
-- TOC entry 4450 (class 0 OID 0)
-- Dependencies: 364
-- Name: TABLE tb_resposta_questionariordiagnostico; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_resposta_questionariordiagnostico TO postgres;


--
-- TOC entry 4451 (class 0 OID 0)
-- Dependencies: 365
-- Name: TABLE tb_respostafrase; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_respostafrase TO postgres;


--
-- TOC entry 4452 (class 0 OID 0)
-- Dependencies: 366
-- Name: TABLE tb_respostafrase_pesquisa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_respostafrase_pesquisa TO postgres;


--
-- TOC entry 4453 (class 0 OID 0)
-- Dependencies: 367
-- Name: TABLE tb_resultado_pesquisa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_resultado_pesquisa TO postgres;


--
-- TOC entry 4454 (class 0 OID 0)
-- Dependencies: 368
-- Name: TABLE tb_risco; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_risco TO postgres;


--
-- TOC entry 4462 (class 0 OID 0)
-- Dependencies: 369
-- Name: TABLE tb_secao; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_secao TO postgres;


--
-- TOC entry 4463 (class 0 OID 0)
-- Dependencies: 370
-- Name: TABLE tb_setor; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_setor TO postgres;


--
-- TOC entry 4469 (class 0 OID 0)
-- Dependencies: 371
-- Name: TABLE tb_statusreport; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_statusreport TO postgres;


--
-- TOC entry 4470 (class 0 OID 0)
-- Dependencies: 372
-- Name: TABLE tb_tipoacordo; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_tipoacordo TO postgres;


--
-- TOC entry 4471 (class 0 OID 0)
-- Dependencies: 373
-- Name: TABLE tb_tipoavaliacao; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_tipoavaliacao TO postgres;


--
-- TOC entry 4472 (class 0 OID 0)
-- Dependencies: 374
-- Name: TABLE tb_tipocontramedida; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_tipocontramedida TO postgres;


--
-- TOC entry 4473 (class 0 OID 0)
-- Dependencies: 375
-- Name: TABLE tb_tipodocumento; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_tipodocumento TO postgres;


--
-- TOC entry 4474 (class 0 OID 0)
-- Dependencies: 376
-- Name: TABLE tb_tipoiniciativa; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_tipoiniciativa TO postgres;


--
-- TOC entry 4475 (class 0 OID 0)
-- Dependencies: 377
-- Name: TABLE tb_tipomudanca; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_tipomudanca TO postgres;


--
-- TOC entry 4476 (class 0 OID 0)
-- Dependencies: 378
-- Name: TABLE tb_tiporisco; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_tiporisco TO postgres;


--
-- TOC entry 4477 (class 0 OID 0)
-- Dependencies: 379
-- Name: TABLE tb_tiposituacaoprojeto; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_tiposituacaoprojeto TO postgres;


--
-- TOC entry 4478 (class 0 OID 0)
-- Dependencies: 380
-- Name: TABLE tb_tratamento; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_tratamento TO postgres;


--
-- TOC entry 4479 (class 0 OID 0)
-- Dependencies: 273
-- Name: TABLE tb_unidade; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_unidade TO postgres;


--
-- TOC entry 4481 (class 0 OID 0)
-- Dependencies: 274
-- Name: SEQUENCE tb_unidade_idunidade_seq; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON SEQUENCE agepnet200.tb_unidade_idunidade_seq TO postgres;


--
-- TOC entry 4486 (class 0 OID 0)
-- Dependencies: 381
-- Name: TABLE tb_unidade_vinculada; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_unidade_vinculada TO postgres;


--
-- TOC entry 4495 (class 0 OID 0)
-- Dependencies: 382
-- Name: TABLE tb_vincula_questionario; Type: ACL; Schema: agepnet200; Owner: postgres
--

GRANT ALL ON TABLE agepnet200.tb_vincula_questionario TO postgres;


-- Completed on 2022-11-16 18:36:53

--
-- PostgreSQL database dump complete
--

