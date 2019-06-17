<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Projeto_Model_DbTable_AtividadeCronoPredecessora extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_atividadecronopredecessora';
    protected $_sequence = false;
    protected $_primary = array(
        'idatividadecronograma',
        'idprojetocronograma',
        'idatividadepredecessora',
    );
    protected $_dependentTables = array(
        'Projeto_Model_DbTable_Atividadecronograma'
    );

    protected $_referenceMap = array(
        'AtividadeCronograma' => array(
            'refTableClass' => 'tb_atividadecronograma',
            'columns' => array(
                'idatividadecronograma',
                'idprojetocronograma'
            ),
            'refColumns' => array(
                'idatividadecronograma',
                'idprojeto'
            ),
            'onDelete' => self::CASCADE
        ),
        'AtividadePredecessora' => array(
            'refTableClass' => 'tb_atividadecronograma',
            'columns' => array(
                'idatividadepredecessora',
                'idprojetocronograma'
            ),
            'refColumns' => array(
                'idatividadecronograma',
                'idprojeto'
            ),
            'onDelete' => self::CASCADE
        ),
    );

}

