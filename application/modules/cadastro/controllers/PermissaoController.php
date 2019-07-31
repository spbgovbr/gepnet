<?php

class Cadastro_PermissaoController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('editar', 'json')
            ->initContext();
        //$ajaxContext->addActionContext('pesquisar', 'json')
    }

    public function indexAction()
    {
        $service = new Default_Service_Permissao();
        $this->view->form = $service->getFormPerfil();
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Permissao');
        $this->view->permissao = $service->getById($this->_request->getParams());
    }

    public function pesquisarAction()
    {
        $service = new Default_Service_Permissao();
        //if($this->_request->isXmlHttpRequest()){
        $paginator = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function retornaPorRecursoAction()
    {
        $service = new Default_Service_Permissao();
        //if($this->_request->isXmlHttpRequest()){
        $resultado = $service->retornaPorRecurso($this->_request->getParams(), true);
        //Zend_Debug::dump($resultado); exit;

        $this->_helper->json->sendJson($resultado);
    }

    public function editarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Permissao');
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $retorno = $service->editar($dados);
            if ($retorno) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $permissao = $service->getById($this->_request->getParams());
            $form->populate($permissao);
            $this->view->permissao = $permissao;
            $this->view->form = $form;
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'documento', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }
}