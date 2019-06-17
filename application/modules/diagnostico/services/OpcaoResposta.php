<?php

use Default_Service_Log as Log;


class Diagnostico_Service_OpcaoResposta extends App_Service_ServiceAbstract
{

    protected $_form;
    /**
     *
     * @var Diagnostico_Model_Mapper_OpcaoResposta
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
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->_mapper = new Diagnostico_Model_Mapper_OpcaoResposta();
        $this->auth = $login->retornaUsuarioLogado();
    }


    public function getErrors()
    {
        return $this->errors;
    }


    /**
     * @return Diagnostico_Form_Pergunta
     */
    public function getForm($params = null)
    {
        $this->_form = new Diagnostico_Form_OpcaoResposta();
        $arrayPos = array();

        $opcoes = $this->_mapper->retornaTodasRespostas($params);

        if (count($opcoes) > 0) {
            foreach ($opcoes as $p) {
                if (!empty($p['ordenacao'])) {
                    $arrayPos[] = $p['ordenacao'];
                }
            }
        }
        if (!empty($params['idresposta']) && isset($params['idresposta'])) {
            $this->_form->populate(array(
                'idresposta' => (int)$params['idresposta'],
            ));
        }

        $this->_form->populate(array(
            'idpergunta' => (int)$params['idpergunta'],
            'idquestionario' => (int)$params['idquestionario'],
            'posicaocad' => implode('|', $arrayPos),

        ));
        return $this->_form;
    }

    public function retornaTodasRespostas($params)
    {
        try {
            return $this->_mapper->retornaTodasRespostas($params);
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            return false;
        }
    }

    /**
     * @param array $params
     * @param boolean $paginator
     * @return App_Service_JqGrid || boolean
     */
    public function retornaTodasOpcoesRespostasPorPergunta($params, $paginator)
    {
        try {
            $dados = $this->_mapper->retornaTodasOpcoesRespostasPorPergunta($params, $paginator);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            return false;
        }
    }

    /**
     * Lista todos as respostas.
     * @param array $params
     * return array
     */
//    public function listar($params = array())
//    {
//
//    }

//    public function getById($dados){
//        return $this->_mapper->getById($dados);
//    }

    /**
     * Exclusão de opções de respostas do questionario
     * @param array $params
     * @return boolean
     */
    public function excluir($params)
    {
        try {
            return $this->_mapper->deleteResposta($params);
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    /**
     * Prepara a model de opções resposta do questionario
     * @param array $dados
     * @return Diagnostico_Model_OpcaoResposta
     */

    private function populaModel($dados)
    {
        $model = new Diagnostico_Model_OpcaoResposta();
        $model->idresposta = (isset($dados['idresposta']) && (!empty($dados['idresposta']))) ? (int)$dados['idresposta'] : null;
        $model->idpergunta = (int)$dados['idpergunta'];
        $model->idquestionario = (int)$dados['idquestionario'];
        $model->desresposta = $dados['desresposta'];
        $model->escala = (!empty($dados['escala'])) ? (int)$dados['escala'] : null;
        $model->ordenacao = (int)$dados['ordenacao'];
        return $model;
    }

    /**
     * Cadastro de opções de respostas do questionario
     * @param array $dados
     * @return Diagnostico_Model_OpcaoResposta || boolean
     */
    public function inserir($dados)
    {
        $form = $this->getForm($dados);
        if ($form->isValidPartial($dados)) {
            try {
                $model = $this->populaModel($dados);
                $retorno = $this->_mapper->insert($model);
                return $retorno;
            } catch (Exception $exc) {
                Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
                throw $exc;
                return false;
            }
        } else {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $form->getErrorMessages()));
            return false;
        }
    }

    /**
     * Alteração de opções de respostas do questionario
     * @param array $dados
     * @return Diagnostico_Model_OpcaoResposta || boolean
     */
    public function update($dados)
    {
        try {
            $model = $this->populaModel($dados);
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } catch (Exception $exc) {
            throw $exc;
            return false;
        }
    }

}
