<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Planejamento_Model_DbTable_Portfolioprograma extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_portifolioprograma';
    protected $_primary = array('idportfolio');
    protected $_referenceMap = array(
        'Programa' => array(
            'refTableClass' => 'tb_programa',
            'columns' => 'idprograma',
            'refColumns' => 'idprograma'
        ),
        'Portfolio' => array(
            'refTableClass' => 'tb_portfolio',
            'columns' => 'idportfolio',
            'refColumns' => 'idportfolio'
        )
    );

}

