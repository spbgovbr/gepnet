<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Atividadepredecessora extends App_Model_ModelAbstract
{
    public $idatividadepredecessora = null;
    public $numseq = null;
    public $idatividade = null;
    public $idprojeto = null;

    /**
     * Atributos auxiliares
     */
    public $nomatividadecronograma = null;
    public $numdiasrealizados = null;
    public $datinicio = null;
    public $datfim = null;

    public function toArray()
    {
        $retorno = array();
        $retorno['idatividadepredecessora'] = $this->idatividadepredecessora;
        $retorno['numseq'] = $this->numseq;
        $retorno['idatividade'] = $this->idatividade;
        $retorno['idprojeto'] = $this->idprojeto;
        $retorno['nomatividadecronograma'] = $this->nomatividadecronograma;
        $retorno['numdiasrealizados'] = $this->numdiasrealizados;
        $retorno['datinicio'] = $this->datinicio;
        $retorno['datfim'] = $this->datfim;
        return $retorno;
    }

}