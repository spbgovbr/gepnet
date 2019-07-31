<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Parteinteressada extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Parteinteressada
     */
    public function insert(Default_Model_Parteinteressada $model)
    {
        $data = array(
            "idparteinteressada" => $model->idparteinteressada,
            "idprojeto" => $model->idprojeto,
            "nomparteinteressada" => $model->nomparteinteressada,
            "nomfuncao" => $model->nomfuncao,
            "destelefone" => $model->destelefone,
            "desemail" => $model->desemail,
            "domnivelinfluencia" => $model->domnivelinfluencia,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Parteinteressada
     */
    public function update(Default_Model_Parteinteressada $model)
    {
        $data = array(
            "idparteinteressada" => $model->idparteinteressada,
            "idprojeto" => $model->idprojeto,
            "nomparteinteressada" => $model->nomparteinteressada,
            "nomfuncao" => $model->nomfuncao,
            "destelefone" => $model->destelefone,
            "desemail" => $model->desemail,
            "domnivelinfluencia" => $model->domnivelinfluencia,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Parteinteressada);
    }

}

