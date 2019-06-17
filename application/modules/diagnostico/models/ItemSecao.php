<?php

/**
 * Newton Carlos
 * This class has generated based on the dbTable "" @ 05-11-2018
 * 12:47
 */
class Diagnostico_Model_ItemSecao extends App_Model_ModelAbstract
{
    public $id_item = null;
    public $ds_item = null;
    public $id_secao = null;
    public $ativo = null;
    public $idquestionariodiagnostico = null;

    public function formPopulate()
    {
        return array(
            'id_item' => $this->id_item,
            'ds_item' => $this->ds_item,
            'id_secao' => $this->id_secao,
            'ativo' => $this->ativo,
            'idquestionariodiagnostico' => $this->idquestionariodiagnostico,
        );
    }

}
