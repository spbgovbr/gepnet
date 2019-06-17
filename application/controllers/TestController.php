<?php

class TestController extends Zend_Controller_Action
{
    public function aclAction()
    {
        $params = new Zend_Controller_Request_Http();

        $acl = new App_Acl($params->user);

        $this->view->user = $acl->_user;

        $this->view->role = $acl->_getUserRoleName;

        foreach ($acl->listResources() as $key => $r) {

            try {
                $s[$r['resource'] . ' - ' . $r['permission']] = $acl->isUserAllowed($r['resource'],
                    $r['permission']) ? '<font color="green">allowed</font>' : '<font color="red">denied</font>';
                $this->view->allowed = $s;
            } catch (Zend_Acl_Exception $e) {
                print_r($e->getMessage());
            }

        }
    }
}