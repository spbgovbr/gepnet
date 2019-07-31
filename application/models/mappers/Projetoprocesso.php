<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Projetoprocesso extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Projetoprocesso
     */
    public function insert(Default_Model_Projetoprocesso $model)
    {


        $data = array(
            "idprojetoprocesso" => $model->idprojetoprocesso,
            "idprocesso" => $model->idprocesso,
            "numano" => $model->numano,
            "domsituacao" => $model->domsituacao,
            "datsituacao" => $model->datsituacao,
            "idresponsavel" => $model->idresponsavel,
            "desprojetoprocesso" => $model->desprojetoprocesso,
            "datinicioprevisto" => $model->datinicioprevisto,
            "datterminoprevisto" => $model->datterminoprevisto,
            "vlrorcamento" => $model->vlrorcamento,
            "idcadastrador" => $model->idcadastrador,
            "codprojeto" => $model->codprojeto,
            "datcadastro" => $model->datcadastro,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Projetoprocesso
     */
    public function update(Default_Model_Projetoprocesso $model)
    {
        $data = array(
            "idprojetoprocesso" => $model->idprojetoprocesso,
            "idprocesso" => $model->idprocesso,
            "numano" => $model->numano,
            "domsituacao" => $model->domsituacao,
            "datsituacao" => $model->datsituacao,
            "idresponsavel" => $model->idresponsavel,
            "desprojetoprocesso" => $model->desprojetoprocesso,
            "datinicioprevisto" => $model->datinicioprevisto,
            "datterminoprevisto" => $model->datterminoprevisto,
            "vlrorcamento" => $model->vlrorcamento,
            "idcadastrador" => $model->idcadastrador,
            "codprojeto" => $model->codprojeto,
            "datcadastro" => $model->datcadastro,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Projetoprocesso);
    }

}

