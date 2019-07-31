<?php

class App_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract {

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

    public function __construct() {
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = new App_Acl();
        $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
    }

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        $module = strtolower($request->getModuleName());
        $controller = strtolower($request->getControllerName());
        $action = strtolower($request->getActionName());
        $resource = $module . ':' . $controller;
        $role = null;

        if ($this->_auth->hasIdentity()) {
            if (isset($this->_auth->getIdentity()->perfilAtivo->idperfil)) {

                $role = $this->_auth->getIdentity()->perfilAtivo->idperfil;
            }


            //$role = $this->_acl->getRoleById($this->_auth->getIdentity()->nr_nivel);
            /*
              if(!$request->isPost() && !$request->isXmlHttpRequest() && !in_array($controller, array('log','error'))){
              file_put_contents(APPLICATION_PATH . '/data/requests/' . $this->_auth->getIdentity()->cd_pessoa . '.txt', serialize($request));
              }
             */
        }
        //Zend_Debug::dump($this->_acl->isAllowed($role, $resource, $action));
        //Zend_Debug::dump($role . $resource . $action); exit;

        if (!$this->_acl->isAllowed($role, $resource, $action)) {
            if ($this->_auth->hasIdentity()) {
                $request->setModuleName('default');
                $request->setControllerName('error');
                $request->setActionName('acl');
            } else {
                $this->_flashMessenger->addMessage(array(
                    'status' => 'error',
                    'message' => 'Favor logar novamente.'
                ));
                $request->setModuleName('default');
                $request->setControllerName('error');
                $request->setActionName('login');
            }
        }
    }

    /*
      public function preDispatch(Zend_Controller_Request_Abstract $request)
      {
      $this->_auth = Zend_Auth::getInstance();
      $this->_acl  = new App_Acl();
      // Zend_Debug::dump($request);
      $module     = strtolower($request->getModuleName());
      $controller = strtolower($request->getControllerName());
      $action     = strtolower($request->getActionName());
      $resource   = $module . ':' . $controller;
      $role = $this->_auth->getIdentity()->NR_NIVEL;
      //$sActionName     = strtolower($oRequest->getActionName());
      if (!($module == 'default' && $controller == 'log' && $action == 'in') && !$auth->hasIdentity())
      {
      $request->setModuleName('admin');
      $request->setControllerName('login');
      $request->setActionName('index');
      $this->_flashMessenger->addMessage(array(
      'status'  => 'error',
      'message' => 'Favor logar novamente.'
      ));
      }
      }
      ["CD_PESSOA"] => string(5) "20662"
      ["NR_NIVEL"] => string(1) "3"
      ["CD_LOTACAO"] => string(2) "41"
      ["DS_USUARIO"] => string(5) "admin"
      ["DS_LOTACAO"] => string(44) "SERVIÇO DISCIPLINAR > SEDIS/CODIS/COGER/DPF"
      ["SG_LOTACAO"] => string(21) "SEDIS/CODIS/COGER/DPF"
      ["SG_UF"] => string(2) "DF"

     * 
     * 
      Zend_Debug::dump($this->_acl->isAllowed($role, 'default:log', 'in'));
      Zend_Debug::dump($this->_acl->isAllowed($role, 'admin:pessoa', 'index'));
      Zend_Debug::dump($this->_acl->isAllowed($role, 'admin:usuario', 'index'));
      Zend_Debug::dump($this->_acl->isAllowed($role, 'admin:tipodoc', 'index'));
      Zend_Debug::dump($this->_acl->isAllowed($role, 'default:documento', 'index'));

     */

    public function dispatchLoopStartup(\Zend_Controller_Request_Abstract $request) {
            $timer = false;
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $session = new Zend_Session_Namespace('Zend_Auth');
            //update session expiry date to 60mins from NOW
             if(true == $session->setExpirationSeconds(14400)){
                $request->setModuleName('default');
                 $request->setControllerName('index');
                  $request->setActionName('index');
                   $this->_flashMessenger->addMessage(array(
                        'status'  => 'error',
                        'message' => 'Sessão expirada. Favor logar novamente'
      ));
             }

            return;
        }
    }

}
