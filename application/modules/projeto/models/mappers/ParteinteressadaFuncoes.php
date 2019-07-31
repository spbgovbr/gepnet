<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_ParteinteressadaFuncoes extends App_Model_Mapper_MapperAbstract
{
    public function insert($params)
    {
        $data = array(
            'idparteinteressada' => $params['idparteinteressada'],
            'idparteinteressadafuncao' => $params['idparteinteressadafuncao']
        );

        return $this->getDbTable()->insert($data);
    }

    public function delete($params)
    {
        $pks = array(
            'idparteinteressada' => $params['idparteinteressada'],
            'idparteinteressadafuncao' => $params['idparteinteressadafuncao']
        );

        $where = $this->_generateRestrictionsFromPrimaryKeys($pks);

        return $this->getDbTable()->delete($where);
    }

    public function deleteAll($params)
    {
        $where = $this->_db->quoteInto('idparteinteressada = ?', $params['idparteinteressada']);

        return $this->getDbTable()->delete($where);
    }
}
