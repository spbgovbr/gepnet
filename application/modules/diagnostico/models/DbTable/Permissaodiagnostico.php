<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 20-11-2018
 * 10:07
 */
class Diagnostico_Model_DbTable_Permissaodiagnostico extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_permissaodiagnostico';
    protected $_primary = array('idpermissao', 'iddiagnostico', 'idpartediagnostico');
    protected $_dependentTables = array(
        'Diagnostico_Model_DbTable_Diagnostico',
        'Default_Model_DbTable_Permissao',
        'Default_Model_DbTable_Recurso',
        'Default_Model_DbTable_Pessoa',
    );

    protected $_referenceMap = array(
        'Permissao' => array(
            'refTableClass' => 'tb_permissao',
            'columns' => 'idpermissao',
            'refColumns' => 'idpermissao'
        ),
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpessoa',
            'refColumns' => 'idpessoa'
        ),
        'Partediagnostico' => array(
            'refTableClass' => 'tb_partediagnostico',
            'columns' => 'idpartediagnostico',
            'refColumns' => 'idpartediagnostico'
        ),
        'Diagnostico' => array(
            'refTableClass' => 'tb_diagnostico',
            'columns' => 'iddiagnostico',
            'refColumns' => 'iddiagnostico'
        ),
        'Recurso' => array(
            'refTableClass' => 'tb_recurso',
            'columns' => 'idrecurso',
            'refColumns' => 'idrecurso'
        )
    );

}

