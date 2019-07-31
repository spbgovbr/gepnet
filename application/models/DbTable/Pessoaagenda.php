<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Default_Model_DbTable_Pessoaagenda extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_pessoaagenda';
    protected $_primary = array(
        'idagenda',
        'idpessoa'
    );
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Agenda' => array(
            'refTableClass' => 'tb_agenda',
            'columns' => 'idagenda',
            'refColumns' => 'idagenda'
        ),
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpessoa',
            'refColumns' => 'idpessoa'
        )
    );

}

