<?php

class Pesquisa_Service_InjectionContainer extends App_Service_InjectionContainerAbstract
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
     * Retorna pessoa servico
     *
     * @return Pesquisa_Service_Pergunta
     */
    public function getPesquisaServicePergunta()
    {
        $service = new Pesquisa_Service_Pergunta(
            new Pesquisa_Model_Mapper_Frase()
        );

        return $service;
    }

    /**
     * Retorna pessoa servico
     *
     * @return Pesquisa_Service_Resposta
     */
    public function getPesquisaServiceResposta()
    {
        $service = new Pesquisa_Service_Resposta(
            new Pesquisa_Model_Mapper_Resposta()
        );

        return $service;
    }

    /**
     * Retorna questionario servico
     *
     * @return Pesquisa_Service_Questionario
     */
    public function getPesquisaServiceQuestionario()
    {
        $service = new Pesquisa_Service_Questionario(
            new Pesquisa_Model_Mapper_Questionario()
        );

        return $service;
    }

    /**
     * Retorna questionario servico
     *
     * @return Pesquisa_Service_Questionario
     */
    public function getPesquisaServiceQuestionariofrase()
    {
        $service = new Pesquisa_Service_Questionariofrase(
            new Pesquisa_Model_Mapper_Questionariofrase()
        );

        return $service;
    }

    public function getPesquisaServicePesquisa()
    {
        $service = new Pesquisa_Service_Pesquisa(
            new Pesquisa_Model_Mapper_Pesquisa()
        );

        return $service;
    }

    public function getPesquisaServiceQuestionarioPesquisa()
    {
        $service = new Pesquisa_Service_QuestionarioPesquisa(
            new Pesquisa_Model_Mapper_QuestionarioPesquisa()
        );

        return $service;
    }

    public function getPesquisaServicePerguntaPesquisa()
    {
        $service = new Pesquisa_Service_PerguntaPesquisa(
            new Pesquisa_Model_Mapper_FrasePesquisa()
        );

        return $service;
    }

    public function getPesquisaServiceRespostaPesquisa()
    {
        $service = new Pesquisa_Service_RespostaPesquisa(
            new Pesquisa_Model_Mapper_RespostaPesquisa()
        );

        return $service;
    }

    public function getPesquisaServiceQuestionariofrasePesquisa()
    {
        $service = new Pesquisa_Service_QuestionariofrasePesquisa(
            new Pesquisa_Model_Mapper_QuestionariofrasePesquisa()
        );

        return $service;
    }

    public function getPesquisaServiceHistoricoPublicacao()
    {
        $service = new Pesquisa_Service_HistoricoPublicacao(
            new Pesquisa_Model_Mapper_HistoricoPublicacao()
        );

        return $service;
    }

    public function getPesquisaServiceResultadoPesquisa()
    {
        $service = new Pesquisa_Service_ResultadoPesquisa(
            new Pesquisa_Model_Mapper_ResultadoPesquisa()
        );

        return $service;
    }

    public function getPesquisaServiceResponder()
    {
        $service = new Pesquisa_Service_Responder();

        return $service;
    }

    public function getDefaultServiceLogin()
    {
        $service = new Default_Service_Login();

        return $service;
    }

    public function getPesquisaServiceRelatorio()
    {
        $service = new Pesquisa_Service_Relatorio();
        return $service;
    }

}