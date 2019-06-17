<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_Atividadeocultar extends App_Model_Mapper_MapperAbstract
{


    public function insert(Projeto_Model_Atividadeocultar $model)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $data = array(
            "idprojeto" => $model->idprojeto,
            "idatividadecronograma" => $model->idatividadecronograma,
            "idpessoa" => $idpessoa
        );

        try {
            $this->getDbTable()->insert($data);
            return $model;
        } catch (Exception $exc) {
            Zend_Debug::dump($exc);
            exit;

        }
    }

    public function excluir($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        try {
            $pks = array(
                "idprojeto" => $params['idprojeto'],
                "idatividadecronograma" => $params['idatividadecronograma'],
                "idpessoa" => $idpessoa
            );
            $where = $this->_generateRestrictionsFromPrimaryKeys($pks);
            //$where = $this->_db->quoteInto('idpessoa = ?', $model->idpessoa);
            $retorno = $this->getDbTable()->delete($where);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function verificaAtividadeOcultar($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "select count(*) ctatividade FROM agepnet200.tb_atividadeocultar vs
                where vs.idprojeto = :idprojeto and vs.idatividadecronograma=:idatividadecronograma
                and   vs.idpessoa  = :idpessoa ";

        $retorno = $this->_db->fetchRow($sql, array(
            'idatividadecronograma' => $params['idatividadecronograma'],
            'idprojeto' => $params['idprojeto'],
            'idpessoa' => $idpessoa,
        ));
        return ($retorno['ctatividade'] > 0 ? true : false);
    }


    public function buscaIdparteinteressada($params)
    {
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $sql = "SELECT par.idparteinteressada, pes.idpessoa FROM agepnet200.tb_pessoa pes, agepnet200.tb_parteinteressada par
        where pes.idpessoa  = par.idpessoainterna and pes.idpessoa = :idpessoa
        and   par.idprojeto = :idprojeto limit 1 ";
        //$resultado = $this->_db->fetchAll($sql, array(
        //    'idprojeto' => $params['idprojeto'],
        //));
        $resultado = $this->_db->fetchRow($sql, array(
            'idprojeto' => $params['idprojeto'],
            'idpessoa' => $idpessoa,
        ));
        return $resultado;
    }


}