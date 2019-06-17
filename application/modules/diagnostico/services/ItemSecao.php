<?php

class Diagnostico_Service_ItemSecao extends App_Service_ServiceAbstract
{

    protected $_form;
    /**
     *
     * @var Diagnostico_Model_Mapper_ItemSecao
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
        $this->_mapper = new Diagnostico_Model_Mapper_ItemSecao();
        $this->auth = $login->retornaUsuarioLogado();
    }

    /**
     * @param array $params
     * @return Diagnostico_Form_ItemSecao
     */
    public function getForm($params)
    {
        $this->_form = new Diagnostico_Form_ItemSecao();

        $fetchPairsSecoes = $this->_mapper->fetchPairsSecoes($params);
        $this->_form->getElement('ds_item')->addMultiOptions($fetchPairsSecoes);

        $fetchPairQuest = $this->fetchPairQuest($params);
        $this->_form->getElement('id_secao')->addMultiOptions($fetchPairQuest);

        $this->_form->populate(array(
                'tpquestionario' => $params['tpquestionario'],
                'idquestionariodiagnostico' => (int)$params['idquestionariodiagnostico']
            )
        );
        return $this->_form;
    }


    public function getFormPesquisar()
    {
        $this->_form = new Diagnostico_Form_ItemSecaoPesquisar();
        return $this->_form;
    }

    public function getErrors()
    {
        return $this->errors;
    }


    /**
     * Lista todas as seções.
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

    public function getByIdClone($dados)
    {
        return $this->_mapper->getByIdClone($dados);
    }

    public function excluir($id)
    {
        return $this->_mapper->delete($id);
    }

    /**
     * Prepara a model de seções
     * @param array $dados
     * @return Diagnostico_Model_ItemSecao
     */

    private function populaModel($dados)
    {

        $model = new Diagnostico_Model_ItemSecao();
        $model->id_item = (isset($dados['id_item']) && (!empty($dados['id_item']))) ? (int)$dados['id_item'] : null;
        $model->ds_item = $dados['ds_item'];
        $model->id_secao = $dados['id_secao'];
        $model->ativo = $dados['ativo'];
        $model->idquestionariodiagnostico = $dados['idquestionariodiagnostico'];
        return $model;
    }

    public function inserir($dados)
    {
        $model = new Diagnostico_Model_ItemSecao();
        unset($model->id_item);
        $model->ds_item = $dados['secaoTexto'];
        $model->id_secao = $dados['secao'];
        $model->ativo = true;
        $model->idquestionariodiagnostico = $dados['idquestionariodiagnostico'];

        try {
            $model->id_item = $this->_mapper->insert($model);
            return $model;
        } catch (Exception $exc) {
            $this->errors = App_Service_ServiceAbstract::ERRO_GENERICO;
            return false;
        }
    }

    public function fetchPairsSecoes($param = null)
    {
        return $this->_mapper->fetchPairsSecoes($param);
    }

    public function fetchPairPerguntaQuest($param = null)
    {
        return $this->_mapper->fetchPairPerguntaQuest($param);
    }

    public function fetchPairQuest($param = null)
    {
        return $this->_mapper->fetchPairQuest($param);
    }

    public function deleteItens($dados)
    {
        return $this->_mapper->deleteItens($dados);
    }

    public function montaArrayIdSecao($array)
    {
        $arr1 = array_filter(explode("{", $array));
        $arrPrincipal = array();
        if (is_array($arr1) && count($arr1) > 0) {
            $i = 1;
            foreach ($arr1 as $key => $value) {
                if ($i <= count($arr1)) {
                    if (strstr($value, "}")) {
                        $s1 = array_filter(explode("},", $value));
                        $s2 = explode(",", $s1[0]);
                        if (strstr($s2[1], "}")) {
                            $s2[1] = str_replace("}", "", $s2[1]);
                        }
                    }
                    array_push($arrPrincipal, $s2[0]);
                }
                $i++;
            }
        }
        return $arrPrincipal;
    }


    public function update($dados)
    {
        $this->_db->beginTransaction();
        try {
            $deleteItens = $this->_mapper->deleteItens($dados);
            $arrayIdSecao = $this->montaArrayIdSecao($dados['secao']);
            $arrayDsSecao = explode(",", $dados['secaoTexto']);

            if (count($arrayIdSecao) > 0 && count($arrayDsSecao) > 0) {

                foreach (array_combine($arrayIdSecao, $arrayDsSecao) as $idSecao => $dsSecao) {
                    $arr = array();
                    $arr['id_secao'] = (int)$idSecao;
                    $arr['ds_item'] = $dsSecao;
                    $arr['idquestionariodiagnostico'] = (int)$dados['idquestionariodiagnostico'];
                    $valores = $arr;
                    $id = $this->_mapper->insert($valores);

                }
                $this->_db->commit();

                return true;
            }
        } catch (SQLiteException $exc) {
            $this->_db->rollBack();
            throw $exc;
            return false;
        }
    }

    /**
     * Cadastramento de parte do diagnostico.
     * @params array $dados
     * @return boolean
     */
    public function inserirIntens($dados)
    {
        try {

        } catch (Exception $ex) {
            $ex->getMessage();
            return false;
        }
    }


    /**
     * Cadastramento de itens da seção.
     * @return boolean
     */
    private function cadastrarItens($dados)
    {

    }


}
