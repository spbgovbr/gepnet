<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Grupocronograma
    extends Projeto_Model_AtividadecronogramaAbstract
{
    /**
     *
     * atributtos auxiliares;
     */
    public $datiniciobaseline = null;
    public $datfimbaseline = null;
    public $datinicio = null;
    public $datfim = null;
    public $nomparteinteressada = null;
    public $numdiasrealizados = null;
    public $numdiasrealizadosreal = null;
    public $numdiasrealatividades = null;
    public $idparteinteressada = null;
    public $numdiasbaseline = '0';
    public $totaldiasbaseline = '0';
    public $numdiascompletos = '0';
    public $numdiasreal = null;
    public $numdiasbase = '0';
    public $flashowhide = 'S';

    protected $vlrgrupo = 0;
    protected $vlrgrupobaseline = 0;
    protected $numcriteriofarol = null;

    protected $prazoEmDias = 0;
    protected $descricaoPrazo = '';
    protected $numpercentualconcluido = 0;
    protected $numpercentualprevisto = 0;

    protected $realTotalDias = 0;
    protected $realTotalDiasExecutados = 0;

    protected $estimativaTotalDias = 0;
    protected $estimativaTotalDiasExecutados = 0;
    protected $percentuaisCalculados = false;
    protected $estimativasCalculados = false;

    protected $custoCalculado = false;
    protected $custoBaseLineCalculado = false;

    protected $hoje = null;

    protected $diasBaseLine = null;
    protected $diasReal = null;

    /**
     * @var array of Projeto_Model_Entregacronograma
     */
    public $entregas = null;

    public function init()
    {
        //$this->timeInterval = new App_TimeInterval();
        $this->hoje = new DateTime('now');
        $this->diasBaseLine = $this->numdiasbaseline;
        $this->diasReal = $this->numdiasrealizados;
    }

    public function setdatiniciobaseline($data)
    {
        if (!empty($data)) {
            $this->datiniciobaseline = DateTime::createFromFormat('d/m/Y', $data);
        }
        return $this;
    }

    public function setDatfimbaseline($data)
    {
        if (!empty($data)) {
            $this->datfimbaseline = DateTime::createFromFormat('d/m/Y', $data);
        }
        return $this;
    }

    public function setDatinicio($data)
    {
        if (!empty($data)) {
            $this->datinicio = DateTime::createFromFormat('d/m/Y', $data);
        }
        return $this;
    }

    public function setDatfim($data)
    {
        if (!empty($data)) {
            $this->datfim = DateTime::createFromFormat('d/m/Y', $data);
        }
        return $this;
    }

    public function getCusto()
    {
        if (false === $this->custoCalculado) {
            $this->custoCalculado = true;
            $a = array();

            if (isset($this->entregas) && count($this->entregas) == 0) {
                foreach ($this->entregas as $entrega) {
                    /* @var $entrega Projeto_Model_Entregacronograma */
                    $a[] = $entrega->getCusto();
                }

                $valor = array_sum($a);
                if ($valor) {
                    $this->vlrgrupo = $valor;
                } else {
                    $this->vlrgrupo = 0;
                }
            } else {
                $this->vlrgrupo = 0;
            }
        }
        return $this->vlrgrupo;
    }

    public function getCustoFormatado()
    {
        $this->getCusto();
        $valor = mb_substr($this->vlrgrupo, 0, -2) . '.' . mb_substr($this->vlrgrupo, -2);
        return number_format($valor, 2, ',', '.');
    }

    public function retornaPercentuais()
    {
        $retorno = new stdClass();
        if (false === $this->percentuaisCalculados) {
            $this->percentuaisCalculados = true;
            //$entregas = $this->entregas->getIterator();
            if (count($this->entregas) <= 0) {
                $retorno->numpercentualprevisto = 0;
                $retorno->numpercentualconcluido = 0;
                return $retorno;
            } else {
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
            //$entregas = $this->entregas->getIterator();
            if (count($this->entregas) <= 0) {
                $retorno->realTotalDias = 0;
                $retorno->realTotalDiasExecutados = 0;
                $retorno->estimativaTotalDias = 0;
                $retorno->estimativaTotalDiasExecutados = 0;
                return $retorno;
            } else {

                foreach ($this->entregas as $entrega) {
                    /* @var $entrega Projeto_Model_Entregacronograma */
                    $resultado = $entrega->retornarDiasEstimadosEReais();
                    $this->realTotalDias += $resultado->realTotalDias;
                    $this->realTotalDiasExecutados += $resultado->realTotalDiasExecutados;
                    $this->estimativaTotalDias += $resultado->estimativaTotalDias;
                    $this->estimativaTotalDiasExecutados += $resultado->estimativaTotalDiasExecutados;
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
        //$entregas = $this->entregas->getIterator();
        $diasCompletos = 0;
        if (count($this->entregas) > 0) {
            foreach ($this->entregas as $entrega) {
                /* @var $entrega Projeto_Model_Entregacronograma */
                $diasCompletosAtividade = $entrega->retornaDiasCompletos();
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
        //$entregas = $this->entregas->getIterator();
        $totalDiasBaselineEntrega = 0;
        if (count($this->entregas) > 0) {
            foreach ($this->entregas as $entrega) {
                /* @var $atividade Projeto_Model_Atividadecronograma */
                //if($atividade->flacancelada == 'S'){
                //    continue;
                //}
                $DiasTBaselineEntrega = $entrega->retornaTotalDiasBaseLine();
                $totalDiasBaselineEntrega += $DiasTBaselineEntrega;
            }
        }
        $this->totaldiasbaseline = $totalDiasBaselineEntrega;
        return $this->totaldiasbaseline;
    }


    public function retornaDiasBaseLine()
    {
        //if(null === $this->diasBaseLine){
        if (null === $this->numdiasbaseline) {
            //$entregas = $this->entregas->getIterator();
            if (count($this->entregas) <= 0) {
                $this->diasBaseLine = 0;
            } else {
                $a = array();

                foreach ($this->entregas as $entrega) {
                    $a[] = $entrega->retornaDiasBaseLine();
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
        if (!empty($this->numdiasrealizados)) {
            if (empty($this->datinicio)) {
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
        $retorno['vlratividade'] = $this->getCustoFormatado();
        $retorno['diasbaseline'] = $this->retornaDiasBaseLine();
        $retorno['numdiasbaseline'] = $this->numdiasbaseline;
        $retorno['numdiasbaseline'] = $this->numdiasbaseline;
        $retorno['totaldiasbaseline'] = $this->retornaTotalDiasBaseLine();
        $retorno['numpercentualprevisto'] = $this->retornaPercentualPrevisto();
        $retorno['numpercentualconcluido'] = number_format($this->numpercentualconcluido, 0);
        $retorno['numdiascompletos'] = $this->retornaDiasCompletos();
        $retorno['numdiascompletos'] = $this->numdiascompletos;
        $retorno['diasreal'] = $this->retornaDiasReal();
        $retorno['idparteinteressada'] = $this->idparteinteressada;
        $retorno['numdiasreal'] = $this->numdiasreal;
        $retorno['numdiasrealizados'] = $this->numdiasrealizados;
        $retorno['numdiasrealizadosreal'] = $this->numdiasrealizadosreal;
        $retorno['numdiasrealatividades'] = $this->numdiasrealatividades;
        $retorno['numdiasbase'] = $this->numdiasbase;

        /*
        $retorno['datiniciobaseline'] = $this->datiniciobaseline->format('d/m/Y');
        $retorno['datfimbaseline'] = $this->datiniciobaseline->format('d/m/Y');
        $retorno['datinicio'] = $this->datiniciobaseline->format('d/m/Y');
        $retorno['datfim'] = $this->datiniciobaseline->format('d/m/Y');
        $retorno['datcadastro'] = $this->datiniciobaseline->format('d/m/Y');
        */
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
       $entregas = $this->entregas->getIterator();
       if ( isset($entregas[0]) ) {
           $entrega    = $entregas[0];
           $this->datiniciobaseline = $entrega->getDatiniciobaseline();
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
       $a = array();
       foreach ( $this->entregas as $entrega )
       {
           $datfimbaseline = $entrega->getDatfimbaseline();
           if($datfimbaseline == null){
               continue;
           }
           $a[] = $datfimbaseline;
       }
       //Zend_Debug::dump($a);exit;
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
       //Zend_Debug::dump($this->datfimbaseline);exit;

       return $this->datfimbaseline;
   }

   public function setDatinicio()
   {
       $entregas = $this->entregas->getIterator();
       if ( isset($entregas[0]) ) {
           $entrega    = $entregas[0];
           $this->datinicio = $entrega->getDatinicio();
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
       $a = array();
       foreach ( $this->entregas as $entrega )
       {
           $datfim = $entrega->getDatfim();
           if($datfim == null){
               continue;
           }
           $a[] = $datfim;
       }
       //Zend_Debug::dump($a);exit;
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
       //Zend_Debug::dump($this->datfimbaseline);exit;

       return $this->datfim;
   }
   */