<?php

class VersaoController extends Zend_Controller_Action
{

    public function init()
    {

    }

    public function indexAction()
    {
        $service = new Default_Service_Versao();
        $resultado = new stdClass();
        $resultado = $service->mostrarTodasVersos();
        $this->view->html = $resultado;
    }


}

