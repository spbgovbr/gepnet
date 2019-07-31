<?php

class Default_Service_Escritorio extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Default_Model_Mapper_Escritorio
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
        $this->_mapper = new Default_Model_Mapper_Escritorio();
    }

    /**
     * @return Default_Form_Escritorio
     */
    public function getForm()
    {
        return $this->_getForm('Default_Form_Escritorio', array('flaativo'));
    }

    /**
     * @return Default_Form_Escritorio
     */
    public function getFormPesquisar()
    {
        return $this->_getForm('Default_Form_EscritorioPesquisar');
    }

    /**
     * @return Default_Form_EscritorioEditar
     */
    public function getFormEditar()
    {
        $formEditar     = $this->_getForm('Default_Form_Escritorio', array('submit', 'reset'));
        $nomescritorio2 = $formEditar->getElement('nomescritorio2');
        $nomescritorio2->setValidators(array(
            'NotEmpty',
            array('StringLength', false, array(0, 100)),
        ));

        $formEditar->removeElement('nomescritorio2');
        $formEditar->addElement($nomescritorio2);

        return $formEditar;
    }

    //put your code here
    public function inserir($dados)
    {
        $form = $this->getForm();

        if ( $form->isValid($dados) ) {
            $model = new Default_Model_Escritorio($form->getValues());
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

        $form = $this->getFormEditar();
        if ( $form->isValid($dados) ) {

            //Não duplicar nomescritorio2
            $escritorio = $this->getById(array('idescritorio' => $form->getValue('idescritorio')));

            //Não alterando nome deixa salvar
            if ( $escritorio->nome == $form->getValue('nomescritorio2') ) {
                $model   = new Default_Model_Escritorio($form->getValues());
                $retorno = $this->_mapper->update($model);
                return $retorno;
            }

            $escritorio2 = $this->getByName(array('nomescritorio2' => $form->getValue('nomescritorio2')));
            //Zend_Debug::dump($escritorio2); exit;
            //sendo igual não permite update
            if ( count($escritorio2) > 0 ) {
                $this->errors[] = "Um registro com o nome {$form->getValue('nomescritorio2')} já existe no banco de dados.";
                return false;
            }

            $model   = new Default_Model_Escritorio($form->getValues());
            $retorno = $this->_mapper->update($model);
            return $retorno;

            //Caso formulario não for valido
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
            //$model = new Default_Model_Escritorio($dados);
            return $this->_mapper->excluir($dados);
        } catch ( Exception $exc ) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function getProjetosPorEscritorio($dados)
    {
        return $this->_mapper->getProjetosPorEscritorio($dados);
    }
    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    public function getByName($dados)
    {
        return $this->_mapper->getByName($dados);
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
        if ( $paginator ) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    public function mapaFetchPairs()
    {
        return $this->_mapper->mapaFetchPairs();
        /*
          $sql = "SELECT DISTINCT idescritorio,nomescritorio from agepnet200.tb_escritorio";
          $this->_db->fetchPairs($sql);
         */
    }

    public function nomeUnique()
    {

        $nomeUnique = new Zend_Validate_Db_NoRecordExists('tb_escritorio', 'nomescritorio2');
        return $nomeUnique;
    }
    
    
    public function fetchPairs()
    {
    	return $this->_mapper->fetchPairs();
    }
    public function selecionarTodoEscritorio()
    {
    	return $this->_mapper->selecionarTodoEscritorio();
    }
    


}

?>
