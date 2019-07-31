<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 30-10-2018 16:11
 */
class Diagnostico_Model_Partediagnostico extends App_Model_ModelAbstract
{
    const CHEFE_DA_UNIDADE_DIAGNOSTICADA = 1;
    const PONTO_FOCAL_UNIDADE_DIAGNOSTICADA = 2;
    const EQUIPE_DE_DIAGNOSTICO = 3;
    const EDITAR = 1;
    const VISUALIZAR = 2;

    public $idpartediagnostico = null;
    public $iddiagnostico = null;
    public $qualificacao = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $idpessoa = null;
    public $tppermissao = null;

    public function toArray()
    {
        $retorno = get_object_vars($this);
        $retorno['idpartediagnostico'] = (int)$this->idpartediagnostico;
        $retorno['iddiagnostico'] = (int)$this->iddiagnostico;
        $retorno['idpessoa'] = (int)$this->idpessoa;
        $retorno['tppermissao'] = $this->tppermissao;
        $retorno['qualificacao'] = (int)$this->qualificacao;
        return $retorno;
    }

    public function formPopulate()
    {
        return array(
            'idunidadeprincipal' => (int)$this->idpartediagnostico,
            'iddiagnostico' => (int)$this->iddiagnostico,
            'qualificacao' => (int)$this->qualificacao,
            'idcadastrador' => (int)$this->idcadastrador,
            'datcadastro' => $this->datcadastro,
            'idpessoa' => (int)$this->idpessoa,
            'tppermissao' => $this->tppermissao,
        );
    }

}
