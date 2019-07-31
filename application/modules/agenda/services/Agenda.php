<?php

class Agenda_Service_Agenda extends App_Service_ServiceAbstract
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

    public function init()
    {
        $this->_mapper = new Agenda_Model_Mapper_Agenda();
    }

    /**
     * @return Agenda_Form_Agenda
     */
    public function getForm()
    {
        $form = $this->_getForm('Agenda_Form_Agenda');
        return $form;
    }


    /**
     * @return Agenda_Form_Agenda
     */
    public function getFormPesquisar()
    {
        return $this->_getForm('Projeto_Form_GerenciaPesquisar');
    }

    /**
     * @return Agenda_Form_AgendaEditar
     */
    public function getFormEditar()
    {
        $formEditar = $this->_getForm('Agenda_Form_Agenda');
        return $formEditar;
    }


    //put your code here
    public function inserir($dados)
    {
        $form = $this->getForm();

        if ($form->isValid($dados)) {
            $model = new Agenda_Model_Agenda($form->getValues());

//            $auth = Zend_Auth::getInstance();
//            if ($auth->hasIdentity()) {
//                $escritorio = $auth->getIdentity()->perfilAtivo->nomescritorio;
//                $model->idcadastrador = $auth->getIdentity()->idpessoa;
//            }

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
        if ($form->isValidPartial($dados)) {
            $values = array_filter($form->getValues());
            $model = new Agenda_Model_Agenda($values);
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

    public function getById($dados, $model = false)
    {

        $dados = $this->_mapper->getById($dados, $model);
        if (isset($dados->participantes)) {
            $data = null;
            foreach ($dados->participantes->getIterator() as $p):
                $data .= (empty($data) ? '' : ', ') . $p->nompessoa;
            endforeach;
            $dados->participantes = $data;
        }
        return $dados;
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
    public function pesquisar($params)
    {
//        var_dump($params); exit;
        $service = App_Service_ServiceAbstract::getService('Agenda_Service_PessoaAgenda');
        $dados = $this->_mapper->pesquisar($params);
        $response = '';

//        var_dump($dados); exit;

        foreach ($dados as $d) {
            $nomparticipantes = null;
            $params['idagenda'] = $d['idagenda'];
            $participantes = $service->retornaPartesPorAgenda($params);
            foreach ($participantes as $p) {
                $nomparticipantes .= (empty($nomparticipantes) ? null : ',') . $p['nompessoa'];
            }
            $array['cell'] = array(
                $d['datagenda'],
                $d['hragendada'],
                $d['deslocal'],
                $d['desassunto'],
                $nomparticipantes,
                $d['nomcadastrador'],
                $d['falenviaemail'],
                $d['idagenda'],
            );

            $response["rows"][] = $array;
        }
        return $response;

//        $service = new App_Service_JqGrid();
//        $service->setPaginator($response);
//        return $service;
//        return $this->_mapper->pesquisar($params);
    }

    public function retornaPartesPorAgenda($params, $model = false)
    {
        return $this->_mapper->retornaPartesPorAgenda($params, $model);
    }

    public function retornaDiasComEventos($params)
    {
        $dados = $this->_mapper->retornaDiasComEventos($params);
        return $dados;
    }
}

?>

