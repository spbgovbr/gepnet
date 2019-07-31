<?php

class Projeto_Service_Gerencia extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Gerencia
     */
    protected $_mapper;
    protected $_mapperAtividadeCronograma;
    protected $_dependencies = array(
        'db'
    );

    /**
     * @var App_TimeInterval
     */
    protected $_timeInterval = null;

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;
    protected $_estimativaTotalDias = 0;
    protected $_estimativaTotalDiasExecutados = 0;
    protected $_realTotalDias = 0;
    protected $_realTotalDiasExecutados = 0;
    protected $_totalPercentualPrevisto = 0;
    protected $_totalPercentualConcluido = 0;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Projeto_Model_Mapper_Gerencia();
        $this->_timeInterval = new App_TimeInterval();
    }

    /**
     * @return Projeto_Form_Gerencia
     */
    public function getForm()
    {
        $codigo = "Código será gerado automaticamente";
        $form = $this->_getForm('Projeto_Form_Gerencia', array('flaativo'));
        $form->populate(array('nomcodigo' => $codigo));

        return $form;
    }

    /**
     * @return Projeto_Form_ClonarProjeto
     */
    public function getFormClonarProjeto($params)
    {
        $form = $this->_getForm('Projeto_Form_ClonarProjeto');
        $form->populate($params);
        return $form;
    }

    public function retornaUltimoSequencialPorEscritorio($params)
    {
        return $this->_mapper->retornaUltimoSequencialPorEscritorio($params);
    }

    public function atualizaStatusProjeto($params)
    {
        return $this->_mapper->atualizaStatusProjeto($params);
    }

    /**
     * @return Projeto_Form_GerenciaPesquisar
     */
    public function getFormPesquisar()
    {
        return $this->_getForm('Projeto_Form_GerenciaPesquisar');
    }

    /**
     * @return Projeto_Form_Gerencia
     */
    public function getFormEditar()
    {
        $formEditar = $this->_getForm('Projeto_Form_Gerencia');
        return $formEditar;
    }

    /**
     * @return Projeto_Form_Configurar
     */
    public function getFormConfigurar()
    {
        $formConfigurar = $this->_getForm('Projeto_Form_Configurar');
        return $formConfigurar;
    }

    /**
     * @return Projeto_Form_Configurar
     */
    public function getFormListaPermissao()
    {
        $formConfigurar = $this->_getForm('Projeto_Form_ListaPermissao');
        return $formConfigurar;
    }

    /**
     * @return Projeto_Form_Permissao_Editar
     */
    public function getFormPermissaoEditar()
    {
        $formPermissaoEditar = $this->_getForm('Projeto_Form_PermissaoEditar');
        return $formPermissaoEditar;
    }

    /**
     * @return Projeto_Form_Gerencia
     */
    public function getFormInformacoesTecnicas()
    {
        $data = array(
            "nomcodigo",
            "nomsigla",
            "nomprojeto",
            "idsetor",
            "idgerenteprojeto",
            "idgerenteadjunto",
            "datinicio",
            "datfim",
            "numperiodicidadeatualizacao",
            "numcriteriofarol",
            "idcadastrador",
            "datcadastro",
            "domtipoprojeto",
            "flapublicado",
            "domstatusprojeto",
            "flaaprovado",
            "desresultadosobtidos",
            "despontosfortes",
            "despontosfracos",
            "dessugestoes",
            "idescritorio",
            "flaaltagestao",
            "idobjetivo",
            "idacao",
            "flacopa",
            "idnatureza",
            "vlrorcamentodisponivel",
            "iddemandante",
            "idpatrocinador",
            "datinicioplano",
            "datfimplano",
            "desescopo",
            "desnaoescopo",
            "despremissa",
            "desrestricao",
            "numseqprojeto",
            "numanoprojeto",
            "desconsideracaofinal",
            "idprograma",
            "nomproponente",
            "numseqprojeto",
        );
        $formEditar = $this->_getForm('Projeto_Form_Gerencia', $data);
        return $formEditar;
    }

    /**
     * @return Projeto_Form_Gerencia
     */
    public function getFormResumoDoProjeto()
    {
        $data = array(
            "nomcodigo",
            "nomsigla",
            "nomprojeto",
            "idsetor",
            "idgerenteprojeto",
            "idgerenteadjunto",
            "desprojeto",
            "desobjetivo",
            "datinicio",
            "datfim",
            "numperiodicidadeatualizacao",
            "numcriteriofarol",
            "idcadastrador",
            "datcadastro",
            "domtipoprojeto",
            "flapublicado",
            "domstatusprojeto",
            "flaaprovado",
            "desresultadosobtidos",
            "despontosfortes",
            "despontosfracos",
            "dessugestoes",
            "idescritorio",
            "flaaltagestao",
            "idobjetivo",
            "idacao",
            "flacopa",
            "idnatureza",
            "vlrorcamentodisponivel",
            "desjustificativa",
            "iddemandante",
            "idpatrocinador",
            "datfimplano",
            "numseqprojeto",
            "numanoprojeto",
            "desconsideracaofinal",
            "idprograma",
            "nomproponente",
            "numseqprojeto",
            "datinicioplano",
            "idtipoiniciativa",
        );
        $formEditar = $this->_getForm('Projeto_Form_Gerencia', $data);
        return $formEditar;
    }

    /**
     * @return Projeto_Form_Gerencia
     */
    public function getFormPartesInteressadas($data)
    {
        $formEditar = $this->_getForm('Projeto_Form_Gerencia', $data);
        return $formEditar;
    }

    //put your code here
    public function inserir($dados)
    {
        $form = $this->getForm();

        if ($form->isValid($dados)) {
            $model = new Projeto_Model_Gerencia($form->getValues());
            $auth = Zend_Auth::getInstance();
            if ($auth->hasIdentity()) {
                $escritorio = $auth->getIdentity()->perfilAtivo->nomescritorio;
                $model->idcadastrador = $auth->getIdentity()->idpessoa;
            }
            $sequencial = $this->retornaUltimoSequencialPorEscritorio(array(
                'ano' => $model->ano,
                'escritorio' => $escritorio
            ));
            if ($sequencial == false) {
                $sequencial = '001';
            } else {
                $sequencial++;
                $sequencial = str_pad($sequencial, 3, "0", STR_PAD_LEFT);
            }

            $model->nomcodigo = $sequencial . "/" . $model->ano . "/" . $escritorio;

            if ($dados['flapublicado'] == 'S') {
                $model->domstatusprojeto = 2;
            }
            return $this->_mapper->insert($model);
        } else {
            $this->errors = $form->getMessages();
            Zend_Debug::dump($form->getMessages());
        }
        return false;
    }

    /**
     * @return Projeto_Form_Gerencia
     */
    public function update($dados)
    {
        $form = $this->getFormResumoDoProjeto();
        if ($form->getElement('vlrorcamentodisponivel')) {
            $form->getElement('vlrorcamentodisponivel')->addFilter('Digits');
        }
        $dados = array_filter($dados);
        if (isset($dados['desjustificativa'])) {
            $dados['desjustificativa'] = trim($dados['desjustificativa']);
            $dados['desjustificativa'] = substr($dados['desjustificativa'], 0, 4000);
        }
        if (isset($dados['desprojeto'])) {
            $dados['desprojeto'] = trim($dados['desprojeto']);
            $dados['desprojeto'] = substr($dados['desprojeto'], 0, 4000);
        }
        if (isset($dados['desobjetivo'])) {
            $dados['desobjetivo'] = trim($dados['desobjetivo']);
            $dados['desobjetivo'] = substr($dados['desobjetivo'], 0, 4000);
        }
        if (isset($dados['desescopo'])) {
            $dados['desescopo'] = trim($dados['desescopo']);
            $dados['desescopo'] = substr($dados['desescopo'], 0, 4000);
        }
        if (isset($dados['desnaoescopo'])) {
            $dados['desnaoescopo'] = trim($dados['desnaoescopo']);
            $dados['desnaoescopo'] = substr($dados['desnaoescopo'], 0, 4000);
        }
        if (isset($dados['despremissa'])) {
            $dados['despremissa'] = trim($dados['despremissa']);
            $dados['despremissa'] = substr($dados['despremissa'], 0, 4000);
        }
        if (isset($dados['desrestricao'])) {
            $dados['desrestricao'] = trim($dados['desrestricao']);
            $dados['desrestricao'] = substr($dados['desrestricao'], 0, 4000);
        }
        if ($form->isValidPartial($dados)) {
            $values = array_filter($form->getValues());
            $model = new Projeto_Model_Gerencia($values);
            //$model->domtipoprojeto = "Normal";
            // Retirado em 04-10-2016 - Solicitado pelo Palmeira - Redmine 3391
            //if ($dados['flapublicado'] == 'S') {
            //    $model->domstatusprojeto = 2;
            //}
            $mapperStatusreport = new Projeto_Model_Mapper_Statusreport();
            $ultimoStatusReportProjeto = $mapperStatusreport->retornaUltimoPorProjeto(array('idprojeto' => $dados['idprojeto']));
            if ($ultimoStatusReportProjeto) {
                $model->domstatusprojeto = $ultimoStatusReportProjeto->domstatusprojeto;
            }
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    /**
     *
     * @param array $dados
     */
    public function excluir($dados)
    {
        try {
            //$model = new Default_Model_Gerencia($dados);
            return $this->_mapper->excluir($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function clonarProjeto($dados)
    {
        $idcadastrador = null;
        $ano = date('Y');
        /******************************************************************/
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $idcadastrador = $auth->getIdentity()->idpessoa;
            $escritorio = $auth->getIdentity()->perfilAtivo->nomescritorio;
        }
        $sequencial = $this->retornaUltimoSequencialPorEscritorio(array('ano' => $ano, 'escritorio' => $escritorio));
        if ($sequencial == false) {
            $sequencial = '001';
        } else {
            $sequencial++;
            $sequencial = str_pad($sequencial, 3, "0", STR_PAD_LEFT);
        }

        $nomcodigo = $sequencial . "/" . $ano . "/" . $escritorio;
        $nomsigla = null;
        $nomeprojeto = substr($dados['nomprojeto'], 0, 80) . " (Plojeto Clonado)";
        /******************************************************************/
        $db = $this->_db;
        $db->beginTransaction();
        try {
            /******************** INSERE PROJETO   ************************/
            $projeto = $this->_mapper->retornaProjetoPorId($dados);

            $ProjetoArray = array(
                "nomcodigo" => $nomcodigo,
                "nomsigla" => $nomsigla,
                "nomprojeto" => $nomeprojeto,
                "idsetor" => $projeto->idsetor,
                "idgerenteprojeto" => $projeto->idgerenteprojeto,
                "idgerenteadjunto" => $projeto->idgerenteadjunto,
                "desprojeto" => $projeto->desprojeto,
                "desobjetivo" => $projeto->desobjetivo,
                "desjustificativa" => $projeto->desjustificativa,
                "datinicio" => new Zend_Db_Expr("to_date('" . $projeto->datinicio->toString('d-m-Y') . "','DD/MM/YYYY')"),
                "datfim" => new Zend_Db_Expr("to_date('" . $projeto->datfim->toString('d-m-Y') . "','DD/MM/YYYY')"),
                "numperiodicidadeatualizacao" => $projeto->numperiodicidadeatualizacao,
                "numcriteriofarol" => $projeto->numcriteriofarol,
                "idcadastrador" => $idcadastrador,
                "datcadastro" => new Zend_Db_Expr('now()'),
                "domtipoprojeto" => $projeto->domtipoprojeto,
                "flapublicado" => $projeto->flapublicado,
                "domstatusprojeto" => 1,
                "flaaprovado" => $projeto->flaaprovado,
                "desresultadosobtidos" => $projeto->desresultadosobtidos,
                "despontosfortes" => $projeto->despontosfortes,
                "despontosfracos" => $projeto->despontosfracos,
                "dessugestoes" => $projeto->dessugestoes,
                "idescritorio" => $dados['idescritorio'],
                "flaaltagestao" => $projeto->flaaltagestao,
                "idobjetivo" => $projeto->idobjetivo,
                "idacao" => $projeto->idacao,
                "flacopa" => $projeto->flacopa,
                "idnatureza" => $projeto->idnatureza,
                "vlrorcamentodisponivel" => $projeto->vlrorcamentodisponivel,
                "iddemandante" => $projeto->iddemandante,
                "idpatrocinador" => $projeto->idpatrocinador,
                "datinicioplano" => new Zend_Db_Expr("to_date('" . $projeto->datinicioplano->toString('d-m-Y') . "','DD/MM/YYYY')"),
                "datfimplano" => new Zend_Db_Expr("to_date('" . $projeto->datfimplano->toString('d-m-Y') . "','DD/MM/YYYY')"),
                "desescopo" => $projeto->desescopo,
                "desnaoescopo" => $projeto->desnaoescopo,
                "despremissa" => $projeto->despremissa,
                "desrestricao" => $projeto->desrestricao,
                "numseqprojeto" => $projeto->numseqprojeto,
                "numanoprojeto" => $projeto->numanoprojeto,
                "desconsideracaofinal" => $projeto->desconsideracaofinal,
                "idprograma" => $projeto->idprograma,
                "nomproponente" => $projeto->nomproponente,
                "iddemandante" => $projeto->iddemandante,
                "nomdemandante" => $projeto->demandante->idpessoa,
                "idpatrocinador" => $projeto->idpatrocinador,
                "nompatrocinador" => $projeto->nompatrocinador,
                "nomgerenteprojeto" => $projeto->nomgerenteprojeto,
                "nomgerenteadjunto" => $projeto->nomgerenteadjunto,
                "numseqprojeto" => $projeto->numanoprojeto,
                "ano" => $dados['ano'],
                "idacao" => $projeto->idacao,
                "idportfolio" => $projeto->idportfolio,
                "idtipoiniciativa" => $projeto->idtipoiniciativa
            );// Informações Iniciais, Partes Interessadas, Cronograma, Comunicacao, Risco e R3G;
            $projetoNovo = $this->inserir($ProjetoArray);
            if ($projetoNovo) {
                ############## CRIA O STATUS REPORT INICIAL #####################
                $statusReport = array();
                $statusReport['domstatusprojeto'] = 1;
                $statusReport['datacompanhamento'] = date("Y-m-d");
                $statusReport['datmarcotendencia'] = date("Y-m-d");
                $statusReport['datcadastro'] = date("Y-m-d");
                $statusReport['dataaprovação'] = date("Y-m-d");
                $statusReport['numpercentualprevisto'] = 0.00;
                $statusReport['numpercentualconcluido'] = 0.00;
                $statusReport['idmarco'] = 1;
                $statusReport['idcadastrador'] = $idcadastrador;
                $statusReport['datfimprojetotendencia'] = date("Y-m-d");
                $statusReport['domcorrisco'] = 1;
                $statusReport['desatividadeconcluida'] = "Projeto sem acompanhamento cadastrado.";
                $statusReport['desatividadeandamento'] = "Projeto sem acompanhamento cadastrado.";
                $statusReport['desmotivoatraso'] = "Projeto sem acompanhamento cadastrado.";
                $statusReport['descontramedida'] = "Projeto sem acompanhamento cadastrado.";
                $statusReport['desirregularidade'] = "Projeto sem acompanhamento cadastrado.";
                $statusReport['desrisco'] = "Projeto sem acompanhamento cadastrado.";
                $statusReport['descaminho'] = "Projeto sem acompanhamento cadastrado.";
                $statusReport['flaaprovado'] = 2;
                $statusReport['idprojeto'] = $projetoNovo->idprojeto;
                $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
                $StatusReportinserir = $serviceStatusReport->inserirStatusProjeto($statusReport);
                ############## COPIA DADOS DO PROJETO #####################
                $servicoParteInteressada = new Projeto_Service_ParteInteressada();
                $copiaParteInteressada = $servicoParteInteressada->copiaPartesInteressadasByProjeto(
                    array('idprojeto' => $dados['idprojeto'], 'idprojetoNovo' => $projetoNovo->idprojeto)
                );
                $servicoAtividadecronograma = new Projeto_Service_AtividadeCronograma();
                $copiaAtividadecronograma = $servicoAtividadecronograma->clonarCronograma(
                    array('idprojeto' => $dados['idprojeto'], 'idprojetoorigem' => $projetoNovo->idprojeto)
                );
                $servicoComunicacao = new Projeto_Service_Comunicacao();
                $copiaComunicacao = $servicoComunicacao->copiaComunicacaoByProjeto(
                    array('idprojeto' => $dados['idprojeto'], 'idprojetoNovo' => $projetoNovo->idprojeto)
                );
                $servicoRisco = new Projeto_Service_Risco();
                $copiaRisco = $servicoRisco->copiaRiscoByProjeto(
                    array('idprojeto' => $dados['idprojeto'], 'idprojetoNovo' => $projetoNovo->idprojeto)
                );
                $servicoR3g = new Projeto_Service_R3g();
                $copiaR3g = $servicoR3g->copiaR3gByProjeto(
                    array('idprojeto' => $dados['idprojeto'], 'idprojetoNovo' => $projetoNovo->idprojeto)
                );
                ############## FIM - DADOS DO PROJETO #################
                $db->commit();
                return true;
            } else {
                return false;
            }
            /*************************************************************/
        } catch (Exception $exc) {
            $db->rollBack();
            $this->errors[] = $exc->getMessage();
            Zend_Debug::dump($exc);
            return false;
        }
    }


    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    // Retorna o ultimo idprojeto
    public function retornaUltimoIdProjeto()
    {
        return $this->_mapper->retornaUltimoIdProjeto();
    }

    public function retornaProjetoPorId($params)
    {
        return $this->_mapper->retornaProjetoPorId($params);
    }

    public function retornaProjetoCronogramaPorId($params)
    {
        return $this->_mapper->retornaProjetoCronogramaPorId($params);
    }

    public function retornaArrayProjetoPorId($params)
    {
        try {
            $projeto = $this->_mapper->retornaProjetoPorId($params);
            # chamada anterior  - 03-10-2017
            //$projeto = $this->_mapper->retornaProjetoPorId($params);
        } catch (Exception $exc) {
            var_dump($exc);
        }

        $projetoArray = $projeto->toArray();
        $servicoAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        if (isset($projeto->grupos) && !empty($projeto->grupos)) {
            $contAtiv = 0;
            $contEnt = 0;
            foreach ($projeto->grupos as $g => $grupo) {
                /* @var $grupo Projeto_Model_Grupocronograma */
                $gr = $grupo->toArray();
                $idEntregas = $grupo->entregas;
                if (count($grupo->entregas) > 0) {
                    foreach ($grupo->entregas as $e => $entrega) {
                        /* @var $entrega Projeto_Model_Entregacronograma */
                        $en = $entrega->toArray();
                        $idAtividades = $entrega->atividades;
                        foreach ($entrega->atividades as $atividade) {
                            $percentuais = $atividade->retornarDiasEstimadosEReais();
                            $prazo = $atividade->retornaPrazo($atividade->numcriteriofarol);
                            $at = $atividade->toArray();
                            $at['descricaoprazo'] = $prazo->descricao;
                            $at['prazo'] = $prazo->dias;
                            $en['atividades'][] = $at;
                            $contAtiv++;
                        }
                        $percentuais = $entrega->retornaPercentuais();
                        if (!empty($en['datfim'])) {
                            $prazoEn = $entrega->retornaPrazo($projeto->numcriteriofarol);
                            $en['descricaoprazo'] = $prazoEn->descricao;
                            $en['prazo'] = $prazoEn->dias;
                        }
                        $en['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                        $en['numpercentualconcluido'] = $percentuais->numpercentualconcluido;
                        $gr['entregas'][$e] = $en;
                        $contEnt++;
                    }
                }
                $percentuais = $grupo->retornaPercentuais();
                $gr['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                $gr['numpercentualconcluido'] = $percentuais->numpercentualconcluido;
                $projetoArray['grupos'][$g] = $gr;
            }
            if (isset($projetoArray['grupos']) && ($projetoArray['grupos'] != null)) {
                $projetoArray['contGrupo'] = count($projetoArray['grupos']);
                $projetoArray['contEntrega'] = $contEnt;
                $projetoArray['contAtividade'] = $contAtiv;
            } else {
                $projetoArray['contGrupo'] = 0;
                $projetoArray['contEntrega'] = 0;
                $projetoArray['contAtividade'] = 0;
            }

        }
        $projetoArray['ultimoStatusReport']['datfimprojetotendencia'] = date($projetoArray['ultimoStatusReport']['datfimprojetotendencia']);
        return $projetoArray;
    }

    public function retornaArrayCronogramaProjetoPorId($params)
    {
        try {
            $projeto = $this->_mapper->retornaProjetoCronogramaPorId($params);
            # chamada anterior  - 03-10-2017
            //$projeto = $this->_mapper->retornaProjetoPorId($params);
        } catch (Exception $exc) {
            var_dump($exc);
        }
        $projetoArray = $projeto->toArray();
        $servicoAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        if (isset($projeto->grupos) && !empty($projeto->grupos)) {
            $contAtiv = 0;
            $contEnt = 0;
            foreach ($projeto->grupos as $g => $grupo) {
                /* @var $grupo Projeto_Model_Grupocronograma */
                $gr = $grupo->toArray();
                $idEntregas = $grupo->entregas;
                if (count($grupo->entregas) > 0) {
                    $totalPercentualEntregas = 0;
                    foreach ($grupo->entregas as $e => $entrega) {
                        /* @var $entrega Projeto_Model_Entregacronograma */
                        $en = $entrega->toArray();
                        $idAtividades = $entrega->atividades;
                        $totalPercentualAtividade = 0;
                        foreach ($entrega->atividades as $atividade) {
                            $percentuais = $atividade->retornarDiasEstimadosEReais();
                            $prazo = $atividade->retornaPrazo($atividade->numcriteriofarol);
                            $at = $atividade->toArray();
                            $totalPercentualAtividade = $totalPercentualAtividade + $at['numpercentualconcluido'];
                            $at['descricaoprazo'] = $prazo->descricao;
                            $at['prazo'] = $prazo->dias;
                            $en['atividades'][] = $at;
                            $contAtiv++;
                        }
                        $percentuais = $entrega->retornaPercentuais();
                        if (!empty($en['datfim'])) {
                            $prazoEn = $entrega->retornaPrazo($projeto->numcriteriofarol);
                            $en['descricaoprazo'] = $prazoEn->descricao;
                            $en['prazo'] = $prazoEn->dias;
                        }
                        $en['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                        if (@$totalPercentualAtividade > 0) {
                            $en['numpercentualconcluido'] = (@trim($percentuais->numpercentualconcluido) == "" ? "0.00" : $percentuais->numpercentualconcluido);
                        } else {
                            $en['numpercentualconcluido'] = 0;
                        }
                        $totalPercentualEntregas = $en['numpercentualconcluido'] + $totalPercentualEntregas;
                        $gr['entregas'][$e] = $en;
                        $contEnt++;
                    }
                }
                $percentuais = $grupo->retornaPercentuais();
                $gr['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                if (@$totalPercentualEntregas > 0) {
                    $gr['numpercentualconcluido'] = (@trim($percentuais->numpercentualconcluido) == "" ? "0.00" : $percentuais->numpercentualconcluido);
                } else {
                    $gr['numpercentualconcluido'] = 0;
                }
                $projetoArray['grupos'][$g] = $gr;
            }
            if (isset($projetoArray['grupos']) && ($projetoArray['grupos'] != null)) {
                $projetoArray['contGrupo'] = count($projetoArray['grupos']);
                $projetoArray['contEntrega'] = $contEnt;
                $projetoArray['contAtividade'] = $contAtiv;
            } else {
                $projetoArray['contGrupo'] = 0;
                $projetoArray['contEntrega'] = 0;
                $projetoArray['contAtividade'] = 0;
            }
        }
        $datUltimaStatus = date($projetoArray['ultimoStatusReport']['datfimprojetotendencia']);
        $projetoArray['ultimoStatusReport']['datfimprojetotendencia'] = $datUltimaStatus;
        return $projetoArray;
    }

    public function retornaPartes($params, $model = false)
    {
        return $this->_mapper->retornaPartesInteressadas($params, $model);
    }

    public function generateStatusReport($params)
    {
        $projeto = $this->retornaProjetoPorId($params);
        $resultado = $projeto->retornarDiasEstimadosEReais();
        $this->_mapperAtividadeCronograma = new Projeto_Model_Mapper_Atividadecronograma();
        $r = $this->_mapperAtividadeCronograma->retornaMetaDadosPorProjeto($params);
        $ac = $this->_mapperAtividadeCronograma->verificarAtividadesConcluidas(array(
            'idprojeto' => $params['idprojeto']
        ));
        $ae = $this->_mapperAtividadeCronograma->verificarAtividadesEmAndamento(array(
            'idprojeto' => $params['idprojeto'],
            'datainicial' => $projeto->ultimoStatusReport->datacompanhamento,
            'datafinal' => date('d/m/Y'),
        ));

        $r['desatividadeandamento'] = $ae;
        $r['desatividadeconcluida'] = $ac;
        $r['estimativas'] = $resultado;

        if ($resultado->estimativaTotalDias == 0) {
            $numpercentualprevisto = 0;
        } else {
            $numpercentualprevisto = floor(100 * ($resultado->estimativaTotalDiasExecutados / $resultado->estimativaTotalDias));
        }
        if ($resultado->realTotalDias == 0) {
            $numpercentualconcluido = 0;
        } else {
            $numpercentualconcluido = floor(100 * ($resultado->realTotalDiasExecutados / $resultado->realTotalDias));
        }

        $r['estimativas']->numpercentualprevisto = $numpercentualprevisto;
        $r['estimativas']->numpercentualconcluido = $numpercentualconcluido;

        return $r;
    }

    /**
     *
     * @return \stdClass
     */
    /*
      protected function calcularPercentualProjeto()
      {
      $numpercentualprevisto   =   floor( 100 * ($this->_estimativaTotalDiasExecutados / $this->_estimativaTotalDias) );
      $numpercentualconcluido  =   floor( 100 * ($this->_realTotalDiasExecutados / $this->_realTotalDias) );

      $retorno = new stdClass();
      $retorno->numpercentualprevisto = $numpercentualprevisto;
      $retorno->numpercentualconcluido = $numpercentualconcluido;
      return $retorno;
      }
     */

    /**
     *
     * @param array $atividade
     */
    /*
      protected function calcularDiasPrevistosEConcluidosAtividade($atividade)
      {
      $hoje = new DateTime('now');


      $datinicio = DateTime::createFromFormat('d/m/Y', $atividade['datinicio']);
      $datfim = DateTime::createFromFormat('d/m/Y', $atividade['datfim']);

      $diasAtividade = $datfim->diff($datinicio)->dias;
      if ($diasAtividade <= 0){
      $diasAtividade = 1;
      }
      $diasExecutadosAtividade = ($diasAtividade * $atividade['numpercentualconcluido']/100);
      //$totalDiasProjeto += $diasAtividade;
      $this->_realTotalDias += $diasAtividade;
      $this->_realTotalDiasExecutados += $diasExecutadosAtividade;
      // $this->_realTotalPercentual += $diasExecutadosAtividade;




      $datInicioBaseLine = DateTime::createFromFormat('d/m/Y', $atividade['datiniciobaseline']);
      $datFimBaseLine = DateTime::createFromFormat('d/m/Y', $atividade['datfimbaseline']);

      $diasBaseLine = $datFimBaseLine->diff($datInicioBaseLine)->dias;
      if ($diasBaseLine <= 0){
      $diasBaseLine = 1;
      }
      $this->_estimativaTotalDias += $diasBaseLine;
      $diasExecutadosBaseLine = ($diasBaseLine * $atividade['numpercentualconcluido']/100);
      //$totalDiasProjeto += $diasBaseLine;
      // $this->_estimativaTotalDias += $diasBaseLine;

      if ($datFimBaseLine <= $hoje){
      $this->_estimativaTotalDiasExecutados += $diasExecutadosBaseLine;
      //$totalDiasExecutadosBaseLine += $diasExecutadosBaseLine;
      }
      //$totalDiasProjetoBaseLine += $diasbaseline;
      }
     */

    /*
      protected function retornaPercentualPrevistoPorAtividade($dataInicial, $dataFinal, $numPercentualConcluido)
      {
      $datinicio = DateTime::createFromFormat('d/m/Y', $dataInicial);
      $datfim = DateTime::createFromFormat('d/m/Y', $dataFinal);
      $dias = $dataFinal->diff($dataInicial)->dias;

      return ($dias * $numPercentualConcluido / 100);
      }

      protected function retornaDescCorDaLinha($datInicio, $datFim, $percentualConcluido)
      {
      if ($percentualConcluido == 100){
      $sinal = "";
      }elseif ($datFim <= date("Y-m-d")){
      $sinal = "important";
      }elseif ($datInicio >= date("Y-m-d")){
      $sinal = "success";
      }
      return $sinal;
      }
     */
    /*
      protected function retornaPrazoAtividade($dataFim, $dataFimBaseLine, $numcriteriofarol)
      {
      $retorno = new stdClass();
      $dias = $this->_timeInterval->tempoTotal($dataFimBaseLine, $dataFim)->dias;

      if ( $dias >= $numcriteriofarol ) {
      $sinal = "important";
      } elseif ( $dias > 0 ) {
      $sinal = "warning";
      } else {
      $sinal = "success";
      }
      $retorno->descricao = $sinal;
      $retorno->dias = ($dias > 0) ? $dias : $dias * (-1);
      return $retorno;
      }
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function pesquisar($params, $idperfil, $idescritorio, $paginator)
    {
        define('PERIODICIDADE_ATUALIZACAO', 3);

        $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $serviceTipo = new Projeto_Service_SituacaoProjeto();
        if ($this->visualizarProjetosPublicados()) {
            $dados = $this->_mapper->pesquisar($params, $idperfil, $idescritorio, $paginator);
        } else {
            $dados = $this->_mapper->pesquisarProjetosPublicados($params, $idperfil, $idescritorio, $paginator);
        }
        $dadosStatus = $serviceStatusReport->getStatusProjeto();
        if ($paginator) {
            $response = array();
            $response['page'] = $dados->getPages()->current;
            $response['total'] = $dados->getPages()->pageCount;
            $response['records'] = $dados->getPages()->totalItemCount;

            foreach ($dados as $d) {
                $desbloqueio = false;
                $array = array();
                $previsto = "-";
                $concluido = "-";
                $atraso = "-";
                $prazo = "-";
                $risco = "-";
                $ultimoacompanhamento = "-";
                $datfimtendencia = "-";
                $ultimostaus = "-";
                $acompanhamentos = $serviceStatusReport->retornaUltimoAcompanhamento(array('idprojeto' => $d['idprojeto']),
                    false);
                if (!empty($acompanhamentos->idstatusreport)) {

                    if ($acompanhamentos->datfimprojetotendencia) {
                        //Calcula a diferenca de dias para atraso e prazo
                        $datfimprojetotendencia = $d['datfimplano'];
                        $datfimtendencia = $acompanhamentos->datfimprojetotendencia->toString('d/m/Y');
                        $datfim = $d['datfim'];
                        $datinicio = $d['datinicio'];
                        $dias = $this->_timeInterval->tempoTotal($datfim, $datinicio)->dias;
                        $atraso = $this->_timeInterval->tempoTotal($acompanhamentos->datfimprojetotendencia,
                            $datfim)->dias;
                        /* Inverte o sinal conforme Redmine 15429 */
                        $atraso = ($atraso * (-1));
                    }
                    $previsto = $acompanhamentos->numpercentualprevisto . "%";
                    $concluido = $acompanhamentos->numpercentualconcluido . "%";
                    $prazo = $dias;
                    $risco = $acompanhamentos->domcorrisco;

                    //calculo de dias para o acompanhamento.
                    $ultimoacompanhamento = $acompanhamentos->datacompanhamento->toString('d/m/Y');
                    $ultimostatus = $acompanhamentos->domstatusprojeto;

                    $periodicidade = $d['numperiodicidadeatualizacao'] * PERIODICIDADE_ATUALIZACAO;

                    if ($this->calcularAcompanhamento($ultimoacompanhamento, $periodicidade) == true) {
                        //Zend_Debug::dump($this->calcularAcompanhamento($ultimoacompanhamento,$periodicidade));exit;
                        //$this->_mapper->updateStatusProjeto($d);
                        //$this->logBloqueioProjeto($d);
                        //$this->enviaEmailBloqueio($d);
                        // $desbloqueio = true;
                        //Zend_Debug::dump($desbloqueio);exit;
                    }

                }

                // retora a situação do projetoo
                $situacao = $serviceTipo->getById($ultimostatus);
                $d['situacao'] = $situacao;
                $this->permissaoPerfil($d);
                $array['cell'] = array(
                    $d['nomprograma'],
                    $d['nomprojeto'],
                    $d['nomgerenteprojeto'],
                    $d['nomcodigo'],
                    $d['flapublicado'],
                    $d['datinicio'],
                    $d['datfim'],
                    $datfimtendencia,
                    $previsto,
                    $concluido,
                    $atraso,
                    $prazo,
                    $risco,
                    $ultimoacompanhamento,
                    $d['idprojeto'],
                    $d['numcriteriofarol'],
                    $situacao,
                    $d['domstatusprojeto']
                );

                $response["rows"][] = $array;
            }
            return $response;
        }
        return $dados;
    }

    private function logBloqueioProjeto($params)
    {
        //set_time_limit(0);


        $rustart = getrusage();
        $horaInicioExecucao = date('d-m-Y H:i:s');

        $log = "\n" . "\n" . "\n" . "\n" . "\n";
        $log .= '[LOG-BLOQUEIO][' . date('d/m/Y H:i:s') . ']';
        $log .= '[PROJETO: ' . $params['idprojeto'] . ' - ' . $params['nomprojeto'] . ' - INICIADO EM: ' . $params['datinicio'] . ']' . "\r\n";

        /*function rutime($ru, $rus, $index) {
            return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
            -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
        }

        $horaFimExecucao = date('d-m-Y H:i:s');
        $ru = getrusage();
        $log .=   "\n". "\n";
        $log .=  'A execucao do processo usou ' . rutime($ru, $rustart, 'utime') .
            ' ms nas operacoes computacionais.' . "\n";
        $log .=  'Gastou ' . rutime($ru, $rustart, 'stime') .
            ' ms em chamadas de sistema' . "\n";
        $log .= 'Processo com inicio as: ' . $horaInicioExecucao . ' e termino as: ' . $horaFimExecucao;*/

        $filename = 'log_P' . $params['idprojeto'] . '_' . date('Y-m-d') . '.txt';
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . $filename;
        $handle = fopen($path, "a+");
        if ($handle) {
            fwrite($handle, $log);
        }

        return true;
    }

    private function calcularAcompanhamento($acopanhamento, $periodicidade)
    {

        $ultimoacompanhamento = new Zend_Date($acopanhamento, 'dd/MM/YYYY');
        $dt = $ultimoacompanhamento->add($periodicidade, Zend_Date::DAY);
        $part = explode(" ", $dt);
        $datVerificacao = $part[0];
        $dat = new Zend_Date();
        $datAtual = $dat->add('1', zend_date::DAY)->toString('d/m/Y');

        if ($datVerificacao >= $datAtual) {
            return true;
        } else {
            return false;
        }
    }

    public function initCombo($objeto, $msg)
    {

        $listArray = array();
        $listArray = array('' => $msg);

        foreach ($objeto as $val => $desc) {
            if ($desc != $msg) {
                $listArray[$val] = $desc;
            }
        }
        return $listArray;
    }

    public function getByIdTapImprimir($dados)
    {
        return $this->_mapper->getByIdTapImprimir($dados);
    }

    public function getRiscoProjeto()
    {
        return array(
            '1' => 'Baixo',
            '2' => 'Médio',
            '3' => 'Alto',
        );
    }

    public function retornaDescricaoStatusProjeto($status)
    {
        switch ($status) {
            case 1:
                $retorno = 'Proposta';
                break;
            case 2:
                $retorno = 'Em Andamento';
                break;
            case 3:
                $retorno = 'Concluído';
                break;
            case 4:
                $retorno = 'Paralisado';
                break;
            case 5:
                $retorno = 'Cancelado';
                break;
            case 6:
                $retorno = 'Bloqueado';
                break;
            case 7:
                $retorno = 'Em Alteração';
                break;
            case 8:
                $retorno = 'Excluído';
                break;
            Default:
                $retorno = 'Proposta';
                break;
        }
        return $retorno;
    }


    public function rotinaBloqueioProjetos()
    {
        set_time_limit(0);
        $rustart = getrusage();
        $horaInicioExecucao = date('d-m-Y H:i:s');
        $hoje = new Zend_Date();
        define('FATOR_MULTIPLICADOR', 3);

        /**
         * @var $projeto Projeto_Model_Gerencia
         */
        $projetos = $this->retornaTodosOsProjetosEmAndamento();
        $log = "\n" . "\n" . "\n" . "\n" . "\n";
        $log .= '############################################################################################' . "\n";
        $log .= '################### EXECUCAO ROTINA BLOQUEIO ' . date('d/m/Y H:i:s') . ' ###########################' . "\n";
        $log .= '############################################################################################' . "\n";
        $log .= "\n";
        $arrayEmailsBloqueio = array();
        // print "<PRE>";
//        print 'retornaTodosOsProjetos()';
//        var_dump($projetos); exit;


        foreach ($projetos as $p) {

            $datUltimoStatusReport = null;

            $periodicidade = (FATOR_MULTIPLICADOR * $p->numperiodicidadeatualizacao);

            //$data = $p->datcadastro;

            if ($p->ultimoStatusReport->idstatusreport) {
                $dtAcompanhamento = $p->ultimoStatusReport->datacompanhamento;
            }

            $verificador = $this->calcDiferencaDias($dtAcompanhamento, $periodicidade);

            //Zend_Debug::dump($verificador);exit;

            if ($verificador == true) {
                //Altera status do projeto para BLOQUEADO
                $this->alterarStatusProjeto(array('idprojeto' => $p->idprojeto, 'domstatusprojeto' => 6));
                $log .= 'Bloqueado Projeto: ' . $p->idprojeto . ' - ' . $p->nomprojeto . ' as ' . date('d-m-Y H:i:s') . ' via Rotina no servidor' . "\r\n";
                // $arrayEmailsBloqueio[] = $this->retornaObjetoEmail($p);
            }


            /*
               Status do Projeto
                1 - Proposta;
                2 - Em Andamento
                3 - Concluído;
                4 - Paralisado;
                5 - Cancelado;
                6 - Bloqueado;
                7 - Em Alteração

        if($p->idprojeto == 391){
            print '<BR>';
            print 'aqui';
            Zend_Debug::dump($data->get('d/m/Y'));
            Zend_Debug::dump($p->idprojeto);
            Zend_Debug::dump($dtAcompanhamento);
            Zend_Debug::dump($periodicidade);
            print '<BR>';
            exit;
        }*/


        }

        /*foreach($arrayEmailsBloqueio as $a){

            if($this->enviaEmailBloqueio($a)){
               echo 'enviou';
            } else {
               echo 'falhou';
            }
            exit;
        }*/

//        print_r($arrayEmailsBloqueio);

        // Script end
        function rutime($ru, $rus, $index)
        {
            return ($ru["ru_$index.tv_sec"] * 1000 + intval($ru["ru_$index.tv_usec"] / 1000))
                - ($rus["ru_$index.tv_sec"] * 1000 + intval($rus["ru_$index.tv_usec"] / 1000));
        }

        $horaFimExecucao = date('d-m-Y H:i:s');
        $ru = getrusage();
        $log .= "\n" . "\n";
        $log .= 'A execucao do processo usou ' . rutime($ru, $rustart, 'utime') .
            ' ms nas operacoes computacionais.' . "\n";
        $log .= 'Gastou ' . rutime($ru, $rustart, 'stime') .
            ' ms em chamadas de sistema' . "\n";
        $log .= 'Processo com inicio as: ' . $horaInicioExecucao . ' e termino as: ' . $horaFimExecucao;

        $filename = 'bloqueio_projeto_' . date('Y-m-d') . '.txt';
        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR . $filename;
        $handle = fopen($path, "a+");
        if ($handle) {
            fwrite($handle, $log);
        }

        //Zend_Debug::dump($log);exit;
        //echo 'fim';
        //exit;
    }

    private function calcDiferencaDias($dtAcompanhamento, $periodicidade)
    {

        $ultimoacompanhamento = new Zend_Date($dtAcompanhamento->get('Y-m-d'), 'YYYY-MM-dd');
        $dt = $ultimoacompanhamento->add($periodicidade, Zend_Date::DAY);
        $part = explode(" ", $dt);
        $datVerificacao = $part[0];
        $dat = new Zend_Date();
        $datAtual = $dat->add('1', zend_date::DAY)->toString('Y-m-d');

        if ($datVerificacao >= $datAtual) {
            return true;
        } else {
            return false;
        }


        // $o = new stdClass();
        //$datInicial = new Zend_Date($data->get('Y-m-d'),'YYYY-MM-dd');
        //$hoje = new Zend_Date($hoje->get('Y-m-d'),'YYYY-MM-dd');
        //$datInicial->add('1',Zend_Date::DAY);
        // Zend_Debug::dump($this->getDiferencaDias($hoje,$datInicial));exit;
        // $o->diferencaDias = $this->getDiferencaDias($hoje,$datInicial);

        //return $o;
    }

    private function getDiferencaDias($hoje, $data)
    {
        // $data = new Zend_Date($data,'YYYY-MM-dd');
        // print $data->get('d/m/Y'); print '<br>';
        //print $hoje->get('d/m/Y'); print '<br>';exit;
        $diff = $hoje->sub($data)->toValue();
        return ceil($diff / 60 / 60 / 24) + 1;
//        return floor($diff/86400);
    }

    public function retornaTodosOsProjetosEmAndamento()
    {
        return $this->_mapper->retornaTodosOsProjetosEmAndamento();
    }

    public function alterarStatusProjeto($params)
    {
        return $this->_mapper->alterarStatusProjeto($params);
    }

    public function registrarBloqueioProjeto($params)
    {
        return $this->_mapper->registrarBloqueioProjeto($params);
    }

    public function enviaEmailBloqueio($params)
    {
        //set_time_limit(0);
        //Zend_Debug::dump($params['idprojeto']);exit;
        $textoEmailBloqueio = 'O sistema SigNet informa que o projeto ';
        $textoEmailBloqueio .= $params['nomprojeto'];
        $textoEmailBloqueio .= ' foi bloqueado a partir desta data,';
        $textoEmailBloqueio .= ' em virtude de estar com mais de três atualizações periódicas';
        $textoEmailBloqueio .= ' (Relatórios de Situação) em atraso.';

        $smtp = "10.2.2.25";
        $config = array(
            'port' => 25,
            'auth' => 'login',
//                'ssl' => 'tls',
            'email' => 'wflino@stefanini.com',
            'username' => 'wflino',
            'password' => 'linosuporte'
        );#inserir a senha de acesso ao email
        #se a senha nao estiver vazia envia o e-mail
        try {

            $mailTransport = new Zend_Mail_Transport_Smtp($smtp, $config);

            if (!empty($config['password'])) {
                # Define essa configuração para o envio de emails pelo Zend_Mail
                Zend_Mail::setDefaultTransport($mailTransport);

                $mail = new Zend_Mail('utf-8');
                $mail->setBodyHtml($textoEmailBloqueio);
                $mail->setSubject("GEPNET - Bloqueio de Projeto");
                $mail->setFrom("wendell.wlfl@gmail.com", "GEPNET - Gestor de Escritórios de Projetos ");
                //$mail->setFrom("wendell.wlfl@gmail.com", "SigNet - Sistema de Gerenciamento de Projetos");

//                $mail->addTo($params->emailpatrocinador,$params->emailgerenteprojeto,$params->emailgerenteadjunto);
                $mail->addTo('wflino@stefanini.com');
                $mail->send();
//                print '<BR>send()';
//                exit;
                return true;
//                $this->view->alerta = "A nova senha foi enviada para o e-mail: " . $email;
//                $this->_helper->redirector->goToRoute(array('controller' => 'login'), null, true);
            } #se as senha nao foi informada exibe uma mensagem de falha
            else {
//                $this->view->alerta = 'Nao foi possível autenticar o servidor de e-mail, tente mais tarde.';
                return false;
            }


        } catch (Zend_Exception $e) {
//            $this->_view->alerta = "Erro ao enviar e-mail:".$e->getMessage();
//            echo $e->getMessage();
            throw $e;
            return false;
        }
    }

    public function desbloquearProjeto($params)
    {
        $this->alterarStatusProjeto(array('idprojeto' => $params['idprojeto'], 'domstatusprojeto' => 2));
    }

    public function retornaObjetoEmail($params)
    {
        $objDadosEmail = new stdClass();
        $objDadosEmail->projeto = $params->nomprojeto . '/' . $params->nomcodigo . '/' . $params->nomescritorio;
        $objDadosEmail->emailPatrocinador = $params->emailpatrocinador;
        $objDadosEmail->emailGerenteProjeto = $params->emailgerenteprojeto;
        $objDadosEmail->emailGerenteAdjunto = $params->emailgerenteadjunto;
        $objDadosEmail->emailEscritorioProjetos = '';

//        new Zend_Validate_EmailAddress()

        return $objDadosEmail;
    }

    /**
     * Relacao de perfis que podem ver Riscos ainda nao aprovados pelo GP
     *
     * @param array $params - ponteiro para o array de parametros
     * @return void
     */
    public function permissaoPerfil(&$params)
    {
        $params['desbloqueio'] = false;
        //Relacao de perfis que podem ver Riscos ainda nao aprovados pelo GP
        $perfisPermissao = array(
            Default_Model_Perfil::ESCRITORIO_DE_PROJETOSEGPE_CIGE,
            Default_Model_Perfil::ADMINISTRADOR_SETORIAL,
            Default_Model_Perfil::ADMINISTRADOR_GEPNET,
        );

//        Zend_Debug::dump($this->auth); exit;

        if (in_array($this->auth->perfilAtivo->idperfil, $perfisPermissao)) {
            $params['desbloqueio'] = true;
        }
    }


    /**
     * Relacao de perfis que podem ver projetos com status publicado como NÃO
     * @return boolean
     */
    public function visualizarProjetosPublicados()
    {

        //Relacao de perfis que podem ver projetos com status publicado como não NÃO
        $perfisPermissao = array(
            Default_Model_Perfil::ADMINISTRADOR_GEPNET,
            Default_Model_Perfil::ADMINISTRADOR_SETORIAL,
            Default_Model_Perfil::ESCRITORIO_DE_PROJETOSEGPE_CIGE,
            Default_Model_Perfil::GERENTE_DE_PROJETOS,
            Default_Model_Perfil::ASSITENTE_DE_PROJETO,
            Default_Model_Perfil::ASSITENTE_DE_CRONOGRAMA,
            Default_Model_Perfil::ASSITENTE_DE_RISCOS,
        );

//        Zend_Debug::dump($this->auth); exit;

        if (in_array($this->auth->perfilAtivo->idperfil, $perfisPermissao)) {
            return true;
        }
        //Zend_Debug::dump($visualizar);

        return false;
    }


    // Mascara para valores fomato brasileiro
    public function mascaraValores($vlrorcamentodisponivel)
    {
        $mascara = substr($vlrorcamentodisponivel, 0, -2) . '.' . substr($vlrorcamentodisponivel, -2);
        $valor = number_format($mascara, 2, ',', '.');
        return $valor;
    }
}

?>