<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Model_AtividadeCronoPredecessora extends App_Model_ModelAbstract
{
    public $idatividadecronograma = null;
    public $idprojetocronograma = null;
    public $idatividadepredecessora = null;

    public function toArray()
    {
        $retorno = array();
        $retorno['idatividadecronograma'] = $this->idatividadecronograma;
        $retorno['idprojetocronograma'] = $this->idprojetocronograma;
        $retorno['idatividadepredecessora'] = $this->idatividadepredecessora;

        return $retorno;
    }

}