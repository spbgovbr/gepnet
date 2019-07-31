<?php

class Pesquisa_Service_HistoricoPublicacao extends App_Service_ServiceAbstract
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
        $this->_mapper = new Pesquisa_Model_Mapper_HistoricoPublicacao();
    }

    public function getFormPesquisar()
    {
        $this->_form = new Pesquisa_Form_HistoricoPesquisar();
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function retornaHistoricoPesquisasGrid($params)
    {
        try {
            $dados = $this->_mapper->getHistoricoPublicacao($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function inserirHistoricoPublicacao($params)
    {
        $historico = new Pesquisa_Model_HistoricoPublicacao();

        if (isset($params['dtencerramento']) && $params['dtencerramento']) {
            $historico->datencerramento = $params['dtencerramento'];
        }
        if (isset($params['datpublicacao']) && $params['datpublicacao']) {
            $historico->datpublicacao = $params['datpublicacao'];
        }
        $historico->idpesquisa = $params['idpesquisa'];
        $historico->idpesencerrou = $params['idpesencerra'];
        $historico->idpespublicou = $params['idpespublica'];

        $idhistorico = $this->_mapper->insert($historico);
        return $idhistorico;
    }
}
