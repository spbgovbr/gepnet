<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Planejamento_Model_Mapper_Objetivo extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Planejamento_Model_Objetivo
     */
    public function insert(Planejamento_Model_Objetivo $model)
    {
        $model->idobjetivo = $this->maxVal('idobjetivo');
        $model->numseq = $this->maxVal('numseq');

//        $model->idcadastrador   = '30605';
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $model->idcadastrador = $auth->getIdentity()->idpessoa;
        }

        $data = array(
            "idobjetivo" => $model->idobjetivo,
            // Id foi comentado pq nao consta na tabela tb_objetivo
            //"idprojeto"    => $model->idprojeto,
            //////////////////////////////////////
            "nomobjetivo" => $model->nomobjetivo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr("now()"),
            "flaativo" => $model->flaativo,
            "desobjetivo" => $model->desobjetivo,
            "codescritorio" => $model->codescritorio,
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
     * @return Planejamento_Model_Objetivo
     */
    public function update(Planejamento_Model_Objetivo $model)
    {
        $data = array(
//            "idobjetivo"    => $model->idobjetivo,
            "nomobjetivo" => $model->nomobjetivo,
//            "idcadastrador" => $model->idcadastrador,
//            "datcadastro"   => $model->datcadastro,
            "flaativo" => $model->flaativo,
            "desobjetivo" => $model->desobjetivo,
            "codescritorio" => $model->codescritorio,
//            "numseq"        => $model->numseq,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));

        try {
            $pks = array(
                "idobjetivo" => $model->idobjetivo,
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
        return $this->_getForm(Planejamento_Form_Objetivo);
    }

    public function fetchPairs()
    {
        $sql = " SELECT idobjetivo, nomobjetivo FROM agepnet200.tb_objetivo order by nomobjetivo asc";
        return $this->_db->fetchPairs($sql);
    }

    public function fetchFlaativo()
    {
        $retorno = array(
            '' => 'Selecione',
            'S' => 'Sim',
            'N' => 'Não'
        );
        return $retorno;
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
                            obj.nomobjetivo,
                            (SELECT p.nompessoa FROM agepnet200.tb_pessoa p WHERE p.idpessoa = obj.idcadastrador) as idcadastrador,
                            to_char(obj.datcadastro, 'DD/MM/YYYY') as datcadastro,
                            CASE obj.flaativo
                                WHEN 'S' THEN 'SIM'
                                WHEN 'N' THEN 'Não'
                            END as flaativo,
                            (SELECT e.nomescritorio FROM agepnet200.tb_escritorio e WHERE e.idescritorio = obj.codescritorio) as codescritorio,
                            obj.idobjetivo
                            --obj.desobjetivo,
                            --obj.numseq
                    FROM
                            agepnet200.tb_objetivo obj
                    WHERE
                            1 = 1
    		";

        $params = array_filter($params);
        if (isset($params['nomobjetivo'])) {
            $nomobjetivo = strtoupper($params['nomobjetivo']);
            $sql .= " AND upper(nomobjetivo) LIKE '%{$nomobjetivo}%'";
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
                            obj.idobjetivo,
                            obj.nomobjetivo,
                            obj.idcadastrador,
                            obj.datcadastro,
                            obj.flaativo,desobjetivo,
                            obj.codescritorio,
                            obj.numseq
                    FROM
                            agepnet200.tb_objetivo obj
                    WHERE
                            obj.idobjetivo = :idobjetivo
            ";

        $resultado = $this->_db->fetchRow($sql, array('idobjetivo' => $params['idobjetivo']));
        $processo = new Planejamento_Model_Objetivo($resultado);
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
                            obj.idobjetivo,
                            obj.nomobjetivo,
                            (SELECT p.nompessoa FROM agepnet200.tb_pessoa p WHERE p.idpessoa = obj.idcadastrador) as idcadastrador,
                            to_char(obj.datcadastro, 'DD/MM/YYYY') as datcadastro,
                            CASE obj.flaativo
                                WHEN 'S' THEN 'SIM'
                                WHEN 'N' THEN 'Não'
                            END as flaativo,
                            obj.desobjetivo,
                            (SELECT e.nomescritorio FROM agepnet200.tb_escritorio e WHERE e.idescritorio = obj.codescritorio) as codescritorio,
                            obj.numseq
                    FROM
                            agepnet200.tb_objetivo obj
                    WHERE
                            obj.idobjetivo = :idobjetivo
            ";

        $resultado = $this->_db->fetchRow($sql, array('idobjetivo' => $params['idobjetivo']));
        $processo = new Planejamento_Model_Objetivo($resultado);
        return $processo;
    }

    public function getTodosObjetivosEAcoes($params)
    {

        $sql = " SELECT 
                        o.idobjetivo,
                        o.nomobjetivo,
                        o.desobjetivo
                  FROM
                       agepnet200.tb_objetivo o
                  WHERE
                       o.flaativo = 'S'
                  ORDER BY o.idobjetivo
                ";

        $resultado = $this->_db->fetchAll($sql);

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Planejamento_Model_Objetivo');
        $mapperAcao = new Planejamento_Model_Mapper_Acao();

        foreach ($resultado as $r) {
            $o = new Planejamento_Model_Objetivo($r);
            $o->acoes = new App_Model_Relation(
                $mapperAcao, 'getByObjetivo', array(
                    array(
                        'idobjetivo' => $o->idobjetivo,
                    ),
                    true
                )
            );
            $o->acoes->getIterator();

            //echo "<pre>"; var_dump($o->acoes->getIterator()); exit;
            $collection[] = $o;
        }//exit;
        //echo "<pre>"; var_dump($collection); die;
        return $collection;
    }

    // Listando os registros da tabela projeto com o idobjetivo
    public function getListaProjetoIdObjetivo($idobjetivoProjeto, $codescritorio)
    {

        $sql = 'SELECT idprojeto, idescritorio FROM agepnet200.tb_projeto'
            . ' WHERE idobjetivo = ' . $idobjetivoProjeto .
            'and idescritorio = ' . $codescritorio . 'and flaativo = ' . 'S';
        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }
}

