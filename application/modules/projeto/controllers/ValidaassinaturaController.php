<?php

class Projeto_ValidaassinaturaController extends Zend_Controller_Action
{
    public function init()
    {
        $servicePerfilPessoa = new Default_Service_Perfilpessoa();
        $dadosEntrada = array(
            "idprojeto" => $this->_request->getParam('idprojeto'),
            "controller" => strtolower($this->_request->getControllerName()),
            "action" => strtolower($this->_request->getActionName()),
        );
        if (!$servicePerfilPessoa->isValidaControllerAction($dadosEntrada)) {
            $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => 'Acesso negado...'));
            $this->_helper->_redirector->gotoSimpleAndExit('forbidden', 'error', 'projeto');
        }
    }

    public function autenticarAction()
    {
        $params = $this->_getAllParams();
        if (!empty($params['idprojeto'])) {
            $service = new Projeto_Service_Validaassinatura();
            $form = $service->getForm();


        }
    }

}