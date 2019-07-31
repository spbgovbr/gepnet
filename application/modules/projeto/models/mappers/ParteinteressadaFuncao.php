<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Projeto_Model_Mapper_ParteinteressadaFuncao extends App_Model_Mapper_MapperAbstract
{
    public function getAll($interno = true)
    {
        $sql = "SELECT idparteinteressadafuncao,
                       nomfuncao
                  FROM agepnet200.tb_parteinteressadafuncao
                  " . (!$interno ? " WHERE idparteinteressadafuncao IN (5, 6) " : "") . "
                 ORDER BY numordem";

        return $this->_db->fetchAll($sql);
    }
}
