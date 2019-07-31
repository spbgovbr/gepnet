<?php

class Pesquisa_Service_QuestionariofrasePesquisa extends App_Service_ServiceAbstract
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
        $this->_mapper = new Pesquisa_Model_Mapper_QuestionariofrasePesquisa();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function inserirQuestionarioFrasePesquisa($params, $idquestionariopesquisa, $idfrasepesquisa)
    {
        $questionarioFrase = new Pesquisa_Model_QuestionariofrasePesquisa();

        $questionarioFrase->idquestionariopesquisa = $idquestionariopesquisa;
        $questionarioFrase->idfrasepesquisa = $idfrasepesquisa;
        $questionarioFrase->numordempergunta = $params['tqf_mumordempergunta'];
        $questionarioFrase->obrigatoriedade = $params['tqf_obrigatoriedade'];
        $questionarioFrase->idcadastrador = $params['tqf_idcadastrador'];
        $questionarioFrase->datcadastro = $params['tqf_datcadastro'];
        return $this->_mapper->insert($questionarioFrase);
    }
}
