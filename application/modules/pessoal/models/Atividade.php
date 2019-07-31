<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Pessoal_Model_Atividade extends App_Model_ModelAbstract
{

    public $idatividade = null;
    public $nomatividade = null;
    public $desatividade = null;
    public $idcadastrador = null;
    public $idresponsavel = null;
    public $datcadastro = null;
    public $datatualizacao = null;
    public $datinicio = null;
    public $datfimmeta = null;
    public $datfimreal = null;
    public $flacontinua = null;
    public $numpercentualconcluido = null;
    public $flacancelada = null;
    public $idescritorio = null;

    /**
     * atributos para o form
     */
    public $nomcadastrador = null;
    public $nomresponsavel = null;
    public $nomescritorio = null;


    public function setDatcadastro($data)
    {
        if (!empty($data)) {
            $this->datcadastro = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }

    public function setDatatualizacao($data)
    {
        if (!empty($data)) {
            $this->datatualizacao = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }

    public function setDatinicio($data)
    {
        if (!empty($data)) {
            $this->datinicio = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }

    public function setDatfimmeta($data)
    {
        if (!empty($data)) {
            $this->datfimmeta = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }

    public function setDatfimreal($data)
    {
        if (!empty($data)) {
            $this->datfimreal = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }

    public function formPopulate()
    {
        return array(
            'idatividade' => $this->idatividade,
            'nomatividade' => $this->nomatividade,
            'desatividade' => $this->desatividade,
            'idcadastrador' => $this->idcadastrador,
            'idresponsavel' => $this->idresponsavel,
            'datcadastro' => $this->datcadastro->toString('d/m/Y'),
            'datatualizacao' => $this->datatualizacao->toString('d/m/Y'),
            'datinicio' => $this->datinicio->toString('d/m/Y'),
            'datfimmeta' => !(empty($this->datfimmeta)) ? $this->datfimmeta->toString('d/m/Y') : '',
            'datfimreal' => !(empty($this->datfimreal)) ? $this->datfimreal->toString('d/m/Y') : '',
            'flacontinua' => $this->flacontinua,
            'numpercentualconcluido' => $this->numpercentualconcluido,
            'flacancelada' => $this->flacancelada,
            'idescritorio' => $this->idescritorio,
            'nomcadastrador' => $this->nomcadastrador,
            'nomresponsavel' => $this->nomresponsavel,
            'nomescritorio' => $this->nomescritorio
        );
    }

}

