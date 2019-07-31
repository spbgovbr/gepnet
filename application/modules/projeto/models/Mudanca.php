<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Projeto_Model_Mudanca extends App_Model_ModelAbstract
{

    public $idmudanca = null;
    public $idprojeto = null;
    public $nomsolicitante = null;
    public $datsolicitacao = null;
    public $datdecisao = null;
    public $flaaprovada = null;
    public $desmudanca = null;
    public $desjustificativa = null;
    public $despareceregp = null;
    public $desaprovadores = null;
    public $despareceraprovadores = null;
    public $idcadastrador = null;
    public $idtipomudanca = null;
    public $datcadastro = null;
    public $dsmudanca = null;

    public function setDatsolicitacao($data)
    {
        if (!empty($data)) {
            $this->datsolicitacao = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }

    public function setDatdecisao($data)
    {
        if (!empty($data)) {
            $this->datdecisao = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }

    public function formPopulate()
    {
        //var_dump($this); exit;
        return array(
            'idmudanca' => $this->idmudanca,
            'idprojeto ' => $this->idprojeto,
            'nomsolicitante' => $this->nomsolicitante,
            'datsolicitacao' => $this->datsolicitacao->toString('d/m/Y'),
            'datdecisao' => !(empty($this->datdecisao)) ? $this->datdecisao->toString('d/m/Y') : '',
            'flaaprovada' => $this->flaaprovada,
            'desmudanca' => $this->desmudanca,
            'desjustificativa' => $this->desjustificativa,
            'despareceregp' => $this->despareceregp,
            'desjustificativa' => $this->desjustificativa,
            'desaprovadores' => $this->desaprovadores,
            'despareceraprovadores' => $this->despareceraprovadores,
            'idcadastrador' => $this->idcadastrador,
            'idtipomudanca' => $this->idtipomudanca,
            'datcadastro' => $this->datcadastro/*,
                'dsmudanca'             => $this->dsmudanca*/
        );

    }

}

