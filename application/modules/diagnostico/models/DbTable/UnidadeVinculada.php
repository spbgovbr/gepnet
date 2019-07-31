<?php

/**
 * Newton Carlos
 * This class has generated based on the dbTable "" @ 14-11-2018
 * 16:12
 */
class Diagnostico_Model_DbTable_UnidadeVinculada extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_unidade_vinculada';
    protected $_primary = array('idunidade', 'id_unidadeprincipal', 'iddiagnostico');

    protected $_dependentTables = array();
    protected $_referenceMap = array();

}
