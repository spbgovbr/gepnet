<?php

class Diagnostico_QuestionarioController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('add', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('dadosbasicos', 'json')
            ->addActionContext('secoes-add', 'json')
            ->addActionContext('secoes', 'json')
            ->addActionContext('pesquisar', 'json')
            ->addActionContext('pergunta-add', 'json')
            ->addActionContext('pergunta', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('excluir-resp-questinario', 'json')
            ->addActionContext('excluir-pergunta-questinario', 'json')
            ->addActionContext('vincular-questinario', 'json')
            ->addActionContext('lista-clonar', 'json')
            ->addActionContext('form-clonar', 'json')
            ->addActionContext('clonar-add', 'json')
            ->addActionContext('retornaperguntajson', 'json')
            ->addActionContext('perguntaeditar', 'json')
            ->addActionContext('opcaorespostaadd', 'json')
            ->addActionContext('retornaopcoesrespostajson', 'json')
            ->addActionContext('manipulaopcoesrespostajson', 'json')
            ->initContext();

        $servicePerfilPessoa = new Default_Service_Perfilpessoa();
        $dadosEntrada = array(
            "module" => strtolower($this->_request->getModuleName()),
            "controller" => strtolower($this->_request->getControllerName()),
            "action" => strtolower($this->_request->getActionName()),
        );
//        Zend_Debug::dump($dadosEntrada);die;

        if (!$servicePerfilPessoa->isValidaControllerActionDiagnostico($dadosEntrada)) {
            $this->_helper->_flashMessenger->addMessage(array(
                    'status' => 'error',
                    'message' => 'Acesso negado...'
                )
            );
            $this->_helper->_redirector->gotoSimpleAndExit('forbidden', 'error', 'diagnostico');
        }
    }


    public function indexAction()
    {
        $service = new Diagnostico_Service_Questionario();
        $this->view->formPesquisar = $service->getFormPesquisar($this->_getParam('iddiagnostico'));
        $this->view->iddiagnostico = $this->_getParam('iddiagnostico');
        $this->view->msgimpressao = $this->_request->getParam('msgimpressao');
    }


    public function pesquisarAction()
    {
        $service = new Diagnostico_Service_Questionario();
        $paginator = $service->listar($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }


    public function addAction()
    {
        $service = new Diagnostico_Service_Questionario();
        $form = $service->getForm();
        $this->view->form = $form;
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            /** @var Diagnostico_Model_Questionario $questionario */
            $questionario = new Diagnostico_Model_Questionario($dados);

            $questionario = $service->inserir($dados);

            if ($questionario) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            if ($this->_request->isXmlHttpRequest()) {

                $this->view->success = $success;
                $this->view->msg = array(
                    'idquestionariodiagnostico' => $questionario->idquestionariodiagnostico,
                    'tpquestionario' => $questionario->tipo,
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('dadosbasicos', 'questionario', 'diagnostico', array(
                            'idquestionariodiagnostico' => $questionario->idquestionariodiagnostico,
                            'tpquestionario' => $questionario->tipo
                        )
                    );
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }

        }
//        if ($this->_request->isPost()) {
//
//        }
    }

    public function editarAction()
    {

    }

    public function dadosbasicosAction()
    {
        $questionario = new Diagnostico_Service_Questionario();
        $form = $questionario->getForm(
            $this->_request->getParam('idquestionariodiagnostico')
        );
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_getAllParams();
            /** Update na tabela diagnostico questionario */
            $questionario = $questionario->update($dados);
            if ($questionario) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $questionario->getErrors();
            }
            if ($this->_request->isXmlHttpRequest()) {
                $response = new stdClass();
                $response->idquestionariodiagnostico = $questionario->idquestionariodiagnostico;
                $response->tpquestionario = $questionario->tipo;
                $this->view->dados = $response;
                $this->view->success = $success;
                $this->view->msg = array(
                    'idquestionariodiagnostico' => $questionario->idquestionariodiagnostico,
                    'tpquestionario' => $questionario->tipo,
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('dadosbasicos', 'questionario', 'diagnostico', array(
                            'idquestionariodiagnostico' => $questionario->idquestionariodiagnostico,
                            'tpquestionario' => $questionario->tipo
                        )
                    );
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        } else {
            $questionario = $questionario->getById($this->_request->getParams());
            $this->view->questionario = $questionario;

            $form->populate($questionario->formPopulate());
        }
        $this->view->form = $form;
    }

    public function secoesAddAction()
    {
        $serviceQuestionario = new Diagnostico_Service_Questionario();
        $service = new Diagnostico_Service_ItemSecao();
        $form = $service->getForm($this->_request->getParams());
        $success = false;

        $idquestionariodiagnostico = explode("/", $_SERVER["REQUEST_URI"]);
        $idquestionariodiagnostico = $idquestionariodiagnostico['7'];

        if ($this->_request->isPost()) {
            $dados = $this->_getAllParams();
            $itemsecao = $service->inserir($dados);
            if ($itemsecao) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        }
        $this->view->form = $form;
        $this->view->idquestionariodiagnostico = $idquestionariodiagnostico;

        $data = array(
            'idquestionariodiagnostico' => $this->_request->getParam('idquestionariodiagnostico'),
        );
        $this->view->nomeQuestionario = $serviceQuestionario->getById($data);

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {

                $this->view->success = $success;
                $this->view->msg = array(
                    'idquestionariodiagnostico' => $idquestionariodiagnostico,
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('secoes', 'questionario', 'diagnostico', array(
                            'idquestionariodiagnostico' => $idquestionariodiagnostico
                        )
                    );
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
            }
        }
    }

    public function secoesAction()
    {
        $serviceQuestionario = new Diagnostico_Service_Questionario();
        $service = new Diagnostico_Service_ItemSecao();
        $form = $service->getForm($this->_request->getParams());
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();

            $resposta = $service->update($dados);

            if ($resposta) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
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
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('secoes', 'questionario', 'diagnostico', array(
                            'idquestionariodiagnostico' => $dados['idquestionariodiagnostico']
                        )
                    );
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
            }
        } else {
            /** @var Diagnostico_Model_ItemSecao $modelItemSecao */
            $modelItemSecao = $service->getById($this->_request->getParams());

            /** @var Diagnostico_Model_Questionario $modelQuestionario */
            $modelQuestionario = $serviceQuestionario->getById($this->_request->getParams());

            $form->populate($modelItemSecao->formPopulate());

            $this->view->questionario = $modelQuestionario;
            $this->view->form = $form;
        }
    }

    public function opcaorespostaaddAction()
    {
        $service = new Diagnostico_Service_OpcaoResposta();
        $form = $service->getForm($this->_getAllParams());
        $this->view->form = $form;
        $this->view->tpregistro = $this->_getParam('tpregistro');
        $success = false;
        $msg = null;
        if ($this->_request->isPost()) {
            $dados = $this->_getAllParams();
            $pergunta = $service->inserir($dados);
            if ($pergunta) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }
        }
        if ($this->_request->isXmlHttpRequest()) {
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        }
    }


    public function manipulaopcoesrespostajsonAction()
    {
        $service = new Diagnostico_Service_OpcaoResposta();
        $success = false;
        $msg = null;
        if ($this->_request->isPost()) {
            $dados = $this->_getAllParams();
            unset($dados['id']);
            $pergunta = false;
            switch ($dados['operacao']) {
                case 'add' :
                    $pergunta = $service->inserir($dados);
                    break;
                case 'edit':
                    $pergunta = $service->update($dados);
                    if ($pergunta) {
                        $success = true;
                        $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
                    } else {
                        $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                    }
                    break;
                case 'del':
                    $pergunta = $service->excluir($dados);
                    if ($pergunta) {
                        $success = true;
                        $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
                    } else {
                        $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                    }
                    break;
            }
        }
    }

    public function perguntaAddAction()
    {
        $service = new Diagnostico_Service_Pergunta();
        $form = $service->getForm($this->_getAllParams());
        $this->view->form = $form;
        $success = false;
        $msg = null;
        if ($this->_request->isPost()) {
            $dados = $this->_getAllParams();
            $pergunta = $service->inserir($dados);

            if ($pergunta) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }
        }
        if ($this->_request->isXmlHttpRequest()) {
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        }
    }

    public function perguntaeditarAction()
    {
        $service = new Diagnostico_Service_Pergunta();
        $form = $service->getFormEditar($this->_getAllParams());
        $pergunta = $service->getById($this->_getAllParams(), false);

        $form->populate($pergunta);
        $this->view->form = $form;
        $success = false;
        $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
        if ($this->_request->isPost()) {
            $dados = $this->_getAllParams();
            $pergunta = $service->update($dados);
            if ($pergunta) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            }
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
                    $this->_helper->_redirector->gotoSimpleAndExit('pergunta', 'questionario', 'diagnostico',
                        array('idquestionariotico' => $dados['idprojeto']));
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function retornaperguntajsonAction()
    {
        $service = new Diagnostico_Service_Pergunta();
        $paginator = $service->buscaTodasPerguntasPorQuestionario($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function retornaopcoesrespostajsonAction()
    {
        $service = new Diagnostico_Service_OpcaoResposta();
        $paginator = $service->retornaTodasOpcoesRespostasPorPergunta($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function perguntaAction()
    {
        $this->view->idquestionario = $this->_getParam('idquestionariodiagnostico');;
    }

    public function detalharAction()
    {
        $service = new Diagnostico_Service_Questionario();
        $dadosQuestionario = $service->getById($this->_request->getParams());
        $this->view->questionario = $dadosQuestionario;
        $this->view->arrayPergunta = $service->getSecaoPerguntaOpcao($this->_request->getParams());
    }

    public function excluirAction()
    {
        $service = new Diagnostico_Service_Questionario();
        $request = $this->getRequest();
        $success = false;

        if ($request->isGet()) {
            $idquestionariodiagnostico = $this->getRequest()->getParam('id');
            $arrayParams = array(
                'idquestionario' => $idquestionariodiagnostico,
                'idquestionariodiagnostico' => $idquestionariodiagnostico
            );
            $result = $service->excluir($arrayParams);
            if ($result) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $success = false;
                $msg = $service->getErrors();
            }
            /** Monta a mensagem de resposta do ajax */
            if ($success) {
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
                    $this->_helper->_flashMessenger->addMessage(array(
                        'status' => 'success',
                        'message' => 'Excluído com sucesso'
                    ));
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'questionario', 'diagnostico');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
            }
        }
    }

    public function excluirRespQuestinarioAction()
    {
        $service = new Diagnostico_Service_Questionario();
        $request = $this->getRequest();
        $success = false;

        if ($request->isPost()) {

            $idquestionariodiagnostico = $this->getRequest()->getParam('id');
            $data = array(
                'idquestionario' => $this->getRequest()->getParam('idquestionariodiagnostico'),
                'idresposta' => $_POST['idresposta'],
                'idpergunta' => $_POST['idquestionario'],
            );

            $result = $service->excluirResp($data);
            if ($result) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $success = false;
                $msg = $service->getErrors();
            }
            /** Monta a mensagem de resposta do ajax */
            if ($success) {
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
                    $this->_helper->_flashMessenger->addMessage(array(
                        'status' => 'success',
                        'message' => 'Excluído com sucesso'
                    ));
                    $this->_helper->_redirector->gotoSimpleAndExit('pergunta', 'questionario', 'diagnostico', array(
                            'idquestionariodiagnostico' => $this->getRequest()->getParam('idquestionariodiagnostico')
                        )
                    );
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
            }
        }
    }

    public function excluirPerguntaQuestinarioAction()
    {
        $service = new Diagnostico_Service_Pergunta();
        //$pergunta = $service->getById($this->_getAllParams(),false);
        $this->view->pergunta = $this->_getAllParams();
        $success = false;
        $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
        if ($this->_request->isPost()) {
            $dados = $this->_getAllParams();
            $pergunta = $service->deletePergunta($dados);
            if ($pergunta) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            }
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
                    $this->_helper->_redirector->gotoSimpleAndExit('pergunta', 'questionario', 'diagnostico',
                        array('idquestionariotico' => $dados['idprojeto']));
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function listaClonarAction()
    {
        $service = new Diagnostico_Service_Questionario();
        $form = $service->getFormClonar($this->_request->getParams());

        $this->view->formPesquisar = $service->getFormPesquisar($this->_getParam('iddiagnostico'));
        $this->view->iddiagnostico = $this->_getParam('iddiagnostico');
        $this->view->msgimpressao = $this->_request->getParam('msgimpressao');
        $this->view->form = $form;
    }

    public function formClonarAction()
    {
        $service = new Diagnostico_Service_Questionario();
        $form = $service->getFormClonar($this->_request->getParams());

        $idquestionariodiagnostico = array(
            'idquestionariodiagnostico' => $this->_getParam('idquestionariodiagnostico'),
        );
        $questionario = $service->getById($idquestionariodiagnostico);

        $form->populate(
            array(
                'idquestionariodiagnostico' => $questionario->idquestionariodiagnostico,
                'nomquestionario' => $questionario->nomquestionario,
                'tipo' => $questionario->tipo,
                'observacao' => $questionario->observacao,

            )
        );

        $this->view->tipo = $questionario->tipo;
        $this->view->iddiagnostico = $this->_getParam('iddiagnostico');
        $this->view->msgimpressao = $this->_request->getParam('msgimpressao');
        $this->view->form = $form;
    }

    public function clonarAddAction()
    {
        $service = new Diagnostico_Service_Questionario();
        $form = $service->getForm();
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getParams();
            $novosDados = array(
                'nomquestionario' => $this->_getParam('nomquestionario') . ' (Clonado)',
                'tipo' => $this->_getParam('tipo'),
                'observacao' => $this->_getParam('observacao'),
                'idquestionario' => $this->_getParam('idquestionariodiagnostico')
            );

            $questionario = $service->inserirClonado($novosDados);
            if ($questionario) {
                $maxId = $service->getMaxId();
                if (empty($questionario->idquestionariodiagnostico)) {
                    $id = $maxId;
                } else {
                    $id = $questionario->idquestionariodiagnostico;
                }
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CLONADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        }
        $this->view->form = $form;
        if ($this->_request->isXmlHttpRequest()) {

            $this->view->success = $success;
            $this->view->msg = array(
                'tpquestionario' => $this->_getParam('tipo'),
                'idquestionariodiagnostico' => $id,
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
            );
        } else {
            if ($success) {
                $this->_helper->_redirector->gotoSimpleAndExit('dadosbasicos', 'questionario', 'diagnostico', array(
                        'idquestionariodiagnostico' => $id,
                        'tpquestionario' => $this->_getParam('tipo')
                    )
                );
            }
            $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
            $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
        }
    }
}
