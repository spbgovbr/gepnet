<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Pessoal_Model_Mapper_Atividade extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Atividade
     */
    public function insert(Pessoal_Model_Atividade $model)
    {

        try {
            $model->idatividade = $this->maxVal('idatividade');
            $datfimmeta = !empty($model->datfimmeta) ? new Zend_Db_Expr("to_date('" . $model->datfimmeta->toString('Y-m-d') . "','YYYY-MM-DD')") : null;
            $datfimreal = !empty($model->datfimreal) ? new Zend_Db_Expr("to_date('" . $model->datfimreal->toString('Y-m-d') . "','YYYY-MM-DD')") : null;

            $data = array(
                "idatividade" => $model->idatividade,
                "nomatividade" => $model->nomatividade,
                "desatividade" => $model->desatividade,
                "idcadastrador" => $model->idcadastrador,
                "idresponsavel" => $model->idresponsavel,
                "datcadastro" => new Zend_Db_Expr("now()"),
                "datatualizacao" => new Zend_Db_Expr("now()"),
                "datinicio" => new Zend_Db_Expr("to_date('" . $model->datinicio->toString('Y-m-d') . "','YYYY-MM-DD')"),
                "datfimmeta" => $datfimmeta,
                "datfimreal" => $datfimreal,
                "flacontinua" => $model->flacontinua,
                "numpercentualconcluido" => $model->numpercentualconcluido,
                "flacancelada" => $model->flacancelada,
                "idescritorio" => $model->idescritorio,
            );

            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }

    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Pessoal_Model_Atividade
     */
    public function update(Pessoal_Model_Atividade $model)
    {

        $datfimmeta = !empty($model->datfimmeta) ? new Zend_Db_Expr("to_date('" . $model->datfimmeta->toString('Y-m-d') . "','YYYY-MM-DD')") : null;
        $datfimreal = !empty($model->datfimreal) ? new Zend_Db_Expr("to_date('" . $model->datfimreal->toString('Y-m-d') . "','YYYY-MM-DD')") : null;

        $data = array(
            "idatividade" => $model->idatividade,
            "nomatividade" => $model->nomatividade,
            "desatividade" => $model->desatividade,
            "idresponsavel" => $model->idresponsavel,
            "datatualizacao" => new Zend_Db_Expr("now()"),
            "datinicio" => new Zend_Db_Expr("to_date('" . $model->datinicio->toString('Y-m-d') . "','YYYY-MM-DD')"),
            "datfimmeta" => $datfimmeta,
            "datfimreal" => $datfimreal,
            "flacontinua" => $model->flacontinua,
            "numpercentualconcluido" => $model->numpercentualconcluido,
            "flacancelada" => $model->flacancelada,
        );
        try {
            $pks = array(
                "idatividade" => $model->idatividade,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->update($data, $where);
            return $retorno;
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
    public function pesquisar($params, $paginator = false)
    {

        $sql = "
                    SELECT 	
                                    ativ.nomatividade,
                                    to_char(ativ.datinicio, 'DD/MM/YYYY') as datinicio, 
                                    to_char(ativ.datfimmeta, 'DD/MM/YYYY') as datfimmeta, 
                                    to_char(ativ.datfimreal, 'DD/MM/YYYY') as datfimreal, 
                                    p1.nompessoa as nomcadastrador,
                                    p2.nompessoa as nomresponsavel, 
                                    ativ.numpercentualconcluido as numpercentualconcluido,
                                    CASE WHEN ativ.flacontinua = 1 THEN 'Sim'	
                                         WHEN ativ.flacontinua = 2 THEN 'Não'
                                    END as flacontinua,
                                    to_char(ativ.datatualizacao, 'DD/MM/YYYY') as datatualizacao,
                                    (SELECT esc.nomescritorio 
                                        FROM agepnet200.tb_escritorio esc 
                                        WHERE esc.idescritorio = ativ.idescritorio) as nomescritorio,
                                    ativ.idatividade as idatividade
                            FROM 
                                    agepnet200.tb_atividade ativ,
                                    agepnet200.tb_pessoa p1,
                                    agepnet200.tb_pessoa p2
                            WHERE 	
                                    p1.idpessoa = ativ.idcadastrador
                                    and p2.idpessoa = ativ.idresponsavel
			";

        $params = array_filter($params);
        if (isset($params['nomatividade'])) {
            $nomatividade = strtoupper($params['nomatividade']);
            $sql .= " AND upper(ativ.nomatividade) LIKE '%{$nomatividade}%'";
        }

        if (isset($params['datinicio'])) {
            $sql .= " AND ativ.datinicio = to_date('{$params['datinicio']}', 'DD/MM/YYYY')";
        }

        if (isset($params['inicioperiodo']) && isset($params['fimperiodo'])) {
            $sql .= " AND ativ.datinicio between to_date('{$params['inicioperiodo']}', 'DD/MM/YYYY') AND to_date('{$params['fimperiodo']}', 'DD/MM/YYYY')";
        } elseif (isset($params['inicioperiodo'])) {
            $sql .= " AND ativ.datinicio >= to_date('{$params['inicioperiodo']}', 'DD/MM/YYYY')";
        } elseif (isset($params['fimperiodo'])) {
            $sql .= " AND ativ.datinicio <= to_date('{$params['fimperiodo']}', 'DD/MM/YYYY')";
        }

        if (isset($params['nomresponsavel'])) {
            $nomresponsavel = strtoupper($params['nomresponsavel']);
            $sql .= " AND upper(p2.nompessoa) LIKE '%{$nomresponsavel}%'";
        }

        if (isset($params['idescritorio'])) {
            $sql .= " AND ativ.idescritorio = {$params['idescritorio']}";
        }
        if (isset($params['flacontinua']) && $params['flacontinua'] != 'T') {
            $sql .= " AND ativ.flacontinua = {$params['flacontinua']}";
        }
        if (isset($params['concluida'])) {
            ($params['concluida'] == 1) ? $sql .= " AND ativ.numpercentualconcluido = 100 " : "";
            ($params['concluida'] == 2) ? $sql .= " AND ativ.numpercentualconcluido < 100 " : "";
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


    public function getForm()
    {
        return $this->_getForm(Pessoal_Form_Atividade);
    }


    public function getById($params)
    {
        $sql = "    SELECT 
                        ativ.idatividade as idatividade,
                        ativ.nomatividade as nomatividade,
                        ativ.desatividade as desatividade,
                        to_char(ativ.datcadastro, 'DD/MM/YYYY') as datcadastro,
                        to_char(ativ.datatualizacao, 'DD/MM/YYYY') as datatualizacao,
                        to_char(ativ.datinicio, 'DD/MM/YYYY') as datinicio, 
                        to_char(ativ.datfimmeta, 'DD/MM/YYYY') as datfimmeta, 
                        to_char(ativ.datfimreal, 'DD/MM/YYYY') as datfimreal,
                        ativ.flacontinua as flacontinua,
                        ativ.flacancelada as flacancelada,
                        ativ.numpercentualconcluido as numpercentualconcluido,
                        p1.nompessoa as nomcadastrador,
                        p2.nompessoa as nomresponsavel,
                        esc.nomescritorio as nomescritorio,
                        ativ.idescritorio as idescritorio,
                        ativ.idresponsavel as idresponsavel
                    FROM 
                        agepnet200.tb_atividade ativ,
                        agepnet200.tb_pessoa p1,
                        agepnet200.tb_pessoa p2,
                        agepnet200.tb_escritorio esc
                    WHERE 
                        p1.idpessoa = ativ.idcadastrador
                        and p2.idpessoa = ativ.idresponsavel
                        and ativ.idescritorio = esc.idescritorio
                        and ativ.idatividade = :idatividade";

        $resultado = $this->_db->fetchRow($sql, array('idatividade' => $params['idatividade']));
        $atividade = new Pessoal_Model_Atividade($resultado);
        return $atividade;
    }

    public function getByIdDetalhar($params)
    {
        $sql = "    SELECT 
                        ativ.idatividade as idatividade,
                        ativ.nomatividade as nomatividade,
                        ativ.desatividade as desatividade,
                        to_char(ativ.datcadastro, 'DD/MM/YYYY') as datcadastro,
                        to_char(ativ.datatualizacao, 'DD/MM/YYYY') as datatualizacao,
                        to_char(ativ.datinicio, 'DD/MM/YYYY') as datinicio, 
                        to_char(ativ.datfimmeta, 'DD/MM/YYYY') as datfimmeta, 
                        to_char(ativ.datfimreal, 'DD/MM/YYYY') as datfimreal, 
                        CASE WHEN ativ.flacontinua = 1 THEN 'Sim'	
                             WHEN ativ.flacontinua = 2 THEN 'Não'
                        END as flacontinua,
                        ativ.numpercentualconcluido as numpercentualconcluido,
                        CASE WHEN ativ.flacancelada = 1 THEN 'Sim'	
                             WHEN ativ.flacancelada = 2 THEN 'Não'
                        END as flacancelada,
                        p1.nompessoa as nomcadastrador,
                        p2.nompessoa as nomresponsavel,
                        esc.nomescritorio as nomescritorio,
                        ativ.idescritorio as idescritorio,
                        ativ.idresponsavel as idresponsavel
                    FROM 
                        agepnet200.tb_atividade ativ,
                        agepnet200.tb_pessoa p1,
                        agepnet200.tb_pessoa p2,
                        agepnet200.tb_escritorio esc
                    WHERE 
                        p1.idpessoa = ativ.idcadastrador
                        and p2.idpessoa = ativ.idresponsavel
                        and ativ.idescritorio = esc.idescritorio
                        and ativ.idatividade = :idatividade";

        $resultado = $this->_db->fetchRow($sql, array('idatividade' => $params['idatividade']));
        $atividade = new Pessoal_Model_Atividade($resultado);
        return $atividade;
    }

    public function fetchPairsPercentual()
    {
        return array(0 => '0', 25 => '25', 50 => '50', 75 => '75', 100 => '100');
    }


}

