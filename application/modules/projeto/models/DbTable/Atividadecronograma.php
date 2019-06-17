<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Projeto_Model_DbTable_Atividadecronograma extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_atividadecronograma';
    protected $_primary = array(
        'idatividadecronograma',
        'idprojeto'
    );
    protected $_dependentTables = array(
        'Projeto_Model_DbTable_Gerencia',
        'Projeto_Model_DbTable_Atividadecronograma'
    );
    protected $_referenceMap = array(
        'Projeto' => array(
            'refTableClass' => 'tb_projeto',
            'columns' => 'idprojeto',
            'refColumns' => 'idprojeto',
            'onDelete' => self::CASCADE
        ),
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idresponsavel',
            'refColumns' => 'idpessoa'
        ),
        'Elementodespesa' => array(
            'refTableClass' => 'tb_elementodespesa',
            'columns' => 'idelementodespesa',
            'refColumns' => 'idelementodespesa'
        ),
        'Atividadecronograma' => array(
            'refTableClass' => 'tb_atividadecronograma',
            'columns' => 'idgrupo',
            'refColumns' => 'idatividadecronograma',
            'onDelete' => self::CASCADE
        )
    );

}

