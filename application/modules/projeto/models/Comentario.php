<?php

/**
 * Created by PhpStorm.
 * User: Wendell
 * Date: 05/10/2018
 * Time: 12:13
 */
class Projeto_Model_Comentario extends Projeto_Model_AtividadecronogramaAbstract
{
    public $idcomentario = null;
    public $idprojeto = null;
    public $idatividadecronograma = null;
    public $idpessoa = null;
    public $dscomentario = null;
    public $dtcomentario = null;

    public function setdtcomentario()
    {
        $this->dtcomentario = new Zend_Db_Expr("now()");
        return $this;
    }

    public function toArray()
    {
        $retorno = get_object_vars($this);
        $retorno['idcomentario'] = $this->idcomentario;
        $retorno['idprojeto'] = $this->idprojeto;
        $retorno['idatividadecronograma'] = $this->idatividadecronograma;
        $retorno['idpessoa'] = $this->idpessoa;
        $retorno['dscomentario'] = $this->dscomentario;
        $retorno['dtcomentario'] = $this->dtcomentario->format('d/m/Y');

    }
}