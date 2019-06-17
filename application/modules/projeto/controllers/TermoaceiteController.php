<?php

class Projeto_TermoaceiteController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('add', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('retornaaceitesjson', 'json')
            ->addActionContext('buscarmarcos', 'json')
            ->addActionContext('listaentregas', 'json')
            ->addActionContext('autenticarassinatura', 'json')
            ->addActionContext('retornaassinaturas', 'json')
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
        $dados = $this->_request->getParams();

        $serviceAssinatura = new Projeto_Service_Assinadocumento();
        $formAssinatura = $serviceAssinatura->getFormTap();
        $this->view->idprojeto = $dados['idprojeto'];

        if (isset($dados['idaceite'])) {
            $this->view->idaceite = $dados['idaceite'];
        }
        $this->view->formAssinatura = $formAssinatura;
    }

    public function retornaaceitesjsonAction()
    {
        $service = new Projeto_Service_Aceite();
        $resultado = $service->retornaAceites($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado->toJqgrid());
    }

    public function listaentregasAction()
    {
        $serviceAtivCronograma = new Projeto_Service_AtividadeCronograma();
        $resultado = $serviceAtivCronograma->fetchPairsEntrega($this->_request->getParams());
        $this->_helper->json->sendJson($resultado);
    }

    public function addAction()
    {

        $idprojeto = $this->_request->getParam('idprojeto');
        $this->view->idprojeto = $idprojeto;
        $service = new Projeto_Service_Aceite();
        $form = $service->getForm(array('idprojeto' => $idprojeto));
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $idmarco = $dados['idmarco'];
            unset($dados['idmarco']);
            $aceite = $service->inserir($dados);
            if ($aceite->idaceite) {
                $aceite->idmarco = $idmarco;
                $serviceAceiteAtv = new Projeto_Service_Aceiteatividadecronograma();
                $resultado = $serviceAceiteAtv->inserir($aceite);
                if ($resultado) {
                    $success = true;
                    /** Cadastra na linha do tempo (auditoria). */
                    $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                    $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                    $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                    $dados['idprojeto'] = $idprojeto;
                    $serviceLinhaTempo->inserir($dados);
                    $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
                } else {
                    $msg = $service->getErrors();
                }
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
        $identrega = $this->_request->getParam('identrega');
        $this->view->idprojeto = $idprojeto;
        $service = new Projeto_Service_Aceite();
        $form = $service->getForm(array('idprojeto' => $idprojeto));
        $serviceAceiteAtivCronograma = new Projeto_Service_Aceiteatividadecronograma();
        $form = $serviceAceiteAtivCronograma->getForm(array('idprojeto' => $idprojeto, 'identrega' => $identrega));
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $dadosAceite = array(
                'idaceite' => $dados['idaceite'],
                'idaceiteativcronograma' => $dados['idaceiteativcronograma'],
                'identrega' => $dados['identrega'],
                'idprojeto' => $dados['idprojeto'],
                'desprodutoservico' => $dados['desprodutoservico'],
                'desparecerfinal' => $dados['desparecerfinal'],
                'flaaceite' => $dados['flaaceite'],
                'nomresponsavel' => $dados['nomresponsavel']
            );
            //$idmarco = $dados['idmarco'];
            //unset($dados['idmarco']);
            $aceite = $service->editar($dadosAceite);
            if ($aceite) {
                $dadosAceiteAtividade = array(
                    'idaceite' => $dados['idaceite'],
                    'idaceiteativcronograma' => $dados['idaceiteativcronograma'],
                    'identrega' => $dados['identrega'],
                    'idprojeto' => $dados['idprojeto'],
                    'idmarco' => $dados['idmarco'],
                    'desprodutoservico' => $dados['desprodutoservico'],
                    'desparecerfinal' => $dados['desparecerfinal'],
                    'flaaceite' => $dados['flaaceite'],
                    'nomresponsavel' => $dados['nomresponsavel']
                );
                $aceiteAtividadeCronograma = $serviceAceiteAtivCronograma->editar($dadosAceiteAtividade);
                if ($aceiteAtividadeCronograma) {
                    $success = true;
                    /** Cadastra na linha do tempo (auditoria). */
                    $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                    $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                    $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                    $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                    $serviceLinhaTempo->inserir($dados);
                    $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
                } else {
                    $success = false;
                    $msg = $service->getErrors();
                }
            } else {
                $msg = $service->getErrors();
            }
            $response = new stdClass();
            $response->$aceite = $aceite;
            $response->success = $success;
            $response->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
            $this->_helper->json->sendJson($response);
        } else {
            $aceite = $service->getById($this->_request->getParams());
            $aceiteAtividadeCronograma = $serviceAceiteAtivCronograma->getById($this->_request->getParams());
            $aceite->flaaceite = $aceiteAtividadeCronograma->aceito;
            $aceite->idmarco = $aceiteAtividadeCronograma->idmarco;
            $this->view->idaceiteativcronograma = $aceiteAtividadeCronograma->idaceiteativcronograma;
            $this->view->aceite = $aceite;
            $form->populate($aceite->formPopulate());
            $this->view->form = $form;
        }
    }

    public function detalharAction()
    {
        $service = new Projeto_Service_Aceite();
        $serviceAceiteAtividadeCronograma = new Projeto_Service_Aceiteatividadecronograma();
        $aceite = $service->getById($this->_request->getParams());
        $aceiteAtividadeCronograma = $serviceAceiteAtividadeCronograma->getById($this->_request->getParams());
        $this->view->nomarco = $aceiteAtividadeCronograma->nomarco;
        $this->view->aceite = $service->getById($aceite);
    }

    public function excluirAction()
    {

        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $service = new Projeto_Service_Aceite();
        $aceite = $service->getById($this->_request->getParams());
        $serviceAceiteAtividadeCronograma = new Projeto_Service_Aceiteatividadecronograma();
        $aceiteAtividadeCronograma = $serviceAceiteAtividadeCronograma->getById($this->_request->getParams());
        $aceite->idmarco = $aceiteAtividadeCronograma->idmarco;
        $this->view->idaceiteativcronograma = $aceiteAtividadeCronograma->idaceiteativcronograma;
        $this->view->nomarco = $aceiteAtividadeCronograma->nomarco;
        $this->view->aceite = $aceite;
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $exluiuAceiteAtvCron = $serviceAceiteAtividadeCronograma->excluir($dados);
            if ($exluiuAceiteAtvCron) {
                $excluiuAceite = $service->excluir($dados);
                if ($excluiuAceite) {
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

    public function buscarEntregaAction()
    {

        $service = new Projeto_Service_AtividadeCronograma();
        $resultado = $service->retornaEntregaPorId($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);

    }

    public function buscarMarcosAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $resultado = $service->fetchPairsMarcosPorEntrega($this->_request->getParams());
        //Zend_Debug::dump($resultado);exit;
        //$this->_helper->json($resultado, array('enableJsonExprFinder' => true, 'keepLayouts'=> true));
        $this->_helper->json->sendJson($resultado);
    }

    private function getUrl()
    {
        $baseUrl = new Zend_View_Helper_ServerUrl();
        return $baseUrl->serverUrl() . Zend_View_Helper_Url::url(array(
                'module' => 'default',
                'controller' => 'autenticarcodigo',
                'action' => 'index'
            ));
    }

    public function imprimirAction()
    {
        $this->_helper->layout->disableLayout();
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceAceite = new Projeto_Service_Aceite();
        $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
        $serviceAssinatura = new Projeto_Service_Assinadocumento();
        $this->view->projeto = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());

        $entrega = $serviceAceite->getById($this->_request->getParams());
        $assinaturas = $serviceAssinatura->retornaAceiteAssinado($this->_request->getParams());
        //var_dump($assinaturas);die;
        $params = array(
            'idprojeto' => $this->_request->getParam('idprojeto'),
            'idatividadecronograma' => $entrega['identrega'],
        );
        $dados = array('idprojeto' => $entrega['idprojeto'], 'identrega' => $entrega['identrega']);

        $marco = $serviceAtividadeCronograma->fetchPairsMarcosPorEntrega($dados);

        $this->view->atividadecronograma = $serviceAtividadeCronograma->retornaEntregaPorId($params);
        $this->view->desparecerfinal = $entrega['desparecerfinal'];
        $this->view->desprodutoservico = $entrega['desprodutoservico'];
        $this->view->assinaturas = $assinaturas;
        $this->view->url = $this->getUrl();

        //$this->view->nomarco = $marco['nomarco'];

        $aceite = $serviceAceite->retornaAceites($this->_request->getParams(), false);
        $this->view->aceite = $aceite;

        $html = $this->view->render('/_partials/termo-aceite-imprimir.phtml');

        $serviceImprimir = App_Service_ServiceAbstract::getService('Default_Service_Impressao');

        $serviceImprimir->gerarPdf($html);
    }

    public function imprimirTodosAction()
    {
        $this->_helper->layout->disableLayout();
        $service = new Projeto_Service_Aceite();
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceAssinatura = new Projeto_Service_Assinadocumento();
        $assinaturas = $serviceAssinatura->retornaTodosAceitesAssinadosPorProjeto($this->_request->getParams());
        $this->view->projeto = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());
        $this->view->aceite = $service->retornaAceites($this->_request->getParams(), false);
        $this->view->assinaturas = $assinaturas;
        $this->view->url = $this->getUrl();
        $html = $this->view->render('/_partials/termo-aceite-imprimir-todos.phtml');
        $serviceImprimir = App_Service_ServiceAbstract::getService('Default_Service_Impressao');
        $serviceImprimir->gerarPdf($html);

    }

    public function imprimirWordAction()
    {
        $this->_helper->layout->disableLayout();
        $service = new Projeto_Service_Aceite();
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');

        $aceite = $service->retornaAceites($this->_request->getParams(), false);
        $projetoAceite = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());

        $this->view->projeto = $projetoAceite;
        $this->view->aceite = $aceite;

        header("Content-type: application/vnd.ms-word");
        header("Content-Type: application/force-download; charset=UTF-8");
        header("Cache-Control: no-store, no-cache");
        header("Content-disposition: inline; filename=termoAceiteProjeto" . $this->_request->getParam('idprojeto') . ".doc");
    }

    public function autenticarassinaturaAction()
    {
        $serviceAssinatura = new Projeto_Service_Assinadocumento();
        $formAssinatura = $serviceAssinatura->getFormTap();

        if ($this->_request->isPost()) {

            $success = false;
            $dados = $this->_request->getParams();
            $dados['numcpf'] = trim($dados['numcpf']);
            $dados['numcpf'] = addslashes($dados['numcpf']);
            $dados['senha'] = trim($dados['senha']);
            $dados['senha'] = addslashes($dados['senha']);
            $dados['senha'] = $dados['senha'];

            $arrayPessoa = $serviceAssinatura->verificarTipoPessoa($dados);
            $msg = null;

            if (is_array($arrayPessoa) && (count($arrayPessoa) > 0)) {

                $arrayPessoa['token'] = $dados['senha'];
                $arrayPessoa['idprojeto'] = $dados['idprojeto'];
                $arrayPessoa['tipodoc'] = array(0 => 3);
                $arrayPessoa['idaceite'] = $dados['idaceite'];
                $retorno = $serviceAssinatura->autenticar($arrayPessoa);

                if ($retorno) {
                    $retorno = $serviceAssinatura->assinarDocumento($arrayPessoa);
                    if ($retorno) {
                        $success = true; ###### AUTENTICATION SUCCESS
                        $msg = App_Service_ServiceAbstract::VALID_SUCCESS_USER;
                    } else {
                        $success = false; ###### AUTENTICATION FALURE
                        $msg = App_Service_ServiceAbstract::INVALID_SUCCESS;
                    }
                } else {
                    $success = false; ###### AUTENTICATION FALURE
                    $msg = App_Service_ServiceAbstract::VALID_DENY_USER;
                }
            } else {
                $success = false; ###### AUTENTICATION FALURE
                $msg = App_Service_ServiceAbstract::NENHUM_USUARIO_ENCONTRADO;
            }
            $response = new stdClass();
            $response->success = $success;
            $response->msg = array(
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
            $this->_helper->json->sendJson($response);
        } else {
            $this->view->form = $formAssinatura;
            $this->view->idprojeto = $this->_request->getParam('idprojeto');
            $this->view->idaceite = $this->_request->getParam('idaceite');
        }
    }

    public function retornaassinaturasAction()
    {
        $service = new Projeto_Service_Assinadocumento();
        $assintauras = $service->retornaAssinaturaPorProjeto($this->_getAllParams());
        $this->_helper->json->sendJson($assintauras);
    }

}