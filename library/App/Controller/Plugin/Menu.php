<?php

class App_Controller_Plugin_Menu extends Zend_Controller_Plugin_Abstract
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
        $role = null;

        switch ($module) {
            case 'pessoal':
            case 'acordocooperacao':
            case 'evento':
            case 'agenda':
            case 'relatorio':
                $module = 'projeto';
                break;
            case 'cadastro':
            case 'seguranca':
                $module = 'administracao';
                break;
            default:
                $module = $module;
                break;
        }

        if ($this->_auth->hasIdentity()) {
            if (isset($this->_auth->getIdentity()->perfilAtivo->idperfil)) {
                if ($this->_auth->getIdentity()->perfilAtivo->idperfil == 16) {
                    $module = 'seguranca';
                }
            }
        }

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $viewRenderer->initView();
        $view = $viewRenderer->view;

        if (file_exists(APPLICATION_PATH . '/configs/' . strtolower($module) . '_navigation.xml')) {
            $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/' . strtolower($module) . '_navigation.xml',
                'nav');
            $navigation = new Zend_Navigation($config);
            $view->navigation($navigation);
        }

    }

}
