<?php

/**
 * Wendell Luiz
 * This class has generated based on the dbTable "" @ 22-01-2019
 * 15:54
 */
class Diagnostico_Model_DbTable_RespostaQuestionarioDiagnostico extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_resposta_questionariordiagnostico';
    protected $_primary = array(
        'id_resposta_pergunta',
        'iddiagnostico',
        'idquestionario',
        'numero'
    );

    protected $_dependentTables = array(
        'Diagnostico_Model_DbTable_Diagnostico',
        'Diagnostico_Model_DbTable_Questionario',
        'Diagnostico_Model_DbTable_RespostaQuestionario',
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
        'RespostaQuestionario' => array(
            'refTableClass' => 'tb_resposta_pergunta',
            'columns' => 'id_resposta_pergunta',
            'refColumns' => 'id_resposta_pergunta'
        )
    );
}