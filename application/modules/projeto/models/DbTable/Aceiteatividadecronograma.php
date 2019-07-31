<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Projeto_Model_DbTable_Aceiteatividadecronograma extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_aceiteatividadecronograma';
    protected $_primary = array('idaceiteativcronograma');

    protected $_dependentTables = array('');
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpesaceitou',
            'refColumns' => 'idpessoa'
        ),
        'Aceite' => array(
            'refTableClass' => 'tb_aceite',
            'columns' => 'idaceite',
            'refColumns' => 'idaceite'
        ),
        'Atividadecronograma' => array(
            'refTableClass' => 'tb_atividadecronograma',
            'columns' => array('identrega', 'idprojeto'),
            'refColumns' => array('idatividadecronograma', 'idprojeto')
        )
    );

}

