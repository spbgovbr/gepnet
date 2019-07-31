<?php

class Projeto_Service_InjectionContainer extends App_Service_InjectionContainerAbstract
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
     * @return Default_Service_Pessoa
     */
    public function getDefaultServicePessoa()
    {
        $service = new Default_Service_Pessoa(
            new Default_Model_Mapper_Pessoa()
        );

        return $service;
    }

    /**
     * Retorna projeto servico
     *
     * @return Default_Service_Gerencia
     */
    public function getProjetoServiceGerencia()
    {
        $service = new Projeto_Service_Gerencia(
            new Projeto_Model_Mapper_Gerencia()
        );

        return $service;
    }


    /**
     * Retorna projeto servico
     *
     * @return Default_Service_Escritorio
     */
    public function getProjetoServiceEscritorio()
    {
        $service = new Projeto_Service_Escritorio(
            new Default_Model_Mapper_Escritorio()
        );

        return $service;
    }


    /**
     * Retorna projeto servico
     *
     * @return Default_Service_Programa
     */
    public function getProjetoServicePrograma()
    {
        $service = new Projeto_Service_Programa(
            new Default_Model_Mapper_Programa()
        );

        return $service;
    }


    /**
     * Retorna projeto servico
     *
     * @return Default_Service_Natureza
     */
    public function getProjetoServiceNatureza()
    {
        $service = new Projeto_Service_Natureza(
            new Default_Model_Mapper_Natureza()
        );

        return $service;
    }


    /**
     * Retorna projeto servico
     *
     * @return Default_Service_Setor
     */
    public function getProjetoServiceSetor()
    {
        $service = new Projeto_Service_Setor(
            new Default_Model_Mapper_Setor()
        );

        return $service;
    }

    /**
     * Retorna projeto servico
     *
     * @return Default_Service_Objetivo
     */
    public function getProjetoServiceObjetivo()
    {
        $service = new Projeto_Service_Objetivo(
            new Default_Model_Mapper_Objetivo()
        );

        return $service;
    }


    /**
     * Retorna projeto servico
     *
     * @return Default_Service_Acao
     */
    public function getProjetoServiceAcao()
    {
        $service = new Projeto_Service_Acao(
            new Default_Model_Mapper_Acao()
        );

        return $service;
    }

    /**
     * Retorna ParteInteressada servico
     *
     * @return Projeto_Service_ParteInteressada
     */
    public function getProjetoServiceParteInteressada()
    {
        $service = new Projeto_Service_ParteInteressada(
            new Projeto_Model_Mapper_Parteinteressada()
        );

        return $service;
    }

    /**
     * Retorna StatusReport servico
     *
     * @return Projeto_Service_StatusReport
     */
    public function getProjetoServiceStatusReport()
    {
        $service = new Projeto_Service_StatusReport(
            new Projeto_Model_Mapper_Statusreport()
        );

        return $service;
    }

    /**
     * Retorna Impressao servico
     *
     * @return Default_Service_Impressao
     */
    public function getDefaultServiceImpressao()
    {
        $service = new Default_Service_Impressao();

        return $service;
    }

    /**
     * Retorna Atividade Cronograma servico
     *
     * @return Projeto_Service_AtividadeCronograma
     */
    public function getProjetoServiceAtividadeCronograma()
    {
        $service = new Projeto_Service_AtividadeCronograma(
            new Projeto_Model_Mapper_Atividadecronograma()
        );

        return $service;
    }

    /**
     * Retorna Plano Projeto servico
     *
     * @return Projeto_Service_PlanoProjeto
     */
    public function getProjetoServicePlanoProjeto()
    {
        $service = new Projeto_Service_PlanoProjeto();

        return $service;
    }

    /**
     * Retorna Relatorio Servico
     *
     * @return Projeto_Service_Relatorio
     */
    public function getProjetoServiceRelatorio()
    {
        $service = new Projeto_Service_Relatorio();

        return $service;
    }

    /**
     * Retorna Comunicacao Servico
     *
     * @return Projeto_Service_Comunicacao
     */
    public function getProjetoServiceComunicacao()
    {
        $service = new Projeto_Service_Comunicacao();

        return $service;
    }

    /**
     * Retorna Ata Servico
     *
     * @return Projeto_Service_Ata
     */
    public function getProjetoServiceAta()
    {
        $service = new Projeto_Service_Ata();

        return $service;
    }

    /**
     * Retorna DiarioBordo Servico
     *
     * @return Projeto_Service_Diariobordo
     */
    public function getProjetoServiceDiariobordo()
    {
        $service = new Projeto_Service_DiarioBordo();

        return $service;
    }

    /**
     * Retorna Gantt Servico
     *
     * @return Projeto_Service_Gantt
     */
    public function getProjetoServiceGantt()
    {
        $service = new Projeto_Service_Gantt();

        return $service;
    }

    /**
     * Retorna Riscos Servico
     *
     * @return Projeto_Service_Riscos
     */
    public function getProjetoServiceRisco()
    {
        $service = new Projeto_Service_Risco();

        return $service;
    }

    /**
     * Retorna Riscos Servico
     *
     * @return Projeto_Service_Contramedida
     */
    public function getProjetoServiceContramedida()
    {
        $service = new Projeto_Service_Contramedida();

        return $service;
    }

    /**
     * Retorna R3g Servico
     *
     * @return Projeto_Service_R3g
     */
    public function getProjetoServiceR3g()
    {
        $service = new Projeto_Service_R3g();

        return $service;
    }

    /**
     * Retorna RUD Servico
     *
     * @return Projeto_Service_Rud
     */
    public function getProjetoServiceRud()
    {
        $service = new Projeto_Service_Rud();

        return $service;
    }

    /**
     * Retorna TEP Servico
     *
     * @return Projeto_Service_Tep
     */
    public function getProjetoServiceTep()
    {
        $service = new Projeto_Service_Tep();

        return $service;
    }

    /**
     * Retorna BloqueioProjeto Servico
     *
     * @return Projeto_Service_BloqueioProjeto
     */
    public function getProjetoServiceBloqueioProjeto()
    {
        $service = new Projeto_Service_BloqueioProjeto();

        return $service;
    }

    /**
     * Retorna PermissaoProjeto Servico
     *
     * @return Projeto_Service_PermissaoProjeto
     */
    public function getProjetoServicePermissaoProjeto()
    {
        $service = new Projeto_Service_PermissaoProjeto();

        return $service;
    }

}