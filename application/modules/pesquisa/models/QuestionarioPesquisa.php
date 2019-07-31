<?php

class Pesquisa_Model_QuestionarioPesquisa extends App_Model_ModelAbstract
{

    public $idquestionariopesquisa = null;
    public $idpesquisa = null;
    public $nomquestionario = null;
    public $desobservacao = null;
    public $tipoquestionario = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $idescritorio = null;

    //Tipo questionario
    const PUBLICADO_COM_SENHA = 1;
    const PUBLICADO_SEM_SENHA = 2;
}
