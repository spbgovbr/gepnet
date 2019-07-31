<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Acordocooperacao_Model_DbTable_Acordoentidadeexterna extends Zend_Db_Table_Abstract
{

    protected $_name = 'agepnet200.tb_acordoentidadeexterna';
    protected $_primary = array(
        'idacordo',
        'identidadeexterna'
    );
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Acordo' => array(
            'refTableClass' => 'tb_acordo',
            'columns' => 'idacordo',
            'refColumns' => 'idacordo'
        ),
        'Entidadeexterna' => array(
            'refTableClass' => 'tb_entidadeexterna',
            'columns' => 'identidadeexterna',
            'refColumns' => 'identidadeexterna'
        )
    );

}

