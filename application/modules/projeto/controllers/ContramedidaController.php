<?php

class  Projeto_ContramedidaController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('cadastrar', 'json')
                ->addActionContext('editar', 'json')
                ->addActionContext('excluir', 'json')
                ->addActionContext('detalhar', 'json')
                ->initContext()
        ;
    }
    public function listarAction()
    {
          $service = App_Service_ServiceAbstract::getService('Projeto_Service_Contramedida');
          $idProjeto = $this->_request->getParam('idprojeto');
          $idrisco = $this->_request->getParam('idrisco');
          $this->view->formPesquisar = $service->getFormPesquisar();          
          $this->view->idrisco = $idrisco;
          $this->view->idprojeto = $idProjeto;
    }
    
    public function cadastrarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Contramedida');
        $serviceRisco = App_Service_ServiceAbstract::getService('Projeto_Service_Risco');
        $form = $service->getFormContramedida();
        $request = $this->getRequest();
        $success = false;

        if ( $request->isPost() ) {
            $contramedida = $service->insert($request->getPost());
            if ( $contramedida ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors() ? : App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            if ( $this->_request->isXmlHttpRequest() ) {
                $this->view->ata = is_object($contramedida) ? get_object_vars($contramedida) : NULL;
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

        $this->view->risco = $serviceRisco->getById($request->getParams());
        $form->populate(array('idrisco' => $request->getParam('idrisco')));
        $this->view->formContramedida = $form;
    }

    public function editarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Contramedida');
        $serviceRisco = App_Service_ServiceAbstract::getService('Projeto_Service_Risco');
        $form = $service->getFormContramedida();
        $request = $this->getRequest();
        $success = false;

        if ( $request->isPost() ) {
            $contramedida = $service->update($request->getPost());
            if ( $contramedida ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors() ? : App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            if ( $this->_request->isXmlHttpRequest() ) {
                $this->view->ata = is_object($contramedida) ? get_object_vars($contramedida) : NULL;
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
        $contramediaResult = $service->getById($this->getRequest()->getParams())->toArray();
        $form->populate($contramediaResult);
         $this->view->risco = $serviceRisco->getById($request->getParams());
        $this->view->formContramedida = $form;
    }

    public function excluirAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Contramedida');
        $request = $this->getRequest();
        $success = false;
        
        if ( $request->isPost() ) {
            $contramedida = $service->excluir($request->getParams());
            if ( $contramedida ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors() ? : App_Service_ServiceAbstract::ERRO_GENERICO;
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
        }
        $contramedidaResult = $service->getByIdDetalhar($request->getParams());
        $this->view->contramedida = $contramedidaResult;
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Contramedida');
        $contramedida = $service->getByIdDetalhar($this->getRequest()->getParams());
        $this->view->contramedida = $contramedida;
    }
    
    public function pesquisarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Contramedida');
        $paginator = $service->retornaContramedidaByRisco($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
        
    }
}
