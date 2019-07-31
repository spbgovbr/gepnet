<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Manutencaogepnet extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Manutencaogepnet
     */
    public function insert(Default_Model_Manutencaogepnet $model)
    {
        $data = array(
            "idmanutencaogepnet" => $model->idmanutencaogepnet,
            "numprioridade" => $model->numprioridade,
            "datfimmeta" => $model->datfimmeta,
            "datfimreal" => $model->datfimreal,
            "desmanutencaogepnet" => $model->desmanutencaogepnet,
            "desobs" => $model->desobs,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "despaginaphp" => $model->despaginaphp,
            "domtipomanutencao" => $model->domtipomanutencao,
            "domsituacao" => $model->domsituacao,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Manutencaogepnet
     */
    public function update(Default_Model_Manutencaogepnet $model)
    {
        $data = array(
            "idmanutencaogepnet" => $model->idmanutencaogepnet,
            "numprioridade" => $model->numprioridade,
            "datfimmeta" => $model->datfimmeta,
            "datfimreal" => $model->datfimreal,
            "desmanutencaogepnet" => $model->desmanutencaogepnet,
            "desobs" => $model->desobs,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "despaginaphp" => $model->despaginaphp,
            "domtipomanutencao" => $model->domtipomanutencao,
            "domsituacao" => $model->domsituacao,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Manutencaogepnet);
    }

}

