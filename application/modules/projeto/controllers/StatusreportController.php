<?php

class Projeto_StatusReportController extends Zend_Controller_Action {

    public function init() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
                ->addActionContext('detalhar', 'json')
                ->initContext();
    }

    public function indexAction() {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $form = $service->getFormPesquisar();
        //Zend_Debug::dump($form);die;
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $projeto = $service->inserir($dados);
            if ($projeto) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        }

        $this->view->form = $form;
        $this->view->codobjetivo = $dados['codobjetivo'];
        $this->view->codacao = $dados['codacao'];
        
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
                //$this->_helper->_redirector->gotoSimpleAndExit('gerencia', 'projeto', 'default');
            }
        }
    }

    public function pesquisarjsonAction() {
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
//        if($this->_request->getParam('statusreport')) {
//            $this->_request->setParam('statusreport', 1);
//        }
        $auth         = Zend_Auth::getInstance();
        $identiti     = $auth->getIdentity();
        $idperfil     = $identiti->perfilAtivo->idperfil;
        $idescritorio = $identiti->perfilAtivo->idescritorio;
        $dados        = $this->_request->getParams(); 
        $paginator = $serviceGerencia->pesquisar($dados,$idperfil,$idescritorio,true);
        $this->_helper->json->sendJson($paginator);
    }

    public function detalharAction() {

        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
        $form = $serviceStatusReport->getFormPesquisar();
        
        $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $listaAcompanhamentos = $serviceStatusReport->retornaAcompanhamentosPorProjeto(array('idprojeto' => $this->_request->getParam('idprojeto')), false);
        $entregasMarcos = $serviceAtividadeCronograma->retornaEntregasEMarcosPorProjeto(array('idprojeto' => $this->_request->getParam('idprojeto'), 'domtipoatividade' => '2,4'),true);
//        $proximoMarco = $serviceAtividadeCronograma->retornaEntregasEMarcosPorProjeto(array('idprojeto' => $this->_request->getParam('idprojeto'), 'domtipoatividade' => '1,2,3,4'));
        $proximoMarco = $serviceAtividadeCronograma->retornaProximoMarco(array('idprojeto' => $this->_request->getParam('idprojeto')));
        
        $this->view->projeto = $projeto;
        $this->view->listaAcompanhamentos = $listaAcompanhamentos;
        $this->view->form = $form;
        $this->view->entregasMarcos = $entregasMarcos;
        $this->view->proximoMarco = $proximoMarco;
        //Zend_Debug::dump($this->view->entregasMarcos); 

        if ($this->_request->getParam('idstatusreport')) {
            $acompanhamento = $serviceStatusReport->retornaAcompanhamentoPorId(array('idstatusreport' => $this->_request->getParam('idstatusreport'), 'idprojeto' => $this->_request->getParam('idprojeto')),false);
            $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $this->_request->getParam('idstatusreport')));
            $this->view->statusReport = $statusReport;
            //retorna o marco relativo ao statusreport e manda para a view
            
        } else {
            $acompanhamento = $statusReport = $serviceStatusReport->retornaUltimoAcompanhamento(array('idprojeto' => $this->_request->getParam('idprojeto')),false);
        }
        
        $atividadeCronograma = $serviceAtividadeCronograma->retornaAtividadePorId(array('idprojeto' => $this->_request->getParam('idprojeto'), 'idatividadecronograma' => $statusReport->idmarco), false);

        $datfimbaseline = new Zend_Date($atividadeCronograma['datfimbaseline'], 'dd/MM/YYYY');
        $datfim = new Zend_Date($atividadeCronograma['datfim'], 'dd/MM/YYYY');
        $diff = $datfimbaseline->sub($datfim)->toValue();
        $dias = floor($diff / 60 / 60 / 24);

        $this->view->diasmarco = $dias;
        
//        print "<PRE>";
//        print_r($proximoMarco); exit;
        
        
        $this->view->acompanhamento = $acompanhamento;
    }
    
    
    public function chartplanejadorealizadojsonAction() {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $resultado = $service->getChartPlanejadoRealizado($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }
    
    public function chartatrasojsonAction() {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $resultado = $service->getChartEvolucaoAtraso($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }
    
    public function chartprazojsonAction() {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $resultado = $service->getChartPrazo($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }
    
    public function chartmarcojsonAction() {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $resultado = $service->getChartMarco($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }
    
    public function imprimirPdfAction(){
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
        $form = $serviceStatusReport->getFormPesquisar();
        
        $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $listaAcompanhamentos = $serviceStatusReport->retornaAcompanhamentosPorProjeto(array('idprojeto' => $this->_request->getParam('idprojeto')), false);
        $entregasMarcos = $serviceAtividadeCronograma->retornaEntregasEMarcosPorProjeto(array('idprojeto' => $this->_request->getParam('idprojeto'), 'domtipoatividade' => '2,4'),true);
//        $proximoMarco = $serviceAtividadeCronograma->retornaEntregasEMarcosPorProjeto(array('idprojeto' => $this->_request->getParam('idprojeto'), 'domtipoatividade' => '1,2,3,4'));
        $proximoMarco = $serviceAtividadeCronograma->retornaProximoMarco(array('idprojeto' => $this->_request->getParam('idprojeto')));
        
        $this->view->projeto = $projeto;
        $this->view->listaAcompanhamentos = $listaAcompanhamentos;
        $this->view->form = $form;
        $this->view->entregasMarcos = $entregasMarcos;
        $this->view->proximoMarco = $proximoMarco;
        //Zend_Debug::dump($this->view->entregasMarcos); 

        if ($this->_request->getParam('idstatusreport')) {
            $acompanhamento = $serviceStatusReport->retornaAcompanhamentoPorId(array('idstatusreport' => $this->_request->getParam('idstatusreport'), 'idprojeto' => $this->_request->getParam('idprojeto')),false);
            $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $this->_request->getParam('idstatusreport')));
            $this->view->statusReport = $statusReport;
            //retorna o marco relativo ao statusreport e manda para a view
            
        } else {
            $acompanhamento = $statusReport = $serviceStatusReport->retornaUltimoAcompanhamento(array('idprojeto' => $this->_request->getParam('idprojeto')),false);
        }
        
        $atividadeCronograma = $serviceAtividadeCronograma->retornaAtividadePorId(array('idprojeto' => $this->_request->getParam('idprojeto'), 'idatividadecronograma' => $statusReport->idmarco), false);

        $datfimbaseline = new Zend_Date($atividadeCronograma['datfimbaseline'], 'dd/MM/YYYY');
        $datfim = new Zend_Date($atividadeCronograma['datfim'], 'dd/MM/YYYY');
        $diff = $datfimbaseline->sub($datfim)->toValue();
        $dias = floor($diff / 60 / 60 / 24);

        $this->view->diasmarco = $dias;
        
//        print "<PRE>";
//        print_r($proximoMarco); exit;
        
        
        $this->view->acompanhamento = $acompanhamento;
        
    }
}


