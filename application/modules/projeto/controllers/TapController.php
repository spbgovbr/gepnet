<?php

class Projeto_TapController extends Zend_Controller_Action {

    public function init() {
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
                ->initContext();
    }
    
    public function indexAction() {
        $seviceAcao                 = App_Service_ServiceAbstract::getService('Default_Service_Acao');
        $portfolio                  =  new Planejamento_Service_Portfolio();
        $seviceEscritorio           = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $service                    = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceParteInteressada    = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $objproj                    = $service->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
        $projeto                    = $service->getById($this->_request->getParams());
        $nomAcao                    = $seviceAcao->getById(array('idacao' => $projeto['idacao']));
        $nomEscritorio              = $seviceEscritorio->getById(array('idescritorio' => $projeto['idescritorio']));
        $noPortfolio                = $portfolio->getPortfolioById(array('idportfolio' => $projeto['idportfolio']));
        //echo "<pre>"; var_dump($noPortfolio['noportfolio']); die;
        $matricula                  = $projeto->matricula;
        $parte                      = $serviceParteInteressada->retornaPartes($this->_request->getParams(),true);
        $this->view->projeto        = $projeto;
        $this->view->objprojeto     = $objproj;
        $this->view->dados          = $parte;
        $this->view->acao           = $nomAcao['nomacao'];
        $this->view->nomeEscritorio = $nomEscritorio['nome'];
        $this->view->noPortifolio   = $noPortfolio['noportfolio'];
        $this->view->matricula      = $matricula;
    }
    
    /*
     * @return Projeto_Model_Gerencia
     */

    public function addAction() {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            
            $dados = $this->_request->getPost();
            $projeto = $service->inserir($dados);
             // Inserindo registros na tabela tb_statusreport
            //Retornando o último id
            $ultimoId = $service->retornaUltimoIdProjeto(); 
            // retornando o is do prefil logado
            $auth     = Zend_Auth::getInstance();
            $idntiti  = $auth->getIdentity();
            $idperfil = $idntiti->perfilAtivo->idperfil;
            $statusReport                           = array();
            $statusReport['domstatusprojeto']       = 1;
            $statusReport['datacompanhamento']      = date("Y-m-d");
            $statusReport['datmarcotendencia']      = date("Y-m-d");
            $statusReport['datcadastro']            = date("Y-m-d");
            $statusReport['dataaprovação']          = date("Y-m-d");
            $statusReport['numpercentualprevisto']  = 0.00;
            $statusReport['numpercentualconcluido'] = 0.00;
            $statusReport['idmarco']                = 1;
            $statusReport['idcadastrador']          = $idperfil;
            $statusReport['datfimprojetotendencia'] = date("Y-m-d");
            $statusReport['domcorrisco']            = 1;
            $statusReport['desatividadeconcluida']  = "Projeto sem acompanhamento cadastrado.";
            $statusReport['desatividadeandamento']  = "Projeto sem acompanhamento cadastrado.";
            $statusReport['desmotivoatraso']        = "Projeto sem acompanhamento cadastrado.";
            $statusReport['descontramedida']        = "Projeto sem acompanhamento cadastrado.";
            $statusReport['desirregularidade']      = "Projeto sem acompanhamento cadastrado.";
            $statusReport['desrisco']               = "Projeto sem acompanhamento cadastrado.";
            $statusReport['descaminho']             = "Projeto sem acompanhamento cadastrado.";
            $statusReport['flaaprovado']            = 2;
            $statusReport['idprojeto']              = $ultimoId[0]['idprojeto'];
            $serviceStatusReport                    = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
            $StatusReportinserir                    = $serviceStatusReport->inserirStatusProjeto($statusReport);
            // Fim Inserindo regostros na tabela tb_statusreport///////////////
            //@todo inserir pessoas interessadas
            if ($projeto) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        }

        $this->view->form = $form;

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
                    $this->_helper->_redirector->gotoSimpleAndExit('projeto', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                //$this->_helper->_redirector->gotoSimpleAndExit('gerencia', 'projeto', 'default');
            }
        }
    }

    public function informacoesiniciaisAction() {
        $service           = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $projeto           = $service->getById($this->_request->getParams());
        $seviceEscritorio  = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $nomEscritorio     = $seviceEscritorio->getById(array('idescritorio' => $projeto['idescritorio']));
        $form        = $service->getFormEditar();
        $auth        = Zend_Auth::getInstance();
        $identiti    = $auth->getIdentity();
        $prefilAtivo = $identiti->perfilAtivo->idperfil;
        $this->view->nomeEscritorio = $nomEscritorio['nome'];
        $perfil      = (int) 1;
        $this->view->identiti = $prefilAtivo;
        $this->view->perfil   = $perfil;
        //Zend_Debug::dump($form);exit;
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            //Zend_Debug::dump($dados);exit;
            $projeto = $service->update($dados);
            if ($projeto) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
                //Zend_Debug::dump($msg);
            }
        } else {
            $projeto = $service->getById($this->_request->getParams());
//            Zend_Debug::dump($projeto); exit;
            $form->populate($projeto->formPopulate());
            $this->view->gerencia = $projeto;
            $this->view->form = $form;
        }

        if ($this->_request->isPost()) {

            if ($this->_request->isXmlHttpRequest()) {
                $response = new stdClass();
                $response->idprojeto = $projeto->idprojeto;
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

    public function informacoestecnicasAction() {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getFormInformacoesTecnicas();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $gerencia = $service->update($dados);
            if ($gerencia) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
                //Zend_Debug::dump($msg);
            }
        } else {
            $gerencia = $service->getById($this->_request->getParams());
//            Zend_Debug::dump($gerencia); exit;
//            print "<PRE>";
//            print_r($gerencia);
//            exit;
            $form->populate($gerencia->formPopulate());
            $this->view->gerencia = $gerencia;
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

    public function resumodoprojetoAction() {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getFormResumoDoProjeto();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $gerencia = $service->update($dados);
            if ($gerencia) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
                //Zend_Debug::dump($msg);
            }
        } else {
            $gerencia = $service->getById($this->_request->getParams());
//            Zend_Debug::dump($gerencia);
            $form->populate($gerencia->formPopulate());
            $this->view->gerencia = $gerencia;
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

    public function partesinteressadasAction() {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $form = $service->getForm();       
        $formExterno = $service->getFormExterno();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if ($this->_request->getParam('idparteinteressada')) {
                $parte = $service->retornaPorId(array('idparteinteressada' => $this->_request->getParam('idparteinteressada')),true);
                
//                $servicePessoa = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
//                $idpessoa = array('idpessoa' => $this->_request->getParam('idparteinteressada'));
//                $parte = $servicePessoa->retornaPorId($idpessoa);
//                $dados['nomparteinteressada'] = $parte->nompessoa;
                //$dados['nomfuncao'] = $parte->domcargo;
//                $dados['destelefone'] = $parte->numfone;
//                $dados['desemail'] = $parte->desemail;
            }
            $parte = $service->insertInterno($dados);
            if ($parte) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
                //Zend_Debug::dump($msg);
            }
        } else {
            $parte = $service->retornaPartes($this->_request->getParams(),true);
//            $parte = $service->getByProjeto($this->_request->getParams());
//            $form->populate($parte->formPopulate());
            $this->view->dados = $parte;
            $this->view->idprojeto = $this->_request->getParam('idprojeto');
            $this->view->form = $form;
            $this->view->formExterno = $formExterno;
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->parte = is_object($parte) ? get_object_vars($parte) : NULL;
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
    
    public function partesinteressadasexternoAction() {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $formExterno = $service->getFormExterno();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();            
            $parte = $service->insertExterno($dados);
            if ($parte) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->parte = is_object($parte) ? get_object_vars($parte) : NULL;
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

    public function excluirparteAction() {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
        $success = false;
        //$idparteinteressada = $this->_request->getParams('id');
        $parte = $service->excluir($this->_request->getParams());
        //Zend_Debug::dump($parte);exit;
        if ($parte) {
            $success = true; ###### AUTENTICATION SUCCESS
            $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
        } else {
            $msg = $service->getErrors();
            //Zend_Debug::dump($msg);
        }

        if ($this->_request->isXmlHttpRequest()) {
            if ($parte) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                $msg = $service->getErrors();
//                Zend_Debug::dump($msg);
            }
        }
    }
    
    public function imprimirAction(){
        $this->_helper->layout->disableLayout();
        $service        =  new Projeto_Service_Gerencia();
        $portfolio      =  new Planejamento_Service_Portfolio();
        $portfolios     =  $service->retornaProjetoPorId($this->_request->getParams());
        $noPortfolio    = $portfolio->getPortfolioById(array('idportfolio' => $portfolios['idportfolio']));
        $protifolioNome = $noPortfolio->noportfolio;
        $processo       = $service->retornaProjetoPorId($this->_request->getParams());
        $projeto        = $service->getById($this->_request->getParams());
        $acao           = $this->_request->getParam('acao');
        $nomdemandante  = $this->_request->getParam('nomdemandante');
        $this->view->nomdemandante = $nomdemandante;
        $this->view->acao          = $acao;
        $this->view->processo      = $processo;
        // flag aprovado S ou N será alterada para sim ou nao
        if($processo['flaaprovado'] == 'S'){
            $aprovadoSimNao = 'Sim';
        }if($processo['flaaprovado'] == 'N'){
            $aprovadoSimNao = 'Não';
        }
        if($processo['flacopa'] == 'S'){
        $grandesEventos = 'Sim';
        }else{
                $grandesEventos = 'Não';
        }
        if($processo['flapublicado'] == 'S'){
              $telaMestra = 'Sim';
      }
      if($processo['flapublicado'] == 'N'){
                $telaMestra = 'Não';
        }
        $this->view->aprovado       = $aprovadoSimNao;
        $this->view->telaMestra     = $telaMestra;
        $this->view->grandesEventos = $grandesEventos;
        $this->view->noPortfolio    = $protifolioNome;
        $this->view->nomdemandante  = $projeto['nomdemandante'];
        $this->view->matricula      = $projeto['matricula'];
         
        
        
        $html = $this->view->render('/_partials/tap-imprimir.phtml');
//        $html = $this->view->render('/tap/imprimirtap.phtml');
        $serviceImprimir = new Default_Service_Impressao();
        
        $serviceImprimir->gerarPdf($html);
    }

    public function acaoAction(){
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $dados = $this->_request->getParams();
        $service = App_Service_ServiceAbstract::getService('Planejamento_Service_Acao');
        $resultado = $service->getByObjetivo($dados);

//        print_r($result);
        $this->_helper->json->sendJson($resultado);
    }
}