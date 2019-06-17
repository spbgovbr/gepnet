<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Perfilmodulo extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Perfilmodulo
     */
    public function insert(Default_Model_Perfilmodulo $model)
    {
        $data = array(
            "idperfil" => $model->idperfil,
            "idmodulo" => $model->idmodulo,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Perfilmodulo
     */
    public function update(Default_Model_Perfilmodulo $model)
    {
        $data = array(
            "idperfil" => $model->idperfil,
            "idmodulo" => $model->idmodulo,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Perfilmodulo);
    }

}

