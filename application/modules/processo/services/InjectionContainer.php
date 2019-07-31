<?php

class Processo_Service_InjectionContainer extends App_Service_InjectionContainerAbstract
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
     * Retorna processo servico
     *
     * @return Processo_Service_Processo
     */
    public function getProcessoServiceProcesso()
    {
        $service = new Processo_Service_Processo(
            new Processo_Model_Mapper_Processo()
        );

        return $service;
    }

    /**
     * Retorna Projetoprocesso servico
     *
     * @return Processo_Service_Projetoprocesso
     */
    public function getProcessoServiceProjetoprocesso()
    {
        $service = new Processo_Service_Projetoprocesso(
            new Processo_Model_Mapper_Projetoprocesso()
        );

        return $service;
    }

    /**
     * Retorna PAcao servico
     *
     * @return Processo_Service_Pacao
     */
    public function getProcessoServicePacao()
    {
        $service = new Processo_Service_Pacao(
            new Processo_Model_Mapper_Pacao()
        );

        return $service;
    }
}

?>
