<?php

class Projeto_Service_AtividadeOcultar extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Atividadeocultar
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
        $this->_mapper = new Projeto_Model_Mapper_Atividadeocultar();
    }

    /**
     * @return Projeto_Form_AtividadeCronogramaMarco
     */
    public function getForm()
    {
        throw new Exception('metodo nao implementado.');
    }

    /**
     *
     * @param array $dados
     */
    public function inserir($dados)
    {
        $model = new Projeto_Model_Atividadeocultar($dados);
        $data = array(
            "idatividadecronograma" => $model->idatividadecronograma,
            "idprojeto" => $model->idprojeto,
            "flashowhide" => $model->flashowhide,
            "dtcadastro" => new Zend_Db_Expr("now()"),
        );
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $data["idpessoa"] = $idpessoa;
        $dados = array_filter($data);
        try {
            $model = new Projeto_Model_Atividadeocultar($dados);
            $this->_mapper->insert($model);
            return $model;
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
        return false;/**/
    }

    /**
     *
     * @param array $dados
     */
    public function excluir($dados)
    {
        $model = new Projeto_Model_Atividadeocultar($dados);
        $data = array(
            "idatividadecronograma" => $model->idatividadecronograma,
            "idprojeto" => $model->idprojeto,
            "dtcadastro" => new Zend_Db_Expr("now()"),
        );
        $idpessoa = Zend_Auth::getInstance()->getIdentity()->idpessoa;
        $data["idpessoa"] = $idpessoa;
        $dados = array_filter($data);
        try {
            $model = new Projeto_Model_Atividadeocultar($dados);
            $this->_mapper->excluir($model);
            return $model;
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
        return false;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function verificaAtividadeOcultar($params)
    {
        return $this->_mapper->verificaAtividadeOcultar($params);
    }

    public function buscaIdparteinteressada($params)
    {
        $resultado = $this->_mapper->buscaIdparteinteressada($params);
        return $resultado["idparteinteressada"];
    }

    public function buscaNomepessoa()
    {
        $nomepessoa = Zend_Auth::getInstance()->getIdentity()->nome;
        return $nomepessoa;
    }

}

?>