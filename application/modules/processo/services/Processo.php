<?php

class Processo_Service_Processo extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Processo_Model_Mapper_Processo
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Processo_Model_Mapper_Processo();
    }

    /**
     * @return Processo_Form_Processo
     */
    public function getForm()
    {
        return $this->_getForm('Processo_Form_Processo', array('datatualizacao'));
    }

    /**
     * @return Processo_Form_ProcessoPesquisar
     */
    public function getFormPesquisar()
    {
        return $this->_getForm('Processo_Form_ProcessoPesquisar');
    }

    /**
     * @return Processo_Form_ProcessoEditar
     */
    public function getFormEditar()
    {
        $form = $this->_getForm('Processo_Form_ProcessoEditar');
        return $form;
    }

    //put your code here
    public function inserir($dados)
    {
        $form = $this->getForm();
        if ($form->isValid($dados)) {

            $model = new Processo_Model_Processo($form->getValues());
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
            $model = new Processo_Model_Processo($form->getValues());
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
            return $this->_mapper->excluir($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    public function getByIdDetalhar($dados)
    {
        return $this->_mapper->getByIdDetalhar($dados);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Processo_Service_JqGrid | array
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

    public function buscar($params, $paginator)
    {
        return $this->buscarSetor($params, $paginator);
    }

    public function importar($params)
    {
        if ($params['tipo'] == 0) {
            return $this->importarServidor($params);
        } else {
            return $this->importarColaborador($params);
        }
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Processo_Service_JqGrid | array
     */
    public function buscarSetor($params, $paginator)
    {
        $paginador = $this->_mapper->buscarSetor($params, $paginator);
        $response = array();
        $response['total'] = $paginador->getTotalItemCount();
        foreach ($paginador as $d) {
            $a = new stdClass();
            $a->id = $d['id'];
            $a->text = $d['text'];
            $response['processos'][] = $a;
        }
        return $response;
    }

    /**
     * Retorna um registro da view processo do owner comum
     * @param array $params
     * @return array
     */
    public function importarServidor($params)
    {
        $processo = $this->_mapper->getServidorById($params);
        $id_servidor = array('id_servidor' => $processo->id_servidor);
        $response = new stdClass();
        $response->dados = null;
        $response->success = false;
        $date = date('d/m/Y H:i:s');
        $response->dados = $processo->formPopulate();
        $response->msg = "Usuario importado: {$processo->nomprocesso} - {$processo->getNumcpfMascarado()} em: {$date}.";
        $response->success = true;
        return $response;
    }

    /**
     * Retorna um registro da view processo do owner comum
     * @param array $params
     * @return array
     */
    public function importarColaborador($params)
    {
        $processo = $this->_mapper->getColaboradorById($params);
        $response = new stdClass();
        $response->dados = null;
        $response->success = false;
        $date = date('d/m/Y H:i:s');
        $response->dados = $processo->formPopulate();
        $response->msg = "Usuario importado: {$processo->nomprocesso} - {$processo->getNumcpfMascarado()} em: {$date}.";
        $response->success = true;
        return $response;
    }

    public function delete($id)
    {
        return $this->_mapper->delete($id);
    }

    public function fetchPairs()
    {
        return $this->_mapper->fetchPairs();
    }

    public function getByCpf($dados)
    {
        return $this->_mapper->getByCpf($dados);
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

?>
