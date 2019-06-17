<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Tipoescritorio extends App_Model_Mapper_MapperAbstract
{

    /**
     * @param string $value
     * @return Default_Model_Tipoescritorio
     * @todo implementar o método de insert
     * Set the property
     *
     */
    public function insert(Default_Model_Tipoescriotorio $model)
    {
        $data = array(
            "idtipodocumento" => $model->idtipodocumento,
            "nomtipodocumento" => $model->nomtipodocumento,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "flaativo" => $model->flaativo,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * @param string $value
     * @return Default_Model_Tipoescritorio
     * @todo implementar o método de update
     *
     * Set the property
     *
     */
    public function update(Default_Model_Tipoescritorio $model)
    {
        $data = array(
            "idescritorio" => $model->idescritorio,
            "nomescritorio" => $model->nomescritorio,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "flaativo" => $model->flaativo,
            "idresponsavel1" => $model->idresponsavel1,
            "idresponsavel2" => $model->idresponsavel2,
            "idescritoriope" => $model->idescritoriope,
            "nomescritorio2" => $model->nomescritorio2,
        );
    }

    public function getById($params)
    {
        $sql = "SELECT 
                    idtipodocumento, nomtipodocumento, idcadastrador, to_char(datcadastro,'DD/MM/YYYY'), flaativo
                FROM agepnet200.tb_tipodocumento
                WHERE id_unidade = :idtipodocumento";
        return $this->_db->fetchOne($sql, array('idtipodocumento' => $params['idtipodocumento']));
    }

    /**
     * @param type $params
     * @return type
     * @todo Desenvolver o método retornando o json filtrado para o componente select2
     */
    public function fetchPairs()
    {
        $sql = "SELECT 
                    idtipodocumento, nomtipodocumento
                FROM agepnet200.tb_tipodocumento
                WHERE flaativo = 'S'
                ORDER BY nomtipodocumento";
        return $this->_db->fetchPairs($sql);
    }

}

