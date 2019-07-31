<?php

/**
 * Automatically generated data model
 */
abstract class App_Model_ModelAbstract implements IteratorAggregate, ArrayAccess, Countable
{

    protected $_db = null;
    protected $_forms = array();
    protected $_formName;
    protected static $_form;
    protected $_locale = null;
    protected $_properties = array();

    //protected $_fields = array();
    //protected $_values = array();
    /**
     *
     * @param array $dados
     */
    public function __construct($dados = null)
    {
        $this->_locale = new Zend_Locale('pt_BR');
        Zend_Date::setOptions(array('format_type' => 'php'));

        if ($dados) {
            $this->setFromArray($dados);
        }

        $this->init();
    }

    public function init()
    {

    }

    //abstract protected function _getForm();

    /**
     *
     * @param array $dados
     * @return App_Model_ModelAbstract
     * @throws Exception
     */
    public function setFromArray($dados)
    {
        if ($dados instanceof Zend_Db_Table_Row_Abstract) {
            $dados = $dados->toArray();
        } elseif ($dados instanceof Zend_Db_Table_Rowset) {
            $dados = $dados->current()->toArray();
        } elseif (is_object($dados)) {
            $dados = (array)$dados;
        }

        if (!is_array($dados)) {
            throw new Exception("O parametro dados deve ser array ou objeto");
        }

        foreach ($dados as $key => $d) {
            $key = strtolower($key);
            if (property_exists($this, $key)) {
                $method = "set" . $this->camelize($key);
                // Zend_Debug::dump($method);
                if (method_exists($this, $method)) {
                    $this->{$method}($d);
                } else {
                    $this->$key = $d;
                }
                $this->_properties[$key] = '';
            }
        }

        $this->updateProperties();
        return $this;
    }

    public function __set($nome, $valor)
    {
        $nome = strtolower($nome);
        if (property_exists($this, $nome)) {
            $method = "set" . $this->camelize($nome);
            //Zend_Debug::dump($method);exit;
            if (method_exists($this, $method)) {
                $this->{$method}($valor);
            } else {
                $this->$nome = $valor;
            }
            $this->_properties[$nome] = '';
            $this->updateProperties();
        }
        return $this;
    }

    public function __get($nome)
    {
        try {
            $nome = strtolower($nome);
            $method = "get" . $this->camelize($nome);
            if (method_exists($this, $method)) {
                /*
                  if($this->$nome instanceof App_Model_Relation){
                  return $this->$nome->getIterator();
                  }
                 */
                return $this->$method();
            }
            return $this->$nome;
        } catch (Exception $exc) {

        }
    }

    private function updateProperties()
    {
        //Zend_Debug::dump($this->_properties);

        foreach ($this->_properties as $key => $d) {
            $key = strtolower($key);
            if (property_exists($this, $key)) {
                $method = "get" . $this->camelize($key);
                // Zend_Debug::dump($method);
                if (method_exists($this, $method)) {
                    $valor = $this->{$method}();
                } else {
                    $valor = $this->$key;
                }

                if ($valor instanceof Zend_Date) {
                    $valor = $this->$key->toString('d/m/Y');
                }

                if ($valor instanceof DateTime) {
                    $valor = $this->$key->format('d/m/Y');
                }

                $this->_properties[$key] = $valor;
            }
        }
        //Zend_Debug::dump($this->_properties);
    }

    /**
     *
     * @param string $value
     * @return string
     */
    public function camelize($value)
    {
        $filter = new Zend_Filter();
        $filter->addFilter(new Zend_Filter_StringToLower());
        $filter->addFilter(new Zend_Filter_Word_UnderscoreToCamelCase());
        return $filter->filter($value);
    }

    /**
     *
     * @param string $data
     * @param string $formatoIn
     * @param string $formatoOut
     * @return string
     */
    public function dateFormat($data = '27/01/2012', $formatoIn = 'd/m/Y', $formatoOut = 'Y-m-d')
    {
        Zend_Date::setOptions(array("format_type" => "php"));
        $date = new Zend_Date($data, $formatoIn);
        return $date->toString($formatoOut);
    }

    /**
     *
     * @param type $name
     * @return Zend_Application_Resource_ResourceAbstract
     * @throws Exception
     */
    public function getResource($name)
    {
        $front = Zend_Controller_Front::getInstance();
        $bootstrap = $front->getParam("bootstrap");
        if (!$bootstrap->hasResource($name)) {
            throw new Exception("Unable to find resource by name {$name}");
        }
        $this->$name = $bootstrap->getResource($name);
        return $this;
    }

    /**
     *
     * @return Zend_Db_Table_Abstract
     */
    public function getDb()
    {
        if (null === $this->_db) {
            $this->_db = Zend_Db_Table_Abstract::getDefaultAdapter();
        }
        return $this->_db;
    }

    public function getForm($name = null)
    {
        if (!self::$_form) {
            $className = get_class($this);
            $name = str_replace("Model", "Form", $className);
            self::$_form = new $name();
        }
        return self::$_form;

        if (!$name) {
            $className = get_class($this);
            $name = str_replace("Model", "Form", $className);
            if (!isset($this->_forms[$name])) {
                $this->_forms[$name] = new $name();
            }
        }
        return $this->_forms[$name];
    }

    public function __isset($name)
    {
        return isset($this->_properties[$name]);
    }

    public function __unset($name)
    {
        if (isset($this->$name)) {
            $this->$name = null;
            unset($this->_properties[$name]);
        }
    }

    public function toArray()
    {
        // return properties as array.
        return $this->_properties;
    }

    public function clear()
    {
        foreach ($this->_properties as $key => $value) {
            $this->_properties[$key] = null;
            $this->$key = null;
        }
    }

    public function getIterator()
    {
        return new ArrayIterator($this->_properties);
    }

    public function offsetSet($offset, $value)
    {
        if (property_exists($this, $offset)) {
            $this->$offset = $value;
            $this->_properties[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {

        return isset($this->_properties[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->_properties[$offset]);
        $this->$offset = null;
    }

    public function offsetGet($offset)
    {
        return isset($this->_properties[$offset]) ? $this->$offset : null;
    }

    public function count()
    {
        $i = $this->getIterator();
        return count($i);
    }

}

