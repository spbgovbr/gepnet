<?php
// Define path to application directory
defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    //get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

require_once 'App/Application.php';

$configCache = null;
//We will cache only in production environment
if (APPLICATION_ENV == 'development') {
    require_once 'Zend/Cache.php';
    require_once 'Zend/Cache/Core.php';
    require_once 'Zend/Cache/Backend/File.php';
    $backendOptions = array(
        'cache_dir' => APPLICATION_PATH . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'cache',
    );
    $configCache = new Zend_Cache_Core(array('automatic_serialization' => true));
    $backend = new Zend_Cache_Backend_File ($backendOptions);
    $configCache->setBackend($backend);
}


// Create application, bootstrap, and run
$application = new App_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini',
    $configCache
);
$application->bootstrap()
    ->run();