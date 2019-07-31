<?php

class Projeto_TapController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('add', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('informacoesiniciais', 'json')
            ->addActionContext('informacoestecnicas', 'json')
            ->addActionContext('resumodoprojeto', 'json')
            ->addActionContext('partesinteressadas', 'json')
            ->addActionContext('partesinteressadasexterno', 'json')
            ->addActionContext('excluirparte', 'json')
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
        $seviceAcao = new Default_Service_Acao();
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $portfolio = new Planejamento_Service_Portfolio();
        $seviceEscritorio = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        /** @var Projeto_Service_Gerencia $service */
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceParteInteressada = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $arrayFeriadosFixos = $serviceCronograma->retornaFeriadosFixos();
        $serviceAssinatura = new Projeto_Service_Assinadocumento();
        $formAssinatura = $serviceAssinatura->getFormTap();
        $feriadosFixos = "";
        for ($i = 0; $i < count($arrayFeriadosFixos); $i++) {
            $feriadosFixos = $feriadosFixos . ($feriadosFixos != "" ? "," : "") . $arrayFeriadosFixos[$i]['diaferiado'] . ";" . $arrayFeriadosFixos[$i]['mesferiado'] . ";" . $arrayFeriadosFixos[$i]['anoferiado'];
        }
        $objproj = $service->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));

        $projeto = $service->getById($this->_request->getParams());

        $nomAcao = null;
        if ((isset($projeto['idacao'])) && (!empty($projeto['idacao']))) {
            $nomAcao = $seviceAcao->getById(array('idacao' => $projeto['idacao']));
        }

        $nomEscritorio = $seviceEscritorio->getById(array('idescritorio' => $projeto['idescritorio']));
        $noPortfolio = $portfolio->getPortfolioById(array('idportfolio' => $projeto['idportfolio']));
        $matricula = $projeto->matricula;
        $assinaturas = $serviceAssinatura->retornaAssinaturaPorProjeto(array('idprojeto' => $projeto->idprojeto));
        $this->view->projeto = $projeto;
        $this->view->objprojeto = $objproj;
        $this->view->acao = $nomAcao['nomacao'];
        $this->view->assinaturas = $assinaturas;
        $this->view->nomeEscritorio = $nomEscritorio['nome'];
        $this->view->noPortifolio = $noPortfolio['noportfolio'];
        $this->view->matricula = $matricula;
        $this->view->feriadosfixos = $feriadosFixos;
        $this->view->formAssinatura = $formAssinatura;
    }
    /**
     * @return Projeto_Service_Gerencia
     */

    /**
     * @return Projeto_Model_Gerencia
     */
    public function addAction()
    {
        $service = new Projeto_Service_Gerencia();
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $projeto = $service->inserir($dados);

            if (is_object($projeto)) {
                try {
                    /**
                     * @var $serviceStatusReport Projeto_Service_StatusReport
                     */
                    $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
                    $serviceStatusReport->inserirStatusProjeto($projeto->formPopulate());

                    /** Cadastra na linha do tempo (auditoria). */
                    $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                    $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                    $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                    $dados['idprojeto'] = $projeto->idprojeto;
                    $serviceLinhaTempo->inserir($dados);
                    $success = true; ###### AUTENTICATION SUCCESS
                    $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
                } catch (Exception $exc) {
                    throw($exc);
                    $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                }
            }
        } else {
            $serviceCronograma = new Projeto_Service_AtividadeCronograma();
            $arrayFeriadosFixos = $serviceCronograma->retornaFeriadosFixos();
            $feriadosFixos = "";
            for ($i = 0; $i < count($arrayFeriadosFixos); $i++) {
                $feriadosFixos = $feriadosFixos . ($feriadosFixos != "" ? "," : "") . $arrayFeriadosFixos[$i]['diaferiado'] . ";" . $arrayFeriadosFixos[$i]['mesferiado'] . ";" . $arrayFeriadosFixos[$i]['anoferiado'];
            }
            $itensEscritorio = $form->getElement('idescritorio')->getMultiOptions();
            $idescritorioLogin = Zend_Auth::getInstance()->getIdentity()->perfilAtivo->idescritorio;
            if (@trim($idescritorioLogin) != "") {
                if (@is_numeric($idescritorioLogin)) {
                    if (isset($itensEscritorio[$idescritorioLogin])) {
                        $form->getElement('idescritorio')->setValue($idescritorioLogin);
                    };
                }
            }
            $form->getElement('datfimplano')->setAttrib('data-rule-datafimplanomaior', true);
            $form->getElement('flapublicado')->setValue('S');
            $form->getElement('flaaprovado')->setValue('N');
            $form->getElement('numperiodicidadeatualizacao')->setValue('30');
            $form->getElement('numcriteriofarol')->setValue('15');
            $form->getElement('nomgerenteadjunto')->setAttrib('placeholder', 'Não detalhado');
            $form->getElement('vlrorcamentodisponivel')->setAttrib('placeholder', 'Não detalhado');
            $form->getElement('nomdemandante')->setAttrib('placeholder', 'Não detalhado');
            $this->view->feriadosfixos = $feriadosFixos;
            $this->view->form = $form;
            $this->view->mostraModel = false;
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $response = new stdClass();
                $response->idprojeto = $projeto->idprojeto;
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
                    $this->_helper->_redirector->gotoSimpleAndExit('projeto', 'gerencia', 'index');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                //$this->_helper->_redirector->gotoSimpleAndExit('gerencia', 'projeto', 'default');
            }
        }
        $this->carregaPortifolio();
    }

    /**
     * @return Projeto_Service_Gerencia
     */
    public function informacoesiniciaisAction()
    {
        $serviceParteInteressada = new Projeto_Service_ParteInteressada();
        $serviceStatusReport = new Projeto_Service_StatusReport();
        $service = new Projeto_Service_Gerencia();
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $serviceAcao = new Planejamento_Service_Acao();
        $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
        $seviceEscritorio = new Default_Service_Escritorio();
        $arrayFeriadosFixos = $serviceCronograma->retornaFeriadosFixos();
        $feriadosFixos = "";
        for ($i = 0; $i < count($arrayFeriadosFixos); $i++) {
            $feriadosFixos = $feriadosFixos . ($feriadosFixos != "" ? "," : "") . $arrayFeriadosFixos[$i]['diaferiado'] . ";" . $arrayFeriadosFixos[$i]['mesferiado'] . ";" . $arrayFeriadosFixos[$i]['anoferiado'];
        }
        /**
         * @var Projeto_Model_Gerencia $mdProjeto
         */
        $mdProjeto = $service->getById($this->_request->getParams());
        $projeto = $mdProjeto->formPopulate();
        $nomEscritorio = $seviceEscritorio->getById(array('idescritorio' => $projeto['idescritorio']));
        $form = $service->getFormEditar();
        $auth = Zend_Auth::getInstance();
        $identiti = $auth->getIdentity();
        $perfilAtivo = $identiti->perfilAtivo->idperfil;
        $idescritorio = $identiti->perfilAtivo->idescritorio;
        $this->view->ano = $projeto['ano'];
        $this->view->projetoIdEscritorio = $projeto['idescritorio'];
        $this->view->nomeEscritorio = $nomEscritorio['nome'];
        $this->view->feriadosfixos = $feriadosFixos;
        $perfil = (int)1;
        $this->view->identiti = $perfilAtivo;
        $this->view->perfil = $perfil;
        $success = false;

        $partes = $serviceParteInteressada->retornaIdPartaPorprojeto(
            array(
                'idprojeto' => (int)$projeto['idprojeto'],
                'idpessoainterna' => $projeto['iddemandante'],
            )
        );
        if (!empty($partes[0]['idparteinteressada']) && isset($partes[0]['idparteinteressada'])) {
            $this->view->idDemandante = $partes[0]['idparteinteressada'];
        }

        $partesAdjunto = $serviceParteInteressada->retornaIdPartaPorprojeto(
            array(
                'idprojeto' => $projeto['idprojeto'],
                'idpessoainterna' => $projeto['idgerenteadjunto'],
            )
        );

        if (!empty($partesAdjunto[0]['idparteinteressada']) && isset($partesAdjunto[0]['idparteinteressada'])) {
            $this->view->idparteAdjunto = $partesAdjunto[0]['idparteinteressada'];
        }

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            /**
             * @var Projeto_Model_Gerencia $modelProjeto
             */
            $modelProjeto = $service->update($dados, true);

            if (is_object($modelProjeto)) {
                $success = true; /* AUTENTICATION SUCCESS */
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"];
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
                $form->getElement('datfimplano')->setAttrib('data-rule-datafimplanomaior', true);
                $this->view->gerencia = $modelProjeto;
                $this->view->form = $form;
            } else {
                $msg = $service->getErrors();
            }

            if ($this->_request->isXmlHttpRequest()) {
                $response = new stdClass();
                $response->idprojeto = $dados["idprojeto"];
                $this->view->dados = $response;
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

        } else {
            $form->getElement('datfimplano')->setAttrib('data-rule-datafimplanomaior', true);
            $form->getElement('idescritorio')->setValue($idescritorio);
            $projeto = $service->getById($this->_request->getParams());
            $projeto['idcadastrador'] = (int)$auth->getIdentity()->idpessoa;
            $arrayAcoes = $serviceAcao->fetchPairsByObjetivo($projeto->formPopulate());
            $form->getElement('idacao')->setMultiOptions($arrayAcoes);
            $form->populate($projeto->formPopulate());
            if (@trim($projeto['idgerenteadjunto'] == "")) {
                $form->getElement('nomgerenteadjunto')->setAttrib('placeholder', 'Não detalhado');
            }
            if (@trim($projeto['iddemandante'] == "")) {
                $form->getElement('nomdemandante')->setAttrib('placeholder', 'Não detalhado');
            }
            if ((@trim($projeto['vlrorcamentodisponivel'] == "0,00")) || (@trim($projeto['vlrorcamentodisponivel'] == "0")) || ($projeto['vlrorcamentodisponivel'] <= 0) || (@trim($projeto['vlrorcamentodisponivel'] == ""))) {
                $form->getElement('vlrorcamentodisponivel')->setValue("");
                $form->getElement('vlrorcamentodisponivel')->setAttrib('placeholder', 'Não detalhado');
            }

            $tapAprovado = $serviceStatusReport->retornaUltimoPorProjeto($this->_request->getParams());
            $this->view->tapaprovado = $tapAprovado;

            $numSeiFormatado = (string)new App_Mask_NumeroSei($projeto->numprocessosei);
            $this->view->numProcessoSei = $numSeiFormatado;

            $this->view->gerencia = $projeto;
            $this->view->form = $form;
            $this->view->mostraModel = false;
        }

        $this->carregaPortifolio();

    }

    public function carregaPortifolio()
    {
        $service = new Planejamento_Service_Portfolio();
        $serviceEscritorio = new Default_Service_Escritorio();
        if ($this->_request->isPost()) {
            $params = $this->_request->getPost();
            if (($params['nomprojeto'] != ''
                || $params['idescritorio'] != ''
                || isset($params['idprograma']) && $params['idprograma'] != ''
                || $params['idobjetivo'] != ''
                || $params['idacao'] != ''
                || $params['idnatureza'] != '')) {
                $buscarPorti = $service->getBuscaPortfolioEstrategico($params);
                $this->view->portfolio = $buscarPorti;
                $escritorio = $serviceEscritorio->getById(array('idescritorio' => $params['idescritorio']));
            }
            $idprograma = $service->pesquisarIdPrograma($params);
            $this->view->escritorio = $escritorio;
            $this->view->idprograma = $idprograma;
        }
        $params = $this->_request->getParams();
        if (!isset($params['idescritorio'])) {
            $serviceLogin = new Default_Service_Login();
            $perfilAtivo = $serviceLogin->retornaPerfilAtivo();
            $idescritorio = $perfilAtivo->idescritorio;
        } else {
            $idescritorio = $params['idescritorio'];

        }
        $this->view->portfolio = $service->getPortfolioEstrategico(array('idescritorio' => $idescritorio));
        $escritorio = $serviceEscritorio->getById(array('idescritorio' => $idescritorio));
        $this->view->escritorio = $escritorio;

        $formPesquisar = $service->getFormPesquisar($params);
        $this->view->formPesquisar = $formPesquisar;
        $selectEscritorio = $serviceEscritorio->selecionarTodoEscritorio();
        $this->view->selectEscritorio = $selectEscritorio;
    }

    public function cadastraParteRhDemandanteAction($dados)
    {
        $serviceParteInteressada = new Projeto_Service_ParteInteressada;
        $arrayPessoa = array('idpessoa' => (int)$dados['idpessoainterna']);
        /** @var Default_Model_Pessoa $pessoa */
        $pessoa = $serviceParteInteressada->retornaPessoaPorIdPessoaInterna($arrayPessoa);

        $data = array(
            'idparteinteressada' => (int)$dados['idpessoainterna'],
            'nomfuncao' => $dados['nomfuncao'],
            'idprojeto' => $dados['idprojeto'],
            'nomparteinteressada' => $pessoa->nompessoa,
            'destelefone' => $pessoa->numfone,
            'desemail' => (!empty($pessoa->desemail)) ? $pessoa->desemail : " - ",
            'tppermissao' => "1",
            'idcadastrador' => (int)$this->_request->getParam('idcadastrador')
        );

        $serviceParteInteressada->insertInterno($data);
    }

    public function cadastraParteRhAdjuntoAction($dados)
    {
        $serviceParteInteressada = new Projeto_Service_ParteInteressada;
        $dataVerificacao = array(
            'idprojeto' => $dados['idprojeto'],
            'idparteinteressada' => $dados['idadjunto'],
        );

        $verificaPartes = $serviceParteInteressada->verificaParteByProjeto($dataVerificacao);
        if ($verificaPartes == '0') {
            $dados = array(
                "idprojeto" => (int)$this->_request->getParam('idprojeto'),
                "idgerenteadjunto" => (int)$dados['idadjunto'],
                "nomparteinteressada" => $this->_request->getParam('nomgerenteadjunto'),
                "nomfuncao" => "Gerente Adjunto do Projeto",
                "idcadastrador" => (int)$this->_request->getParam('idcadastrador'),
                "domnivelinfluencia" => "Alto",
                'idpessoa' => (int)$dados['idadjunto'],
                'destelefone' => "0000000000",
                'tppermissao' => '1',
            );
            $serviceParteInteressada->insertInterno($dados);
        } else {
            return false;
        }
    }

    /**
     * @return Projeto_Service_Gerencia
     */
    public function informacoestecnicasAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getFormInformacoesTecnicas();
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $gerencia = $service->update($dados, false);

            if ($gerencia) {
                $success = true; ###### AUTENTICATION SUCCESS
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
            $gerencia = $service->getById($this->_request->getParams());
            $form->populate($gerencia->formPopulate());
            $form->getElement('desjustificativa')->setAttrib('placeholder',
                'Não detalhado                                      ');
            $form->getElement('desprojeto')->setAttrib('data-rule-required', true);
            $form->getElement('desprojeto')->setRequired(true);
            $form->getElement('desobjetivo')->setAttrib('placeholder',
                'Não detalhado                                      ');
            $this->view->gerencia = $gerencia;
            $this->view->ano = date("Y");
            $this->view->form = $form;
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $response = new stdClass();
                $response->idprojeto = $gerencia->idprojeto;
                $this->view->dados = $response;
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

    public function resumodoprojetoAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getFormResumoDoProjeto();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $gerencia = $service->update($dados, false);
            if ($gerencia) {
                $success = true; ###### AUTENTICATION SUCCESS
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
            $gerencia = $service->getById($this->_request->getParams());
            $form->populate($gerencia->formPopulate());
            $form->getElement('desescopo')->setAttrib('placeholder',
                'Não detalhado                                      ');
            $form->getElement('desnaoescopo')->setAttrib('placeholder',
                'Não detalhado                                      ');
            $form->getElement('despremissa')->setAttrib('placeholder',
                'Não detalhado                                      ');
            $form->getElement('desrestricao')->setAttrib('placeholder',
                'Não detalhado                                      ');
            $this->view->gerencia = $gerencia;
            $this->view->form = $form;
            $this->view->mostraModel = false;
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $response = new stdClass();
                $response->idprojeto = $gerencia->idprojeto;
                $this->view->dados = $response;
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

    public function partesinteressadasAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $form = $service->getForm();
        $formExterno = $service->getFormExterno();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $ctParte = $service->verificaParteInteressadaInternaByProjeto($this->_request->getParams());
            if ($ctParte > 0) {
                $success = false;
                $msg = "Parte já cadastrada neste projeto.";
            } else {
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
                } else {
                    $msg = $service->getErrors();
                }
            }
        } else {
            $parte = $service->retornaPartes($this->_request->getParams(), true);
//            $parte = $service->getByProjeto($this->_request->getParams());
//            $form->populate($parte->formPopulate());
            $this->view->dados = $parte;
            $this->view->idprojeto = $this->_request->getParam('idprojeto');
            $this->view->form = $form;
            $this->view->formExterno = $formExterno;
        }

        if ($this->_request->isPost()) {
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
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function partesinteressadasexternoAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $serviceParteInteressada = new Projeto_Model_Mapper_Parteinteressada();
        $formExterno = $service->getFormExterno();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $ctParte = $serviceParteInteressada->verificaParteExternaByProjeto($this->_request->getParams());
            if ($ctParte) {
                $success = false;
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
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
            }
            $arrayMsg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
            $response = new stdClass();
            $response->success = $success;
            $response->msg = $arrayMsg;
            $response->parte = $parte;
            $this->view->success = $success;
            $this->view->dados = $response;
            $this->view->parte = $parte;
            $this->view->msg = $arrayMsg;
        }
    }

    /**
     * @return Projeto_Service_ParteInteressada
     */
    public function excluirparteAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $success = false;
        $parte = $service->excluir($this->_request->getParams());
        if ($parte) {
            $success = true; ###### AUTENTICATION SUCCESS
            $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
        } else {
            $msg = $service->getErrors();
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
        $service = new Projeto_Service_Gerencia();
        $portfolio = new Planejamento_Service_Portfolio();
        $serviceAssinatura = new Projeto_Service_Assinadocumento();
        $portfolios = $service->retornaProjetoPorId($this->_request->getParams());
        $noPortfolio = $portfolio->getPortfolioById(array('idportfolio' => $portfolios['idportfolio']));
        $protifolioNome = $noPortfolio->noportfolio;
        $processo = $service->retornaProjetoPorId($this->_request->getParams());
        $valor = $service->mascaraValores($processo->vlrorcamentodisponivel);
        $projeto = $service->getById($this->_request->getParams());
        $acao = urldecode($this->_request->getParam('acao'));
        $nomdemandante = $this->_request->getParam('nomdemandante');
        $assinaturas = $serviceAssinatura->retornaAssinaturaPorTipoEProjeto($this->_request->getParams());
        $this->view->nomdemandante = $nomdemandante;
        $this->view->acao = $acao;
        $this->view->assinaturas = $assinaturas;
        $this->view->processo = $processo;
        // flag aprovado S ou N será alterada para sim ou nao
        if ($processo['flaaprovado'] == 'S') {
            $aprovadoSimNao = 'Sim';
        }
        if ($processo['flaaprovado'] == 'N') {
            $aprovadoSimNao = 'Não';
        }
        if ($processo['flacopa'] == 'S') {
            $grandesEventos = 'Sim';
        } else {
            $grandesEventos = 'Não';
        }
        if ($processo['flapublicado'] == 'S') {
            $telaMestra = 'Sim';
        }
        if ($processo['flapublicado'] == 'N') {
            $telaMestra = 'Não';
        }
        $this->view->valor = $valor;
        $this->view->aprovado = $aprovadoSimNao;
        $this->view->telaMestra = $telaMestra;
        $this->view->grandesEventos = $grandesEventos;
        $this->view->noPortfolio = $protifolioNome;
        $this->view->nomdemandante = $projeto['nomdemandante'];
        $this->view->matricula = $projeto['matricula'];
        $this->view->url = $this->getUrl();

        $serviceImprimir = new Default_Service_Impressao();
        $serviceImprimir->setMargin(15, 15, 10, 13, 5, 5);
        $serviceImprimir->adicionaPagina("P");
        $serviceImprimir->insertFooter("text");
        $serviceImprimir->cssHtml = true;
        $serviceImprimir->addHtml('../public/js/library/bootstrap/css/bootstrap.min.css', 1, true);
        $serviceImprimir->addHtml('../public/js/library/bootstrap/css/bootstrap-responsive.min.css', 1, true);
        $serviceImprimir->addHtml('../library/MPDF57/examples/mpdfstyletables.css', 1, true);
        $serviceImprimir->addHtml('../library/MPDF57/mpdf.css', 1, true);
        $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');
        $html = $this->view->render('/_partials/tap-imprimir.phtml');
        $this->_helper->layout->disableLayout();
        $serviceImprimir->addHtml($cabecalho, 2);
        $serviceImprimir->addHtml($html, 2);
        $serviceImprimir->gerarPdfHtml("P");
    }

    public function imprimirWordAction()
    {
        $this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);
        $service = new Projeto_Service_Gerencia();
        $portfolio = new Planejamento_Service_Portfolio();
        $portfolios = $service->retornaProjetoPorId($this->_request->getParams());
        $noPortfolio = $portfolio->getPortfolioById(array('idportfolio' => $portfolios['idportfolio']));
        $protifolioNome = $noPortfolio->noportfolio;
        $processo = $service->retornaProjetoPorId($this->_request->getParams());
        $valor = $service->mascaraValores($processo->vlrorcamentodisponivel);
        $projeto = $service->getById($this->_request->getParams());
        $acao = $this->_request->getParam('acao');
        $this->view->acao = $acao;
        $this->view->processo = $processo;
        // flag aprovado S ou N será alterada para sim ou nao
        if ($processo['flaaprovado'] == 'S') {
            $aprovadoSimNao = 'Sim';
        }
        if ($processo['flaaprovado'] == 'N') {
            $aprovadoSimNao = 'Não';
        }
        if ($processo['flacopa'] == 'S') {
            $grandesEventos = 'Sim';
        } else {
            $grandesEventos = 'Não';
        }
        if ($processo['flapublicado'] == 'S') {
            $telaMestra = 'Sim';
        }
        if ($processo['flapublicado'] == 'N') {
            $telaMestra = 'Não';
        }
        $this->view->valor = $valor;
        $this->view->aprovado = $aprovadoSimNao;
        $this->view->telaMestra = $telaMestra;
        $this->view->grandesEventos = $grandesEventos;
        $this->view->noPortfolio = $protifolioNome;
        $this->view->nomdemandante = $projeto['nomdemandante'];
        $this->view->matricula = $projeto['matricula'];

        $numSeiFormatado = (string)new App_Mask_NumeroSei($processo->numprocessosei);
        $this->view->numProcessoSei = $numSeiFormatado;

        header("Content-type: application/vnd.ms-word");
        header("Content-Type: application/force-download; charset=UTF-8");
        header("Cache-Control: no-store, no-cache");
        header("Content-disposition: inline; filename=tapProjeto" . $this->_request->getParam('idprojeto') . ".doc");
    }

    public function acaoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);

        $dados = $this->_request->getParams();
        $service = new Planejamento_Service_Acao();
        $resultado = $service->getByObjetivo($dados);

        $this->_helper->json->sendJson($resultado);
    }

    public function autenticarassinaturaAction()
    {
        $serviceAssinatura = new Projeto_Service_Assinadocumento();
        $formAssinatura = $serviceAssinatura->getFormTap();
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $dados['numcpf'] = trim($dados['numcpf']);
            $dados['numcpf'] = addslashes($dados['numcpf']);
            $dados['senha'] = trim($dados['senha']);
            $dados['senha'] = addslashes($dados['senha']);
            $dados['senha'] = $dados['senha'];

            $arrayPessoa = $serviceAssinatura->verificarTipoPessoa($dados);

            $msg = null;
            if (is_array($arrayPessoa) && (count($arrayPessoa) > 0)) {

                if (!empty($dados['tipodoc']) && is_array($dados['tipodoc']) && count($dados['tipodoc']) > 0) {
                    $arrayPessoa['token'] = $dados['senha'];
                    $arrayPessoa['idprojeto'] = (int)$dados['idprojeto'];
                    $arrayPessoa['tipodoc'] = $dados['tipodoc'];

                    $retorno = $serviceAssinatura->autenticar($arrayPessoa);

                    if ($retorno) {

                        $resposta = $serviceAssinatura->assinarDocumento($arrayPessoa);

                        if ($resposta) {
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
                    $msg = App_Service_ServiceAbstract::UNSELECTED;
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
        }

    }

    public function retornaassinaturasAction()
    {
        $service = new Projeto_Service_Assinadocumento();
        $assintauras = $service->retornaAssinaturaPorProjeto($this->_getAllParams());
        $this->_helper->json->sendJson($assintauras);
    }
}