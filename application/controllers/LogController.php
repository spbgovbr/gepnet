<?php

class LogController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('in', 'json')->initContext();
        $ajaxContext->addActionContext('out', 'json')->initContext();
    }

    public function indexAction()
    {
        $translate = new Zend_Translate(array(
            'adapter' => 'csv',
            'content' => APPLICATION_PATH . '/data/auth.csv',
            'locale' => 'pt'
        ));
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $token = $this->_request->getCookie('SSO_GUID', false);
        $params = $this->_getAllParams();
        $params['token'] = $token;
        $params['cd_pessoa'] = 1;

        $authAdapter = new App_Auth_Adapter_Gepnet($db, $params);
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);

        if ($result->isValid()) {
            $data = $authAdapter->getResultRowObject(null, array('nomsenha', 'senha', 'password'));
            $auth->getStorage()->write($data);
            $module = 'default';
            $controller = 'index';
            $action = 'index';
            $this->_helper->_redirector->gotoSimpleAndExit($action, $controller, $module);
        } else {
            $msn = $result->getMessages();
            $message = $translate->_($msn[0]);
            $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $message));
            $this->_helper->_redirector->gotoSimpleAndExit('login', 'error', 'default');
        }
    }

    public function outAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->_flashMessenger->addMessage(array(
            'status' => 'info',
            'message' => 'Logoff efetuado com sucesso'
        ));
        $this->view->logoff = true;
    }
}