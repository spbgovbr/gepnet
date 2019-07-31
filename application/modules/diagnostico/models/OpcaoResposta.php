<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 30-10-2018 16:11
 */
class Diagnostico_Model_OpcaoResposta extends App_Model_ModelAbstract
{

    public $idresposta = null;
    public $idpergunta = null;
    public $idquestionario = null;
    public $desresposta = null;
    public $escala = 0;
    public $ordenacao = 0;

    public function formPopulate()
    {
        return array(
            'idresposta' => $this->idresposta,
            'idpergunta' => $this->idpergunta,
            'idquestionario' => $this->idquestionario,
            'desresposta' => $this->desresposta,
            'escala' => $this->escala,
            'ordenacao' => $this->ordenacao,
        );
    }

}
