<?php

class Planejamento_PortfolioController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('cadastrar', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        $service = new Planejamento_Service_Portfolio();
        $this->view->form = $service->getFormPortfolioEstrategico();
    }

    public function portfolioestrategicoAction()
    {
        $service = new Planejamento_Service_Portfolio();
        $serviceEscritorio = new Default_Service_Escritorio();
        if ($this->_request->isPost()) {
            $params = $this->_request->getPost();
            if (($params['nomprojeto'] != ''
                || $params['idescritorio'] != ''
                || isset($params['idprograma']) && $params['idprograma'] != ''
                || $params['idobjetivo'] != ''
                || $params['idacao'] != ''
                || $params['idnatureza'] != '')) {

                //$this->view->portfolio = $pesquisa;
                $buscarPorti = $service->getBuscaPortfolioEstrategico($params);
                $this->view->portfolio = $buscarPorti;
                $escritorio = $serviceEscritorio->getById(array('idescritorio' => $params['idescritorio']));
                //$this->view->form = $service->getFormPortfolioEstrategico(array('idescritorio' => $idescritorio));
            }
            $idprograma = $service->pesquisarIdPrograma($params);
            $this->view->escritorio = $escritorio;
            $this->view->idprograma = $idprograma;
        }
        //$this->view->form = $service->getFormPortfolioEstrategico(array('idescritorio' => $idescritorio));
        //$this->view->escritorio = $escritorio;
        $params = $this->_request->getParams();
        //$serviceFrom = App_Service_ServiceAbstract::getService('Planejamento_Service_Portfolio');
        if (!isset($params['idescritorio'])) {
            $serviceLogin = new Default_Service_Login();
            $perfilAtivo = $serviceLogin->retornaPerfilAtivo();
            $idescritorio = $perfilAtivo->idescritorio;
        } else {
            $idescritorio = $params['idescritorio'];

        }
//        $grafico = $service->getPortfolioEstrategico(array('idescritorio' => $idescritorio));
//        Zend_Debug::dump($grafico);die;
        $this->view->portfolio = $service->getPortfolioEstrategico(array('idescritorio' => $idescritorio));
        $escritorio = $serviceEscritorio->getById(array('idescritorio' => $idescritorio));
        //$this->view->form = $service->getFormPortfolioEstrategico(array('idescritorio' => $idescritorio));
        $this->view->escritorio = $escritorio;


        $formPesquisar = $service->getFormPesquisar($params);
        //Zend_Debug::dump($formPesquisar); die;
        $this->view->formPesquisar = $formPesquisar;
        $selectEscritorio = $serviceEscritorio->selecionarTodoEscritorio();
        $this->view->selectEscritorio = $selectEscritorio;
    }

    public function cadastrarAction()
    {
        $service = new Planejamento_Service_Portfolio();
        $form = $service->getForm();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $portfolio = $service->inserir($dados);
            if ($portfolio) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        }
        $this->view->form = $form;
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
                    $this->_helper->_redirector->gotoSimpleAndExit('cadastrar', 'portfolio', 'planejamento');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('cadastrar', 'portfolio', 'planejamento');
            }
        }
    }

    public function editarAction()
    {
        $service = new Planejamento_Service_Portfolio();
        $form = $service->getFormEditar();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $portfolio = $service->editar($dados);
            if ($portfolio) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $portfolio = $service->getPortfolioById($this->_request->getParams());
            $form->populate($portfolio->formPopulate());
        }

        $this->view->form = $form;
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
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'portfolio', 'planejamento');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function detalharAction()
    {
        $service = new Planejamento_Service_Portfolio();
        $this->view->portfolio = $service->getByIdDetalhar($this->_request->getParams());
    }

    public function pesquisarportfoliojsonAction()
    {
        $service = new Planejamento_Service_Portfolio();
        $resultado = $service->pesquisarPortfolio($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado->toJqgrid());
    }

    public function pesquisarprojetoAction()
    {
        $params = $this->_request->getParams();
        $service = new Planejamento_Service_Portfolio();
        $this->view->form = $service->getFormPesquisar($params);
    }

    public function chartorcamentarioprojetosprogramajsonAction()
    {
        $param = $this->_request->getParams();
        $service = new Planejamento_Service_Portfolio();
        $resultado = $service->getTotalOrcamentarioProjetosPrograma($param);
        $this->_helper->json->sendJson($resultado);
    }

    public function chartprojetosprogramajsonAction()
    {
        $param = $this->_request->getParams();
        $service = new Planejamento_Service_Portfolio();
        $resultado = $service->getTotalOrcamentarioProjetosPrograma($param);
        $this->_helper->json->sendJson($resultado);
    }


    public function chartprojetosnaturezajsonAction()
    {
        $param = $this->_request->getParams();
        $service = new Planejamento_Service_Portfolio();
        $resultado = $service->getTotalProjetosPorNatureza($param);
        $this->_helper->json->sendJson($resultado);
    }

    public function pesquisarprojetojsonAction()
    {
        $service = new Planejamento_Service_Portfolio();
        $resultado = $service->pesquisarProjeto($this->_request->getParams(), true);
        $this->_helper->json->sendJson($resultado);
    }
}
