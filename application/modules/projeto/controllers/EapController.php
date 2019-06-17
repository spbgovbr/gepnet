<?php

class Projeto_EapController extends Zend_Controller_Action
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
            ->addActionContext('retorna-projeto', 'json')
            ->addActionContext('cadastrar-grupo', 'json')
            ->addActionContext('cadastrar-entrega', 'json')
            ->addActionContext('editar-entrega', 'json')
            ->addActionContext('excluir-entrega', 'json')
            ->addActionContext('excluir-grupo', 'json')
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
        $service = new Projeto_Service_Eap();
        $eap = $service->montaEAP($this->_request->getParams());
        $this->view->idprojeto = $eap['projeto']['idprojeto'];
        $this->view->projeto = $eap['projeto'];

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

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $grupo = $service->inserirGrupo($dados);

            if ($grupo) {
                $this->view->item = $grupo;
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            $this->view->idprojeto = $dados['idprojeto'];
            $this->view->msg = array(
                'idprojeto' => $this->_request->getParam('idprojeto'),
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        } else {
            $this->view->form = $service->getFormGrupo($this->_request->getParams());
            $this->view->idprojeto = $this->_request->getParam('idprojeto');
        }
    }

    public function cadastrarEntregaAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $grupo = $service->inserirEntrega($dados);

            if ($grupo) {
                $this->view->item = $grupo;
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
                'idprojeto' => $this->_request->getParam('idprojeto'),
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
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
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if ($service->editarEntrega($dados)) {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
                'idprojeto' => $this->_request->getParam('idprojeto'),
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        }
    }

    public function excluirGrupoAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $excluirGrupo = $service->excluir($dados);

            if ($excluirGrupo) {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'E'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
//                $dados['idprojeto'] = $dados['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $dados = $this->_request->getParams();
            $servicePredecessora = new Projeto_Service_AtividadeCronoPredecessora();
            $retornaSucessoras = $servicePredecessora->retornaAtividadeCountPredecGrupo(
                array('idgrupo' => $dados['idatividadecronograma'], 'idprojeto' => $dados['idprojeto'])
            );
            $sucessora = "0";
            // Se atividade tiver predecessora
            if ($retornaSucessoras) {
                $sucessora = "1";
                $msg = "atividadeSucessora";
            }
            $grupo = $service->retornaGrupoPorId($dados, false, true);
            $this->view->grupo = $grupo;
            $this->view->sucessora = $sucessora;
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->idprojeto = $this->_request->getParam('idprojeto');
                $this->view->success = $success;
                $this->view->msg = array(
                    'idprojeto' => $this->_request->getParam('idprojeto'),
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
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
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            // Excluindo a entrega
            $excluirEntrega = $service->excluirEntrega($dados);
            if ($excluirEntrega) {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'E'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
//                $dados['idprojeto'] = $params['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $dados = $this->_request->getParams();
            $servicePredecessora = new Projeto_Service_AtividadeCronoPredecessora();
            $retornaSucessoras = $servicePredecessora->retornaAtividadeCountPredecEntrega(
                array('identrega' => $dados['idatividadecronograma'], 'idprojeto' => $dados['idprojeto'])
            );
            $sucessora = "0";
            // Se atividade tiver predecessora
            if ($retornaSucessoras) {
                $sucessora = "1";
                $msg = "atividadeSucessora";
            }
            $entrega = $service->retornaEntregaPorId($dados, false, true);
            $this->view->entrega = $entrega;
            $this->view->sucessora = $sucessora;
        }
        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'idprojeto' => $this->_request->getParam('idprojeto'),
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function visualizarImpressaoAction()
    {
        $service = new Projeto_Service_Eap();
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $eap = $service->montaEAP($this->_request->getParams());
        $this->view->projeto = $eap['projeto'];
    }

    public function imprimirPdfAction()
    {

        $service = new Projeto_Service_Eap();
        $eap = $service->montaEAP($this->_request->getParams());
        $projeto = $eap['projeto'];
        $projeto['pdf'] = true;

        $this->view->projeto = $projeto;
        $numSeiFormatado = (string)new App_Mask_NumeroSei($projeto['numprocessosei']);
        $this->view->numProcessoSei = $numSeiFormatado;

        $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');

        $cabecalhoProjeto = $this->view->render('/_partials/projeto.phtml');

        /**
         * @todo Essa implementação necessária, pois a view perde a referência ao objeto "$this->view->projeto"
         */
        $this->view->projeto = $projeto;
        $eap = $this->view->render('/_partials/eap-imprimir-pdf.phtml');

        $this->_helper->layout->disableLayout();

        $this->mpdf = new App_Service_MPDF('UTF-8', 'A4-L', '', '', 15, 15, 15, 25, 10, 15, '');

        $this->mpdf->AddPage('L', '', '', '', '', 15, 15, 15, 20, 15, 15);
        $this->mpdf->setFooter('{DATE j/m/Y} - Pág. {PAGENO}/{nb}');

        $stylesheet = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);

        $css = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

        $cssBootstrap = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap.min.css');
        $this->mpdf->WriteHTML($cssBootstrap, 1);

        $cssBootstrapResp = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap-responsive.min.css');
        $this->mpdf->WriteHTML($cssBootstrapResp, 1);

        /*$cssCron = file_get_contents('../public/css/app/projeto/css/cronograma/index.css');
        $this->mpdf->WriteHTML($cssCron, 1);*/


        $this->mpdf->WriteHTML($cabecalho);
        $this->mpdf->WriteHTML($cabecalhoProjeto);
        $this->mpdf->WriteHTML($eap);

        $this->mpdf->Output('EAP_Projeto.pdf', 'I');

    }


}
