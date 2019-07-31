<?php

class Pesquisa_Service_Questionario extends App_Service_ServiceAbstract
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
        $this->_mapper = new Pesquisa_Model_Mapper_Questionario();
    }

    public function getFormQuestionario()
    {
        $this->_form = new Pesquisa_Form_Questionario();
        return $this->_form;
    }

    public function getFormPesquisar()
    {
        $this->_form = new Pesquisa_Form_QuestionarioPesquisar();
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
            $dados = $this->_mapper->retornaQuestionariosToGrid($params);
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
        $questionario = $this->_mapper->getByIdDetalhar($params);
        return $questionario;
    }

    public function getById($params)
    {
        $questionario = $this->_mapper->getById($params);
        return $questionario;
    }

    public function getByIdAndEscritorio($params)
    {
        $questionario = $this->_mapper->getByIdAndEscritorio($params);
        return $questionario;
    }

    public function insert($dados)
    {
        $form = $this->getFormQuestionario();

        if ($form->isValid($dados)) {
            $model = new Pesquisa_Model_Questionario();
            $model->setFromArray($form->getValidValues($dados));
            $model->idcadastrador = $this->auth->idpessoa;
            $model->idescritorio = $this->auth->perfilAtivo->idescritorio;
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
        $form = $this->getFormQuestionario();
        if ($form->isValid($params)) {
            $model = new Pesquisa_Model_Questionario($form->getValidValues($params));
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

    /**
     * Altera o status de disponibilidade do questionario.
     *
     * @param type $params
     * @return boolean
     */
    public function alterarDisponibilidade($params)
    {
        try {
            $dataQuestionario = $this->_mapper->getByIdAndEscritorio($params);
            $modelQuestionario = new Pesquisa_Model_Questionario($dataQuestionario);

            if ($modelQuestionario->disponivel == Pesquisa_Model_Questionario::DISPONILVEL) {
                $modelQuestionario->disponivel = Pesquisa_Model_Questionario::INDISPONILVEL;
            } else {
                $modelQuestionario->disponivel = Pesquisa_Model_Questionario::DISPONILVEL;
            }

            $questionario = $this->_mapper->updateDisponibilidade($modelQuestionario);
            return $questionario;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }
}
