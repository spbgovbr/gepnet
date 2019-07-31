<?php

class Default_Service_Download extends App_Service_ServiceAbstract
{

    protected $_form;
    protected $_mapper;

    public function init()
    {
        //$this->_mapper = new Default_Model_Mapper_Documento();
    }

    /**
     *
     * @param array $params
     * @return \stdClass
     */
    public function getDownloadConfig($params)
    {
        $serviceDoc = App_Service_ServiceAbstract::getService('Default_Service_Documento');
        $documento = $serviceDoc->getById($params);
        $config = Zend_Registry::get('config');
        $dir = $config->resources->cachemanager->default->backend->options->arquivos_dir;
        $fileLocation = $dir . $documento->descaminho;
        //Zend_Debug::dump($fileLocation); exit;

        $size = filesize($fileLocation);
        $extension = pathinfo($documento->descaminho, PATHINFO_EXTENSION);
        //$extension = strtolower(end(explode('.', $documento->descaminho)));
        $fileName = $documento->nomdocumento . '.' . $extension;
        $download = new App_Download();
        $headers = $download->getHeaders($fileName, $size);

        $response = new stdClass();
        $response->headers = $headers;
        $response->path = $fileLocation;

        return $response;
    }

    public function getDownloadConfigRud($params)
    {
        $config = Zend_Registry::get('config');
        $uploadDir = $config->resources->cachemanager->default->backend->options->upload_dir;
        $fileLocation = $uploadDir . $params['file'];

        //$fileLocation = realpath(APPLICATION_PATH .  $d . '..' . $d . 'upload') . $d . $params['file'] . "<br/>";
        //exit;

        $size = filesize($fileLocation);
        $extension = pathinfo($fileLocation, PATHINFO_EXTENSION);
        $fileName = pathinfo($fileLocation, PATHINFO_FILENAME) . '.' . $extension;
        $download = new App_Download();
        $headers = $download->getHeaders($fileName, $size);

        $response = new stdClass();
        $response->headers = $headers;
        $response->path = $fileLocation;
        $response->filename = $fileName;

        return $response;
    }
}

?>
