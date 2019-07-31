<?php

class Projeto_GanttController extends Zend_Controller_Action
{

    const ano_meses = 1;
    const ano_meses_semanas = 2;
    const ano_meses_dias = 3;
    const ano_meses_semanas_dias = 4;

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('gerargantt', 'json')
            ->addActionContext('atividade', 'json')
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

    public function visualizarAction()
    {
        set_time_limit(180);
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gantt');

        $idprojeto = $this->_request->getParam('idprojeto');
        $tipoexibicao = $this->_request->getParam('tipoexibicao');

        $result = $service->montaDadosCronogramaGantt($this->_request->getParams());
        //$result = $service->montaDadosGantt($request);
        $form = $service->getFormGantt();

        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $comboGrupo = $serviceAtividadeCronograma->fetchPairsGrupo(array('idprojeto' => $idprojeto));
        $arrayGrupo = $serviceGerencia->initCombo($comboGrupo, "Selecione");

        if (is_numeric($this->_request->getParam('idgrupo'))) {
            $comboEntrega = $serviceAtividadeCronograma->fetchPairsEntrega(array(
                'idprojeto' => $idprojeto,
                'idgrupo' => $this->_request->getParam('idgrupo')
            ));
            $arrayEntrega = $serviceGerencia->initCombo($comboEntrega, "Selecione");
        }
        if (is_numeric($this->_request->getParam('identrega'))) {
            $comboAtividade = $serviceAtividadeCronograma->fetchPairsAtividade(array(
                    'idprojeto' => $idprojeto,
                    'identrega' => $this->_request->getParam('identrega')
                )
            );
        }
        if (null !== $tipoexibicao && $tipoexibicao != "3") {
            $form->getElement('tipoexibicao')->setValue($tipoexibicao);
        }
        $form->getElement('idgrupo')->options = $arrayGrupo;
        if (@is_numeric($this->_request->getParam('idgrupo'))) {
            $form->getElement('idgrupo')->setValue($this->_request->getParam('idgrupo'));
        }
        if (@is_numeric($this->_request->getParam('idgrupo'))) {
            $form->getElement('identrega')->options = $arrayEntrega;
        }
        if (@is_numeric($this->_request->getParam('identrega'))) {
            $form->getElement('identrega')->setValue($this->_request->getParam['identrega']);
        }
        if (@is_numeric($this->_request->getParam('idgrupo'))) {
            if (@is_numeric($this->_request->getParam('identrega'))) {
                $form->getElement('idatividadecronograma')->options = $comboAtividade;
                if (@is_numeric($this->_request->getParam('idatividadecronograma'))) {
                    $form->getElement('idatividadecronograma')->setValue($this->_request->getParam('idatividadecronograma'));
                }
            }
        }
        $this->view->form = $form;
        $this->view->idgrupo_cons = $this->_request->getParam('idgrupo');
        $this->view->identrega_cons = $this->_request->getParam('identrega');
        $this->view->idatividadecronograma_cons = $this->_request->getParam('idatividadecronograma');
        $this->view->idatividademarco_cons = $this->_request->getParam('idatividademarco');

        $this->view->idprojeto = $idprojeto;
//        $this->_helper->layout()->setLayout('gantt'); //seta o layout especifico evitando travamento dos scripts js

        if ($result) {
            if ($this->_request->isPost()) {
                $gantt = new App_Gantt_Gantti($result, array(
                    'title' => 'GANTT',
                    'cellwidth' => 40,
                    'cellheight' => 22,
                    'today' => true,
                    'show_header_type' => $tipoexibicao,
                ));
                $this->view->htmlGantt = $gantt;

            } else {
                //exibicao default
                $gantt = new App_Gantt_Gantti($result, array(
                    'title' => 'GANTT',
                    'cellwidth' => 40,
                    'cellheight' => 22,
                    'today' => true,
                    'show_header_type' => self::ano_meses_dias,
                ));
                $this->view->htmlGantt = $gantt;
            }
        } else {
            $this->view->htmlGantt = 'Nenhum resultado encontrado.';
        }
    }

    public function detalharatividadeAction()
    {
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $params = $this->_request->getParams();
        //Zend_Debug::dump($params);exit;
        $success = false;
        if (($params['domatividade'] == "3") || ($params['domatividade'] == "4")) {
            $atividade = $serviceAtividadeCronograma->retornaAtividadePorId($params, true);
            if ($atividade) {
                $form = $serviceAtividadeCronograma->getFormAtividadeAtualizar($params);
                $success = true;
                $msg = App_Service_ServiceAbstract::PUBLICACAO_REALIZADA_SUCESSO;
                if ($serviceAtividadeCronograma->verificarAtividadePredecessoras($atividade)) { //Verifica maior data predecessora
                    $atividade['datinicio'] = $serviceAtividadeCronograma->retornaInicioBaseLinePorAtividade($atividade);
                    $this->view->dataPredecessora = $serviceAtividadeCronograma->retornaInicioBaseLinePorAtividade($atividade);
                    $dataInicio = $serviceAtividadeCronograma->preparaData($atividade['datinicio']);
                    //verificar se os dias reais da atividade é maior que zero
                    if ($atividade['numfolga'] > 0) {
                        //soma os dias reais a data inicio da atividade sucessora
                        $atividade['datinicio'] = $serviceAtividadeCronograma->adicionarDias($atividade['datinicio'],
                            $atividade['numfolga']);
                    } elseif ($atividade['numfolga'] < 0) {
                        $atividade['datinicio'] = $serviceAtividadeCronograma->subtrairDias($atividade['datinicio'],
                            $atividade['numfolga']);
                    } else {
                        $atividade['datinicio'] = $dataInicio->format('d/m/Y');
                    }
                }
                $dataFim = $serviceAtividadeCronograma->preparaData($atividade['datinicio']);
                if ($atividade['numdiasrealizados'] > 0) {
                    $atividade['datfim'] = $serviceAtividadeCronograma->adicionarDias($atividade['datinicio'],
                        $atividade['numdiasrealizados']);
                } elseif ($atividade['numdiasrealizados'] < 0) {
                    $atividade['datfim'] = $serviceAtividadeCronograma->subtrairDias($atividade['datinicio'],
                        $atividade['numdiasrealizados']);
                } else {
                    $atividade['datfim'] = $dataFim->format('d/m/Y');
                }
                $grupoAtividade = "";
                if (trim($atividade['idgrupo'] != "")) {
                    $grupoAtividade = $serviceAtividadeCronograma->retornaEntregaPorId(
                        array('idatividadecronograma' => $atividade['idgrupo'], 'idprojeto' => $atividade['idprojeto'])
                    );
                }
                $atividade['grupo'] = $grupoAtividade;
                $elementoDespesa = array('idelementodespesa' => '', 'nomeelementodespesa' => '');
                if (trim($atividade['idelementodespesa'] != "")) {
                    $serviceElementoDespesa = new Default_Service_ElementoDespesa();
                    $elementoDespesa = $serviceElementoDespesa->getById(array('idelementodespesa' => $atividade['idelementodespesa']));
                }
                $atividade['elementodespesa'] = $elementoDespesa;

                $this->view->atividade = $atividade;
                unset($atividade['predecessoras']);
                $form->populate($atividade);
                $this->view->form = $form;
            } else {
                $success = false;
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }

        } else {
            $success = false;
            $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
        }

        $arrayMsg = array(
            'text' => $msg,
            'type' => ($success) ? 'success' : 'error',
            'hide' => true,
            'closer' => true,
            'sticker' => false
        );
        $response = new stdClass();
        $response->success = $success;
        $response->msg = $arrayMsg;
        $this->view->success = $success;
        $this->view->dados = $response;
        $this->view->msg = $arrayMsg;
    }

    public function detalharentregaAction()
    {
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $params = $this->_request->getParams();
        $success = false;
        if ($params['domatividade'] == "2") {
            $dados = $this->_request->getParams();
            $entrega = $serviceAtividadeCronograma->retornaEntregaPorId($dados, true);
            if ($entrega) {
                $grupoAtividade = "";
                if (trim($entrega['idgrupo'] != "")) {
                    $grupoAtividade = $serviceAtividadeCronograma->getAtividadeByProjetoId(
                        array('idatividadecronograma' => $entrega['idgrupo'], 'idprojeto' => $entrega['idprojeto'])
                    );
                }
                $entrega['grupo'] = $grupoAtividade;
                $form = $serviceAtividadeCronograma->getFormEntrega($this->_request->getParams());
                $success = true;
                $msg = App_Service_ServiceAbstract::PUBLICACAO_REALIZADA_SUCESSO;
                $this->view->entrega = $entrega;
                $form->populate($entrega);
                $this->view->form = $form;
            } else {
                $success = false;
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }
        } else {
            $success = false;
            $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
        }
        $arrayMsg = array(
            'text' => $msg,
            'type' => ($success) ? 'success' : 'error',
            'hide' => true,
            'closer' => true,
            'sticker' => false
        );
        $response = new stdClass();
        $response->success = $success;
        $response->msg = $arrayMsg;
        $this->view->success = $success;
        $this->view->dados = $response;
        $this->view->msg = $arrayMsg;
    }

    public function importarGraficoAction()
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '1024M');
        $params = $this->_request->getParams();
        $hoje = date("d-m-Y-G-i-s");
        $this->_helper->layout->disableLayout();
        if (@$params['formato'] == "jpg") {
            $tipo = "image/jpeg";
            $extensao = ".jpg";
        } elseif ($params['formato'] == "gif") {
            $tipo = "image/gif";
            $extensao = ".gif";
        } else {
            $tipo = "image/png";
            $extensao = ".png";
        }
        $parametros = array(
            'idprojeto' => @$params['idprojeto'],
            'formato' => @$tipo,
            'idgrupo' => @$params['idgrupo'],
            'identrega' => @$params['identrega'],
            'idatividadecronograma' => @$params['idatividadecronograma'],
            'idatividademarco' => @$params['idatividademarco'],
            'chkfiltro' => @$params['chkfiltro'],
        );
        $parametros = array_filter($parametros);
        $serviceGantt = App_Service_ServiceAbstract::getService('Projeto_Service_Gantt');
        $ganttImmagemGrafico = $serviceGantt->getObjetoGraficoGantt($parametros);
        $this->view->graficoGantt = base64_encode($ganttImmagemGrafico);
        header("Content-type: " . $tipo);
        header("Content-Transfer-Encoding: binary");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: public");
        if (@trim($params['tpabertura']) == "1") {
            header("Content-Disposition: attachment; filename=GraficoGantt-" . $hoje . $extensao);
        } else {
            header("Content-disposition: inline; filename=GraficoGantt-" . $hoje . $extensao);
        }

        $this->_helper->layout->disableLayout();
    }

    /**
     * @return Projeto_Service_Gantt
     * @return Projeto_Service_Gerencia
     */
    public function gerarganttAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gantt');
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $success = true;
        $dados = $this->_request->getParams();
        $form = $service->getFormGerarGantt($dados);
        $projeto = $serviceGerencia->getById($dados);
        $dadosForm = array(
            'idprojeto' => $projeto->idprojeto,
            'nomprojeto' => $projeto->nomcodigo . "-" . $projeto->nomprojeto,
            'nomcodigo' => $projeto->nomcodigo,
            'idgrupo' => $this->_request->getParam('idgrupo'),
            'identrega' => $this->_request->getParam('identrega'),
            'idatividadecronograma' => $this->_request->getParam('idatividadecronograma'),
            'idatividademarco' => $this->_request->getParam('idatividademarco'),
        );
        $dadosForm = array_filter($dadosForm);
        $form->populate($dadosForm);
        $this->view->form = $form;
    }

    public function atividadeAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $dados = $this->_request->getParams();
        $parametros = array(
            'idprojeto_pesq' => @$dados['idprojeto'],
            'domtipoatividade_pesq' => (@$dados['domtipoatividade'] != "3" ? @$dados['domtipoatividade'] : ""),
            'idgrupo_pesq' => @$dados['idgrupo'],
            'idgrupopai_pesq' => @$dados['idgrupopai'],
            'idatividadecronograma_pesq' => @$dados['idatividadecronograma'],
            'order_pesq' => 'S',
        );
        $parametros = array_filter($parametros);
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $resultado = $serviceAtividadeCronograma->pesquisar($parametros);

        $this->_helper->json->sendJson($resultado);
    }

}
