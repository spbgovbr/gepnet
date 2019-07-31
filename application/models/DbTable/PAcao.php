<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Default_Model_DbTable_PAcao extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_pacao';
    protected $_primary = array('id_p_acao');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idresponsavel',
            'refColumns' => 'idpessoa'
        ),
        'Projetoprocesso' => array(
            'refTableClass' => 'tb_projetoprocesso',
            'columns' => 'idprojetoprocesso',
            'refColumns' => 'idprojetoprocesso'
        ),
        'Setor' => array(
            'refTableClass' => 'tb_setor',
            'columns' => 'idsetorresponsavel',
            'refColumns' => 'idsetor'
        )
    );

}

