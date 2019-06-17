<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Projeto_Model_DbTable_Licao extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_licao';
    protected $_primary = array('idlicao');

    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Projeto' => array(
            'refTableClass' => 'tb_projeto',
            'columns' => 'idprojeto',
            'refColumns' => 'idprojeto'
        ),
        'Atividadecronograma' => array(
            'refTableClass' => 'tb_atividadecronograma',
            'columns' => 'identrega',
            'refColumns' => 'idatividadecronograma'
        )
    );

}

