<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Planejamento_Model_DbTable_Objetivo extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_objetivo';
    protected $_primary = array('idobjetivo');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Escritorio' => array(
            'refTableClass' => 'tb_escritorio',
            'columns' => 'codescritorio',
            'refColumns' => 'idescritorio'
        ),
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idcadastrador',
            'refColumns' => 'idpessoa'
        )
    );

}

