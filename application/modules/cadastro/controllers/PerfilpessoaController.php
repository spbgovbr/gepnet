<?php

class Cadastro_PerfilpessoaController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('associarperfil', 'json')
            ->addActionContext('trocarsituacao', 'json')
            ->addActionContext('pesquisarjson', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        $service = new Default_Service_Perfilpessoa();
        $form = $service->getForm();
        $this->view->form = $form;

    }

    public function pesquisarjsonAction()
    {
        $service = new Default_Service_Perfilpessoa();
        $auth = Zend_Auth::getInstance();
        $idescritorio = $auth->getIdentity()->perfilAtivo->idescritorio;
        $idperfil = $auth->getIdentity()->perfilAtivo->idperfil;
        $paginator = $service->pesquisar($this->_request->getParams(), $idperfil, $idescritorio, true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function associarperfilAction()
    {
        $service = new Default_Service_Perfilpessoa();
        $form = $service->getFormAssociarPerfil();
        $this->view->form = $form;

        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $auth = Zend_Auth::getInstance();
            $identiti = $auth->getIdentity()->perfilAtivo->idperfil;
            // Caso o usuario nao seja adm getp net pega o escritorio logado
            if ($identiti != 1) {
                $dados['idescritorio'] = $idescritorio = $auth->getIdentity()->perfilAtivo->idescritorio;
            }
            $assocperfil = $service->associarPerfil($dados);
            if ($assocperfil) {
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
                    $this->_helper->_redirector->gotoSimpleAndExit('associarperfil', 'perfilpessoa', 'cadastro');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('associarperfil', 'perfilpessoa', 'cadastro');
            }
        }
    }

    public function trocarsituacaoAction()
    {

        $success = false;
        $msg = 'Não foi possível alterar a situação';
        $service = new Default_Service_Perfilpessoa();
        $retorno = $service->trocarSituacao($this->_request->getPost());
        if ($retorno) {
            $success = true;
            $msg = "Situação alterada com sucesso";
        }
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