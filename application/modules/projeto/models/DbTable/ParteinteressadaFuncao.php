<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Projeto_Model_DbTable_ParteinteressadaFuncao extends Zend_Db_Table_Abstract
{

    protected $_schema = 'agepnet200';
    protected $_name = 'tb_parteinteressadafuncao';
    protected $_primary = array('idparteinteressadafuncao');
    protected $_dependentTables = array(
        'Projeto_Model_DbTable_Parteinteressada_Funcoes',
    );
    protected $_referenceMap = array(
        'ParteInteressadaFuncoes' => array(
            'refTableClass' => 'tb_parteinteressada_funcoes',
            'columns' => 'idparteinteressadafuncao',
            'refColumns' => 'idparteinteressadafuncao'
        ),
    );

}

