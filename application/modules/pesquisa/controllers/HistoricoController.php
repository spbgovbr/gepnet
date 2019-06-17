<?php

class Pesquisa_HistoricoController extends Zend_Controller_Action
{

    public function listarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_HistoricoPublicacao');
        $this->view->formPesquisar = $service->getFormPesquisar();
    }

    public function pesquisarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_HistoricoPublicacao');
        $paginator = $service->retornaHistoricoPesquisasGrid($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

//    public function relatorioPercentualAction() 
//    {   $request = $this->_request;
//        if($request->isPost()) {
//            $params = $request->getParams();
//            $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_ResultadoPesquisa');
//            $totalPesquisas = $service->totalRespondidas($params);
//            $this->view->totalPesquisas = $totalPesquisas;
//            $this->view->resultados = $service->relatorioPercentual($params);
//        }
//    }
//    
//    public function relatorioTabeladoAction() 
//    {   $request = $this->_request;
//        if($request->isPost()) {
//            $params = $request->getParams();
//            $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_ResultadoPesquisa');
//            $totalPesquisas = $service->totalRespondidas($params);
//            $this->view->totalPesquisas = $totalPesquisas;
//            if ($totalPesquisas) {
//                $this->view->enuciado = $service->retornaEnunciadoPesquisa($params);
//                $this->view->resultados = $service->relatorioTabelado($params);
//            }
//        }
//    }

}
