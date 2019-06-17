<?php

/**
 * Automatically generated data model
 *
 */
class Diagnostico_Model_SugestaoMelhoria extends App_Model_ModelAbstract
{

    public $idmelhoria = null;
    public $datmelhoria = null;
    public $idunidadeprincipal = null;
    public $matriculaproponente = null;
    public $desmelhoria = null;
    public $idmacroprocessotrabalho = null;
    public $idmacroprocessomelhorar = null;
    public $idunidaderesponsavelproposta = null;
    public $flaabrangencia = null;
    public $idunidaderesponsavelimplantacao = null;
    public $idobjetivoinstitucional = null;
    public $idacaoestrategica = null;
    public $idareamelhoria = null;
    public $idsituacao = null;
    public $iddiagnostico = null;

    public function formPopulate()
    {
        return array(
            'idmelhoria' => $this->idmelhoria,
            'datmelhoria' => $this->datmelhoria,
            'idunidadeprincipal' => $this->idunidadeprincipal,
            'matriculaproponente' => $this->matriculaproponente,
            'desmelhoria' => $this->desmelhoria,
            'idmacroprocessotrabalho' => $this->idmacroprocessotrabalho,
            'idmacroprocessomelhorar' => $this->idmacroprocessomelhorar,
            'idunidaderesponsavelproposta' => $this->idunidaderesponsavelproposta,
            'flaabrangencia' => $this->flaabrangencia,
            'idunidaderesponsavelimplantacao' => $this->idunidaderesponsavelimplantacao,
            'idobjetivoinstitucional' => $this->idobjetivoinstitucional,
            'idacaoestrategica' => $this->idacaoestrategica,
            'idareamelhoria' => $this->idareamelhoria,
            'idsituacao' => $this->idsituacao,
            'iddiagnostico' => $this->iddiagnostico,
        );
    }

}
