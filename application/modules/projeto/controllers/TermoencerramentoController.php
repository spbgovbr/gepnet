<?php

class Projeto_TermoencerramentoController extends Zend_Controller_Action
{

    public function init()
    {

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('autenticarassinatura', 'json')
            ->addActionContext('retornaassinaturas', 'json')
            ->initContext();

        $servicePerfilPessoa = new Default_Service_Perfilpessoa();
        $dadosEntrada = array(
            "idprojeto" => $this->_request->getParam('idprojeto'),
            "controller" => strtolower($this->_request->getControllerName()),
            "action" => strtolower($this->_request->getActionName()),
        );
        if (!$servicePerfilPessoa->isValidaControllerAction($dadosEntrada)) {
            $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => 'Acesso negado...'));
            $this->_helper->_redirector->gotoSimpleAndExit('forbidden', 'error', 'projeto');
        }
    }

    public function indexAction()
    {

    }

    private function getUrl()
    {
        $baseUrl = new Zend_View_Helper_ServerUrl();
        return $baseUrl->serverUrl() . Zend_View_Helper_Url::url(array(
                'module' => 'default',
                'controller' => 'autenticarcodigo',
                'action' => 'index'
            ));
    }

    public function imprimirAction()
    {
        $dados = $this->_request->getParams();
        $serviceAssinatura = new Projeto_Service_Assinadocumento();
        $this->_helper->layout->disableLayout();
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        // Lição aprendida
        $serviceLicao = new Projeto_Service_Licao();
        $licao = $serviceLicao->retornaLicaoPorProjeto($dados['idprojeto']);
//        $serviceAtividadeCronograma = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
        $this->view->projeto = $serviceGerencia->retornaProjetoPorId($this->_request->getParams());
        $assinaturas = $serviceAssinatura->retornaAssinaturaPorTipoEProjeto($this->_request->getParams());
        // var_dump($assinaturas);die;
        $this->view->assinaturas = $assinaturas;
        $this->view->licao = $licao;
        $this->view->url = $this->getUrl();
        $html = $this->view->render('/_partials/termo-encerramento-imprimir.phtml');
        $serviceImprimir = App_Service_ServiceAbstract::getService('Default_Service_Impressao');

        $serviceImprimir->gerarPdf($html);
    }


    public function imprimirWordAction()
    {
        $dados = $this->_request->getParams();
        $this->_helper->layout->disableLayout();
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        // Lição aprendida
        $serviceLicao = new Projeto_Service_Licao();
        $licao = $serviceLicao->retornaLicaoPorProjeto($dados['idprojeto']);

        $projeto = $serviceGerencia->getById($this->_request->getParams());
        $this->view->projeto = $projeto;
        $this->view->licao = $licao;

        header("Content-type: application/vnd.ms-word");
        header("Content-Type: application/force-download; charset=UTF-8");
        header("Cache-Control: no-store, no-cache");
        header("Content-disposition: inline; filename=tepProjeto" . $dados['idprojeto'] . ".doc");

    }

    public function autenticarassinaturaAction()
    {
        $serviceAssinatura = new Projeto_Service_Assinadocumento();
        $formAssinatura = $serviceAssinatura->getFormTap();
        if ($this->_request->isPost()) {
            $success = false;
            $dados = $this->_request->getParams();
            $dados['numcpf'] = trim($dados['numcpf']);
            $dados['numcpf'] = addslashes($dados['numcpf']);
            $dados['senha'] = trim($dados['senha']);
            $dados['senha'] = addslashes($dados['senha']);
            $dados['senha'] = $dados['senha'];

            $arrayPessoa = $serviceAssinatura->verificarTipoPessoa($dados);
            $msg = null;

            if (is_array($arrayPessoa) && (count($arrayPessoa) > 0)) {

                $arrayPessoa['token'] = $dados['senha'];
                $arrayPessoa['idprojeto'] = $dados['idprojeto'];
                $arrayPessoa['tipodoc'] = array(0 => 4);
                $retorno = $serviceAssinatura->autenticar($arrayPessoa);
                if ($retorno) {
                    $retorno = $serviceAssinatura->assinarDocumento($arrayPessoa);
                    if ($retorno) {
                        $success = true; ###### AUTENTICATION SUCCESS
                        $msg = App_Service_ServiceAbstract::VALID_SUCCESS_USER;
                    } else {
                        $success = false; ###### AUTENTICATION FALURE
                        $msg = App_Service_ServiceAbstract::INVALID_SUCCESS;
                    }
                } else {
                    $success = false; ###### AUTENTICATION FALURE
                    $msg = App_Service_ServiceAbstract::VALID_DENY_USER;
                }
            } else {
                $success = false; ###### AUTENTICATION FALURE
                $msg = App_Service_ServiceAbstract::NENHUM_USUARIO_ENCONTRADO;
            }
            $response = new stdClass();
            $response->success = $success;
            $response->msg = array(
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
            $this->_helper->json->sendJson($response);
        } else {
            $this->view->form = $formAssinatura;
            $this->view->idprojeto = $this->_request->getParam('idprojeto');
        }
    }

    public function retornaassinaturasAction()
    {
        $service = new Projeto_Service_Assinadocumento();
        $assintauras = $service->retornaAssinaturaPorProjeto($this->_getAllParams());
        $this->_helper->json->sendJson($assintauras);
    }

}