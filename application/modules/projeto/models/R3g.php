<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:22
 */
class Projeto_Model_R3g extends App_Model_ModelAbstract
{

    public $idr3g = null;
    public $idprojeto = null;
    public $datdeteccao = null;
    public $domtipo = null;
    public $desplanejado = null;
    public $desrealizado = null;
    public $descausa = null;
    public $desconsequencia = null;
    public $descontramedida = null;
    public $datprazocontramedida = null;
    public $datprazocontramedidaatraso = null;
    public $domcorprazoprojeto = null;
    public $domstatuscontramedida = null;
    public $flacontramedidaefetiva = null;
    public $idcadastrador = null;
    public $datcadastro = null;
    public $desresponsavel = null;
    public $desobs = null;

    public function formPopulate()
    {

        //Zend_Debug::dump($this->datprazocontramedida);exit;
        $array = array(
            'idr3g' => $this->idr3g,
            'idprojeto' => $this->idprojeto,
            'datdeteccao' => $this->datdeteccao->toString('d/m/Y'),
            'domtipo' => $this->domtipo,
            'desplanejado' => $this->desplanejado,
            'desrealizado' => $this->desrealizado,
            'descausa' => $this->descausa,
            'desconsequencia' => $this->desconsequencia,
            'descontramedida' => $this->descontramedida,
            'datprazocontramedida' => null,
            'datprazocontramedidaatraso' => null,
            'domcorprazoprojeto' => $this->domcorprazoprojeto,
            'domstatuscontramedida' => $this->domstatuscontramedida,
            'flacontramedidaefetiva' => $this->flacontramedidaefetiva,
            'idcadastrador' => $this->idcadastrador,
            'datcadastro' => $this->datcadastro,
            'desresponsavel' => $this->desresponsavel,
            'desobs' => $this->desobs,
        );
        if ($this->datprazocontramedida != null) {
            $array['datprazocontramedida'] = $this->datprazocontramedida->toString('d/m/Y');
        }
        if ($this->datprazocontramedidaatraso != null) {
            $array['datprazocontramedidaatraso'] = $this->datprazocontramedidaatraso->toString('d/m/Y');
        }
        $array = array_filter($array);
        return $array;
    }


    public function setDatdeteccao($data)
    {
        $this->datdeteccao = new Zend_Date($data, 'dd/MM/yyyy');
    }

    public function setDatprazocontramedida($data)
    {
        if (!empty($data)) {
            $this->datprazocontramedida = new Zend_Date($data, 'dd/MM/yyyy');
        }
    }

    public function setDatprazocontramedidaatraso($data)
    {
        if (!empty($data)) {
            $this->datprazocontramedidaatraso = new Zend_Date($data, 'dd/MM/yyyy');
        }
    }



//    public function setDatcadastro($data)
//    {
//        $this->datcadastro = new Zend_Date($data, 'dd/MM/yyyy');
//    }
}

