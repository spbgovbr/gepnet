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
        $serviceDoc   = App_Service_ServiceAbstract::getService('Default_Service_Documento');
        $documento    = $serviceDoc->getById($params);
        $d            = DIRECTORY_SEPARATOR;
        $fileLocation = realpath(APPLICATION_PATH . $d . '..' . $d . 'arquivos') . $d . $documento->descaminho;
        //Zend_Debug::dump($fileLocation); exit;

        $size         = filesize($fileLocation);
        $extension = pathinfo($documento->descaminho, PATHINFO_EXTENSION);
        //$extension = strtolower(end(explode('.', $documento->descaminho)));
        $fileName = $documento->nomdocumento . '.' . $extension;
        $download = new App_Download();
        $headers  = $download->getHeaders($fileName, $size);

        $response          = new stdClass();
        $response->headers = $headers;
        $response->path    = $fileLocation;

        return $response;
    }

    public function getDownloadConfigRud($params)
    {
//        $serviceDoc   = App_Service_ServiceAbstract::getService('Default_Service_Documento');
//        $documento    = $serviceDoc->getById($params);
        $d            = DIRECTORY_SEPARATOR;
        $fileLocation = realpath(APPLICATION_PATH . $d . '..' . $d . '..' . $d . 'upload') . $d . $params['file'];
//        Zend_Debug::dump($fileLocation); exit;

        $size         = filesize($fileLocation);
        $extension = pathinfo($fileLocation, PATHINFO_EXTENSION);
        //$extension = strtolower(end(explode('.', $documento->descaminho)));
        $fileName = pathinfo($fileLocation, PATHINFO_FILENAME) . '.' . $extension;
//        Zend_Debug::dump($fileName); exit;
        $download = new App_Download();
        $headers  = $download->getHeaders($fileName, $size);

        $response          = new stdClass();
        $response->headers = $headers;
        $response->path    = $fileLocation;
        $response->filename = $fileName;

        return $response;
    }
}

?>
