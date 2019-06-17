<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */

class Projeto_Model_DbTable_Atividadeocultar extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'agepnet200.tb_atividadeocultar';
    protected $_sequence = false;
    protected $_primary = array(
        'idprojeto',
        'idatividadecronograma',
        'idpessoa'
    );
    protected $_dependentTables = array('Projeto_Model_DbTable_Atividadecronograma');
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpessoa',
            'refColumns' => 'idpessoa'
        ),
        'Atividadecronograma' => array(
            'refTableClass' => 'tb_atividadecronograma',
            'columns' => array('idatividadecronograma', 'idprojeto'),
            'refColumns' => array('idatividadecronograma', 'idprojeto')
        )
    );
}

