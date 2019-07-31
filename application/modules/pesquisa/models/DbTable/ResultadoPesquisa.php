<?php

class Pesquisa_Model_DbTable_ResultadoPesquisa extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_resultado_pesquisa';
    protected $_primary = array('id', 'idresultado', 'idfrasepesquisa', 'idquestionariopesquisa');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Questionario' => array(
            'refTableClass' => 'tb_questionario_pesquisa',
            'columns' => 'idquestionariopesquisa',
            'refColumns' => 'idquestionariopesquisa'
        ),
        'Pergunta' => array(
            'refTableClass' => 'tb_frase_pesquisa',
            'columns' => 'idfrasepesquisa',
            'refColumns' => 'idfrasepesquisa'
        ),
    );

}

