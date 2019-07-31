<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Pesquisa_Model_Mapper_FrasePesquisa extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_FrasePesquisa
     */
    public function insert(Pesquisa_Model_FrasePesquisa $model)
    {
        $data = array(
            "idfrasepesquisa" => $this->maxVal('idfrasepesquisa'),
            "desfrase" => $model->desfrase,
            "domtipofrase" => $model->domtipofrase,
            "flaativo" => $model->flaativo,
            "datcadastro" => $model->datcadastro,
            "idescritorio" => $model->idescritorio,
            "idcadastrador" => $model->idcadastrador,
        );
        return $this->getDbTable()->insert($data);
    }

}