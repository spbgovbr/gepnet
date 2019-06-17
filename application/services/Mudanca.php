<?php

class Default_Service_Mudanca extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Default_Model_Mapper_Mudanca
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
        $this->_mapper = new Default_Model_Mapper_Mudanca();
    }

    /**
     * @return Default_Form_Mudanca
     */
    public function getForm()
    {
        return $this->_getForm('Default_Form_Mudanca', array('flaativo'));
    }

    //put your code here
    public function inserir($dados)
    {
        $form = $this->getForm();

        if ($form->isValid($dados)) {
            $model = new Default_Model_Mudanca($form->getValues());
            return $this->_mapper->insert($model);
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }


    /**
     *
     * @param array $dados
     */
    public function update($dados)
    {
        try {
            //$model = new Default_Model_Escritorio($dados);
            return $this->_mapper->update($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function retornaPorProjeto($dados)
    {
        return $this->_mapper->retornaPorProjeto($dados);
    }
}