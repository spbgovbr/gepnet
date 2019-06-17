<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_Atividadeocultar extends App_Model_ModelAbstract
{

    public $idprojeto = null;
    public $idatividadecronograma = null;
    public $idpessoa = null;
    public $flashowhide = null;
    public $dtcadastro = null;

    public function toArray()
    {
        $retorno = array();
        $retorno['idprojeto'] = $this->idprojeto;
        $retorno['idatividadecronograma'] = $this->idatividadecronograma;
        $retorno['idpessoa'] = $this->idpessoa;
        $retorno['dtcadastro'] = $this->dtcadastro;
        return $retorno;
    }

}