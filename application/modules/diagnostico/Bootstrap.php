<?php

class Diagnostico_Bootstrap extends Zend_Application_Module_Bootstrap
{

    protected function _initAutoload()
    {
        $this->bootstrap('frontController');
        $autoloader = new Zend_Loader_Autoloader_Resource(array(
            'namespace' => 'Diagnostico',
            'basePath' => APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'diagnostico',
            'resourceTypes' => array(
                'form' => array(
                    'path' => 'forms',
                    'namespace' => 'Form'
                ),
                'model' => array(
                    'path' => 'models',
                    'namespace' => 'Model',
                ),
                'mapper' => array(
                    'path' => 'models/mappers',
                    'namespace' => 'Model_Mapper',
                ),
                'service' => array(
                    'path' => 'services',
                    'namespace' => 'Service',
                ),
            )
        ));
        return $autoloader;
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
            new Diagnostico_Service_InjectionContainer($config), 'Diagnostico_Service_'
        );
        App_Service_ServiceAbstract::attachInjectionContainer(
            new Default_Service_InjectionContainer($config), 'Default_Service_'
        );
    }

//    protected function _initViewHelpers()
//    {
//        $view = Zend_Layout::getMvcInstance()->getView();
//    	$view->addHelperPath('App/View/Helper/', 'App_View_Helper');
//    	$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
//	$viewRenderer->setView($view);
//
//        return $view;
//    }
}