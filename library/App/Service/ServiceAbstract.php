<?php

/**
 * Abstract serivce
 */
abstract class App_Service_ServiceAbstract implements Zend_Acl_Resource_Interface
{

    /**
     * ACL object
     *
     * @var App_Model_Acl
     */
    protected $_acl;

    /**
     * Resource ID of the service
     *
     * @var string
     */
    protected $_resource;

    /**
     * Prepared services
     * 
     * @var array
     */
    protected static $_services = array();

    /**
     * Forms
     * @var array 
     */
    protected static $_forms = array();

    /**
     * Injection containers for lazy loading services
     *
     * @var array
     */
    protected static $_injectionContainers = array();
    protected $_bootstrap = null;

    const NENHUM_REGISTRO_ENCONTRADO        = 'Nenhum registro encontrado.';
    const MAIS_DE_500_REGISTROS_ENCONTRADOS = 'Mais de 500 partes foram encontradas.';
    const REGISTRO_CADASTRADO_COM_SUCESSO   = 'Registro cadastrado com sucesso.';
    const REGISTRO_ALTERADO_COM_SUCESSO     = 'Registro alterado com sucesso.';
    const REGISTRO_EXCLUIDO_COM_SUCESSO     = 'Registro exclu&iacute;do com sucesso.';
    const ERRO_GENERICO                     = 'Erro ao tentar realizar a opera&ccedil;&atilde;o.';
    const REGISTRO_CLONADO_COM_SUCESSO      = 'Registro clonado com sucesso.';
    const CRONOGRAMA_COPIADO_COM_SUCESSO    = 'Cronograma copiado com sucesso.';
    const BASELINE_ATUALIZADA_COM_SUCESSO   = 'Base Line atualizada com sucesso.';
    const ERRO_VIOLACAO_FK_CODE_23503       = 'Remova as depend&ecirc;ncias do registro antes de exclu&iacute;-lo.';
    const PUBLICACAO_REALIZADA_SUCESSO      = 'Pesquisa publicada com sucesso.';
    const DATA_INICIO_MAIOR_DATA_FIM        = 'Aten&ccedil;&atilde;o: Data inicial maior que a data final.';
    const REGISTROS_OBRIGATORIOS            = 'Registros obrigat&oacute;rios n&atilde;o encontrados';
    const ATIVIDADE_PREDECESSORA            = 'Para essa atividade existe predecessora(s), não podendo assim ser excluida';
    const ALTERACAO_MULTPRED                = 'A data selecionada não pode ser de menor valor a data da predecessora.';
    const ERROCRITICO_PREDEC                = 'Por algum motivo as predecessoras não foram excluídas. Favor consulcar o administrador';
    /**
     *
     * @var Zend_Paginator 
     */
    protected $_paginator = null;

    public function __construct()
    {
        $this->resourceInjector();
        $this->helperInjector();
        $this->init();
    }

    public function init()
    {
        
    }

    /**
     * Attach an injection container
     *
     * @param  App_Service_InjectionContainerAbstract $container
     * @param  string                                 $prefix
     * @return void
     */
    public static function attachInjectionContainer(App_Service_InjectionContainerAbstract $container, $prefix)
    {
        self::$_injectionContainers[$prefix] = $container;
    }

    /**
     * Detach an injection container
     *
     * @param  string $prefix
     * @return void
     */
    public static function detachInjectionContainer($prefix)
    {
        if ( !isset(self::$_injectionContainers[$prefix]) ) {
            throw new App_Service_OutOfBoundsException(
                sprintf('Container with name "%s" not found in registry', $serviceName)
            );
        }

        unset(self::$_injectionContainers[$prefix]);
    }

    /**
     * Attach a service
     * 
     * @param  App_Service_ServiceAbstract $service
     * @return void
     */
    public static function attachService(App_Service_ServiceAbstract $service)
    {
        $serviceName = get_class($service);

        self::$_services[$serviceName] = $service;
    }

    /**
     * Attach multiple services
     * 
     * @param  array $services
     * @return void
     */
    public static function attachServices(array $services)
    {
        foreach ( $services as $service )
        {
            self::attachService($service);
        }
    }

    /**
     * Get a service
     * 
     * @param  string $serviceName
     * @throws App_Service_OutOfBoundsException When given service was not defined
     * @return App_Service_ServiceAbstract
     */
    public static function getService($serviceName)
    {
        if ( !isset(self::$_services[$serviceName]) ) {
            foreach ( self::$_injectionContainers as $prefix => $container )
            {
                if ( preg_match('(^' . preg_quote($prefix) . ')', $serviceName) ) {
                    $service = $container->load($serviceName);

                    self::attachService($service);

                    return $service;
                }
            }

            throw new Exception(
                sprintf('Service with name "%s" not found in registry', $serviceName)
            );
        }

        return self::$_services[$serviceName];
    }

    /**
     * Detach a service
     * 
     * @param  App_Service_ServiceAbstract $service
     * @throws App_Service_OutOfBoundsException When given service was not defined
     * @return void
     */
    public static function detachService(App_Service_ServiceAbstract $service)
    {
        $serviceName = get_class($service);

        if ( !isset(self::$_services[$serviceName]) ) {
            throw new App_Service_OutOfBoundsException(
                sprintf('Service with name "%s" not found in registry', $serviceName)
            );
        }

        unset(self::$_services[$serviceName]);
    }

    /**
     * Get ACL
     *
     * @return Zend_Acl
     */
    public function getAcl()
    {
        if ( null === $this->_acl ) {
            $this->_acl = new Default_Model_Acl();
            $this->_acl->add($this);

            $this->_setupAcl();
        }

        return $this->_acl;
    }

    /**
     * Setup ACL
     *
     * @return void
     */
    protected function _setupAcl()
    {
        
    }

    /**
     * Check if a specific user has access to the given resource
     *
     * @param  string                  $permissions
     * @param  Zend_Acl_Role_Interface $user
     * @return boolean
     */
    public function checkAcl($permissions, Zend_Acl_Role_Interface $user = null)
    {
        if ( null === $user ) {
            $user = self::getService('Default_Service_User')->getCurrentUser();
        }

        return $this->getAcl()->isAllowed($user, $this, $permissions);
    }

    /**
     * @see    Zend_Acl_Resource_Interface::getResourceId()
     * @return string
     */
    public function getResourceId()
    {
        return $this->_resource;
    }

    protected function resourceInjector()
    {
        $bootstrap = $this->getBootstrap();

        if ( !isset($this->_dependencies) || !is_array($this->_dependencies) ) {
            return;
        }

        foreach ( $this->_dependencies as $name )
        {
            $helper = $name;
            $filter = new Zend_Filter_Word_CamelCaseToUnderscore();
            $name   = $filter->filter($name);
            $name   = str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($name))));
            $name   = '_' . lcfirst($name);
            /*
              if ($helper == 'cachemanager') {
              $this->$name = new Zend_Cache_Manager;
              continue;
              }
             */
            if ( !$bootstrap->hasResource($helper) && !$bootstrap->hasPluginResource($helper) ) {
                throw new Exception("Unable to find dependency by name '$helper'");
            }

            if ( $bootstrap->hasResource($helper) ) {
                $this->$name = $bootstrap->getResource($helper);
            } else {
                $this->$name = $bootstrap->getPluginResource($helper);
            }
        }
    }

    protected function helperInjector()
    {
        if ( !isset($this->_helpers) || !is_array($this->_helpers) ) {
            return;
        }

        foreach ( $this->_helpers as $name )
        {
            // $this->_flashMessenger = Zend_Controller_Action_HelperBroker::hasHelper($name) getStaticHelper('FlashMessenger');
            /*
              if (!Zend_Controller_Action_HelperBroker::($name)) {
              throw new Exception("Unable to find helper by name '$name'");
              }
             */
            $helper = $name;
            $filter = new Zend_Filter_Word_CamelCaseToUnderscore();
            $name   = $filter->filter($name);
            $name   = str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($name))));
            $name   = '_' . lcfirst($name);

            $this->$name = Zend_Controller_Action_HelperBroker::getStaticHelper($helper);
        }
    }

    /**
     *
     * @return Zend_Application_Bootstrap_BootstrapAbstract
     */
    protected function getBootstrap()
    {
        if ( !$this->_bootstrap ) {
            $this->_bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        }
        return $this->_bootstrap;

        //->getPluginResource('cachemanager')->getCacheManager()->getCache('default');
    }

    /**
     * 
     * @param string $formName
     * @param array $remove
     * @return Zend_Form
     * @throws Exception
     */
    protected static function _getForm($formName, $remove = array())
    {
        if ( !isset(self::$_forms[$formName]) ) {
            self::$_forms[$formName] = new $formName();
            if ( count($remove) > 0 ) {
                if ( !is_array($remove) ) {
                    throw new Exception('O parâmetro remove deve ser array');
                }
                foreach ( $remove as $r )
                {
                    self::$_forms[$formName]->removeElement($r);
                }
            }
        }
        return self::$_forms[$formName];
    }

    /**
     * 
     * @param Zend_Paginator $paginator
     */
    public function setPaginator(Zend_Paginator $paginator = null)
    {
        $this->_paginator = $paginator;
        return $this;
    }

    /**
     * 
     * @return Zend_Paginator | null
     */
    public function getPaginator()
    {
        return $this->_paginator;
    }

    public function toJqgrid()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        // Instantiate Zend_Paginator with the required data source adaptor
        if ( !$this->_paginator instanceof Zend_Paginator ) {
            $this->_paginator = new Zend_Paginator($this->_adapter);
            $this->_paginator->setDefaultItemCountPerPage($request->getParam('rows', $this->_defaultItemCountPerPage));
        }

        // Pass the current page number to paginator
        $this->_paginator->setCurrentPageNumber($request->getParam('page', 1));
        //Zend_Debug::dump($this->_paginator);exit;
        // Fetch a row of items from the adapter
        $rows = $this->_paginator->getCurrentItems();

        $grid          = new stdClass();
        $grid->page    = $this->_paginator->getCurrentPageNumber();
        //$grid->total = $this->_paginator->getItemCountPerPage();
        $grid->total   = $this->_paginator->count();
        //$grid->records = $this->_paginator->getPageCount();
        $grid->records = $this->_paginator->getTotalItemCount();
        $grid->rows    = array();

        foreach ( $rows as $k => $row )
        {
            if ( isset($row['id']) ) {
                $grid->rows[$k]['id'] = $row['id'];
            }

            $grid->rows[$k]['cell'] = array_values($row);
            /*
              $grid->rows[$k]['cell'] = array();
              array_push($grid->rows[$k]['cell'], $row);
             */
        }
        return $grid;
    }

}