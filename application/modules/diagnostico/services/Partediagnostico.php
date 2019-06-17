<?php

class Diagnostico_Service_Partediagnostico extends App_Service_ServiceAbstract
{

    protected $_form;
    /**
     * @var Diagnostico_Model_Mapper_Partediagnostico
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     * $var Zend_Auth_Storage_Interface $auth
     */
    protected $auth;

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
        $login = new Default_Service_Login();
        $this->_mapper = new Diagnostico_Model_Mapper_Partediagnostico();
        $this->auth = $login->retornaUsuarioLogado();
    }

    /**
     * @return Diagnostico_Form_Diagnostico
     */
    public function getForm()
    {
        return $this->_getForm('Diagnostico_Form_Diagnostico');
        return $this->_form;
    }

    /**
     * @param array $params
     * @return boolean
     */
    public function isPessoaParteInteressadaByDiagnostico($params)
    {
        return $this->_mapper->isPessoaParteInteressadaByDiagnostico($params);
    }

    /**
     * @param array $params
     * @return array || Diagnostico_Model_Partediagnostico
     */
    public function retornarParteByIdPessoa($params, $parte, $array)
    {
        return $this->_mapper->retornarParteByIdPessoa($params, $parte, $array);
    }

    /** Popular a model das partes
     * @params array $dados
     * @params string $qualificacao
     * return Diagnostico_Model_Partediagnostico
     */
    private function popularModel($dados, $qualificacao)
    {

        $model = new Diagnostico_Model_Partediagnostico();

        if (isset($dados['iddiagnostico']) && (!empty($dados['iddiagnostico']))) {
            $model->iddiagnostico = (int)$dados['iddiagnostico'];
        }
        $model->idcadastrador = (int)$this->auth->idpessoa;
        $model->datcadastro = new Zend_Db_Expr("now()");
        if (((int)$qualificacao) === $model::CHEFE_DA_UNIDADE_DIAGNOSTICADA) {
            $model->idpessoa = (int)$dados['idchefedaunidade'];
            $model->qualificacao = (int)$model::CHEFE_DA_UNIDADE_DIAGNOSTICADA;
            $model->tppermissao = (int)$model::VISUALIZAR;
        } elseif (((int)$qualificacao) === $model::PONTO_FOCAL_UNIDADE_DIAGNOSTICADA) {
            $model->idpessoa = (int)$dados['idpontofocal'];
            $model->qualificacao = (int)$model::PONTO_FOCAL_UNIDADE_DIAGNOSTICADA;
            $model->tppermissao = (int)$model::EDITAR;
        } elseif (((int)$qualificacao) === $model::EQUIPE_DE_DIAGNOSTICO) {
            $model->idpessoa = (int)$dados['idPessoaEquipe'];
            $model->qualificacao = (int)$model::EQUIPE_DE_DIAGNOSTICO;
            $model->tppermissao = (int)$model::EDITAR;
        }
        return $model;
    }

    /**
     * Cadastramento da equipe do diagnostico.
     * @params array $equipes, int $iddiagnostico
     * @return boolean
     */
    private function cadastrarEquipe($dados)
    {
        $servicePermissaoDiagnostico = new Diagnostico_Service_Permissaodiagnostico();
        try {
            $arrayEquipe = explode(",", $dados['pessparte']);

            if (count($arrayEquipe) > 0) {
                //Cadastro de equipe
                foreach ($arrayEquipe as $equipe) {
                    $arr = array();
                    $arr['iddiagnostico'] = $dados['iddiagnostico'];
                    $arr['idPessoaEquipe'] = (int)$equipe;

                    /** @var $modelEquipe Diagnostico_Model_Partediagnostico */
                    $modelEquipe = $this->popularModel($arr, 3);
                    $idparteequipe = $this->_mapper->insert($modelEquipe);
                    $servicePermissaoDiagnostico->permissaoNoDiagnostico($modelEquipe);
                }
                return true;
            }
        } catch (Exception $ex) {
            $ex->getMessage();
            return false;
        }
    }


    /**
     * Cadastramento de parte do diagnostico.
     * @params array $dados
     * @return boolean
     */
    public function inserir($dados)
    {
        $servicePermissaoDiagnostico = new Diagnostico_Service_Permissaodiagnostico();
        //try {
        //Cadastro da Parte Chefe de unidade
        $modelChefeUnidade = $this->_mapper->insert($this->popularModel($dados, 1));
        $servicePermissaoDiagnostico->permissaoNoDiagnostico($modelChefeUnidade);

        //Cadastro da Parte Ponto Focal
        $modelPontoFocal = $this->_mapper->insert($this->popularModel($dados, 2));
        $servicePermissaoDiagnostico->permissaoNoDiagnostico($modelPontoFocal);

        //Cadastro da Equipe
        if (!empty($dados['pessparte'])) {
            $this->cadastrarEquipe($dados);
        }
        return true;
//        }catch (Exception $ex){
//            $ex->getMessage();
//            return false;
//        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function deletePartes($dados)
    {
        return $this->_mapper->deletePartes($dados);
    }

}
