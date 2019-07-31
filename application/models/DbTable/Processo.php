<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Default_Model_DbTable_Processo extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_processo';
    protected $_primary = array('idprocesso');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idgestor',
            'refColumns' => 'idpessoa'
        ),
        'Processo' => array(
            'refTableClass' => 'tb_processo',
            'columns' => 'idprocessopai',
            'refColumns' => 'idprocesso'
        ),
        'Setor' => array(
            'refTableClass' => 'tb_setor',
            'columns' => 'idsetor',
            'refColumns' => 'idsetor'
        )
    );

}

