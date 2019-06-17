<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Acordocooperacao_Model_Entidadeexterna extends App_Model_ModelAbstract
{

    public $identidadeexterna = null;
    public $nomentidadeexterna = null;
    public $idcadastrador = null;
    public $datcadastro = null;

    public $nompessoa = null;

    public function formPopulate()
    {
        return array(
            'identidadeexterna' => $this->identidadeexterna,
            'nomentidadeexterna' => $this->nomentidadeexterna,
            'idcadastrador' => $this->idcadastrador,
            'datcadastro' => $this->datcadastro,
            'nompessoa' => $this->nompessoa,
        );
    }
}

