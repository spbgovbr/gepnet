<?php

class Pesquisa_Model_Mapper_RespostaFrase extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_Frase
     */
    public function insert(Pesquisa_Model_RespostaFrase $model)
    {
        $data = array(
            "idfrase" => $model->idfrase,
            "idresposta" => $model->idresposta,
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Pesquisa_Model_RespostaFrase
     */
    public function update(Pesquisa_Model_RespostaFrase $model)
    {
        $data = array(
            "idfrase" => $model->idfrase,
            "idresposta" => $model->idresposta,
        );
        return $this->getDbTable()->update($data,
            array("idfrase = ?" => $model->idfrase, 'idresposta = ?' => $model->idresposta));
    }

//    public function delete($params)
//    {
//       $where =  $this->quoteInto(' idresposta = ? ', (int)$params['idresposta']);
//       $result =  $this->getDbTable()->delete($where);
//       return $result;
//    }
}