<?php

class App_Controller_Plugin_LayoutSwitcher extends Zend_Layout_Controller_Plugin_Layout
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $config = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOptions();
        $moduleName = $request->getModuleName();

        /*
         * Setando o nome do layout, se não estiver setado no application.ini o padrão é o nome do modulo
         */
        if (isset ($config [$moduleName] ['resources'] ['layout'] ['layout'])) {
            $layoutScript = $config [$moduleName] ['resources'] ['layout'] ['layout'];
            Zend_Layout::getMvcInstance()->setLayout($layoutScript);
        } else {
            Zend_Layout::getMvcInstance()->setLayout($moduleName);
        }

        /*
         * Setando o path do layout, se não estiver setado no application.ini o padrão é: $moduleDir . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'scripts'
         */
        if (isset ($config [$moduleName] ['resources'] ['layout'] ['layoutPath'])) {
            $layoutPath = $config [$moduleName] ['resources'] ['layout'] ['layoutPath'];
            Zend_Layout::getMvcInstance()->setLayout($moduleName);
            $moduleDir = Zend_Controller_Front::getInstance()->getModuleDirectory();
            Zend_Layout::getMvcInstance()->setLayoutPath($moduleDir . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'scripts');
        } else {
            $moduleDir = Zend_Controller_Front::getInstance()->getModuleDirectory();
            Zend_Layout::getMvcInstance()->setLayoutPath($moduleDir . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'scripts');
        }
        /*
         Para utilizar os layouts do modulo default basta usar o seguinte script para setar o path do layout
         $controllerDir = Zend_Controller_Front::getInstance()->getControllerDirectory();
         Zend_Layout::getMvcInstance()->setLayoutPath(
            $controllerDir. DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'scripts'
         );
         */
    }
}

?>