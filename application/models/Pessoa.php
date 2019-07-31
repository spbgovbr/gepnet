<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Default_Model_Pessoa extends App_Model_ModelAbstract
{

    public $idpessoa      = null;
    public $nompessoa     = null;
    public $numcpf        = null;
    public $token         = null;
    public $desobs        = null;
    public $numfone       = null;
    public $numcelular    = null;
    public $desemail      = null;
    public $domcargo      = null;
    public $idcadastrador = null;

    /**
     *
     * @var Zend_Date 
     */
    public $datcadastro  = null;
    public $lotacao      = null;
    public $id_servidor  = null;
    public $nummatricula = null;
    public $desfuncao    = null;
    public $flaagenda    = 'S';
    public $unidade      = null;

    public function getDatcadastro()
    {
        return $this->datcadastro;
    }

    public function setDatcadastro($datcadastro)
    {
        $this->datcadastro = new Zend_Date($datcadastro, 'dd/MM/yyyy');
        return $this;
    }
    function getToken() {
        return $this->token;
    }

    function setToken($token) {
        $this->token = md5($token);
    }

        /**
     * Retorna verdadeiro ou falso para a utilização da agenda.
     * @return booelan
     */
    public function temAgenda()
    {
        return ($this->flaagenda == 'S') ? true : false;
    }

    public function getFlaagenda()
    {
        return $this->flaagenda;
    }

    public function setFlaagenda($flaagenda)
    {
        $valores = array('S', 'N');
        if ( !in_array($flaagenda, $valores) ) {
            throw new Exception('Este model somente aceita os valores S ou N');
        }
        $this->flaagenda = $flaagenda;
        return $this;
    }

    public function getDescricaoFlaagenda()
    {
        $valores = array(
            'S' => 'Sim',
            'N' => 'Não',
        );

        if ( array_key_exists($this->flaagenda, $valores) ) {
            return $valores[$this->flaagenda];
        }
        return 'Não informado.';
    }

    public function getNumcpfMascarado()
    {
        return (string) new App_Mask_Cpf($this->numcpf);
    }

    public function getNumfoneMascarado()
    {
        return (string) new App_Mask_TelefoneFixo($this->numfone);
    }

    public function getNumcelularMascarado()
    {
        return (string) new App_Mask_TelefoneCelular($this->numcelular);
    }

    public function getDescricaoDomcargo()
    {
        $valores = array(
            /*
              "ADM" => "AGENTE ADMINISTRATIVO",
              "APF" => "AGENTE DE POLICIA FEDERAL",
              "DPF" => "DELEGADO DE POLICIA FEDERAL",
              "EPF" => "ESCRIVAO DE POLICIA FEDERAL",
              "PCF" => "PERITO CRIMINAL FEDERAL",
              "PPF" => "PAPILOSCOPISTA POLICIAL FEDERAL",
             * 
             */
            "COL" => "COLABORADOR",
        );


        if ( !array_key_exists($this->domcargo, $valores) ) {
            return $this->domcargo;
        }
        return "COLABORADOR";
    }

    public function isColaborador()
    {
        return ($this->domcargo == 'COL') ? true : false;
    }

    public function formPopulate()
    {
        return array(
            'idpessoa'     => $this->idpessoa,
            'nompessoa'    => $this->nompessoa,
            'numcpf'       => $this->getNumcpfMascarado(),
            'desobs'       => $this->desobs,
            'numfone'      => $this->getNumfoneMascarado(),
            'numcelular'   => $this->getNumcelularMascarado(),
            'desemail'     => $this->desemail,
            'domcargo'     => $this->domcargo,
            'lotacao'      => $this->lotacao,
            'id_servidor'  => $this->id_servidor,
            'nummatricula' => $this->nummatricula,
            'desfuncao'    => $this->desfuncao,
            'flaagenda'    => $this->flaagenda,
            'token'        => $this->getToken(),
        );
    }

}

