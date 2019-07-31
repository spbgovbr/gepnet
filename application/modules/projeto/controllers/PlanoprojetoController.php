<?php

class Projeto_PlanoprojetoController extends Zend_Controller_Action
{
    /**
     * @var $mpdf App_Service_MPDF
     */
    private $mpdf;

    public function init()
    {
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

    }

    public function imprimirAction()
    {
        set_time_limit(0);
        $diasreal = 0;
        $diasFarol = 0;
        $custo = 0;
        $descriaoPrazoProjeto = null;
        $serviceCron = new Projeto_Service_AtividadeCronograma();
        $serviceGerencia = new Projeto_Service_Gerencia();
        $serviceComunica = new Projeto_Service_Comunicacao();
        $serviceAssinatura = new Projeto_Service_Assinadocumento();
        $serviceEap = new Projeto_Service_Eap();
        $risco = new Projeto_Service_Risco();
        $service = new Projeto_Service_Gerencia();
        $params = $this->_request->getParams();
        $matrizRisco = $risco->matrizRisco(
            array(
                'idprojeto' => $params['idprojeto'],
                'flariscoativo' => "1"
            )
        );
        $retornaComunica = $serviceComunica->retornaComunicacaoPorIdProjeto(array('idprojeto' => $params['idprojeto']));
        $this->view->comunica = $retornaComunica;
        $this->view->risco = $matrizRisco;
        $arrayCronograma = $serviceCron->retornaCronogramaByArray($this->_request->getParams());
        $projeto['pdf'] = true;
        $projeto = $arrayCronograma['projeto'];
        $arrayCronograma = $arrayCronograma['cronograma'];
        $linhaResumo = $projeto;
        $numSeiFormatado = (string)new App_Mask_NumeroSei($projeto['numprocessosei']);
        $descricaProjeto = mb_substr($linhaResumo['nomcodigo'] . ' - ' . $linhaResumo['nomprojeto'], 0, 50, 'UTF-8') . '...';
        $custo = (!empty($projeto['vlratividadet']) && $projeto['vlratividadet'] > 0) ? mb_substr($projeto['vlratividadet'],
                0, -2) . '.' . mb_substr($projeto['vlratividadet'], -2) : number_format(0, 2);
        $diasreal = $projeto['numdiasrealizados'];
        $descriaoPrazoProjeto = $projeto['descricaoPrazo'];

        $diasFarol = $projeto['prazoEmDias'];
        $eap = $serviceEap->montaEAP($this->_request->getParams());
        $this->view->projetosEap = $eap['projeto'];
        $this->view->projetos = $serviceGerencia->retornaArrayProjetoPorId($projeto);

        $assinaturas = $serviceAssinatura->retornaAssinaturaPorTipoEProjeto($this->_request->getParams());
        $this->view->assinaturas = $assinaturas;
        $this->view->linhaResumoFarol = $diasFarol;
        $this->view->linhaCorFarol = $descriaoPrazoProjeto;
        $this->view->cronograma = $arrayCronograma;
        $this->view->projeto = $projeto;
        $this->view->numProcessoSei = $numSeiFormatado;
        $this->view->linhaResumoDtPlanejado = (!empty($projeto["datiniciobaseline"])) ? $projeto["datiniciobaseline"] . ' a ' . $projeto["datfimbaseline"] : "";
        $this->view->linhaResumoDtRealizado = (!empty($projeto["datinicioReal"])) ? $projeto["datinicioReal"] . ' a ' . $projeto["datfimReal"] : "";
        $this->view->linhaResumoDiasBaseLine = (!empty($projeto["numdiasbaseline"])) ? $projeto["numdiasbaseline"] : "";
        $this->view->linhaResumoCusto = $custo;
        $this->view->linhaResumoDiasRealizados = $diasreal;
        $this->view->linhaResumoPercentual = (!empty($projeto["numpercentualconcluido"])) ? $projeto["numpercentualconcluido"] . '%' : '0%';
        $this->view->linhaResumoNomProjeto = (!empty($descricaProjeto)) ? $descricaProjeto : "";


        $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');
        $plano_gerenciamento_projeto = $this->view->render('/_partials/plano-gerenciamento-projeto-imprimir.phtml');
        $cabProjeto = $this->view->render('/_partials/cabecalho_projeto.phtml');
        $eap = $this->view->render('/_partials/plano-eap.phtml');
        //$dicionario_eap = $this->view->render('/_partials/plano-eap-dicionario.phtml');
        $cronograma = $this->view->render('/_partials/cron-imprimir-pdf.phtml');
        $planos_aprovacao = $this->view->render('/_partials/plano-aprovacao.phtml');
        $this->_helper->layout->disableLayout();

        $this->mpdf = new App_Service_MPDF('UTF-8', 'A4-L', '', '', 15, 15, 15, 25, 10, 15, '');
        $this->mpdf->AddPage('L', '', '', '', '', 15, 15, 15, 20, 15, 15);
        $this->mpdf->setFooter('{DATE j/m/Y} - Pág. {PAGENO}/{nb}');

        $stylesheet = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);

        $css = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

        $cssBootstrap = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap.min.css');
        $this->mpdf->WriteHTML($cssBootstrap, 1, true);

        $cssBootstrapResp = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap-responsive.min.css');
        $this->mpdf->WriteHTML($cssBootstrapResp, 1, true);

        // Página no formato de retrato. //////////////////////////////////
        //$this->mpdf->AddPage('L', '', '', '', '', 20, 20, 20, 20, 20, 20);
        ///////////////////////////////////////////////////////////////////

        $this->mpdf->WriteHTML($cabecalho);
        $this->mpdf->WriteHTML($plano_gerenciamento_projeto);
        $this->mpdf->WriteHTML($cabProjeto);
        $this->mpdf->WriteHTML($eap);
        $this->mpdf->setHeader();
        $this->mpdf->AddPage();
        //$this->mpdf->WriteHTML($dicionario_eap);
        $this->mpdf->WriteHTML($cronograma);
        $this->mpdf->WriteHTML($planos_aprovacao);
        $this->mpdf->Output('document.pdf', 'I');

    }

    public function imprimirWordAction()
    {
        $local = false;
        $PosLocal = strpos($_SERVER['SERVER_NAME'], "localhost");
        if (!($PosLocal === false)) {
            $local = true;
        }
        $protocol = "http:";
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
            $protocol = "https:";
        }
        $this->_helper->layout->disableLayout();
        $serviceCron = new Projeto_Service_AtividadeCronograma();
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceComunica = App_Service_ServiceAbstract::getService('Projeto_Service_Comunicacao');
        $params = $this->_request->getParams();
        $risco = new Projeto_Service_Risco();
        $matrizRisco = $risco->matrizRisco(array('idprojeto' => $params['idprojeto']));
        $retornaComunica = $serviceComunica->retornaComunicacaoPorIdProjeto(array('idprojeto' => $params['idprojeto']));
        $projetos = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());
        $itemProjeto = $serviceGerencia->retornaArrayProjetoPorId($this->_request->getParams());

        $this->view->comunica = $retornaComunica;
        $this->view->risco = $matrizRisco;
        $this->view->projetos = $projetos;
        $this->view->projeto = $itemProjeto;
        $this->view->server = $protocol . '//' . $_SERVER['SERVER_NAME'] . ($local ? ':83' : '') . '/gepnet/public/';

        $numSeiFormatado = (string)new App_Mask_NumeroSei($projetos->numprocessosei);
        $this->view->numProcessoSei = $numSeiFormatado;

        $service = new Projeto_Service_Gerencia();
        $this->view->projeto = $service->retornaArrayProjetoPorId($this->_request->getParams());
        $linhaResumo = $service->retornaArrayCronogramaProjetoPorId($this->_request->getParams());
        $this->view->linhaResumoNomProjeto = mb_substr($linhaResumo['nomcodigo'] . ' - ' . $linhaResumo['nomprojeto'],
                0, 48) . '...';
        $this->view->linhaResumoDtPlanejado = $linhaResumo["datiniciobaselinet"] . ' &agrave;  ' . $linhaResumo["datfimbaselinet"];
        $this->view->linhaResumoDtRealizado = $linhaResumo["datiniciot"] . ' &agrave;  ' . $linhaResumo["datfimt"];
        $this->view->linhaResumoDiasBaseLine = $linhaResumo["diasbaselinet"];
        $this->view->linhaResumoCusto = (!empty($linhaResumo['vlratividadet']) && $linhaResumo['vlratividadet'] > 0) ? mb_substr($linhaResumo['vlratividadet'],
                0, -2) . '.' . mb_substr($linhaResumo['vlratividadet'], -2) : number_format(0, 2);
        $this->view->linhaResumoDiasRealizados = $linhaResumo["diasrealt"];
        $this->view->linhaResumoPercentual = $linhaResumo["numpercentualconcluidot"] . '%';
        $this->view->linhaCorFarol = (int)$linhaResumo["numpercentualconcluidot"] == 100 ? "" : $linhaResumo['descricaoAtrasoFarol'];

        $numPrazoEmDias = $projetos->retornaPrazoEmDias();
        $this->view->linhaResumoFarol = $numPrazoEmDias;

        header("Content-type: application/vnd.ms-word");
        header("Content-Type: application/force-download; charset=UTF-8");
        header("Cache-Control: no-store, no-cache");
        header("Content-disposition: inline; filename=planoProjeto" . $params['idprojeto'] . ".doc");

    }

}
