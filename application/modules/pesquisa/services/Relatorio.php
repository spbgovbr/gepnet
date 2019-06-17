<?php

class Pesquisa_Service_Relatorio extends App_Service_ServiceAbstract
{

    protected $_form = null;

    /**
     * @var array
     */
    public $errors = array();

    public function getErrors()
    {
        return $this->errors;
    }

    public function getFormPesquisar()
    {
        $form = new Pesquisa_Form_PesquisaPesquisar();
        return $form;
    }

    public function retornaPesquisasRelatorioGrid($params)
    {
        try {
            $pesquisa = new Pesquisa_Model_Mapper_Pesquisa();
            $dados = $pesquisa->retornaPesquisasRelatorioGrid($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

}
