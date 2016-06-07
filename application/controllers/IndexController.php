<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('mudar-perfil', 'json')
            ->addActionContext('logout', 'json')
            ->initContext();
        //$ajaxContext->addActionContext('pesquisar', 'json')
    }

    public function sairAction()
    {
        $this->_helper->layout->setLayout('login');
    }

    public function boasVindasAction()
    {
        /*
          $service = new Default_Service_Login();
          Zend_Debug::dump($service->retornaUsuarioLogado()); exit;
        $service = new Default_Service_Pessoa();

        $form = $service->getForm();
        $pessoa = $service->getByCpf(array('cpf' => '70931941172'));
        $form->populate($pessoa->toArray());
        $this->view->form = $form;

         */
    }

    public function logoutAction()
    {
        $service = App_Service::getService('Default_Service_Login');
        $service->logout();
		//$module     = 'default';
		//$controller = 'index';
		//$action     = 'index';
		//$this->_helper->_redirector->gotoSimpleAndExit($action, $controller, $module);
    }

    public function indexAction()
    {
        $this->_helper->layout->setLayout('login');
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->messages  = $this->_flashMessenger->getMessages();
        $service = App_Service::getService('Default_Service_Login');
        $form    = $service->getFormLogarUsuario();
        $this->view->form      = $form;
        //$token                 = $this->_request->getCookie('SSO_GUID', false);
        //$token = '{5912B4E3-DB40-79D0-D3BD-723BA343F566}'; // errado
        //$token = '{2645B5E5-54C3-E3DF-C7C0-9201069683E8}';
        //$token = '{A1D59D91-13FA-5DA6-7C51-0402EC1EF0A8}'; //GRSF
        if($this->_request->isPost()){
            
        $params          = $this->_getAllParams();
        $desemail        = $params['desemail'];
        $token           = $params['token'];
        //$params['token'] = $token;
        $serviceLogin    = App_Service::getService('Default_Service_Login');
        $login           = $serviceLogin->autenticar($desemail,$token);
        
        //GRSF
        //$login = true;

        if ( $login == true ) {
            $success    = true; ###### AUTENTICATION SUCCESS
            $msg        = App_Service::REGISTRO_CADASTRADO_COM_SUCESSO;
            $module     = 'default';
            $controller = 'index';
            $action     = 'perfil';

            $this->_helper->_redirector->gotoSimpleAndExit($action, $controller, $module);
        } else {
            $msg = "Dados incorretos";
		//var_dump($msg);exit;
            $this->_helper->_flashMessenger->addMessage(array('status'  => 'error', 'message' => $msg));
            $this->_redirect('/');
        }
        }
    }

    public function perfilAction()
    {
        $this->_helper->layout->setLayout('login');
        $service = App_Service::getService('Default_Service_Login');
        $form    = $service->getFormPerfil();

        if ( $this->_request->isPost() ) {
            $success = false;
            $dados   = $this->_request->getPost();
            $retorno = $service->selecionarPerfil($dados);
            if ( $retorno ) {
                $success = true;
            } else {
                $msg = $service->getErrors();
            }
        }

        $this->view->form = $form;

        if ( $this->_request->isPost() ) {
            if ( $this->_request->isXmlHttpRequest() ) {
                $this->view->usuario = $service->retornaUsuarioLogado();
                $this->view->success = $success;
                $this->view->msg     = array(
                    'text               '    => $msg,
                    'type'    => ($success) ? 'success' : 'error',
                    'hide'    => true,
                    'closer'  => true,
                    'sticker' => false
                );
            } else {
                if ( $success ) {
                    $this->_helper->_redirector->gotoSimpleAndExit('boas-vindas');
                }
                $this->_helper->_flashMessenger->addMessage(array('status'  => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('perfil');
            }
        }
    }

    public function mudarPerfilAction()
    {
        $service = App_Service::getService('Default_Service_Login');
        $form    = $service->getFormMudarPerfil();

        if ( $this->_request->isPost() ) {
            $success = false;
            $dados   = $this->_request->getPost();
            $idPerfil = explode('-',$dados['idperfil']);
            $dados['idperfil'] = $idPerfil[0];
            $retorno = $service->selecionarPerfil($dados);
            if ( $retorno ) {
                $msg = 'Perfil alterado com sucesso.';
                $success = true;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $this->view->form = $form;
        }


        if ( $this->_request->isPost() ) {
            if ( $this->_request->isXmlHttpRequest() ) {
                //$this->view->usuario = $service->retornaUsuarioLogado();
                $this->view->success = $success;
                $this->view->msg     = array(
                    'text'    => $msg,
                    'type'    => ($success) ? 'success' : 'error',
                    'hide'    => true,
                    'closer'  => true,
                    'sticker' => false
                );
            } else {
                if ( $success ) {
                    $this->_helper->_redirector->gotoSimpleAndExit('boas-vindas');
                }
                $this->_helper->_flashMessenger->addMessage(array('status'  => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('perfil');
            }
        }
    }

    public function generateAction()
    {
        /*
          set_time_limit(0);
          $generator = new App_Generator_Generator();
          $generator->generate();
         */
    }

    public function testeAction()
    {
        
    }

    public function sisegAction()
    {
        
    }

    public function abcAction()
    {

    }
    

}