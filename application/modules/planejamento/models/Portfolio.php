<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Planejamento_Model_Portfolio extends App_Model_ModelAbstract
{

    public $idportfolio = null;
    public $noportfolio = null;
    public $idportfoliopai = null;
    public $ativo = null;
    public $tipo = null;
    public $idresponsavel = null;
    public $idescritorio = null;

    public $idprograma = null;
    public $nomresponsavel = null;
    public $nomescritorio = null;
    public $desativo = null;
    public $destipo = null;
    public $noportfoliopai = null;


    public function formPopulate()
    {
        return array(
            'idportfolio' => $this->idportfolio,
            'noportfolio' => $this->noportfolio,
            'idportfoliopai' => $this->idportfoliopai,
            'ativo' => $this->ativo,
            'tipo' => $this->tipo,
            'idresponsavel' => $this->idresponsavel,
            'idescritorio' => $this->idescritorio,
            'idprograma' => $this->idprograma,
            'nomresponsavel' => $this->nomresponsavel
        );
    }

    public function toArray()
    {

        $retorno = array();
        $retorno['idportfolio'] = $this->idportfolio;
        $retorno['noportfolio'] = $this->noportfolio;
        $retorno['idportfoliopai'] = $this->idportfoliopai;
        $retorno['ativo'] = $this->ativo;
        $retorno['tipo'] = $this->tipo;
        $retorno['idresponsavel'] = $this->idresponsavel;
        $retorno['idescritorio'] = $this->idescritorio;

        return $retorno;
    }

    public function getAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $valores = array('S', 'N');
        if (!in_array($ativo, $valores)) {
            throw new Exception('Este model somente aceita os valores S ou N');
        }
        $this->ativo = $ativo;
        return $this;
    }
}

