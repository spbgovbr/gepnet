<?php

class Pesquisa_Service_RespostaPesquisa extends App_Service_ServiceAbstract
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
        $this->_mapper = new Pesquisa_Model_Mapper_RespostaPesquisa();
        $this->_mapperRepostaFrase = new Pesquisa_Model_Mapper_RespostaFrasePesquisa();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function inserirRespostaPesquisa($params)
    {
        $respostaPesquisa = new Pesquisa_Model_RespostaPesquisa();

        $respostaPesquisa->desresposta = $params['tr_desresposta'];
        $respostaPesquisa->flaativo = $params['tr_flaativo'];
        $respostaPesquisa->numordem = $params['tr_numordem'];
        $respostaPesquisa->datcadastro = $params['tr_datcadastro'];
        $respostaPesquisa->idcadastrador = $params['tr_idcadastrador'];

        $idrespostapesquisa = $this->_mapper->insert($respostaPesquisa);
        return $idrespostapesquisa;
    }

    public function inserirRespostaFrasePesquisa($params)
    {
        $respostaFrasePesquisa = new Pesquisa_Model_RespostaFrasePesquisa();

        $respostaFrasePesquisa->idfrasepesquisa = $params['idfrasepesquisa'];
        $respostaFrasePesquisa->idrespostapesquisa = $params['idrespostapesquisa'];
        return $this->_mapperRepostaFrase->insert($respostaFrasePesquisa);
    }
}
