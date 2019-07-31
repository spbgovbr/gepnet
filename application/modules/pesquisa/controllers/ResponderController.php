<?php

class Pesquisa_ResponderController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('responder-pesquisa', 'json');
        $ajaxContext->addActionContext('responder-externa', 'json')
            ->initContext();
    }

    public function listarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
        $this->view->formPesquisar = $service->getFormPesquisar();
        //se for acesso externo (GEPNET LOGOFF) seta layout sem menus
        if (Zend_Auth::getInstance()->hasIdentity() == false) {
            $this->_helper->layout()->setLayout('pesquisa-externa');
        }
    }

    public function pesquisarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Responder');
        $paginator = $service->retornaPesquisasResponderGrid($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function responderPesquisaAction()
    {
        $request = $this->getRequest();
        $params = $request->getParams();
        $this->view->idpesquisa = $params['idpesquisa'];
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Responder');

        $servicePesquisa = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pesquisa');
        $pesquisaPublicada = $servicePesquisa->retornaPesquisaPublicadaById($params);
        //se for acesso externo (GEPNET LOGOFF) seta layout sem menus
        if (Zend_Auth::getInstance()->hasIdentity() == false) {
            $this->_helper->layout()->setLayout('pesquisa-externa');
            //se for pesquisa for tipo publicado com senha
            if ($pesquisaPublicada['tipoquestionario'] == Pesquisa_Model_QuestionarioPesquisa::PUBLICADO_COM_SENHA) {
                $this->_forward('autenticar', 'responder', 'pesquisa');
            } else {
                $this->_forward('responder-externa', 'responder', 'pesquisa');
            }
        } else {
            if ($pesquisaPublicada) {
                $isRespondida = $service->respondeuPesquisaInterna($pesquisaPublicada);

                if ($isRespondida) {
                    $this->view->message = 'Pesquisa já respondida por este usuário anteriormente.';
                } else {
                    $form = $service->getFormPesquisa($params);
                    if ($request->isPost()) {

                        $result = $service->salvarPesquisaRespondida($request->getParam('idpesquisa'),
                            $request->getPost());
                        $success = false;
                        if ($result) {
                            $success = true; ###### AUTENTICATION SUCCESS
                            $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
                        } else {
                            $msg = $service->getErrors() ?: App_Service_ServiceAbstract::ERRO_GENERICO;
                        }

                        if ($this->_request->isXmlHttpRequest()) {
                            $this->view->success = $success;
                            $this->view->msg = array(
                                'text' => $msg,
                                'type' => ($success) ? 'success' : 'error',
                                'hide' => true,
                                'closer' => true,
                                'sticker' => false
                            );
                        }
                    }

                    $user = ($pesquisaPublicada['tipoquestionario'] == Pesquisa_Model_Questionario::PUBLICADO_COM_SENHA) ? Zend_Auth::getInstance()->getIdentity()->nome : "Anônimo";
                    $form->populate($params);
                    $this->view->user = $user;
                    $this->view->form = $form;
                }
            } else {
                $this->view->message = 'Pesquisa encerrada ou inexistente';
            }
        }
    }

    /**
     * Monta a tela de autenticacao caso a pesquisa seja tipo "publicada com senha"
     */
    public function autenticarAction()
    {
        //se for acesso externo (GEPNET LOGOFF) seta layout sem menus
        $this->_helper->layout()->setLayout('pesquisa-externa');

        $service = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $request = $this->getRequest();

        $form = new Pesquisa_Form_Autenticar();

        if ($request->isPost()) {
            $post = $request->getPost();

            if ($form->isValid($post)) {
                $result = $service->autenticaLdap($post);
                if ($result) {
                    $this->_forward('responder-externa', 'responder', 'pesquisa');
                } else {
                    $this->view->message = $service->getErrors();
                }
            } else {
                $this->view->message = App_Service_ServiceAbstract::ERRO_GENERICO;
            }
        }

        $this->view->form = $form;

    }

    public function responderExternaAction()
    {
        //recupera os dados LDAP do usuario externo        
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('ldap_pesquisa'));
        $dataUser = $auth->getStorage()->read();

        $request = $this->getRequest();
        $params = $request->getParams();
        $this->view->idpesquisa = $params['idpesquisa'];
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Responder');

        $servicePesquisa = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pesquisa');
        $pesquisaPublicada = $servicePesquisa->retornaPesquisaPublicadaById($params);
        if ($pesquisaPublicada) {

            $isRespondida = $service->respondeuPesquisaLdap($pesquisaPublicada);
            if ($isRespondida) {
                $this->view->message = 'Pesquisa já respondida por este usuário anteriormente.';
            } else {
                $form = $service->getFormPesquisa($params);
                if ($request->isPost()) {
                    $result = $service->salvarPesquisaRespondidaExterna($request->getParam('idpesquisa'),
                        $request->getPost());
                    $success = false;
                    if ($result) {
                        $success = true; ###### AUTENTICATION SUCCESS
                        $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
                    } else {
                        $msg = $service->getErrors() ?: App_Service_ServiceAbstract::ERRO_GENERICO;
                    }

                    if ($this->_request->isXmlHttpRequest()) {
                        $this->view->success = $success;
                        $this->view->msg = array(
                            'text' => $msg,
                            'type' => ($success) ? 'success' : 'error',
                            'hide' => true,
                            'closer' => true,
                            'sticker' => false
                        );
                    }
                }

                $user = ($pesquisaPublicada['tipoquestionario'] == Pesquisa_Model_Questionario::PUBLICADO_COM_SENHA) ? $dataUser['data_user']['cn'][0] : "Anônimo";
                $form->populate($params);
                $this->view->user = $user;
                $this->view->form = $form;
            }
        } else {
            $this->view->message = 'Pesquisa encerrada ou inexistente';
        }
    }
}
