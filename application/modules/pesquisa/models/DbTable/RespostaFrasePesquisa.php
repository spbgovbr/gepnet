<?php

class Pesquisa_Model_DbTable_RespostaFrasePesquisa extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_respostafrase_pesquisa';
    protected $_primary = array('idfrasepesquisa', 'idrespostapesquisa');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Frase' => array(
            'refTableClass' => 'tb_frase_pesquisa',
            'columns' => 'idfrasepesquisa',
            'refColumns' => 'idfrasepesquisa'
        ),
        'Resposta' => array(
            'refTableClass' => 'tb_resposta_pesquisa',
            'columns' => 'idrespostapesquisa',
            'refColumns' => 'idrespostapesquisa'
        )
    );

}

