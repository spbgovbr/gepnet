<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_PAcao extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_PAcao
     */
    public function insert(Default_Model_PAcao $model)
    {
        $data = array(
            "id_p_acao" => $model->id_p_acao,
            "idprojetoprocesso" => $model->idprojetoprocesso,
            "nom_p_acao" => $model->nom_p_acao,
            "des_p_acao" => $model->des_p_acao,
            "datinicioprevisto" => $model->datinicioprevisto,
            "datinicioreal" => $model->datinicioreal,
            "datterminoprevisto" => $model->datterminoprevisto,
            "datterminoreal" => $model->datterminoreal,
            "idsetorresponsavel" => $model->idsetorresponsavel,
            "flacancelada" => $model->flacancelada,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "numseq" => $model->numseq,
            "idresponsavel" => $model->idresponsavel,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_PAcao
     */
    public function update(Default_Model_PAcao $model)
    {
        $data = array(
            "id_p_acao" => $model->id_p_acao,
            "idprojetoprocesso" => $model->idprojetoprocesso,
            "nom_p_acao" => $model->nom_p_acao,
            "des_p_acao" => $model->des_p_acao,
            "datinicioprevisto" => $model->datinicioprevisto,
            "datinicioreal" => $model->datinicioreal,
            "datterminoprevisto" => $model->datterminoprevisto,
            "datterminoreal" => $model->datterminoreal,
            "idsetorresponsavel" => $model->idsetorresponsavel,
            "flacancelada" => $model->flacancelada,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "numseq" => $model->numseq,
            "idresponsavel" => $model->idresponsavel,
        );
    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_PAcao);
    }

}

