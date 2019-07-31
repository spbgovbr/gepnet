<?php

class Default_Service_Ata extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Default_Model_Mapper_Ata
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
        $this->_mapper = new Default_Model_Mapper_Ata();
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    public function retornaPorProjeto($dados)
    {
        return $this->_mapper->retornaPorProjeto($dados);
    }
}