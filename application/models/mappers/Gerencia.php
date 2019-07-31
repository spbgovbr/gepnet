<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Gerencia extends App_Model_Mapper_MapperAbstract
{

    private $_mapperParteInteressada = null;

    protected function _init()
    {
        $this->_mapperParteInteressada = new Projeto_Model_Mapper_Parteinteressada();
    }

    /**
     * Set the property
     *
     * @param Projeto_Model_Gerencia
     * @return Projeto_Model_Gerencia
     */
    public function insert(Projeto_Model_Gerencia $model)
    {
        /**
         * @todo remover o idcadastrador abaixo
         */
        $model->idprojeto = $this->maxVal('idprojeto');
        $model->flaativo = 'S';
        $data = array(
            "idprojeto" => $model->idprojeto,
            "nomcodigo" => $model->nomcodigo,
            "nomsigla" => $model->nomsigla,
            "nomprojeto" => $model->nomprojeto,
            "idsetor" => $model->idsetor,
            "idgerenteprojeto" => $model->idgerenteprojeto,
            "idgerenteadjunto" => $model->idgerenteadjunto,
            "desprojeto" => $model->desprojeto,
            "desobjetivo" => $model->desobjetivo,
            "datinicio" => new Zend_Db_Expr("to_date('" . $model->datinicio->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datfim" => new Zend_Db_Expr("to_date('" . $model->datfim->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "numperiodicidadeatualizacao" => $model->numperiodicidadeatualizacao,
            "numcriteriofarol" => $model->numcriteriofarol,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr('now()'),
            "domtipoprojeto" => $model->domtipoprojeto,
            "flapublicado" => $model->flapublicado,
            "domstatusprojeto" => $model->domstatusprojeto,
            "flaaprovado" => $model->flaaprovado,
            "desresultadosobtidos" => $model->desresultadosobtidos,
            "despontosfortes" => $model->despontosfortes,
            "despontosfracos" => $model->despontosfracos,
            "dessugestoes" => $model->dessugestoes,
            "idescritorio" => $model->idescritorio,
            "flaaltagestao" => $model->flaaltagestao,
            "idobjetivo" => $model->idobjetivo,
            "idacao" => $model->idacao,
            "flacopa" => $model->flacopa,
            "idnatureza" => $model->idnatureza,
            "vlrorcamentodisponivel" => $model->vlrorcamentodisponivel,
            "desjustificativa" => $model->desjustificativa,
            "iddemandante" => $model->iddemandante,
            "idpatrocinador" => $model->idpatrocinador,
            "datinicioplano" => new Zend_Db_Expr("to_date('" . $model->datinicioplano->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datfimplano" => new Zend_Db_Expr("to_date('" . $model->datfimplano->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "desescopo" => $model->desescopo,
            "desnaoescopo" => $model->desnaoescopo,
            "despremissa" => $model->despremissa,
            "desrestricao" => $model->desrestricao,
            "numseqprojeto" => $model->numseqprojeto,
            "numanoprojeto" => $model->numanoprojeto,
            "desconsideracaofinal" => $model->desconsideracaofinal,
            //"datenviouemailatualizacao"     => new Zend_Db_Expr("to_date('" . $model->datenviouemailatualizacao->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "idprograma" => $model->idprograma,
            "nomproponente" => $model->nomproponente,
            "numseqprojeto" => $model->numanoprojeto,
            "ano" => $model->ano,
            "idacao" => $model->idacao,
            "idportfolio" => $model->idportfolio
        );

//        return $this->getDbTable()->insert($data);
        try {
            $this->getDbTable()->insert($data);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Escritorio
     */
    public function update(Projeto_Model_Gerencia $model)
    {
        $datainicio = !empty($model->datinicio) ? new Zend_Db_Expr("to_date('" . $model->datinicio->toString('Y-m-d') . "','YYYY-MM-DD')") : '';
        $datafim = !empty($model->datfim) ? new Zend_Db_Expr("to_date('" . $model->datfim->toString('Y-m-d') . "','YYYY-MM-DD')") : "";
        $datainicioplano = !empty($model->datinicioplano) ? new Zend_Db_Expr("to_date('" . $model->datinicioplano->toString('Y-m-d') . "','YYYY-MM-DD')") : '';
        $datafimplano = !empty($model->datfimplano) ? new Zend_Db_Expr("to_date('" . $model->datfimplano->toString('Y-m-d') . "','YYYY-MM-DD')") : '';

//        Zend_Debug::dump($model); exit;

        $data = array(
            "idprojeto" => $model->idprojeto,
            "nomcodigo" => $model->nomcodigo,
            "nomsigla" => $model->nomsigla,
            "nomprojeto" => $model->nomprojeto,
            "idsetor" => $model->idsetor,
            "idgerenteprojeto" => $model->idgerenteprojeto,
            "idgerenteadjunto" => $model->idgerenteadjunto,
            "desprojeto" => $model->desprojeto,
            "desobjetivo" => $model->desobjetivo,
            "datinicio" => $datainicio,
            "datfim" => $datafim,
            "numperiodicidadeatualizacao" => $model->numperiodicidadeatualizacao,
            "numcriteriofarol" => $model->numcriteriofarol,
            "idcadastrador" => $model->idcadastrador,
            "domtipoprojeto" => $model->domtipoprojeto,
            "flapublicado" => $model->flapublicado,
            "domstatusprojeto" => $model->domstatusprojeto,
            "flaaprovado" => $model->flaaprovado,
            "desresultadosobtidos" => $model->desresultadosobtidos,
            "despontosfortes" => $model->despontosfortes,
            "despontosfracos" => $model->despontosfracos,
            "dessugestoes" => $model->dessugestoes,
            "idescritorio" => $model->idescritorio,
            "flaaltagestao" => $model->flaaltagestao,
            "idobjetivo" => $model->idobjetivo,
            "idacao" => $model->idacao,
            "flacopa" => $model->flacopa,
            "idnatureza" => $model->idnatureza,
            "vlrorcamentodisponivel" => $model->vlrorcamentodisponivel,
            "desjustificativa" => $model->desjustificativa,
            "iddemandante" => $model->iddemandante,
            "idpatrocinador" => $model->idpatrocinador,
            "datinicioplano" => $datainicioplano,
            "datfimplano" => $datafimplano,
            "desescopo" => $model->desescopo,
            "desnaoescopo" => $model->desnaoescopo,
            "despremissa" => $model->despremissa,
            "desrestricao" => $model->desrestricao,
            "numseqprojeto" => $model->numseqprojeto,
            "numanoprojeto" => $model->numanoprojeto,
            "desconsideracaofinal" => $model->desconsideracaofinal,
            //"datenviouemailatualizacao"     => new Zend_Db_Expr("to_date('" . $model->datenviouemailatualizacao->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "idprograma" => $model->idprograma,
            "nomproponente" => $model->nomproponente,
            "numseqprojeto" => $model->numanoprojeto,
            "idacao" => $model->idacao,
            "idportfolio" => $model->idportfolio
        );

        $data = array_filter($data);

//        Zend_Debug::dump($data); exit;

        try {
            $pks = array("idprojeto" => $model->idprojeto);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * @param array $params
     */
    public function updateStatusProjeto($params)
    {

        $data = array("domstatusprojeto" => 6);
        try {
            $pks = array("idprojeto" => $params['idprojeto']);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            //Zend_Debug::dump($params);exit;
            //return true;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisar($params, $idperfil = null, $idescritorio = null, $paginator = false)
    {

        $sql = "SELECT
                       (SELECT nomprograma from agepnet200.tb_programa prog WHERE prog.idprograma = proj.idprograma) as nomprograma,
                        proj.nomprojeto,
                        (SELECT pess.nompessoa FROM agepnet200.tb_pessoa pess WHERE pess.idpessoa = proj.idgerenteprojeto) as idgerenteprojeto,
                        proj.nomcodigo,
                        CASE proj.flapublicado
                            WHEN 'S' THEN 'SIM'
                            WHEN 'N' THEN 'NAO'
                        END as flapublicado,
                        to_char(proj.datinicio, 'DD/MM/YYYY') as datinicio,
                        to_char(proj.datfimplano, 'DD/MM/YYYY') as datfimplano,
                        to_char(proj.datfim, 'DD/MM/YYYY') as datfim,
                        '' as previsto,
                        '' as concluido,
                        '' as prazo,
                        '' as risco,
                        '' as atraso,
                        '' as ultimoacompanhamento,
                        proj.idprojeto,
                        proj.numcriteriofarol,
                        proj.domstatusprojeto,
                        proj.numperiodicidadeatualizacao,
                        proj.domstatusprojeto
                FROM
                        agepnet200.tb_projeto proj
                WHERE
                        1 = 1 ";

        /* if (isset($params['statusreport'])) {
             $sql .= " and proj.flapublicado = 'S'";
             $sql .= " and proj.flaaprovado = 'S'";
         }*/
        $params = array_filter($params);

        if (isset($idperfil) && $idperfil <> 1) {

            $sql .= " and proj.idescritorio = {$idescritorio}";
        }
        if (isset($params['nomprojeto'])) {
            $nomprojeto = $params['nomprojeto'];
            $sql .= " and proj.nomprojeto LIKE '%{$nomprojeto}%'";
        }

        if (isset($params['idprograma'])) {
            $idprograma = $params['idprograma'];
            $sql .= " and proj.idprograma = {$idprograma}";
        }

        if (isset($params['idescritorio'])) {
            $idescritorio = $params['idescritorio'];
            $sql .= " and proj.idescritorio = {$idescritorio}";
        }

        if (isset($params['domstatusprojeto'])) {

//            switch($params['domstatusprojeto']){ case "Proposta": $status = 1; break; case "Em Andamento": $status = 2;
//                break;case "Concluido": $status = 3; break; case "Paralisado": $status = 4; break;
//                case "Cancelado": $status = 5; break; case "Bloqueado": $status = 6; break;
//                case "Em Alteracao": $status = 7; break; }
            $domstatusprojeto = $params['domstatusprojeto'];
            $sql .= " and proj.domstatusprojeto = {$domstatusprojeto}";
        }
        if (isset($params['codobjetivo'])) {
            $status = $params['codobjetivo'];
            $sql .= " and proj.idobjetivo = '{$status}'";
        }
        if (isset($params['codacao']) && empty($params['codacao']) == false) {
            $status = $params['codacao'];
            $sql .= " and proj.idacao = '{$status}'";
        }
        if (isset($params['codnatureza']) && empty($params['codnatureza']) == false) {
            $status = $params['codnatureza'];
            $sql .= " and st.idnatureza = '{$status}'";
        }
        if (isset($params['codsetor']) && empty($params['codsetor']) == false) {
            $status = $params['codsetor'];
            $sql .= " and proj.idsetor = '{$status}'";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }
        //Zend_Debug::dump($sql); exit;

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);

        return $resultado;
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Gerencia);
    }

    public function fetchPairs()
    {
        $sql = "  SELECT distinct nomescritorio FROM agepnet200.tb_escritorio order by nomescritorio asc";
        return $this->_db->fetchAll($sql);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "
                        SELECT
                                proj.datcadastro,
                                proj.datenviouemailatualizacao,
                                proj.datfim,
                                proj.datfimplano,
                                proj.datinicio,
                                proj.datinicioplano,
                                proj.desconsideracaofinal,
                                proj.desescopo,
                                proj.desjustificativa,
                                proj.desnaoescopo,
                                proj.desobjetivo,
                                proj.despontosfortes,
                                proj.despontosfracos,
                                proj.despremissa,
                                proj.desprojeto,
                                proj.desrestricao,
                                proj.desresultadosobtidos,
                                proj.dessugestoes,
                                proj.domstatusprojeto,
                                proj.domtipoprojeto,
                                proj.flaaltagestao,
                                proj.flaaprovado,
                                CASE proj.flaaprovado
                                    WHEN 'S' THEN 'SIM'
                                    WHEN 'N' THEN 'NÃO'
                                END as aprovado,
                                proj.flacopa,
                                CASE proj.flacopa
                                    WHEN 'S' THEN 'SIM'
                                    WHEN 'N' THEN 'NÃO'
                                END as copa,
                                proj.flapublicado,
                                CASE proj.flapublicado
                                    WHEN 'S' THEN 'SIM'
                                    WHEN 'N' THEN 'NÃO'
                                END as publicado,
                                proj.idacao,
                                proj.idcadastrador,
                                proj.iddemandante,
                                p1.nompessoa as nomdemandante,
                                p1.nummatricula,
                                proj.idescritorio,
                                proj.idgerenteadjunto,
                                p4.nompessoa as nomgerenteadjunto,
                                proj.idgerenteprojeto,
                                p3.nompessoa as nomgerenteprojeto,
                                proj.idnatureza,
                                nat.nomnatureza as natureza,
                                proj.idobjetivo,
                                obj.nomobjetivo as objetivo,
                                proj.idpatrocinador,
                                p2.nompessoa as nompatrocinador,
                                proj.idprograma,
                                prog.nomprograma as programa,
                                proj.idprojeto,
                                proj.idsetor,
                                setor.nomsetor as setor,
                                proj.nomcodigo,
                                proj.nomprojeto,
                                proj.nomproponente,
                                proj.nomsigla,
                                proj.numanoprojeto,
                                proj.numcriteriofarol,
                                proj.numperiodicidadeatualizacao,
                                proj.numseqprojeto,
                                proj.vlrorcamentodisponivel,
                                port.idportfolio,
                                port.noportfolio,
                                CASE port.tipo
                                  WHEN 1 THEN 'NORMAL'
                                  WHEN 2 THEN 'ESTRATÉGICO'
                                END as tipo
                        FROM
                                agepnet200.tb_projeto proj                                 
                                			
			left join agepnet200.tb_pessoa p1 on p1.idpessoa = proj.iddemandante
			left join agepnet200.tb_pessoa p2 on p2.idpessoa = proj.idpatrocinador
			inner join agepnet200.tb_pessoa p3 on p3.idpessoa = proj.idgerenteprojeto
			inner join agepnet200.tb_pessoa p4 on p4.idpessoa = proj.idgerenteadjunto
			left join agepnet200.tb_programa prog on proj.idprograma = prog.idprograma
			left join agepnet200.tb_objetivo obj on  proj.idobjetivo = obj.idobjetivo 
			left join agepnet200.tb_natureza nat on nat.idnatureza = proj.idnatureza
			left join agepnet200.tb_setor setor on proj.idsetor = setor.idsetor
            left join agepnet200.tb_portfolio port on proj.idportfolio = port.idportfolio
                        WHERE 				 
                              idprojeto = :idprojeto";

        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        $projeto = new Projeto_Model_Gerencia($resultado);
        //print("<PRE>");
        //print_r($resultado); exit;

        return $projeto;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getByName($params)
    {
        $sql = "
				SELECT
     		        e.idescritoriope,
     		        e.idresponsavel1,
     		        e.idresponsavel2,
					e.nomescritorio as sigla,
					e.nomescritorio2 as nome,
					resp1.nompessoa as responsavel1,
					resp2.nompessoa as responsavel2,
					(select nomescritorio from agepnet200.tb_escritorio where idescritorio = e.idescritoriope) as mapa,
					e.flaativo,
				        'logo.jpg' as logo,
				        e.idescritorio as id
				FROM agepnet200.tb_escritorio e
					left JOIN agepnet200.tb_pessoa resp1
					ON e.idresponsavel1 = resp1.idpessoa
					left JOIN agepnet200.tb_pessoa resp2
					ON e.idresponsavel2 = resp2.idpessoa
				    where e.nomescritorio2 = :nomescritorio2";

        $resultado = $this->_db->fetchRow($sql, array('nomescritorio2' => $params['nomescritorio2']));

        return $resultado;
    }

    public function mapaFetchPairs()
    {
        $sql = "SELECT DISTINCT idescritorio,nomescritorio from agepnet200.tb_escritorio";
        return $this->_db->fetchPairs($sql);
    }

    /**
     * @param array $params
     * @return array
     */
    public function getByIdTapImprimir($params)
    {
        $sql = "
                 SELECT
                        proj.datcadastro,
                        proj.datenviouemailatualizacao,
                        proj.datfim,
                        proj.datfimplano,
                        proj.datinicio,
                        proj.datinicioplano,
                        proj.desconsideracaofinal,
                        proj.desescopo,
                        proj.desjustificativa,
                        proj.desnaoescopo,
                        proj.desobjetivo,
                        proj.despontosfortes,
                        proj.despontosfracos,
                        proj.despremissa,
                        proj.desprojeto,
                        proj.desrestricao,
                        proj.desresultadosobtidos,
                        proj.dessugestoes,
                        proj.domstatusprojeto,
                        proj.domtipoprojeto,
                        proj.flaaltagestao,
                        proj.flaaprovado,
                        proj.flacopa,
                        proj.flapublicado,
                        proj.idacao,
                        proj.idcadastrador,
                        proj.iddemandante,
                        proj.idescritorio,
                        proj.idgerenteadjunto,
                        proj.idgerenteprojeto,
                        proj.idnatureza,
                        proj.idobjetivo,
                        proj.idpatrocinador,
                        proj.idprograma,
                        proj.idprojeto,
                        proj.idsetor,
                        proj.nomcodigo,
                        proj.nomprojeto,
                        proj.nomproponente,
                        proj.nomsigla,
                        proj.numanoprojeto,
                        proj.numcriteriofarol,
                        proj.numperiodicidadeatualizacao,
                        proj.numseqprojeto,
                        proj.vlrorcamentodisponivel
                FROM
                        agepnet200.tb_projeto proj
                WHERE 
                        proj.idprojeto = :idprojeto";

        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));

        if (false == $resultado) {
            return false;
        }


        $projeto = new Projeto_Model_Gerencia($resultado);
        $projeto->partes = new App_Model_Relation(
            $this->_mapperParteInteressada, 'getByProjeto', array(array('idprojeto' => $resultado['idprojeto'])));
        return $projeto;
    }

    /**
     * @param array $params
     * @return Projeto_Model_Projeto
     */
    public function retornaProjetoPorId($params)
    {
        // Zend_Debug::dump($params); exit;
        $sql = "SELECT
                    to_char(proj.datcadastro,'DD/MM/YYYY') as datcadastro,
                    to_char(proj.datenviouemailatualizacao,'DD/MM/YYYY') as datenviouemailatualizacao,
                    to_char(proj.datfim,'DD/MM/YYYY') as datfim,
                    to_char(proj.datfimplano,'DD/MM/YYYY') as datfimplano,
                    to_char(proj.datinicio,'DD/MM/YYYY') as datinicio,
                    to_char(proj.datinicioplano,'DD/MM/YYYY') as datinicioplano,
                    proj.desconsideracaofinal,
                    proj.desescopo,
                    proj.desjustificativa,
                    proj.desnaoescopo,
                    proj.desobjetivo,
                    proj.despontosfortes,
                    proj.despontosfracos,
                    proj.despremissa,
                    proj.desprojeto,
                    proj.desrestricao,
                    proj.desresultadosobtidos,
                    proj.dessugestoes,
                    proj.domstatusprojeto,
                    proj.domtipoprojeto,
                    proj.flaaltagestao,
                    proj.flaaprovado,
                    proj.flacopa,
                    proj.flapublicado,
                    proj.idacao,
                    proj.idcadastrador,
                    proj.iddemandante,
                    proj.idescritorio,
                    proj.idgerenteadjunto,
                    p4.nompessoa as nomgerenteadjunto,
                    proj.idgerenteprojeto,
                    p3.nompessoa as nomgerenteprojeto,
                    proj.idnatureza,
                    proj.idobjetivo,
                    proj.idpatrocinador,
                    p2.nompessoa as nompatrocinador,
                    proj.idprograma,
                    proj.idprojeto,
                    proj.idsetor,
                    proj.nomcodigo,
                    proj.nomprojeto,
                    proj.nomproponente,
                    proj.nomsigla,
                    proj.numanoprojeto,
                    proj.numcriteriofarol,
                    proj.numperiodicidadeatualizacao,
                    proj.numseqprojeto,
                    proj.vlrorcamentodisponivel,
                    p.idportfolio,
                    p.tipo
            FROM
                    agepnet200.tb_projeto proj
                    left join agepnet200.tb_portfolio p
                    on p.idportfolio = proj.idportfolio,
                    agepnet200.tb_pessoa p1,
                    agepnet200.tb_pessoa p2,
                    agepnet200.tb_pessoa p3,
                    agepnet200.tb_pessoa p4

            WHERE
                    p1.idpessoa = proj.iddemandante
                    and p2.idpessoa = proj.idpatrocinador
                    and p3.idpessoa = proj.idgerenteprojeto
                    and p4.idpessoa = proj.idgerenteadjunto
                    and idprojeto = :idprojeto ";

        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        //Zend_Debug::dump('wilton');
//        Zend_Debug::dump($resultado);
        //print "<PRE>";
        //print_r($resultado);
        //Zend_Debug::dump($params['idprojeto']);exit;
        $mapperPessoa = new Default_Model_Mapper_Pessoa();
        $mapperAtividadeCron = new Projeto_Model_Mapper_Atividadecronograma();
        $mapperEscritorio = new Default_Model_Mapper_Escritorio();
        $mapperParteInteressada = new Projeto_Model_Mapper_Parteinteressada();
        $mapperStatusreport = new Projeto_Model_Mapper_Statusreport();
        $mapperPrograma = new Default_Model_Mapper_Programa();
        $mapperObjetivo = new Default_Model_Mapper_Objetivo();
        $mapperNatureza = new Default_Model_Mapper_Natureza();
        $mapperSetor = new Default_Model_Mapper_Setor();
        $mapperAcao = new Default_Model_Mapper_Acao();
        $mapperComunicacao = new Projeto_Model_Mapper_Comunicacao();
        $mapperRisco = new Projeto_Model_Mapper_Risco();
        $mapperAta = new Projeto_Model_Mapper_Ata();
        $mapperAceite = new Projeto_Model_Mapper_Aceite();


        $projeto = new Projeto_Model_Gerencia($resultado);
        //return $projeto;
        $projeto->demandante = $mapperPessoa->retornaPessoaProjeto(array('idpessoa' => $projeto->iddemandante));
        $projeto->patrocinador = $mapperPessoa->retornaPessoaProjeto(array('idpessoa' => $projeto->idpatrocinador));
        $projeto->gerenteprojeto = $mapperPessoa->retornaPessoaProjeto(array('idpessoa' => $projeto->idgerenteprojeto));
        $projeto->gerenteadjunto = $mapperPessoa->retornaPessoaProjeto(array('idpessoa' => $projeto->idgerenteadjunto));
        $projeto->ultimoStatusReport = $mapperStatusreport->retornaUltimoPorProjeto(array('idprojeto' => $projeto->idprojeto));
        $projeto->grupos = new App_Model_Relation($mapperAtividadeCron, 'retornaGrupoPorProjeto',
            array(array('idprojeto' => $projeto->idprojeto)));
        $projeto->escritorio = $mapperEscritorio->getById(array('idescritorio' => $projeto->idescritorio));
        $projeto->partes = new App_Model_Relation($mapperParteInteressada, 'retornaPartes',
            array(array('idprojeto' => $resultado['idprojeto'])));
        $projeto->programa = $mapperPrograma->getById(array('idprograma' => $resultado['idprograma']));
        $projeto->objetivo = $mapperObjetivo->getById(array('idobjetivo' => $resultado['idobjetivo']));
        $projeto->natureza = $mapperNatureza->getById(array('idnatureza' => $resultado['idnatureza']));
        $projeto->setor = $mapperSetor->getById(array('idsetor' => $resultado['idsetor']));
        $projeto->acao = $mapperAcao->getById(array('idacao' => $resultado['idacao']));
        $projeto->comunicacao = $mapperComunicacao->retornaPorProjeto(array('idprojeto' => $resultado['idprojeto']));
        $projeto->risco = $mapperRisco->retornaPorProjeto(array('idprojeto' => $resultado['idprojeto']));
        $projeto->ata = $mapperAta->retornaPorProjeto(array('idprojeto' => $resultado['idprojeto']));
        $projeto->aceite = $mapperAceite->retornaPorProjeto(array('idprojeto' => $resultado['idprojeto']));


        return $projeto;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisarProjetoCronograma($params, $paginator = false)
    {

        $sql = "SELECT
                        prog.nomprograma as nomprograma,
                        proj.nomprojeto as nomprojeto,
                        pess.nompessoa as idgerenteprojeto,
                        esc.nomescritorio as nomescritorio,
                        proj.nomcodigo as nomcodigo,
                        proj.idprojeto as idprojeto
                FROM
                        agepnet200.tb_projeto proj,
                        agepnet200.tb_programa prog,
                        agepnet200.tb_pessoa pess,
                        agepnet200.tb_escritorio esc
                WHERE 
                        prog.idprograma = proj.idprograma
                        and pess.idpessoa = proj.idgerenteprojeto 
                        and proj.idescritorio = esc.idescritorio 
                        and proj.idprojeto in (select idprojeto
						from agepnet200.tb_atividadecronograma)";

        if (isset($params['idprojeto'])) {
            $idprojeto = $params['idprojeto'];
            $sql .= " and proj.idprojeto <> {$idprojeto} ";
        }

        if (isset($params['idescritorio']) && $params['idescritorio'] != "") {
            $idescritorio = $params['idescritorio'];
            $sql .= " and proj.idescritorio = {$idescritorio}";
        }
        $params = array_filter($params);

        if (isset($params['nomprojeto'])) {
            $nomprojeto = $params['nomprojeto'];
            $sql .= " and lower(proj.nomprojeto) LIKE '%{$nomprojeto}%'";
        }

        if (isset($params['idprograma'])) {
            $idprograma = $params['idprograma'];
            $sql .= " and proj.idprograma = {$idprograma}";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function retornaUltimoSequencialPorEscritorio($params)
    {
        $sql = "
            SELECT 
                SUBSTRING(nomcodigo,1,3) as sequencial
                --nomcodigo,
                --idescritorio 
            FROM 
                agepnet200.tb_projeto 
            WHERE  	
            SUBSTRING(nomcodigo,5,4) = :ano
            AND SUBSTRING(nomcodigo,10,10) = :escritorio
            order by idprojeto desc
        ";

        $retorno = $this->_db->fetchRow($sql, array('ano' => $params['ano'], 'escritorio' => $params['escritorio']));
        return $retorno['sequencial'];

    }

    public function getQtdeProjetosPorStatus($params)
    {
        $statusprojeto = $params['domstatusprojeto'];
        $sql = " SELECT 
                     count(*) as totalprojetos
                 FROM 
                    agepnet200.tb_projeto 
                WHERE  	
                    domstatusprojeto = 1";

        $params = array_filter($params);
        if (isset($params['idescritorio']) && $params['idescritorio'] != '') {
            $idescritorio = $params['idescritorio'];
            $sql .= " and idescritorio = {$idescritorio} ";
        }

        if (isset($params['idobjetivo']) && $params['idobjetivo'] != '') {
            $idobjetivo = $params['idobjetivo'];
            $sql .= " and idobjetivo = {$idobjetivo} ";
        }

        if (isset($params['idsetor']) && $params['idsetor'] != '') {
            $idsetor = $params['idsetor'];
            $sql .= " and idsetor = {$idsetor} ";
        }
        if (isset($params['nomprojeto']) && $params['nomprojeto'] != '') {
            $nomprojeto = $params['nomprojeto'];
            $sql .= " and nomprojeto LIKE '%{$nomprojeto}%'";
        }

        if (isset($params['idacao']) && $params['idacao'] != '') {
            $idacao = $params['idacao'];
            $sql .= " and idacao = {$idacao} ";
        }
        $retorno = $this->_db->fetchRow($sql);
        return $retorno['totalprojetos'];
    }

    public function getPesquisaQtdeProjetosPorStatus($params)
    {
        $statusprojeto = $params['domstatusprojeto'];
        $sql = " SELECT 
                     count(*) as totalprojetos
                 FROM 
                    agepnet200.tb_projeto 
                WHERE  	
                    1 = 1";

        $params = array_filter($params);
        if (isset($params['nomprojeto']) && $params['nomprometo'] != '') {
            $nomprojeto = $params['nomprojeto'];
            $sql .= " and nomprojeto LIKE '%{$nomprojeto}%'";
        }
        if (isset($params['idescritorio']) && $params['idescritorio'] != '') {
            $idescritorio = $params['idescritorio'];
            $sql .= " and idescritorio = {$idescritorio} ";
        }

        if (isset($params['idobjetivo']) && $params['idobjetivo'] != '') {
            $idobjetivo = $params['idobjetivo'];
            $sql .= " and idobjetivo = {$idobjetivo} ";
        }
        if (isset($params['idacao']) && $params['idacao'] != '') {
            $idacao = $params['idacao'];
            $sql .= " and idacao = {$idacao} ";
        }

        if (isset($params['idnatureza']) && $params['idnatureza'] != '') {
            $idsetor = $params['idnatureza'];
            $sql .= " and idnatureza = {$idsetor} ";
        }

        $retorno = $this->_db->fetchRow($sql);
        return $retorno['totalprojetos'];
    }

    public function getQtdeProjetosPorStatusAcao($params)
    {
        $statusprojeto = $params['domstatusprojeto'];
        $sql = " SELECT 
                     count(*) as totalprojetos
                 FROM 
                    agepnet200.tb_projeto 
                WHERE  	
                    domstatusprojeto != 1 ";

        $params = array_filter($params);
        if (isset($params['idescritorio']) && $params['idescritorio'] != '') {
            $idescritorio = $params['idescritorio'];
            $sql .= " and idescritorio = {$idescritorio} ";
        }

        if (isset($params['idobjetivo']) && $params['idobjetivo'] != '') {
            $idobjetivo = $params['idobjetivo'];
            $sql .= " and idobjetivo = {$idobjetivo} ";
        }

        if (isset($params['idsetor']) && $params['idsetor'] != '') {
            $idsetor = $params['idsetor'];
            $sql .= " and idsetor = {$idsetor} ";
        }
        if (isset($params['nomprojeto']) && $params['nomprojeto'] != '') {
            $nomprojeto = $params['nomprojeto'];
            $sql .= " and nomprojeto LIKE '%{$nomprojeto}%'";
        }

        if (isset($params['idacao']) && $params['idacao'] != '') {
            $idacao = $params['idacao'];
            $sql .= " and idacao = {$idacao} ";
        }
        $retorno = $this->_db->fetchRow($sql);
        return $retorno;
    }

    public function getTotalProjetosPorObjetivo($idobjetivo, $params)
    {

        $sql = "SELECT 
                    count(*) as totalprojetos
                    FROM 
                    agepnet200.tb_projeto 
                    WHERE 
                    idobjetivo = " . $idobjetivo;

        if (isset($params['nomprojeto'])) {
            $nomprojeto = $params['nomprojeto'];
            $sql .= " and nomprojeto LIKE '%{$nomprojeto}%'";
        }
        if (isset($params['idescritorio'])) {
            $idescritorio = $params['idescritorio'];
            $sql .= " and idescritorio = {$idescritorio}";
        }
        if (isset($params['idprograma'])) {
            $idprograma = $params['idprograma'];
            $sql .= " and idprograma = {$idprograma}";
        }

        if (isset($params['domstatusprojeto'])) {
            $domstatusprojeto = $params['domstatusprojeto'];
            $sql .= " and domstatusprojeto = {$domstatusprojeto}";
        }

        if (isset($params['idobjetivo'])) {
            $idobjetivo = $params['idobjetivo'];
            $sql .= " and idobjetivo = {$idobjetivo}";
        }

        if (isset($params['idacao'])) {
            $idacao = $params['idacao'];
            $sql .= " and idacao = {$idacao}";
        }

        if (isset($params['idnatureza'])) {
            $idnatureza = $params['idnatureza'];
            $sql .= " and .idnatureza = {$idnatureza}";
        }

        $retorno = $this->_db->fetchRow($sql);
        return $retorno['totalprojetos'];
    }

    public function getProjetosPorEscritorio($idescritorio)
    {

        $sql = " SELECT
                       (SELECT nomprograma from agepnet200.tb_programa prog WHERE prog.idprograma = proj.idprograma) as nomprograma,
                        proj.nomprojeto
                FROM
                        agepnet200.tb_projeto proj
                WHERE 
                        proj.idescritorio = " . $idescritorio;
        $retorno = $this->_db->fetchAll($sql);
    }


    public function getTotalOrcamentarioProjetosPorPrograma($params)
    {
        $sql = " SELECT count(p.idprojeto) as total, 
                        sum(p.vlrorcamentodisponivel) as soma, 
                        prog.nomprograma, 
                        prog.idprograma 
                FROM agepnet200.tb_projeto as p,
                     agepnet200.tb_programa as prog,
                     agepnet200.tb_escritorio as esc
                WHERE p.idprograma = prog.idprograma
                      and p.idescritorio = esc.idescritorio
                      --and p.domstatusprojeto != " . Projeto_Model_Gerencia::STATUS_PROPOSTA . " 
                          and prog.idobjetivo = " . $params;


//        if( isset($params['idescritorio'])){
//            $idescritorio = $params['idescritorio'];
//            $sql .= " and esc.idescritorio = {$idescritorio}";
//        }              
        $sql .= " GROUP BY prog.nomprograma, prog.idprograma
                  ORDER BY soma DESC ";

        $retorno = $this->_db->fetchAll($sql);
        return $retorno;
    }

    public function getTotalProjetosNatureza($params)
    {

        $sql = " SELECT count(p.idprojeto) as total, 
                        n.nomnatureza,
                        n.idnatureza
                FROM agepnet200.tb_projeto as p,
                     agepnet200.tb_natureza as n,
                     agepnet200.tb_escritorio as esc
                WHERE p.idnatureza = n.idnatureza
                      and p.idescritorio = esc.idescritorio
                      --and p.domstatusprojeto != " . Projeto_Model_Gerencia::STATUS_PROPOSTA . "
                       ";

        if (isset($params['idprograma'])) {
            $idprograma = $params['idprograma'];
            $sql .= " and p.idprograma = {$idprograma}";
        }

        $sql .= " GROUP BY n.nomnatureza, n.idnatureza
                  ORDER BY total DESC ";

        $retorno = $this->_db->fetchAll($sql);
        return $retorno;

    }

    public function pesquisarProjetoPortfolio($params, $paginator = true)
    {
        $sql = " SELECT 
                        prog.nomprograma as nomprograma,
                        proj.nomprojeto as nomprojeto,
                        proj.domstatusprojeto as domstatusprojeto,
                        pess.nompessoa as idgerenteprojeto,
                        to_char(proj.datinicio,'DD/MM/YYYY') as datinicio,
                        to_char(proj.datfimplano, 'DD/MM/YYYY') as datfimplano,
                        to_char(proj.datfim, 'DD/MM/YYYY') as datfim,
                        to_char(str.datmarcotendencia,'DD/MM/YYYY') as datterminometa,
                        to_char(str.datfimprojetotendencia,'DD/MM/YYYY') as datfimprojetotendencia,
                        str.numpercentualconcluido as numpercentualconcluido,
                        str.numpercentualprevisto as numpercentualprevisto,
                        '' as prazo,
                        '' as risco,
                        '' as atraso,
                        to_char(str.datacompanhamento,'DD/MM/YYYY') as datacompanhamento,
                        proj.idprojeto as idprojeto,
                        proj.numcriteriofarol,
                        str.idstatusreport as idstatusreport,
                        esc.nomescritorio as nomescritorio,
                        str.domcorrisco as domcorrisco
                FROM    agepnet200.tb_projeto proj
                inner join agepnet200.tb_programa prog    ON prog.idprograma = proj.idprograma
                inner join agepnet200.tb_pessoa pess      ON pess.idpessoa = proj.idgerenteprojeto
                inner join agepnet200.tb_escritorio esc   ON proj.idescritorio = esc.idescritorio  
                left  join agepnet200.tb_statusreport str ON proj.idprojeto = str.idprojeto
                           and str.datacompanhamento = (select max(s.datacompanhamento)
                                                          from agepnet200.tb_statusreport s
							  where s.idprojeto = proj.idprojeto)
                where 1 = 1 ";


        if (isset($params['idescritorio'])) {
            $idescritorio = $params['idescritorio'];
            $sql .= " and proj.idescritorio = {$idescritorio}";
        }
        $params = array_filter($params);

        if (isset($params['idprograma'])) {
            $idprograma = $params['idprograma'];
            $sql .= " and proj.idprograma = {$idprograma}";
        }

        if (isset($params['domstatusprojeto'])) {
            $domstatusprojeto = $params['domstatusprojeto'];
            $sql .= " and proj.domstatusprojeto = {$domstatusprojeto}";
        }

        if (isset($params['nomprojeto'])) {
            $nomprojeto = $params['nomprojeto'];
            $sql .= " and lower(proj.nomprojeto) LIKE '%{$nomprojeto}%'";
        }

        if (isset($params['idobjetivo'])) {
            $idobjetivo = $params['idobjetivo'];
            $sql .= " and proj.idobjetivo = {$idobjetivo}";
        }

        if (isset($params['idacao'])) {
            $idacao = $params['idacao'];
            $sql .= " and proj.idacao = {$idacao}";
        }

        if (isset($params['idnatureza'])) {
            $idnatureza = $params['idnatureza'];
            $sql .= " and proj.idnatureza = {$idnatureza}";
        }

        if (isset($params['idsetor'])) {
            $idsetor = $params['idsetor'];
            $sql .= " and proj.idsetor = {$idsetor}";
        }

        if (isset($params['flacopa'])) {
            $flacopa = $params['flacopa'];
            $sql .= " and proj.flacopa = '{$flacopa}' ";
        }

        if (isset($params['cronogramadesatualizado']) && $params['cronogramadesatualizado'] == 'S') {
            $dataAtual = date("Y-m-d");
            $sql .= " and proj.idprojeto in (select idprojeto from agepnet200.tb_atividadecronograma where numpercentualconcluido <> 100 and datfim < '$dataAtual') ";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    public function buscarProjetos($params)
    {
        $sql = "SELECT
                        proj.idprojeto as idprojeto,
                        proj.nomprojeto as nomprojeto
                FROM
                        agepnet200.tb_projeto proj,
                        agepnet200.tb_programa prog,
                        agepnet200.tb_escritorio esc
                WHERE 
                        prog.idprograma = proj.idprograma
                        and proj.idescritorio = esc.idescritorio 
                        and proj.idprojeto in (select idprojeto
						from agepnet200.tb_atividadecronograma)";

        if (isset($params['idescritorio']) && $params['idescritorio'] != "") {
            $idescritorio = $params['idescritorio'];
            $sql .= " and proj.idescritorio = {$idescritorio}";
        }
        $params = array_filter($params);

        if (isset($params['nomprojeto'])) {
            $nomprojeto = $params['nomprojeto'];
            $sql .= " and lower(proj.nomprojeto) LIKE '%{$nomprojeto}%'";
        }

        if (isset($params['idprograma'])) {
            $idprograma = $params['idprograma'];
            $sql .= " and proj.idprograma = {$idprograma}";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }
        $resultado = $this->_db->fetchPairs($sql);
        return $resultado;
    }

    public function retornaCronogramaProjetos($params)
    {

        $sql = " SELECT
                    to_char(proj.datcadastro,'DD/MM/YYYY') as datcadastro,
                    to_char(proj.datenviouemailatualizacao,'DD/MM/YYYY') as datenviouemailatualizacao,
                    to_char(proj.datfim,'DD/MM/YYYY') as datfim,
                    to_char(proj.datfimplano,'DD/MM/YYYY') as datfimplano,
                    to_char(proj.datinicio,'DD/MM/YYYY') as datinicio,
                    to_char(proj.datinicioplano,'DD/MM/YYYY') as datinicioplano,
                    proj.desprojeto,
                    proj.domstatusprojeto,
                    proj.domtipoprojeto,
                    proj.flaaprovado,
                    proj.flacopa,
                    (select nomescritorio from agepnet200.tb_escritorio where idescritorio = proj.idescritorio) as idescritorio,
                    (select nomprograma from agepnet200.tb_programa where idprograma = proj.idprograma) as idprograma,
                    proj.idprojeto,
                    proj.nomcodigo,
                    proj.nomprojeto,
                    proj.nomsigla,
                    proj.numcriteriofarol,
                    proj.vlrorcamentodisponivel
            FROM
                    agepnet200.tb_projeto proj
            WHERE
                   1 = 1 ";

        if (isset($params['idprojetos']) && count($params['idprojetos']) > 0 && !empty($params['idprojetos'][0])) {
            $ids = implode(",", $params['idprojetos']);
            $sql .= " and idprojeto in ({$ids}) ";
        }

        if (isset($params['idescritorio']) && $params['idescritorio'] != "") {
            $idescritorio = $params['idescritorio'];
            $sql .= " and proj.idescritorio = {$idescritorio}";
        }
        $params = array_filter($params);
        if (isset($params['idprograma'])) {
            $idprograma = $params['idprograma'];
            $sql .= " and proj.idprograma = {$idprograma}";
        }

        if (isset($params['domstatusprojeto'])) {
            $domstatusprojeto = $params['domstatusprojeto'];
            $sql .= " and proj.domstatusprojeto = {$domstatusprojeto}";
        }
        if (isset($params['idresponsavel'])) {
            $idparteinteressada = $params['idresponsavel'];
            $sql .= " and proj.idprojeto in (select ativ.idprojeto 
                                                from agepnet200.tb_atividadecronograma ativ,
                                                     agepnet200.tb_parteinteressada par
                                                where  ativ.idparteinteressada = par.idparteinteressada
                                                and par.idpessoainterna = {$idparteinteressada}
                                                and domtipoatividade = 3 )";
        } else {
            $sql .= " and proj.idprojeto in (select idprojeto
				      from agepnet200.tb_atividadecronograma) ";
        }

        if (isset($params['nomprojeto'])) {
            $nomprojeto = $params['nomprojeto'];
            $sql .= " and lower(proj.nomprojeto) LIKE '%{$nomprojeto}%'";
        }
        $resultado = $this->_db->fetchAll($sql);

        $mapperAtividadeCron = new Projeto_Model_Mapper_Atividadecronograma();
        $mapperStatusreport = new Projeto_Model_Mapper_Statusreport();

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Gerencia');

        foreach ($resultado as $res) {
            $o = new Projeto_Model_Gerencia($res);
            $params['idprojeto'] = $o->idprojeto;

            $o->grupos = new App_Model_Relation(
                $mapperAtividadeCron, 'retornaGrupoPorProjeto', array($params));
            $o->grupos->getIterator();

            $o->ultimoStatusReport = $mapperStatusreport->retornaUltimoPorProjeto(array('idprojeto' => $o->idprojeto));
            $collection[] = $o;
        }

        return $collection;
    }

    public function fetchPairsProjeto()
    {
        $sql = "select idprojeto, nomprojeto from agepnet200.tb_projeto order by nomprojeto";
        return $this->_db->fetchPairs($sql);
    }


    public function retornaTodosOsProjetosEmAndamento()
    {
        $sql = "SELECT
                    to_char(proj.datcadastro,'DD/MM/YYYY') as datcadastro,
                    --to_char(proj.datenviouemailatualizacao,'DD/MM/YYYY') as datenviouemailatualizacao,
                    --to_char(proj.datfim,'DD/MM/YYYY') as datfim,
                    --to_char(proj.datfimplano,'DD/MM/YYYY') as datfimplano,
                    --to_char(proj.datinicio,'DD/MM/YYYY') as datinicio,
                    --to_char(proj.datinicioplano,'DD/MM/YYYY') as datinicioplano,
                    --proj.desconsideracaofinal,
                    --proj.desescopo,
                    --proj.desjustificativa,
                    --proj.desnaoescopo,
                    --proj.desobjetivo,
                    --proj.despontosfortes,
                    --proj.despontosfracos,
                    --proj.despremissa,
                    --proj.desprojeto,
                    --proj.desrestricao,
                    --proj.desresultadosobtidos,
                    --proj.dessugestoes,
                    proj.domstatusprojeto,
                    --proj.domtipoprojeto,
                    --proj.flaaltagestao,
                    --proj.flaaprovado,
                    --proj.flacopa,
                    --proj.flapublicado,
                    --proj.idacao,
                    --proj.idcadastrador,
                    --proj.iddemandante,
                    proj.idescritorio,
                    e.nomescritorio as nomescritorio,
                    proj.idgerenteadjunto,
                    p4.nompessoa as nomgerenteadjunto,
                    p4.desemail as emailgerenteadjunto,
                    proj.idgerenteprojeto,
                    p3.nompessoa as nomgerenteprojeto,
                    p3.desemail as emailgerenteprojeto,
                    --proj.idnatureza,
                    --proj.idobjetivo,
                    proj.idpatrocinador,
                    p2.nompessoa as nompatrocinador,
                    p2.desemail as emailpatrocinador,
                    --proj.idprograma,
                    proj.idprojeto,
                    --proj.idsetor,
                    proj.nomcodigo,
                    proj.nomprojeto,
                    --proj.nomproponente,
                    --proj.nomsigla,
                    --proj.numanoprojeto,
                    --proj.numcriteriofarol,
                    proj.numperiodicidadeatualizacao
                    --proj.numseqprojeto,
                    --proj.vlrorcamentodisponivel
            FROM
                    agepnet200.tb_projeto proj,
                    agepnet200.tb_pessoa p2,
                    agepnet200.tb_pessoa p3,
                    agepnet200.tb_pessoa p4,
                    agepnet200.tb_escritorio e
            WHERE
                    domstatusprojeto = 2
                    and p2.idpessoa = proj.idpatrocinador
                    and p3.idpessoa = proj.idgerenteprojeto
                    and p4.idpessoa = proj.idgerenteadjunto
                    and e.idescritorio = proj.idescritorio";
//        $sql .= " ORDER BY proj.nomprojeto ASC";

        $resultado = $this->_db->fetchAll($sql);

        $mapperStatusreport = new Projeto_Model_Mapper_Statusreport();

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Gerencia');

        foreach ($resultado as $res) {
            $o = new Projeto_Model_Gerencia($res);
            $params['idprojeto'] = $o->idprojeto;

            $o->ultimoStatusReport = $mapperStatusreport->retornaUltimoPorProjeto(array('idprojeto' => $o->idprojeto));
            $o->ultimoStatusReport->getIterator();
            $collection[] = $o;
        }

        return $collection;

        /*$projeto = new Projeto_Model_Gerencia($resultado);
        //return $projeto;
        $projeto->ultimoStatusReport = $mapperStatusreport->retornaUltimoPorProjeto(array('idprojeto' => $projeto->idprojeto));


        return $projeto;*/
    }

    /**
     * Set the property
     *
     * @param string $value
     */
    public function alterarStatusProjeto($params)
    {
//        Zend_Debug::dump($params); exit;

        $data = array(
            "idprojeto" => $params['idprojeto'],
//            "desjustificativa" => $params['desjustificativa'],
            "domstatusprojeto" => $params['domstatusprojeto'],
        );

//        $data = array_filter($data);

//        Zend_Debug::dump($data); exit;

        try {
            $pks = array("idprojeto" => $params['idprojeto']);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            return $this->getDbTable()->update($data, $where);
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     */
    public function registrarBloqueioProjeto($params)
    {
//        Zend_Debug::dump($params); exit;

        $data = array(
            "idprojeto" => $params['idprojeto'],
//            "desjustificativa" => $params['desjustificativa'],
            "domstatusprojeto" => $params['domstatusprojeto'],
        );

//        $data = array_filter($data);

//        Zend_Debug::dump($data); exit;

        try {
            $pks = array("idprojeto" => $params['idprojeto']);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            return $this->getDbTable()->update($data, $where);
        } catch (Exception $exc) {
            throw $exc;
        }
    }
}
