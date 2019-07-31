<?php

class App_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{

    /**
     *
     * @var Zend_Auth
     */
    protected $_auth;

    /**
     *
     * @var App_Acl
     */
    protected $_acl;

    /**
     *
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger;

    /**
     *
     * @var Zend_Controller_Action_Helper_Redirector
     */
    protected $_redirector;

    public function __construct()
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = new App_Acl();
        $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        $this->_redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $module = strtolower($request->getModuleName());
        $controller = strtolower($request->getControllerName());
        $action = strtolower($request->getActionName());
        $resource = $module . ':' . $controller;
        $role = null;

        if ($this->_auth->hasIdentity()) {
            if (isset($this->_auth->getIdentity()->perfilAtivo->idperfil)) {
                $role = $this->_auth->getIdentity()->perfilAtivo->idperfil;
            }
        }

        if (!$this->_acl->isAllowed($role, $resource, $action)) {
            if ($this->_auth->hasIdentity()) {
                $this->_redirector->gotoSimpleAndExit('acl', 'error', 'default');
            } else {
                $this->_flashMessenger->addMessage(array(
                    'status' => 'error',
                    'message' => 'Favor logar novamente.'
                ));
                $this->_redirector->gotoSimpleAndExit('login', 'error', 'default');
            }
        }
    }

    /**
     *
     * Esta funcao controla o tempo de duracao da sessao de trabalho.
     * Após o periodo de tempo especificado (em segundos) sem atividade
     * o usuário necessita logar novamente.
     */
    public function dispatchLoopStartup(\Zend_Controller_Request_Abstract $request)
    {
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $session = new Zend_Session_Namespace('Zend_Auth');
            //update session expiry date to 60mins from NOW
            if (true == $session->setExpirationSeconds(144000)) {
                $this->_flashMessenger->addMessage(array(
                    'status' => 'error',
                    'message' => 'Sessão expirada. Favor logar novamente'
                ));
                $this->_redirector->gotoSimpleAndExit('index', 'index', 'default');
            }

            return;
        }
    }
}
