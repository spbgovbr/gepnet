<?php

class Pesquisa_Service_QuestionarioPesquisa extends App_Service_ServiceAbstract
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
        $this->_mapper = new Pesquisa_Model_Mapper_QuestionarioPesquisa();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function inserirQuestionarioPesquisa($params)
    {
        $questionarioPesquisa = new Pesquisa_Model_QuestionarioPesquisa();

        $questionarioPesquisa->nomquestionario = $params['nomquestionario'];
        $questionarioPesquisa->idpesquisa = $params['idpesquisa'];
        $questionarioPesquisa->idescritorio = $params['idescritorio'];
        $questionarioPesquisa->idcadastrador = $params['idcadastrador'];
        $questionarioPesquisa->tipoquestionario = $params['tipoquestionario'];
        $questionarioPesquisa->desobservacao = $params['desobservacao'];
        $questionarioPesquisa->datcadastro = $params['datcadastro'];
        $idquestionariopesquisa = $this->_mapper->insert($questionarioPesquisa);

        return $idquestionariopesquisa;
    }

    public function retornaQuestionarioByPesquisa($params)
    {
        $questionario = $this->_mapper->retornaQuestionarioByPesquisa($params);
        return $questionario;
    }

    public function isDuplicada($params)
    {
        try {
            $pesquisa = $this->_mapper->retornaPesquisaMesmoNome($params);
            if ($pesquisa) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return true;
        }
    }
}
