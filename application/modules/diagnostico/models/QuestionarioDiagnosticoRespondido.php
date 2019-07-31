<?php

class Diagnostico_Model_QuestionarioDiagnosticoRespondido extends App_Model_ModelAbstract
{

    public $iddiagnostico = null;
    public $idquestinario = null;
    public $numero = null;
    public $dtresposta = null;
    public $idpessoaresposta = null;


    public function toArray()
    {
        return array(
            'idquestionario' => $this->idquestinario,
            'iddiagnostico' => $this->iddiagnostico,
            'numero' => $this->numero,
            'dtresposta' => $this->dtresposta,
            'idpessoaresposta' => $this->idpessoaresposta
        );
    }

}