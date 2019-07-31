<?php

class App_Generator_Generator
{
    private $_adapter = null;
    private $_db = null;
    protected $basePath = '';
    /**
     * @var Zend_Log
     */
    protected $_log = null;
    protected $_options = array(
        'module' => 'Admin',
        'schema' => 'agepnet200',
        'path' => array(
            'model' => array('models'),
            'table' => array('models', 'DbTable'),
            'mapper' => array('models', 'Mapper'),
            'form' => array('forms'),
        ),
        'prefix' => array(
            'model' => '%s_Model_%s',
            'table' => '%s_Model_DbTable_%s',
            'mapper' => '%s_Model_Mapper_%s',
            'form' => '%s_Form_%s',
        ),
    );
    protected $_abstract = array(
        'model' => 'App_Model_ModelAbstract',
        'table' => 'Zend_Db_Table_Abstract',
        'mapper' => 'App_Model_Mapper_MapperAbstract',
        'form' => 'App_Form_FormAbstract',
    );

    protected $objetos = array(
        'model' => null,
        'table' => null,
        'mapper' => null,
        'form' => null,
    );

    protected $_cacheTables = array();

    public function __construct()
    {
        $this->setup();
        $this->_log = new Zend_Log();
        $writer = new Zend_Log_Writer_Stream(APPLICATION_PATH . '/../logs/generator.log');
        $this->_log->addWriter($writer);
    }

    public function generate($itens = array('model', 'table', 'mapper', 'form'))
    {
        //$this->getAdapter();
        $files = array();
        $tables = $this->getAdapter()->listTables();
        //$this->_db->listTables();
        /*
        $tables = array('tb_auditoria','tb_copia_destino','tb_documento','tb_historico',
                        'tb_lot_tipo_doc','tb_numeracao','tb_parecer_antigo','tb_protocolo_doc',
                        'tb_situacao_doc','tb_tipodoc','tb_tipo_operacao','tb_tp_usuario',
                        'tb_tramitacao','tb_usuario','tb_lotacao');
        */
        foreach ($tables as $tableName) {
            try {

                foreach ($itens as $item) {
                    $config = $this->getConfig($tableName, $item);

                    if (!$this->objetos[$item]) {
                        $this->objetos[$item] = new $config->geradora();
                    }

                    $objeto = $this->objetos[$item];

                    if (isset($this->_abstract[$item])) {
                        $config->extends = $this->_abstract[$item];
                    }

                    if (file_exists($config->path)) {
                        $config->isReflection = true;
                    }
                    //Zend_Debug::dump($config);
                    $retorno = $objeto->create($config);
                    if ($config) {
                        $config->classGen = $retorno['classGen'];
                        $config->content = $retorno['content'];
                    }
                    //Zend_Debug::dump($config);exit;
                    if ($config->classGen instanceof Zend_CodeGenerator_Php_Class) {
                        $files[] = clone $config;
                        $this->_log->log('Item: ' . $config->className, Zend_Log::INFO);
                    } else {
                        $this->_log->log('Erro: ' . $config->className, Zend_Log::ERR);
                    }
                }
                //unset($config);
            } catch (Exception $e) {
                //Zend_Debug::dump($config);
                throw $e;
            }
        }
        foreach ($files as $config) {
            if (file_put_contents($config->path, $config->content)) {
                $this->_log->log('Arquivo: ' . $config->className . ' - ' . $config->path, Zend_Log::INFO);
            } else {
                $this->_log->log('Erro Arquivo: ' . $config->className . ' - ' . $config->path, Zend_Log::ERR);
            }
        }
        //Zend_Debug::dump($files);
    }

    /**
     *
     * @param string $tableName
     * @param string $item
     * @return App_Generator_Php_Config
     */
    protected function getConfig($tableName, $item)
    {
        //var_dump($this->_options['schema']);
        if (!array_key_exists($tableName, $this->_cacheTables)) {
            $config = new App_Generator_Php_Config();
            $config->metadata = $this->getAdapter()->describeTable($tableName, $this->_options['schema']);
            $config->primary = $this->getAdapter()->getPrimaryKey($tableName, $this->_options['schema']);
            $config->relations = $this->getAdapter()->getReference($tableName, $this->_options['schema']);
            $config->dependents = $this->getAdapter()->getDependent($tableName, $this->_options['schema']);
            $config->moduleName = $this->_options['module'];
            $this->_cacheTables[$tableName] = $config;
        } else {
            $config = $this->_cacheTables[$tableName];
        }
        if (stripos($tableName, 'tb_') !== false) {
            $tableName = $this->sanitizeTableName($tableName);
        }

        $name = $this->camelize(strtolower($tableName));
        $config->path = $this->_options['path'][$item] . $name . '.php';
        $config->className = sprintf($this->_options['prefix'][$item], $this->_options['module'], $name);
        $config->modelName = sprintf($this->_options['prefix']['model'], $this->_options['module'], $name);
        $config->dbTableName = sprintf($this->_options['prefix']['table'], $this->_options['module'], $name);
        $config->mapperName = sprintf($this->_options['prefix']['mapper'], $this->_options['module'], $name);
        $config->formName = sprintf($this->_options['prefix']['form'], $this->_options['module'], $name);
        $config->geradora = 'App_Generator_Php_' . ucfirst($item);
        $config->prefix = $this->_options['prefix'];
        return $config;
    }

    protected function setup()
    {
        $front = Zend_Controller_Front::getInstance();
        $this->basePath = $front->getModuleDirectory() . DIRECTORY_SEPARATOR;
        $this->_options['module'] = ucfirst($front->getRequest()->getModuleName());
        foreach ($this->_options['path'] as $i => $p) {
            $this->_options['path'][$i] = $this->basePath . implode(DIRECTORY_SEPARATOR, $p) . DIRECTORY_SEPARATOR;
        }

        $this->createPaths();
    }


    public function createPaths()
    {
        foreach ($this->_options['path'] as $path) {
            if (!file_exists($path)) {
                //exec("mkdir {dir}");
                mkdir($path, 0777);
            }
        }
    }

    public function getAdapterName()
    {
        $className = get_class(Zend_Db_Table::getDefaultAdapter());
        switch ($className) {
            case 'Zend_Db_Adapter_Pdo_Oci'   :
                $adapter = 'Oracle';
                break;
            case 'Zend_Db_Adapter_Oracle'    :
                $adapter = 'Oracle';
                break;
            case 'Zend_Db_Adapter_Pdo_Mysql' :
                $adapter = 'Mysql';
                break;
            case 'Zend_Db_Adapter_Pdo_Pgsql' :
                $adapter = 'Pgsql';
                break;
            default:
                $adapter = 'Mysql';
                break;
        }
        //$adapter = str_replace('Zend_Db_Adapter_Pdo_Oci_', 'Preceptor_Generator_Adapter_', $className);

        $adapter = 'App_Generator_Adapter_' . $adapter;
        return $adapter;
    }

    public function getAdapter()
    {
        if (null == $this->_adapter) {
            $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
            $adapter = $this->getAdapterName();
            $this->_adapter = new $adapter($this->_db);
            //Zend_Debug::dump($this->_adapter);
        }
        return $this->_adapter;
    }

    public function camelize($value)
    {
        return str_replace(" ", "", ucwords(str_replace("_", " ", mb_strtolower($value))));
    }

    public function sanitizeTableName($value)
    {
        return substr($value, 3);
        /*
        $filter = new Zend_Filter_StringTrim('tb_');
        return $filter->filter($value);
         * 
         */
    }
}