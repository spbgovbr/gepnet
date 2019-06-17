<?php

/**
 * Automatically generated data model
 *
 */
class Diagnostico_Model_PadronizacaoMelhoria extends App_Model_ModelAbstract
{

    public $idpadronizacaomelhoria = null;
    public $idmelhoria = null;
    public $desrevisada = null;
    public $idprazo = null;
    public $idimpacto = null;
    public $idesforco = null;
    public $numpontuacao = null;
    public $numincidencia = null;
    public $numvotacao = null;
    public $flaagrupadora = null;
    public $destitulogrupo = null;
    public $desmelhoriaagrupadora = null;
    public $desinformacoescomplementares = null;

    public function formPopulate()
    {
        return array(
            'idpadronizacaomelhoria' => $this->idpadronizacaomelhoria,
            'idmelhoria' => $this->idmelhoria,
            'desrevisada' => $this->desrevisada,
            'idprazo' => $this->idprazo,
            'idimpacto' => $this->idimpacto,
            'idesforco' => $this->idesforco,
            'numpontuacao' => $this->numpontuacao,
            'numincidencia' => $this->numincidencia,
            'numvotacao' => $this->numvotacao,
            'flaagrupadora' => $this->flaagrupadora,
            'destitulogrupo' => $this->destitulogrupo,
            'desmelhoriaagrupadora' => $this->desmelhoriaagrupadora,
            'desinformacoescomplementares' => $this->desinformacoescomplementares,
        );
    }

}
