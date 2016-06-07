<?php

class Projeto_Service_ParteInteressada extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Projeto_Model_Mapper_ParteInteressada
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     * @var array
     */
    public $errors = array();

    public $auth = null;
    
    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Projeto_Model_Mapper_Parteinteressada();
    }

    /**
     * @return Projeto_Form_ParteInteressada
     */
    public function getForm()
    {
        return $this->_getForm('Projeto_Form_Parteinteressada');
    }
    
    /**
     * @return Projeto_Form_ParteInteressada
     */
    public function getFormExterno()
    {
        return $this->_getForm('Projeto_Form_ParteinteressadaExterno');
    }

    /**
     * @return Projeto_Form_ParteInteressada
     */
    public function getFormPesquisar()
    {
        return $this->_getForm('Projeto_Form_ParteinteressadaPesquisar');
    }

    /**
     * @return Projeto_Form_ParteInteressadaEditar
     */
    public function getFormEditar()
    {
        $formEditar     = $this->_getForm('Projeto_Form_Parteinteressada');
        return $formEditar;
    }
    
    public function insertExterno($dados) {
        $formExterno = $this->getFormExterno();
        
        if ( $formExterno->isValid($dados) ) {
                $model = new Projeto_Model_Parteinteressada();                
                //tratamento para popular model pelo metodo construtor 
                $model->setParteInteressadaExterna($formExterno->getValues());
                $model->idcadastrador       = $this->auth->idpessoa;
                $model->idparteinteressada = $this->_mapper->insert($model);
                return $model;
            } else {
                $this->errors = $formExterno->getMessages();
                return false;
            }
    }
    
    public function insertInterno($dados) {
        $form = $this->getForm();
        
        if ( $form->isValid($dados) ) {
              ///  $model = new Projeto_Model_Gerencia($form->getValues());
                $model = new Projeto_Model_Parteinteressada($form->getValues());
    //            print_r($model); exit;
                if(is_numeric($model->idparteinteressada)){
                    $servicePessoa = new Default_Service_Pessoa();
                    $pessoa = $servicePessoa->retornaPorId(array('idpessoa' => $dados['idparteinteressada']));
//                print_r($pessoa); exit;
                    $model->idcadastrador       = $this->auth->idpessoa;
                    $model->nomparteinteressada = $pessoa->nompessoa ? $pessoa->nompessoa : " - ";
                    $model->destelefone         = $pessoa->numfone ? $pessoa->numfone : " - ";
//                    $model->nomfuncao           = $pessoa->domcargo ? $pessoa->domcargo : " - ";
                    $model->nomfuncao           = $dados['nomfuncao'] ? $dados['nomfuncao'] : " - ";
                    $model->desemail            = $pessoa->desemail ? $pessoa->desemail : " - ";
                    $model->idpessoainterna     = $pessoa->idpessoa;

                    $model->idparteinteressada = $this->_mapper->insert($model);
                    return $model;
                }
            } else {
                $this->errors = $form->getMessages();
                return false;
            }
    }
    
    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function update($dados)
    {

        $form = $this->getFormEditar();
        if ( $form->isValid($dados) ) {
                $model   = new Projeto_Model_Parteinteressada($form->getValues());
                $retorno = $this->_mapper->update($model);
                return $retorno;
        } else {
                $this->errors = $form->getMessages();
                return false;
            }        
    }

    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function updateInterno($dados)
    {
        $form = $this->getForm();
        if ( $form->isValidPartial($dados) ) {
                   $parte = $this->_mapper->updateParte($form->getValidValues($dados));
                   return $parte;                 
        } else {
            $this->errors = $form->getMessages();
                return false;
        }
    }
    
    /**
     *
     * @param array $dados
     * @return boolean | array
     */
    public function updateExterno($dados)
    {
        $form = $this->getFormExterno();
        if ( $form->isValidPartial($dados) ) {
                   //tratamento de array para persistencia
                   $dataForm = $this->trataToPersist($form->getValidValues($dados));
                   $parte = $this->_mapper->updateParte($dataForm);
                   return $parte;
        } else {
            $this->errors = $form->getMessages();
                return false; 
        }
    }
    
    
    /**
     *
     * @param array $dados
     */
    public function excluir($dados)
    {
        try {
            //$model = new Default_Model_Gerencia($dados);
            return $this->_mapper->excluir($dados);
        } catch ( Zend_Db_Statement_Exception $exc ) {
            if($exc->getCode() == 23503) {                
              $this->errors = App_Service_ServiceAbstract::ERRO_VIOLACAO_FK_CODE_23503;
            }
        } catch ( Exception $exc ) {
            //Zend_Debug::dump($exc);exit;
            $this->errors = $exc->getMessage();
            return false;
        }
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }
    
    public function getByProjeto($dados)
    {
        return $this->_mapper->getByProjeto($dados);
    }


    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function pesquisar($params, $paginator)
    {
        $dados = $this->_mapper->pesquisar($params, $paginator);
        if ( $paginator ) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    public function fetchPairsPorProjeto($params, $selecione = true)
    {
        $resultado = $this->_mapper->fetchPairsPorProjeto($params);
        $retorno = array();

        if ( $selecione ) {
            $retorno[''] = 'Selecione';
        }

        foreach ( $resultado as $key => $value )
        {
            $retorno[$key] = $value;
        }
        return $retorno;
    }
    
    public function getParteInteressada($dados)
    {
        $resultado = $this->_mapper->getParteInteressada($dados);
        if ($resultado["idpessoainterna"] != NULL) {
            $servicePessoa = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
            $resultado = $servicePessoa->getById(array('idpessoa' => $resultado["idpessoainterna"]));
        }
        
        return $resultado;
    }

    public function retornaPorId($params,$model = false){
        return $this->_mapper->retornaPorId($params,$model);
    }
    
    public function retornaPartes($params,$model = false){
        return $this->_mapper->retornaPartes($params,$model);
    }
    
    
    public function retornaPartesGrid($params) {
        $dados = $this->_mapper->retornaPartesGrid($params);        
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
    }
    
    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function getParteInteressadaGrid($params, $paginator)
    {
        $dados = $this->_mapper->parteInteressadaGrid($params, $paginator);
        if ( $paginator ) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }
    
    /**
     * Altera o nome da chave do array para poder popular o form
     * @return array - $arrData
     */
    public function alteraKeyArray($data) 
    {
        $arrData = array();
        foreach ( $data as $key => $value ) {
            $strElement = $key.'externo';
            $arrData[$strElement] = $value;
        }
        return $arrData;
    }
    
    /**
     * Altera o nome da chave do array para persistir os dados
     * @return array - $arrData
     */
    public function trataToPersist($data) {
         $arrData = array();
        foreach ( $data as $key => $value ) {
            $strElement = str_replace('externo', '', $key);
            $arrData[$strElement] = $value;
        }        
        return $arrData;
    }
}

