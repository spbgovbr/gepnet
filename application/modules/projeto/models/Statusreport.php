<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Projeto_Model_Statusreport extends App_Model_ModelAbstract
{

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
    public $nomdomcorrisco = null;
    public $descontramedida = null;
    public $desrisco = null;
    public $descaminho = null;
    public $dataprovacao = null;
    public $nomstatusprojeto = null;
    public $nomdomstatusprojeto = null;
    public $nomproximomarco = null;
    public $nomrisco = null;
    public $pgpassinado = null;
    public $tepassinado = null;
    public $desandamentoprojeto = null;
    public $diaatraso = null;
    public $numpercentualconcluidomarco = null;
    public $domcoratraso = null;
    public $numcriteriofarol = null;
    public $datfimprojeto = null;

    /**
     * Serviço de atividades
     * @var Projeto_Service_AtividadeCronograma
     */
    public $serviceATividadeCronograma;


    public function retornaDescricaoStatusProjeto()
    {
        switch ($this->domstatusprojeto) {
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
                $retorno = 'Paralisado';
                break;
            case 5:
                $retorno = 'Cancelado';
                break;
            case 6:
                $retorno = 'Bloqueado';
                break;
            case 8:
                $retorno = 'Excluído';
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
            'idstatusreport' => $this->idstatusreport,
            'idprojeto' => $this->idprojeto,
            'idprograma' => $this->idprograma,
            'datacompanhamento' => $this->datacompanhamento->toString('d/m/Y'),
            'numpercentualconcluido' => $this->numpercentualconcluido,
            'numpercentualprevisto' => $this->numpercentualprevisto,
            'desatividadeconcluida' => $this->desatividadeconcluida,
            'desatividadeandamento' => $this->desatividadeandamento,
            'desmotivoatraso' => $this->desmotivoatraso,
            'desirregularidade' => $this->desirregularidade,
            'idmarco' => $this->idmarco,
            'datmarcotendencia' => $this->datmarcotendencia->toString('d/m/Y'),
            'datfimprojetotendencia' => $this->datfimprojetotendencia->toString('d/m/Y'),
            'idcadastrador' => $this->idcadastrador,
            'datcadastro' => $this->datcadastro->toString('d/m/Y'),
            'domstatusprojeto' => $this->domstatusprojeto,
            'flaaprovado' => $this->flaaprovado,
            'domcorrisco' => $this->domcorrisco,
            'descontramedida' => $this->descontramedida,
            'desrisco' => $this->desrisco,
            'dataprovacao' => $this->dataprovacao,
            'descaminho' => $this->descaminho,
            'pgpassinado' => $this->pgpassinado,
            'tepassinado' => $this->tepassinado,
            'desandamentoprojeto' => $this->desandamentoprojeto,
            'diaatraso' => $this->diaatraso,
            'numpercentualconcluidomarco' => $this->numpercentualconcluidomarco,
            'domcoratraso' => $this->domcoratraso,
            'datfimprojeto' => $this->datfimprojeto ? $this->datfimprojeto->toString('d/m/Y') : $this->datfimprojeto,
            'numcriteriofarol' => $this->numcriteriofarol
        );
    }

    public function setPercentualConcluidoMarco($percentualMarco)
    {
        $this->numpercentualconcluidomarco = $percentualMarco;
    }

    public function setAtrasoProjeto($atraso)
    {
        $this->diaatraso = $atraso;
    }

    public function setNumeroCriterioFarol($numcriteriofarol)
    {
        $this->numcriteriofarol = $numcriteriofarol;
    }

    public function setDomCorAtraso($corAtraso)
    {
        $this->domcoratraso = $corAtraso;
    }

    public function setDatacompanhamento($data)
    {
        $this->datacompanhamento = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDatfimprojeto($data)
    {
        $this->datfimprojeto = new Zend_Date($data, 'dd/MM/yyyy');
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

    /**
     * @return string
     * @throws Zend_Exception
     */
    public function getUrlDocumento()
    {
        $config = Zend_Registry::get('config');
        $dir = $config->resources->cachemanager->default->backend->options->arquivos_dir;
        $path = $dir . $this->descaminho;
        $view = new Zend_View_Helper_Url();
        return $view->url(
            array(
                'arquivo' => base64_encode($path)
            ),
            'download'
        );
    }

    public function getFile($params)
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $arquivo = $service->retornaAnexo(array(
            'idprojeto' => $this->idprojeto,
            'idstatusreport' => $this->idstatusreport
        ), false, true);
        $linkArquivo = "";
        if ($arquivo) {
            $linkArquivo = '<a id="linkFile" href="' . $arquivo . '" title="Cronograma" target="_blank"><i class="icon-download-alt"></i></a>';
        }
        return $linkArquivo;
    }
}