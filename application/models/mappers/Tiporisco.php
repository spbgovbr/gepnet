<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Tiporisco extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Tiporisco
     */
    public function insert(Default_Model_Tiporisco $model)
    {
        $data = array(
            "idtiporisco" => $model->idtiporisco,
            "dstiporisco" => $model->dstiporisco,
            "idcadastrador" => $model->idcadastrador,
            "dtcadastro" => $model->dtcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Tiporisco
     */
    public function update(Default_Model_Tiporisco $model)
    {
        $data = array(
            "idtiporisco" => $model->idtiporisco,
            "dstiporisco" => $model->dstiporisco,
            "idcadastrador" => $model->idcadastrador,
            "dtcadastro" => $model->dtcadastro,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Tiporisco);
    }

}

