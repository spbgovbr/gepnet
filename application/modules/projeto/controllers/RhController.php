<?php

class Projeto_RhController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('addinterno', 'json')
            ->addActionContext('addexterno', 'json')
            ->addActionContext('editarinterno', 'json')
            ->addActionContext('editarexterno', 'json')
            ->addActionContext('excluirparte', 'json')
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
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $form = $service->getFormPesquisar();
        $idProjeto = $this->_request->getParam('idprojeto');
        $form->populate(array('idprojeto' => $idProjeto));
        $this->view->formPesquisar = $form;
        $this->view->idprojeto = $idProjeto;
    }

    public function gridRhAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $paginator = $service->retornaPartesGrid($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function addinternoAction()
    {
        /**
         * @var $service Projeto_Service_ParteInteressada
         */
        $service = new Projeto_Service_ParteInteressada();

        $form = $service->getForm();
        $formExterno = $service->getFormExterno();

        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $dados['idpessoa'] = $dados['idparteinteressada'];

            $verificarEmProjeto = $service->verificarPartesPorProjeto($dados);

            $verificarExistencia = $verificarEmProjeto['is_parteinteressada'];

            if ($verificarExistencia == true) {
                $success = false;
                $msg = App_Service_ServiceAbstract::REGISTRO_DUPLICADO;
                if ($this->_request->isXmlHttpRequest()) {
                    // $this->view->parte = is_object($ctParte) ? get_object_vars($ctParte) : NULL;
                    $this->view->parte = is_object($verificarExistencia) ? get_object_vars($verificarExistencia) : null;
                    $this->view->success = $success;
                    $this->view->msg = array(
                        'text' => $msg,
                        'type' => 'warning',
                        'hide' => true,
                        'closer' => true,
                        'sticker' => false
                    );
                }
            } else {
                /** @var Projeto_Model_Parteinteressada $parte */
                $parte = $service->insertInterno($dados);
                if ($parte) {
                    $success = true; ###### AUTENTICATION SUCCESS
                    /** Cadastra na linha do tempo (auditoria). */
                    $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                    $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                    $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                    $dados['idprojeto'] = $dados['idprojeto'];
                    $serviceLinhaTempo->inserir($dados);
                    $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
                    if (!empty($parte->nomfuncao)) {
                        $serviceGerencia = new Projeto_Service_Gerencia();
                        $serviceGerencia->updatePartesProjeto($parte);
                    }
                } else {
                    $msg = $service->getErrors();
                }

                if ($this->_request->isXmlHttpRequest()) {
                    $this->view->parte = is_object($parte) ? get_object_vars($parte) : null;
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
        } else {
            $this->view->idprojeto = $this->_request->getParam('idprojeto');
            $this->view->form = $form;
            $this->view->formExterno = $formExterno;
        }
    }

    public function addexternoAction()
    {
        $service = new Projeto_Service_ParteInteressada();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();

            $verificarEmProjeto = $service->verificarPartesPorProjeto($dados);
            $verificarExistencia = $verificarEmProjeto['is_parteinteressada'];

            if ($verificarExistencia == true) {
                $success = false;
                $msg = App_Service_ServiceAbstract::REGISTRO_DUPLICADO;
                if ($this->_request->isXmlHttpRequest()) {
                    // $this->view->parte = is_object($ctParte) ? get_object_vars($ctParte) : NULL;
                    $this->view->parte = is_object($verificarExistencia) ? get_object_vars($verificarExistencia) : null;
                    $this->view->success = $success;
                    $this->view->msg = array(
                        'text' => $msg,
                        'type' => ($success) ? 'success' : 'error',
                        'hide' => true,
                        'closer' => true,
                        'sticker' => false
                    );
                }
            } else {
                $parte = $service->insertExterno($dados);
                if ($parte) {
                    $success = true; ###### AUTENTICATION SUCCESS
                    /** Cadastra na linha do tempo (auditoria). */
                    $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                    $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                    $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                    $dados['idprojeto'] = $dados['idprojeto'];
                    $serviceLinhaTempo->inserir($dados);
                    $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
                } else {
                    $msg = $service->getErrors();
                }

                if ($this->_request->isXmlHttpRequest()) {
                    $this->view->parte = is_object($parte) ? get_object_vars($parte) : null;
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
    }

    public function editarinternoAction()
    {
        $service = new Projeto_Service_ParteInteressada();

        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();

            $parte = $service->updateInterno($dados);

            if ($parte) {
                $success = true; /* AUTENTICATION SUCCESS */
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }

            $this->view->success = $success;
            $this->view->msg = array(
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );


        } else {
            $parte = $service->retornaPorId($this->_request->getParams(), true)->toArray();
            $form->populate($parte);
            $this->view->form = $form;
            $this->view->idparteinteressadafuncao = explode(',', $parte['idparteinteressadafuncao']);
        }


        $initCombo = $service->getFuncaoProjeto();
        $this->view->combofuncao = $initCombo;

        $this->view->idprojeto = $this->getRequest()->getParam('idprojeto');
    }

    public function editarexternoAction()
    {
        $service = new Projeto_Service_ParteInteressada();
        $formExterno = $service->getFormExterno();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();

            $parte = $service->updateExterno($dados);

            if ($parte) {
                $success = true; ###### AUTENTICATION SUCCESS
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojetoexterno"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }

            if ($this->_request->isXmlHttpRequest()) {
                $this->view->parte = is_object($parte) ? get_object_vars($parte) : null;
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            }
        } else {

            $parte = $service->retornaPorId($this->_request->getParams(), true)->toArray();
            $parte = $service->alteraKeyArray($parte);
            $formExterno->populate($parte);
            $this->view->formExterno = $formExterno;
            $this->view->idparteinteressadafuncaoexterno = explode(',', $parte['idparteinteressadafuncaoexterno']);
        }


        $initCombo = $service->getFuncaoProjeto(false);
        $this->view->combofuncao = $initCombo;

        $this->view->idprojeto = $this->getRequest()->getParam('idprojeto');
    }

    public function excluirparteAction()
    {
        $service = new Projeto_Service_ParteInteressada();
        $success = false;
        $request = $this->getRequest();

        if ($request->isPost()) {
            $parte = $service->excluir($request->getParams());

            if ($parte) {
                $success = true; ###### AUTENTICATION SUCCESS
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'E'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $request->getParams()['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
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

            return;
        }

        $parte = $service->retornaPorId($request->getParams(), true);
        $this->view->parte = $parte;
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $request = $this->getRequest();

        $parte = $service->retornaPorId($request->getParams(), true);
        $this->view->parte = $parte;
    }

}
