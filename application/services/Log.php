<?php

class Default_Service_Log extends App_Service_ServiceAbstract
{
    public function init()
    {
    }

    public static function info($arrayData = array())
    {
        return self::log($arrayData, Zend_Log::INFO);
    }

    public static function debug($arrayData = array())
    {
        return self::log($arrayData, Zend_Log::DEBUG);
    }

    public static function error($arrayData = array())
    {
        return self::log($arrayData, Zend_Log::ERR);
    }

    private static function log($arrayData = array(), $priority = Zend_Log::INFO)
    {
        try {
            $config = Zend_Registry::get('config');
            $log_path = $config->resources->log->stream->writerParams->stream;
            $logger = new Zend_Log();
            $logger->addWriter(new Zend_Log_Writer_Stream($log_path));
            $filter = new Zend_Log_Filter_Priority($priority);
            $logger->addFilter($filter);
            $logger->log(APPLICATION_ENV . ': ' . json_encode($arrayData), $priority);
            return $logger;
        } catch (Exception $exception) {
            return null;
        }
    }
}

?>
