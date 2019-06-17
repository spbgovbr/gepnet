<?php

use Default_Service_Log as Log;

class Diagnostico_Service_QuestionarioDiagnosticoRespondido extends App_Service_ServiceAbstract
{

    /**
     *
     * @var Diagnostico_Model_Mapper_QuestionarioDiagnosticoRespondido
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
        $this->_mapper = new Diagnostico_Model_Mapper_QuestionarioDiagnosticoRespondido();
        $this->auth = $login->retornaUsuarioLogado();
    }

    public function gerarNumeroSequencial($params)
    {
        try {
            $model = new Diagnostico_Model_QuestionarioDiagnosticoRespondido();
            $model->iddiagnostico = (int)$params['iddiagnostico'];
            $model->idquestinario = (int)$params['idquestionariodiagnostico'];
            $model->idpessoaresposta = (int)$this->auth->idpessoa;
            $retorno = $this->_mapper->insert($model);
            return $retorno;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            $retorno = false;
        }

    }


}