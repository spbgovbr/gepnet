<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Default_Model_DbTable_Projetoprocesso extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_projetoprocesso';
    protected $_primary = array('idprojetoprocesso');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idresponsavel',
            'refColumns' => 'idpessoa'
        ),
        'Processo' => array(
            'refTableClass' => 'tb_processo',
            'columns' => 'idprocesso',
            'refColumns' => 'idprocesso'
        )
    );

}

