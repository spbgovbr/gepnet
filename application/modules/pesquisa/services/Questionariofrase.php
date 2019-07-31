<?php

class Pesquisa_Service_Questionariofrase extends App_Service_ServiceAbstract
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
        $this->_mapper = new Pesquisa_Model_Mapper_Questionariofrase();
    }

    public function getFormPesquisar()
    {
        $this->_form = new Pesquisa_Form_QuestionarioFrasePesquisar();
        return $this->_form;
    }

    public function getFormQuestionarioFrase()
    {
        $this->_form = new Pesquisa_Form_QuestionarioFrase();
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Retorna as Questionarios cadastradas
     *
     * @param array $params - parametros do request
     * @return boolean|\App_Service_JqGrid
     */
    public function retornaQuestionarioGrid($params = null)
    {
        try {
            if (isset($params['operacao']) && $params['operacao'] == 'vincular') {
                $questionariofrase = new Pesquisa_Model_Mapper_Questionariofrase();
                $dados = $questionariofrase->retornaVincularPerguntasToGrid($params);
            } else {
                $dados = $this->_mapper->retornaPerguntasToGrid($params);
            }
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function getAllByIdQuestionario($params)
    {
        $quetionarioFrase = $this->_mapper->getAllByIdQuestionario($params);
        return $quetionarioFrase;
    }

    public function getById($params)
    {
        $questionarioFrase = $this->_mapper->getById($params);
        return $questionarioFrase;
    }

    public function getByIdDetalhar($params)
    {
        $questionarioFrase = $this->_mapper->getByIdDetalhar($params);
        return $questionarioFrase;
    }

    public function insert($dados)
    {
        $form = $this->getFormQuestionarioFrase();

        if ($form->isValid($dados)) {
            $model = new Pesquisa_Model_Questionariofrase();
            $model->setFromArray($form->getValidValues($dados));
            $model->idcadastrador = $this->auth->idpessoa;
            try {
                $model->idquestionario = $this->_mapper->insert($model);
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
        $form = $this->getFormQuestionarioFrase();
        if ($form->isValid($params)) {
            $model = new Pesquisa_Model_Questionariofrase($form->getValidValues($params));
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
            echo 'Linha:' . __LINE__ . '<br/>Arquivo:' . __FILE__ . '<br/>';
            echo($exc->getMessage());
            exit;
            if ($exc->getCode() == 23503) {
                $this->errors = App_Service_ServiceAbstract::ERRO_VIOLACAO_FK_CODE_23503;
            }
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function retornaPerguntasRespostasByQuestionario($params)
    {
        try {
            return $this->_mapper->retornaPerguntasRespostasByQuestionario($params);
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }
}
