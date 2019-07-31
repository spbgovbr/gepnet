<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Frase extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Frase
     */
    public function insert(Default_Model_Frase $model)
    {
        $data = array(
            "idfrase" => $model->idfrase,
            "idfrasepai" => $model->idfrasepai,
            "desfrase" => $model->desfrase,
            "numordem" => $model->numordem,
            "domtipofrase" => $model->domtipofrase,
            "flaativo" => $model->flaativo,
            "datcadastro" => $model->datcadastro,
            "idescritorio" => $model->idescritorio,
            "idcadastrador" => $model->idcadastrador,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Frase
     */
    public function update(Default_Model_Frase $model)
    {
        $data = array(
            "idfrase" => $model->idfrase,
            "idfrasepai" => $model->idfrasepai,
            "desfrase" => $model->desfrase,
            "numordem" => $model->numordem,
            "domtipofrase" => $model->domtipofrase,
            "flaativo" => $model->flaativo,
            "datcadastro" => $model->datcadastro,
            "idescritorio" => $model->idescritorio,
            "idcadastrador" => $model->idcadastrador,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Frase);
    }

}

