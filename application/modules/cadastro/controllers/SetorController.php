<?php

class Cadastro_SetorController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('add', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        $service = new Cadastro_Service_Setor();
        $form = $service->getFormPesquisar();
        $this->view->form = $form;

    }

    public function detalharAction()
    {
        $service = new Cadastro_Service_Setor();
        $this->view->setor = $service->getById($this->_request->getParams());
    }

    public function addAction()
    {
        $service = new Cadastro_Service_Setor();
        $form = $service->getForm();
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $setor = $service->inserir($dados);
            if ($setor) {
                $success = true;
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
                    $this->_helper->_redirector->gotoSimpleAndExit('add', 'setor', 'cadastro');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('index', 'setor', 'cadastro');
            }
        }
    }

    public function editarAction()
    {
        $service = new Cadastro_Service_Setor();
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $setor = $service->update($dados);
            if ($setor) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $setor = $service->getById($this->_request->getParams());
            $form->populate($setor->formPopulate());
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
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'setor', 'cadastro');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function pesquisarjsonAction()
    {
        $service = new Cadastro_Service_Setor();
        $resultado = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado->toJqgrid());
    }


}
