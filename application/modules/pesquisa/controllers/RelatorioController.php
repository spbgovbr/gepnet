<?php

class Pesquisa_RelatorioController extends Zend_Controller_Action
{
    /**
     * @var $mpdf App_Service_MPDF
     */
    private $mpdf;

    public function listarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Relatorio');
        $this->view->formPesquisar = $service->getFormPesquisar();
    }

    public function pesquisarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Relatorio');
        $paginator = $service->retornaPesquisasRelatorioGrid($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function relatorioPercentualAction()
    {
        $request = $this->_request;
        if ($request->isPost()) {
            $params = $request->getParams();
            $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_ResultadoPesquisa');
            $totalPesquisas = $service->totalRespondidas($params);
            $this->view->totalPesquisas = $totalPesquisas;
            $this->view->resultados = $service->relatorioPercentual($params);
        }
    }

    public function relatorioTabeladoAction()
    {
        $request = $this->_request;
        if ($request->isPost()) {
            $params = $request->getParams();
            $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_ResultadoPesquisa');
            $totalPesquisas = $service->totalRespondidas($params);
            $this->view->totalPesquisas = $totalPesquisas;
            if ($totalPesquisas) {
                $this->view->enuciado = $service->retornaEnunciadoPesquisa($params);
                $this->view->resultados = $service->relatorioTabelado($params);
            }
        }
    }

    public function imprimirRelatorioAction()
    {
        $this->_helper->layout->disableLayout();
        $request = $this->_request;
        if ($request->isGet()) {
            $params = $request->getParam('idpesquisa');
            $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_ResultadoPesquisa');
            $totalPesquisas = $service->totalRespondidas(array('idpesquisa' => $params));
            $this->view->totalPesquisas = $totalPesquisas;
            $this->view->resultados = $service->relatorioPercentual(array('idpesquisa' => $params));
        }
        $relatorioPercentual = $this->view->render('relatorio/imprimir-relatorio.phtml');
        $stylesheet = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/examples/mpdfstyletables.css');
        $css = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/mpdf.css');
        $this->mpdf = new App_Service_MPDF('UTF-8', 'A4', '', '', 20, 20, 20, 20, 20, 20);
        $this->mpdf->SetDisplayMode('fullpage', 'two');
        $this->mpdf->mirrorMargins = 1;
        $this->mpdf->WriteHTML($stylesheet, 1);
        $this->mpdf->WriteHTML($css, 1);
        $this->mpdf->WriteHTML($relatorioPercentual);
        $this->mpdf->Output('document.pdf', 'I');
        exit();
    }

    public function imprimirTabeladoAction()
    {
        $this->_helper->layout->disableLayout();
        $request = $this->_request;
        if ($request->isGet()) {
            $params = $request->getParam('idpesquisa');
            $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_ResultadoPesquisa');
            $totalPesquisas = $service->totalRespondidas(array('idpesquisa' => $params));
            $this->view->totalPesquisas = $totalPesquisas;
            if ($totalPesquisas) {
                $this->view->enuciado = $service->retornaEnunciadoPesquisa(array('idpesquisa' => $params));
                $this->view->resultados = $service->relatorioTabelado(array('idpesquisa' => $params));
            }
        }
        $relatorioPercentual = $this->view->render('relatorio/imprimir-tabelado.phtml');
        $stylesheet = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/examples/mpdfstyletables.css');
        $css = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/mpdf.css');
        $this->mpdf = new App_Service_MPDF('UTF-8', 'A4', '', '', 20, 20, 20, 20, 20, 20);
        $this->mpdf->SetDisplayMode('fullpage', 'two');
        $this->mpdf->mirrorMargins = 1;
        $this->mpdf->WriteHTML($stylesheet, 1);
        $this->mpdf->WriteHTML($css, 1);
        $this->mpdf->WriteHTML($relatorioPercentual);
        $this->mpdf->Output('document.pdf', 'I');
        exit();
    }

}
