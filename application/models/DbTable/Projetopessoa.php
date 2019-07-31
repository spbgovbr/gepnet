<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Default_Model_DbTable_Projetopessoa extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_projetopessoa';
    protected $_primary = array(
        'idpessoa',
        'idprojeto'
    );
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpessoa',
            'refColumns' => 'idpessoa'
        )
    );

}

