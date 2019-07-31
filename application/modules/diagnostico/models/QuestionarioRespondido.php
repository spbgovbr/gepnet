<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 30-10-2018 16:11
 */
class Diagnostico_Model_QuestionarioRespondido extends App_Model_ModelAbstract
{

    public $id_resposta_pergunta = null;
    public $iddiagnostico = null;
    public $id_questinario = null;
    public $idpergunta = null;
    public $idresposta = null;
    public $idpessoa_responde = null;
    public $ds_resposta_descritiva = null;
    public $dt_resposta = null;


    public function toArray()
    {
        return array(
            'id_resposta_pergunta' => $this->id_resposta_pergunta,
            'iddiagnostico' => $this->iddiagnostico,
            'id_questinario' => $this->id_questinario,
            'idpergunta' => $this->idpergunta,
            'idresposta' => $this->idresposta,
            'idpessoa_responde' => $this->idpessoa_responde,
            'ds_resposta_descritiva' => $this->ds_resposta_descritiva,
            'dt_resposta' => $this->dt_resposta,
        );
    }

}
