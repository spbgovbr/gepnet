<?php

class DocumentoController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('cadastrar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('detalhar', 'json')
            ->addActionContext('excluir', 'json')
            ->initContext();
        //$ajaxContext->addActionContext('pesquisar', 'json')
    }

    public function indexAction()
    {
        $this->_forward('pesquisar');
    }

    public function cadastrarAction()
    {
        $service = new Default_Service_Documento();
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $documento = $service->inserir($dados);
            if ($documento) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = "USAR {$documento['DS_TP_DOC']} Nº: <strong>{$documento['NR_DOCUMENTO']}</strong>";
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
                    'hide' => false,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('cadstrar', 'documento', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function editarAction()
    {
        $service = new Default_Service_Documento();
        $form = $service->getFormEditar($this->_getAllParams());
        $success = false;
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            $documento = $service->update($data);
            if ($documento) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = "USAR {$documento['DS_TP_DOC']} Nº: <strong>{$documento['NR_DOCUMENTO']}</strong>";
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $values = $service->getById($this->_request->getParams());
            $values['tipodoc'] = $values['tipodoc_cd_tipodoc'];
            $form->populate($values);
        }

        $this->view->form = $form;

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => false,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('editar', 'documento', 'default',
                        $this->_request->getUserParams());
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $message));
                $this->_helper->_redirector->gotoSimpleAndExit('editar', 'documento', 'default');
            }
        }
    }

    public function detalharAction()
    {
        $mapper = new Default_Model_Mapper_Documento();
        $values = $mapper->getById($this->_request->getUserParams());
        $this->view->doc = $values;
        //Zend_Debug::dump($values);
    }

    public function pesquisarAction()
    {
        $form = new Default_Form_DocumentoPesquisar();
        /*
        if($this->_request->isPost()) {
            $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
            $service = new Default_Service_Documento();
            $paginator = $service->pesquisar($this->_request->getPost(), true);
            //$mapper  = new Default_Model_Mapper_Documento();
            //$paginator = $mapper->pesquisar($this->_request->getPost() , true);
            if($this->_request->isXmlHttpRequest()){
                $this->_helper->json->sendJson($paginator->toJqgrid());
            } else {
                $this->view->paginator = $paginator;
            }
        } 
         */
        $this->view->form = $form;
    }

    public function pesquisarjsonAction()
    {
        $service = new Default_Service_Documento();
        $paginator = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function formEditarAction()
    {
        $service = new Default_Service_Documento();
        $form = $service->getFormEditar($this->_getAllParams());
        $values = $service->getById($this->_request->getParams());
        $values['tipodoc'] = $values['tipodoc_cd_tipodoc'];
        $form->populate($values);
        $this->view->form = $form;
    }

    public function formExcluirAction()
    {
        $service = new Default_Service_Documento();
        $values = $service->getById($this->_request->getParams());
        $values['tipodoc'] = $values['tipodoc_cd_tipodoc'];
        $this->view->doc = $values;
    }

    public function excluirAction()
    {
        $service = new Default_Service_Documento();
        $success = false;
        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();
            $documento = $service->excluir($data);
            if ($documento) {
                $success = true;
                $msg = "Registro exclu&iacute;do com sucesso";
            } else {
                $msg = $service->getErrors();
            }
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    //'hide'    => false,
                    'closer' => true,
                    //'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('pesquisar', 'documento', 'default',
                        $this->_request->getUserParams());
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('pesquisar', 'documento', 'default');
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
}