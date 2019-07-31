<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Default_Model_DbTable_Escritorio extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_escritorio';
    protected $_primary = array('idescritorio');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idresponsavel2',
            'refColumns' => 'idpessoa'
        ),
        'Escritorio' => array(
            'refTableClass' => 'tb_escritorio',
            'columns' => 'idescritoriope',
            'refColumns' => 'idescritorio'
        )
    );

}

