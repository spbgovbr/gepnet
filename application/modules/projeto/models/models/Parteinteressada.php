<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Projeto_Model_Parteinteressada extends App_Model_ModelAbstract {

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
    
    /**
     * Trata os dados do form fora do padrão e popula o model. Ex: campotabelaexterno => campotabela     
     * @return void
     */
    public function setParteInteressadaExterna($dataForm) 
    {
        $arrData = array();
        foreach ( $dataForm as $key => $value ) {
            $strElement = str_replace('externo', '', $key);
            $arrData[$strElement] = $value;
        }
        $this->setFromArray($arrData);
    }

    public function formPopulate() {
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
            'observacao' => $this->observacao
        );
    }

}

