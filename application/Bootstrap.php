<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initAutoload()
    {
        $autoloader = $this->getApplication()->getAutoloader();
        if (!$autoloader->isFallbackAutoloader()) {
            $autoloader->setFallbackAutoloader(true);
        }

        $this->bootstrap('frontController');

        $autoloader = new Zend_Loader_Autoloader_Resource(array(
            'namespace' => 'Default',
            'basePath' => APPLICATION_PATH,
            'resourceTypes' => array(
                'form' => array(
                    'path' => 'forms',
                    'namespace' => 'Form'
                ),
                'model' => array(
                    'path' => 'models',
                    'namespace' => 'Model',
                ),
            )
        ));
        $autoloader->addResourceType('Form', 'forms/', 'Form');
        $autoloader->addResourceType('service', 'services/', 'Service');
        $autoloader->addResourceType('mapper', 'models/mappers', 'Model_Mapper');

        return $autoloader;
    }

    protected function _initCache()
    {
        $this->bootstrap('db');

        // First, set up the Cache
        $frontendOptions = array(
            'automatic_serialization' => true
        );

        $backendOptions = array(
            'cache_dir' => APPLICATION_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'cache',
        );

        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

        // Next, set the cache to be used with all table objects
        Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
    }

    protected function _initResourceLoader()
    {
        $this->_resourceLoader->addResourceType('service', 'services', 'Service');
    }

    protected function _iniLocale()
    {
        date_default_timezone_set('America/Sao_Paulo');
        //Data no formato 12-12-2010
        $locale = new Zend_Locale('pt_BR');
        Zend_Locale_Format::setOptions(array(
            'locale' => 'pt_BR',
            'date_format' => 'dd/MM/YYYY'
        ));
        $registry = Zend_Registry::getInstance();
        $registry->set(Zend_Locale, $locale);
    }

    protected function _initTranslate()
    {
        try {
            $translate = new Zend_Translate('Array',
                APPLICATION_PATH . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR . 'pt_BR.php', 'pt_BR');
            Zend_Registry::set('translate', $translate);
            Zend_Validate_Abstract::setDefaultTranslator($translate);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    protected function _initRoutes()
    {
        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini');
        $router = $frontController->getRouter();
        $router->addConfig($config, 'routes');
    }

    protected function _initView()
    {
        // Initialize view
        $view = new Zend_View ();
        //$view->doctype ( 'HTML4_STRICT' );
        //$view->headTitle('Meu Projeto');
        $view->env = APPLICATION_ENV;
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
        $view->headMeta()->appendHttpEquiv('Content-Language', 'pt-BR');

        /*
        $view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
        $view->jQuery()->enable();
        $view->jQuery()->uiEnable();
        */

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        //Zend_Paginator::setDefaultScrollingStyle('Elastic');
        //Zend_Paginator::setDefaultScrollingStyle('Sliding');
        //Zend_View_Helper_PaginationControl::setDefaultViewPartial('_partial/pagination_control.phtml');
        //Zend_Paginator::setView($view);
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }

    protected function _initViewHelpers()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->addHelperPath('App/View/Helper', 'App_View_Helper');
    }

    protected function _initControllerPlugins()
    {
        $this->bootstrap('db');
        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin(new App_Controller_Plugin_CustomView());
        $frontController->registerPlugin(new App_Controller_Plugin_Auth());
        $frontController->registerPlugin(new App_Controller_Plugin_Menu());
    }

    protected function _initZFDebug()
    {
        $zfdebugConfig = $this->getOption('zfdebug');

        if ($zfdebugConfig['enabled'] != 1) {
            return;
        }
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');
        /*
          $this->bootstrap('Doctrine');
          $doctrine = $this->getResource('Doctrine');
         */
        $options = array(
            'plugins' => array(
                'Variables',
                'File' => array('base_path' => realpath(APPLICATION_PATH . '/../')),
                'Memory',
                'ZFDebug_Controller_Plugin_Debug_Plugin_Debug' => array(
                    'tab' => 'Debug',
                    'panel' => ''
                ),
                'ZFDebug_Controller_Plugin_Debug_Plugin_Auth',
                //'App_ZFDebug_Controller_Plugin_Debug_Plugin_Doctrine',
                'Time',
                'Registry',
                'Exception'
            )
        );

        # Instantiate the database adapter and setup the plugin.
        # Alternatively just add the plugin like above and rely on the autodiscovery feature.
        if ($this->hasPluginResource('db')) {
            $this->bootstrap('db');
            $db = $this->getPluginResource('db')->getDbAdapter();
            $options['plugins']['Database']['adapter'] = $db;
        }

        # Setup the cache plugin
        if ($this->hasPluginResource('cache')) {
            $this->bootstrap('cache');
            $cache = $this->getPluginResource('cache')->getDbAdapter();
            $options['plugins']['Cache']['backend'] = $cache->getBackend();
        }

        $debug = new ZFDebug_Controller_Plugin_Debug($options);

        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin($debug);
    }

    protected function _initNavigation()
    {
//        $this->bootstrap('db');
//        $this->bootstrap('layout');
//        $this->bootstrap('view');
//        $this->bootstrap('FrontController');
//        $layout     = $this->getResource('layout');
//        $view       = $layout->getView();
//        $config     = new Zend_Config_Xml(APPLICATION_PATH .
//            '/configs/navigation_menu.xml', 'nav');
//
//        $navigation = new Zend_Navigation($config);
//        $role       = null;
//        $auth       = Zend_Auth::getInstance();
//        $acl        = new App_Acl();
//
//        if ( $auth->hasIdentity() ) {
//            //$role = $acl->getRoleById($auth->getIdentity()->nr_nivel);
//            //Zend_Debug::dump($auth->getIdentity()); exit;
//            if ( isset($auth->getIdentity()->perfilAtivo->idperfil) ) {
//                $role = new Zend_Acl_Role($auth->getIdentity()->perfilAtivo->idperfil);
//            }
//        }
//
//        $nav = $view->navigation($navigation);
//        $nav->setAcl($acl)->setRole($role);
//        return $navigation;
    }

    /**
     * Attach depency injection container
     *
     * @return void
     */
    protected function _initServices()
    {
        $config = new Zend_Config($this->getOptions());
        App_Service_ServiceAbstract::attachInjectionContainer(
            new Default_Service_InjectionContainer($config), 'Default_Service_'
        );
    }

    protected function _initConfig()
    {
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);
        return $config;
    }
}
