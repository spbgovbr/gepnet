<?php

class Projeto_Service_Eap extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Atividadecronograma
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
        $this->_mapper = new Projeto_Model_Mapper_Atividadecronograma();
    }
    
    public function editarEntrega($params)
    {
        $model = new Projeto_Model_Atividadecronograma($params);
        $atualiza = $this->_mapper->atualizarEntregaEap($model);
        if($atualiza){
            $this->_mapper->atualizarPercentuaisGrupoEntrega($model);
            $modelEntrega = $this->_mapper->retornaEntregaPorId($params);
            $serviceAtividadeCron = new Projeto_Service_AtividadeCronograma();
            $serviceAtividadeCron->atualizarDatasGrupo($modelEntrega);
        }
        return $atualiza;
        
    }

    public function getErrors()
    {
        return $this->errors;
    }
   
}


