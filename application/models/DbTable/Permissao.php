<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 28-06-2013
 * 10:07
 */
class Default_Model_DbTable_Permissao extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_permissao';
    protected $_primary = array(1 => 'idpermissao');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Recurso' => array(
            'refTableClass' => 'tb_recurso',
            'columns' => 'idrecurso',
            'refColumns' => 'idrecurso'
        )
    );

}

