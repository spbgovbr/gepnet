<?php

class Acordocooperacao_EntidadeexternaController extends Zend_Controller_Action
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
        $service = new Acordocooperacao_Service_Entidadeexterna();
        $form = $service->getFormPesquisar();
        $this->view->form = $form;

    }

    public function detalharAction()
    {
        $service = new Acordocooperacao_Service_Entidadeexterna();
        $this->view->entidadeexterna = $service->getById($this->_request->getParams());
    }

    public function addAction()
    {
        $service = new Acordocooperacao_Service_Entidadeexterna();
        $form = $service->getForm();
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $entidadeexterna = $service->inserir($dados);
            if ($entidadeexterna) {
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
                    $this->_helper->_redirector->gotoSimpleAndExit('add', 'entidadeexterna', 'acordocooperacao');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('index', 'entidadeexterna', 'acordocooperacao');
            }
        }
    }

    public function editarAction()
    {
        $service = new Acordocooperacao_Service_Entidadeexterna();
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $entidadeexterna = $service->update($dados);
            if ($entidadeexterna) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $entidadeexterna = $service->getById($this->_request->getParams());
            $form->populate($entidadeexterna->formPopulate());
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
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'entidadeexterna', 'acordocooperacao');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function pesquisarjsonAction()
    {
        $service = new Acordocooperacao_Service_Entidadeexterna();
        $resultado = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado->toJqgrid());
    }


}
