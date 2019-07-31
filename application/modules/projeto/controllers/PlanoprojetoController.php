<?php

class Projeto_PlanoprojetoController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        
    }

    public function imprimirAction() {
        $this->_helper->layout->disableLayout();
        $serviceGerencia      = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceComunica      = App_Service_ServiceAbstract::getService('Projeto_Service_Comunicacao');
        $params               = $this->_request->getParams();
        $risco                = new Projeto_Service_Risco();
        $matrizRisco          = $risco->matrizRisco(array('idprojeto' =>$params['idprojeto']));  
        $retornaComunica      = $serviceComunica->retornaComunicacaoPorIdProjeto(array('idprojeto' =>$params['idprojeto']));
        //$retonraPartes        = $partesGerencia->retornaPorId();
        //Zend_Debug::dump($retonraPartes);die;
        $this->view->comunica = $retornaComunica;
        $this->view->risco    = $matrizRisco;
        $this->view->projetos  = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());
        $projeto = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());
        //Zend_Debug::dump($projeto->partes); die;
        //$gerecia = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());
        //$retornaPessoa        = $servicePessoa->retornaPessoaProjeto(array('idprojeto' => $this->_request->getParams()));
//        $this->view->projeto    = $serviceGerencia->retornaArrayProjetoPorId($this->_request->getParams());
//        $client = new Zend_Http_Client(Zend_Controller_Front::getInstance()->getBaseUrl() . '/projeto/cronograma/index/idprojeto/' . $this->_request->getParam('idprojeto'));
//        $response = $client->request();
//        print "<PRE>";
//        print_r($response);
//        exit;
//        print "<PRE>";
//        print_r($this->view->projeto->grupos);
//        exit;

        $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');
        $plano_gerenciamento_projeto = $this->view->render('/_partials/plano-gerenciamento-projeto-imprimir.phtml');
        $service = new Projeto_Service_Gerencia();
        $this->view->projeto = $service->retornaArrayProjetoPorId($this->_request->getParams());
        $eap = $this->view->render('/_partials/plano-eap.phtml');
        $dicionario_eap = $this->view->render('/_partials/plano-eap-dicionario.phtml');
        $cronograma = $this->view->render('/_partials/cron-imprimir-pdf.phtml');
        $planos_aprovacao = $this->view->render('/_partials/plano-aprovacao.phtml');
        
        define('_MPDF_PATH', '../library/MPDF57/');
        include('../library/MPDF57/mpdf.php');
        
        $this->mpdf = new mPDF('UTF-8', 'A4', '', '', 20, 20, 20, 20, 20, 20);

        $this->mpdf->SetDisplayMode('fullpage', 'two');

        $this->mpdf->mirrorMargins = 1;

        $stylesheet = file_get_contents('../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);

        $css = file_get_contents('../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

        $this->mpdf->WriteHTML($cabecalho);
        $this->mpdf->WriteHTML($plano_gerenciamento_projeto);
        
        // Página no formato de retrato. //////////////////////////////////
        $this->mpdf->AddPage('L', '', '', '', '', 20, 20, 20, 20, 20, 20);
        ///////////////////////////////////////////////////////////////////
        $this->mpdf->WriteHTML($eap);
        
        $this->mpdf->setHeader(); 
        $this->mpdf->AddPage();
        $this->mpdf->WriteHTML($dicionario_eap);
        $this->mpdf->WriteHTML($cronograma);
        $this->mpdf->WriteHTML($planos_aprovacao);
        
//        $this->mpdf->SetHTMLHeader($header);
//        $this->mpdf->SetHTMLFooter($this->footer);

//        if ($orientation == "L") {
//            $this->mpdf->AddPage('L', '', '', '', '', 25, 25, 55, 45, 18, 12);
//        } else {
//            $this->mpdf->AddPage();
//        }

//        $this->mpdf->WriteHTML($this->body, 2);

        $this->mpdf->Output('document.pdf', 'I');


//        $html = $this->view->render('/_partials/plano-projeto-imprimir.phtml');
//        
//        $serviceImprimir = App_Service_ServiceAbstract::getService('Default_Service_Impressao');
//        
//        $serviceImprimir->gerarPdf(utf8_encode($html),"L");
    }

}
