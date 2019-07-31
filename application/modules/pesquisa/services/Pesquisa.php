<?php

class Pesquisa_Service_Pesquisa extends App_Service_ServiceAbstract
{

    public $_mapper = null;
    protected $_form = null;
    protected $auth = null;
    public $errors = array();

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Pesquisa_Model_Mapper_Pesquisa();
    }

    public function getFormPesquisar()
    {
        $this->_form = new Pesquisa_Form_PesquisaPesquisar();
        return $this->_form;
    }

    /**
     * Retorna relação de questionarios para montar grid de publicacao
     *
     * @param array $params - parametros do request
     * @return boolean|\App_Service_JqGrid
     */
    public function retornaQuestionarioPesquisaGrid($params)
    {
        try {
            $mapperQuestionario = new Pesquisa_Model_Mapper_Questionario();
            $dados = $mapperQuestionario->retornaQuestionarioPesquisaGrid($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function publicarPesquisa($params)
    {
        try {
            $this->_mapper->getDbTable()->getAdapter()->beginTransaction();
            $questionario = $this->getQuestionarioPublicarById($params);

            //monta insert tb_pesquisa
            $idpesquisa = $this->inserirPesquisa($questionario);
            $idquestionariopesquisa = $this->inserirQuestionarioPesquisa(array_merge($questionario,
                array('idpesquisa' => $idpesquisa)));
            $this->insereEstruturaQuestionarioFrasePesquisa(array_merge($questionario,
                array('idpesquisa' => $idpesquisa, 'idquestionariopesquisa' => $idquestionariopesquisa)));

            $this->_mapper->getDbTable()->getAdapter()->commit();
            return true;
        } catch (Exception $exc) {
            $this->_mapper->getDbTable()->getAdapter()->rollBack();
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function getQuestionarioPublicarById($params)
    {
        $mapperQuestionario = new Pesquisa_Model_Mapper_Questionario();
        $questionario = $mapperQuestionario->getQuestionarioPublicar($params);
        if ($questionario) {
            return $questionario;
        }
        throw new Exception('Questionário não disponível para publicação. ');
    }

    public function inserirPesquisa($params)
    {
        $pesquisaModel = new Pesquisa_Model_Pesquisa();
        $pesquisaModel->idquestionario = $params['idquestionario'];
        $pesquisaModel->idcadastrador = $this->auth->idpessoa;
        $pesquisaModel->idpespublica = $this->auth->idpessoa;
        $pesquisaModel->idpesquisa = $this->_mapper->insert($pesquisaModel);

        //insere a data de publicação para inserir no historico
        $pesquisaModel->datpublicacao = new Zend_Db_Expr('now()');
        $this->inserirHistoricoPublicacao($pesquisaModel);

        return $pesquisaModel->idpesquisa;
    }

    public function inserirQuestionarioPesquisa($params)
    {
        $questionarioPesquisa = App_Service_ServiceAbstract::getService('Pesquisa_Service_QuestionarioPesquisa');
        return $questionarioPesquisa->inserirQuestionarioPesquisa($params);
    }

    public function insereEstruturaQuestionarioFrasePesquisa($params)
    {
        $mapperQuestionarioFrasePesquisa = new Pesquisa_Model_Mapper_Questionariofrase();
        $resultset = $mapperQuestionarioFrasePesquisa->retornaPerguntasRespostasByQuestionario($params);

        $idfrase = '';
        foreach ($resultset as $result) {
            //se pergunta nao tiver sido cadastrada insere
            if ($idfrase != $result['tqf_idfrase']) {
                $idfrasepesquisa = $this->inserirFrasePesquisa($result);
                $idfrase = $result['tqf_idfrase'];
                //insere vinculo do questionario com a pergunta
                $this->inserirQuestionarioFrasePesquisa($result, $params['idquestionariopesquisa'], $idfrasepesquisa);
            }

            //se resposta no tiver sido cadastrada insere
            if (isset($result['tr_idresposta']) && $result['tr_idresposta'] != '') {
                $idrespostapesquisa = $this->inserirRespostaPesquisa($result);
                $this->inserirRespostaFrasePesquisa(array(
                    'idfrasepesquisa' => $idfrasepesquisa,
                    'idrespostapesquisa' => $idrespostapesquisa
                ));
            }
        }
    }

    public function inserirFrasePesquisa($params)
    {
        $frasePesquisa = App_Service_ServiceAbstract::getService('Pesquisa_Service_PerguntaPesquisa');
        return $frasePesquisa->inserirFrasePesquisa($params);
    }

    public function inserirRespostaPesquisa($params)
    {
        $respostaPesquisa = App_Service_ServiceAbstract::getService('Pesquisa_Service_RespostaPesquisa');
        return $respostaPesquisa->inserirRespostaPesquisa($params);
    }

    public function inserirRespostaFrasePesquisa($params)
    {
        $respostaFrasePesquisa = App_Service_ServiceAbstract::getService('Pesquisa_Service_RespostaPesquisa');
        return $respostaFrasePesquisa->inserirRespostaFrasePesquisa($params);
    }

    public function inserirQuestionarioFrasePesquisa($params, $idquestionariopesquisa, $idfrasepesquisa)
    {
        $questionarioFrasePesquisa = App_Service_ServiceAbstract::getService('Pesquisa_Service_QuestionariofrasePesquisa');
        return $questionarioFrasePesquisa->inserirQuestionarioFrasePesquisa($params, $idquestionariopesquisa,
            $idfrasepesquisa);
    }

    public function inserirHistoricoPublicacao($params)
    {
        $params = (array)$params;
        $historicoService = App_Service_ServiceAbstract::getService('Pesquisa_Service_HistoricoPublicacao');
        return $historicoService->inserirHistoricoPublicacao($params);
    }

    public function retornaPesquisasPublicadasGrid($params)
    {
        try {
            $dados = $this->_mapper->retornaQuestionarioPesquisaGrid($params);
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function publicaEncerraPesquisa($params)
    {
        try {
            $this->_mapper->getDbTable()->getAdapter()->beginTransaction();
            $dataPesquisa = $this->_mapper->retornaPesquisaById($params);
            $modelpesquisa = new Pesquisa_Model_Pesquisa($dataPesquisa);

            if ($modelpesquisa->situacao == Pesquisa_Model_Pesquisa::PUBLICADO) {
                $modelpesquisa->dtencerramento = new Zend_Db_Expr("now()");
                $modelpesquisa->situacao = Pesquisa_Model_Pesquisa::ENCERRADO;
                $modelpesquisa->idpesencerra = $this->auth->idpessoa;
            } else {
                $modelpesquisa->situacao = Pesquisa_Model_Pesquisa::PUBLICADO;
                $modelpesquisa->idpespublica = $this->auth->idpessoa;
                $modelpesquisa->datpublicacao = new Zend_Db_Expr("now()");
            }

            $pesquisa = $this->_mapper->update($modelpesquisa);
            $this->inserirHistoricoPublicacao($modelpesquisa);
            $this->_mapper->getDbTable()->getAdapter()->commit();
            return $pesquisa;
        } catch (Exception $exc) {
            $this->_mapper->getDbTable()->getAdapter()->rollBack();
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function detalharPesquisaById($params)
    {
        try {
            return $this->_mapper->detalharPesquisaById($params);
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function retornaPesquisaPublicadaById($params)
    {
        try {
            $result = $this->_mapper->retornaPesquisaPublicadaById($params);
            return $result;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function retornaEnunciadoPesquisa($params)
    {
        try {
            $result = $this->_mapper->retornaEnunciadoPesquisaById($params);
            return $result;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

}
