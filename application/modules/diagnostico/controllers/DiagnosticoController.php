<?php

class Diagnostico_DiagnosticoController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('add', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('detalhar', 'json')
            ->addActionContext('clonar-add', 'json')
            ->addActionContext('gera-sequence', 'json')
            ->initContext();
        $servicePerfilPessoa = new Default_Service_Perfilpessoa();
        $dadosEntrada = array(
            "iddiagnostico" => (int)$this->_request->getParam('iddiagnostico'),
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

    public function indexAction()
    {
        $service = new Diagnostico_Service_Diagnostico();
        $this->view->formPesquisar = $service->getFormPesquisar($this->_getParam('iddiagnostico'));
        $this->view->iddiagnostico = $this->_getParam('iddiagnostico');
        $this->view->msgimpressao = $this->_request->getParam('msgimpressao');
    }

    public function pesquisarAction()
    {
        $service = new Diagnostico_Service_Diagnostico();
        $paginator = $service->listar($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function detalharAction()
    {
        $service = new Diagnostico_Service_Diagnostico();
        $sugestaoMelhoria = new Diagnostico_Service_SugestaoMelhoria();
        $diagnostico = $service->getById($this->_request->getParams());
        $this->view->diagnostico = $diagnostico;
        $this->view->quantidadeMelhoria = $sugestaoMelhoria->getMelhoriaToDiagnostico(
            array('iddiagnostico' => $diagnostico->iddiagnostico))['quantidade'];
    }

    public function addAction()
    {
        $service = new Diagnostico_Service_Diagnostico();
        $form = $service->getForm();

        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();

            $diagnostico = $service->inserir($dados);

            if ($diagnostico) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }

            if ($this->_request->isXmlHttpRequest()) {
                $response = new stdClass();
                $response->iddiagnostico = $diagnostico->iddiagnostico;
                $this->view->dados = $response;
                $this->view->success = $success;
                $this->view->msg = array(
                    'iddiagnostico' => $diagnostico->iddiagnostico,
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('detalhar', 'diagnostico', 'diagnostico',
                        array('iddiagnostico' => $diagnostico->iddiagnostico));
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        } else {
            $serviceCronograma = new Projeto_Service_AtividadeCronograma();
            $arrayFeriadosFixos = $serviceCronograma->retornaFeriadosFixos();
            $feriadosFixos = "";
            for ($i = 0; $i < count($arrayFeriadosFixos); $i++) {
                $feriadosFixos = $feriadosFixos . ($feriadosFixos != "" ? "," : "") . $arrayFeriadosFixos[$i]['diaferiado'] . ";" . $arrayFeriadosFixos[$i]['mesferiado'] . ";" . $arrayFeriadosFixos[$i]['anoferiado'];
            }
            $this->view->feriadosfixos = $feriadosFixos;

            $this->view->form = $form;
            $this->view->mostraModel = false;
        }
    }

    public function geraSequenceAction()
    {
        $service = new Diagnostico_Service_Diagnostico();
        $id = $this->_getParam('unidade');
        $this->_helper->json->sendJson($service->getSequence($id));
    }

    public function editarAction()
    {
        $service = new Diagnostico_Service_Diagnostico();
        $sugestaoMelhoria = new Diagnostico_Service_SugestaoMelhoria();
        $form = $service->getForm($this->_request->getParam('iddiagnostico'));
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_getAllParams();
            /** Update na tabela diagnostico */
            $qtdeMelhoria = $sugestaoMelhoria->getMelhoriaToDiagnostico(array('iddiagnostico' => $this->_request->getParam('iddiagnostico')))['quantidade'];
            if ($qtdeMelhoria == 0) {
                $diagnostico = $service->update($dados);
                if ($diagnostico) {
                    $success = true;
                    $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
                } else {
                    $msg = $service->getErrors();
                }
            } else {
                $success = false;
                $diagnostico['iddiagnostico'] = 0;
                $msg = 'Não foi possível atualizar os dados. Existe uma ou mais sugestões de melhorias cadastradas para este diagnóstico.';
            }

            if ($this->_request->isXmlHttpRequest()) {
                $response = new stdClass();
                $response->iddiagnostico = isset($diagnostico->iddiagnostico) ? $diagnostico->iddiagnostico : 0;
                $this->view->dados = $response;
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
                    $this->_helper->_redirector->gotoSimpleAndExit('detalhar', 'diagnostico', 'diagnostico',
                        array('iddiagnostico' => $diagnostico->iddiagnostico));
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        } else {
            $diagnostico = $service->getById($this->_request->getParams());
            $form->populate($diagnostico->formPopulate());
        }

        $equipe = $service->fetchPairsPorDiagnostico($diagnostico['iddiagnostico']);
        $this->view->equipe = $equipe;
        $this->view->iddiagnostico = $diagnostico['iddiagnostico'];

        $idpessoacheckbox = $service->getCheckbox($diagnostico['iddiagnostico']);
        $this->view->idpessoacheckbox = $idpessoacheckbox;

        $this->view->form = $form;
    }

    public function clonarAction()
    {
        $service = new Diagnostico_Service_Diagnostico();
        $this->view->formPesquisar = $service->getFormPesquisar($this->_getParam('iddiagnostico'));
        $this->view->iddiagnostico = $this->_getParam('iddiagnostico');
        $this->view->msgimpressao = $this->_request->getParam('msgimpressao');
    }

    public function excluirAction()
    {
        $service = new Diagnostico_Service_Diagnostico();
        $request = $this->getRequest();
        $success = false;

        if ($request->isGet()) {
            $iddiagnostico = (int)$this->getRequest()->getParam('iddiagnostico');

            $result = $service->excluir((int)$iddiagnostico);
            if ($result) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
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
                        'message' => 'Cadastrado com sucesso'
                    ));
                    $this->_helper->_redirector->gotoSimpleAndExit('diagnostico', 'diagnostico', 'pesquisar');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
            }
        }
    }

    public function unidadesFilhasAction()
    {
        $service = new Diagnostico_Service_Diagnostico();
        $this->view->unidadesFilhas = $service->getUnidadesFilhas($this->_getParam('id'));
    }

    public function clonarAddAction()
    {
        $service = new Diagnostico_Service_Diagnostico();
        $form = $service->getFormClonar($this->_getParam('iddiagnostico'));
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            unset($dados['iddiagnostico']);
            $dados['dsdiagnostico'] = $dados['dsdiagnostico'] . ' (Diagnóstico Clonado)';
            $diagnostico = $service->inserir($dados);
            if ($diagnostico) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CLONADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }

            if ($this->_request->isXmlHttpRequest()) {
                $this->view->msg = array(
                    'iddiagnostico' => $diagnostico->iddiagnostico,
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('detalhar', 'diagnostico', 'diagnostico',
                        array('iddiagnostico' => $diagnostico->iddiagnostico));
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        } else {
            $diagnostico = $service->getById(array(
                'iddiagnostico' => $this->_getParam('iddiagnostico')
            ));
            $form->populate($diagnostico->formPopulate());
        }

        $equipe = $service->fetchPairsPorDiagnostico($diagnostico['iddiagnostico']);
        $this->view->equipe = $equipe;

        $idpessoacheckbox = $service->getCheckbox($diagnostico['iddiagnostico']);
        $this->view->idpessoacheckbox = $idpessoacheckbox;

        $this->view->form = $form;
    }

}
