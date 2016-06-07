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
    public $datiniciobaseline      = null;
    public $datfimbaseline         = null;
    public $datinicio              = null;
    public $datfim                 = null;
    public $nomparteinteressada    = null;
    
    
    protected $vlrgrupo = 0;
    protected $vlrgrupobaseline = 0;
    
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
    
    protected $custoCalculado         = false;
    protected $custoBaseLineCalculado = false;
    
    protected $hoje = null;
    
    protected $diasBaseLine = null;
    protected $diasReal     = null;
    
    /**
     * @var array of Projeto_Model_Entregacronograma
     */
    public $entregas = null;

    public function init()
    {
        //$this->timeInterval = new App_TimeInterval();
        $this->hoje = new DateTime('now');
    }
    
    public function setdatiniciobaseline($data)
    {
        if( !empty($data)){
            $this->datiniciobaseline = DateTime::createFromFormat('d/m/Y', $data);
        }
        return $this;
    }

    public function setDatfimbaseline($data)
    {
        if( !empty($data)){
            $this->datfimbaseline = DateTime::createFromFormat('d/m/Y', $data);
        }
        return $this;
    }

    public function setDatinicio($data)
    {
        if( !empty($data)){
            $this->datinicio = DateTime::createFromFormat('d/m/Y', $data);
        }
        return $this;
    }

    public function setDatfim($data)
    {
        if( !empty($data)){
            $this->datfim = DateTime::createFromFormat('d/m/Y', $data);
        }
        return $this;
    }
   
    public function getCusto()
    {
        if(false === $this->custoCalculado )
        {
            $this->custoCalculado = true;
            if(null === $this->entregas || count($this->entregas) <= 0){
                $this->vlrgrupo = 0;
            }
            $a = array();

            foreach ( $this->entregas as $entrega )
            {
                /* @var $entrega Projeto_Model_Entregacronograma */
                $a[] = $entrega->getCusto();
            }

            $valor = array_sum($a);
            if ( $valor ) {
                $this->vlrgrupo =  $valor;
            } else {
                $this->vlrgrupo =  0;
            }
        }
        return $this->vlrgrupo;
    }
    
    public function getCustoFormatado()
    {
        $this->getCusto();
        $valor = substr($this->vlrgrupo, 0, -2) . '.' . substr($this->vlrgrupo, -2);
        return number_format($valor, 2, ',', '.');
    }
    
    public function retornaPercentuais()
    {
        $retorno = new stdClass();
        if(false === $this->percentuaisCalculados)
        {
            $this->percentuaisCalculados = true;
            $entregas = $this->entregas->getIterator();
            if(count($entregas) <= 0){
                $retorno->numpercentualprevisto = 0;
                $retorno->numpercentualconcluido = 0;
                return $retorno;
            }
            
            $i = 0;
            $previsto = array();
            $concluido = array();
            foreach($this->entregas as $entrega)
            {
                /* @var $entrega Projeto_Model_Entregacronograma */
                $percentual  = $entrega->retornaPercentuais();
                $previsto[]  = $percentual->numpercentualprevisto;
                $concluido[] = $percentual->numpercentualconcluido;
                $i++;
            }

            $this->numpercentualprevisto  = array_sum($previsto)/$i;
            $this->numpercentualconcluido = array_sum($concluido)/$i;
        }
        
        $retorno->numpercentualprevisto = number_format($this->numpercentualprevisto, 2);
        $retorno->numpercentualconcluido = number_format($this->numpercentualconcluido, 2);
        return $retorno;
    }
    
    public function retornarDiasEstimadosEReais()
    {
        $retorno = new stdClass();
        if(false === $this->estimativasCalculados)
        {
            $this->estimativasCalculados = true;
            $entregas = $this->entregas->getIterator();
            if(count($entregas) <= 0){
                $retorno->realTotalDias = 0;
                $retorno->realTotalDiasExecutados = 0;
                $retorno->estimativaTotalDias = 0;
                $retorno->estimativaTotalDiasExecutados = 0;
                return $retorno;
            }
            
            foreach($this->entregas as $entrega)
            {
                /* @var $entrega Projeto_Model_Entregacronograma */
                $resultado  = $entrega->retornarDiasEstimadosEReais();
                $this->realTotalDias += $resultado->realTotalDias;
                $this->realTotalDiasExecutados += $resultado->realTotalDiasExecutados;
                $this->estimativaTotalDias += $resultado->estimativaTotalDias;
                $this->estimativaTotalDiasExecutados += $resultado->estimativaTotalDiasExecutados;
            }
        }
        
        $retorno->realTotalDias = $this->realTotalDias;
        $retorno->realTotalDiasExecutados = $this->realTotalDiasExecutados;
        $retorno->estimativaTotalDias = $this->estimativaTotalDias;
        $retorno->estimativaTotalDiasExecutados = $this->estimativaTotalDiasExecutados;
        return $retorno;
    }
    
    public function retornaDiasBaseLine()
    {
        if(null === $this->diasBaseLine){
            $entregas = $this->entregas->getIterator();
            if(count($entregas) <= 0){
                $this->diasBaseLine = 0;
            } else {
                $a = array();

                foreach ( $this->entregas as $entrega )
                {
                    $a[] = $entrega->retornaDiasBaseLine();
                }
                $this->diasBaseLine = array_sum($a);
            }
        }
        return $this->diasBaseLine;
    }
    
     public function retornaDiasReal()
    {
        if(null != $this->diasReal){
            $this->diasReal = $this->datfim->diff($this->datinicio)->days;
        }
        return $this->diasReal;
    }
    
    public function toArray() 
    {
        $retorno = array();
        $retorno['idatividadecronograma']  = $this->idatividadecronograma;
        $retorno['idprojeto']              = $this->idprojeto;
        $retorno['nomatividadecronograma'] = $this->nomatividadecronograma;
        $retorno['domtipoatividade']       = $this->domtipoatividade;
        $retorno['idcadastrador']          = $this->idcadastrador;
        $retorno['datcadastro']            = $this->datcadastro;
        $retorno['vlratividade']           = $this->getCustoFormatado();
        $retorno['diasbaseline']           = $this->retornaDiasBaseLine();
        $retorno['diasreal']               = $this->retornaDiasReal();
        /*
        $retorno['datiniciobaseline'] = $this->datiniciobaseline->format('d/m/Y');
        $retorno['datfimbaseline'] = $this->datiniciobaseline->format('d/m/Y');
        $retorno['datinicio'] = $this->datiniciobaseline->format('d/m/Y');
        $retorno['datfim'] = $this->datiniciobaseline->format('d/m/Y');
        $retorno['datcadastro'] = $this->datiniciobaseline->format('d/m/Y');
        */
        if($this->datiniciobaseline instanceof DateTime){
            $retorno['datiniciobaseline'] = $this->datiniciobaseline->format('d/m/Y');
        }
        
        if($this->datfimbaseline instanceof DateTime){
            $retorno['datfimbaseline'] = $this->datfimbaseline->format('d/m/Y');
        }
        
        if($this->datinicio instanceof DateTime){
            $retorno['datinicio'] = $this->datinicio->format('d/m/Y');
        }
        
        if($this->datfim instanceof DateTime){
            $retorno['datfim'] = $this->datfim->format('d/m/Y');
        }
        
        if($this->datcadastro instanceof DateTime){
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