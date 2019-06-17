<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 08-07-2013
 * 13:45
 */
class Default_Model_DbTable_Permissaoperfil extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_permissaoperfil';
    protected $_primary = array(1 => 'idpermissaoperfil');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Perfil' => array(
            'refTableClass' => 'tb_perfil',
            'columns' => 'idperfil',
            'refColumns' => 'idperfil'
        ),
        'Permissao' => array(
            'refTableClass' => 'tb_permissao',
            'columns' => 'idpermissao',
            'refColumns' => 'idpermissao'
        )
    );

}

