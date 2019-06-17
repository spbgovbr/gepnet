<?php

class Default_Service_Cargo extends App_Service_ServiceAbstract
{

    protected $_form;
    /**
     *
     * @var Default_Model_Mapper_Cargo
     */
    protected $_mapper;
    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Default_Model_Mapper_Cargo();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function fetchPairs()
    {
        $resultado = $this->_mapper->fetchPairs();
        $aux = array();
        foreach ($resultado as $i => $r) {
            $aux[$r['sigla']] = $r['sigla'];
        }
        $aux['COL'] = 'COL';
        return $aux;
    }
}

?>
