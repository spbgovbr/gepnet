<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Projeto_Model_DbTable_Gerencia extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_projeto';
    protected $_primary = array('idprojeto');
    protected $_dependentTables = array(
        'Default_Model_DbTable_Pessoa',
        'Cadastro_Model_DbTable_Setor',
    );
    protected $_referenceMap = array(
        'PessoaGerente' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idgerenteprojeto',
            'refColumns' => 'idpessoa'
        ),
        'PessoaAdjunto' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idgerenteadjunto',
            'refColumns' => 'idpessoa'
        ),
        'PessoaDemandante' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'iddemandante',
            'refColumns' => 'idpessoa'
        ),
        'PessoaPatrocinador' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpatrocinador',
            'refColumns' => 'idpessoa'
        ),
        'Programa' => array(
            'refTableClass' => 'tb_programa',
            'columns' => 'idprograma',
            'refColumns' => 'idprograma'
        ),
        'Setor' => array(
            'refTableClass' => 'tb_setor',
            'columns' => 'idsetor',
            'refColumns' => 'idsetor'
        ),
        'Marco' => array(
            'refTableClass' => 'tb_marco',
            'columns' => 'idmarco',
            'refColumns' => 'idmarco'
        )
    );

}
