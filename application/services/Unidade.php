<?php

class Default_Service_Unidade extends App_Service_ServiceAbstract
{

    protected $_form;
    /**
     *
     * @var Default_Model_Mapper_Unidade
     */
    protected $_mapper;
    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Default_Model_Mapper_Unidade();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function fetchPairs()
    {
        return $this->_mapper->fetchPairs();
    }

    public function listUnidadePrincipal()
    {
        return $this->_mapper->listUnidadePrincipal();
    }
}

?>
