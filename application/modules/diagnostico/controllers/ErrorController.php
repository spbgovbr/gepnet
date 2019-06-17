<?php

/**
 * Created by Visual Studio Code.
 * User: newton.ncs
 * Date: 30/10/2018
 * Time: 14:35
 */
class Diagnostico_ErrorController extends Zend_Controller_Action
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