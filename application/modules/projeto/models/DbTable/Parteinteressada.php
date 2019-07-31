<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Projeto_Model_DbTable_Parteinteressada extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_parteinteressada';
    protected $_primary = array('idparteinteressada');
    protected $_dependentTables = array(
        'Default_Model_DbTable_Pessoa',
        'Projeto_Model_DbTable_Gerencia'
    );
    protected $_referenceMap = array(
        'PessoaCadastra' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idcadastrador',
            'refColumns' => 'idpessoa'
        ),
        'PessoaInterna' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpessoainterna',
            'refColumns' => 'idpessoa'
        ),
        'Projeto' => array(
            'refTableClass' => 'tb_projeto',
            'columns' => 'idprojeto',
            'refColumns' => 'idprojeto'
        ),
    );


}

