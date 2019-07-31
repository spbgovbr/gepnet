<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Cadastro_Model_Setor extends App_Model_ModelAbstract
{

    public $idsetor = null;
    public $nomsetor = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $flaativo = 's';

    public $situacao = null;


    public function getDescricaoFlaativo()
    {
        $valores = array(
            'S' => 'Ativo',
            'N' => 'Inativo',
        );

        if (array_key_exists($this->flaativo, $valores)) {
            return $valores[$this->flaativo];
        }
        return 'Não informado.';
    }

    public function formPopulate()
    {
        return array(
            "idsetor" => $this->idsetor,
            "nomsetor" => $this->nomsetor,
            "idcadastrador" => $this->idcadastrador,
            "datcadastro" => $this->datcadastro,
            "flaativo " => $this->flaativo,
        );

    }
}

