<?php

class Projeto_StatusReportController extends Zend_Controller_Action
{

    private $_oPChartModel;

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('chartmarcojson', 'json')
            ->addActionContext('chartmarcoreljson', 'json')
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
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $form = $service->getFormPesquisar();
        $success = false;
        $dados = null;
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

        $service = new Default_Service_Versao();

        $resultado = new stdClass();

        $resultado = $service->mostraUltimaVersao();

        if ($resultado->resposta == 'visualizar') {
            $this->view->versaoHTML = $resultado;
        }

        if (isset($dados) && $dados != null) {
            $this->view->codobjetivo = $dados['codobjetivo'];
            $this->view->codacao = $dados['codacao'];
        }

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
                    $this->_helper->_redirector->gotoSimpleAndExit('projeto', 'statusreport', 'index');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
            }
        }
    }

    public function pesquisarjsonAction()
    {
        $serviceGerencia = new Projeto_Service_Gerencia();
        $auth = Zend_Auth::getInstance();
        $identiti = $auth->getIdentity();
        $idperfil = $identiti->perfilAtivo->idperfil;
        $idescritorio = $identiti->perfilAtivo->idescritorio;
        $dados = $this->_request->getParams();

        //$paginator = $serviceGerencia->pesquisarProjetosPublicos($dados, $idperfil, $idescritorio, true);
        $paginator = $serviceGerencia->pesquisarGerenciaProjeto($dados, $idperfil, $idescritorio, true);

        $this->_helper->json->sendJson($paginator);
    }

    public function detalharAction()
    {
        $idStatusReport = null;
        $idStatusReport = $this->_request->getParam('idstatusreport');
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $form = $serviceStatusReport->getFormPesquisar();

        $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $listaAcompanhamentos = $serviceStatusReport->retornaAcompanhamentosPorProjeto(array('idprojeto' => $this->_request->getParam('idprojeto')),
            false);
        $entregasMarcos = $serviceAtividadeCronograma->retornaEntregasEMarcosPorProjeto(array(
            'idprojeto' => $this->_request->getParam('idprojeto'),
            'domtipoatividade' => '2,4'
        ), true);
        $proximoMarco = $serviceAtividadeCronograma->retornaProximoMarco(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $ultimoPrazo = $serviceStatusReport->getUltimoPrazo(array(
            'idstatusreport' => $this->_request->getParam('idstatusreport'),
            'idprojeto' => $this->_request->getParam('idprojeto')
        ), true);
        $this->view->form = $form;
        $this->view->projeto = $projeto;
        $this->view->listaAcompanhamentos = $listaAcompanhamentos;
        $this->view->entregasMarcos = $entregasMarcos;
        $this->view->proximoMarco = $proximoMarco;
        $this->view->ultimoPrazo = $ultimoPrazo->prazo;

        if ($idStatusReport != null) {
            $acompanhamento = $serviceStatusReport->retornaAcompanhamentoPorId(array(
                'idstatusreport' => $this->_request->getParam('idstatusreport'),
                'idprojeto' => $this->_request->getParam('idprojeto')
            ), false);
            $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $this->_request->getParam('idstatusreport')));
            $this->view->statusReport = $statusReport;
            //retorna o marco relativo ao statusreport e manda para a view
        } else {
            $acompanhamento = $serviceStatusReport->retornaUltimoAcompanhamento(array('idprojeto' => $this->_request->getParam('idprojeto')),
                false);
            $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $acompanhamento->idstatusreport));
            $this->view->statusReport = $statusReport;
        }
        $atividadeCronograma = $serviceAtividadeCronograma->retornaAtividadePorId(array(
            'idprojeto' => $this->_request->getParam('idprojeto'),
            'idatividadecronograma' => $statusReport->idmarco
        ), false);
        if (@trim($atividadeCronograma['datfimbaseline']) != "") {
            $datfimbaseline = new Zend_Date($atividadeCronograma['datfimbaseline'], 'dd/MM/YYYY');
        } else {
            $datfimbaseline = "";
        }

        if (@trim($atividadeCronograma['datfim']) != "") {
            $datfim = new Zend_Date($atividadeCronograma['datfim'], 'dd/MM/YYYY');
        } else {
            $datfim = "";
        }

        if (@trim($atividadeCronograma['datfimbaseline']) != "") {
            $dados['datainicio'] = new Zend_Date($atividadeCronograma['datfimbaseline'], 'dd/MM/YYYY');
        } else {
            $dados['datainicio'] = "";
        }

        if (@trim($atividadeCronograma['datfim']) != "") {
            $dados['datafim'] = new Zend_Date($atividadeCronograma['datfim'], 'dd/MM/YYYY');
        } else {
            $dados['datafim'] = "";
        }
        $dias = 0;
        if (($dados['datainicio'] != "") && ($dados['datafim'] != "")) {
            $dias = $serviceAtividadeCronograma->retornaQtdeDiasUteisEntreDatas($dados);
        }

        if ($dias != 0) {
            /**********************************************************/
            /* retira um dia do cálculo para atender a regra definida */
            $dias = $dias * (-1);
            $dias = ($dias > 0 ? $dias - 1 : $dias + 1);
        }
        $this->view->diasmarco = $dias;
        $this->view->acompanhamento = $acompanhamento;
    }

    public function chartplanejadorealizadojsonAction()
    {
        $service = new Projeto_Service_StatusReport();
        $resultado = $service->getChartPlanejadoRealizado($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }

    public function chartatrasojsonAction()
    {
        $service = new Projeto_Service_StatusReport();
        $resultado = $service->getChartEvolucaoAtraso($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }

    public function chartprazojsonAction()
    {
        $service = new Projeto_Service_StatusReport();
        $resultado = $service->getChartPrazo($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }

    public function chartmarcojsonAction()
    {
        $service = new Projeto_Service_StatusReport();
        $resultado = $service->getChartMarco($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }

    public function chartmarcoreljsonAction()
    {
        $service = new Projeto_Service_StatusReport();
        $resultado = $service->getChartMarcoByRelatorio($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }

    public function imprimirPdfAction()
    {
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
        $form = $serviceStatusReport->getFormPesquisar();

        $projeto = $serviceGerencia->retornaArrayProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $listaAcompanhamentos = $serviceStatusReport->retornaAcompanhamentosPorProjeto(array('idprojeto' => $this->_request->getParam('idprojeto')),
            false);
        $entregasMarcos = $serviceAtividadeCronograma->retornaEntregasEMarcosPorProjeto(array(
            'idprojeto' => $this->_request->getParam('idprojeto'),
            'domtipoatividade' => '2,4'
        ), true);
        $proximoMarco = $serviceAtividadeCronograma->retornaProximoMarco(array('idprojeto' => $this->_request->getParam('idprojeto')));

        $projeto['pdf'] = true;

        $this->view->projeto = $projeto;
        $this->view->listaAcompanhamentos = $listaAcompanhamentos;
        $this->view->form = $form;
        $this->view->entregasMarcos = $entregasMarcos;
        $this->view->proximoMarco = $proximoMarco;

        if ($this->_request->getParam('idstatusreport')) {
            $acompanhamento = $serviceStatusReport->retornaAcompanhamentoPorId(array(
                'idstatusreport' => $this->_request->getParam('idstatusreport'),
                'idprojeto' => $this->_request->getParam('idprojeto')
            ), false);
            $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $this->_request->getParam('idstatusreport')));
            $this->view->statusReport = $statusReport;
            //retorna o marco relativo ao statusreport e manda para a view

        } else {
            $acompanhamento = $statusReport = $serviceStatusReport->retornaUltimoAcompanhamento(array('idprojeto' => $this->_request->getParam('idprojeto')),
                false);
        }

        $atividadeCronograma = $serviceAtividadeCronograma->retornaAtividadePorId(array(
            'idprojeto' => $this->_request->getParam('idprojeto'),
            'idatividadecronograma' => $statusReport->idmarco
        ), false);
        $dias = 0;

        if (!empty($atividadeCronograma['datfimbaseline']) && (!empty($atividadeCronograma['datfim']))
            && $atividadeCronograma['datfimbaseline'] != "" && $atividadeCronograma['datfim'] != "") {
            $datfimbaseline = new Zend_Date($atividadeCronograma['datfimbaseline'], 'dd/MM/YYYY');
            $datfim = new Zend_Date($atividadeCronograma['datfim'], 'dd/MM/YYYY');
            $dados['datainicio'] = new Zend_Date($atividadeCronograma['datfimbaseline'], 'dd/MM/YYYY');
            $dados['datafim'] = new Zend_Date($atividadeCronograma['datfim'], 'dd/MM/YYYY');
            $dias = $serviceAtividadeCronograma->retornaQtdeDiasUteisEntreDatas($dados);

            /**********************************************************/
            /* retira um dia do cálculo para atender a regra definida */
            $dias = $dias * (-1);
            $dias = ($dias > 0 ? $dias - 1 : $dias + 1);
        }

        $this->view->diasmarco = $dias;
        $this->view->acompanhamento = $acompanhamento;

    }

    public function visualizarimpressaoAction()
    {
        $this->_helper->layout->disableLayout();
        $serviceGerencia = new Projeto_Service_Gerencia();
        $serviceStatusReport = new Projeto_Service_StatusReport();
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $projeto = $serviceGerencia->retornaProjetoPorId(
            array('idprojeto' => $this->_request->getParam('idprojeto'))
        );
        $listaAcompanhamentos = $serviceStatusReport->retornaAcompanhamentosPorProjeto(
            array('idprojeto' => $this->_request->getParam('idprojeto')), false
        );
//        $entregasMarcos = $serviceAtividadeCronograma->retornaEntregasEMarcosPorProjeto(
//            array('idprojeto' => $this->_request->getParam('idprojeto'), 'domtipoatividade' => '2,4'), true
//        );
        $entregasMarcos = $serviceAtividadeCronograma->retornaEntregasEMarcosPorProjetoRelatorio(
            array('idprojeto' => $this->_request->getParam('idprojeto'), 'domtipoatividade' => '2,4'), true
        );

        // flag aprovado S ou N será alterada para sim ou nao
        if ($projeto['flaaprovado'] == 'S') {
            $aprovadoSimNao = 'Sim';
        }
        if ($projeto['flaaprovado'] == 'N') {
            $aprovadoSimNao = 'Não';
        }
        if ($projeto['flacopa'] == 'S') {
            $grandesEventos = 'Sim';
        } else {
            $grandesEventos = 'Não';
        }
        if ($projeto['flapublicado'] == 'S') {
            $telaMestra = 'Sim';
        }
        if ($projeto['flapublicado'] == 'N') {
            $telaMestra = 'Não';
        }
        $dias = 0;

        $idstatusreport = $this->_request->getParam('idstatusreport');

        if (!empty($idstatusreport)) {
            $acompanhamento = $serviceStatusReport->retornaAcompanhamentoPorId(array(
                'idstatusreport' => $this->_request->getParam('idstatusreport'),
                'idprojeto' => $this->_request->getParam('idprojeto')
            ), false);
            $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $this->_request->getParam('idstatusreport')));
            $dadosMarco = $serviceAtividadeCronograma->retornaAtividadeById(array(
                'idatividadecronograma' => $statusReport->idmarco,
                'idprojeto' => $statusReport->idprojeto
            ));

            //retorna o marco relativo ao statusreport e manda para a view
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
            $proximoMarco = new stdClass();
            $proximoMarco->idatividadecronograma = $dadosMarco['idatividadecronograma'];
            $proximoMarco->nomatividadecronograma = $dadosMarco['nomatividadecronograma'];
            $proximoMarco->datfimbaseline = $dadosMarco['datfimbaseline'];
            $proximoMarco->datfim = $dadosMarco['datfim'];
            $this->view->statusReport = $statusReport;
            $ultimoPrazo = $serviceStatusReport->getUltimoPrazo(array(
                'idstatusreport' => $this->_request->getParam('idstatusreport'),
                'idprojeto' => $this->_request->getParam('idprojeto')
            ), true);
            $statusReportImgPlanejadoRealizado = $serviceStatusReport->getImagemPlanejadoRealizado(array(
                'idprojeto' => $this->_request->getParam('idprojeto'),
                'idstatusreport' => $this->_request->getParam('idstatusreport')
            ));
            $statusReportImgEvolucaoAtraso = $serviceStatusReport->getImagemEvolucaoAtraso(array(
                'idprojeto' => $this->_request->getParam('idprojeto'),
                'idstatusreport' => $this->_request->getParam('idstatusreport')
            ));
        } else {
            $acompanhamento = $statusReport = $serviceStatusReport->retornaUltimoAcompanhamento(array('idprojeto' => $this->_request->getParam('idprojeto')),
                false);
            $proximoMarco = $serviceAtividadeCronograma->retornaProximoMarco(array('idprojeto' => $this->_request->getParam('idprojeto')));
            $atividadeCronograma = $serviceAtividadeCronograma->retornaAtividadePorId(array(
                'idprojeto' => $this->_request->getParam('idprojeto'),
                'idatividadecronograma' => $statusReport->idmarco
            ), false);

            if (isset($atividadeCronograma['datfimbaseline']) && isset($atividadeCronograma['datfim']) && $atividadeCronograma['datfimbaseline'] != "" && $atividadeCronograma['datfim'] != "") {
                $datfimbaseline = new Zend_Date($atividadeCronograma['datfimbaseline'], 'dd/MM/YYYY');
                $datfim = new Zend_Date($atividadeCronograma['datfim'], 'dd/MM/YYYY');
                $dados['datainicio'] = $datfimbaseline;
                $dados['datafim'] = $datfim;
                $dias = $serviceAtividadeCronograma->retornaQtdeDiasUteisEntreDatas($dados);

                /**********************************************************/
                /* retira um dia do cálculo para atender a regra definida */
                $dias = $dias * (-1);
                $dias = ($dias > 0 ? $dias - 1 : $dias + 1);
                //$diff = $datfimbaseline->sub($datfim)->toValue();
                //$dias = floor($diff / 60 / 60 / 24);
            }
            $ultimoPrazo = $serviceStatusReport->getUltimoPrazo(array('idprojeto' => $this->_request->getParam('idprojeto')),
                true);
            $statusReportImgPlanejadoRealizado = $serviceStatusReport->getImagemPlanejadoRealizado(array('idprojeto' => $this->_request->getParam('idprojeto')));
            $statusReportImgEvolucaoAtraso = $serviceStatusReport->getImagemEvolucaoAtraso(array('idprojeto' => $this->_request->getParam('idprojeto')));
        }

        $projeto['pdf'] = true;

        $this->view->projeto = $projeto;
        $this->view->listaAcompanhamentos = $listaAcompanhamentos;
        $this->view->entregasMarcos = $entregasMarcos;
        $this->view->proximoMarco = $proximoMarco;
        $this->view->diasmarco = $dias;
        $this->view->aprovado = $aprovadoSimNao;
        $this->view->telaMestra = $telaMestra;
        $this->view->grandesEventos = $grandesEventos;
        $this->view->nomdemandante = $projeto['nomdemandante'];
        $this->view->matricula = $projeto['matricula'];
        $this->view->acompanhamento = $acompanhamento;
        $this->view->desRisco = $projeto->retornaDescricaoRisco();
        $this->view->ultimoPrazo = $ultimoPrazo->prazo;
        $this->view->graficoLinhaPlanejadoRealizado = base64_encode($statusReportImgPlanejadoRealizado);
        /* ************** EvolucaoAtraso - Imagem ****************** */
        $serviceStatusReportEv = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $this->view->graficoLinhaEvolucaoAtraso = base64_encode($statusReportImgEvolucaoAtraso);
        /* ************** FIM - Imagem ***************************** */

        $serviceImprimir = new Default_Service_Impressao();
        $serviceImprimir->setMargin(5, 5, 10, 12, 5, 5);
        $serviceImprimir->adicionaPagina("P");
        $serviceImprimir->insertFooter("html");
        $serviceImprimir->cssHtml = true;
        $serviceImprimir->addHtml('../public/js/library/bootstrap/css/bootstrap.css', 1, true);
        $serviceImprimir->addHtml('../public/js/library/bootstrap/css/bootstrap.min.css', 1, true);
        $serviceImprimir->addHtml('../public/js/library/bootstrap/css/bootstrap-responsive.min.css', 1, true);
        $serviceImprimir->addHtml('../public/css/portlet.css', 1, true);
        $serviceImprimir->addHtml('../library/MPDF57/examples/mpdfstyletables.css', 1, true);
        $serviceImprimir->addHtml('../library/MPDF57/mpdf.css', 1, true);
        $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');
        $html = $this->view->render('/_partials/status-report-visualizar-impressao.phtml');
        $this->_helper->layout->disableLayout();
        $serviceImprimir->addHtml($cabecalho, 2);
        $serviceImprimir->addHtml($html, 2);
        $serviceImprimir->gerarPdfHtml("P");
        $this->_helper->layout->disableLayout();/**/
    }
}