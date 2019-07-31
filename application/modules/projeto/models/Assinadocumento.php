<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Assinadocumento extends App_Model_ModelAbstract
{

    public $id = null;
    public $idpessoa = null;
    public $idprojeto = null;
    public $assinado = null;
    public $tipodoc = null;
    public $hashdoc = null;
    public $situacao = null;
    public $nomfuncao = null;
    public $idaceite = null;

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'idpessoa' => $this->idpessoa,
            'idprojeto' => $this->idprojeto,
            'assinado' => $this->assinado,
            'tipodoc' => $this->tipodoc,
            'hashdoc' => $this->hashdoc,
            'situacao' => $this->situacao,
            'nomfuncao' => $this->nomfuncao,
            'idaceite' => $this->idaceite,
        );
    }
}

