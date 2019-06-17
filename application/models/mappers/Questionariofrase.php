<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Questionariofrase extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Questionariofrase
     */
    public function insert(Default_Model_Questionariofrase $model)
    {
        $data = array(
            "idfrase" => $model->idfrase,
            "idquestionario" => $model->idquestionario,
            "numordempergunta" => $model->numordempergunta,
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
     * @return Default_Model_Questionariofrase
     */
    public function update(Default_Model_Questionariofrase $model)
    {
        $data = array(
            "idfrase" => $model->idfrase,
            "idquestionario" => $model->idquestionario,
            "numordempergunta" => $model->numordempergunta,
            "flaativo" => $model->flaativo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Questionariofrase);
    }

}

