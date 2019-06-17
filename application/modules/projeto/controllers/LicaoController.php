<?php

class Projeto_LicaoController extends Zend_Controller_Action
{
    /**
     * @var $mpdf App_Service_MPDF
     */
    private $mpdf;

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
        $idprojeto = $this->_request->getParam('idprojeto');
        $this->view->idprojeto = $idprojeto;
        $service = new Projeto_Service_Licao();

        $form = $service->getFormPesquisar($this->_request->getParams());
        $this->view->form = $form;

    }

    public function retornalicoesjsonAction()
    {
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
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();

            if ($dados['idassociada'] == 2) {
                $form = $service->getForm(array('idprojeto' => $idprojeto));
                $form->getElement('identrega')->setAttribs(array('data-rule-required' => false))
                    ->setRequired(false);
                unset($dados['identrega']);
            }

            $licao = $service->inserir($dados);
            $msgError = "";
            if ($licao) {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                $msgError = $service->getErrors();
            }
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
                $this->view->msgError = $msgError;
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('projeto', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                //$this->_helper->_redirector->gotoSimpleAndExit('gerencia', 'projeto', 'default');
            }
        } else {
            $form = $service->getForm(array('idprojeto' => $idprojeto));
            $this->view->form = $form;
        }
    }

    public function editarAction()
    {
        $idprojeto = $this->_request->getParam('idprojeto');
        $service = new Projeto_Service_Licao();
        $form = $service->getForm(array('idprojeto' => $idprojeto));
        $this->view->idprojeto = $idprojeto;

        $idlicao = $this->_request->getParam('idlicao');
        $this->view->idassociada = $idlicao;

        $tipoassociada = $service->retornaTipoassociada(
            array(
                'idprojeto' => $idprojeto,
                'idlicao' => $idlicao
            )
        );
        $this->view->tipoassociada = $tipoassociada;

        $success = false;
        $msgError = "";
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if ($dados['idassociada'] == 2) {
                $form->getElement('identrega')->setAttribs(array('data-rule-required' => false))
                    ->setRequired(false);
            }

            $evento = $service->update($dados);
            if ($evento) {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                $msgError = $service->getErrors();
            }
        } else {
            $dados = $service->getById($this->_request->getParams());
            $form->populate($dados->formPopulate());
            $this->view->dados = $dados;
        }
        $this->view->form = $form;
        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msgError = $msgError;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {

                if ($success) {
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
        $this->view->licao = $licao;
    }

    public function excluirAction()
    {
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $service = new Projeto_Service_Licao();
        $licao = $service->getById($this->_request->getParams());
        $this->view->licao = $licao;
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $idProjeto = $dados["idprojeto"];
            $excluiu = $service->excluir($dados);
            if ($excluiu) {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'E'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $idProjeto;
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            if ($this->_request->isXmlHttpRequest()) {
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

    public function imprimirAction()
    {

        $this->_helper->layout->disableLayout();
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $serviceGerencia = new Projeto_Service_Gerencia();
        $service = new Projeto_Service_Licao();
        $dados = $this->_request->getParams();
        $loop = false;
        if ($dados['print'] == 'all') {
            $loop = true;
            $licao = $service->retornaLicaoPorProjeto($dados['idprojeto']);
        } else {
            $licao = $service->getById($this->_request->getParams());
        }
        $arrayCronograma = $serviceCronograma->retornaCronogramaByArray($this->_request->getParams());
        $this->view->projeto = $arrayCronograma['projeto'];
//            $serviceGerencia->retornaArrayProjetoPorId($dados);
        $this->view->licao = $licao;
        $this->view->loop = $loop;
        $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');
        $cabecalhoProjeto = $this->view->render('/_partials/projeto-cabecalho.phtml');
        $licao = $this->view->render('/_partials/licao-imprimir.phtml');

        $this->mpdf = new App_Service_MPDF('UTF-8', 'A4', '', '', 15, 15, 15, 25, 10, 15, '');
        //$this->mpdf = new mPDF();
        $this->mpdf->AddPage('', '', '', '', '', 15, 15, 15, 20, 15, 15);
        $this->mpdf->setFooter('{DATE j/m/Y} - Pág. {PAGENO}/{nb}');

        $stylesheet = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);

        $css = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

        $cssBootstrap = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap.min.css');
        $this->mpdf->WriteHTML($cssBootstrap, 1);

        $cssBootstrapResp = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap-responsive.min.css');
        $this->mpdf->WriteHTML($cssBootstrapResp, 1);


        $this->mpdf->WriteHTML($cabecalho);
        $this->mpdf->WriteHTML($cabecalhoProjeto);
        $this->mpdf->WriteHTML($licao);

        $this->mpdf->Output('LicoesAprendidas_projeto_' . $this->_request->getParam('idprojeto') . '.pdf', 'I');
    }

}
