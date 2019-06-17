<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Entregacronograma extends Projeto_Model_AtividadecronogramaAbstract
{
    public $idgrupo = null;
    public $idparteinteressada = '0'; # Responsável pela aceitação
    public $idresponsavel = '0'; # Responsável pela entrega
    public $flacancelada = 'N';
    public $flashowhide = 'S';
    public $desobs = null;
    public $descriterioaceitacao = null;
    public $numdiasrealizados = null;
    public $numdiasrealizadosreal = null;
    public $numdiasrealatividades = null;
    public $numdiasbaseline = '0';
    public $totaldiasbaseline = '0';
    public $numdiascompletos = '0';
    public $numpercentualprevisto = 0.00;
    public $numdiasreal = null;
    public $numdiasbase = '0';
    public $grupo = null;
    public $nomparteinteressadaentrega = null;
    public $responsavelAceitacao = array();

    /* @var DateTime */
    public $datiniciobaseline = null;
    /* @var DateTime */
    public $datfimbaseline = null;
    /* @var DateTime */
    public $datinicio = null;
    /* @var DateTime */
    public $datfim = null;

    /**
     * atributtos auxiliares;
     */
    public $nomparteinteressada = null;
    public $nomeresponsavelaceitacao = null;
    /**
     * @var Projeto_Model_Parteinteressada
     */
    public $parteinteressada = null;
    protected $vlrentrega = 0;
    protected $vlrentregabaseline = 0;
    protected $numcriteriofarol = null;


    protected $prazoEmDias = 0;
    protected $descricaoPrazo = '';

    protected $numpercentualconcluido = 0;

    protected $realTotalDias = 0;
    protected $realTotalDiasExecutados = 0;

    protected $estimativaTotalDias = 0;
    protected $estimativaTotalDiasExecutados = 0;

    protected $percentuaisCalculados = false;
    protected $estimativasCalculadas = false;
    protected $custoCalculado = false;
    protected $custoBaseLineCalculado = false;

    protected $diasBaseLine = null;
    protected $diasReal = null;

    /**
     * @var array of Projeto_Model_Atividadecronograma
     */
    public $atividades = null;

    public function init()
    {
        //$this->timeInterval = new App_TimeInterval();
        $this->diasBaseLine = $this->numdiasbaseline;
        $this->diasReal = $this->numdiasrealizados;
        $this->hoje = new DateTime('now');
    }

    public function setdatiniciobaseline($data)
    {
        //$this->datiniciobaseline = new App_DateTime($data, 'd/m/Y');
        $this->datiniciobaseline = DateTime::createFromFormat('d/m/Y', $data);
        return $this;
    }

    public function setDatfimbaseline($data)
    {
        $this->datfimbaseline = DateTime::createFromFormat('d/m/Y', $data);
        return $this;
    }

    public function setDatinicio($data)
    {
        $this->datinicio = DateTime::createFromFormat('d/m/Y', $data);
        return $this;
    }

    public function setDatfim($data)
    {
        $this->datfim = DateTime::createFromFormat('d/m/Y', $data);
        return $this;
    }

    public function getCusto()
    {
        if (false === $this->custoCalculado) {
            $this->custoCalculado = true;
            $a = array();

            if (isset($this->atividades) && count($this->atividades) == 0) {
                foreach ($this->atividades as $atividade) {
                    if ($atividade->flacancelada == 'S') {
                        continue;
                    }
                    $a[] = $atividade->vlratividade;
                }

                $valor = array_sum($a);
                if ($valor) {
                    $this->vlrentrega = $valor;
                } else {
                    $this->vlrentrega = 0;
                }
            } else {
                $this->vlrentrega = 0;
            }
        }
        return $this->vlrentrega;
    }

    public function getCustoFormatado()
    {
        $this->getCusto();
        $valor = mb_substr($this->vlrentrega, 0, -2) . '.' . mb_substr($this->vlrentrega, -2);
        return number_format($valor, 2, ',', '.');
    }

    public function getCustoBaseLine()
    {
        if (false === $this->custoBaseLineCalculado) {
            $this->custoBaseLineCalculado = true;
            $a = array();
            if (isset($this->atividades) && count($this->atividades) == 0) {
                foreach ($this->atividades as $atividade) {
                    if ($atividade->flacancelada == 'S') {
                        continue;
                    }
                    $a[] = $atividade->vlratividadebaseline;
                }

                $valor = array_sum($a);
                if ($valor) {
                    $this->vlrentregabaseline = $valor;
                } else {
                    $this->vlrentregabaseline = 0;
                }
            } else {
                $this->vlrentregabaseline = 0;
            }
        }
        return $this->vlrentregabaseline;
    }

    public function getCustoBaseLineFormatado()
    {
        $this->getCustoBaseLine();
        $valor = mb_substr($this->vlrentregabaseline, 0, -2) . '.' . mb_substr($this->vlrentregabaseline, -2);
        return number_format($valor, 2, ',', '.');
    }

    public function retornaPercentuais()
    {
        $retorno = new stdClass();
        if (false === $this->percentuaisCalculados) {
            $this->percentuaisCalculados = true;
            //$atividades = $this->atividades->getIterator();
            if (count($this->atividades) <= 0) {
                $retorno->numpercentualprevisto = 0;
                $retorno->numpercentualconcluido = 0;
                return $retorno;
            }
            $resultado = $this->retornarDiasEstimadosEReais();
            $this->numpercentualprevisto = $this->retornaPercentualPrevisto();
            $this->numpercentualprevisto = number_format($this->numpercentualprevisto, 0);
            if ($resultado->realTotalDiasExecutados > 0 && $resultado->realTotalDias > 0) {
                $this->numpercentualconcluido = round((100 * ($resultado->realTotalDiasExecutados / $resultado->realTotalDias)),
                    2);
                $this->numpercentualconcluido = number_format($this->numpercentualconcluido, 0);
            } else {
                $this->numpercentualconcluido = "0";
            }
        }

        $retorno->numpercentualprevisto = $this->numpercentualprevisto;
        $retorno->numpercentualconcluido = $this->numpercentualconcluido;
        return $retorno;
    }

    public function retornarDiasEstimadosEReais()
    {
        $retorno = new stdClass();

        if (false === $this->estimativasCalculadas) {
            $this->estimativasCalculadas = true;
            // $atividades = $this->atividades->getIterator();

            if (count($this->atividades) <= 0) {
                $retorno->realTotalDias = 0;
                $retorno->realTotalDiasExecutados = 0;
                $retorno->estimativaTotalDias = 0;
                $retorno->estimativaTotalDiasExecutados = 0;
                return $retorno;
            } else {

                foreach ($this->atividades as $atividade) {
                    /* @var $atividade Projeto_Model_Atividadecronograma */
                    if ($atividade->flacancelada == 'S') {
                        continue;
                    }

                    $dias = $atividade->retornarDiasEstimadosEReais();
                    $this->realTotalDias += $dias->realTotalDias;
                    $this->realTotalDiasExecutados += $dias->realTotalDiasExecutados;

                    $this->estimativaTotalDias += $dias->estimativaTotalDias;
                    $this->estimativaTotalDiasExecutados += $dias->estimativaTotalDiasExecutados;
                }
            }

        }
        $retorno->realTotalDias = $this->realTotalDias;
        $retorno->realTotalDiasExecutados = $this->realTotalDiasExecutados;
        $retorno->estimativaTotalDias = $this->estimativaTotalDias;
        $retorno->estimativaTotalDiasExecutados = $this->estimativaTotalDiasExecutados;
        return $retorno;

    }

    public function retornaDiasCompletos()
    {
        // $atividades = $this->atividades->getIterator();
        $diasCompletos = 0;
        if (count($this->atividades) > 0) {
            foreach ($this->atividades as $atividade) {
                /* @var $atividade Projeto_Model_Atividadecronograma */
                if ($atividade->flacancelada == 'S') {
                    continue;
                }
                $diasCompletosAtividade = $atividade->retornaDiasCompletos();
                $diasCompletos += $diasCompletosAtividade;
            }
        }
        $this->numdiascompletos = $diasCompletos;
        return $this->numdiascompletos;
    }

    public function retornaPercentualPrevisto()
    {
        if ((null === $this->numdiascompletos) || (null === $this->totaldiasbaseline)) {
            $this->numpercentualprevisto = 0;
        } else {
            if ($this->totaldiasbaseline > 0) {
                $this->numpercentualprevisto = round(($this->numdiascompletos / $this->totaldiasbaseline) * 100, 1);
            }
        }
        return $this->numpercentualprevisto;
    }

    public function retornaTotalDiasBaseLine()
    {
        // $atividades = $this->atividades->getIterator();
        $totalDiasBaseline = 0;
        if (count($this->atividades) > 0) {
            foreach ($this->atividades as $atividade) {
                /* @var $atividade Projeto_Model_Atividadecronograma */
                if ($atividade->flacancelada == 'S') {
                    continue;
                }
                $diasBaselineAtividade = $atividade->numdiasbaseline;
                $totalDiasBaseline += $diasBaselineAtividade;
            }
        }
        $this->totaldiasbaseline = $totalDiasBaseline;
        return $this->totaldiasbaseline;
    }

    public function retornaDiasBaseLine()
    {
        if (null === $this->numdiasbaseline) {
            //$atividades = $this->atividades->getIterator();
            if (count($this->atividades) <= 0) {
                $this->diasBaseLine = 0;
            } else {
                $a = array();

                foreach ($this->atividades as $atividade) {
                    if ($atividade->flacancelada == 'S') {
                        continue;
                    }
                    $a[] = $atividade->retornaDiasBaseLine();
                }
                $this->diasBaseLine = array_sum($a);
            }
        } else {
            $this->diasBaseLine = $this->numdiasbaseline;
        }
        return $this->diasBaseLine;
    }

    public function retornaDiasReal()
    {
        if (null === $this->numdiasrealizados) {
            if (!empty($this->diasReal)) {
                if (null == $this->datinicio) {
                    $this->diasReal = 0;
                } else {
                    $dtIni = new Zend_Date($this->datinicio->format('d/m/Y'), 'd/m/Y');
                    $dtFim = new Zend_Date($this->datfim->format('d/m/Y'), 'd/m/Y');

                    if (Zend_Date::isDate($dtIni) && Zend_Date::isDate($dtFim)) {
                        if (($dtIni->equals($dtFim)) == false) {
                            $dados['datainicio'] = $dtIni->toString('d/m/Y');
                            $dados['datafim'] = $dtFim->toString('d/m/Y');
                            $servico = new Projeto_Service_AtividadeCronograma();
                            $this->diasReal = $servico->retornaQtdeDiasUteisEntreDatas($dados);
                        }
                    }
                }
            }
        } else {
            $this->diasReal = $this->numdiasrealizados;
        }
        return $this->diasReal;
    }

    public function toArray()
    {
        $retorno = array();
        $retorno['idatividadecronograma'] = $this->idatividadecronograma;
        $retorno['numseq'] = $this->numseq;
        $retorno['idprojeto'] = $this->idprojeto;
        $retorno['nomatividadecronograma'] = $this->nomatividadecronograma;
        $retorno['domtipoatividade'] = $this->domtipoatividade;
        $retorno['idcadastrador'] = $this->idcadastrador;
        $retorno['datcadastro'] = $this->datcadastro;
        $retorno['idgrupo'] = $this->idgrupo;
        $retorno['idparteinteressada'] = $this->idparteinteressada;
        $retorno['idresponsavel'] = $this->idresponsavel;
        $retorno['flacancelada'] = $this->flacancelada;
        $retorno['flashowhide'] = $this->flashowhide;
        $retorno['desobs'] = $this->desobs;
        $retorno['grupo'] = $this->grupo;
        $retorno['nomparteinteressadaentrega'] = $this->nomparteinteressadaentrega;
        $retorno['descriterioaceitacao'] = $this->descriterioaceitacao;
        $retorno['datiniciobaseline'] = '';
        $retorno['datfimbaseline'] = '';
        $retorno['datinicio'] = '';
        $retorno['datfim'] = '';
        $retorno['datcadastro'] = '';
        $retorno['vlratividade'] = $this->getCustoFormatado();
        $retorno['vlratividadebaseline'] = $this->getCustoBaseLineFormatado();

        $retorno['numdiasbaseline'] = $this->numdiasbaseline;
        $retorno['numdiascompletos'] = $this->retornaDiasCompletos();
        $retorno['diasbaseline'] = $this->retornaDiasBaseLine();
        $retorno['totaldiasbaseline'] = $this->retornaTotalDiasBaseLine();
        $retorno['numpercentualprevisto'] = $this->retornaPercentualPrevisto();
        $retorno['numpercentualconcluido'] = number_format($this->numpercentualconcluido, 0);
        $retorno['diasreal'] = $this->retornaDiasReal();
        $retorno['numdiasrealizados'] = $this->numdiasrealizados;
        $retorno['numdiasrealizadosreal'] = $this->numdiasrealizadosreal;
        $retorno['numdiasrealatividades'] = $this->numdiasrealatividades;

        $retorno['numdiasreal'] = $this->numdiasreal;
        $retorno['numdiasbase'] = $this->numdiasbase;

        if ($this->datiniciobaseline instanceof DateTime) {
            $retorno['datiniciobaseline'] = $this->datiniciobaseline->format('d/m/Y');
        } else {
            $retorno['datiniciobaseline'] = "";
        }

        if ($this->datfimbaseline instanceof DateTime) {
            $retorno['datfimbaseline'] = $this->datfimbaseline->format('d/m/Y');
        } else {
            $retorno['datfimbaseline'] = "";
        }

        if ($this->datinicio instanceof DateTime) {
            $retorno['datinicio'] = $this->datinicio->format('d/m/Y');
        } else {
            $retorno['datinicio'] = "";
        }

        if ($this->datfim instanceof DateTime) {
            $retorno['datfim'] = $this->datfim->format('d/m/Y');
        } else {
            $retorno['datfim'] = "";
        }

        if ($this->datcadastro instanceof DateTime) {
            $retorno['datcadastro'] = $this->datcadastro->format('d/m/Y');
        }

        $atividadeParte = new Projeto_Model_Mapper_Parteinteressada();
        $parteInteressada = $atividadeParte->parteInteressadaPorAtividade($this->idprojeto,
            $this->idatividadecronograma);
        $retorno['nomeresponsavelaceitacao'] = !empty($parteInteressada) ? $parteInteressada[0]['nomparteinteressada'] : null;
        $retorno['nomparteinteressada'] = is_object($this->parteinteressada) ? $this->parteinteressada->nomparteinteressada : null;

        $retorno['desemail'] = is_object($this->parteinteressada) ? mb_substr($this->parteinteressada->desemail, 0,
            strpos($this->parteinteressada->desemail, "@")) : null;
        $retorno['email'] = is_object($this->parteinteressada) ? $this->parteinteressada->desemail : null;

        //$retorno = get_object_vars($this);
        /*
        $datiniciobaseline = $this->getDatiniciobaseline();
        if($datiniciobaseline instanceof DateTime){
            $retorno['datiniciobaseline'] = $datiniciobaseline->format('d/m/Y');
        }
        
        $datfimbaseline = $this->getDatfimbaseline();
        if($datfimbaseline instanceof DateTime){
            $retorno['datfimbaseline'] = $datfimbaseline->format('d/m/Y');
        }
        
        $datinicio = $this->getDatinicio();
        if($datinicio instanceof DateTime){
            $retorno['datinicio'] = $datinicio->format('d/m/Y');
        }
        
        $datfim = $this->getDatfim();
        if($datfim instanceof DateTime){
            $retorno['datfim'] = $datfim->format('d/m/Y');
        }
        */

        return $retorno;
    }
}

/*
    public function setDatiniciobaseline()
    {
        $atividades = $this->atividades->getIterator();
            //Zend_Debug::dump(count($atividades));
            //Zend_Debug::dump($this->atividades);
        if(count($atividades) <= 0){

            //Zend_Debug::dump('wilton');exit;
            return;
        }
        
        if ( isset($atividades[0]) ) {
            $atividade = $atividades[0];
            $this->datiniciobaseline = $atividade->datiniciobaseline;
        }

        return $this;
    }
    
    
    public function getDatiniciobaseline()
    {
        if(null === $this->datiniciobaseline){
            $this->setDatiniciobaseline();
        }
        return $this->datiniciobaseline;
    }

    
    public function setDatfimbaseline()
    {
        $atividades = $this->atividades->getIterator();
        if(count($atividades) <= 0){
            return;
        }
        $a = array();

        foreach ( $this->atividades as $atividade )
        {
            $a[] = $atividade->datfimbaseline;
        }

        $datafim = array_pop($a);
        if ( $datafim ) {
            $this->datfimbaseline = $datafim;
        }
        return $this;
    }
    
    public function getDatfimbaseline()
    {
        if(null === $this->datfimbaseline){
            $this->setDatfimbaseline();
        }
        return $this->datfimbaseline;
    }
    
    public function setDatinicio()
    {
        $atividades = $this->atividades->getIterator();
            //Zend_Debug::dump(count($atividades));
            //Zend_Debug::dump($this->atividades);
        if(count($atividades) <= 0){

            //Zend_Debug::dump('wilton');exit;
            return;
        }
        
        if ( isset($atividades[0]) ) {
            $atividade = $atividades[0];
            $this->datinicio = $atividade->datinicio;
        }

        return $this;
    }
    
    public function getDatinicio()
    {
        if(null === $this->datinicio){
            $this->setDatinicio();
        }
        return $this->datinicio;
    }
    
    public function setDatfim()
    {
        $atividades = $this->atividades->getIterator();
        if(count($atividades) <= 0){
            return;
        }
        $a = array();

        foreach ( $this->atividades as $atividade )
        {
            $a[] = $atividade->datfim;
        }

        $datafim = array_pop($a);
        if ( $datafim ) {
            $this->datfim = $datafim;
        }
        return $this;
    }
    
    public function getDatfim()
    {
        if(null === $this->datfim){
            $this->setDatfim();
        }
        return $this->datfim;
    }
    */