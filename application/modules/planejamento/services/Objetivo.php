<?php

class Planejamento_Service_Objetivo extends App_Service_ServiceAbstract
{
    protected $_form;

    /**
     *
     * @var Planejamento_Model_Mapper_Objetivo
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Planejamento_Model_Mapper_Objetivo();
    }

    /**
     * @return Planejamento_Form_Objetivo
     */
    public function getForm()
    {
        return $this->_getForm('Planejamento_Form_Objetivo', array('datcadastro', 'idcadastrador'));
    }

    /**
     * @return Planejamento_Form_ObjetivoEditar
     */
    public function getFormEditar()
    {
        $form = $this->_getForm('Planejamento_Form_ObjetivoEditar', array('datcadastro', 'idcadastrador'));
        return $form;
    }

    /**
     * @return Planejamento_Form_ObjetivoPesquisar
     */
    public function getFormPesquisar()
    {
        $form = $this->_getForm('Planejamento_Form_ObjetivoPesquisar', array('datcadastro', 'idcadastrador'));
        return $form;
    }

    public function inserir($dados)
    {
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Planejamento_Model_Objetivo($form->getValues());
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
            $model = new Planejamento_Model_Objetivo($form->getValues());
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
