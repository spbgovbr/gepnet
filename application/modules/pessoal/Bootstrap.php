<?php

class Pessoal_Bootstrap extends Zend_Application_Module_Bootstrap
{
    /*
    public function _initView()
    {
       
    }
    */
    protected function _initAutoload()
    {
        $this->bootstrap('frontController');


        $autoloader = new Zend_Loader_Autoloader_Resource(array(
            'namespace' => 'Pessoal',
            'basePath' => APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'pessoal',
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
            new Pessoal_Service_InjectionContainer($config), 'Pessoal_Service_'
        );
        App_Service_ServiceAbstract::attachInjectionContainer(
            new Default_Service_InjectionContainer($config), 'Default_Service_'
        );
    }

}