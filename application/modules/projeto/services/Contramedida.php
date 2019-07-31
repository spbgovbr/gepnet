<?php

class Projeto_Service_Contramedida extends App_Service_ServiceAbstract
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
        $this->_mapper = new Projeto_Model_Mapper_Contramedida();
    }

    public function getFormContramedida()
    {
        $this->_form = new Projeto_Form_Contramedida();
        return $this->_form;
    }

    public function getFormPesquisar()
    {
        $this->_form = new Projeto_Form_ContramedidaPesquisar();
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function retornaContramedidaByRisco($params = null)
    {
        $dados = $this->_mapper->retornaPorRiscoToGrid($params);
        $service = new App_Service_JqGrid();
        $service->setPaginator($dados);
        return $service;
    }

    public function getByIdDetalhar($params)
    {
        $contramedida = $this->_mapper->getByIdDetalhar($params);
        return $contramedida;
    }

    public function getById($params)
    {
        $contramedida = $this->_mapper->getById($params);
        return $contramedida;
    }

    public function copiaContramedidaByRisco($params)
    {
        return $this->_mapper->copiaContramedidaByRisco($params);
    }

    public function insert($dados)
    {
        $form = $this->getFormContramedida();

        if ($form->isValid($dados)) {
            $model = new Projeto_Model_Contramedida();
            $model->setFromArray($form->getValidValues($dados));
            $model->idcadastrador = $this->auth->idpessoa;
            try {
                $model->idcontramedida = $this->_mapper->insert($model);
                return $model;
            } catch (Exception $exc) {
                $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
                return false;
            }
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function update($params)
    {
        $form = $this->getFormContramedida();
        if ($form->isValid($params)) {
            $model = new Projeto_Model_Contramedida($form->getValidValues($params));
            try {
                $retorno = $this->_mapper->update($model);
                return $retorno;
            } catch (Exception $exc) {
                $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
                return false;
            }
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
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

}
