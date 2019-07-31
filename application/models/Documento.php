<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Default_Model_Documento extends App_Model_ModelAbstract
{

    public $iddocumento = null;
    public $idescritorio = null;
    public $nomdocumento = null;
    public $idtipodocumento = null;
    public $descaminho = null;
    public $datdocumento = null;
    public $desobs = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $flaativo = null;
    public $tipodocumento = null;

    public function setDatcadastro($datcadastro)
    {
        $this->datcadastro = new Zend_Date($datcadastro, 'dd/MM/yyyy');
        return $this;
    }

    public function setDatdocumento($datdocumento)
    {
        $this->datdocumento = new Zend_Date($datdocumento, 'dd/MM/yyyy');
        return $this;
    }

    public function getFlaativo()
    {
        return $this->flaativo;
    }

    public function setFlaativo($flaativo)
    {
        $valores = array('S', 'N');
        if (!in_array($flaativo, $valores)) {
            throw new Exception('Este model somente aceita os valores S ou N');
        }
        $this->flaativo = $flaativo;
        return $this;
    }

    /**
     * Retorna verdadeiro ou falso para a utilização da agenda.
     * @return booelan
     */
    public function isAtivo()
    {
        return ($this->flaativo == 'S') ? true : false;
    }

    public function getDescricaoFlaativo()
    {
        $valores = array(
            'S' => 'Sim',
            'N' => 'Não',
        );

        if (array_key_exists($this->flaativo, $valores)) {
            return $valores[$this->flaativo];
        }
        return 'Não informado.';
    }

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

    public function formPopulate()
    {
        return array(
            'iddocumento' => $this->iddocumento,
            'idescritorio' => $this->idescritorio,
            'nomdocumento' => $this->nomdocumento,
            'idtipodocumento' => $this->idtipodocumento,
            'descaminho' => $this->descaminho,
            'datdocumento' => $this->datdocumento->toString('d/m/Y'),
            'desobs' => $this->desobs,
            'idcadastrador' => $this->idcadastrador,
            'datcadastro' => $this->datcadastro,
            'flaativo' => $this->flaativo,
        );
    }

}

