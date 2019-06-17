<?php

class App_Controller_Action_Helper_Teste extends Zend_Controller_Action_Helper_Abstract
{
    protected $_request;

    function direct($a)
    {
        $this->paginator($a);
    }

    function paginator($a)
    {
        $this->_request = $this->getFrontController()->getRequest();
    }

}
