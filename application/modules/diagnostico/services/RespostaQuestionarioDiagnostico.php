<?php

use Default_Service_Log as Log;

class Diagnostico_Service_RespostaQuestionarioDiagnostico extends App_Service_ServiceAbstract
{

    /**
     *
     * @var Diagnostico_Model_Mapper_RespostaQuestionarioDiagnostico
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
        $this->_mapper = new Diagnostico_Model_Mapper_RespostaQuestionarioDiagnostico();
    }

    public function inserirHistoricoRespostas($params)
    {
        try {
            $retorno = $this->_mapper->insert($params);
            return $retorno;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            $retorno = false;
        }

    }


}