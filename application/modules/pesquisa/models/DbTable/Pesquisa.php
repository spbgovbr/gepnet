<?php

class Pesquisa_Model_DbTable_Pesquisa extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_pesquisa';
    protected $_primary = array('idpesquisa');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idcadastrador',
            'refColumns' => 'idpessoa'
        ),
        'Publica' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpespublica',
            'refColumns' => 'idpessoa'
        ),
        'Encerra' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpesencerra',
            'refColumns' => 'idpessoa'
        ),
        'Escritorio' => array(
            'refTableClass' => 'tb_questionario',
            'columns' => 'idquestionario',
            'refColumns' => 'idquestionario'
        )
    );

}

