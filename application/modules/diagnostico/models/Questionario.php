<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 30-10-2018 16:11
 */
class Diagnostico_Model_Questionario extends App_Model_ModelAbstract
{

    public $idquestionariodiagnostico = null;
    public $nomquestionario = null;
    public $tipo = null;
    public $observacao = null;
    public $idpescadastrador = null;
    public $dtcadastro = null;


    public function formPopulate()
    {
        return array(
            'idquestionariodiagnostico' => $this->idquestionariodiagnostico,
            'nomquestionario' => $this->nomquestionario,
            'tipo' => $this->tipo,
            'observacao' => $this->observacao,
            'idpescadastrador' => $this->idpescadastrador,
            'dtcadastro' => $this->dtcadastro,
        );
    }

}
