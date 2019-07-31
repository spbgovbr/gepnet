<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Comunicacao extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Comunicacao
     */
    public function insert(Default_Model_Comunicacao $model)
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
            "datcadastro" => $model->datcadastro,
            "nomresponsavel" => $model->nomresponsavel,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Comunicacao
     */
    public function update(Default_Model_Comunicacao $model)
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
            "datcadastro" => $model->datcadastro,
            "nomresponsavel" => $model->nomresponsavel,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Comunicacao);
    }

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
        return new Default_Model_Comunicacao($resultado);
    }

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
        return new Default_Model_Comunicacao($resultado);
    }
}

