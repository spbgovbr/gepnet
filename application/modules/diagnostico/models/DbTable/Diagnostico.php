<?php

/**
 * Newton Carlos
 * This class has generated based on the dbTable "" @ 30-10-2018
 * 15:54
 */
class Diagnostico_Model_DbTable_Diagnostico extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_diagnostico';
    protected $_primary = array('iddiagnostico');

    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idcadastrador',
            'refColumns' => 'idpessoa'
        )
    );

}

