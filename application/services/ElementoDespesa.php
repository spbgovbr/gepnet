<?php

class Default_Service_ElementoDespesa extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Default_Model_Mapper_Elementodespesa
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Default_Model_Mapper_Elementodespesa();
    }


    public function fetchPairs()
    {
        $data = $this->_mapper->fetchPairs();
        $retorno[''] = 'Todos';
        foreach ($data as $d) {
            $retorno[] = $d;
        }
        return $retorno;
    }

    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }
}