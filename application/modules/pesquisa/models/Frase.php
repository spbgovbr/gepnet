<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Pesquisa_Model_Frase extends App_Model_ModelAbstract
{

    public $idfrase = null;
    public $desfrase = null;
    public $domtipofrase = null;
    public $flaativo = null;
    public $datcadastro = null;
    public $idescritorio = null;
    public $idcadastrador = null;

    //dom tipo frase
    const UMA_ESCOLHA = 1;
    const MULTIPLA_ESCOLHA = 2;
    const DESCRITIVO = 3;
    const TEXTO = 4;
    const NUMERO = 5;
    const DATA = 6;
    const UF = 7;

    //flaativo
    const ATIVO = 'S';
    const INATIVO = 'N';

}

