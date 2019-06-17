<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 30-10-2018 16:11
 */
class Diagnostico_Model_Diagnostico extends App_Model_ModelAbstract
{

    public $iddiagnostico = null;
    public $dsdiagnostico = null;
    public $idunidadeprincipal = null;
    public $dtinicio = null;
    public $dtencerramento = null;
    public $idcadastrador = null;
    public $dtcadastro = null;
    public $ativo = null;
    public $unidadeprincipal = null;
    public $unidadesvinculadas = null;
    public $idchefedaunidade = null;
    public $chefedaunidade = null;
    public $idpontofocal = null;
    public $pontofocal = null;
    public $pessoasequipe = null;
    public $idunidade = null;
    public $idequipe = null;
    public $ano = null;
    public $sq_diagnostico = null;


    public function setDtinicio($data)
    {
        $this->dtinicio = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDtencerramento($data)
    {
        $this->dtencerramento = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function formPopulate()
    {
        return array(
            'iddiagnostico' => $this->iddiagnostico,
            'dsdiagnostico' => $this->dsdiagnostico,
            'idunidadeprincipal' => $this->idunidadeprincipal,
            'dtinicio' => $this->dtinicio->toString('d/m/Y'),
            'dtencerramento' => $this->dtencerramento->toString('d/m/Y'),
            'idcadastrador' => $this->idcadastrador,
            'dtcadastro' => $this->dtcadastro,
            'unidadeprincipal' => $this->unidadeprincipal,
            'unidadesvinculadas' => $this->unidadesvinculadas,
            'idchefedaunidade' => $this->idchefedaunidade,
            'chefedaunidade' => $this->chefedaunidade,
            'idpontofocal' => $this->idpontofocal,
            'pontofocal' => $this->pontofocal,
            'pessoasequipe' => $this->pessoasequipe,
            'idunidade' => $this->idunidade,
            'idequipe' => $this->idequipe,
            'ano' => $this->ano,
            'sq_diagnostico' => $this->sq_diagnostico,
        );
    }

}
