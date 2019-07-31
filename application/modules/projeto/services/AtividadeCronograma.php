<?php

use Default_Service_Log as Log;

class Projeto_Service_AtividadeCronograma extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     * @var Projeto_Model_Mapper_Atividadecronograma
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_Atividadecronograma();
    }

    /**
     * @return Projeto_Form_AtividadeCronogramaGrupo
     */
    public function getFormGrupo($params = array())
    {
        $form = $this->_getForm('Projeto_Form_AtividadeCronogramaGrupo', array('submit', 'reset'));
        $form->populate($params);
        $form->populate(array('domtipoatividade' => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_GRUPO));
        return $form;
    }

    /**
     * @param array $params
     * @return Projeto_Form_AtividadeCronogramaEntrega
     */
    public function getFormEntrega($params = array())
    {
        $parteInteressada = new Projeto_Service_ParteInteressada();

        $form = $this->_getForm('Projeto_Form_AtividadeCronogramaEntrega', array('submit', 'reset'));
        $arrayGrupo = $this->fetchPairsGrupo($params);
        $selOpcaoGr = array('' => 'Selecione');
        $form->getElement('idgrupo')->setMultiOptions($selOpcaoGr + $arrayGrupo);
        $form->getElement('idparteinteressada')->setMultiOptions($parteInteressada->fetchPairsPorProjeto($params));
        $selOpcaoGrEnt = array('' => 'Selecione');
        $form->getElement('idgrupo')->setMultiOptions($selOpcaoGrEnt + $arrayGrupo);
        $form->getElement('idresponsavel')->setMultiOptions($parteInteressada->fetchPairsPorProjeto($params));
        $form->populate($params);
        $form->populate(array('domtipoatividade' => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_ENTREGA));
        return $form;
    }

    public function retornaMarcoById($params)
    {
        return $this->_mapper->retornaMarcoById($params);
    }


    /**
     * @return Projeto_Form_AtividadeCronograma
     */
    public function getFormAtividade($params)
    {
        $parteInteressada = new Projeto_Service_ParteInteressada();
        $form = $this->_getForm('Projeto_Form_AtividadeCronograma', array('submit', 'reset'));

        $elementoDespesa = new Default_Model_Mapper_Elementodespesa();

        $form->getElement('idgrupo')->setMultiOptions($this->fetchPairsEntrega(array('idprojeto' => $params['idprojeto'])));
        $form->getElement('idparteinteressada')->setMultiOptions($parteInteressada->fetchPairsPorProjeto($params));
        //$form->getElement('idresponsavel')->setMultiOptions($parteInteressada->fetchPairsPorProjeto($params));
        $form->getElement('predecessora')->setMultiOptions($this->fetchPairsAtividade($params, false));
        $form->getElement('idelementodespesa')->setMultiOptions($this->initCombo($elementoDespesa->fetchPairs(),
            "Selecione"));
        $form->populate($params);
        $form->populate(array(
            'domtipoatividade' => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_COMUM,
            'numfolga' => 0,
            'vlratividadebaseline' => 0,
            'vlratividade' => 0,
        ));
        return $form;
    }

    public function getFormAtividadeAtualizar($params, $para = true)
    {
        $parteInteressada = new Projeto_Service_ParteInteressada();
        $form = $this->_getForm('Projeto_Form_AtividadeCronograma', array('submit', 'reset'));
        $elementoDespesa = new Default_Model_Mapper_Elementodespesa();
        $form->getElement('idgrupo')->setMultiOptions($this->fetchPairsEntrega(array('idprojeto' => $params['idprojeto'])));

        $form->getElement('idparteinteressada')->setMultiOptions($parteInteressada->fetchPairsPorProjeto($params));
        if ($para) {
            $predecessoras = $this->fetchPairsAtividade($params, false);
            if ($predecessoras) {
                $form->getElement('predecessora')->setMultiOptions($predecessoras);
            }
        }

        $form->getElement('idelementodespesa')->setMultiOptions($this->initCombo($elementoDespesa->fetchPairs(),
            "Selecione"));
        $dataInicio = $this->retornarDataInicioAtividade($params);
        $form->getElement('datInicioHidden')->setAttrib('value', $dataInicio);
        $form->getElement('datinicio')->setAttrib('data-rule-dataatividadeferiado', true);
        $form->getElement('datfim')->setAttrib('data-rule-dataatividadeferiado', true);
        if ($this->verificarAtividadePredecessoras($params)) {
            $maiorData = $this->retornaInicioBaseLinePorAtividade($params);
            $form->getElement('datinicio')->setAttribs(array(
                'readonly' => true,
                'disabled' => 'disabled',
                'required' => false
            ));
            $form->getElement('maior_valor')->setAttrib('value', $maiorData);
            $form->getElement('datInicioHidden')->setAttrib('value', $maiorData);
        }
        $form->populate($params);
        $form->populate(array(
            'domtipoatividade' => (@trim($params['domtipoatividade']) != "" ? $params['domtipoatividade'] : Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_COMUM),
            //'domtipoatividade' => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_COMUM,
            'numfolga' => 0,
            'vlratividadebaseline' => 0,
            'vlratividade' => 0,
        ));
        return $form;
    }

    public function getFormAtividadeEditar($params, $para = true)
    {
        $parteInteressada = new Projeto_Service_ParteInteressada();
        $form = $this->_getForm('Projeto_Form_AtividadeCronograma',
            array('submit', 'reset', 'predecessora', 'predecessorasAtividade'));
        $elementoDespesa = new Default_Model_Mapper_Elementodespesa();
        $dataInicio = $this->retornarDataInicioAtividade($params);
        $form->getElement('datInicioHidden')->setAttrib('value', $dataInicio);

        $form->populate($params);
        $form->populate(array(
            'domtipoatividade' => (@trim($params['domtipoatividade']) != "" ? $params['domtipoatividade'] : Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_COMUM),
            'numfolga' => 0,
            'vlratividadebaseline' => 0,
            'vlratividade' => 0,
        ));
        return $form;
    }

    /**
     * Função que verifica se existe cronogrma
     * cadastrado para o projeto
     * @param int $idprojeto
     * @return boolean
     */
    public function isExisteCronograma($idprojeto)
    {
        return $this->_mapper->isExisteCrongrama($idprojeto);
    }


    public function retornaMenorDataFimBaseLineAndMaiorRealizadaCronogramaByProjeto($params)
    {
        return $this->_mapper->retornaMenorDataFimBaseLineAndMaiorRealizadaCronogramaByProjeto($params);
    }

    public function getFormAtividadeAtualizarPercentual($params)
    {
        $parteInteressada = new Projeto_Service_ParteInteressada();
        $form = $this->_getForm('Projeto_Form_AtividadeCronograma', array('submit', 'reset'));
        $elementoDespesa = new Default_Model_Mapper_Elementodespesa();

        $form->getElement('idgrupo')->setMultiOptions($this->fetchPairsEntrega(array('idprojeto' => $params['idprojeto'])));
        $form->getElement('idparteinteressada')->setMultiOptions($parteInteressada->fetchPairsPorProjeto($params));
        $form->getElement('predecessora')->setMultiOptions($this->fetchPairsAtividadePredecessora($params));
        $form->getElement('idelementodespesa')->setMultiOptions($this->initCombo($elementoDespesa->fetchPairs(),
            "Selecione"));
        $dataInicio = $this->retornarDataInicioAtividade($params);
        $form->getElement('datInicioHidden')->setAttrib('value', $dataInicio);
        if ($this->verificarAtividadePredecessoras($params)) {
            $maiorData = $this->retornaMaiorDataPredecessora($params);
            $form->getElement('maior_valor')->setAttrib('value', $maiorData);
        }

        $form->populate($params);
        $form->populate(array(
            'domtipoatividade' => (@trim($params['domtipoatividade']) != "" ? $params['domtipoatividade'] : Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_COMUM),
            'numfolga' => 0,
            'vlratividadebaseline' => 0,
            'vlratividade' => 0,
        ));
        return $form;
    }

    public function verificarAtividadePredecessoras($params)
    {
        if ($params) {
            $atividadePredecessoraService = new Projeto_Service_AtividadeCronoPredecessora();
            $resultado = $atividadePredecessoraService->retornaPredecePorIdAtividade($params);
            if (count($resultado) > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Projeto_Form_AtividadeCronogramaPesquisar
     */
    public function getFormAtividadePesquisar($params)
    {
        $parteInteressada = new Projeto_Service_ParteInteressada();
        $form = $this->_getForm('Projeto_Form_AtividadeCronogramaPesquisar');

        //$form->getElement('idgrupo')->setMultiOptions($this->fetchPairsEntrega($params));
        $form->getElement('idparteinteressada_pesq')->setMultiOptions($parteInteressada->fetchPairsPorProjeto($params));
        $form->populate(array('idprojeto_pesq' => $params['idprojeto']));
        return $form;
    }

    /**
     * @return Projeto_Form_AtividadeCronogramaMarco
     */
    public function getFormAtividadeMarco()
    {
        return $this->_getForm('Projeto_Form_AtividadeCronogramaMarco', array('submit', 'reset'));
    }

    public function getFormRelatorioCronograma()
    {
        return $this->_getForm('Projeto_Form_RelatorioCronograma', array());
    }

    public function inserir($dados)
    {
        $form = $this->getForm();

        if ($form->isValid($dados)) {
            $model = new Projeto_Model_Gerencia($form->getValues());
            return $this->_mapper->insert($model);
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function inserirGrupo($dados)
    {
        $form = $this->getFormGrupo($dados);

        if ($form->isValid($dados)) {
            $model = new Projeto_Model_Grupocronograma($form->getValues());
            $grupo = $this->_mapper->inserirGrupo($model);
            $this->_mapper->atualizaSucessoras($grupo->toArray());
            $this->_mapper->atualizarPercentualProjeto($dados);
            return $grupo;

        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function inserirEntrega($dados)
    {
        $serviceGerencia = new Projeto_Service_Gerencia();
        $form = $this->getFormEntrega($dados);

        if ($form->isValid($dados)) {
            $model = new Projeto_Model_Entregacronograma($form->getValues());
            $entrega = $this->_mapper->inserirEntrega($model);
            $this->_mapper->atualizaSucessoras($entrega->toArray());
            $this->_mapper->atualizarPercentualProjeto($dados);
            return $entrega;
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function inserirAtividade($dados)
    {
        if (isset($dados['flainformatica']) && $dados['flainformatica'] == 'N') {
            unset($dados['flainformatica']);
        }
        $form = $this->getFormAtividade($dados);
        if ($form->isValid($dados)) {
            $dados['numdiasrealizados'] = $dados['numdiasbaseline'];
            ///  $model = new Projeto_Model_Gerencia($form->getValues());
            $model = new Projeto_Model_Atividadecronograma($form->getValues());
            //verificar se os dias reais da atividade é maior que zero
            if ($model->numdiasrealizados > 0) {
                //soma os dias reais a data inicio da atividade sucessora
                $model->datfim = $this->preparaData($this->buscarProximoDiaUtil($model->datinicio->format('d/m/Y'),
                    $model->numdiasrealizados));
            } else {
                $model->datfim = $model->datinicio;
            }

            @set_time_limit(0);
            @ini_set('max_execution_time', 1800);
            /* @var $atividade Projeto_Model_Atividadecronograma */
            $atividade = $this->_mapper->inserirAtividade($model);

            $predecessorasIn = array();

            if (isset($dados['listaPredecessoras']) && trim($dados['listaPredecessoras']) != "") {
                $servicePrede = new Projeto_Service_AtividadeCronoPredecessora();
                if (is_numeric(strpos($dados['listaPredecessoras'], ';'))) {
                    $predecessorasIn = explode(";", $dados['listaPredecessoras']);
                    if (count($predecessorasIn) > 0) {
                        foreach ($predecessorasIn as $predecessora) {
                            $dadosInsert['idprojeto'] = $dados['idprojeto'];
                            $dadosInsert['idatividadepredecessora'] = $predecessora;
                            $dadosInsert['idatividade'] = $atividade->idatividadecronograma;
                            $predecessora = $servicePrede->inserir($dadosInsert);
                        }
                    }
                } else {
                    $dadosInsert = array(
                        'idatividadecronograma' => (int)$atividade->idatividadecronograma,
                        'idprojetocronograma' => (int)$dados['idprojeto'],
                        'idatividadepredecessora' => (int)$dados['listaPredecessoras'],
                    );
                    $predecessora = $servicePrede->inserir($dadosInsert);
                }
            }

            $dadosSucessora = array(
                'idprojeto' => $atividade->idprojeto,
                'idatividadecronograma' => $atividade->idatividadecronograma
            );

            $this->_mapper->atualizaSucessoras($dadosSucessora);
            $this->_mapper->atualizarPercentualProjeto($dados);
            return $atividade;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    /**
     * Calcula os dias de atraso do projeto
     * @param array $params
     * @return Object
     */

    public function calculaDiaAtrasoProjeto($params)
    {

        $datasCabecalho = $this->_mapper->retornaMaiorAndMenorDataPorProjeto($params);

        $totalDiasAtrasoFarol = 0;

        $descricaoAtrazoFarol = "default";
        if ((@trim($datasCabecalho[0]['datfimbaseline']) != "") && (@trim($datasCabecalho[0]['datfim']) != "")) {
            $datfimbaselineprojeto = new Zend_Date($datasCabecalho[0]['datfim'], 'd/m/Y');
            $datfimPlanejado = new Zend_Date($datasCabecalho[0]['datfimbaseline'], 'd/m/Y');
            //Calcula a diferenca de dias para prazo
            if (($datfimbaselineprojeto->equals($datfimPlanejado)) == false) {
                $entradaFarol['datainicio'] = $datfimbaselineprojeto->toString('d/m/Y');
                $entradaFarol['datafim'] = $datfimPlanejado->toString('d/m/Y');
                $totalDiasAtrasoFarol = $this->retornaQtdeDiasUteisEntreDatas($entradaFarol);
                /**********************************************************/
                /* retira um dia do cálculo para atender a regra definida */
                $totalDiasAtrasoFarol = $totalDiasAtrasoFarol * (-1);
                /**********************************************************/
                $totalDiasAtrasoFarol = ($totalDiasAtrasoFarol > 0 ? $totalDiasAtrasoFarol - 1 : $totalDiasAtrasoFarol + 1);
            }
            $descricaoAtrazoFarol = $this->retornaDescricaoFarol($datfimbaselineprojeto, $datfimPlanejado,
                $params['numcriteriofarol']);
        }
        $dadosAtraso = new stdClass();
        $dadosAtraso->totalDiasAtrasoFarol = $totalDiasAtrasoFarol;
        $dadosAtraso->descricaoAtrazoFarol = $descricaoAtrazoFarol;

        return $dadosAtraso;
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
            /* retira um dia do cálculo para atender a regra definida */
            if (($dataFimRealizada->equals($dataFimPlanejada)) == false) {
                $numEmDias = $this->retornaQtdeDiasUteisEntreDatas($dados);
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

    public function atualizarDatasEntrega(Projeto_Model_Atividadecronograma $model)
    {
        try {
            $entrega = $this->_mapper->retornaEntregaPorId(array(
                'idprojeto' => (int)$model->idprojeto,
                'idatividadecronograma' => (int)$model->idgrupo
            ), false);

            if (!empty($entrega) && is_object($entrega)) {
                $numDiasPlanejado = 0;
                $numDiasReais = 0;

                $maiorDataEntrega = $this->_mapper->retornaMaiorDataPorEntrega(array(
                    'idprojeto' => $model->idprojeto,
                    'idEntrega' => $entrega['idatividadecronograma'],
                    'paiEntrega' => $entrega['idgrupo']
                ));

                if (count($maiorDataEntrega) > 0) {
                    $dataPlanejada = new Zend_Date($maiorDataEntrega['datfimbaseline'], 'd/m/Y');
                    $dataRealizada = new Zend_Date($maiorDataEntrega['datiniciobaseline'], 'd/m/Y');

                    if (
                        (Zend_Date::isDate($dataPlanejada)) && (Zend_Date::isDate($dataRealizada))
                    ) {
                        if (($dataPlanejada->equals($dataRealizada)) == false) {
                            $dados['datainicio'] = $dataPlanejada->toString('d/m/Y');
                            $dados['datafim'] = $dataRealizada->toString('d/m/Y');

                            $numDiasPlanejado = $this->retornaQtdeDiasUteisEntreDatas($dados);
                            /**********************************************************/
                            /* retira um dia do cálculo para atender a regra definida */
                            $numDiasPlanejado = $numDiasPlanejado * (-1);
                            $numDiasPlanejado = ($numDiasPlanejado > 0 ? $numDiasPlanejado - 1 : $numDiasPlanejado + 1);
                            /**********************************************************/
                        }
                    }

                    $dataRealPlanejada = new Zend_Date($maiorDataEntrega['datfim'], 'd/m/Y');
                    $dataRealRealizada = new Zend_Date($maiorDataEntrega['datinicio'], 'd/m/Y');

                    if (
                        (Zend_Date::isDate($dataRealPlanejada)) && (Zend_Date::isDate($dataRealRealizada))
                    ) {
                        if (($dataRealPlanejada->equals($dataRealRealizada)) == false) {
                            $dadosReais['datainicio'] = $dataRealPlanejada->toString('d/m/Y');
                            $dadosReais['datafim'] = $dataRealRealizada->toString('d/m/Y');

                            $numDiasReais = $this->retornaQtdeDiasUteisEntreDatas($dadosReais);
                            /**********************************************************/
                            /* retira um dia do cálculo para atender a regra definida */
                            $numDiasReais = $numDiasReais * (-1);
                            $numDiasReais = ($numDiasReais > 0 ? $numDiasReais - 1 : $numDiasReais + 1);
                            /**********************************************************/
                        }
                    }

                }
                $entrega->numdiasbaseline = $numDiasPlanejado;
                $entrega->numdiasrealizados = $numDiasReais;
                $entrega->datiniciobaseline = $this->preparaData($maiorDataEntrega["datiniciobaseline"]);
                $entrega->datfimbaseline = $this->preparaData($maiorDataEntrega["datfimbaseline"]);
                $entrega->datinicio = $this->preparaData($maiorDataEntrega["datinicio"]);
                $entrega->datfim = $this->preparaData($maiorDataEntrega["datfim"]);
                $this->_mapper->atualizarDatasEntrega($entrega);
                $this->atualizarDatasGrupo($entrega);
                return true;
            }
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
        //}

    }

    public function preparaData($data)
    {
        if (isset($data) && (!empty($data))) {
            $dataZend = new Zend_Date($data, 'd/m/Y');
            if (Zend_Date::isDate($dataZend->toString('d/m/Y'))) {
                $dataRetornada = DateTime::createFromFormat('Y-m-d', $dataZend->toString('Y-m-d'));
                return $dataRetornada;
            }
        }
        return null;
    }

    public function preparaDataComBarra($data)
    {
        if (isset($data) && (!empty($data))) {
            $dataZend = new Zend_Date($data, 'Y-m-d');
            if (Zend_Date::isDate($dataZend->toString('d-m-Y'))) {
                return $dataZend->toString('d/m/Y');
            }
        }
        return null;
    }

    public function preparaValor($data)
    {
        $valor = 0.00;
        if (isset($data) && (!empty($data))) {
            $valor = mb_substr($data, 0, -2) . '.' . mb_substr($data, -2);
        }
        return number_format($valor, 2, ',', '.');
    }

    public function comparaData($data1, $data2)
    {
        $dtReferencia = new Zend_Date($data1);
        $dataComparada = new Zend_Date($data2);

        if ($dtReferencia->isLater($dataComparada) || $dtReferencia->equals($dataComparada)) {
            return true;
        }
        return false;
    }

    public function dataMenorOuIgual($data1, $data2)
    {
        $dtReferencia = new Zend_Date($data1, 'd/m/Y');
        $dataComparada = new Zend_Date($data2, 'd/m/Y');

        if ($dtReferencia->isEarlier($dataComparada) || $dtReferencia->equals($dataComparada)) {
            return true;
        }
        return false;
    }


    public function atualizarDatasGrupo(Projeto_Model_Entregacronograma $model)
    {
        if (!empty($model)) {

            $grupo = $this->_mapper->retornaGrupoPorId(array(
                'idprojeto' => $model->idprojeto,
                'idatividadecronograma' => $model->idgrupo
            ), true);

            if (is_object($grupo)) {
                $maiorDataGrupo = $this->_mapper->retornaDatasPorGrupo(array(
                    'idprojeto' => $model->idprojeto,
                    'idgrupo' => $model->idgrupo
                ));
                if (count($maiorDataGrupo) > 0) {
                    $dataPlanejada = new Zend_Date($maiorDataGrupo['datfimbaseline'], 'd/m/Y');
                    $dataRealizada = new Zend_Date($maiorDataGrupo['datiniciobaseline'], 'd/m/Y');
                    $numDiasPlanejado = 0;
                    $numDiasReais = 0;

                    if (
                        (Zend_Date::isDate($dataPlanejada)) && (Zend_Date::isDate($dataRealizada))
                    ) {
                        if (($dataPlanejada->equals($dataRealizada)) == false) {
                            $dados['datainicio'] = $dataPlanejada->toString('d/m/Y');
                            $dados['datafim'] = $dataRealizada->toString('d/m/Y');

                            $numDiasPlanejado = $this->retornaQtdeDiasUteisEntreDatas($dados);
                            /**********************************************************/
                            /* retira um dia do cálculo para atender a regra definida */
                            $numDiasPlanejado = $numDiasPlanejado * (-1);
                            $numDiasPlanejado = ($numDiasPlanejado > 0 ? $numDiasPlanejado - 1 : $numDiasPlanejado + 1);
                            /**********************************************************/
                        }
                    }
                    $dataRealPlanejada = new Zend_Date($maiorDataGrupo['datfim'], 'd/m/Y');
                    $dataRealRealizada = new Zend_Date($maiorDataGrupo['datinicio'], 'd/m/Y');

                    if (
                        (Zend_Date::isDate($dataRealPlanejada)) && (Zend_Date::isDate($dataRealRealizada))
                    ) {
                        if (($dataRealPlanejada->equals($dataRealRealizada)) == false) {
                            $dadosReais['datainicio'] = $dataRealPlanejada->toString('d/m/Y');
                            $dadosReais['datafim'] = $dataRealRealizada->toString('d/m/Y');


                            $numDiasReais = $this->retornaQtdeDiasUteisEntreDatas($dadosReais);
                            /**********************************************************/
                            /* retira um dia do cálculo para atender a regra definida */
                            $numDiasReais = $numDiasReais * (-1);
                            $numDiasReais = ($numDiasReais > 0 ? $numDiasReais - 1 : $numDiasReais + 1);
                            /**********************************************************/
                        }
                    }

                    $grupo->numdiasbaseline = $numDiasPlanejado;
                    $grupo->numdiasrealizados = $numDiasReais;
                    $grupo->datiniciobaseline = $this->preparaData($maiorDataGrupo["datiniciobaseline"]);
                    $grupo->datfimbaseline = $this->preparaData($maiorDataGrupo["datfimbaseline"]);
                    $grupo->datinicio = $this->preparaData($maiorDataGrupo["datinicio"]);
                    $grupo->datfim = $this->preparaData($maiorDataGrupo["datfim"]);
                    $retorno = $this->_mapper->atualizarDatasGrupo($grupo);
                }
            }
        }
    }


    /**
     * @param array $dados
     * @return boolean | array
     */
    public function atualizarGrupo($dados)
    {
        $serviceGerencia = new Projeto_Service_Gerencia();
        $form = $this->getFormGrupo();
        if ($form->isValidPartial($dados)) {
            $model = new Projeto_Model_Grupocronograma($form->getValues());
            $retorno = $this->_mapper->atualizarGrupo($model);
            $this->_mapper->atualizaSucessoras($dados);
            $this->_mapper->atualizarPercentualProjeto($dados);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }


    /**
     * @param array $dados
     * @return boolean | array
     */
    public function atualizarEntrega($dados)
    {

        $form = $this->getFormEntrega($dados);
        if ($form->isValidPartial($dados)) {
            $model = new Projeto_Model_Entregacronograma($form->getValues());
            $retorno = $this->_mapper->atualizarEntrega($model);
            $this->_mapper->atualizaSucessoras($dados);
            $this->_mapper->atualizarPercentualProjeto($dados);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    /**
     * Atualizar os percentuais do projeto
     * @param array $params
     * @return void
     */

    public function atualizarPercentualProjeto($params)
    {
        $this->_mapper->atualizarPercentualProjeto($params);
    }


    /**
     * Função de atualização dos dados de entrega pela edição na lisnha do cronograma
     * @param array $dados
     * @return boolean | array
     */
    public function atualizarEntregaInLine($dados)
    {
        try {
            $model = $this->retornaEntregaPorId($dados);
            $model->nomatividadecronograma = $dados['nomatividadecronograma'];
            $model->idparteinteressada = $dados['idparteinteressada'];
            $retorno = $this->_mapper->atualizarEntrega($model);
            $this->_mapper->atualizaSucessoras($dados);
            $this->_mapper->atualizarPercentualProjeto($dados);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            return false;
        }
    }


    /**
     * Função de atualização dos dados do grupo pela edição na linha do cronograma
     * @param array $dados
     * @return boolean | array
     */
    public function atualizarGrupoInLine($dados)
    {
        try {
            $model = $this->retornaGrupoPorId($dados, true, false);
            $model->nomatividadecronograma = $dados['nomatividadecronograma'];
            $model->idparteinteressada = $dados['idparteinteressada'];
            $retorno = $this->_mapper->atualizarGrupo($model);
            $this->_mapper->atualizaSucessoras($dados);
            $this->_mapper->atualizarPercentualProjeto($dados);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            return false;
        }
    }


    public function retornaMaiorDataPredecessora($params)
    {
        $predecessoraService = new Projeto_Service_AtividadePredecessora();
        return $predecessoraService->retornaMaiorDataPredecessoraByIdAtividade($params);
    }

    public function atualizarMapperAtividade(Projeto_Model_Atividadecronograma $model)
    {
        if (isset($model) && is_object($model)) {
            return $this->_mapper->atualizarAtividade($model);
        }
    }

    /**
     * @param array $dados
     * @return boolean | array
     */
    public function atualizarAtividade($dados)
    {
        if (isset($dados['flainformatica'])
            && (!empty($dados['flainformatica']))
            && $dados['flainformatica'] == 'N'
        ) {
            $dados['flainformatica'] = null;
        }
        $dados['orderAsc'] = 'S';
        $dados = array_filter($dados);
        $params = $this->montaArray($dados);
        $params = array_filter($params);
        $form = $this->getFormAtividadeAtualizar($params, false);
        $atividadePrecessoraMaiorData = null;
        $predecessorasIn = array();

        if ($form->isValidPartial($params)) {
            $data = null;
            $novaDataFim = null;
            $novaDatainicio = null;
            $novaData = null;
            $model = new Projeto_Model_Atividadecronograma($params);
            $servicePrede = new Projeto_Service_AtividadeCronoPredecessora();

            $servicePrede->excluirPorAtividade(
                array(
                    'idatividade' => $dados['idatividadecronograma'],
                    'idprojeto' => $dados['idprojeto']
                )
            );

            if (isset($dados['listaPredecessoras']) && (!empty($dados['listaPredecessoras']))) {
                if (is_numeric(strpos($dados['listaPredecessoras'], ';'))) {
                    $predecessorasIn = explode(";", $dados['listaPredecessoras']);
                    if (count($predecessorasIn) > 0) {

                        foreach ($predecessorasIn as $predecessora) {
                            $dadosInsert['idprojetocronograma'] = $dados['idprojeto'];
                            $dadosInsert['idatividadepredecessora'] = $predecessora;
                            $dadosInsert['idatividadecronograma'] = $dados['idatividadecronograma'];

                            if ($servicePrede->isPredecessora($dadosInsert) == false) {
                                $servicePrede->inserir($dadosInsert);
                            }
                        }
                    }
                } else {
                    $dadosInsert = array(
                        'idatividadecronograma' => (int)$model->idatividadecronograma,
                        'idprojetocronograma' => (int)$dados['idprojeto'],
                        'idatividadepredecessora' => (int)$dados['listaPredecessoras'],
                    );
                    $predecessora = $servicePrede->inserir($dadosInsert);
                }
            }

            $model->numdiasbaseline = null;
            $model->numdias = null;
            $model->numdiasbaseline = null;
            $model->datiniciobaseline = null;
            $model->datfimbaseline = null;

            @set_time_limit(0);
            @ini_set('max_execution_time', 1800);

            try {

                $retorno = $this->_mapper->atualizarAtividade($model);
                $dadosSucessora = array(
                    'idprojeto' => $dados['idprojeto'],
                    'idatividadecronograma' => $model->idatividadecronograma
                );

                $this->_mapper->atualizaSucessoras($dadosSucessora);
                $this->_mapper->atualizarPercentualProjeto($dados);

                return $model;
            } catch (Exception $exc) {
                Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
                throw $exc;
                return false;
            }
        } else {
            $this->errors = $form->getMessages();
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $this->errors));
            return false;
        }
    }


    /**
     * Retorna o total geral de percentual de conclusão dos marcos por projeto
     * formula utilizada: Percentual concluído = (numero de marcos 100% concluidos / numero total de marcos) * 100
     * @param array $params
     * @return array
     */

    public function retornaPercentualConcluidoMarcoByProjeto($params)
    {
        $totalConcluido = 0;
        $totalGeral = 0;
        $retorno = 0;

        $dados = $this->_mapper->retornaMarcosPorProjeto($params);

        if (count($dados) > 0) {
            $totalConcluido = $dados[0]['concluidos'];
            $totalGeral = $dados[0]['total'];
            $retorno = round(($totalConcluido / $totalGeral) * 100);
        }
        $retorno = str_replace(".", ",", $retorno);
        $retorno = mb_substr($retorno, 0, 5);
        return $retorno;
    }

    /**
     * Quantidade de atividade iniciada = Quantidade de atividades com % de andamento maior que zero e menor que cem
     * % de atividade iniciadas = (Quantidade de atividade iniciada/Total de atividades do projeto) * 100
     * Quantidade de atividade não iniciada = Quantidade de atividades com % de andamento igual a zero
     * % de atividade não iniciadas = (Quantidade de atividade não iniciada/Total de atividades do projeto) * 100
     * Quantidade de atividade concluída = Quantidade de atividades com % de andamento igual a cem
     * % de atividade concluídas = (Quantidade de atividade concluída/Total de atividades do projeto) * 100
     * @param array $params
     * @return array
     */

    public function retornaPercentuaisByProjeto($params)
    {
        $totalatividadeporprojeto = 0;
        $qtdeatividadeiniciada = 0;
        $qtdeatividadenaoiniciada = 0;
        $qtdeatividadeconcluida = 0;
        $percentIni = 0;
        $percentNaoIni = 0;
        $percentConcluido = 0;

        $dados = $this->_mapper->retornaQtdePercentualPorProjeto($params);

        if (count($dados) > 0) {
            if (!empty($dados['totalatividadeporprojeto'])) {
                $totalatividadeporprojeto = $dados['totalatividadeporprojeto'];

                if (!empty($dados['qtdeatividadeiniciada'])) {
                    $qtdeatividadeiniciada = $dados['qtdeatividadeiniciada'];
                    $percentIni = round(($qtdeatividadeiniciada / $totalatividadeporprojeto) * 100);
                    $percentIni = str_replace(".", ",", $percentIni);
                    $percentIni = mb_substr($percentIni, 0, 5);
                }

                if (!empty($dados['qtdeatividadenaoiniciada'])) {
                    $qtdeatividadenaoiniciada = $dados['qtdeatividadenaoiniciada'];
                    $percentNaoIni = round(($qtdeatividadenaoiniciada / $totalatividadeporprojeto) * 100);
                    $percentNaoIni = str_replace(".", ",", $percentNaoIni);
                    $percentNaoIni = mb_substr($percentNaoIni, 0, 5);
                }

                if (!empty($dados['qtdeatividadeconcluida'])) {
                    $qtdeatividadeconcluida = $dados['qtdeatividadeconcluida'];
                    $percentConcluido = round(($qtdeatividadeconcluida / $totalatividadeporprojeto) * 100);
                    $percentConcluido = str_replace(".", ",", $percentConcluido);
                    $percentConcluido = mb_substr($percentConcluido, 0, 5);
                }
            }
        }

        $data = array(
            'qtdeatividadeiniciada' => $qtdeatividadeiniciada,
            'numpercentualiniciado' => $percentIni,
            'qtdeatividadenaoiniciada' => $qtdeatividadenaoiniciada,
            'numpercentualnaoiniciado' => $percentNaoIni,
            'qtdeatividadeconcluida' => $qtdeatividadeconcluida,
            'numpercentualatividadeconcluido' => $percentConcluido,
        );
        return $data;
    }

    /**
     * Retorna o total geral de percentual de conclusão dos marcos por relatorio
     * @param array $params
     * @return array
     */
    public function getPercentualConcluidoMarcoByRelatorio($params)
    {
        $totalConcluido = 0;
        $totalGeral = 0;
        $retorno = 0;
        $dados = array();
        $dados = $this->_mapper->retornaMarcoPorStatusReport($params);

        if ($dados[0]['concluido'] > 0) {
            $totalConcluido = $dados[0]['concluido'];
            $totalGeral = $dados[0]['total'];
            $retorno = round(($totalConcluido / $totalGeral) * 100);
            $retorno = mb_substr($retorno, 0, 5);
        }

        return $retorno;
    }

    public function retornaUltmaDataFimCronograma($params)
    {
        return $this->_mapper->retornaUltmaDataFimCronograma($params);
    }


    /**
     * Retorna o total geral de percentual de conclusão dos marcos por relatorio
     * @param array $params
     * @return array
     */
    public function retornaPercentualMarcoPorDataEProjeto($params)
    {
        $totalConcluido = 0;
        $totalGeral = 0;
        $retorno = 0;
        $dados = array();
        $dados = $this->_mapper->retornaPercentualMarcoPorDataEProjeto($params);

        if (count($dados) > 0) {
            $totalConcluido = $dados[0]['concluido'];
            $totalGeral = $dados[0]['total'];
            if ($totalGeral != 0) {
                $retorno = round(($totalConcluido / $totalGeral) * 100);
            }
            $retorno = mb_substr($retorno, 0, 5);
        }
        return $retorno;
    }


    public function buscarProximoDiaUtil($data, $folga = 0)
    {
        $dataCheck = $data;
        $QtFeriado = $this->retornaDataFeriado(array('data' => $dataCheck));
        if (($this->dataFinaldeSemana($dataCheck)) || (($QtFeriado > 0))) {
            for ($r = 0; $r <= 11; $r++) {
                $dtaIn = explode("/", $dataCheck);
                $datearray = array('year' => date($dtaIn[2]), 'month' => date($dtaIn[1]), 'day' => date($dtaIn[0]));
                $zenddate = new Zend_Date($datearray);
                $zenddate->add(1, Zend_Date::DAY_SHORT);
                $dataCheck = $zenddate->toString('d/m/Y');
                $QtFeriado = $this->retornaDataFeriado(array('data' => $dataCheck));
                if (!(($this->dataFinaldeSemana($dataCheck)) || ($QtFeriado > 0))) {
                    break;
                }
            }
        }
        if ($folga > 0) {
            $dataCheck = $this->retornaDataFimValidaPorDias(
                array(
                    'datainicio' => $dataCheck,
                    'numdias' => $folga
                )
            );
        }
        return $dataCheck;
    }

    public function subtrairDias($data, $dias)
    {
        $novaData = new Zend_Date();
        $novaData->set($data);
        if ($dias > 0) {
            $novaData->subDay(abs($dias));
        }
        return mb_substr($novaData, 0, 10);
    }

    public function adicionarDias($data, $dias)
    {
        $novaData = new Zend_Date();
        $novaData->set($data);
        // Se for 1 retorna a mesma data
        if ($dias > 0) {
            $novaData->addDay(abs($dias));
            $novaData->subDay(abs(1));
        }
        return mb_substr($novaData, 0, 10);
    }

    public function compararDadas($data, $dtComparar)
    {
        $data1 = new Zend_Date();
        $data1->set($data);
        $data2 = new Zend_Date();
        $data2->set($dtComparar);
        if ($data1->isLater($data2) || $data1->equals($data2)) {
            return true;
        }
        return false;
    }

    function dataFinaldeSemana($dtStrInicio)
    {
        $partsIni = explode('/', $dtStrInicio);
        if ((intval($partsIni[0]) > 31) || (intval($partsIni[1]) > 12)
            || (intval($partsIni[2]) > 2250) || (intval($partsIni[2]) < 1900)
        ) {
            $rtn = false;
        }
        $time = mktime(0, 0, 0, $partsIni[1], $partsIni[0], $partsIni[2]);
        $weekday = date('w', $time);
        return ($weekday == 0 || $weekday == 6);
    }

    public function retornaAtividadeCronogramaByIdAtividade($params)
    {

        return $this->_mapper->retornaAtividadeCronogramaByIdAtividade($params);
    }

    public function retornaNumDiasProjeto($params)
    {

        return $this->_mapper->retornaNumDiasProjeto($params);
    }

    public function atualizarAtividadePercentual($dados)
    {
        $serviceGerencia = new Projeto_Service_Gerencia();
        $params = $this->montaArray($dados);
        $params = array_filter($params);
        $model = new Projeto_Model_Atividadecronograma($params);


        if ($model->domtipoatividade == "4") {
            $model->datfim = $model->datinicio;
            $model->numdias = 0;
            $model->numdiasrealizados = 0;
        }

        if ($this->dataMenorOuIgual($model->datfim->format('d/m/Y'), $model->datinicio->format('d/m/Y'))) {
            $model->datfim = $model->datinicio;
            if ($model->domtipoatividade == "4") {
                $model->numdias = 0;
                $model->numdiasrealizados = 0;
            } else {
                $model->numdias = 1;
                $model->numdiasrealizados = 1;
            }
        }
        $model->idparteinteressada = $dados['idparteinteressada'];

        try {
            $atividade = $this->_mapper->atualizarAtividade($model);
            $this->_mapper->atualizaSucessoras($atividade->toArray());
            $this->_mapper->atualizarPercentualProjeto($atividade->toArray());
            return $atividade;
        } catch (Exception $exception) {
            $this->errors[] = $exception->getMessage();
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exception));
            throw $exception;
            return false;
        }
    }

    private function montaArray($dados)
    {
        $params = null;
        $params = array(
            'idgrupo' => (empty($dados['idgrupo'])) ? null : $dados['idgrupo'],
            'domtipoatividade' => (empty($dados['domtipoatividade'])) ? null : $dados['domtipoatividade'],
            'nomatividadecronograma' => (empty($dados['nomatividadecronograma'])) ? null : $dados['nomatividadecronograma'],
            'numpercentualconcluido' => (empty($dados['numpercentualconcluido'])) ? null : $dados['numpercentualconcluido'],
            'flacancelada' => (empty($dados['flacancelada'])) ? null : $dados['flacancelada'],
            'flaaquisicao' => (empty($dados['flaaquisicao'])) ? null : $dados['flaaquisicao'],
            'idparteinteressada' => (empty($dados['idparteinteressada'])) ? null : $dados['idparteinteressada'],
            'predecessora' => (empty($dados['predecessora'])) ? null : $dados['predecessora'],
            'numfolga' => (empty($dados['numfolga'])) ? null : $dados['numfolga'],
            'idpredecessora' => (empty($dados['idpredecessora'])) ? null : $dados['idpredecessora'],
            'datInicioHidden' => (empty($dados['datInicioHidden'])) ? null : $dados['datInicioHidden'],
            'idprojeto' => (empty($dados['idprojeto'])) ? null : $dados['idprojeto'],
            'idatividadecronograma' => (empty($dados['idatividadecronograma'])) ? null : $dados['idatividadecronograma'],
            'vlratividadebaseline' => (empty($dados['vlratividadebaseline'])) ? null : $dados['vlratividadebaseline'],
            'numdiasrealizados' => (empty($dados['numdiasrealizados'])) ? null : $dados['numdiasrealizados'],
            'datinicio' => (empty($dados['datinicio'])) ? null : $dados['datinicio'],
            'datfim' => (empty($dados['datfim'])) ? null : $dados['datfim'],
            'datiniciobaseline' => (empty($dados['datiniciobaseline'])) ? null : $dados['datiniciobaseline'],
            'datfimbaseline' => (empty($dados['datfimbaseline'])) ? null : $dados['datfimbaseline'],
            'vlratividade' => (empty($dados['vlratividade'])) ? null : $dados['vlratividade'],
            'flainformatica' => (empty($dados['flainformatica'])) ? null : $dados['flainformatica'],
            'desobs' => (empty($dados['desobs'])) ? null : $dados['desobs'],
            'idelementodespesa' => (empty($dados['idelementodespesa'])) ? null : $dados['idelementodespesa'],
        );

        return $params;
    }

    /**
     * REFACTORING: Funcionalidade de excluir atividade
     * @param $dados
     * @return int
     * @throws Exception
     */
    public function excluir($dados)
    {
        return $this->_mapper->excluir($dados);
    }

    /**
     * REFACTORING: Funcionalidade de excluir atividade
     * @param $dados
     * @return int
     * @throws Exception
     */
    public function excluirGrupo($dados)
    {
        try {
            $retorno = $this->_mapper->excluir($dados);
            $this->_mapper->atualizaSucessoras($dados);
            $this->_mapper->atualizarPercentualProjeto($dados);
            return $retorno;
        } catch (Exception $exception) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exception));
            throw $exception;
        }
    }

    public function getAtividadeById($atividade, $projeto)
    {
        return $this->_mapper->getAtividadeById($atividade, $projeto);
    }

    public function retornaAtividadeById($params)
    {
        return $this->_mapper->retornaAtividadeById($params);
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    public function getAtividadeByProjetoId($dados)
    {
        return $this->_mapper->getAtividadeByProjetoId($dados);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function retornaGrupoPorId($params, $model = false, $collection = false)
    {
        return $this->_mapper->retornaGrupoPorId($params, $model, $collection);
    }

    public function validaPredecessoraAtividade($dados)
    {
        return $this->_mapper->retornaValidaPredecessoraAtividade($dados);
    }

    public function fetchPairsGrupo($params)
    {
        return $this->_mapper->fetchPairsGrupo($params);
    }

    public function fetchPairsEntrega($params)
    {
        $resultado = $this->_mapper->fetchPairsEntrega($params);
        $retorno = array('' => 'Selecione');
        return $retorno + $resultado;
    }

    public function fetchPairsAtividade($params, $sel = true)
    {
        $resultado = $this->_mapper->fetchPairsAtividade($params);
        if ($sel) {
            $retorno = array(
                '' => 'Selecione'
            );
            return $retorno + $resultado;
        }
        return $resultado;
    }

    public function fetchPairsAtividadePredecessora($params, $selecione = true)
    {
        $resultado = $this->_mapper->fetchPairsAtividadePredecessora($params);
        if ($selecione) {
            $retorno = array(
                '' => 'selecione'
            );
            return $retorno + $resultado;
        } else {
            return $resultado;
        }
    }

    public function retornaAtividadePorProjeto($params)
    {
        $resultado = $this->_mapper->retornaAtividadePorProjeto($params);
        return $resultado;
    }

    public function retornaIdAtividadePorEntrega($idprojeto, $idgrupoEntrega)
    {
        $resultado = $this->_mapper->retornaIdAtividadePorEntrega($idprojeto, $idgrupoEntrega);
        return $resultado;
    }

    public function retornaListaAtividadesPorEntrega($idprojeto, $idgrupoEntrega, $predecessora)
    {
        $resultado = $this->_mapper->retornaListaAtividadesPorEntrega($idprojeto, $idgrupoEntrega, $predecessora);
        return $resultado;
    }

    public function retornaIdEntregaPorGrupo($params)
    {
        $resultado = $this->_mapper->retornaIdEntregaPorGrupo($params);
        return $resultado;
    }

    public function retornaIdAtividadePorProjeto($params)
    {
        $resultado = $this->_mapper->retornaIdAtividadePorProjeto($params);
        return $resultado;
    }

    public function fetchPairsMarcosPorAceite($params)
    {
        return $this->_mapper->retornaMarcosPorAceite($params);
    }

    public function fetchPairsMarcosPorEntrega($params)
    {
        return $this->_mapper->fetchPairsMarcoPorEntrega($params);

    }

    public function retornaInicioBaseLinePorAtividade($params)
    {
        return $this->_mapper->retornaInicioBaseLinePorAtividade($params);
    }

    public function retornaInicioBaseLineComFolgaPorAtividade($params)
    {

        $resultado = $this->_mapper->retornaInicioBaseLineComFolgaPorAtividade($params);
//        Zend_Debug::dump($resultado);die;
        $novaDataFim = "";
        if ($resultado['numfolga'] > 0) {
            if ($resultado['datfim'] != "") {
                $numFolga = $resultado['numfolga'];
                $DataFim = $this->preparaData($resultado['datfim']);
                $novaDataFim = $this->retornaDataFimValidaPorDias(
                    array(
                        'datainicio' => $DataFim->format('d/m/Y'),
                        'numdias' => ($params['domtipoatividade'] == "3" ? $numFolga + 2 : $numFolga + 1)
                    )
                );
            }
        } else {
            if ($resultado['datfim'] != "") {
                $DataFim = $this->preparaData($resultado['datfim']);
                $novaDataFim = $this->retornaDataFimValidaPorDias(
                    array(
                        'datainicio' => $DataFim->format('d/m/Y'),
                        'numdias' => ($params['domtipoatividade'] == "3" ? 2 : 1)
                    )
                );
            }
        }
        return $novaDataFim;
    }

    public function retornaInicioBaseLinePorPredecessoras($params)
    {
        $predecessoras = array();
        if (isset($params['idpredecessora']) && count($params['idpredecessora']) > 0) {
            foreach ($params['idpredecessora'] as $id) {
                $predecessoras[] = $id;
            }
        }

        $params['predecessora'] = $predecessoras;
        return $this->_mapper->retornaInicioBaseLinePorPredecessoras($params);
    }

    public function retornaInicioRealPorPredecessoras($params)
    {
        return $this->_mapper->retornaInicioRealPorPredecessoras($params);
    }

    public function retornaEntregasEMarcosPorProjetoRelatorio($params)
    {
        return $this->_mapper->retornaEntregasEMarcosPorProjetoRelatorio($params);
    }


    public function retornaEntregasEMarcosPorProjeto($params, $parteinteressada = false)
    {
        try {
            $mapperProjeto = new Projeto_Model_Mapper_Gerencia();
            $projeto = $mapperProjeto->retornaProjetoPorId($params);
        } catch (Exception $exc) {
            var_dump($exc);
        }
        $retorno = $this->_mapper->retornaEntregasPorProjeto($params);
        if (count($retorno) > 0) {
            for ($i = 0; $i < count($retorno); $i++) {
                $paramAtividade = array(
                    'idprojeto' => $retorno[$i]->idprojeto,
                    'idatividadecronograma' => $retorno[$i]->idatividadecronograma
                );
                $resultado = $this->_mapper->getAtividadeByProjetoId($paramAtividade, true);
                $prazo = $resultado->retornaPrazo($projeto->numcriteriofarol);
                $retorno[$i]->descricaoprazo = $prazo->descricao;
                $retorno[$i]->prazo = $prazo->dias;
                if ($parteinteressada) {
                    $serviceParteInteressada = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
                    $parteInteressada = $serviceParteInteressada->getParteInteressada(array('idparteinteressada' => $retorno[$i]->idparteinteressada));
                    if (isset($parteInteressada['idpessoa'])) {
                        $retorno[$i]->nomparteinteressada = $parteInteressada['nompessoa'];
                    } else {
                        $retorno[$i]->nomparteinteressada = $parteInteressada['nomparteinteressada'];
                    }
                }
                $retMarcos = $this->_mapper->retornaMarcosPorEntregaEProjeto(array(
                    'idprojeto' => $retorno[$i]->idprojeto,
                    'idgrupo' => $retorno[$i]->idatividadecronograma,
                ));
                if (count($retMarcos) > 0) {
                    for ($k = 0; $k < count($retMarcos); $k++) {
                        $paramAtividadeMarcos = array(
                            'idprojeto' => $retMarcos[$k]['idprojeto'],
                            'idatividadecronograma' => $retMarcos[$k]['idatividadecronograma']
                        );
                        $resultadoMarcos = $this->_mapper->getAtividadeByProjetoId($paramAtividadeMarcos, true);
                        $prazoMarcos = $resultadoMarcos->retornaPrazo($projeto->numcriteriofarol);
                        $retMarcos[$k]['descricaoprazo'] = $prazoMarcos->descricao;
                        $retMarcos[$k]['prazo'] = $prazoMarcos->dias;
                    }
                }
                $retorno[$i]->marcos = $retMarcos;
            }
        }

        return $retorno;
    }

    public function fetchPairsMarcosPorProjeto($params, $parteinteressada = false)
    {
        $retorno = $this->_mapper->fetchPairsMarcosPorProjeto($params);
        if ($parteinteressada) {
            if (count($retorno) > 0) {
                for ($i = 0; $i < count($retorno); $i++) {
                    $serviceParteInteressada = App_Service_ServiceAbstract::getService('Projeto_Service_ParteInteressada');
                    $parteInteressada = $serviceParteInteressada->getParteInteressada(array('idparteinteressada' => $retorno[$i]['idparteinteressada']));
                    if (isset($parteInteressada['idpessoa'])) {
                        $retorno[$i]['nomparteinteressada'] = $parteInteressada['nompessoa'];
                    } else {
                        $retorno[$i]['nomparteinteressada'] = $parteInteressada['nomparteinteressada'];
                    }
                }
            }
        }
        return $retorno;
    }

    /**
     * @param array $params
     * @param boolean $array
     * @return Projeto_Model_AtividadeCronograma or array
     */
    public function retornaEntregaPorId($params, $array = false, $collection = false)
    {
        return $this->_mapper->retornaEntregaPorId($params, $array, $collection);
    }

    public function retornaProximoMarco($params, $array = false)
    {
        return $this->_mapper->retornaProximoMarco($params);
    }

    public function retornaUltimoMarco($params, $array = false)
    {
        return $this->_mapper->retornaUltimoMarco($params);
    }

    public function retornaDataPeriodo($params, $array = false)
    {
        return $this->_mapper->retornaDatasDoPeriodo($params);
    }

    /**
     * @param array $params
     * @param boolean $array
     * @return Projeto_Model_AtividadeCronograma or array
     */
    public function retornaAtividadePorId($params, $predecessoras = false, $pairspredecessoras = false)
    {
        return $this->_mapper->retornaAtividadePorId($params, $predecessoras, $pairspredecessoras);
    }

    public function retornaPgpAssinadoPorId($params)
    {
        return $this->_mapper->retornaPgpAssinadoPorId($params);
    }

    public function atividadeAtualizarPercentual($params)
    {
        $model = new Projeto_Model_Atividadecronograma($params);
        $atividade = $this->_mapper->atividadeAtualizarPercentual($model);
        $this->atualizarDatasEntrega($atividade);
        $this->atualizarPercentuaisGrupoEntrega(array(
            'idprojeto' => $atividade->idprojeto,
            'idatividadecronograma' => $atividade->idatividadecronograma
        ));
        return $atividade;
    }


    public function atualizarPercentuaisGrupoEntrega($params, Projeto_Model_Grupocronograma $grupo = null)
    {
        $obj = new Projeto_Model_Atividadecronograma();
        $db = $this->_db;
        $db->beginTransaction();

        try {
            if (is_null($grupo)) {
                /*##################entrega############################*/
                /* @var $entrega Projeto_Model_Entregacronograma */
                $arrayEntrega = $this->retornaAtividadeCronogramaByIdAtividade($params);
                $entrega = new Projeto_Model_Entregacronograma($arrayEntrega);

                $arrayatividadeEntrega = $this->_mapper->retornaAtividadesPorEntrega(array(
                    'idprojeto' => $params['idprojeto'],
                    'idatividadecronograma' => $params['idatividadecronograma']
                ));

                $arrayatividade = array();
                if (count($arrayatividadeEntrega) > 0) {
                    foreach ($arrayatividadeEntrega as $atividade) {

                        /** @var Projeto_Model_Atividadecronograma $modelAtividade */
                        $modelAtividade = new Projeto_Model_Atividadecronograma($atividade);
                        $arrayatividade[] = $modelAtividade;
                    }
                }

                if (count($arrayatividade) > 0) {
                    $entrega->atividades = $arrayatividade;
                    $dados = $entrega->toArray();
                    $percentuais = $entrega->retornaPercentuais();
                    $dados['numpercentualconcluido'] = $percentuais->numpercentualconcluido;
                    $obj->setFromArray($dados);
                    $retorno = $this->_mapper->atualizarPercentuaisGrupoEntrega($obj);
                }

                $arr = array(
                    'idprojeto' => $entrega->idprojeto,
                    'idatividadecronograma' => $entrega->idgrupo
                );
                $arrayGrupo = $this->retornaAtividadeCronogramaByIdAtividade($arr);
                $grupo = new Projeto_Model_Grupocronograma($arrayGrupo);
            }

            $arrayEngregaGrupo = $this->_mapper->retornaEntregasPorGrupo(array(
                'idprojeto' => (int)$grupo->idprojeto,
                'idatividadecronograma' => (int)$grupo->idatividadecronograma
            ));

            $arrayaEntrega = array();
            if (count($arrayEngregaGrupo) > 0) {
                foreach ($arrayEngregaGrupo as $entrega) {
                    /** @var Projeto_Model_Entrega $modelEntrega */
                    $modelEntrega = new Projeto_Model_Entregacronograma($entrega);
                    $arrayaEntrega[] = $modelEntrega;
                }
            }

            $grupo->entregas = $arrayaEntrega;
            $percentuaisGrupo = $grupo->retornaPercentuais();
            $dadosGrupo['numdiasrealizados'] = $grupo->retornaDiasReal();
            $dadosGrupo['idprojeto'] = $grupo->idprojeto;
            $dadosGrupo['idatividadecronograma'] = $grupo->idatividadecronograma;
            $dadosGrupo['numpercentualconcluido'] = $percentuaisGrupo->numpercentualconcluido;
            $obj->setFromArray($dadosGrupo);
            $retorno = $this->_mapper->atualizarPercentuaisGrupoEntrega($obj);
            $db->commit();
        } catch (Exception $exc) {
            $db->rollBack();
            throw $exc;
        }
    }

    public function retornaEntrega($params)
    {
        return $this->_mapper->retornaEntrega($params);
    }

    public function retornaEntregaPorAtividade($params)
    {
        return $this->_mapper->retornaEntregaPorAtividade($params);
    }

    public function atualizarTipoAtividade($params)
    {
        $serviceGerencia = new Projeto_Service_Gerencia();
        $dados = array_filter($params);
        $params = $this->montaArray($dados);
        $params = array_filter($params);
        $model = new Projeto_Model_Atividadecronograma($params);
        $datBaseLine = $this->retornaInicioBaseLinePorAtividade($params);
        if (isset($datBaseLine) && (trim($datBaseLine)) != "") {
            $dataPredecessora = $this->preparaData($datBaseLine);
            $model->numfolga = (int)$model->numfolga;
            $model->numdiasrealizados = (int)$model->numdiasrealizados;
            $nFolga = 0;
            if ($dataPredecessora) { //Verifica maior data predecessora
                if ($params['domtipoatividade'] == "3") {
                    if ($model->numfolga >= 0) {
                        if ($model->numfolga > 0) {
                            $nFolga = $nFolga + 2;
                            $nFolga = $nFolga + $model->numfolga;
                            $model->datinicio = $this->preparaData($this->retornaDataFimValidaPorDias(
                                array(
                                    'datainicio' => $dataPredecessora->format('d/m/Y'),
                                    'numdias' => $nFolga
                                )
                            ));
                        } else {
                            $nFolga = $nFolga + 2;
                            $nFolga = $nFolga + $model->numfolga;
                            $model->datinicio = $this->preparaData($this->retornaDataFimValidaPorDias(
                                array(
                                    'datainicio' => $dataPredecessora->format('d/m/Y'),
                                    'numdias' => $nFolga
                                )
                            ));
                        }
                    } else {
                        $nFolga = $nFolga + ($model->numfolga * (-1));
                        $model->datinicio = $this->preparaData($this->retornaDataAnteriorValidaPorDias(
                            array(
                                'datainicio' => $dataPredecessora->format('d/m/Y'),
                                'numdias' => $nFolga
                            )
                        ));
                    }
                    $model->numdiasrealizados = 1;
                    $model->datfim = $model->datinicio;
                } else {
                    if ($model->numfolga >= 0) {
                        $nFolga = $nFolga + 1;
                        $nFolga = $nFolga + $model->numfolga;
                        $model->datinicio = $this->preparaData($this->retornaDataFimValidaPorDias(
                            array(
                                'datainicio' => $dataPredecessora->format('d/m/Y'),
                                'numdias' => $nFolga
                            )
                        ));
                    } else {
                        $nFolga = $nFolga + ($model->numfolga * (-1));
                        $nFolga = $nFolga + 1;
                        $model->datinicio = $this->preparaData($this->retornaDataAnteriorValidaPorDias(
                            array(
                                'datainicio' => $dataPredecessora->format('d/m/Y'),
                                'numdias' => $nFolga
                            )
                        ));
                    }
                    $model->numdiasrealizados = 0;
                    $model->datfim = $model->datinicio;
                }
            } else {
                $model->datfim = $model->datinicio;
                if ($params['domtipoatividade'] == "4") {
                    $model->numdiasrealizados = 0;
                } else {
                    $model->numdiasrealizados = 1;
                    $model->numdias = 1;
                }
                $model->numfolga = 0;
            }
        } else {
            $model->datfim = $model->datinicio;
            if ($params['domtipoatividade'] == "4") {
                $model->numdiasrealizados = 0;
            } else {
                $model->numdiasrealizados = 1;
                $model->numdias = 1;
            }
        }
        if ($model->datfim < $model->datinicio) {
            $model->datfim = $model->datinicio;
        }
        if ($model->domtipoatividade == "4") {
            $model->datfim = $model->datinicio;
            $model->numdias = 0;
            $model->numdiasrealizados = 0;
        }
        $model->numdiasbaseline = null;
        $model->numdiasbaseline = null;
        $model->datiniciobaseline = null;
        $model->datfimbaseline = null;
        @set_time_limit(0);
        @ini_set('max_execution_time', 1800);
        try {
            $atividade = $this->_mapper->atualizarAtividade($model);
            $this->_mapper->atualizaSucessoras($model->toArray());
            $this->_mapper->atualizarPercentualProjeto($model->toArray());
            return $model;
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function pesquisar($params)
    {
        $servicoGerencia = new Projeto_Service_Gerencia();
        $arrayCronograma['cronograma'] = $this->_mapper->pesquisar($params);

        $params['idprojeto'] = $params['idprojeto_pesq'];
        /**@var Projeto_Model_Gerencia $projeto */
        $projeto = $servicoGerencia->retornaProjetoArrayPorId($params, false);

        $arrayProjeto = $projeto->toArray();

        $datas = $this->_mapper->retornaDataPorProjeto($params);


        $descricaProjeto =

        $custo =
        $arrayProjeto['nomprojeto'] = $projeto->nomprojeto;
        $arrayProjeto['nomprojeto'] = $projeto->nomprojeto;
        $arrayProjeto['datiniciobaseline'] = $datas['datiniciobaseline'];
        $arrayProjeto['datfimbaseline'] = $datas['datfimbaseline'];
        $arrayProjeto['datinicioReal'] = $datas['datinicio'];
        $arrayProjeto['datfimReal'] = $datas['datfim'];
        $arrayProjeto['vlratividadet'] = (!empty($projeto['vlratividadet']) && $projeto['vlratividadet'] > 0) ? mb_substr($projeto['vlratividadet'],
                0, -2) . '.' . mb_substr($projeto['vlratividadet'], -2) : number_format(0, 2);
        $arrayProjeto['numdiasbaseline'] = $datas['numdiasbaseline'];
        $arrayProjeto['numdiasrealizados'] = $datas['totaldiasrealizados'];
        $arrayCronograma['projeto'] = $arrayProjeto;

        return $arrayCronograma;
    }

    /**
     * REFACTORING: Funcionalidade de excluir atividade
     * @param $dados
     * @return int
     * @throws Exception
     */
    public function excluirAtividade($dados)
    {
        try {
//            $arrayatividade = $this->retornaAtividadeCronogramaByIdAtividade($dados);
//            $model = new Projeto_Model_Atividadecronograma($arrayatividade);
            $retorno = $this->excluir($dados);
            $this->_mapper->atualizaSucessoras($dados);
            $this->_mapper->atualizarPercentualProjeto($dados);
            return $retorno;
        } catch (Exception $exception) {
            $this->errors[] = $exception->getMessage();
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exception));
            throw $exception;
        }
    }

    public function excluirAtividadeSemSucessora($dados)
    {
        $serviceAtividadePredecessora = new Projeto_Service_AtividadeCronoPredecessora();
        try {
            $rsAtividade = $this->retornaAtividadePorId($dados, true);
            foreach ($rsAtividade['predecessoras'] as $sucessora) {
                $dadosExcluirPredecessora = array(
                    'idprojeto' => $sucessora['idprojeto'],
                    'idatividade' => $sucessora['idatividade'],
                    'idatividadepredecessora' => $sucessora['idatividadepredecessora']
                );
                $serviceAtividadePredecessora->excluir($dadosExcluirPredecessora);
            }
            $grupo = $this->_mapper->retornaGrupoPorAtividade($dados);
            $retorno = $this->excluir($dados);
            $this->atualizarPercentuaisGrupoEntrega($dados, $grupo);
            return $retorno;
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function excluirAtividadeSucessorasByPredecessora($entitySucessoras, $predecessora)
    {
        $service = new Projeto_Service_AtividadePredecessora();
        foreach ($entitySucessoras as $sucessora) {
            $dadosExcluirPredecessora = array(
                'idprojeto' => $predecessora['idprojeto'],
                'idatividade' => $sucessora['idatividadecronograma'],
                'idatividadepredecessora' => $predecessora['idatividadecronograma']
            );
            $service->excluir($dadosExcluirPredecessora);
        }
    }


    public function excluirComPredecessora($dados)
    {
        try {
            $grupo = $this->_mapper->retornaGrupoPorAtividade($dados);
            $retorno = $this->_mapper->excluirComPredecessora($dados);
            if ($retorno) {
                $this->atualizarPercentuaisGrupoEntrega($dados, $grupo);
            }
            return $retorno;
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function excluirEntrega($dados)
    {
        try {
            $retorno = $this->_mapper->excluir($dados);
            $this->_mapper->atualizaSucessoras($dados);
            $this->_mapper->atualizarPercentualProjeto($dados);
            return $retorno;
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function clonarGrupo($params)
    {
        $serviceGerencia = new Projeto_Service_Gerencia();
        $grupo = $this->_mapper->retornaGrupoPorId($params, true);
        $entregas = $this->_mapper->retornaEntrega(array(
            'idprojeto' => $params['idprojeto'],
            'idgrupo' => $params['idatividadecronograma']
        ));
        $servicePredecessora = new Projeto_Service_AtividadePredecessora();
        $db = $this->_db;
        $db->beginTransaction();
        try {
            $modelGrupo = $this->_mapper->inserirGrupo($grupo);
            if (count($entregas) > 0) {
                foreach ($entregas as $ent) {
                    $ent->idgrupo = $modelGrupo->idatividadecronograma;
                    $modelEntrega = $this->_mapper->inserirEntrega($ent);
                    if (count($ent->atividades) > 0) {
                        foreach ($ent->atividades as $ativ) {
                            /* @var $ativ Projeto_Model_Atividadecronograma */
                            $idAtividadeAnterior = $ativ->idatividadecronograma;
                            $ativ->idgrupo = $modelEntrega->idatividadecronograma;
                            if (count($ativ->predecessoras) > 0) {
                                $ativ->numfolga = 0;
                                $ativ->predecessoras = null;
                            } else {
                                $ativ->numfolga = 0;
                            }
                            $novaModelAtividade = $this->_mapper->inserirAtividade($ativ);
                            $ativ->idatividadecronograma = $novaModelAtividade->idatividadecronograma;
                            $ativ->idgrupo = $novaModelAtividade->idgrupo;
                            $this->atualizarDatasEntrega($ativ);
                        }
                    }
                }
                $arrayProjeto = $serviceGerencia->retornaArrayProjetoPorId($params);

                $this->_mapper->atualizaSucessoras($params);
                $this->_mapper->atualizarPercentualProjeto($params);
            }
            $db->commit();
        } catch (Exception $exc) {
            $db->rollBack();
            throw $exc;
        }
        return true;
    }

    public function clonarEntrega($params)
    {

        $entrega = $this->_mapper->retornaEntregaPorId($params);
        $atividades = $this->_mapper->retornaAtividade(array(
            'idprojeto' => $params['idprojeto'],
            'idgrupo' => $params['idatividadecronograma']
        ));
        $db = $this->_db;
        $db->beginTransaction();
        try {
            $modelEntrega = $this->_mapper->inserirEntrega($entrega);
            if (count($atividades) > 0) {
                foreach ($atividades as $ativ) {
                    /* @var $ativ Projeto_Model_Atividadecronograma */
                    $ativ->idgrupo = $modelEntrega->idatividadecronograma;

                    if (count($ativ->predecessoras) > 0) {
                        $ativ->numfolga = 0;
                        $ativ->predecessoras = null;
                    } else {
                        $ativ->numfolga = 0;
                    }
                    $this->_mapper->inserirAtividade($ativ);
//                    $this->atualizarDatasEntrega($ativ);
                }
//                $serviceGerencia = new Projeto_Service_Gerencia();
//                $arrayProjeto = $serviceGerencia->retornaArrayProjetoPorId($params);
//                $serviceGerencia->atualizaAtrasoEPercentualMarcoProjeto($arrayProjeto);
//                $serviceGerencia->atualizaPercentuaisAtividadesPorProjeto($arrayProjeto);
//                $serviceGerencia->atualizaPercentualProjeto($params);
            }
            $dados = array(
                'idprojeto' => $modelEntrega->idprojeto,
                'idatividadecronograma' => $modelEntrega->idatividadecronograma
            );
            $this->_mapper->atualizaSucessoras($dados);
            $this->_mapper->atualizarPercentualProjeto($dados);
            $db->commit();
        } catch (Exception $exc) {
            $db->rollBack();
            throw $exc;
        }
        return true;
    }

    public function pesquisarProjeto($params, $paginator)
    {

        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $dados = $mapperGerencia->pesquisarProjetoCronograma($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service->toJqgrid();
        }
        return $dados;
    }

    public function copiarCronograma($params)
    {
        $serviceGerencia = new Projeto_Service_Gerencia();
        $auth = Zend_Auth::getInstance();
        $params['idcadastrador'] = $auth->getIdentity()->idpessoa;

        try {

            $this->_mapper->copiarCronograma($params);
//            $serviceGerencia->atualizaPercentualProjeto(
//                array(
//                    'idprojeto' => $params['idprojetoorigem']
//                )
//            );
            return true;
        } catch (Exception $exc) {
            throw $exc;
            return false;
        }
    }


    public function retornaArrayCronogramaPorId($params)
    {
        try {
            $cronograma = $this->retornaGrupoPorProjeto($params);
        } catch (Exception $exc) {
            var_dump($exc);
        }
        $servicoAtividadeCronograma = new Projeto_Service_AtividadeCronograma();
        if (isset($cronograma) && !empty($cronograma)) {
            $contAtiv = 0;
            $contEnt = 0;
            foreach ($cronograma as $g => $grupo) {
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
                            $prazoEn = $entrega->retornaPrazo($cronograma->numcriteriofarol);
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
                $cronogramaArray['grupos'][$g] = $gr;
            }

            if (isset($cronogramaArray['grupos']) && ($cronogramaArray['grupos'] != null)) {
                $cronogramaArray['contGrupo'] = count($cronogramaArray['grupos']);
                $cronogramaArray['contEntrega'] = $contEnt;
                $cronogramaArray['contAtividade'] = $contAtiv;
            } else {
                $cronogramaArray['contGrupo'] = 0;
                $cronogramaArray['contEntrega'] = 0;
                $cronogramaArray['contAtividade'] = 0;
            }

        }
        //$cronogramaArray['ultimoStatusReport']['datfimprojetotendencia'] = date($projetoArray['ultimoStatusReport']['datfimprojetotendencia']);
        return $cronogramaArray;
    }

    public function cancelaAtividade($params)
    {
        try {
            $atividade = $this->_mapper->getAtividadeByProjetoId(
                array(
                    'idprojeto' => $params['idprojeto'],
                    'idatividadecronograma' => $params['idatividadecronograma']
                )
            );
            $flag = $params['flacancelada'];
            if (($flag != 'N') && ($flag != 'S')) {
                $flag != 'N';
            }
            if (count($atividade) > 0) {
                $itemAtividade = new Projeto_Model_Atividadecronograma($atividade);
                $resposta = $this->_mapper->cancelaAtividade($itemAtividade, $flag);
                $dados = array(
                    'idprojeto' => $params['idprojeto'],
                    'idatividadecronograma' => $params['idatividadecronograma']
                );
                $this->_mapper->atualizaSucessoras($dados);
                $this->_mapper->atualizarPercentualProjeto($dados);
                if ($resposta) {
                    return true;
                } else {
                    return false;
                }

            } else {
                return false;
            }
        } catch (Exception $exc) {
            return false;
        }

    }

    public function inserirParteInteressada(Projeto_Model_Parteinteressada $parteInteressada)
    {

        $mapperParteInteressada = new Projeto_Model_Mapper_Parteinteressada();

        $arrayParteExternaAtividade = array(
            'idprojeto' => $parteInteressada->idprojeto,
            'nomparteinteressada' => $parteInteressada->nomparteinteressada
        );
        $novaParteInteressada = $this->validaParteInteressada($arrayParteExternaAtividade);
        if (count($novaParteInteressada) > 0) {
            return $novaParteInteressada;
        } elseif (count($novaParteInteressada) == 0) {
            $parteInteressada->idparteinteressada = null;
            //insere a parte interessada para o projeto de origem
            $idParteInteressadaAtividade = $mapperParteInteressada->insert($parteInteressada);
            $parteInteressada->idparteinteressada = $idParteInteressadaAtividade;
            return $parteInteressada;
        }

    }

    public function validaParteInteressada($params)
    {

        $serviceParteInteressada = new Projeto_Service_ParteInteressada();

        return $serviceParteInteressada->buscaParteInteressadaExterna($params);

    }


    public function detalhar($params)
    {
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $projeto = $mapperGerencia->retornaProjetoPorId($params);
        return $projeto;
    }

    public function atualizarBaselineAtividade($params)
    {
        $atividade = new Projeto_Model_Atividadecronograma($params);
        $atividade->setDatiniciobaseline($params['datinicio']);
        $atividade->setDatfimbaseline($params['datfim']);
        $atividade->setNumdiasrealizados($params['numdiasrealizados']);
        return $this->_mapper->atualizarDatasAtividade($atividade);
    }

    public function atualizarBaseline($params)
    {
        return $this->_mapper->atualizarTodasDatasAtividade($params);
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

    public function fetchPairsProjetos($params)
    {

        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $dados = $mapperGerencia->buscarProjetos($params);
        return $dados;
    }

    public function fetchPairsNaturezas($params)
    {

        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $dados = $mapperGerencia->buscarNaturezas($params);
        return $dados;
    }

    public function retornaCronogramaProjetos($params)
    {
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        try {
            $projetos = $mapperGerencia->retornaCronogramaProjetos($params);
        } catch (Exception $exc) {
            throw $exc;
        }
        $cont = 0;
        $contAtiv = 0;
        $contEnt = 0;
        $contGrupo = 0;
        $custoTodosProjetos = 0;
        $params = array_filter($params);
        foreach ($projetos as $projeto) {
            $custoProjeto = 0;
            $projetoArray = null;
            $projetoArray[$cont] = $projeto->formPopulate();
            $projetoArray[$cont]['statusprojeto'] = $projeto->retornaDescricaoStatusProjeto();
            $projetoArray[$cont]['numpercentualprevisto'] = $projeto->ultimoStatusReport->numpercentualprevisto;
            $projetoArray[$cont]['numpercentualconcluido'] = $projeto->ultimoStatusReport->numpercentualconcluido;

            if (isset($projeto->grupos)) {
                foreach ($projeto->grupos as $i => $grupo) {
                    $gr = $grupo->toArray();

                    $gr['show'] = true;
                    if (!isset($params['tipogrupo'])) {
                        $gr['show'] = false;
                    } else {
                        $contGrupo++;
                    }

                    foreach ($grupo->entregas as $j => $entrega) {
                        $en = $entrega->toArray();

                        $en['show'] = true;
                        if (!isset($params['tipoentrega'])) {
                            $en['show'] = false;
                        } else {
                            $contEnt++;
                        }

                        foreach ($entrega->atividades as $k => $atividade) {
                            $at = $atividade->toArray();

                            $at['showAtividade'] = true;
                            $at['showMarco'] = true;
                            if (!isset($params['tipoatividade'])) {
                                $at['showAtividade'] = false;
                            } else {
                                $contAtiv++;
                            }
                            if (!isset($params['tipomarco'])) {
                                $at['showMarco'] = false;
                            }

                            $percentuais = $atividade->retornarDiasEstimadosEReais();
                            $prazo = $atividade->retornaPrazo($projeto->numcriteriofarol);
                            $at['descricaoprazo'] = $prazo->descricao;
                            $at['prazo'] = $prazo->dias;
                            $en['atividades'][$k] = $at;
                            //$contAtiv++;
                        }
                        $en['prazo'] = 0;
                        if (!empty($en['datfim'])) {
                            $prazoEn = $entrega->retornaPrazo($projeto->numcriteriofarol);
                            $en['descricaoprazo'] = $prazoEn->descricao;
                            $en['prazo'] = $prazoEn->dias;
                        }
                        $percentuais = $entrega->retornaPercentuais();
                        $en['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                        $en['numpercentualconcluido'] = $percentuais->numpercentualconcluido;
                        $gr['entregas'][$j] = $en;
                        //$contEnt++;
                    }
                    $percentuais = $grupo->retornaPercentuais();
                    $gr['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                    $gr['numpercentualconcluido'] = $percentuais->numpercentualconcluido;
                    $valor = str_replace(",", "", str_replace(".", "", $gr['vlratividade']));
                    $custoProjeto += $valor;
                    $valorfinal = mb_substr($custoProjeto, 0, -2) . '.' . mb_substr($custoProjeto, -2);
                    $projetoArray[$cont]['grupos'][$i] = $gr;
                    //$contGrupo++;
                }
                //$contGrupo += count($projetoArray[$cont]['grupos']);

            }
            $projetoArray[$cont]['custoProjeto'] = number_format($valorfinal, 2, ',', '.');
            $custoTodosProjetos += $custoProjeto;
            $cont++;
        }
        $custoTodosProjetos = mb_substr($custoTodosProjetos, 0, -2) . '.' . mb_substr($custoTodosProjetos, -2);
        //   exit;

        return array(
            'projetos' => $projetoArray,
            'custoTodosProjetos' => number_format($custoTodosProjetos, 2, ',', '.'),
            'qtdeRegistros' => $contGrupo + $contEnt + $contAtiv
        );

    }

    public function retornaCsvCronogramaProjeto($params)
    {
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        try {
            $Projetos = $mapperGerencia->retornaCronogramaProjeto($params);

        } catch (Exception $exc) {
            throw $exc;
        }
        $linhas = array();
        $params = array_filter($params);
        $resultado = '';
        $nLinha = 0;
        $contEntrega = 0;
        $contAtividade = 0;
        $contGrupo = 0;
        $linhas[$nLinha++] = "CRONOGRAMA";
        $linhas[$nLinha++] = mb_convert_encoding("ID;Seq;Grupo/Evento/Atividade;Predec.;Folga;Flags;Início/Fim(Baseline);(D/B);"
            . "Início/Fim(Realizado);(D/R);%;C. Plan.;C. Reali.;Responsável;Farol;Observação;", 'ISO-8859-1', 'UTF-8');
        $linhas[$nLinha++] = ";;;;;;;;;;;;;;;";
        foreach ($Projetos as $projeto) {
            $custoProjeto = 0;
            foreach ($projeto->grupos as $i => $grupo) {
                $gr = $grupo->toArray();
                $valorNumDiasB = "";
                if (isset($gr['datiniciobaseline']) && isset($gr['datfimbaseline'])) {
                    $dateiniB = new Zend_Date($gr['datiniciobaseline'], 'dd/MM/YYYY');
                    $datefimB = new Zend_Date($gr['datfimbaseline'], 'dd/MM/YYYY');
                    $intervaloB = $datefimB->sub($dateiniB)->toValue();
                    $valorNumDiasB = floor($intervaloB / 60 / 60 / 24);
                }
                $valorNumDias = "";
                if (isset($gr['datinicio']) && isset($gr['datfim'])) {
                    $dateini = new Zend_Date($gr['datinicio'], 'dd/MM/YYYY');
                    $datefim = new Zend_Date($gr['datfim'], 'dd/MM/YYYY');
                    $intervalo = $datefim->sub($dateini)->toValue();
                    $valorNumDias = floor($intervalo / 60 / 60 / 24);
                }
                $linhas[$nLinha++] = ""
                    . $gr['idatividadecronograma'] . ";"
                    . $gr['numseq'] . ";"
                    . mb_convert_encoding($gr['nomatividadecronograma'], 'ISO-8859-1', 'UTF-8') . ";"
                    . ";"
                    . ";"
                    . ";"
                    . (isset($gr['datiniciobaseline']) ? $gr['datiniciobaseline'] : "") . mb_convert_encoding(" à ",
                        'ISO-8859-1', 'UTF-8') . (isset($gr['datfimbaseline']) ? $gr['datfimbaseline'] : "") . ";"
                    . $valorNumDiasB . ";"
                    . (isset($gr['datinicio']) ? $gr['datinicio'] : "") . mb_convert_encoding(" à ", 'ISO-8859-1',
                        'UTF-8') . (isset($gr['datfim']) ? $gr['datfim'] : "") . ";"
                    . $valorNumDias . ";"
                    . $gr['numpercentualconcluido'] . "%;"
                    . $gr['vlratividadebaseline'] . ";"
                    . $gr['vlratividade'] . ";"
                    . ";"
                    . ";"
                    . ";";
                $contGrupo++;
                foreach ($grupo->entregas as $j => $entrega) {
                    $en = $entrega->toArray();
                    $flag = false;
                    $txtFlags = "";
                    if ($en['flaaquisicao'] == 'S') {
                        $txtFlags .= "A";
                        $flag = true;
                    }
                    if ($en['flainformatica'] == 'S') {
                        $txtFlags .= ($flag ? ", " : "") . "I";
                        $flag = true;
                    }
                    if ($en['flacancelada'] == 'S') {
                        $txtFlags .= ($flag ? ", " : "") . "X";
                        $flag = true;
                    }
                    if ($en['numpercentualconcluido'] == 100) {
                        $txtFlags .= ($flag ? ", " : "") . "C";
                        $flag = true;
                    }
                    $valorNumDiasB = "";
                    if (isset($en['datiniciobaseline']) && isset($en['datfimbaseline'])) {
                        $dateiniB = new Zend_Date($en['datiniciobaseline'], 'dd/MM/YYYY');
                        $datefimB = new Zend_Date($en['datfimbaseline'], 'dd/MM/YYYY');
                        $intervaloB = $datefimB->sub($dateiniB)->toValue();
                        $valorNumDiasB = floor($intervaloB / 60 / 60 / 24);
                    }
                    $valorNumDias = "";
                    if (isset($en['datinicio']) && isset($en['datfim'])) {
                        $dateini = new Zend_Date($en['datinicio'], 'dd/MM/YYYY');
                        $datefim = new Zend_Date($en['datfim'], 'dd/MM/YYYY');
                        $intervalo = $datefim->sub($dateini)->toValue();
                        $valorNumDias = floor($intervalo / 60 / 60 / 24);
                    }
                    $en['prazo'] = 0;
                    if (!empty($en['datfim'])) {
                        $prazoEn = $entrega->retornaPrazo($projeto->numcriteriofarol);
                        $en['descricaoprazo'] = $prazoEn->descricao;
                        $en['prazo'] = $prazoEn->dias;
                    }
                    $percentuais = $entrega->retornaPercentuais();
                    $en['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                    $en['numpercentualconcluido'] = $percentuais->numpercentualconcluido;
                    $gr['entregas'][$j] = $en;
                    $linhas[$nLinha++] = ""
                        . $en['idatividadecronograma'] . ";"
                        . $en['numseq'] . ";"
                        . mb_convert_encoding($en['nomatividadecronograma'], 'ISO-8859-1', 'UTF-8') . ";"
                        . ";"
                        . $en['numfolga'] . ";"
                        . $txtFlags . ";"
                        . (isset($en['datiniciobaseline']) ? $en['datiniciobaseline'] : "") . mb_convert_encoding(" à ",
                            'ISO-8859-1', 'UTF-8') . (isset($en['datfimbaseline']) ? $en['datfimbaseline'] : "") . ";"
                        . $valorNumDiasB . ";"
                        . (isset($en['datinicio']) ? $en['datinicio'] : "") . mb_convert_encoding(" à ", 'ISO-8859-1',
                            'UTF-8') . (isset($en['datfim']) ? $en['datfim'] : "") . ";"
                        . $valorNumDias . ";"
                        . $en['numpercentualconcluido'] . ";"
                        . $en['vlratividadebaseline'] . ";"
                        . $en['vlratividade'] . ";"
                        . $en['desemail'] . ";"
                        . (isset($en['prazo']) ? $en['prazo'] : "") . ";"
                        . mb_convert_encoding($en['desobs'], 'ISO-8859-1', 'UTF-8') . ";";
                    $contEntrega++;

                    foreach ($entrega->atividades as $k => $atividade) {
                        $at = $atividade->toArray();
                        /********************/
                        if (count($at['predecessoras']) > 0) {
                            $txtPred = "";
                            $ctP = 0;
                            foreach ($at['predecessoras'] as $pr) {
                                $txtPred .= ($ctP != 0 ? ", " : "") . $pr['idatividadepredecessora'];
                                $ctP++;
                            }
                        }
                        $txtFlags = "";
                        $flag = false;
                        if ($at['flaaquisicao'] == 'S') {
                            $txtFlags = "A";
                            $flag = true;
                        }
                        if ($at['flainformatica'] == 'S') {
                            $txtFlags .= ($flag ? ", " : "") . "I";
                            $flag = true;
                        }
                        if ($at['flacancelada'] == 'S') {
                            $txtFlags .= ($flag ? ", " : "") . "X";
                            $flag = true;
                        }
                        if ($at['numpercentualconcluido'] == 100) {
                            $txtFlags .= ($flag ? ", " : "") . "C";
                        }
                        $valorNumDiasB = "";
                        if (isset($at['datiniciobaseline']) && isset($at['datfimbaseline'])) {
                            $dateiniB = new Zend_Date($at['datiniciobaseline'], 'dd/MM/YYYY');
                            $datefimB = new Zend_Date($at['datfimbaseline'], 'dd/MM/YYYY');
                            $intervaloB = $datefimB->sub($dateiniB)->toValue();
                            $valorNumDiasB = floor($intervaloB / 60 / 60 / 24);
                        }
                        $valorNumDias = "";
                        if (isset($at['datinicio']) && isset($at['datfim'])) {
                            $dateini = new Zend_Date($at['datinicio'], 'dd/MM/YYYY');
                            $datefim = new Zend_Date($at['datfim'], 'dd/MM/YYYY');
                            $intervalo = $datefim->sub($dateini)->toValue();
                            $valorNumDias = floor($intervalo / 60 / 60 / 24);
                        }
                        $percentuais = $atividade->retornarDiasEstimadosEReais();
                        $prazo = $atividade->retornaPrazo($projeto->numcriteriofarol);
                        $at['descricaoprazo'] = $prazo->descricao;
                        $at['prazo'] = $prazo->dias;
                        $en['atividades'][$k] = $at;

                        $linhas[$nLinha++] = ""
                            . $at['idatividadecronograma'] . ";"
                            . ";"
                            . mb_convert_encoding($at['nomatividadecronograma'], 'ISO-8859-1', 'UTF-8') . ";"
                            . $txtPred . ";"
                            . $at['numfolga'] . ";"
                            . $txtFlags . ";"
                            . $at['datiniciobaseline'] . mb_convert_encoding(" à ", 'ISO-8859-1',
                                'UTF-8') . $at['datfimbaseline'] . ";"
                            . $valorNumDiasB . ";"
                            . $at['datinicio'] . mb_convert_encoding(" à ", 'ISO-8859-1', 'UTF-8') . $at['datfim'] . ";"
                            . $valorNumDias . ";"
                            . $at['numpercentualconcluido'] . "%;"
                            . $at['vlratividadebaseline'] . ";"
                            . $at['vlratividade'] . ";"
                            . $at['desemail'] . ";"
                            . $at['prazo'] . ";"
                            . mb_convert_encoding($at['desobs'], 'ISO-8859-1', 'UTF-8') . ";";
                        $contAtividade++;
                        /********************/
                    }
                }
                $percentuais = $grupo->retornaPercentuais();
                $gr['numpercentualprevisto'] = $percentuais->numpercentualprevisto;
                $gr['numpercentualconcluido'] = $percentuais->numpercentualconcluido;
                $valor = str_replace(",", "", str_replace(".", "", $gr['vlratividade']));
                $custoProjeto += $valor;
                $valorfinal = mb_substr($custoProjeto, 0, -2) . '.' . mb_substr($custoProjeto, -2);
            }
        }
        $linhas[] = ""
            . $contGrupo . " grupo(s), " . $contEntrega . " entrega(s) e " . $contAtividade . " atividade(s).;";

        foreach ($linhas as $linha) {
            $resultado .= $linha . "\n";
        }
        return $resultado;
    }

    public function verificarAtividadesDesatualizadas($params)
    {
        return $this->_mapper->verificarAtividadesDesatualizadas($params);
    }

    public function verificarAtividadesConcluidas($params)
    {
        return $this->_mapper->verificarAtividadesConcluidas($params);
    }

    public function retornaAtividadesConcluidas($params)
    {
        return $this->_mapper->retornaAtividadesConcluidas($params);
    }

    public function retornaAtividadesEmAndamento($params)
    {
        return $this->_mapper->retornaAtividadesEmAndamento($params);
    }

    public function verificarAtividadesEmAndamento($params)
    {
        return $this->_mapper->verificarAtividadesConcluidas($params);
    }

    public function retornaTendenciaProjeto($params)
    {
        return $this->_mapper->retornaTendenciaProjeto($params);
    }

    public function retornaIrregularidades($params)
    {
        return $this->_mapper->retornaIrregularidades($params);
    }

    public function retornaIrregularidadesAtividades($params)
    {
        $retorno = $this->_mapper->retornaIrregularidadesAtividades($params);
        return $retorno;
    }

    public function retornaGrupoAtividade($params)
    {
        $retorno = $this->_mapper->retornaGrupoAtividade($params);
        return $retorno;
    }

    public function retornaFeriadosFixos()
    {
        $retorno = $this->_mapper->retornaFeriadosFixos();
        return $retorno;
    }

    public function retornarDataInicioAtividade($params)
    {
        return $this->_mapper->retornaDataInicioPorIdAtividade($params);
    }

    public function atualizaNumseq($params)
    {
        return $this->_mapper->atualizaNumseq($params);
    }

    public function retornaQtdeDiasUteisEntreDatas($params)
    {
        if ((@trim($params['datainicio']) != "") && (@trim($params['datafim']) != "")) {
            $rtn = true;
            $dateStringIni = $params['datainicio'];
            $partsIni = explode('/', $dateStringIni);
            if ((intval($partsIni[0]) > 31) || (intval($partsIni[1]) > 12)
                || (intval($partsIni[2]) > 2250) || (intval($partsIni[2]) < 1900)
            ) {
                $rtn = false;
            }
            $dateStringFim = $params['datafim'];
            $partsFim = explode('/', $dateStringFim);
            if ((intval($partsFim[0]) > 31) || (intval($partsFim[1]) > 12)
                || (intval($partsFim[2]) > 2250) || (intval($partsFim[2]) < 1900)
            ) {
                $rtn = false;
            }
            $dataNegativa = false;
            if (!($this->comparaData($dateStringFim, $dateStringIni))) {
                $params['datafim'] = $params['datainicio'];
                $params['datainicio'] = $dateStringFim;
                $dataNegativa = true;
            }

            if (!$rtn) {
                return "";
            } else {
                $diasSaida = $this->_mapper->retornaQtdeDiasUteisEntreDatas($params);
                if ($dataNegativa) {
                    return ($diasSaida * (-1));
                } else {
                    return $diasSaida;
                }
            }
        } else {
            return "";
        }
    }

    public function retornaDataFimValidaPorDias($params)
    {
        if ((@trim($params['datainicio']) != "") && (@trim($params['numdias']) != "")) {
            if ($params['numdias'] > 0) {
                $rtn = true;
                $dateString = $params['datainicio'];
                $parts = explode('/', $dateString);
                if ((intval($parts[0]) > 31) || (intval($parts[1]) > 12)
                    || (intval($parts[2]) > 2250) || (intval($parts[2]) < 1900)
                ) {
                    $rtn = false;
                }
                if (intval($params['numdias']) <= 0) {
                    return $params['datainicio'];
                } else {
                    if (!$rtn) {
                        return "";
                    } else {
                        if (Zend_Date::isDate($dateString, 'd/m/Y')) {
                            return $this->_mapper->retornaDataFimValidaPorDias($params);
                        } else {
                            return "";
                        }
                    }
                    return $this->_mapper->retornaDataFimValidaPorDias($params);
                }
            } else {
                return $params['datainicio'];
            }
        } else {
            return "";
        }
    }

    public function retornaDataFeriado($params)
    {
        return $this->_mapper->retornaDataFeriado($params);
    }

    public function retornaDataAnteriorValidaPorDias($params)
    {
        if ($params['numdias'] < 0) {
            $params['numdias'] = ($params['numdias'] * (-1));
        }
        return $this->_mapper->retornaDataAnteriorValidaPorDias($params);
    }

    public function verificaCountNumSeq($params)
    {
        return $this->_mapper->verificaCountNumSeq($params);
    }

    public function retornaGrupoPorProjeto($params)
    {
        return $this->_mapper->retornaGrupoPorProjeto($params);
    }

    public function retornaTextIrregularidades($params)
    {
        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */
        $ResultSaida = array();
        $data = "";
        $resultado = $this->_mapper->retornaGrupoPorProjeto($params);
        if (count($resultado) > 0) {
            $dataTmp = "";
            for ($i = 0; $i < count($resultado); $i++) {
                $entregaIrreg = false;
                $entregaIrreg = $this->_mapper->retornaIrregularidadesNovoAcompanhamentoAtividades(
                    array(
                        'idprojeto' => $resultado[$i]['idprojeto'],
                        'idgrupo' => $resultado[$i]['idatividadecronograma'],
                        'domtipoatividade' => 2,
                        'atividadeAtiva' => false,
                        'atividadeConcluida' => false,
                        'dtfim' => false,
                        'numseq' => true
                    )
                );
                if ($entregaIrreg) {
                    foreach ($entregaIrreg as $r) {
                        if (($r['atrasada'] == 'S') && ($r['concluida'] == 'N') && ($r['cancelada'] == 'N') && (!empty($r['flainformatica'])) && $r['numpercentualconcluido'] != 100) {
                            $dataTmp .= $r['datinicio'] . " - " . $r['datfim'] . " - " . $r['nomatividadecronograma'] . "\n";
                            $ResultSaida[] = array(
                                'idatividadecronograma' => $r["idatividadecronograma"],
                                'datinicio' => $r["datinicio"],
                                'datfim' => $r["datfim"],
                                'nomatividadecronograma' => $r["nomatividadecronograma"],
                                'numseq' => $r["numseq"]
                            );
                        }
                        $atividadeIrreg = $this->_mapper->retornaIrregularidadesNovoAcompanhamentoAtividades(
                            array(
                                'idprojeto' => $r['idprojeto'],
                                'idgrupo' => $r['idatividadecronograma'],
                                'domtipoatividade' => 3,
                                'atividadeAtiva' => true,
                                'atividadeConcluida' => false,
                                'dtfim' => true,
                                'numseq' => true
                            )
                        );
                        foreach ($atividadeIrreg as $at) {
                            if (($at['atrasada'] == 'S') && ($at['concluida'] == 'N') && ($at['cancelada'] == 'N')) {
                                $dataTmp .= $at['datinicio'] . " - " . $at['datfim'] . " - " . $at['nomatividadecronograma'] . "\n";
                                $ResultSaida[] = array(
                                    'idatividadecronograma' => $at["idatividadecronograma"],
                                    'datinicio' => $at["datinicio"],
                                    'datfim' => $at["datfim"],
                                    'nomatividadecronograma' => $at["nomatividadecronograma"],
                                    'numseq' => $at["numseq"]
                                );
                            }
                        }
                    }

                } else {
                    $atividadeIrreg = false;
                    $atividadeIrreg = $this->_mapper->retornaIrregularidadesAtividadesSemAtrasoEntrega(
                        array(
                            'idprojeto' => $resultado[$i]['idprojeto'],
                            'idgrupo' => $resultado[$i]['idatividadecronograma'],
                            'domtipoatividade' => 3,
                            'atividadeAtiva' => true,
                            'atividadeConcluida' => false,
                            'dtfim' => true,
                            'numseq' => true
                        )
                    );
                    foreach ($atividadeIrreg as $at1) {
                        //if (($at1['atrasada'] == 'S')&&($at1['concluida'] == 'N')&&($at1['cancelada'] == 'N')) {
                        $dataTmp .= $at1['datinicio'] . " - " . $at1['datfim'] . " - " . $at1['nomatividadecronograma'] . "\n";
                        $ResultSaida[] = array(
                            'idatividadecronograma' => $at1["idatividadecronograma"],
                            'datinicio' => $at1["datinicio"],
                            'datfim' => $at1["datfim"],
                            'nomatividadecronograma' => $at1["nomatividadecronograma"],
                            'numseq' => $at1["numseq"]
                        );
                        //}
                    }
                }
                $data .= $dataTmp;
                $dataTmp = "";
            }
        }
        if (@isset($params['listaArray'])) {
            return $ResultSaida;
        } else {
            return $data;
        }
    }

    public function retornaTextEmAndamento($params)
    {
        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */
        $ResultSaida = array();
        $data = "";
        $resultado = $this->_mapper->retornaGrupoPorProjeto($params);
        if (count($resultado) > 0) {
            $dataTmp = "";
            for ($i = 0; $i < count($resultado); $i++) {
                $entregaIrreg = false;
                $entregaIrreg = $this->_mapper->retornaIrregularidadesAtividades(
                    array(
                        'idprojeto' => $resultado[$i]['idprojeto'],
                        'idgrupo' => $resultado[$i]['idatividadecronograma'],
                        'domtipoatividade' => 2,
                        'atividadeAtiva' => false,
                        'atividadeConcluida' => false,
                        'dtfim' => false
                    )
                );
                if ($entregaIrreg) {
                    foreach ($entregaIrreg as $r) {
                        //if (($r['concluida'] == 'N')&&($r['cancelada'] == 'N')) {
                        //    $dataTmp .= $r['datinicio'] . " - " . $r['datfim'] . " - " . $r['nomatividadecronograma'] . "\n";
                        //}
                        $atividadeIrreg = $this->_mapper->retornaIrregularidadesAtividades(
                            array(
                                'idprojeto' => $r['idprojeto'],
                                'idgrupo' => $r['idatividadecronograma'],
                                'domtipoatividade' => 3,
                                'atividadeAtiva' => true,
                                'atividadeConcluida' => false,
                                'atividadeAndamento' => true,
                                'dtfim' => false
                            )
                        );
                        foreach ($atividadeIrreg as $at) {
                            if (($at['concluida'] == 'N') && ($at['cancelada'] == 'N')) {
                                $dataTmp .= $at['datinicio'] . " - " . $at['datfim'] . " - " . $at['nomatividadecronograma'] . "\n";
                                $ResultSaida[] = array(
                                    'idatividadecronograma' => $at["idatividadecronograma"],
                                    'datinicio' => $at["datinicio"],
                                    'datfim' => $at["datfim"],
                                    'nomatividadecronograma' => $at["nomatividadecronograma"]
                                );
                            }
                        }
                    }

                } else {
                    $atividadeIrreg = false;
                    $atividadeIrreg = $this->_mapper->retornaIrregularidadesAtividadesSemAtrasoEntrega(
                        array(
                            'idprojeto' => $resultado[$i]['idprojeto'],
                            'idgrupo' => $resultado[$i]['idatividadecronograma'],
                            'domtipoatividade' => 3,
                            'atividadeAtiva' => true,
                            'atividadeConcluida' => false,
                            'atividadeAndamento' => true,
                            'dtfim' => false
                        )
                    );
                    foreach ($atividadeIrreg as $at1) {
                        //if (($at1['atrasada'] == 'S')&&($at1['concluida'] == 'N')&&($at1['cancelada'] == 'N')) {
                        $dataTmp .= $at1['datinicio'] . " - " . $at1['datfim'] . " - " . $at1['nomatividadecronograma'] . "\n";
                        //}
                        $ResultSaida[] = array(
                            'idatividadecronograma' => $at1["idatividadecronograma"],
                            'datinicio' => $at1["datinicio"],
                            'datfim' => $at1["datfim"],
                            'nomatividadecronograma' => $at1["nomatividadecronograma"]
                        );
                    }
                }
                $data .= $dataTmp;
                $dataTmp = "";
            }
        }
        if (@isset($params['listaArray'])) {
            return $ResultSaida;
        } else {
            return $data;
        }
    }

    public function retornaTextConcluida($params)
    {
        /* domtipoatividade
         * 1 - grupo
         * 2 - entrega
         * 3 - atividade
         * 4 - marco
         */
        $ResultSaida = array();
        $data = "";
        $resultado = $this->_mapper->retornaGrupoPorProjeto($params);
        if (count($resultado) > 0) {
            $dataTmp = "";
            for ($i = 0; $i < count($resultado); $i++) {
                $entregaIrreg = false;
                $entregaIrreg = $this->_mapper->retornaIrregularidadesAtividades(
                    array(
                        'idprojeto' => $resultado[$i]['idprojeto'],
                        'idgrupo' => $resultado[$i]['idatividadecronograma'],
                        'domtipoatividade' => 2,
                        'atividadeAtiva' => false,
                        'atividadeConcluida' => false,
                        'dtfim' => false
                    )
                );
                if ($entregaIrreg) {
                    foreach ($entregaIrreg as $r) {
                        //if (($r['concluida'] == 'N')&&($r['cancelada'] == 'N')) {
                        //    $dataTmp .= $r['datinicio'] . " - " . $r['datfim'] . " - " . $r['nomatividadecronograma'] . "\n";
                        //}
                        $atividadeIrreg = $this->_mapper->retornaIrregularidadesAtividades(
                            array(
                                'idprojeto' => $r['idprojeto'],
                                'idgrupo' => $r['idatividadecronograma'],
                                'domtipoatividade' => 3,
                                'atividadeAtiva' => false,
                                'atividadeConcluida' => true,
                                'atividadeAndamento' => false,
                                'dtfim' => false
                            )
                        );
                        foreach ($atividadeIrreg as $at) {
                            if ($at['concluida'] == 'S') {
                                $dataTmp .= $at['datinicio'] . " - " . $at['datfim'] . " - " . $at['nomatividadecronograma'] . "\n";
                                $ResultSaida[] = array(
                                    'idatividadecronograma' => $at["idatividadecronograma"],
                                    'datinicio' => $at["datinicio"],
                                    'datfim' => $at["datfim"],
                                    'nomatividadecronograma' => $at["nomatividadecronograma"]
                                );
                            }
                        }
                    }

                } else {
                    $atividadeIrreg = $this->_mapper->retornaIrregularidadesAtividadesSemAtrasoEntrega(
                        array(
                            'idprojeto' => $resultado[$i]['idprojeto'],
                            'idgrupo' => $resultado[$i]['idatividadecronograma'],
                            'domtipoatividade' => 3,
                            'atividadeAtiva' => false,
                            'atividadeConcluida' => true,
                            'atividadeAndamento' => false,
                            'dtfim' => false
                        )
                    );
                    foreach ($atividadeIrreg as $at1) {
                        //if (($at1['atrasada'] == 'S')&&($at1['concluida'] == 'N')&&($at1['cancelada'] == 'N')) {
                        $dataTmp .= $at1['datinicio'] . " - " . $at1['datfim'] . " - " . $at1['nomatividadecronograma'] . "\n";
                        //}
                        $ResultSaida[] = array(
                            'idatividadecronograma' => $at1["idatividadecronograma"],
                            'datinicio' => $at1["datinicio"],
                            'datfim' => $at1["datfim"],
                            'nomatividadecronograma' => $at1["nomatividadecronograma"]
                        );
                    }
                }
                $data .= $dataTmp;
                $dataTmp = "";
            }
        }
        if (@isset($params['listaArray'])) {
            return $ResultSaida;
        } else {
            return $data;
        }
    }

    public function retornaDataPorProjeto($params)
    {
        return $this->_mapper->retornaDataPorProjeto($params);
    }

    public function retornaCronograma($params)
    {
        return $this->_mapper->retornaCronogramaByArray($params);
    }


    /**
     * Funcionalidade que retorna a estrutura do cronograma de forma recursiva
     * @param array
     * @return array
     */
    public function retornaCronogramaByArray($params)
    {
        $this->atualizaNumseq($params);
        $servicoGerencia = new Projeto_Service_Gerencia();
        $arrayCronograma['cronograma'] = $this->_mapper->retornaCronogramaByArray($params);

        /**@var Projeto_Model_Gerencia $projeto */
        $projeto = $servicoGerencia->retornaProjetoArrayPorId($params, false);

        $arrayProjeto = $projeto->toArray();

        $datas = $this->_mapper->retornaDataPorProjeto($params);

        $arrayProjeto['nomprojeto'] = $projeto->nomprojeto;
        $arrayProjeto['nomprojeto'] = $projeto->nomprojeto;
        $arrayProjeto['datiniciobaseline'] = $datas['datiniciobaseline'];
        $arrayProjeto['datfimbaseline'] = $datas['datfimbaseline'];
        $arrayProjeto['datinicioReal'] = $datas['datinicio'];
        $arrayProjeto['datfimReal'] = $datas['datfim'];
        $arrayProjeto['vlratividadet'] = (!empty($projeto['vlratividadet']) && $projeto['vlratividadet'] > 0) ? mb_substr($projeto['vlratividadet'],
                0, -2) . '.' . mb_substr($projeto['vlratividadet'], -2) : number_format(0, 2);
        $arrayProjeto['numdiasbaseline'] = $datas['numdiasbaseline'];
        $arrayProjeto['numdiasrealizados'] = $datas['totaldiasrealizados'];
        $arrayCronograma['projeto'] = $arrayProjeto;

        return $arrayCronograma;
    }

    public function atualizarCronogramaDoProjeto($params)
    {
        $retorno = false;
        $servicoGerencia = new Projeto_Service_Gerencia();
        try {
            $projeto = $servicoGerencia->retornaProjetoPorId($params);
        } catch (Exception $exc) {
            var_dump($exc);
        }

        if (isset($projeto->grupos) && !empty($projeto->grupos)) {

            $contaGrupo = count($projeto->grupos);
            $contador = 0;

            foreach ($projeto->grupos as $g => $grupo) {

                /* @var $grupo Projeto_Model_Grupocronograma */
                $gr = $grupo->toArray();
                $dados = array(
                    'idprojeto' => $grupo->idprojeto,
                    'idgrupo' => $grupo->idatividadecronograma
                );

                $idEntregas = $this->retornaIdEntregaPorGrupo($dados);

                if (count($idEntregas) > 0) {

                    foreach ($idEntregas as $e => $idEntrega) {

                        $paramsEntrega = array(
                            'idprojeto' => $gr['idprojeto'],
                            'idatividadecronograma' => $idEntrega['idatividadecronograma']
                        );

                        /* @var $entrega Projeto_Model_Entregacronograma */
                        $entrega = $this->retornaEntregaPorId($paramsEntrega, false, true);

                        $en = $entrega->toArray();

                        foreach ($entrega->atividades as $a => $atividade) {

                            /* @var $atividade Projeto_Model_Atividadecronograma */

                            $paramsAtividade = array(
                                'idprojeto' => $atividade->idprojeto,
                                'idatividadecronograma' => $atividade->idatividadecronograma
                            );
                            if ($this->verificarAtividadePredecessoras($paramsAtividade)) { //Verifica maior data predecessora
                                $atividade->datinicio = $this->preparaData($this->retornaInicioBaseLinePorAtividade($paramsAtividade));
                            }

                            //verificar se os dias reais da atividade é maior que zero
                            if ($atividade->numdiasrealizados > 0) {
                                //soma os dias reais a data inicio da atividade sucessora
                                $novaDataFim = $this->preparaData($this->retornaDataFimValidaPorDias(
                                    array(
                                        'datainicio' => $atividade->datinicio->format('d/m/Y'),
                                        'numdias' => $atividade->numdiasrealizados
                                    )
                                ));
                                $atividade->datfim = $novaDataFim;
                            } else {
                                $atividade->datfim = $atividade->datinicio;
                            }

                            $atividade->numdias = $atividade->retornaDiasReal();
                            $atividade->numdiasrealizados = $atividade->numdiasrealizados;

                            $atv = $this->_mapper->atualizarAtividade($atividade);
                        }
                    }
                }
                $contador++;
            }
            if ($contador == $contaGrupo) {
                $retorno = true;
            }
        }
        $this->_mapper->atualizaSucessoras($params);
        $this->_mapper->atualizarPercentualProjeto($params);
        return $retorno;
    }

    public static function getFeriados()
    {
        $serviceCronograma = new self();
        $arrayFeriadosFixos = $serviceCronograma->retornaFeriadosFixos();
        $arrayFeriados = array();
        foreach ($arrayFeriadosFixos as $feriadoFixo) {
            if ('S' === $feriadoFixo['flaativo']) {
                $data = new DateTime(implode('-',
                    array($feriadoFixo['anoferiado'], $feriadoFixo['mesferiado'], $feriadoFixo['diaferiado'])));
                if ($feriadoFixo['anoferiado'] === 0) {
                    $arrayFeriados[] = $data->format('m-d');
                } else {
                    $arrayFeriados[] = $data->format('Y-m-d');
                }
            }
        }
        return $arrayFeriados;
    }

    public function verificaQtdeAtivPorProjeto($params, $array = false)
    {
        return $this->_mapper->verificaQtdeAtivPorProjeto($params);
    }

}
