<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Agenda_Model_Mapper_Pessoaagenda extends App_Model_Mapper_MapperAbstract
{

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Pessoaagenda
     */
    public function insert(Agenda_Model_Pessoaagenda $model)
    {
        $data = array(
            "idagenda" => $model->idagenda,
            "idpessoa" => $model->idpessoa,
        );
        try {
            return $this->getDbTable()->insert($data);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return Default_Model_Pessoaagenda
     */
    public function update(Agenda_Model_Pessoaagenda $model)
    {
        $data = array(
            "idagenda" => $model->idagenda,
            "idpessoa" => $model->idpessoa,
        );
        // $this->getDbTable()->update($data, array("id = ?" => $id));
    }

    public function getForm()
    {
        return $this->_getForm(Agenda_Form_Pessoaagenda);
    }

    public function retornaPartesPorAgenda($params)
    {
        $sql = "
                SELECT
                  pagenda.idagenda,
                  pagenda.idpessoa,
                  pessoa.nompessoa,
                  pessoa.desemail
                FROM
                  agepnet200.tb_pessoaagenda pagenda,
                  agepnet200.tb_pessoa pessoa
                WHERE
                  pagenda.idpessoa = pessoa.idpessoa
                  and pagenda.idagenda = :idagenda
               ";
        $params = array_filter($params);

        $resultado = $this->_db->fetchAll($sql, array('idagenda' => $params['idagenda']));

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Agenda_Model_PessoaAgenda');

//        Zend_Debug::dump($resultado); exit;

        foreach ($resultado as $r) {
            $parte = new Agenda_Model_Pessoaagenda($r);
            $collection[] = $parte;
        }

        return $collection;
    }

    /**
     * Set the property
     *
     * @param string $value
     * @return
     */
//    public function excluir($params) {
//        try {
//            $pks = array(
//                "idpessoa" => $params['idpessoa'],
//                "idagenda" => $params['idagenda'],
//            );
//            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
//            //$where = $this->_db->quoteInto('idpessoa = ?', $model->idpessoa);
//            $retorno = $this->getDbTable()->delete($where);
//            return $retorno;
//        } catch (Exception $exc) {
//            throw $exc;
//        }
//    }

    public function excluir($params)
    {
        /*        try {
                    $pks = array(
                        "idagenda" => $params['idagenda'],
                    );
                    $where   = $this->_generateRestrictionsFromPrimaryKeys($pks);
                    $retorno = $this->getDbTable()->delete($where);
                    return $retorno;
                } catch ( Exception $exc ) {
                    throw $exc;
                }*/
//        var_dump($params); exit;
        $sql = "
                DELETE
                FROM
                  agepnet200.tb_pessoaagenda
                WHERE
                  idagenda = :idagenda
               ";
        $params = array_filter($params);

        $resultado = $this->_db->fetchAll($sql, array('idagenda' => $params['idagenda']));

        return $resultado;

    }

    public function excluirparticipante($params)
    {
        $sql = "
                DELETE
                FROM
                  agepnet200.tb_pessoaagenda
                WHERE
                  idagenda = :idagenda
                  and idpessoa = :idpessoa
               ";
        $params = array_filter($params);

        $resultado = $this->_db->fetchAll($sql,
            array('idagenda' => $params['idagenda'], 'idpessoa' => $params['idpessoa']));

        return $resultado;

    }
}

