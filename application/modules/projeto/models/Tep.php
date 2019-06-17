<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Tep extends App_Model_ModelAbstract
{

    public $idprojeto = null;
    public $desconsideracaofinal = null;

    public function init()
    {

    }

    public function formPopulate()
    {
        return array(
            'idprojeto' => $this->idprojeto,
            'desobjetivo' => $this->desobjetivo,
            'desprojeto' => $this->desprojeto,
            'desconsideracaofinal' => $this->desconsideracaofinal
        );
    }
}

