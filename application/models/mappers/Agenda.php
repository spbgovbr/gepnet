<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Agenda extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Agenda
     */
    public function insert(Default_Model_Agenda $model)
    {
        $data = array(
            "idagenda" => $model->idagenda,
            "desassunto" => $model->desassunto,
            "datagenda" => $model->datagenda,
            "desagenda" => $model->desagenda,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "hragendada" => $model->hragendada,
            "deslocal" => $model->deslocal,
            "flaenviaemail" => $model->flaenviaemail,
            "idescritorio" => $model->idescritorio,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Agenda
     */
    public function update(Default_Model_Agenda $model)
    {
        $data = array(
            "idagenda" => $model->idagenda,
            "desassunto" => $model->desassunto,
            "datagenda" => $model->datagenda,
            "desagenda" => $model->desagenda,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "hragendada" => $model->hragendada,
            "deslocal" => $model->deslocal,
            "flaenviaemail" => $model->flaenviaemail,
            "idescritorio" => $model->idescritorio,
        );

    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Agenda);
    }

}

