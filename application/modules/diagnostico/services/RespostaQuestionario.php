<?php

class Diagnostico_Service_RespostaQuestionario extends App_Service_ServiceAbstract
{

    protected $_form;
    /**
     *
     * @var Diagnostico_Model_Mapper_RespostaQuestionario
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
        $this->_mapper = new Diagnostico_Model_Mapper_RespostaQuestionario();
        $this->auth = $login->retornaUsuarioLogado();
    }

    /**
     * @return Diagnostico_Form_RespostaQuestionario
     */
    public function getForm($params)
    {
        $form = $this->_getForm('Diagnostico_Form_ResponderQuestionario');
        $form->populate(array(
            'iddiagnostico' => (int)$params['iddiagnostico'],
            'idquestionariodiagnostico' => (int)$params['idquestionariodiagnostico'],
            'idpessoaresponde' => $this->auth->idpessoa,
            'numero' => (int)$params['numero']
        ));
        return $form;
    }


    public function getErrors()
    {
        return $this->errors;
    }

    public function validaPerguntaObrigatoria($dados)
    {
        $service = new Diagnostico_Service_Pergunta();
        $arrayPerguntaObrigatoria = $service->retornaPerguntaObrigatoria($dados);
        $arrayPerguntaRespondida = array();

        if (!empty($dados['textareaResposta']) && count($dados['textareaResposta']) > 0) {
            foreach ($dados['textareaResposta'] as $key => $value) {

                if (count($arrayPerguntaRespondida) == 0) {
                    //Zend_Debug::dump($value);die;
                    foreach ($value as $i => $dsresposta) {

                        if (!empty($dsresposta)) {
                            $arrayPerguntaRespondida[] = $key;
                        }
                    }
                } else {
                    foreach ($value as $i => $dsresposta) {
                        if (!empty($dsresposta)) {
                            array_push($arrayPerguntaRespondida, $key);
                        }
                    }
                }
            }
        }

        if (!empty($dados['checkbox']) && count($dados['checkbox']) > 0) {
            foreach ($dados['checkbox'] as $idPerguntas => $respostas) {
                if (count($arrayPerguntaRespondida) == 0) {
                    $arrayPerguntaRespondida[] = $idPerguntas;
                } else {
                    array_push($arrayPerguntaRespondida, $idPerguntas);
                }
            }
        }
        if (!empty($dados['radio']) && count($dados['radio']) > 0) {
            foreach ($dados['radio'] as $key => $value) {
                if (count($arrayPerguntaRespondida) == 0) {
                    $arrayPerguntaRespondida[] = $key;
                } else {
                    array_push($arrayPerguntaRespondida, $key);
                }
            }
        }
        return (count($arrayPerguntaRespondida) >= count($arrayPerguntaObrigatoria));
    }

    /**
     * Cadastro de respostas do questionario
     * @param array $dados
     * @return boolean
     */
    public function inserir($dados)
    {
        $servicoRespostaQuestionariodiagnostico = new Diagnostico_Service_RespostaQuestionarioDiagnostico();
        $retorno = false;
        if (count($dados) > 0) {
            if (!empty($dados['textareaResposta']) && count($dados['textareaResposta']) > 0) {
                foreach ($dados['textareaResposta'] as $key => $value) {
                    foreach ($value as $dsresposta) {
                        $model = new Diagnostico_Model_RespostaQuestionario();
                        $model->ds_resposta_descritiva = trim($dsresposta);
                        $model->idpergunta = $key;
                        $model->idquestionariodiagnostico = (int)$dados['idquestionariodiagnostico'];
                        $model->iddiagnostico = (int)$dados['iddiagnostico'];
                        $model->nrquestionario = (int)$dados['numero'];
                        /** @var Diagnostico_Model_RespostaQuestionario $newModel */
                        $newModel = $this->_mapper->insert($model);
                        $params = array(
                            'id_resposta_pergunta' => $newModel->id_resposta_pergunta,
                            'idquestionario' => $dados['idquestionariodiagnostico'],
                            'iddiagnostico' => $dados['iddiagnostico'],
                            'numero' => $dados['numero']
                        );

                        $historico = $servicoRespostaQuestionariodiagnostico->inserirHistoricoRespostas($params);
                        $retorno = true;
                    }
                }
            }

            if (!empty($dados['checkbox']) && count($dados['checkbox']) > 0) {
                foreach ($dados['checkbox'] as $idPerguntas => $respostas) {
                    foreach ($respostas as $idresposta) {
                        $model = new Diagnostico_Model_RespostaQuestionario();
                        $model->idresposta = (int)$idresposta;
                        $model->idpergunta = (int)$idPerguntas;
                        $model->idquestionariodiagnostico = (int)$dados['idquestionariodiagnostico'];
                        $model->iddiagnostico = (int)$dados['iddiagnostico'];
                        $model->nrquestionario = (int)$dados['numero'];
                        /** @var Diagnostico_Model_RespostaQuestionario $newModel */
                        $newModel = $this->_mapper->insert($model);
                        $params = array(
                            'id_resposta_pergunta' => $newModel->id_resposta_pergunta,
                            'idquestionario' => $dados['idquestionariodiagnostico'],
                            'iddiagnostico' => $dados['iddiagnostico'],
                            'numero' => $dados['numero']
                        );
                        $historico = $servicoRespostaQuestionariodiagnostico->inserirHistoricoRespostas($params);
                        $retorno = true;
                    }
                }
            }
            if (!empty($dados['radio']) && count($dados['radio']) > 0) {
                foreach ($dados['radio'] as $key => $value) {
                    $model = new Diagnostico_Model_RespostaQuestionario();
                    $model->idresposta = (int)$value;
                    $model->idpergunta = (int)$key;
                    $model->idquestionariodiagnostico = (int)$dados['idquestionariodiagnostico'];
                    $model->iddiagnostico = (int)$dados['iddiagnostico'];
                    $model->nrquestionario = (int)$dados['numero'];
                    /** @var Diagnostico_Model_RespostaQuestionario $newModel */
                    $newModel = $this->_mapper->insert($model);
                    $params = array(
                        'id_resposta_pergunta' => $newModel->id_resposta_pergunta,
                        'idquestionario' => $dados['idquestionariodiagnostico'],
                        'iddiagnostico' => $dados['iddiagnostico'],
                        'numero' => $dados['numero']
                    );
                    $historico = $servicoRespostaQuestionariodiagnostico->inserirHistoricoRespostas($params);
                    $retorno = true;
                }
            }
        }
        return $retorno;
    }

}
