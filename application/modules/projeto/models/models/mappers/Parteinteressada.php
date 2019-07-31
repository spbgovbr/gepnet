<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Parteinteressada extends App_Model_Mapper_MapperAbstract {

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Parteinteressada
     */
    public function insert(Projeto_Model_Parteinteressada $model) {
//        Zend_Debug::dump('Mapper');
//        Zend_Debug::dump($model); exit;
        $model->idparteinteressada = $this->maxVal('idparteinteressada');
        //$model->idparteinteressada = 30605;
        //$model->idcadastrador = 30605;
//        if (empty($mapper->idpessoa) == false) {
//            $model->nomparteinteressada = $mapper->idpessoa;
//        }

        $data = array(
            "idparteinteressada" => $model->idparteinteressada,
            "idprojeto" => $model->idprojeto,
            "nomparteinteressada" => $model->nomparteinteressada,
            "nomfuncao" => $model->nomfuncao,
            "destelefone" => $model->destelefone,
            "desemail" => $model->desemail,
            "domnivelinfluencia" => $model->domnivelinfluencia,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => new Zend_Db_Expr('now()'),
            "idpessoainterna" => $model->idpessoainterna,
            "observacao" => $model->observacao
        );
        try {
            return $this->getDbTable()->insert($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Parteinteressada
     */
    public function update(Projeto_Model_Parteinteressada $model) {
        $data = array(
            "idparteinteressada" => $model->idparteinteressada,
            "idprojeto" => $model->idprojeto,
            "nomparteinteressada" => $model->nomparteinteressada,
            "nomfuncao" => $model->nomfuncao,
            "destelefone" => $model->destelefone,
            "desemail" => $model->desemail,
            "domnivelinfluencia" => $model->domnivelinfluencia,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "idpessoainterna" => $model->idpessoainterna,
            "observacao" => $model->observacao
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }
    
    public function updateParte($data) {     
        try {            
            return $this->_db->update('agepnet200.tb_parteinteressada', $data, array('idparteinteressada =?'=> $data['idparteinteressada']));
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }        
    }

    /**
     * @param array $params
     * @return array
     */
    public function getById($params) {
        $sql = "
                        SELECT
                                idparteinteressada,
                                idprojeto,
                                nomparteinteressada,
                                nomfuncao,
                                destelefone,
                                desemail,
                                domnivelinfluencia,
                                idpessoainterna,
                                observacao
                        FROM
                                agepnet200.tb_parteinteressada
                        WHERE 
                                idprojeto = :idprojeto";

        $resultado = $this->_db->fetchAll($sql, array('idparteinteressada' => $params['idparteinteressada']));

//        $projeto = new Projeto_Model_Gerencia($resultado);

        return $resultado;
    }

    /**
     * @param array $params
     * @return array
     */
    public function getByProjeto($params) {

//        Zend_Debug::dump($params);exit;
//        if(is_numeric($params)){
//            $params = array('idprojeto' => $params);
//        }

        $sql = "
                        SELECT
                                idparteinteressada,
                                idprojeto,
                                nomparteinteressada,
                                nomfuncao,
                                destelefone,
                                desemail,
                                domnivelinfluencia,
                                idpessoainterna,
                                observacao
                        FROM
                                agepnet200.tb_parteinteressada
                        WHERE 
                                idprojeto = :idprojeto
                        ORDER BY idparteinteressada DESC";

        $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));

//      $projeto = new Projeto_Model_Gerencia($resultado);
        $collection = new App_Model_Collection();
        $collection->setDomainClass('Projeto_Model_Parteinteressada');

//        Zend_Debug::dump($resultado); exit;

        foreach ($resultado as $r) {
            $parte = new Projeto_Model_Parteinteressada($r);
            $collection[] = $parte;
        }

        return $collection;
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return 
     */
    public function excluir($params) {
        try {
            $pks = array(
                "idparteinteressada" => $params['id']
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            //$where = $this->_db->quoteInto('idpessoa = ?', $model->idpessoa);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;            
        }
    }

    /**
     * @param array $params
     * @return array
     */
    public function fetchPairsPorProjeto($params) {
        $sql = "SELECT
                    idparteinteressada,
                    nomparteinteressada
                FROM
                    agepnet200.tb_parteinteressada
                WHERE
                    idprojeto = :idprojeto
                ORDER BY nomparteinteressada";

        return $this->_db->fetchPairs($sql, array('idprojeto' => $params['idprojeto']));
    }

    /**
     * Retorna os valores da parte interessada interna ou externa
     * @param array $params
     * @return array
     */
    public function getParteInteressada($params) {
        $sql = "
                        SELECT
                                idparteinteressada,
                                idprojeto,
                                nomparteinteressada,
                                nomfuncao,
                                destelefone,
                                desemail,
                                domnivelinfluencia,
                                idcadastrador,
                                datcadastro,
                                idpessoainterna,
                                observacao
                        FROM
                                agepnet200.tb_parteinteressada
                        WHERE 
                                idparteinteressada = :idparteinteressada";

        $resultado = $this->_db->fetchRow($sql, array('idparteinteressada' => $params['idparteinteressada']));

        return $resultado;
    }

    /**
     * Retorna os valores da parte interessada interna ou externa
     * @param array $params
     * @return array
     */
    public function retornaPorId($params, $model = false) {
//        print_r($params); exit;
        $sql = "SELECT 
                    pin.idparteinteressada,
                    pin.idprojeto,
                    pin.nomparteinteressada,
                    pin.nomfuncao,
                    pin.destelefone,
                    pin.desemail,
                    pin.domnivelinfluencia,
                    pin.idcadastrador,
                    to_char(pin.datcadastro,'DD/MM/YYYY') AS datcadastro,
                    pin.idpessoainterna, 
                    pin.observacao 
                  FROM 
                    agepnet200.tb_parteinteressada pin, 
                    agepnet200.tb_pessoa pes
                  WHERE 
                    pin.idpessoainterna = pes.idpessoa
                    and pin.idpessoainterna is not null
                    and pin.idparteinteressada = :idparteinteressada
                  UNION
                  SELECT 
                    pin.idparteinteressada,
                    pin.idprojeto,
                    pin.nomparteinteressada,
                    pin.nomfuncao,
                    pin.destelefone,
                    pin.desemail,
                    pin.domnivelinfluencia,
                    pin.idcadastrador,
                    to_char(pin.datcadastro,'DD/MM/YYYY') AS datcadastro,
                    pin.idpessoainterna,
                    pin.observacao 
                  FROM 
                    agepnet200.tb_parteinteressada pin
                  WHERE 
                    pin.idpessoainterna is null
                    and pin.idparteinteressada = :idparteinteressada";

        $resultado = $this->_db->fetchRow($sql, array('idparteinteressada' => $params['idparteinteressada']));

        if ($model) {
            return new Projeto_Model_Parteinteressada($resultado);
        }

        return $resultado;
    }

    /**
     * Retorna os valores da parte interessada interna ou externa
     * @param array $params
     * @return array
     */
    public function retornaPartes($params, $model = false) {
        $sql = "SELECT 
                    pin.idparteinteressada,
                    pin.idprojeto,
                    pin.idpessoainterna,
                    pin.nomparteinteressada,
                    pin.nomfuncao,
                    pin.destelefone,
                    pin.desemail,
                    pin.domnivelinfluencia,
                    pin.idcadastrador,
                    to_char(pin.datcadastro,'DD/MM/YYYY') AS datcadastro,
                    pin.idpessoainterna,
                     pin.observacao 
                  FROM 
                    agepnet200.tb_parteinteressada pin, 
                    agepnet200.tb_pessoa pes
                  WHERE 
                    pin.idpessoainterna = pes.idpessoa
                    and pin.idpessoainterna is not null";

        if (isset($params['idprojeto']))
            $sql .= " and pin.idprojeto = :idprojeto";
        if (isset($params['idparteinteressada']))
            $sql .= " and pin.idparteinteressada = :idparteinteressada";

        $sql .="  UNION
                  SELECT 
                    pin.idparteinteressada,
                    pin.idprojeto,
                    pin.idpessoainterna,
                    pin.nomparteinteressada,
                    pin.nomfuncao,
                    pin.destelefone,
                    pin.desemail,
                    pin.domnivelinfluencia,
                    pin.idcadastrador,
                    to_char(pin.datcadastro,'DD/MM/YYYY') AS datcadastro,
                    pin.idpessoainterna,
                    pin.observacao 
                  FROM 
                    agepnet200.tb_parteinteressada pin
                  WHERE 
                    pin.idpessoainterna is null";
        if (isset($params['idprojeto']))
            $sql .= " and pin.idprojeto = :idprojeto";
        if (isset($params['idparteinteressada']))
            $sql .= " and pin.idparteinteressada = :idparteinteressada";

//        Zend_Debug::dump($params['idprojeto']); exit;

        if (isset($params['idprojeto']))
            $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));
        if (isset($params['idparteinteressada']))
            $resultado = $this->_db->fetchAll($sql, array('idparteinteressada' => $params['idparteinteressada']));

//        Zend_Debug::dump($resultado);

        if ($model) {
            $collection = new App_Model_Collection();
            $collection->setDomainClass('Projeto_Model_Parteinteressada');

            foreach ($resultado as $r) {
                $status = new Projeto_Model_Parteinteressada($r);
                $collection[] = $status;
            }

            return $collection;
            
        }

        return $resultado;
    }
    
    public function retornaPartesGrid($params) 
    {
        $sqlCondition = "";
        if(isset($params['nomparteinteressadapesquisar']) && $params['nomparteinteressadapesquisar'] != '') {
                    $sqlCondition .= " AND UPPER( pin.nomparteinteressada) LIKE '%".strtoupper($params['nomparteinteressadapesquisar'])."%' ";
        } 
        if(isset($params['nomfuncaopesquisar']) && $params['nomfuncaopesquisar'] != '') {
                    $sqlCondition .= " AND UPPER( pin.nomfuncao) LIKE '%".strtoupper($params['nomfuncaopesquisar'])."%' ";
        } 
        if(isset($params['destelefonepesquisar']) && $params['destelefonepesquisar'] != '') {
                    $sqlCondition .= " AND pin.destelefone = '{$params['destelefonepesquisar']}'";
        } 
        if(isset($params['desemailpesquisar']) && $params['desemailpesquisar'] != '') {
                    $sqlCondition .= " AND pin.desemail = '{$params['desemailpesquisar']}'";
        } 
        if(isset($params['domnivelinfluenciapesquisar']) && $params['domnivelinfluenciapesquisar'] != '') {
                    $sqlCondition .= " AND pin.domnivelinfluencia =  '{$params['domnivelinfluenciapesquisar']}'";
        } 
        
        $sql = "SELECT 
                    pes.nompessoa as nomparteinteressada,
                    pin.nomfuncao,
                    pin.desemail,
                    pin.destelefone,
                    pin.domnivelinfluencia,
                    pin.idparteinteressada,
                    pin.idprojeto,
                    pin.idcadastrador,
                    to_char(pin.datcadastro,'DD/MM/YYYY') AS datcadastro,
                    pin.idpessoainterna,
                    pin.observacao 
                  FROM 
                    agepnet200.tb_parteinteressada pin, 
                    agepnet200.tb_pessoa pes
                  WHERE 
                    pin.idpessoainterna = pes.idpessoa
                    and pin.idpessoainterna is not null
                    and pin.idprojeto = ". (int) $params['idprojeto'].
                    $sqlCondition.
              " UNION
                  SELECT 
                    pin.nomparteinteressada,
                    pin.nomfuncao,
                    pin.desemail,
                    pin.destelefone,
                    pin.domnivelinfluencia,
                    pin.idparteinteressada,
                    pin.idprojeto,
                    pin.idcadastrador,
                    to_char(pin.datcadastro,'DD/MM/YYYY') AS datcadastro,
                    pin.idpessoainterna,
                    pin.observacao 
                  FROM 
                    agepnet200.tb_parteinteressada pin
                  WHERE 
                    pin.idpessoainterna is null
                    and pin.idprojeto = ".(int) $params['idprojeto']
                    .$sqlCondition
                ;               
        
        $sql .= ' order by ' . $params['sidx'] . ' ' . $params['sord'];
        
        $page      = (isset($params['page'])) ? $params['page'] : 1;
        $limit     = (isset($params['rows'])) ? $params['rows'] : 20;
        $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($page);
        return $paginator;
    }
    
    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Zend_Paginator | array
     */
    public function parteInteressadaGrid($params, $paginator = false)
    {
        $params = array_filter($params);
        $sql    = "select pint.idparteinteressada, pint.nomparteinteressada 
                    from agepnet200.tb_parteinteressada pint
                    where pint.idprojeto = ".(int)$params['idprojeto']
                ;
        if ( isset($params['nomparteinteressada']) ) {
            $strInteressado = strtoupper($params['nomparteinteressada']);
            $sql .= " AND upper(pint.nomparteinteressada) LIKE '%{$strInteressado}%' ";
        }
        
        $sql .= ' order by pint.nomparteinteressada ';
        
        if ( $paginator ) {
            $page      = (isset($params['page'])) ? $params['page'] : 1;
            $limit     = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }
        $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));
        return $resultado;
    }

}

