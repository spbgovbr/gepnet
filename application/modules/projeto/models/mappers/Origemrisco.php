<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Origemrisco extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Origemrisco
     */
    public function insert(Projeto_Model_Origemrisco $model)
    {
        $data = array(
            "idorigemrisco" => $model->idorigemrisco,
            "desorigemrisco" => $model->desorigemrisco,
            "idcadastrador" => $model->idcadastrador,
            "dtcadastro" => $model->dtcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Origemrisco
     */
    public function update(Projeto_Model_Origemrisco $model)
    {
        $data = array(
            "idorigemrisco" => $model->idorigemrisco,
            "desorigemrisco" => $model->desorigemrisco,
            "idcadastrador" => $model->idcadastrador,
            "dtcadastro" => $model->dtcadastro,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Projeto_Form_Origemrisco);
    }

    public function fetchPairs($selecione = false)
    {
        $sql = " SELECT idorigemrisco, desorigemrisco FROM agepnet200.tb_origemrisco order by idorigemrisco";

        if ($selecione) {
            $arrOrigemRisco = array('' => 'Selecione') + $this->_db->fetchPairs($sql);
            return $arrOrigemRisco;
        }

        return $this->_db->fetchPairs($sql);
    }

}

