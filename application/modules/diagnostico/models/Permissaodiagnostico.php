<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 20-11-2018 10:07
 */
class Diagnostico_Model_Permissaodiagnostico extends App_Model_ModelAbstract
{
    const ATIVO = 'S';
    const INATIVO = 'N';

    public $idpartediagnostico = null;
    public $iddiagnostico = null;
    public $idrecurso = null;
    public $idpermissao = null;
    public $idpessoa = null;
    public $data = null;
    public $ativo = null;

    public function setdata($data)
    {
        $this->data = @DateTime::createFromFormat('d/m/Y', $data);
        return $this;
    }

    public function getData()
    {
        return $this->data->format('d/m/Y');
    }

    public function toArray()
    {
        $retorno = get_object_vars($this);
        $retorno['idpartediagnostico'] = $this->idpartediagnostico;
        $retorno['iddiagnostico'] = $this->iddiagnostico;
        $retorno['idpermissao'] = $this->idpermissao;
        $retorno['idrecurso'] = $this->idrecurso;
        $retorno['ativo'] = $this->ativo;
        $retorno['idpessoa'] = $this->idpessoa;
        $retorno['data'] = $this->getData();
    }
}

