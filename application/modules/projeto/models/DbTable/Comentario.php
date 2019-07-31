<?php

/**
 * Created by PhpStorm.
 * User: Wendell
 * Date: 05/10/2018
 * Time: 12:03
 */

class Projeto_Model_DbTable_Comentario extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_comentario';
    protected $_primary = 'idcomentario';

    protected $_dependentTables = array(
        'Projeto_Model_DbTable_Gerencia',
        'Projeto_Model_DbTable_Atividadecronograma',
        'Default_Model_DbTable_Pessoa',
    );
    protected $_referenceMap = array(
        'Projeto' => array(
            'refTableClass' => 'tb_projeto',
            'columns' => 'idprojeto',
            'refColumns' => 'idprojeto',
            'onDelete' => self::CASCADE
        ),
        'Atividadecronograma' => array(
            'refTableClass' => 'tb_atividadecronograma',
            'columns' => array(
                'idelementodespesa',
                'idprojeto'
            ),
            'refColumns' => array(
                'idelementodespesa',
                'idprojeto'
            ),
            'onDelete' => self::CASCADE
        ),
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpessoa',
            'refColumns' => 'idpessoa',
            'onDelete' => self::CASCADE
        )
    );

}