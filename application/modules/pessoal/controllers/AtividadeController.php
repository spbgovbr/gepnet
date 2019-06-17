<?php

class Pessoal_AtividadeController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('add', 'json')
            ->addActionContext('edit', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        //$service = App_Service_ServiceAbstract::getService('Processo_Service_Processo');
        $service = new Pessoal_Service_Atividade();
        $form = $service->getFormPesquisar();
        $this->view->form = $form;

    }

    public function detalharAction()
    {
        $service = new Pessoal_Service_Atividade();
        $this->view->atividade = $service->getByIdDetalhar($this->_request->getParams());
    }

    public function addAction()
    {
        //$service = App_Service_ServiceAbstract::getService('Pessoal_Service_Atividade');
        $service = new Pessoal_Service_Atividade();
        $form = $service->getForm();
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $atividade = $service->inserir($dados);
            if ($atividade) {
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
                    $this->_helper->_redirector->gotoSimpleAndExit('add', 'atividade', 'pessoal');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('add', 'atividade', 'pessoal');
            }
        }
    }

    public function editAction()
    {
        $service = new Pessoal_Service_Atividade();
        $form = $service->getFormEditar();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $atividade = $service->update($dados);
            if ($atividade) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $atividade = $service->getById($this->_request->getParams());
            $form->populate($atividade->formPopulate());
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
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'atividade', 'pessoal');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function pesquisarjsonAction()
    {
        $service = new Pessoal_Service_Atividade();
        $resultado = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado->toJqgrid());
    }

    public function relatorioAction()
    {
        $service = new Pessoal_Service_Atividade();
        $form = $service->getFormRelatorio();
        $this->view->form = $form;

    }

    public function imprimirAction()
    {
        $service = new Pessoal_Service_Atividade();
        $this->view->atividade = $service->pesquisarRelatorio($this->_request->getParams());
        $html = $this->view->render('/_partials/atividade-imprimir.phtml');
        $serviceImprimir = new Default_Service_Impressao();
        $serviceImprimir->gerarPdf($html);
        exit;
    }


}
