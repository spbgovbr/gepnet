<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Natureza extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Natureza
     */
    public function insert(Default_Model_Natureza $model)
    {
        $data = array(
            "idnatureza" => $model->idnatureza,
            "nomnatureza" => $model->nomnatureza,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "flaativo" => $model->flaativo,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Natureza
     */
    public function update(Default_Model_Natureza $model)
    {
        $data = array(
            "idnatureza" => $model->idnatureza,
            "nomnatureza" => $model->nomnatureza,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "flaativo" => $model->flaativo,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Natureza);
    }

    public function fetchPairs()
    {
        $sql = "  SELECT idnatureza, nomnatureza FROM agepnet200.tb_natureza order by nomnatureza asc";

        //Zend_Debug::dump($sql);exit;
        return $this->_db->fetchPairs($sql);
    }

    public function getById($params)
    {
        $sql = "
            SELECT
                idnatureza,
                nomnatureza,
                idcadastrador,
                datcadastro,
                flaativo
            FROM
                agepnet200.tb_natureza
            WHERE
                idnatureza = :idnatureza";

        $resultado = $this->_db->fetchRow($sql, array("idnatureza" => $params["idnatureza"]));

        if (false == $resultado) {
            return false;
        }

        return new Default_Model_Natureza($resultado);
    }
}

