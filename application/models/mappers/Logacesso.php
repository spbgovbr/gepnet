<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Logacesso extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Logacesso
     */
    public function insert(Default_Model_Logacesso $model)
    {
        $data = array(
            "idmodulo" => $model->idmodulo,
            "idperfilpessoa" => $model->idperfilpessoa,
            "datacesso" => $model->datacesso,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Logacesso
     */
    public function update(Default_Model_Logacesso $model)
    {
        $data = array(
            "idmodulo" => $model->idmodulo,
            "idperfilpessoa" => $model->idperfilpessoa,
            "datacesso" => $model->datacesso,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Logacesso);
    }

}

