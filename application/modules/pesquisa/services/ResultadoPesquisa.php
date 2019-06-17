<?php

class Pesquisa_Service_ResultadoPesquisa extends App_Service_ServiceAbstract
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
        $this->_mapper = new Pesquisa_Model_Mapper_ResultadoPesquisa();
    }

    public function getFormPesquisar($params)
    {
        $this->_form = new Pesquisa_Form_ResultadoPesquisa();
        return $this->_form->populate($params);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Retorna lista de pesquisas respondidas
     *
     * @param array $params - parametros do request
     * @return boolean|\App_Service_JqGrid
     */
    public function retornaResultadoPesquisaGrid($params)
    {
        try {
            $dados = $this->_mapper->retornaResultadoPesquisaGrid($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function retornaResultadoByPessoa($params)
    {
        try {
            return $this->_mapper->retornaResultadoByPessoa($params);
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function relatorioPercentual($params)
    {
        try {
            return $this->_mapper->retornaTotalRespostaQuestoesByPesquisa($params);
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function relatorioTabelado($params)
    {
        try {
            return $this->_mapper->retornaPesquisasRespondidasByPesquisa($params);
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function totalRespondidas($params)
    {
        try {
            return $this->_mapper->totalPesquisasRespondidasByIdpesquisa($params);
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function retornaEnunciadoPesquisa($params)
    {
        try {
            $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pesquisa');
            return $service->retornaEnunciadoPesquisa($params);
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function salvarResultadoPesquisa($params)
    {
        $adapter = $this->_mapper->getDbTable()->getAdapter();
        try {
            $adapter->beginTransaction();

            //cada idresultado corresponde a uma pesquisa respondida
            $idresultado = $this->_mapper->maxIdResultado($params);

            $resultadoPesquisa = new Pesquisa_Model_ResultadoPesquisa();
            $mapper = new Pesquisa_Model_Mapper_QuestionarioPesquisa();
            $questionario = $mapper->retornaQuestionarioById($params);

            if ($questionario['tipoquestionario'] == Pesquisa_Model_QuestionarioPesquisa::PUBLICADO_COM_SENHA) {
                $resultadoPesquisa->cpf = $this->auth->cpf;
            }

            $dataCadastro = date('Y-m-d H:i:s');

            foreach ($params as $element => $resposta) {
                //se for o idquestionario pula para o proximo elemento
                if ($element == 'idquestionariopesquisa') {
                    continue;
                }
                //recupera o id da pergunta(frase)
                $fraseId = explode('_', $element);

                //se for (checkbox|select|radio) insere as respectivas respostas
                if (is_array($resposta)) {
                    foreach ($resposta as $valor) {
                        $resultadoPesquisa->idresultado = $idresultado;
                        $resultadoPesquisa->idquestionariopesquisa = $params['idquestionariopesquisa'];
                        $resultadoPesquisa->idfrasepesquisa = $fraseId[1];
                        $resultadoPesquisa->desresposta = $valor;
                        $resultadoPesquisa->datcadastro = $dataCadastro;
                        $this->_mapper->insert($resultadoPesquisa);
                    }
                } else {
                    $resultadoPesquisa->idresultado = $idresultado;
                    $resultadoPesquisa->idquestionariopesquisa = $params['idquestionariopesquisa'];
                    $resultadoPesquisa->idfrasepesquisa = $fraseId[1];
                    $resultadoPesquisa->desresposta = $resposta ?: null;
                    $resultadoPesquisa->datcadastro = $dataCadastro;
                    $this->_mapper->insert($resultadoPesquisa);
                }
            }
            $adapter->commit();
            return true;
        } catch (Exception $exc) {
            $adapter->rollBack();
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function salvarResultadoPesquisaExterna($params)
    {
        $adapter = $this->_mapper->getDbTable()->getAdapter();
        try {
            $adapter->beginTransaction();
            //cada idresultado corresponde a uma pesquisa respondida
            $idresultado = $this->_mapper->maxIdResultado($params);
            $resultadoPesquisa = new Pesquisa_Model_ResultadoPesquisa();
            $mapper = new Pesquisa_Model_Mapper_QuestionarioPesquisa();
            $questionario = $mapper->retornaQuestionarioById($params);

            if ($questionario['tipoquestionario'] == Pesquisa_Model_QuestionarioPesquisa::PUBLICADO_COM_SENHA) {
                $auth = Zend_Auth::getInstance();
                $auth->setStorage(new Zend_Auth_Storage_Session('ldap_pesquisa'));
                $dataUser = $auth->getStorage()->read();
                $resultadoPesquisa->cpf = $dataUser['data_user']['cpf'][0];
            }

            $dataCadastro = date('Y-m-d H:i:s');

            foreach ($params as $element => $resposta) {
                //se for o idquestionario pula para o proximo elemento
                if ($element == 'idquestionariopesquisa') {
                    continue;
                }
                //recupera id o da pergunta(frase)
                $fraseId = explode('_', $element);

                //se for (checkbox|select|radio) insere as respectivas respostas
                if (is_array($resposta)) {
                    foreach ($resposta as $valor) {
                        $resultadoPesquisa->idresultado = $idresultado;
                        $resultadoPesquisa->idquestionariopesquisa = $params['idquestionariopesquisa'];
                        $resultadoPesquisa->idfrasepesquisa = $fraseId[1];
                        $resultadoPesquisa->desresposta = $valor;
                        $resultadoPesquisa->datcadastro = $dataCadastro;
                        $this->_mapper->insert($resultadoPesquisa);
                    }
                } else {
                    $resultadoPesquisa->idresultado = $idresultado;
                    $resultadoPesquisa->idquestionariopesquisa = $params['idquestionariopesquisa'];
                    $resultadoPesquisa->idfrasepesquisa = $fraseId[1];
                    $resultadoPesquisa->desresposta = $resposta ?: null;
                    $resultadoPesquisa->datcadastro = $dataCadastro;
                    $this->_mapper->insert($resultadoPesquisa);
                }
            }

            //apos a insercao limpa o namespace 'ldap_pesquisa'
            $auth = Zend_Auth::getInstance();
            $auth->setStorage(new Zend_Auth_Storage_Session('ldap_pesquisa'));
            $auth->getStorage()->clear();

            $adapter->commit();
            return true;
        } catch (Exception $exc) {
            $adapter->rollBack();
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

}
