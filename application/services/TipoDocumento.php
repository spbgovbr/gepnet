<?php

class Default_Service_TipoDocumento extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Default_Model_Mapper_Tipodocumento
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Default_Model_Mapper_Tipodocumento();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function fetchPairs()
    {
        $resultado = $this->_mapper->fetchPairs();
        $retorno = array('' => 'Todos');
        foreach ($resultado as $r => $v) {
            $retorno[$r] = $v;
        }

        return $retorno;
    }

}

?>
