<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Projeto_Model_Statusreport extends App_Model_ModelAbstract {

    public $idstatusreport = null;
    public $idprojeto = null;
    public $idprograma = null;
    public $datacompanhamento = null;
    public $numpercentualconcluido = null;
    public $numpercentualprevisto = null;
    public $desatividadeconcluida = null;
    public $desatividadeandamento = null;
    public $desmotivoatraso = null;
    public $desirregularidade = null;
    public $idmarco = null;
    public $datmarcotendencia = null;
    public $datfimprojetotendencia = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $domstatusprojeto = null;
    public $flaaprovado = null;
    public $domcorrisco = null;
    public $descontramedida = null;
    public $desrisco = null;
    public $descaminho = null;
    public $dataprovacao = null;
    public $nomstatusprojeto = null;
    public $nomproximomarco = null;
    public $nomrisco = null;
    
    
    public function retornaDescricaoStatusProjeto() { 
        switch($this->domstatusprojeto)
        {
            case 1:
                $retorno = 'Proposta';
                break;
            case 2:
                $retorno = 'Em Andamento';
                break;
            case 3:
                $retorno = 'Concluído';
                break;
            case 4:
                $retorno = 'Paralizado';
                break;
            case 5:
                $retorno = 'Cancelado';
                break;
            case 6:
                $retorno = 'Bloqueado';
                break;
            Default:
                $retorno = 'Proposta';
                break;
        }        
        return $retorno;
    }

    public function formPopulate()
    {
        return array(
            'idstatusreport'         => $this->idstatusreport,
            'idprojeto'              => $this->idprojeto,
            'idprograma'             => $this->idprograma,
            'datacompanhamento'      => $this->datacompanhamento->toString('d/m/Y'),
            'numpercentualconcluido' => $this->numpercentualconcluido,
            'numpercentualprevisto'  => $this->numpercentualprevisto,
            'desatividadeconcluida'  => $this->desatividadeconcluida,
            'desatividadeandamento'  => $this->desatividadeandamento,
            'desmotivoatraso'        => $this->desmotivoatraso,
            'desirregularidade'      => $this->desirregularidade,
            'idmarco'                => $this->idmarco,
            'datmarcotendencia'      => $this->datmarcotendencia->toString('d/m/Y'),
            'datfimprojetotendencia' => $this->datfimprojetotendencia->toString('d/m/Y'),
            'idcadastrador'          => $this->idcadastrador,
            'datcadastro'            => $this->datcadastro->toString('d/m/Y'),
            'domstatusprojeto'       => $this->domstatusprojeto,
            'flaaprovado'            => $this->flaaprovado,
            'domcorrisco'            => $this->domcorrisco,
            'descontramedida'        => $this->descontramedida,
            'desrisco'               => $this->desrisco,
            'dataprovacao'           => $this->dataprovacao,
            'descaminho'             => $this->descaminho,
        );
    }
    public function setDatacompanhamento($data)
    {
        $this->datacompanhamento = new Zend_Date($data, 'dd/MM/yyyy');
    }
    
    public function setDatfimprojetotendencia($data)
    {
        $this->datfimprojetotendencia = new Zend_Date($data, 'dd/MM/yyyy');
    }
    
    public function setDatcadastro($data)
    {
        $this->datcadastro = new Zend_Date($data, 'dd/MM/yyyy');
    }
    
    public function setDatmarcotendencia($data)
    {
        $this->datmarcotendencia = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function getUrlDocumento()
    {
        $d       = DIRECTORY_SEPARATOR;
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        return $baseUrl . $d . '..' . $d . 'arquivos' . $d . $this->descaminho;
    }
}