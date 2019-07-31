<?php

/**
 * Newton Carlos
 * This class has generated based on the dbTable "" @ 14-11-2018
 * 16:12
 */
class Diagnostico_Model_DbTable_Partediagnostico extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_partediagnostico';
    protected $_primary = array('idpartediagnostico');

    protected $_dependentTables = array(
        'Diagnostico_Model_DbTable_Diagnostico',
        'Default_Model_DbTable_Pessoa',
    );
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpessoa',
            'refColumns' => array('idpessoa'),
            'onDelete' => self::CASCADE
        ),
        'Diagnostico' => array(
            'refTableClass' => 'tb_diagnostico',
            'columns' => 'iddiagnostico',
            'refColumns' => 'iddiagnostico'
        ),

    );

}
