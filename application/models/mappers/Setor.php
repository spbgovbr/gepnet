<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Setor extends App_Model_Mapper_MapperAbstract
{

    /**
     *
     * @var Default_Model_Mapper_Setor
     */
    protected $_mapper;

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Setor
     */
    public function insert(Default_Model_Setor $model)
    {
        $data = array(
            "idsetor" => $model->idsetor,
            "nomsetor" => $model->nomsetor,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "flaativo" => $model->flaativo,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the  property
     *
     * @param string $value
     * @return Default_Model_Setor
     */
    public function update(Default_Model_Setor $model)
    {
        $data = array(
            "idsetor" => $model->idsetor,
            "nomsetor" => $model->nomsetor,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "flaativo" => $model->flaativo,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Setor);
    }

    public function fetchPairs()
    {
        $sql = " SELECT idsetor, nomsetor FROM agepnet200.tb_setor
                  where flaativo = 'S' order by nomsetor asc";
        return $this->_db->fetchPairs($sql);
    }

    public function getById($params)
    {
        $sql = "
                SELECT
                    idsetor,
                    nomsetor,
                    idcadastrador,
                    datcadastro,
                    flaativo
                FROM
                    agepnet200.tb_setor
                WHERE
                    idsetor = :idsetor";

        $resultado = $this->_db->fetchRow($sql, array('idsetor' => $params["idsetor"]));

        if (false == $resultado) {
            return false;
        }

        return $resultado;
    }
}

