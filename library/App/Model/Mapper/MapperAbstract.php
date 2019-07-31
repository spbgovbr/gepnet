<?php

/**
 * Automatically generated data model
 */
abstract class App_Model_Mapper_MapperAbstract
{
    /**
     * Automatically generated from db
     */

    /**
     *
     * @var Zend_Db_Table_Abstract
     */
    protected $_dbTable = null;
    protected $_adapter = null;
    protected static $_defaultAdapter = null;
    protected static $_form;

    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;
    public $rows = null;

    /**
     * Instantiate a new mapper with a specific adapter.
     *
     * If no adapter is defined, the default adapter is used. If there is also
     * no default adapter, an exception is thrown.
     *
     * @param Zend_Db_Adapter_Abstract $adapter
     * @throws Exception When no adapter was defined
     * @throws
     */
    public function __construct(Zend_Db_Adapter_Abstract $adapter = null)
    {
        if ($adapter === null) {
            $adapter = self::getDefaultAdapter();
        }

        if ($adapter === null) {
            throw new Exception('No adapter was defined');
        }

        $this->_adapter = $adapter;
        $this->_db = self::getDefaultAdapter();
        $this->resourceInjector();
        //$this->helperInjector();
        $this->_db->getProfiler()->setEnabled(true);
        $this->_init();
    }

    /**
     * Do some initial stuff
     *
     * @return void
     */
    protected function _init()
    {

    }

    /**
     * Get the adapter
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public static function getDefaultAdapter()
    {
        if (!self::$_defaultAdapter) {
            self::setDefaultAdapter();
        }

        return self::$_defaultAdapter;
    }

    /**
     * Set the adapter
     *
     * @param string $banco
     * @return void
     */
    public static function setDefaultAdapter($banco = 'trf')
    {
        $adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
        self::$_defaultAdapter = $adapter;
    }

    public function quoteInto($text, $value, $type = null, $count = null)
    {
        return self::getDefaultAdapter()->quoteInto($text, $value, $type, $count);
    }

    /**
     * @param string $value
     * @return Application_Model_Mapper_Abstract
     */
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception("Invalid table data gateway provided");
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    /**
     * @param string $value
     * @return Zend_Db_Table_Abstract
     */
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $a = get_class($this);
            //$a = substr(str_replace("_Model_Mapper_", "_Model_DbTable_", $a), 0, -6);
            $dbTable = str_replace("_Model_Mapper_", "_Model_DbTable_", $a);
            $this->setDbTable($dbTable);
        }
        return $this->_dbTable;
    }

    /**
     * @param string $value
     * @return Zend_Form
     */
    protected function _getForm($formName)
    {
        if (!self::$_form) {
            self::$_form = new $formName();
        }
        return self::$_form;
    }

    /**
     *
     * @param type $name
     * @return Zend_Application_Resource_ResourceAbstract
     * @throws Exception
     */
    public static function getResource($name)
    {
        $bootstrap = self::getBootstrap();

        if (!$bootstrap->hasResource($name)) {
            throw new Exception("Unable to find dependency by name '$name'");
        }
        return $bootstrap->getResource($name);
    }

    /**
     * get bootstrap
     * @return Zend_Application_Bootstrap_BootstrapAbstract
     */
    public static function getBootstrap()
    {
        $f = Zend_Controller_Front::getInstance();
        return $f->getParam('bootstrap');
    }

    /**
     * REFACTORING: Funcionalidade de excluir atividade
     *
     * @param $pkey
     * @return array
     * @throws Zend_Db_Table_Exception
     */
    protected function _generateRestrictionsFromPrimaryKeys($pkey)
    {
        $where = array();
        $primary = $this->getDbTable()->info('primary');
        $name = $this->getDbTable()->info('name');

        if (is_array($primary)) {
            foreach ($primary as $key) {
                if (isset($pkey[$key])) {
                    $valor = (is_array($pkey)) ? $pkey[$key] : $pkey;
                    $where[] = $this->_db->quoteInto($name . '.' . $key . ' = ?', $valor);
                }
            }
        } else {
            $where = $this->$this->_db->quoteInto($name . '.' . $primary . ' = ?', $pkey);
        }
        return $where;
    }

    /**
     *
     * @param string $coluna
     * @param mixed $where
     * @param array $params
     * @return integer
     */
    protected function maxVal($coluna, $where = null, $params = null, $schema = 'agepnet200')
    {
        $name = $this->getDbTable()->info('name');
        $select = $this->getDbTable()->select();
        $select->from("{$schema}.{$name}", array(new Zend_Db_Expr("MAX($coluna) AS maxID")));
        //->columns(array(new Zend_Db_Expr("MAX($coluna) AS maxID")));
        //agepnet200
        //$sql = "SELECT MAX({$coluna}) as maxID FROM agepnet200.{$name}";


        if ($where && $params) {
            $select->where($where, $params);
            $max = $this->_db->fetchOne($select, $params);
        } else {
            $max = $this->_db->fetchOne($select);
        }
        //Zend_Debug::dump($select->__toString()); exit;


        if ($max == false) {
            $max = 0;
        }

        return $max + 1;
    }

    public function resourceInjector()
    {
        $bootstrap = $this->getBootstrap();

        if (!isset($this->_dependencies) || !is_array($this->_dependencies)) {
            return;
        }

        foreach ($this->_dependencies as $name) {
            $helper = $name;
            $filter = new Zend_Filter_Word_CamelCaseToUnderscore();
            $name = $filter->filter($name);
            $name = str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($name))));
            $name = '_' . lcfirst($name);
            /*
              if ($helper == 'cachemanager') {
              $this->$name = new Zend_Cache_Manager;
              continue;
              }
             */
            if (!$bootstrap->hasResource($helper) && !$bootstrap->hasPluginResource($helper)) {
                throw new Exception("Unable to find dependency by name '$helper'");
            }

            if ($bootstrap->hasResource($helper)) {
                $this->$name = $bootstrap->getResource($helper);
            } else {
                $this->$name = $bootstrap->getPluginResource($helper);
            }
        }
    }

    public function getPanel()
    {
        if (!$this->_db) {
            return '';
        }

        $html = '<h4>Database queries</h4>';
        if (Zend_Db_Table_Abstract::getDefaultMetadataCache()) {
            $html .= 'Metadata cache is ENABLED';
        } else {
            $html .= 'Metadata cache is DISABLED';
        }

        if ($profiles = $this->_db->getProfiler()->getQueryProfiles()) {
            $html .= '<h4>Adapter </h4><ol>';
            foreach ($profiles as $profile) {
                $html .= '<li><strong>[' . round($profile->getElapsedSecs() * 1000, 2) . ' ms]</strong> '
                    . htmlspecialchars($profile->getQuery()) . '</li>';
            }
            $html .= '</ol>';
        }


        return $html;
    }

    // abstract function getForm();
}

