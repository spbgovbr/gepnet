<?php

class Planejamento_Service_Acao extends App_Service_ServiceAbstract
{
    protected $_form;

    /**
     *
     * @var Planejamento_Model_Mapper_Acao
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Planejamento_Model_Mapper_Acao();
    }

    /**
     * @return Planejamento_Form_Acao
     */
    public function getForm()
    {
        return $this->_getForm('Planejamento_Form_Acao', array('datcadastro', 'idcadastrador'));
    }

    /**
     * @return Planejamento_Form_AcaoEditar
     */
    public function getFormEditar()
    {
        $form = $this->_getForm('Planejamento_Form_AcaoEditar', array('datcadastro', 'idcadastrador'));
        return $form;
    }

    /**
     * @return Planejamento_Form_AcaoPesquisar
     */
    public function getFormPesquisar()
    {
        $form = $this->_getForm('Planejamento_Form_AcaoPesquisar', array('datcadastro', 'idcadastrador'));
        return $form;
    }

    public function inserir($dados)
    {
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Planejamento_Model_Acao($form->getValues());
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
            $model = new Planejamento_Model_Acao($form->getValues());
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

    public function getByObjetivo($dados)
    {
        return $this->_mapper->getByObjetivo($dados);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function fetchPairsByObjetivo($params)
    {
        return $this->_mapper->fetchPairsByObjetivo($params);
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
