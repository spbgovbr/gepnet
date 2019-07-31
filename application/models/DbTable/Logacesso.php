<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Default_Model_DbTable_Logacesso extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_logacesso';
    protected $_primary = array('idmodulo');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Perfilpessoa' => array(
            'refTableClass' => 'tb_perfilpessoa',
            'columns' => 'idperfilpessoa',
            'refColumns' => 'idperfilpessoa'
        )
    );

}

