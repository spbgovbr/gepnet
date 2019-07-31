<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Agenda_Model_Agenda extends App_Model_ModelAbstract
{

    public $idagenda = null;
    public $desassunto = null;
    public $datagenda = null;
    public $desagenda = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $hragendada = null;
    public $deslocal = null;
    public $flaenviaemail = null;
    public $idescritorio = null;

    //Join Pessoaagenda
    public $participantes = null;

    //Join escritorio
    public $nomescritorio = null;

    //Join Pessoa
    public $nomcadastrador = null;

    public function formPopulate()
    {
        return array(
            'idagenda' => $this->idagenda,
            'desassunto' => $this->desassunto,
            'datagenda' => $this->datagenda->toString('d/m/Y'),
            'desagenda' => $this->desagenda,
            'datcadastro' => $this->datcadastro,
//            'hragendada'    => $this->hragendada,
            'deslocal' => $this->deslocal,
            'flaenviaemail' => $this->flaenviaemail,
            'idescritorio' => $this->idescritorio,
            'nomescritorio' => $this->nomescritorio,
            'nomcadastrador' => $this->nomcadastrador,
        );

    }

    public function setDatagenda($data)
    {
        $this->datagenda = new Zend_Date($data, 'dd/MM/yyyy');
    }

//    public function setHragendada($data)
//    {
//        $this->datagenda = new Zend_Date($data, 'H:i');
//    }

}

