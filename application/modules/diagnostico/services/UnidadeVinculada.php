<?php

class Diagnostico_Service_UnidadeVinculada extends App_Service_ServiceAbstract
{

    /**
     *
     * @var Diagnostico_Model_Mapper_UnidadeVinculada
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
        $this->_mapper = new Diagnostico_Model_Mapper_UnidadeVinculada();

    }

    /**
     * Cadastro de unidades vinculadas
     * @param array $dados
     * @return boolean
     */
    public function inserir($dados)
    {
        foreach ($dados["unidades-vinculadas"] as $u) {
            $modelUnidadeVinculada = new Diagnostico_Model_UnidadeVinculada();
            $modelUnidadeVinculada->iddiagnostico = $dados['iddiagnostico'];
            $modelUnidadeVinculada->idunidade = $u;
            $modelUnidadeVinculada->idunidadeprincipal = $dados['idunidadeprincipal'];
            $retorno = $this->_mapper->insert($modelUnidadeVinculada);
            //Zend_Debug::dump($retorno);die;
        }
        return true;
    }

    /**
     * Remover todos as unidade vinculadas do diagnostico
     * @param array $dados
     *
     */
    public function excluir($dados)
    {
        return $this->_mapper->deletar($dados);
    }

    /**
     * Retornar a unidade vinculadas por diagnostico e unidade principal
     * @param array $params
     * @return array
     */

    public function retornaUnidadeVinculadaByIdDiagonosticoAndUnidadePrincial($params)
    {
        return $this->_mapper->retornaUnidadeVinculadaByIdDiagonosticoAndUnidadePrincial($params);
    }

    /**
     * Retornar a unidade vinculadas por diagnostico
     * @param array $params
     * @return array
     */

    public function retornaUnidadeVinculadaByIdDiagonostico($params)
    {
        return $this->_mapper->retornaUnidadeVinculadaByIdDiagonosticoAndUnidadePrincial($params);
    }

    /**
     * Retornar a unidade subordinadas pela unidade pai
     * @param array $params
     * @return array
     */
    public function retornaUnidadeSubordinada($params)
    {
        return $this->_mapper->retornaUnidadeSubordinada($params);
    }

}