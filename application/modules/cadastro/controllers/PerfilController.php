<?php

class Cadastro_PerfilController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('revogar-permissao', 'json')
            ->addActionContext('conceder-permissao', 'json')
            ->initContext();
        //$ajaxContext->addActionContext('pesquisar', 'json')
    }

    public function concederPermissaoAction()
    {
        $success = false;
        $msg = 'Não foi possível conceder a permissao';
        $service = new Default_Service_Perfil();
        $retorno = $service->concederPermissao($this->_request->getPost());
        if ($retorno) {
            $perfil = $service->retornaPorId($this->_request->getPost());
            $success = true;
            $msg = "Permissão concedida com sucesso para o perfil: <strong>{$perfil->nomperfil}</strong>";
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

    public function revogarPermissaoAction()
    {
        $success = false;
        $msg = 'Não foi possível revogar a permissao';
        $service = new Default_Service_Perfil();
        $retorno = $service->revogarPermissao($this->_request->getPost());
        if ($retorno) {
            $perfil = $service->retornaPorId($this->_request->getPost());
            $success = true;
            $msg = "Permissão revogada com sucesso para o perfil: <strong>{$perfil->nomperfil}</strong>";
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

    public function permissaoAction()
    {
        $service = new Default_Service_Recurso();
        //if($this->_request->isXmlHttpRequest()){
        $paginator = $service->pesquisarPermissao($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }
}