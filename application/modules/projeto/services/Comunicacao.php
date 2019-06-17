<?php

class Projeto_Service_Comunicacao extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Comunicacao
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    /**
     * @var Zend_Auth
     */
    public $auth;

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Projeto_Model_Mapper_Comunicacao();
    }

    /**
     * @return Projeto_Form_ComunicacaoInserir
     */
    public function getFormComunicacaoInserir()
    {
        return $this->_getForm('Projeto_Form_ComunicacaoInserir');
    }

    /**
     * @return Projeto_Form_ComunicacaoEdit
     */
    public function getFormComunicacaoEdit()
    {
        return $this->_getForm('Projeto_Form_ComunicacaoEdit');
    }

    /**
     * @return Projeto_Form_ComunicacaoPesquisar
     */
    public function getFormComunicacaoPesquisar()
    {
        return $this->_getForm('Projeto_Form_ComunicacaoPesquisar', null);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getComunicacaoById($params)
    {
        return $this->_mapper->getById($params);
    }

    public function getComunicacaoByIdParteInteressada($params)
    {
        return $this->_mapper->getByIdFromParteInteressada($params);
    }

    public function getByIdComunicacaoProjetoResponsavel($params)
    {
        return $this->_mapper->getByIdComunicacaoProjetoResponsavel($params);
    }

    public function updateComunicacaoByProjetoResponsavel($params)
    {
        return $this->_mapper->updateComunicacaoByProjetoResponsavel($params);
    }

    public function excluirComunicacaoByProjetoResponsavel($params)
    {
        return $this->_mapper->excluirComunicacaoByProjetoResponsavel($params);
    }

    public function insert($data)
    {
        $form = $this->getFormComunicacaoInserir();

        if ($form->isValid($data)) {
            $model = new Projeto_Model_Comunicacao($form->getValues());
            $model->setDatcadastro();
            $model->setIdcadastrador($this->auth->idpessoa);
            $result = $this->_mapper->insert($model);
            return $result;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function update($data)
    {
        $form = $this->getFormComunicacaoEdit();

        if ($form->isValid($data)) {
            $model = new Projeto_Model_Comunicacao($form->getValues());
            $model->setIdcadastrador($this->auth->idpessoa);
            $result = $this->_mapper->update($model);
            return $result;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function delete($params)
    {
        return $this->_mapper->delete($params);
    }

    public function retornaPorProjeto($params)
    {
        return $this->_mapper->retornaPorProjeto($params);
    }

    public function retornaComunicacaoPorIdProjeto($params)
    {
        return $this->_mapper->retornaComunicacaoPorIdProjeto($params);
    }

    public function getGridComunicacao($params, $paginator)
    {
        $result = $this->_mapper->retornaPorProjetoToGrid($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($result);
            return $service;
        }
        return $result;
    }

}
