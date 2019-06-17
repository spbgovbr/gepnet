<?php

/**
 * Newton Carlos
 * This class has generated based on the dbTable "" @ 30-10-2018
 * 15:54
 */
class Diagnostico_Model_DbTable_Questionario extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_questionario_diagnostico';
    protected $_sequence = 'sq_questionariodiagnostico';
    protected $_primary = array('idquestionariodiagnostico');

    protected $_dependentTables = array(
        'Default_Model_DbTable_Pessoa',
    );
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpessoa',
            'refColumns' => 'idpescadastrador'
        ),
    );

}

