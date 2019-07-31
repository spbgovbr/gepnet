<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Default_Model_DbTable_Perfilmodulo extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_perfilmodulo';
    protected $_primary = array(
        'idperfil',
        'idmodulo'
    );
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Modulo' => array(
            'refTableClass' => 'tb_modulo',
            'columns' => 'idmodulo',
            'refColumns' => 'idmodulo'
        ),
        'Perfil' => array(
            'refTableClass' => 'tb_perfil',
            'columns' => 'idperfil',
            'refColumns' => 'idperfil'
        )
    );

}

