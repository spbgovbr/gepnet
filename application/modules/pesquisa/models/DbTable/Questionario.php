<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Pesquisa_Model_DbTable_Questionario extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_questionario';
    protected $_primary = array('idquestionario');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Escritorio' => array(
            'refTableClass' => 'tb_escritorio',
            'columns' => 'idescritorio',
            'refColumns' => 'idescritorio'
        ),
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idcadastrador',
            'refColumns' => 'idpessoa'
        )
    );

}

