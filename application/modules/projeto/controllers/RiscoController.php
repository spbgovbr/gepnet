<?php

class  Projeto_RiscoController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('cadastrar', 'json')
                ->addActionContext('editar', 'json')
                ->addActionContext('excluir', 'json')
                ->addActionContext('detalhar', 'json')
                ->initContext()
        ;
    }
    public function listarAction()
    {
          $service = App_Service_ServiceAbstract::getService('Projeto_Service_Risco');
          $idProjeto = $this->_request->getParam('idprojeto');
          $this->view->formPesquisar = $service->getFormPesquisar();
          $this->view->idprojeto = $idProjeto;
          //mensagem erro action imprimir
          $this->view->msgimpressao =  $this->_request->getParam('msgimpressao');
    }
    
    public function cadastrarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Risco');
        $formRisco = $service->getFormRisco();
        $request = $this->getRequest();
        $success = false;

        if ( $request->isPost() ) {
            $risco = $service->insert($request->getPost());
            if ( $risco ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors() ? : App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            if ( $this->_request->isXmlHttpRequest() ) {
                $this->view->ata = is_object($risco) ? get_object_vars($risco) : NULL;
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

        $formRisco->populate(array('idprojeto' => $request->getParam('idprojeto')));
        $this->view->formRisco = $formRisco;
    }

    public function editarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Risco');
        $form = $service->getFormRisco();
        $request = $this->getRequest();
        $success = false;

        if ( $request->isPost() ) {
            $risco = $service->update($request->getPost());
            if ( $risco ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors() ? : App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            if ( $this->_request->isXmlHttpRequest() ) {
                $this->view->ata = is_object($risco) ? get_object_vars($risco) : NULL;
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
        $riscoResult = $service->getById($this->getRequest()->getParams())->toArray();
        $form->populate($riscoResult);
        $this->view->form = $form;
    }

    public function excluirAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Risco');
        $request = $this->getRequest();
        $success = false;
        
        if ( $request->isPost() ) {
            $risco = $service->excluir($request->getParams());
            if ( $risco ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors() ? : App_Service_ServiceAbstract::ERRO_GENERICO;
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
        $riscoResult = $service->getByIdDetalhar($request->getParams());
        $this->view->risco = $riscoResult;
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Risco');
        $riscos = $service->getByIdDetalhar($this->getRequest()->getParams());
        $this->view->risco = $riscos;
    }

    public function pesquisarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Risco');
        $paginator = $service->retornaRiscoByProjeto($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
        
    }
    
    public function imprimirAction()
    {
        $serviceRisco = App_Service_ServiceAbstract::getService('Projeto_Service_Risco');
        $resultRisco =  $serviceRisco->imprimirPorProjeto($this->_request->getParams());
        $print = $this->_request->getParam('print');
        $this->view->imprimir = $resultRisco;
        
        if ($resultRisco) {

            $serviceGerencia =  new Projeto_Service_Gerencia();
            $this->view->print = $print;
            $this->view->projeto = $serviceGerencia->retornaArrayProjetoPorId($this->_request->getParams());
            
            //renderiza os templates para montar o pdf
            $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');
            $cabecalhoProjeto = $this->view->render('/_partials/projeto-cabecalho.phtml');
            $html = $this->view->render('/_partials/risco_relatorio.phtml');
            $this->_helper->layout->disableLayout();
            if($print == 'one') {
                $tituloRelatorio =  '<div class="pagination-centered"><h4>Relatório do Risco</h4></div>';
                $nomeRelatorio = 'Relatorio_de_Risco';
            } else {
                $tituloRelatorio =  '<div class="pagination-centered"><h4>Relatório de Riscos por Projeto</h4></div>';
                $nomeRelatorio = 'Relatorio_de_Riscos_Projeto';
            }
            $serviceRisco->gerarPdf(array(1=>$cabecalho, 2=>$tituloRelatorio , 3=>$cabecalhoProjeto , 4=>$html), $nomeRelatorio);

        } else {
            return $this->_forward('listar','risco', 'projeto', array('idprojeto'=>$this->_request->getParam('idprojeto'), 'msgimpressao'=>App_Service_ServiceAbstract::ERRO_GENERICO) );                
        }
    }
}
