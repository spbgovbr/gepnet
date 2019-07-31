<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Etapa extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Etapa
     */
    public function insert(Projeto_Model_Etapa $model)
    {
        $data = array(
            "idetapa" => $model->idetapa,
            "dsetapa" => $model->dsetapa,
            "idcadastrador" => $model->idcadastrador,
            "dtcadastro" => $model->dtcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Etapa
     */
    public function update(Projeto_Model_Etapa $model)
    {
        $data = array(
            "idetapa" => $model->idetapa,
            "dsetapa" => $model->dsetapa,
            "idcadastrador" => $model->idcadastrador,
            "dtcadastro" => $model->dtcadastro,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Projeto_Form_Etapa);
    }

    public function fetchPairs($selecione = false)
    {
        $sql = " SELECT idetapa, dsetapa FROM agepnet200.tb_etapa order by dsetapa asc";
        if ($selecione) {
            $arrEtapa = array('' => 'Selecione') + $this->_db->fetchPairs($sql);
            return $arrEtapa;
        }
        return $this->_db->fetchPairs($sql);
    }

    public function getEtapaPgp($selecione = false, $idProjeto = false)
    {
        $sql = " select *
                 from agepnet200.tb_etapa  
                 where pgpassinado is not null order by dsetapa asc";

        return $this->_db->fetchAll($sql);
    }

}

