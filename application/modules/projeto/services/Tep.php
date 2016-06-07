<?php

class Projeto_Service_Tep extends App_Service_ServiceAbstract {

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

    public function init() {
        $this->_mapper = new Projeto_Model_Mapper_Tep();
        $this->_timeInterval = new App_TimeInterval();
    }

    /**
     * @return Default_Form_Tep
     */
    public function getFormTep() {
        $formEditar = $this->_getForm('Projeto_Form_Tep');
        return $formEditar;
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function update($dados) {
        $form = $this->getFormTep();

        if ($form->isValidPartial($dados)) {
            $values = array_filter($form->getValues());
//            var_dump($values); exit;
            $model = new Projeto_Model_Tep($values);
//            var_dump($model); exit;
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function getById($dados) {
        return $this->_mapper->getById($dados);
    }
}
?>

