<?php

/**
 * Newton Carlos
 * This class has generated based on the dbTable "" @ 11-12-2018
 * 15:54
 */
class Diagnostico_Model_DbTable_RespostaQuestionario extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_resposta_pergunta';
    protected $_primary = array('id_resposta_pergunta');

    protected $_dependentTables = array(
        'Diagnostico_Model_DbTable_OpcaoResposta',
        'Diagnostico_Model_DbTable_Pergunta',
        'Diagnostico_Model_DbTable_QuestionarioDiagnosticoRespondido',
    );
    protected $_referenceMap = array(
        'OpcaoResposta' => array(
            'refTableClass' => 'tb_opcao_resposta',
            'columns' => 'idresposta',
            'refColumns' => 'idresposta'
        ),
        'Pergunta' => array(
            'refTableClass' => 'tb_pergunta',
            'columns' => 'idpergunta',
            'refColumns' => 'idpergunta'
        ),
        'QuestionarioDiagnosticoRespondido' => array(
            'refTableClass' => 'tb_questionariodiagnostico_respondido',
            'columns' => array(
                'idquestionario',
                'iddiagnostico',
                'numero',
            ),
            'refColumns' => array(
                'idquestionario',
                'iddiagnostico',
                'numero',
            )
        ),
    );

}

