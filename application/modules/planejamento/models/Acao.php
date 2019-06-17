<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Planejamento_Model_Acao extends App_Model_ModelAbstract
{

    public $idacao = null;
    public $idobjetivo = null;
    public $nomacao = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $flaativo = 's';
    public $desacao = null;
    public $idescritorio = '0';
    public $numseq = '0';

    public $totalProjeto = null;
    public $totalProjetoProposta = null;

    public function formPopulate()
    {
        return array(
            'idacao' => $this->idacao,
            'idobjetivo' => $this->idobjetivo,
            'nomacao' => $this->nomacao,
            'idcadastrador' => $this->idcadastrador,
            'datcadastro' => $this->datcadastro->toString('d/m/Y'),
            'flaativo' => $this->flaativo,
            'desacao' => $this->desacao,
            'idescritorio' => $this->idescritorio,
            'numseq' => $this->numseq,
        );
    }

    public function setDatcadastro($data)
    {
        $this->datcadastro = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function toArray()
    {

        $retorno = array();
        $retorno['idacao'] = $this->idacao;
        $retorno['idobjetivo'] = $this->idobjetivo;
        $retorno['nomacao'] = $this->nomacao;
        $retorno['idcadastrador'] = $this->idcadastrador;
        $retorno['datcadastro'] = $this->datcadastro;
        $retorno['flaativo'] = $this->flaativo;
        $retorno['desacao'] = $this->desacao;
        $retorno['idescritorio'] = $this->idescritorio;
        $retorno['numseq'] = $this->numseq;

        return $retorno;
    }
}

