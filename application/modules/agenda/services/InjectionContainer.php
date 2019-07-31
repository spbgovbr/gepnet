<?php

class Agenda_Service_InjectionContainer extends App_Service_InjectionContainerAbstract
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
     * Retorna agenda servico
     *
     * @return Agenda_Service_Agenda
     */
    public function getAgendaServiceAgenda()
    {
        $service = new Agenda_Service_Agenda();

        return $service;
    }

    /**
     * Retorna agenda servico
     *
     * @return Agenda_Service_Agenda
     */
    public function getAgendaServicePessoaAgenda()
    {
        $service = new Agenda_Service_PessoaAgenda();

        return $service;
    }

    /**
     * Retorna Projetoprocesso servico
     *
     * @return Processo_Service_Projetoprocesso
     */
//    public function getProcessoServiceProjetoprocesso()
//    {
//    	$service = new Processo_Service_Projetoprocesso(
//    			new Processo_Model_Mapper_Projetoprocesso()
//    	);
//
//    	return $service;
//    }
}

?>
