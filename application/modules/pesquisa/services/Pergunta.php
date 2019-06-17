<?php

class Pesquisa_Service_Pergunta extends App_Service_ServiceAbstract
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
        $this->_mapper = new Pesquisa_Model_Mapper_Frase();
    }

    public function getFormPergunta()
    {
        $this->_form = new Pesquisa_Form_Pergunta();
        return $this->_form;
    }

    public function getFormPesquisar()
    {
        $this->_form = new Pesquisa_Form_PerguntaPesquisar();
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Retorna as Perguntas cadastradas
     *
     * @param array $params - parametros do request
     * @return boolean|\App_Service_JqGrid
     */
    public function retornaPerguntasGrid($params = null)
    {
        try {
            $dados = $this->_mapper->retornaPerguntasToGrid($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function getByIdDetalhar($params)
    {
        $pergunta = $this->_mapper->getByIdDetalhar($params);
        return $pergunta;
    }

    public function getById($params)
    {
        $pergunta = $this->_mapper->getById($params);
        return $pergunta;
    }

    public function insert($dados)
    {
        $form = $this->getFormPergunta();

        if ($form->isValid($dados)) {
            $model = new Pesquisa_Model_Frase();
            $model->setFromArray($form->getValidValues($dados));
            $model->idcadastrador = $this->auth->idpessoa;
            $model->idescritorio = $this->auth->perfilAtivo->idescritorio;
            try {
                $model->idpergunta = $this->_mapper->insert($model);
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
        $form = $this->getFormPergunta();
        if ($form->isValid($params)) {
            $model = new Pesquisa_Model_Frase($form->getValidValues($params));
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

//    public function excluir($params)
//    {
//        try {
//            return $this->_mapper->delete($params);
//        } catch ( Zend_Db_Statement_Exception $exc ) {
//            if ( $exc->getCode() == 23503 ) {
//                $this->errors = App_Service_ServiceAbstract::ERRO_VIOLACAO_FK_CODE_23503;
//            }
//        } catch ( Exception $exc ) {
//            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
//            return false;
//        }
//    }

}
