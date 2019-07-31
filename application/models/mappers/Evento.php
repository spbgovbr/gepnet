<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Evento extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Evento
     */
    public function insert(Default_Model_Evento $model)
    {
        $data = array(
            "idevento" => $model->idevento,
            "nomevento" => $model->nomevento,
            "desevento" => $model->desevento,
            "desobs" => $model->desobs,
            "idcadastrador" => $model->idcadastrador,
            "idresponsavel" => $model->idresponsavel,
            "datcadastro" => $model->datcadastro,
            "datinicio" => $model->datinicio,
            "datfim" => $model->datfim,
            "uf" => $model->uf,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Evento
     */
    public function update(Default_Model_Evento $model)
    {
        $data = array(
            "idevento" => $model->idevento,
            "nomevento" => $model->nomevento,
            "desevento" => $model->desevento,
            "desobs" => $model->desobs,
            "idcadastrador" => $model->idcadastrador,
            "idresponsavel" => $model->idresponsavel,
            "datcadastro" => $model->datcadastro,
            "datinicio" => $model->datinicio,
            "datfim" => $model->datfim,
            "uf" => $model->uf,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Evento);
    }

}

