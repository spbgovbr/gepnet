<?php

abstract class App_Generator_Php_Abstract
{
    protected $_options = array();
    private $_adapter = null;

    public function camelize($value)
    {
        return str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($value))));
    }

    public function sanitizeTableName($value)
    {
        $filter = new Zend_Filter_StringTrim('tb_');
        return $filter->filter($value);
    }

    public function createPaths()
    {
        $paths = explode(DIRECTORY_SEPARATOR, $this->_options['path']);
        $current_path = null;

        array_shift($paths);

        foreach ($paths as $path) {
            $current_path .= DIRECTORY_SEPARATOR . $path;
            if (!file_exists($current_path)) {
                mkdir($current_path);
                exec("chmod 777 {$current_path}");
            }
        }
    }

    /**
     *
     * @param App_Generator_Php_Config $config
     * @return array
     */
    public function create(App_Generator_Php_Config $config)
    {
        $retorno = array();
        $retorno['classGen'] = $this->_create($config);
        if (!$retorno['classGen']) {
            $retorno['classGen'] = null;
            return $retorno;
        }

        $file = new App_Generator_Php_File(array(
            'classes' => array($retorno['classGen'])
        ));
        $retorno['content'] = $file->generate();
        return $retorno;
    }

    public function getAdapterName()
    {
        $className = get_class(Zend_Db_Table::getDefaultAdapter());
        switch ($className) {
            case 'Zend_Db_Adapter_Pdo_Oci'   :
                $adapter = 'Oracle';
                break;
            //case 'Zend_Db_Adapter_Pdo_Mysql' : $adapter = 'Mysql'; break;
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
        if (!$this->_adapter) {
            $db = Zend_Db_Table_Abstract::getDefaultAdapter();
            $adapter = $this->getAdapterName();
            $this->_adapter = new $adapter($db);
        } else {
            return $this->_adapter;
        }
    }

    /**
     *
     * @param App_Generator_Php_Config $config
     * @param App_Generator_Php_Class $classGen
     * @return App_Generator_Php_Class
     */
    public function _createFromReflection(App_Generator_Php_Config $config, $classGen)
    {
        //Zend_Debug::dump($config);
        //instanceof 
        try {
            $class = App_Generator_Php_Class::fromReflection(
                new Zend_Reflection_Class($config->className)
            );

            if (!($classGen instanceof Zend_CodeGenerator_Php_Class)) {
                $classGen = $class;
            }

            $properties = $class->getProperties();
            foreach ($properties as $p) {
                if (!$classGen->getProperty($p->getName())) {
                    $classGen->setProperty($p);
                }
            }

            $methods = $class->getMethods();
            foreach ($methods as $m) {
                if (!$classGen->hasMethod($m->getName())) {
                    $classGen->setMethod($m);
                }
            }
            return $classGen;
        } catch (Exception $exc) {
            //Zend_Debug::dump($config);exit;
            echo $exc->getTraceAsString();
        }

    }
}