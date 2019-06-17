<?php

class Diagnostico_Model_DbTable_QuestionarioVinculado extends Zend_Db_Table_Abstract
{
    protected $_schema = 'agepnet200';
    protected $_name = 'tb_vincula_questionario';
    protected $_primary = array(
        'idquestionario',
        'iddiagnostico'
    );

    protected $_dependentTables = array(
        'Diagnostico_Model_DbTable_Diagnostico',
        'Diagnostico_Model_DbTable_Questionario',
        'Default_Model_DbTable_Pessoa',
    );
    protected $_referenceMap = array(
        'Diagnostico' => array(
            'refTableClass' => 'tb_diagnostico',
            'columns' => 'iddiagnostico',
            'refColumns' => 'iddiagnostico',
            'onDelete' => self::CASCADE
        ),
        'Questionario' => array(
            'refTableClass' => 'tb_questionario_diagnostico',
            'columns' => 'idquestionariodiagnostico',
            'refColumns' => 'idquestionario',
            'onDelete' => self::CASCADE
        ),
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpessoa',
            'refColumns' => 'idpesdisponibiliza',
            'onDelete' => self::CASCADE
        ),
        'Pessoa' => array(
            'refTableClass' => 'tb_pessoa',
            'columns' => 'idpessoa',
            'refColumns' => 'idpesencerrou',
            'onDelete' => self::CASCADE
        )

    );

}