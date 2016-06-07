<?php

class Projeto_TepController extends Zend_Controller_Action{

    public function init(){
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
                    ->addActionContext('editar', 'json')
                    ->initContext();
    }

    public function indexAction(){
    }

    public function editarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Tep');
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getFormTep();
        $success = false;
        if($this->_request->isPost()){
            $dados = $this->_request->getPost();
//            var_dump($dados); exit;
            $gerencia = $service->update($dados);
            if($gerencia){
                $success = true; ###### AUTENTICATION SUCCESS
                $msg     = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
//            $gerencia = $serviceGerencia->getById($this->_request->getParams());
            $gerencia = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
//            Zend_Debug::dump($gerencia); exit;
            $form->populate($gerencia->formPopulate());
            $this->view->gerencia = $gerencia;
            $this->view->form = $form;
        }


        if ($this->_request->isPost()) {
            if($this->_request->isXmlHttpRequest()){
                $this->view->success = $success;
                $this->view->msg = array(
                    'text'    => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type'    => ($success) ? 'success' : 'error',
                    'hide'    => true,
                    'closer'  => true,
                    'sticker' => false
                );
            } else {
                if($success){
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }
}