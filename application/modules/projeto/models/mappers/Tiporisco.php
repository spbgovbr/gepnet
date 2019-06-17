<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Tiporisco extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Tiporisco
     */
    public function insert(Projeto_Model_Tiporisco $model)
    {
        $data = array(
            "idtiporisco" => $model->idtiporisco,
            "dstiporisco" => $model->dstiporisco,
            "idcadastrador" => $model->idcadastrador,
            "dtcadastro" => $model->dtcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Tiporisco
     */
    public function update(Projeto_Model_Tiporisco $model)
    {
        $data = array(
            "idtiporisco" => $model->idtiporisco,
            "dstiporisco" => $model->dstiporisco,
            "idcadastrador" => $model->idcadastrador,
            "dtcadastro" => $model->dtcadastro,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Projeto_Form_Tiporisco);
    }

    public function fetchPairs($selecione = false)
    {
        $sql = " SELECT idtiporisco, dstiporisco FROM agepnet200.tb_tiporisco order by dstiporisco asc";

        if ($selecione) {
            $arrTipoRisco = array('' => 'Selecione') + $this->_db->fetchPairs($sql);
            return $arrTipoRisco;
        }

        return $this->_db->fetchPairs($sql);
    }

}

