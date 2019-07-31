<?php

use Default_Service_Log as Log;

class Diagnostico_Service_QuestionarioVinculado extends App_Service_ServiceAbstract
{

    /**
     *
     * @var Diagnostico_Model_Mapper_QuestionarioVinculado
     */
    protected $_mapper;

    /**
     * @var Zend_Auth
     */
    protected $auth;


    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->_mapper = new Diagnostico_Model_Mapper_QuestionarioVinculado();
        $this->auth = $login->retornaUsuarioLogado();
    }

    /**
     * Função que popula o formulario que vincula um questionario.
     * @return Diagnostico_Form_PesquisaQuestionarioVinculado
     */
    public function getFormPesquisaQuestionarioVinculado($params)
    {
        $form = $this->_getForm('Diagnostico_Form_PesquisaQuestionarioVinculado');
        $form->populate(array(
            'iddiagnostico' => (int)$params['iddiagnostico'],
            'tpquestionario' => $params['tpquestionario']
        ));
        return $form;
    }

    /**
     * @return Diagnostico_Form_VincularQuestionario
     */
    public function getFormQuestionarioVincular($params)
    {
        $form = $this->_getForm('Diagnostico_Form_VincularQuestionario');
        $serviceQuestionarios = new Diagnostico_Service_Questionario();
        $form->getElement('ds_questionario')->setMultiOptions($serviceQuestionarios->fetchPairsQuestionario($params));
        $form->getElement('idquestionario')->setMultiOptions($serviceQuestionarios->fetchPairsQuestionarioVinculado($params));
        return $form;
    }

    /**
     * Função que vincula um ou varios questionários em um diagnóstico.
     * @params array
     * @return boolean
     */
    public function vincularQuestionario($params)
    {
        $retorno = false;

        foreach ($params['questionario'] as $questionario) {

            $params['idquestionario'] = (int)$questionario;

            if (!$this->_mapper->isQuestionarioVinculadoByQuestionarioAndDiagnostico($params)) {
                try {
                    $model = new Diagnostico_Model_QuestionarioVinculado();
                    $model->iddiagnostico = (int)$params['iddiagnostico'];
                    $model->idquestionario = (int)$questionario;
                    $model->idpesdisponibiliza = (int)$this->auth->idpessoa;
                    $model->dtdisponibilidade = new Zend_Db_Expr("now()");
                    $model->disponivel = Diagnostico_Model_QuestionarioVinculado::DISPONIVEL;
                    $retorno = $this->_mapper->insert($model);

                } catch (Exception $exc) {
                    Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
                    throw $exc;
                    $retorno = false;
                }
            }
        }
        return $retorno;
    }

    /**
     * Função que desvincula um ou varios questionários vinculados a um diagnóstico.
     * @params array
     * @return boolean
     */
    public function desvincularQuestionario($params)
    {

        try {
            $retorno = $this->_mapper->removeVinculo($params);
            if ($retorno) {
                return true;
            }
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    /**
     * Função que verifica se existe questionario vinculado
     * @param array $params
     * @return boolean
     */
    public function isQuestionarioVinculado($params)
    {
        return $this->_mapper->isQuestionarioVinculado($params);
    }

    /**
     * Função que verifica se existe questionario vinculado
     * @param array $params
     * @return boolean
     */
    public function isQuestionarioVinculadoByQuestionarioAndDiagnostico($params)
    {
        return $this->_mapper->isQuestionarioVinculadoByQuestionarioAndDiagnostico($params);
    }

    /**
     * Funcão que encerra o questionário vinculado para respostas
     * @param array $params
     * @return boolean
     */
    public function atualizaStatusQuestionario($params)
    {
        return $this->_mapper->atualizaStatusQuestionario($params);
    }

    /**
     * Funcão que retorna o questionário vinculado por numero, questionario, e diagnostico
     * @param array $params
     * @return array
     */
    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }


    /**
     * Função que lista os funcionários vinculados na grid.
     * @param array $params
     * @return App_Service_JqGrid || boolean
     */
    public function listaQuestionarioVinculado($params)
    {
        try {
            $dados = $this->_mapper->lista($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }


}