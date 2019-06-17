<?php

class Relatorio_Service_InjectionContainer extends App_Service_InjectionContainerAbstract
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
     * Retorna relatorio servico
     *
     * @return Relatorio_Service_Risco
     */
    public function getRelatorioServiceRisco()
    {
        $service = new Relatorio_Service_Risco(
            new Relatorio_Model_Mapper_Risco()
        );

        return $service;
    }

    /**
     * Retorna relatorio servico
     *
     * @return Relatorio_Service_Licao
     */
    public function getRelatorioServiceLicao()
    {
        $service = new Relatorio_Service_Licao(
            new Relatorio_Model_Mapper_Licao()
        );

        return $service;
    }

    /**
     * Retorna relatorio servico
     *
     * @return Relatorio_Service_Diariobordo
     */
    public function getRelatorioServiceDiariobordo()
    {
        $service = new Relatorio_Service_Diariobordo(
            new Relatorio_Model_Mapper_Diariobordo()
        );

        return $service;
    }

    /**
     * Retorna relatorio servico
     *
     * @return Relatorio_Service_Aceite
     */
    public function getRelatorioServiceAceite()
    {
        $service = new Relatorio_Service_Aceite(
            new Relatorio_Model_Mapper_Aceite()
        );

        return $service;
    }

}