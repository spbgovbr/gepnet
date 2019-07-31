<?php

class Default_Service_Permissao extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Default_Model_Mapper_Permissao
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db',
        'log'
    );

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     *
     * @var Zend_Log
     */
    protected $_log;

    /**
     * @var array
     */
    public $errors = array();
    private $arrRecursos = array();
    private $recursos = array();
    private $recursosCadastrados = array();
    private $permissoesAtual = array();

    public function init()
    {
        $this->_mapper = new Default_Model_Mapper_Permissao();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getForm()
    {
        $form = new Default_Form_Permissao();
        return $form;
    }

    public function getFormPerfil()
    {
        $form = new Default_Form_PerfilPermissao();
        return $form;
    }

    public function inserir($params)
    {
        try {
            $permissao = new Default_Model_Permissao($params);
            return $this->_mapper->insert($permissao);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
        return false;
    }

    public function editar($params)
    {
        try {
            $form = $this->getForm();
            if ($form->isValid($params)) {
                $permissao = new Default_Model_Permissao($params);
                return $this->_mapper->update($permissao);
            } else {
                $this->errors = $form->getMessages();
            }
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
        return false;
    }

    /**
     *
     * @param array $param
     * @return array
     */
    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }

    /**
     *
     * @param array $param
     * @return Default_Model_Mapper_Permissao
     *
     */
    public function getRecursosProjeto()
    {
        return $this->_mapper->getRecursosProjeto();
    }

    /**
     *
     * @param array $param
     * @return Default_Model_Mapper_Permissao
     *
     */
    public function getRecursosPlanodeacao()
    {
        return $this->_mapper->getRecursosPlanodeacao();
    }

    /**
     *
     * @param array $param
     * @return array Default_Model_Mapper_Permissao
     */
    public function getRecursosProjetoPorId()
    {
        return $this->_mapper->getRecursosProjetoPorId();
    }

    /**
     *
     * @param array $param
     * @return Default_Model_Mapper_Permissao
     */
    public function getRecursosProjetoPorParte($params)
    {
        return $this->_mapper->getRecursosProjetoPorParte($params);
    }

    /**
     *
     * @param array $param
     * @return Default_Model_Mapper_Permissao
     */
    public function getRecursosProjetoRecursoPorParte($params)
    {
        return $this->_mapper->getRecursosProjetoRecursoPorParte($params);
    }

    /**
     *
     * @param array $param
     * @return array Default_Model_Mapper_Permissao
     */
    public function getRecursosPlanodeacaoPorId()
    {
        return $this->_mapper->getRecursosPlanodeacaoPorId();
    }

    /**
     *
     * @param array $param
     * @return Default_Model_Mapper_Permissao
     */
    public function getRecursosPlanodeacaoPorParte($params)
    {
        return $this->_mapper->getRecursosPlanodeacaoPorParte($params);
    }

    /**
     *
     * @param array $param
     * @return Default_Model_Mapper_Permissao
     */
    public function getRecursosPlanodeacaoRecursoPorParte($params)
    {
        return $this->_mapper->getRecursosPlanodeacaoRecursoPorParte($params);
    }

    /**
     *
     * @param array $params
     * @return Default_Model_Permissao
     */
    public function retornaPorId($params)
    {
        return $this->_mapper->retornaPorId($params);
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
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    /**
     *
     * @return array
     */
    public function retornaPorPerfil()
    {
        $serviceLogin = new Default_Service_Login();
        $perfil = $serviceLogin->retornaPerfilAtivo();
        $permissoes = $this->_mapper->retornaPorPerfil($perfil->idperfil);
        return $permissoes;
    }

    public function fetchPairs($params = array())
    {
        if (count($params) <= 0) {
            $retorno = array(
                '' => 'Todas'
            );
            $options = $this->_mapper->fetchPairs();
            foreach ($options as $i => $val) {
                $retorno[$i] = $val;
            }
            return $retorno;
        }
        return $this->_mapper->fetchPairs($params);
    }

    public function retornaPorRecurso($params)
    {
        $retorno = array();
        $todas = new stdClass();
        $todas->id = '';
        $todas->nome = 'Todas';
        $retorno[] = $todas;
        $resultado = $this->_mapper->retornaPorRecurso($params);
        foreach ($resultado as $r => $permissao) {
            $p = new stdClass();
            $p->id = $permissao->idpermissao;
            $p->nome = $permissao->no_permissao;
            $retorno[] = $p;
        }
        return $retorno;
    }

    public function retornaRecursoEPermissaoDiagnosticoPorTipo($tipo)
    {
        return $this->_mapper->retornaRecursoEPermissaoDiagnosticoPorTipo($tipo);
    }

    public function retornaRecursoEPermissaoPorTipo($tipo)
    {
        return $this->_mapper->retornaRecursoEPermissaoPorTipo($tipo);
    }

}
