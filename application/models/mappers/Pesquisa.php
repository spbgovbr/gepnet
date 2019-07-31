<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Pesquisa extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Pesquisa
     */
    public function insert(Default_Model_Pesquisa $model)
    {
        $data = array(
            "idpesquisa" => $model->idpesquisa,
            "idquestionario" => $model->idquestionario,
            "idfraserespondeu" => $model->idfraserespondeu,
            "desresposta" => $model->desresposta,
            "flaativo" => $model->flaativo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "datpesquisa" => $model->datpesquisa,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Pesquisa
     */
    public function update(Default_Model_Pesquisa $model)
    {
        $data = array(
            "idpesquisa" => $model->idpesquisa,
            "idquestionario" => $model->idquestionario,
            "idfraserespondeu" => $model->idfraserespondeu,
            "desresposta" => $model->desresposta,
            "flaativo" => $model->flaativo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "datpesquisa" => $model->datpesquisa,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Pesquisa);
    }

}

