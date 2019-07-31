<?php

class Pessoal_Service_Atividade extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Pessoal_Model_Mapper_Atividade
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Pessoal_Model_Mapper_Atividade();
    }

    /**
     * @return Pessoal_Form_Atividade
     */
    public function getForm()
    {
        return $this->_getForm('Pessoal_Form_Atividade');
    }

    /**
     * @return Pessoal_Form_AtividadePesquisar
     */
    public function getFormPesquisar()
    {
        $form = $this->_getForm('Pessoal_Form_AtividadePesquisar');
        return $form;
    }

    /**
     * @return Pessoal_Form_AtividadeEditar
     */
    public function getFormEditar()
    {
        $form = $this->_getForm('Pessoal_Form_AtividadeEditar');
        return $form;
    }

    /**
     * @return Pessoal_Form_AtividadeRelatorio
     */
    public function getFormRelatorio()
    {
        $form = $this->_getForm('Pessoal_Form_AtividadeRelatorio');
        return $form;
    }


    public function inserir($dados)
    {
        //Zend_Debug::dump($dados); exit;
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Pessoal_Model_Atividade($form->getValues());
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
            $model = new Pessoal_Model_Atividade($form->getValues());
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
     * @return \Atividade_Service_JqGrid | array
     */
    public function pesquisar($params, $paginator)
    {
        $dados = $this->_mapper->pesquisar($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        }
        return $dados;
    }

    /*public function pesquisarRelatorio($params){
        return $this->_mapper->pesquisarRelatorio($params);
    }*/

    public function fetchPairsPercentual()
    {
        return $this->_mapper->fetchPairsPercentual();
    }

    public function initCombo($objeto, $msg)
    {

        $listArray = array();
        $listArray = array('' => $msg);

        foreach ($objeto as $val => $desc) {
            if ($desc != $msg) {
                $listArray[$val] = $desc;
            }
        }
        return $listArray;
    }
}


