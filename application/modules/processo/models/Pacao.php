<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Processo_Model_PAcao extends App_Model_ModelAbstract
{

    public $id_p_acao = null;
    public $idprojetoprocesso = null;
    public $nom_p_acao = null;
    public $des_p_acao = null;
    public $datinicioprevisto = null;
    public $datinicioreal = null;
    public $datterminoprevisto = null;
    public $datterminoreal = null;
    public $idsetorresponsavel = '0';
    public $nomsetorresponsavel = null;
    public $flacancelada = null;
    public $idcadastrador = null;
    public $nomcadastrador = null;
    public $datcadastro = null;
    public $numseq = null;
    public $idresponsavel = null;
    public $nomresponsavel = null;

    public function getFlacancelada()
    {
        return $this->flacancelada;
    }

    public function setFlacancelada($flag)
    {
        $valores = array(1, 2);
        if (!in_array($flag, $valores)) {
            throw new Exception('Este model somente aceita os valores S ou N');
        }
        $this->flacancelada = $flag;
        return $this;
    }

    public function getDescricaoFlacancelada()
    {

        $valores = array(
            1 => 'Sim',
            2 => 'Não',
        );

        if (array_key_exists($this->flacancelada, $valores)) {
            return $valores[$this->flacancelada];
        }
        return 'Não informado.';
    }

    public function formPopulate()
    {
        return array(
            'id_p_acao' => $this->id_p_acao,
            'idprojetoprocesso' => $this->idprojetoprocesso,
            'nom_p_acao' => $this->nom_p_acao,
            'des_p_acao' => $this->des_p_acao,
            'datinicioprevisto' => $this->datinicioprevisto,
            'datinicioreal' => $this->datinicioreal,
            'datterminoprevisto' => $this->datterminoprevisto,
            'datterminoreal' => $this->datterminoreal,
            'idsetorresponsavel' => $this->idsetorresponsavel,
            'flacancelada' => $this->flacancelada,
            'idcadastrador' => $this->idcadastrador,
            'datcadastro' => $this->datcadastro,
            'numseq' => $this->numseq,
            'idresponsavel' => $this->idresponsavel,
            'nomresponsavel' => $this->nomresponsavel,
        );
    }

    public function setDatinicioprevisto($data)
    {
        $this->datinicioprevisto = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDatinicioreal($data)
    {
        $this->datinicioreal = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDatterminoprevisto($data)
    {
        $this->datterminoprevisto = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDatterminoreal($data)
    {
        $this->datterminoreal = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDatcadastro($data)
    {
        $this->datcadastro = new Zend_Date($data, 'dd/MM/yyyy');
    }

}

