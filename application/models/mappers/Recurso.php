<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 28-06-2013
 * 10:07
 */
class Default_Model_Mapper_Recurso extends App_Model_Mapper_MapperAbstract
{

    protected $_dependencies = array('log');

    /**
     *
     * @var Zend_Log
     */
    protected $_log;

    /**
     *
     * @var Default_Model_Mapper_Permissao
     */
    private $_mapperPermissao = null;

    protected function _init()
    {
        $this->_mapperPermissao = new Default_Model_Mapper_Permissao();
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Recurso
     */
    public function insert(Default_Model_Recurso $model)
    {
        try {
            $model->idrecurso = $this->maxVal('idrecurso');
            $data = array(
                "idrecurso" => $model->idrecurso,
                "ds_recurso" => $model->ds_recurso,
            );
            $idrecurso = $this->getDbTable()->insert($data);
            $permissions = $model->retornaPermissoes();
            //$i = 0;
            if ($permissions) {
                $mapperPermissao = new Default_Model_Mapper_Permissao();
                foreach ($permissions as $permissao) {
                    $permissao->idrecurso = $idrecurso;
                    try {
                        $retorno = $mapperPermissao->insert($permissao);
                    } catch (Exception $exc) {
                        $this->_log->log($exc, Zend_Log::ERR);
                        throw $exc;
                    }
                }
            }
            //Zend_Debug::dump($i); exit;

            return $idrecurso;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    /**
     *
     * @param array $params
     * @return Default_Model_Recurso
     */
    public function retornaPorDescricao($params)
    {
        $sql = "select
                    idrecurso,
                    ds_recurso
               from agepnet200.tb_recurso
               where ds_recurso like :ds_recurso || '%'
               limit 1";
        $row = $this->_db->fetchRow($sql, array(
            'ds_recurso' => $params['ds_recurso']
        ));

        if (false == $row) {
            return false;
        }

        $recurso = new Default_Model_Recurso();
        $recurso->idrecurso = $row['idrecurso'];
        $recurso->ds_recurso = substr($row['ds_recurso'], strpos(':', $row['ds_recurso']) - 1);
        $recurso->permissions = new App_Model_Relation(
            $this->_mapperPermissao, 'retornaPorRecurso', array('idrecurso' => $row['idrecurso'])
        );
        return $recurso;
    }

    /**
     *
     * @return array
     */
    public function retornaTodos()
    {
        $sql = "select
                    idrecurso,
                    ds_recurso
               from agepnet200.tb_recurso";
        $rows = $this->_db->fetchAll($sql);

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Default_Model_Recurso');

        foreach ($rows as $row) {
            $recurso = new Default_Model_Recurso($row);
            $recurso->permissions = new App_Model_Relation(
                $this->_mapperPermissao, 'retornaPorRecurso', array(array('idrecurso' => $row['idrecurso']))
            );
            $collection[] = $recurso;
        }

        return $collection;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisar($params, $paginator = false)
    {
        // 'Cargo','Nome','Matrícula','CPF','Lotação','Operações'

        $sql = "select
                    rec.ds_recurso,
                    per.no_permissao,
                    per.ds_permissao,
                    per.idpermissao
               from
                    agepnet200.tb_recurso rec,
                    agepnet200.tb_permissao per
               where
                    rec.idrecurso = per.idrecurso
                and  rec.ds_recurso <> 'default:error' ";
        $params = array_filter($params);
        if (isset($params['ds_recurso'])) {
            $ds_recurso = strtoupper($params['ds_recurso']);
            $sql .= " AND lower(rec.ds_recurso) LIKE '%{$ds_recurso}%'";
        }

        if (isset($params['no_pemissao'])) {
            $sql .= " AND lower.no_permissao =  {$params['no_permissao']}";
        }

        if (isset($params['ds_permissao'])) {
            $sql .= " AND lower(per.ds_permissao) LIKE  '%{$params['ds_permissao']}%'";
        }

        if (isset($params['idrecurso'])) {
            $sql .= " AND per.idrecurso =  {$params['idrecurso']}";
        }

        if (isset($params['idpermissao'])) {
            $sql .= " AND per.idpermissao =  {$params['idpermissao']}";
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
        //Zend_Debug::dump($id);exit;
        return $resultado;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisarPermissao($params, $paginator = false)
    {
        $sql = "select
                    rec.ds_recurso,
                    per.no_permissao,
                    per.ds_permissao,
                    1 as allow,
                    per.idpermissao
               from
                    agepnet200.tb_recurso rec,
                    agepnet200.tb_permissao per
               where
                    rec.idrecurso = per.idrecurso
                and  rec.ds_recurso <> 'default:error' ";
        $params = array_filter($params);
        if (isset($params['ds_recurso'])) {
            $ds_recurso = strtoupper($params['ds_recurso']);
            $sql .= " AND lower(rec.ds_recurso) LIKE '%{$ds_recurso}%'";
        }

        if (isset($params['no_pemissao'])) {
            $sql .= " AND lower.no_permissao =  {$params['no_permissao']}";
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
        //Zend_Debug::dump($id);exit;
        return $resultado;
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Recurso
     */
    public function update(Default_Model_Recurso $model)
    {
        $data = array(
            "idrecurso" => $model->idrecurso,
            "ds_recurso" => $model->ds_recurso,
        );
    }

    public function retornaPermissaoPorPerfil($params)
    {
        $sql = "select idpermissaoperfil, idpermissao
                from agepnet200.tb_permissaoperfil pp
                where pp.idperfil = :idperfil";

        return $this->_db->fetchAll($sql, array(
            'idperfil' => $params['idperfil']
        ));
    }

    public function fetchPairs()
    {
        $sql = "select
                    idrecurso,
                    ds_recurso
               from agepnet200.tb_recurso
               order by ds_recurso asc";
        return $this->_db->fetchPairs($sql);
    }

    public function getDescricao()
    {
        $sql = "select idrecurso,  descricao from agepnet200.tb_recurso
                where descricao is not null";
        return $this->_db->fetchAll($sql);
    }

}