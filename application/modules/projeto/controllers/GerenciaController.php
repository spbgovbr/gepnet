<?php

class Projeto_GerenciaController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('add', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('desbloquear', 'json')
            ->addActionContext('configurar', 'json')
            ->addActionContext('atualiza-permissao', 'json')
            ->addActionContext('clonarprojeto', 'json')
            ->addActionContext('excluirprojeto', 'json')
            ->addActionContext('restaurarprojeto', 'json')
            ->addActionContext('pesquisaracaojson', 'json')
            ->addActionContext('pesquisarjson', 'json')
            ->initContext();
        $servicePerfilPessoa = new Default_Service_Perfilpessoa();
        $dadosEntrada = array(
            "idprojeto" => $this->_request->getParam('idprojeto'),
            "controller" => strtolower($this->_request->getControllerName()),
            "action" => strtolower($this->_request->getActionName())
        );

        if (!$servicePerfilPessoa->isValidaControllerAction($dadosEntrada)) {
            $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => 'Acesso negado...'));
            $this->_helper->_redirector->gotoSimpleAndExit('forbidden', 'error', 'projeto');
        }

    }

    /**
     * Carregamento da pagina principal
     *
     * @return Projeto_Service_Gerencia
     */
    public function indexAction()
    {
        $service = new Projeto_Service_Gerencia();

        $auth = Zend_Auth::getInstance();
        $identiti = $auth->getIdentity();
        $identiti->perfilAtivo->idperfil;
        $this->view->identiti = $identiti;
        $form = $service->getFormPesquisar();

        $this->view->form = $form;

        $service = new Default_Service_Versao();

        $resultado = new stdClass();

        $resultado = $service->mostraUltimaVersao();

        if (property_exists($resultado, 'resposta') && $resultado->resposta == 'visualizar') {
            $this->view->versaoHTML = $resultado;
        }

    }

    /**
     * Busca as ações extratégicas do Objetivo
     * Instituicional
     *
     * @return array
     */
    public function pesquisaracaojsonAction()
    {
        $serviceAcaoExtrategica = new Default_Service_Acao();
        try {
            $acaoExtrategica = $serviceAcaoExtrategica->retornaAcaoByObjetivo($this->_request->getParams());
        } catch (Exception $e) {
            $acaoExtrategica = array("0" => "Todos");
        }
        $this->_helper->json->sendJson($acaoExtrategica);
    }

    /**
     * Resumo do projeto
     *
     * @return array
     */
    public function resumoAction()
    {
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $this->view->projeto = $projeto;
    }

    /**
     * Pesquisa json do projeto
     *
     * @return array
     */
    public function pesquisarjsonAction()
    {
        $service = new Projeto_Service_Gerencia();
        $dadosParam = $this->_request->getParams();
        unset($dadosParam['module']);
        unset($dadosParam['controller']);
        unset($dadosParam['action']);
        unset($dadosParam['_search']);
        $paginator = null;


        if (isset($dadosParam['nomprojeto']) && (!empty($dadosParam['nomprojeto']))
            || isset($dadosParam['idescritorio']) && (!empty($dadosParam['idescritorio']))
            || isset($dadosParam['acompanhamento']) && (!empty($dadosParam['acompanhamento']))
            || isset($dadosParam['domstatusprojeto'])
        ) {
            $domstatusprojeto = (int)preg_replace('/[^0-9]/', '', $dadosParam['domstatusprojeto']);
            unset($dadosParam['domstatusprojeto']);
            $dadosParam['domstatusprojeto'] = $domstatusprojeto;
            if ((empty($dadosParam['domstatusprojeto'])) && $dadosParam['domstatusprojeto'] == 0) {
                $dadosParam['domstatusprojeto'] = null;
            }
            $paginator = $service->filtrarProjetoGerencia($dadosParam, true);
        } else {
            $paginator = $service->pesquisarGerenciaProjeto($dadosParam, true);
        }
        $this->_helper->json->sendJson($paginator);
    }

    /**
     * Detalhar projeto selecionado
     *
     * @return array
     */
    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $this->view->escritorio = $service->getById($this->_request->getParams());
    }

    public function addAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $projeto = $service->inserir($dados);
            if ($projeto) {
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
                    $this->_helper->_redirector->gotoSimpleAndExit('projeto', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
            }
        }
    }

    public function editarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getFormEditar();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $gerencia = $service->update($dados);
            if ($gerencia) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $gerencia = $service->getById($this->_request->getParams());
            $form->populate($gerencia->formPopulate());
            $this->view->gerencia = $gerencia;
            $this->view->form = $form;
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    /**
     * @return Projeto_Service_Gerencia
     */
    public function configurarAction()
    {
        /**
         * @var $service Projeto_Service_Gerencia
         */
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getFormConfigurar();
        $parte = $service->retornaPartes($this->_request->getParams(), true);
        $this->view->dados = $parte;
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $this->view->form = $form;
    }

    /**
     * @return Projeto_Service_Gerencia
     *
     * @return Projeto_Service_ParteInteressada
     *
     * @return Default_Service_Permissao
     */
    public function editarpermissaoAction()
    {
        var_dump('huml');
        die;
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getFormPermissaoEditar();

        $serviceParte = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $resultadoParte = $serviceParte->retornaPorId($this->_request->getParams(), false);

        $servicePermissaoProjeto = App_Service_ServiceAbstract::getService('Default_Service_Permissao');
        $resultadoPermissaoProjeto = $servicePermissaoProjeto->getRecursosProjeto();

        $this->view->dadosParte = $resultadoParte;
        $this->view->dadosPermissao = $resultadoPermissaoProjeto;

        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $this->view->idparteinteressada = $this->_request->getParam('idparteinteressada');
        $this->view->idpessoa = $this->_request->getParam('idpessoa');
        $this->view->form = $form;
    }

    /**
     * @return Default_Service_Permissao
     */
    public function listapermissaoAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Permissao');
        $resultado = $service->getRecursosProjetoPorParte($this->_request->getParams());
        $params = array_filter($this->_request->getParams());
        $this->view->parametros = $params;
        $this->view->dados = $resultado;
    }

    /**
     * @return Default_Service_Permissao
     */
    public function editapermissaoAction()
    {
        $service = App_Service_ServiceAbstract::getService('Default_Service_Permissao');
        $resultado = $service->getRecursosProjetoPorParte($this->_request->getParams());
        $params = array_filter($this->_request->getParams());
        $this->view->parametros = $params;
        $this->view->dados = $resultado;
    }

    /**
     * @return Projeto_Service_ParteInteressada
     */
    public function detalharpermissaoAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getFormPermissaoEditar();

        $serviceParte = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $resultadoParte = $serviceParte->retornaPorId($this->_request->getParams(), false);

        $servicePermissaoProjeto = App_Service_ServiceAbstract::getService('Default_Service_Permissao');
        $resultadoPermissaoProjeto = $servicePermissaoProjeto->getRecursosProjeto();

        $this->view->dadosParte = $resultadoParte;
        $this->view->dadosPermissao = $resultadoPermissaoProjeto;

        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $this->view->idparteinteressada = $this->_request->getParam('idparteinteressada');
        $this->view->idpessoa = $this->_request->getParam('idpessoa');
        $this->view->form = $form;
    }

    /**
     * @return Projeto_Service_PermissaoProjeto
     */
    public function atualizapermissaoAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_PermissaoProjeto');
        $configuracao = $service->getById($this->_request->getParams());
        if (@count($configuracao) > 0) {
            $serviceed = App_Service_ServiceAbstract::getService('Projeto_Service_PermissaoProjeto');
            $resultado = $serviceed->editar($this->_request->getParams());
        } else {
            $servicein = App_Service_ServiceAbstract::getService('Projeto_Service_PermissaoProjeto');
            $resultado = $servicein->inserir($this->_request->getParams());
        }
        if ($resultado) {
            $success = true; ###### AUTENTICATION SUCCESS
            $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
        } else {
            $success = false;
            $msg = $service->getErrors();
        }
        $response = new stdClass();
        $response->resultado = $resultado;
        $response->success = $success;
        $response->msg = array(
            'text' => $msg,
            'type' => ($success) ? 'success' : 'error',
            'hide' => true,
            'closer' => true,
            'sticker' => false
        );
        $this->_helper->json->sendJson($response);
        //$this->_helper->json->sendJson($resultado);
    }

    public function pesquisarviewcomumjsonAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $resultado = $service->pesquisarViewComum($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }

    /**
     * @return Projeto_Service_Gerencia
     */
    public function importarjsonAction()
    {
        $this->_helper->layout->disableLayout();
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $resultado = $service->importarViewComum($this->_request->getParams());
        $this->_helper->json->sendJson($resultado);
    }

    public function rotinaBloqueioProjetosAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_BloqueioProjeto');
        $service->rotinaBloqueioProjetos();
    }

    public function desbloquearAction()
    {
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_BloqueioProjeto');
        $form = $service->getFormDesbloqueio();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $gerencia = $service->desbloquearProjeto($dados);
            if ($gerencia) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $gerencia = $serviceGerencia->getById($this->_request->getParams());
            //$form->populate($service->formPopulate());
            $this->view->gerencia = $gerencia;
            $this->view->form = $form;
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }

    }

    /**
     * @return Projeto_Service_Gerencia
     */
    public function clonarprojetoAction()
    {
        $service = new Projeto_Service_Gerencia();

        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getParams();

            $verificaPublicos = $service->verificaPublicos($dados['idprojeto']);
            $auth = Zend_Auth::getInstance();
            $perfil = $auth->getIdentity()->perfilAtivo->nomeperfilACL;

            if (trim($verificaPublicos) == "S" ||
                ($perfil == "admin_gepnet" && trim($verificaPublicos) == "N")) {
                $cloneProjeto = $service->clonarProjeto($dados);
                $success = true; /* CLONAGEM SUCCESS */
                $msg = App_Service_ServiceAbstract::REGISTRO_CLONADO_COM_SUCESSO;
            } elseif (trim($verificaPublicos) == "N") {
                return $this->_helper->json->sendJson(
                    array(
                        'status' => 'error',
                        'message' => 'Acesso negado...',
                        'closer' => true,
                        'sticker' => false
                    )
                );
                //break;
            } else {
                $success = false; /* CLONAGEM ERROR */
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false,
                'idprojeto' => $cloneProjeto
            );

        } else {

            $form = $service->getFormClonarProjeto($this->_request->getParams());
            $projeto = $service->getById($this->_request->getParams());

            $auth = Zend_Auth::getInstance();
            $perfil = $auth->getIdentity()->perfilAtivo->nomeperfilACL;

            if ($perfil != "admin_gepnet") {
                $form->getElement('idescritorio')->setAttribs(
                    array(
                        'readonly' => 'readonly',
                        'pointer-events' => 'none',
                        'tabindex' => '-1',
                        'use_hidden_element' => true,
                        'disabled' => 'disabled',
                    )
                );
            }
            $form->populate(
                array(
                    'idprojeto' => $projeto->idprojeto,
                    'nomprojeto' => $projeto->nomprojeto,
                    'nomcodigo' => $projeto->nomcodigo,
                    'ano' => $projeto->ano,
                    'idescritorio' => $projeto->idescritorio,
                )
            );
            $this->view->form = $form;
        }
    }

    /**
     * @return Projeto_Service_Gerencia
     */
    public function excluirprojetoAction()
    {
        $service = new Projeto_Service_Gerencia();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $idProjeto = $dados['idprojeto'];
            $StatusExclusao = Projeto_Model_Gerencia::STATUS_EXCLUIDO;
            $excluirProjeto = $service->alterarStatusProjeto(array(
                'idprojeto' => $idProjeto,
                'domstatusprojeto' => $StatusExclusao
            ));

            if ($excluirProjeto) {
                $idcadastrador = null;
                $auth = Zend_Auth::getInstance();
                if ($auth->hasIdentity()) {
                    $idcadastrador = $auth->getIdentity()->idpessoa;
                }
                $statusReportIns = array();
                $statusReportIns['domstatusprojeto'] = $StatusExclusao;
                $statusReportIns['datacompanhamento'] = date("Y-m-d");
                $statusReportIns['datmarcotendencia'] = date("Y-m-d");
                $statusReportIns['datcadastro'] = date("Y-m-d");
                $statusReportIns['dataaprovacao'] = date("Y-m-d");
                $statusReportIns['numpercentualprevisto'] = 0.00;
                $statusReportIns['numpercentualconcluido'] = 0.00;
                $statusReportIns['idmarco'] = 1;
                $statusReportIns['idcadastrador'] = $idcadastrador;
                $statusReportIns['datfimprojetotendencia'] = date("Y-m-d");
                $statusReportIns['domcorrisco'] = 1;
                $statusReportIns['desatividadeconcluida'] = "Projeto sem acompanhamento cadastrado.";
                $statusReportIns['desatividadeandamento'] = "Projeto sem acompanhamento cadastrado.";
                $statusReportIns['desmotivoatraso'] = "Projeto sem acompanhamento cadastrado.";
                $statusReportIns['descontramedida'] = "Projeto sem acompanhamento cadastrado.";
                $statusReportIns['desirregularidade'] = "Projeto sem acompanhamento cadastrado.";
                $statusReportIns['desrisco'] = "Projeto sem acompanhamento cadastrado.";
                $statusReportIns['descaminho'] = "Projeto sem acompanhamento cadastrado.";
                $statusReportIns['flaaprovado'] = 2;
                $statusReportIns['idprojeto'] = $idProjeto;
                $serviceStatusReport = new Projeto_Service_StatusReport();
                $StatusReportinserir = $serviceStatusReport->alterarStatusProjeto($statusReportIns);

                if ($StatusReportinserir) {
                    $success = true; ###### EXCLUSAO SUCCESS
                    $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
                } else {
                    $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                }
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        } else {
            $dados = $this->_request->getParams();
            $projeto = $service->getById($dados, false, true);
            $this->view->projeto = $projeto;
        }
    }

    /**
     * @return Projeto_Service_Gerencia
     */
    public function restaurarprojetoAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $statusreport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $idProjeto = $dados['idprojeto'];
            $ultimoStReport = 1;
            $listaStatusreport = $statusreport->retornaAcompanhamentosPorProjeto(array(
                'idprojeto' => $idProjeto,
                'sidx' => 'datcadastro desc, sr.idstatusreport ',
                'sord' => 'desc'
            ), $paginator = false);
            foreach ($listaStatusreport as $itemStatusReport) {
                if ($itemStatusReport['domstatusprojeto'] != 8) {
                    $ultimoStReport = $itemStatusReport['domstatusprojeto'];
                    break;
                }
            }
            $idcadastrador = null;
            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                $idcadastrador = $auth->getIdentity()->idpessoa;
            }
            $statusReportIns = array();
            $statusReportIns['domstatusprojeto'] = $ultimoStReport;
            $statusReportIns['datacompanhamento'] = date("Y-m-d");
            $statusReportIns['datmarcotendencia'] = date("Y-m-d");
            $statusReportIns['datcadastro'] = date("Y-m-d");
            $statusReportIns['dataaprovacao'] = date("Y-m-d");
            $statusReportIns['numpercentualprevisto'] = 0.00;
            $statusReportIns['numpercentualconcluido'] = 0.00;
            $statusReportIns['idmarco'] = 1;
            $statusReportIns['idcadastrador'] = $idcadastrador;
            $statusReportIns['datfimprojetotendencia'] = date("Y-m-d");
            $statusReportIns['domcorrisco'] = 1;
            $statusReportIns['desatividadeconcluida'] = "Projeto sem acompanhamento cadastrado.";
            $statusReportIns['desatividadeandamento'] = "Projeto sem acompanhamento cadastrado.";
            $statusReportIns['desmotivoatraso'] = "Projeto sem acompanhamento cadastrado.";
            $statusReportIns['descontramedida'] = "Projeto sem acompanhamento cadastrado.";
            $statusReportIns['desirregularidade'] = "Projeto sem acompanhamento cadastrado.";
            $statusReportIns['desrisco'] = "Projeto sem acompanhamento cadastrado.";
            $statusReportIns['descaminho'] = "Projeto sem acompanhamento cadastrado.";
            $statusReportIns['flaaprovado'] = 2;
            $statusReportIns['idprojeto'] = $idProjeto;
            $serviceStatusReport = new Projeto_Service_StatusReport();
            $StatusReportinserir = $serviceStatusReport->alterarStatusProjeto($statusReportIns);
            $restaurarProjeto = $service->alterarStatusProjeto(
                array('idprojeto' => $idProjeto, 'domstatusprojeto' => $ultimoStReport)
            );
            if ($restaurarProjeto) {
                $success = true; ###### RESTAURAR SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $success = false; ###### RESTAURAR ERROR
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        } else {
            $dados = $this->_request->getParams();
            $projeto = $service->getById($dados, false, true);
            $this->view->projeto = $projeto;
        }
    }

    public function phpinfoAction()
    {
        echo phpinfo();
        exit;
    }

    /**
     * Lista de projetos que podem ser clonados.
     */
    public function clonarAction()
    {
        $service = new Projeto_Service_Gerencia();
        //$this->rotinaBloqueioProjetosAction();
        //$this->rotinaBloqueioProjetosAction();

        $auth = Zend_Auth::getInstance();
        $identiti = $auth->getIdentity();
        $identiti->perfilAtivo->idperfil;
        $this->view->identiti = $identiti;
        $form = $service->getFormPesquisar();

        $this->view->form = $form;

        $service = new Default_Service_Versao();

        $resultado = new stdClass();

        $resultado = $service->mostraUltimaVersao();

        if (!empty($resultado->resposta) && $resultado->resposta == 'visualizar') {
            $this->view->versaoHTML = $resultado;
        }

    }

}