<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Agenda_Model_Pessoaagenda extends App_Model_ModelAbstract
{

    public $idagenda = null;
    public $idpessoa = null;
    public $nompessoa = null;
    public $desemail = null;

    public function formPopulate()
    {
        return array(
            'idagenda' => $this->idagenda,
            'idpessoa' => $this->idpessoa,
            'nompessoa' => $this->nompessoa,
            'desemail' => $this->desemail,
        );
    }
}

