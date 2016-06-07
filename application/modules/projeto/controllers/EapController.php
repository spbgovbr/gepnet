<?php
class Projeto_EapController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('retorna-projeto', 'json')
            ->addActionContext('cadastrar-grupo', 'json')
            ->addActionContext('cadastrar-entrega', 'json')
            ->addActionContext('editar-entrega', 'json')
            ->addActionContext('excluir-entrega', 'json')
            ->addActionContext('excluir-grupo', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
       
    }

    public function retornaProjetoAction()
    {
        /*$service = new Projeto_Service_AtividadeCronograma();
        $this->view->projeto = $service->retorn($this->_request->getParams());*/
    }
    
    public function cadastrarGrupoAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ( $this->_request->isPost() ) {
            $dados = $this->_request->getPost();
            $grupo = $service->inserirGrupo($dados);
         
            if ( $grupo ) {
                $this->view->item = $grupo;
                $success           = true;
                $msg               = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
                'text'    => $msg,
                'type'    => ($success) ? 'success' : 'error',
                'hide'    => true,
                'closer'  => true,
                'sticker' => false
            );
        } else {
            $this->view->form = $service->getFormGrupo($this->_request->getParams());
        }
    }
    
    public function cadastrarEntregaAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ( $this->_request->isPost() ) {
            $dados = $this->_request->getPost();
            $grupo = $service->inserirEntrega($dados);
            
            if ( $grupo ) {
                $this->view->item = $grupo;
                $success           = true;
                $msg               = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
                'text'    => $msg,
                'type'    => ($success) ? 'success' : 'error',
                'hide'    => true,
                'closer'  => true,
                'sticker' => false
            );
        } else {
            $this->view->form = $service->getFormEntrega($this->_request->getParams());
        }
    }
    
    public function editarEntregaAction()
    {
        $service = new Projeto_Service_Eap();
        $success = false;
        if($this->_request->isPost()){
            $dados = $this->_request->getPost();
            if($service->editarEntrega($dados)){
                $success = true;
                $msg     = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
                    'text'    => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type'    => ($success) ? 'success' : 'error',
                    'hide'    => true,
                    'closer'  => true,
                    'sticker' => false
            );
        } 
    }
    
    public function excluirGrupoAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if($this->_request->isPost()){
            $dados = $this->_request->getPost();
            $idProjeto = $dados['idprojeto'];
            // *********Excluindo as predecessoras por grupo****************//
           // Selecionando as atividades por projeto
            $serviceAtividade = new Projeto_Service_AtividadeCronograma();
            $serviceIdGrupo   = $serviceAtividade->retornaIdEntregaPorGrupo(array('idprojeto' => $idProjeto, 'idgrupo' => $dados['idatividadecronograma']));
            $idgrupoEntrega   = '';
            foreach ($serviceIdGrupo as $serviceIdGrupos) {
                $idgrupoEntrega .= $serviceIdGrupos['idatividadecronograma']. ',';
            }
            $idgrupoEntrega = substr($idgrupoEntrega, 0,  strlen($idgrupoEntrega)- 1);
            $serviceIdEntrega = $serviceAtividade->retornaIdAtividadePorEntrega($idProjeto, $idgrupoEntrega);
            // Criando uma variavel que receberá todos os ids da atividade
                $idatividade = '';
                foreach ($serviceIdEntrega as $serviceIdEntregas) {
                    $idatividade .= $serviceIdEntregas['idatividadecronograma']. ',';
                }
                $idatividade = substr($idatividade, 0, strlen($idatividade)-1);
            // Retornando todas as predecessoras por id ativiadade 
               $Epredecessora         = new Projeto_Model_Mapper_Atividadepredecessora();
               $retornaPredPorIdAtivi = $Epredecessora->retornaTodasPredecessorasPorIdAtividade($idProjeto, $idatividade);
            // Deletando todas as predecessoras do grupo   
               if($retornaPredPorIdAtivi != ''){
               $servicePredecessora  = new Projeto_Service_AtividadePredecessora();
                foreach ($retornaPredPorIdAtivi as $retornaPredPorIdAtividade) {
                    $idPredecessora                    = $retornaPredPorIdAtividade['idatividadepredecessora'];
                    $params                            = array();
                    $params['idatividadepredecessora'] = $idPredecessora;
                    $params['idprojeto']               = $idProjeto;
                    $predecessora                      = $servicePredecessora->excluirPorProjeto($params);
                } 
               } 
            // *****Fim Excluindo as predecessoras por grupo****************//
               $excluirGrupo = $service->excluir($dados);
            if($excluirGrupo){
                $success = true;
                $msg     = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            }else {
                $msg = $service->getErrors();
            }
        } else {
            $grupo = $service->retornaGrupoPorId($this->_request->getParams(), false, true);
            $this->view->grupo = $grupo;
        }
        
        if ($this->_request->isPost()) {
            if($this->_request->isXmlHttpRequest()){
                $this->view->success = $success;
                $this->view->msg = array(
                    'text'    => (is_array($msg)) ? array_shift($msg) : $msg,
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
    
    public function excluirEntregaAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if($this->_request->isPost()){
            $dados = $this->_request->getPost();
             $idProjeto = $dados['idprojeto'];
               $idgrupoEntrega = $dados['idatividadecronograma'];
            // *********Excluindo as predecessoras por grupo****************//
           // Selecionando as atividades por projeto
            $serviceAtividade = new Projeto_Service_AtividadeCronograma();
            $serviceIdEntrega = $serviceAtividade->retornaIdAtividadePorEntrega($idProjeto, $idgrupoEntrega);
            // Criando uma variavel que receberá todos os ids da atividade
                $idatividade = '';
                foreach ($serviceIdEntrega as $serviceIdEntregas) {
                    $idatividade .= $serviceIdEntregas['idatividadecronograma']. ',';
                }
                $idatividade = substr($idatividade, 0, strlen($idatividade)-1);
            // Retornando todas as predecessoras por id ativiadade 
               $Epredecessora         = new Projeto_Model_Mapper_Atividadepredecessora();
               $retornaPredPorIdAtivi = $Epredecessora->retornaTodasPredecessorasPorIdAtividade($idProjeto, $idatividade);
               // Deletando todas as predecessoras do grupo   
               if($retornaPredPorIdAtivi != ''){
               $servicePredecessora  = new Projeto_Service_AtividadePredecessora();
                foreach ($retornaPredPorIdAtivi as $retornaPredPorIdAtividade) {
                    $idPredecessora                    = $retornaPredPorIdAtividade['idatividadepredecessora'];
                    $params                            = array();
                    $params['idatividadepredecessora'] = $idPredecessora;
                    $params['idprojeto']               = $idProjeto;
                    $predecessora                      = $servicePredecessora->excluirPorProjeto($params);
                } 
               } 
            // *****Fim Excluindo as predecessoras por grupo****************//
           // Excluindo a entrega
            $excluirEntrega = $service->excluirEntrega($dados);
            if($excluirEntrega){
                $success = true;
                $msg     = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            }else {
                $msg = $service->getErrors();
            }
        } else {
            $entrega = $service->retornaEntregaPorId($this->_request->getParams(), false, true);
            $this->view->entrega = $entrega;
        }
        if ($this->_request->isPost()) {
            if($this->_request->isXmlHttpRequest()){
                $this->view->success = $success;
                $this->view->msg = array(
                    'text'    => (is_array($msg)) ? array_shift($msg) : $msg,
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
    
    public function visualizarImpressaoAction(){
        $service =  new Projeto_Service_Gerencia();
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $this->view->projeto = $service->retornaArrayProjetoPorId($this->_request->getParams());
    }
    
    public function imprimirPdfAction(){
       
        $service =  new Projeto_Service_Gerencia();
        $this->view->projeto = $service->retornaArrayProjetoPorId($this->_request->getParams());
        
        $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');
        $cabecalhoProjeto = $this->view->render('/_partials/projeto-cabecalho.phtml');
        $eap = $this->view->render('/_partials/eap-imprimir-pdf.phtml');
        $this->_helper->layout->disableLayout();
        
        define('_MPDF_PATH', '../library/MPDF57/');
        include('../library/MPDF57/mpdf.php');
        
        $this->mpdf = new mPDF('UTF-8', 'A4-L', '', '', 15, 15, 15, 25, 10, 15, '');
        //$this->mpdf = new mPDF();
        $this->mpdf->AddPage('L', '', '', '', '', 15, 15, 15, 20, 15, 15);
        $this->mpdf->setFooter('{DATE j/m/Y} - Pág. {PAGENO}/{nb}');
        
        $stylesheet = file_get_contents('../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);
        
        $css = file_get_contents('../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);
        
        $cssBootstrap = file_get_contents('../public/js/library/bootstrap/css/bootstrap.min.css');
        $this->mpdf->WriteHTML($cssBootstrap, 1);
        
        $cssBootstrapResp = file_get_contents('../public/js/library/bootstrap/css/bootstrap-responsive.min.css');
        $this->mpdf->WriteHTML($cssBootstrapResp, 1);
        
        /*$cssCron = file_get_contents('../public/css/app/projeto/css/cronograma/index.css');
        $this->mpdf->WriteHTML($cssCron, 1);*/
        
        
        $this->mpdf->WriteHTML($cabecalho);
        $this->mpdf->WriteHTML($cabecalhoProjeto);
        $this->mpdf->WriteHTML($eap);
        
        $this->mpdf->Output('EAP_Projeto.pdf', 'I');
       
    }

   
}
