<?php

class Projeto_CronogramaController extends Zend_Controller_Action
{
    /**
     * @var $mpdf App_Service_MPDF
     */
    private $mpdf;

    /**
     * @var $mpdf App_Service_simple_html_dom
     */
    private $dom_html;

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('editar-grupo', 'json')
            ->addActionContext('editar-entrega', 'json')
            ->addActionContext('editar-atividade', 'json')
            ->addActionContext('cadastrar-grupo', 'json')
            ->addActionContext('cadastrar-entrega', 'json')
            ->addActionContext('cadastrar-atividade', 'json')
            ->addActionContext('retorna-projeto', 'json')
            ->addActionContext('retorna-predecessora', 'json')
            ->addActionContext('atualizar-cronograma', 'json')
            ->addActionContext('retorna-inicio-base-line', 'json')
            ->addActionContext('retorna-data-fim-por-dias', 'json')
            ->addActionContext('retorna-data-anterior-por-dias', 'json')
            ->addActionContext('retorna-parte-interessada', 'json')
            ->addActionContext('retorna-qtde-dias-uteis-entre-datas', 'json')
            ->addActionContext('atividade-atualizar-percentual', 'json')
            ->addActionContext('atualizar-dom-tipo-atividade', 'json')
            ->addActionContext('excluir-grupo', 'json')
            ->addActionContext('excluir-entrega', 'json')
            ->addActionContext('excluir-atividade', 'json')
            ->addActionContext('verifica-atividades-predecessoras', 'json')
            ->addActionContext('valida-predecessora', 'json')
            ->addActionContext('alterar-visibilidade', 'json')
            ->addActionContext('clonar-grupo', 'json')
            ->addActionContext('clonar-entrega', 'json')
            ->addActionContext('copiar-cronograma', 'json')
            ->addActionContext('atualizar-baseline-atividade', 'json')
            ->addActionContext('atualizar-baseline', 'json')
            ->addActionContext('addcomentario', 'json')
            ->addActionContext('excluircomentariojson', 'json')
            ->addActionContext('atualiza-entrega-in-line', 'json')
            ->addActionContext('atualiza-grupo-in-line', 'json')
            ->addActionContext('gerar-pdf', 'json')
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
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $arrayFeriadosFixos = $serviceCronograma->retornaFeriadosFixos();
        $feriadosFixos = "";
        for ($i = 0; $i < count($arrayFeriadosFixos); $i++) {
            $feriadosFixos = $feriadosFixos . ($feriadosFixos != "" ? "," : "") . $arrayFeriadosFixos[$i]['diaferiado'] . ";" . $arrayFeriadosFixos[$i]['mesferiado'] . ";" . $arrayFeriadosFixos[$i]['anoferiado'];
        }
        if ($this->_request->isPost()) {
            $arrayCronograma = $serviceCronograma->pesquisar($this->_request->getPost());
        } else {
            $arrayCronograma = $serviceCronograma->retornaCronogramaByArray($this->_request->getParams());
        }
        $this->view->conteudo = $arrayCronograma;
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $this->view->feriadosfixos = $feriadosFixos;
        $this->view->formAtividadePesquisar = $serviceCronograma->getFormAtividadePesquisar($this->_request->getParams());

        /*
         * RDM#16789
         * Se o PGP for Aprovado/Assinado o Gerente de Projetos não poderá mais 
         * atualizar a BaseLine
         * Os perfis que poderão aprovar após assinado: Escritório de Projetos 
         * e o Admin Setorial
         */
        $auth = Zend_Auth::getInstance();
        $perfil = $auth->getIdentity()->perfilAtivo->nomeperfilACL;
        $pgpAssinado = $serviceCronograma->retornaPgpAssinadoPorId($this->_request->getParam('idprojeto'));

        $display = "";
        if ($perfil == "gerente" && $pgpAssinado == true) {
            $display = 'disabled';
            $this->view->display = $display;
        } else {
            $display = "inline-block";
            $this->view->display = $display;
        }


    }


    public function retornacronogramajsonAction()
    {
        set_time_limit(0);
        //$this->_helper->layout->setActionController('index');
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $arrayCronograma = $serviceCronograma->retornaCronogramaByArray($this->_request->getParams());
        $this->view->conteudo = $arrayCronograma;
    }

    public function retornaParteInteressadaAction()
    {
        set_time_limit(0);
        $servicePartes = new Projeto_Service_ParteInteressada();
        $arrayPartes = $servicePartes->retornaPartes($this->_request->getParams(), false);
        $this->_helper->json->sendJson($arrayPartes);
    }

    /**
     * Action que retorna o projeto e seu cronograma
     */

    public function retornaProjetoAction()
    {
        set_time_limit(0);
        $service = new Projeto_Service_Gerencia();
        $projeto = $service->retornaArrayCronogramaProjetoPorId($this->_request->getParams());
        $this->view->projeto = $projeto;
//        $this->_helper->json->sendJson($projeto);
    }

    public function cadastrarGrupoAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if (@trim($dados['nomatividadecronograma']) != "") {
                $dados['nomatividadecronograma'] = mb_substr(trim($dados['nomatividadecronograma']), 0, 255);
            }
            $grupo = $serviceCronograma->inserirGrupo($dados);
            //@todo inserir pessoas interessadas
            $this->view->acao = "cadastrar-grupo";
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
                $msg = $serviceCronograma->getErrors();
            }
            $this->view->success = $success;
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        } else {
            $this->view->form = $serviceCronograma->getFormGrupo($this->_request->getParams());
        }
    }

    public function atualizaGrupoInLineAction()
    {

        if ($this->_request->isPost()) {
            $serviceCronograma = new Projeto_Service_AtividadeCronograma();
            $success = false;
            $dados = $this->_request->getPost();
            $entrega = $serviceCronograma->atualizarGrupoInLine($dados);
            if (!is_null($entrega)) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            $this->view->success = $success;
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            }
        }
    }


    public function editarGrupoAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $form = $serviceCronograma->getFormGrupo();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if (@trim($dados['nomatividadecronograma']) != "") {
                $dados['nomatividadecronograma'] = mb_substr(trim($dados['nomatividadecronograma']), 0, 255);
            }
            $ItemGrupo = $serviceCronograma->retornaGrupoPorId($dados);
            $dados['datinicio'] = $ItemGrupo['datinicio'];
            $dados['datfim'] = $ItemGrupo['datfim'];
            $dados['datiniciobaseline'] = $ItemGrupo['datiniciobaseline'];
            $dados['datfimbaseline'] = $ItemGrupo['datfimbaseline'];
            $atividade = $serviceCronograma->atualizarGrupo($dados);
            $this->view->acao = "editar-grupo";
            if ($atividade) {
                $success = true; ###### AUTENTICATION SUCCESS
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"];
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $serviceCronograma->getErrors();
            }
            $this->view->success = $success;
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->item = $atividade;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'cronograma', 'projeto',
                        array('idprojeto' => $dados['idprojeto']));
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }

        } else {
            $dados = $this->_request->getParams();
            $grupo = $serviceCronograma->retornaGrupoPorId($dados);
            $form->populate($grupo);
            $this->view->form = $form;
        }
    }

    public function atualizaEntregaInLineAction()
    {

        if ($this->_request->isPost()) {
            $serviceCronograma = new Projeto_Service_AtividadeCronograma();
            $success = false;
            $dados = $this->_request->getPost();
            $entrega = $serviceCronograma->atualizarEntregaInLine($dados);
            if (!is_null($entrega)) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            $this->view->success = $success;
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            }
        }
    }

    public function editarEntregaAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $form = $serviceCronograma->getFormEntrega($this->_request->getParams());
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if (@trim($dados['nomatividadecronograma']) != "") {
                $dados['nomatividadecronograma'] = mb_substr(trim($dados['nomatividadecronograma']), 0, 255);
            }
            if (@trim($dados['desobs']) != "") {
                $dados['desobs'] = mb_substr(trim($dados['desobs']), 0, 4000);
            }
            if (@trim($dados['descriterioaceitacao']) != "") {
                $dados['descriterioaceitacao'] = mb_substr(trim($dados['descriterioaceitacao']), 0, 4000);
            }
            $ItemEntrega = $serviceCronograma->retornaEntregaPorId($dados, true);
            $dados['datinicio'] = $ItemEntrega['datinicio'];
            $dados['datfim'] = $ItemEntrega['datfim'];
            $dados['datiniciobaseline'] = $ItemEntrega['datiniciobaseline'];
            $dados['datfimbaseline'] = $ItemEntrega['datfimbaseline'];
            $entrega = $serviceCronograma->atualizarEntrega($dados);
            $this->view->acao = "editar-entrega";
            if ($entrega) {
                $success = true; ###### AUTENTICATION SUCCESS
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $serviceCronograma->getErrors();
            }
            $this->view->success = $success;
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->item = $entrega;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'cronograma', 'projeto',
                        array('idprojeto' => $dados['idprojeto']));
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        } else {
            $dados = $this->_request->getParams();
            $entrega = $serviceCronograma->retornaEntregaPorId($this->_request->getParams(), true);
            $form->populate($entrega);
            $this->view->form = $form;
        }
    }

    public function cadastrarEntregaAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if (@trim($dados['nomatividadecronograma']) != "") {
                $dados['nomatividadecronograma'] = mb_substr(trim($dados['nomatividadecronograma']), 0, 255);
            }
            if (@trim($dados['desobs']) != "") {
                $dados['desobs'] = mb_substr(trim($dados['desobs']), 0, 4000);
            }
            if (@trim($dados['descriterioaceitacao']) != "") {
                $dados['descriterioaceitacao'] = mb_substr(trim($dados['descriterioaceitacao']), 0, 4000);
            }
            $grupo = $serviceCronograma->inserirEntrega($dados);
            //@todo inserir pessoas interessadas
            $this->view->acao = "cadastrar-entrega";
            if ($grupo) {
                $success = true;
                $this->view->item = $grupo;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $serviceCronograma->getErrors();
            }
            $this->view->success = $success;
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        } else {
            $this->view->form = $serviceCronograma->getFormEntrega($this->_request->getParams());
        }
    }

    public function editarAtividadeAction()
    {
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $form = $serviceAtividadeCronograma->getFormAtividadeAtualizar($this->_request->getParams());
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();

            if ($dados['domtipoatividade'] == "3") {
                if ($dados['numdiasrealizados'] <= 0) {
                    $dados['numdiasrealizados'] = 1;
                }
            }
            if (isset($dados['desobs']) && (!empty($dados['desobs']))) {
                $dados['desobs'] = mb_substr(trim($dados['desobs']), 0, 4000);
            }
            if (isset($dados['descriterioaceitacao']) && (!empty($dados['descriterioaceitacao']))) {
                $dados['descriterioaceitacao'] = mb_substr(trim($dados['descriterioaceitacao']), 0, 4000);
            }
            if (isset($dados['nomatividadecronograma']) && (!empty($dados['nomatividadecronograma']))) {
                $dados['nomatividadecronograma'] = mb_substr(trim($dados['nomatividadecronograma']), 0, 255);
            }
            unset($dados['predecessorasAtividade']);
            unset($dados['idpredecessora']);
            unset($dados['predecessora']);

            $atividade = $serviceAtividadeCronograma->atualizarAtividade($dados);
            $this->view->acao = "editar-atividade";
            $projeto = "";
            $msgErr = "";
            if ($atividade) {
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
                $msgErr = $serviceAtividadeCronograma->getErrors();
            }
            $this->view->projeto = $projeto;
            $this->view->success = $success;
            $this->view->msg = array(
                'text' => $msg,
                'textErr' => $msgErr,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        } else {
            $dados = $this->_request->getParams();
            $dados['orderAsc'] = 'S';
            $dados['idatividadeAt'] = $dados['idatividadecronograma'];
            $form = $serviceAtividadeCronograma->getFormAtividadeAtualizar($dados, true);
            $dadosRequest = $this->_request->getParams();
            $dadosRequest['orderAsc'] = 'S';
            $atividade = $serviceAtividadeCronograma->retornaAtividadePorId($dadosRequest, false, true);

            if (is_array($atividade['predecessoras']) && count($atividade['predecessoras']) > 0) {//Verifica maior data predecessora
                $maxDatinicio = $serviceAtividadeCronograma->retornaInicioBaseLinePorAtividade($atividade);
                $this->view->dataPredecessora = $maxDatinicio;
                $dataFim = $serviceAtividadeCronograma->preparaData($atividade['datfim']);

                if ($atividade['numdiasrealizados'] >= 0) {
                    if ($atividade['numdiasrealizados'] > 0) {
                        $atividade['datfim'] = $serviceAtividadeCronograma->retornaDataFimValidaPorDias(
                            array(
                                'datainicio' => $atividade['datinicio'],
                                'numdias' => $atividade['numdiasrealizados']
                            )
                        );
                        //$atividade['datfim'] = $serviceAtividadeCronograma->adicionarDias($atividade['datinicio'], $atividade['numdiasrealizados']);
                    } else {
                        if (isset($dataFim) && (!empty($dataFim))) {
                            $atividade['datfim'] = $dataFim->format('d/m/Y');
                        }
                    }
                }
            }

            if ($atividade['domtipoatividade'] == "4") {
                $atividade['datfim'] = $atividade['datinicio'];
                $atividade['numdiasrealizados'] = "0";
                $form->getElement('numdiasrealizados')->setAttribs(array('value' => "0",));
                $form->getElement('numpercentualconcluido')->setMultiOptions(
                    array(0 => '0%', 100 => '100%')
                );
            }
            if ($atividade['domtipoatividade'] == "3") {
                if ($atividade['numdiasrealizados'] == "0") {
                    $atividade['numdiasrealizados'] = "1";
                } else {
                    $form->getElement('numdiasrealizados')->setAttribs(array('value' => $atividade['numdiasrealizados'],));
                }
            }

            if (count($atividade['predecessoras']) > 0) {//Verifica maior data predecessora
                //$atividade['predecessorasAtividade'] = $atividade['predecessoras'];
                $form->getElement('predecessorasAtividade')->setMultiOptions($atividade['predecessoras']);
            }
            $atividade['vlratividade'] = $serviceAtividadeCronograma->preparaValor($atividade['vlratividade']);
            $this->view->atividade = $atividade;
            //unset($atividade['predecessoras']);
            if ($atividade['domtipoatividade'] == "4") {
                //$form->getElement('datfim')->setAttribs(           array('readonly' => true, ));
                $form->getElement('datfim')->setAttribs(array(
                    'readonly' => true,
                    'disabled' => 'disabled',
                    'required' => false
                ));
                $form->getElement('numdiasrealizados')->setAttribs(array('readonly' => true,));
            }
            $form->getElement("datfim")->setAttribs(array('onchange' => "javascript:CRONOGRAMA.atividade.trataDatasAtividadeEdicao($(this));",));
            $form->getElement("datinicio")->setAttribs(array('onchange' => "javascript:CRONOGRAMA.atividade.trataDatasAtividadeEdicao($(this));",));
            $form->getElement("domtipoatividade")->setAttribs(array('onchange' => "javascript:CRONOGRAMA.atividade.trataDominioTipoAtividade('editar');",));

            $form->populate($atividade);
            $this->view->form = $form;
        }
    }

    public function cadastrarAtividadeAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $dados['numdiasrealizados'] = $dados['numdiasbaseline'];
            if (isset($dados['desobs']) && trim($dados['desobs']) != "") {
                $dados['desobs'] = mb_substr(trim($dados['desobs']), 0, 4000);
            }
            if (trim($dados['nomatividadecronograma']) != "") {
                $dados['nomatividadecronograma'] = mb_substr(trim($dados['nomatividadecronograma']), 0, 255);
            }
            unset($dados['predecessora']);

            $atividade = $serviceCronograma->inserirAtividade($dados);
            //@todo inserir pessoas interessadas
            $this->view->acao = "cadastrar-atividade";
            if ($atividade) {
                $success = true;
                $this->view->item = $atividade;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $serviceCronograma->getErrors();
            }
            $this->view->success = $success;
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        } else {
            $form = $serviceCronograma->getFormAtividade($this->_request->getParams());
            $this->view->form = $form;
            $form->getElement("datfimbaseline")->setAttribs(array('onchange' => "javascript:CRONOGRAMA.atividade.trataDatasAtividadeEdicao($(this));",));
            $form->getElement("datiniciobaseline")->setAttribs(array('onchange' => "javascript:CRONOGRAMA.atividade.trataDatasAtividadeEdicao($(this));",));
            $form->getElement("domtipoatividade")->setAttribs(array('onchange' => "javascript:CRONOGRAMA.atividade.trataDominioTipoAtividade('cadastrar');",));
        }
    }

    public function retornaInicioBaseLineAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $resultado = new stdClass();
        $datainiciobaseline = $serviceCronograma->retornaInicioBaseLineComFolgaPorAtividade($this->_request->getPost());
        $maxDatinicio = $serviceCronograma->retornaInicioBaseLinePorAtividade($this->_request->getPost());
        $resultado->datainiciobaseline = $datainiciobaseline;
        $resultado->maiordatapredecessora = $maxDatinicio;
        $this->_helper->json->sendJson($resultado);
    }

    public function retornaDataFimPorDiasAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $resultado = new stdClass();
        $dados = $this->_request->getPost();
        $dfim = $serviceCronograma->retornaDataFimValidaPorDias($dados);
        $resultado->datafim = $dfim;
        $resultado->datainicio = $dados['datainicio'];
        $resultado->numdias = $dados['numdias'];
        $this->_helper->json->sendJson($resultado);
    }

    public function retornaDataAnteriorPorDiasAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $resultado = new stdClass();
        $dados = $this->_request->getPost();
        $dfim = $serviceCronograma->retornaDataAnteriorValidaPorDias($dados);
        $resultado->datafim = $dfim;
        $resultado->datainicio = $dados['datainicio'];
        $resultado->numdias = $dados['numdias'];
        $this->_helper->json->sendJson($resultado);
    }

    public function retornaQtdeDiasUteisEntreDatasAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $resultado = new stdClass();
        $dados = $this->_request->getPost();
        $numdias = $serviceCronograma->retornaQtdeDiasUteisEntreDatas($dados);
//        $numdias = $numdias * (-1);
        //$numdias = ($numdias > 0 ? $numdias - 1 : $numdias + 1);
        $resultado->numdias = $numdias;
        $resultado->datainicio = $dados['datainicio'];
        $resultado->datafim = $dados['datafim'];
        $this->_helper->json->sendJson($resultado);
    }

    public function retornaPredecessoraAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        //$resultado = $service->retornaInicioBaseLinePorPredecessoras($this->_request->getPost());
        $resultado = $serviceCronograma->retornaInicioBaseLinePorAtividade($this->_request->getPost());
        $this->_helper->json->sendJson($resultado);
    }

    public function validaPredecessoraAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $success = true;
        $valido = true;
        $msg = '';
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $predecessora = $serviceCronograma->validaPredecessoraAtividade($dados);
            if (!($predecessora)) {
                $valido = false;
                $msg = 'Aten&ccedil;&atilde;o: A atividade selecionada como predecessora &eacute; inv&aacute;lida, pois cria uma rela&ccedil;&atilde;o de depend&ecirc;ncia circular com outras atividades.';
            }
            $response = new stdClass();
            $response->success = $success;
            $response->valida = $valido;
            $response->msg = array(
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
            $this->_helper->json->sendJson($response);
        }
    }

    public function alterarVisibilidadeAction()
    {
        $serviceOcultar = new Projeto_Service_AtividadeOcultar();
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $auth = Zend_Auth::getInstance();
            $identity = $auth->getIdentity();
            if (property_exists($identity, 'cd_matricula')) {
                $dados["nummatricula"] = $auth->getIdentity()->cd_matricula;
            }
            if ((@trim($dados["flashowhide"] == "")) || ((@trim($dados["flashowhide"] != "S")) && (@trim($dados["flashowhide"] != "N")))) {
                $dados["flashowhide"] = "S";
            } else {
                $dados["flashowhide"] = (@trim($dados["flashowhide"] == "S") ? "N" : "S");
            }
            if ($dados["flashowhide"] == 'S') {
                if ($serviceOcultar->verificaAtividadeOcultar($dados)) {
                    $atividade = $serviceOcultar->excluir($dados);
                } else {
                    $atividade = true;
                }
            } else {
                if ($serviceOcultar->verificaAtividadeOcultar($dados)) {
                    $atividade = true;
                } else {
                    $atividade = $serviceOcultar->inserir($dados);
                }
            }
            if (!($atividade)) {
                $success = false;
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            } else {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            }
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        }
    }

    public function retornaInicioRealAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $resultado = $serviceCronograma->retornaInicioRealPorPredecessoras($this->_request->getPost());
        $this->_helper->json->sendJson($resultado);
    }

    public function adicionarPredecessoraAction()
    {
        $service = new Projeto_Service_AtividadeCronoPredecessora();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $buscaPredecessora = $service->pesquisaPredecessoraAtividade($dados);
            if ($buscaPredecessora) {
                $success = false;
                $msg = "Predecessora já cadastrada.";
            } else {
                $predecessora = $service->inserir($dados);
                if ($predecessora) {
                    /** Cadastra na linha do tempo (auditoria). */
                    $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                    $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                    $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                    $dados['idprojeto'] = $dados['idprojeto'];
                    $serviceLinhaTempo->inserir($dados);
                    $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
                    $success = true;
                    $parametros = array(
                        'idprojeto' => $dados['idprojeto'],
                        'idatividadecronograma' => $dados['idatividade'],
                        'orderAsc' => 'S'
                    );
                    $listaPredecessoras = $service->listaPorAtividade($parametros);

                } else {
                    $listaPredecessoras = array();
                    $success = false;
                    $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                }
            }
            $response = new stdClass();
            $response->predecessora = "";
            if (isset($predecessora)) {
                $response->predecessora = $predecessora;
            }
            $response->listaPredecessoras = "";
            if (isset($listaPredecessoras)) {
                $response->listaPredecessoras = $listaPredecessoras;
            }
            $response->success = $success;
            $response->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'error' => ($success) ? false : true,
                'hide' => true,
                'closer' => true,
                'success' => $success,
                'sticker' => false
            );
            $this->_helper->json->sendJson($response);
        }
    }

    /**
     * REFACTORING: Funcionalidade de excluir atividade
     *
     * @return mixed
     */
    public function excluirPredecessoraAction()
    {
        $response = array(
            'msg' => array(
                'type' => 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            ),
            'listaPredecessoras' => '',
            'success' => false
        );
        try {
            if ($this->_request->isPost()) {
                $service = new Projeto_Service_AtividadeCronoPredecessora();
                $dados = $this->_request->getPost();
                $params = array(
                    'idprojeto' => $this->_request->getPost('idprojeto'),
                    'idatividade' => $this->_request->getPost('idatividadecronograma'),
                );
                if (null === $params['idprojeto'] || null === $params['idatividade']) {
                    throw new Exception('Faltando parametro pra exclusao: ' . json_encode($params));
                }
                $predecessora = $service->excluir($params);
                if (!$predecessora) {
                    throw new Exception(
                        App_Service_ServiceAbstract::ERRO_GENERICO . ': ' . json_encode($service->getErrors())
                    );
                }
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'E'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $params['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $response['msg']['text'] = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
                $response['msg']['type'] = 'success';
                $response['predecessora'] = $predecessora;
                $response['success'] = true;
                $parametros = array(
                    'idprojeto' => $dados['idprojeto'],
                    'idatividadecronograma' => $dados['idatividadecronograma'],
                    'orderAsc' => 'S'
                );
                $listaPredecessoras = $service->listaPorAtividade($parametros);
                if (isset($listaPredecessoras)) {
                    $response['listaPredecessoras'] = $listaPredecessoras;
                }
            }
            return $this->_helper->json->sendJson($response);
        } catch (Exception $exception) {
            $response['msg']['text'] = $exception->getMessage();
            $response['success'] = false;
            return $this->_helper->json->sendJson($response);
        }
    }

    public function atualizarCronogramaAction()
    {
        set_time_limit(0);
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $success = false;
        $dados = $this->_request->getParams();
        $resultado = $serviceCronograma->atualizarCronogramaDoProjeto($dados);
        if ($resultado) {
            $serviceCronograma->atualizaNumseq($dados);
            $serviceGerencia = new Projeto_Service_Gerencia();
            $arrayProjeto = $serviceGerencia->retornaArrayProjetoPorId($dados);
            $serviceGerencia->atualizaPercentualProjeto($dados);
            $this->view->projeto = $serviceGerencia->retornaArrayProjetoPorId($dados);
            $msg = App_Service_ServiceAbstract::CRONOGRAMA_ATUALIZADO_COM_SUCESSO;
            $success = true;
        } else {
            $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
        }
        $response = new stdClass();
        $response->success = $success;
        $response->msg = array(
            'text' => $msg,
            'type' => ($success) ? 'success' : 'error',
            'hide' => true,
            'closer' => true,
            'sticker' => false
        );

        $this->_helper->json->sendJson($response);
    }

    public function atividadeAtualizarPercentualAction()
    {
        $success = false;
        $msg = null;
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        if ($this->_request->isPost()) {
            $params = $this->_request->getPost();

            $atividade = $serviceCronograma->atualizarAtividade($params);

            if ($atividade) {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $params['idprojeto']; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $serviceCronograma->getErrors();
            }

            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        }
    }

    public function atualizarDomTipoAtividadeAction()
    {
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $serviceCronograma = new Projeto_Service_AtividadeCronograma();
            $atividadeItem = $serviceCronograma->retornaAtividadePorId($dados, false, false);
            if ($atividadeItem['domtipoatividade'] == "3") {
                $atividadeItem['domtipoatividade'] = "4";
            } else {
                $atividadeItem['domtipoatividade'] = "3";
            }
            $atividadeItem['predecessorasAtividade'] = "";
            $atividadeItem['idpredecessora'] = "";
            $atividadeItem['predecessora'] = "";
            $atividade = $serviceCronograma->atualizarTipoAtividade($atividadeItem);
            //@todo inserir pessoas interessadas
            if ($atividade) {
                $this->view->item = $atividade;
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $serviceCronograma->getErrors();
            }
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        }
    }

    /**
     * REFACTORING: Funcionalidade de excluir atividade
     *
     * @throws Exception
     */
    public function excluirGrupoAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            // *****Fim Excluindo as predecessoras por grupo****************//
            // Excluindo o grupo
            $excluirGrupo = $serviceCronograma->excluirGrupo($dados);
            if ($excluirGrupo) {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'E'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $success = false;
                $msg = $serviceCronograma->getErrors();
            }
            $response = new stdClass();
            $response->success = $success;
            $response->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
            $this->_helper->json->sendJson($response);
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
            $grupo = $serviceCronograma->retornaGrupoPorId($dados, false, true);
            $this->view->grupo = $grupo;
            $this->view->sucessora = $sucessora;
        }
    }

    /**
     * REFACTORING: Funcionalidade de excluir atividade
     *
     * @throws Exception
     */
    public function excluirEntregaAction()
    {
        $serviceAtividade = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();

            // *****Fim Excluindo as predecessoras por grupo****************//
            // Excluindo a entrega
            $excluirEntrega = $serviceAtividade->excluirEntrega($dados);
            if ($excluirEntrega) {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'E'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $msg = $serviceAtividade->getErrors();
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
            $entrega = $serviceAtividade->retornaEntregaPorId($this->_request->getParams(), false, true);
            $this->view->entrega = $entrega;
            $this->view->sucessora = $sucessora;
        }
        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'cronograma', 'projeto',
                        array('idprojeto' => $dados['idprojeto']));
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    /**
     * REFACTORING: Funcionalidade de excluir atividade
     * @return Zend_View_Interface
     */
    public function excluirAtividadeAction()
    {
        try {
            $serviceCronograma = new Projeto_Service_AtividadeCronograma();
            if ($this->_request->isPost()) {
                $dados = $this->_request->getPost();
                $resultado = $serviceCronograma->excluirAtividade($dados);
                if ($resultado) {
                    $success = true;
                    $msgErr = "";
                    /** Cadastra na linha do tempo (auditoria). */
                    $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                    $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                    $dados['tpacao'] = 'E'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                    $dados['idprojeto'] = $dados['idprojeto'];
                    $serviceLinhaTempo->inserir($dados);
                    $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
                } else {
                    $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                    $msgErr = $serviceCronograma->getErrors();
                    $success = false;
                }
                $response = new stdClass();
                $response->success = $success;
                $response->msgErr = $msgErr;
                $response->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
                return $this->_helper->json->sendJson($response);
            }
            return $this->view;
        } catch (Exception $exception) {
            return $this->_helper->json->sendJson([
                'success' => false,
                'msgErr' => $exception->getMessage(),
                'msg' => array(
                    'text' => $exception->getMessage(),
                    'type' => 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                )
            ]);
        }
    }

    /**
     * REFACTORING: Funcionalidade de excluir atividade
     *
     * @return Zend_View_Interface
     */
    public function verificaAtividadesPredecessorasAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $idatividade = $this->_request->getParam('idatividadecronograma');
        $idProjeto = $this->_request->getParam('idprojeto');
        $servicePredecessora = new Projeto_Service_AtividadeCronoPredecessora();
        // Retorna as sucessoras por atividade
        $retornaSucessoras = $servicePredecessora->retornaAtividadeCountPredec(array(
            'idatividadecronograma' => $idatividade,
            'idprojeto' => $idProjeto
        ));
        $msgErr = "";
        // Se atividade tiver predecessora
        if ($retornaSucessoras) {
            $success = true;
            $msg = "atividadeSucessora";
        } else {
            // Retora todas as predecessora por projeto
            $retornaTodasPred = $servicePredecessora->retornaTodasPredecessorasPorIdAtividade(
                $idProjeto,
                $idatividade
            );
            if ($retornaTodasPred) {
                if (count($retornaTodasPred) > 0) {
                    $success = true;
                    $msg = "predecessora";
                } elseif ($retornaTodasPred['idatividadepredecessora'] == '') {
                    $success = true;
                    $msg = 'atividade';
                } else {
                    $msgErr = $serviceCronograma->getErrors();
                    $msg = "905:" . App_Service_ServiceAbstract::ERRO_GENERICO;
                }
            } else {
                $success = true;
                $msg = 'atividade';
            }
        }
        if ($this->_request->isXmlHttpRequest()) {
            $this->view->success = $success;
            $this->view->msg = array(
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'error' => $msgErr,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        } else {
            if ($success) {
                return $this->_helper->_redirector->gotoSimpleAndExit(
                    'index',
                    'cronograma',
                    'projeto',
                    array(
                        'idprojeto' => $idProjeto
                    )
                );
            }
            $this->_helper->_flashMessenger->addMessage(array(
                'status' => ($success) ? 'success' : 'error',
                'message' => $msg
            ));
            return $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
        }
        return $this->view;
    }

    public function pesquisarAction()
    {
        if ($this->_request->isPost()) {
            $serviceCronograma = new Projeto_Service_AtividadeCronograma();
            $resultado = $serviceCronograma->pesquisar($this->_request->getPost());
            $this->_helper->json->sendJson($resultado);
        }
    }

    public function clonarEntregaAction()
    {

        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if ($serviceCronograma->clonarEntrega($dados)) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CLONADO_COM_SUCESSO;
            } else {
                $msg = $serviceCronograma->getErrors();
            }
            $response = new stdClass();
            $response->success = $success;
            $response->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
            $this->_helper->json->sendJson($response);
            /*
            $this->view->msg = array(
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );*/
        }
    }

    public function clonarGrupoAction()
    {

        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $clonagem = $serviceCronograma->clonarGrupo($dados);
            if ($clonagem) {
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);

                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CLONADO_COM_SUCESSO;
            } else {
                $msg = $serviceCronograma->getErrors();
            }
            $response = new stdClass();
            $response->success = $success;
            $response->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
            $this->_helper->json->sendJson($response);
            /*
            $this->view->msg = array(
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );*/
        }
    }

    public function pesquisarprojetojsonAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $paginator = $serviceCronograma->pesquisarProjeto($this->_request->getParams(), true);
        $this->_helper->json->sendJson($paginator);
    }

    public function pesquisarProjetoAction()
    {
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $serviceProjeto = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $serviceProjeto->getFormPesquisar();
        $this->view->form = $form;
    }

    public function copiarCronogramaAction()
    {
        $params = $this->_request->getParams();
        $this->view->params = $params;
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $idprojeto = (int)$this->_request->getParam('idprojetoorigem');
            if ($serviceCronograma->isExisteCronograma($idprojeto)) {
                $copia = $serviceCronograma->copiarCronograma($params);
                if ($copia) {
                    $msg = App_Service_ServiceAbstract::CRONOGRAMA_COPIADO_COM_SUCESSO .
                        "<br /> Clique <a href=" . $this->getFrontController()->getBaseUrl() . "/projeto/cronograma/index/idprojeto/" . $params['idprojetoorigem'] . ">aqui</a> para voltar ao Projeto de origem. ";
                    $success = true;
                } else {
                    $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                }
            } else {
                $msg = App_Service_ServiceAbstract::EXISTE_CRONOGRAMA_CADASTRADO;
            }
            $this->view->msg = array(
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => false,
                'sticker' => false
            );
        }
    }

    public function detalharAction()
    {
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $service = new Projeto_Service_Gerencia();
        $projeto = $service->retornaArrayProjetoPorId($this->_request->getParams());
        $this->view->projeto = $projeto;
    }

    public function atualizarBaselineAtividadeAction()
    {

        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $atividade = new Projeto_Model_Atividadecronograma($serviceCronograma->retornaAtividadePorId($this->_request->getParams(),
                false, false));
            if (@trim($atividade['numdiasrealizados'] == "")) {
                $numdias = $atividade->retornaDiasReal();
                $dados['numdiasbaseline'] = $numdias;
                $dados['numdiasrealizados'] = $numdias;
                $dados['numdias'] = $numdias;
            } else {
                $dados['numdiasbaseline'] = $atividade['numdiasrealizados'];
                $dados['numdiasrealizados'] = $atividade['numdiasrealizados'];
                $dados['numdias'] = $atividade['numdiasrealizados'];
            }
            $dados['domtipoatividade'] = $atividade['domtipoatividade'];
            if ($serviceCronograma->atualizarBaselineAtividade($dados)) {
                $success = true;
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $serviceCronograma->getErrors();
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
            exit;
        } else {
            $atividade = $serviceCronograma->retornaAtividadePorId($this->_request->getParams(), true);
            $this->view->atividade = $atividade;
        }
    }

    public function atualizarBaselineAction()
    {
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();

            if ($serviceCronograma->atualizarBaseline($dados)) {
                $success = true;

                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);

                $msg = App_Service_ServiceAbstract::BASELINE_ATUALIZADA_COM_SUCESSO;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
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
            exit;
        }
    }

    public function imprimirAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $arrayFeriadosFixos = $serviceCronograma->retornaFeriadosFixos();
        $feriadosFixos = "";
        for ($i = 0; $i < count($arrayFeriadosFixos); $i++) {
            $feriadosFixos = $feriadosFixos . ($feriadosFixos != "" ? "," : "") . $arrayFeriadosFixos[$i]['diaferiado'] . ";" . $arrayFeriadosFixos[$i]['mesferiado'] . ";" . $arrayFeriadosFixos[$i]['anoferiado'];
        }
        $arrayCronograma = $serviceCronograma->retornaCronogramaByArray($this->_request->getParams());

        $this->view->conteudo = $arrayCronograma;
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $this->view->feriadosfixos = $feriadosFixos;
        $this->view->formAtividadePesquisar = $serviceCronograma->getFormAtividadePesquisar($this->_request->getParams());

        /*
         * RDM#16789
         * Se o PGP for Aprovado/Assinado o Gerente de Projetos não poderá mais
         * atualizar a BaseLine
         * Os perfis que poderão aprovar após assinado: Escritório de Projetos
         * e o Admin Setorial
         */
        $auth = Zend_Auth::getInstance();
        $perfil = $auth->getIdentity()->perfilAtivo->nomeperfilACL;
        $pgpAssinado = $serviceCronograma->retornaPgpAssinadoPorId($this->_request->getParam('idprojeto'));

        $display = "";
        if ($perfil == "gerente" && $pgpAssinado == true) {
            $display = 'disabled';
            $this->view->display = $display;
        } else {
            $display = "inline-block";
            $this->view->display = $display;
        }


    }

    public function imprimirPdfAction()
    {
        set_time_limit(0);

        $diasFarol = 0;
        $descriaoPrazoProjeto = null;
        $serviceCron = new Projeto_Service_AtividadeCronograma();
        $service = new Projeto_Service_Gerencia();
        $arrayCronograma = $serviceCron->retornaCronogramaByArray($this->_request->getParams());
        $projeto = $arrayCronograma['projeto'];
        $arrayCronograma = $arrayCronograma['cronograma'];
        $linhaResumo = $projeto;
        $numSeiFormatado = (string)new App_Mask_NumeroSei($projeto['numprocessosei']);
        $diasreal = $linhaResumo['numdiasrealizados'];

        if (!empty($linhaResumo['datfimReal'])) {
            $descriaoPrazoProjeto = $service->retornaDescricaoFarol($linhaResumo['datfimReal'],
                $linhaResumo['datfimbaseline'], $linhaResumo['numcriteriofarol']);
        }

        if (isset($linhaResumo["datfimbaseline"]) && (!empty($linhaResumo["datfimbaseline"])) &&
            isset($linhaResumo["datfimReal"]) && (!empty($linhaResumo["datfimReal"]))
        ) {
            $datfimbaseline = new Zend_Date($linhaResumo["datfimbaseline"], 'dd/MM/YYYY');
            $datfim = new Zend_Date($linhaResumo["datfimReal"], 'dd/MM/YYYY');

            if (Zend_Date::isDate($datfimbaseline) && Zend_Date::isDate($datfim)) {
                $dados['datainicio'] = $datfimbaseline;
                $dados['datafim'] = $datfim;
                $diasFarol = $serviceCron->retornaQtdeDiasUteisEntreDatas($dados);

                /**********************************************************/
                /* retira um dia do cálculo para atender a regra definida */
                $diasFarol = ($diasFarol > 0 ? $diasFarol - 1 : $diasFarol + 1);
            }
        }

        $projeto['pdf'] = true;
//        Zend_Debug::dump($linhaResumo);die;
        $this->view->linhaResumoFarol = $diasFarol;
        $this->view->linhaCorFarol = $descriaoPrazoProjeto;
        $this->view->cronograma = $arrayCronograma;
        $this->view->projeto = $projeto;
        $this->view->numProcessoSei = $numSeiFormatado;
        $this->view->linhaResumoDtPlanejado = (!empty($linhaResumo["datiniciobaseline"])) ? $linhaResumo["datiniciobaseline"] . ' a ' . $linhaResumo["datfimbaseline"] : "";
        $this->view->linhaResumoDtRealizado = (!empty($linhaResumo["datinicioReal"])) ? $linhaResumo["datinicioReal"] . ' a ' . $linhaResumo["datfimReal"] : "";
        $this->view->linhaResumoDiasBaseLine = (!empty($linhaResumo["numdiasbaseline"])) ? $linhaResumo["numdiasbaseline"] : "";
        $this->view->linhaResumoCusto = $linhaResumo['vlratividadet'];
        $this->view->linhaResumoDiasRealizados = $diasreal;
        $this->view->linhaResumoPercentual = (!empty($linhaResumo["numpercentualconcluido"])) ? round($linhaResumo["numpercentualconcluido"]) . '%' : '0%';
        $this->view->linhaResumoNomProjeto = (!empty($linhaResumo["nomprojeto"])) ? $linhaResumo["nomprojeto"] : "";


        $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');
        $cabecalhoProjeto = $this->view->render('/_partials/projeto.phtml');
        $cronograma = $this->view->render('/_partials/cron-imprimir-pdf.phtml');
        $this->_helper->layout->disableLayout();

        $this->mpdf = new App_Service_MPDF('UTF-8', 'A4-L', '', '', 15, 15, 15, 25, 10, 15, '');
        $this->mpdf->AddPage('L', '', '', '', '', 15, 15, 15, 20, 15, 15);
        $this->mpdf->setFooter('{DATE j/m/Y} - Pág. {PAGENO}/{nb}');

        $stylesheet = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);

        $css = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

        $cssBootstrap = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap.min.css');
        $this->mpdf->WriteHTML($cssBootstrap, 1, true);

        $cssBootstrapResp = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap-responsive.min.css');
        $this->mpdf->WriteHTML($cssBootstrapResp, 1, true);

        $this->mpdf->WriteHTML($cabecalho);
        $this->mpdf->WriteHTML($cabecalhoProjeto);
        $this->mpdf->WriteHTML($cronograma);

        $this->mpdf->Output('CronogramaProjeto' . $projeto["idprojeto"] . '.pdf', 'I');


    }

    public function relatorioCronogramaAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $this->view->form = $serviceCronograma->getFormRelatorioCronograma();
    }

    public function resultadoRelatorioCronogramaAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $params = array_filter($this->_request->getParams());
        $this->view->parametros = $params;

        $resultado = $serviceCronograma->retornaCronogramaProjetos($this->_request->getParams());
        $this->view->resultado = $resultado['projetos'];
        $this->view->custoTodosProjetos = $resultado['custoTodosProjetos'];
        $this->view->qtdeRegistros = $resultado['qtdeRegistros'];
    }

    public function buscarprojetosAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $dados = $serviceCronograma->fetchPairsProjetos($this->_request->getParams());
        $this->_helper->json->sendJson($dados);
    }

    public function buscarnaturezasAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $dados = $serviceCronograma->fetchPairsNaturezas($this->_request->getParams());
        $this->_helper->json->sendJson($dados);
    }

    public function verificaparteinteressadajsonAction()
    {
        $serviceLogin = new Default_Service_Login();
        $serviceParteInteressada = new Projeto_Service_ParteInteressada();
        $usuario = $serviceLogin->retornaUsuarioLogado();
        $params['idprojeto'] = (int)$this->_request->getParam('idprojeto');
        $params['idpessoainterna'] = $usuario->idpessoa;
        /**
         * @var $modelParte Projeto_Model_Parteinteressada
         */
        $modelParte = $serviceParteInteressada->buscaParteInteressadaInterna($params);

        if (is_object($modelParte) && (null != $modelParte->idparteinteressada)) {
            $success = true;
            $msg = App_Service_ServiceAbstract::PARTE_INTERESSADA_ENCONTRADA;
        } else {
            $success = false;
            $msg = App_Service_ServiceAbstract::PARTE_INTERESSADA_NAO_ENCONTRADA;
        }
        $response = new stdClass();
        $response->success = $success;
        $response->msg = array(
            'text' => $msg,
            'type' => ($success) ? 'success' : 'info',
            'hide' => true,
            'closer' => true,
            'sticker' => false
        );

        $this->_helper->json->sendJson($response);

    }

    public function addcomentarioAction()
    {
        $service = new Projeto_Service_Comentario();
        $serviceLogin = new Default_Service_Login();
        $serviceParteInteressada = new Projeto_Service_ParteInteressada();
        $serviceAtividadeCron = new Projeto_Service_AtividadeCronograma();
        $success = false;

        $usuario = $serviceLogin->retornaUsuarioLogado();
        $params['idprojeto'] = (int)$this->_request->getParam('idprojeto');
        $params['idpessoainterna'] = $usuario->idpessoa;
        /** @var $modelParte Projeto_Model_Parteinteressada */
        $modelParte = $serviceParteInteressada->buscaParteInteressadaInterna($params);
        $params['idatividadecronograma'] = (int)$this->_request->getParam('idatividadecronograma');
        $arrAtividade = $serviceAtividadeCron->retornaAtividadeById($params);
        $modelAtividade = new Projeto_Model_Atividadecronograma($arrAtividade);
        $this->view->parte = $modelParte;
        $this->view->atividade = $modelAtividade;
        $form = $service->getForm();
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $this->view->nomeatividade = $this->_request->getParam('nomeatividadecronograma');
        $this->view->idatividadecronograma = $this->_request->getParam('idatividadecronograma');
        $this->view->form = $form;
        $listaComentarios = $service->listarComentarios($this->_request->getParams());
        $this->view->comentarios = $listaComentarios;


        if ($this->_request->isPost()) {
            $params = $this->_request->getPost();
            $resultado = $service->inserir($params);
            $contador = 0;
            if ($resultado) {
                $success = true;
                $listaComentarios = $service->listarComentarios($params);
                $contador = count($listaComentarios);
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'N'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $params['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::COMENTARIO_ADICONADO_SUCCESS;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            $response = new stdClass();
            $response->success = $success;
            $response->msg = array(
                'text' => $msg,
                'qtdComentario' => $contador,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
            $this->_helper->json->sendJson($response);
        }
    }

    public function excluircomentariojsonAction()
    {
        $service = new Projeto_Service_Comentario();
        $success = false;
        if ($this->_request->isPost()) {
            $params = $this->_request->getPost();
            $retorno = $service->excluir($params);
            $contador = 0;
            if ($retorno) {
                $success = true;
                $listaComentarios = $service->listarComentarios($params);
                $contador = count($listaComentarios);
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'E'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $params['idprojeto'];
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::COMENTARIO_EXCLUIDO_SUCCESS;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            $response = new stdClass();
            $response->success = $success;
            $response->msg = array(
                'text' => $msg,
                'qtdComentario' => $contador,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
            $this->_helper->json->sendJson($response);
        }
    }

    public function gerarPdfAction()
    {
        set_time_limit(0);
        ini_set('memory_limit', '1024M');

        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $dados = $this->_request->getParams();
        $this->view->parametros = $dados;

        $parametros = array(
            "idprograma" => trim($dados['idprograma']),
            "nomprojeto" => trim($dados['nomprojeto']),
            "idescritorio" => trim($dados['idescritorio']),
            "domstatusprojeto" => trim($dados['domstatusprojeto']),
            "idresponsavel" => trim($dados['idresponsavel']),
            "idelementodespesa" => trim($dados['idelementodespesa']),
            "statusatividade" => trim($dados['statusatividade']),
            "inicial_dti" => trim($dados['inicial_dti']),
            "inicial_dtf" => trim($dados['inicial_dtf']),
            "final_dti" => trim($dados['final_dti']),
            "final_dtf" => trim($dados['final_dtf']),
            "tipogrupo" => trim($dados['tipogrupo']),
            "tipoentrega" => trim($dados['tipoentrega']),
            "tipoatividade" => trim($dados['tipoatividade']),
            "tipomarco" => trim($dados['tipomarco']),
            "nomprograma" => trim($dados['nomprograma']),
            "nomescritorio" => trim($dados['nomescritorio']),
            "statusprojeto" => trim($dados['statusprojeto']),
            "projetos" => trim($dados['projetos']),
            "nomstatusatividade" => trim($dados['nomstatusatividade']),
            "nomelementodespesa" => trim($dados['nomelementodespesa']),
            "nomresponsavel" => trim($dados['nomresponsavel']),
            "marco" => trim($dados['marco']),
            "atividade" => trim($dados['atividade']),
            "entrega" => trim($dados['entrega']),
            "grupo" => trim($dados['grupo']),
        );

        $params = array_filter($parametros);
        $resultado = $serviceCronograma->retornaCronogramaProjetos($params);

        $this->view->resultado = $resultado['projetos'];
        $this->view->custoTodosProjetos = $resultado['custoTodosProjetos'];
        $this->view->qtdeRegistros = $resultado['qtdeRegistros'];

        $corpo = $this->view->render('/cronograma/gerar-pdf.phtml');

        $this->mpdf = new App_Service_MPDF('UTF-8', 'A4-L', '', '', 15, 15, 15, 25, 10, 15, '');
        $this->mpdf->AddPage('L', '', '', '', '', 15, 15, 15, 20, 15, 15);
        $this->mpdf->setFooter('{DATE j/m/Y} - Pág. {PAGENO}/{nb}');

        $stylesheet = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/examples/mpdfstyletables.css');
        $this->mpdf->WriteHTML($stylesheet, 1);

        $css = file_get_contents(APPLICATION_PATH . '/../library/MPDF57/mpdf.css');
        $this->mpdf->WriteHTML($css, 1);

        $cssBootstrap = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap.min.css');
        $this->mpdf->WriteHTML($cssBootstrap, 1, true);

        $cssBootstrapResp = file_get_contents(APPLICATION_PATH . '/../public/js/library/bootstrap/css/bootstrap-responsive.min.css');
        $this->mpdf->WriteHTML($cssBootstrapResp, 1, true);

        $this->mpdf->WriteHTML($corpo);
        $this->mpdf->Output('document.pdf', 'I');
        exit(0);
    }

    public function gerarCsvAction()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $dados = $this->_request->getParams();
        $this->view->parametros = $dados;

        $parametros = array(
            "idprograma" => trim($dados['idprograma']),
            "nomprojeto" => trim($dados['nomprojeto']),
            "idescritorio" => trim($dados['idescritorio']),
            "domstatusprojeto" => trim($dados['domstatusprojeto']),
            "idresponsavel" => trim($dados['idresponsavel']),
            "idelementodespesa" => trim($dados['idelementodespesa']),
            "statusatividade" => trim($dados['statusatividade']),
            "inicial_dti" => trim($dados['inicial_dti']),
            "inicial_dtf" => trim($dados['inicial_dtf']),
            "final_dti" => trim($dados['final_dti']),
            "final_dtf" => trim($dados['final_dtf']),
            "tipogrupo" => trim($dados['tipogrupo']),
            "tipoentrega" => trim($dados['tipoentrega']),
            "tipoatividade" => trim($dados['tipoatividade']),
            "tipomarco" => trim($dados['tipomarco']),
            "nomprograma" => trim($dados['nomprograma']),
            "nomescritorio" => trim($dados['nomescritorio']),
            "statusprojeto" => trim($dados['statusprojeto']),
            "projetos" => trim($dados['projetos']),
            "nomstatusatividade" => trim($dados['nomstatusatividade']),
            "nomelementodespesa" => trim($dados['nomelementodespesa']),
            "nomresponsavel" => trim($dados['nomresponsavel']),
            "marco" => trim($dados['marco']),
            "atividade" => trim($dados['atividade']),
            "entrega" => trim($dados['entrega']),
            "grupo" => trim($dados['grupo']),
        );

        $params = array_filter($parametros);
        $resultado = $serviceCronograma->retornaCronogramaProjetos($params);

        $this->view->resultado = $resultado['projetos'];
        $this->view->custoTodosProjetos = $resultado['custoTodosProjetos'];
        $this->view->qtdeRegistros = $resultado['qtdeRegistros'];

        $corpo = $this->view->render('/cronograma/gerar-csv.phtml');

        $this->dom_html = new App_Service_DomHtml();
        $html = str_get_html($corpo);

        header('Content-type: application/ms-excel');
        header('Content-Disposition: attachment; filename=RelatorioCronograma.csv');

        $fp = fopen("php://output", "w");
        foreach ($html->find('tr') as $element) {
            $td = array();
            foreach ($element->find('td') as $row) {
                $td[] = $row->plaintext;
            }
            fputcsv($fp, $td);
        }
        fclose($fp);
        exit;
    }

}
