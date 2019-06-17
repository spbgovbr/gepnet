<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Projetopessoa extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Projetopessoa
     */
    public function insert(Default_Model_Projetopessoa $model)
    {
        $data = array(
            "idpessoa" => $model->idpessoa,
            "idprojeto" => $model->idprojeto,
            "desfuncao" => $model->desfuncao,
            "destelefone" => $model->destelefone,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Projetopessoa
     */
    public function update(Default_Model_Projetopessoa $model)
    {
        $data = array(
            "idpessoa" => $model->idpessoa,
            "idprojeto" => $model->idprojeto,
            "desfuncao" => $model->desfuncao,
            "destelefone" => $model->destelefone,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Projetopessoa);
    }

}

