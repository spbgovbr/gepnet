<?php

class Cadastro_ProgramaController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('add', 'json')
            ->addActionContext('editar', 'json')
            ->initContext();
        //$ajaxContext->addActionContext('pesquisar', 'json')
    }

    public function indexAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Programa');
        $form = $service->getFormPesquisar();
        $this->view->form = $form;
    }

    public function pesquisarjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Programa');
        $paginator = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function detalharAction()
    {
        $service = new Default_Service_Programa();
        $this->view->programa = $service->retornaPorId($this->_request->getParams());


    }

    public function addAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Programa');
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $pessoa = $service->inserir($dados);
            if ($pessoa) {
                $success = true; ###### AUTENTICATION SUCCESS
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
                    $this->_helper->_redirector->gotoSimpleAndExit('cadastrar', 'programa', 'cadastro');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('add');
            }
        }
    }

    public function editarAction()
    {

        $service = App_Service_ServiceAbstract::getService('Default_Service_Programa');
        $form = $service->getFormEditar();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $programa = $service->update($dados);
            if ($programa) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
                //Zend_Debug::dump($msg);
            }
        } else {
            $programa = $service->getById($this->_request->getParams());
            //Zend_Debug::dump($programa);exit;
            $form->populate($programa);

            /*
            $form->populate(array(
            	'idprograma'          => $programa->idprograma,
    			'nomprograma'         => $programa->nomprograma,
    			'desprograma'         => $programa->desprograma,
    			'idcadastrador'       => $programa->idcadastrador,
    			'datcadastro'         => $programa->datcadastro,
    			'flaativo'            => $programa->flaativo,
    			'idresponsavel'       => $programa->idresponsavel,
    			'idsimpr'             => $programa->idsimpr,
    			'idsimpreixo'         => $programa->idsimpreixo,
    			'idsimprareatematica' => $programa->idsimprareatematica,
    			//'nompessoa'			  => $programa->nompessoa	
            ));
            */
            $this->view->programa = $programa;
            $this->view->form = $form;
        }


        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? $msg[0] : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'programa', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }


    public function pesquisarviewcomumjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Programa');
        $resultado = $service->pesquisarViewComum($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }

    public function importarjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Programa');
        $resultado = $service->importarViewComum($this->_request->getParams());
        $this->_helper->json->sendJson($resultado);
    }
}