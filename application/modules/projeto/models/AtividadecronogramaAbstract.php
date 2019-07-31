<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
abstract class Projeto_Model_AtividadecronogramaAbstract
    extends App_Model_ModelAbstract 
    implements Projeto_Model_AtividadecronogramaInterface
{
    
    const TIPO_ATIVIDADE_GRUPO   = 1;
    const TIPO_ATIVIDADE_ENTREGA = 2;
    const TIPO_ATIVIDADE_COMUM   = 3;
    const TIPO_ATIVIDADE_MARCO   = 4;
    
    public $idatividadecronograma  = null;
    public $idprojeto              = null;
    public $nomatividadecronograma = null;
    public $domtipoatividade       = null;
    public $idcadastrador          = null;
    public $datcadastro            = null;
    public $idgrupo                = null;
    public $flacancelada           = null;
    
    /**
     * Retorna a descricao e o prazo em dias
     * @param integer $numcriteriofarol
     * @return \stdClass
     */
    public function retornaPrazo($numcriteriofarol)
    {
        $dias = $this->datfim->diff($this->datfimbaseline)->days;
        
        //SAPS 106187​ (item 6)
        if($this->datfim <= $this->datfimbaseline){ 
            $sinal = "success";
        } else {
            if ( $dias > $numcriteriofarol ) {
                $sinal = "important";
                $dias = "-".$dias;
            } elseif ( $dias <= $numcriteriofarol && $dias > 0 ) {
                $dias = "-".$dias;
                $sinal = "warning";
            }
        }
        $retorno = new stdClass();
        $retorno->descricao = $sinal;
        $retorno->dias = $dias;
        
        return $retorno;
    }
    
    public function retornaDescricaoConclusao()
    {
        $hoje = new DateTime('now');
        $classe = 'item-em-dia';
        
        if ($this->numpercentualconcluido == 100){
            $classe = 'item-concluido';
        } else {
            if ( $this->datfim < $hoje ) {
                $classe = 'item-atrasado';
            }
        }
    
        
        /*
        elseif ( $this->datfim <= $hoje ){
            $classe = 'item-atrasado';
        }elseif ( $this->datinicio >= $hoje && $this->datfim > $hoje ){
            $classe = 'item-em-dia';
        }
        */

        if ($this->domtipoatividade == self::TIPO_ATIVIDADE_COMUM && $this->flacancelada == 'S'){
            $classe = 'item-cancelado';
        }
        
        return $classe;
    }
}

