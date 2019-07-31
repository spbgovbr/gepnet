<?php

class Agenda_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('add', 'json')
            ->addActionContext('edit', 'json')
            ->addActionContext('participantes', 'json')
            ->addActionContext('excluirparticipante', 'json')
            ->addActionContext('retornaDiasComEventos', 'json')
            ->initContext();
        //$ajaxContext->addActionContext('pesquisar', 'json')
    }

    public function indexAction()
    {
        $hoje = new DateTime('now');
        $this->view->hoje = $hoje;
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Agenda_Service_Agenda');
        $this->view->agenda = $service->getById($this->_request->getParams(), true);

//        var_dump($this->view->agenda); exit;
    }

    public function addAction()
    {
        $service = App_Service_ServiceAbstract::getService('Agenda_Service_Agenda');
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $agenda = $service->inserir($dados);
//            var_dump($agenda); exit;
            if ($agenda) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        }

        $this->view->escritorio = '';

        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->view->escritorio = $auth->getIdentity()->perfilAtivo->nomescritorio;
            $idescritorio = $auth->getIdentity()->perfilAtivo->idescritorio;
            $form->populate(array('idescritorio' => $idescritorio));
        }

//        Zend_Debug::dump($form->idescritorio ); exit;
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
                $this->view->idagenda = $agenda->idagenda;
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('add', 'index', 'agenda');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('add', 'index', 'agenda');
            }
        }
    }

    public function editAction()
    {
        $service = App_Service_ServiceAbstract::getService('Agenda_Service_Agenda');
//        $servicePessoaAgenda = App_Service_ServiceAbstract::getService('Agenda_Service_PessoaAgenda');
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $agenda = $service->update($dados);
//            var_dump($agenda); exit;
            if ($agenda) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $agenda = $service->getById($this->_request->getParams());
//          Zend_Debug::dump($processo); exit;
            $form->populate($agenda);
        }

        $this->view->form = $form;
        $this->view->escritorio = $agenda['nomescritorio'];
//        $this->view->dados  = $servicePessoaAgenda->retornaPartesPorAgenda($this->_request->getParams());

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

    public function pesquisarjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Agenda_Service_Agenda');
        $paginator = $service->pesquisar($this->_request->getParams(), true);
//        $this->_helper->json->sendJson($paginator->toJqgrid());
        $this->_helper->json->sendJson($paginator);
// 		Zend_Debug::dump($resultado);exit;
//        $this->_helper->json->sendJson($resultado->toJqgrid());
//        $this->_helper->json->sendJson($resultado);
    }

    public function participantesAction()
    {
//        var_dump($this->_request->getParams());
//        exit;
        $serviceAgenda = App_Service_ServiceAbstract::getService('Agenda_Service_Agenda');
        $service = App_Service_ServiceAbstract::getService('Agenda_Service_PessoaAgenda');
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $parte = $service->inserir($dados);
            if ($parte) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
                //Zend_Debug::dump($msg);
            }
        } else {
//            var_dump($agenda);exit;
            $parte = $service->retornaPartesPorAgenda($this->_request->getParams());
            $this->view->dados = $parte;
            $this->view->idagenda = $this->_request->getParam('idagenda');
            $this->view->form = $form;
            $this->view->agenda = $serviceAgenda->getById($this->_request->getParams(), true);
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
//                $this->view->form = $form;
                $this->view->parte = is_object($parte) ? get_object_vars($parte) : null;
                $this->view->success = $success;
                $this->view->msg = $service->getNotify();
                /*$this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );*/
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function excluirparticipanteAction()
    {
        $service = App_Service_ServiceAbstract::getService('Agenda_Service_PessoaAgenda');
        $success = false;
        //$idparteinteressada = $this->_request->getParams('id');
        $parte = $service->excluirparticipante($this->_request->getParams());
//        Zend_Debug::dump($this->_request->getParams());exit;
//        Zend_Debug::dump($parte);exit;
        if ($parte) {
            $success = true; ###### AUTENTICATION SUCCESS
            $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
        } else {
            $msg = $service->getErrors();
            //Zend_Debug::dump($msg);
        }

        if ($this->_request->isXmlHttpRequest()) {
            if ($parte) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                $msg = $service->getErrors();
//                Zend_Debug::dump($msg);
            }
        }
    }

    public function excluirAction()
    {
//        var_dump($this->_request->getParams()); exit;
        $success = false;
        $serviceAgenda = App_Service_ServiceAbstract::getService('Agenda_Service_Agenda');
        $servicePessoaAgenda = App_Service_ServiceAbstract::getService('Agenda_Service_PessoaAgenda');

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();

//            var_dump($this->_request->getParams()); exit;
            try {
                $servicePessoaAgenda->excluir($this->_request->getParams(), true);
                $serviceAgenda->excluir($this->_request->getParams(), true);

                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } catch (Exception $e) {
                $msg = $e;
            }

//            if ($success){
//                $success = true; ###### AUTENTICATION SUCCESS
//                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
//            } else {
//                $msg = $service->getErrors();
//                //Zend_Debug::dump($msg);
//            }
        } else {
            $this->view->agenda = $serviceAgenda->getById($this->_request->getParams(), true);
        }

        /*
        */


        if ($this->_request->isXmlHttpRequest()) {
            if ($success) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
//                $msg = $serviceAgenda->getErrors();
//                $msg .= $servicePessoaAgenda->getErrors();
//                Zend_Debug::dump($msg);
            }
        }


//        var_dump($this->view->agenda); exit;
    }

    public function retornaDiasComEventosAction()
    {
        $service = App_Service_ServiceAbstract::getService('Agenda_Service_Agenda');
        $paginator = $service->retornaDiasComEventos($this->_request->getParams());
        $this->_helper->json->sendJson($paginator);
//        $this->_helper->json->sendJson($paginator->toJqgrid());
// 		Zend_Debug::dump($resultado);exit;
//        $this->_helper->json->sendJson($resultado->toJqgrid());
//        $this->_helper->json->sendJson($resultado);
    }
}
