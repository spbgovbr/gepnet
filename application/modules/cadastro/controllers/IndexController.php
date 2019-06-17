<?php

class Cadastro_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_redirect = $this->_helper->getHelper('Redirector');
    }

    function defaultAction()
    {

    }

    function indexAction()
    {
        $this->view->title = "Index Index";
        //Zend_registry::get('firebug')->info('Controller Index');
        //$usuario = new Zend_Session_Namespace('userNS');
        //$this->view->form = new PrivilegeForm();
    }


}
