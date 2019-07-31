<?php

class Cadastro_DocumentoController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('cadastrar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('editar-arquivo', 'json')
            ->addActionContext('excluir', 'json')
            ->initContext();
        //$ajaxContext->addActionContext('pesquisar', 'json')
    }

    public function indexAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Documento');
        $form = $service->getFormPesquisar();
        $this->view->form = $form;
    }

    public function pesquisarjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Documento');
        $paginator = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Documento');
        $this->view->documento = $service->getById($this->_request->getParams());
    }

    public function cadastrarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Documento');
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $pessoa = $service->inserir($dados);
            if ($pessoa) {
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
                    $this->_helper->_redirector->gotoSimpleAndExit('cadstrar', 'documento', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function editarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Documento');
        $form = $service->getFormEditar();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $documento = $service->update($dados);
            if ($documento) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $documento = $service->getById($this->_request->getParams());
            $form->populate($documento->formPopulate());
            $this->view->documento = $documento;
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

    public function editarArquivoAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Documento');
        $form = $service->getFormEditarArquivo();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $documento = $service->editarArquivo($dados);
            if ($documento) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $documento = $service->getById($this->_request->getParams());
            $form->populate($documento->formPopulate());
            $this->view->documento = $documento;
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

    public function excluirAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Documento');
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $documento = $service->excluir($dados);
            if ($documento) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $documento = $service->getById($this->_request->getParams());
            $this->view->documento = $documento;
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

    public function abrirAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $service = App_Service_ServiceAbstract::getService('Default_Service_Download');

        $download = $service->getDownloadConfig($this->_request->getParams());
        //Zend_Debug::dump($download); exit;

        $this->getResponse()
            ->clearAllHeaders()
            ->clearBody();
        foreach ($download->headers as $key => $value) {
            if (is_string($key)) {
                $this->getResponse()->setHeader($key, $value, true);
            }
            $this->getResponse()->setHeader($key, $value, true);
        }
        $this->getResponse()->sendHeaders();
        $this->getResponse()->setBody(file_get_contents($download->path));
        $this->getResponse()->sendResponse();
    }
}