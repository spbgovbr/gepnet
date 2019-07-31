<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Risco extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Risco
     */
    public function insert(Default_Model_Risco $model)
    {
        $data = array(
            "idrisco" => $model->idrisco,
            "idprojeto" => $model->idprojeto,
            "idorigemrisco" => $model->idorigemrisco,
            "idetapa" => $model->idetapa,
            "idtiporisco" => $model->idtiporisco,
            "datdeteccao" => $model->datdeteccao,
            "desrisco" => $model->desrisco,
            "domcorprobabilidade" => $model->domcorprobabilidade,
            "domcorimpacto" => $model->domcorimpacto,
            "domcorrisco" => $model->domcorrisco,
            "descausa" => $model->descausa,
            "desconsequencia" => $model->desconsequencia,
            "domtratamento" => $model->domtratamento,
            "flariscoativo" => $model->flariscoativo,
            "datencerramentorisco" => $model->datencerramentorisco,
            "idcadastrador" => $model->idcadastrador,
            "datcadastrado" => $model->datcadastrado,
            "flaaprovado" => $model->flaaprovado,
            "datinatividade" => $model->datinatividade,
        );


        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Risco
     */
    public function update(Default_Model_Risco $model)
    {
        $data = array(
            "idrisco" => $model->idrisco,
            "idprojeto" => $model->idprojeto,
            "idorigemrisco" => $model->idorigemrisco,
            "idetapa" => $model->idetapa,
            "idtiporisco" => $model->idtiporisco,
            "datdeteccao" => $model->datdeteccao,
            "desrisco" => $model->desrisco,
            "domcorprobabilidade" => $model->domcorprobabilidade,
            "domcorimpacto" => $model->domcorimpacto,
            "domcorrisco" => $model->domcorrisco,
            "descausa" => $model->descausa,
            "desconsequencia" => $model->desconsequencia,
            "domtratamento" => $model->domtratamento,
            "flariscoativo" => $model->flariscoativo,
            "datencerramentorisco" => $model->datencerramentorisco,
            "idcadastrador" => $model->idcadastrador,
            "datcadastrado" => $model->datcadastrado,
            "flaaprovado" => $model->flaaprovado,
            "datinatividade" => $model->datinatividade,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Risco);
    }

    public function getById($params)
    {
        $sql = "SELECT
                    idrisco,
                    idprojeto,
                    idorigemrisco,
                    idetapa,
                    idtiporisco,
                    datdeteccao,
                    desrisco,
                    domcorprobabilidade,
                    domcorimpacto,
                    domcorrisco,
                    descausa,
                    desconsequencia,
                    domtratamento,
                    flariscoativo,
                    datencerramentorisco,
                    idcadastrador,
                    datcadastro
                FROM agepnet200.tb_risco
                WHERE idrisco = :idrisco";
        $resultado = $this->_db->fetchRow($sql, array('idrisco' => $params['idrisco']));
        return new Default_Model_Risco($resultado);
    }

    public function retornaPorProjeto($params)
    {
        $sql = "SELECT * /*
                    idrisco,
                    idprojeto,
                    idorigemrisco,
                    idetapa,
                    idtiporisco,
                    datdeteccao,
                    desrisco,
                    domcorprobabilidade,
                    domcorimpacto,
                    domcorrisco,
                    descausa,
                    desconsequencia,
                    domtratamento,
                    flariscoativo,
                    datencerramentorisco,
                    idcadastrador,
                    datcadastro*/
                FROM agepnet200.tb_risco
                WHERE idprojeto = :idprojeto";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return new Default_Model_Risco($resultado);
    }

}

