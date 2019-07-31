<?php

class Projeto_RelatorioController extends Zend_Controller_Action
{
    /**
     * @var $mpdf App_Service_MPDF
     */
    private $mpdf;

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('relatoriojson', 'json')
            ->addActionContext('add', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('detalhar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('atualizaacompanhamento', 'json')
            ->addActionContext('atualizarcabecalhojson', 'json')
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

    public function atualizaacompanhamentoAction()
    {
        $serviceStatusReport = new Projeto_Service_StatusReport();
        $listaAcompanhamentos = $serviceStatusReport->retornarTodosAcompanhamento(array('idprojeto' => $this->_request->getParam('idprojeto')),
            false);
        $this->_helper->json->sendJson($listaAcompanhamentos);
    }

    public function indexAction()
    {
        $serviceStatusReport = new Projeto_Service_StatusReport();
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $serviceGerencia = new Projeto_Service_Gerencia();
        $form = $serviceStatusReport->getFormPesquisar();
        $dias = 0;
        $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $listaAcompanhamentos = $serviceStatusReport->retornaAcompanhamentosPorProjeto(array('idprojeto' => $this->_request->getParam('idprojeto')),
            false);
        $idStatusReport = $this->_request->getParam('idstatusreport');
        $entregasMarcos = $serviceAtividadeCronograma->retornaEntregasEMarcosPorProjetoRelatorio(array('idprojeto' => $this->_request->getParam('idprojeto')));

        if (isset($idStatusReport) && (!empty($idStatusReport))) {
            /**@var Projeto_Model_Statusreport $acompanhamento */
            $acompanhamento = $serviceStatusReport->retornaAcompanhamentoPorId(array(
                'idstatusreport' => $idStatusReport,
                'idprojeto' => $this->_request->getParam('idprojeto')
            ), false);
            $ultimoAcompanhamento = $serviceStatusReport->retornaUltimoAcompanhamento(array('idprojeto' => $this->_request->getParam('idprojeto')),
                false);
            $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $idStatusReport));
            $ultimoPrazo = $serviceStatusReport->getUltimoPrazo(array(
                'idstatusreport' => $idStatusReport,
                'idprojeto' => $this->_request->getParam('idprojeto')
            ), true);
            $atividadeCron = array(
                'idatividadecronograma' => $statusReport->idmarco,
                'idprojeto' => $statusReport->idprojeto
            );
            $proximoMarco = new stdClass();
            $dadosMarco = $serviceAtividadeCronograma->retornaAtividadeById($atividadeCron);

            $dados = array();

            if (!empty($dadosMarco['datfimbaseline'])) {
                $dados['datainicio'] = new Zend_Date($dadosMarco['datfimbaseline'], 'dd/MM/YYYY');
            } else {
                $dados['datainicio'] = "";
            }

            if (!empty($dadosMarco['datfim'])) {
                $dados['datafim'] = new Zend_Date($dadosMarco['datfim'], 'dd/MM/YYYY');
            } else {
                $dados['datafim'] = "";
            }

            if (!empty($dadosMarco['datainicio']) && (!empty($dadosMarco['datafim']))) {
                $dias = $serviceAtividadeCronograma->retornaQtdeDiasUteisEntreDatas($dados);
            }
            /**********************************************************/
            /* retira um dia do cálculo para atender a regra definida */
            if ($dias != 0) {
                $dias = $dias * (-1);
                $dias = ($dias > 0 ? $dias - 1 : $dias + 1);
            }

            $proximoMarco->idatividadecronograma = $dadosMarco['idatividadecronograma'];
            $proximoMarco->nomatividadecronograma = $dadosMarco['nomatividadecronograma'];
            $proximoMarco->datfimbaseline = $dadosMarco['datfimbaseline'];
            $proximoMarco->datfim = $dadosMarco['datfim'];
            $this->view->idUltimoStatus = $ultimoAcompanhamento->idstatusreport;
            $this->view->idStatusSelecionado = $idStatusReport;
        } else {
            $acompanhamento = $serviceStatusReport->retornaUltimoAcompanhamento(array('idprojeto' => $this->_request->getParam('idprojeto')),
                false);
            $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $acompanhamento->idstatusreport));
            $proximoMarco = $serviceAtividadeCronograma->retornaProximoMarco(array('idprojeto' => $this->_request->getParam('idprojeto')));
            $ultimoPrazo = $serviceStatusReport->getUltimoPrazo(array(
                'idstatusreport' => $acompanhamento->idstatusreport,
                'idprojeto' => $this->_request->getParam('idprojeto')
            ), true);
            $atividadeCronograma = $serviceAtividadeCronograma->retornaAtividadePorId(array(
                'idprojeto' => $this->_request->getParam('idprojeto'),
                'idatividadecronograma' => $statusReport->idmarco
            ), false);
            $dados = array();

            if (!empty($atividadeCronograma['datfimbaseline'])) {
                $dados['datainicio'] = new Zend_Date($atividadeCronograma['datfimbaseline'], 'dd/MM/YYYY');
            } else {
                $dados['datainicio'] = "";
            }

            if (!empty($atividadeCronograma['datfim'])) {
                $dados['datafim'] = new Zend_Date($atividadeCronograma['datfim'], 'dd/MM/YYYY');
            } else {
                $dados['datafim'] = "";
            }

            if (!empty($dados['datainicio']) && (!empty($dados['datafim']))) {
                $dias = $serviceAtividadeCronograma->retornaQtdeDiasUteisEntreDatas($dados);
            }
            /**********************************************************/
            /* retira um dia do cálculo para atender a regra definida */
            if ($dias != 0) {
                $dias = $dias * (-1);
                $dias = ($dias > 0 ? $dias - 1 : $dias + 1);
            }
            $this->view->idUltimoStatus = $acompanhamento->idstatusreport;
        }

        ####################################ATVIDADES#########################################
        $arrayDatas = $serviceStatusReport->retornaPeriodoAcompanhamento($this->_request->getParams());
        $desatividadeconcluida = $serviceStatusReport->getAtividadesConcluidas($arrayDatas);
        $desatividadeandamento = $serviceStatusReport->getAtividadesEmAndamento($arrayDatas);

        $statusReport->desatividadeconcluida = $desatividadeconcluida;
        $statusReport->desatividadeandamento = $desatividadeandamento;
        $acompanhamento->desatividadeconcluida = $desatividadeconcluida;
        $acompanhamento->desatividadeandamento = $desatividadeandamento;
        $datasPeriodo = array(
            'datainiperiodo' => $serviceAtividadeCronograma->preparaDataComBarra($arrayDatas['dtInicio']),
            'datafinperiodo' => $serviceAtividadeCronograma->preparaDataComBarra($arrayDatas['dtFim'])
        );

        $numSeiFormatado = (string)new App_Mask_NumeroSei($projeto->numprocessosei);
        $this->view->numProcessoSei = $numSeiFormatado;
        $this->view->ultimoPrazo = $ultimoPrazo->prazo;
        $this->view->form = $form;
        $this->view->diasmarco = $dias;
        $this->view->statusReport = $statusReport;
        $this->view->entregasMarcos = $entregasMarcos;
        $this->view->projeto = $projeto;
        $this->view->listaAcompanhamentos = $listaAcompanhamentos;
        $this->view->acompanhamento = $acompanhamento;
        $this->view->proximoMarco = $proximoMarco;
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $this->view->periodo = $datasPeriodo;
    }

    public function relatoriojsonAction()
    {
        $service = new Projeto_Service_StatusReport();
        $serviceRelatorio = new Projeto_Service_Relatorio();
        $params = array();
        $params['idprojeto'] = $this->_request->getParam('idprojeto');
        $idstatusreport = $this->_request->getParam('idstatusreport');

        if (null != $idstatusreport && (!empty($idstatusreport))) {
            $params['idstatusreport'] = $idstatusreport;
        }

        $acompanhamentos = $service->retornaAcompanhamentosPorProjeto($this->_request->getParams(), true);
        $resultado = $serviceRelatorio->getFiles($acompanhamentos, $this->_request->getParams());
        $this->_helper->json->sendJson($resultado);
    }

    public function atualizarcabecalhojsonAction()
    {
        if ($this->_request->isPost()) {
            $idprojeto = (int)$this->_request->getParam('idprojeto');
            $serviceGerencia = new Projeto_Service_Gerencia();
            $projeto = $serviceGerencia->retornaArrayCronogramaProjetoPorId(array('idprojeto' => $idprojeto));
            $this->view->projeto = $projeto;
            $this->_helper->json->sendJson($projeto);
        }
    }

    public function addAction()
    {
        $serviceStatusReport = new Projeto_Service_StatusReport();
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $serviceGerencia = new Projeto_Service_Gerencia();
        $serviceR3g = new Projeto_Service_R3g();
        $serviceRisco = new Projeto_Service_Risco();
        $serviceSituacao = new Projeto_Service_SituacaoProjeto();
        $arrayAcaompanhamento = array();
        $success = false;
        if ($this->_request->isPost()) {

            $dados = $this->_request->getPost();

            $serviceStatusReport = new Projeto_Service_StatusReport();

            /** @var Projeto_Model_Statusreport $acompanhamento */
            $acompanhamento = $serviceStatusReport->inserir($dados);

            if (is_object($acompanhamento) && (!empty($acompanhamento->idstatusreport))) {
                $this->imprimirPdfAction();
                $success = true; /* AUTENTICATION SUCCESS */
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $serviceStatusReport->getErrors();
            }
        } else {
            $ignoreatividadesatrasadas = (@trim($this->_request->getParam('ignoreatividadesatrasadas') != "") ? true : false);
            $datasPeriodo = $serviceAtividadeCronograma->retornaDataPeriodo($this->_request->getParams());
            // Verificando se existe atividade desatualizada no cronograma
            $atividadesDesatualizadas = $serviceAtividadeCronograma->retornaTextIrregularidades(array(
                'idprojeto' => $this->_request->getParam('idprojeto'),
                'listaArray' => true
            ));
            $array = $serviceAtividadeCronograma->retornaCronogramaByArray($this->_request->getParams());
            $projeto = $array['projeto'];
            $form = $serviceStatusReport->getForm($this->_request->getParams());
            $this->view->form = $form;
            $this->view->projeto = $projeto;
            $this->view->ignoreatividadesatrasadas = $ignoreatividadesatrasadas;

            if ((count($atividadesDesatualizadas) > 0) && ($ignoreatividadesatrasadas == false)) {
                if (!$ignoreatividadesatrasadas) {
                    $this->view->atividadesDesatualizadas = $atividadesDesatualizadas;
                    $this->view->QtAtividadesDesatualizadas = count($atividadesDesatualizadas);
                }
            } else {
                $statusReport = $serviceGerencia->generateStatusReport(
                    array('idprojeto' => $this->_request->getParam('idprojeto'))
                );
                $statusProjeto = $serviceStatusReport->getStatusProjeto();
                $this->view->idprojeto = $this->_request->getParam('idprojeto');
                $this->view->ultimoStatusReport = $projeto['ultimoStatusReport'];
                $this->view->statusreport = $statusReport;
                $this->view->statusprojeto = $statusProjeto;
                $this->view->flaaprovado = $serviceStatusReport->getTapAssinado();
                $this->view->pgpassinado = $serviceStatusReport->getPgpAssinado();
                $this->view->tepassinado = $serviceStatusReport->getTepAssinado();

                $dias = $projeto['prazoEmDias'];
                $this->view->atraso = $dias;
                $this->view->domcoratraso = $projeto['descricaoPrazo'];

                $proximoMarco = $serviceAtividadeCronograma->retornaProximoMarco(
                    array('idprojeto' => $this->_request->getParam('idprojeto'))
                );
                $this->view->proximoMarco = $proximoMarco;
                $this->view->desatividadesconcluidas = "";

                if (count($statusReport['desatividadeconcluida']) > 0) {
                    foreach ($statusReport['desatividadeconcluida'] as $sr) {
                        $this->view->desatividadesconcluidas .= $sr['datinicio'] . " - " . $sr['datfim'] . " - " . $sr['registro'] . "<BR>\n";
                    }
                }
                $datfimprojetotendencia = (isset($projeto['datfimReal']) && (!empty($projeto['datfimReal']))) ? $projeto['datfimReal'] : $projeto['datfim'];

                $this->view->datfimprojetotendencia = $datfimprojetotendencia;

                $desmotivoatraso = (isset($projeto['ultimoStatusReport']['desmotivoatraso']) && (!empty($projeto['ultimoStatusReport']['desmotivoatraso']))) ? $projeto['ultimoStatusReport']['desmotivoatraso'] : "Não há atraso.";
                $params['desmotivoatraso'] = $desmotivoatraso;

                $descontramedida = $serviceR3g->retornaTodasContramedidas($this->_request->getParams());
                $params['descontramedida'] = "";
                foreach ($descontramedida as $cTr) {
                    $params['descontramedida'] .=
                        trim($cTr['desplanejado']) . "\n" .
                        trim($cTr['desrealizado']) . "\n" .
                        trim($cTr['descausa']) . "\n" .
                        trim($cTr['desconsequencia']) . "\n" .
                        trim($cTr['descontramedida']) . "\n - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - \n";
                }
                if (empty($params['descontramedida'])) {
                    $params['descontramedida'] = "Não há contramedidas em andamento.";
                }
                $desirregularidade = $serviceAtividadeCronograma->retornaTextIrregularidades($this->_request->getParams());
                $params['desirregularidade'] = $desirregularidade ? "Atividade(s) Atrasada(s):</br> \n" . $desirregularidade : "Não há irregularidades.";
                $desrisco = $serviceRisco->retornaRiscos($this->_request->getParams());;
                $params['desrisco'] = $desrisco ? $desrisco : "Não há riscos identificados.";

                $serviceStatusReport = new Projeto_Service_StatusReport();
                $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $this->_request->getParam('idstatusreport')));

                $config = Zend_Registry::get('config');
                $dir = $config->resources->cachemanager->default->backend->options->arquivos_dir;
                // Abre um diretorio conhecido, e faz a leitura de seu conteudo
                if (is_dir($dir)) {
                    if ($dh = opendir($dir)) { // <-- AQUI VALIDA  SE RETORNA ALGO
                        while (($file = readdir($dh)) !== false) {

                            if (mb_substr($file, -4) == ".pdf") {
                                if ('pdf_' . $projeto["idprojeto"] . "_" . $statusReport["idstatusreport"] . ".pdf" == $file) {
                                    //$string =  ereg_replace("[^a-zA-Z.]", "", $file); 
                                    // echo "filename: $file : filetype: " . filetype($dir . $file) . "\n";
                                    $this->view->descaminho = $file;
                                    $params['descaminho'] = $file;
                                }
                            }
                        }
                        closedir($dh);
                    }
                }
                $statusDoProjeto = $serviceSituacao->retornaStatusDoProjeto($this->_request->getParams());
                $numProcessoSei = $serviceGerencia->retornaNumProcessoSei($this->_request->getParams());
                $this->view->numProcessoSei = $numProcessoSei;

                $this->view->statusdoprojeto = $statusDoProjeto[0];
                $this->view->periodo = $datasPeriodo;
                $this->view->form->populate($params);
            }
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->imprimirPdfAction();
                if (is_object($acompanhamento)) {
                    $acompanhamento->descaminho = $serviceStatusReport->retornaAnexo(array(
                        'idprojeto' => $acompanhamento->idprojeto,
                        'idstatusreport' => $acompanhamento->idstatusreport
                    ), true, true);
                    $arrayAcaompanhamento = $serviceStatusReport->toArrayAcompanhmento($acompanhamento);

                    $this->view->msg = array(
                        'text' => $msg,
                        'type' => ($success) ? 'success' : 'error',
                        'hide' => true,
                        'acompanhamento' => $arrayAcaompanhamento,
                        'closer' => true,
                        'sticker' => false
                    );
                }
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('projeto', 'relatorio', 'index');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
            }
        }
    }

    public function editarAction()
    {
        $serviceStatusReport = new Projeto_Service_StatusReport();
        $serviceAtivCron = new Projeto_Service_AtividadeCronograma();
        $serviceGerencia = new Projeto_Service_Gerencia();
        //TODO $statusReport getbyid
        $success = false;
        $form = $serviceStatusReport->getFormEditar($this->_request->getParams());

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            /** @var  $statusreport Projeto_Model_Statusreport */
            $statusreport = $serviceStatusReport->update($dados);
            $statusreport->descaminho = $serviceStatusReport->retornaAnexo(array(
                'idprojeto' => $dados['idprojeto'],
                'idstatusreport' => $statusreport->idstatusreport
            ), true);

            if ($statusreport) {
                $success = true; ###### AUTENTICATION SUCCESS
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
                $idstatus = $dados['idstatusreport'];
                $idprojeto = $dados['idprojeto'];
                $arrayAcaompanhamento = $serviceStatusReport->toArrayAcompanhmento($statusreport);
                /* Atualiza no projeto*/
                $serviceGerencia->updateTapAssinado($statusreport->toArray());

            } else {
                $msg = $serviceStatusReport->getErrors();
            }
        } else {
            $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
            $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
            //        $statusReport = $serviceGerencia->generateStatusReport(array('idprojeto' => $this->_request->getParam('idprojeto')));
            $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $this->_request->getParam('idstatusreport')));

            $statusProjeto = $serviceStatusReport->getStatusProjeto();

            $marco = $serviceAtividadeCronograma->retornaUltimoMarco(array('idprojeto' => $this->_request->getParam('idprojeto')));

            $proximoMarco = new stdClass();

            $dadosMarco = $serviceAtividadeCronograma->getAtividadeById($statusReport->idmarco,
                $statusReport->idprojeto);
            $proximoMarco->idatividadecronograma = $dadosMarco['idatividadecronograma'];
            $proximoMarco->nomatividadecronograma = $dadosMarco['nomatividadecronograma'];
            $datfim = $marco->datfim;
            $datfimbaseline = $marco->datfimbaseline;

            $this->view->proximoMarco = $proximoMarco;

            $numSeiFormatado = (string)new App_Mask_NumeroSei($projeto->numprocessosei);
            $this->view->numProcessoSei = $numSeiFormatado;

            //$dias = $serviceStatusReport->retornaDiferencaDias($datfim, $datfimbaseline);
            //$this->view->semaforo = $serviceStatusReport->getSemaforo($dias, $projeto->numcriteriofarol);
            $arrayDatas = $serviceStatusReport->retornaPeriodoAcompanhamento($this->_request->getParams());

            $datasPeriodo = array(
                'datainiperiodo' => $serviceAtividadeCronograma->preparaDataComBarra($arrayDatas['dtInicio']),
                'datafinperiodo' => $serviceAtividadeCronograma->preparaDataComBarra($arrayDatas['dtFim'])
            );

            $this->view->projeto = $projeto;
            $this->view->statusreport = $statusReport;
            $this->view->statusprojeto = $statusProjeto;

            $statusreport = $serviceStatusReport->getById($this->_request->getParams());
            //$form->populate($statusreport->formPopulate());

            $anexo = $serviceStatusReport->retornaAnexo($this->_request->getParams(), false, true);

            $this->view->periodo = $datasPeriodo;
            $this->view->anexo = $anexo;
            $this->view->gerencia = $statusreport;
            $this->view->form = $form;
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'idstatus' => $idstatus,
                    'idprojeto' => $idprojeto,
                    'acompanhamento' => $arrayAcaompanhamento,
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

    public function excluirAction()
    {
        $serviceStatusReport = new Projeto_Service_StatusReport();
        $serviceGerencia = new Projeto_Service_Gerencia();
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $this->_request->getParam('idstatusreport')));
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $idstatusreportAnterior = $dados['idstatusreport'];
            $documento = $serviceStatusReport->excluir($dados);
            $ultimoAcompanhamento = new Projeto_Model_Statusreport();
            $ultimoAcompanhamento = $serviceStatusReport->retornaUltimoAcompanhamento(array('idprojeto' => (int)$dados['idprojeto']),
                false);
            $ultimoAcompanhamento->descaminho = $serviceStatusReport->retornaAnexo(array(
                'idprojeto' => $dados['idprojeto'],
                'idstatusreport' => $ultimoAcompanhamento->idstatusreport
            ), false, true);
            if ($documento) {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'E'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
                $idprojeto = $dados['idprojeto'];
                $arrayAcaompanhamento = $serviceStatusReport->toArrayAcompanhmento($ultimoAcompanhamento);
            } else {
                $msg = $serviceStatusReport->getErrors();
            }
        } else {
            $statusProjeto = $serviceStatusReport->getStatusProjeto();
            $form = $serviceStatusReport->getFormExcluir($this->_request->getParams());
            //        $form = $serviceStatusReport->getForm();

            $numProcessoSei = $serviceGerencia->retornaNumProcessoSei($this->_request->getParams());
            $numSeiFormatado = (string)new App_Mask_NumeroSei($numProcessoSei['numprocessosei']);
            $this->view->numProcessoSei = $numSeiFormatado;

            ####################################ATVIDADES#########################################
            $arrayDatas = $serviceStatusReport->retornaPeriodoAcompanhamento($this->_request->getParams());
            $desatividadeconcluida = $serviceStatusReport->getAtividadesConcluidas($arrayDatas);
            $desatividadeandamento = $serviceStatusReport->getAtividadesEmAndamento($arrayDatas);
            $statusReport->desatividadeconcluida = $desatividadeconcluida;
            $statusReport->desatividadeandamento = $desatividadeandamento;

            $datasPeriodo = array(
                'datainiperiodo' => $serviceAtividadeCronograma->preparaDataComBarra($arrayDatas['dtInicio']),
                'datafinperiodo' => $serviceAtividadeCronograma->preparaDataComBarra($arrayDatas['dtFim'])
            );

            $this->view->periodo = $datasPeriodo;
            $this->view->projeto = $projeto;
            $this->view->statusreport = $statusReport;
            $this->view->statusprojeto = $statusProjeto;

            $form->populate($statusReport->formPopulate());
            $this->view->gerencia = $statusReport;
            $this->view->form = $form;
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'acompanhamento' => $arrayAcaompanhamento,
                    'idstatus' => $idstatusreportAnterior,
                    'idprojeto' => $idprojeto,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'relatorio', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function detalharAction()
    {
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $serviceStatusReport = new Projeto_Service_StatusReport();
        $serviceGerencia = new Projeto_Service_Gerencia();
        $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $this->_request->getParam('idstatusreport')));
        $success = false;

        $statusProjeto = $serviceStatusReport->getStatusProjeto();
        $form = $serviceStatusReport->getFormExcluir($this->_request->getParams());
        //        $form = $serviceStatusReport->getForm();

        $arrayDatas = $serviceStatusReport->retornaPeriodoAcompanhamento($this->_request->getParams());
        //Zend_Debug::dump($arrayDatas);die;
        $datasPeriodo = array(
            'datainiperiodo' => $serviceAtividadeCronograma->preparaDataComBarra($arrayDatas['dtInicio']),
            'datafinperiodo' => $serviceAtividadeCronograma->preparaDataComBarra($arrayDatas['dtFim'])
        );

        ####################################ATVIDADES#########################################
        $statusreport = $serviceStatusReport->getById($this->_request->getParams());
        $desatividadeconcluida = $serviceStatusReport->getAtividadesConcluidas($arrayDatas);
        $desatividadeandamento = $serviceStatusReport->getAtividadesEmAndamento($arrayDatas);
        $statusreport->desatividadeconcluida = $desatividadeconcluida;
        $statusreport->desatividadeandamento = $desatividadeandamento;

        $numProcessoSei = $serviceGerencia->retornaNumProcessoSei($this->_request->getParams());
        $numSeiFormatado = (string)new App_Mask_NumeroSei($numProcessoSei['numprocessosei']);
        $this->view->numProcessoSei = $numSeiFormatado;

        $this->view->periodo = $datasPeriodo;

        $this->view->projeto = $projeto;
        $this->view->statusreport = $statusReport;
        $this->view->statusprojeto = $statusProjeto;

        //$form->populate($statusreport->formPopulate());
        $this->view->gerencia = $statusreport;
        $this->view->form = $form;
        $this->view->statusreport = $statusreport;
        $msg = "";
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
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'relatorio', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function imprimirPdfAction()
    {
        set_time_limit(0);
        $diasFarol = 0;
        $descriaoPrazoProjeto = null;
        $service = new Projeto_Service_Gerencia();
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $serviceUltimoIdStatusReport = new Projeto_Service_StatusReport();
        $arrayGeral = $serviceAtividadeCronograma->retornaCronogramaByArray($this->_request->getParams());
        $arrayCronograma = $arrayGeral['cronograma'];
        $projeto = $arrayGeral['projeto'];
        $projeto['numprocessosei'] = (string)new App_Mask_NumeroSei($projeto['numprocessosei']);
        $linhaResumo = $projeto;
        $custo = (!empty($linhaResumo['vlratividadet']) && $linhaResumo['vlratividadet'] > 0) ? mb_substr($linhaResumo['vlratividadet'],
                0, -2) . '.' . mb_substr($linhaResumo['vlratividadet'], -2) : number_format(0, 2);
        $diasreal = $linhaResumo['numdiasrealizados'];
        $descricaProjeto = mb_substr($linhaResumo['nomcodigo'] . ' - ' . $linhaResumo['nomprojeto'], 0, 50, 'UTF-8') . '...';

        if (!empty($linhaResumo['datfimReal'])) {
            $descriaoPrazoProjeto = $service->retornaDescricaoFarol($linhaResumo['datfimReal'],
                $linhaResumo['datfimbaseline'], $linhaResumo['numcriteriofarol']);
        }

        if (isset($linhaResumo["datfimbaseline"]) && (!empty($linhaResumo["datfimbaseline"])) &&
            isset($linhaResumo["datfimReal"]) && (!empty($linhaResumo["datfimReal"]))) {
            $datfimbaseline = new Zend_Date($linhaResumo["datfimbaseline"], 'dd/MM/YYYY');
            $datfim = new Zend_Date($linhaResumo["datfimReal"], 'dd/MM/YYYY');

            if (Zend_Date::isDate($datfimbaseline) && Zend_Date::isDate($datfim)) {
                $dados['datainicio'] = $datfimbaseline;
                $dados['datafim'] = $datfim;
                $diasFarol = $serviceAtividadeCronograma->retornaQtdeDiasUteisEntreDatas($dados);

                /**********************************************************/
                /* retira um dia do cálculo para atender a regra definida */
                $diasFarol = ($diasFarol > 0 ? $diasFarol - 1 : $diasFarol + 1);
            }
        }

        $this->view->linhaCorFarol = $descriaoPrazoProjeto;
        $this->view->linhaResumoFarol = $diasFarol;
        $this->view->linhaResumoDtPlanejado = (!empty($linhaResumo["datiniciobaseline"])) ? $linhaResumo["datiniciobaseline"] . ' a ' . $linhaResumo["datfimbaseline"] : "";
        $this->view->linhaResumoDtRealizado = (!empty($linhaResumo["datinicioReal"])) ? $linhaResumo["datinicioReal"] . ' a ' . $linhaResumo["datfimReal"] : "";
        $this->view->linhaResumoDiasBaseLine = (!empty($linhaResumo["numdiasbaseline"])) ? $linhaResumo["numdiasbaseline"] : "";
        $this->view->linhaResumoCusto = $custo;
        $this->view->linhaResumoDiasRealizados = $diasreal;
        $this->view->linhaResumoPercentual = (!empty($linhaResumo["numpercentualconcluido"])) ? $linhaResumo["numpercentualconcluido"] . '%' : '0%';
        $this->view->linhaResumoNomProjeto = (!empty($descricaProjeto)) ? $descricaProjeto : "";
        $this->view->cronograma = $arrayCronograma;
        $this->view->projeto = $projeto;

        $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');
        $cabecalhoProjeto = $this->view->render('/_partials/projeto-cabecalho.phtml');
        $cronograma = $this->view->render('/_partials/cron-imprimir-pdf.phtml');

        $this->mpdf = new App_Service_MPDF('UTF-8', 'A4-L', '', '', 15, 15, 15, 25, 10, 15, '');
        $this->mpdf->AddPage('L', '', '', '', '', 15, 15, 15, 20, 15, 15);
        $this->mpdf->setFooter('{DATE j/m/Y} - Pág. {PAGENO}/{nb}');

        $stylesheet = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);

        $css = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

        $cssBootstrap = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap.min.css');
        $this->mpdf->WriteHTML($cssBootstrap, 1);

        $cssBootstrapResp = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap-responsive.min.css');
        $this->mpdf->WriteHTML($cssBootstrapResp, 1);

        $this->mpdf->WriteHTML($cabecalho);
        $this->mpdf->WriteHTML($cabecalhoProjeto);
        $this->mpdf->WriteHTML($cronograma);
        $config = Zend_Registry::get('config');
        $ultimoIdStatusReport = $serviceUltimoIdStatusReport->ultimoId();
        $dir = $config->resources->cachemanager->default->backend->options->arquivos_dir;
        $this->mpdf->Output($dir . 'pdf_' . $linhaResumo["idprojeto"] . '_' . $ultimoIdStatusReport . '.pdf', 'F');
    }
}
