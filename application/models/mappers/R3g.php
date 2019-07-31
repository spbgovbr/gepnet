<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_R3g extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_R3g
     */
    public function insert(Default_Model_R3g $model)
    {
        $data = array(
            "idr3g" => $model->idr3g,
            "idprojeto" => $model->idprojeto,
            "datdeteccao" => $model->datdeteccao,
            "domtipo" => $model->domtipo,
            "desplanejado" => $model->desplanejado,
            "desrealizado" => $model->desrealizado,
            "descausa" => $model->descausa,
            "desconsequencia" => $model->desconsequencia,
            "descontramedida" => $model->descontramedida,
            "datprazocontramedida" => $model->datprazocontramedida,
            "datprazocontramedidaatraso" => $model->datprazocontramedidaatraso,
            "domcorprazoprojeto" => $model->domcorprazoprojeto,
            "domstatuscontramedida" => $model->domstatuscontramedida,
            "flacontramedidaefetiva" => $model->flacontramedidaefetiva,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "desresponsavel" => $model->desresponsavel,
            "desobs" => $model->desobs,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_R3g
     */
    public function update(Default_Model_R3g $model)
    {
        $data = array(
            "idr3g" => $model->idr3g,
            "idprojeto" => $model->idprojeto,
            "datdeteccao" => $model->datdeteccao,
            "domtipo" => $model->domtipo,
            "desplanejado" => $model->desplanejado,
            "desrealizado" => $model->desrealizado,
            "descausa" => $model->descausa,
            "desconsequencia" => $model->desconsequencia,
            "descontramedida" => $model->descontramedida,
            "datprazocontramedida" => $model->datprazocontramedida,
            "datprazocontramedidaatraso" => $model->datprazocontramedidaatraso,
            "domcorprazoprojeto" => $model->domcorprazoprojeto,
            "domstatuscontramedida" => $model->domstatuscontramedida,
            "flacontramedidaefetiva" => $model->flacontramedidaefetiva,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "desresponsavel" => $model->desresponsavel,
            "desobs" => $model->desobs,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_R3g);
    }

    public function getById($params)
    {
        $sql = "SELECT
                    idr3g,
                    idprojeto,
                    datdeteccao,
                    domtipo,
                    desplanejado,
                    desrealizado,
                    descausa,
                    desconsequencia,
                    descontramedida,
                    datprazocontramedida,
                    datprazocontramedidaatraso,
                    domcorprazoprojeto,
                    domstatuscontramedida,
                    flacontramedidaefetiva,
                    idcadastrador,
                    datcadastro,
                    desresponsavel,
                    desobs
                FROM agepnet200.tb_r3g
                WHERE idr3g = :idr3g";
        $resultado = $this->_db->fetchRow($sql, array('idr3g' => $params['idr3g']));
        return new Default_Model_R3g($resultado);
    }

    public function retornaPorProjeto($params)
    {
        $sql = "SELECT
                    idr3g,
                    idprojeto,
                    datdeteccao,
                    domtipo,
                    desplanejado,
                    desrealizado,
                    descausa,
                    desconsequencia,
                    descontramedida,
                    datprazocontramedida,
                    datprazocontramedidaatraso,
                    domcorprazoprojeto,
                    domstatuscontramedida,
                    flacontramedidaefetiva,
                    idcadastrador,
                    datcadastro,
                    desresponsavel,
                    desobs
                FROM agepnet200.tb_r3g
                WHERE idprojeto = :idprojeto";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return new Default_Model_R3g($resultado);
    }

}

