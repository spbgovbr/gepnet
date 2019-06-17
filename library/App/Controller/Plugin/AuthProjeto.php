<?php

class App_Controller_Plugin_AuthProjeto extends Zend_Controller_Plugin_Abstract
{

    /**
     *
     * @var Zend_Auth
     */
    protected $_auth;

    /**
     *
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     *
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger;

    public function __construct()
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = Zend_Registry::get('aclProjeto');
        $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
    }


    protected $_notLoggedRoute = array(
        'controller' => 'error',
        'action' => 'login',
        'module' => 'default'
    );
    /**
     * @var array
     */
    protected $_forbiddenRoute = array(
        'controller' => 'error',
        'action' => 'forbidden',
        'module' => 'projeto'
    );


    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $controller = "";
        $action = "";
        $module = "";

        if (!$this->_auth->hasIdentity()) {
            $controller = $this->_notLoggedRoute['controller'];
            $action = $this->_notLoggedRoute['action'];
            $module = $this->_notLoggedRoute['module'];

        } else {
            if (!$this->_isAuthorized($request->getControllerName(), $request->getActionName())) {
                $this->_flashMessenger->addMessage(array(
                    'status' => 'error',
                    'message' => 'Permissão Negada para esta ação.'
                ));
                $controller = $this->_forbiddenRoute['controller'];
                $action = $this->_forbiddenRoute['action'];
                $module = $this->_forbiddenRoute['module'];
            } else {
                $controller = $request->getControllerName();
                $action = $request->getActionName();
                $module = $request->getModuleName();
            }
        }
        $request->setControllerName($controller);
        $request->setActionName($action);
        $request->setModuleName($module);

    }

    protected function _isAuthorized($controller, $action)
    {
        $this->_acl = Zend_Registry::get('aclProjeto');
        $user = $this->_auth->getIdentity();
        if (!$this->_acl->has($controller) || !$this->_acl->isAllowed($user, $controller, $action)) {
            return false;
        }
        return true;
    }
}