<?php

/**
 *
 * Newton Carlos "" @ 07-12-2018 15:54
 */
class Diagnostico_Model_Pergunta extends App_Model_ModelAbstract
{

    public $idpergunta = null;
    public $dspergunta = null;
    public $tipopergunta = null;
    public $ativa = null;
    public $idquestionario = null;
    public $posicao = null;
    public $id_secao = null;
    public $tiporegistro = null;
    public $dstitulo = null;
    public $iddiagnostico = null;
    public $numero = null;

    const DESCRITIVA = '1';
    const MULTIPLA_ESCOLHA = '2';
    const UNICA_ESCOLHA = '3';

    public function formPopulate()
    {
        return array(
            'idpergunta' => $this->idpergunta,
            'dspergunta' => $this->dspergunta,
            'tipopergunta' => $this->tipopergunta,
            'ativa' => $this->ativa,
            'idquestionario' => $this->idquestionario,
            'posicao' => $this->posicao,
            'id_secao' => $this->id_secao,
            'tiporegistro' => $this->tiporegistro,
            'dstitulo' => $this->dstitulo,
            'iddiagnostico' => $this->iddiagnostico,
            'numero' => $this->numero,
        );
    }

}
