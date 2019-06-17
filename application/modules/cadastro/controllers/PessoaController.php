<?php

class Cadastro_PessoaController extends Zend_Controller_Action
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
    }

    public function indexAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $form = $service->getFormPesquisar();
        $this->view->form = $form;
    }

    public function pesquisarjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $paginator = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function pesquisarSemUnidadeAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $paginator = $service->pesquisarSemUnidade($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $this->view->pessoa = $service->getById($this->_request->getParams());
    }

    public function addAction()
    {
        $service = new Default_Service_Pessoa();
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $pessoa = $service->inserir($dados);
            if ($pessoa) {
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
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'pessoa', 'cadastro');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function editAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $form = $service->getFormEditar();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $pessoa = $service->update($dados);
            if ($pessoa) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $pessoa = $service->getById($this->_request->getParams());
            $form->populate($pessoa->formPopulate());
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
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'pessoa', 'cadastro');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function importarjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $resultado = $service->importar($this->_request->getParams());
        $this->_helper->json->sendJson($resultado);
    }

    public function gridAction()
    {
        if ($this->_request->getParam('agenda')) {
            $this->view->agenda = 1;
        }
    }

    public function buscarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $paginator = $service->buscar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }
}