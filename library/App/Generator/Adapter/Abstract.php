<?php

abstract class App_Generator_Adapter_Abstract
{
    /**
     *
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db = null;

    /**
     *
     * @param Zend_Db_Adapter_Abstract $db
     */
    public function __construct(Zend_Db_Adapter_Abstract $db)
    {
        //Zend_Debug::dump($db);exit;
        $this->_db = $db;
    }

    public function camelize($value)
    {
        return str_replace(" ", "", ucwords(str_replace("_", " ", mb_strtolower($value))));
    }

    public function sanitizeTableName($value)
    {
        return substr($value, 3);
    }

    public function describeTable($table)
    {
        return $this->_db->describeTable($table);
    }
}