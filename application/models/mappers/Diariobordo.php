<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Diariobordo extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Diariobordo
     */
    public function insert(Default_Model_Diariobordo $model)
    {
        $data = array(
            "iddiariobordo" => $model->iddiariobordo,
            "idprojeto" => $model->idprojeto,
            "datdiariobordo" => $model->datdiariobordo,
            "domreferencia" => $model->domreferencia,
            "domsemafaro" => $model->domsemafaro,
            "desdiariobordo" => $model->desdiariobordo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Diariobordo
     */
    public function update(Default_Model_Diariobordo $model)
    {
        $data = array(
            "iddiariobordo" => $model->iddiariobordo,
            "idprojeto" => $model->idprojeto,
            "datdiariobordo" => $model->datdiariobordo,
            "domreferencia" => $model->domreferencia,
            "domsemafaro" => $model->domsemafaro,
            "desdiariobordo" => $model->desdiariobordo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Diariobordo);
    }

}

