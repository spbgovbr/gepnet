<?php

class Default_Service_Perfilpessoa extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Default_Model_Mapper_Perfilpessoa
     */
    protected $_mapper;

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     *
     * @var Zend_Log
     */
    protected $_log;

    /**
     * @var array 
     */
    public $errors = array();
  

    public function init()
    {
        $this->_mapper = new Default_Model_Mapper_Perfilpessoa();
        $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getForm()
    {
        $form = new Default_Form_Perfilpessoa();
        return $form;
    }
    
    public function getFormAssociarPerfil()
    {
        $form = new Default_Form_AssociarPerfil();
        return $form;
    }
    
    /**
     * 
     * @param array $params
     * @param boolean $paginator
     * @return \Atividade_Service_JqGrid | array
     */
    public function pesquisar($params,$idperfil,$idescritorio, $paginator)
    {
        $dados = $this->_mapper->pesquisar($params,$idperfil,$idescritorio, $paginator);
        if ( $paginator ) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        }
        return $dados;
    }

    public function associarPerfil($dados)
    {
        //Zend_Debug::dump($dados); exit;
        $validator = new Zend_Validate_Db_NoRecordExists(
                            array(  'table'   => 'tb_perfilpessoa',
                                    'field'   => 'idpessoa',
                                    'schema'  => 'agepnet200'
                                )
        );
        $validator->getSelect()->where('idescritorio = ?', $dados['idescritorio'])
                               ->where('idperfil = ?',     $dados['idperfil']);
        
        $form = $this->getFormAssociarPerfil();
        $form->idpessoa->addValidator($validator);
        
        if ( $form->isValid($dados) ) {
             $model     = new Default_Model_Perfilpessoa($form->getValues());
             $retorno = $this->_mapper->associarPerfil($model);
             //Zend_debug::dump($retorno); exit;
            return $retorno;
        } else {
            $this->errors = $form->getMessages();
        }
        return false;
    }
    
    public function trocarSituacao($params){
         //Zend_Debug::dump($params); exit;
         $model  = new Default_Model_Perfilpessoa($params);
         $retorno = $this->_mapper->updateSituacao($model);
         return $retorno;
    }

    public function initCombo($objeto, $msg) {

         $listArray = array();
         $listArray = array('' => $msg);

         foreach ($objeto as $val => $desc) {
             if ($desc != $msg) {
                 $listArray[$val] = $desc;
             }
         }
         return $listArray;
     }

}
