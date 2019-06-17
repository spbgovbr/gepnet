<?php

class Default_Service_Setor extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Default_Model_Mapper_Programa
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
        $this->_mapper = new Default_Model_Mapper_Setor();
    }

    /**
     * @return Default_Form_Programa
     */
    public function getForm()
    {
        return $this->_getForm('Default_Form_Programa', array('flaativo'));
    }

    /**
     * @return Default_Form_Programa
     */
    public function getFormPesquisar()
    {
        return $this->_getForm('Default_Form_ProgramaPesquisar');
    }

    /**
     * @return Default_Form_Programa_Editar
     */
    public function getFormEditar()
    {
        $formEditar = $this->_getForm('Default_Form_Programa', array('submit', 'reset'));
        $nomprograma = $formEditar->getElement('nomprograma');
        $nomprograma->setValidators(array(
            'NotEmpty',
            array('StringLength', false, array(0, 100)),
        ));

        $formEditar->removeElement('nomprograma');
        $formEditar->addElement($nomprograma);

        return $formEditar;
    }

    //put your code here
    public function inserir($dados)
    {
        $form = $this->getForm();

        if ($form->isValid($dados)) {
            $model = new Default_Model_Programa($form->getValues());
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
        if ($form->isValid($dados)) {

            //Não duplicar nomescritorio2
            $programa = $this->getById(array('idprograma' => $form->getValue('idprograma')));
            //Zend_Debug::dump($programa);exit;


            //Não alterando nome deixa salvar
            if ($programa['nomprograma'] == $form->getValue('nomprograma')) {
                $model = new Default_Model_Programa($form->getValues());
                $retorno = $this->_mapper->update($model);
                return $retorno;

            }

            $programa2 = $this->getByName(array('nomprograma' => $form->getValue('nomprograma')));
            //Zend_Debug::dump($escritorio2); exit;
            //sendo igual não permite update
            if (count($programa2) > 0) {
                $this->errors[] = "Um registro com o nome {$form->getValue('nomprograma')} já existe no banco de dados.";
                return false;
            }

            $model = new Default_Model_Programa($form->getValues());
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
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    public function retornaPorId($dados)
    {
        return $this->_mapper->retornaPorId($dados);
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
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    public function fetchPairs()
    {
        $resultado = $this->_mapper->fetchPairs();
        $retorno = array('' => 'Selecione');
        foreach ($resultado as $r => $v) {
            $retorno[$r] = $v;
        }
        return $retorno;
    }

    public function mapaFetchPairs()
    {
        return $this->_mapper->mapaFetchPairs();
    }

    public function nomeUnique()
    {

        $nomeUnique = new Zend_Validate_Db_NoRecordExists('tb_escritorio', 'nomescritorio2');
        return $nomeUnique;

    }
}

?>


