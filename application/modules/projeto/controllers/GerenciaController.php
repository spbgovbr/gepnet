<?php

class Projeto_GerenciaController extends Zend_Controller_Action
{
    public function init ()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('add', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('desbloquear', 'json')
            ->initContext();
        //$ajaxContext->addActionContext('pesquisar', 'json')
    }
    
    public function indexAction ()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        //$this->rotinaBloqueioProjetosAction();
        $auth     = Zend_Auth::getInstance();
        $identiti = $auth->getIdentity();
        $identiti->perfilAtivo->idperfil;
        $this->view->identiti = $identiti;
        $form = $service->getFormPesquisar();
        $this->view->form = $form;
    }
    
    public function resumoAction ()
    {
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $this->view->projeto = $projeto;
    }
    
    public function pesquisarjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $auth         = Zend_Auth::getInstance();
        $identiti     = $auth->getIdentity();
        $idperfil     = $identiti->perfilAtivo->idperfil;
        $idescritorio = $identiti->perfilAtivo->idescritorio;
        $paginator = $service->pesquisar($this->_request->getParams(), $idperfil,$idescritorio,true);
        $this->_helper->json->sendJson($paginator);
    }
    
    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $this->view->escritorio = $service->getById($this->_request->getParams());
    }
    
    public function addAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getForm();
        $success = false;
        if($this->_request->isPost()){
            $dados = $this->_request->getPost();
            $projeto = $service->inserir($dados);
            if($projeto){
                $success = true; ###### AUTENTICATION SUCCESS
                $msg     = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        }
        
        $this->view->form = $form;
        
        if ($this->_request->isPost()) {
            if($this->_request->isXmlHttpRequest()){
                $this->view->success = $success;
                $this->view->msg = array(
                    'text'    => $msg,
                    'type'    => ($success) ? 'success' : 'error',
                    'hide'    => true,
                    'closer'  => true,
                    'sticker' => false
                );
            } else {
                if($success){
                    $this->_helper->_redirector->gotoSimpleAndExit('projeto', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
            }
        }
    }
    
    public function editarAction()
    {

        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getFormEditar();
        $success = false;
        if($this->_request->isPost()){
            $dados = $this->_request->getPost();
            $gerencia = $service->update($dados);
            if($gerencia){
                $success = true; ###### AUTENTICATION SUCCESS
                $msg     = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $gerencia = $service->getById($this->_request->getParams());
            $form->populate($gerencia->formPopulate());
            $this->view->gerencia = $gerencia;
            $this->view->form = $form;
        }
        
        
        if ($this->_request->isPost()) {
            if($this->_request->isXmlHttpRequest()){
                $this->view->success = $success;
                $this->view->msg = array(
                    'text'    => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type'    => ($success) ? 'success' : 'error',
                    'hide'    => true,
                    'closer'  => true,
                    'sticker' => false
                );
            } else {
                if($success){
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }
    
    
    
    public function pesquisarviewcomumjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $resultado = $service->pesquisarViewComum($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }
    
    public function importarjsonAction()
    {
        $this->_helper->layout->disableLayout();
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $resultado = $service->importarViewComum($this->_request->getParams());
        $this->_helper->json->sendJson($resultado);
    }

    public function rotinaBloqueioProjetosAction(){
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_BloqueioProjeto');
        $service->rotinaBloqueioProjetos();
    }

    public function desbloquearAction(){
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_BloqueioProjeto');
        $form = $service->getFormDesbloqueio();
        $success = false;
        if($this->_request->isPost()){
            $dados = $this->_request->getPost();
//            $dados['domstatusprojeto'] = 2;
//            Zend_Debug::dump($dados); exit;
            $gerencia = $service->desbloquearProjeto($dados);
            if($gerencia){
                $success = true; ###### AUTENTICATION SUCCESS
                $msg     = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $gerencia = $serviceGerencia->getById($this->_request->getParams());
            //$form->populate($service->formPopulate());
            $this->view->gerencia = $gerencia;
            $this->view->form = $form;
        }


        if ($this->_request->isPost()) {
            if($this->_request->isXmlHttpRequest()){
                $this->view->success = $success;
                $this->view->msg = array(
                    'text'    => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type'    => ($success) ? 'success' : 'error',
                    'hide'    => true,
                    'closer'  => true,
                    'sticker' => false
                );
            } else {
                if($success){
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }



    }

    public function phpinfoAction(){
        echo phpinfo();
        exit;
    }
}