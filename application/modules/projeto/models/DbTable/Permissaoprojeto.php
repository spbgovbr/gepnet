<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 28-06-2013
 * 10:07
 */
class Projeto_Model_DbTable_Permissaoprojeto extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_permissaoprojeto';
    protected $_primary = array('idpermissao', 'idprojeto', 'idparteinteressada');
    protected $_dependentTables = array();
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
        'Parteinteressada' => array(
            'refTableClass' => 'tb_parteinteressada',
            'columns' => 'idparteinteressada',
            'refColumns' => 'idparteinteressada'
        ),
        'Projeto' => array(
            'refTableClass' => 'tb_projeto',
            'columns' => 'idprojeto',
            'refColumns' => 'idprojeto'
        ),
        'Recurso' => array(
            'refTableClass' => 'tb_recurso',
            'columns' => 'idrecurso',
            'refColumns' => 'idrecurso'
        )
    );

}

