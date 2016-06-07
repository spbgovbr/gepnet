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
            //Zend_Debug::dump($model);exit;
            $this->_mapper->insert($model);
            return $model;
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
        return false;
    }

    /**
     *
     * @param array $dados
     */
    public function excluir($dados)
    {
        try {
            //$model = new Default_Model_Gerencia($dados);
            return $this->_mapper->excluir($dados);
        } catch ( Exception $exc ) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }
    public function excluirPorProjeto($dados)
    {
        try {
            //$model = new Default_Model_Gerencia($dados);
            return $this->_mapper->excluirPorProjeto($dados);
        } catch ( Exception $exc ) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
    
    public function retornaPorAtividade($params)
    {
        return $this->_mapper->retornaPorAtividade($params);
    }
    public function retornaTodasPredecessorasPorIdAtividade($params)
    {
        return $this->_mapper->retornaTodasPredecessorasPorIdAtividade($params);
    }
    public function retornaDataMaiorPredecessora($params)
    {
        return $this->_mapper->retornaDataMaiorPredecessora($params);
    }
    
}
?>

