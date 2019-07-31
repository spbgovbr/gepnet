<?php

class Projeto_AtareuniaoController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('cadastrar', 'json')
                ->addActionContext('editar', 'json')
                ->addActionContext('excluir', 'json')
                ->initContext()
        ;
    }

    public function indexAction()
    {
        
    }

    public function imprimirAction()
    {
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        //$serviceAta = App_Service_ServiceAbstract::getService('Default_Service_Ata');
        $serviceAtaProjeto = App_Service_ServiceAbstract::getService('Projeto_Service_Ata');
        
        $opcao = $this->_request->getParam('print');
        switch ( $opcao) {
            case 'one':
                    $this->view->imprimir = $serviceAtaProjeto->retornaAtaImprimir($this->_request->getParams());
                    $html = $this->view->render('/_partials/ata-reuniao-imprimir-one.phtml');
                    break;
            case 'all':
                    $this->view->imprimir = $serviceAtaProjeto->imprmimirTodasAtas($this->_request->getParams());
                    $html = $this->view->render('/_partials/ata-reuniao-imprimir-all.phtml');
                    break;
            default:
                    $this->view->projeto = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());
                    $html = $this->view->render('/_partials/ata-reuniao-imprimir.phtml');
                    break;
        }
        $this->_helper->layout->disableLayout();
        $serviceImprimir = App_Service_ServiceAbstract::getService('Default_Service_Impressao');
        $serviceImprimir->gerarPdf($html);
    }

    public function listarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Ata');
        $idProjeto = $this->_request->getParam('idprojeto');
        $this->view->formPesquisar = $service->getFormPesquisar();
        $this->view->idprojeto = $idProjeto;
    }

    public function cadastrarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Ata');
        $form = $service->getFormAta();
        $request = $this->getRequest();
        $success = false;

        if ( $request->isPost() ) {
            $ata = $service->insert($request->getPost());
            if ( $ata ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors() ? : App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            if ( $this->_request->isXmlHttpRequest() ) {
                $this->view->ata = is_object($ata) ? get_object_vars($ata) : NULL;
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

        $form->populate(array('idprojeto' => $request->getParam('idprojeto')));
        $this->view->form = $form;
    }

    public function editarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Ata');
        $form = $service->getFormAta();
        $request = $this->getRequest();
        $success = false;

        if ( $request->isPost() ) {
            $ata = $service->update($request->getPost());
            if ( $ata ) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors() ? : App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            if ( $this->_request->isXmlHttpRequest() ) {
                $this->view->ata = is_object($ata) ? get_object_vars($ata) : NULL;
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
        $ata = $service->getById($this->getRequest()->getParams())->toArray();
        $date = new DateTime($ata['datata']);
        $ata['datata'] = $date->format('d/m/Y');

        $form->populate($ata);
        $this->view->form = $form;
    }

    public function excluirAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Ata');
        $request = $this->getRequest();
        $ata = $service->getByIdDetalhar($request->getParams());
        $this->view->ata = $ata;

        if ( $request->isPost() ) {
            $ata = $service->excluir($request->getParams());
            if ( $ata ) {
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
    }

    public function detalharAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Ata');
        $ata = $service->getByIdDetalhar($this->getRequest()->getParams());
        $this->view->ata = $ata;
    }

    public function gridAtaAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Ata');
        $paginator = $service->retornaAtaByProjeto($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

}
