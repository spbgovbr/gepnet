<?php

class Projeto_Model_Mapper_Tipocontramedida extends App_Model_Mapper_MapperAbstract {

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Tipocontramedida
     */
    public function insert(Projeto_Model_Tipocontramedida $model)
    {
        $data = array(
            "idtipocontramedida" => $this->maxVal('idtipocontramedida'),
            "notipocontramedida" => $model->notipocontramedida,
            "dstipocontramedida" => $model->dstipocontramedida,
        );
        return $this->getDbTable()->insert($data);
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Projeto_Model_Tipocontramedida
     */
    public function update(Projeto_Model_Tipocontramedida $model)
    {
         $data = array(
            "idtipocontramedida" => $this->maxVal('idtipocontramedida'),
            "notipocontramedida" => $model->notipocontramedida,
            "dstipocontramedida" => $model->dstipocontramedida,
        );
        return $this->getDbTable()->update($data, array("idtipocontramedida = ?" => $model->idtipocontramedida));
    }

    public function delete($params)
    {
       $where =  $this->quoteInto('idtipocontramedida = ?', (int)$params['idtipocontramedida']);        
       $result =  $this->getDbTable()->delete($where);
       return $result;
    }

    public function fetchPairs($selecione = false)
    {
    	$sql = " SELECT idtipocontramedida, notipocontramedida FROM agepnet200.tb_tipocontramedida order by notipocontramedida asc";
        
        if($selecione) {
            $arrTipoContramedida = array(''=>'Selecione') +  $this->_db->fetchPairs($sql);
            return $arrTipoContramedida;
        }
        
    	return $this->_db->fetchPairs($sql);
    } 

}
