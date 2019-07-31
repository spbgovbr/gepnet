<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Projeto_Model_DbTable_Mudanca extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_mudanca';
    protected $_primary = array('idmudanca');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idcadastrador',
            'refColumns' => 'idpessoa'
        ),
        'Tipomudanca' => array(
            'refTableClass' => 'tb_tipomudanca',
            'columns' => 'idtipomudanca',
            'refColumns' => 'idtipomudanca'
        )
    );

}

