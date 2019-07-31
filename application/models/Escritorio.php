<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Default_Model_Escritorio extends App_Model_ModelAbstract
{

    public $idescritorio = null;
    public $nomescritorio = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $flaativo = null;
    public $idresponsavel1 = null;
    public $idresponsavel2 = null;
    public $idescritoriope = null;
    public $nomescritorio2 = null;
    public $nome = null;
    public $sigla = null;
    public $desemail = null;
    public $numfone = null;
    //public $situacao = null;
    /**
     * Relacionamentos
     * @var Default_Model_Pessoa
     */
    public $responsavel1 = null;

    /**
     * Relacionamentos
     * @var Default_Model_Pessoa
     */
    public $responsavel2 = null;

    /**
     * Relacionamentos
     * @var Default_Model_Escritorio
     */
    public $mapa = null;


    public $nomresponsavel1 = null;
    public $nomresponsavel2 = null;

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
            'idescritorio' => $this->idescritorio,
            'nomescritorio' => $this->nomescritorio,
            'idcadastrador' => $this->idcadastrador,
            'datcadastro' => $this->datcadastro,
            'flaativo' => $this->flaativo,
            'idresponsavel1' => $this->idresponsavel1,
            'idresponsavel2' => $this->idresponsavel2,
            'idescritoriope' => $this->idescritoriope,
            'nomescritorio2' => $this->nomescritorio2,
            'desemail' => $this->desemail,
            'numfone' => $this->numfone
        );
    }

}

