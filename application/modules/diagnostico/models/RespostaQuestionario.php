<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 30-10-2018 16:11
 */
class Diagnostico_Model_RespostaQuestionario extends App_Model_ModelAbstract
{

    public $id_resposta_pergunta = null;
    public $ds_resposta_descritiva = null;
    public $idpergunta = null;
    public $idresposta = null;
    public $nrquestionario = null;
    public $idquestionariodiagnostico = null;
    public $iddiagnostico = null;


    public function toArray()
    {
        return array(
            'id_resposta_pergunta' => $this->id_resposta_pergunta,
            'ds_resposta_descritiva' => $this->ds_resposta_descritiva,
            'idpergunta' => $this->idpergunta,
            'idresposta' => $this->idresposta,
            'nrquestionario' => $this->nrquestionario,
            'idquestionariodiagnostico' => $this->idquestionariodiagnostico,
            'iddiagnostico' => $this->iddiagnostico,
        );
    }

}
