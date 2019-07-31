<?php

class Evento_Service_Grandeseventos extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Evento_Model_Mapper_Evento
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Evento_Model_Mapper_Evento();
    }

    /**
     * @return Evento_Form_Evento
     */
    public function getForm()
    {
        return $this->_getForm('Evento_Form_Evento');
    }

    /**
     * @return Evento_Form_Evento
     */
    public function getFormPesquisar()
    {
        $form = $this->_getForm('Evento_Form_Evento');
        $form->getElement('nomevento')
            ->setAttribs(array('class' => 'span3', 'data-rule-required' => false))
            ->setRequired(false)
            ->removeValidator('NotEmpty');
        return $form;
    }

    public function inserir($dados)
    {
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Evento_Model_Evento($form->getValues());
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
            $model = new Evento_Model_Evento($form->getValues());
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

    public function getByIdDetalhar($dados)
    {
        return $this->_mapper->getByIdDetalhar($dados);
    }

    public function fetchPairs()
    {
        return $this->_mapper->fetchPairs();
    }

    public function getErrors()
    {
        return $this->errors;
    }


    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \App_Service_JqGrid | array
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

    public function getUfs()
    {
        return array(
            '' => 'Selecione',
            'AC' => 'AC',
            'AL' => 'AL',
            'AP' => 'AP',
            'AM' => 'AM',
            'BA' => 'BA',
            'CE' => 'CE',
            'DF' => 'DF',
            'ES' => 'ES',
            'GO' => 'GO',
            'MA' => 'MA',
            'MT' => 'MT',
            'MS' => 'MS',
            'MG' => 'MG',
            'PA' => 'PA',
            'PB' => 'PB',
            'PR' => 'PR',
            'PE' => 'PE',
            'PI' => 'PI',
            'RJ' => 'RJ',
            'RN' => 'RN',
            'RS' => 'RS',
            'RO' => 'RO',
            'RR' => 'RR',
            'SC' => 'SC',
            'SP' => 'SP',
            'SE' => 'SE',
            'TO' => 'TO'
        );
    }

    public function initCombo($objeto, $msg)
    {

        $listArray = array();
        $listArray = array('' => $msg);

        foreach ($objeto as $val => $desc) {
            if ($desc != $msg) {
                $listArray[$val] = $desc;
            }
        }
        return $listArray;
    }
}


