<?php

class Relatorio_Service_Risco extends App_Service_ServiceAbstract
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
//        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Relatorio_Model_Mapper_Risco();
    }

    public function getFormPesquisar()
    {
        $this->_form = new Relatorio_Form_RiscoPesquisar();
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function gerarRelatorio($params)
    {
        try {
            $form = $this->getFormPesquisar();
            if ($form->isValid($params)) {
                $result = $this->_mapper->relatorioRiscos($form->getValidValues($params));
                return $result;
            } else {
                $this->errors = $form->getErrors();
                return false;
            }
        } catch (Exception $exception) {
            $this->errors[] = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

}
