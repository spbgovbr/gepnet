<?php

class Default_Service_Perfil extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Default_Model_Mapper_Perfil
     */
    protected $_mapper;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Default_Model_Mapper_Perfil();
    }

    /**
     * @return Default_Form_Documento
     */
    public function getForm()
    {
        throw new Exception('Não implementado');
        return $this->_getForm('Default_Form_Pessoa');
    }

    /**
     * @return Default_Form_Documento
     */
    public function getFormPesquisar()
    {
        throw new Exception('Não implementado');
        return $this->_getForm('Default_Form_PessoaPesquisar');
    }

    /**
     * @return Default_Form_DocumentoEditar
     */
    public function getFormEditar($params = null)
    {
        throw new Exception('Não implementado');
        $form = $this->_getForm('Default_Form_Pessoa', array('submit'));
        if ($params) {
            $values = $this->_mapper->getById($params);

            $dateMin = date('d/m/Y', strtotime("first day of january " . $values['AN_DOCUMENTO']));
            $dateMax = date('d/m/Y', strtotime("last day of december " . $values['AN_DOCUMENTO']));
            $form->getElement('dt_documento')->addValidator('DateRange', false, array(
                'min' => $dateMin,
                'max' => $dateMax,
                'locale' => 'pt_BR',
            ));
        }
        return $form;
    }

    //put your code here
    public function inserir($dados)
    {
        throw new Exception('Não implementado');
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Default_Model_Pessoa($form->getValues());
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
        throw new Exception('Não implementado');
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Default_Model_Pessoa($form->getValues());
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
        throw new Exception('Não implementado');
        try {
            //$model = new Default_Model_Documento($dados);
            return $this->_mapper->excluir($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }

    /**
     *
     * @param array $dados
     * @return Default_Model_Perfil
     */
    public function retornaPorId($params)
    {
        return $this->_mapper->retornaPorId($params);
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
        throw new Exception('Não implementado');
        $dados = $this->_mapper->pesquisar($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    public function buscar($params, $paginator)
    {
        throw new Exception('Não implementado');
        if ($params['tipo'] == 0) {
            return $this->buscarServidor($params, $paginator);
        } else {
            return $this->buscarColaborador($params, $paginator);
        }
    }

    public function delete($id)
    {
        throw new Exception('Não implementado');
        return $this->_mapper->delete($id);
    }

    public function fetchPairs()
    {
        return $this->_mapper->fetchPairs();
    }

    public function authfetchPairs($identiti)
    {
        return $this->_mapper->authfetchPairs($identiti);
    }

    public function getByCpf($dados)
    {
        throw new Exception('Não implementado');
        return $this->_mapper->getByCpf($dados);
    }

    public function retornaPorPessoa($params)
    {

        return $this->_mapper->retornaPorPessoa($params);
    }

    public function retornaPorIdEPessoa($params)
    {
        return $this->_mapper->retornaPorIdEPessoa($params);
    }

    public function revogarPermissao($params)
    {
        $mpp = new Default_Model_Mapper_Permissaoperfil();
        return $mpp->delete($params);
    }

    public function concederPermissao($params)
    {
        $mpp = new Default_Model_Mapper_Permissaoperfil();
        $model = new Default_Model_Permissaoperfil($params);
        return $mpp->insert($model);
    }
}

?>
