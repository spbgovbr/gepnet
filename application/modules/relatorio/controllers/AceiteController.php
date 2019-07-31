<?php

class Relatorio_AceiteController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $service = App_Service_ServiceAbstract::getService('Relatorio_Service_Aceite');
        $form = $service->getFormPesquisar();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $params = array_filter($request->getPost());
            $paramsLink = "";
            foreach ($params as $key => $value) {
                $paramsLink .= "/$key/$value";
            }
            $this->view->relatorio = $service->gerarRelatorio($request->getPost());
            $this->view->paramsLink = $paramsLink;
            $form->populate($request->getPost());
        }
        $this->view->formPesquisar = $form;
    }

    public function imprimirPdfAction()
    {
        $service = App_Service_ServiceAbstract::getService('Relatorio_Service_Aceite');
        $this->_helper->layout->disableLayout();
        $params = array_filter($this->_request->getParams());
        $service->gerarRelatorio($params);
        $this->view->relatorio = $service->gerarRelatorio($params);
        $serviceImprimir = new Default_Service_Impressao();
        $serviceImprimir->setMargin(15, 15, 15, 15, 10, 10);
        $serviceImprimir->adicionaPagina("L");
        $serviceImprimir->insertFooter("html");
        $serviceImprimir->cssHtml = true;
        $serviceImprimir->addHtml('../public/js/library/bootstrap/css/bootstrap.min.css', 1, true);
        $serviceImprimir->addHtml('../public/js/library/bootstrap/css/bootstrap-responsive.min.css', 1, true);
        $serviceImprimir->addHtml('../library/MPDF57/examples/mpdfstyletables.css', 1, true);
        $serviceImprimir->addHtml('../library/MPDF57/mpdf.css', 1, true);
        $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');
        $html = $this->view->render('/_partials/aceite-imprimir-pdf.phtml');
        $this->_helper->layout->disableLayout();
        $serviceImprimir->addHtml($cabecalho, 2);
        $serviceImprimir->addHtml($html, 2);
        $serviceImprimir->gerarPdfHtml("L");
        $this->_helper->layout->disableLayout();/**/
    }
}
