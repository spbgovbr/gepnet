<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Comunicacao extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Comunicacao
     */
    public function insert(Projeto_Model_Comunicacao $model)
    {
        $model->idcomunicacao = $this->maxVal("idcomunicacao");
        $data = array(
            "idcomunicacao" => $model->idcomunicacao,
            "idprojeto" => $model->idprojeto,
            "desinformacao" => $model->desinformacao,
            "desinformado" => $model->desinformado,
            "desorigem" => $model->desorigem,
            "desfrequencia" => $model->desfrequencia,
            "destransmissao" => $model->destransmissao,
            "desarmazenamento" => $model->desarmazenamento,
            "idresponsavel" => $model->idresponsavel,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "nomresponsavel" => $model->nomresponsavel,
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Comunicacao
     */
    public function update(Projeto_Model_Comunicacao $model)
    {
        $data = array(
            "idcomunicacao" => $model->idcomunicacao,
            "idprojeto" => $model->idprojeto,
            "desinformacao" => $model->desinformacao,
            "desinformado" => $model->desinformado,
            "desorigem" => $model->desorigem,
            "desfrequencia" => $model->desfrequencia,
            "destransmissao" => $model->destransmissao,
            "desarmazenamento" => $model->desarmazenamento,
            "idresponsavel" => $model->idresponsavel,
            "idcadastrador" => $model->idcadastrador,
            "nomresponsavel" => $model->nomresponsavel,
        );
        return $this->getDbTable()->update($data, array("idcomunicacao = ?" => $data['idcomunicacao']));
    }

    public function updateComunicacaoByProjetoResponsavel($params)
    {
        $data = array(
            "idresponsavel" => $params['idresponsavelNovo']
        );
        return $this->getDbTable()->update($data, array(
            'idcomunicacao = ?' => (int)$params['idcomunicacao'],
            'idprojeto = ?' => $params['idprojeto'],
            'idresponsavel = ?' => $params['idresponsavelAlterar']
        ));
    }

    /**
     * Recupera os dados da tb_comunicacao pelo idcomunicacao
     * @param array $params - parametros de busca
     */
    public function getById($params)
    {
        $sql = "SELECT
                    idcomunicacao,
                    idprojeto,
                    desinformacao,
                    desinformado,
                    desorigem,
                    desfrequencia,
                    destransmissao,
                    desarmazenamento,
                    idresponsavel,
                    idcadastrador,
                    datcadastro,
                    nomresponsavel
                FROM agepnet200.tb_comunicacao
                WHERE idcomunicacao = :idcomunicacao";
        $resultado = $this->_db->fetchRow($sql, array('idcomunicacao' => $params['idcomunicacao']));
        return new Projeto_Model_Comunicacao($resultado);
    }

    public function excluirComunicacaoByProjetoResponsavel($params)
    {
        $result = $this->_db->delete('agepnet200.tb_comunicacao', array(
            'idcomunicacao = ?' => (int)$params['idcomunicacao'],
            'idprojeto = ?' => $params['idprojeto'],
            'idresponsavel = ?' => $params['idresponsavel']
        ));
        return $result;
    }

    public function getByIdComunicacaoProjetoResponsavel($params)
    {
        $sql = "SELECT
                    idcomunicacao,
                    idprojeto,
                    desinformacao,
                    desinformado,
                    desorigem,
                    desfrequencia,
                    destransmissao,
                    desarmazenamento,
                    idresponsavel,
                    idcadastrador,
                    datcadastro,
                    nomresponsavel
                FROM agepnet200.tb_comunicacao
                WHERE idcomunicacao = :idcomunicacao and 
                idprojeto=:idprojeto and 
                idresponsavel = :idresponsavel";

        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idcomunicacao' => $params['idcomunicacao'],
            'idresponsavel' => $params['idparteinteressada']
        ));
        //Zend_Debug::dump($resultado);exit;
        return $resultado;
    }

    public function getByIdFromParteInteressada($params)
    {
        $sql = "SELECT
                    idcomunicacao,
                    idprojeto,                    
                    idresponsavel
                FROM agepnet200.tb_comunicacao
                WHERE idprojeto = :idprojeto and idresponsavel = :idresponsavel ";

        $resultado = $this->_db->fetchAll($sql,
            array('idprojeto' => $params['idprojeto'], 'idresponsavel' => $params['idparteinteressada']));
        return $resultado;
    }

    /**
     * Recupera os dados da tb_comunicacao pelo idprojeto
     * @param array $params - parametros de busca
     */
    public function retornaPorProjeto($params)
    {
        $sql = "SELECT
                    com.idcomunicacao,
                    com.idprojeto,
                    com.desinformacao,
                    com.desinformado,
                    com.desorigem,
                    com.desfrequencia,
                    com.destransmissao,
                    com.desarmazenamento,
                    com.idresponsavel,
                    com.idcadastrador,
                    com.datcadastro,
                    com.nomresponsavel
                FROM 
                    agepnet200.tb_comunicacao com
                WHERE idprojeto = :idprojeto";

        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return new Projeto_Model_Comunicacao($resultado);
    }

    public function retornaComunicacaoPorIdProjeto($params)
    {
        $sql = "SELECT
                    com.idcomunicacao,
                    com.idprojeto,
                    com.desinformacao,
                    com.desinformado,
                    com.desorigem,
                    com.desfrequencia,
                    com.destransmissao,
                    com.desarmazenamento,
                    com.idresponsavel,
                    com.idcadastrador,
                    com.datcadastro,
                    com.nomresponsavel
                FROM 
                    agepnet200.tb_comunicacao com
                WHERE idprojeto = :idprojeto";

        $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));
        return $resultado;
    }

    /**
     * Recupera os dados da tb_comunicacao pelo idprojeto para
     * @param array $params - parametros de busca
     */
    public function retornaPorProjetoToGrid($params, $paginator = false)
    {
        array_filter($params);
        $sql = "SELECT
                    com.datcadastro,
                    com.nomresponsavel,
                    com.desorigem,
                    com.desinformado,
                    com.desinformacao,
                    com.desfrequencia,
                    com.destransmissao,
                    com.desarmazenamento,
                    com.idcomunicacao,
                    com.idprojeto,
                    com.idresponsavel,
                    com.idcadastrador
                FROM 
                    agepnet200.tb_comunicacao com
                WHERE idprojeto =" . (int)$params['idprojetopesquisar'];
        if (isset($params['desinformacaopesquisar']) && $params['desinformacaopesquisar'] != '') {
            $sql .= " AND UPPER(com.desinformacao) LIKE '%" . strtoupper($params['desinformacaopesquisar']) . "%' "; //@todo bindar os parametros da consulta e reduzir uso ILIKE
        }
        if (isset($params['desinformadopesquisar']) && $params['desinformadopesquisar'] != '') {
            $sql .= " AND UPPER(com.desinformado) LIKE '%" . strtoupper($params['desinformadopesquisar']) . "%' ";
        }
        if (isset($params['desorigempesquisar']) && $params['desorigempesquisar'] != '') {
            $sql .= " AND UPPER(com.desorigem) LIKE '%" . strtoupper($params['desorigempesquisar']) . "%' ";
        }
        if (isset($params['desfrequenciapesquisar']) && $params['desfrequenciapesquisar'] != '') {
            $sql .= " AND UPPER(com.desfrequencia) LIKE '%" . strtoupper($params['desfrequenciapesquisar']) . "%' ";
        }
        if (isset($params['destransmissaopesquisar']) && $params['destransmissaopesquisar'] != '') {
            $sql .= " AND UPPER(com.destransmissao) LIKE '%" . strtoupper($params['destransmissaopesquisar']) . "%' ";
        }
        if (isset($params['desarmazenamentopesquisar']) && $params['desarmazenamentopesquisar'] != '') {
            $sql .= " AND UPPER(com.desarmazenamento) LIKE '%" . strtoupper($params['desarmazenamentopesquisar']) . "%' ";
        }
        if (isset($params['idresponsavelpesquisar']) && $params['idresponsavelpesquisar'] != '') {
            $sql .= " AND com.idresponsavel = " . (int)$params['idresponsavelpesquisar'];
        }
        $sql .= ' order by ' . $params['sidx'] . ' ' . $params['sord'];

        if ($paginator) {
            $page = (isset($params['page'])) ? $params['page'] : 1;
            $limit = (isset($params['rows'])) ? $params['rows'] : 20;
            $paginator = new Zend_Paginator(new App_Paginator_Adapter_Sql_Pgsql($sql));
            $paginator->setItemCountPerPage($limit);
            $paginator->setCurrentPageNumber($page);
            return $paginator;
        }

        $resultado = $this->_db->fetchRow($sql);
        return new Projeto_Model_Comunicacao($resultado);
    }

    /**
     * @return boolean
     */
    public function delete($params)
    {
        $result = $this->_db->delete('agepnet200.tb_comunicacao', array('idcomunicacao = ?' => (int)$params));
        return $result;
    }
}