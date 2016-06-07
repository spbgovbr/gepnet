<?php

class Projeto_Service_Licao extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Licao
     */
    protected $_mapper;

    /**
     * @var array 
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_Licao();
    }

    /**
     * @return Projeto_Form_Licao
     */
    public function getForm($params)
    {
        $serviceAtivCronograma = new Projeto_Service_AtividadeCronograma();
        $fetchPairEntrega = $serviceAtivCronograma->fetchPairsEntrega($params);
        $form = $this->_getForm('Projeto_Form_Licao');
        $form->getElement('identrega')->setMultiOptions($fetchPairEntrega);
        return $form;
    }

    public function getFormPesquisar($params)
    {
        $serviceAtivCronograma = new Projeto_Service_AtividadeCronograma();
        $fetchPairEntrega = $serviceAtivCronograma->fetchPairsEntrega($params);
        $form = $this->_getForm('Projeto_Form_Licao');
        $form->getElement('identrega')->setMultiOptions($fetchPairEntrega)
                                      ->setAttribs(array('class' => 'span3', 'data-rule-required' => false))
                                      ->setRequired(false);
        $form->getElement('submit')->setLabel('Pesquisar');
        return $form;
    }

    public function inserir($dados)
    {
        $form = $this->getForm($dados);
        if ( $form->isValid($dados) ) {
             $model     = new Projeto_Model_Licao($dados);
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
        $form = $this->getForm($dados);
        if ( $form->isValid($dados) ) {
            $model   = new Projeto_Model_Licao($form->getValues());
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function excluir($dados)
    {
        return $this->_mapper->delete($dados);
    }

    /**
     * 
     * @param array $params
     * @param boolean $paginator
     * @return \App_Service_JqGrid | array
     */
    public function pesquisar($params, $paginator)
    {
        $dados = $this->_mapper->pesquisar($params, $paginator);
        if ( $paginator ) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        }
        return $dados;
    }

    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }

    public function detalhar($params)
    {
        return $this->_mapper->getById($params);
    }
    public function retornaLicaoPorProjeto($idprojeto)
    {
        return $this->_mapper->retornaLicaoPorProjeto($idprojeto);
    }

    public function retornaLicoes($params, $paginator)
    {
        $dados = $this->_mapper->retornaPorProjeto($params, $paginator);
        if ( $paginator ) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        }
        return $dados;
    }

    public function getErrors()
    {
        return $this->errors;
    }

}

