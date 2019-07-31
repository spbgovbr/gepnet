<?php

class Relatorio_Service_Aceite extends App_Service_ServiceAbstract
{

    public $_mapper = null;
    protected $_form = null;
//    protected $auth = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->_mapper = new Relatorio_Model_Mapper_Aceite();
    }

    public function getFormPesquisar()
    {
        $this->_form = new Relatorio_Form_AceitePesquisar();
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function gerarRelatorio($params)
    {
        try {
            $result = $this->_mapper->relatorioAceite($params);
            return $result;
        } catch (Exception $exception) {
            $this->errors[] = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

}
