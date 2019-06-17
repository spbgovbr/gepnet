<?php

class Diagnostico_Service_Questionario extends App_Service_ServiceAbstract
{

    protected $_form;
    /**
     *
     * @var Diagnostico_Model_Mapper_Questionario
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
        $this->_mapper = new Diagnostico_Model_Mapper_Questionario();
        $this->auth = $login->retornaUsuarioLogado();
    }

    /**
     * @return Diagnostico_Form_Questionario
     */
    public function getForm()
    {
        return $this->_getForm('Diagnostico_Form_Questionario');
    }

    /**
     * @return Projeto_Form_Clonar
     */
    public function getFormClonar($params = null)
    {
        if (isset($params) && !empty($params)) {
            $form = $this->_getForm('Diagnostico_Form_Clonar');
            $form->populate($params);
        }
        return $form;
    }

    /**
     * @return Diagnostico_Form_QuestionarioPesquisar
     */
    public function getFormPesquisar()
    {
        $this->_form = new Diagnostico_Form_QuestionarioPesquisar();
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }


    /**
     * Lista todos os diagnósticos.
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
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    public function getByResposta($dados)
    {
        return $this->_mapper->getByResposta($dados);
    }

    public function excluir($params = array())
    {
        $opcaoResposta = new Diagnostico_Model_Mapper_RespostaQuestionario();
        $pergunta = new Diagnostico_Model_Mapper_Pergunta();
        $itemSecao = new Diagnostico_Model_Mapper_ItemSecao();
        $questionario = new Diagnostico_Model_Mapper_Questionario();
        try {
            $opcaoResposta->delete($params);
            $pergunta->delete($params);
            $itemSecao->deleteItens($params);
            $questionario->delete($params);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function fetchPairsQuestionario($param = null)
    {
        return $this->_mapper->fetchPairsQuestionario($param);
    }

    public function fetchPairsQuestionarioVinculado($param = null)
    {
        return $this->_mapper->fetchPairsQuestionarioVinculado($param);
    }

    public function excluirResp($params = array())
    {
        $resposta = new Diagnostico_Model_Mapper_OpcaoResposta();
        try {
            $resposta->deleteResposta($params);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    public function excluirPergResp($params = array())
    {
        $pergunta = new Diagnostico_Model_Mapper_Pergunta();
        try {
            //$result = $resposta->deleteResPergunta($params);
            $pergunta->deletePergunta($params);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * Prepara a model de questionario
     * @param array $dados
     * @return Diagnostico_Model_Questionario
     */

    private function populaModel($dados)
    {
        $model = new Diagnostico_Model_Questionario();
        $model->idquestionariodiagnostico = (isset($dados['idquestionariodiagnostico']) &&
            (!empty($dados['idquestionariodiagnostico']))) ?
            (int)$dados['idquestionariodiagnostico'] : null;
        $model->nomquestionario = $dados['nomquestionario'];
        $model->tipo = $dados['tipo'];
        $model->observacao = $dados['observacao'];
        $model->dtrespondido = $dados['dtrespondido'];
        $model->respondido = $dados['respondido'];

        return $model;
    }

    public function inserir($dados)
    {
        $form = $this->getForm();

        if ($form->isValid($dados)) {
            $model = new Diagnostico_Model_Questionario();
            $model->setFromArray($form->getValidValues($dados));
            $auth = Zend_Auth::getInstance();
            $model->idpescadastrador = $auth->getIdentity()->idpessoa;
            $model->respondido = 0;

            try {
                $model->idquestionariodiagnostico = $this->_mapper->insert($model);
                return $model;

            } catch (Exception $exc) {
                $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
                return false;
            }
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }


    public function inserirClonado($dados)
    {
        $form = $this->getForm();

        if ($form->isValid($dados)) {
            $model = new Diagnostico_Model_Questionario();
            $model->setFromArray($form->getValidValues($dados));
            $auth = Zend_Auth::getInstance();
            $model->idpescadastrador = $auth->getIdentity()->idpessoa;
            $model->respondido = 0;

            try {
                $model->idquestionariodiagnostico = $this->_mapper->insert($model);

                if ($model) {
                    $param = array(
                        'idquestionariodiagnostico' => $dados["idquestionario"],
                    );

                    /* Clona Itens da seção */
                    $cloneSecQuest = new Diagnostico_Model_Mapper_ItemSecao();
                    $clone = $cloneSecQuest->getByIdClone($param);

                    foreach ($clone as $c) {
                        unset($c["id_item"]);
                        unset($c["idquestionariodiagnostico"]);
                        $novoArray = $c + array('idquestionariodiagnostico' => $model->idquestionariodiagnostico);
                        $c = $cloneSecQuest->insert($novoArray);
                    }

                    /* Clona pergunta*/
                    $cloneItens = new Diagnostico_Model_Mapper_Pergunta();
                    $cloneItem = $cloneItens->getByIdCloneItens($dados["idquestionario"]);

                    if (count($cloneItem) > 0) {
                        foreach ($cloneItem as $ci) {
                            unset($ci["idpergunta"]);
                            unset($ci["idquestionario"]);
                            $novoArray = $ci + array('idquestionario' => $model->idquestionariodiagnostico);
                            $ci = $cloneItens->insertCopia($novoArray);
                            return $model->idquestionariodiagnostico;
                        }

                        /* Clona as opções de respostas*/
                        $opResp = new Diagnostico_Model_Mapper_OpcaoResposta();
                        $opcoesAnteriores = $opResp->getByIdOpRespAnterior($dados["idquestionario"]);
                        $opcoes = $opResp->getByIdOpResp($model->idquestionariodiagnostico);

                        if (count($opResp) > 0) {
                            $opAnterior = array();
                            foreach ($opcoesAnteriores as $op) {
                                $opAnterior[] = $op;
                            }
                            $opPosterior = array();
                            foreach ($opcoes as $o) {
                                $opPosterior[] = $o;
                            }
                            $op = $opResp->insertCopiaOpResp($opAnterior, $opPosterior,
                                $model->idquestionariodiagnostico);
                        }
                    }
                }
                return $model;

            } catch (Exception $exc) {
                $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
                return false;
            }
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    public function getMaxId()
    {
        return $this->_mapper->getMaxId();
    }

    public function update($dados)
    {

        $form = $this->getForm();
        if ($form->isValid($dados)) {
            $model = new Diagnostico_Model_Questionario($dados);
            $retorno = $this->_mapper->update($model);
            return $model;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }

    }

    public function getSecaoPerguntaOpcao($idquestionariodiagnostico)
    {
        $arrayQuestionario = $this->_mapper->getQuestionarioSecaoPerguntaOpcao(
            $idquestionariodiagnostico["idquestionariodiagnostico"]
        );
        $questionarioComp = array();
        foreach ($arrayQuestionario as $i => $q) {
            $questionarioComp['Seção ' . $q['id_secao'] . '|' . $q['ds_secao']][$q["idpergunta"]]['resposta'][$q["idresposta"]] = $q["desresposta"];
            $questionarioComp['Seção ' . $q['id_secao'] . '|' . $q['ds_secao']][$q["idpergunta"]]['numero'] = $q['posicao'];
            $questionarioComp['Seção ' . $q['id_secao'] . '|' . $q['ds_secao']][$q["idpergunta"]]['tipoRegistro'] = $q['tiporegistro'];
            $questionarioComp['Seção ' . $q['id_secao'] . '|' . $q['ds_secao']][$q["idpergunta"]]['obrigatoria'] = $q['ativa'];
            $questionarioComp['Seção ' . $q['id_secao'] . '|' . $q['ds_secao']][$q["idpergunta"]]['tipoReposta'] = $this->tipoResposta($q['tipopergunta']);
            $questionarioComp['Seção ' . $q['id_secao'] . '|' . $q['ds_secao']][$q["idpergunta"]]['descricaoPergunta'] = $q['dstitulo'];
        }
        return $questionarioComp;
    }

    public function tipoResposta($id = null)
    {
        $tipo = array(
            1 => 'Resposta',
            2 => 'Múltipla Escolha',
            3 => 'Seleção'
        );
        return $id ? $tipo[$id] : $tipo;
    }


}
