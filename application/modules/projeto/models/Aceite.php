<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Aceite extends App_Model_ModelAbstract
{

    public $idaceite = null;
    public $identrega = null;
    public $idprojeto = null;
    public $idmarco = null;
    public $desprodutoservico = null;
    public $desparecerfinal = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $flaaceite = null;

    public $nomatividadecronograma = null;
    public $nomresponsavel = null;
    public $desflaaceite = null;
    public $desobs = null;
    public $descriterioaceitacao = null;
    public $nomarco = null;
    public $grupo = null;
    public $nomparteinteressadaentrega = null;

    public function formPopulate()
    {
        return array(
            'idaceite' => $this->idaceite,
            'identrega' => $this->identrega,
            'idprojeto' => $this->idprojeto,
            'idmarco' => $this->idmarco,
            'desprodutoservico' => $this->desprodutoservico,
            'desparecerfinal' => $this->desparecerfinal,
            'idcadastrador' => $this->idcadastrador,
            'datcadastro' => $this->datcadastro,
            'flaaceite' => $this->flaaceite,
            'nomresponsavel' => $this->nomresponsavel,
            'grupo' => $this->grupo,
            'nomparteinteressadaentrega' => $this->nomparteinteressadaentrega
        );
    }

}

