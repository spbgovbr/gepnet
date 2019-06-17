<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Gerencia extends App_Model_ModelAbstract
{

    const STATUS_PROPOSTA = 1;
    const STATUS_ANDAMENTO = 2;
    const STATUS_CONCLUIDO = 3;
    const STATUS_PARALISADO = 4;
    const STATUS_CANCELADO = 5;
    const STATUS_BLOQUEADO = 6;
    const STATUS_ALTERACAO = 7;
    const STATUS_EXCLUIDO = 8;

    const TIPO_INICIATIVA_PROJETO = 1;
    /* NÃO UTILIZADO */
    const TIPO_INICIATIVA_PLANODEACAO = 2;

    public $idprojeto = null;
    public $nomcodigo = null;
    public $nomsigla = null;
    public $nomprojeto = null;
    public $idsetor = null;
    public $idgerenteprojeto = null;
    public $idgerenteadjunto = null;
    public $desprojeto = null;
    public $desobjetivo = null;
    public $numperiodicidadeatualizacao = null;
    public $numcriteriofarol = null;
    public $idcadastrador = null;
    public $domtipoprojeto = null;
    public $domstatusprojeto = 1;
    public $flaaprovado = null;
    public $desresultadosobtidos = null;
    public $despontosfortes = null;
    public $despontosfracos = null;
    public $dessugestoes = null;
    public $idescritorio = null;
    public $flaaltagestao = null;
    public $idobjetivo = null;
    public $idacao = null;
    public $flacopa = null;
    public $idnatureza = null;
    public $vlrorcamentodisponivel = null;
    public $desjustificativa = null;
    public $iddemandante = null;
    public $idpatrocinador = null;
    public $desescopo = null;
    public $desnaoescopo = null;
    public $flapublicado = null;
    public $despremissa = null;
    public $desrestricao = null;
    public $numseqprojeto = null;
    public $numanoprojeto = null;
    public $nummatricula = null;
    public $desconsideracaofinal = null;
    public $idprograma = null;
    public $datenviouemailatualizacao = null;
    public $datinicioplano = null;
    public $datfimplano = null;
    public $datcadastro = null;
    public $datinicio = null;
    public $datfim = null;
    public $ano = null;
    public $idportfolio = null;
    public $idtipoiniciativa = null;
    public $numdiasrealizados = null;
    public $numdiasrealizadosreal = null;
    public $numdiasbaseline = null;
    public $numdiascompletos = null;
    public $numpercentualconcluido = 0;
    public $numpercentualprevisto = 0;
    public $totaldiasbaseline = null;
    public $datCalculo = null;
    public $dataFimMeta = null;
    public $dataFimTendencia = null;
    public $dataCriterioFarol = null;
    public $numprocessosei = null;
    public $percentualConcluidoMarco = null;
    public $atraso = null;
    public $domcoratraso = null;
    public $qtdeatividadeiniciada = null;
    public $numpercentualiniciado = 0;
    public $qtdeatividadenaoiniciada = null;
    public $numpercentualnaoiniciado = 0;
    public $qtdeatividadeconcluida = null;
    public $numpercentualatividadeconcluido = null;

    /**
     *
     * @var Projeto_Model_Statusreport
     */
    public $ultimoStatusReport = null;

    /**
     *
     * @var App_TimeInterval
     */
    public $timeInterval = null;

    /**
     *
     * atributos para o form
     */
    public $nomproponente = null;
    public $nomdemandante = null;
    public $nompatrocinador = null;
    public $nomgerenteprojeto = null;
    public $nomgerenteadjunto = null;
    public $partes = null;
    public $copa = null;
    public $publicado = null;
    public $aprovado = null;

    /**
     *
     * Atributos para relacionamento
     */

    /**
     * @var Default_Model_Pessoa
     */
    public $proponente = null;

    /**
     * @var Default_Model_Pessoa
     */
    public $demandante = null;

    /**
     * @var Default_Model_Pessoa
     */
    public $patrocinador = null;
    public $emailpatrocinador = null;
    public $matricula = null;

    /**
     * @var Default_Model_Pessoa
     */
    public $gerenteprojeto = null;
    public $emailgerenteprojeto = null;

    /**
     * @var Default_Model_Pessoa
     */
    public $gerenteadjunto = null;
    public $emailgerenteadjunto = null;

    /**
     * @var Projeto_Model_Atividadecronograma
     */
    public $grupos = null;

    /**
     * @var Default_Model_Escritorio
     */
    public $escritorio = null;

    /**
     * @var Default_Model_Programa
     */
    public $programa = null;

    /**
     * @var Default_Model_Objetivo
     */
    public $objetivo = null;

    /**
     * @var Default_Model_Natureza
     */
    public $natureza = null;

    /**
     * @var Default_Model_Setor
     */
    public $setor = null;

    /**
     * @var Default_Model_Acao
     */
    public $acao = null;

    /**
     * @var Default_Model_Comunicacao
     */
    public $comunicacao = null;

    /**
     * @var Default_Model_Risco
     */
    public $risco = null;

    /**
     * @var Default_Model_Ata
     */
    public $ata = null;

    /**
     * @var Default_Model_Aceite
     */
    public $aceite = null;

    /**
     * @var Default_Model_Escritorio
     */
    public $nomescritorio = null;

    /**
     * @var Planejamento_Model_Portfolio
     */
    public $portfolio = null;

    protected $realTotalDias = 0;
    protected $realTotalDiasExecutados = 0;

    protected $estimativaTotalDias = 0;
    protected $estimativaTotalDiasExecutados = 0;

    protected $percentuaisCalculados = false;
    protected $estimativasCalculados = false;

    public function init()
    {
        $this->timeInterval = new App_TimeInterval();
        if ((null === $this->numdiascompletos) || (null === $this->totaldiasbaseline)) {
            $this->numpercentualprevisto = 0;
        } else {
            if ($this->totaldiasbaseline > 0) {
                $this->numpercentualprevisto = $this->retornaPercentualPrevisto();
            } else {
                $this->numpercentualprevisto = 0.00;
            }
        }

        $this->getPercentualConcluidoMarco();
        $this->getAtividades();
    }

    public function retornaPercetualConcluidoMarco()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $percentualConcluidoMarcos = 0.00;
        if (!empty($this->idprojeto)) {
            $arrayProjeto = array('idprojeto' => $this->idprojeto);
            $percentualConcluidoMarcos = $serviceCronograma->retornaPercentualConcluidoMarcoByProjeto($arrayProjeto);
        }
        return $percentualConcluidoMarcos;
    }

    public function retornaAtividades()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $valores = 0.00;
        if (!empty($this->idprojeto)) {
            $arrayProjeto = array('idprojeto' => $this->idprojeto);
            $valores = $serviceCronograma->retornaPercentuaisByProjeto($arrayProjeto);
        }
        return $valores;
    }

    public function retornaDatasCronograma()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $datas = array();
        if (!empty($this->idprojeto)) {
            $arrayProjeto = array('idprojeto' => $this->idprojeto);
            $datas = $serviceCronograma->retornaMenorDataFimBaseLineAndMaiorRealizadaCronogramaByProjeto($arrayProjeto);
        }
        return $datas;
    }

    public function setAtraso()
    {
        $serviceCronograma = new Projeto_Service_AtividadeCronograma();
        $params['numcriteriofarol'] = $this->idprojeto;
        $params['idprojeto'] = $this->numcriteriofarol;
        $dadosAtraso = $serviceCronograma->calculaDiaAtrasoProjeto($params);
        $atraso = 0;
        $domcoratraso = 'default';
        if (is_object($dadosAtraso)) {
            $atraso = $dadosAtraso->totalDiasAtrasoFarol;
            $domcoratraso = $dadosAtraso->descricaoAtrazoFarol;
        }
        $this->atraso = $atraso;
        $this->domcoratraso = $domcoratraso;
    }

    public function setPercentualConcluidoMarco()
    {
        $this->percentualConcluidoMarco = $this->retornaPercetualConcluidoMarco();
    }

    public function getPercentualConcluidoMarco()
    {
        $this->setPercentualConcluidoMarco();
        return $this->percentualConcluidoMarco;
    }


    public function setAtividades()
    {
        $this->qtdeatividadeiniciada = $this->retornaAtividades();
    }

    public function getAtividades()
    {
        $this->setAtividades();
        return $this->qtdeatividadeiniciada;
    }


    public function getFlaativo()
    {
        return $this->flaativo;
    }

    public function setFlaativo($flaativo)
    {
        $valores = array('S', 'N');
        if (!in_array($flaativo, $valores)) {
            throw new Exception('Este model somente aceita os valores S ou N');
        }
        $this->flaativo = $flaativo;
        return $this;
    }

    public function getDescricaoFlaativo()
    {

        $valores = array(
            'S' => 'Sim',
            'N' => 'Não',
        );

        if (array_key_exists($this->flaativo, $valores)) {
            return $valores[$this->flaativo];
        }
        return 'Não informado.';
    }

    public function formPopulate()
    {
        return array(
            'datcadastro' => $this->datcadastro,
            'datfim' => @$this->datfim->toString('d/m/Y'),
            'datfimplano' => @$this->datfimplano->toString('d/m/Y'),
            'datinicio' => (isset($this->datinicio) ? $this->datinicio->toString('d/m/Y') : $this->datinicio),
            'datinicioplano' => @$this->datinicioplano->toString('d/m/Y'),
            'desescopo' => $this->desescopo,
            'desjustificativa' => $this->desjustificativa,
            'desnaoescopo' => $this->desnaoescopo,
            'desobjetivo' => $this->desobjetivo,
            'despremissa' => $this->despremissa,
            'desprojeto' => $this->desprojeto,
            'desrestricao' => $this->desrestricao,
            'domtipoprojeto' => $this->domtipoprojeto,
            'desresultadosobtidos' => $this->desresultadosobtidos,
            'despontosfortes' => $this->despontosfortes,
            'despontosfracos' => $this->despontosfracos,
            'dessugestoes' => $this->dessugestoes,
            'flaaprovado' => $this->flaaprovado,
            'flacopa' => $this->flacopa,
            'flapublicado' => $this->flapublicado,
            'idcadastrador' => $this->idcadastrador,
            'iddemandante' => $this->iddemandante,
            'idgerenteadjunto' => $this->idgerenteadjunto,
            'idgerenteprojeto' => $this->idgerenteprojeto,
            'idnatureza' => $this->idnatureza,
            'idobjetivo' => $this->idobjetivo,
            'idpatrocinador' => $this->idpatrocinador,
            'idprograma' => $this->idprograma,
            'idprojeto' => $this->idprojeto,
            'idsetor' => $this->idsetor,
            'idescritorio' => $this->idescritorio,
            'nomcodigo' => $this->nomcodigo,
            'nomprojeto' => $this->nomprojeto,
            'numcriteriofarol' => $this->numcriteriofarol,
            'numpercentualconcluido' => (isset($this->numpercentualconcluido) ? $this->numpercentualconcluido : 0),
            'numpercentualprevisto' => (isset($this->numpercentualprevisto) ? $this->numpercentualprevisto : 0),
            'numperiodicidadeatualizacao' => $this->numperiodicidadeatualizacao,
            'vlrorcamentodisponivel' => $this->getVlrorcamentodisponivelFormatado(),
            'nomdemandante' => $this->nomdemandante,
            'nompatrocinador' => $this->nompatrocinador,
            'nomgerenteprojeto' => $this->nomgerenteprojeto,
            'nomgerenteadjunto' => $this->nomgerenteadjunto,
            'ano' => $this->ano,
            'idacao' => $this->idacao,
            'desconsideracaofinal' => $this->desconsideracaofinal,
            'idportfolio' => $this->idportfolio,
            'idtipoiniciativa' => $this->idtipoiniciativa,
            'numprocessosei' => $this->numprocessosei,
            'percentualConcluidoMarco' => $this->getPercentualConcluidoMarco(),
            'qtdeatividadeiniciada' => $this->getAtividades(),
            'numpercentualiniciado' => $this->getAtividades(),
            'qtdeatividadenaoiniciada' => $this->getAtividades(),
            'numpercentualnaoiniciado' => $this->getAtividades(),
            'qtdeatividadeconcluida' => $this->getAtividades(),
            'numpercentualatividadeconcluido' => $this->getAtividades(),
            //'tipo'                        => $this->tipo
            //'nomecodigo'                  => $this->nomcodigo,
        );
    }

    public function setDatinicio($data)
    {
        $this->datinicio = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDatfim($data)
    {
        $this->datfim = new Zend_Date($data, 'dd/MM/yyyy');
        $this->datCalculo = $this->datfim;
    }

    public function setDatcadastro($data)
    {
        $this->datcadastro = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDatinicioplano($data)
    {
        $this->datinicioplano = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDatfimplano($data)
    {
        $this->datfimplano = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDatenviouemailatualizacao($data)
    {
        $this->datenviouemailatualizacao = new Zend_Date($data, 'dd/MM/yyyy');
    }

    private function isFinalSemana($dataRealizada, $dataPlanejada)
    {
        $dtPlanejada = new Zend_Date($dataPlanejada, 'd/m/Y');
        $dtPlanejada = $dtPlanejada->addDay(1);
        $dataFimRealizada = new Zend_Date($dataRealizada, 'd/m/Y');

        if ($dtPlanejada->equals($dataFimRealizada) && $dtPlanejada->toString('EEE') == 'sáb'
            || $dtPlanejada->equals($dataFimRealizada) && $dtPlanejada->toString('EEE') == 'dom') {
            return true;
        }
        return false;
    }

    public function retornaDescricaoPrazoCabecalho()
    {
        $sinal = "success";
        $datas = $this->retornaDatasCronograma();

        if (null != $datas && (count($datas) > 0)) {
            $dataPlanejada = new Zend_Date($datas[0]['datfimbaseline'], 'd/m/Y');
            $dataRealizada = new Zend_Date($datas[0]['datfim'], 'd/m/Y');

            if (
                (Zend_Date::isDate($dataPlanejada)) && (Zend_Date::isDate($dataRealizada))
            ) {
                if (($dataPlanejada->equals($dataRealizada)) == false) {
                    $dados['datainicio'] = $dataRealizada->toString('d/m/Y');
                    $dados['datafim'] = $dataPlanejada->toString('d/m/Y');
                    $numEmDias = 0;
                    $criterioFarol = $this->numcriteriofarol;
                    $service = new Projeto_Service_AtividadeCronograma();
                    /* retira um dia do cálculo para atender a regra definida */

                    $numEmDias = $service->retornaQtdeDiasUteisEntreDatas($dados);
                    $numEmDias = $numEmDias * (-1);
                    $numEmDias = ($numEmDias > 0 ? $numEmDias - 1 : $numEmDias + 1);

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
            }
        }
        return $sinal;
    }

    public function retornaDescricaoPrazo()
    {
        $sinal = "success";
        $datas = $this->retornaDatasCronograma();

        if (null != $datas && (count($datas) > 0)) {
            $dataPlanejada = new Zend_Date($datas[0]['datfimbaseline'], 'd/m/Y');
            $dataRealizada = new Zend_Date($datas[0]['datfim'], 'd/m/Y');

            if (
                (Zend_Date::isDate($dataPlanejada)) && (Zend_Date::isDate($dataRealizada))
            ) {
                if (($dataPlanejada->equals($dataRealizada)) == false) {
                    $dados['datainicio'] = $dataRealizada->toString('d/m/Y');
                    $dados['datafim'] = $dataPlanejada->toString('d/m/Y');
                    $numEmDias = 0;
                    $criterioFarol = $this->numcriteriofarol;
                    $service = new Projeto_Service_AtividadeCronograma();
                    /* retira um dia do cálculo para atender a regra definida */
                    $numEmDias = $service->retornaQtdeDiasUteisEntreDatas($dados);
                    $numEmDias = $numEmDias * (-1);
                    $numEmDias = ($numEmDias > 0 ? $numEmDias - 1 : $numEmDias + 1);

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
            }
        }
        return $sinal;
    }

    public function retornaDescricaoRisco()
    {
        if ($this->ultimoStatusReport->domcorrisco == 1) {
            $sinal = "success";
        } elseif ($this->ultimoStatusReport->domcorrisco == 2) {
            $sinal = "warning";
        } else {
            $sinal = "important";
        }
        return $sinal;
    }

    public function retornaPrazoEmDias()
    {
        $numPrazoEmDias = 0;
        $datas = $this->retornaDatasCronograma();

        if (count($datas) > 0) {
            $dataPlanejada = new Zend_Date($datas[0]['datfimbaseline'], 'd/m/Y');
            $dataRealizada = new Zend_Date($datas[0]['datfim'], 'd/m/Y');

            if (
                (Zend_Date::isDate($dataPlanejada)) && (Zend_Date::isDate($dataRealizada))
            ) {
                if (($dataPlanejada->equals($dataRealizada)) == false) {
                    $dados['datainicio'] = $dataRealizada->toString('d/m/Y');
                    $dados['datafim'] = $dataPlanejada->toString('d/m/Y');
                    $service = new Projeto_Service_AtividadeCronograma();
                    $numPrazoEmDias = $service->retornaQtdeDiasUteisEntreDatas($dados);
                    /**********************************************************/
                    /* retira um dia do cálculo para atender a regra definida */
                    $numPrazoEmDias = $numPrazoEmDias * (-1);
                    $numPrazoEmDias = ($numPrazoEmDias > 0 ? $numPrazoEmDias - 1 : $numPrazoEmDias + 1);
                    /**********************************************************/
                }
            }
        }
        return $numPrazoEmDias;
    }

    public function retornaPrazoEmDiasCabecalho()
    {
        $numPrazoEmDias = 0;
        $sinal = "success";
        $datas = $this->retornaDatasCronograma();

        if (null != $datas && (count($datas) > 0)) {
            $sinal = $this->retornaDescricaoPrazo();
            $dataPlanejada = new Zend_Date($datas[0]['datfimbaseline'], 'd/m/Y');
            $dataRealizada = new Zend_Date($datas[0]['datfim'], 'd/m/Y');


            if (
                (Zend_Date::isDate($dataPlanejada)) && (Zend_Date::isDate($dataRealizada))
            ) {
                if (($dataPlanejada->equals($dataRealizada)) == false) {
                    $dados['datainicio'] = $dataRealizada->toString('d/m/Y');
                    $dados['datafim'] = $dataPlanejada->toString('d/m/Y');
                    $service = new Projeto_Service_AtividadeCronograma();
                    $numPrazoEmDias = $service->retornaQtdeDiasUteisEntreDatas($dados);
                    /**********************************************************/
                    /* retira um dia do cálculo para atender a regra definida */
                    $numPrazoEmDias = $numPrazoEmDias * (-1);
                    $numPrazoEmDias = ($numPrazoEmDias > 0 ? $numPrazoEmDias - 1 : $numPrazoEmDias + 1);
                    /**********************************************************/
                }
            }
        }
        $retorno = new stdClass();
        $retorno->descricao = $sinal;
        $retorno->dias = $numPrazoEmDias;
        return $retorno;
    }

    public function retornaMetaEmDias()
    {
        $numMetaEmDias = 0;
        if (
            (Zend_Date::isDate($this->datinicio)) && (Zend_Date::isDate($this->datfim))
        ) {
            $dados['datainicio'] = $this->datinicio->toString('d/m/Y');
            $dados['datafim'] = $this->datfim->toString('d/m/Y');
            $service = new Projeto_Service_AtividadeCronograma();
            $numMetaEmDias = $service->retornaQtdeDiasUteisEntreDatas($dados);
        }
        return $numMetaEmDias;
        // anterior
        //return $this->timeInterval->tempoTotal($this->datinicio, $this->datfim)->dias;
    }

    public function retornaTendenciaEmDias()
    {
        $numTendenciaEmDias = 0;
        if (
            (Zend_Date::isDate($this->datinicio)) &&
            (Zend_Date::isDate($this->ultimoStatusReport->datfimprojetotendencia))
        ) {
            $dados['datainicio'] = $this->datinicio->toString('d/m/Y');
            $dados['datafim'] = $this->ultimoStatusReport->datfimprojetotendencia->toString('d/m/Y');
            $service = new Projeto_Service_AtividadeCronograma();
            $numTendenciaEmDias = $service->retornaQtdeDiasUteisEntreDatas($dados);
        }
        return $numTendenciaEmDias;
        // anterior
        //return $this->timeInterval->tempoTotal($this->datinicio, $this->ultimoStatusReport->datfimprojetotendencia)->dias;
    }

    public function retornaPercentualPrevisto()
    {
        if ((null === $this->numdiascompletos) || (null === $this->totaldiasbaseline)) {
            $this->numpercentualprevisto = 0;
        } else {
            if ($this->totaldiasbaseline > 0) {
                $this->numpercentualprevisto = round(($this->numdiascompletos / $this->totaldiasbaseline) * 100, 1);
            } else {
                $this->numpercentualprevisto = 0;
            }
        }
        $this->numpercentualprevisto = number_format($this->numpercentualprevisto, 0);
        return $this->numpercentualprevisto;
    }

    public function toArray()
    {
        $retorno = $this->formPopulate();
        $retorno['metaEmDias'] = $this->retornaMetaEmDias();
        $retorno['patrocinador']['nompessoa'] = @$this->patrocinador->nompessoa;
        $retorno['ultimoStatusReport']['numpercentualprevisto'] = @$this->ultimoStatusReport->numpercentualprevisto;
        $retorno['ultimoStatusReport']['datacompanhamento'] = @$this->ultimoStatusReport->datacompanhamento->toString('d/m/Y');
        $retorno['descricaoPrazo'] = $this->retornaDescricaoPrazo();
        $retorno['prazoEmDias'] = $this->retornaPrazoEmDias();
        //$retorno['prazoEmDias']                                   = $this->timeInterval->tempoTotal($this->datfim, $this->ultimoStatusReport->datfimprojetotendencia)->dias;
        $retorno['tendenciaEmDias'] = $this->retornaTendenciaEmDias();

        $retorno['gerenteprojeto']['nompessoa'] = @$this->gerenteprojeto->nompessoa;
        $retorno['ultimoStatusReport']['datfimprojetotendencia'] = @$this->ultimoStatusReport->datfimprojetotendencia;
        $retorno['ultimoStatusReport']['idstatusreport'] = @$this->ultimoStatusReport->idstatusreport;
        $retorno['ultimoStatusReport']['numpercentualconcluido'] = @$this->ultimoStatusReport->numpercentualconcluido;
        $retorno['ultimoStatusReport']['domstatusprojeto'] = @$this->ultimoStatusReport->retornaDescricaoStatusProjeto();
        $retorno['ultimoStatusReport']['nomdomcorrisco'] = @$this->ultimoStatusReport->nomdomcorrisco;
        $retorno['ultimoStatusReport']['domcorrisco'] = @$this->ultimoStatusReport->domcorrisco;
        $retorno['ultimoStatusReport']['nomdomstatusprojeto'] = @$this->ultimoStatusReport->nomdomstatusprojeto;
        $retorno['ultimoStatusReport']['desmotivoatraso'] = @$this->ultimoStatusReport->desmotivoatraso;
        $retorno['descricaoRisco'] = $this->retornaDescricaoRisco();

        $retorno['gerenteadjunto']['nompessoa'] = $this->gerenteadjunto->nompessoa;
        $retorno['objetivo']['nomobjetivo'] = $this->objetivo->nomobjetivo;
        $retorno['acao']['nomacao'] = $this->acao->nomacao;
        $retorno['natureza']['nomnatureza'] = (@trim($this->natureza->nomnatureza) != "" ? $this->natureza->nomnatureza : '');
        if (isset($this->numdiasrealizados)) {
            $retorno['numdiasrealizados'] = $this->numdiasrealizados;
        }
        if (isset($this->numdiasrealizadosreal)) {
            $retorno['numdiasrealizadosreal'] = $this->numdiasrealizadosreal;
        }
        if (isset($this->numpercentualconcluido)) {
            $retorno['numpercentualconcluido'] = $this->numpercentualconcluido;
        }
        if (isset($this->numpercentualprevisto)) {
            $retorno['numpercentualprevisto'] = $this->numpercentualprevisto;
        }
        $retorno['numdiasbaseline'] = $this->numdiasbaseline;
        $retorno['numdiascompletos'] = $this->numdiascompletos;
        $retorno['totaldiasbaseline'] = $this->totaldiasbaseline;
        $retorno['numpercentualprevisto'] = $this->retornaPercentualPrevisto();

        return $retorno;
    }

    public function retornaPercentuais()
    {
        $retorno = new stdClass();
        if (false === $this->percentuaisCalculados) {
            $this->percentuaisCalculados = true;
            $grupos = $this->grupos->getIterator();
            if (count($grupos) <= 0) {
                $retorno->numpercentualprevisto = 0;
                $retorno->numpercentualconcluido = 0;
                return $retorno;
            }
            $resultado = $this->retornarDiasEstimadosEReais();
            $this->numpercentualprevisto = $this->retornaPercentualPrevisto();
            $this->numpercentualprevisto = number_format($this->numpercentualprevisto, 0);
            $this->numpercentualconcluido = round((100 * ($resultado->realTotalDiasExecutados / ($resultado->realTotalDias > 0 ? $resultado->realTotalDias : 1))),
                2);
            $this->numpercentualconcluido = number_format($this->numpercentualconcluido, 0);
        }

        $retorno->numpercentualprevisto = $this->numpercentualprevisto;
        $retorno->numpercentualconcluido = $this->numpercentualconcluido;
        return $retorno;
    }


    public function retornarDiasEstimadosEReais()
    {
        $retorno = new stdClass();
        if (false === $this->estimativasCalculados) {
            $this->estimativasCalculados = true;
            $grupos = $this->grupos->getIterator();
            if (count($grupos) <= 0) {
                $retorno->realTotalDias = 0;
                $retorno->realTotalDiasExecutados = 0;
                $retorno->estimativaTotalDias = 0;
                $retorno->estimativaTotalDiasExecutados = 0;
                return $retorno;
            }

            $i = 0;
            foreach ($this->grupos as $grupo) {
                /* @var $entrega Projeto_Model_Entregacronograma */
                $dias = $grupo->retornarDiasEstimadosEReais();
                $this->realTotalDias += $dias->realTotalDias;
                $this->realTotalDiasExecutados += $dias->realTotalDiasExecutados;

                $this->estimativaTotalDias += $dias->estimativaTotalDias;
                $this->estimativaTotalDiasExecutados += $dias->estimativaTotalDiasExecutados;
            }
        }

        $retorno->realTotalDias = $this->realTotalDias;
        $retorno->realTotalDiasExecutados = $this->realTotalDiasExecutados;
        $retorno->estimativaTotalDias = $this->estimativaTotalDias;
        $retorno->estimativaTotalDiasExecutados = $this->estimativaTotalDiasExecutados;
        return $retorno;
    }

    public function setVlrorcamentodisponivel($valor)
    {
        $valorfinal = str_replace(",", "", str_replace(".", "", $valor));
        $this->vlrorcamentodisponivel = $valorfinal;
        return $this;
    }

    public function getVlrorcamentodisponivel()
    {
        return $this->vlrorcamentodisponivel;
    }

    public function getVlrorcamentodisponivelFormatado()
    {
        $valor = 0;
        if (!empty($this->vlrorcamentodisponivel) && $this->vlrorcamentodisponivel > 0) {
            $valor = mb_substr($this->vlrorcamentodisponivel, 0, -2) . '.' . mb_substr($this->vlrorcamentodisponivel,
                    -2);
            return number_format($valor, 2, ',', '.');
        } else {
            return 0;
        }
    }

    public function maskSei($val, $mask)
    {
        $maskared = '';
        $k = 0;

        if (!empty($val)) {
            for ($i = 0; $i <= strlen($mask) - 1; $i++) {
                if ($mask[$i] == '#') {
                    if (isset($val[$k])) {
                        $maskared .= $val[$k++];
                    }
                } else {
                    if (isset($mask[$i])) {
                        $maskared .= $mask[$i];
                    }
                }
            }
            return $maskared;
        } else {
            return $val;
        }
    }

    public function retornaDescricaoStatusProjeto()
    {

        $descricao = array(
            1 => 'Proposta',
            2 => 'Em Andamento',
            3 => 'Concluído',
            4 => 'Paralisado',
            5 => 'Cancelado',
            6 => 'Bloqueado',
            8 => 'Excluído',
        );

        return $descricao[$this->domstatusprojeto];
    }

    public function retornaDescricaoTipoIniciativa()
    {

        $descricao = array(
            1 => 'Projeto',
            2 => 'Plano de Ação',
        );

        return $descricao[$this->idtipoiniciativa];
    }
}
