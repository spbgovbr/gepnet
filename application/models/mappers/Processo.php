<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Processo extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Processo
     */
    public function insert(Default_Model_Processo $model)
    {
        $data = array(
            "idprocesso"     => $model->idprocesso,
            "idprocessopai"  => $model->idprocessopai,
            "nomcodigo"      => $model->nomcodigo,
            "nomprocesso"    => $model->nomprocesso,
            "idsetor"        => $model->idsetor,
            "desprocesso"    => $model->desprocesso,
            "iddono"         => $model->iddono,
            "idexecutor"     => $model->idexecutor,
            "idgestor"       => $model->idgestor,
            "idconsultor"    => $model->idconsultor,
            "numvalidade"    => $model->numvalidade,
            "datatualizacao" => $model->datatualizacao,
            "idcadastrador"  => $model->idcadastrador,
            "datcadastro"    => $model->datcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Processo
     */
    public function update(Default_Model_Processo $model)
    {
        $data = array(
            "idprocesso"     => $model->idprocesso,
            "idprocessopai"  => $model->idprocessopai,
            "nomcodigo"      => $model->nomcodigo,
            "nomprocesso"    => $model->nomprocesso,
            "idsetor"        => $model->idsetor,
            "desprocesso"    => $model->desprocesso,
            "iddono"         => $model->iddono,
            "idexecutor"     => $model->idexecutor,
            "idgestor"       => $model->idgestor,
            "idconsultor"    => $model->idconsultor,
            "numvalidade"    => $model->numvalidade,
            "datatualizacao" => $model->datatualizacao,
            "idcadastrador"  => $model->idcadastrador,
            //"datcadastro"    => $model->datcadastro,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Processo);
    }

}

