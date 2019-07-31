<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Gerencia extends App_Model_ModelAbstract
{

    const STATUS_PROPOSTA   = 1; 
    const STATUS_ANDAMENTO  = 2; 
    const STATUS_CONCLUIDO  = 3; 
    const STATUS_PARALISADO = 4; 
    const STATUS_CANCELADO  = 5; 
    const STATUS_BLOQUEADO  = 6; 
    const STATUS_ALTERACAO  = 7; 
    
    public $idprojeto                   = null;
    public $nomcodigo                   = null;
    public $nomsigla                    = null;
    public $nomprojeto                  = null;
    public $idsetor                     = null;
    public $idgerenteprojeto            = null;
    public $idgerenteadjunto            = null;
    public $desprojeto                  = null;
    public $desobjetivo                 = null;
    public $numperiodicidadeatualizacao = null;
    public $numcriteriofarol            = null;
    public $idcadastrador               = null;
    public $domtipoprojeto              = null;
    public $domstatusprojeto            = 1;
    public $flaaprovado                 = null;
    public $desresultadosobtidos        = null;
    public $despontosfortes             = null;
    public $despontosfracos             = null;
    public $dessugestoes                = null;
    public $idescritorio                = null;
    public $flaaltagestao               = null;
    public $idobjetivo                  = null;
    public $idacao                      = null;
    public $flacopa                     = null;
    public $idnatureza                  = null;
    public $vlrorcamentodisponivel      = null;
    public $desjustificativa            = null;
    public $iddemandante                = null;
    public $idpatrocinador              = null;
    public $desescopo                   = null;
    public $desnaoescopo                = null;
    public $flapublicado                = null;
    public $despremissa                 = null;
    public $desrestricao                = null;
    public $numseqprojeto               = null;
    public $numanoprojeto               = null;
    public $nummatricula                = null;
    public $desconsideracaofinal        = null;
    public $idprograma                  = null;
    public $datenviouemailatualizacao   = null;
    public $datinicioplano              = null;
    public $datfimplano                 = null;
    public $datcadastro                 = null;
    public $datinicio                   = null;
    public $datfim                      = null;
    public $ano                         = null;
    public $idportfolio                 = null;

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
    public $nomproponente     = null;
    public $nomdemandante     = null;
    public $nompatrocinador   = null;
    public $nomgerenteprojeto = null;
    public $nomgerenteadjunto = null;
    public $partes            = null;
    public $copa              = null;
    public $publicado         = null;
    public $aprovado          = null;

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
    public $matricula         = null;

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


    protected $numpercentualconcluido = 0;
    protected $numpercentualprevisto = 0;
    
    protected $realTotalDias = 0;
    protected $realTotalDiasExecutados = 0;
    
    protected $estimativaTotalDias = 0;
    protected $estimativaTotalDiasExecutados = 0;
    
    protected $percentuaisCalculados = false;
    protected $estimativasCalculados = false;

    public function init()
    {
        $this->timeInterval = new App_TimeInterval();
    }

    public function getFlaativo()
    {
        return $this->flaativo;
    }

    public function setFlaativo($flaativo)
    {
        $valores = array('S', 'N');
        if ( !in_array($flaativo, $valores) ) {
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

        if ( array_key_exists($this->flaativo, $valores) ) {
            return $valores[$this->flaativo];
        }
        return 'Não informado.';
    }

    public function formPopulate()
    {
        return array(
            'datcadastro'                 => $this->datcadastro,
            'datfim'                      => $this->datfim->toString('d/m/Y'),
            'datfimplano'                 => $this->datfimplano->toString('d/m/Y'),
            'datinicio'                   => $this->datinicio->toString('d/m/Y'),
            'datinicioplano'              => $this->datinicioplano->toString('d/m/Y'),
            'desescopo'                   => $this->desescopo,
            'desjustificativa'            => $this->desjustificativa,
            'desnaoescopo'                => $this->desnaoescopo,
            'desobjetivo'                 => $this->desobjetivo,
            'despremissa'                 => $this->despremissa,
            'desprojeto'                  => $this->desprojeto,
            'desrestricao'                => $this->desrestricao,
            'domtipoprojeto'              => $this->domtipoprojeto,
            'desresultadosobtidos'        => $this->desresultadosobtidos,
            'despontosfortes'             => $this->despontosfortes,
            'despontosfracos'             => $this->despontosfracos,
            'dessugestoes'                => $this->dessugestoes,
            'flaaprovado'                 => $this->flaaprovado,
            'flacopa'                     => $this->flacopa,
            'flapublicado'                => $this->flapublicado,
            'idcadastrador'               => $this->idcadastrador,
            'iddemandante'                => $this->iddemandante,
            'idgerenteadjunto'            => $this->idgerenteadjunto,
            'idgerenteprojeto'            => $this->idgerenteprojeto,
            'idnatureza'                  => $this->idnatureza,
            'idobjetivo'                  => $this->idobjetivo,
            'idpatrocinador'              => $this->idpatrocinador,
            'idprograma'                  => $this->idprograma,
            'idprojeto'                   => $this->idprojeto,
            'idsetor'                     => $this->idsetor,
            'idescritorio'                => $this->idescritorio,
            'nomcodigo'                   => $this->nomcodigo,
            'nomprojeto'                  => $this->nomprojeto,
            'numcriteriofarol'            => $this->numcriteriofarol,
            'numperiodicidadeatualizacao' => $this->numperiodicidadeatualizacao,
            'vlrorcamentodisponivel'      => $this->getVlrorcamentodisponivelFormatado(),
            'nomdemandante'               => $this->nomdemandante,
            'nompatrocinador'             => $this->nompatrocinador,
            'nomgerenteprojeto'           => $this->nomgerenteprojeto,
            'nomgerenteadjunto'           => $this->nomgerenteadjunto,
            'ano'                         => $this->ano,
            'idacao'                      => $this->idacao,
            'desconsideracaofinal'        => $this->desconsideracaofinal,
            'idportfolio'                 => $this->idportfolio,
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

    public function retornaDescricaoPrazo()
    {
        $dias = $this->timeInterval->tempoTotal($this->datfim, $this->ultimoStatusReport->datfimprojetotendencia)->dias;

        if ( $dias >= $this->numcriteriofarol ) {
            $sinal = "success";
        } elseif ( $dias > 0 ) {
            $sinal = "warning";
        } else {
            $sinal = "important";
        }
        return $sinal;
    }

    public function retornaDescricaoRisco()
    {
        if ( $this->ultimoStatusReport->domcorrisco == 1 ) {
            $sinal = "success";
        } elseif ( $this->ultimoStatusReport->domcorrisco == 2 ) {
            $sinal = "warning";
        } else {
            $sinal = "important";
        }
        return $sinal;
    }

    public function retornaPrazoEmDias()
    {
        return $this->timeInterval->tempoTotal($this->datfim, $this->ultimoStatusReport->datfimprojetotendencia)->dias;
    }

    public function retornaMetaEmDias()
    {
        return $this->timeInterval->tempoTotal($this->datinicio, $this->datfim)->dias;
    }

    public function retornaTendenciaEmDias()
    {
        return $this->timeInterval->tempoTotal($this->datinicio, $this->ultimoStatusReport->datfimprojetotendencia)->dias;
    }
    
    public function toArray() 
    {
        $retorno = $this->formPopulate();
        $retorno['metaEmDias'] = $this->retornaMetaEmDias();
        $retorno['patrocinador']['nompessoa'] = $this->patrocinador->nompessoa ;
        $retorno['ultimoStatusReport']['numpercentualprevisto'] = $this->ultimoStatusReport->numpercentualprevisto ;
        $retorno['ultimoStatusReport']['datacompanhamento'] = $this->ultimoStatusReport->datacompanhamento ;
        $retorno['descricaoPrazo'] = $this->retornaDescricaoPrazo() ;
        $retorno['prazoEmDias'] = $this->retornaPrazoEmDias() ;
        $retorno['tendenciaEmDias'] = $this->retornaTendenciaEmDias() ;
        
        $retorno['gerenteprojeto']['nompessoa'] = $this->gerenteprojeto->nompessoa ;
        $retorno['ultimoStatusReport']['datfimprojetotendencia'] = $this->ultimoStatusReport->datfimprojetotendencia;
        $retorno['ultimoStatusReport']['numpercentualconcluido'] = $this->ultimoStatusReport->numpercentualconcluido;
        $retorno['ultimoStatusReport']['domstatusprojeto'] = $this->ultimoStatusReport->retornaDescricaoStatusProjeto();
        $retorno['descricaoRisco'] = $this->retornaDescricaoRisco() ;
        
        
        $retorno['gerenteadjunto']['nompessoa'] = $this->gerenteadjunto->nompessoa ;
        $retorno['objetivo']['nomobjetivo'] = $this->objetivo->nomobjetivo;
        $retorno['acao']['nomacao'] = $this->acao->nomacao;
        $retorno['natureza']['nomnatureza'] = isset($this->natureza->nomnatureza)? $this->natureza->nomnatureza : '' ;
        
        return $retorno;
    }

    public function retornaPercentuais()
    {
        $retorno = new stdClass();
        if(false === $this->percentuaisCalculados)
        {
            $this->percentuaisCalculados = true;
            $grupos = $this->grupos->getIterator();
            if(count($grupos) <= 0){
                $retorno->numpercentualprevisto = 0;
                $retorno->numpercentualconcluido = 0;
                return $retorno;
            }
            
            $resultado = $this->retornarDiasEstimadosEReais();
            /*
            foreach($this->grupos as $grupo)
            {
                $dias = $grupo->retornarDiasEstimadosEReais();
                $this->realTotalDias += $dias->realTotalDias;
                $this->realTotalDiasExecutados += $dias->realTotalDiasExecutados;

                $this->estimativaTotalDias += $dias->estimativaTotalDias;
                $this->estimativaTotalDiasExecutados += $dias->estimativaTotalDiasExecutados;
            }
            */

            $this->numpercentualprevisto   =   floor( 100 * ($resultado->estimativaTotalDiasExecutados / $resultado->estimativaTotalDias) );
            $this->numpercentualconcluido  =   floor( 100 * ($resultado->realTotalDiasExecutados / $resultado->realTotalDias) );
        }

        $retorno->numpercentualprevisto = $this->numpercentualprevisto;
        $retorno->numpercentualconcluido = $this->numpercentualconcluido;
        return $retorno;
    }
    
    
    public function retornarDiasEstimadosEReais()
    {
        $retorno = new stdClass();
        if(false === $this->estimativasCalculados)
        {
            $this->estimativasCalculados = true;
            $grupos = $this->grupos->getIterator();
            if(count($grupos) <= 0){
                $retorno->realTotalDias = 0;
                $retorno->realTotalDiasExecutados = 0;
                $retorno->estimativaTotalDias = 0;
                $retorno->estimativaTotalDiasExecutados = 0;
                return $retorno;
            }
            
            $i = 0;
            foreach($this->grupos as $grupo)
            {
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
        $valorfinal = str_replace(",","", str_replace(".","",$valor));
        $this->vlrorcamentodisponivel = $valorfinal;
        return $this;
    }

    public function getVlrorcamentodisponivel()
    {
        return $this->vlrorcamentodisponivel;
    }
    
    public function getVlrorcamentodisponivelFormatado()
    {
        $valor = substr($this->vlrorcamentodisponivel, 0, -2) . '.' . substr($this->vlrorcamentodisponivel, -2);
        return number_format($valor, 2, ',', '.');
    }
    
    
    public function retornaDescricaoStatusProjeto() { 
        
        $descricao = array(
            1 =>  'Proposta',
            2 =>  'Em Andamento',
            3 =>  'Concluído',
            4 =>  'Paralizado',
            5 =>  'Cancelado',
            6 =>  'Bloqueado',
        );
        
        return $descricao[$this->domstatusprojeto];
    }
}

