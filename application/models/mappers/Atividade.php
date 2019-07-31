<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Atividade extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Atividade
     */
    public function insert(Default_Model_Atividade $model)
    {
        $data = array(
            "idatividade" => $model->idatividade,
            "nomatividade" => $model->nomatividade,
            "desatividade" => $model->desatividade,
            "idcadastrador" => $model->idcadastrador,
            "idresponsavel" => $model->idresponsavel,
            "datcadastro" => $model->datcadastro,
            "datatualizacao" => $model->datatualizacao,
            "datinicio" => $model->datinicio,
            "datfimmeta" => $model->datfimmeta,
            "datfimreal" => $model->datfimreal,
            "flacontinua" => $model->flacontinua,
            "numpercentualconcluido" => $model->numpercentualconcluido,
            "flacancelada" => $model->flacancelada,
            "idescritorio" => $model->idescritorio,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Atividade
     */
    public function update(Default_Model_Atividade $model)
    {
        $data = array(
            "idatividade" => $model->idatividade,
            "nomatividade" => $model->nomatividade,
            "desatividade" => $model->desatividade,
            "idcadastrador" => $model->idcadastrador,
            "idresponsavel" => $model->idresponsavel,
            "datcadastro" => $model->datcadastro,
            "datatualizacao" => $model->datatualizacao,
            "datinicio" => $model->datinicio,
            "datfimmeta" => $model->datfimmeta,
            "datfimreal" => $model->datfimreal,
            "flacontinua" => $model->flacontinua,
            "numpercentualconcluido" => $model->numpercentualconcluido,
            "flacancelada" => $model->flacancelada,
            "idescritorio" => $model->idescritorio,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Atividade);
    }

}

