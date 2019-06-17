<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Evento_Model_Mapper_Tipoavaliacao extends App_Model_Mapper_MapperAbstract
{

    public $idtipoavaliacao = null;
    public $noavaliacao = null;


    public function fetchPairs()
    {
        $sql = "SELECT 
                      idtipoavaliacao,
                      noavaliacao
                 FROM 
                    agepnet200.tb_tipoavaliacao";

        return $this->_db->fetchPairs($sql);
    }
}

