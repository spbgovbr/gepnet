<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Objetivo extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Objetivo
     */
    public function insert(Default_Model_Objetivo $model)
    {
        $data = array(
            "idobjetivo" => $model->idobjetivo,
            "nomobjetivo" => $model->nomobjetivo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "flaativo" => $model->flaativo,
            "desobjetivo" => $model->desobjetivo,
            "codescritorio" => $model->codescritorio,
            "numseq" => $model->numseq,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Objetivo
     */
    public function update(Default_Model_Objetivo $model)
    {
        $data = array(
            "idobjetivo" => $model->idobjetivo,
            "nomobjetivo" => $model->nomobjetivo,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "flaativo" => $model->flaativo,
            "desobjetivo" => $model->desobjetivo,
            "codescritorio" => $model->codescritorio,
            "numseq" => $model->numseq,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Objetivo);
    }

    public function fetchPairs()
    {
        $sql = " SELECT idobjetivo, nomobjetivo FROM agepnet200.tb_objetivo
                 where flaativo = 'S' order by nomobjetivo asc";
        return $this->_db->fetchPairs($sql);
    }

    public function getById($params)
    {
        $sql = " SELECT 
                        idobjetivo, 
                        nomobjetivo,
                        idcadastrador,
                        datcadastro,
                        flaativo,
                        desobjetivo,
                        codescritorio,
                        numseq
                 FROM   
                        agepnet200.tb_objetivo
                 WHERE 
                        idobjetivo = :idobjetivo";

        $resultado = $this->_db->fetchRow($sql, array('idobjetivo' => $params['idobjetivo']));


        return new Default_Model_Objetivo($resultado);
    }

}

