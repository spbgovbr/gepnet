<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Projeto_Model_Parteinteressada extends App_Model_ModelAbstract
{

    const EDITAR = 1;
    const VISUALIZAR = 2;
    const GERENTE_PROJETO = 1;
    const GERENTE_ADJUNTO = 2;
    const DEMANDANTE = 3;
    const PATROCINADOR = 4;
    const PARTE_INTERESSADA = 5;
    const EQUIPE_PROJETO = 6;

    public $idparteinteressada = null;
    public $idprojeto = null;
    public $nomfuncao = null;
    public $destelefone = null;
    public $desemail = null;
    public $domnivelinfluencia = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $idpessoainterna = null;
    public $nomparteinteressada = null;
    public $observacao = null;
    public $tppermissao = null;
    public $idparteinteressadafuncao = null;
    public $status;

    /**
     * Trata os dados do form fora do padrão e popula o model. Ex: campotabelaexterno => campotabela
     * @return void
     */
    public function setParteInteressadaExterna($dataForm)
    {
        $arrData = array();
        foreach ($dataForm as $key => $value) {
            $strElement = str_replace('externo', '', $key);
            $arrData[$strElement] = $value;
        }
        $this->setFromArray($arrData);
    }

    public function formPopulate()
    {
        return array(
            'idparteinteressada' => $this->idparteinteressada,
            'idprojeto' => $this->idprojeto,
            'nomparteinteressada' => $this->nomparteinteressada,
            'nomfuncao' => $this->nomfuncao,
            'destelefone' => $this->destelefone,
            'desemail' => $this->desemail,
            'domnivelinfluencia' => $this->domnivelinfluencia,
            'idcadastrador' => $this->idcadastrador,
            'datcadastro' => $this->datcadastro,
            'idpessoainterna' => $this->idpessoainterna,
            'observacao' => $this->observacao,
            'tppermissao' => $this->tppermissao,
            'idparteinteressadafuncao' => $this->idparteinteressadafuncao,
        );
    }

}

