<?php

class Pesquisa_QuestionarioController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('cadastrar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('detalhar', 'json')
            ->addActionContext('vincular-pergunta', 'json')
            ->addActionContext('editar-vinculo-pergunta', 'json')
            ->addActionContext('desvincular-pergunta', 'json')
            ->addActionContext('alterar-disponibilidade', 'json')
            ->addActionContext('status-questionario', 'json')
            ->initContext();
    }

    public function listarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
        $this->view->formPesquisar = $service->getFormPesquisar();
    }

    public function pesquisarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
        $paginator = $service->retornaQuestionarioGrid($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function cadastrarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
        $formQuestionario = $service->getFormQuestionario();
        $request = $this->getRequest();
        $success = false;

        if ($request->isPost()) {
            $questionario = $service->insert($request->getPost());

            if ($questionario) {
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

        $this->view->formQuestionario = $formQuestionario;
    }

    public function editarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
        $form = $service->getFormQuestionario();
        $request = $this->getRequest();
        $success = false;
        $disponivel = $service->getById($request->getParams());

        if ($disponivel['disponivel'] == Pesquisa_Model_Questionario::DISPONILVEL) {
            $this->view->boolDisponivel = true;
        } else {
            if ($request->isPost()) {
                $questionario = $service->update($request->getPost());
                if ($questionario) {
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
        }
        $questionarioResult = $service->getByIdAndEscritorio($request->getParams());
        $form->populate($questionarioResult);
        $this->view->form = $form;
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
        $serviceQuestionarioFrase = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionariofrase');
        $questionarios = $service->getByIdDetalhar($this->getRequest()->getParams());
        $perguntas = $serviceQuestionarioFrase->getAllByIdQuestionario($this->getRequest()->getParams());
        $this->view->questionario = $questionarios;
        $this->view->perguntas = $perguntas;
    }


    /* Perguntas do questionario */

    public function listarPerguntasAction()
    {
        $params = $this->getRequest()->getParams();
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionariofrase');
        $serviceQuestionario = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
        $questionario = $serviceQuestionario->getByIdDetalhar(array('idquestionario' => $params['idquestionario']));

        $this->view->questionario = $questionario;
        $this->view->formPesquisar = $service->getFormPesquisar();
        $this->view->idquestionario = $params['idquestionario'];
    }

    public function pesquisarPerguntasAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionariofrase');
        $paginator = $service->retornaQuestionarioGrid($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function vincularPerguntaAction()
    {
        $serviceQuestionarioFrase = App_Service_ServiceAbstract::getService('Pesquisa_Service_QuestionarioFrase');
        $formQuestionarioFrase = $serviceQuestionarioFrase->getFormQuestionarioFrase();
        $request = $this->getRequest();
        $success = false;

        $questionario = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
        $disponivel = $questionario->getById($request->getParams());
        if ($disponivel['disponivel'] == Pesquisa_Model_Questionario::DISPONILVEL) {
            $this->view->boolDisponivel = true;
        } else {
            if ($request->isPost()) {
                $questionarioFrase = $serviceQuestionarioFrase->insert($request->getPost());

                if ($questionarioFrase) {
                    $success = true; ###### AUTENTICATION SUCCESS
                    $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
                } else {
                    $msg = $serviceQuestionarioFrase->getErrors() ?: App_Service_ServiceAbstract::ERRO_GENERICO;
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
        }
        $formQuestionarioFrase->populate(array('idquestionario' => $request->getParam('idquestionario')));
        $this->view->formQuestionarioFrase = $formQuestionarioFrase;
    }

    public function editarVinculoPerguntaAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_QuestionarioFrase');
        $formQuestionarioFrase = $service->getFormQuestionarioFrase();
        $request = $this->getRequest();
        $success = false;

        $questionario = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
        $disponivel = $questionario->getById($request->getParams());
        if ($disponivel['disponivel'] == Pesquisa_Model_Questionario::DISPONILVEL) {
            $this->view->boolDisponivel = true;
        } else {
            if ($request->isPost()) {
                $questionarioFrase = $service->update($request->getPost());
                if ($questionarioFrase) {
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
        }
        $questionarioFraseResult = $service->getById($request->getParams());
        $formQuestionarioFrase->populate($questionarioFraseResult);

        $this->view->desfrase = $questionarioFraseResult['desfrase'];
        $this->view->nomescritorio = $questionarioFraseResult['nomescritorio'];
        $this->view->formQuestionarioFrase = $formQuestionarioFrase;
    }

    public function desvincularPerguntaAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_QuestionarioFrase');
        $request = $this->getRequest();
        $success = false;

        $questionario = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
        $disponivel = $questionario->getById($request->getParams());
        if ($disponivel['disponivel'] == Pesquisa_Model_Questionario::DISPONILVEL) {
            $this->view->boolDisponivel = true;
        } else {
            if ($request->isPost()) {
                $questionarioFrase = $service->excluir($request->getParams());
                if ($questionarioFrase) {
                    $success = true; ###### AUTENTICATION SUCCESS
                    $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
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
        }
        $questionarioFraseResult = $service->getByIdDetalhar($request->getParams());
        $this->view->nomescritorio = $questionarioFraseResult['nomescritorio'];
        $this->view->questionarioFrase = $questionarioFraseResult;
    }

    public function detalharVinculoPerguntaAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_QuestionarioFrase');
        $questionarioFrase = $service->getByIdDetalhar($this->getRequest()->getParams());
        $this->view->questionarioFrase = $questionarioFrase;
    }

    public function alterarDisponibilidadeAction()
    {
        $request = $this->getRequest();
        $data = $request->getPost();
        $success = false;


        if ($request->isPost()) {

            $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_QuestionarioFrase');
            $boolPerguntasRespostas = $service->retornaPerguntasRespostasByQuestionario($this->getRequest()->getParams());

            $isValid = true;
            if ($boolPerguntasRespostas) {
                $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
                $result = $service->alterarDisponibilidade($data);
                if ($result) {
                    $success = true; ###### AUTENTICATION SUCCESS
                    $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
                } else {
                    $msg = $service->getErrors() ?: App_Service_ServiceAbstract::ERRO_GENERICO;
                }
            } else {
                $isValid = false;
                $msg = $service->getErrors() ?: App_Service_ServiceAbstract::ERRO_GENERICO;
            }
            $this->view->success = $success;
            $this->_helper->json->sendJson(array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false,
                'isValid' => $isValid,
            ));
        }
    }

    public function statusQuestionarioAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
        $questionario = $service->getById($this->getRequest()->getParams());
        if ($questionario['disponivel'] == Pesquisa_Model_Questionario::DISPONILVEL) {
            $success = true;
        } else {
            $success = false;
        }

        $this->_helper->json->sendJson(array('disponivel' => $success));
    }
}
