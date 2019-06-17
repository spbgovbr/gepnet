<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Default_Model_DbTable_Perfilpessoa extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_perfilpessoa';
    protected $_primary = array('idperfilpessoa');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idperfilpessoa',
            'refColumns' => 'idpessoa'
        ),
        'Perfil' => array(
            'refTableClass' => 'tb_perfil',
            'columns' => 'idperfil',
            'refColumns' => 'idperfil'
        )
    );

}

