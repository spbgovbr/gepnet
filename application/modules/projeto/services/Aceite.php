<?php

class Projeto_Service_Aceite extends App_Service_ServiceAbstract
{

    protected $_form;
    /**
     *
     * @var Projeto_Model_Mapper_Aceite
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_Aceite();
    }

    public function getForm($params)
    {
        $serviceAtivCronograma = new Projeto_Service_AtividadeCronograma();
        $fetchPairEntrega = $serviceAtivCronograma->fetchPairsEntrega($params);
        $arrayEntrega = $this->initCombo($fetchPairEntrega, 'Selecione');
        $form = $this->_getForm('Projeto_Form_Aceite');
        $form->getElement('identrega')->setMultiOptions($arrayEntrega);

        return $form;
    }


    public function retornaAceites($params, $paginator)
    {
        $dados = $this->_mapper->retornaPorProjeto($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);

            return $service;
        }
        return $dados;
    }

    public function inserir($dados)
    {

        $form = $this->getForm($dados);

        if ($form->isValid($dados)) {
            $modelAceite = new Projeto_Model_Aceite($form->getValues());

            $retorno = $this->_mapper->insert($modelAceite);

            return $retorno;
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }

    public function editar($dados)
    {

        $form = $this->getForm(array('idprojeto' => $dados['idprojeto']));
        if ($form->isValid($dados)) {
            $model = new Projeto_Model_Aceite($form->getValues());
            $retorno = $this->_mapper->update($model);
            return ($retorno ? true : false);
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function detalhar($params)
    {

    }

    public function excluir($dados)
    {
        return $this->_mapper->delete($dados);
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

    public function getErrors()
    {
        return $this->errors;
    }
}