<?php

class Acordocooperacao_Service_InjectionContainer extends App_Service_InjectionContainerAbstract
{
    /**
     * Configuração
     *
     * @var Zend_Config
     */
    protected $_config;

    /**
     * armazena a configuração do container
     *
     * @param Zend_Config $config
     */
    public function __construct(Zend_Config $config)
    {
        $this->_config = $config;
    }

    /**
     * Retorna Acordo Cooperacao servico
     *
     * @return Acordocooperacao_Service_Acordo
     */
    public function getAcordocooperacaoServiceAcordo()
    {
        $service = new Acordocooperacao_Service_Acordo(
            new Acordocooperacao_Model_Mapper_Acordo()
        );

        return $service;
    }

    /**
     * Retorna Entidade Externa servico
     *
     * @return Acordocooperacao_Service_Entidadeexterna
     */
    public function getAcordocooperacaoServiceEntidadeexterna()
    {
        $service = new Acordocooperacao_Service_Entidadeexterna(
            new Acordocooperacao_Model_Mapper_Entidadeexterna()
        );

        return $service;
    }

    public function getAcordocooperacaoServiceAcordoentidadeexterna()
    {
        $service = new Acordocooperacao_Service_Acordoentidadeexterna();

        return $service;
    }
}