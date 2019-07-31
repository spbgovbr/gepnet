<?php

use Default_Service_Log as Log;

class Diagnostico_Service_Pergunta extends App_Service_ServiceAbstract
{

    protected $_form;
    /**
     *
     * @var Diagnostico_Model_Mapper_Pergunta
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
        $this->_mapper = new Diagnostico_Model_Mapper_Pergunta();
        $this->auth = $login->retornaUsuarioLogado();
    }

    /**
     * @return Diagnostico_Form_Pergunta
     */
    public function getForm($params = null)
    {
        $idquestionario = null;
        if (isset($params['idquestionariodiagnostico'])) {
            $idquestionario = (int)$params['idquestionariodiagnostico'];
        } else {
            $idquestionario = (int)$params['idquestionario'];
        }


        $this->_form = new Diagnostico_Form_Pergunta();
        $itensSecao = new Diagnostico_Service_ItemSecao();
        $fetchPairQuest = $itensSecao->fetchPairPerguntaQuest($idquestionario);
        $this->_form->getElement('id_secao')->addMultiOptions($fetchPairQuest);
        $arrayPos = array();
        foreach ($this->_mapper->retornaPerguntas(array('idquestionario' => $idquestionario)) as $p) {
            if (!empty($p['posicao']) && $p['posicao'] != '|') {
                $arrayPos[] = $p['posicao'];
            }
        }

        $this->_form->populate(array(
            'idquestionario' => $idquestionario,
            'posicaocad' => implode('|', $arrayPos),

        ));
        return $this->_form;
    }

    public function getFormEditar($params = null)
    {
        $idquestionario = null;
        $idquestionario = (int)$params['idquestionario'];

        $this->_form = new Diagnostico_Form_Pergunta();
        $itensSecao = new Diagnostico_Service_ItemSecao();
        $fetchPairQuest = $itensSecao->fetchPairPerguntaQuest($idquestionario);
        $this->_form->getElement('id_secao')->addMultiOptions($fetchPairQuest);
        $arrayPos = array();
        foreach ($this->_mapper->retornaPerguntas(array('idquestionario' => $idquestionario)) as $p) {
            if (!empty($p['posicao']) && $p['posicao'] != '|') {
                $arrayPos[] = $p['posicao'];
            }
        }

        $this->_form->populate(array(
            'idquestionario' => $idquestionario,
            'idpergunta' => $params['idpergunta'],
            'posicaocad' => implode('|', $arrayPos),
        ));
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }


    /**
     * Lista todos as perguntas.
     * @param array $params
     * return array
     */
    public function listar($params = array())
    {
        try {
            $dados = $this->_mapper->listar($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    public function getById($dados, $model)
    {
        return $this->_mapper->getById($dados, $model);
    }

    public function buscaTodasPerguntasPorQuestionario($params)
    {
        try {
            $dados = $this->_mapper->buscaTodasPerguntasPorQuestionario($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            return false;
        }
    }

    public function retornaPerguntas($dados)
    {
        return $this->_mapper->retornaPerguntas($dados);
    }

    public function excluir($id)
    {
        return $this->_mapper->delete($id);
    }

    public function deletePergunta($params)
    {
        return $this->_mapper->deletePergunta($params);
    }

    public function getPosicao($parans)
    {
        return $this->_mapper->getPosicao($parans);
    }

    public function getPosicaoUpdate($parans)
    {
        return $this->_mapper->getPosicaoUpdate($parans);
    }

    /**
     * Prepara a model de pergunta
     * @param array $dados
     * @return Diagnostico_Model_Pergunta
     */

    private function populaModel($dados)
    {
        $model = new Diagnostico_Model_Pergunta();
        $model->idpergunta = (!empty($dados['idpergunta']) && isset($dados['idpergunta'])) ? (int)$dados['idpergunta'] : null;
        $model->idpergunta = $dados['idpergunta'];
        $model->dspergunta = '-';
        $model->tipopergunta = $dados['tipopergunta'];
        $model->ativa = $dados['ativa'];
        $model->idquestionario = $dados['idquestionariodiagnostico'];
        $model->posicao = $dados['posicao'];
        $model->id_secao = $dados['id_secao'];
        $model->tiporegistro = $dados['tiporegistro'];
        $model->dstitulo = $dados['dstitulo'];

        return $model;
    }

    /**
     * Cadastra opções de resposta da pergunta
     * @param array $opcoes
     * @param Diagnostico_Model_Pergunta $model
     * @return boolean
     */

    private function adicionarOpcoesResposta($opcoes, $model)
    {
        $serviceRespQuest = new Diagnostico_Service_OpcaoResposta();
        $resultado = $serviceRespQuest->inserir($opcoes, $model->idpergunta);
        return $resultado;
    }


    public function inserir($dados)
    {
        try {
            $dados['ativa'] = ($dados['ativa'] == '1') ? 't' : 'f';
            $model = new Diagnostico_Model_Pergunta($dados);
            $model->idpergunta = $this->_mapper->insert($model);
            return $model;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    public function retornaPerguntaObrigatoria($params)
    {
        $array = $this->_mapper->retornaPerguntaObrigatoria($params);

        if (count($array) > 0) {
            $pred = preg_replace('/[^A-Za-z0-9-\/\][^,]/', '', $array['pergunta']);
            $perguntas = explode(',', $pred);
            $arrayPerguntas = array();
            foreach ($perguntas as $pergunta) {
                if (count($arrayPerguntas) == 0) {
                    $arrayPerguntas[] = (int)$pergunta;
                } else {
                    array_push($arrayPerguntas, (int)$pergunta);
                }
            }
        }
        return $arrayPerguntas;
    }


    public function update($dados)
    {
        try {
            $dados['ativa'] = ($dados['ativa'] == '1') ? 't' : 'f';
            $model = new Diagnostico_Model_Pergunta($dados);
            $retorno = $this->_mapper->update($model);
            return $retorno;
        } catch (Exception $exc) {
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
            return false;
        }
    }

    /**
     * Função que retornar as perguntas de um questionario.
     * @param array $params
     * @return array || boolean
     */
    public function retornaPerguntaQuestionario($params)
    {
        $retorno = $this->_mapper->retornaPerguntaQuestionario($params);
        return $retorno;
    }

}
