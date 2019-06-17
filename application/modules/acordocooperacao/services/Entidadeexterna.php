<?php

class Acordocooperacao_Service_Entidadeexterna extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Acordocooperacao_Model_Mapper_Entidadeexterna
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Acordocooperacao_Model_Mapper_Entidadeexterna();
    }

    /**
     * @return Evento_Form_Evento
     */
    public function getForm()
    {
        return $this->_getForm('Acordocooperacao_Form_Entidadeexterna');
    }

    /**
     * @return Evento_Form_Evento
     */
    public function getFormPesquisar()
    {
        $form = $this->_getForm('Acordocooperacao_Form_Entidadeexterna');
        $form->getElement('nomentidadeexterna')
            ->setAttribs(array('class' => 'span3', 'data-rule-required' => false))
            ->setRequired(false)
            ->removeValidator('NotEmpty');
        return $form;
    }

    public function inserir($dados)
    {
        //Zend_Debug::dump($dados); exit;
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Acordocooperacao_Model_Entidadeexterna($form->getValues());
            $retorno = $this->_mapper->insert($model);
            return $retorno;
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
            $model = new Acordocooperacao_Model_Entidadeexterna($form->getValues());
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }


    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Atividade_Service_JqGrid | array
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

    public function fetchPairs()
    {
        $dados = $this->_mapper->fetchPairs();
        $listArray = array('' => 'Selecione');

        foreach ($dados as $val => $desc) {
            $listArray[$val] = $desc;
        }
        return $listArray;
    }

    public function retornaEntidadesExternas($params)
    {
        $resultado = $this->_mapper->retornaEntidadesExternas($params);

        $collection = new App_Model_Collection();
        $collection->setDomainClass('Acordocooperacao_Model_Entidadeexterna');

//        Zend_Debug::dump($resultado); exit;

        foreach ($resultado as $r) {
            $parte = new Acordocooperacao_Model_Entidadeexterna($r);
            $collection[] = $parte;
        }

//        Zend_Debug::dump($collection); exit;
        return $collection;

    }
}


