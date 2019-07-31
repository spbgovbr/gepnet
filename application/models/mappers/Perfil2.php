<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Perfil2 extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Perfil2
     */
    public function insert(Default_Model_Perfil2 $model)
    {
        $data = array(
            "idperfil" => $model->idperfil,
            "nomperfil" => $model->nomperfil,
            "flaativo" => $model->flaativo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Perfil2
     */
    public function update(Default_Model_Perfil2 $model)
    {
        $data = array(
            "idperfil" => $model->idperfil,
            "nomperfil" => $model->nomperfil,
            "flaativo" => $model->flaativo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Perfil2);
    }

}

