<?php

/**
 * Created by PhpStorm.
 * User: wendell.wlfl
 * Date: 03/05/2016
 * Time: 08:45
 */
class Projeto_ErrorController extends Zend_Controller_Action
{
    public function init()
    {

    }

    public function forbiddenAction()
    {
        $errors = $this->_getParam('error_handler');
        $this->view->message = 'Acesso negado para essa ação.';
        // Log exception, if logger available
        if (null != $errors) {
            if ($log = $this->getLog()) {
                $log->crit($this->view->message, $errors->exception);
            }

            // conditionally display exceptions
            if ($this->getInvokeArg('displayExceptions') == true) {
                $this->view->exception = $errors->exception;
            }

            $this->view->request = $errors->request;
        }
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

}