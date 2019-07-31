<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Modulo extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Modulo
     */
    public function insert(Default_Model_Modulo $model)
    {
        $data = array(
            "idmodulo" => $model->idmodulo,
            "idmodulopai" => $model->idmodulopai,
            "numsequencial" => $model->numsequencial,
            "nomitemmenu" => $model->nomitemmenu,
            "deslink" => $model->deslink,
            "flaativo" => $model->flaativo,
            "flaitemmenu" => $model->flaitemmenu,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Modulo
     */
    public function update(Default_Model_Modulo $model)
    {
        $data = array(
            "idmodulo" => $model->idmodulo,
            "idmodulopai" => $model->idmodulopai,
            "numsequencial" => $model->numsequencial,
            "nomitemmenu" => $model->nomitemmenu,
            "deslink" => $model->deslink,
            "flaativo" => $model->flaativo,
            "flaitemmenu" => $model->flaitemmenu,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Modulo);
    }

}

