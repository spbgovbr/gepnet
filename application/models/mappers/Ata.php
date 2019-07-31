<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Ata extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Ata
     */
    public function insert(Default_Model_Ata $model)
    {
        $data = array(
            "idata" => $model->idata,
            "idprojeto" => $model->idprojeto,
            "desassunto" => $model->desassunto,
            "datata" => $model->datata,
            "hrreuniao" => $model->hrreuniao,
            "deslocal" => $model->deslocal,
            "desparticipante" => $model->desparticipante,
            "despontodiscutido" => $model->despontodiscutido,
            "desdecisao" => $model->desdecisao,
            "despontoatencao" => $model->despontoatencao,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "desproximopasso" => $model->desproximopasso,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Ata
     */
    public function update(Default_Model_Ata $model)
    {
        $data = array(
            "idata" => $model->idata,
            "idprojeto" => $model->idprojeto,
            "desassunto" => $model->desassunto,
            "datata" => $model->datata,
            "hrreuniao" => $model->hrreuniao,
            "deslocal" => $model->deslocal,
            "desparticipante" => $model->desparticipante,
            "despontodiscutido" => $model->despontodiscutido,
            "desdecisao" => $model->desdecisao,
            "despontoatencao" => $model->despontoatencao,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "desproximopasso" => $model->desproximopasso,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Ata);
    }

    public function getById($params)
    {
        $sql = "SELECT
                    idata,
                    idprojeto,
                    desassunto,
                    datata,
                    hrreuniao,
                    deslocal,
                    desparticipante,
                    despontodiscutido,
                    desdecisao,
                    despontoatencao,
                    idcadastrador,
                    datcadastro,
                    desproximopasso
                FROM agepnet200.tb_ata
                WHERE idata = :idata";
        $resultado = $this->_db->fetchRow($sql, array('idata' => $params['idata']));
        return new Default_Model_Ata($resultado);
    }

    public function retornaPorProjeto($params)
    {
        $sql = "SELECT
                    idata,
                    idprojeto,
                    desassunto,
                    datata,
                    hrreuniao,
                    deslocal,
                    desparticipante,
                    despontodiscutido,
                    desdecisao,
                    despontoatencao,
                    idcadastrador,
                    datcadastro,
                    desproximopasso
                FROM agepnet200.tb_ata
                WHERE idprojeto = :idprojeto";
        $resultado = $this->_db->fetchRow($sql, array('idprojeto' => $params['idprojeto']));
        return new Default_Model_Ata($resultado);
    }

}

