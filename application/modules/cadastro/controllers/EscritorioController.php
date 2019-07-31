<?php

class Cadastro_EscritorioController extends Zend_Controller_Action
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
        $service = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $form = $service->getFormPesquisar();
        $this->view->form = $form;
    }

    public function pesquisarjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $paginator = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $this->view->escritorio = $service->getById($this->_request->getParams());
    }

    public function addAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $form = $service->getForm();
        $success = false;
        $msgError = "";
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $escritorio = $service->getByName($dados);
            $pessoa = $service->inserir($dados);
            if ($pessoa) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msgError = $service->getErrors();
                if (count($escritorio) > 0) {
                    $msg = 'Escrit&oacute;rio j&aacute; cadastrado.';
                } else {
                    $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                }

            }
        }

        $this->view->form = $form;

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msgError = $msgError;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('cadastrar', 'documento', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function editarAction()
    {

        $service = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $form = $service->getFormEditar();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $escritorio = $service->update($dados);
            if ($escritorio) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
                //Zend_Debug::dump($msg);
            }
        } else {
            $escritorio = $service->getById($this->_request->getParams());
            //Zend_Debug::dump($escritorio);exit;


            $form->populate(array(
                'idescritorio' => $escritorio->idescritorio,
                'nomescritorio' => $escritorio->sigla,
                'flaativo' => $escritorio->flaativo,
                'idresponsavel1' => $escritorio->idresponsavel1,
                'nomresponsavel1' => $escritorio->nomresponsavel1,
                'idresponsavel2' => $escritorio->idresponsavel2,
                'nomresponsavel2' => $escritorio->nomresponsavel2,
                'idescritoriope' => $escritorio->idescritoriope,
                'nomescritorio2' => $escritorio->nome,
                'desemail' => $escritorio->desemail,
                'numfone' => $escritorio->numfone
            ));
            $this->view->escritorio = $escritorio;
            $this->view->form = $form;
        }


        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'escritorio', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function buscarEscritorioAction()
    {
        $service = new Default_Service_Escritorio();
        $escritorio = $service->getById($this->_request->getParams());
        $this->_helper->json->sendJson($escritorio);

    }

    public function pesquisarviewcomumjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $resultado = $service->pesquisarViewComum($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }

    public function importarjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $resultado = $service->importarViewComum($this->_request->getParams());
        $this->_helper->json->sendJson($resultado);
    }
}