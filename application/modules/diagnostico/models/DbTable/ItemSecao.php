<?php

/**
 * Newton Carlos
 * This class has generated based on the dbTable "" @ 05-11-2018
 * 12:49
 */
class Diagnostico_Model_DbTable_ItemSecao extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_item_secao';
    protected $_primary = array('id_item');

    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Secao' => array(
            'refTableClass' => 'tb_secao',
            'columns' => 'id_secao',
            'refColumns' => 'id_secao'
        )
    );

}

