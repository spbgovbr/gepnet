<?php

class Pesquisa_PerguntaController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('cadastrar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('detalhar', 'json')
            ->initContext();
    }

    public function listarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pergunta');
        $this->view->formPesquisar = $service->getFormPesquisar();
    }

    public function cadastrarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pergunta');
        $formPergunta = $service->getFormPergunta();
        $request = $this->getRequest();
        $success = false;

        if ($request->isPost()) {
            $pergunta = $service->insert($request->getPost());
            if ($pergunta) {
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

        $this->view->formPergunta = $formPergunta;
    }

    public function editarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pergunta');
        $form = $service->getFormPergunta();
        $request = $this->getRequest();
        $success = false;

        if ($request->isPost()) {
            $pergunta = $service->update($request->getPost());
            if ($pergunta) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
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
        $perguntaResult = $service->getById($request->getParams());
        $form->populate($perguntaResult);
        $this->view->form = $form;
    }

//    public function excluirAction()
//    {
//        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pergunta');
//        $request = $this->getRequest();
//        $success = false;
//
//        if ($request->isPost()) {
//            $pergunta = $service->excluir($request->getParams());
//            if ($pergunta) {
//                $success = true; ###### AUTENTICATION SUCCESS
//                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
//            } else {
//                $msg = $service->getErrors() ? : App_Service_ServiceAbstract::ERRO_GENERICO;
//            }
//
//            if ($this->_request->isXmlHttpRequest()) {
//                $this->view->success = $success;
//                $this->view->msg = array(
//                    'text' => $msg,
//                    'type' => ($success) ? 'success' : 'error',
//                    'hide' => true,
//                    'closer' => true,
//                    'sticker' => false
//                );
//            }
//        }
//        $perguntaResult = $service->getByIdDetalhar($request->getParams());
//        $this->view->pergunta = $perguntaResult;
//    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pergunta');
        $serviceResposta = App_Service_ServiceAbstract::getService('Pesquisa_Service_Resposta');

        $respostas = $serviceResposta->getByIdPerguntaDetalhar($this->getRequest()->getParams());
        $perguntas = $service->getByIdDetalhar($this->getRequest()->getParams());

        $this->view->pergunta = $perguntas;
        $this->view->resposta = $respostas;
    }

    public function pesquisarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pergunta');
        $paginator = $service->retornaPerguntasGrid($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

}
