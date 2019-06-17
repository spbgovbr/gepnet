<?php

class Projeto_LinhatempoController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('cadastrar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('excluir', 'json')
            ->initContext();
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

    public function indexAction()
    {
        $service = new Projeto_Service_LinhaTempo();
        $this->view->formPesquisar = $service->getFormPesquisar($this->_getParam('idprojeto'));
        $this->view->idprojeto = $this->_getParam('idprojeto');
        $this->view->msgimpressao = $this->_request->getParam('msgimpressao');
    }

    public function pesquisarAction()
    {
        $service = new Projeto_Service_LinhaTempo();
        $paginator = $service->listar($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

}