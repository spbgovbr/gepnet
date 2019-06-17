<?php

class Pesquisa_Service_PerguntaPesquisa extends App_Service_ServiceAbstract
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
        $this->_mapper = new Pesquisa_Model_Mapper_FrasePesquisa();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function inserirFrasePesquisa($params)
    {
        $frasePesquisa = new Pesquisa_Model_FrasePesquisa();

        $frasePesquisa->desfrase = $params['tf_desfrase'];
        $frasePesquisa->domtipofrase = $params['tf_domtipofrase'];
        $frasePesquisa->flaativo = $params['tf_flaativo'];
        $frasePesquisa->datcadastro = $params['tf_datcadastro'];
        $frasePesquisa->idescritorio = $params['tf_idescritorio'];
        $frasePesquisa->idcadastrador = $params['tf_idcadastrador'];

        $idfrase = $this->_mapper->insert($frasePesquisa);
        return $idfrase;
    }
}
