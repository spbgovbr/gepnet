<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Pessoa extends App_Model_Mapper_MapperAbstract
{

    protected $_dependencies = array('log');

    /**
     *
     * @var Zend_Log 
     */
    protected $_log;

    /**
     *
     * @var Default_Model_Mapper_Unidade
     */
    protected $_mapper;

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Pessoa
     */
    public function insert(Default_Model_Pessoa $model)
    {
        try {
            $model->idpessoa = $this->maxVal('idpessoa');
            //Zend_Debug::dump($model->idpessoa); exit;

            $data = array(
                "idpessoa"      => $model->idpessoa,
                "nompessoa"     => $model->nompessoa,
                "numcpf"        => $model->numcpf,
                "desobs"        => $model->desobs,
                "numfone"       => $model->numfone,
                "numcelular"    => $model->numcelular,
                "token"         => $model->getToken(),
                "desemail"      => $model->desemail,
                "domcargo"      => $model->domcargo,
                "idcadastrador" => $model->idcadastrador,
                "datcadastro"   => new Zend_Db_Expr("now()"),
                "lotacao"       => $model->lotacao,
                "id_servidor"   => $model->id_servidor,
                "nummatricula"  => $model->nummatricula,
                "desfuncao"     => $model->desfuncao,
                "flaagenda"     => $model->flaagenda,
            );

            if ( $model->isColaborador() ) {
                unset($data['id_servidor']);
            }
            //$data = array_filter($data);
            $retorno = $this->getDbTable()->insert($data);
            return $retorno;
        } catch ( Exception $exc ) {
            $this->_log->err($exc);
            throw $exc;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Pessoa
     */
    public function update(Default_Model_Pessoa $model)
    {
        $data = array(
            /*
            "nompessoa"    => $model->nompessoa,
            "numcpf"       => $model->numcpf,
            "nummatricula" => $model->nummatricula,
            "domcargo"     => $model->domcargo,
            */
            "numfone"      => $model->numfone,
            "numcelular"   => $model->numcelular,
            "desemail"     => $model->desemail,
            "token"        => $model->getToken(),
            "lotacao"      => $model->lotacao,
            "desobs"       => $model->desobs,
            "desfuncao"    => $model->desfuncao,
            "flaagenda"    => $model->flaagenda,
        );

        //Zend_Debug::dump($model); exit;

        try {
            $pks     = array(
                "idpessoa" => $model->idpessoa,
            );
            $where   = $this->_generateRestrictionsFromPrimaryKeys($pks);
            //$where = $this->_db->quoteInto('idpessoa = ?', $model->idpessoa);
            $retorno = $this->getDbTable()->update($data, $where);
            return $retorno;
        } catch ( Exception $exc ) {
            throw $exc;
        }
    }

    /**
     * 
     * @param array $params
     * @return int numero de linhas apagadas
     * @throws Exception
     */
    public function delete($params)
    {
        try {
            $pks     = array(
                "idpessoa" => $params['idpessoa'],
            );
            $where   = $this->_generateRestrictionsFromPrimaryKeys($pks);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch ( Exception $exc ) {
            throw $exc;
        }
    }

    /**
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        $sql = "SELECT 
                    idpessoa, nompessoa, numcpf, desobs, numfone, numcelular, desemail, 
                    domcargo, idcadastrador, to_char(datcadastro,'DD/MM/YYYY') as datcadastro, lotacao, 
                    id_servidor, nummatricula, desfuncao, flaagenda
                FROM agepnet200.tb_pessoa pes
                WHERE 
                    --uni.id_unidade = pes.id_unidade
                     pes.idpessoa = :idpessoa";

        $resultado       = $this->_db->fetchRow($sql, array('idpessoa' => $params['idpessoa']));
        $pessoa          = new Default_Model_Pessoa($resultado);
        $unidade         = new Default_Model_Unidade($resultado);
        $pessoa->unidade = $unidade;
        return $pessoa;
    }

    /**
     * @param array $params
     * @return array
     */
    public function retornaPessoaProjeto($params)
    {
        $sql = "SELECT
                    idpessoa, nompessoa, numcpf, numfone, numcelular, desemail,
                    domcargo, pes.lotacao as id_unidade,
                    id_servidor, nummatricula, desfuncao, flaagenda
                FROM agepnet200.tb_pessoa pes
                WHERE
                    pes.idpessoa = :idpessoa";

        $resultado = $this->_db->fetchRow($sql, array('idpessoa' => $params['idpessoa']));
        if(count($resultado) <= 0){
            return false;
        }
        return new Default_Model_Pessoa($resultado);
    }

    /**
     * @param array $params
     * @return array
     */
    public function retornaPorId($params)
    {
        $sql = "SELECT 
                    idpessoa, pes.nompessoa, pes.numcpf, pes.desobs, pes.numfone, pes.numcelular, pes.desemail, 
                    domcargo, pes.idcadastrador, to_char(pes.datcadastro,'DD/MM/YYYY') as datcadastro ,
                    pes.nummatricula, pes.desfuncao, pes.flaagenda
                FROM 
                    agepnet200.tb_pessoa pes 
                    --vw_rh_cargo cgo,
                    --vw_rh_servidor srv
                WHERE
                    --id_pessoa = srv.id_pessoa
                    --and cgo.id = srv.cd_cargo
                    --and cgo.inativo = false
                    --and pes.tipo = 'F'
                    idpessoa = :idpessoa";

        $resultado       = $this->_db->fetchRow($sql, array('idpessoa' => $params['idpessoa']));
        $pessoa          = new Default_Model_Pessoa($resultado);
        return $pessoa;
    }

    /**
     *
     * @param array $params
     * @return \Default_Model_Pessoa
     */
    public function getByCpf($params)
    {
        $sql = "SELECT
                    idpessoa, nompessoa, numcpf, desobs, numfone, numcelular, desemail,
                    domcargo, idcadastrador, to_char(datcadastro,'DD/MM/YYYY') as datcadastro, lotacao,
                    id_servidor, nummatricula, desfuncao, flaagenda
                FROM agepnet200.tb_pessoa pes
                WHERE
                   -- uni.id_unidade = pes.id_unidade
                     pes.numcpf = :numcpf";

        $resultado = $this->_db->fetchRow($sql, array('numcpf' => $params));
        
        if ( count($resultado) < 1 ) {
            return false;
        }
        
        $pessoa          = new Default_Model_Pessoa($resultado);
        $unidade         = new Default_Model_Unidade($resultado);
        $pessoa->unidade = $unidade;
        
        return $pessoa;
    }
    public function getByEmail($params)
    {
        $sql = "SELECT
                    idpessoa, nompessoa, numcpf, desobs, numfone, numcelular, desemail,
                    domcargo, idcadastrador, to_char(datcadastro,'DD/MM/YYYY') as datcadastro,lotacao,
                    id_servidor, nummatricula, desfuncao, flaagenda
                FROM agepnet200.tb_pessoa pes
                WHERE
                    --uni.id_unidade = pes.id_unidade
                     pes.desemail = :desemail";

        $resultado = $this->_db->fetchRow($sql, array('desemail' => $params));
        
        if ( count($resultado) < 1 ) {
            return false;
        }
        
        $pessoa          = new Default_Model_Pessoa($resultado);
        $unidade         = new Default_Model_Unidade($resultado);
        $pessoa->unidade = $unidade;
        
        return $pessoa;
    }

    /**
     * 
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisar($params, $paginator = false)
    {
        // 'Carbo','Nome','Matrícula','CPF','Lotação','Operações'

        $sql    = "SELECT domcargo, nompessoa, nummatricula, numcpf, lotacao, idpessoa 
                FROM agepnet200.tb_pessoa pes
                WHERE 1=1";
        $params = array_filter($params);
        if ( isset($params['nompessoa']) ) {
            $nompessoa = strtoupper($params['nompessoa']);
            $sql .= " AND upper(nompessoa) LIKE '%{$nompessoa}%'";
        }

        if ( isset($params['numcpf']) ) {
            $sql .= " AND numcpf =  '{$params['numcpf']}'";
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
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function buscarServidor($params, $paginator = false)
    {
        /*
        $sql    = "SELECT cpes.id_pessoa as id, cpes.nome as text
                   FROM 
                        vw_comum_pessoa cpes,
                        vw_rh_servidor srv
                   where 
                        cpes.tipo = 'F'
                        and cpes.id_pessoa = srv.id_pessoa
                        
                        and cpes.id_pessoa NOT IN (
                            SELECT pes.idpessoa
                            FROM agepnet200.tb_pessoa pes
                            WHERE pes.id_servidor = srv.id_servidor
                        )";
        */
        $sql    = "SELECT cpes.id_pessoa as id, cpes.nome as nome
                   FROM
                       agepnet200.tb_pessoa cpes
                   where 1 = 1
                        --cpes.tipo = 'F'
                        --and cpes.id_pessoa = srv.id_pessoa
                        /*
                        and cpes.id_pessoa NOT IN (
                            SELECT pes.idpessoa
                            FROM agepnet200.tb_pessoa pes
                            WHERE pes.id_servidor = srv.id_servidor
                        )*/";
        $params = array_filter($params);
        if ( isset($params['nome']) ) {
            $nompessoa = strtoupper($params['nome']);
            $sql .= " AND upper(cpes.nome) LIKE '%{$nompessoa}%'";
        }

        if ( $paginator ) {
            $page      = (isset($params['page'])) ? $params['page'] : 1;
            $limit     = (isset($params['page_limit'])) ? $params['page_limit'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchAll($sql);
        return $resultado;
    }

    /**
     * Retorna um registro da view pessoa do owner comum
     * @param array $params
     * @return Default_Model_Pessoa
     */
    public function getServidorById($params)
    {
        $sql = "SELECT 
                      * 
                    FROM 
                       agepnet200.tb_pessoa
                    WHERE 
                         
                         pes.id_pessoa = :idpessoa";

        $row = $this->_db->fetchRow($sql, array('idpessoa' => $params['id']));
        return new Default_Model_Pessoa($row);
    }

    public function fetchPairs()
    {
        $sql = " SELECT idpessoa, nompessoa FROM agepnet200.tb_pessoa order by nompessoa asc";
        return $this->_db->fetchPairs($sql);
    }

    /**
     * Recebe um array com o indice idprojeto e retorna um array de pessoas
     * @param array $params
     * @return array
     */
    public function fetchPairsPorProjeto($params)
    {
        $sql = "select
                    idpessoa, nompessoa
                from agepnet200.tb_pessoa
                where idpessoa in (
                    select idpessoa
                    from agepnet200.tb_projetopessoa
                    where idprojeto = :idprojeto)
                order by nompessoa";
        
        return $this->_db->fetchPairs($sql, array('idprojeto' => $params['idprojeto']));
    }

    /**
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function buscarColaborador($params, $paginator = false)
    {
        /* @var $db Zend_Db_Adapter_Abstract */
        $db     = $this->getBootstrap()->getPluginResource('multidb')->getDb('dpf_oracle');
        /*
        $sql    = "SELECT CD_PESSOA AS ID, NO_PESSOA AS TEXT
                FROM TB_PESSOA PES
                WHERE
                PES.TP_PESSOA = 2
                AND PES.ST_PESSOA = 'A'";
        */
        $sql    = "SELECT CD_PESSOA id, NO_PESSOA nome
                FROM TB_PESSOA PES
                WHERE
                PES.TP_PESSOA = 2
                AND PES.ST_PESSOA = 'A'";
        $params = array_filter($params);
        if ( isset($params['nome']) ) {
            $nompessoa = strtoupper($params['nome']);
            $sql .= " AND upper(PES.NO_PESSOA) LIKE '%{$nompessoa}%'";
        }
        //Zend_Debug::dump($sql); exit;

        if ( $paginator ) {
            $page             = (isset($params['page'])) ? $params['page'] : 1;
            $limit            = (isset($params['page_limit'])) ? $params['page_limit'] : 20;
            $paginatorAdapter = new App_Paginator_Adapter_Sql_Oracle($sql);
            $paginatorAdapter->setDb($db);
            $paginator        = new Zend_Paginator($paginatorAdapter);
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $db->fetchAll($sql);
        return $resultado;
    }

    /**
     * Retorna um registro da view pessoa do owner comum
     * @param array $params
     * @return Default_Model_Pessoa
     */
    public function getColaboradorById($params)
    {
        /* @var $db Zend_Db_Adapter_Abstract */
        $db  = $this->getBootstrap()->getPluginResource('multidb')->getDb('dpf_oracle');
        $sql = "select
                    cd_matricula as nummatricula,
                    lot_cd_lotacao as id_unidade,
                    no_pessoa as nompessoa, nr_cpf as numcpf,
                    ds_e_mail as desemail
                  from tb_pessoa pes
                  where
                     tp_pessoa = 2
                     and st_pessoa = 'A'
                     and cd_pessoa = :idpessoa";

        $row       = $db->fetchRow($sql, array('idpessoa' => $params['id']));
        //Zend_Debug::dump($row); exit;
        $resultado = array_change_key_case($row, CASE_LOWER);
        return new Default_Model_Pessoa($resultado);
    }


    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function pesquisarSemUnidade($params, $paginator = false)
    {
        $sql    = "SELECT nompessoa, idpessoa
                FROM agepnet200.tb_pessoa pes
                WHERE 1 = 1";

        if(isset($params['agenda'])){
            $sql .= " and flaagenda = 'S' ";
        }
        $params = array_filter($params);
        if ( isset($params['gridpessoa']) ) {
            $nompessoa = strtoupper($params['gridpessoa']);
            $sql .= " AND upper(nompessoa) LIKE '%{$nompessoa}%'";
        }

        $sql .= " ORDER BY pes.nompessoa ";

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

}

