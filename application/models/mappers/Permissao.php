<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 28-06-2013
 * 10:07
 */
class Default_Model_Mapper_Permissao extends App_Model_Mapper_MapperAbstract
{

    protected $_dependencies = array('log');

    /**
     *
     * @var Zend_Log
     */
    protected $_log;

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Permissao
     */
    public function insert(Default_Model_Permissao $model)
    {
        try {
            $model->idpermissao = $this->maxVal('idpermissao');

            $data = array(
                "idpermissao"  => $model->idpermissao,
                "idrecurso"    => $model->idrecurso,
                "ds_permissao" => $model->ds_permissao,
                //"idperfil"     => $model->idperfil,
                "no_permissao"  => $model->no_permissao,
            );

            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
            //Zend_Debug::dump($retorno);
        } catch ( Exception $exc ) {
            throw $exc;
        }
    }

    /**
     *
     * @param array $params
     * @return array of Default_Model_Permissao
     */
    public function retornaPorRecurso($params)
    {
        $sql     = "select idpermissao, no_permissao as no_permissao, idrecurso, ds_permissao
                from agepnet200.tb_permissao
                where 1 = 1";

        if(isset($params['idrecurso'])){
            $sql .= " and idrecurso = {$params['idrecurso']}";
        }

        $retorno = $this->_db->fetchAll($sql);
        //Zend_Debug::dump($params); exit;
        //$this->_log->log('retornaPorRecurso: ' . $params['idrecurso'], Zend_Log::ALERT);

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Default_Model_Permissao');

        foreach ( $retorno as $r )
        {
            $permissao    = new Default_Model_Permissao($r);
            $collection[] = $permissao;
        }

        return $collection;
    }

    /**
     *
     * @param array $params
     * @return Default_Model_Permissao
     */
    public function retornaPorId($params)
    {
        return new Default_Model_Permissao($this->getById($params));
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "select per.idpermissao, per.no_permissao, per.idrecurso, per.ds_permissao, rec.ds_recurso
                from 
                    agepnet200.tb_permissao as per,
                    agepnet200.tb_recurso as rec
                where
                    per.idrecurso = rec.idrecurso
                    and per.idpermissao = :idpermissao";
        return $this->_db->fetchRow($sql, array(
                'idpermissao' => $params['idpermissao']
        ));
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Permissao
     */
    public function update(Default_Model_Permissao $model)
    {
        $data = array(
            //"idpermissao"  => $model->idpermissao,
            //"idrecurso"    => $model->idrecurso,
            //"idperfil"     => $model->idperfil,
            "ds_permissao" => $model->ds_permissao,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
        try {
            $pks   = array(
                "idpermissao" => $model->idpermissao,
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            //$where = $this->_db->quoteInto('idpessoa = ?', $model->idpessoa);
            return $this->getDbTable()->update($data, $where);
        } catch ( Exception $exc ) {
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
        $sql    = "select
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
        if ( isset($params['ds_recurso']) ) {
            $ds_recurso = strtoupper($params['ds_recurso']);
            $sql .= " AND lower(rec.ds_recurso) LIKE '%{$ds_recurso}%'";
        }

        if ( isset($params['no_pemissao']) ) {
            $sql .= " AND lower(per.no_permissao) LIKE  '%{$params['no_permissao']}%'";
        }

        if ( isset($params['ds_permissao']) ) {
            $sql .= " AND lower(per.ds_permissao) LIKE  '%{$params['ds_permissao']}%'";
        }

        if ( isset($params['idrecurso']) ) {
            $sql .= " AND per.idrecurso =  {$params['idrecurso']}";
        }

        if ( isset($params['idpermissao']) ) {
            $sql .= " AND per.idpermissao =  {$params['idpermissao']}";
        }

        if ( isset($params['sidx']) ) {
            $sql .= " order by " . $params['sidx'] . " " . $params['sord'];
        }

        if ( $paginator ) {
            $page      = (isset($params['page'])) ? $params['page'] : 1;
            $limit     = (isset($params['rows'])) ? $params['rows'] : 20;
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
     * @param int $perfil
     * @return array
     */
    public function retornaPorPerfil($perfil)
    {
        $sql = "select per.idpermissao, rec.idrecurso, per.no_permissao, rec.ds_recurso
                from
                    agepnet200.tb_permissaoperfil ppe,
                    agepnet200.tb_permissao per,
                    agepnet200.tb_recurso rec
                where
                    per.idpermissao = ppe.idpermissao
                    and rec.idrecurso = per.idrecurso
                    and ppe.idperfil = :perfil
                order by rec.ds_recurso asc, per.no_permissao asc";
        return $this->_db->fetchAll($sql,array('perfil' => $perfil));
    }

    /**
     *
     * @param array $params
     * @return array
     */
    public function fetchPairs()
    {
        $sql     = "select idpermissao, no_permissao
                from agepnet200.tb_permissao";
        return $this->_db->fetchPairs($sql);
    }
}