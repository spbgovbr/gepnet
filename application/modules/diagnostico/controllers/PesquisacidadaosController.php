<?php

class Diagnostico_PesquisacidadaosController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('vincularquestionariocidadao', 'json')
            ->addActionContext('buscaquestionariovinculadocidadao', 'json')
            ->addActionContext('responderquestionariocidadao', 'json')
            ->addActionContext('listarquestionariorespondidocidadao', 'json')
            ->addActionContext('buscaquestionariorespondidocidadao', 'json')
            ->initContext();

        $servicePerfilPessoa = new Default_Service_Perfilpessoa();
        $dadosEntrada = array(
            "iddiagnostico" => $this->_request->getParam('iddiagnostico'),
            "module" => strtolower($this->_request->getModuleName()),
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

    public function vincularquestionariocidadaoAction()
    {
        $success = false;
        $service = new Diagnostico_Service_QuestionarioVinculado();

        if ($this->_request->isPost()) {
            $dados = $this->_request->getParams();
            $arrayQuestionario = explode(",", $dados['questionario']);

            if (!empty($dados['questionario']) && count($arrayQuestionario) > 0) {
                unset($dados['questionario']);
                $dados['questionario'] = $arrayQuestionario;
                $retorno = $service->vincularQuestionario($dados);
                if ($retorno) {
                    $success = true; ###### AUTENTICATION SUCCESS
                    $msg = App_Service_ServiceAbstract::REGISTRO_VINCULADO_COM_SUCESSO;
                } else {
                    $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                }
            } else {
                if ($service->isQuestionarioVinculado($dados)) {
                    $resposta = $service->desvincularQuestionario($dados);
                    if ($resposta) {
                        $success = true; ###### SUCCESS
                        $msg = App_Service_ServiceAbstract::REGISTRO_DESVINCULADO_COM_SUCESSO;
                    } else {
                        $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                    }
                } else {
                    $msg = "ATENÇÃO: O diagnóstico necessita de um questionário para vincular.";
                }
            }

        } else {
            $this->view->aba = 'vincularQuestionario';
            $form = $service->getFormQuestionarioVincular($this->_request->getParams());
            $this->view->form = $form;
            $this->view->iddiagnostico = $this->_request->getParam('iddiagnostico');
        }

        if ($this->_request->isXmlHttpRequest()) {
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => false,
                'closer' => true,
                'sticker' => false
            );
        }
    }

    public function buscaquestionariovinculadocidadaoAction()
    {
        $service = new Diagnostico_Service_QuestionarioVinculado();
        $paginator = $service->listaQuestionarioVinculado($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }


    public function listarquestionariocidadaoAction()
    {
        $service = new Diagnostico_Service_QuestionarioVinculado();
        $form = $service->getFormPesquisaQuestionarioVinculado($this->_request->getParams());
        $this->view->aba = 'listarQuestionario';
        $this->view->iddiagnostico = $this->_request->getParam('iddiagnostico');
        $this->view->form = $form;
    }

    public function gerarnumeroquestionariocidadaoAction()
    {

        if ($this->_request->isGet()) {
            $serviceQuestionarioRespondido = new Diagnostico_Service_QuestionarioDiagnosticoRespondido();
            $resposta = $serviceQuestionarioRespondido->gerarNumeroSequencial($this->_request->getParams());
            $success = 'error';
            $msg = App_Service_ServiceAbstract::GERADORNUMERICO_ERROR;

            if ($resposta) {
                $success = true;
                $dados = $this->_request->getParams();
                $dados['numero'] = (int)$resposta->numero;
            }

            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text               ' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $request = clone $this->getRequest();
                    $request->setActionName('responderquestionariocidadao')
                        ->setParams(array('numero' => (int)$resposta->numero));
                    $this->_helper->actionStack($request);
                    $this->_helper->_redirector->gotoSimpleAndExit($request->getActionName(), null, null, $dados);
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('listarquestionariocidadao', null, null, $dados);
            }
        }
    }

    public function responderquestionariocidadaoAction()
    {
        $service = new Diagnostico_Service_Pergunta();
        $serviceQuestionarioVinculado = new Diagnostico_Service_QuestionarioVinculado();
        $serviceRespostaQuestionario = new Diagnostico_Service_RespostaQuestionario();
        $success = 'error';
        $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
        $dados = $this->_request->getParams();

        $questionario = $serviceQuestionarioVinculado->getById($dados);
        $perguntas = $service->retornaPerguntaQuestionario($dados);
        $form = $serviceRespostaQuestionario->getForm($dados);
        $this->view->form = $form;
        $this->view->questionario = $questionario;
        $this->view->perguntas = $perguntas;
        $this->view->iddiagnostico = $this->_request->getParam('iddiagnostico');
        $this->view->aba = 'responderQuestionario';

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if ($serviceRespostaQuestionario->validaPerguntaObrigatoria($dados)) {

                $resposta = $serviceRespostaQuestionario->inserir($dados);

                if ($resposta) {
                    $success = 'success';
                    $msg = App_Service_ServiceAbstract::FORMULARIO_RESPONDIDO_SUCCESS;
                }
            } else {
                $success = 'info';
                $msg = App_Service_ServiceAbstract::OBRIGATORIEDADE_ERROR;
            }

            if ($this->_request->isXmlHttpRequest()) {
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => $success,
                    'hide' => false,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                $this->_helper->_flashMessenger->addMessage(array('status' => $success, 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('responderquestionariocidadao', null, null, $dados);
            }
        }
    }

    public function listarquestionariorespondidocidadaoAction()
    {
        $service = new Diagnostico_Service_QuestionarioRespondido();
        $form = $service->getFormPesquisaQuestionarioRespondido($this->_request->getParams());
        $this->view->aba = 'listarQuestionarioRespondido';
        $this->view->iddiagnostico = $this->_request->getParam('iddiagnostico');
        $this->view->form = $form;
    }

    public function buscaquestionariorespondidocidadaoAction()
    {
        $service = new Diagnostico_Service_QuestionarioRespondido();
        $paginator = $service->listaQuestionarioRespondido($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function visualizarquestionariorespondidocidadaoAction()
    {
        $service = new Diagnostico_Service_QuestionarioRespondido();
        $serviceQuestionario = new Diagnostico_Service_QuestionarioVinculado();
        $serviceRespostaQuestionario = new Diagnostico_Service_RespostaQuestionario();
        $questionario = $serviceQuestionario->getById($this->_request->getParams());
        $perguntas = $service->retornaQuestionarioRespondido($this->_request->getParams());
        $form = $serviceRespostaQuestionario->getForm($this->_request->getParams());

        $this->view->form = $form;
        $this->view->questionario = $questionario;
        $this->view->iddiagnostico = $this->_request->getParam('iddiagnostico');
        $this->view->perguntas = $perguntas;
        $this->view->aba = 'visualizarQuestionario';
    }

    public function sumariocidadaoAction()
    {
        $service = new Diagnostico_Service_Sumario();
        $this->view->aba = 'sumario';
        $this->view->iddiagnostico = $this->_request->getParam('iddiagnostico');
        $qtdQuestRespondido = $service->retornaQuantitativoQuestionarioRespondido($this->_request->getParams());
        $mediaGeralRespNumerica = $service->retornaMediaGeralRespNumericaPorDiagnostico($this->_request->getParams(),
            $qtdQuestRespondido);
        $mediaGeralPorResposta = $service->retornaSomatorioEscalaLinkertPorResposta($this->_request->getParams());
        $this->view->qtdQuestionarioRespondido = $qtdQuestRespondido;
        $this->view->mediaGeralRespNumerica = $mediaGeralRespNumerica;
        $this->view->mediaGeralPorResposta = $mediaGeralPorResposta;
    }

}