<?php

class Projeto_Service_Aceiteatividadecronograma extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_Aceiteatividadecronograma
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );
    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_Aceiteatividadecronograma();
    }

    public function getForm($params)
    {
        $serviceAtivCronograma = new Projeto_Service_AtividadeCronograma();
        $fetchPairMarco = $serviceAtivCronograma->fetchPairsMarcosPorEntrega($params);
        $arrayMarco = $this->initCombo($fetchPairMarco, 'Selecione');
        $form = $this->_getForm('Projeto_Form_Aceite');
        $form->getElement('idmarco')->setMultiOptions($arrayMarco);

        return $form;
    }

    public function inserir($params)
    {

        try {
            $modelAceiteAtividadeCronograma = new Projeto_Model_Aceiteatividadecronograma($params);
            $modelAceiteAtividadeCronograma->aceito = $params['flaaceite'];
            if ($modelAceiteAtividadeCronograma->aceito == "S") {
                $modelAceiteAtividadeCronograma->idpesaceitou = $params['idcadastrador'];
                $modelAceiteAtividadeCronograma->dataceitacao = new Zend_Db_Expr("now()");
            }
            $resultado = $this->_mapper->inserir($modelAceiteAtividadeCronograma);
            return $resultado;
        } catch (Exception $exc) {
            $this->errors = array('msg' => 'Não foi possível inserir o registro.');
            return false;
        }
    }

    public function editar($params)
    {
        try {
            $model = new Projeto_Model_Aceiteatividadecronograma($params);
            $model->aceito = $params['flaaceite'];
            $retorno = $this->_mapper->update($model);
            //Zend_Debug::dump($model);exit;
            return ($retorno ? true : false);
        } catch (Exception $exc) {
            $this->errors = array('msg' => 'Não foi possível atualizar o registro.');
            return false;
        }
    }

    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }


    public function excluir($dados)
    {
        return $this->_mapper->delete($dados);
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

    public function getErrors()
    {
        return $this->errors;
    }
}