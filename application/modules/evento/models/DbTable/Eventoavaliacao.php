<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:21
 */
class Evento_Model_DbTable_Eventoavaliacao extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_eventoavaliacao';
    protected $_primary = array('ideventoavaliacao');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idavaliador',
            'refColumns' => 'idpessoa'
        ),
        'Evento' => array(
            'refTableClass' => 'tb_evento',
            'columns' => 'idevento',
            'refColumns' => 'idevento'
        ),
        'Tipoavaliacao' => array(
            'refTableClass' => 'tb_tipoavaliacao',
            'columns' => 'domtipoavaliacao',
            'refColumns' => 'idtipoavaliacao'
        )
    );

}

