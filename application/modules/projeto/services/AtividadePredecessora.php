<?php

class Projeto_Service_AtividadePredecessora extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Atividadepredecessora
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_Atividadepredecessora();
    }

    /**
     * @return Projeto_Form_AtividadeCronogramaMarco
     */
    public function getForm()
    {
        throw new Exception('metodo nao implementado.');
    }

    public function inserir($dados)
    {

        try {
            $model = new Projeto_Model_Atividadepredecessora($dados);
            $this->_mapper->insert($model);
            return $model;
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
        return false;
    }

    /**
     * REFACTORING: Funcionalidade de excluir atividade
     *
     * @param $dados
     * @return bool
     */
    public function excluir($dados)
    {
        try {
            return $this->_mapper->excluir($dados);
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }


    public function excluirPorAtividade($dados)
    {
        try {
            return $this->_mapper->excluirPorAtividade($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function retornaAtividadeSucessora($params, $array = true)
    {
        return $this->_mapper->retornaAtividadePorPredec($params, $array);
    }

    public function retornaAtividadeCountPredec($params)
    {
        return $this->_mapper->retornaAtividadeCountPredec($params);
    }

    public function retornaAtividadeCountPredecEntrega($params)
    {
        return $this->_mapper->retornaAtividadeCountPredecEntrega($params);
    }

    public function retornaAtividadeCountPredecGrupo($params)
    {
        return $this->_mapper->retornaAtividadeCountPredecGrupo($params);
    }

    public function retornaPorAtividade($params)
    {
        return $this->_mapper->retornaPorAtividade($params);
    }

    public function fetchPairsPorAtividade($params)
    {
        return $this->_mapper->fetchPairsPorAtividade($params);
    }

    public function listaPorAtividade($params)
    {
        return $this->_mapper->listaPorAtividade($params);
    }

    public function retornaMaiorDataPredecessoraByIdAtividade($params)
    {
        return $this->_mapper->retornaMaiorDataPredecessoraByIdAtividade($params);
    }

    public function retornaPredecePorIdAtividade($params)
    {
        return $this->_mapper->retornaPredecePorIdAtividade($params);
    }

    public function isPredecessora($params)
    {
        $total = $this->_mapper->isPredecessora($params);
        return ($total > 0) ? true : false;
    }

    public function pesquisaPredecessoraAtividade($params)
    {
        return $this->_mapper->pesquisaPredecessoraAtividade($params);
    }

    public function addDias($data, $dias)
    {
        $dataRetorna = new Zend_Date($data);
        $dataRetorna->add('' . $dias . '', Zend_Date::DAY);
        return $dataRetorna->toString('d/m/Y');
    }

    private function preparaData($data)
    {
        $dt = explode("/", $data);
        $dataFormatada = $dt[2] . "-" . $dt[1] . "-" . $dt[0];
        $dataRetornada = DateTime::createFromFormat('Y-m-d', $dataFormatada);
        return $dataRetornada;
    }

}