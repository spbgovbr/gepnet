<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Entidadeexterna extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Entidadeexterna
     */
    public function insert(Default_Model_Entidadeexterna $model)
    {
        $data = array(
            "identidadeexterna" => $model->identidadeexterna,
            "nomentidadeexterna" => $model->nomentidadeexterna,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Entidadeexterna
     */
    public function update(Default_Model_Entidadeexterna $model)
    {
        $data = array(
            "identidadeexterna" => $model->identidadeexterna,
            "nomentidadeexterna" => $model->nomentidadeexterna,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );

    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Entidadeexterna);
    }

}

