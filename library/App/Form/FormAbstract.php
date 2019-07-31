<?php

abstract class App_Form_FormAbstract extends Twitter_Bootstrap_Form_Vertical
{
     /**
     * @see   Zend_Form::__construct()
     * @param mixed $options
     */
    public function __construct($options = null)
    {
        $this->addPrefixPath('App_Form', 'App/Form');
        $this->addElementPrefixPath('App_Validate', 'App/Validate', 'validate');
        $this->addElementPrefixPath('App_Filter', 'App/Filter', 'filter');
        //$this->setAttrib('accept-charset', 'utf-8');

        parent::__construct($options);
    }
}