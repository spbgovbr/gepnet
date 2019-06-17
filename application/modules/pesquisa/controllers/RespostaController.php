<?php

class Pesquisa_RespostaController extends Zend_Controller_Action
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
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Resposta');
        $servicePergunta = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pergunta');
        $this->view->formPesquisar = $service->getFormPesquisar();
        $this->view->pergunta = $servicePergunta->getByIdDetalhar(array('idfrase' => $this->getRequest()->getParam('idfrase')));
        $this->view->idfrase = $this->getRequest()->getParam('idfrase');
    }

    public function cadastrarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Resposta');
        $formResposta = $service->getFormResposta();
        $request = $this->getRequest();
        $idfrase = $request->getParam('idfrase');
        $success = false;

        if ($request->isPost()) {
            $resposta = $service->insert($request->getPost());
            if ($resposta) {
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
        $formResposta->populate(array('idfrase' => $idfrase));
        $this->view->formResposta = $formResposta;
    }

    public function editarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Resposta');
        $form = $service->getFormResposta();
        $request = $this->getRequest();
        $success = false;

        if ($request->isPost()) {
            $resposta = $service->update($request->getPost());
            if ($resposta) {
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
        $respostaResult = $service->getById($request->getParams());
        $form->populate($respostaResult);
        $this->view->desfrase = $respostaResult['desfrase'];
        $this->view->form = $form;
    }

//    public function excluirAction()
//    {
//        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Resposta');
//        $request = $this->getRequest();
//        $success = false;
//
//        if ($request->isPost()) {
//            $resposta = $service->excluir($request->getParams());
//            if ($resposta) {
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
//        $respostaResult = $service->getByIdDetalhar($request->getParams());
//        $this->view->resposta = $respostaResult;
//    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Resposta');
        $respostas = $service->getByIdDetalhar($this->getRequest()->getParams());
        $this->view->resposta = $respostas;
    }

    public function pesquisarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Resposta');
        $paginator = $service->retornaRespostasGrid($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

}
