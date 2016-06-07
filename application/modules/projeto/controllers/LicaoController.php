<?php

class Projeto_LicaoController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('cadastrar', 'json')
            ->addActionContext('excluir', 'json')
            ->addActionContext('retornalicoesjson', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        $idprojeto = $this->_request->getParam('idprojeto');
        $this->view->idprojeto = $idprojeto;
        $service = new Projeto_Service_Licao();

        $form = $service->getFormPesquisar($this->_request->getParams());
        $this->view->form = $form;
        
    }

    public function retornalicoesjsonAction(){
        $service = new Projeto_Service_Licao();
        $resultado = $service->retornaLicoes($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado->toJqgrid());
    }
    
    public function cadastrarAction()
    {
       $idprojeto = $this->_request->getParam('idprojeto');
       $service = new Projeto_Service_Licao();
       $serviceGerencia = new Projeto_Service_Gerencia();
       $this->view->dadosProjeto = $serviceGerencia->getById(array('idprojeto' => $idprojeto));
       $this->view->idprojeto = $idprojeto;
       $success = false;

       if($this->_request->isPost()){
           $dados = $this->_request->getPost();
           $licao = $service->inserir($dados);
           if($licao){
               $success = true; 
               $msg     = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
           } else {
               $msg = $service->getErrors();
           }
       }

       $form = $service->getForm(array('idprojeto' => $idprojeto));
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
                   $this->_helper->_redirector->gotoSimpleAndExit('cadastrar', 'licao', 'projeto');
               }
               $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
               $this->_helper->_redirector->gotoSimpleAndExit('cadastrar', 'licao', 'projeto');
           }
       }
    }

    public function editarAction()
    {
        $idprojeto = $this->_request->getParam('idprojeto');
        $service = new Projeto_Service_Licao();
        $form = $service->getForm(array('idprojeto' => $idprojeto));
        $this->view->idprojeto = $idprojeto;
        $success = false;

        if($this->_request->isPost()){
            $dados = $this->_request->getPost();
            $evento = $service->update($dados);
            if($evento){
                $success = true;
                $msg     = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        }else{
            $dados = $service->getById($this->_request->getParams());
            $form->populate($dados->formPopulate());
            $this->view->dados = $dados;
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
                    $this->_helper->_redirector->gotoSimpleAndExit('cadastrar', 'licao', 'projeto');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('cadastrar', 'licao', 'projeto');
            }
        }

    }

    public function detalharAction()
    {
        $service = new Projeto_Service_Licao();
        $licao = $service->getById($this->_request->getParams());
        //var_dump($licao); exit;
        $this->view->licao = $licao;
    }

    public function excluirAction()
    {
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $service = new Projeto_Service_Licao();
        $licao = $service->getById($this->_request->getParams());
        $this->view->licao = $licao;
        $success = false;
        if ( $this->_request->isPost() ) {
            $dados = $this->_request->getPost();
            $excluiu = $service->excluir($dados);
            if ( $excluiu ) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
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

    public function imprimirAction(){

        $this->_helper->layout->disableLayout();
        $serviceGerencia =  new Projeto_Service_Gerencia();
        $service         = new Projeto_Service_Licao();
        $dados           = $this->_request->getParams();
        $loop            = false;
        if($dados['print'] == 'all'){
        $loop = true;
        $licao = $service->retornaLicaoPorProjeto($dados['idprojeto']);
        }else{
            $licao = $service->getById($this->_request->getParams());
        }
        $this->view->projeto = $serviceGerencia->retornaArrayProjetoPorId($dados);
        $this->view->licao = $licao;
        $this->view->loop  = $loop;
        $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');
        $cabecalhoProjeto = $this->view->render('/_partials/projeto-cabecalho.phtml');
        $licao = $this->view->render('/_partials/licao-imprimir.phtml');

        define('_MPDF_PATH', '../library/MPDF57/');
        include('../library/MPDF57/mpdf.php');

        $this->mpdf = new mPDF('UTF-8', 'A4', '', '', 15, 15, 15, 25, 10, 15, '');
        //$this->mpdf = new mPDF();
        $this->mpdf->AddPage('', '', '', '', '', 15, 15, 15, 20, 15, 15);
        $this->mpdf->setFooter('{DATE j/m/Y} - Pág. {PAGENO}/{nb}');

        $stylesheet = file_get_contents('../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);

        $css = file_get_contents('../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

        $cssBootstrap = file_get_contents('../public/js/library/bootstrap/css/bootstrap.min.css');
        $this->mpdf->WriteHTML($cssBootstrap, 1);

        $cssBootstrapResp = file_get_contents('../public/js/library/bootstrap/css/bootstrap-responsive.min.css');
        $this->mpdf->WriteHTML($cssBootstrapResp, 1);


        $this->mpdf->WriteHTML($cabecalho);
        $this->mpdf->WriteHTML($cabecalhoProjeto);
        $this->mpdf->WriteHTML($licao);

        $this->mpdf->Output('LicoesAprendidas_projeto_'.$this->_request->getParam('idprojeto').'.pdf', 'I');
    }

}
