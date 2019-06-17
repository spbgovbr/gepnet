<?php

class Projeto_CronogramaController extends Zend_Controller_Action
{

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
            ->addActionContext('atividade-atualizar-percentual', 'json')
            ->addActionContext('atualizar-dom-tipo-atividade', 'json')
            ->addActionContext('excluir-grupo', 'json')
            ->addActionContext('excluir-entrega', 'json')
            ->addActionContext('excluir-atividade', 'json')
            ->addActionContext('clonar-grupo', 'json')
            ->addActionContext('clonar-entrega', 'json')
            ->addActionContext('copiar-cronograma', 'json')
            ->addActionContext('atualizar-baseline-atividade', 'json')
            ->addActionContext('atualizar-baseline', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        $service = new Projeto_Service_Gerencia();
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        /*
          $projeto             = $service->retornaArrayProjetoPorId($this->_request->getParams());
          $this->view->projeto = $projeto;
         */
        $this->view->idprojeto = $this->_request->getParam('idprojeto');

        $this->view->formGrupo = $serviceCronograma->getFormGrupo($this->_request->getParams());
        $this->view->formEntrega = $serviceCronograma->getFormEntrega($this->_request->getParams());
        $this->view->formAtividade = $serviceCronograma->getFormAtividade($this->_request->getParams());
        $this->view->formAtividadeMarco = $serviceCronograma->getFormAtividadeMarco();
        $this->view->formAtividadePesquisar = $serviceCronograma->getFormAtividadePesquisar($this->_request->getParams());
    }

    public function retornaProjetoAction()
    {
        set_time_limit(0);
        $service = new Projeto_Service_Gerencia();
        //Zend_Debug::dump($service->retornaArrayProjetoPorId($this->_request->getParams())); die;
        $this->view->projeto = $service->retornaArrayProjetoPorId($this->_request->getParams());
    }

    public function cadastrarGrupoAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $grupo = $service->inserirGrupo($dados);
            //@todo inserir pessoas interessadas
            if ($grupo) {
                $this->view->item = $grupo;
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        } else {
            $this->view->form = $service->getFormGrupo($this->_request->getParams());
        }
    }

    public function editarGrupoAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $form = $service->getFormGrupo();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $atividade = $service->atualizarGrupo($dados);
            if ($atividade) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $grupo = $service->retornaGrupoPorId($this->_request->getParams());
            $form->populate($grupo);
            $this->view->form = $form;
        }


        if ($this->_request->isPost()) {
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
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function editarEntregaAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $form = $service->getFormEntrega($this->_request->getParams());
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $entrega = $service->atualizarEntrega($dados);
            if ($entrega) {
                $success = true; ###### AUTENTICATION SUCCESS
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $entrega = $service->retornaEntregaPorId($this->_request->getParams(), true);
            $form->populate($entrega);
            $this->view->form = $form;
        }


        if ($this->_request->isPost()) {
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
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function cadastrarEntregaAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $grupo = $service->inserirEntrega($dados);
            //@todo inserir pessoas interessadas
            if ($grupo) {
                $this->view->item = $grupo;
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
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

    public function cadastrarAtividadeAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $dados['numdiasrealizados'] = $dados['numdiasbaseline'];
            $atividade = $service->inserirAtividade($dados);
            //@todo inserir pessoas interessadas
            if ($atividade) {
                $this->view->item = $atividade;
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        } else {
            $this->view->form = $service->getFormAtividade($this->_request->getParams());
        }
    }

    public function retornaInicioBaseLineAction()
    {
        //zend_debug::dump($this->_request->getPost());exit;
        $service = new Projeto_Service_AtividadeCronograma();
        //$resultado = $service->retornaInicioBaseLinePorPredecessoras($this->_request->getPost());
        $resultado = $service->retornaInicioBaseLinePorAtividade($this->_request->getPost());
        //Zend_Debug::dump($resultado);exit;
        $this->_helper->json->sendJson($resultado);
    }

    public function retornaPredecessoraAction()
    {
        //Zend_Debug::dump($this->_request->getPost());exit;
        $service = new Projeto_Service_AtividadeCronograma();
        //$resultado = $service->retornaInicioBaseLinePorPredecessoras($this->_request->getPost());
        $resultado = $service->retornaInicioBaseLinePorAtividade($this->_request->getPost());
        //Zend_Debug::dump($resultado);exit;
        $this->_helper->json->sendJson($resultado);
    }

    public function retornaInicioRealAction()
    {
        //Zend_Debug::dump($this->_request->getPost());exit;
        $service = new Projeto_Service_AtividadeCronograma();
        $resultado = $service->retornaInicioRealPorPredecessoras($this->_request->getPost());
        //Zend_Debug::dump($resultado);exit;
        $this->_helper->json->sendJson($resultado);
    }

    public function adicionarPredecessoraAction()
    {
        //Zend_Debug::dump($this->_request->getPost());exit;
        $service = new Projeto_Service_AtividadePredecessora();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $predecessora = $service->inserir($dados);

            if ($predecessora) {
                $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
                $success = true;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            $response = new stdClass();
            $response->predecessora = $predecessora;
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
    }

    public function atualizarCronogramaAction()
    {
        set_time_limit(0);
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        $dados = $this->_request->getParams();
        $resultado = $service->atualizarCronogramaDoProjeto($dados);

        //Zend_Debug::dump($resultado);exit;
        if ($resultado) {
            $service = new Projeto_Service_Gerencia();
            $this->view->projeto = $service->retornaArrayProjetoPorId($dados);
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


    public function excluirPredecessoraAction()
    {
        $service = new Projeto_Service_AtividadePredecessora();
        $success = false;
        if ($this->_request->isGet()) {
            $dados = $this->_request->getParams();
            //Zend_Debug::dump($dados); die;
            // Retirando os parametros da model, controller e action
            unset($dados['module']);
            unset($dados['controller']);
            unset($dados['action']);
            // Criando o objeto atividade
            $serviceAtividade = new Projeto_Service_AtividadeCronograma();
            // Se param for atividade, entao deleta-se só ativiadade
            if ($dados['params'] == "atividade") {
                unset($dados['idatividadepredecessora']);
                $atividade = $serviceAtividade->excluirAtividade($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
                $this->_helper->json->sendJson($msg);
            }
            // Se o params for predecessora, deleta-se a predecessora
            if ($dados['params'] == "predecessora") {
                $predecessora = $service->excluirPorProjeto($dados);
                unset($dados['idatividadepredecessora']);
                $atividade = $serviceAtividade->excluirAtividade($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
                $this->_helper->json->sendJson($msg);
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }
        }
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $predecessora = $service->excluir($dados);

            if ($predecessora) {
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
                $success = true;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }

            $response = new stdClass();
            $response->predecessora = $predecessora;
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
    }

    public function editarAtividadeAction()
    {
        $serviceAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        $form = $serviceAtividadeCronograma->getFormAtividadeAtualizar($this->_request->getParams());
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            unset($dados['datiniciobaseline']);
            unset($dados['datfimbaseline']);
            $atividade = $serviceAtividadeCronograma->atualizarAtividade($dados);

            if ($atividade) {
                $this->view->item = $atividade;
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $serviceAtividadeCronograma->getErrors();
            }
            $this->view->msg = array(
                'text' => $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        } else {
            $atividade = $serviceAtividadeCronograma->retornaAtividadePorId($this->_request->getParams(), true);
            if ($serviceAtividadeCronograma->verificarAtividadePredecessoras($atividade)) { //Verifica maior data predecessora
                $atividade['datinicio'] = $serviceAtividadeCronograma->retornaInicioBaseLinePorAtividade($atividade);
                $this->view->dataPredecessora = $serviceAtividadeCronograma->retornaInicioBaseLinePorAtividade($atividade);
                //Zend_Debug::dump($atividade);exit;
                $dataInicio = $serviceAtividadeCronograma->preparaData($atividade['datinicio']);
                //verificar se os dias reais da atividade é maior que zero
                if ($atividade['numfolga'] > 0) {
                    //soma os dias reais a data inicio da atividade sucessora
                    $novaDataFim = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->adicionarDias($dataInicio,
                        $atividade['numfolga']));
                    $atividade['datinicio'] = $novaDataFim->format('d/m/Y');
                } elseif ($atividade['numfolga'] < 0) {
                    //Zend_Debug::dump('iniciando calculo numfolga data: '.$atividade['datinicio']);
                    // Zend_Debug::dump('numfolga: '.$atividade['numfolga']);
                    $novaDataFim = $serviceAtividadeCronograma->preparaData($serviceAtividadeCronograma->subtrairDias($dataInicio,
                        $atividade['numfolga']));
                    //Zend_Debug::dump('final calculo data: '.$novaDataFim->format('d/m/Y'));exit;
                    $atividade['datinicio'] = $novaDataFim->format('d/m/Y');
                } else {
                    $atividade['datinicio'] = $dataInicio->format('d/m/Y');
                }
            }
            //Zend_Debug::dump($atividade['datinicio']);exit;
            $this->view->atividade = $atividade;
            unset($atividade['predecessoras']);
            //Zend_Debug::dump($atividade);exit;
            $form->populate($atividade);
            $this->view->form = $form;

        }
    }

    public function atividadeAtualizarPercentualAction()
    {
        $success = false;
        $service = new Projeto_Service_AtividadeCronograma();

        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();

            $atividade = $service->atualizarAtividadePercentual($params);

            if ($atividade) {
                $this->view->item = $atividade;
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
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


    /* public function atividadeAtualizarPercentualAction() {
         $service = new Projeto_Service_AtividadeCronograma();
         $servicePrede = new Projeto_Model_Mapper_Atividadepredecessora();
         $Epredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
         $param = $this->_request->getParams();
         $form = $service->getFormAtividade(array('idatividadecronograma' => $param['idatividadecronograma'], 'idprojeto' => $param['idprojeto']));
         $success = false;

         if ($this->_request->isPost()) {
             $dados = $this->_request->getPost();
             //Zend_Debug::dump($dados);  die;
             $idCronograma = $dados['idatividadecronograma'];
             $idGrupo = $dados['idgrupo'];
             $idProjeto = $dados['idprojeto'];
             $dtInicial = new Zend_Date($dados['datinicio'], 'dd/MM/YYYY');
             $dtFinal = new Zend_Date($dados['datfim'], 'dd/MM/YYYY');

             //$tempoTotal = $dtFinal - $dtInicial;
             $comparar = $dtInicial->isEarlier($dtFinal);
             // Data inicial não pode ser maior que a data final
             if ($dtInicial <= $dtFinal) {
                 // Verica se é predecessora
                 $Predecessora = new Projeto_Service_AtividadePredecessora();
                 $idPredecessora = $Epredecessora->retornaPoriDPredecessora(array('idatividadepredecessora' => $idCronograma, 'idprojeto' => $idProjeto));
                 $idAtividade = '';
                 $idpreSql = '';
                 $contPrede = $idPredecessora['idatividadepredecessora'];
                 // se for predecessora
                 if ($contPrede != 0 || $contPrede != null) {
                     $atividadePre = $contPrede;
                     if ($atividadePre != '') {
                         //Atualizando a alteraçao da predecessora
                         $retornaProjeto = $Epredecessora->retornaPorAtividade(array('idatividadecronograma' => $dados['idatividadecronograma'], 'idprojeto' => $idProjeto));
                         $service = new Projeto_Service_AtividadeCronograma();
                         $atividade = $service->atualizarAtividade($dados);
                         //=============== Efeito cascata ===========================
                         // Pegando as predecessoras por grupo.
                         //Atualização para multiplas predecessoras
                         // Todas as predecessoras do projeto
                         $Atividade = new Projeto_Service_AtividadeCronograma();
                         $mostrAtividad = $Atividade->retornaAtividadePorProjeto(array('idprojeto' => $idProjeto));
                         $contarAtivida = count($mostrAtividad);
                         //$countar        = count($retorgaProjeto);
                         $idAtivPred = '';
                         for ($i = 0; $i < $contarAtivida; $i ++) {
                             $idAtivPred = $mostrAtividad[$i]['idatividadecronograma'];
                             $retorgaProjeto = $Epredecessora->retornaPredecePorIdAtividade(array('idatividadecronograma' => $idAtivPred, 'idprojeto' => $idProjeto));
                             foreach ($retorgaProjeto as $retorgaProjetos) {
                                 $idPredecessora = $retorgaProjetos['idatividadepredecessora'];
                                 $retornaPredec = $Epredecessora->retornaDataMaiorPredecessoras(array('idatividadecronograma' => $idPredecessora, 'idprojeto' => $idProjeto));
                                 foreach ($retornaPredec as $retornaPredecs) {
                                     $idatividade = $retorgaProjetos['idatividade'];
                                     $retornaAtividade = $Epredecessora->retoraAtividadePorId($idatividade, $idProjeto);
                                     foreach ($retornaAtividade as $retornaAtividades) {
                                         $idAtividadeCrono = $retornaAtividades['idatividadecronograma'];
                                         $numDiaRealizado = $retornaAtividades['numdiasrealizados'];
                                         $dataInicioAtividade = $retornaAtividades['datinicio'];
                                         $dataFimatividade = $retornaAtividades['datfim'];
                                         $numerodeFolga = $retornaAtividades['numfolga'];
                                         $precentualAtiv = $retornaAtividades['numpercentualconcluido'];
                                         //calculando folga com data fim
                                         $dtFim = $retornaPredecs['datfim'];
                                         //separando a data para o calculo predecessora
                                         $diaF = substr($dtFim, 0, 2);
                                         $mesF = substr($dtFim, 3, 2);
                                         $anoF = substr($dtFim, 6, 4);
                                         //retornando a data fim com o numero de folga calculado
                                         $datIniComFolga = date('Y-m-d', mktime(0, 0, 0, $mesF, $diaF + $numerodeFolga, $anoF));
                                         // Calculando a nova data final para a sucessora
                                         $diaA = substr($datIniComFolga, 8, 2);
                                         $mesA = substr($datIniComFolga, 5, 2);
                                         $anoA = substr($datIniComFolga, 0, 4);
                                         // retornando a data fim com o numero calculado
                                         $dtFimReal = date('Y-m-d', mktime(0, 0, 0, $mesA, $diaA + $numDiaRealizado, $anoA));
                                         $dadosReal = array(
                                             'datinicio' => $datIniComFolga,
                                             'datfim' => $dtFimReal,
                                             'numpercentualconcluido' => $precentualAtiv
                                         );

                                         $atualizarMultPrede = new Default_Model_DbTable_Atividadecronogramas();
                                         $where = $atualizarMultPrede->getAdapter()->quoteInto('idatividadecronograma = ?', $idAtividadeCrono);
                                         //Zend_Debug::dump('Id atividade '. $idAtividadeCrono.' Data fim predec '.$dtFim. ' Data inicio atividades '.$retornaAtivi['datinicio'] .' Numero de folga '. $numerodeFolga. ' Data inicio calculada com a folga '.$datIniComFolga. ' Numero de dias '.$numDiaRealizado. ' Data final calculada '.$dtFimReal );
                                         $update = $atualizarMultPrede->update($dadosReal, $where);
                                         //}
                                     }
                                 }
                             }
                         }
                         if ($update) {
                             $this->view->item = $update;
                             $success = true;
                             $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
                         } else {
                             $msg = $service->getErrors();
                         }
                         //===============//Efeito cascata===========================
                     }
                 }
                 if ($contPrede <= 0) {
                     // Se verdadeiro, atualiza a data.
                     if ($dtInicial <= $dtFinal) {
                         $atividade = $service->atividadeAtualizarPercentual($dados);
                         if ($atividade) {
                             $this->view->item = $atividade;
                             $success = true;
                             $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
                         } else {
                             $msg = $service->getErrors();
                         }
                     }
                 }
             }
         } else {
             $success = false;
             $msg = App_Service_ServiceAbstract::DATA_INICIO_MAIOR_DATA_FIM;
         }
         $this->view->msg = array(
             'text' => $msg,
             'type' => ($success) ? 'success' : 'error',
             'hide' => true,
             'closer' => true,
             'sticker' => false
         );
     }*/

    public function atualizarDomTipoAtividadeAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            //$dados['domtipoatividade'] = Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO;
            $atividade = $service->atualizarTipoAtividade($dados);
            //@todo inserir pessoas interessadas
            if ($atividade) {
                $this->view->item = $atividade;
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
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

    public function excluirGrupoAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $idProjeto = $dados['idprojeto'];
            // *********Excluindo as predecessoras por grupo****************//
            // Selecionando as atividades por projeto
            $serviceAtividade = new Projeto_Service_AtividadeCronograma();
            $serviceIdGrupo = $serviceAtividade->retornaIdEntregaPorGrupo(array(
                'idprojeto' => $idProjeto,
                'idgrupo' => $dados['idatividadecronograma']
            ));
            $idgrupoEntrega = '';
            foreach ($serviceIdGrupo as $serviceIdGrupos) {
                $idgrupoEntrega .= $serviceIdGrupos['idatividadecronograma'] . ',';
            }
            $idgrupoEntrega = substr($idgrupoEntrega, 0, strlen($idgrupoEntrega) - 1);
            $serviceIdEntrega = $serviceAtividade->retornaIdAtividadePorEntrega($idProjeto, $idgrupoEntrega);
            // Criando uma variavel que receberá todos os ids da atividade

            if ($serviceIdEntrega != '') {
                $idatividade = '';
                foreach ($serviceIdEntrega as $serviceIdEntregas) {
                    $idatividade .= $serviceIdEntregas['idatividadecronograma'] . ',';
                }
                $idatividade = substr($idatividade, 0, strlen($idatividade) - 1);
                // Retornando todas as predecessoras por id ativiadade 
                $Epredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
                $retornaPredPorIdAtivi = $Epredecessora->retornaTodasPredecessorasPorIdAtividade($idProjeto,
                    $idatividade);
                // Deletando todas as predecessoras do grupo   
                if ($retornaPredPorIdAtivi != '') {
                    $servicePredecessora = new Projeto_Service_AtividadePredecessora();
                    foreach ($retornaPredPorIdAtivi as $retornaPredPorIdAtividade) {
                        $idPredecessora = $retornaPredPorIdAtividade['idatividadepredecessora'];
                        $params = array();
                        $params['idatividadepredecessora'] = $idPredecessora;
                        $params['idprojeto'] = $idProjeto;
                        $predecessora = $servicePredecessora->excluirPorProjeto($params);
                    }
                }
            }
            // *****Fim Excluindo as predecessoras por grupo****************//
            // Excluindo o grupo  
            $excluirGrupo = $service->excluir($dados);
            if ($excluirGrupo) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $grupo = $service->retornaGrupoPorId($this->_request->getParams(), false, true);
            $this->view->grupo = $grupo;
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
            $idProjeto = $dados['idprojeto'];
            $idgrupoEntrega = $dados['idatividadecronograma'];
            // *********Excluindo as predecessoras por grupo****************//
            // Selecionando as atividades por projeto
            $serviceAtividade = new Projeto_Service_AtividadeCronograma();
            $serviceIdEntrega = $serviceAtividade->retornaIdAtividadePorEntrega($idProjeto, $idgrupoEntrega);
            // Criando uma variavel que receberá todos os ids da atividade
            $idatividade = '';
            foreach ($serviceIdEntrega as $serviceIdEntregas) {
                $idatividade .= $serviceIdEntregas['idatividadecronograma'] . ',';
            }
            $idatividade = substr($idatividade, 0, strlen($idatividade) - 1);
            // Retornando todas as predecessoras por id ativiadade 
            $Epredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
            $retornaPredPorIdAtivi = $Epredecessora->retornaTodasPredecessorasPorIdAtividade($idProjeto, $idatividade);
            // Deletando todas as predecessoras do grupo   
            if ($retornaPredPorIdAtivi != '') {
                $servicePredecessora = new Projeto_Service_AtividadePredecessora();
                foreach ($retornaPredPorIdAtivi as $retornaPredPorIdAtividade) {
                    $idPredecessora = $retornaPredPorIdAtividade['idatividadepredecessora'];
                    $params = array();
                    $params['idatividadepredecessora'] = $idPredecessora;
                    $params['idprojeto'] = $idProjeto;
                    $predecessora = $servicePredecessora->excluirPorProjeto($params);
                }
            }
            // *****Fim Excluindo as predecessoras por grupo****************//
            // Excluindo a entrega
            $excluirEntrega = $service->excluirEntrega($dados);
            if ($excluirEntrega) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_EXCLUIDO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
        } else {
            $entrega = $service->retornaEntregaPorId($this->_request->getParams(), false, true);
            $this->view->entrega = $entrega;
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
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function excluirAtividadeAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            // Zend_Debug::dump($dados);die;
            $idatividade = $dados['idatividadecronograma'];
            $idProjeto = $dados['idprojeto'];
            $Epredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
            // Retorna as predecessora por atividade
            $retornaPred = $Epredecessora->retornaPredecePorIdAtividade(array(
                'idatividadecronograma' => $idatividade,
                'idprojeto' => $idProjeto
            ));
            // Retora todas as predecessora por projeto
            $retornaTodasPred = $Epredecessora->retornaPoriDPredecessora(array(
                'idatividadepredecessora' => $idatividade,
                'idprojeto' => $idProjeto
            ));

            $countPrede = count($retornaPred);
            // Se atividade tiver predecessora
            if ($countPrede > 0 || $retornaTodasPred['idatividadepredecessora'] == $idatividade) {
                if ($countPrede > 0) {
                    $success = true;
                    $msg = "ativiPredec";
                    //$this->_helper->json->sendJson($msg);
                } else {
                    if ($retornaTodasPred['idatividadepredecessora'] == $idatividade) {

                        $success = true;
                        $msg = "predecessora";

                        //$this->_helper->json->sendJson($msg);
                    }
                }
            } else {
                if ($retornaTodasPred['idatividadepredecessora'] == '') {
                    $success = true;
                    $msg = 'atividade';
                    //$service->excluirAtividade($dados)
                } else {
                    $msg = $service->getErrors();
                }
            }
        } else {
            $atividade = $service->retornaAtividadePorId($this->_request->getParams(), true);
            $this->view->atividade = $atividade;
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
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function pesquisarAction()
    {
        if ($this->_request->isPost()) {
            $service = App_Service_ServiceAbstract::getService('Projeto_Service_AtividadeCronograma');
            $resultado = $service->pesquisar($this->_request->getPost());
            $this->_helper->json->sendJson($resultado);
        }
    }

    public function clonarEntregaAction()
    {

        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if ($service->clonarEntrega($dados)) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CLONADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        }
    }

    public function clonarGrupoAction()
    {

        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if ($service->clonarGrupo($dados)) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_CLONADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        }
    }

    public function pesquisarprojetojsonAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $paginator = $service->pesquisarProjeto($this->_request->getParams(), true);
        //Zend_Debug::dump($paginator);
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
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $copia = $service->copiarCronograma($dados);
            if ($copia) {
                $msg = App_Service_ServiceAbstract::CRONOGRAMA_COPIADO_COM_SUCESSO .
                    "<br /> Clique <a href=" . $this->getFrontController()->getBaseUrl() . "/projeto/cronograma/index/idprojeto/" . $params['idprojetoorigem'] . ">aqui</a> para voltar ao Projeto de origem. ";
                $success = true;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
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

        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if ($service->atualizarBaselineAtividade($dados)) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }
            $this->view->msg = array(
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        } else {
            $atividade = $service->retornaAtividadePorId($this->_request->getParams(), true);
            $this->view->atividade = $atividade;
        }
    }

    public function atualizarBaselineAction()
    {

        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $service = new Projeto_Service_AtividadeCronograma();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if ($service->atualizarBaseline($dados)) {
                $success = true;
                $msg = App_Service_ServiceAbstract::BASELINE_ATUALIZADA_COM_SUCESSO;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
            }
            $this->view->msg = array(
                'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                'type' => ($success) ? 'success' : 'error',
                'hide' => true,
                'closer' => true,
                'sticker' => false
            );
        }
    }

    public function imprimirAction()
    {

        $service = new Projeto_Service_Gerencia();
        $this->view->idprojeto = $this->_request->getParam('idprojeto');
        $this->view->projeto = $service->retornaArrayProjetoPorId($this->_request->getParams());
    }

    public function imprimirPdfAction()
    {

        $service = new Projeto_Service_Gerencia();
        $this->view->projeto = $service->retornaArrayProjetoPorId($this->_request->getParams());

        $cabecalho = $this->view->render('/_partials/relatorio-cabecalho.phtml');
        $cabecalhoProjeto = $this->view->render('/_partials/projeto-cabecalho.phtml');
        $cronograma = $this->view->render('/_partials/cron-imprimir-pdf.phtml');
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


        $this->mpdf->WriteHTML($cabecalho);
        $this->mpdf->WriteHTML($cabecalhoProjeto);
        $this->mpdf->WriteHTML($cronograma);

        $this->mpdf->Output('CronogramaProjeto.pdf', 'I');
    }

    public function relatorioCronogramaAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $this->view->form = $service->getFormRelatorioCronograma();
    }

    public function resultadoRelatorioCronogramaAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $params = array_filter($this->_request->getParams());
        $this->view->parametros = $params;
        $resultado = $service->retornaCronogramaProjetos($this->_request->getParams());
        $this->view->resultado = $resultado['projetos'];
        $this->view->custoTodosProjetos = $resultado['custoTodosProjetos'];
        $this->view->qtdeRegistros = $resultado['qtdeRegistros'];
    }

    public function buscarprojetosAction()
    {
        $service = new Projeto_Service_AtividadeCronograma();
        $dados = $service->fetchPairsProjetos($this->_request->getParams());
        $this->_helper->json->sendJson($dados);
    }

}
