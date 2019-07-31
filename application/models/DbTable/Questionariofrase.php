<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated based on the dbTable "" @ 16-05-2013
 * 17:22
 */
class Default_Model_DbTable_Questionariofrase extends Zend_Db_Table_Abstract
{

    protected $_name = 'tb_questionariofrase';
    protected $_primary = array('idfrase');
    protected $_dependentTables = array();
    protected $_referenceMap = array(
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idcadastrador',
            'refColumns' => 'idpessoa'
        ),
        'Frase' => array(
            'refTableClass' => 'tb_frase',
            'columns' => 'idfrase',
            'refColumns' => 'idfrase'
        ),
        'Questionario' => array(
            'refTableClass' => 'tb_questionario',
            'columns' => 'idquestionario',
            'refColumns' => 'idquestionario'
        )
    );

}

