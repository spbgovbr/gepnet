<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Planejamento_Model_Mapper_Acao extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Planejamento_Model_Acao
     */
    public function insert(Planejamento_Model_Acao $model)
    {
        $model->idacao = $this->maxVal('idacao');
        $model->numseq = $this->maxVal('numseq');
//        $model->idcadastrador   = '30605';
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $model->idcadastrador = $auth->getIdentity()->idpessoa;
        }
        $data = array(
            "idacao" => $model->idacao,
            "idobjetivo" => $model->idobjetivo,
            "nomacao" => $model->nomacao,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr("now()"),
            "flaativo" => $model->flaativo,
            "desacao" => $model->desacao,
            "idescritorio" => $model->idescritorio,
            "numseq" => $model->numseq,
        );
//        $this->getDbTable()->insert($data);
        try {
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
     * @return Planejamento_Model_Acao
     */
    public function update(Planejamento_Model_Acao $model)
    {
        $data = array(
//            "idacao"        => $model->idacao,
//            "idobjetivo"    => $model->idobjetivo,
            "nomacao" => $model->nomacao,
//            "idcadastrador" => $model->idcadastrador,
//            "datcadastro"   => $model->datcadastro,
            "flaativo" => $model->flaativo,
            "desacao" => $model->desacao,
            "idescritorio" => $model->idescritorio,
//            "numseq"        => $model->numseq,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));

        try {
            $pks = array(
                "idacao" => $model->idacao,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            //$where = $this->_db->quoteInto('idpessoa = ?', $model->idpessoa);
            $retorno = $this->getDbTable()->update($data, $where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function getForm()
    {
        return $this->_getForm(Planejamento_Form_Acao);
    }


    public function fetchPairs()
    {
        $sql = " SELECT idacao, nomacao FROM agepnet200.tb_acao order by nomacao asc";
        return $this->_db->fetchPairs($sql);
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
                            a.nomacao,
                            (SELECT p.nompessoa FROM agepnet200.tb_pessoa p WHERE p.idpessoa = a.idcadastrador) as idcadastrador,
                            to_char(a.datcadastro, 'DD/MM/YYYY') as datcadastro,
                            CASE a.flaativo
                                WHEN 'S' THEN 'SIM'
                                WHEN 'N' THEN 'Não'
                            END as flaativo,
                            --a.desacao,
                            (SELECT e.nomescritorio FROM agepnet200.tb_escritorio e WHERE e.idescritorio = a.idescritorio) as idescritorio,
                            --a.numseq
                            a.idobjetivo,
                            a.idacao
                    FROM
                            agepnet200.tb_acao a
                    WHERE 
                            1=1
    		";

//            Zend_Debug::dump($params);
//    	$params = array_filter($params);
        if (isset($params['nomacao'])) {
            $nomacao = strtoupper($params['nomacao']);
            $sql .= " AND upper(nomacao) LIKE '%{$nomacao}%'";
        }
        if (isset($params['idobjetivo'])) {
            $idobjetivo = $params['idobjetivo'];
            $sql .= " AND a.idobjetivo = {$idobjetivo} ";
        }

        if (isset($params['sidx'])) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }
        //Zend_Debug::dump($sql);exit;

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
//     	Zend_Debug::dump($resultado);exit;
        return $resultado;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "
                    SELECT 
                            a.idacao,
                            a.idobjetivo,
                            a.nomacao,
                            a.idcadastrador,
                            a.datcadastro,
                            a.flaativo,
                            a.desacao,
                            a.idescritorio,
                            a.numseq
                    FROM
                            agepnet200.tb_acao a
                    WHERE
                            a.idacao = :idacao
            ";

        $resultado = $this->_db->fetchRow($sql, array('idacao' => $params['idacao']));
        $processo = new Planejamento_Model_Acao($resultado);
        return $processo;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getByIdDetalhar($params)
    {
        $sql = "    
                    SELECT 
                            a.idacao,
                            a.idobjetivo,
                            a.nomacao,
                            (SELECT p.nompessoa FROM agepnet200.tb_pessoa p WHERE p.idpessoa = a.idcadastrador) as idcadastrador,
                            to_char(a.datcadastro, 'DD/MM/YYYY') as datcadastro,
                            CASE a.flaativo
                                WHEN 'S' THEN 'SIM'
                                WHEN 'N' THEN 'Não'
                            END as flaativo,
                            a.desacao,
                            (SELECT e.nomescritorio FROM agepnet200.tb_escritorio e WHERE e.idescritorio = a.idescritorio) as idescritorio,
                            a.numseq
                    FROM
                            agepnet200.tb_acao a
                    WHERE
                            a.idacao = :idacao
            ";

        $resultado = $this->_db->fetchRow($sql, array('idacao' => $params['idacao']));
        $processo = new Planejamento_Model_Acao($resultado);
        return $processo;
    }

    public function getByObjetivo($params, $model = false)
    {

        $sql = "SELECT
                    idacao,
                    nomacao,
                    idobjetivo,
                    idescritorio
                FROM agepnet200.tb_acao
                WHERE idobjetivo = :idobjetivo 
                ORDER BY nomacao ";

        $resultado = $this->_db->fetchAll($sql, array('idobjetivo' => $params['idobjetivo']));

        if ($model) {
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Planejamento_Model_Acao');
            foreach ($resultado as $r) {
                $o = new Planejamento_Model_Acao($r);
                $collection[] = $o;
            }
            return $collection;
        }
        return $resultado;
    }


    public function fetchPairsByObjetivo($params)
    {
        $sql = "SELECT idacao, nomacao FROM agepnet200.tb_acao
                WHERE flaativo = 'S' AND idobjetivo in (:idobjetivo) 
                ORDER BY nomacao asc";
        return $this->_db->fetchPairs($sql, array(
            'idobjetivo' => $params['idobjetivo']
        ));
    }


}

