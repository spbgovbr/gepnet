<?php

class App_Generator_Php_Config
{
    /**
     * #vat boolean
     */
    public $isReflection = false;

    /**
     * @var string
     */
    public $moduleName;

    /**
     * @var string
     */
    public $className;

    /**
     * @var string
     */
    public $tableName;

    /**
     * @var string
     */
    public $geradora;

    /**
     * @var array
     */
    public $classNameAbstract;

    /**
     * @var array
     */
    public $modelName;
    /**
     * @var array
     */
    public $dbTableName;
    /**
     * @var array
     */
    public $mapperName;
    /**
     * @var array
     */
    public $formName;

    /**
     * @var string
     */
    public $path;

    /**
     * @var array
     */
    public $prefix;

    /**
     * @var string
     */
    public $extends;

    /**
     * @var array
     */
    public $primary;

    /**
     * @var array
     */
    public $relations;

    /**
     * @var array
     */
    public $dependents;

    /**
     * @var array
     */
    public $metadata;

    /**
     * @var App_Generator_Php_Class
     */
    public $classGen = null;

    /**
     * @var string
     */
    public $content;

}