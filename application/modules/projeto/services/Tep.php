<?php

class Projeto_Service_Tep extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Gerencia
     */
    protected $_mapper;
    protected $_mapperAtividadeCronograma;
    protected $_dependencies = array(
        'db'
    );

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_Tep();
        $this->_timeInterval = new App_TimeInterval();
    }

    /**
     * @return Default_Form_Tep
     */
    public function getFormTep()
    {
        $formEditar = $this->_getForm('Projeto_Form_Tep');
        return $formEditar;
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function update($dados)
    {
        if (@trim($dados['desprojeto']) != "") {
            $dados['desprojeto'] = @mb_substr(@trim($dados['desprojeto']), 0, 4000);
        }
        if (@trim($dados['desobjetivo']) != "") {
            $dados['desobjetivo'] = @mb_substr(@trim($dados['desobjetivo']), 0, 4000);
        }
        if (@trim($dados['desconsideracaofinal']) != "") {
            $dados['desconsideracaofinal'] = @mb_substr(@trim($dados['desconsideracaofinal']), 0, 4000);
        }
        $form = $this->getFormTep();
        if ($form->isValidPartial($dados)) {
            $values = $form->getValues();
            $model = new Projeto_Model_Tep($values);
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
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

}

?>

