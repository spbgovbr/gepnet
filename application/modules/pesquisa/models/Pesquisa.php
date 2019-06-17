<?php

class Pesquisa_Model_Pesquisa extends App_Model_ModelAbstract
{

    public $idpesquisa = null;
    public $situacao = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $datpublicacao = null;
    public $idpespublica = null;
    public $idpesencerra = null;
    public $idquestionario = null;
    public $dtencerramento = null;

    const PUBLICADO = 1;
    const ENCERRADO = 2;
}
