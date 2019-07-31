<?php

class Diagnostico_Model_DbTable_Pergunta extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_pergunta';
    protected $_primary = array('idpergunta');

    protected $_dependentTables = array(
        'Diagnostico_Model_DbTable_QuestionarioVinculado',
    );
    protected $_referenceMap = array(
        'Questionario' => array(
            'refTableClass' => 'tb_questionario_diagnostico',
            'columns' => array('idquestionario'),
            'refColumns' => array('idquestionariodiagnostico')
        ),

        'Secao' => array(
            'refTableClass' => 'fk_pergunta_secao',
            'columns' => 'id_secao',
            'refColumns' => 'id_secao'
        ),

    );

}

