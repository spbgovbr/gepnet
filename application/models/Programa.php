<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Default_Model_Programa extends App_Model_ModelAbstract
{

    public $idprograma = null;
    public $nomprograma = null;
    public $desprograma = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $flaativo = null;
    public $idresponsavel = null;
    public $nomresponsavel = null;
    public $idsimpr = null;
    public $idsimpreixo = null;
    public $idsimprareatematica = null;

    /**
     *
     * @var Default_Model_Pessoa
     */
    public $responsavel = null;


    //public $nompessoa = null;


    public function getFlaativo()
    {
        return $this->flaativo;
    }

    public function setFlaativo($flaativo)
    {
        $valores = array('S', 'N');
        if (!in_array($flaativo, $valores)) {
            throw new Exception('Este model somente aceita os valores S ou N');
        }
        $this->flaativo = $flaativo;
        return $this;
    }


    public function getDescricaoFlaativo()
    {

        $valores = array(
            'S' => 'Sim',
            'N' => 'Não',
        );

        if (array_key_exists($this->flaativo, $valores)) {
            return $valores[$this->flaativo];
        }
        return 'Não informado.';
    }


    public function formPopulate()
    {
        return array(

            'idprograma' => $this->idprograma,
            'nomprograma' => $this->nomprograma,
            'desprograma' => $this->desprograma,
            'idcadastrador' => $this->idcadastrador,
            'datcadastro' => $this->datcadastro,
            'flaativo' => $this->flaativo,
            'idresponsavel' => $this->idresponsavel,
            'nomresponsavel' => $this->nomresponsavel,
            'idsimpr' => $this->idsimpr,
            'idsimpreixo' => $this->idsimpreixo,
            'idsimprareatematica' => $this->idsimprareatematica,
            'nompessoa' => $this->nompessoa


        );
    }


}

