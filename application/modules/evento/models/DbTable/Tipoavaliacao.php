<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Evento_Model_DbTable_Tipoavaliacao extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_tipoavaliacao';
    protected $_primary = array('idtipoavaliacao');
    protected $_dependentTables = array();

    protected $_referenceMap = array(
        'Eventoavaliacao' => array(
            'refTableClass' => 'tb_eventoavaliacao',
            'columns' => 'idtipoavaliacao',
            'refColumns' => 'domtipoavaliacao'
        )
    );

}

