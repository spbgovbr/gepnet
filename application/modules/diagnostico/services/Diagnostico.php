<?php

class Diagnostico_Service_Diagnostico extends App_Service_ServiceAbstract
{

    protected $_form;
    /**
     *
     * @var Diagnostico_Model_Mapper_Diagnostico
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
        $this->_mapper = new Diagnostico_Model_Mapper_Diagnostico();
        $this->auth = $login->retornaUsuarioLogado();
    }

    /**
     * @return Diagnostico_Form_Diagnostico
     */
    public function getForm($idDiagnostico = null)
    {
        $this->_form = new Diagnostico_Form_Diagnostico();
        $fetchPairPessoa = $this->fetchPairs($idDiagnostico);

        $this->_form->getElement('pessoas')->addMultiOptions($fetchPairPessoa);
        if (!empty($idDiagnostico)) {
            $this->_form->getElement('idunidadeprincipal')->setAttrib('disabled', 'disabled');
        }
        return $this->_form;

    }


    public function getSequence($params = null)
    {
        date_default_timezone_set('America/Sao_Paulo');
        if (!empty($params) || isset($params)) {
            $unidadeSelecionada = $this->_mapper->getNomeUnidade($params);
        } else {
            $unidadeSelecionada["sigla"] = '';
        }
        $anoAtual = date('Y');
        $sequence = (string)$this->_mapper->getSequenceDiagnostico()["nextval"];
        $codigo = $anoAtual . '.' . str_pad($sequence, 2, "0", STR_PAD_LEFT) . ' - ' . $unidadeSelecionada["sigla"];
        return $codigo;
    }


    /**
     * @return Diagnostico_Form_Diagnostico
     */
    public function getFormClonar($idDiagnostico = null)
    {
        $this->_form = new Diagnostico_Form_Diagnostico();
        $fetchPairPessoa = $this->fetchPairs($idDiagnostico);
        $this->_form->getElement('pessoas')->addMultiOptions($fetchPairPessoa);
        return $this->_form;

    }

    public function getFormPesquisar()
    {
        $this->_form = new Diagnostico_Form_DiagnosticoPesquisar();
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function fetchPairs($idDiagnostico)
    {
        return $this->_mapper->fetchPairs($idDiagnostico);
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

    public function excluir($id)
    {
        return $this->_mapper->delete($id);
    }

    /**
     * Prepara a model de diagnostico
     * @param array $dados
     * @return Diagnostico_Model_Diagnostico
     */

    private function populaModel($dados)
    {
        $model = new Diagnostico_Model_Diagnostico();
        $model->iddiagnostico = (isset($dados['iddiagnostico']) && (!empty($dados['iddiagnostico']))) ? (int)$dados['iddiagnostico'] : null;
        $model->dsdiagnostico = $dados['dsdiagnostico'];
        $model->idunidadeprincipal = (int)$dados['idunidadeprincipal'];
        $model->dtinicio = new Zend_Db_Expr("to_date('" . $dados['dtinicio'] . "','DD/MM/YYYY')");
        $model->dtencerramento = new Zend_Db_Expr("to_date('" . $dados['dtencerramento'] . "','DD/MM/YYYY')");
        return $model;
    }


    public function update($dados)
    {

        $model = $this->populaModel($dados);
        //Zend_Debug::dump($model);die;
        $model = $this->_mapper->update($model);
        $serviceParteDiagnostico = new Diagnostico_Service_Partediagnostico();
        $serviceUnidadeVinculada = new Diagnostico_Service_UnidadeVinculada();
        $deletePartes = $serviceParteDiagnostico->deletePartes($dados);
        $retorno = $serviceParteDiagnostico->inserir($dados);
        $resporta = $serviceUnidadeVinculada->excluir($dados);
        $resultado = $serviceUnidadeVinculada->inserir($dados);
        return $model;

    }

    public function fetchPairsPorDiagnostico($param)
    {
        return $this->_mapper->fetchPairsPorDiagnostico($param);
    }

    public function getCheckbox($param)
    {
        return $this->_mapper->getCheckbox($param);
    }

    public function getListarUnidadePrincipalFetchPairs()
    {
        $arrayUnidPrincipal = [];
        foreach ($this->_mapper->getListarUnidadePrincipal() as $u) {
            $arrayUnidPrincipal[$u['id_unidade']] = $u['sigla'];
        }
        return $arrayUnidPrincipal;
    }

    public function getUnidadesFilhas($idPai)
    {
        return $this->_mapper->getUnidadesFilhas($idPai);
    }

    public function inserir($dados)
    {
        $serviceParteDiagnostico = new Diagnostico_Service_Partediagnostico();
        $serviceUnidadeVinculada = new Diagnostico_Service_UnidadeVinculada();
        try {
            $dados['idcadastrador'] = $this->auth->idpessoa;
            $modelDiagnostico = new Diagnostico_Model_Diagnostico($dados);

            $strAno = explode('.', $dados['dsdiagnostico']);
            $strSequence = explode(' - ', $strAno[1]);

            $modelDiagnostico['ano'] = $strAno[0];
            $modelDiagnostico['sq_diagnostico'] = $strSequence[0];

            /** @var  $retorno Diagnostico_Model_Diagnostico */
            $modelRetornado = $this->_mapper->insert($modelDiagnostico);
            $dados['iddiagnostico'] = $modelRetornado->iddiagnostico;
            /** Insere os dados na tabela de partes do diagnóstico. */
            $retorno = $serviceParteDiagnostico->inserir($dados);
            /** Inserir unidades vinculadas a unidade principal */
            $resultado = $serviceUnidadeVinculada->inserir($dados);
            return $modelDiagnostico;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            throw $exc;
        }
    }

}
