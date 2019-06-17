<?php

class Projeto_Model_Mapper_Tipocontramedida extends App_Model_Mapper_MapperAbstract
{

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
        $where = $this->quoteInto('idtipocontramedida = ?', (int)$params['idtipocontramedida']);
        $result = $this->getDbTable()->delete($where);
        return $result;
    }

    public function fetchPairs($selecione = false, $status = false)
    {
        $sql = " SELECT * FROM agepnet200.tb_tipocontramedida ";
        $sql .= $status ? " where idstatustipocontramedida in ($status, 3) " : "";
        $sql .= " order by notipocontramedida asc";

        if ($selecione) {
            array('' => 'Selecione') + $this->_db->fetchPairs($sql);
        }

        return $this->_db->fetchPairs($sql);
    }

}
