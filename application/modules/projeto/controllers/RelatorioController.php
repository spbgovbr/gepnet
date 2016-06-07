<?php

class Projeto_RelatorioController extends Zend_Controller_Action {

    public function init() {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
                ->addActionContext('relatoriojson', 'json')
                ->addActionContext('add', 'json')
                ->addActionContext('editar', 'json')
                ->addActionContext('detalhar', 'json')
                ->addActionContext('excluir', 'json')
                ->initContext();
    }

    public function indexAction() {
        $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $form = $serviceStatusReport->getForm();
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $this->view->projeto = $projeto;
        $this->view->form = $form;
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
    }

    public function relatoriojsonAction() {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $serviceRelatorio = App_Service_ServiceAbstract::getService('Projeto_Service_Relatorio');
        $acompanhamentos = $service->retornaAcompanhamentosPorProjeto($this->_request->getParams(), true);
        $resultado = $serviceRelatorio->getFiles($acompanhamentos,$this->_request->getParams());
        $this->_helper->json->sendJson($resultado);
    }

    public function addAction() {
        
        $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceR3g = App_Service_ServiceAbstract::getService('Projeto_Service_R3g');
        $serviceRisco = App_Service_ServiceAbstract::getService('Projeto_Service_Risco');
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            //Zend_Debug::dump($dados);die;
            $projeto   = $serviceStatusReport->inserir($dados);
            // Atualizando o campo domstatusprojeto de acord
            // o com domstatusprojeto do statusreport
            $atualizarProjeto['domstatusprojeto'] = $dados['domstatusprojeto'];
            $modelProjeto = new Projeto_Model_DbTable_Gerencia();
            $where = "idprojeto = ".$dados['idprojeto']; 
            $projetoAtualizar   = $modelProjeto->update($atualizarProjeto, $where);
//            var_dump($projeto);
//            var_dump($serviceStatusReport->getErrors());
//            exit;
            if ($projeto) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $serviceStatusReport->getErrors();
            }
        } else {
            // Verificando se existe atividade desatualizada no cronograma
            $atividadesDesatualizadas = $serviceAtividadeCronograma->verificarAtividadesDesatualizadas($this->_request->getParams());
            $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
            $form = $serviceStatusReport->getForm($this->_request->getParams());
            $this->view->form = $form;
            $this->view->projeto = $projeto;
            if(count($atividadesDesatualizadas) > 0){
                $this->view->atividadesDesatualizadas = $atividadesDesatualizadas;
            } else {
                $statusReport = $serviceGerencia->generateStatusReport(array('idprojeto' => $this->_request->getParam('idprojeto')));
                $statusProjeto = $serviceStatusReport->getStatusProjeto();
                //$form = $serviceStatusReport->getForm(array('idprojeto' => $this->_request->getParam('idprojeto')), $statusReport, $projeto);
                $this->view->idprojeto = $this->_request->getParam('idprojeto');
                $this->view->statusreport = $statusReport;
                $this->view->statusprojeto = $statusProjeto;
//                print "<PRE>";
//                var_dump($projeto->ultimoStatusReport->desmotivoatraso); exit;

                $dias = $serviceStatusReport->retornaDiferencaDias($projeto->datfim, $projeto->ultimoStatusReport->datfimprojetotendencia);
                $this->view->semaforo = $serviceStatusReport->getSemaforo($dias, $projeto->numcriteriofarol);

                $this->view->desatividadesconcluidas = "";
                foreach ($statusReport['desatividadeconcluida'] as $sr) {
                    $this->view->desatividadesconcluidas .= $sr['datinicio'] . " - " . $sr['datfim'] . " - " . $sr['nomatividadecronograma'] . "<BR>";
                }
                $datfimprojetotendencia = $serviceAtividadeCronograma->retornaTendenciaProjeto($this->_request->getParams());
                $this->view->datfimprojetotendencia = $datfimprojetotendencia;

                $desmotivoatraso = $projeto->ultimoStatusReport->desmotivoatraso ? $projeto->ultimoStatusReport->desmotivoatraso : "Não há atraso.";
                $params['desmotivoatraso'] = $desmotivoatraso;

                $descontramedida = $serviceR3g->retornaContramedida($this->_request->getParams());
                $params['descontramedida'] = $descontramedida ? $descontramedida : "Não há contramedidas em andamento.";

                $desirregularidade = $serviceAtividadeCronograma->retornaIrregularidades($this->_request->getParams());;
                $params['desirregularidade'] = $desirregularidade ? $desirregularidade : "Não há irregularidades.";

                $desrisco = $serviceRisco->retornaRiscos($this->_request->getParams());;
                $params['desrisco'] = $desrisco ? $desrisco : "Não há riscos identificados.";


                $this->view->form->populate($params);
//                Zend_Debug::dump($descontramedida); exit;
            }
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
                    $this->_helper->_redirector->gotoSimpleAndExit('projeto', 'relatorio', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
            }
        }
    }
    
    public function editarAction()
    {       
        $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        
//        print "<PRE>";
//        print_r($this->_request->getParams());
//        exit;
        
        //TODO $statusReport getbyid

        $success = false;
        if($this->_request->isPost()){
            $dados = $this->_request->getPost();
            $statusreport = $serviceStatusReport->update($dados);
            if($statusreport){
                $success = true; ###### AUTENTICATION SUCCESS
                $msg     = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $serviceStatusReport->getErrors();
            }
        } else {
            $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
            $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
            $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
    //        $statusReport = $serviceGerencia->generateStatusReport(array('idprojeto' => $this->_request->getParam('idprojeto')));
            $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $this->_request->getParam('idstatusreport')));
            
            $statusProjeto = $serviceStatusReport->getStatusProjeto();
            $form = $serviceStatusReport->getFormEditar($this->_request->getParams());
    //        $form = $serviceStatusReport->getForm();
            
            $marco = $serviceAtividadeCronograma->retornaUltimoMarco(array('idprojeto' => $this->_request->getParam('idprojeto')));
//    Zend_Debug::dump($marco); exit;
            
            $datfim         = $marco->datfim;
            $datfimbaseline = $marco->datfimbaseline;
            
//            Zend_Debug::dump($datfim);
//            Zend_Debug::dump($datfimbaseline); exit;
            
            //$dias = $serviceStatusReport->retornaDiferencaDias($datfim, $datfimbaseline);
            //$this->view->semaforo = $serviceStatusReport->getSemaforo($dias, $projeto->numcriteriofarol);

            $this->view->projeto = $projeto;
            $this->view->statusreport = $statusReport;
            $this->view->statusprojeto = $statusProjeto;
            
            $statusreport = $serviceStatusReport->getById($this->_request->getParams());
            $form->populate($statusreport->formPopulate());

            $anexo = $serviceStatusReport->retornaAnexo($this->_request->getParams());

            $this->view->anexo = $anexo;
            $this->view->gerencia = $statusreport;
            $this->view->form = $form;
        }
        
        
        if ($this->_request->isPost()) {
            if($this->_request->isXmlHttpRequest()){
                $this->view->success = $success;
                $this->view->msg = array(
                    'text'    => $msg,
                    'type'    => ($success) ? 'success' : 'error',
                    'hide'    => true,
                    'closer'  => true,
                    'sticker' => false
                );
            } else {
                if($success){
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }
    
    public function excluirAction()
    {
        $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $this->_request->getParam('idstatusreport')));
        $success = false;
        if($this->_request->isPost()){
            $dados = $this->_request->getPost();
            $documento = $serviceStatusReport->excluir($dados);
            if($documento){
                $success = true; 
                $msg     = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $msg = $serviceStatusReport->getErrors();
            }
        } else {
            $statusProjeto = $serviceStatusReport->getStatusProjeto();
            $form = $serviceStatusReport->getFormExcluir($this->_request->getParams());
    //        $form = $serviceStatusReport->getForm();

            $this->view->projeto = $projeto;
            $this->view->statusreport = $statusReport;
            $this->view->statusprojeto = $statusProjeto;
            
            $statusreport = $serviceStatusReport->getById($this->_request->getParams());
            $form->populate($statusreport->formPopulate());
            $this->view->gerencia = $statusreport;
            $this->view->form = $form;
            $this->view->statusreport = $statusreport;
        }


        if ($this->_request->isPost()) {
            if($this->_request->isXmlHttpRequest()){
                $this->view->success = $success;
                $this->view->msg = array(
                    'text'    => $msg,
                    'type'    => ($success) ? 'success' : 'error',
                    'hide'    => true,
                    'closer'  => true,
                    'sticker' => false
                );
            } else {
                if($success){
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'relatorio', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function detalharAction()
    {
        $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $projeto = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $statusReport = $serviceStatusReport->getById(array('idstatusreport' => $this->_request->getParam('idstatusreport')));
        $success = false;

        $statusProjeto = $serviceStatusReport->getStatusProjeto();
        $form = $serviceStatusReport->getFormExcluir($this->_request->getParams());
        //        $form = $serviceStatusReport->getForm();

        $this->view->projeto = $projeto;
        $this->view->statusreport = $statusReport;
        $this->view->statusprojeto = $statusProjeto;

        $statusreport = $serviceStatusReport->getById($this->_request->getParams());
        $form->populate($statusreport->formPopulate());
        $this->view->gerencia = $statusreport;
        $this->view->form = $form;
        $this->view->statusreport = $statusreport;



        if ($this->_request->isPost()) {
            if($this->_request->isXmlHttpRequest()){
                $this->view->success = $success;
                $this->view->msg = array(
                    'text'    => $msg,
                    'type'    => ($success) ? 'success' : 'error',
                    'hide'    => true,
                    'closer'  => true,
                    'sticker' => false
                );
            } else {
                if($success){
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'relatorio', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

}
