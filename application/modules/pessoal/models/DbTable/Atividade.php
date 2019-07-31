<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Pessoal_Model_DbTable_Atividade extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_atividade';
    protected $_primary = array('idatividade');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idresponsavel',
            'refColumns' => 'idpessoa'
        ),
        'Escritorio' => array(
            'refTableClass' => 'tb_escritorio',
            'columns' => 'idescritorio',
            'refColumns' => 'idescritorio'
        )
    );

}

