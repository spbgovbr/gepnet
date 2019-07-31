<?php

class Cadastro_RecursoController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('cadastrar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('pesquisar', 'json')
            ->addActionContext('toggle', 'json')
            ->initContext();

        //$ajaxContext->addActionContext('pesquisar', 'json')
    }

    public function indexAction()
    {
        $service = new Default_Service_Recurso();
        $this->view->dados = $service->retornaNaoCadastrados();
    }

    public function gerenciarAction()
    {
        $service = new Default_Service_Recurso();
        $this->view->form = $service->getFormPesquisar();
    }

    /**
     * retorna perfil por permissao
     */
    public function retornaPorPerfilAction()
    {
        $service = new Default_Service_Recurso();
        $perfis = $service->retornaPermissaoPorPerfil($this->_request->getParams());
        $this->_helper->json->sendJson($perfis);
    }

    public function novosRecursosAction()
    {
        $service = new Default_Service_Recurso();
        $paginator = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function pesquisarAction()
    {
        $service = new Default_Service_Recurso();
        $paginator = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Documento');
        $this->view->documento = $service->getById($this->_request->getParams());
    }

    public function cadastrarAction()
    {
        $service = new Default_Service_Recurso();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $retorno = $service->inserir($dados);
            if ($retorno) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
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
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('cadstrar', 'documento', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('index', 'index', 'default');
            }
        }
    }
}