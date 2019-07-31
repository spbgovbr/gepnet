<?php

class  Projeto_DiarioController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('cadastrar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('excluir', 'json')
            ->initContext();
        $servicePerfilPessoa = new Default_Service_Perfilpessoa();
        $dadosEntrada = array(
            "idprojeto" => $this->_request->getParam('idprojeto'),
            "controller" => strtolower($this->_request->getControllerName()),
            "action" => strtolower($this->_request->getActionName()),
        );

        if (!$servicePerfilPessoa->isValidaControllerAction($dadosEntrada)) {
            $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => 'Acesso negado...'));
            $this->_helper->_redirector->gotoSimpleAndExit('forbidden', 'error', 'projeto');
        }
    }

    public function listarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Diariobordo');
        $idProjeto = $this->_request->getParam('idprojeto');
        $this->view->formPesquisar = $service->getFormPesquisar();
        $this->view->idprojeto = $idProjeto;
    }

    public function cadastrarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Diariobordo');
        $form = $service->getFormDiario();
        $request = $this->getRequest();
        $success = false;

        if ($request->isPost()) {
            $ata = $service->insert($request->getPost());
            if ($ata) {
                $success = true; ###### AUTENTICATION SUCCESS
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $request->getPost()['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors() ?: App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            if ($this->_request->isXmlHttpRequest()) {
                $this->view->ata = is_object($ata) ? get_object_vars($ata) : null;
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

        $form->populate(array('idprojeto' => $request->getParam('idprojeto')));
        $this->view->form = $form;
    }

    public function editarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Diariobordo');
        $form = $service->getFormDiario();
        $request = $this->getRequest();
        $success = false;

        if ($request->isPost()) {
            $diario = $service->update($request->getPost());
            if ($diario) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors() ?: App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            if ($this->_request->isXmlHttpRequest()) {
                $this->view->ata = is_object($diario) ? get_object_vars($diario) : null;
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
        $diarioResult = $service->getById($this->getRequest()->getParams());

        $form->populate($diarioResult);
        $this->view->nompessoa = $diarioResult['nompessoa'];
        $this->view->form = $form;
    }

    public function excluirAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Diariobordo');
        $request = $this->getRequest();
        $diario = $service->getById($request->getParams());
        $this->view->nompessoa = $diario['nompessoa'];
        $this->view->diario = $diario;
        $success = false;

        if ($request->isPost()) {
            $idProjeto = $service->getById(array('iddiariobordo' => $request->getParams()['iddiariobordo']))["idprojeto"];
            $diario = $service->excluir($request->getParams());
            if ($diario) {
                $success = true; ###### AUTENTICATION SUCCESS
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'E'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $idProjeto;
                $serviceLinhaTempo->inserir($dados);
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

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Diariobordo');
        $diario = $service->getById($this->getRequest()->getParams());
        $this->view->nompessoa = $diario['nompessoa'];
        $this->view->diario = $diario;
    }

    public function pesquisarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Diariobordo');
        $paginator = $service->retornaDiarioByProjeto($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }
}
