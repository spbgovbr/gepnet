<?php

class Evento_Service_Avaliacaoservidor extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Evento_Model_Mapper_Eventoavaliacao
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Evento_Model_Mapper_Eventoavaliacao();
    }

    /**
     * @return Evento_Form_Evento
     */
    public function getForm()
    {
        return $this->_getForm('Evento_Form_AvaliacaoServidor');
    }

    public function getFormPesquisar()
    {
        $form = $this->_getForm('Evento_Form_AvaliacaoServidor');
        $form->getElement('idevento')
            ->setRequired(false)
            ->setAttribs(array('data-rule-required' => false));
        $form->getElement('idtipoavaliacao')
            ->setRequired(false)
            ->setAttribs(array('data-rule-required' => false));

        $serviceEvento = new Evento_Service_Grandeseventos();
        $form->getElement('idevento')->setMultioptions($this->initCombo($serviceEvento->fetchPairs(), 'Todos'));
        $form->getElement('idtipoavaliacao')->setMultioptions($this->initCombo($this->fetchPairsTipoAvaliacao(),
            'Todos'));
        return $form;
    }

    public function getFormEditar()
    {
        return $this->_getForm('Evento_Form_AvaliacaoServidorEditar');
    }

    public function inserir($dados)
    {
        //echo "<pre>"; var_dump($dados);
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Evento_Model_Eventoavaliacao($dados);
            $model->calculaMedias();
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
        $form = $this->getFormEditar();
        if ($form->isValid($dados)) {
            $model = new Evento_Model_Eventoavaliacao($dados);
            $model->calculaMedias();
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
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

    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }

    public function getByIdDetalhar($dados)
    {
        return $this->_mapper->getByIdDetalhar($dados);
    }

    public function getErrors()
    {
        return $this->errors;
    }


    public function getPerguntas()
    {
        return array(
            'numpontualidade' => 'Compareceu pontualmente ao local de trabalho?',
            'numordens' => 'Acatou as ordens da chefia?',
            'numrespeitochefia' => 'Demonstrou respeito no trato da chefia?',
            'numrespeitocolega' => 'Demonstrou respeito no trato dos colegas?',
            'numurbanidade' => 'Demonstrou urbanidade no tratamento do público externo?',
            'numequilibrio' => 'Demonstrou equilíbrio em situações de crise?',
            'numcomprometimento' => 'Demonstrou comprometimento com o resultado das missões designadas?',
            'numesforco' => 'Empreendeu todos os esforços necessários ao cumprimento das missões designadas?',
            'numtrabalhoequipe' => 'Demonstrou capacidade de trabalhar em equipe?',
            'numauxiliouequipe' => 'Permaneceu auxiliando a equipe até a liberação final da chefia?',
            'numaceitousugestao' => 'Aceitou sugestões para melhor desenvolver seu trabalho?',
            'numconhecimentonorma' => 'Demonstrou conhecimento das normas aplicáveis ao caso concreto?',
            'numalternativaproblema' => 'Apresentou alternativas para solucionar problemas?',
            'numiniciativa' => 'Demonstrou iniciativa para realizar atividades além das propostas?',
            'numtarefacomplexa' => 'Demonstrou interesse em assumir tarefas de maior complexidade?',
        );
    }

    public function fetchPairsTipoAvaliacao()
    {
        $mapperTipoAva = new Evento_Model_Mapper_Tipoavaliacao();
        return $mapperTipoAva->fetchPairs();
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


