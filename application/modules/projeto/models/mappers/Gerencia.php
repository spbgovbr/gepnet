<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Gerencia extends App_Model_Mapper_MapperAbstract
{
    private $stringsEncoded = 'áàâãäåaaaÁÂÃÄÅAAAÀéèêëeeeeeEEEÉEEÈìíîïìiiiÌÍÎÏÌIIIóôõöoooòÒÓÔÕÖOOOùúûüuuuuÙÚÛÜUUUUçÇñÑýÝ';
    private $stringsDecoded = 'aaaaaaaaaAAAAAAAAAeeeeeeeeeEEEEEEEiiiiiiiiIIIIIIIIooooooooOOOOOOOOuuuuuuuuUUUUUUUUcCnNyY';
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
            "idescritorio" => (int)$model->idescritorio,
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
            "idprograma" => $model->idprograma,
            "nomproponente" => $model->nomproponente,
            "numseqprojeto" => $model->numanoprojeto,
            "ano" => $model->ano,
            "idacao" => $model->idacao,
            "idportfolio" => $model->idportfolio,
            "idtipoiniciativa" => Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO,
            "numprocessosei" => $model->numprocessosei,
            'atraso' => $model->atraso,
            'domcoratraso' => $model->domcoratraso,
            'numpercentualconcluidomarco' => $model->getPercentualConcluidoMarco()
        );
        $data = array_filter($data);
        $data["idescritorio"] = (int)$model->idescritorio;
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
     * @return Projeto_Model_Gerencia
     */
    public function update(Projeto_Model_Gerencia $model)
    {
        $datainicio = !empty($model->datinicio) ? new Zend_Db_Expr("to_date('" . $model->datinicio->toString('Y-m-d') . "','YYYY-MM-DD')") : '';
        $datafim = !empty($model->datfim) ? new Zend_Db_Expr("to_date('" . $model->datfim->toString('Y-m-d') . "','YYYY-MM-DD')") : "";
        $datainicioplano = !empty($model->datinicioplano) ? new Zend_Db_Expr("to_date('" . $model->datinicioplano->toString('Y-m-d') . "','YYYY-MM-DD')") : '';
        $datafimplano = !empty($model->datfimplano) ? new Zend_Db_Expr("to_date('" . $model->datfimplano->toString('Y-m-d') . "','YYYY-MM-DD')") : '';
        $numprocessosei = preg_replace('/[^0-9]/i', '', $model->numprocessosei);

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
            "numpercentualconcluido" => (@trim($model->numpercentualconcluido) == "" ? $model->numpercentualconcluido : ""),
            "numpercentualprevisto" => (@trim($model->numpercentualprevisto) == "" ? $model->numpercentualprevisto : ""),
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
            "idprograma" => $model->idprograma,
            "nomproponente" => $model->nomproponente,
            "numseqprojeto" => $model->numanoprojeto,
            "idacao" => $model->idacao,
            "idportfolio" => $model->idportfolio,
            "ano" => $model->ano,
            "idtipoiniciativa" => (@trim($model->idtipoiniciativa) == "" ? Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO : $model->idtipoiniciativa),
            "numprocessosei" => $numprocessosei,
            'atraso' => $model->atraso,
            'domcoratraso' => $model->domcoratraso,
            'numpercentualconcluidomarco' => $model->getPercentualConcluidoMarco()
        );

        $data = array_filter($data);

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
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     * @param array $params
     */
    public function removePapeisNoProjeto($params)
    {
        if ($params['papel'] == 1) {
            $data = array("idgerenteprojeto" => null);
        } elseif ($params['papel'] == 2) {
            $data = array("idgerenteadjunto" => null);
        } elseif ($params['papel'] == 3) {
            $data = array("iddemandante" => null);
        } elseif ($params['papel'] == 4) {
            $data = array("idpatrocinador" => null);
        }
        try {
            $pks = array("idprojeto" => $params['idprojeto']);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
        } catch (Exception $exc) {
            throw $exc;
        }
    }


    /**
     * @param Projeto_Model_Statusreport $model
     *
     * @return void| boolean
     */
    public function atualizaStatusProjeto($model)
    {
        if ($model) {
            $data = array("domstatusprojeto" => $model->domstatusprojeto);
            try {
                $pks = array("idprojeto" => (int)$model->idprojeto);
                $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
                $this->getDbTable()->update($data, $where);
                return true;
            } catch (Exception $exc) {
                throw $exc;
                return false;
            }
        }
    }

    public function retornaIdParteProjeto($params)
    {

        $sql = "SELECT pi.idprojeto, pi.idpessoainterna, pi.idparteinteressada, ARRAY_TO_STRING(ARRAY_AGG(pi.funcao::VARCHAR), '&&&&&', '') AS funcao
                      FROM (SELECT pi.idprojeto,
                               pi.idpessoainterna, 
                               pi.idparteinteressada, 
                               'Demandante' AS funcao
                          FROM agepnet200.tb_parteinteressada pi 
                          JOIN agepnet200.tb_projeto pd 
                            ON pd.idprojeto = pi.idprojeto 
                           AND pd.iddemandante = pi.idpessoainterna
                           
                         UNION ALL 
                         
                        SELECT pi.idprojeto,
                               pi.idpessoainterna, 
                               pi.idparteinteressada, 
                               'Patrocinador' AS funcao
                          FROM agepnet200.tb_parteinteressada pi 
                          JOIN agepnet200.tb_projeto pp 
                            ON pp.idprojeto = pi.idprojeto 
                           AND pp.idpatrocinador = pi.idpessoainterna
                    
                         UNION ALL 
                         
                        SELECT pi.idprojeto, 
                               pi.idpessoainterna, 
                               pi.idparteinteressada, 
                               'Gerente Adjunto do Projeto' AS funcao
                          FROM agepnet200.tb_parteinteressada pi 
                          JOIN agepnet200.tb_projeto pga 
                            ON pga.idprojeto = pi.idprojeto 
                           AND pga.idgerenteadjunto = pi.idpessoainterna
                    
                         UNION ALL 
                         
                        SELECT pi.idprojeto,
                               pi.idpessoainterna, 
                               pi.idparteinteressada, 
                               'Gerente do Projeto' AS funcao
                          FROM agepnet200.tb_parteinteressada pi 
                          JOIN agepnet200.tb_projeto pgp 
                            ON pgp.idprojeto = pi.idprojeto 
                           AND pgp.idgerenteprojeto = pi.idpessoainterna
                           ) pi
                     WHERE pi.idprojeto = :idprojeto ";

        if (isset($params['idpessoainterna'])) {
            $sql .= "AND pi.idpessoainterna = {$params['idpessoainterna']} ";
        }
        $sql .= "GROUP BY pi.idprojeto, pi.idpessoainterna, pi.idparteinteressada";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto']
        ));

        return $resultado;
    }

    public function retornaPartesDoProjeto($params)
    {

        $sql = "SELECT ";

        if ('DEMANDANTE' == $params['tipo']) {
            $sql .= "p.iddemandante, pi.idparteinteressada ";
        } elseif ('GERENTEPRJ' == $params['tipo']) {
            $sql .= "p.idgerenteprojeto, pi.idparteinteressada ";
        } elseif ('GERENTEADJ' == $params['tipo']) {
            $sql .= "p.idgerenteadjunto, pi.idparteinteressada ";
        } elseif ('PATROCINADOR' == $params['tipo']) {
            $sql .= "p.idpatrocinador, pi.idparteinteressada ";
        }
        $sql .= "FROM agepnet200.tb_projeto p 
               LEFT JOIN agepnet200.tb_parteinteressada pi 
                ON pi.idprojeto=p.idprojeto ";

        if ('DEMANDANTE' == $params['tipo']) {
            $sql .= "and pi.idpessoainterna=p.iddemandante ";
        } elseif ('GERENTEPRJ' == $params['tipo']) {
            $sql .= "and pi.idpessoainterna=p.idgerenteprojeto ";
        } elseif ('GERENTEADJ' == $params['tipo']) {
            $sql .= "and pi.idpessoainterna=p.idgerenteadjunto ";
        } elseif ('PATROCINADOR' == $params['tipo']) {
            $sql .= "and pi.idpessoainterna=p.idpatrocinador ";
        }
        $sql .= "WHERE pi.idprojeto = :idprojeto ";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto']
        ));

        return $resultado;

    }


    public function isFuncaoParaPessoaInternaTAP($params)
    {

        $sql = "SELECT pi.nomparteinteressada, 
                    CASE 
                       WHEN p.iddemandante=pi.idpessoainterna THEN 'DEMANDANTE'
                       WHEN p.idgerenteprojeto=pi.idpessoainterna THEN 'GERENTEPR'
                       WHEN p.idpatrocinador=pi.idpessoainterna THEN 'PATROCINADOR'
                       ELSE 'GERENTEAD'
                       END AS funcaoTAP
                FROM agepnet200.tb_parteinteressada pi 
                INNER JOIN agepnet200.tb_projeto p 
                ON p.idprojeto=pi.idprojeto 
                WHERE pi.idprojeto = :idprojeto ";

        if (isset($params) && (!empty($params['idpessoainterna']))) {
            $sql .= "AND pi.idpessoainterna={$params['idpessoainterna']}";
        }

        $sql .= "GROUP BY pi.nomparteinteressada,p.iddemandante,p.idgerenteprojeto,p.idpatrocinador, pi.idpessoainterna";

        $resultado = $this->_db->fetchAll($sql, array(
            'idprojeto' => $params['idprojeto']
        ));

        return $resultado;
    }

    public function atualizaDemandanteProjeto($params)
    {
        $data = array("iddemandante" => $params['iddemandante']);
        try {
            $pks = array("idprojeto" => (int)$params['idprojeto']);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return true;
        } catch (Exception $exc) {
            throw $exc;
            return false;
        }
    }

    public function atualizaGerenteAdjuntoProjeto($params)
    {
        $data = array("idgerenteadjunto" => (int)$params['idgerenteadjunto']);
        try {
            $pks = array("idprojeto" => (int)$params['idprojeto']);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return true;
        } catch (Exception $exc) {
            throw $exc;
            return false;
        }
    }

    public function atualizaAtrasoEPercentualMarcoProjeto($params)
    {
        try {
            $data = array(
                "atraso" => $params['atraso'],
                "domcoratraso" => $params['domcoratraso'],
                "numpercentualconcluidomarco" => $params['numpercentualconcluidomarco'],
            );
            $pks = array("idprojeto" => $params['idprojeto']);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function identificadorPapelTAP($params)
    {

        $sql = "SELECT
                    ( SELECT
                        CASE
                           WHEN p1.idgerenteprojeto is not null  THEN TRUE
                           ELSE FALSE
                        END AS gerente
                      FROM agepnet200.tb_projeto p1
                      WHERE p1.idprojeto=p.idprojeto AND p1.idgerenteprojeto={$params['idpessoainterna']}
                    ) as gerente,
                    ( SELECT
                        CASE
                           WHEN p1.idgerenteadjunto is not null  THEN TRUE
                           ELSE FALSE
                        END AS adjunto
                      FROM agepnet200.tb_projeto p1
                      WHERE p1.idprojeto=p.idprojeto AND p1.idgerenteadjunto={$params['idpessoainterna']}
                    ) as adjunto,
                    ( SELECT
                        CASE
                           WHEN p1.iddemandante is not null  THEN TRUE
                           ELSE FALSE
                        END AS demandante
                      FROM agepnet200.tb_projeto p1
                      WHERE p1.idprojeto=p.idprojeto AND p1.iddemandante={$params['idpessoainterna']}
                    ) as demandante,
                    ( SELECT
                        CASE
                           WHEN p1.idpatrocinador is not null  THEN TRUE
                           ELSE FALSE
                        END AS patrocinador
                      FROM agepnet200.tb_projeto p1
                      WHERE p1.idprojeto=p.idprojeto AND p1.idpatrocinador={$params['idpessoainterna']}
                    ) as patrocinador
                FROM agepnet200.tb_projeto p
                WHERE p.idprojeto={$params['idprojeto']} ";

        $resultado = $this->_db->fetchRow($sql);

        return $resultado;
    }


    public function atualizaNumeroSEIProjeto($params)
    {
        try {
            $data = array("numprocessosei" => $params['numprocessosei']);
            $pks = array("idprojeto" => $params['idprojeto']);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
        } catch (Exception $exc) {
            throw $exc;
        }
    }


    /**
     *
     * @param array $params
     */
    public function atualizaPercentualProjeto($params)
    {
        if (!((null === $params['numpercentualprevisto']) || (null === $params['numpercentualconcluido']))) {
            $data = array(
                "numpercentualprevisto" => $params['numpercentualprevisto'],
                "numpercentualconcluido" => $params['numpercentualconcluido'],
            );
            try {
                $pks = array("idprojeto" => $params['idprojeto']);
                $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
                $this->getDbTable()->update($data, $where);
            } catch (Exception $exc) {
                throw $exc;
            }
        } else {
            return true;
        }
    }

    /**
     *
     * @param array $params
     */
    public function atualizarValoresAtividadesPorProjeto($params)
    {
        if (!((null === $params['qtdeatividadeiniciada']) || (null === $params['numpercentualiniciado'])) ||
            !((null === $params['qtdeatividadenaoiniciada']) || (null === $params['numpercentualnaoiniciado'])) ||
            !((null === $params['qtdeatividadeconcluida']) || (null === $params['numpercentualatividadeconcluido']))
        ) {
            $data = array(
                "qtdeatividadeiniciada" => $params['qtdeatividadeiniciada'],
                "numpercentualiniciado" => $params['numpercentualiniciado'],

                "qtdeatividadenaoiniciada" => $params['qtdeatividadenaoiniciada'],
                "numpercentualnaoiniciado" => $params['numpercentualnaoiniciado'],

                "qtdeatividadeconcluida" => $params['qtdeatividadeconcluida'],
                "numpercentualatividadeconcluido" => $params['numpercentualatividadeconcluido'],
            );

            try {
                $pks = array("idprojeto" => $params['idprojeto']);
                $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
                $this->getDbTable()->update($data, $where);
            } catch (Exception $exc) {
                throw $exc;
            }
        } else {
            return true;
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
        $params = array_filter($params);
        $sql = " SELECT
                        proj.idprojeto,
                        (
                            select str.domstatusprojeto
                            from agepnet200.tb_statusreport str
                            where str.idprojeto = proj.idprojeto
                            order by str.datacompanhamento desc, str.idstatusreport desc
                            limit 1
                        ) as domstatusprojeto,
                        proj.nomprojeto,
                       (SELECT nomprograma from agepnet200.tb_programa prog WHERE prog.idprograma = proj.idprograma) as nomprograma,
                        proj.idprograma,
                        (SELECT upper(pess.nompessoa) FROM agepnet200.tb_pessoa pess WHERE pess.idpessoa = proj.idgerenteprojeto) as nomgerenteprojeto,
                        proj.idgerenteprojeto as idgerenteprojeto,
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
						(select
							DATE_PART('day', CONCAT(to_char(proj.datfim,  'YYYY-MM-DD'),' ','00:00:00.0000000')::timestamp -
									 CONCAT(to_char(atc.datfimbaseline, 'YYYY-MM-DD'),' ','23:59:59.9999999')::timestamp) as atraso
							from agepnet200.tb_atividadecronograma atc
							where proj.idprojeto = atc.idprojeto and atc.datfimbaseline is not null 
							order by datfimbaseline desc limit 1) as atraso,											  																																																	  																																											
                        ( select to_char(sr.datacompanhamento, 'DD/MM/YYYY')
                            from agepnet200.tb_statusreport sr where sr.idprojeto =  proj.idprojeto
                            order by sr.datacompanhamento DESC, idstatusreport desc LIMIT 1) as ultimoacompanhamento,
                        ( select to_char(sr.datacompanhamento, 'YYYYMMDD')
                            from agepnet200.tb_statusreport sr where sr.idprojeto =  proj.idprojeto
                            order by sr.datacompanhamento DESC, idstatusreport desc LIMIT 1) as orderultimoacompanhamento,-- Para fazer a ordenacao
                        proj.numcriteriofarol,
                        proj.numperiodicidadeatualizacao,
                        proj.idtipoiniciativa,
                        proj.idescritorio,
                        proj.idobjetivo,
                        proj.idacao,
                        proj.idnatureza,
                        proj.idsetor,
                        (SELECT nomescritorio FROM agepnet200.tb_escritorio where idescritorio = proj.idescritorio) as nomescritorio,
						(SELECT to_char(datfimbaseline, 'DD/MM/YYYY') FROM agepnet200.tb_atividadecronograma where idprojeto = proj.idprojeto and datfimbaseline is not null order by datfimbaseline desc limit 1 ) as datfimbaseline																																																											 
                FROM
                        agepnet200.tb_projeto proj
                WHERE proj.idtipoiniciativa = 1 ";

        if (@trim($params['idprojeto']) != "") {
            $idprojeto = $params['idprojeto'];
            $sql .= " and proj.idprojeto = {$idprojeto}";
        }

        if (isset($idperfil) && $idperfil <> 1 && (!isset($params['idescritorio']))) {
            $sql .= " and proj.idescritorio = {$idescritorio}";
        }

        if (@trim($params['nomprojeto']) != "") {
            $nomprojeto = strtoupper($params['nomprojeto']);
            $sql .= " and translate(upper(proj.nomprojeto),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "') "
                . " LIKE ( "
                . " '%' ||
                translate(upper('{$nomprojeto}'),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "')
                || '%')";
        }

        if (((@trim($params['idprograma']) != "")) && ((@trim($params['idprograma']) != "0"))) {
            $idprograma = $params['idprograma'];
            $sql .= " and proj.idprograma = {$idprograma}";
        }

        if (isset($params['idescritorio'])) {
            if (((@trim($params['idescritorio']) <> 9999))) {
                $idescritorio = $params['idescritorio'];
                $sql .= " and proj.idescritorio = {$idescritorio}";
            } elseif (((@trim($params['idescritorio']) == 9999))) {
                $sql .= " and proj.flapublicado in('S')";
            }
        }

        if (@trim($params['codobjetivo']) != "") {
            if (((@trim($params['codobjetivo']) != "")) && ((@trim($params['codobjetivo']) != "0"))) {
                $status = $params['codobjetivo'];
                $sql .= " and proj.idobjetivo = '{$status}'";
            }
        }
        if (@trim($params['codacao']) != "") {
            $status = $params['codacao'];
            $sql .= " and proj.idacao = '{$status}'";
        }
        if (@trim($params['codnatureza']) != "") {
            $status = $params['codnatureza'];
            $sql .= " and proj.idnatureza = '{$status}'";
        }
        if (@trim($params['codsetor']) != "") {
            $status = $params['codsetor'];
            $sql .= " and proj.idsetor = '{$status}' ";
        }
        if (isset($params['domstatusprojeto'])) {
            if (((@trim($params['domstatusprojeto']) != "")) && ((@trim($params['domstatusprojeto']) != "0"))) {

                $domstatusprojeto = $params['domstatusprojeto'];
                $sql .= " and  (
                            select str.domstatusprojeto
                            from agepnet200.tb_statusreport str
                            where str.idprojeto = proj.idprojeto
                            order by str.datacompanhamento desc, str.idstatusreport desc
                            limit 1
                        ) = {$domstatusprojeto}";
            }
        } else {
            $sql .= " and (
                            select str.domstatusprojeto
                            from agepnet200.tb_statusreport str
                            where str.idprojeto = proj.idprojeto
                            order by str.datacompanhamento desc, str.idstatusreport desc
                            limit 1
                        ) != " . Projeto_Model_Gerencia::STATUS_EXCLUIDO;
        }
        if (@trim($params['sidx']) != "") {
            $sql .= " order by " . ($params['sidx'] == "situacao" ? " domstatusprojeto " :
                    ($params['sidx'] == "idgerenteprojeto" ? " nomgerenteprojeto " :
                        ($params['sidx'] == "nomprograma" ? " 4 " :
                            ($params['sidx'] == "datinicio" ? " proj.datinicio " :
                                ($params['sidx'] == "datfimplano" ? " proj.datfimplano " :
                                    ($params['sidx'] == "datfim" ? " proj.datfim " :
                                        ($params['sidx'] == "nomescritorio" ? " 28 " :
                                            ($params['sidx'] == "ultimoacompanhamento" ? " orderultimoacompanhamento " :
                                                " upper(" . $params['sidx'] . ") ")))))))) . $params['sord'];
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

    public function pesquisarGerenciaProjeto(
        $params,
        $idperfil = null,
        $idescritorio = null,
        $idpessoa = null,
        $paginator = false,
        $publicado = null
    ) {

        $params = array_filter($params);

        $sql = "SELECT
                    proj.idprojeto,
                    (SELECT
                        CASE
                            WHEN str.domstatusprojeto='1' THEN 'Proposta'
                            WHEN str.domstatusprojeto='2' THEN 'Em Andamento'
                            WHEN str.domstatusprojeto='3' THEN 'Concluido'
                            WHEN str.domstatusprojeto='4' THEN 'Paralisado'
                            WHEN str.domstatusprojeto='5' THEN 'Cancelado'
                            WHEN str.domstatusprojeto='6' THEN 'Bloqueado'
                            WHEN str.domstatusprojeto='7' THEN 'Em Alteracao'
                            WHEN str.domstatusprojeto='8' THEN 'Excluído'
                        END
                      FROM agepnet200.tb_statusreport str
                      WHERE str.idprojeto = proj.idprojeto
                      ORDER BY str.datacompanhamento DESC, str.idstatusreport DESC limit 1) AS domstatusprojeto, 
                    proj.nomprojeto,
                    (SELECT nomprograma 
                      FROM agepnet200.tb_programa prog 
                      WHERE prog.idprograma = proj.idprograma) AS nomprograma,
                    proj.idprograma,
                    (SELECT upper(pess.nompessoa) 
                      FROM agepnet200.tb_pessoa pess 
                      WHERE pess.idpessoa = proj.idgerenteprojeto) AS nomgerenteprojeto,
                    proj.idgerenteprojeto as idgerenteprojeto,
                    proj.nomcodigo,
                    CASE proj.flapublicado
                        WHEN 'S' THEN 'SIM'
                        WHEN 'N' THEN 'NAO'
                    END AS flapublicado,
                    proj.flapublicado AS stflapublicado,
                    to_char(proj.datinicio, 'DD/MM/YYYY') AS datinicio,
                    to_char(proj.datfimplano, 'DD/MM/YYYY') AS datfimplano,
                    to_char(proj.datfim, 'DD/MM/YYYY') AS datfim,
                    '' AS previsto,
                    '' AS concluido,
                    '' AS prazo,
                    '' AS risco,
                    (SELECT	
                        DATE_PART('day', CONCAT(to_char(proj.datfim,  'YYYY-MM-DD'),' ','00:00:00.0000000')::timestamp - 
                        CONCAT(to_char(atc.datfimbaseline, 'YYYY-MM-DD'),' ','23:59:59.9999999')::timestamp) as atraso
                     FROM agepnet200.tb_atividadecronograma atc 
                     WHERE proj.idprojeto = atc.idprojeto AND atc.datfimbaseline IS NOT NULL 
                     ORDER BY datfimbaseline DESC limit 1) AS atraso,											  													
                    (SELECT to_char(sr.datacompanhamento, 'DD/MM/YYYY')
                      FROM agepnet200.tb_statusreport sr WHERE sr.idprojeto =  proj.idprojeto
                      ORDER BY sr.datacompanhamento DESC, idstatusreport DESC LIMIT 1) AS ultimoacompanhamento,
                    (SELECT to_char(sr.datacompanhamento, 'YYYYMMDD')
                      FROM agepnet200.tb_statusreport sr 
                      WHERE sr.idprojeto =  proj.idprojeto
                      ORDER BY sr.datacompanhamento DESC, idstatusreport DESC LIMIT 1) AS orderultimoacompanhamento,
                    (SELECT sr.datacompanhamento
                      FROM agepnet200.tb_statusreport sr 
                      WHERE sr.idprojeto =  proj.idprojeto
                      ORDER BY sr.datacompanhamento DESC, idstatusreport DESC LIMIT 1) AS dtultimoacompanhamento, 
                    proj.numcriteriofarol,
                    proj.numperiodicidadeatualizacao,
                    proj.idtipoiniciativa,
                    proj.idescritorio,
                    proj.idobjetivo,
                    proj.idacao,
                    proj.idnatureza,
                    proj.idsetor,
                    proj.idgerenteadjunto,
                    e.nomescritorio AS nomescritorio,                    
                    (SELECT to_char(datfimbaseline, 'DD/MM/YYYY') FROM agepnet200.tb_atividadecronograma 
                     WHERE idprojeto = proj.idprojeto 
                     AND datfimbaseline IS NOT NULL ORDER BY datfimbaseline DESC limit 1 ) AS datfimbaseline,
                    (SELECT to_char(sr.datfimprojetotendencia, 'YYYYMMDD')
                      FROM agepnet200.tb_statusreport sr 
                      WHERE sr.idprojeto =  proj.idprojeto
                      ORDER BY idstatusreport DESC, datfimprojetotendencia DESC  LIMIT 1) AS terminotendencia,
                    (SELECT str.domstatusprojeto
                      FROM agepnet200.tb_statusreport str
                      WHERE str.idprojeto = proj.idprojeto
                      ORDER BY str.datacompanhamento DESC, str.idstatusreport DESC limit 1) AS numdomstatusprojeto,
                    proj.atraso,
                    proj.domcoratraso,
                    proj.numpercentualconcluidomarco,
                   COALESCE (proj.numpercentualconcluido::NUMERIC, 0) AS numpercentualconcluido,
                   COALESCE (proj.numpercentualprevisto::NUMERIC, 0) AS numpercentualprevisto
            FROM
                    agepnet200.tb_projeto proj  
            INNER JOIN agepnet200.tb_escritorio e
              ON e.idescritorio = proj.idescritorio
            WHERE proj.idtipoiniciativa = 1 ";

        if (!empty($params['idprojeto']) && trim($params['idprojeto'])) {
            $idprojeto = $params['idprojeto'];
            $sql .= " AND proj.idprojeto = {$idprojeto}";
        }

        if (isset($idperfil) && $idperfil <> 1) {
            $sql .= " AND proj.idescritorio IN($idescritorio) ";
        }

        $sql .= " AND (
                        select str.domstatusprojeto
                        from agepnet200.tb_statusreport str
                        where str.idprojeto = proj.idprojeto
                        order by str.datacompanhamento desc, str.idstatusreport desc
                        limit 1
                    ) != " . Projeto_Model_Gerencia::STATUS_EXCLUIDO;

        if (isset($idpessoa) && !empty($idpessoa) && isset($idperfil) && $idperfil > Default_Model_Perfil::ESCRITORIO_DE_PROJETOSEGPE_CIGE) {
            $sql .= " AND
                        CASE 
                        WHEN proj.idgerenteprojeto = {$idpessoa}	THEN proj.idgerenteprojeto = {$idpessoa} AND proj.flapublicado IN ('S','N') 
                        WHEN proj.idgerenteadjunto = {$idpessoa}	THEN proj.idgerenteadjunto = {$idpessoa} AND proj.flapublicado IN ('S','N') 
                        ELSE 
                            CASE 
				                WHEN 'publico/privado' = '{$publicado}' THEN proj.flapublicado IN ('S','N')                              
				                ELSE proj.flapublicado IN ('S') 
                            END
                        END ";
        } else {
            $sql .= "AND
                    CASE 
                        WHEN 'publico/privado' = '{$publicado}' THEN proj.flapublicado IN ('S','N')                             
                        ELSE proj.flapublicado IN ('S')
                    END";
        }

        if (!empty($params['sidx']) && trim($params['sidx'])) {
            $sql .= " ORDER BY " . ($params['sidx'] == "situacao" ? " domstatusprojeto " :
                    ($params['sidx'] == "idgerenteprojeto" ? " nomgerenteprojeto " :
                        ($params['sidx'] == "nomprograma" ? " 4 " :
                            ($params['sidx'] == "datinicio" ? " proj.datinicio " :
                                ($params['sidx'] == "datfim" ? " proj.datfim " :
                                    ($params['sidx'] == "datfimplano" ? " terminotendencia " :
                                        ($params['sidx'] == "nomescritorio" ? " 28 " :
                                            ($params['sidx'] == "ultimoacompanhamento" ? " orderultimoacompanhamento " :
                                                " upper(proj." . $params['sidx'] . ") ")))))))) . $params['sord'];
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

    public function filtrarProjeto(
        $params,
        $idperfil = null,
        $idescritorio = null,
        $idpessoa = null,
        $paginator = false,
        $publicado = null
    ) {
        $params = array_filter($params);

        $sql = " 
            SELECT proj.* FROM 
                (SELECT
                        proj.idprojeto,
                        (SELECT
                            CASE
                                WHEN str.domstatusprojeto='1' THEN 'Proposta'
                                WHEN str.domstatusprojeto='2' THEN 'Em Andamento'
                                WHEN str.domstatusprojeto='3' THEN 'Concluido'
                                WHEN str.domstatusprojeto='4' THEN 'Paralisado'
                                WHEN str.domstatusprojeto='5' THEN 'Cancelado'
                                WHEN str.domstatusprojeto='6' THEN 'Bloqueado'
                                WHEN str.domstatusprojeto='7' THEN 'Em Alteracao'
                                WHEN str.domstatusprojeto='8' THEN 'Excluído'
                            END
                          FROM agepnet200.tb_statusreport str
                          WHERE str.idprojeto = proj.idprojeto
                          ORDER BY str.datacompanhamento DESC, str.idstatusreport DESC limit 1) AS domstatusprojeto, 
                        proj.nomprojeto,
                        (SELECT nomprograma 
                          FROM agepnet200.tb_programa prog 
                          WHERE prog.idprograma = proj.idprograma) AS nomprograma,
                        proj.idprograma,
                        (SELECT upper(pess.nompessoa) 
                          FROM agepnet200.tb_pessoa pess 
                          WHERE pess.idpessoa = proj.idgerenteprojeto) AS nomgerenteprojeto,
                        proj.idgerenteprojeto as idgerenteprojeto,
                        proj.nomcodigo,
                        CASE proj.flapublicado
                            WHEN 'S' THEN 'SIM'
                            WHEN 'N' THEN 'NAO'
                        END AS flapublicado,
                        proj.flapublicado AS stflapublicado,
                        to_char(proj.datinicio, 'DD/MM/YYYY') AS datinicio,
                        to_char(proj.datfimplano, 'DD/MM/YYYY') AS datfimplano,
                        to_char(proj.datfim, 'DD/MM/YYYY') AS datfim,
                        '' AS previsto,
                        '' AS concluido,
                        '' AS prazo,
                        '' AS risco,
						(SELECT	
						    DATE_PART('day', CONCAT(to_char(proj.datfim,  'YYYY-MM-DD'),' ','00:00:00.0000000')::timestamp - 
						    CONCAT(to_char(atc.datfimbaseline, 'YYYY-MM-DD'),' ','23:59:59.9999999')::timestamp) as atraso
						 FROM agepnet200.tb_atividadecronograma atc 
						 WHERE proj.idprojeto = atc.idprojeto AND atc.datfimbaseline IS NOT NULL 
						 ORDER BY datfimbaseline DESC limit 1) AS atraso,											  													
                        (SELECT to_char(sr.datacompanhamento, 'DD/MM/YYYY')
                          FROM agepnet200.tb_statusreport sr WHERE sr.idprojeto =  proj.idprojeto
                          ORDER BY sr.datacompanhamento DESC, idstatusreport DESC LIMIT 1) AS ultimoacompanhamento,
                        (SELECT to_char(sr.datacompanhamento, 'YYYYMMDD')
                          FROM agepnet200.tb_statusreport sr 
                          WHERE sr.idprojeto =  proj.idprojeto
                          ORDER BY sr.datacompanhamento DESC, idstatusreport DESC LIMIT 1) AS orderultimoacompanhamento,
                        (SELECT sr.datacompanhamento
                          FROM agepnet200.tb_statusreport sr 
                          WHERE sr.idprojeto =  proj.idprojeto
                          ORDER BY sr.datacompanhamento DESC, idstatusreport DESC LIMIT 1) AS dtultimoacompanhamento, 
                        proj.numcriteriofarol,
                        proj.numperiodicidadeatualizacao,
                        proj.idtipoiniciativa,
                        proj.idescritorio,
                        proj.idobjetivo,
                        proj.idacao,
                        proj.idnatureza,
                        proj.idsetor,
                        proj.idgerenteadjunto,
                        (SELECT nomescritorio 
                         FROM agepnet200.tb_escritorio 
                         WHERE idescritorio = proj.idescritorio) AS nomescritorio,
                        (SELECT to_char(datfimbaseline, 'DD/MM/YYYY') FROM agepnet200.tb_atividadecronograma 
                         WHERE idprojeto = proj.idprojeto 
                         AND datfimbaseline IS NOT NULL ORDER BY datfimbaseline DESC limit 1 ) AS datfimbaseline,
                        (SELECT to_char(sr.datfimprojetotendencia, 'YYYYMMDD')
                          FROM agepnet200.tb_statusreport sr 
                          WHERE sr.idprojeto =  proj.idprojeto
                          ORDER BY idstatusreport DESC, datfimprojetotendencia DESC  LIMIT 1) AS terminotendencia,
                        (SELECT str.domstatusprojeto
                          FROM agepnet200.tb_statusreport str
                          WHERE str.idprojeto = proj.idprojeto
                          ORDER BY str.datacompanhamento DESC, str.idstatusreport DESC limit 1) AS numdomstatusprojeto,
                        proj.atraso,
                        proj.domcoratraso,
                        proj.numpercentualconcluidomarco,
                       COALESCE (proj.numpercentualconcluido::INTEGER, 0) AS numpercentualconcluido,
                       COALESCE (proj.numpercentualprevisto::INTEGER, 0) AS numpercentualprevisto
                FROM
                        agepnet200.tb_projeto proj)
                        
                        proj

                WHERE proj.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " ";

        if (!empty($params['idprojeto']) && trim($params['idprojeto'])) {
            $idprojeto = $params['idprojeto'];
            $sql .= " and proj.idprojeto = {$idprojeto}";
        }

        if ($idperfil != 1) {
            $sql .= " AND proj.stflapublicado IN ('S') ";
        } else {
            $sql .= " AND proj.stflapublicado IN ('S','N') ";
        }

        if (!empty($params['nomprojeto']) && (trim($params['nomprojeto']))) {
            $nomprojeto = strtoupper($params['nomprojeto']);
            $sql .= " and translate(upper(proj.nomprojeto),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "') "
                . " ILIKE ( "
                . " '%' ||
                translate(upper('{$nomprojeto}'),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "')
                || '%')";
        }

        if (isset($params['idprograma'])
            && (!empty($params['idprograma']))
            && trim($params['idprograma'])
            && $params['idprograma'] != "0"
        ) {
            $idprograma = $params['idprograma'];
            $sql .= " and proj.idprograma = {$idprograma} ";
        }

        if (isset($params['idescritorio'])
            && (is_array($params['idescritorio']))
            && (count($params['idescritorio']) > 0)
        ) {
            if ($params['idescritorio'][0] != "9999") {
                $escritorios = implode(",", $params['idescritorio']);
                $sql .= " and proj.idescritorio IN({$escritorios}) ";
            }
        } else {
            if ($idperfil != 1) {
                $sql .= " AND proj.idescritorio NOT IN ({$idescritorio})";
            }
        }

        if (!empty($params['domstatusprojeto']) && $params['domstatusprojeto'] > 0) {

            $domstatusprojeto = $params['domstatusprojeto'];

            $sql .= " and  (
                            select str.domstatusprojeto
                            from agepnet200.tb_statusreport str
                            where str.idprojeto = proj.idprojeto
                            order by str.datacompanhamento desc, str.idstatusreport desc
                            limit 1
                        ) = '{$domstatusprojeto}' ";

        } else {
            $sql .= " and (
                            select str.domstatusprojeto
                            from agepnet200.tb_statusreport str
                            where str.idprojeto = proj.idprojeto
                            order by str.datacompanhamento desc, str.idstatusreport desc
                            limit 1
                        ) != " . Projeto_Model_Gerencia::STATUS_EXCLUIDO;
        }

        if (isset($params['codobjetivo']) && (!empty($params['codobjetivo'])) && $params['codobjetivo'] != "0") {
            $status = (int)$params['codobjetivo'];
            $sql .= " and proj.idobjetivo IN({$status})";
        }

        if (isset($params['codacao']) && is_array($params['codacao'])
            && count($params['codacao']) > 0
            && $params['codacao'][0] != "0"
        ) {
            $status = impode(",", $params['codacao']);
            $sql .= (!empty($status)) ? " and proj.idacao IN($status) " : "";
        }

        if (isset($params['acompanhamento']) && (!empty($params['acompanhamento']))) {

            $sql .= " AND proj.idprojeto = (SELECT p.idprojeto  
                                                 FROM agepnet200.tb_projeto p 
                                                 LEFT JOIN (SELECT idprojeto, MAX(datacompanhamento) AS datacompanhamento
                                                             FROM agepnet200.tb_statusreport
                                                            GROUP BY idprojeto) sr  
                                                  ON sr.idprojeto = p.idprojeto ";

            switch ($params['acompanhamento']) {
                case 1:
                    $sql .= " WHERE proj.idprojeto=p.idprojeto) ";
                    break;
                case 2: // Atualizados nos últimos 30 dias
                    $sql .= " WHERE proj.idprojeto=p.idprojeto AND (sr.datacompanhamento BETWEEN CURRENT_DATE - 30 AND CURRENT_DATE)) ";
                    break;
                case 3: //Atualizados nos últimos 90 dias
                    $sql .= " WHERE proj.idprojeto=p.idprojeto AND (sr.datacompanhamento BETWEEN CURRENT_DATE - 90 AND CURRENT_DATE)) ";
                    break;
                case 4: //Sem atualização há mais de 30 dias
                    $sql .= " WHERE proj.idprojeto=p.idprojeto AND sr.datacompanhamento <= (CURRENT_DATE - 30)) ";
                    break;
                case 5: //Sem atualização há mais de 90 dias
                    $sql .= " WHERE proj.idprojeto=p.idprojeto AND sr.datacompanhamento <= (CURRENT_DATE - 90)) ";
                    break;
                case 6: //Sem atualização há mais de 180 dias
                    $sql .= " WHERE proj.idprojeto=p.idprojeto AND sr.datacompanhamento <= (CURRENT_DATE - 180)) ";
                    break;
            }
        }

        if (!empty($params['sidx'])) {

            $sql .= " ORDER BY " . ((trim($params['sidx'])) == "situacao" ? " domstatusprojeto " :
                    ((trim($params['sidx'])) == "idgerenteprojeto" ? " nomgerenteprojeto " :
                        ((trim($params['sidx'])) == "nomprograma" ? " 4 " :
                            ((trim($params['sidx'])) == "datinicio" ? " proj.datinicio " :
                                ((trim($params['sidx'])) == "datfim" ? " proj.datfim " :
                                    ((trim($params['sidx'])) == "datfimplano" ? " terminotendencia " :
                                        ((trim($params['sidx'])) == "nomescritorio" ? " 28 " :
                                            ((trim($params['sidx'])) == "ultimoacompanhamento" ? " orderultimoacompanhamento " :
                                                ((trim($params['sidx'])) == "flapublicado" ? " flapublicado " :
                                                    " upper(proj." . (trim($params['sidx'])) . ") "))))))))) . $params['sord'];
        }

//        echo '<pre>';
//        var_dump($sql);die;

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

    public function retornaUltimoIdProjeto()
    {
        $sql = "  SELECT max(idprojeto)as idprojeto FROM agepnet200.tb_projeto order by idprojeto desc";
        return $this->_db->fetchAll($sql);
    }

    public function getEscritorioByIdProjeto($params)
    {
        $sql = "SELECT
                    proj.idescritorio
                FROM
                    agepnet200.tb_projeto proj
                WHERE
                    proj.idtipoiniciativa = 1 /* PROJETO */
                    and proj.idprojeto = :idprojeto ";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return $resultado;
    }

    /**
     * @param array $params
     * @return Projeto_Model_Gerencia
     */
    public function getById($params)
    {
        $sql = "SELECT
                                proj.datcadastro,
                                proj.datenviouemailatualizacao,
                                proj.ano,
                                proj.datinicio,
                                proj.datfim,
                                proj.datinicioplano,
                                proj.datfimplano,
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
                                proj.idtipoiniciativa,
                                CASE port.tipo
                                  WHEN 1 THEN 'NORMAL'
                                  WHEN 2 THEN 'ESTRATÃ‰GICO'
                                END as tipo,
                                proj.numprocessosei,
                                proj.numpercentualconcluido,
                                proj.numpercentualprevisto,
                                to_char(qtdeatividadeiniciada,'999') as qtdeatividadeiniciada,
                                proj.numpercentualiniciado,
                                to_char(qtdeatividadenaoiniciada,'999') as qtdeatividadenaoiniciada,
                                proj.numpercentualnaoiniciado,
                                to_char(qtdeatividadeconcluida,'999') as qtdeatividadeconcluida,
                                proj.numpercentualatividadeconcluido
                        FROM
                                agepnet200.tb_projeto proj                                 
			left join agepnet200.tb_pessoa p1 on p1.idpessoa = proj.iddemandante
			left join agepnet200.tb_pessoa p2 on p2.idpessoa = proj.idpatrocinador
			inner join agepnet200.tb_pessoa p3 on p3.idpessoa = proj.idgerenteprojeto
			left join agepnet200.tb_pessoa p4 on p4.idpessoa = proj.idgerenteadjunto
			left join agepnet200.tb_programa prog on proj.idprograma = prog.idprograma
			left join agepnet200.tb_objetivo obj on  proj.idobjetivo = obj.idobjetivo 
			left join agepnet200.tb_natureza nat on nat.idnatureza = proj.idnatureza
			left join agepnet200.tb_setor setor on proj.idsetor = setor.idsetor
            left join agepnet200.tb_portfolio port on proj.idportfolio = port.idportfolio
                        WHERE proj.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " /* PROJETO */
                              and proj.idprojeto = :idprojeto ";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        $projeto = new Projeto_Model_Gerencia($resultado);
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
                        case when (
                           SELECT min(atc.datiniciobaseline)
                           FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
                           and atc.domtipoatividade in (3,4)
                        ) is null or (
                           SELECT max(atc.datfimbaseline)
                           FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
                           and atc.domtipoatividade in (3,4)
                        ) is null then '0'
                        else(
                            SELECT count(*) AS diasuteis
                            FROM generate_series(
                                to_timestamp(to_char((
                            SELECT min(atc.datiniciobaseline)
                            FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
                            and atc.domtipoatividade in (3,4)
                        ), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                to_timestamp(to_char((
                       SELECT max(atc.datfimbaseline)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
                       and atc.domtipoatividade in (3,4)
                        ), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                            ) the_day
                            WHERE  extract('ISODOW' FROM the_day) < 6
                            and to_char(the_day,'dd/mm') not in(
                               SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                            )
                            and to_char(the_day,'dd/mm/yyyy') not in(
                               SELECT
                                 lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                            )
                       end as numdiasbaseline,
                       (
                            SELECT sum(round(coalesce(
                            case
                            when cronProjB.datfimbaseline is null or cronProjB.datiniciobaseline is null or cronProjB.domtipoatividade = 4 then '0'
                            else(
                               SELECT count(*) AS diasuteis
                               FROM generate_series(
                                 to_timestamp(to_char(cronProjB.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                 to_timestamp(to_char(cronProjB.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                               ) the_day
                               WHERE  extract('ISODOW' FROM the_day) < 6
                               and to_char(the_day,'dd/mm') not in(
                                  SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                  FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                               )
                               and to_char(the_day,'dd/mm/yyyy') not in(
                                  SELECT lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                  FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                               )
                            ) end,0) , 2)) as totaldiasbaseline
                            FROM agepnet200.tb_atividadecronograma cronProjB
                            WHERE cronProjB.idprojeto = proj.idprojeto and cronProjB.domtipoatividade in (3,4)
                            and (cronProjB.flacancelada='N' or cronProjB.flacancelada is null)
                       ) as totaldiasbaseline,
                       (
                            SELECT sum(round(coalesce(
                                case
                                when cronProj.datfimbaseline is null or cronProj.datiniciobaseline is null  or cronProj.domtipoatividade = 4 then '0'
                                when to_date(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then '0'
                                when to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') = to_char(now(), 'DD/MM/YYYY') then '0'
                                when to_date(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                                (
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(now(),    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                )
                                )
                                when to_date(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') < to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                                (
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                )
                                    )
                                else(
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(cronProj.datfimbaseline,    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                     FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                )
                            ) end,0) , 2)) as numdiascompletos
                            FROM
                                agepnet200.tb_atividadecronograma cronProj
                            WHERE
                                cronProj.idprojeto = proj.idprojeto and cronProj.domtipoatividade in (3,4)
                       ) as numdiascompletos,
                       (
                            SELECT SUM(ROUND(coalesce(
                            case
                            when (
                               SELECT min(atc1.datinicio)
                               FROM agepnet200.tb_atividadecronograma atc1 WHERE atc1.idprojeto = proj.idprojeto
                               and atc1.domtipoatividade in (3,4) and atc1.idgrupo in (
                                    select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                    where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                    and entrega.idgrupo in(
                                        select grp.idatividadecronograma
                                        from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                        and grp.domtipoatividade=1
                                    )
                                )
                            ) is null or (
                               SELECT max(atc2.datfim)
                               FROM agepnet200.tb_atividadecronograma atc2 WHERE atc2.idprojeto = proj.idprojeto
                               and atc2.domtipoatividade in (3,4) and atc2.idgrupo in (
                                    select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                    where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                    and entrega.idgrupo in(
                                        select grp.idatividadecronograma
                                        from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                        and grp.domtipoatividade=1
                                    )
                               )
                            ) is null then '0'
                            else(
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(atividade.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(atividade.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                ))
                                end ,1),2)) as numdiasrealizados
                                FROM agepnet200.tb_atividadecronograma atividade
                                WHERE
                                atividade.idprojeto = proj.idprojeto and atividade.domtipoatividade=3
                                and (atividade.flacancelada='N' or atividade.flacancelada is null)
                                and atividade.idgrupo in (
                                    select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                    where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                    and entrega.idgrupo in(
                                        select grp.idatividadecronograma
                                        from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                        and grp.domtipoatividade=1
                                    )
                                )
                        ) as numdiasrealizados,
                       (
                       SELECT SUM(ROUND(coalesce(
                       case
                       when (
                           SELECT min(atc1.datinicio)
                           FROM agepnet200.tb_atividadecronograma atc1 WHERE atc1.idprojeto = proj.idprojeto
                           and atc1.domtipoatividade in (3,4) and atc1.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                and entrega.idgrupo in(
                                    select grp.idatividadecronograma
                                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                    and grp.domtipoatividade=1
                                )
                            )
                       ) is null or (
                           SELECT max(atc2.datfim)
                           FROM agepnet200.tb_atividadecronograma atc2 WHERE atc2.idprojeto = proj.idprojeto
                           and atc2.domtipoatividade in (3,4) and atc2.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                and entrega.idgrupo in(
                                    select grp.idatividadecronograma
                                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                    and grp.domtipoatividade=1
                                )
                           )
                       ) is null then '0'
                       else(((((
                            SELECT count(*) AS diasuteis
                            FROM generate_series(
                                to_timestamp(to_char(atividade.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                to_timestamp(to_char(atividade.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                            ) the_day
                            WHERE  extract('ISODOW' FROM the_day) < 6
                            and to_char(the_day,'dd/mm') not in(
                               SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                            )
                            and to_char(the_day,'dd/mm/yyyy') not in(
                               SELECT
                                 lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                            )))*coalesce(atividade.numpercentualconcluido,0))/100))
                            end ,1),2)) as numdiasrealizadosreal
                            FROM agepnet200.tb_atividadecronograma atividade
                            WHERE
                            atividade.idprojeto = proj.idprojeto and atividade.domtipoatividade=3
                            and (atividade.flacancelada='N' or atividade.flacancelada is null)
                            and atividade.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
				                and entrega.idgrupo in(
				                    select grp.idatividadecronograma
				                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
				                    and grp.domtipoatividade=1
				                )
                            )
                        ) as numdiasrealizadosreal,
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
                        proj.idtipoiniciativa,
                        proj.vlrorcamentodisponivel
                FROM
                        agepnet200.tb_projeto proj
                WHERE 
                        proj.idtipoiniciativa = 1 and proj.idprojeto = :idprojeto ";

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
     * @return Projeto_Model_Gerencia
     */
    public function retornaProjetoPorId($params, $dadosCompletos = true)
    {

        $sql = "SELECT
                    to_char(proj.datcadastro,'DD/MM/YYYY') as datcadastro,
                    to_char(proj.datenviouemailatualizacao,'DD/MM/YYYY') as datenviouemailatualizacao,
                    to_char(proj.datfim,'DD/MM/YYYY') as datfim,
                    to_char(proj.datfimplano,'DD/MM/YYYY') as datfimplano,
                    to_char(proj.datinicio,'DD/MM/YYYY') as datinicio,
                    to_char(proj.datinicioplano,'DD/MM/YYYY') as datinicioplano,
					case when (
                       SELECT min(atc.datiniciobaseline)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
                       and atc.domtipoatividade in (3,4)
                    ) is null or (
                       SELECT max(atc.datfimbaseline)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
                       and atc.domtipoatividade in (3,4)
                    ) is null then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char((
			       		SELECT min(atc.datiniciobaseline)
			       		FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
			       		and atc.domtipoatividade in (3,4)
			    	), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char((
			       SELECT max(atc.datfimbaseline)
			       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
			       and atc.domtipoatividade in (3,4)
			    	), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
                        end as numdiasbaseline,
                        (
                            SELECT sum(round(coalesce(
                            case
                            when cronProjB.datfimbaseline is null or cronProjB.datiniciobaseline is null or cronProjB.domtipoatividade = 4 then '0'
                            else(
                               SELECT count(*) AS diasuteis
                               FROM generate_series(
                                 to_timestamp(to_char(cronProjB.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                 to_timestamp(to_char(cronProjB.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                               ) the_day
                               WHERE  extract('ISODOW' FROM the_day) < 6
                               and to_char(the_day,'dd/mm') not in(
                                  SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                  FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                               )
                               and to_char(the_day,'dd/mm/yyyy') not in(
                                  SELECT lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                  FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                               )
                            ) end,0) , 2)) as totaldiasbaseline
                            FROM agepnet200.tb_atividadecronograma cronProjB
                            WHERE cronProjB.idprojeto = proj.idprojeto and cronProjB.domtipoatividade in (3,4)
                            and (cronProjB.flacancelada='N' or cronProjB.flacancelada is null)
                       ) as totaldiasbaseline,
                       (
                            SELECT sum(round(coalesce(
                                case
                                when cronProj.datfimbaseline is null or cronProj.datiniciobaseline is null  or cronProj.domtipoatividade = 4 then '0'
                                when to_date(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then '0'
                                when to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') = to_char(now(), 'DD/MM/YYYY') then '0'
                                when to_date(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                                (
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(now(),    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                )
                                )
                                when to_date(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') < to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                                (
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                )
                                    )
                                else(
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(cronProj.datfimbaseline,    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                     FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                )
                            ) end,0) , 2)) as numdiascompletos
                            FROM
                                agepnet200.tb_atividadecronograma cronProj
                            WHERE
                                cronProj.idprojeto = proj.idprojeto and cronProj.domtipoatividade in (3,4)
                                and (cronProj.flacancelada='N' or cronProj.flacancelada is null)
                       ) as numdiascompletos,
                       (
                            SELECT SUM(ROUND(coalesce(
                            case
                            when (
                               SELECT min(atc1.datinicio)
                               FROM agepnet200.tb_atividadecronograma atc1 WHERE atc1.idprojeto = proj.idprojeto
                               and atc1.domtipoatividade in (3,4) and atc1.idgrupo in (
                                    select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                    where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                    and entrega.idgrupo in(
                                        select grp.idatividadecronograma
                                        from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                        and grp.domtipoatividade=1
                                    )
                                )
                            ) is null or (
                               SELECT max(atc2.datfim)
                               FROM agepnet200.tb_atividadecronograma atc2 WHERE atc2.idprojeto = proj.idprojeto
                               and atc2.domtipoatividade in (3,4) and atc2.idgrupo in (
                                    select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                    where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                    and entrega.idgrupo in(
                                        select grp.idatividadecronograma
                                        from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                        and grp.domtipoatividade=1
                                    )
                               )
                            ) is null then '0'
                            else(
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(atividade.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(atividade.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                ))
                                end ,1),2)) as numdiasrealizados
                                FROM agepnet200.tb_atividadecronograma atividade
                                WHERE
                                atividade.idprojeto = proj.idprojeto and atividade.domtipoatividade=3
                                and (atividade.flacancelada='N' or atividade.flacancelada is null)
                                and atividade.idgrupo in (
                                    select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                    where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                    and entrega.idgrupo in(
                                        select grp.idatividadecronograma
                                        from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                        and grp.domtipoatividade=1
                                    )
                                )
                        ) as numdiasrealizados,
                       (
                       SELECT SUM(ROUND(coalesce(
                        case
                        when (
                           SELECT min(atc1.datinicio)
                           FROM agepnet200.tb_atividadecronograma atc1 WHERE atc1.idprojeto = proj.idprojeto
                           and atc1.domtipoatividade in (3,4) and atc1.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                and entrega.idgrupo in(
                                    select grp.idatividadecronograma
                                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                    and grp.domtipoatividade=1
                                )
                            )
                        ) is null or (
                           SELECT max(atc2.datfim)
                           FROM agepnet200.tb_atividadecronograma atc2 WHERE atc2.idprojeto = proj.idprojeto
                           and atc2.domtipoatividade in (3,4) and atc2.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                and entrega.idgrupo in(
                                    select grp.idatividadecronograma
                                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                    and grp.domtipoatividade=1
                                )
                           )
                        ) is null then '0'
                        else(((((
                            SELECT count(*) AS diasuteis
                            FROM generate_series(
                                to_timestamp(to_char(atividade.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                to_timestamp(to_char(atividade.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                            ) the_day
                            WHERE  extract('ISODOW' FROM the_day) < 6
                            and to_char(the_day,'dd/mm') not in(
                               SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                            )
                            and to_char(the_day,'dd/mm/yyyy') not in(
                               SELECT
                                 lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                            )))*coalesce(atividade.numpercentualconcluido,0))/100))
                            end ,1),2)) as numdiasrealizadosreal
                            FROM agepnet200.tb_atividadecronograma atividade
                            WHERE
                            atividade.idprojeto = proj.idprojeto and atividade.domtipoatividade=3
                            and (atividade.flacancelada='N' or atividade.flacancelada is null)
                            and atividade.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
				                and entrega.idgrupo in(
				                    select grp.idatividadecronograma
				                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
				                    and grp.domtipoatividade=1
				                )
                            )
                    ) as numdiasrealizadosreal,
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
                    proj.idtipoiniciativa,
                    p.tipo,
                    proj.numprocessosei,
                    proj.numpercentualconcluido,
                    proj.numpercentualprevisto,
                    proj.atraso,
                    proj.numpercentualconcluidomarco,
                    proj.domcoratraso,
                    proj.qtdeatividadeiniciada,
                    proj.numpercentualiniciado,
                    proj.qtdeatividadenaoiniciada,
                    proj.numpercentualnaoiniciado,
                    proj.qtdeatividadeconcluida,
                    proj.numpercentualatividadeconcluido
            FROM
                    agepnet200.tb_projeto proj
                    left join  agepnet200.tb_portfolio p  on p.idportfolio = proj.idportfolio
                    left join  agepnet200.tb_pessoa p1    on p1.idpessoa   = proj.iddemandante
                    left join  agepnet200.tb_pessoa p2    on p2.idpessoa   = proj.idpatrocinador
                    inner join agepnet200.tb_pessoa p3    on p3.idpessoa   = proj.idgerenteprojeto
                    left join agepnet200.tb_pessoa p4    on p4.idpessoa   = proj.idgerenteadjunto
            WHERE
                    proj.idtipoiniciativa = 1 and proj.idprojeto = :idprojeto ";

        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => (int)$params['idprojeto']));

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

        if ($dadosCompletos) {
            $projeto->demandante = $mapperPessoa->retornaPessoaProjeto(array('idpessoa' => $projeto->iddemandante));
            $projeto->patrocinador = $mapperPessoa->retornaPessoaProjeto(array('idpessoa' => $projeto->idpatrocinador));
            $projeto->gerenteprojeto = $mapperPessoa->retornaPessoaProjeto(array('idpessoa' => $projeto->idgerenteprojeto));
            $projeto->gerenteadjunto = $mapperPessoa->retornaPessoaProjeto(array('idpessoa' => $projeto->idgerenteadjunto));
            $projeto->ultimoStatusReport = $mapperStatusreport->retornaUltimoPorProjeto(array('idprojeto' => $projeto->idprojeto));
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
        }
        $projeto->grupos = new App_Model_Relation($mapperAtividadeCron, 'retornaGrupoPorProjeto',
            array(array('idprojeto' => $projeto->idprojeto)));

        return $projeto;
    }

    public function retornaProjetoPorIdObjeto($params, $dadosCompletos = true)
    {

        $sql = "SELECT
                    to_char(proj.datcadastro,'DD/MM/YYYY') as datcadastro,
                    to_char(proj.datenviouemailatualizacao,'DD/MM/YYYY') as datenviouemailatualizacao,
                    to_char(proj.datfim,'DD/MM/YYYY') as datfim,
                    to_char(proj.datfimplano,'DD/MM/YYYY') as datfimplano,
                    to_char(proj.datinicio,'DD/MM/YYYY') as datinicio,
                    to_char(proj.datinicioplano,'DD/MM/YYYY') as datinicioplano,
					case when (
                       SELECT min(atc.datiniciobaseline)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
                       and atc.domtipoatividade in (3,4)
                    ) is null or (
                       SELECT max(atc.datfimbaseline)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
                       and atc.domtipoatividade in (3,4)
                    ) is null then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char((
			       		SELECT min(atc.datiniciobaseline)
			       		FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
			       		and atc.domtipoatividade in (3,4)
			    	), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char((
			       SELECT max(atc.datfimbaseline)
			       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
			       and atc.domtipoatividade in (3,4)
			    	), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
                        end as numdiasbaseline,
                        (
                            SELECT sum(round(coalesce(
                            case
                            when cronProjB.datfimbaseline is null or cronProjB.datiniciobaseline is null or cronProjB.domtipoatividade = 4 then '0'
                            else(
                               SELECT count(*) AS diasuteis
                               FROM generate_series(
                                 to_timestamp(to_char(cronProjB.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                 to_timestamp(to_char(cronProjB.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                               ) the_day
                               WHERE  extract('ISODOW' FROM the_day) < 6
                               and to_char(the_day,'dd/mm') not in(
                                  SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                  FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                               )
                               and to_char(the_day,'dd/mm/yyyy') not in(
                                  SELECT lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                  FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                               )
                            ) end,0) , 2)) as totaldiasbaseline
                            FROM agepnet200.tb_atividadecronograma cronProjB
                            WHERE cronProjB.idprojeto = proj.idprojeto and cronProjB.domtipoatividade in (3,4)
                            and (cronProjB.flacancelada='N' or cronProjB.flacancelada is null)
                       ) as totaldiasbaseline,
                       (
                            SELECT sum(round(coalesce(
                                case
                                when cronProj.datfimbaseline is null or cronProj.datiniciobaseline is null  or cronProj.domtipoatividade = 4 then '0'
                                when to_date(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then '0'
                                when to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') = to_char(now(), 'DD/MM/YYYY') then '0'
                                when to_date(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                                (
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(now(),    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                )
                                )
                                when to_date(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') < to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                                (
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                )
                                    )
                                else(
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(cronProj.datfimbaseline,    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                     FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                )
                            ) end,0) , 2)) as numdiascompletos
                            FROM
                                agepnet200.tb_atividadecronograma cronProj
                            WHERE
                                cronProj.idprojeto = proj.idprojeto and cronProj.domtipoatividade in (3,4)
                                and (cronProj.flacancelada='N' or cronProj.flacancelada is null)
                       ) as numdiascompletos,
                       (
                            SELECT SUM(ROUND(coalesce(
                            case
                            when (
                               SELECT min(atc1.datinicio)
                               FROM agepnet200.tb_atividadecronograma atc1 WHERE atc1.idprojeto = proj.idprojeto
                               and atc1.domtipoatividade in (3,4) and atc1.idgrupo in (
                                    select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                    where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                    and entrega.idgrupo in(
                                        select grp.idatividadecronograma
                                        from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                        and grp.domtipoatividade=1
                                    )
                                )
                            ) is null or (
                               SELECT max(atc2.datfim)
                               FROM agepnet200.tb_atividadecronograma atc2 WHERE atc2.idprojeto = proj.idprojeto
                               and atc2.domtipoatividade in (3,4) and atc2.idgrupo in (
                                    select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                    where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                    and entrega.idgrupo in(
                                        select grp.idatividadecronograma
                                        from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                        and grp.domtipoatividade=1
                                    )
                               )
                            ) is null then '0'
                            else(
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(atividade.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(atividade.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                ))
                                end ,1),2)) as numdiasrealizados
                                FROM agepnet200.tb_atividadecronograma atividade
                                WHERE
                                atividade.idprojeto = proj.idprojeto and atividade.domtipoatividade=3
                                and (atividade.flacancelada='N' or atividade.flacancelada is null)
                                and atividade.idgrupo in (
                                    select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                    where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                    and entrega.idgrupo in(
                                        select grp.idatividadecronograma
                                        from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                        and grp.domtipoatividade=1
                                    )
                                )
                        ) as numdiasrealizados,
                       (
                       SELECT SUM(ROUND(coalesce(
                        case
                        when (
                           SELECT min(atc1.datinicio)
                           FROM agepnet200.tb_atividadecronograma atc1 WHERE atc1.idprojeto = proj.idprojeto
                           and atc1.domtipoatividade in (3,4) and atc1.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                and entrega.idgrupo in(
                                    select grp.idatividadecronograma
                                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                    and grp.domtipoatividade=1
                                )
                            )
                        ) is null or (
                           SELECT max(atc2.datfim)
                           FROM agepnet200.tb_atividadecronograma atc2 WHERE atc2.idprojeto = proj.idprojeto
                           and atc2.domtipoatividade in (3,4) and atc2.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                and entrega.idgrupo in(
                                    select grp.idatividadecronograma
                                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                    and grp.domtipoatividade=1
                                )
                           )
                        ) is null then '0'
                        else(((((
                            SELECT count(*) AS diasuteis
                            FROM generate_series(
                                to_timestamp(to_char(atividade.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                to_timestamp(to_char(atividade.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                            ) the_day
                            WHERE  extract('ISODOW' FROM the_day) < 6
                            and to_char(the_day,'dd/mm') not in(
                               SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                            )
                            and to_char(the_day,'dd/mm/yyyy') not in(
                               SELECT
                                 lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                            )))*coalesce(atividade.numpercentualconcluido,0))/100))
                            end ,1),2)) as numdiasrealizadosreal
                            FROM agepnet200.tb_atividadecronograma atividade
                            WHERE
                            atividade.idprojeto = proj.idprojeto and atividade.domtipoatividade=3
                            and (atividade.flacancelada='N' or atividade.flacancelada is null)
                            and atividade.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
				                and entrega.idgrupo in(
				                    select grp.idatividadecronograma
				                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
				                    and grp.domtipoatividade=1
				                )
                            )
                    ) as numdiasrealizadosreal,
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
                    proj.idtipoiniciativa,
                    p.tipo,
                    proj.numprocessosei,
                    proj.numpercentualconcluido,
                    proj.numpercentualprevisto,
                    proj.atraso,
                    proj.numpercentualconcluidomarco,
                    proj.domcoratraso,
                    proj.qtdeatividadeiniciada,
                    proj.numpercentualiniciado,
                    proj.qtdeatividadenaoiniciada,
                    proj.numpercentualnaoiniciado,
                    proj.qtdeatividadeconcluida,
                    proj.numpercentualatividadeconcluido
            FROM
                    agepnet200.tb_projeto proj
                    left join  agepnet200.tb_portfolio p  on p.idportfolio = proj.idportfolio
                    left join  agepnet200.tb_pessoa p1    on p1.idpessoa   = proj.iddemandante
                    left join  agepnet200.tb_pessoa p2    on p2.idpessoa   = proj.idpatrocinador
                    inner join agepnet200.tb_pessoa p3    on p3.idpessoa   = proj.idgerenteprojeto
                    left join agepnet200.tb_pessoa p4    on p4.idpessoa   = proj.idgerenteadjunto
            WHERE
                    proj.idtipoiniciativa = 1 and proj.idprojeto = :idprojeto ";

        return $this->_db->fetchRow($sql, array('idprojeto' => (int)$params['idprojeto']));
    }

    /**
     * @param array $params
     * @return Projeto_Model_Gerencia
     */
    public function retornaProjetoCronogramaPorId($params, $dadosCompletos = true)
    {

        $sql = "SELECT
                    to_char(proj.datcadastro,'DD/MM/YYYY') as datcadastro,
                    to_char(proj.datenviouemailatualizacao,'DD/MM/YYYY') as datenviouemailatualizacao,
                    to_char(proj.datfim,'DD/MM/YYYY') as datfim,
                    to_char(proj.datfimplano,'DD/MM/YYYY') as datfimplano,
                    to_char(proj.datinicio,'DD/MM/YYYY') as datinicio,
                    to_char(proj.datinicioplano,'DD/MM/YYYY') as datinicioplano,
					case when (
                       SELECT min(atc.datiniciobaseline)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
                       and atc.domtipoatividade in (3,4)
                    ) is null or (
                       SELECT max(atc.datfimbaseline)
                       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
                       and atc.domtipoatividade in (3,4)
                    ) is null then '0'
                    else(
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char((
			       		SELECT min(atc.datiniciobaseline)
			       		FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
			       		and atc.domtipoatividade in (3,4)
			    	), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char((
			       SELECT max(atc.datfimbaseline)
			       FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
			       and atc.domtipoatividade in (3,4)
			    	), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2')
                        )
                        end as numdiasbaseline,
                        (
                            SELECT sum(round(coalesce(
                            case
                            when cronProjB.datfimbaseline is null or cronProjB.datiniciobaseline is null or cronProjB.domtipoatividade = 4 then '0'
                            else(
                               SELECT count(*) AS diasuteis
                               FROM generate_series(
                                 to_timestamp(to_char(cronProjB.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                 to_timestamp(to_char(cronProjB.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                               ) the_day
                               WHERE  extract('ISODOW' FROM the_day) < 6
                               and to_char(the_day,'dd/mm') not in(
                                  SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                  FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                               )
                               and to_char(the_day,'dd/mm/yyyy') not in(
                                  SELECT lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                  FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                               )
                            ) end,0) , 2)) as totaldiasbaseline
                            FROM agepnet200.tb_atividadecronograma cronProjB
                            WHERE cronProjB.idprojeto = proj.idprojeto and cronProjB.domtipoatividade in (3,4)
                            and (cronProjB.flacancelada='N' or cronProjB.flacancelada is null)
                       ) as totaldiasbaseline,
                       (
                            SELECT sum(round(coalesce(
                                case
                                when cronProj.datfimbaseline is null or cronProj.datiniciobaseline is null  or cronProj.domtipoatividade = 4 then '0'
                                when to_date(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then '0'
                                when to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') = to_char(now(), 'DD/MM/YYYY') then '0'
                                when to_date(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                                (
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(now(),    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                )
                                )
                                when to_date(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') < to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
                                (
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                )
                                    )
                                else(
                                SELECT count(*) AS diasuteis
                                FROM generate_series(
                                    to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                    to_timestamp(to_char(cronProj.datfimbaseline,    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                                ) the_day
                                WHERE  extract('ISODOW' FROM the_day) < 6
                                and to_char(the_day,'dd/mm') not in(
                                   SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                                   FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                                )
                                and to_char(the_day,'dd/mm/yyyy') not in(
                                   SELECT
                                     lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                     lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                                     FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                                )
                            ) end,0) , 2)) as numdiascompletos
                            FROM
                                agepnet200.tb_atividadecronograma cronProj
                            WHERE
                                cronProj.idprojeto = proj.idprojeto and cronProj.domtipoatividade in (3,4)
                                and (cronProj.flacancelada='N' or cronProj.flacancelada is null)
                       ) as numdiascompletos,
                       (
                        SELECT SUM(ROUND(coalesce(
                        case
                        when (
                           SELECT min(atc1.datinicio)
                           FROM agepnet200.tb_atividadecronograma atc1 WHERE atc1.idprojeto = proj.idprojeto
                           and atc1.domtipoatividade in (3,4) and atc1.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                and entrega.idgrupo in(
                                    select grp.idatividadecronograma
                                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                    and grp.domtipoatividade=1
                                )
                            )
                        ) is null or (
                           SELECT max(atc2.datfim)
                           FROM agepnet200.tb_atividadecronograma atc2 WHERE atc2.idprojeto = proj.idprojeto
                           and atc2.domtipoatividade in (3,4) and atc2.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                and entrega.idgrupo in(
                                    select grp.idatividadecronograma
                                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                    and grp.domtipoatividade=1
                                )
                           )
                        ) is null then '0'
                        else(
                            SELECT count(*) AS diasuteis
                            FROM generate_series(
                                to_timestamp(to_char(atividade.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                                to_timestamp(to_char(atividade.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                            ) the_day
                            WHERE  extract('ISODOW' FROM the_day) < 6
                            and to_char(the_day,'dd/mm') not in(
                               SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                            )
                            and to_char(the_day,'dd/mm/yyyy') not in(
                               SELECT
                                 lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                                 lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                               FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                            ))
                            end ,1),2)) as numdiasrealizados
                            FROM agepnet200.tb_atividadecronograma atividade
                            WHERE
                            atividade.idprojeto = proj.idprojeto and atividade.domtipoatividade=3
                            and (atividade.flacancelada='N' or atividade.flacancelada is null)
                            and atividade.idgrupo in (
                                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                                and entrega.idgrupo in(
                                    select grp.idatividadecronograma
                                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                    and grp.domtipoatividade=1
                                )
                            )
                    ) as numdiasrealizados,
                    (
                    SELECT SUM(ROUND(coalesce(
                    case
                    when (
                       SELECT min(atc1.datinicio)
                       FROM agepnet200.tb_atividadecronograma atc1 WHERE atc1.idprojeto = proj.idprojeto
                       and atc1.domtipoatividade in (3,4) and atc1.idgrupo in (
                            select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                            where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                            and entrega.idgrupo in(
                                select grp.idatividadecronograma
                                from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                and grp.domtipoatividade=1
                            )
                        )
                    ) is null or (
                       SELECT max(atc2.datfim)
                       FROM agepnet200.tb_atividadecronograma atc2 WHERE atc2.idprojeto = proj.idprojeto
                       and atc2.domtipoatividade in (3,4) and atc2.idgrupo in (
                            select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                            where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                            and entrega.idgrupo in(
                                select grp.idatividadecronograma
                                from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                and grp.domtipoatividade=1
                            )
                       )
                    ) is null then '0'
                    else(((((
                        SELECT count(*) AS diasuteis
                        FROM generate_series(
                            to_timestamp(to_char(atividade.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                            to_timestamp(to_char(atividade.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                        ) the_day
                        WHERE  extract('ISODOW' FROM the_day) < 6
                        and to_char(the_day,'dd/mm') not in(
                           SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                        )
                        and to_char(the_day,'dd/mm/yyyy') not in(
                           SELECT
                             lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                             lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                           FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                        )))*coalesce(atividade.numpercentualconcluido,0))/100))
                        end ,1),2)) as numdiasrealizadosreal
                        FROM agepnet200.tb_atividadecronograma atividade
                        WHERE
                        atividade.idprojeto = proj.idprojeto and atividade.domtipoatividade=3
                        and (atividade.flacancelada='N' or atividade.flacancelada is null)
                        and atividade.idgrupo in (
                            select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                            where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                            and entrega.idgrupo in(
                                select grp.idatividadecronograma
                                from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                                and grp.domtipoatividade=1
                            )
                        )
                    ) as numdiasrealizadosreal,
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
                    proj.idtipoiniciativa,
                    p.tipo,
                    proj.numprocessosei,
                    proj.numpercentualconcluido,
                    proj.numpercentualprevisto,
                    proj.atraso,
                    proj.numpercentualconcluidomarco,
                    proj.domcoratraso,
                    proj.qtdeatividadeiniciada,
                    proj.numpercentualiniciado,
                    proj.qtdeatividadenaoiniciada,
                    proj.numpercentualnaoiniciado,
                    proj.qtdeatividadeconcluida,
                    proj.numpercentualatividadeconcluido
            FROM
                    agepnet200.tb_projeto proj
                    left join  agepnet200.tb_portfolio p  on p.idportfolio = proj.idportfolio
                    left join  agepnet200.tb_pessoa p1    on p1.idpessoa   = proj.iddemandante
                    left join  agepnet200.tb_pessoa p2    on p2.idpessoa   = proj.idpatrocinador
                    inner join agepnet200.tb_pessoa p3    on p3.idpessoa   = proj.idgerenteprojeto
                    left join agepnet200.tb_pessoa p4    on p4.idpessoa   = proj.idgerenteadjunto
            WHERE
                    proj.idtipoiniciativa = 1 and proj.idprojeto = :idprojeto ";

        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));

        $mapperAtividadeCron = new Projeto_Model_Mapper_Atividadecronograma();
        $mapperStatusreport = new Projeto_Model_Mapper_Statusreport();
        $mapperPessoa = new Default_Model_Mapper_Pessoa();
        $mapperPrograma = new Default_Model_Mapper_Programa();
        $mapperObjetivo = new Default_Model_Mapper_Objetivo();
        $mapperNatureza = new Default_Model_Mapper_Natureza();
        $mapperEscritorio = new Default_Model_Mapper_Escritorio();
        $mapperAcao = new Default_Model_Mapper_Acao();

        $projeto = new Projeto_Model_Gerencia($resultado);

        $projeto->ultimoStatusReport = $mapperStatusreport->retornaUltimoPorProjeto(array('idprojeto' => $projeto->idprojeto));

        $projeto->grupos = new App_Model_Relation($mapperAtividadeCron, 'retornaGrupoPorProjeto',
            array(array('idprojeto' => $projeto->idprojeto)));

        $projeto->demandante = $mapperPessoa->retornaPessoaProjeto(array('idpessoa' => $projeto->iddemandante));
        $projeto->patrocinador = $mapperPessoa->retornaPessoaProjeto(array('idpessoa' => $projeto->idpatrocinador));
        $projeto->gerenteprojeto = $mapperPessoa->retornaPessoaProjeto(array('idpessoa' => $projeto->idgerenteprojeto));
        $projeto->gerenteadjunto = $mapperPessoa->retornaPessoaProjeto(array('idpessoa' => $projeto->idgerenteadjunto));
        $projeto->programa = $mapperPrograma->getById(array('idprograma' => $resultado['idprograma']));

        $projeto->objetivo = $mapperObjetivo->getById(array('idobjetivo' => $resultado['idobjetivo']));
        $projeto->natureza = $mapperNatureza->getById(array('idnatureza' => $resultado['idnatureza']));
        $projeto->escritorio = $mapperEscritorio->getById(array('idescritorio' => $projeto->idescritorio));
        $projeto->acao = $mapperAcao->getById(array('idacao' => $resultado['idacao']));
        $projeto->numprocessosei = $this->maskSei($projeto->numprocessosei, '#####.######/####-##');

        return $projeto;
    }

    public function retornaNumeroCriterioFarol($params)
    {
        $sql = "SELECT numcriteriofarol from agepnet200.tb_projeto where idprojeto = :idprojeto";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return $resultado;
    }


    /**
     *
     * @param array $params
     * @return Projeto_Model_Gerencia
     */
    public function retornaProjetoCronogramaDiasPrevistoPorId($params)
    {
        $dataIniP = $params['dataIniP'];
        $dataFimP = $params['dataFimP'];
        $dataIniR = $params['dataIniR'];
        $dataFimR = $params['dataFimR'];
        $idProjeto = $_REQUEST["idprojeto"];

        $sql = "SELECT
		   case
                    when ent.datfim is null or ent.datinicio is null then '0'
                    else
                        --DATE_PART('day', CONCAT(to_char(':dataFim', 'YYYY-MM-DD'),' ','23:59:59.9999999')::timestamp -
                                         --CONCAT(to_char(':dataIni', 'YYYY-MM-DD'),' ','00:00:00.0000000')::timestamp)
			DATE_PART('day', CONCAT(('{$dataFimP}'),' ','23:59:59.9999999')::timestamp -
                             CONCAT(('{$dataIniP}'),' ','00:00:00.0000000')::timestamp)
                    end as diasplanejados,
                    DATE_PART('day', CONCAT(('{$dataFimR}'),' ','23:59:59.9999999')::timestamp -
                                     CONCAT(('{$dataIniR}'),' ','00:00:00.0000000')::timestamp)
                    as diasrealizados
            FROM
                    agepnet200.tb_projeto proj
                    inner join agepnet200.tb_atividadecronograma ent on proj.idprojeto = ent.idprojeto
            WHERE
                     proj.idprojeto = {$idProjeto}
                     limit 1";

        $retorno = $this->_db->fetchRow($sql);
        return $retorno;
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
                        and proj.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . "
                        and proj.idprojeto in (select idprojeto
						from agepnet200.tb_atividadecronograma) ";

        if (isset($params['idprojeto'])) {
            $idprojeto = $params['idprojeto'];
            $sql .= " and proj.idprojeto <> {$idprojeto} ";
        }

        if (isset($params['idescritorio']) && $params['idescritorio'] != "") {
            $idescritorio = $params['idescritorio'];
            $sql .= " and proj.idescritorio = {$idescritorio}";
        }
        $params = array_filter($params);

        if (@trim($params['nomprojeto']) != "") {
            $nomprojeto = strtoupper($params['nomprojeto']);
            $sql .= " and translate(upper(proj.nomprojeto),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "') "
                . " LIKE ( "
                . " '%' ||
                translate(upper('{$nomprojeto}'),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "')
                || '%')";
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
            WHERE idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " and
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
                    idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " and domstatusprojeto = 1 ";

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

        if (@trim($params['nomprojeto']) != "") {
            $nomprojeto = strtoupper($params['nomprojeto']);
            $sql .= " and translate(upper(nomprojeto),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "') "
                . " LIKE ( "
                . " '%' ||
                translate(upper('{$nomprojeto}'),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "')
                || '%')";
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
                    idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " and 1 = 1 ";

        if (isset($params['idescritorio'])) {
            $idescritorio = $params['idescritorio'];
            $sql .= " and idescritorio = {$idescritorio} ";
        }
        $params = array_filter($params);

        if (@trim($params['nomprojeto']) != "") {
            $nomprojeto = strtoupper($params['nomprojeto']);
            $sql .= " and translate(upper(nomprojeto),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "') "
                . " LIKE ( "
                . " '%' ||
                translate(upper('{$nomprojeto}'),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "')
                || '%')";
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
                    idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " and domstatusprojeto != 1 ";

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

        if (@trim($params['nomprojeto']) != "") {
            $nomprojeto = strtoupper($params['nomprojeto']);
            $sql .= " and translate(upper(nomprojeto),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "') "
                . " LIKE ( "
                . " '%' ||
                translate(upper('{$nomprojeto}'),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "')
                || '%')";
        }

        if (isset($params['idacao']) && $params['idacao'] != '') {
            $idacao = $params['idacao'];
            $sql .= " and idacao = {$idacao} ";
        }
        $retorno = $this->_db->fetchRow($sql);
        return $retorno['totalprojetos'];
    }

    public function getTotalProjetosPorObjetivo($idobjetivo, $params)
    {

        $sql = "SELECT
                    count(*) as totalprojetos
                    FROM 
                    agepnet200.tb_projeto 
                    WHERE 
                    idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " and idobjetivo = " . $idobjetivo;

        if (@trim($params['nomprojeto']) != "") {
            $nomprojeto = strtoupper($params['nomprojeto']);
            $sql .= " and translate(upper(nomprojeto),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "') "
                . " LIKE ( "
                . " '%' ||
                translate(upper('{$nomprojeto}'),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "')
                || '%')";
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
            $sql .= " and idnatureza = {$idnatureza}";
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
                        proj.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " and proj.idescritorio = " . $idescritorio;
        $retorno = $this->_db->fetchAll($sql);
        return $retorno;
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
                WHERE p.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " and p.idprograma = prog.idprograma
                      and p.idescritorio = esc.idescritorio
                      --and p.domstatusprojeto != " . Projeto_Model_Gerencia::STATUS_PROPOSTA . "
                ";

        if (isset($params['idprograma']) && $params['idprograma'] != '') {
            $idprograma = $params['idprograma'];
            $sql .= " and prog.idprograma = {$idprograma}";
        }
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
                WHERE p.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " and p.idnatureza = n.idnatureza
                      and p.idescritorio = esc.idescritorio
                      --and p.domstatusprojeto != " . Projeto_Model_Gerencia::STATUS_PROPOSTA . "
                       ";

        if (isset($params['idprograma']) && $params['idprograma'] != '') {
            $idprograma = $params['idprograma'];
            $sql .= " and p.idprograma = {$idprograma}";
        }

        $sql .= " GROUP BY n.nomnatureza, n.idnatureza
                  ORDER BY total DESC ";

        $retorno = $this->_db->fetchAll($sql);
        return $retorno;
    }

    public function buscaGerentesCadastrados($params)
    {

        $sql = "SELECT
                    :idprojeto as idprojeto, nomparteinteressada as nomparteinteressada, idparteinteressada as idparteinteressada, 'Demandante' as nomfuncao, '-' as destelefoneexterno, desemail as desemailexterno,
                    domnivelinfluencia, idcadastrador, now() as dtcadastro, idpessoainterna, observacao
                    FROM agepnet200.tb_parteinteressada pes where pes.idpessoainterna=(select iddemandante from agepnet200.tb_projeto where idprojeto=:idprojeto) and pes.idprojeto=:idprojeto                
                UNION
                SELECT
                    :idprojeto as idprojeto, nomparteinteressada as nomparteinteressada, idparteinteressada as idparteinteressada, 'Patrocinador' as nomfuncao, '-' as destelefoneexterno, desemail as desemailexterno,
                    domnivelinfluencia, idcadastrador, now() as dtcadastro, idpessoainterna, observacao
                    FROM agepnet200.tb_parteinteressada pes where pes.idpessoainterna=(select idpatrocinador from agepnet200.tb_projeto where idprojeto=:idprojeto) and pes.idprojeto=:idprojeto
                UNION
                SELECT
                    :idprojeto as idprojeto, nomparteinteressada as nomparteinteressada, idparteinteressada as idparteinteressada, 'Gerente do Projeto' as nomfuncao, '-' as destelefoneexterno, desemail as desemailexterno,
                    domnivelinfluencia, idcadastrador, now() as dtcadastro, idpessoainterna, observacao
                    FROM agepnet200.tb_parteinteressada pes where pes.idpessoainterna=(select idgerenteprojeto from agepnet200.tb_projeto where idprojeto=:idprojeto)  and pes.idprojeto=:idprojeto
                UNION
                SELECT
                    :idprojeto as idprojeto, nomparteinteressada as nomparteinteressada, idparteinteressada as idparteinteressada, 'Gerente Adjunto do Projeto' as nomfuncao, '-' as destelefoneexterno, desemail as desemailexterno,
                    domnivelinfluencia, idcadastrador, now() as dtcadastro, idpessoainterna, observacao
                    FROM agepnet200.tb_parteinteressada pes where pes.idpessoainterna=(select idgerenteadjunto from agepnet200.tb_projeto where idprojeto=:idprojeto) and pes.idprojeto=:idprojeto 
                    ORDER BY nomparteinteressada, nomfuncao";

        if (isset($params['idprojeto'])) {
            $retorno = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));
        } elseif (isset($params['idprojetoexterno'])) {
            $retorno = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojetoexterno']));
        }

        return $retorno;


    }

    public function buscaGerentesNaoCadastradosProjeto($params)
    {
        $sql = "SELECT
                :idprojeto as idprojeto, nompessoa as nomparteinteressadaexterno, idpessoa as idparteinteressadaexterno, 'Demandante' as nomfuncaoexterno, '-' as destelefoneexterno, desemail as desemailexterno,
                'Alto' as domnivelinfluenciaexterno, idcadastrador, now() as dtcadastro, idpessoa as idpessoainterna, '' as observacaoexterno
                FROM agepnet200.tb_pessoa pes where idpessoa=(select iddemandante from agepnet200.tb_projeto where idprojeto=:idprojeto) and not exists(
                select 1 from agepnet200.tb_parteinteressada p2 where p2.idpessoainterna=pes.idpessoa and p2.idprojeto=:idprojeto
                ) union
            SELECT
                :idprojeto as idprojeto, nompessoa as nomparteinteressadaexterno, idpessoa as idparteinteressadaexterno, 'Patrocinador' as nomfuncaoexterno, '-' as destelefoneexterno, desemail as desemailexterno,
                'Alto' as domnivelinfluenciaexterno, idcadastrador, now() as dtcadastro, idpessoa as idpessoainterna, '' as observacaoexterno
                FROM agepnet200.tb_pessoa pes where idpessoa=(select idpatrocinador from agepnet200.tb_projeto where idprojeto=:idprojeto) and not exists(
                select 1 from agepnet200.tb_parteinteressada p2 where p2.idpessoainterna=pes.idpessoa and p2.idprojeto=:idprojeto
                )union
            SELECT
                :idprojeto as idprojeto, nompessoa as nomparteinteressadaexterno, idpessoa as idparteinteressadaexterno, 'Gerente do Projeto' as nomfuncaoexterno, '-' as destelefoneexterno, desemail as desemailexterno,
                'Alto' as domnivelinfluenciaexterno, idcadastrador, now() as dtcadastro, idpessoa as idpessoainterna, '' as observacaoexterno
                FROM agepnet200.tb_pessoa pes where idpessoa=(select idgerenteprojeto from agepnet200.tb_projeto where idprojeto=:idprojeto) and not exists(
                select 1 from agepnet200.tb_parteinteressada p2 where p2.idpessoainterna=pes.idpessoa and p2.idprojeto=:idprojeto
                )union
            SELECT
                :idprojeto as idprojeto, nompessoa as nomparteinteressadaexterno, idpessoa as idparteinteressadaexterno, 'Gerente Adjunto do Projeto' as nomfuncaoexterno, '-' as destelefoneexterno, desemail as desemailexterno,
                'Alto' as domnivelinfluenciaexterno, idcadastrador, now() as dtcadastro, idpessoa as idpessoainterna, '' as observacaoexterno
                FROM agepnet200.tb_pessoa pes where idpessoa=(select idgerenteadjunto from agepnet200.tb_projeto where idprojeto=:idprojeto) and not exists(
                select 1 from agepnet200.tb_parteinteressada p2 where p2.idpessoainterna=pes.idpessoa and p2.idprojeto=:idprojeto
                ) and exists(
                select 1 from agepnet200.tb_projeto pj where pj.idgerenteadjunto is not null and pj.idprojeto=:idprojeto
           )";

        $retorno = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));
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
                        proj.idtipoiniciativa,
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
                where  proj.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " and 1 = 1 ";


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

        if (@trim($params['nomprojeto']) != "") {
            $nomprojeto = strtoupper($params['nomprojeto']);
            $sql .= " and translate(upper(proj.nomprojeto),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "') "
                . " LIKE ( "
                . " '%' ||
                translate(upper('{$nomprojeto}'),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "')
                || '%')";
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
                        and proj.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . "
                        and proj.idescritorio = esc.idescritorio 
                        and proj.idprojeto in (select idprojeto
						from agepnet200.tb_atividadecronograma)";

        $params = array_filter($params);
        if (isset($params['idescritorio']) && $params['idescritorio'] != "") {
            $idescritorio = $params['idescritorio'];
            $sql .= " and proj.idescritorio = {$idescritorio}";
        }
        if (@trim($params['nomprojeto']) != "") {
            $nomprojeto = strtoupper($params['nomprojeto']);
            $sql .= " and translate(upper(proj.nomprojeto),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "') "
                . " LIKE ( "
                . " '%' ||
                translate(upper('{$nomprojeto}'),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "')
                || '%')";
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

    public function buscarNaturezas($params)
    {
        $sql = "SELECT distinct nat.idnatureza as idnatureza, nat.nomnatureza as nomnatureza
                FROM agepnet200.tb_natureza nat
                where nat.idnatureza is not null ";

        $params = array_filter($params);
        if (
            (isset($params['idescritorio']) && (@trim($params['idescritorio']) != "")) ||
            (isset($params['idprograma']) && (@trim($params['idprograma']) != "")
                && (@trim($params['idprograma']) != "1")
            )
        ) {
            $sql .= " and nat.idnatureza in (
                select proj.idnatureza FROM agepnet200.tb_projeto proj
                    where proj.idtipoiniciativa = 1 ";
            if (isset($params['idescritorio']) && (@trim($params['idescritorio']) != "")) {
                $idescritorio = $params['idescritorio'];
                $sql .= " and idescritorio = {$idescritorio} ";
            }
            if (isset($params['idprograma']) && (@trim($params['idprograma']) != "")
                && (@trim($params['idprograma']) != "1")
            ) {
                $idprograma = $params['idprograma'];
                $sql .= " and idprograma = {$idprograma} ";
            }
            $sql .= " ) ";
        }
        $sql .= " order by nat.nomnatureza asc ";
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
                    proj.idtipoiniciativa,
                    proj.vlrorcamentodisponivel
            FROM
                    agepnet200.tb_projeto proj
            WHERE
                   proj.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " and 1 = 1 ";

        if (isset($params['idprojetos']) && count($params['idprojetos']) > 0 && !empty($params['idprojetos'][0])) {
            $ids = implode(",", $params['idprojetos']);
            $sql .= " and idprojeto in ({$ids}) ";
        }

        if (isset($params['idnaturezas']) && count($params['idnaturezas']) > 0 && !empty($params['idnaturezas'][0])) {
            $idns = implode(",", $params['idnaturezas']);
            $sql .= " and proj.idnatureza in ({$idns}) ";
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

        if (@trim($params['nomprojeto']) != "") {
            $nomprojeto = strtoupper($params['nomprojeto']);
            $sql .= " and translate(upper(proj.nomprojeto),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "') "
                . " LIKE ( "
                . " '%' ||
                translate(upper('{$nomprojeto}'),
                '" . $this->stringsEncoded . "',
                '" . $this->stringsDecoded . "')
                || '%')";
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
                    proj.idtipoiniciativa,
                    --proj.nomproponente,
                    --proj.nomsigla,
                    --proj.numanoprojeto,
                    --proj.numcriteriofarol,
                    proj.numperiodicidadeatualizacao
                    --proj.numseqprojeto,
                    --proj.vlrorcamentodisponivel
            FROM
                    agepnet200.tb_projeto proj
                    left join agepnet200.tb_pessoa p4 on p4.idpessoa   = proj.idgerenteadjunto,
                    agepnet200.tb_pessoa p2,
                    agepnet200.tb_pessoa p3,
                    agepnet200.tb_escritorio e
            WHERE
                    proj.domstatusprojeto = 2
                    and proj.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . "
                    and p2.idpessoa = proj.idpatrocinador
                    and p3.idpessoa = proj.idgerenteprojeto
                    and e.idescritorio = proj.idescritorio ";

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
    }


    public function retornaDataFimProjeto($params)
    {
        $sql = "select
                  to_char(datfim,'dd/mm/YYYY')  as datfim
                from agepnet200.tb_projeto
                where idprojeto = :idprojeto";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
        ));
        return $resultado;
    }

    /**
     *
     * @param array $params
     * @return \Zend_Paginator | array
     */
    public function retornaNumDiasProjeto($params)
    {
        $sql = "SELECT case when (
            SELECT min(atc.datiniciobaseline)
            FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
            and atc.domtipoatividade in (3,4)
        ) is null or (
            SELECT max(atc.datfimbaseline)
            FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
            and atc.domtipoatividade in (3,4)
        ) is null then '0'
        else(
            SELECT count(*) AS diasuteis
            FROM generate_series(
            to_timestamp(to_char((
            SELECT min(atc.datiniciobaseline)
            FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
            and atc.domtipoatividade in (3,4)
            ), 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
            to_timestamp(to_char((
            SELECT max(atc.datfimbaseline)
            FROM agepnet200.tb_atividadecronograma atc WHERE atc.idprojeto = proj.idprojeto
            and atc.domtipoatividade in (3,4)
            ), 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'	) the_day
            WHERE  extract('ISODOW' FROM the_day) < 6
            and to_char(the_day,'dd/mm') not in(
              SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
              FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
            )
            and to_char(the_day,'dd/mm/yyyy') not in(
                SELECT
                lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
            )
        )
        end as numdiasbaseline,
        (
            SELECT sum(round(coalesce(
            case
            when cronProjB.datfimbaseline is null or cronProjB.datiniciobaseline is null or cronProjB.domtipoatividade = 4 then '0'
            else(
                SELECT count(*) AS diasuteis
                FROM generate_series(
                  to_timestamp(to_char(cronProjB.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                  to_timestamp(to_char(cronProjB.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                ) the_day
                WHERE  extract('ISODOW' FROM the_day) < 6
                and to_char(the_day,'dd/mm') not in(
                SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                )
                and to_char(the_day,'dd/mm/yyyy') not in(
                SELECT lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                )
            ) end,0) , 2)) as totaldiasbaseline
            FROM agepnet200.tb_atividadecronograma cronProjB
            WHERE cronProjB.idprojeto = proj.idprojeto and cronProjB.domtipoatividade in (3,4)
            and (cronProjB.flacancelada='N' or cronProjB.flacancelada is null)
        ) as totaldiasbaseline,
        (
            SELECT sum(round(coalesce(
            case
            when cronProj.datfimbaseline is null or cronProj.datiniciobaseline is null  or cronProj.domtipoatividade = 4 then '0'
            when to_date(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then '0'
            when to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') = to_char(now(), 'DD/MM/YYYY') then '0'
            when to_date(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') > to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
            (
                SELECT count(*) AS diasuteis
                FROM generate_series(
                to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                to_timestamp(to_char(now(),    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                ) the_day
                WHERE  extract('ISODOW' FROM the_day) < 6
                and to_char(the_day,'dd/mm') not in(
                SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                )
                and to_char(the_day,'dd/mm/yyyy') not in(
                    SELECT
                    lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                    lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                    lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                    FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                )
            )
            when to_date(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY'),'DD/MM/YYYY') < to_date(to_char(now(), 'DD/MM/YYYY'),'DD/MM/YYYY') then
            (
                SELECT count(*) AS diasuteis
                FROM generate_series(
                  to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                  to_timestamp(to_char(cronProj.datfimbaseline, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                ) the_day
                WHERE  extract('ISODOW' FROM the_day) < 6
                and to_char(the_day,'dd/mm') not in(
                  SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                  FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                )
                and to_char(the_day,'dd/mm/yyyy') not in(
                    SELECT
                    lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                    lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                    lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                    FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                )
            )
            else(
                SELECT count(*) AS diasuteis
                FROM generate_series(
                  to_timestamp(to_char(cronProj.datiniciobaseline, 'DD/MM/YYYY') || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                  to_timestamp(to_char(cronProj.datfimbaseline,    'DD/MM/YYYY') || ' 00:00:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp, '24 hours'
                ) the_day
                WHERE  extract('ISODOW' FROM the_day) < 6
                and to_char(the_day,'dd/mm') not in(
                  SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                  FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                )
                and to_char(the_day,'dd/mm/yyyy') not in(
                    SELECT
                    lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                    lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                    lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                    FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                )
            ) end,0) , 2)) as numdiascompletos
            FROM
            agepnet200.tb_atividadecronograma cronProj
            WHERE
            cronProj.idprojeto = proj.idprojeto and cronProj.domtipoatividade in (3,4)
            and (cronProj.flacancelada='N' or cronProj.flacancelada is null)
        ) as numdiascompletos,
        (
            SELECT SUM(ROUND(coalesce(
            case
            when (
                SELECT min(atc1.datinicio)
                FROM agepnet200.tb_atividadecronograma atc1 WHERE atc1.idprojeto = proj.idprojeto
                and atc1.domtipoatividade in (3,4) and atc1.idgrupo in (
                    select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                    where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                    and entrega.idgrupo in(
                      select grp.idatividadecronograma
                      from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                      and grp.domtipoatividade=1
                    )
                )
            ) is null or (
                SELECT max(atc2.datfim)
                FROM agepnet200.tb_atividadecronograma atc2 WHERE atc2.idprojeto = proj.idprojeto
                and atc2.domtipoatividade in (3,4) and atc2.idgrupo in (
                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                and entrega.idgrupo in(
                  select grp.idatividadecronograma
                  from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                  and grp.domtipoatividade=1
                )
                )
            ) is null then '0'
            else(
                SELECT count(*) AS diasuteis
                FROM generate_series(
                to_timestamp(to_char(atividade.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
                to_timestamp(to_char(atividade.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
                ) the_day
                WHERE  extract('ISODOW' FROM the_day) < 6
                and to_char(the_day,'dd/mm') not in(
                  SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
                  FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
                )
                and to_char(the_day,'dd/mm/yyyy') not in(
                    SELECT
                    lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                    lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                    lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                    FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
                )
            )
            end ,1),2)) as numdiasrealizados
            FROM agepnet200.tb_atividadecronograma atividade
            WHERE
            atividade.idprojeto = proj.idprojeto and atividade.domtipoatividade=3
            and (atividade.flacancelada='N' or atividade.flacancelada is null)
            and atividade.idgrupo in (
                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                and entrega.idgrupo in(
                  select grp.idatividadecronograma
                  from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                  and grp.domtipoatividade=1
                )
            )
        ) as numdiasrealizados,
        (
            SELECT SUM(ROUND(coalesce(
            case
            when (
                SELECT min(atc1.datinicio)
                FROM agepnet200.tb_atividadecronograma atc1 WHERE atc1.idprojeto = proj.idprojeto
                and atc1.domtipoatividade in (3,4) and atc1.idgrupo in (
                    select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                    where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                    and entrega.idgrupo in(
                      select grp.idatividadecronograma
                      from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                      and grp.domtipoatividade=1
                    )
                )
            ) is null or (
                SELECT max(atc2.datfim)
                FROM agepnet200.tb_atividadecronograma atc2 WHERE atc2.idprojeto = proj.idprojeto
                and atc2.domtipoatividade in (3,4) and atc2.idgrupo in (
                    select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                    where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                    and entrega.idgrupo in(
                      select grp.idatividadecronograma
                      from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                      and grp.domtipoatividade=1
                    )
                )
            ) is null then '0'
            else(((((
            SELECT count(*) AS diasuteis
            FROM generate_series(
            to_timestamp(to_char(atividade.datinicio, 'DD/MM/YYYY') || ' 00:30:00', 'DD/MM/YYYY HH24:MI:SS')::timestamp,
            to_timestamp(to_char(atividade.datfim, 'DD/MM/YYYY')    || ' 23:59:59', 'DD/MM/YYYY HH24:MI:SS')::timestamp, interval  '1 day'
            ) the_day
            WHERE  extract('ISODOW' FROM the_day) < 6
            and to_char(the_day,'dd/mm') not in(
            SELECT lpad(trim(to_char(tf.diaferiado, '99')),2,'0')||'/'|| lpad(trim(to_char(tf.mesferiado, '99')),2,'0') as ddmm
            FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='1'
            )
            and to_char(the_day,'dd/mm/yyyy') not in(
                SELECT
                lpad(trim(to_char(tf.diaferiado, '99'))   ,2,'0')||'/'||
                lpad(trim(to_char(tf.mesferiado, '99'))   ,2,'0')||'/'||
                lpad(trim(to_char(tf.anoferiado, '9999')) ,4,'0') as ddmmaaaa
                FROM agepnet200.tb_feriado tf where tf.flaativo='S' AND tf.tipoferiado='2'
            )))*coalesce(atividade.numpercentualconcluido,0))/100))
            end ,1),2)) as numdiasrealizadosreal
            FROM agepnet200.tb_atividadecronograma atividade
            WHERE
            atividade.idprojeto = proj.idprojeto and atividade.domtipoatividade=3
            and (atividade.flacancelada='N' or atividade.flacancelada is null)
            and atividade.idgrupo in (
                select entrega.idatividadecronograma FROM agepnet200.tb_atividadecronograma entrega
                where entrega.domtipoatividade=2 and entrega.idprojeto = proj.idprojeto
                and entrega.idgrupo in(
                    select grp.idatividadecronograma
                    from agepnet200.tb_atividadecronograma grp where grp.idprojeto = proj.idprojeto
                    and grp.domtipoatividade=1
                )
            )
        ) as numdiasrealizadosreal
        FROM
        agepnet200.tb_projeto proj
        WHERE
        proj.idtipoiniciativa = 1 and proj.idprojeto = :idprojeto ";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return $resultado;
    }

    public function retornaPartesInteressadas($params, $model = false)
    {
        return $this->_mapperParteInteressada->retornaPartesInternas($params, $model);
    }

    public function retornaPartesByIdProjeto($params)
    {
        return $this->_mapperParteInteressada->getById($params);
    }

    /**
     * Set the property
     *
     * @param string $value
     */
    public function alterarStatusProjeto($params)
    {
        $data = array(
            "idprojeto" => $params['idprojeto'],
            "domstatusprojeto" => $params['domstatusprojeto'],
        );

        try {
            $pks = array("idprojeto" => $params['idprojeto']);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            if ($this->getDbTable()->update($data, $where)) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exc) {
            return false;
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
        $data = array(
            "idprojeto" => $params['idprojeto'],
            "domstatusprojeto" => $params['domstatusprojeto'],
        );

        try {
            $pks = array("idprojeto" => $params['idprojeto']);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            return $this->getDbTable()->update($data, $where);
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function isGerenteORAdjuntoByEscritorio($idprojeto, $idEscritorio, $idGerente)
    {
        $sql = "SELECT
                    count(proj.idprojeto)                     
                FROM
                    agepnet200.tb_projeto proj
                WHERE
                    proj.idtipoiniciativa = " . Projeto_Model_Gerencia::TIPO_INICIATIVA_PROJETO . " 
                    and proj.idescritorio = $idEscritorio 
                    and (proj.idgerenteprojeto = $idGerente OR proj.idgerenteadjunto = $idGerente) ";

        if (isset($idprojeto) && $idprojeto != null) {
            $sql .= "and proj.idprojeto=$idprojeto ";
        }
        $resultado = $this->_db->fetchOne($sql);
        return ($resultado > 0) ? true : false;

    }

    public function retornaNumProcessoSei($params)
    {
        $sql = "select numprocessosei  
                from agepnet200.tb_projeto 
                where idprojeto = {$params['idprojeto']}";

        $resultado = $this->_db->fetchRow($sql);
        return $resultado;
    }

    /**
     * @param array $params
     */
    public function updateNumProcessoSei($params)
    {
        if (!empty($params['numprocessosei'])) {
            $data = array(
                "numprocessosei" => $params['numprocessosei'],
            );
        }

        try {
            $pks = array("idprojeto" => $params['idprojeto']);
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function maskSei($val, $mask)
    {
        $maskared = '';
        $k = 0;

        if (!empty($val)) {
            for ($i = 0; $i <= strlen($mask) - 1; $i++) {
                if ($mask[$i] == '#') {
                    if (isset($val[$k])) {
                        $maskared .= $val[$k++];
                    }
                } else {
                    if (isset($mask[$i])) {
                        $maskared .= $mask[$i];
                    }
                }
            }
            return $maskared;
        } else {
            return $val;
        }
    }

    public function retornaPublicos($params)
    {

        $sql = "SELECT flapublicado FROM agepnet200.tb_projeto 
        WHERE idprojeto = {$params} ";

        $resultado = $this->_db->fetchOne($sql);
        return $resultado;
    }

    /**
     * FUNCÃO QUE ATUALIZA AS PARTES DO TAP
     * @param Projeto_Model_Parteinteressada $parte
     * @return boolean
     */
    public function updatePartesProjeto($parte)
    {
        $data = array();
        if ($parte::DEMANDANTE == $parte->nomfuncao) {
            $data['iddemandante'] = $parte->idpessoainterna;
        } elseif ($parte::PATROCINADOR == $parte->nomfuncao) {
            $data['idpatrocinador'] = $parte->idpessoainterna;
        } elseif ($parte::GERENTE_ADJUNTO == $parte->nomfuncao) {
            $data['idgerenteadjunto'] = $parte->idpessoainterna;
        } elseif ($parte::GERENTE_PROJETO == $parte->nomfuncao) {
            $data['idgerenteprojeto'] = $parte->idpessoainterna;
        }

        if (count($data) > 0) {
            $pks = array(
                "idprojeto" => $parte->idprojeto,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $this->getDbTable()->update($data, $where);
            return true;
        } else {
            return false;
        }
    }

    public function updateTapAssinado($dados)
    {
        if ($dados['flaaprovado'] == '1') {
            $data["flaaprovado"] = "S";
        } else {
            $data["flaaprovado"] = "N";
        }

        $pks = array(
            "idprojeto" => $dados["idprojeto"],
        );
        $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
        $this->getDbTable()->update($data, $where);
        return true;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function clonarProjeto($params)
    {
        $sql = 'SELECT agepnet200."ClonarProjeto"(:idprojetobase, :idcadastrador, :idescritorio, :nomcodigo)';

        $resultado = $this->_db->fetchOne($sql, array(
            'idprojetobase'     => $params['idprojeto'],
            'idcadastrador'     => $params['idcadastrador'],
            'idescritorio'      => $params['idescritorio'],
            'nomcodigo'         => $params['nomcodigo']
        ));

        return $resultado;
    }

}
