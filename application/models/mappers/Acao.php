<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Acao extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Acao
     */
    public function insert(Default_Model_Acao $model)
    {
        $data = array(
            "idacao" => $model->idacao,
            "idobjetivo" => $model->idobjetivo,
            "nomacao" => $model->nomacao,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "flaativo" => $model->flaativo,
            "desacao" => $model->desacao,
            "idescritorio" => $model->idescritorio,
            "numseq" => $model->numseq,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Acao
     */
    public function update(Default_Model_Acao $model)
    {
        $data = array(
            "idacao" => $model->idacao,
            "idobjetivo" => $model->idobjetivo,
            "nomacao" => $model->nomacao,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "flaativo" => $model->flaativo,
            "desacao" => $model->desacao,
            "idescritorio" => $model->idescritorio,
            "numseq" => $model->numseq,
        );

    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Acao);
    }


    public function retornarAcaoByObjetivo($params)
    {
        $sql = " SELECT idacao, nomacao FROM agepnet200.tb_acao
                 WHERE flaativo = 'S' AND idobjetivo in(:idobjetivo) order by nomacao asc";
        //var_dump($sql);die;
        return $this->_db->fetchPairs($sql, array('idobjetivo' => $params['idobjetivo']));
    }

    public function fetchPairs()
    {
        $sql = " SELECT idacao, nomacao FROM agepnet200.tb_acao
                                     where flaativo = 'S' order by nomacao asc";
        return $this->_db->fetchPairs($sql);
    }

    public function fetchPairsByObjetivo($params)
    {
        $sql = "SELECT idacao, nomacao FROM agepnet200.tb_acao
                WHERE flaativo = 'S' AND idobjetivo in (:idobjetivo) 
                ORDER BY nomacao asc";
        return $this->_db->fetchPairs($sql, array(
            'idobjetivo' => $params['idobjetivo']
        ));
    }

    public function getById($params)
    {
        $sql = "SELECT
                    idacao, idobjetivo, nomacao,
                    datcadastro, flaativo, desacao, idescritorio, numseq
                FROM agepnet200.tb_acao
                WHERE idacao = :idacao";
        $resultado = $this->_db->fetchRow($sql, array('idacao' => $params['idacao']));
        return new Default_Model_Acao($resultado);
    }

}

