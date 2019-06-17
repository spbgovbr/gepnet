<?php

class Pesquisa_Model_Questionario extends App_Model_ModelAbstract
{

    public $idquestionario = null;
    public $nomquestionario = null;
    public $desobservacao = null;
    public $tipoquestionario = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $idescritorio = null;
    public $disponivel = null;

    //disponivel
    const DISPONILVEL = 1;
    const INDISPONILVEL = 0;

    //tipoquestionario
    const PUBLICADO_COM_SENHA = 1;
    const PUBLICADO_SEM_SENHA = 2;
}

