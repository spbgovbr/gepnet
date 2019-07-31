<?php

/**
 * Newton Carlos
 * This class has generated based on the dbTable "" @ 11-12-2018
 * 15:54
 */
class Diagnostico_Model_DbTable_OpcaoResposta extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_opcao_resposta';
    protected $_primary = array('idresposta');

    protected $_dependentTables = array(
        'Diagnostico_Model_DbTable_Pergunta',
        'Diagnostico_Model_DbTable_Questionario',
    );
    protected $_referenceMap = array(
        'Pergunta' => array(
            'refTableClass' => 'tb_pergunta',
            'columns' => 'idpergunta',
            'refColumns' => 'idpergunta'
        ),
        'Questionario' => array(
            'refTableClass' => 'tb_questionario_diagnostico',
            'columns' => 'idquestionariodiagnostico',
            'refColumns' => 'idquestionario'
        ),
        'onDelete' => self::CASCADE
    );

}

