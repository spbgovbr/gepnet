<?php

class Pesquisa_Model_DbTable_QuestionariofrasePesquisa extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_questionariofrase_pesquisa';
    protected $_primary = array('idfrasepesquisa', 'idquestionariopesquisa');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idcadastrador',
            'refColumns' => 'idpessoa'
        ),
        'Frase' => array(
            'refTableClass' => 'tb_frase_pesquisa',
            'columns' => 'idfrasepesquisa',
            'refColumns' => 'idfrasepesquisa'
        ),
        'Questionario' => array(
            'refTableClass' => 'tb_questionario_pesquisa',
            'columns' => 'idquestionariopesquisa',
            'refColumns' => 'idquestionariopesquisa'
        )
    );

}

