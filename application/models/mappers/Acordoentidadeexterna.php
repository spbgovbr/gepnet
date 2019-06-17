<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Acordoentidadeexterna extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Acordoentidadeexterna
     */
    public function insert(Default_Model_Acordoentidadeexterna $model)
    {
        $data = array(
            "idacordo" => $model->idacordo,
            "identidadeexterna" => $model->identidadeexterna,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Acordoentidadeexterna
     */
    public function update(Default_Model_Acordoentidadeexterna $model)
    {
        $data = array(
            "idacordo" => $model->idacordo,
            "identidadeexterna" => $model->identidadeexterna,
        );

    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Acordoentidadeexterna);
    }

}

