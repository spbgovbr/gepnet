<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Default_Model_DbTable_Acordo extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_acordo';
    protected $_primary = array('idacordo');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Acordo' => array(
            'refTableClass' => 'tb_acordo',
            'columns' => 'idacordopai',
            'refColumns' => 'idacordo'
        ),
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idresponsavelinterno',
            'refColumns' => 'idpessoa'
        ),
        'Setor' => array(
            'refTableClass' => 'tb_setor',
            'columns' => 'idsetor',
            'refColumns' => 'idsetor'
        ),
        'Tipoacordo' => array(
            'refTableClass' => 'tb_tipoacordo',
            'columns' => 'idtipoacordo',
            'refColumns' => 'idtipoacordo'
        )
    );

}

