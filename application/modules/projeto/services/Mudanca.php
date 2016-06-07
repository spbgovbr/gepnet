<?php

class Projeto_Service_Mudanca extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Mudanca
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
        $this->_mapper = new Projeto_Model_Mapper_Mudanca();
    }

    /**
     * @return Projeto_Form_Mudanca
     */
    public function getForm()
    {
        return $this->_getForm('Projeto_Form_Mudanca');
    }

        //put your code here
    public function editar($dados)
    {
        $form = $this->getForm();

        if ( $form->isValid($dados) ) {
            $model        = new Projeto_Model_Mudanca($form->getValues());
            return $this->_mapper->update($model);
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }
    
    public function inserir($dados)
    {
        $form = $this->getForm();

        if ( $form->isValid($dados) ) {
            $model        = new Projeto_Model_Mudanca($form->getValues());
            return $this->_mapper->insert($model);
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function excluir($dados)
    {
        return $this->_mapper->delete($dados);
    }

    /**
     * 
     * @param array $dados
     */
    public function update($dados)
    {
        try {
            return $this->_mapper->update($dados);
        } catch ( Exception $exc ) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function pesquisar($params, $paginator)
    {
        $dados = $this->_mapper->retornaPorProjeto($params, $paginator);
        if ( $paginator ) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        }
        return $dados;
    }
    
    public function getById($params){
       return $this->_mapper->getById($params);
    }
    
    public function retornaPorProjeto($dados)
    {
    	return $this->_mapper->retornaPorProjeto($dados);
    }
    
    public function fetchPairsTipoMudanca(){
         $mapper = new Projeto_Model_Mapper_Tipomudanca();
         return $mapper->fetchPairs();
    }
    
    public function initCombo($objeto, $msg) {

        $listArray = array();
        $listArray = array('' => $msg);

        foreach ($objeto as $val => $desc) {
            if ($desc != $msg) {
                $listArray[$val] = $desc;
            }
        }
        return $listArray;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}