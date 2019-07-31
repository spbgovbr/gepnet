<?php

class Admin_Service_Usuario extends App_Service_ServiceAbstract
{
    protected $_form;
    /**
     *
     * @var Admin_Model_Mapper_Usuario
     */
    protected $_mapper;
    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Admin_Model_Mapper_Usuario();
    }

    /**
     * @return Zend_Form
     */
    public function getForm()
    {
        return $this->_getForm('Admin_Form_Usuario');
    }

    /**
     * @return Zend_Form
     */
    public function getFormEditar()
    {
        $form = $this->_getForm('Admin_Form_Usuario', array('ds_senha'));
        $form->setName('usuario-editar');
        $form->setAttrib('id', 'usuario-editar');
        return $form;
    }

    /**
     * @return Zend_Form
     */
    public function getFormPesquisar()
    {
        return $this->_getForm('Admin_Form_UsuarioPesquisar');
    }

    /**
     * @return Zend_Form
     */
    public function getFormAlterarSenha()
    {
        return $this->_getForm('Admin_Form_UsuarioAlterarSenha');
    }

    public function getById($dados)
    {
        $values = $this->_mapper->getById($dados);
        return array_change_key_case($values, CASE_LOWER);
    }

    public function getByCdPessoa($dados)
    {
        $values = $this->_mapper->getByCdPessoa($dados);
        return array_change_key_case($values, CASE_LOWER);
    }

    public function inserir($dados)
    {
        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Admin_Model_Usuario($form->getValues());
            //Zend_Debug::dump($model);exit;
            try {
                return $this->_mapper->insert($model);
            } catch (Exception $exc) {
                $this->errors = $exc->getMessage();
            }
        } else {
            //$msg = $form->getMessages();
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function update($dados)
    {
        $form = $this->getFormEditar();
        if ($form->isValid($dados)) {
            $model = new Admin_Model_Usuario($form->getValues());
            //Zend_Debug::dump($model);exit;
            try {
                return $this->_mapper->update($model);
            } catch (Exception $exc) {
                $this->errors = $exc->getMessage();
            }
        } else {
            //$msg = $form->getMessages();
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function excluir($dados)
    {
        if (isset($dados['cd_lotacao']) && isset($dados['cd_pessoa'])) {
            $model = new Admin_Model_Usuario($dados);
            //Zend_Debug::dump($model);exit;
            try {
                return $this->_mapper->excluir($model);
            } catch (Exception $exc) {
                $this->errors = $exc->getMessage();
            }
        } else {
            //$msg = $form->getMessages();
            $this->errors = $form->getMessages();
        }
        return false;
    }

    public function alterarSenha($dados)
    {
        $form = $this->getFormAlterarSenha();
        if ($form->isValid($dados)) {
            $model = new Admin_Model_Usuario($form->getValues());
            //Zend_Debug::dump($model);exit;
            try {
                return $this->_mapper->alterarSenha($model);
            } catch (Exception $exc) {
                $this->errors = $exc->getMessage();
            }
        } else {
            //$msg = $form->getMessages();
            $this->errors = $form->getMessages();
        }
        return false;
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
        $dados = $this->_mapper->pesquisar($params, $paginator);
        if ($paginator) {
            $service = new Admin_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }
}

?>
