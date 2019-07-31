<?php

class Projeto_RhController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('addinterno', 'json')
                ->addActionContext('addexterno', 'json')
                ->addActionContext('editarinterno', 'json')
                ->addActionContext('editarexterno', 'json')
                ->addActionContext('excluirparte', 'json')
                ->initContext()
        ;
    }

    public function listarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $form = $service->getFormPesquisar();
        $idProjeto = $this->_request->getParam('idprojeto');
        $form->populate(array('idprojeto' => $idProjeto));
        $this->view->formPesquisar = $form;
        $this->view->idprojeto = $idProjeto;
    }

    public function gridRhAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $paginator = $service->retornaPartesGrid($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function addinternoAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $form = $service->getForm();
        $formExterno = $service->getFormExterno();
        $success = false;
        if ( $this->_request->isPost() ) {
            $dados = $this->_request->getPost();
            $parte = $service->insertInterno($dados);
            if ( $parte ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }

            if ( $this->_request->isXmlHttpRequest() ) {
                $this->view->parte = is_object($parte) ? get_object_vars($parte) : NULL;
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            }
        } else {
            $this->view->idprojeto = $this->_request->getParam('idprojeto');
            $this->view->form = $form;
            $this->view->formExterno = $formExterno;
        }
    }

    public function addexternoAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $success = false;
        if ( $this->_request->isPost() ) {
            $dados = $this->_request->getPost();
            $parte = $service->insertExterno($dados);
            if ( $parte ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }

            if ( $this->_request->isXmlHttpRequest() ) {
                $this->view->parte = is_object($parte) ? get_object_vars($parte) : NULL;
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
    }

    public function editarinternoAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');        
        $form = new Projeto_Form_Parteinteressada();
        $formExterno = $service->getFormExterno();
        $success = false;
        if ( $this->_request->isPost() ) {
            $dados = $this->_request->getPost();
            $parte = $service->updateInterno($dados);
            if ( $parte ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }

            if ( $this->_request->isXmlHttpRequest() ) {
                //$this->view->parte = is_object($parte) ? get_object_vars($parte) : NULL;
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            }
        } else {
            $parte = $service->retornaPorId($this->_request->getParams(), true)->toArray();
            if ( $parte['idpessoainterna'] ) {
                $form->populate($parte);
                $this->view->form = $form;
            } else {
                //tratamento para popular o formulario externo
                $parte = $service->alteraKeyArray($parte);
                $formExterno->populate($parte);
                $this->view->formExterno = $formExterno;
            }
        }
        $this->view->idprojeto = $this->getRequest()->getParam('idprojeto');
    }

    public function editarexternoAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $success = false;
        if ( $this->_request->isPost() ) {
            $dados = $this->_request->getPost();
            $parte = $service->updateExterno($dados);
            if ( $parte ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }

            if ( $this->_request->isXmlHttpRequest() ) {
                $this->view->parte = is_object($parte) ? get_object_vars($parte) : NULL;
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
    }
    
    
    public function excluirparteAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $success = false;
        $request = $this->getRequest();

        if ( $request->isPost() ) {
            $parte = $service->excluir($request->getParams());
            if ( $parte ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }

            if ( $this->_request->isXmlHttpRequest() ) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            }

            return;
        }

        $parte = $service->retornaPorId($request->getParams(), true);
        $this->view->parte = $parte;
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $request = $this->getRequest();

        $parte = $service->retornaPorId($request->getParams(), true);
        $this->view->parte = $parte;
    }

}
