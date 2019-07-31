<?php

class Projeto_Service_AtividadeCronograma extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Atividadecronograma
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     *
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
        $form->populate($params);
        $form->populate(array('domtipoatividade' => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_ENTREGA));
        return $form;
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
        if ($this->verificarAtividadePredecessoras($params)) {
            $maiorData = $this->retornaInicioBaseLinePorAtividade($params);
            $form->getElement('datinicio')->setAttribs(array(
                'readonly' => true,
                'disabled' => 'disabled',
                'required' => false
            ));
            //$form->getElement('datinicio')->setAttribs(array('readonly' => true, 'disabled' => 'disabled', 'required' => false));
            //$form->getElement('datinicio')->removeFilter('StringTrim');
            //$form->getElement('datinicio')->removeFilter('StripTags');
            //$form->getElement('datinicio')->removeValidator('NotEmpty');
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
        //$form->getElement('idgrupo')->setMultiOptions($this->fetchPairsEntrega(array('idprojeto' => $params['idprojeto'])));
        //$form->getElement('idparteinteressada')->setMultiOptions($parteInteressada->fetchPairsPorProjeto($params));
        //$form->getElement('idelementodespesa')->setMultiOptions($this->initCombo($elementoDespesa->fetchPairs(), "Selecione"));
        $dataInicio = $this->retornarDataInicioAtividade($params);
        $form->getElement('datInicioHidden')->setAttrib('value', $dataInicio);
        /*if ($this->verificarAtividadePredecessoras($params)) {
            $maiorData = $this->retornaInicioBaseLinePorAtividade($params);
            $form->getElement('datinicio')->setAttribs(array('readonly' => true, 'disabled' => 'disabled', 'required' => false));
            //$form->getElement('datinicio')->setAttribs(array('readonly' => true, 'disabled' => 'disabled', 'required' => false));
            //$form->getElement('datinicio')->removeFilter('StringTrim');
            //$form->getElement('datinicio')->removeFilter('StripTags');
            //$form->getElement('datinicio')->removeValidator('NotEmpty');
            $form->getElement('maior_valor')->setAttrib('value', $maiorData);
            $form->getElement('datInicioHidden')->setAttrib('value', $maiorData);
        }*/
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
        //$form->getElement('datinicio')->setAttrib('value',$dataInicio);
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
            $atividadePredecessoraService = new Projeto_Service_AtividadePredecessora();
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

    //put your code here
    public function inserir($dados)
    {
        $form = $this->getForm();

        if ($form->isValid($dados)) {
            ///  $model = new Projeto_Model_Gerencia($form->getValues());
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
            return $this->_mapper->inserirGrupo($model);
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function inserirEntrega($dados)
    {
        $form = $this->getFormEntrega($dados);

        if ($form->isValid($dados)) {
            $model = new Projeto_Model_Entregacronograma($form->getValues());
            return $this->_mapper->inserirEntrega($model);
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
                $model->datfim = $this->preparaData($this->adicionarDias($model->datinicio->format('d/m/Y'),
                    $model->numdiasrealizados));
            } else {
                $model->datfim = $model->datinicio;
            }
            if (isset($dados['idpredecessora']) && count($dados['idpredecessora']) > 0) {
                foreach ($dados['idpredecessora'] as $id) {
                    $p = new Projeto_Model_Atividadepredecessora();
                    $p->idatividadepredecessora = $id;
                    $model->adicionarPredecessora($p);
                }
            }
            @set_time_limit(0);
            @ini_set('max_execution_time', 1800);
            /* @var $atividade Projeto_Model_Atividadecronograma */
            $atividade = $this->_mapper->inserirAtividade($model);
            if (@trim($dados['listaPredecessoras']) != "") {
                $servicePrede = new Projeto_Service_AtividadePredecessora();
                $predecessorasIn = explode(";", $dados['listaPredecessoras']);
                foreach ($predecessorasIn as $predecessora) {
                    $dadosInsert['idprojeto'] = $dados['idprojeto'];
                    $dadosInsert['idatividadepredecessora'] = $predecessora;
                    $dadosInsert['idatividade'] = $atividade->idatividadecronograma;
                    $predecessora = $servicePrede->inserir($dadosInsert);
                }
            }
            $this->atualizarDatasEntrega($model);
            $this->atualizarPercentuaisGrupoEntrega(array(
                'idprojeto' => $atividade->idprojeto,
                'idatividadecronograma' => $atividade->idatividadecronograma
            ));
            //$this->atualizarCronogramaDoProjeto(array('idprojeto' => $atividade->idprojeto));
            return $atividade;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function atualizarDatasEntrega(Projeto_Model_Atividadecronograma $model)
    {
        if ($model) {
            $entrega = $this->_mapper->retornaEntregaPorId(array(
                'idprojeto' => $model->idprojeto,
                'idatividadecronograma' => $model->idgrupo
            ), false);
            if ($entrega) {

                $maiorDataEntrega = $this->_mapper->retornaMaiorDataPorEntrega(array(
                    'idprojeto' => $model->idprojeto,
                    'idEntrega' => $entrega['idatividadecronograma']
                ));
                $entrega->datiniciobaseline = $this->preparaData($maiorDataEntrega["datiniciobaseline"]);
                $entrega->datfimbaseline = $this->preparaData($maiorDataEntrega["datfimbaseline"]);
                $entrega->datinicio = $this->preparaData($maiorDataEntrega["datinicio"]);
                $entrega->datfim = $this->preparaData($maiorDataEntrega["datfim"]);
                $this->_mapper->atualizarDatasEntrega($entrega);
                $this->atualizarDatasGrupo($entrega);
                return true;


                /* $datasEntrega = $this->_mapper->retornaDatasPorEntrega(array('idprojeto' => $model->idprojeto, 'idEntrega' => $entrega['idatividadecronograma']));

                 if(is_null($datasEntrega['datinicio']))
                 {
                     $maiorDataEntrega = $this->_mapper->retornaMaiorDataPorEntrega(array('idprojeto' => $model->idprojeto, 'idEntrega' => $entrega['idatividadecronograma']));
                     $entrega->datiniciobaseline = $this->preparaData($maiorDataEntrega["datiniciobaseline"]);
                     $entrega->datfimbaseline = $this->preparaData($maiorDataEntrega["datfimbaseline"]);
                     $entrega->datinicio = $this->preparaData($maiorDataEntrega["datinicio"]);
                     $entrega->datfim = $this->preparaData($maiorDataEntrega["datfim"]);
                     $this->_mapper->atualizarDatasEntrega($entrega);
                     $this->atualizarDatasGrupo($entrega);
                     return true;

                 } else {

                     if ($this->comparaData($model->datfim->format('d/m/Y'), $datasEntrega['datfim'])) {

                         $entrega->datfim = $model->datfim;
                         $this->_mapper->atualizarDatasEntrega($entrega);
                         $this->atualizarDatasGrupo($entrega);
                         return true;
                     } else {
                         $maiorDataEntrega = $this->_mapper->retornaMaiorDataPorEntrega(array('idprojeto' => $model->idprojeto, 'idEntrega' => $entrega['idatividadecronograma']));
                         $data = explode("/", $maiorDataEntrega["datfim"]);
                         $dataFormatada = $data[2] . "-" . $data[1] . "-" . $data[0];
                         $dataFimEntrega = DateTime::createFromFormat('Y-m-d', $dataFormatada);
                         $entrega->datfim = $dataFimEntrega;
                         $this->_mapper->atualizarDatasEntrega($entrega);
                         $this->atualizarDatasGrupo($entrega);
                         return true;
                     }
                 }*/
            }
        }
        return false;
    }

    public function preparaData($data)
    {


        $dt = explode("/", $data);

        $dataFormatada = $dt[2] . "-" . $dt[1] . "-" . $dt[0];

        $dataRetornada = DateTime::createFromFormat('Y-m-d', $dataFormatada);

        return $dataRetornada;
    }

    public function preparaValor($data)
    {
        $valor = substr($data, 0, -2) . '.' . substr($data, -2);
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

    public function atualizarDatasGrupo(Projeto_Model_Entregacronograma $model)
    {
        if ($model != null) {

            $resultado = $this->_mapper->retornaGrupoPorId(array(
                'idprojeto' => $model->idprojeto,
                'idatividadecronograma' => $model->idgrupo
            ));

            if ($resultado) {

                $grupo = new Projeto_Model_Grupocronograma($resultado);

                if ($grupo) {
                    $maiorDataGrupo = $this->_mapper->retornaDatasPorGrupo(array(
                        'idprojeto' => $model->idprojeto,
                        'idgrupo' => $model->idgrupo
                    ));
                    $grupo->datiniciobaseline = $this->preparaData($maiorDataGrupo["datiniciobaseline"]);
                    $grupo->datfimbaseline = $this->preparaData($maiorDataGrupo["datfimbaseline"]);
                    $grupo->datinicio = $this->preparaData($maiorDataGrupo["datinicio"]);
                    $grupo->datfim = $this->preparaData($maiorDataGrupo["datfim"]);

                    return $this->_mapper->atualizarDatasGrupo($grupo);
                }
            }
        }
    }


    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function atualizarGrupo($dados)
    {

        $form = $this->getFormGrupo();
        if ($form->isValidPartial($dados)) {
            $model = new Projeto_Model_Grupocronograma($form->getValues());
            $retorno = $this->_mapper->atualizarGrupo($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }


    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function atualizarEntrega($dados)
    {

        $form = $this->getFormEntrega($dados);
        if ($form->isValidPartial($dados)) {
            $model = new Projeto_Model_Entregacronograma($form->getValues());
            $retorno = $this->_mapper->atualizarEntrega($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
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
        if (isset($model)) {
            return $this->_mapper->atualizarAtividade($model);
        }
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function atualizarAtividade($dados)
    {
        if (isset($dados['flainformatica']) && $dados['flainformatica'] == 'N') {
            $dados['flainformatica'] = null;
        }
        $dados['orderAsc'] = 'S';
        $dados = array_filter($dados);
        $params = $this->montaArray($dados);
        $params = array_filter($params);
        $form = $this->getFormAtividadeAtualizar($params, false);
        if ($form->isValidPartial($params)) {
            $data = null;
            $novaDataFim = null;
            $novaDatainicio = null;
            $novaData = null;
            $model = new Projeto_Model_Atividadecronograma($params);
            $servicePrede = new Projeto_Service_AtividadePredecessora();
            $predecessorasExclui = $servicePrede->excluirPorAtividade(array(
                    'idatividade' => $dados['idatividadecronograma'],
                    'idprojeto' => $dados['idprojeto']
                )
            );
            if (@trim($dados['listaPredecessoras']) != "") {
                $predecessorasIn = explode(";", $dados['listaPredecessoras']);
                foreach ($predecessorasIn as $predecessora) {
                    $dadosInsert['idprojeto'] = $dados['idprojeto'];
                    $dadosInsert['idatividadepredecessora'] = $predecessora;
                    $dadosInsert['idatividade'] = $dados['idatividadecronograma'];
                    $predecessora = $servicePrede->inserir($dadosInsert);
                }
            }
            $dataPredec = $this->retornaInicioBaseLinePorAtividade($params);
            if ($dataPredec) {
                $dataPredecessora = $this->preparaData($dataPredec);
                $model->numfolga = (int)$model->numfolga;
                $model->numdiasrealizados = (int)$model->numdiasrealizados;
                //Zend_Debug::dump($dataPredecessora);exit;
                if ($dataPredecessora) { //Verifica maior data predecessora
                    //verificar se os dias reais da atividade é maior que zero
                    $nFolga = 0;
                    if ($params['domtipoatividade'] == "3") {
                        if ($model->numfolga == 0) {
                            //Zend_Debug::dump("492) numfolga:" . $model->numfolga);exit;
                            //Zend_Debug::dump("494) inicio:");
                            //Zend_Debug::dump($model->datinicio );
                            $nFolga = $nFolga + 1;
                            $nFolga = $nFolga + $model->numfolga;
                            //$model->datinicio = $this->preparaData($this->adicionarDias($dataPredecessora->format('d/m/Y'), $nFolga));
                            //$model->datinicio = $this->preparaData($this->adicionarDias($dataPredecessora->format('d/m/Y'), $nFolga));
                            //Zend_Debug::dump("499) inicio:");
                            //Zend_Debug::dump($model->datinicio );
                        } else {
                            if ($model->numfolga > 0) {
                                //Zend_Debug::dump("492) numfolga:" . $model->numfolga);exit;
                                //Zend_Debug::dump("505) inicio:");
                                //Zend_Debug::dump($model->datinicio );
                                $nFolga = $nFolga + 2;
                                $nFolga = $nFolga + $model->numfolga;
                                $model->datinicio = $this->preparaData($this->adicionarDias($dataPredecessora->format('d/m/Y'),
                                    $nFolga));
                                //Zend_Debug::dump("510) inicio:");
                                //Zend_Debug::dump($model->datinicio );
                            } else {
                                //Zend_Debug::dump("1) datinicio:" . $model->datinicio);
                                $nFolga = 1;
                                $nFolga = $nFolga + ($model->numfolga * (-1));
                                $model->datinicio = $this->preparaData($this->subtrairDias($dataPredecessora->format('d/m/Y'),
                                    $nFolga));
                                //Zend_Debug::dump("2) datinicio:" . $model->datinicio);
                                //exit;
                            }
                        }
                    } else {
                        if ($model->numfolga >= 0) {
                            $nFolga = $nFolga + 1;
                            $nFolga = $nFolga + $model->numfolga;
                            $model->datinicio = $this->preparaData($this->adicionarDias($dataPredecessora->format('d/m/Y'),
                                $nFolga));
                        } else {
                            $nFolga = 1;
                            $nFolga = $nFolga + ($model->numfolga * (-1));
                            $model->datinicio = $this->preparaData($this->subtrairDias($dataPredecessora->format('d/m/Y'),
                                $nFolga));
                        }
                    }
                } else {
                    $model->numfolga = 0;
                }
            }
            if ($model->datinicio > $model->datfim) {
                $model->datfim = $model->datinicio;
            }
            $model->numdiasbaseline = null;
            $model->numdias = $model->retornaDiasReal();
            //$model->numdiasbaseline = $model->retornaDiasBaseLine()+1;
            $model->numdiasbaseline = null;
            $model->datiniciobaseline = null;
            $model->datfimbaseline = null;
            try {
                $atividade = $this->_mapper->atualizarAtividade($model);
                //Fazer consulta na tabela atividadepredecessora para verificar se existe esta atividade sucessora
                $paramSucessora = array(
                    'idprojeto' => $atividade->idprojeto,
                    'idatividadecronograma' => $atividade->idatividadecronograma
                );
                $entitySucessoras = $this->retornaSucessorasAtividade($paramSucessora);
                $qtdAtividadeSucessora = count($entitySucessoras);
                if ($qtdAtividadeSucessora > 0) {
                    $serviceAtividadePredecessora = new Projeto_Service_AtividadePredecessora();
                    $serviceAtividadePredecessora->atualizarModelAtividadeSucessora($atividade->idprojeto,
                        $entitySucessoras, $atividade);
                }
                /***************** versao anterior -  03-11-2017 ************************************* /
                 * $serviceAtividadePredecessora = new Projeto_Service_AtividadePredecessora();
                 * $entitySucessoras = $serviceAtividadePredecessora->retornaAtividadeSucessora($params,false);
                 * $qtdAtividadeSucessora = count($entitySucessoras);
                 * if ($qtdAtividadeSucessora > 0) {
                 * $serviceAtividadePredecessora->atualizarAtividadeSucessora($atividade->idprojeto, $entitySucessoras, $atividade);
                 * }
                 * /******************************************************/
                $this->atualizarDatasEntrega($model);
                $this->atualizarPercentuaisGrupoEntrega(array(
                    'idprojeto' => $model->idprojeto,
                    'idatividadecronograma' => $model->idatividadecronograma
                ));
                //$this->atualizarCronogramaDoProjeto(array('idprojeto'=>$model->idprojeto));
                return $model;
            } catch (Exception $exc) {
                throw $exc;
            }
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function subtrairDias($data, $dias)
    {
        $novaData = new Zend_Date();
        $novaData->set($data);
        if ($dias > 0) {
            $novaData->subDay(abs($dias));
        }
        return substr($novaData, 0, 10);
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
        return substr($novaData, 0, 10);
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

    public function atualizarAtividadePercentual($dados)
    {
        if (isset($dados['flainformatica']) && $dados['flainformatica'] == 'N') {
            $dados['flainformatica'] = null;
        }
        $dados = array_filter($dados);
        $params = $this->montaArray($dados);
        // Adicionado em 07/02/2017 pois estava mudando o domtipoatividade erradamente, pois chegava vazio
        $params = array_filter($params);
        $form = $this->getFormAtividadeAtualizarPercentual($params);
        if ($form->isValidPartial($params)) {
            $model = new Projeto_Model_Atividadecronograma($form->getValues());
            $serviceAtividadePredecessora = new Projeto_Service_AtividadePredecessora();
            $datBaseLine = $this->retornaInicioBaseLinePorAtividade($dados);
            if (@trim($datBaseLine) != "") {
                $dataPredecessora = $this->preparaData($datBaseLine);
                if ($dataPredecessora) { //Verifica maior data predecessora
                    //verificar se os dias reais da atividade é maior que zero
                    if ($model->numfolga >= 0) {
                        $nFolga = ($model->numfolga > 0 ? $model->numfolga + 2 : $model->numfolga);
                        $model->datinicio = $this->preparaData($this->adicionarDias($dataPredecessora->format('d/m/Y'),
                            $nFolga));
                    } elseif ($model->numfolga < 0) {
                        $model->datinicio = $this->preparaData($this->subtrairDias($dataPredecessora->format('d/m/Y'),
                            $model->numfolga));
                    }
                } else {
                    $model->numfolga = 0;
                }
            }
            /*
            //verificar se os dias reais da atividade sucessora é maior que zero
            if ($model->numdiasrealizados >= 0) {
                $model->datfim = $this->preparaData($this->adicionarDias($model->datinicio->format('d/m/Y'), $model->numdiasrealizados));
            } elseif ($model->numdiasrealizados < 0) {
                $model->datfim = $this->preparaData($this->subtrairDias($model->datinicio->format('d/m/Y'), $model->numdiasrealizados));
            }*/
            if ($model->domtipoatividade == "4") {
                $model->datfim = $model->datinicio;
                $model->numdias = 0;
                $model->numdiasrealizados = 0;
            } else {
                $model->numdias = $model->retornaDiasReal();
                $model->numdiasrealizados = $model->numdiasrealizados;
            }
            if ($model->datfim < $model->datinicio) {
                if ($model->domtipoatividade == "4") {
                    $model->datfim = $model->datinicio;
                    $model->numdias = 0;
                    $model->numdiasrealizados = 0;
                } else {
                    $model->datfim = $this->preparaData($this->adicionarDias($model->datinicio->format('d/m/Y'), 1));
                    $model->numdias = 1;
                    $model->numdiasrealizados = 1;
                }
            }
            $atividade = $this->_mapper->atualizarAtividade($model);
            $this->atualizarDatasEntrega($model);
            $this->atualizarPercentuaisGrupoEntrega(array(
                'idprojeto' => $model->idprojeto,
                'idatividadecronograma' => $model->idatividadecronograma
            ));
            //Fazer consulta na tabela atividadepredecessora para verificar se existe esta atividade sucessora
            $entitySucessoras = $serviceAtividadePredecessora->retornaAtividadeSucessora($params);
            $qtdAtividadeSucessora = count($entitySucessoras);
            if ($qtdAtividadeSucessora > 0) {
                $serviceAtividadePredecessora->atualizarAtividadeSucessora($model->idprojeto, $entitySucessoras,
                    $model);
            }
            return $model;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    private function montaArray($dados)
    {
        $params = null;

        if (isset($dados['datinicio'])) {
            $params = array(
                'idgrupo' => @$dados['idgrupo'],
                'domtipoatividade' => @$dados['domtipoatividade'],
                'nomatividadecronograma' => @$dados['nomatividadecronograma'],
                'numpercentualconcluido' => @$dados['numpercentualconcluido'],
                'flacancelada' => @$dados['flacancelada'],
                'flaaquisicao' => @$dados['flaaquisicao'],
                'idparteinteressada' => @$dados['idparteinteressada'],
                'predecessora' => @$dados['predecessora'],
                'numfolga' => @$dados['numfolga'],
                'idpredecessora' => @$dados['idpredecessora'],
                'datInicioHidden' => @$dados['datInicioHidden'],
                'idprojeto' => @$dados['idprojeto'],
                'idatividadecronograma' => @$dados['idatividadecronograma'],
                'vlratividadebaseline' => @$dados['vlratividadebaseline'],
                'numdiasrealizados' => @$dados['numdiasrealizados'],
                'datinicio' => @$dados['datinicio'],
                'datfim' => @$dados['datfim'],
                'datiniciobaseline' => @$dados['datiniciobaseline'],
                'datfimbaseline' => @$dados['datfimbaseline'],
                'vlratividade' => @$dados['vlratividade'],
                'flainformatica' => @$dados['flainformatica'],
                'desobs' => @$dados['desobs']
            );
            if (isset($dados['idelementodespesa'])) {
                $params['idelementodespesa'] = @$dados['idelementodespesa'];
            }
        } else {
            $params = array(
                'idgrupo' => @$dados['idgrupo'],
                'domtipoatividade' => @$dados['domtipoatividade'],
                'nomatividadecronograma' => @$dados['nomatividadecronograma'],
                'numpercentualconcluido' => @$dados['numpercentualconcluido'],
                'flacancelada' => @$dados['flacancelada'],
                'flaaquisicao' => @$dados['flaaquisicao'],
                'idparteinteressada' => @$dados['idparteinteressada'],
                'predecessora' => @$dados['predecessora'],
                'numfolga' => @$dados['numfolga'],
                'idpredecessora' => @$dados['idpredecessora'],
                'datInicioHidden' => @$dados['datInicioHidden'],
                'idprojeto' => @$dados['idprojeto'],
                'idatividadecronograma' => @$dados['idatividadecronograma'],
                'vlratividadebaseline' => @$dados['vlratividadebaseline'],
                'numdiasrealizados' => @$dados['numdiasrealizados'],
                'datinicio' => @$dados['datInicioHidden'],
                'datfim' => @$dados['datfim'],
                'datiniciobaseline' => @$dados['datiniciobaseline'],
                'datfimbaseline' => @$dados['datfimbaseline'],
                'vlratividade' => @$dados['vlratividade'],
                'flainformatica' => @$dados['flainformatica'],
                'desobs' => @$dados['desobs']
            );
            if (isset($dados['idelementodespesa'])) {
                $params['idelementodespesa'] = @$dados['idelementodespesa'];
            }
        }
        return $params;
    }


    /**
     * Excluir Grupo
     * @param array $dados
     */
    public function excluir($dados)
    {
        return $this->_mapper->excluir($dados);
    }

    public function getAtividadeById($atividade, $projeto)
    {
        return $this->_mapper->getAtividadeById($atividade, $projeto);
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

    public function retornaSucessorasAtividade($params, $predecessora = false, $collection = true)
    {
        return $this->_mapper->retornaSucessorasAtividade($params, $predecessora, $collection);
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
        $novaDataFim = "";
        if ($resultado['numfolga'] > 0) {
            if ($resultado['datfim'] != "") {
                $numFolga = $resultado['numfolga'];
                $DataFim = $this->preparaData($resultado['datfim']);
                $novaDataFim = $this->adicionarDias($DataFim->format('d/m/Y'),
                    ($params['domtipoatividade'] == "3" ? $numFolga + 2 : $numFolga + 1));
            }
        } else {
            if ($resultado['datfim'] != "") {
                $DataFim = $this->preparaData($resultado['datfim']);
                $novaDataFim = $this->adicionarDias($DataFim->format('d/m/Y'),
                    ($params['domtipoatividade'] == "3" ? 2 : 1));
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
        //$predecessoras[] = $params['predecessora'];
        $params['predecessora'] = $predecessoras;
        return $this->_mapper->retornaInicioBaseLinePorPredecessoras($params);
    }

    public function retornaInicioRealPorPredecessoras($params)
    {
        return $this->_mapper->retornaInicioRealPorPredecessoras($params);
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
     *
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

    /**
     *
     * @param array $params
     * @param boolean $array
     * @return Projeto_Model_AtividadeCronograma or array
     */
    public function retornaAtividadePorId($params, $predecessoras = false, $pairspredecessoras = false)
    {
        return $this->_mapper->retornaAtividadePorId($params, $predecessoras, $pairspredecessoras);
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
                $grupo = $this->_mapper->retornaGrupoPorAtividade(array(
                    'idprojeto' => $params['idprojeto'],
                    'idatividadecronograma' => $params['idatividadecronograma']
                ));
            }
            foreach ($grupo->entregas as $j => $entrega) {
                /* @var $entrega Projeto_Model_Entregacronograma */
                $atividades = $this->retornaIdAtividadePorEntrega($entrega->idprojeto, $entrega->idatividadecronograma);
                if (count($atividades) > 0) {
                    $dados = $entrega->toArray();
                    $percentuais = $entrega->retornaPercentuais();
                    $dados['numpercentualconcluido'] = $percentuais->numpercentualconcluido;
                    $obj->setFromArray($dados);
                    $this->_mapper->atualizarPercentuaisGrupoEntrega($obj);
                }
            }
            $percentuaisGrupo = $grupo->retornaPercentuais();
            $dadosGrupo['idprojeto'] = $grupo->idprojeto;
            $dadosGrupo['idatividadecronograma'] = $grupo->idgrupo;
            $dadosGrupo['numpercentualconcluido'] = $percentuaisGrupo->numpercentualconcluido;
            $obj->setFromArray($dadosGrupo);
            $this->_mapper->atualizarPercentuaisGrupoEntrega($obj);

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
        $model = new Projeto_Model_Atividadecronograma($params);
        $serviceAtividadePredecessora = new Projeto_Service_AtividadePredecessora();
        $datBaseLine = $this->retornaInicioBaseLinePorAtividade($params);
        if (@trim($datBaseLine) != "") {
            $dataPredecessora = $this->preparaData($datBaseLine);
            $nFolga = 0;
            if ($dataPredecessora) { //Verifica maior data predecessora
                if ($params['domtipoatividade'] == "3") {
                    if ($model->numfolga >= 0) {
                        if ($model->numfolga > 0) {
                            $nFolga = $nFolga + 2;
                            $nFolga = $nFolga + $model->numfolga;
                            $model->datinicio = $this->preparaData($this->adicionarDias($dataPredecessora->format('d/m/Y'),
                                $nFolga));
                        } else {
                            $nFolga = $nFolga + 2;
                            $nFolga = $nFolga + $model->numfolga;
                            $model->datinicio = $this->preparaData($this->adicionarDias($dataPredecessora->format('d/m/Y'),
                                $nFolga));
                        }
                    } else {
                        $nFolga = $model->numfolga;
                        $nFolga = ($nFolga * (-1)) - 1;
                        $model->datinicio = $this->preparaData($this->subtrairDias($dataPredecessora->format('d/m/Y'),
                            $nFolga));
                    }
                    $novaDataFim = $this->preparaData($this->adicionarDias($model->datinicio->format('d/m/Y'),
                        $model->numdiasrealizados));
                    $model->datfim = $novaDataFim;
                } else {
                    if ($model->numfolga >= 0) {
                        $nFolga = $nFolga + 1;
                        $nFolga = $nFolga + $model->numfolga;
                        $model->datinicio = $this->preparaData($this->adicionarDias($dataPredecessora->format('d/m/Y'),
                            $nFolga));
                    } else {
                        $nFolga = $model->numfolga;
                        $nFolga = ($nFolga * (-1));
                        $model->datinicio = $this->preparaData($this->subtrairDias($dataPredecessora->format('d/m/Y'),
                            $nFolga));
                    }
                }
            } else {
                $model->numfolga = 0;
            }
        }
        if ($model->datfim < $model->datinicio) {
            $model->datfim = $model->datinicio;
        }
        if ($model->domtipoatividade == "4") {
            $model->datfim = $model->datinicio;
            $model->numdias = 0;
            $model->numdiasrealizados = 0;
        } else {
            $model->numdias = $model->retornaDiasReal();
        }
        return $this->_mapper->atualizarTipoAtividade($model);
    }

    public function pesquisar($params)
    {
        return $this->_mapper->pesquisar($params);
    }

    public function excluirAtividade($dados)
    {
        $serviceAtividadePredecessora = new Projeto_Service_AtividadePredecessora();
        $retorno = false;
        try {
            $retornaSucessoras = $serviceAtividadePredecessora->retornaAtividadeCountPredec(
                array(
                    'idatividadecronograma' => $dados['idatividadecronograma'],
                    'idprojeto' => $dados['idprojeto']
                )
            );
            if (!($retornaSucessoras)) {
                $retorno = $this->excluirAtividadeSemSucessora($dados);
            }
            return $retorno;
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function excluirAtividadeSemSucessora($dados)
    {
        $serviceAtividadePredecessora = new Projeto_Service_AtividadePredecessora();
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
            $grupo = $this->_mapper->retornaGrupoPorEntrega($dados);
            $retorno = $this->_mapper->excluir($dados);
            if ($retorno) {
                $this->atualizarPercentuaisGrupoEntrega($dados, $grupo);
            }
            return $retorno;
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function clonarGrupo($params)
    {

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
                    $this->atualizarDatasEntrega($ativ);
                }
            }
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
        $db = $this->_db;
        $db->beginTransaction();
        try {
            $this->clonarCronograma($params);
            $db->commit();
            return true;
        } catch (Exception $exc) {
            $db->rollBack();
            throw $exc;
        }
    }

    public function clonarCronograma($params)
    {
        $gruposProjeto = $this->retornaGrupoPorProjeto($params);
        if (count($gruposProjeto) > 0) {
            for ($i = 0; $i < count($gruposProjeto); $i++) {
                $paramGrupo = array(
                    'idprojeto' => $gruposProjeto[$i]['idprojeto'],
                    'idatividadecronograma' => $gruposProjeto[$i]['idatividadecronograma']
                );
                $grupo = $this->retornaGrupoPorId($paramGrupo, true);
                $entregas = $this->_mapper->retornaEntrega(array(
                    'idprojeto' => $paramGrupo['idprojeto'],
                    'idgrupo' => $paramGrupo['idatividadecronograma']
                ));
                try {
                    $grupo->idprojeto = $params['idprojetoorigem'];
                    $modelGrupo = $this->_mapper->inserirGrupo($grupo);
                    if (count($entregas) > 0) {
                        foreach ($entregas as $ent) {
                            $ent->idgrupo = $modelGrupo->idatividadecronograma;
                            $ent->idprojeto = $params['idprojetoorigem'];
                            $ent->idparteinteressada = "";
                            $modelEntrega = $this->_mapper->inserirEntrega($ent);
                            if (count($ent->atividades) > 0) {
                                foreach ($ent->atividades as $ativ) {
                                    /* @var $ativ Projeto_Model_Atividadecronograma */
                                    $idAtividadeAnterior = $ativ->idatividadecronograma;
                                    $ativ->idprojeto = $params['idprojetoorigem'];
                                    $ativ->idparteinteressada = "";
                                    $ativ->idgrupo = $modelEntrega->idatividadecronograma;
                                    if (count($ativ->predecessoras) > 0) {
                                        $ativ->numfolga = 0;
                                        $ativ->predecessoras = null;
                                    } else {
                                        $ativ->numfolga = 0;
                                    }
                                    $novaModelAtividade = $this->_mapper->inserirAtividade($ativ);
                                    $ativ->idatividadecronograma = $novaModelAtividade->idatividadecronograma;
                                    $ativ->idprojeto = $novaModelAtividade->idprojeto;
                                    $ativ->idgrupo = $novaModelAtividade->idgrupo;
                                    $this->atualizarDatasEntrega($ativ);
                                }
                            }
                        }
                    }
                } catch (Exception $exc) {
                    throw $exc;
                }
            }
        }
    }

    public function clonarCronograma1($params)
    {
        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $mapperPredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
        $idsAtividades = array();
        $predecessoras = array();
        $projeto = $mapperGerencia->retornaProjetoPorId($params);

        foreach ($projeto->grupos as $gr) {
            $gr['idprojeto'] = $params['idprojetoorigem'];
            $insereGrupo = $this->_mapper->inserirGrupo($gr);

            foreach ($gr->entregas as $en) {
                /* @var $en Projeto_Model_Entregacronograma */
                $en->idprojeto = $params['idprojetoorigem'];
                $en->idgrupo = $insereGrupo->idatividadecronograma;
                $en->parteinteressada->idprojeto = $params['idprojetoorigem'];

                /* @var $parteInteressadaEntrega Projeto_Model_Parteinteressada */
                $parteInteressadaEntrega = $en->parteinteressada;
                //verifica se existe parte interessada interna responsavel pela atividade
                if (!empty($parteInteressadaEntrega)) {
                    $novaParteInteressadaInterna = $this->inserirParteInteressada($parteInteressadaEntrega);
                    $en->idparteinteressada = $novaParteInteressadaInterna->idparteinteressada;
                    $en->parteinteressada->idparteinteressada = $novaParteInteressadaInterna->idparteinteressada;
                }

                $insereEntrega = $this->_mapper->inserirEntrega($en);

                foreach ($en->atividades as $at) {
                    /* @var $at Projeto_Model_Atividadecronograma */

                    $idAntigo = $at->idatividadecronograma;
                    $at->idprojeto = $params['idprojetoorigem'];
                    $at->idgrupo = $insereEntrega->idatividadecronograma;
                    $at->parteinteressada->idprojeto = $params['idprojetoorigem'];

                    /* @var $parteInteressadaAtividade Projeto_Model_Parteinteressada */
                    $parteInteressadaAtividade = $at->parteinteressada;

                    //verifica se existe parte interessada interna responsavel pela atividade
                    if (!empty($parteInteressadaAtividade)) {
                        $novaParteInteressadaInterna = $this->inserirParteInteressada($parteInteressadaAtividade);
                        $at->idparteinteressada = $novaParteInteressadaInterna->idparteinteressada;
                        $at->parteinteressada->idparteinteressada = $novaParteInteressadaInterna->idparteinteressada;
                    }

                    $insereAtividade = $this->_mapper->inserirAtividade($at, false);
                    $idsAtividades[$insereAtividade->idatividadecronograma] = $idAntigo;

                    if (count($at->predecessoras) > 0) {
                        foreach ($at->predecessoras as $pr) {
                            $predecessoras[] = $pr;
                        }
                    }

                }
                //$datasEn = $this->_mapper->retornaDatasPorEntrega(array('idprojeto' => $params['idprojetoorigem'], 'idgrupo' => $insereEntrega->idatividadecronograma));
                //$en->setFromArray($datasEn);
                $this->_mapper->atualizarDatasEntrega($en);
            }
            //$datasGr = $this->_mapper->retornaDatasPorGrupo(array('idprojeto' => $params['idprojetoorigem'], 'idgrupo' => $insereGrupo->idatividadecronograma));
            //$gr->setFromArray($datasGr);

            $this->_mapper->atualizarDatasGrupo($gr);
        }

        if (count($predecessoras) > 0) {

            foreach ($predecessoras as $pre) {
                $novoIdAtividadePredecessora = array_search($pre['idatividadepredecessora'], $idsAtividades);
                $novoIdAtividade = array_search($pre['idatividade'], $idsAtividades);
                if (!empty($novoIdAtividadePredecessora) && !empty($novoIdAtividade)) {
                    $pre['idatividadepredecessora'] = $novoIdAtividadePredecessora;
                    $pre['idatividade'] = $novoIdAtividade;
                    $pre['idprojeto'] = $params['idprojetoorigem'];
                    $inserePredecessora = $mapperPredecessora->insert($pre);

                    $arrPredecessora = array(
                        'idprojeto' => $params['idprojetoorigem'],
                        'idatividadecronograma' => $novoIdAtividadePredecessora
                    );

                    $arrayAtividadePredecessora = $this->retornaAtividadePorId($arrPredecessora, false);

                    $dados = array(
                        'idprojeto' => $params['idprojetoorigem'],
                        'idatividadecronograma' => $novoIdAtividade
                    );

                    $arrayAtividade = $this->retornaAtividadePorId($dados, false);

                    $maiorData = $this->retornaInicioBaseLinePorAtividade($dados);

                    if ($this->compararDadas($arrayAtividadePredecessora['datfim'], $maiorData)) {
                        $dataPredecessora = $arrayAtividadePredecessora['datfim'];
                    } else {
                        $dataPredecessora = $maiorData;
                    }

                    $novoArrayAtividade = $this->flushAtividade($arrayAtividade, $dataPredecessora);
                    $modelAtividade = new Projeto_Model_Atividadecronograma($novoArrayAtividade);
                    $modelAtividade->numdias = $modelAtividade->retornaDiasReal();
                    $modelAtividade->numdiasrealizados = $modelAtividade->numdiasrealizados;
                    $this->_mapper->atualizarAtividade($modelAtividade);
                    $this->atualizarDatasEntrega($modelAtividade);
                    //$this->atualizarPercentuaisGrupoEntrega(array('idprojeto' => $modelAtividade->idprojeto, 'idatividadecronograma' => $modelAtividade->idatividadecronograma));
                }
            }
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
                if ($this->_mapper->cancelaAtividade($itemAtividade, $flag)) {
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

    public function ordenarGrupoEntrega($params)
    {
        $acao = $params['acaoatividade'];
        $atividade = $this->_mapper->getAtividadeByProjetoId(
            array(
                'idprojeto' => $params['idprojeto'],
                'idatividadecronograma' => $params['idatividadecronograma']
            )
        );
        /*
        domtipoatividade: * 1 - grupo      * 2 - entrega
                          * 3 - atividade  * 4 - marco
        */
        if (count($atividade) > 0) {
            //if (/*($atividade->numseq == 1) &&*/ (($acao == 1) || ($acao == 2))) {
            //    return true;
            //} else {
            if (($atividade->domtipoatividade == 1) || ($atividade->domtipoatividade == 2)) {
                try {
                    $filtro = array(
                        'idProjeto_pesq' => $params['idprojeto'],
                        'domtipoatividade_pesq' => $atividade->domtipoatividade,
                        'idgrupo_pesq' => ($atividade->domtipoatividade == 1 ? "" : $atividade->idgrupo),
                        'numseq_pesq' => 1,
                    );
                    $listaAtividades = $this->_mapper->pesquisar($filtro);
                    /* **************** GRUPO *************/
                    if ($atividade->domtipoatividade == 1) {
                        $gruposProjeto = $this->_mapper->retornaGrupoPorProjeto($params);
                        if (count($listaAtividades) > 1) {
                            if ($this->ordenaListaAtividade($gruposProjeto)) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            $numOrdem = ($acao == 1 ? 1 :
                                ($acao == 2 ? $atividade->numseq - 1 :
                                    ($acao == 3 ? $atividade->numseq + 1 : 999)
                                )
                            );
                            $numOrdem = ($numOrdem < 1 ? 1 : $numOrdem);
                            //$numOrdem = ($numOrdem>count($listaAtividades) ? count($listaAtividades) : $numOrdem);
                            if ($this->ordenaItemListaAtividade($gruposProjeto, $atividade->idatividadecronograma,
                                $numOrdem)) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    } else {
                        /* **************** ENTREGA *************/
                        $entregasProjeto = $this->_mapper->retornaEntrega(
                            array(
                                'idprojeto' => $params['idprojeto'],
                                'idgrupo' => $atividade->idgrupo,
                            )
                        );
                        if (count($listaAtividades) > 1) {
                            if ($this->ordenaListaAtividade($entregasProjeto)) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            $numOrdem = ($acao == 1 ? 1 :
                                ($acao == 2 ? $atividade->numseq - 1 :
                                    ($acao == 3 ? $atividade->numseq + 1 : 999)
                                )
                            );
                            $numOrdem = ($numOrdem < 1 ? 1 : $numOrdem);
                            //$numOrdem = ($numOrdem>count($listaAtividades) ? count($listaAtividades) : $numOrdem);
                            if ($this->ordenaItemListaAtividade($entregasProjeto, $atividade->idatividadecronograma,
                                $numOrdem)) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                } catch (Exception $exc) {
                    return false;
                }
            } else {
                return false;
            }
            //}
        } else {
            return false;
        }
    }

    public function ordenaListaAtividade($atividades)
    {
        if (count($atividades) > 0) {
            try {
                $indice = 0;
                foreach ($atividades as $ativ) {
                    if (isset($ativ->idatividadecronograma)) {
                        $indice++;
                        $itemAtividade = new Projeto_Model_Atividadecronograma($ativ);
                        $this->_mapper->atualizaNumSeqAtividade(
                            $itemAtividade, $indice
                        );
                    }
                }
                return ($indice > 0 ? true : false);
            } catch (Exception $exc) {
                return false;
            }
        } else {
            return false;
        }
    }

    public function ordenaItemListaAtividade($atividades, $numidatividade, $numordem)
    {
        if (count($atividades) > 0) {
            try {
                $maxindice = count($atividades);
                if (($numordem == 999) || ($numordem > count($atividades))) {
                    $numordem = $maxindice;
                }
                $indice = 1;
                foreach ($atividades as $ativ) {
                    if (isset($ativ->idatividadecronograma)) {
                        if ($indice == $numordem) {
                            if ($ativ->idatividadecronograma == $numidatividade) {
                                $itemAtividade = new Projeto_Model_Atividadecronograma($ativ);
                                $this->_mapper->atualizaNumSeqAtividade(
                                    $itemAtividade, $numordem
                                );
                                $indice++;
                            } else {
                                $indice++;
                                $itemAtividade = new Projeto_Model_Atividadecronograma($ativ);
                                $this->_mapper->atualizaNumSeqAtividade(
                                    $itemAtividade, $indice
                                );
                                $indice++;
                            }
                        } else {
                            if ($ativ->idatividadecronograma == $numidatividade) {
                                $itemAtividade = new Projeto_Model_Atividadecronograma($ativ);
                                $this->_mapper->atualizaNumSeqAtividade(
                                    $itemAtividade, $numordem
                                );
                            } else {
                                $itemAtividade = new Projeto_Model_Atividadecronograma($ativ);
                                $this->_mapper->atualizaNumSeqAtividade(
                                    $itemAtividade, $indice
                                );
                                $indice++;
                            }
                        }
                    }
                }
                return ($maxindice > 0 ? true : false);
            } catch (Exception $exc) {
                return false;
            }
        } else {
            return false;
        }
    }

    public function flushAtividade($arrayAtividade, $dataPredecessora)
    {
        //verificar se os dias reais da atividade é maior que zero
        if ($arrayAtividade['numfolga'] >= 0) {
            $arrayAtividade['datinicio'] = $this->adicionarDias($dataPredecessora, $arrayAtividade['numfolga']);
        } elseif ($arrayAtividade['numfolga'] < 0) {
            $arrayAtividade['datinicio'] = $this->subtrairDias($dataPredecessora, $arrayAtividade['numfolga']);
        }
        //verificar se os dias reais da atividade sucessora é maior que zero
        if ($arrayAtividade['numdiasrealizados'] >= 0) {
            $arrayAtividade['datfim'] = $this->adicionarDias($arrayAtividade['datinicio'],
                $arrayAtividade['numdiasrealizados']);
        } elseif ($arrayAtividade['numdiasrealizados'] < 0) {
            $arrayAtividade['datfim'] = $this->subtrairDias($arrayAtividade['datinicio'],
                $arrayAtividade['numdiasrealizados']);
        }
        return $arrayAtividade;
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

        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $db = $this->_db;
        $db->beginTransaction();
        try {
            $projeto = $mapperGerencia->retornaProjetoPorId($params);
            foreach ($projeto->grupos as $gr) {
                foreach ($gr->entregas as $en) {
                    foreach ($en->atividades as $at) {
                        $at->datiniciobaseline = $at->datinicio;
                        $at->datfimbaseline = $at->datfim;
                        $at->numdiasbaseline = $at->numdiasrealizados;
                        $this->_mapper->atualizarDatasAtividade($at);
                    }
                    $en->datiniciobaseline = $en->datinicio;
                    $en->datfimbaseline = $en->datfim;
                    if (!empty($en->datiniciobaseline) && !empty($en->datfimbaseline)) {
                        $this->_mapper->atualizarDatasEntrega($en);
                    }
                }
                $gr->datiniciobaseline = $gr->datinicio;
                $gr->datfimbaseline = $gr->datfim;
                $this->_mapper->atualizarDatasGrupo($gr);
            }
            $db->commit();
            return true;
        } catch (Exception $exc) {
            $db->rollBack();
            throw $exc;
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

    public function fetchPairsProjetos($params)
    {

        $mapperGerencia = new Projeto_Model_Mapper_Gerencia();
        $dados = $mapperGerencia->buscarProjetos($params);
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
                    $valorfinal = substr($custoProjeto, 0, -2) . '.' . substr($custoProjeto, -2);
                    $projetoArray[$cont]['grupos'][$i] = $gr;
                    //$contGrupo++;
                }
                //$contGrupo += count($projetoArray[$cont]['grupos']);

            }
            $projetoArray[$cont]['custoProjeto'] = number_format($valorfinal, 2, ',', '.');
            $custoTodosProjetos += $custoProjeto;
            $cont++;
        }
        $custoTodosProjetos = substr($custoTodosProjetos, 0, -2) . '.' . substr($custoTodosProjetos, -2);
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
                $valorfinal = substr($custoProjeto, 0, -2) . '.' . substr($custoProjeto, -2);
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
                        if (($r['atrasada'] == 'S') && ($r['concluida'] == 'N') && ($r['cancelada'] == 'N')) {
                            $dataTmp .= $r['datinicio'] . " - " . $r['datfim'] . " - " . $r['nomatividadecronograma'] . "\n";
                            $ResultSaida[] = array(
                                'idatividadecronograma' => $r["idatividadecronograma"],
                                'datinicio' => $r["datinicio"],
                                'datfim' => $r["datfim"],
                                'nomatividadecronograma' => $r["nomatividadecronograma"]
                            );
                        }
                        $atividadeIrreg = $this->_mapper->retornaIrregularidadesAtividades(
                            array(
                                'idprojeto' => $r['idprojeto'],
                                'idgrupo' => $r['idatividadecronograma'],
                                'domtipoatividade' => 3,
                                'atividadeAtiva' => true,
                                'atividadeConcluida' => false,
                                'dtfim' => false
                            )
                        );
                        foreach ($atividadeIrreg as $at) {
                            if (($at['atrasada'] == 'S') && ($at['concluida'] == 'N') && ($at['cancelada'] == 'N')) {
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
                            'dtfim' => true
                        )
                    );
                    foreach ($atividadeIrreg as $at1) {
                        //if (($at1['atrasada'] == 'S')&&($at1['concluida'] == 'N')&&($at1['cancelada'] == 'N')) {
                        $dataTmp .= $at1['datinicio'] . " - " . $at1['datfim'] . " - " . $at1['nomatividadecronograma'] . "\n";
                        $ResultSaida[] = array(
                            'idatividadecronograma' => $at1["idatividadecronograma"],
                            'datinicio' => $at1["datinicio"],
                            'datfim' => $at1["datfim"],
                            'nomatividadecronograma' => $at1["nomatividadecronograma"]
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
                                $novaDataFim = $this->preparaData($this->adicionarDias($atividade->datinicio->format('d/m/Y'),
                                    $atividade->numdiasrealizados));
                                $atividade->datfim = $novaDataFim;
                            } else {
                                $atividade->datfim = $atividade->datinicio;
                            }

                            $atividade->numdias = $atividade->retornaDiasReal();
                            $atividade->numdiasrealizados = $atividade->numdiasrealizados;

                            $atv = $this->_mapper->atualizarAtividade($atividade);

                            $serviceAtividadePredecessora = new Projeto_Service_AtividadePredecessora();

                            //Fazer consulta na tabela atividadepredecessora para verificar se existe esta atividade sucessora
                            $entitySucessoras = $serviceAtividadePredecessora->retornaAtividadeSucessora($paramsAtividade);

                            $qtdAtividadeSucessora = count($entitySucessoras);
                            if ($qtdAtividadeSucessora > 0) {
                                $serviceAtividadePredecessora->atualizarAtividadeSucessora($atividade->idprojeto,
                                    $entitySucessoras, $atividade);
                            }

                            $this->atualizarDatasEntrega($atividade);
                            $this->atualizarPercentuaisGrupoEntrega(array(
                                'idprojeto' => $atividade->idprojeto,
                                'idatividadecronograma' => $atividade->idatividadecronograma
                            ));
                        }
                    }
                }
                $contador++;
            }
            if ($contador == $contaGrupo) {
                $retorno = true;
            }
        }
        return $retorno;
    }


}

?>