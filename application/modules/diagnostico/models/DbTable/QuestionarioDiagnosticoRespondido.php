<?php

/**
 * Wendell Luiz
 * This class has generated based on the dbTable "" @ 22-01-2019
 * 15:54
 */
class Diagnostico_Model_DbTable_QuestionarioDiagnosticoRespondido extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_questionariodiagnostico_respondido';
    protected $_primary = array(
        'iddiagnostico',
        'idquestionario',
        'numero'
    );

    protected $_dependentTables = array(
        'Diagnostico_Model_DbTable_Diagnostico',
        'Diagnostico_Model_DbTable_Questionario',
        'Default_Model_DbTable_Pessoa'
    );
    protected $_referenceMap = array(
        'Diagnostico' => array(
            'refTableClass' => 'tb_diagnostico',
            'columns' => 'iddiagnostico',
            'refColumns' => 'iddiagnostico'
        ),
        'Questionario' => array(
            'refTableClass' => 'tb_questionario_diagnostico',
            'columns' => 'idquestionariodiagnostico',
            'refColumns' => 'idquestionario'
        ),
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpessoa',
            'refColumns' => 'idpescadastrador'
        )
    );
}