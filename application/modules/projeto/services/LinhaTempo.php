<?php

class Projeto_Service_LinhaTempo extends App_Service_ServiceAbstract
{

    /**
     *
     * @var Projeto_Model_Mapper_Linhatempo
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_Linhatempo();
    }

    /**
     * Grava a linha do tempo (auditoria).
     * @param array $dados
     * @return bolean
     */
    public function inserir($dados)
    {
        $serviceParteInteressada = new Projeto_Service_ParteInteressada();
        $auth = Zend_Auth::getInstance();
        $identiti = $auth->getIdentity();
        $dados['idpessoa'] = $identiti->idpessoa; // Pessoa que realizou a ação.
        $dados['dsfuncaoprojeto'] = $serviceParteInteressada->buscarParteInteressadaInterna(
            array('idprojeto' => $dados["idprojeto"], 'idpessoainterna' => $identiti->idpessoa)
        )["nomfuncao"] ?: $identiti->perfilAtivo->nomperfil; // Função que a pessoa desempenha no projeto.
        $model = new Projeto_Model_Linhatempo($dados);
        $retorno = $this->_mapper->insert($model);
        return $retorno;
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

    /**
     *
     * @param string $controller Nome do controller.
     * @param string $module Nome do módulo.
     * @return array
     */
    public function getRecurso($controller, $module = 'projeto')
    {
        return $this->_mapper->getRecurso($controller, $module = 'projeto');
        return $result;
    }

    /**
     * Lista toda a linha do tempo (auditoria) do usuário.
     * @param array $params
     * return array
     */
    public function listar($params = array())
    {
        try {
            $dados = $this->_mapper->listar($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }

    }

    public function getFormPesquisar($idProjeto)
    {
        $this->_form = new Projeto_Form_LinhaTempo();
        $this->_form->getElement('idprojeto')->setValue($idProjeto);
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getDescricaoRecurso()
    {
        $recurso = new Default_Model_Mapper_Recurso();
        $arrayDescricao = [];
        foreach ($recurso->getDescricao() as $d) {
            $arrayDescricao[$d['descricao']] = $d['descricao'];
        }
        return $arrayDescricao;
    }

}