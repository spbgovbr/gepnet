<?php

class Pesquisa_Model_DbTable_QuestionarioPesquisa extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_questionario_pesquisa';
    protected $_primary = array('idquestionariopesquisa');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Escritorio' => array(
            'refTableClass' => 'tb_escritorio',
            'columns' => 'idescritorio',
            'refColumns' => 'idescritorio'
        ),
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idcadastrador',
            'refColumns' => 'idpessoa'
        ),
        'Pesquisa' => array(
            'refTableClass' => 'tb_pesquisa',
            'columns' => 'idpesquisa',
            'refColumns' => 'idpesquisa'
        )
    );

}

