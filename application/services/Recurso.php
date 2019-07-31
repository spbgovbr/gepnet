<?php

class Default_Service_Recurso extends App_Service_ServiceAbstract
{

    protected $_form;

    /**
     *
     * @var Default_Model_Mapper_Recurso
     */
    protected $_mapper;

    /**
     *
     * @var Default_Model_Mapper_Permissao
     */
    protected $_mapperPermissao;
    protected $_dependencies = array(
        'db',
        'log'
    );

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
    private $arrRecursos = array();
    private $recursos = array();
    private $recursosCadastrados = array();
    private $permissoesAtual = array();

    public function init()
    {
        $this->_mapper = new Default_Model_Mapper_Recurso();
        $this->_mapperPermissao = new Default_Model_Mapper_Permissao();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getFormPesquisar()
    {
        return $this->_getForm('Default_Form_RecursoPesquisar');
    }

    public function retornaRecursos()
    {
        $this->configuraRecursos();
        $this->recursos = array();
        foreach ($this->arrRecursos as $moduleName => $controllers) {
            foreach ($controllers as $controllerName => $actions) {
                $underline = strpos($controllerName, '_');
                if ($underline) {
                    $controllerName = substr($controllerName, $underline + 1);
                }

                $recurso = new Default_Model_Recurso();
                $recurso->ds_recurso = $moduleName . ':' . $controllerName;

                $filter = new Zend_Filter();
                $filter
                    ->addFilter(new Zend_Filter_Word_CamelCaseToDash())
                    ->addFilter(new Zend_Filter_StringToLower());


                foreach ($actions as $actionName) {
                    $permissao = new Default_Model_Permissao();
                    $permissao->no_permissao = $filter->filter($actionName);
                    $permissao->ds_permissao = 'xxx';
                    $recurso->adicionarPermissao($permissao);
                }
                $this->recursos[$recurso->ds_recurso] = $recurso;
            }
        }
        //Zend_Debug::dump($this->recursos); exit;
        return $this->recursos;
    }

    public function permissoesToArray($array)
    {
        $this->permissoesAtual = array();
        foreach ($array as $permissao) {
            $this->permissoesAtual[] = $permissao->no_permissao;
        }
    }

    public function retornaNaoCadastrados()
    {
        $recursos = $this->retornaRecursos();
        $aux = array();
        $this->recursosCadastrados = $this->_mapper->retornaTodos();
        foreach ($this->recursosCadastrados as $r) {
            foreach ($r->permissions as $permission) {
                $aux[] = $indexperm = $r->ds_recurso . '_' . $permission->no_permissao;
            }
        }

        foreach ($recursos as $i => $recurso) {
            foreach ($recurso->permissions as $j => $permission) {
                $indexperm = $recurso->ds_recurso . '_' . $permission->no_permissao;
                if (in_array($indexperm, $aux)) {
                    unset($recursos[$i]->permissions[$j]);
                }
            }
        }
        $rows = array_filter($recursos, array($this, 'filtrarNaoCadastrados'));

        return $rows;
    }

    private function filtrarNaoCadastrados(Default_Model_Recurso $recurso)
    {
        if (count($recurso->permissions) <= 0) {
            return false;
        }
        return true;
    }

    public function writeToDB()
    {
        $this->retornaRecursos();
        //$this->_db->beginTransaction();
        try {
            foreach ($this->recursos as $recurso) {
                $this->_mapper->insert($recurso);
            }
            //$this->_db->commit();
            return true;
        } catch (Exception $exc) {
            //$this->_db->rollBack();
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    private function configuraRecursos()
    {
        $front = Zend_Controller_Front::getInstance();
        $modules = $front->getControllerDirectory();
        foreach ($modules as $module => $path) {
            foreach (scandir($path) as $file) {
                $this->_addRecurso($module, $path, $file);
            }
        }
        /**
         * Caso o controller recurso não tenha sido carregado devemos fazer por reflexao.
         * Na máquina local este o controller recurso não foi carregador por ser o arquivo atual.
         * O erro é exibido no método _addRecurso na linha do include_once.
         */
        if (!isset($this->arrRecursos['cadastro']['recurso'])) {
            $a = array('modules', 'cadastro', 'controllers');
            $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $a);
            $this->_addRecursoFromReflection('cadastro', $path, 'RecursoController.php');
        }
    }

    private function _addRecursoFromReflection($module, $path, $file)
    {
        if (strstr($file, "Controller.php") !== false) {
            $file = Zend_CodeGenerator_Php_File::fromReflectedFileName($path . DIRECTORY_SEPARATOR . $file);
            $classes = $file->getClasses();
            $actions = array();
            foreach ($classes as $class) {
                $controller = strtolower(substr($class->getName(), 0, strpos($class->getName(), "Controller")));
                $methods = $class->getMethods();
                foreach ($methods as $method) {
                    if ($this->endsWith($method->getName(), 'Action')) {
                        $actions[] = substr($method->getName(), 0, -6);
                    }
                }
            }
            $this->arrRecursos[$module][$controller] = $actions;
        }
    }

    private function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }

    /**
     *
     * @param string $module
     * @param string $path
     * @param string $file
     */
    private function _addRecurso($module, $path, $file)
    {
        if (strstr($file, "Controller.php") !== false) {

            include_once $path . DIRECTORY_SEPARATOR . $file;
            //include_once $file;

            foreach (get_declared_classes() as $class) {
                if (is_subclass_of($class, 'Zend_Controller_Action')) {
                    $controller = strtolower(substr($class, 0, strpos($class, "Controller")));
                    $actions = array();

                    foreach (get_class_methods($class) as $action) {
                        if (strstr($action, "Action") !== false) {
                            $actions[] = substr($action, 0, -6);
                        }
                    }
                }
            }

            $this->arrRecursos[$module][$controller] = $actions;
        }
    }

    public function inserir($params)
    {
        try {
            $permissao = new Default_Model_Permissao($params);
            $recurso = $this->_mapper->retornaPorDescricao($params);
            //Zend_Debug::dump($recurso); exit;

            if (false === $recurso) {
                $model = new Default_Model_Recurso($params);
                $model->adicionarPermissao($permissao);
                return $this->_mapper->insert($model);
            }
            $permissao->idrecurso = $recurso->idrecurso;
            return $this->_mapperPermissao->insert($permissao);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
        return false;
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
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    public function retornaPermissaoPorPerfil($params)
    {
        $retorno = array();
        $response = new stdClass();
        $response->success = false;
        $rows = $this->_mapper->retornaPermissaoPorPerfil($params);
        foreach ($rows as $row) {
            $retorno[] = $row['idpermissao'];
        }
        if (count($rows) > 0) {
            $response->success = true;
            $response->dados = $retorno;
        }
        return $response;
    }

    /**
     *
     * @return array
     */
    public function retornaTodos()
    {
        return $this->_mapper->retornaTodos();
    }

    /**
     *
     * @return array
     */
    public function fetchPairs()
    {
        $retorno = array(
            '' => 'Todos'
        );
        $options = $this->_mapper->fetchPairs();
        foreach ($options as $i => $val) {
            $retorno[$i] = $val;
        }
        return $retorno;
    }

}
