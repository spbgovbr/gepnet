<?php

class Pesquisa_Model_DbTable_RespostaFrase extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_respostafrase';
    protected $_primary = array('idfrase', 'idresposta');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Frase' => array(
            'refTableClass' => 'tb_frase',
            'columns' => 'idfrase',
            'refColumns' => 'idfrase'
        ),
        'Resposta' => array(
            'refTableClass' => 'tb_resposta',
            'columns' => 'idresposta',
            'refColumns' => 'idresposta'
        )
    );

}

