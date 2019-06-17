<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Acordocooperacao_Model_Acordo extends App_Model_ModelAbstract
{

    public $idacordo = 0;
    public $idacordopai = 0;
    public $idtipoacordo = 1;
    public $nomacordo = null;
    public $idresponsavelinterno = 0;
    public $destelefoneresponsavelinterno = null;
    public $idsetor = 0;
    public $idfiscal = 0;
    public $destelefonefiscal = null;
    public $despalavrachave = null;
    public $desobjeto = null;
    public $desobservacao = null;
    public $datassinatura = null;
    public $datiniciovigencia = null;
    public $datfimvigencia = null;
    public $numprazovigencia = 0;
    public $datatualizacao = null;
    public $datcadastro = null;
    public $idcadastrador = 0;
    public $flarescindido = 'N';
    public $flasituacaoatual = 2;
    public $numsiapro = null;
    public $descontatoexterno = null;
    public $idfiscal2 = 0;
    public $idfiscal3 = 0;
    public $idacordoespecieinstrumento = 0;
    public $datpublicacao = null;
    public $descargofiscal = null;
    public $descaminho = null;
    public $nomfiscal2 = null;
    public $nomfiscal3 = null;
    public $nomsetor = null;
    public $instrumentoprincipal = null;
    public $responsavelinterno = null;
    public $nomfiscal = null;

    public function formPopulate()
    {
        return array(
            'idacordo' => $this->idacordo,
            'idacordopai' => $this->idacordopai,
            'idtipoacordo' => $this->idtipoacordo,
            'nomacordo' => $this->nomacordo,
            'idresponsavelinterno' => $this->idresponsavelinterno,
            'destelefoneresponsavelinterno' => $this->destelefoneresponsavelinterno,
            'idsetor' => $this->idsetor,
            'idfiscal' => $this->idfiscal,
            'destelefonefiscal' => $this->destelefonefiscal,
            'despalavrachave' => $this->despalavrachave,
            'desobjeto' => $this->desobjeto,
            'desobservacao' => $this->desobservacao,
            'datassinatura' => $this->datassinatura->toString('d/m/Y'),
            'datiniciovigencia' => $this->datiniciovigencia->toString('d/m/Y'),
            'datfimvigencia' => $this->datfimvigencia->toString('d/m/Y'),
            //'datatualizacao'                => $this->datatualizacao->toString('d/m/Y'),
//            'datcadastro'                   => $this->datcadastro->toString('d/m/Y'),
            'datpublicacao' => $this->datpublicacao->toString('d/m/Y'),
            'numprazovigencia' => $this->numprazovigencia,
            'idcadastrador' => $this->idcadastrador,
            'flarescindido' => $this->flarescindido,
            'flasituacaoatual' => $this->flasituacaoatual,
            'numsiapro' => $this->numsiapro,
            'descontatoexterno' => $this->descontatoexterno,
            'idfiscal2' => $this->idfiscal2,
            'idfiscal3' => $this->idfiscal3,
            'descargofiscal' => $this->descargofiscal,
        );
    }


    public function setDatassinatura($data)
    {
//        Zend_Debug::dump(empty($data)); exit;
        if (empty($data) == false) {
            $this->datassinatura = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }

    public function setDatiniciovigencia($data)
    {
        if (empty($data) == false) {
            $this->datiniciovigencia = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }

    public function setDatfimvigencia($data)
    {
        if (empty($data) == false) {
            $this->datfimvigencia = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }
//    public function setDatatualizacao($data)
//    {
//        $this->datatualizacao = new Zend_Date($data, 'dd/MM/yyyy');
//    }
//    public function setDatcadastro($data)
//    {
//        $this->datcadastro = new Zend_Date($data, 'dd/MM/yyyy');
//    }
    public function setDatpublicacao($data)
    {
        if (empty($data) == false) {
            $this->datpublicacao = new Zend_Date($data, 'dd/MM/yyyy');
        }
        return $this;
    }
}

