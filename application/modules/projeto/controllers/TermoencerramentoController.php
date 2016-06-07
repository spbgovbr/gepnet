<?php

class Projeto_TermoencerramentoController extends Zend_Controller_Action {

    public function init() {
    }

    public function indexAction() {
        
    }
    
    public function imprimirAction(){
        $dados                      = $this->_request->getParams();
        $this->_helper->layout->disableLayout();
        $serviceGerencia            = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        // Lição aprendida
        $serviceLicao               = new Projeto_Service_Licao();
        $licao                      = $serviceLicao->retornaLicaoPorProjeto($dados['idprojeto']);
        $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
        $this->view->projeto = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());
        $this->view->licao   = $licao;
        $html = $this->view->render('/_partials/termo-encerramento-imprimir.phtml');
        $serviceImprimir     = App_Service_ServiceAbstract::getService('Default_Service_Impressao');
        
        $serviceImprimir->gerarPdf($html);
    }
}