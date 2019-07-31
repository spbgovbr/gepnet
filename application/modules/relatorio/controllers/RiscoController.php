<?php

class Relatorio_RiscoController extends Zend_Controller_Action {

    public function indexAction()
    {
        $service = App_Service_ServiceAbstract::getService('Relatorio_Service_Risco');
        $form = $service->getFormPesquisar();
        
        $request =  $this->getRequest();
        if($request->isPost()) {
            $this->view->relatorio = $service->gerarRelatorio($request->getPost());
            $form->populate($request->getPost());
        }
        $this->view->formPesquisar = $form;
    }
}
