<?php

class Cadastro_Service_Setor extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Cadastro_Model_Mapper_Setor
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
        $this->_mapper = new Cadastro_Model_Mapper_Setor();
    }

    /**
     * @return Cadastro_Form_Setor
     */
    public function getForm()
    {
        return $this->_getForm('Cadastro_Form_Setor');
    }

    /**
     * @return Cadastro_Form_Setor
     */
    public function getFormPesquisar()
    {
        $form = $this->_getForm('Cadastro_Form_Setor');
        $form->getElement('nomsetor')
            ->setAttribs(array('class' => 'span3', 'data-rule-required' => false))
            ->setRequired(false)
            ->removeValidator('NotEmpty');
        return $form;
    }

    //put your code here
    public function inserir($dados)
    {
        $form = $this->getForm();

        if ($form->isValid($dados)) {
            $model = new Cadastro_Model_Setor($form->getValues());
            return $this->_mapper->insert($model);
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function update($dados)
    {
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Cadastro_Model_Setor($form->getValues());
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);


    }


    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function pesquisar($params, $paginator)
    {
        $dados = $this->_mapper->pesquisar($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        }
        return $dados;
    }


}

?>


