<?php

class Pesquisa_Service_Resposta extends App_Service_ServiceAbstract
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
        $this->_mapper = new Pesquisa_Model_Mapper_Resposta();
    }

    public function getFormResposta()
    {
        $this->_form = new Pesquisa_Form_Resposta();
        return $this->_form;
    }

    public function getFormPesquisar()
    {
        $this->_form = new Pesquisa_Form_RespostaPesquisar();
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Retorna as Respostas cadastradas
     *
     * @param array $params - parametros do request
     * @return boolean|\App_Service_JqGrid
     */
    public function retornaRespostasGrid($params = null)
    {
        try {
            $dados = $this->_mapper->retornaRespostasToGrid($params);
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
        try {
            $resposta = $this->_mapper->getByIdDetalhar($params);
            return $resposta;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function getByIdPerguntaDetalhar($params)
    {
        try {
            $resposta = $this->_mapper->getByIdPerguntaDetalhar($params);
            return $resposta;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function getById($params)
    {
        try {
            $resposta = $this->_mapper->getById($params);
            return $resposta;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function insert($dados)
    {
        $form = $this->getFormResposta();

        if ($form->isValid($dados)) {
            $model = new Pesquisa_Model_Resposta();
            $model->setFromArray($form->getValidValues($dados));
            $model->idcadastrador = $this->auth->idpessoa;
            $model->idescritorio = $this->auth->perfilAtivo->idescritorio;

            $adapter = $this->_mapper->getDbTable()->getAdapter();

            try {
                $adapter->beginTransaction();
                //salva a resposta
                $model->idresposta = $this->_mapper->insert($model);

                //salva tb_respostafrase
                $mapperRespostaFrase = new Pesquisa_Model_Mapper_RespostaFrase();
                $modelRespostaFrase = new Pesquisa_Model_RespostaFrase();
                $modelRespostaFrase->idfrase = $dados['idfrase'];
                $modelRespostaFrase->idresposta = $model->idresposta;

                $mapperRespostaFrase->insert($modelRespostaFrase);

                $adapter->commit();
                return $model;
            } catch (Exception $exc) {
                $adapter->rollBack();
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
        $form = $this->getFormResposta();
        if ($form->isValid($params)) {
            $model = new Pesquisa_Model_Resposta($form->getValidValues($params));

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
        $adapter = $this->_mapper->getDbTable()->getAdapter();
        try {
            $adapter->beginTransaction();

            $mapperRespostaFrase = new Pesquisa_Model_Mapper_RespostaFrase();
            $mapperRespostaFrase->delete($params);
            $result = $this->_mapper->delete($params);

            $adapter->commit();
            return $result;
        } catch (Zend_Db_Statement_Exception $exc) {
            if ($exc->getCode() == 23503) {
                $this->errors = App_Service_ServiceAbstract::ERRO_VIOLACAO_FK_CODE_23503;
            }
        } catch (Exception $exc) {
            $adapter->rollBack();
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

}
