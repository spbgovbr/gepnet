<?php

class Projeto_TermoaceiteController extends Zend_Controller_Action {

    public function init() {
        
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('add', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('retornaaceitesjson', 'json')
            ->addActionContext('buscarmarcos', 'json')
            ->initContext();
        
    }

    public function indexAction() {
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
    }
    
    public function retornaaceitesjsonAction(){
        $service = new Projeto_Service_Aceite();
        $resultado = $service->retornaAceites($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado->toJqgrid());
    }
    
    public function addAction() {

        $idprojeto = $this->_request->getParam('idprojeto');
        $this->view->idprojeto = $idprojeto;
        $service = new Projeto_Service_Aceite();
        $form = $service->getForm(array('idprojeto' => $idprojeto));
        $success = false;
        if($this->_request->isPost()){
            $dados = $this->_request->getPost();
            $idmarco = $dados['idmarco'];
            unset($dados['idmarco']);
            $aceite = $service->inserir($dados);
            if($aceite->idaceite) {
                $aceite->idmarco = $idmarco;
                $serviceAceiteAtv = new Projeto_Service_Aceiteatividadecronograma();
                $resultado = $serviceAceiteAtv->inserir($aceite);
                if($resultado){
                    $success = true;
                    $msg     = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
                } else {
                    $msg = $service->getErrors();
                }
            } else {
                $msg = $service->getErrors();
            }
        }
       $this->view->form = $form;
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
                   $this->_helper->_redirector->gotoSimpleAndExit('add', 'termoaceite', 'projeto');
               }
               $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
               $this->_helper->_redirector->gotoSimpleAndExit('add', 'termoaceite', 'projeto');
           }
       }
    }
    
    public function editarAction() {
        
        $idprojeto = $this->_request->getParam('idprojeto');
        $identrega = $this->_request->getParam('identrega');
        $this->view->idprojeto = $idprojeto;
        $service = new Projeto_Service_Aceite();
        $form = $service->getForm(array('idprojeto' => $idprojeto));
        $serviceAceiteAtivCronograma = new Projeto_Service_Aceiteatividadecronograma();
        $form = $serviceAceiteAtivCronograma->getForm(array('idprojeto' => $idprojeto, 'identrega' => $identrega));
        $success = false;
        if($this->_request->isPost()){
            $dados = $this->_request->getPost();
            $idmarco = $dados['idmarco'];
            unset($dados['idmarco']);
            $aceite = $service->editar($dados);
            if($aceite) {
                array_push($dados,$idmarco);
                $aceiteAtividadeCronograma = $serviceAceiteAtivCronograma->editar($dados);
                if ($aceiteAtividadeCronograma) {
                    $success = true;
                    $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
                } else {
                    $msg = $service->getErrors();
                }
            }else{
                $msg = $service->getErrors();
            }
        } else {
            $aceite = $service->getById($this->_request->getParams());
            $aceiteAtividadeCronograma = $serviceAceiteAtivCronograma->getById($this->_request->getParams());
            $aceite->flaaceite = $aceiteAtividadeCronograma->aceito;
            $aceite->idmarco = $aceiteAtividadeCronograma->idmarco;
            $this->view->idaceiteativcronograma = $aceiteAtividadeCronograma->idaceiteativcronograma;
            $this->view->aceite = $aceite;
            $form->populate($aceite->formPopulate());
        }
        
        $this->view->form = $form;
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
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'termoaceite', 'projeto', array('idprojeto' => $idprojeto));
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }
    
    public function detalharAction() {
        $service = new Projeto_Service_Aceite();
        $serviceAceiteAtividadeCronograma = new Projeto_Service_Aceiteatividadecronograma();
        $aceite = $service->getById($this->_request->getParams());
        $aceiteAtividadeCronograma = $serviceAceiteAtividadeCronograma->getById($this->_request->getParams());
        $this->view->nomarco = $aceiteAtividadeCronograma->nomarco;
        $this->view->aceite = $service->getById($aceite);
    }
    
    public function excluirAction() {
        
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $service = new Projeto_Service_Aceite();
        $aceite = $service->getById($this->_request->getParams());
        $serviceAceiteAtividadeCronograma = new Projeto_Service_Aceiteatividadecronograma();
        $aceiteAtividadeCronograma = $serviceAceiteAtividadeCronograma->getById($this->_request->getParams());
        $aceite->idmarco = $aceiteAtividadeCronograma->idmarco;
        $this->view->idaceiteativcronograma = $aceiteAtividadeCronograma->idaceiteativcronograma;
        $this->view->nomarco = $aceiteAtividadeCronograma->nomarco;
        $this->view->aceite = $aceite;
        $success = false;
        if ( $this->_request->isPost() ) {
            $dados = $this->_request->getPost();
            $exluiuAceiteAtvCron = $serviceAceiteAtividadeCronograma->excluir($dados);
            if ( $exluiuAceiteAtvCron ) {
                $excluiuAceite = $service->excluir($dados);
                if ( $excluiuAceite ) {
                    $success = true;
                    $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
                } else {
                    $msg = $service->getErrors();
                }
            } else {
                $msg = $service->getErrors();
            }
            if ( $this->_request->isXmlHttpRequest() ) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            }
        }
    }

    public function buscarEntregaAction(){

        $service = new Projeto_Service_AtividadeCronograma();
        $resultado = $service->retornaEntregaPorId($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);

    }

    public function buscarMarcosAction(){
        $service = new Projeto_Service_AtividadeCronograma();
        $resultado = $service->fetchPairsMarcosPorEntrega($this->_request->getParams());
        //Zend_Debug::dump($resultado);exit;
        //$this->_helper->json($resultado, array('enableJsonExprFinder' => true, 'keepLayouts'=> true));
        $this->_helper->json->sendJson($resultado);
    }
    
    public function imprimirAction(){
        $this->_helper->layout->disableLayout();
        $serviceGerencia            = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceAceite              = new Projeto_Service_Aceite();
        $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
        
        $this->view->projeto                = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());

        $entrega = $serviceAceite->getById($this->_request->getParams());

        $params = array(
            'idprojeto'               =>    $this->_request->getParam('idprojeto'),
            'idatividadecronograma'   =>    $entrega['identrega'],
        );
        $dados = array('idprojeto' => $entrega['idprojeto'], 'identrega' => $entrega['identrega']);

        $marco = $serviceAtividadeCronograma->fetchPairsMarcosPorEntrega($dados);

        $this->view->atividadecronograma = $serviceAtividadeCronograma->retornaEntregaPorId($params);
        //Zend_Debug::dump($entrega['desparecerfinal']);exit;

        $this->view->desparecerfinal = $entrega['desparecerfinal'];



        $this->view->nomarco = $marco['nomarco'];

        $html = $this->view->render('/_partials/termo-aceite-imprimir.phtml');
        
        $serviceImprimir = App_Service_ServiceAbstract::getService('Default_Service_Impressao');
        
        $serviceImprimir->gerarPdf($html);
    }
    
    public function imprimirTodosAction(){
        $this->_helper->layout->disableLayout();
        $service = new Projeto_Service_Aceite();
        $serviceGerencia            = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        
        $this->view->projeto = $serviceGerencia->retornaProjetoPorId($this->_request->getParams()); 
        $this->view->aceite = $service->retornaAceites($this->_request->getParams(), false);
        //echo '<pre>'; var_dump($aceites); exit;
        
        $html = $this->view->render('/_partials/termo-aceite-imprimir-todos.phtml');
        
        $serviceImprimir = App_Service_ServiceAbstract::getService('Default_Service_Impressao');
        
        $serviceImprimir->gerarPdf($html);
        
        
    }
}