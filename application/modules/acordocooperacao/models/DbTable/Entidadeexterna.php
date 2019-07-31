<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Acordocooperacao_Model_DbTable_Entidadeexterna extends Zend_Db_Table_Abstract
{


    protected $_schema = 'agepnet200';
    protected $_name = 'tb_entidadeexterna';
    protected $_primary = array('identidadeexterna');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idcadastrador',
            'refColumns' => 'idpessoa'
        )
    );

}