<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Pessoaagenda extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Pessoaagenda
     */
    public function insert(Default_Model_Pessoaagenda $model)
    {
        $data = array(
            "idagenda" => $model->idagenda,
            "idpessoa" => $model->idpessoa,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Pessoaagenda
     */
    public function update(Default_Model_Pessoaagenda $model)
    {
        $data = array(
            "idagenda" => $model->idagenda,
            "idpessoa" => $model->idpessoa,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Pessoaagenda);
    }

}

