<?php

class Pesquisa_Model_Mapper_QuestionariofrasePesquisa extends App_Model_Mapper_MapperAbstract
{
    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_QuestionariofrasePesquisa
     */
    public function insert(Pesquisa_Model_QuestionariofrasePesquisa $model)
    {
        $data = array(
            "idfrasepesquisa" => $model->idfrasepesquisa,
            "idquestionariopesquisa" => $model->idquestionariopesquisa,
            "numordempergunta" => $model->numordempergunta,
            "obrigatoriedade" => $model->obrigatoriedade,
            "idcadastrador" => $model->idcadastrador,
            "datcadastro" => $model->datcadastro,
        );
        return $this->getDbTable()->insert($data);
    }
}
