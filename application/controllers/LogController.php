<?php

class LogController extends Zend_Controller_Action
{

    public function init ()
    {
        //$this->_helper->layout()->setLayout('login');
        
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('in', 'json')->initContext();
        $ajaxContext->addActionContext('out', 'json')->initContext();
        
        //$this->_helper->_layout->setLayout('other-layout') 
    }
    
    public function indexAction()
    {
        $translate = new Zend_Translate(array(
            'adapter' => 'csv',
            'content' => APPLICATION_PATH . '/data/auth.csv',
            'locale'  => 'pt'
        ));
        //$this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
        //$request = $this->getRequest();
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $token = $this->_request->getCookie('SSO_GUID', false);
        //$token = '{7BA1D59D91-13FA-5DA6-7C51-0402EC1EF0A8}'; //GRSF
        $params = $this->_getAllParams();
        $params['token'] = $token;
        $params['cd_pessoa'] = 1;
        
        $authAdapter = new App_Auth_Adapter_Gepnet($db, $params);
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($authAdapter);
        //Zend_Debug::dump($result);

        if ($result->isValid()){
            $data = $authAdapter->getResultRowObject(null, array('nomsenha','senha','password'));
            /*
            //Zend_Debug::dump($data);
            //Zend_Auth::getInstance()->clearIdentity();
            $mapper = new Default_Model_Mapper_Usuario();
            $pessoa = $mapper->retornarPorId($data->cd_pessoa, $data->lot_cd_lotacao);
            $lotacao = $mapper->getLotacao($pessoa['CD_LOTACAO']);
            //$pessoa = $mapper->getPessoa($data->cd_pessoa);
            $data->ds_lotacao = $lotacao['DS_LOTACAO'];
            $data->sg_lotacao = $lotacao['SG_LOTACAO'];
            $data->sg_uf      = $lotacao['SG_UF'];
            $data->nr_nivel   = $pessoa['NR_NIVEL'];
            $data->lot_cd_lotacao   = $pessoa['CD_LOTACAO'];
            */

            //Zend_Debug::dump($pessoa);exit;
            //$data->cd_pessoa  = $pessoa['CD_PESSOA'];
            //$data->no_pessoa  = $pessoa['NO_PESSOA'];

            $auth->getStorage()->write($data);
            $module     = 'default';
            $controller = 'index';
            $action     = 'index';
            /*
            $filename = APPLICATION_PATH . '/data/requests/' . Zend_Auth::getInstance()->getIdentity()->cd_pessoa . '.txt';
            if(file_exists($filename)){
                $request    = unserialize(file_get_contents($filename));
                $module     = strtolower($request->getModuleName());
                $controller = strtolower($request->getControllerName());
                $action     = strtolower($request->getActionName());
            } 
            Zend_Debug::dump(array($action, $controller , $module));exit;
             */
            $this->_helper->_redirector->gotoSimpleAndExit($action, $controller , $module);
        } else {
            $msn     = $result->getMessages();
            //Zend_Debug::dump($msn);exit;
            $message = $translate->_($msn[0]);
            $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $message));
            $this->_helper->_redirector->gotoSimpleAndExit('login', 'error','default');
        }
        
       // Zend_Debug::dump($params);
    }
    
    public function outAction() 
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->_flashMessenger->addMessage(array('status' => 'info', 'message' => 'Logoff efetuado com sucesso'));
        $this->view->logoff = true;
        //$this->_helper->_redirector->gotoSimpleAndExit('login', 'error', 'default');
        
        /*
        $authSession = new Zend_Session_Namespace( Zend_Auth::getInstance()->getStorage()->getNamespace() );
        $authSession->unsetAll();
        $it = Zend_Session::getIterator();
        foreach ($it as $i)
        {
            Zend_Session::namespaceUnset($i);
        }
         */
        //$this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
    }
    /*
    protected function getUrl()
    {
        //return 'http://localhost' . $this->_helper->url('index','index');
        $filename = APPLICATION_PATH . '/data/requests/' . Zend_Auth::getInstance()->getIdentity()->CD_PESSOA . '.txt';
        if(file_exists($filename)){
            $request    = unserialize(file_get_contents($filename));
            $module     = strtolower($request->getModuleName());
            $controller = strtolower($request->getControllerName());
            $action     = strtolower($request->getActionName());
            //$params     = $request->getUserParams();
            return 'http://cogerzf.localhost' . $this->_helper->url($action, $controller , $module);
        } else {
            return 'http://cogerzf.localhost' . $this->_helper->url('index','index');
        }
    }
    
    protected function redirecionar()
    {
        $module     = 'default';
        $controller = 'index';
        $action     = 'index';
        $filename = APPLICATION_PATH . '/data/requests/' . Zend_Auth::getInstance()->getIdentity()->CD_PESSOA . '.txt';
        if(file_exists($filename)){
            $request    = unserialize(file_get_contents($filename));
            $module     = strtolower($request->getModuleName());
            $controller = strtolower($request->getControllerName());
            $action     = strtolower($request->getActionName());
        } 
        $this->_helper->_redirector->gotoSimpleAndExit($action, $controller , $module);
    }
     */
}