<?php

class IndexController extends Zend_Controller_Action
{
    /**
     * @var App_View_Helper_FlashMessages
     */
    private $_flashMessenger;

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('mudar-perfil', 'json')
            ->addActionContext('logout', 'json')
            ->initContext();
        //$ajaxContext->addActionContext('pesquisar', 'json')
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function sairAction()
    {
        $this->_helper->layout->setLayout('login');
    }

    public function boasVindasAction()
    {
        if (null == Zend_Auth::getInstance()->getStorage()->read()) {
            $this->_redirect('/');
            exit;
        }
    }

    public function logoutAction()
    {
        $service = App_Service::getService('Default_Service_Login');
        $service->logout();

        $url = '/';

        $this->_helper->json->sendJson(array('success' => true, 'redirect' => $url));
    }

    /**
     * @return mixed
     */
    public function indexAction()
    {
        $this->_helper->layout->setLayout('login');
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->view->messages = $this->_flashMessenger->getMessages();
        $service = App_Service::getService('Default_Service_Login');
        $form = $service->getFormLogarUsuario();
        $this->view->form = $form;

        if ($this->_request->isPost()) {

            $params = $this->_getAllParams();
            $desemail = $params['desemail'];
            $token = $params['token'];
            $serviceLogin = new Default_Service_Login();
            $login = $serviceLogin->autenticar($desemail, $token);

            //GRSF
            //$login = true;

            if ($login == true) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service::REGISTRO_CADASTRADO_COM_SUCESSO;
                $module = 'default';
                $controller = 'index';
                $action = 'perfil';

                $this->_helper->_redirector->gotoSimpleAndExit($action, $controller, $module);
            } else {
                $msg = "Dados incorretos";
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_redirect('/');
            }
        }
    }

    public function perfilAction()
    {
        if (null == Zend_Auth::getInstance()->getStorage()->read()) {
            $this->_redirect('/');
            exit;
        }

        $this->_helper->layout->setLayout('login');
        $service = new Default_Service_Login();
        $form = $service->getFormPerfil();

        if ($this->_request->isPost()) {
            $success = false;
            $dados = $this->_request->getPost();
            $retorno = $service->selecionarPerfil($dados);
            if ($retorno) {
                $success = true;
            } else {
                $msg = $service->getErrors();
            }
        }

        $this->view->form = $form;

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->usuario = $service->retornaUsuarioLogado();
                $this->view->success = $success;
                $this->view->msg = array(
                    'text               ' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('boas-vindas');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('perfil');
            }
        }
    }

    public function mudarPerfilAction()
    {
        $service = App_Service::getService('Default_Service_Login');
        $form = $service->getFormMudarPerfil();

        if ($this->_request->isPost()) {
            $success = false;
            $dados = $this->_request->getPost();
//            $idPerfil = explode('-',$dados['idperfil']);
//            $dados['idperfil'] = $idPerfil[0];
            $retorno = $service->selecionarPerfil($dados);
            if ($retorno) {
                $msg = 'Perfil alterado com sucesso.';
                $success = true;
            } else {
                $msg = $service->getErrors();
            }
        } else {
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
                    $this->_helper->_redirector->gotoSimpleAndExit('boas-vindas');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('perfil');
            }
        }
    }

    public function gerenciaAction()
    {
        $this->_redirect('projeto/gerencia');
    }

    public function statusreportAction()
    {
        $this->_redirect('projeto/statusreport');
    }

    /**
     * @return mixed
     */
    public function alterarSenhaAction()
    {
        try {
            $service = App_Service::getService('Default_Service_Login');
            $hash = $this->getRequest()->getParam('hash');
            if ($hash) {
                $hashData = Default_Service_Security::decryptArrayObject($hash);
                if (!$hashData || !isset($hashData['token']) || !isset($hashData['desemail'])) {
                    throw new Exception('Link de recuperação expirado!');
                } else {
                    $login = $service->autenticar(
                        $hashData['desemail'],
                        $hashData['token'],
                        false
                    );
                    if (!$login) {
                        throw new Exception('Link de recuperação expirado!');
                    }
                }
            }

            $this->_helper->layout->setLayout('login');
            $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
            $this->view->messages = $this->_flashMessenger->getMessages();
            $form = $service->getFormAlterarSenha($hash ? array('token_atual') : null);
            $usuario = $service->retornaUsuarioLogado();
            $this->view->form = $form;
            if ($this->_request->isPost()) {
                $params = $this->_getAllParams();
                if ($form->isValid($params)) {
                    $params = $form->getValues();
                    if ($hash) {
                        $antenticou = true;
                    } else {
                        $antenticou = $service->verifyPassword(
                            $usuario->desemail,
                            $params['token_atual']
                        );
                    }

                    if ($antenticou) {
                        $servicePessoa = App_Service::getService('Default_Service_Pessoa');
                        $usuarioAtualizado = $servicePessoa->updatePassword($params['token']);
                        if ($usuarioAtualizado) {
                            $this->_helper->_flashMessenger->addMessage(array(
                                'status' => 'success',
                                'message' => 'Senha alterada com sucesso!'
                            ));
                            return $this->_helper->_redirector->gotoSimpleAndExit('perfil');
                        }
                    } else {
                        $errors = $service->getErrors();
                        $this->view->message = reset($errors);
                    }
                } else {
//                $this->view->message = $form->getMessages();
                }
            }
        } catch (Exception $exception) {
            $this->view->message = $exception->getMessage();
            $this->_helper->_flashMessenger->addMessage(array(
                'status' => 'error',
                'message' => $exception->getMessage()
            ));
            return $this->_helper->_redirector->gotoSimpleAndExit('index');
        }
    }

    /**
     * @return mixed
     * @throws Zend_Form_Exception
     */
    public function esqueciSenhaAction()
    {
        $this->_helper->layout->setLayout('login');
        $service = App_Service::getService('Default_Service_Login');
        /**
         * @var $form Default_Form_EsqueciSenha
         */
        $form = $service->getFormEsqueciSenha();
        if ($this->_request->isPost()) {
            $params = $this->_getAllParams();
            try {
                if ($form->isValid($params)) {
                    $values = $form->getValues();
                    $servicePessoa = App_Service::getService('Default_Service_Pessoa');
                    $user = $servicePessoa->getTokenByEmail($values['desemail']);
                    if ($user) {
                        $values['token'] = $user['token'];
                        $sent = $service->sendResetLink($values);
                        if ($sent) {
                            $this->_helper->_flashMessenger->addMessage(array(
                                'status' => 'success',
                                'message' => 'Um link para alterar sua senha foi enviado para o e-mail informado!'
                            ));
                            return $this->_helper->_redirector->gotoSimpleAndExit('index');
                        }
                    } else {
                        throw new Exception('E-mail não cadastrado no sistema.');
                    }
                } else {
                    $errorMsg = reset($form->getMessages());
                    throw new Exception($errorMsg);
                }
            } catch (Exception $exception) {
                $this->view->message = $exception->getMessage();
            }
        }
        $this->view->form = $form;
    }
}