<?php

class Projeto_SolicitacaomudancaController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('add', 'json')
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

    public function indexAction()
    {
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
    }

    public function addAction()
    {

        $idprojeto = $this->_request->getParam('idprojeto');
        $this->view->idprojeto = $idprojeto;
        $service = new Projeto_Service_Mudanca();
        $form = $service->getForm(array('idprojeto' => $idprojeto));
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $aceite = $service->inserir($dados);
            if ($aceite) {
                $success = true;
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
        }
        $this->view->form = $form;
        if ($this->_request->isPost()) {
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
                    $this->_helper->_redirector->gotoSimpleAndExit('add', 'termoaceite', 'projeto');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('add', 'termoaceite', 'projeto');
            }
        }
    }

    public function editarAction()
    {
        $idprojeto = $this->_request->getParam('idprojeto');
        $this->view->idprojeto = $idprojeto;
        $service = new Projeto_Service_Mudanca();
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $mudanca = $service->editar($dados);
            if ($mudanca) {
                $success = true;
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
        } else {
            $mudanca = $service->getById($this->_request->getParams());
            $mudanca->flaaprovada = ($mudanca->flaaprovada == "Sim" ? "S" : "N");
            $form->populate($mudanca->formPopulate());
        }

        $this->view->form = $form;
        if ($this->_request->isPost()) {
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
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'solicitacaomudanca', 'projeto',
                        array('idprojeto' => $idprojeto));
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function excluirAction()
    {
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $service = new Projeto_Service_Mudanca();
        $mudanca = $service->getById($this->_request->getParams());
        $this->view->mudanca = $mudanca;
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $excluiu = $service->excluir($dados);
            if ($excluiu) {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'E'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $this->_request->getParam('idprojeto');
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
        }
    }

    public function detalharAction()
    {
        $service = new Projeto_Service_Mudanca();
        $this->view->mudanca = $service->getById($this->_request->getParams());
    }

    public function pesquisarjsonAction()
    {
        $service = new Projeto_Service_Mudanca();
        $resultado = $service->pesquisar($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado->toJqgrid());
    }


    public function imprimirAction()
    {
        $this->_helper->layout->disableLayout();
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceMudanca = new Projeto_Service_Mudanca();

        $this->view->projeto = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());
        $this->view->mudanca = $serviceMudanca->getById($this->_request->getParams());
        $this->_helper->layout->disableLayout();

        $html = $this->view->render('/_partials/solicitacao-mudanca-imprimir.phtml');
        $serviceImprimir = App_Service_ServiceAbstract::getService('Default_Service_Impressao');
        $serviceImprimir->gerarPdf($html);
    }

    public function imprimirtodosAction()
    {
        $this->_helper->layout->disableLayout();
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceMudanca = new Projeto_Service_Mudanca();

        $this->view->projeto = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());
        $params = array(
            'idprojeto' => $this->_request->getParam('idprojeto'),
        );
        $this->view->mudanca = $serviceMudanca->retornaPorProjeto($params);
        $this->_helper->layout->disableLayout();
        $html = $this->view->render('/_partials/solicitacao-mudanca-imprimir-todos.phtml');

        $serviceImprimir = App_Service_ServiceAbstract::getService('Default_Service_Impressao');
        $serviceImprimir->gerarPdf($html);
    }


}