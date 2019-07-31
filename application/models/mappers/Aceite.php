<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Aceite extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Aceite
     */
    public function insert(Default_Model_Aceite $model)
    {
        $data = array(
            "idaceite" => $model->idaceite,
            "identrega" => $model->identrega,
            "idprojeto" => $model->idprojeto,
            "desprodutoservico" => $model->desprodutoservico,
            "desparecerfinal" => $model->desparecerfinal,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Aceite
     */
    public function update(Default_Model_Aceite $model)
    {
        $data = array(
            "idaceite" => $model->idaceite,
            "identrega" => $model->identrega,
            "idprojeto" => $model->idprojeto,
            "desprodutoservico" => $model->desprodutoservico,
            "desparecerfinal" => $model->desparecerfinal,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );

    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Aceite);
    }

    public function getById($params)
    {
        $sql = "SELECT
                    idaceite,
                    identrega,
                    idprojeto,
                    desprodutoservico,
                    desparecerfinal,
                    idcadastrador,
                    datcadastro
                FROM agepnet200.tb_aceite
                WHERE idaceite = :idaceite";
        $resultado = $this->_db->fetchRow($sql, array('idaceite' => $params['idaceite']));
        return new Default_Model_Aceite($resultado);
    }

    public function retornaPorProjeto($params)
    {
        $sql = "SELECT
                    idaceite,
                    identrega,
                    idprojeto,
                    desprodutoservico,
                    desparecerfinal,
                    idcadastrador,
                    datcadastro
                FROM agepnet200.tb_aceite
                WHERE idprojeto = :idprojeto";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return new Default_Model_Aceite($resultado);
    }
}

