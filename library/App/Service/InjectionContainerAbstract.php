<?php

/**
 * Abstract injection container
 */
abstract class App_Service_InjectionContainerAbstract
{
    /**
     * Load a service
     *
     * @return App_Service_ServiceAbstract
     * @throws App_Service_InvalidArgumentException When service does not exist
     */
    public function load($serviceName)
    {
        $methodName = 'get' . str_replace('_', '', $serviceName);
        if (!method_exists($this, $methodName)) {
            throw new App_Service_InvalidArgumentException(sprintf('Service with name "%s" does not exist',
                $serviceName));
        }
        return $this->{$methodName}();
    }
}