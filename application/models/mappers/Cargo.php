<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 14-05-2013
 * 18:02
 */
class Default_Model_Mapper_Cargo extends App_Model_Mapper_MapperAbstract
{
    public function fetchPairs()
    {
        $sql = "SELECT distinct sigla
                FROM vw_rh_cargo order by 1 asc ";
        return $this->_db->fetchAll($sql);
    }
}

