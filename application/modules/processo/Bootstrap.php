<?php

class Processo_Bootstrap extends Zend_Application_Module_Bootstrap
{
    /*
    public function _initView()
    {
        $view         = Zend_Layout::getMvcInstance()->getView();
        $view->setEncoding('UTF-8');
        //$view->doctype('XHTML1_STRICT');
        //$view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
        $view->addHelperPath('App/View/Helper/', 'App_View_Helper');
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        $view->headMeta('Content-Type', 'text/html; charset=utf-8');
        //Zend_Dojo::enableView($view);
        return $view;
    }
    */
    protected function _initAutoload()
    {
        $this->bootstrap('frontController');

        //Zend_Debug::dump('passou');
        //new Zend_
        $autoloader = new Zend_Loader_Autoloader_Resource(array(
            'namespace' => 'Processo',
            'basePath' => APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'processo',
            'resourceTypes' => array(
                'form' => array(
                    'path' => 'forms',
                    'namespace' => 'Form'
                ),
                'model' => array(
                    'path' => 'models',
                    'namespace' => 'Model',
                ),
                'dbtable' => array(
                    'path' => 'models/dbTable',
                    'namespace' => 'Model_DbTable',
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
        //$autoloader->addResourceType('Form', 'forms/', 'Form');
        //$autoloader->addResourceType('mapper', 'models/mappers', 'Model_Mapper');
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
            new Processo_Service_InjectionContainer($config), 'Processo_Service_'
        );
    }

}