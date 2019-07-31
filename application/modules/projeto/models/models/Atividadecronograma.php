<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Atividadecronograma extends Projeto_Model_AtividadecronogramaAbstract
{
    public $idatividadecronograma  = null;
    public $idprojeto              = null;
    public $idgrupo                = null;
    public $numseq                 = null;
    public $numpercentualconcluido = '0';
    public $numdiasrealizados      = 0;
    public $numdiasbaseline        = 0;
    public $nomatividadecronograma = null;
    public $datiniciobaseline      = null;
    public $datfimbaseline         = null;
    public $datinicio              = null;
    public $datfim                 = null;
    public $idparteinteressada     = '0'; # Responsável pela aceitação da entrega
    public $domtipoatividade       = null;
    public $flacancelada           = 'N';
    public $desobs                 = null;
    public $idcadastrador          = null;
    public $datcadastro            = null;
    public $idmarcoanterior        = null;
    public $numdias                = null;
    public $vlratividadebaseline   = '0';
    public $vlratividade           = '0';
    public $numfolga               = '0';
    public $descriterioaceitacao   = null;
    public $flaaquisicao           = 'N';
    public $flainformatica         = 'N';
    public $idelementodespesa      = null;
    
    /**
     * atributtos auxiliares;
     */
    public $nomparteinteressada      = null;
    public $hoje = null;
    public $predecessoras = array();
    /**
     *
     * @var Projeto_Model_Parteinteressada 
     */
    public $parteinteressada = null;
    
    protected $realTotalDias = 0;
    protected $realTotalDiasExecutados = 0;
    protected $estimativaTotalDias = 0;
    protected $estimativaTotalDiasExecutados = 0;
    
    protected $metaEmDias = 0;
    protected $tendenciaEmDias = 0;
    
    protected $diasCalculados = false;
    
    protected $diasBaseLine = null;
    protected $diasReal     = null;
        
    /**
     *
     * @var App_TimeInterval
     */
   // public $timeInterval = null;

    public function init()
    {
        //$this->timeInterval = new App_TimeInterval();
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
    
    public function setVlratividadebaseline($valor)
    {
        $valorfinal = str_replace(",","", str_replace(".","",$valor));
        $this->vlratividadebaseline = $valorfinal;
        return $this;
    }

    public function getVlratividadebaseline()
    {
        return $this->vlratividadebaseline;
    }
    
    public function getVlratividadebaselineFormatado()
    {
        $valor = substr($this->vlratividadebaseline, 0, -2) . '.' . substr($this->vlratividadebaseline, -2);
        return number_format($valor, 2, ',', '.');
    }
    
    public function setVlratividade($valor)
    {
        $valorfinal = str_replace(",","", str_replace(".","",$valor));
        $this->vlratividade = $valorfinal;
        return $this;
    }

    public function getVlratividade()
    {
        return $this->vlratividade;
    }
    
    public function getVlratividadeFormatado()
    {
        $valor = substr($this->vlratividade, 0, -2) . '.' . substr($this->vlratividade, -2);
        return number_format($valor, 2, ',', '.');
    }


     /**
     *
     * @param Default_Model_Permissao $permissao
     */
    public function adicionarPredecessora(Projeto_Model_Atividadepredecessora $predecessora)
    {
        $this->predecessoras[] = $predecessora;
        return $this;
    }
    
     /**
     *
     * @return boolean | array
     */
    public function retornaPredecessoras()
    {
        if(count($this->predecessoras) > 0){
            return $this->predecessoras;
        }
        return false;
    }

     /**
     *
     * @return boolean | array
     */
    public function limparPredecessoras()
    {
        $this->predecessoras = array();
    }
    
    /**
     * 
     * @return \stdClass
     */
    public function retornarDiasEstimadosEReais()
    {
        if(false === $this->diasCalculados)
        {
            $this->diasCalculados = true;
            /**
             * Calculo para a atividade
             */
            $diasAtividade = $this->datfim->diff($this->datinicio)->days;
            if ($diasAtividade <= 0){
                $diasAtividade = 1;
            }
            $diasExecutadosAtividade = ($diasAtividade * $this->numpercentualconcluido/100);
            $this->realTotalDias = $diasAtividade;
            $this->realTotalDiasExecutados = $diasExecutadosAtividade;

            /**
             * Calculo para a estimativa
             */

            $diasBaseLine = $this->datfimbaseline->diff($this->datiniciobaseline)->days;
            if ($diasBaseLine <= 0){
                $diasBaseLine = 1;
            }
            $this->estimativaTotalDias = $diasBaseLine;
            $diasExecutadosBaseLine = ($diasBaseLine * $this->numpercentualconcluido/100);

            if ($this->datfimbaseline <= $this->hoje){
                $this->estimativaTotalDiasExecutados = $diasExecutadosBaseLine;
            }
        }
        
        $retorno = new stdClass();
        $retorno->estimativaTotalDias           = $this->estimativaTotalDias;
        $retorno->estimativaTotalDiasExecutados = $this->estimativaTotalDiasExecutados;
        $retorno->realTotalDias                 = $this->realTotalDias;
        $retorno->realTotalDiasExecutados       = $this->realTotalDiasExecutados;
        
        return $retorno;
    }   
    
    public function retornaDiasBaseLine()
    {
        if(null === $this->diasBaseLine){
            $this->diasBaseLine = $this->datfimbaseline->diff($this->datiniciobaseline)->days;
        }
        return $this->diasBaseLine;
    }
    public function retornaDiasReal()
    {
        if(null === $this->diasReal){
            $this->diasReal = $this->datfim->diff($this->datinicio)->days;
        }
        return $this->diasReal;
    }
    
    public function toArray() 
    {
        $retorno = get_object_vars($this);
        $retorno['datiniciobaseline'] = $this->datiniciobaseline->format('d/m/Y');
        $retorno['datfimbaseline'] = $this->datfimbaseline->format('d/m/Y');
        $retorno['datinicio'] = $this->datinicio->format('d/m/Y');
        $retorno['datfim'] = $this->datfim->format('d/m/Y');
        $retorno['diasbaseline'] = $this->retornaDiasBaseLine();
        $retorno['diasreal']     = $this->retornaDiasReal();
        $retorno['vlratividade'] = $this->getVlratividadeFormatado();
        $retorno['vlratividadebaseline'] = $this->getVlratividadebaselineFormatado();
        
        if($this->numfolga >= 0){
            $retorno['datfim'] .= " F({$this->numfolga})";
        }
        
        $retorno['realtotaldias'] = $this->realTotalDias;
        $retorno['realtotaldiasexecutados'] = $this->realTotalDiasExecutados;
        $retorno['retornaDescricaoConclusao'] = $this->retornaDescricaoConclusao();
        $retorno['nomparteinteressada'] = $this->parteinteressada->nomparteinteressada;
        $retorno['desemail'] = substr($this->parteinteressada->desemail, 0, strpos($this->parteinteressada->desemail, "@"));
        $retorno['email'] = $this->parteinteressada->desemail;
        $retorno['predecessoras'] = array();
        
        foreach ($this->predecessoras as $p)
        {
            $retorno['predecessoras'][] = $p->toArray();
        }
        
        //$retorno['datcadastro'] = $this->datcadastro->format('d/m/Y');
        
        return $retorno;
    }
}

