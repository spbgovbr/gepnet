<?php

class Pesquisa_Model_Mapper_RespostaFrasePesquisa extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_RespostaFrasePesquisa
     */
    public function insert(Pesquisa_Model_RespostaFrasePesquisa $model)
    {
        $data = array(
            "idfrasepesquisa" => $model->idfrasepesquisa,
            "idrespostapesquisa" => $model->idrespostapesquisa,
        );
        return $this->getDbTable()->insert($data);
    }
}