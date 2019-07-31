<?php


class Diagnostico_Service_QuestionarioRespondido extends App_Service_ServiceAbstract
{

    /**
     *
     * @var Diagnostico_Model_Mapper_QuestionarioRespondido
     */
    protected $_mapper;

    /**
     * @var Zend_Auth
     */
    protected $auth;


    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->_mapper = new Diagnostico_Model_Mapper_QuestionarioRespondido();
        $this->auth = $login->retornaUsuarioLogado();
    }

    /**
     * @return Diagnostico_Form_PesquisaQuestionarioRespondido
     */
    public function getFormPesquisaQuestionarioRespondido($params)
    {
        $form = $this->_getForm('Diagnostico_Form_PesquisaQuestionarioRespondido');

        $form->populate(array(
            'iddiagnostico' => (int)$params['iddiagnostico'],
            'tpquestionario' => $params['tpquestionario']
        ));
        return $form;
    }

    public function listaQuestionarioRespondido($params)
    {
        try {
            $dados = $this->_mapper->listarQuestionariosRespondidos($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function retornaQuestionarioRespondido($params)
    {
        return $this->_mapper->retornaQuestionarioRespondido($params);
    }

}