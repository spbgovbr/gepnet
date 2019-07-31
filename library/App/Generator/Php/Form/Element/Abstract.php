<?php

abstract class App_Generator_Php_Form_Element_Abstract
{
    /**
     * @var Zend_Translate
     */
    protected $translate;
    /**
     * @var App_Generator_Php_Form_Element_Property
     */
    protected $prop;
    /**
     * @var App_Generator_Php_Config
     */
    protected $config;


    /**
     * @param App_Generator_Php_Form_Element_Property $prop
     * @param App_Generator_Php_Config $config
     */
    public function __construct(App_Generator_Php_Form_Element_Property $prop, App_Generator_Php_Config $config)
    {
        $this->prop = $prop;
        $this->config = $config;
        $this->translate = new Zend_Translate(array(
            'adapter' => 'csv',
            'content' => APPLICATION_PATH . '/data/labels.csv',
            'locale' => 'pt'
        ));
    }

    abstract function __toString();
}