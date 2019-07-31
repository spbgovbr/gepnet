<?php

class Pesquisa_Model_Mapper_RespostaPesquisa extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_RespostaPesquisa
     */
    public function insert(Pesquisa_Model_RespostaPesquisa $model)
    {
        $data = array(
            "idrespostapesquisa" => $this->maxVal('idrespostapesquisa'),
            "numordem" => $model->numordem,
            "flaativo" => $model->flaativo,
            "datcadastro" => $model->datcadastro,
            "desresposta" => $model->desresposta,
            "idcadastrador" => $model->idcadastrador,
        );
        return $this->getDbTable()->insert($data);
    }

}