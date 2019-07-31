<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Comunicacao extends App_Model_ModelAbstract
{

    public $idcomunicacao = null;
    public $idprojeto = null;
    public $desinformacao = null;
    public $desinformado = null;
    public $desorigem = null;
    public $desfrequencia = null;
    public $destransmissao = null;
    public $desarmazenamento = null;
    public $idresponsavel = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $nomresponsavel = null;

    public function setDatcadastro()
    {
        $this->datcadastro = new Zend_Db_Expr("now()");
    }

    public function setIdcadastrador($idcadastrador)
    {
        $this->idcadastrador = $idcadastrador;
    }

}

