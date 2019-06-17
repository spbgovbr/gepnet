<?php

class Pesquisa_Model_DbTable_HistoricoPublicacao extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_hst_publicacao';
    protected $_primary = array('idhistoricopublicacao');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pesquisa' => array(
            'refTableClass' => 'tb_pesquisa',
            'columns' => 'idpesquisa',
            'refColumns' => 'idpesquisa'
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
    );

}

