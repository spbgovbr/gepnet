<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Processo_Model_Projetoprocesso extends App_Model_ModelAbstract
{

    public $idprojetoprocesso = null;
    public $idprocesso = null;
    public $numano = null;
    public $domsituacao = null;
    public $datsituacao = null;
    public $idresponsavel = null;
    public $nomresponsavel = null;
    public $desprojetoprocesso = null;
    public $datinicioprevisto = null;
    public $datterminoprevisto = null;
    public $vlrorcamento = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $nomprocesso = null;
    public $nomsetor = null;

    public function formPopulate()
    {
        return array(
            'idprojetoprocesso' => $this->idprojetoprocesso,
            'idprocesso' => $this->idprocesso,
            'numano' => $this->numano,
            'domsituacao' => $this->domsituacao,
            'datsituacao' => $this->datsituacao->toString('d/m/Y'),
            'idresponsavel' => $this->idresponsavel,
            'nomresponsavel' => $this->nomresponsavel,
            'desprojetoprocesso' => $this->desprojetoprocesso,
            'datinicioprevisto' => $this->datinicioprevisto->toString('d/m/Y'),
            'datterminoprevisto' => $this->datterminoprevisto->toString('d/m/Y'),
            'vlrorcamento' => $this->vlrorcamento,
            'idcadastrador' => $this->idcadastrador,
            'datcadastro' => $this->datcadastro,
        );
    }

    public function setDatsituacao($data)
    {
        $this->datsituacao = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDatinicioprevisto($data)
    {
        $this->datinicioprevisto = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDatterminoprevisto($data)
    {
        $this->datterminoprevisto = new Zend_Date($data, 'dd/MM/yyyy');
    }

}

