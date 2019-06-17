<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Licao extends App_Model_ModelAbstract
{

    public $idlicao = null;
    public $idprojeto = null;
    public $identrega = null;
    public $desresultadosobtidos = null;
    public $despontosfortes = null;
    public $despontosfracos = null;
    public $dessugestoes = null;
    public $datcadastro = null;

    public $nomatividadecronograma = null;
    public $numdiasrealizados = null;
    public $desprojeto = null;
    public $desobjetivo = null;
    public $idassociada = null;
    public $nomassociada = null;

    public function formPopulate()
    {
        return array(
            'idlicao' => $this->idlicao,
            'idprojeto' => $this->idprojeto,
            'identrega' => $this->identrega,
            'desresultadosobtidos' => $this->desresultadosobtidos,
            'despontosfortes' => $this->despontosfortes,
            'despontosfracos' => $this->despontosfracos,
            'dessugestoes' => $this->dessugestoes,
            'datcadastro' => $this->datcadastro,
            'idassociada' => $this->idassociada,
            'nomassociada' => $this->nomassociada,

        );
    }

}