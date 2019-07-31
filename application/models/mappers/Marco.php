<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Marco extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Marco
     */
    public function insert(Default_Model_Marco $model)
    {
        $data = array(
            "idmarco" => $model->idmarco,
            "idprojeto" => $model->idprojeto,
            "numseq" => $model->numseq,
            "nommarco" => $model->nommarco,
            "datplanejado" => $model->datplanejado,
            "datprevisto" => $model->datprevisto,
            "datencerrado" => $model->datencerrado,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "idresponsavel" => $model->idresponsavel,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Marco
     */
    public function update(Default_Model_Marco $model)
    {
        $data = array(
            "idmarco" => $model->idmarco,
            "idprojeto" => $model->idprojeto,
            "numseq" => $model->numseq,
            "nommarco" => $model->nommarco,
            "datplanejado" => $model->datplanejado,
            "datprevisto" => $model->datprevisto,
            "datencerrado" => $model->datencerrado,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "idresponsavel" => $model->idresponsavel,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Marco);
    }

}

