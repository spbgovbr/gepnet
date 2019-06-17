<?php

class Evento_AvaliacaoservidorController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('cadastrar', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        $service = new Evento_Service_Avaliacaoservidor();
        $form = $service->getFormPesquisar();
        $this->view->form = $form;

    }

    public function detalharAction()
    {
        $service = new Evento_Service_Avaliacaoservidor();
        $this->view->avaliacao = $service->getById($this->_request->getParams());
        $this->view->perguntas = $service->getPerguntas();
    }

    public function cadastrarAction()
    {
        $service = new Evento_Service_Avaliacaoservidor();
        $this->view->perguntas = $service->getPerguntas();
        $serviceLogin = new Default_Service_Login();
        $usuario = $serviceLogin->retornaUsuarioLogado();
        $this->view->nomavaliador = $usuario->nompessoa;
        $form = $service->getForm();
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $evento = $service->inserir($dados);
            if ($evento) {
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
                    $this->_helper->_redirector->gotoSimpleAndExit('cadastrar', 'avaliacaoservidor', 'evento');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('cadastrar', 'avaliacaoservidor', 'evento');
            }
        }
    }

    public function editarAction()
    {
        $service = new Evento_Service_Avaliacaoservidor();
        $form = $service->getFormEditar();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $evento = $service->update($dados);
            if ($evento) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $evento = $service->getById($this->_request->getParams());
            $this->view->perguntas = $service->getPerguntas();
            $this->view->dadosBanco = $evento->formPopulate();
            //var_dump($evento->formPopulate());
            $form->populate($evento->formPopulate());
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
                    $this->_helper->_redirector->gotoSimpleAndExit('editar', 'avaliacaoservidor', 'evento');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function pesquisarjsonAction()
    {
        $service = new Evento_Service_Avaliacaoservidor();
        $resultado = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado->toJqgrid());
    }


}
