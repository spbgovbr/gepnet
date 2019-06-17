<?php

class Pesquisa_PesquisaController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('cadastrar', 'json')
            ->addActionContext('pesquisar', 'json')
            ->addActionContext('publicar', 'json')
            ->initContext();
    }

    public function listarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Questionario');
        $this->view->formPesquisar = $service->getFormPesquisar();
    }

    public function pesquisarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pesquisa');
        $paginator = $service->retornaQuestionarioPesquisaGrid($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function publicarAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $this->getRequest()->getParams();
            $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pesquisa');
            $result = $service->publicarPesquisa($data);
            if ($result) {
                $this->_helper->json->sendJson(array('msg' => App_Service_ServiceAbstract::PUBLICACAO_REALIZADA_SUCESSO));
            }
        }
    }

    public function pesquisaDuplicadaAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_QuestionarioPesquisa');
        $result = $service->isDuplicada($this->_request->getParams());
        $this->_helper->json->sendJson(array('duplicada' => $result));
    }

    public function gerenciarPesquisasAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pesquisa');
        $this->view->formPesquisar = $service->getFormPesquisar();
    }

    public function listarPublicadasAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pesquisa');
        $paginator = $service->retornaPesquisasPublicadasGrid($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function publicarEncerrarAction()
    {
        $request = $this->getRequest();
        $data = $request->getPost();
        if ($request->isPost()) {
            $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Pesquisa');
            $pesquisa = $service->publicaEncerraPesquisa($data);
            if ($pesquisa) {
                $this->_helper->json->sendJson(array('msg' => App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO));
            }
        }
    }

    public function pesquisasRespondidasAction()
    {
        $idpesquisa = array('idpesquisa' => $this->_request->getParam('idpesquisa'));

        $serviceQuestionario = App_Service_ServiceAbstract::getService('Pesquisa_Service_QuestionarioPesquisa');
        $this->view->questionario = $serviceQuestionario->retornaQuestionarioByPesquisa($idpesquisa);

        $serviceResultado = App_Service_ServiceAbstract::getService('Pesquisa_Service_ResultadoPesquisa');
        $this->view->formPesquisar = $serviceResultado->getFormPesquisar($idpesquisa);
    }

    public function listarRespostasPesquisaAction()
    {
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_ResultadoPesquisa');
        $paginator = $service->retornaResultadoPesquisaGrid($this->_request->getParams());
        $this->_helper->json->sendJson($paginator->toJqgrid());
    }

    public function respostaPesquisaAction()
    {
        $params = $this->_request->getParams();
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_ResultadoPesquisa');
        $this->view->resultados = $service->retornaResultadoByPessoa($params);
        $this->view->idresultado = $params['idresultado'];
    }

    public function detalharPesquisaAction()
    {
        $params = $this->_request->getParams();
        $service = App_Service_ServiceAbstract::getService('Pesquisa_Service_Responder');
        $form = $service->getFormPesquisa($params);
        $this->view->form = $form;
    }

}
