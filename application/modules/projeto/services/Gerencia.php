<?php

class Projeto_Service_Gerencia extends App_Service_ServiceAbstract
{

    protected $_form;

    protected $_login;
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
        $this->_login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $this->_login->retornaUsuarioLogado();
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
        return $form;
    }

    public function retornaUltimoSequencialPorEscritorio($params)
    {
        return $this->_mapper->retornaUltimoSequencialPorEscritorio($params);
    }

    /**
     * Atualiza a situaçao do projeto
     *
     * @param Projeto_Model_Statusreport $model
     *
     * @return void|boolean
     *
     */
    public function atualizaStatusProjeto($model)
    {
        return $this->_mapper->atualizaStatusProjeto($model);
    }

    public function atualizaDemandanteProjeto($params)
    {
        return $this->_mapper->atualizaDemandanteProjeto($params);
    }

    public function isFuncaoParaPessoaInternaTAP($params)
    {
        return $this->_mapper->isFuncaoParaPessoaInternaTAP($params);
    }

    public function retornaIdParteProjeto($params)
    {
        return $this->_mapper->retornaIdParteProjeto($params);
    }

    public function retornaPartesDoProjeto($params)
    {
        return $this->_mapper->retornaPartesDoProjeto($params);
    }

    public function atualizaGerenteAdjuntoProjeto($params)
    {
        return $this->_mapper->atualizaGerenteAdjuntoProjeto($params);
    }

    /*
     * Atualiza o numero SEI do projeto de acordo com o acompanhamento
     *
     * @param array
     *
     * @return void
     *
     */
    public function atualizaNumeroSEIProjeto($params)
    {
        $this->_mapper->atualizaNumeroSEIProjeto($params);
    }

    public function removeParteInteressadaPorIdPessoaNoTAP($params)
    {
        try {
            $papelExcluir = $this->_mapper->identificadorPapelTAP($params);

            if (!empty($papelExcluir['gerente'])) {
                $params['papel'] = 1;
                $this->_mapper->removePapeisNoProjeto($params);
            }
            if (!empty($papelExcluir['adjunto'])) {
                $params['papel'] = 2;
                $this->_mapper->removePapeisNoProjeto($params);
            }
            if (!empty($papelExcluir['demandante'])) {
                $params['papel'] = 3;
                $this->_mapper->removePapeisNoProjeto($params);
            }
            if (!empty($papelExcluir['patrocinador'])) {
                $params['papel'] = 4;
                $this->_mapper->removePapeisNoProjeto($params);
            }
            return true;
        } catch (Exception $exc) {
            throw($exc);
            return false;
        }
    }

    public function atualizaAtrasoEPercentualMarcoProjeto($params)
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $dadosAtraso = $serviceCronograma->calculaDiaAtrasoProjeto($params);
        if (is_object($dadosAtraso)) {
            $params['atraso'] = $dadosAtraso->totalDiasAtrasoFarol;
            $params['domcoratraso'] = $dadosAtraso->descricaoAtrazoFarol;
        }
        $params['numpercentualconcluidomarco'] = $serviceCronograma->retornaPercentualConcluidoMarcoByProjeto($params);

        $this->_mapper->atualizaAtrasoEPercentualMarcoProjeto($params);
    }

    public function atualizaPercentuaisAtividadesPorProjeto($params)
    {
        $serviceGerencia = new Projeto_Service_AtividadeCronograma();
        $valores = $serviceGerencia->retornaPercentuaisByProjeto($params);

        $dadosProjeto = array(
            'idprojeto' => $params['idprojeto'],
            'qtdeatividadeiniciada' => $valores['qtdeatividadeiniciada'],
            'numpercentualiniciado' => $valores['numpercentualiniciado'],

            'qtdeatividadenaoiniciada' => $valores['qtdeatividadenaoiniciada'],
            'numpercentualnaoiniciado' => $valores['numpercentualnaoiniciado'],

            'qtdeatividadeconcluida' => $valores['qtdeatividadeconcluida'],
            'numpercentualatividadeconcluido' => $valores['numpercentualatividadeconcluido'],
        );

        $retornoAtualiza = $this->_mapper->atualizarValoresAtividadesPorProjeto($dadosProjeto);
        return $retornoAtualiza;
    }

    public function atualizaPercentualProjeto($params)
    {
        $serviceGerencia = new Projeto_Service_Gerencia();
        $numdiasProjeto = $serviceGerencia->retornaNumDiasProjeto($params);
        $dadosProjeto = array(
            'idprojeto' => $params['idprojeto'],
            'numpercentualprevisto' => $numdiasProjeto->numpercentualprevisto,
            'numpercentualconcluido' => $numdiasProjeto->numpercentualconcluido,
        );

        $retornoAtualiza = $this->_mapper->atualizaPercentualProjeto($dadosProjeto);
        return $retornoAtualiza;
    }

    /**
     * @return Projeto_Form_GerenciaPesquisar
     */
    public function getFormPesquisar()
    {
        $form = $this->_getForm('Projeto_Form_GerenciaPesquisar');

        $acopanhamentos = array(
            1 => 'Todos',
            2 => 'Atualizado nos últimos 30 dias',
            3 => 'Atualizado nos últimos 90 dias',
            4 => 'Sem atualização há mais de 30 dias',
            5 => 'Sem atualização há mais de 90 dias',
            6 => 'Sem atualização há mais de 180 dias',
        );
        $form->getElement('acompanhamento')->setMultiOptions($acopanhamentos);
        $form->getElement('codacao')->setMultiOptions(array("0" => "Todos"))->setValue("0");
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

    public function retornaDataFimProjeto($params)
    {
        return $this->_mapper->retornaDataFimProjeto($params);
    }

    /**
     * @return Projeto_Form_PermissaoEditar
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
        if (!empty($dados['numprocessosei'])) {
            $dados['numprocessosei'] = preg_replace('/[^0-9]/i', '', $dados['numprocessosei']);
        }

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


            try {
                $db = $this->_mapper->getDbTable()->getAdapter();
                $db->beginTransaction();

                $model->setAtraso();
                $model->setPercentualConcluidoMarco();

                $retorno = $this->_mapper->insert($model);

                $dados['idprojeto'] = $retorno->idprojeto;

                $this->atualizaPartesInteressadas($dados, false);

                $db->commit();

                return $retorno;
            } catch (Exception $exc) {
                $this->errors = $exc->getMessage();
                $db->rollBack();
                return false;
            }
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    /**
     * @return Projeto_Form_Gerencia
     */
    public function update($dados, $isParteInteressada = true)
    {
        $form = $this->getFormResumoDoProjeto();
        if ($form->getElement('vlrorcamentodisponivel')) {
            $form->getElement('vlrorcamentodisponivel')->addFilter('Digits');
        }
        $dados = array_filter($dados);
        if (isset($dados['desjustificativa'])) {
            $dados['desjustificativa'] = trim($dados['desjustificativa']);
            $dados['desjustificativa'] = mb_substr($dados['desjustificativa'], 0, 4000);
        }
        if (isset($dados['desprojeto'])) {
            $dados['desprojeto'] = trim($dados['desprojeto']);
            $dados['desprojeto'] = mb_substr($dados['desprojeto'], 0, 4000);
        }
        if (isset($dados['desobjetivo'])) {
            $dados['desobjetivo'] = trim($dados['desobjetivo']);
            $dados['desobjetivo'] = mb_substr($dados['desobjetivo'], 0, 4000);
        }
        if (isset($dados['desescopo'])) {
            $dados['desescopo'] = trim($dados['desescopo']);
            $dados['desescopo'] = mb_substr($dados['desescopo'], 0, 4000);
        }
        if (isset($dados['desnaoescopo'])) {
            $dados['desnaoescopo'] = trim($dados['desnaoescopo']);
            $dados['desnaoescopo'] = mb_substr($dados['desnaoescopo'], 0, 4000);
        }
        if (isset($dados['despremissa'])) {
            $dados['despremissa'] = trim($dados['despremissa']);
            $dados['despremissa'] = mb_substr($dados['despremissa'], 0, 4000);
        }
        if (isset($dados['desrestricao'])) {
            $dados['desrestricao'] = trim($dados['desrestricao']);
            $dados['desrestricao'] = mb_substr($dados['desrestricao'], 0, 4000);
        }

        $model = new Projeto_Model_Gerencia($dados);
        $mapperStatusreport = new Projeto_Model_Mapper_Statusreport();
        $ultimoStatusReportProjeto = $mapperStatusreport->retornaUltimoPorProjeto(array('idprojeto' => $dados['idprojeto']));

        if ($ultimoStatusReportProjeto) {
            $model->domstatusprojeto = $ultimoStatusReportProjeto->domstatusprojeto;
        }

        try {
            $db = $this->_mapper->getDbTable()->getAdapter();
            $db->beginTransaction();

            $model->setAtraso();
            $model->setPercentualConcluidoMarco();

            if ($isParteInteressada) {
                $this->atualizaPartesInteressadas($dados);
            }

            $retorno = $this->_mapper->update($model);

            $db->commit();

            return $model;
        } catch (Exception $exc) {
            $this->errors = $exc->getMessage();
            $db->rollBack();
            return false;
        }
    }

    /**
     * Atualiza todas as partes interessadas do projeto
     *
     * @param mixed $dados
     * @param boolean $atualizarPermissao
     *
     * @return void
     */
    public function atualizaPartesInteressadas($dados, $update = true)
    {
        $projeto = $this->getById($dados);

        $this->atualizaParteInteressada($dados['idprojeto'], ($update ? $projeto->idgerenteprojeto : 0),
            isset($dados['idgerenteprojeto']) ? $dados['idgerenteprojeto'] : 0, 1);

        $this->atualizaParteInteressada($dados['idprojeto'], ($update ? $projeto->idgerenteadjunto : 0),
            isset($dados['idgerenteadjunto']) ? $dados['idgerenteadjunto'] : 0, 2);

        $this->atualizaParteInteressada($dados['idprojeto'], ($update ? $projeto->iddemandante : 0),
            isset($dados['iddemandante']) ? $dados['iddemandante'] : 0, 3);

        $this->atualizaParteInteressada($dados['idprojeto'], ($update ? $projeto->idpatrocinador : 0),
            isset($dados['idpatrocinador']) ? $dados['idpatrocinador'] : 0, 4);
    }

    /**
     * Atualiza a parte interessada com o perfil relacionado
     *
     * @param int $idProjeto
     * @param int $idPessoaAntiga
     * @param int $idPessoaNova
     * @param int $idParteInteressadaFuncao
     *
     * @return array|null
     */
    public function atualizaParteInteressada($idProjeto, $idPessoaAntiga, $idPessoaNova, $idParteInteressadaFuncao)
    {
        $serviceParteInteressada = new Projeto_Service_ParteInteressada();

        $params = array(
            'idprojeto' => $idProjeto,
            'idcadastrador' => $this->auth->idpessoa,
            'idpessoaantiga' => $idPessoaAntiga,
            'idpessoanova' => $idPessoaNova,
            'idparteinteressadafuncao' => $idParteInteressadaFuncao,
        );

        $serviceParteInteressada->atualizarFuncaoRhProjeto($params);
    }

    /**
     * Atualiza as permissões de uma parte interessada
     *
     * @param array $parteInteressada
     *
     * @return array|null
     */
    private function atualizaPermissaoParteInteressada($parteInteressada)
    {
        if (null == $parteInteressada) {
            return null;
        }

        $parteInteressada['tppermissao'] = 1;

        $serviceParteInteressada = new Projeto_Service_ParteInteressada();

        return $serviceParteInteressada->updateInterno($parteInteressada);
    }

    /**
     *
     * @param array $dados
     */
    public function excluir($dados)
    {
        try {
            $model = new Default_Model_Gerencia($dados);
            return $this->_mapper->excluir($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function clonarProjeto($dados)
    {
        $idcadastrador = null;
        $idEscritorio = null;
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

        $dados['nomcodigo']     = $nomcodigo;
        $dados['idcadastrador'] = $idcadastrador;
        $dados['ano']           = $ano;
        try {
            return $this->_mapper->clonarProjeto($dados);
        } catch (Exception $exc) {
            throw $exc;
            return false;
        }
    }

    public function getEscritorioByIdProjeto($params)
    {
        return $this->_mapper->getEscritorioByIdProjeto($params);
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
            # chamada anterior  - 03-10-2017
            $projeto = $this->_mapper->retornaProjetoPorId($params);
        } catch (Exception $exc) {
            var_dump($exc);
        }

        $projetoArray = $projeto->toArray();
        $projetoArray['partes'] = $projeto->partes;
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
                        } else {
                            $en['descricaoprazo'] = "success";
                            $en['prazo'] = 0;
                        }

                        if (!empty($gr['datfim'])) {
                            $prazoGr = $grupo->retornaPrazo($projeto->numcriteriofarol);
                            $gr['descricaoprazo'] = $prazoGr->descricao;
                            $gr['prazo'] = $prazoGr->dias;
                        } else {
                            $gr['descricaoprazo'] = 'success';
                            $gr['prazo'] = 0;
                        }
                        $en['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                        $en['numpercentualconcluido'] = $percentuais->numpercentualconcluido;
                        $gr['entregas'][$e] = $en;
                        if ((int)$en["numpercentualconcluido"] == 100) {
                            $en['descricaoprazo'] = "";
                        }
                        if ((int)$gr["numpercentualconcluido"] == 100) {
                            $gr['descricaoprazo'] = "";
                        }
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

    private function isFinalSemana($dataRealizada, $dataPlanejada)
    {
        $dtPlanejada = new Zend_Date($dataPlanejada, 'd/m/Y');
        $dtPlanejada = $dtPlanejada->addDay(1);
        $dataFimRealizada = new Zend_Date($dataRealizada, 'd/m/Y');

        if ($dtPlanejada->equals($dataFimRealizada) && $dtPlanejada->toString('EEE') == 'sáb'
            || $dtPlanejada->equals($dataFimRealizada) && $dtPlanejada->toString('EEE') == 'dom'
        ) {
            return true;
        }
        return false;
    }


    public function retornaDescricaoFarol($dtRealizada, $dtPlanejada, $criterioFarol = 0)
    {
        $sinal = "";
        $dataFimPlanejada = new Zend_Date($dtPlanejada, 'd/m/Y');
        $dataFimRealizada = new Zend_Date($dtRealizada, 'd/m/Y');

        if ((Zend_Date::isDate($dataFimPlanejada)) &&
            (Zend_Date::isDate($dataFimRealizada))
        ) {
            $numEmDias = 0;
            $dados['datainicio'] = $dataFimRealizada->toString('d/m/Y');
            $dados['datafim'] = $dataFimPlanejada->toString('d/m/Y');
            $service = new Projeto_Service_AtividadeCronograma();
            /* retira um dia do cálculo para atender a regra definida */

            if (($dataFimRealizada->equals($dataFimPlanejada)) == false) {
                $numEmDias = $service->retornaQtdeDiasUteisEntreDatas($dados);
                $numEmDias = $numEmDias * (-1);
                $numEmDias = ($numEmDias > 0 ? $numEmDias - 1 : $numEmDias + 1);
            }

            if ($numEmDias < 0 || $numEmDias == 0) {
                $sinal = "success";
            } else {
                if ($numEmDias > 0 && $numEmDias <= $criterioFarol) {
                    $sinal = "warning";
                } else {
                    if ($numEmDias > $criterioFarol) {
                        $sinal = "important";
                    }
                }
            }
        }
        return $sinal;
    }

    public function retornaProjetoArrayPorId($params, $dadosCompletos)
    {
        try {
            $projeto = $this->_mapper->retornaProjetoCronogramaPorId($params, $dadosCompletos);
            return $projeto;
        } catch (Exception $exc) {
            var_dump($exc);
        }
    }

    public function retornaProjetoPorIdObjeto($params)
    {
        try {
            $projeto = $this->_mapper->retornaProjetoPorIdObjeto($params);

            $object = new stdClass();
            foreach ((array)$projeto as $key => $value) {
                if (in_array($key, array(
                    'numpercentualnaoiniciado',
                    'numpercentualiniciado',
                    'numpercentualatividadeconcluido',
                    'numpercentualconcluido',
                    'numpercentualprevisto',
                    'numpercentualconcluidomarco'
                ))) {
                    $value = (0 == $value || in_array((int)$value, array(0, 100))) ? ((int)$value) : str_replace('.',
                        ',', $value);
                }

                $object->$key = $value;
            }

            return $object;
        } catch (Exception $exc) {
            var_dump($exc);
        }
    }


    public function retornaArrayCronogramaProjetoPorId($params)
    {
        try {
            $service = new Projeto_Service_AtividadeCronograma();
            $projeto = $this->_mapper->retornaProjetoCronogramaPorId($params);

        } catch (Exception $exc) {
            var_dump($exc);
        }
        /************  Calcula os dias e percentuais de conclusão do Projeto   *************/
        $resultadoDiasProjeto = $projeto->retornarDiasEstimadosEReais();
        if ($resultadoDiasProjeto->estimativaTotalDias == 0) {
            $numpercentualprevisto = 0;
        } else {
            $numpercentualprevisto = round(100 * ($resultadoDiasProjeto->estimativaTotalDiasExecutados / $resultadoDiasProjeto->estimativaTotalDias));
        }
        if ($resultadoDiasProjeto->realTotalDias == 0) {
            $numpercentualconcluido = 0;
        } else {
            $numpercentualconcluido = round((100 * ($resultadoDiasProjeto->realTotalDiasExecutados / $resultadoDiasProjeto->realTotalDias)));
            $numpercentualconcluido = number_format($numpercentualconcluido, 0);
        }
        /*********************************************************************************/
        $serviceComentario = new Projeto_Service_Comentario();
        $datinicioprojeto = (@trim($projeto->datinicio) != "" ? $projeto->datinicio->toString('d/m/Y') : "");
        $datfimprojeto = (@trim($projeto->datfim) != "" ? $projeto->datfim->toString('d/m/Y') : "");
        $atrazoCabecalho = $projeto->retornaPrazoEmDiasCabecalho();
        $atrazoEmdias = $atrazoCabecalho->dias;
        $atrazodescricaoPrazo = $atrazoCabecalho->descricao;
        $projetoArray = $projeto->toArray();
        $projetoArray['datinicioprojeto'] = $datinicioprojeto;
        $projetoArray['datfimprojeto'] = $datfimprojeto;
        $projetoArray['nomeprojeto'] = mb_substr($projeto['nomcodigo'] . '--' . $projeto['nomprojeto'], 0, 40) . '...';
        $projetoArray['datiniciobaselinet'] = "";
        $projetoArray['datfimbaselinet'] = "";
        $projetoArray['datiniciot'] = "";
        $projetoArray['datfimt'] = "";
        $projetoArray['diasbaselinet'] = $projeto['numdiasbaseline'];
        $projetoArray['numdiascompletost'] = $projeto['numdiascompletos'];
        $projetoArray['totaldiasbaselinet'] = $projeto['totaldiasbaseline'];
        $projetoArray['numpercentualprevistot'] = $projeto['numpercentualprevisto'];

        ### REMOVER PARA PUBLICACAO ###
        $projetoArray['estimativaTotalDias'] = $resultadoDiasProjeto->estimativaTotalDias;
        $projetoArray['estimativaTotalDiasExecutados'] = $resultadoDiasProjeto->estimativaTotalDiasExecutados;

        $projetoArray['realTotalDiasExecutados'] = $resultadoDiasProjeto->realTotalDiasExecutados;
        $projetoArray['realTotalDias'] = $resultadoDiasProjeto->realTotalDias;

        $projetoArray['vlratividadet'] = 0;
        $projetoArray['diasrealt'] = $projeto['numdiasrealizados'];
        $projetoArray['diasrealizadosreal'] = $projeto['numdiasrealizadosreal'];
        $projetoArray['numpercentualconcluidot'] = $numpercentualconcluido;
        $projetoArray['numpercentualprevistot'] = $numpercentualprevisto;
        $projetoArray['totalnumpercconcluidot'] = $numpercentualconcluido;
        $contLinha = 1;
        $servicoAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        if (isset($projeto->grupos) && !empty($projeto->grupos)) {
            $contAtiv = 0;
            $contEnt = 0;
            $contGr = 0;
            $dados['idprojeto'] = $params['idprojeto'];
            foreach ($projeto->grupos as $g => $grupo) {
                /* @var $grupo Projeto_Model_Grupocronograma */
                $gr = $grupo->toArray();
                $idEntregas = $grupo->entregas;
                $gr['numlinha'] = @$contLinha;
                $dados['idatividadecronograma'] = $grupo->idatividadecronograma;
                $gr['countComentarioGrp'] = $serviceComentario->retornaQtdComentarioPorIdAtvCronograma($dados);
//                var_dump($gr);die;
                @$contLinha++;
                if (count($grupo->entregas) > 0) {
                    $totalPercentualEntregas = 0;
                    foreach ($grupo->entregas as $e => $entrega) {
                        /* @var $entrega Projeto_Model_Entregacronograma */
                        $en = $entrega->toArray();
                        $idAtividades = $entrega->atividades;
                        $totalPercentualAtividade = 0;
                        $en['numlinha'] = $contLinha;
                        $contLinha++;
                        unset($dados['idatividadecronograma']);
                        $dados['idatividadecronograma'] = $entrega->idatividadecronograma;
                        $en['countComentarioEnt'] = $serviceComentario->retornaQtdComentarioPorIdAtvCronograma($dados);
                        foreach ($entrega->atividades as $atividade) {
                            if (@$projetoArray['datiniciobaselinet'] == "") {
                                $projetoArray['datiniciobaselinet'] = $atividade->datiniciobaseline;
                            } else {
                                if ($atividade->datiniciobaseline < $projetoArray['datiniciobaselinet']) {
                                    $projetoArray['datiniciobaselinet'] = $atividade->datiniciobaseline;
                                }
                            }
                            if (@$projetoArray['datiniciot'] == "") {
                                $projetoArray['datiniciot'] = $atividade->datinicio;
                            } else {
                                if ($atividade->datinicio < $projetoArray['datiniciot']) {
                                    $projetoArray['datiniciot'] = $atividade->datinicio;
                                }
                            }
                            if (@$projetoArray['datfimbaselinet'] == "") {
                                $projetoArray['datfimbaselinet'] = $atividade->datfimbaseline;
                            } else {
                                if ($atividade->datfimbaseline > $projetoArray['datfimbaselinet']) {
                                    $projetoArray['datfimbaselinet'] = $atividade->datfimbaseline;
                                }
                            }
                            if (@$projetoArray['datfimt'] == "") {
                                $projetoArray['datfimt'] = $atividade->datfim;
                            } else {
                                if ($atividade->datfim > $projetoArray['datfimt']) {
                                    $projetoArray['datfimt'] = $atividade->datfim;
                                }
                            }
                            unset($dados['idatividadecronograma']);
                            $dados['idatividadecronograma'] = $atividade->idatividadecronograma;
                            $percentuais = $atividade->retornarDiasEstimadosEReais();
                            $prazo = $atividade->retornaPrazo($atividade->numcriteriofarol);
                            $at = $atividade->toArray();
                            $at['countComentarioAtv'] = $serviceComentario->retornaQtdComentarioPorIdAtvCronograma($dados);

                            $totalPercentualAtividade = $totalPercentualAtividade + $at['numpercentualconcluido'];
                            $at['descricaoprazo'] = $prazo->descricao;
                            $at['prazo'] = $prazo->dias;
                            $at['numlinha'] = $contLinha;
                            if ((int)$at['numpercentualconcluido'] == 100) {
                                $at['descricaoprazo'] = "";
                            }
                            if ($at['domtipoatividade'] == "3") {
                                if (($at['diasbaseline'] == 0) || ($at['diasbaseline'] == "0")) {
                                    $at['diasbaseline'] = "1";
                                }
                                if (($at['numdiasrealizados'] == "0") || ($at['numdiasrealizados'] == "0")) {
                                    $at['numdiasrealizados'] = 1;
                                };
                            }
                            $at['flashowhide'] = $atividade->flashowhide;
                            $en['atividades'][] = $at;
                            $contLinha++;
                            $contAtiv++;
                        }
                        $percentuais = $entrega->retornaPercentuais();
                        if (!empty($en['datfim'])) {
                            $prazoEn = $entrega->retornaPrazo($projeto->numcriteriofarol);
                            $en['descricaoprazo'] = $prazoEn->descricao;
                            $en['prazo'] = $prazoEn->dias;
                        } else {
                            $en['descricaoprazo'] = "success";
                            $en['prazo'] = 0;
                        }
                        if ((int)$en['numpercentualconcluido'] == 100) {
                            $en['descricaoprazo'] = "";
                        }
                        if (!empty($gr['datfim'])) {
                            $prazoGr = $grupo->retornaPrazo($projeto->numcriteriofarol);
                            $gr['descricaoprazo'] = $prazoGr->descricao;
                            $gr['prazo'] = $prazoGr->dias;
                        } else {
                            $gr['descricaoprazo'] = "success";
                            $gr['prazo'] = 0;
                        }
                        $en['flashowhide'] = $entrega->flashowhide;
                        $en['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                        if (@$totalPercentualAtividade > 0) {
                            $en['numpercentualconcluido'] = (@trim($percentuais->numpercentualconcluido) == "" ? "0.00" : $percentuais->numpercentualconcluido);
                        } else {
                            $en['numpercentualconcluido'] = 0;
                        }
                        if ((int)$en['numpercentualconcluido'] == 100) {
                            $en['descricaoprazo'] = "";
                        }
                        if ((int)$gr['numpercentualconcluido'] == 100) {
                            $gr['descricaoprazo'] = "";
                        }
                        $totalPercentualEntregas = $en['numpercentualconcluido'] + $totalPercentualEntregas;
                        $gr['entregas'][$e] = $en;
                        $contEnt++;

                    }
                }
                $gr['flashowhide'] = $grupo->flashowhide;
                if (@trim($gr['vlratividade']) != "") {
                    $valor = str_replace(",", "", str_replace(".", "", $gr['vlratividade']));
                    $valor = (int)$valor;
                } else {
                    $valor = 0;
                }
                $projetoArray['vlratividadet'] = @$projetoArray['vlratividadet'] + $valor;
                $percentuais = $grupo->retornaPercentuais();
                $gr['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                if (@$totalPercentualEntregas > 0) {
                    $gr['numpercentualconcluido'] = (@trim($percentuais->numpercentualconcluido) == "" ? "0.00" : $percentuais->numpercentualconcluido);
                } else {
                    $gr['numpercentualconcluido'] = 0;
                }
                if ((int)$gr['numpercentualconcluido'] == 100) {
                    $gr['descricaoprazo'] = "";
                }
                $projetoArray['grupos'][$g] = $gr;
                $contGr++;
                $contLinha++;
            }
            if (isset($projetoArray['datiniciobaselinet'])) {
                if (@get_class($projetoArray['datiniciobaselinet']) == 'DateTime') {
                    $projetoArray['datiniciobaselinet'] = @$projetoArray['datiniciobaselinet']->format('d/m/Y');
                }
            }
            if (isset($projetoArray['datfimbaselinet'])) {
                if (@get_class($projetoArray['datfimbaselinet']) == 'DateTime') {
                    $projetoArray['datfimbaselinet'] = @$projetoArray['datfimbaselinet']->format('d/m/Y');
                }
            }
            if (isset($projetoArray['datiniciot'])) {
                if (@get_class($projetoArray['datiniciot']) == 'DateTime') {
                    $projetoArray['datiniciot'] = @$projetoArray['datiniciot']->format('d/m/Y');
                }
            }
            if (isset($projetoArray['datfimt'])) {
                if (@get_class($projetoArray['datfimt']) == 'DateTime') {
                    $projetoArray['datfimt'] = @$projetoArray['datfimt']->format('d/m/Y');
                }
            }
            if (isset($projetoArray['vlratividadet'])) {
                $projetoArray['vlratividadet'] = mb_substr($projetoArray['vlratividadet'], 0,
                        -2) . '.' . mb_substr($projetoArray['vlratividadet'], -2);
                $projetoArray['vlratividadet'] = number_format($projetoArray['vlratividadet'], 2, ',', '.');
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
        $datUltimaStatus = new Zend_Date($projetoArray['ultimoStatusReport']['datfimprojetotendencia'], 'd/m/Y');
        $projetoArray['ultimoStatusReport']['datfimprojetotendencia'] = $datUltimaStatus->toString('d/m/Y');
        $diasFarol = 0;
        $sinalFarol = "sucess";
        $totalDiasAtrasoFarol = 0;
        $descricaoAtrazoFarol = "default";
        $dadosAtraso = $service->calculaDiaAtrasoProjeto($projetoArray);

        if (is_object($dadosAtraso)) {
            $totalDiasAtrasoFarol = $dadosAtraso->totalDiasAtrasoFarol;
            $descricaoAtrazoFarol = $dadosAtraso->descricaoAtrazoFarol;
        }

        $projetoArray['numpercentualconcluido'] = $projeto->numpercentualconcluido;
        $projetoArray['numpercentualprevisto'] = $projeto->numpercentualprevisto;
        $projetoArray['prazoCabecalho'] = $totalDiasAtrasoFarol;
        $projetoArray['prazoEmDias'] = $atrazoEmdias;
        $projetoArray['descricaoPrazo'] = (int)$projeto->numpercentualconcluido == 100 ? "" : $projeto->retornaDescricaoPrazoCabecalho();
        $projetoArray['descricaoPrazoCabecalho'] = $projeto->retornaDescricaoPrazoCabecalho();
        $projetoArray['atrasoCabecalhoFarol'] = $totalDiasAtrasoFarol;
        $projetoArray['descricaoAtrasoFarol'] = $descricaoAtrazoFarol;
        if ((int)$projetoArray['numpercentualconcluido'] == 100) {
            $projetoArray['descricaoPrazo'] = "";
            $projetoArray['descricaoAtrasoFarol'] = "";
        } else {
            $projetoArray['descricaoAtrasoFarol'] = $descricaoAtrazoFarol;
        }
        return $projetoArray;
    }

    public function retornaNumeroCriterioFarol($params)
    {
        return $this->_mapper->retornaNumeroCriterioFarol($params);
    }


    public function retornaPartes($params, $model = false)
    {
        return $this->_mapper->retornaPartesInteressadas($params, $model);
    }

    public function retornaNumDiasProjeto($params)
    {
        $serviceAtividade = new Projeto_Service_AtividadeCronograma();
        $resultadoDias = $serviceAtividade->retornaNumDiasProjeto($params);
        //Zend_Debug::dump($resultadoDias);die;
        //$resultadoDias = $this->_mapper->retornaNumDiasProjeto($params);
//        $numpercentualprevisto = 0;
//        $numpercentualconcluido = 0;
//        if ($resultadoDias) {
//            if ((null === $resultadoDias['numdiascompletos']) || (null === $resultadoDias['totaldiasbaseline'])) {
//                $numpercentualprevisto = 0;
//                $numdiascompletos = 0;
//                $totaldiasbaseline = 0;
//            } else {
//                if ($resultadoDias['totaldiasbaseline'] > 0) {
//                    $numpercentualprevisto = round(($resultadoDias['numdiascompletos'] / $resultadoDias['totaldiasbaseline']) * 100,
//                        1);
//                    $numdiascompletos = number_format($resultadoDias['numdiascompletos'], 0);
//                    $totaldiasbaseline = number_format($resultadoDias['totaldiasbaseline'], 0);
//                } else {
//                    $numpercentualprevisto = 0;
//                    $numdiascompletos = 0;
//                    $totaldiasbaseline = 0;
//                }
//            }
//            $numpercentualprevisto = number_format($numpercentualprevisto, 0);
//            if ((null === $resultadoDias['numdiasrealizadosreal']) || (null === $resultadoDias['numdiasrealizados'])) {
//                $numpercentualconcluido = 0;
//                $numdiasrealizadosreal = 0;
//                $numdiasrealizados = 0;
//            } else {
//                if ($resultadoDias['numdiasrealizados'] > 0) {
//                    $numpercentualconcluido = round(($resultadoDias['numdiasrealizadosreal'] / $resultadoDias['numdiasrealizados']) * 100,
//                        1);
//                    $numdiasrealizadosreal = number_format($resultadoDias['numdiasrealizadosreal'], 0);
//                    $numdiasrealizados = number_format($resultadoDias['numdiasrealizados'], 0);
//                } else {
//                    $numpercentualconcluido = 0;
//                    $numdiasrealizadosreal = 0;
//                    $numdiasrealizados = 0;
//                }
//            }
//            $numpercentualconcluido = number_format($numpercentualconcluido, 0);
//        }
        $objResposta = new stdClass();
        $objResposta->numpercentualprevisto = $resultadoDias['numpercentualprevisto'];
        $objResposta->numpercentualconcluido = $resultadoDias['numpercentualconcluido'];
        $objResposta->numdiasbaseline = $resultadoDias['totaldiasbaseline'];
        $objResposta->numdiascompletos = $resultadoDias['numdiascompletos'];
        $objResposta->numdiasrealizados = $resultadoDias['numdiasrealizados'];
        $objResposta->numdiasrealizadosreal = $resultadoDias['numdiasrealizadosreal'];
        return $objResposta;
    }

    public function generateStatusReport($params)
    {
        $projeto = $this->retornaProjetoPorId($params);

        $resultado = $projeto->retornarDiasEstimadosEReais();

        $this->_mapperAtividadeCronograma = new Projeto_Model_Mapper_Atividadecronograma();
        $r = $this->_mapperAtividadeCronograma->retornaMetaDadosPorProjeto($params);
        $periodos = $this->_mapperAtividadeCronograma->retornaDatasDoPeriodo(array(
            'idprojeto' => $params['idprojeto']
        ));

        if ($periodos) {
            $dtInicio = new Zend_Date($periodos['datainiperiodo'], 'dd/MM/YYYY');
            $dtFim = new Zend_Date($periodos['datafinperiodo'], 'dd/MM/YYYY');

            $dadosAtvConcluidas = array(
                'idprojeto' => $params['idprojeto'],
                'dtInicio' => $dtInicio->toString('d-m-Y'),
                'dtFim' => $dtFim->toString('d-m-Y')
            );

            $dadosAtvEmAndamento = array(
                'idprojeto' => $params['idprojeto'],
                'dtInicio' => $dtInicio->toString('d-m-Y'),
                'dtFim' => date('d-m-Y')
            );

            $ac = $this->_mapperAtividadeCronograma->retornaAtividadesConcluidas($dadosAtvConcluidas);
            $ae = $this->_mapperAtividadeCronograma->retornaAtividadesEmAndamento($dadosAtvEmAndamento);
            $r['desatividadeandamento'] = $ae;
            $r['desatividadeconcluida'] = $ac;
        }

        $r['estimativas'] = $resultado;
        $r['estimativas']->numpercentualprevisto = $projeto->numpercentualprevisto;
        $r['estimativas']->numpercentualconcluido = $projeto->numpercentualconcluido;
        $r['estimativas']->diaatraso = $projeto->atraso;
        $r['estimativas']->domcoratraso = $projeto->domcoratraso;
        $r['estimativas']->numpercentualconcluido = $projeto->numpercentualconcluido;

        return $r;
    }

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
        $dados = $this->_mapper->pesquisar($params, $idperfil, $idescritorio, $paginator);

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
                $descricaoCorFarolAtraso = "";
                $acompanhamentos = $serviceStatusReport->retornaUltimoAcompanhamento(array('idprojeto' => $d['idprojeto']),
                    false);
                if (!empty($acompanhamentos->idstatusreport)) {

                    if ($acompanhamentos->datfimprojetotendencia) {
                        $service = new Projeto_Service_AtividadeCronograma();
                        //Calcula a diferenca de dias para atraso e prazo
                        //$datfimprojetotendencia = $d['datfimplano'];
                        $datfimprojetotendencia = date('d/m/Y', strtotime($d['terminotendencia']));
                        $datfimtendencia = $acompanhamentos->datfimprojetotendencia->toString('d/m/Y');
                        $datfim = $d['datfim'];
                        $datinicio = $d['datinicio'];
                        //$dias = $this->_timeInterval->tempoTotal($datfim, $datinicio)->dias;
                        //$atraso = $this->_timeInterval->tempoTotal($acompanhamentos->datfimprojetotendencia, $datfim)->dias;
                        /***** Calculo anterior *****/
                        //$dias = $this->_timeInterval->tempoTotal($datfim, $datinicio)->dias;
                        /*********************/
                        $inData['datainicio'] = $datinicio;
                        $inData['datafim'] = $datfim;
                        $dias = $service->retornaQtdeDiasUteisEntreDatas($inData);
                        /***** Calculo anterior *****/
                        //$atraso = $this->_timeInterval->tempoTotal($acompanhamentos->datfimprojetotendencia, $datfim)->dias;
                        /*****************/
                        $entrada['datainicio'] = $acompanhamentos->datfimprojetotendencia->toString('d/m/Y');
                        $entrada['datafim'] = $datfim;
                        $atraso = $service->retornaQtdeDiasUteisEntreDatas($entrada);
                        /* Inverte o sinal conforme Redmine 15429 */
                        $atraso = ($atraso * (-1));
                        $atraso = ($atraso > 0 ? $atraso - 1 : $atraso + 1);
                        //$descricaoCorFarolAtraso = $this->retornaDescricaoFarol($inData['datainicio'], $inData['datafim'], $d['numcriteriofarol']);
                        //Zend_Debug::dump($atraso." - ".$d['numcriteriofarol']);

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
                //$d['descricaoCorFarol'] = $descricaoCorFarolAtraso;
                $array['cell'] = array(
                    $d['nomprograma'],
                    $d['nomprojeto'],
                    $d['nomgerenteprojeto'],
                    $d['nomescritorio'],
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
                    @trim($situacao),
                    $d['domstatusprojeto'],
                    $datfimprojetotendencia
                    //$d['descricaoCorFarol']
                );
                $response["rows"][] = $array;
                //Zend_Debug::dump($response);exit;
            }
            return $response;
        }
        return $dados;
    }

    public function isParteInteressada($params)
    {
        $service = new Projeto_Service_ParteInteressada();
        return $service->isParteInteressada($params);
    }

    public function pesquisarGerenciaProjeto($params, $paginator)
    {

        define('PERIODICIDADE_ATUALIZACAO', 3);

        $serviceStatusReport = new Projeto_Service_StatusReport();
        $service = new Projeto_Service_AtividadeCronograma();
        $serviceTipo = new Projeto_Service_SituacaoProjeto();
        $nomeperfilACL = $this->auth->perfilAtivo->nomeperfilACL;
        $idperfil = $this->auth->perfilAtivo->idperfil;
        $idescritorio = $this->auth->perfilAtivo->idescritorio;
        $idpessoa = $this->auth->idpessoa;
        $dados = null;

        switch ($nomeperfilACL) {
            case 'report':
                $idpessoa = null;
                $publicado = 'publico';
                $dados = $this->_mapper->pesquisarGerenciaProjeto($params, $idperfil, $idescritorio, $idpessoa,
                    $paginator, $publicado);
                break;
            case 'gerente':
                $publicado = 'publico';
                $dados = $this->_mapper->pesquisarGerenciaProjeto($params, $idperfil, $idescritorio, $idpessoa,
                    $paginator, $publicado);
                break;
            case 'escritorio':
                $publicado = 'publico/privado';
                $dados = $this->_mapper->pesquisarGerenciaProjeto($params, $idperfil, $idescritorio, $idpessoa,
                    $paginator, $publicado);
                break;
            case 'admin_setorial':
                $publicado = 'publico/privado';
                $dados = $this->_mapper->pesquisarGerenciaProjeto($params, $idperfil, $idescritorio, $idpessoa,
                    $paginator, $publicado);
                break;
            default :
                $idpessoa = null;
                $publicado = 'publico/privado';
                $dados = $this->_mapper->pesquisarGerenciaProjeto($params, $idperfil, $idescritorio, $idpessoa,
                    $paginator, $publicado);
                break;
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
                $atraso = 0;
                $prazo = "-";
                $risco = "-";
                $ultimoacompanhamento = "-";
                $datfimtendencia = "-";
                $ultimostaus = "-";
                $descricaoCorFarolAtraso = "success";

                $d['numpercentualconcluido'] = number_format($d['numpercentualconcluido'], 1) . "%";
                $d['numpercentualprevisto'] = number_format($d['numpercentualprevisto'], 1) . "%";

                $acompanhamentos = $serviceStatusReport->retornaUltimoAcompanhamento(array('idprojeto' => $d['idprojeto']),
                    false);
                /**
                 * @var Projeto_Model_Gerencia $projeto
                 *
                 */
                $projeto = $this->getById($d);

                if (!empty($acompanhamentos->idstatusreport)) {
                    $datfimprojetotendencia = date('d/m/Y', strtotime($d['terminotendencia']));
                    $datfimtendencia = $acompanhamentos->datfimprojetotendencia->toString('d/m/Y');
                    $previsto = $acompanhamentos->numpercentualprevisto . "%";
                    $concluido = $acompanhamentos->numpercentualconcluido . "%";
                    $prazo = 0;
                    $risco = $acompanhamentos->domcorrisco;
                    $ultimoacompanhamento = $acompanhamentos->datacompanhamento->toString('d/m/Y');
                    $ultimostatus = $acompanhamentos->domstatusprojeto;
                }
                $atraso = $projeto->retornaPrazoEmDias();
                $descricaoCorFarolAtraso = $projeto->retornaDescricaoPrazo();
                $situacao = trim($serviceTipo->getById($ultimostatus));
                $d['situacao'] = $situacao;
                $this->permissaoPerfil($d);
                $d['descricaoCorFarol'] = $descricaoCorFarolAtraso;

                $array['cell'] = array(
                    $d['nomprograma'],
                    $d['nomprojeto'],
                    $d['nomgerenteprojeto'],
                    $d['nomescritorio'],
                    $d['nomcodigo'],
                    $d['flapublicado'],
                    $d['datinicio'],
                    $d['datfim'],
                    $datfimtendencia,
                    $previsto,
                    $concluido,
                    $atraso . ' dias',
                    $prazo,
                    $risco,
                    $ultimoacompanhamento,
                    $d['idprojeto'],
                    $d['numcriteriofarol'],
                    $situacao,
                    $d['domstatusprojeto'],
                    $d['descricaoCorFarol'],
                    $datfimprojetotendencia,
                    $d['numdomstatusprojeto']
                );
                $response["rows"][] = $array;
            }
            return $response;
        }
        return $dados;
    }


    public function filtrarProjetoGerencia($params, $paginator)
    {

        define('PERIODICIDADE_ATUALIZACAO', 3);

        $serviceStatusReport = new Projeto_Service_StatusReport();
        $service = new Projeto_Service_AtividadeCronograma();
        $serviceTipo = new Projeto_Service_SituacaoProjeto();
        $nomeperfilACL = $this->auth->perfilAtivo->nomeperfilACL;
        $idperfil = $this->auth->perfilAtivo->idperfil;
        $idescritorio = $this->auth->perfilAtivo->idescritorio;
        $idpessoa = $this->auth->idpessoa;
        $dados = null;

        switch ($nomeperfilACL) {
            case 'report':
                $idpessoa = null;
                $publicado = 'publico';
                $dados = $this->_mapper->filtrarProjeto($params, $idperfil, $idescritorio, $idpessoa, $paginator,
                    $publicado);
                break;
            case 'gerente':
                $publicado = 'publico';
                $dados = $this->_mapper->filtrarProjeto($params, $idperfil, $idescritorio, $idpessoa, $paginator,
                    $publicado);
                break;
            case 'escritorio':
                $publicado = 'publico/privado';
                $dados = $this->_mapper->filtrarProjeto($params, $idperfil, $idescritorio, $idpessoa, $paginator,
                    $publicado);
                break;
            case 'admin_setorial':
                $publicado = 'publico/privado';
                $dados = $this->_mapper->filtrarProjeto($params, $idperfil, $idescritorio, $idpessoa, $paginator,
                    $publicado);
                break;
            default :
                $idpessoa = null;
                $publicado = 'publico/privado';
                $dados = $this->_mapper->filtrarProjeto($params, $idperfil, $idescritorio, $idpessoa, $paginator,
                    $publicado);
                break;
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
                $atraso = 0;
                $prazo = "-";
                $risco = "-";
                $ultimoacompanhamento = "-";
                $datfimtendencia = "-";
                $ultimostaus = "-";
                $descricaoCorFarolAtraso = "success";

                $d['numpercentualconcluido'] = number_format($d['numpercentualconcluido'], 1) . "%";
                $d['numpercentualprevisto'] = number_format($d['numpercentualprevisto'], 1) . "%";

                $acompanhamentos = $serviceStatusReport->retornaUltimoAcompanhamento(array('idprojeto' => $d['idprojeto']),
                    false);
                /**
                 * @var Projeto_Model_Gerencia $projeto
                 *
                 */
                $projeto = $this->getById($d);

                if (!empty($acompanhamentos->idstatusreport)) {
                    $datfimprojetotendencia = date('d/m/Y', strtotime($d['terminotendencia']));
                    $datfimtendencia = $acompanhamentos->datfimprojetotendencia->toString('d/m/Y');
                    $previsto = $acompanhamentos->numpercentualprevisto . "%";
                    $concluido = $acompanhamentos->numpercentualconcluido . "%";
                    $prazo = 0;
                    $risco = $acompanhamentos->domcorrisco;
                    $ultimoacompanhamento = $acompanhamentos->datacompanhamento->toString('d/m/Y');
                    $ultimostatus = $acompanhamentos->domstatusprojeto;
                }
                $atraso = $projeto->retornaPrazoEmDias();
                $descricaoCorFarolAtraso = $projeto->retornaDescricaoPrazo();
                $situacao = trim($serviceTipo->getById($ultimostatus));
                $d['situacao'] = $situacao;
                $this->permissaoPerfil($d);
                $d['descricaoCorFarol'] = $descricaoCorFarolAtraso;

                $array['cell'] = array(
                    $d['nomprograma'],
                    $d['nomprojeto'],
                    $d['nomgerenteprojeto'],
                    $d['nomescritorio'],
                    $d['nomcodigo'],
                    $d['flapublicado'],
                    $d['datinicio'],
                    $d['datfim'],
                    $datfimtendencia,
                    $previsto,
                    $concluido,
                    $atraso . ' dias',
                    $prazo,
                    $risco,
                    $ultimoacompanhamento,
                    $d['idprojeto'],
                    $d['numcriteriofarol'],
                    $situacao,
                    $d['domstatusprojeto'],
                    $d['descricaoCorFarol'],
                    $datfimprojetotendencia,
                    $d['numdomstatusprojeto']
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

        $config = Zend_Registry::get('config');
        $dir = $config->resources->cachemanager->default->backend->options->logs_dir;
        $filename = 'log_P' . $params['idprojeto'] . '_' . date('Y-m-d') . '.txt';
        $path = $dir . $filename;
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
        $listArray = array('0' => $msg);

        foreach ($objeto as $val => $desc) {
            if ($desc != $msg) {
                $listArray[$val] = $desc;
            }
        }
        return $listArray;
    }

    public function initComboEscritorio($objeto, $msg)
    {
        $escritorio = new Default_Service_Escritorio();
        return $escritorio->initComboEscritorio($objeto, $msg);
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

        $config = Zend_Registry::get('config');
        $dir = $config->resources->cachemanager->default->backend->options->logs_dir;
        $filename = 'bloqueio_projeto_' . date('Y-m-d') . '.txt';
        $path = $dir . $filename;
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
        //$diff = $hoje->sub($data)->toValue();
        //return ceil($diff / 60 / 60 / 24) + 1;
        $service = new Projeto_Service_AtividadeCronograma();
        //Calcula a diferenca de dias para prazo
        $entradaDiff['datainicio'] = $hoje;
        $entradaDiff['datafim'] = $data;
        $dias = $service->retornaQtdeDiasUteisEntreDatas($entradaDiff);
        //$diff = $hoje->sub($data)->toValue();
        //return ceil($diff / 60 / 60 / 24) + 1;
//        return floor($diff/86400);
        return $dias;
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

        $config = Zend_Registry::get('config');
        $host = $config->smtp->host;
        $configMail = array(
            'port' => $config->smtp->port,
            'auth' => 'login',
//                'ssl' => 'tls',
            'email' => $config->smtp->email,
            'username' => $config->smtp->username,
            'password' => $config->smtp->password
        );
        #inserir a senha de acesso ao email
        #se a senha nao estiver vazia envia o e-mail
        try {

            $mailTransport = new Zend_Mail_Transport_Smtp($host, $configMail);

            if (!empty($config['password'])) {
                # Define essa configuração para o envio de emails pelo Zend_Mail
                Zend_Mail::setDefaultTransport($mailTransport);

                $mail = new Zend_Mail('utf-8');
                $mail->setBodyHtml($textoEmailBloqueio);
                $mail->setSubject("GEPNET - Bloqueio de Projeto");
                $mail->setFrom($configMail['email'], "GEPNET - Gestor de Escritórios de Projetos ");

                $mail->addTo($params->emailpatrocinador);
                $mail->addTo($params->emailgerenteprojeto);
                $mail->addTo($params->emailgerenteadjunto);
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
        $mascara = mb_substr($vlrorcamentodisponivel, 0, -2) . '.' . mb_substr($vlrorcamentodisponivel, -2);
        $valor = number_format($mascara, 2, ',', '.');
        return $valor;
    }

    public function isGerenteORAdjuntoByEscritorio($idProjeto, $idEscritorio, $idGerente)
    {
        if ($this->_mapper->isGerenteORAdjuntoByEscritorio($idProjeto, $idEscritorio, $idGerente)) {
            return true;
        }
        return false;
    }

    public function updateNumProcessoSei($params)
    {
        return $this->_mapper->updateNumProcessoSei($params);
    }

    public function retornaNumProcessoSei($params)
    {
        return $this->_mapper->retornaNumProcessoSei($params);
    }

    public function verificaPublicos($params)
    {
        return $this->_mapper->retornaPublicos($params);
    }

    public function updatePartesProjeto($params)
    {
        return $this->_mapper->updatePartesProjeto($params);
    }

    public function updateTapAssinado($params)
    {
        return $this->_mapper->updateTapAssinado($params);
    }

}