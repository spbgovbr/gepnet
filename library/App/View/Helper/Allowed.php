<?php

/**
 * FlashMessages view helper
 * application/modules/admin/views/helpers/FlashMessages.php
 *
 * This helper creates an easy method to return groupings of
 * flash messages by status.
 *
 * @author Aaron Bach <bachya1208[at]googlemail.com
 * @license Free to use - no strings.
 */
class App_View_Helper_Allowed extends Zend_View_Helper_Abstract
{

    public function __construct()
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = new App_Acl();
        $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
    }

    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function allowed($module, $controller, $action)
    {
        $resource = $module . ':' . $controller;
        $role = null;

        if ($this->_auth->hasIdentity()) {
            if (isset($this->_auth->getIdentity()->perfilAtivo->idperfil)) {
                $role = $this->_auth->getIdentity()->perfilAtivo->idperfil;
            }
        }

        return $this->_acl->isAllowed($role, $resource, $action);
    }
}