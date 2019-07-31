<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Aquisicao extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Aquisicao
     */
    public function insert(Default_Model_Aquisicao $model)
    {
        $data = array(
            "idaquisicao" => $model->idaquisicao,
            "idprojeto" => $model->idprojeto,
            "identrega" => $model->identrega,
            "descontrato" => $model->descontrato,
            "desfornecedor" => $model->desfornecedor,
            "numvalor" => $model->numvalor,
            "datprazoaquisicao" => $model->datprazoaquisicao,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "numquantidade" => $model->numquantidade,
            "desaquisicao" => $model->desaquisicao,
        );
        $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Aquisicao
     */
    public function update(Default_Model_Aquisicao $model)
    {
        $data = array(
            "idaquisicao" => $model->idaquisicao,
            "idprojeto" => $model->idprojeto,
            "identrega" => $model->identrega,
            "descontrato" => $model->descontrato,
            "desfornecedor" => $model->desfornecedor,
            "numvalor" => $model->numvalor,
            "datprazoaquisicao" => $model->datprazoaquisicao,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
            "numquantidade" => $model->numquantidade,
            "desaquisicao" => $model->desaquisicao,
        );

    }

    public function getForm()
    {
        return $this->_getForm(Default_Form_Aquisicao);
    }

}

