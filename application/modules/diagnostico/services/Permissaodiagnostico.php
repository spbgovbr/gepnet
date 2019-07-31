<?php

class Diagnostico_Service_Permissaodiagnostico extends App_Service_ServiceAbstract
{

    protected $_form;
    //protected $_isAuthorized;

    /**
     * @var Diagnostico_Model_Mapper_Permissaodiagnostico
     */
    protected $_mapper;
    protected $_dependencies = array(
        'db'
    );

    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger;
    /**
     * @var array
     */
    protected $_forbiddenRoute = array(
        'controller' => 'error',
        'action' => 'forbidden',
        'module' => 'projeto'
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
        $this->_mapper = new Diagnostico_Model_Mapper_Permissaodiagnostico();
        $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $params
     * @return Diagnostico_Model_Permissaodiagnostico
     */
    public function inserir($params)
    {
        try {
            return $this->_mapper->insert($params);
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    /**
     * @param array $params
     * @return Diagnostico_Model_Permissaodiagnostico
     */
    public function editar($params)
    {
        try {
            return $this->_mapper->update($params);
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function excluir($params)
    {
        try {
            return $this->_mapper->excluir($params);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            return false;
        }
    }


    /**
     * @param array $param
     * @return array
     */
    public function getById($params)
    {
        return $this->_mapper->getById($params);
    }

    public function getByIdPermissaoParteInteressada($params)
    {
        return $this->_mapper->getByIdParteInteressada($params);
    }

    public function getByIdParteInteressadaByPermissao($params)
    {
        return $this->_mapper->getByIdParteInteressadaByPermissao($params);
    }

    public function updatePermissaoParteInteressada($params)
    {
        return $this->_mapper->updatePermissaoParteInteressada($params);
    }

    public function excluirPermissaoByParteInteressada($params)
    {
        return $this->_mapper->excluirPermissaoByParteInteressada($params);
    }

    public function verificaPermissaoByParteInteressadaAndDiagnostico($params)
    {
        return $this->_mapper->verificaPermissaoByParteInteressadaAndDiagnostico($params);
    }

    public function excluirPermissoesDiagnosticoByParteInteressada($params)
    {
        $retorno = false;

        //buscar o serviço de permissão projeto        
        $countPermissao = $this->verificaPermissaoByParteInteressadaAndDiagnostico($params);
        if ($countPermissao['count'] != 0) {
            $resultado = $this->_mapper->excluir($params);
            $retorno = true;
        }
        return $retorno;
    }


    /**
     * @param array $params
     * @return array
     */
    public function getPermissaoPorParte($params)
    {
        return $this->_mapper->getPermissaoPorParte($params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function fetchPairs($params)
    {
        return $this->_mapper->fetchPairs($params);
    }

    /**
     * Funcão de atribuir ou negar permissões para parte interessada no diagnostico.
     * @param Diagnostico_Model_Partediagnostico $parte
     * @return boolean
     */
    public function permissaoNoDiagnostico($parte)
    {
        try {
            $servicePermissao = new Default_Service_Permissao();
            $arrayParte = array(
                'iddiagnostico' => (int)$parte->iddiagnostico,
                'idpartediagnostico' => $parte->idpartediagnostico
            );

            //Verifica se existe permissões para a parte interessada
            $permissoes = $this->getByIdPermissaoParteInteressada($arrayParte);
            //Zend_Debug::dump($arrayParte);die;
            if (count($permissoes) > 0) {
                //Exclui as permissoes da parte interessada
                $retorno = $this->excluir($arrayParte);
            }

            if ($parte->tppermissao == $parte::VISUALIZAR) {//caso o tipo de permissao seja visualizar

                //Busca todas as permissões do tipo 'G' - Gerais.
                $arrayPermissao = $servicePermissao->retornaRecursoEPermissaoDiagnosticoPorTipo('G');
                //Adiciona as permissões.
                $this->ajustarPermissoes($arrayPermissao, $parte);

            } elseif ($parte->tppermissao == $parte::EDITAR) {//caso o tipo de permissao seja Editar

                //Busca todas as permissões do tipo 'TODOS' - Especifica e Gerais.
                $arrayPermissao = $servicePermissao->retornaRecursoEPermissaoDiagnosticoPorTipo('Todos');
                //Adiciona as permissões.
                $this->ajustarPermissoes($arrayPermissao, $parte);
            }
            return true;
        } catch (Exception $exc) {
            Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
            return false;
        }
    }

    /**
     * @param Diagnostico_Model_Partediagnostico $parte
     * @param array $permissoes
     * void
     */

    private function ajustarPermissoes($permissoes, $parte)
    {
        if (count($permissoes) > 0) {
            foreach ($permissoes as $permissao) {
                $data = array();
                $retorno = null;
                $data = array(
                    "idpermissao" => $permissao['idpermissao'],
                    "iddiagnostico" => $parte->iddiagnostico,
                    "idpartediagnostico" => $parte->idpartediagnostico,
                    "idpessoa" => $parte->idpessoa,
                    "idrecurso" => $permissao['idrecurso'],
                );
                try {
                    $retorno = $this->inserir($data);
                } catch (Exception $exc) {
                    Default_Service_Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc));
                }
            }
        }
    }

    public function atualizaPermissaoParteDiagnosticoId($params)
    {
        return $this->_mapper->fetchPairs($params);
    }

    /**
     * @param array $param
     * @return boolean
     */
    public function getPermissaoDiagnostico($params)
    {
        return $this->_mapper->getPermissaoAcaoDiagnosticoPorParte($params);
    }

    /**
     *
     * @param array $param
     * @return  Projeto_Model_Mapper_Permissaoprojeto
     */
    public function getPermissaoPorDiagnostico($params)
    {
        return $this->_mapper->getPermissaoPorDiagnostico($params);
    }

}
