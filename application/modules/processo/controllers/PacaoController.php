<?php

class Processo_PacaoController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('add', 'json')
            ->addActionContext('edit', 'json')
            ->initContext();
        //$ajaxContext->addActionContext('pesquisar', 'json')
    }

    public function indexAction()
    {
        $service = App_Service_ServiceAbstract::getService('Processo_Service_PAcao');
        $form = $service->getFormPesquisar();
        $this->view->form = $form;

        $service = App_Service_ServiceAbstract::getService('Processo_Service_Projetoprocesso');
        $projetoprocesso = $service->getByIdDetalhar($this->_request->getParams('idprojetoprocesso'));
        $this->view->projetoprocesso = $projetoprocesso;
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Processo_Service_PAcao');
        $this->view->processo = $service->getById($this->_request->getParams());
    }

    public function addAction()
    {
        $service = App_Service_ServiceAbstract::getService('Processo_Service_PAcao');
        $form = $service->getForm();

        $form->populate($this->_request->getParams('idprojetoprocesso'));

        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $processo = $service->inserir($dados);
            if ($processo) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        }

        $this->view->form = $form;

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
                    $this->_helper->_redirector->gotoSimpleAndExit('processo', 'acao', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function editAction()
    {
        $service = App_Service_ServiceAbstract::getService('Processo_Service_PAcao');
        $form = $service->getFormEditar();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $processo = $service->update($dados);
            if ($processo) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $processo = $service->getById($this->_request->getParams());
//             Zend_Debug::dump($processo); exit;
            $form->populate($processo->formPopulate());
        }

        $this->view->form = $form;

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
                    $this->_helper->_redirector->gotoSimpleAndExit('processo', 'pacao', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function buscarjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Processo_Service_PAcao');
        $resultado = $service->buscar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }

    public function pesquisarjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Processo_Service_PAcao');
        $resultado = $service->pesquisar($this->_request->getParams(), true);
        //Zend_Debug::dump($resultado->toJqgrid());exit;
        $this->_helper->json->sendJson($resultado->toJqgrid());
    }
}
