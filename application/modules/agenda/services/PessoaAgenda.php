<?php

class Agenda_Service_PessoaAgenda extends App_Service_ServiceAbstract
{

    protected $_form;

    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     * @var array
     */
    public $errors = array();
    protected $notify = null;

    public function init()
    {
        $this->_mapper = new Agenda_Model_Mapper_Pessoaagenda();
    }

    public function getForm()
    {
        $form = $this->_getForm('Agenda_Form_Pessoaagenda');
        return $form;
    }

    //put your code here
    public function inserir($dados)
    {
        $form = $this->getForm();

//        var_dump($dados); exit;
        if (empty($dados['idpessoa'])) {
            $this->setNotify(false, 'Necessário selecionar um usuário.');
        }
        if ($form->isValid($dados)) {
            $model = new Agenda_Model_Pessoaagenda($form->getValues());

//            $auth = Zend_Auth::getInstance();
//            if ($auth->hasIdentity()) {
//                $escritorio = $auth->getIdentity()->perfilAtivo->nomescritorio;
//                $model->idcadastrador = $auth->getIdentity()->idpessoa;
//            }

            $model->idagenda = $this->_mapper->insert($model);

            $servicePessoa = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
            $pessoa = $servicePessoa->retornaPorId($model);

            return $pessoa;
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

        $form = $this->getFormResumoDoProjeto();
        $form->getElement('vlrorcamentodisponivel')->addFilter('Digits');
        if ($form->isValidPartial($dados)) {
            $values = array_filter($form->getValues());
            $model = new Projeto_Model_Gerencia($values);
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    /**
     *
     * @param array $dados
     */
    public function excluir($dados)
    {
        try {
            //$model = new Default_Model_Gerencia($dados);
            return $this->_mapper->excluir($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    /**
     *
     * @param array $dados
     */
    public function excluirparticipante($dados)
    {
        try {
            //$model = new Default_Model_Gerencia($dados);
            return $this->_mapper->excluirparticipante($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
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

    public function retornaPartesPorAgenda($params)
    {
        return $this->_mapper->retornaPartesPorAgenda($params);
    }

    public function setNotify($success, $msg)
    {
        $this->notify['text'] = $msg;
        $this->notify['type'] = ($success) ? 'success' : 'error';
    }

    public function getNotify()
    {
        return $this->notify;
    }
}

?>

