<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Processo_Model_Processo extends App_Model_ModelAbstract
{

    public $idprocesso = null;
    public $idprocessopai = null;
    public $nomcodigo = null;
    public $nomprocesso = null;
    public $idsetor = null;
    public $desprocesso = null;
    public $iddono = null;
    public $idexecutor = null;
    public $idgestor = null;
    public $idconsultor = null;
    public $numvalidade = null;
    public $datatualizacao = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $nomprocessopai = null;
    public $nomdono = null;
    public $nomgestor = null;
    public $nomexecutor = null;
    public $nomconsultor = null;

    public function setDatatualizacao($data)
    {
        if (!empty($data)) {
            $this->datatualizacao = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }

    public function formPopulate()
    {
        return array(
            'idprocesso' => $this->idprocesso,
            'idprocessopai' => $this->idprocessopai,
            'nomprocesso' => $this->nomprocesso,
            'nomcodigo' => $this->nomcodigo,
            'idsetor' => $this->idsetor,
            'desprocesso' => $this->desprocesso,
            'iddono' => $this->iddono,
            'idexecutor' => $this->idexecutor,
            'idgestor' => $this->idgestor,
            'idconsultor' => $this->idconsultor,
            'numvalidade' => $this->numvalidade,
            'datatualizacao' => $this->datatualizacao,
            'idcadastrador' => $this->idcadastrador,
            'nomdono' => $this->nomdono,
            'nomgestor' => $this->nomgestor,
            'nomexecutor' => $this->nomexecutor,
            'nomconsultor' => $this->nomconsultor,
        );
    }

}

