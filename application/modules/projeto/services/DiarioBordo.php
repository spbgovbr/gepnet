<?php

class Projeto_Service_DiarioBordo extends App_Service_ServiceAbstract
{

    public $_mapper = null;
    protected $_form = null;
    protected $auth = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Projeto_Model_Mapper_Diariobordo();
    }

    public function getFormDiario()
    {
        $this->_form = new Projeto_Form_DiarioBordo();
        return $this->_form;
    }

    public function getFormPesquisar()
    {
        $this->_form = new Projeto_Form_DiarioBordoPesquisar();
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function retornaDiarioByProjeto($params = null)
    {
        $dados = $this->_mapper->retornaPorProjetoToGrid($params);
        $service = new App_Service_JqGrid();
        $service->setPaginator($dados);
        return $service;
    }

    public function getById($params)
    {
        $diario = $this->_mapper->getById($params);
        return $diario;
    }

    public function insert($dados)
    {
        $form = $this->getFormDiario();

        if ($form->isValid($dados)) {
            $model = new Projeto_Model_Diariobordo();
            $model->setFromArray($form->getValidValues($dados));
            $model->idcadastrador = $this->auth->idpessoa;
            $model->iddiariobordo = $this->_mapper->insert($model);
            return $model;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function update($params)
    {
        $form = $this->getFormDiario();
        if ($form->isValid($params)) {
            $model = new Projeto_Model_Diariobordo($form->getValidValues($params));
            $model->idalterador = $this->auth->idpessoa;
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function excluir($params)
    {
        try {
            return $this->_mapper->delete($params);
        } catch (Zend_Db_Statement_Exception $exc) {
            if ($exc->getCode() == 23503) {
                $this->errors = App_Service_ServiceAbstract::ERRO_VIOLACAO_FK_CODE_23503;
            }
        } catch (Exception $exc) {
            $this->errors = $exc->getMessage();
            return false;
        }
    }

    public function copiaDiarioByProjeto($dados)
    {
        return $this->_mapper->copiaDiarioByProjeto($dados);
    }

}
