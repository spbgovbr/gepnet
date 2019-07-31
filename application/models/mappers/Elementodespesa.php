<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Elementodespesa extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Elementodespesa
     */
    public function insert(Default_Model_Elementodespesa $model)
    {
        $data = array(
            "idelementodespesa" => $model->idelementodespesa,
            "idoficial" => $model->idoficial,
            "nomelementodespesa" => $model->nomelementodespesa,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "numseq" => $model->numseq,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Elementodespesa
     */
    public function update(Default_Model_Elementodespesa $model)
    {
        $data = array(
            "idelementodespesa" => $model->idelementodespesa,
            "idoficial" => $model->idoficial,
            "nomelementodespesa" => $model->nomelementodespesa,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "numseq" => $model->numseq,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Elementodespesa);
    }

    public function fetchPairs()
    {

        $sql = "SELECT idelementodespesa, nomelementodespesa
                FROM agepnet200.tb_elementodespesa ";

        return $this->_db->fetchPairs($sql);

    }

    public function getById($params)
    {

        $sql = "SELECT idelementodespesa, nomelementodespesa
                FROM agepnet200.tb_elementodespesa where idelementodespesa = :idelementodespesa ";

        $resultado = $this->_db->fetchRow($sql, array('idelementodespesa' => $params['idelementodespesa']));
        return $resultado;

    }
}
