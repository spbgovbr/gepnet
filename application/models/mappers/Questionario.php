<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Questionario extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Questionario
     */
    public function insert(Default_Model_Questionario $model)
    {
        $data = array(
            "idquestionario" => $model->idquestionario,
            "nomquestionario" => $model->nomquestionario,
            "desobservacao" => $model->desobservacao,
            "flaativo" => $model->flaativo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "codescritorio" => $model->codescritorio,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Questionario
     */
    public function update(Default_Model_Questionario $model)
    {
        $data = array(
            "idquestionario" => $model->idquestionario,
            "nomquestionario" => $model->nomquestionario,
            "desobservacao" => $model->desobservacao,
            "flaativo" => $model->flaativo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "codescritorio" => $model->codescritorio,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Questionario);
    }

}

