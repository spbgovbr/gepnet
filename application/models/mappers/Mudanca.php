<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Mudanca extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Mudanca
     */
    public function insert(Default_Model_Mudanca $model)
    {
        $data = array(
            "idmudanca" => $model->idmudanca,
            "idprojeto" => $model->idprojeto,
            "nomsolicitante" => $model->nomsolicitante,
            "datsolicitacao" => $model->datsolicitacao,
            "datdecisao" => $model->datdecisao,
            "flaaprovada" => $model->flaaprovada,
            "desmudanca" => $model->desmudanca,
            "desjustificativa" => $model->desjustificativa,
            "despareceregp" => $model->despareceregp,
            "desaprovadores" => $model->desaprovadores,
            "despareceraprovadores" => $model->despareceraprovadores,
            "idcadastrador" => $model->idcadastrador,
            "idtipomudanca" => $model->idtipomudanca,
            "datcadastro" => $model->datcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Mudanca
     */
    public function update(Default_Model_Mudanca $model)
    {
        $data = array(
            "idmudanca" => $model->idmudanca,
            "idprojeto" => $model->idprojeto,
            "nomsolicitante" => $model->nomsolicitante,
            "datsolicitacao" => $model->datsolicitacao,
            "datdecisao" => $model->datdecisao,
            "flaaprovada" => $model->flaaprovada,
            "desmudanca" => $model->desmudanca,
            "desjustificativa" => $model->desjustificativa,
            "despareceregp" => $model->despareceregp,
            "desaprovadores" => $model->desaprovadores,
            "despareceraprovadores" => $model->despareceraprovadores,
            "idcadastrador" => $model->idcadastrador,
            "idtipomudanca" => $model->idtipomudanca,
            "datcadastro" => $model->datcadastro,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Mudanca);
    }

    public function retornaPorProjeto($params)
    {
        $sql = "select
                    m.idmudanca,
                    m.idprojeto,
                    m.nomsolicitante,
                    m.datsolicitacao,
                    m.datdecisao,
                    m.flaaprovada,
                    m.desmudanca,
                    m.desjustificativa,
                    m.despareceregp,
                    m.desaprovadores,
                    m.despareceraprovadores,
                    m.idcadastrador,
                    tm.dsmudanca,
                    m.datcadastro
                from 
                    agepnet200.tb_mudanca m, agepnet200.tb_tipomudanca tm
                where 
                    m.idtipomudanca = tm.idtipomudanca
                    and idprojeto = :idprojeto
                order by m.datsolicitacao asc";

        $resultado = $this->_db->fetchAll($sql, array('idprojeto' => $params['idprojeto']));
        return new Default_Model_Mudanca($resultado);
    }

}

