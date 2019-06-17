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
    public function pesquisar($params, $idperfil, $idescritorio, $paginator)
    {
        $dados = $this->_mapper->pesquisar($params, $idperfil, $idescritorio, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            return $service;
        }
        return $dados;
    }

    public function associarPerfil($dados)
    {
        try {

            $validator = new Zend_Validate_Db_NoRecordExists(
                array(
                    'table' => 'tb_perfilpessoa',
                    'field' => 'idpessoa',
                    'schema' => 'agepnet200'
                )
            );
            $validator->getSelect()->where('idescritorio = ?', $dados['idescritorio'])
                ->where('idperfil = ?', $dados['idperfil']);

            /**@var $model Default_Model_Perfilpessoa* */
            $model = new Default_Model_Perfilpessoa($dados);

            $retorno = $this->_mapper->associarPerfil($model);

            return $retorno;
        } catch (Exception $exc) {
            throw ($exc);
            return false;
        }
    }

    public function isValidaControllerActionDiagnostico($params)
    {
        $auth = Zend_Auth::getInstance();

        switch ($params['controller']) {
            case 'diagnostico':
                if ($auth->getIdentity()->perfilAtivo->nomeperfilACL == 'admin_gepnet') {
                    return true;
                } else {
                    switch ($params['action']) {
                        case 'add':
                        case 'clonar':
                            return false;
                            break;
                        default:
                            return $this->validandoPemissaoDiagnosticoByParte($params);
                            break;
                    }
                }
                break;
            default :
                if ($auth->getIdentity()->perfilAtivo->nomeperfilACL == 'admin_gepnet') {
                    return true;
                } else {
                    return false;
                }
                break;
        }
    }

    protected function validandoPemissaoDiagnosticoByParte($params)
    {
        $auth = Zend_Auth::getInstance();
        $serviceParteInteressada = new Diagnostico_Service_Partediagnostico();
        $params['idpessoa'] = $auth->getIdentity()->idpessoa;

        if ($serviceParteInteressada->isPessoaParteInteressadaByDiagnostico($params)) {
            /** @var $modelParte Diagnostico_Model_Partediagnostico */
            $modelParte = $serviceParteInteressada->retornarParteByIdPessoa($params, false, false);
            $params['idpartediagnostico'] = $modelParte->idpartediagnostico;

            if (($modelParte->qualificacao) != $modelParte::CHEFE_DA_UNIDADE_DIAGNOSTICADA) {
                if ($this->validaPermissoesDiagnostico($params) === true) {
                    return true;
                }
            } elseif (($modelParte->qualificacao) == $modelParte::CHEFE_DA_UNIDADE_DIAGNOSTICADA) {
                return false;
            }
        }
        return false;
    }


    /**
     * Funcão que verifica se as permissoes do usuario logado para action é permitida
     *
     * @param $params array
     *
     * @return boolean
     */
    private function validaPermissoesDiagnostico($params)
    {
        $service = new  Diagnostico_Service_Permissaodiagnostico();
        return $service->getPermissaoDiagnostico($params);
    }


    public function isValidaControllerAction($params)
    {
        if (isset($params['idprojeto']) && (!empty($params['idprojeto']))) {
            $auth = Zend_Auth::getInstance();
            $service = new  Projeto_Service_PermissaoProjeto();
            $serviceGerencia = new Projeto_Service_Gerencia();
            $serviceParteInteressada = new Projeto_Service_ParteInteressada();
            $params['idprojeto'] = (int)$params['idprojeto'];
            $params['idpessoa'] = $auth->getIdentity()->idpessoa;
            $params['idpessoainterna'] = $auth->getIdentity()->idpessoa;
            /**
             * @var $modelParte Projeto_Model_Parteinteressada
             */
            $modelParte = $serviceParteInteressada->buscaParteInteressadaInterna($params);
            $params['idparteinteressada'] = $modelParte->idparteinteressada;
            $params['idescritorio'] = (int)$auth->getIdentity()->perfilAtivo->idescritorio;
            $params['perfilAtivo'] = $auth->getIdentity()->perfilAtivo->idperfil;
            $arrayEscritorio = $serviceGerencia->getEscritorioByIdProjeto($params);
            $params['idescritorioFiltro'] = (int)$arrayEscritorio['idescritorio'];

            switch ($auth->getIdentity()->perfilAtivo->nomeperfilACL) {
                case 'report':
                    if ($serviceGerencia->isParteInteressada($params) === true) {
                        return $this->validaPermissoes($params);
                    } else {
                        if ($this->permissaoStatusReport($params) === true) {
                            return true;
                        }
                        return false;
                    }
                    break;
                case 'gerente':

                    if ($this->comparaEsritorio($params) === true) {
                        if ($serviceGerencia->isGerenteORAdjuntoByEscritorio(
                                $params['idprojeto'], $params['idescritorio'], $params['idpessoa']) === true) {
                            return true;
                        } else {
                            if ($serviceGerencia->isGerenteORAdjuntoByEscritorio(
                                    $params['idprojeto'], $params['idescritorio'], $params['idpessoa']) === false) {
                                if ($params['action'] === 'clonarprojeto') {
                                    return true;
                                } elseif ($serviceGerencia->isParteInteressada($params) === true) {
                                    return $this->validaPermissoes($params);
                                } else {
                                    if ($this->permissaoStatusReport($params) === true) {
                                        return true;
                                    }
                                    return false;
                                }
                            }
                        }
                    } else {
                        if ($this->comparaEsritorio($params) === false) {
                            if ($params['action'] === 'clonarprojeto') {
                                return true;
                            } elseif ($serviceGerencia->isParteInteressada($params) === true) {
                                return $this->validaPermissoes($params);
                            } else {
                                if ($this->permissaoStatusReport($params) === true) {
                                    return true;
                                }
                                return false;
                            }
                        }
                    }
                    break;
                case 'escritorio':
                    if ($this->comparaEsritorio($params) === true) {
                        return true;
                    } else {
                        if ($this->comparaEsritorio($params) === false) {
                            if ($params['action'] === 'clonarprojeto') {
                                return true;
                            } elseif ($serviceGerencia->isParteInteressada($params) === true) {
                                return $this->validaPermissoes($params);
                            } else {
                                if ($this->permissaoStatusReport($params) === true) {
                                    return true;
                                }
                                return false;
                            }
                        }
                    }
                    break;
                case 'admin_setorial':
                    if ($this->comparaEsritorio($params) === true) {
                        return true;
                    } else {
                        if ($this->comparaEsritorio($params) === false) {
                            if ($params['action'] === 'clonarprojeto') {
                                return true;
                            } elseif ($serviceGerencia->isParteInteressada($params) === true) {
                                return $this->validaPermissoes($params);
                            } else {
                                if ($this->permissaoStatusReport($params) === true) {
                                    return true;
                                }
                                return false;
                            }
                        }
                    }
                    break;
                case 'admin_gepnet':
                    return true;
                    break;
                default:
                    if ($serviceGerencia->isParteInteressada($params) === true) {
                        return $this->validaPermissoes($params);
                    } else {
                        if ($this->permissaoStatusReport($params) === true) {
                            return true;
                        }
                        return false;
                    }
                    break;
            }
        } else {
            return true;
        }
    }


    /**
     * Funcão que verifica se as permissoes do usuario logado para action é permitida
     *
     * @param $params array
     *
     * @return boolean
     */
    private function validaPermissoes($params)
    {
        $service = new  Projeto_Service_PermissaoProjeto();
        if (
            ($service->getPermissaoProjeto($params) === false) &&
            ($this->permissaoStatusReport($params) === false)
        ) {
            return false;
        } else {
            if (
            ($service->getPermissaoProjeto($params) === true)
            ) {
                return true;
            } else {
                if (
                ($this->permissaoStatusReport($params) === true)
                ) {
                    return true;
                }
            }
        }
        return false;
    }


    /**
     * Funcão que verifica se o usuario logado esta no mesmo escritorio do projeto
     *
     * @param $escritorio array
     *
     * @return bool
     */
    public function comparaEsritorio($params)
    {

        if (isset($params['idescritorioFiltro'])) {
            if (($params['idescritorioFiltro'] >= 0) && $params['idescritorioFiltro'] == $params['idescritorio']) {
                return true;
            }
        }
        return false;
    }


    public function permissaoStatusReport($params)
    {
        $params['controller'] = 'projeto:' . $params['controller'];
        return $this->_mapper->permissaoStatusReport($params);
    }

    public function permitirAction($params)
    {
        $params['controller'] = 'projeto:' . $params['controller'];
        return $this->_mapper->permitirAction($params);
    }


    public function trocarSituacao($params)
    {
        //Zend_Debug::dump($params); exit;
        $model = new Default_Model_Perfilpessoa($params);
        $retorno = $this->_mapper->updateSituacao($model);
        return $retorno;
    }

    public function initCombo($objeto, $msg)
    {

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
