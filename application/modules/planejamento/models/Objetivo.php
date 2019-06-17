<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Planejamento_Model_Objetivo extends App_Model_ModelAbstract
{

    public $idobjetivo = null;
    public $nomobjetivo = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $flaativo = 's';
    public $desobjetivo = null;
    public $codescritorio = '0';
    public $numseq = '0';

    public $acoes = array();
    public $totalProjeto = null;
    public $totalProjetoProposta = null;

    public function formPopulate()
    {
        return array(
            'idobjetivo' => $this->idobjetivo,
            'nomobjetivo' => $this->nomobjetivo,
            'idcadastrador' => $this->idcadastrador,
            'datcadastro' => $this->datcadastro->toString('d/m/Y'),
            'flaativo' => $this->flaativo,
            'desobjetivo' => $this->desobjetivo,
            'codescritorio' => $this->codescritorio,
            'numseq' => $this->numseq,
        );
    }

    public function setDatCadastro($data)
    {
        $this->datcadastro = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function toArray()
    {
        $retorno = array();
        $retorno['idobjetivo'] = $this->idobjetivo;
        $retorno['nomobjetivo'] = $this->nomobjetivo;
        $retorno['idcadastrador'] = $this->idcadastrador;
        $retorno['datcadastro'] = $this->datcadastro;
        $retorno['flaativo'] = $this->flaativo;
        $retorno['desobjetivo'] = $this->desobjetivo;
        $retorno['codescritorio'] = $this->codescritorio;
        $retorno['numseq'] = $this->numseq;

        return $retorno;
    }
}

