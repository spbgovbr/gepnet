<?php

class Planejamento_Service_InjectionContainer extends App_Service_InjectionContainerAbstract
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
     * Retorna Objetivo servico
     *
     * @return Planejamento_Service_Objetivo
     */
    public function getPlanejamentoServiceObjetivo()
    {
        $service = new Planejamento_Service_Objetivo(
            new Planejamento_Model_Mapper_Objetivo()
        );

        return $service;
    }

    /**
     * Retorna Acao servico
     *
     * @return Planejamento_Service_Acao
     */
    public function getPlanejamentoServiceAcao()
    {
        $service = new Planejamento_Service_Acao(
            new Planejamento_Model_Mapper_Acao()
        );

        return $service;
    }

    /**
     * Retorna Escritorio servico
     *
     * @return Default_Service_Escritorio
     */
    public function getDefaultServiceEscritorio()
    {
        $service = new Default_Service_Escritorio(
            new Default_Model_Mapper_Escritorio()
        );

        return $service;
    }

}
