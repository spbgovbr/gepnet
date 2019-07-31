<?php

class Diagnostico_EstatisticaController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('resumo', 'json')
            ->initContext();

        $servicePerfilPessoa = new Default_Service_Perfilpessoa();
        $dadosEntrada = array(
            "module" => strtolower($this->_request->getModuleName()),
            "controller" => strtolower($this->_request->getControllerName()),
            "action" => strtolower($this->_request->getActionName()),
        );
//        Zend_Debug::dump($dadosEntrada);die;

        if (!$servicePerfilPessoa->isValidaControllerActionDiagnostico($dadosEntrada)) {
            $this->_helper->_flashMessenger->addMessage(array(
                    'status' => 'error',
                    'message' => 'Acesso negado...'
                )
            );
            $this->_helper->_redirector->gotoSimpleAndExit('forbidden', 'error', 'diagnostico');
        }


    }

    public function resumoAction()
    {
        $resumo = new Diagnostico_Service_Estatistica();
        $params = $this->_getAllParams();
        $this->view->arrayDiagnostico = $resumo->listaDiagnosticoAll();
        $resumo = $resumo->resumo($params);
        $success = true;

        if ($this->_request->isXmlHttpRequest()) {
            $response = new stdClass();
            $this->view->dados = $resumo;
            $this->view->success = $success;
        }
    }

}
