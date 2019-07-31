<?php

class Processo_Service_PAcao extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Processo_Model_Mapper_PAcao
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Processo_Model_Mapper_PAcao();
    }

    /**
     * @return Processo_Form_PAcao
     */
    public function getForm()
    {
        return $this->_getForm('Processo_Form_PAcao', array('datcadastro', 'idcadastrador'));
    }

    /**
     * @return Processo_Form_PAcaoEditar
     */
    public function getFormEditar()
    {
        $form = $this->_getForm('Processo_Form_PAcaoEditar', array('datcadastro', 'idcadastrador'));
        return $form;
    }

    /**
     * @return Processo_Form_PAcaoPesquisar
     */
    public function getFormPesquisar()
    {
        $form = $this->_getForm('Processo_Form_PAcaoPesquisar', array('datcadastro', 'idcadastrador'));
        return $form;
    }

    public function inserir($dados)
    {
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Processo_Model_PAcao($form->getValues());
            $retorno = $this->_mapper->insert($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function update($dados)
    {
        $form = $this->getFormEditar();
        if ($form->isValid($dados)) {
            $model = new Processo_Model_PAcao($form->getValues());
            //Zend_Debug::dump($model); exit;
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    public function getByIdDetalhar($dados)
    {
        return $this->_mapper->getByIdDetalhar($dados);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Processo_Service_JqGrid | array
     */
    public function pesquisar($params, $paginator)
    {
        $dados = $this->_mapper->pesquisar($params, $paginator);
        //Zend_Debug::dump($dados);exit;
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    public function fetchPairs()
    {
        return $this->_mapper->fetchPairs();
    }

}