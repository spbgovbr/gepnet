<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Evento_Model_Evento extends App_Model_ModelAbstract
{

    public $idevento = null;
    public $nomevento = null;
    public $desevento = null;
    public $desobs = null;
    public $idcadastrador = null;
    public $idresponsavel = null;
    public $datcadastro = null;
    public $datinicio = null;
    public $datfim = null;
    public $uf = null;

    public $nomresponsavel = null;


    public function setDatinicio($data)
    {
        if (!empty($data)) {
            $this->datinicio = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }

    public function setDatfim($data)
    {
        if (!empty($data)) {
            $this->datfim = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }

    public function formPopulate()
    {
        return array(
            'idevento' => $this->idevento,
            'nomevento' => $this->nomevento,
            'desevento' => $this->desevento,
            'desobs' => $this->desobs,
            'idcadastrador' => $this->idcadastrador,
            'idresponsavel' => $this->idresponsavel,
            'datcadastro' => $this->datcadastro,
            'datinicio' => $this->datinicio->toString('d/m/Y'),
            'datfim' => $this->datfim->toString('d/m/Y'),
            'uf' => $this->uf,
            'nomresponsavel' => $this->nomresponsavel

        );
    }

}

