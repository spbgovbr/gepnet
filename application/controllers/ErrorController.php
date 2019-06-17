<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        /**
         * @var $errors ArrayObject
         */
        $errors = $this->_getParam('error_handler');
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:

                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }

        // Log exception, if logger available
        Default_Service_Log::error(array($errors, $errors->exception->getTrace()));
        if ($log = $this->getLog()) {
            $log->crit($this->view->message, $errors->exception);
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request = $errors->request;
    }

    public function aclAction()
    {
        $errors = $this->_getParam('error_handler');
        $this->view->message = 'Acesso Negado.';
        // Log exception, if logger available
        if ($log = $this->getLog()) {
            @$log->crit($this->view->message, $errors->exception);
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = @$errors->exception;
        }

        $this->view->request = @$errors->request;
    }

    public function loginAction()
    {
        $this->view->link = $this->view->baseUrl("");
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
    }

    public function acessoAction()
    {
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
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

