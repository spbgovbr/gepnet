<?php

class Diagnostico_SugestaomelhoriaController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('cadastrar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('detalhar', 'json')
            ->initContext();
        $servicePerfilPessoa = new Default_Service_Perfilpessoa();
        $dadosEntrada = array(
            "idprojeto" => $this->_request->getParam('idprojeto'),
            "controller" => strtolower($this->_request->getControllerName()),
            "action" => strtolower($this->_request->getActionName()),
        );

        if (!$servicePerfilPessoa->isValidaControllerActionDiagnostico($dadosEntrada)) {
            $this->_helper->_flashMessenger->addMessage(array(
                    'status' => 'error',
                    'message' => 'Acesso negado...'
                )
            );
            $this->_helper->_redirector->gotoSimpleAndExit('forbidden', 'error', 'diagnostico');
        }
    }

    public function listarAction()
    {
        $service = new Diagnostico_Service_SugestaoMelhoria();
        $idDiagnostico = $this->_request->getParam('iddiagnostico');
        $this->view->formPesquisar = $service->getFormPesquisar();
        $this->view->iddiagnostico = $idDiagnostico;
        $this->view->msgimpressao = $this->_request->getParam('msgimpressao');
    }

    public function cadastrarAction()
    {
        $service = new Diagnostico_Service_SugestaoMelhoria();
        $request = $this->getRequest();
        $allParams = $this->_getAllParams();
        $formSugestaoMelhoria = $service->getFormSugestaoMelhoria($allParams);
//        exit('teste');
//            Zend_Debug::dump($request->getPost()); exit;
        $formPadronizacaoMelhoria = $service->getFormPadronizacaoMelhoria($request);
        $success = false;
        $sugestaoMelhoria = 0;
        if ($request->isPost()) {
            if (isset($allParams['desrevisada'])) {
                $padronizacaoMelhoria = $service->insertParonizacaoMelhoria($request->getPost());
            } else {
                $sugestaoMelhoria = $service->insert($request->getPost());
                $arrayMelhoria = $service->retornaPorIdMelhoria(array('idmelhoria' => $sugestaoMelhoria->idmelhoria));
            }
            if ($sugestaoMelhoria || $padronizacaoMelhoria) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors() ?: App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            if ($this->_request->isXmlHttpRequest()) {
                $this->view->ata = is_object($sugestaoMelhoria) ? get_object_vars($sugestaoMelhoria) : 0;
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false,
                    'idsituacao' => isset($arrayMelhoria['idsituacao']) ? $arrayMelhoria['idsituacao'] : 0
                );
            }
        }

        $arrayPopulate = array(
            'iddiagnostico' => $request->getParam('iddiagnostico')
        );

        if (isset($allParams['idsituacao'])) {
            $this->view->idsituacao = $allParams['idsituacao'];
        }

        $formSugestaoMelhoria->populate($arrayPopulate);
        $this->view->formSugestaoMelhoria = $formSugestaoMelhoria;
        $this->view->formPadronizacaoMelhoria = $formPadronizacaoMelhoria;
        $this->view->aba = $request->getParam('aba');
        $this->view->id = isset($allParams['id']);
    }

    public function editarAction()
    {
        $allParams = $this->_getAllParams();
        $service = new Diagnostico_Service_SugestaoMelhoria();
        $request = $this->getRequest();
        $arraySugestaoMelhoria = $service->retornaPorIdMelhoria($this->_getAllParams());
        $pontuacao = $service->retornaPontuacao($request->getParam('idmelhoria'));
        $formSugestaoMelhoria = $service->getFormSugestaoMelhoria(array_merge($allParams, $arraySugestaoMelhoria));
        $formPadronizacaoMelhoria = $service->getFormPadronizacaoMelhoria($request);
        $success = false;
        $sugestaoMelhoria = 0;
        if ($request->isPost()) {
            if (isset($allParams['desrevisada'])) {
                $padronizacaoMelhoria = $service->updatePadronizacaoMelhoria($request->getPost());
            } else {
                $sugestaoMelhoria = $service->update($request->getPost());
            }
            if ($sugestaoMelhoria || $padronizacaoMelhoria) {
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

        $formSugestaoMelhoria->populate($arraySugestaoMelhoria);
        $formPadronizacaoMelhoria->populate($arraySugestaoMelhoria);

        $this->view->pontuacao = $pontuacao;
        $this->view->sugestaoMelhoria = $arraySugestaoMelhoria;
        $this->view->formSugestaoMelhoria = $formSugestaoMelhoria;
        $this->view->formPadronizacaoMelhoria = $formPadronizacaoMelhoria;
        $this->view->aba = $request->getParam('aba');
    }

    public function excluirAction()
    {
        $service = new Diagnostico_Service_SugestaoMelhoria();
        $request = $this->getRequest();
        $success = false;

        $retorno = $service->getByIdDetalhar($request->getParams());
        $this->view->result = $retorno;

        if ($request->isPost()) {
            $dados = $request->getParams();

            $result = $service->excluir($dados);

            if ($result) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $success = false;
                $msg = $service->getErrors() ?: App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            /** Monta a mensagem de resposta do ajax */
            if ($this->_request->isXmlHttpRequest()) {

                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );

            } else {
                if ($success) {
                    $this->_helper->_flashMessenger->addMessage(array(
                        'status' => 'success',
                        'message' => 'Excluído com sucesso'
                    ));
                    $this->_helper->_redirector->gotoSimpleAndExit('listar', 'sugestaomelhoria', 'diagnostico', array(
                            'iddiagnostico' => $dados["iddiagnostico"]
                        )
                    );
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
            }
        }
    }

    public function detalharAction()
    {
        $service = new Diagnostico_Service_SugestaoMelhoria();
        $sugestaoMelhoria = $service->getByIdDetalhar($this->getRequest()->getParams());
        $this->view->sugestaomelhoria = $sugestaoMelhoria;
    }

    public function pesquisarAction()
    {
        $service = new Diagnostico_Service_SugestaoMelhoria();
        $paginator = $service->retornaSugestaoMelhoriaByDiagnostico($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function unidadesVinculadasAction()
    {
        $getAllParams = $this->_getAllParams();
        $service = new Diagnostico_Service_SugestaoMelhoria();
        $this->_helper->json->sendJson($service->getUnidadesVinculadas($getAllParams));
    }

    public function delegaciasAction()
    {
        $service = new Diagnostico_Service_SugestaoMelhoria();
        $this->_helper->json->sendJson($service->getAllDelegacias());
    }

    public function quantidadeagrupadoraAction()
    {
        $allParams = $this->_getAllParams();
        $service = new Diagnostico_Service_SugestaoMelhoria();
        $this->_helper->json->sendJson($service->quantidadeMelhoriaAgrupadora(
            array('desmelhoriaagrupadora' => $allParams['desmelhoriaagrupadora'])
        ));
    }

}
