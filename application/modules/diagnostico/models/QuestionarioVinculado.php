<?php

class Diagnostico_Model_QuestionarioVinculado extends App_Model_ModelAbstract
{
    const DISPONIVEL = '1';
    const INDISPONIVEL = '2';

    public $idquestionario = null;
    public $iddiagnostico = null;
    public $disponivel = null;
    public $idpesdisponibiliza = null;
    public $dtdisponibilidade = null;
    public $dtencerrramento = null;
    public $idpesencerrou = null;

    public function setDtDisponibilidade($data)
    {
        $this->dtdisponibilidade = @DateTime::createFromFormat('d/m/Y', $data);
        return $this;
    }

    public function toArray()
    {
        return array(
            'idquestionario' => $this->idquestionario,
            'iddiagnostico' => $this->iddiagnostico,
            'disponivel' => $this->disponivel,
            'idpesdisponibiliza' => $this->idpesdisponibiliza,
            'dtdisponibilidade' => $this->dtdisponibilidade,
            'dtencerrramento' => $this->dtencerrramento,
            'idpesencerrou' => $this->idpesencerrou
        );
    }

}