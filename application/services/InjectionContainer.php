<?php

class Default_Service_InjectionContainer extends App_Service_InjectionContainerAbstract
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
     * Retorna cargo servico
     *
     * @return Default_Service_Cargo
     */
    public function getDefaultServiceCargo()
    {
        $service = new Default_Service_Cargo(
            new Default_Model_Mapper_Cargo()
        );

        return $service;
    }

    /**
     * Retorna cargo servico
     *
     * @return Default_Service_Documento
     */
    public function getDefaultServiceDocumento()
    {
        $service = new Default_Service_Documento(
            new Default_Model_Mapper_Documento()
        );

        return $service;
    }

    /**
     * Retorna unidade servico
     *
     * @return Default_Service_Unidade
     */
    public function getDefaultServiceUnidade()
    {
        $service = new Default_Service_Unidade(
            new Default_Model_Mapper_Unidade()
        );

        return $service;
    }

    /**
     * Retorna tipoDocumento servico
     *
     * @return Default_Service_TipoDocumento
     */
    public function getDefaultServiceTipoDocumento()
    {
        $service = new Default_Service_TipoDocumento(
            new Default_Model_Mapper_Tipodocumento()
        );


        return $service;
    }


    /**
     * Retorna download servico
     *
     * @return Default_Service_Download
     */
    public function getDefaultServiceDownload()
    {
        $service = new Default_Service_Download();
        return $service;
    }

    /**
     *
     *
     *
     *
     *
     *
     * /**
     * @return Default_Service_Escritorio
     */
    public function getDefaultServiceEscritorio()
    {
        $service = new Default_Service_Escritorio();
        return $service;
    }

    /**
     * Retorna perfil servico
     *
     * @return Default_Service_Escritorio
     */
    public function getDefaultServicePerfil()
    {
        $service = new Default_Service_Perfil();
        return $service;
    }


    public function getDefaultServiceTipoEscritorio()
    {
        $service = new Default_Service_TipoEscritorio();
        return $service;
    }

    /**
     * Retorna login servico
     *
     * @return Default_Service_Login
     */
    public function getDefaultServiceLogin()
    {
        $service = new Default_Service_Login();
        return $service;
    }

    public function getDefaultServicePrograma()
    {
        $service = new Default_Service_Programa();
        return $service;
    }


    /**
     *
     * @return \Default_Service_Permissao
     */
    public function getDefaultServicePermissao()
    {
        $service = new Default_Service_Permissao();
        return $service;
    }

    public function getDefaultServiceNatureza()
    {
        $service = new Default_Service_Natureza();
        return $service;
    }

    public function getDefaultServiceSetor()
    {
        $service = new Default_Service_Setor();
        return $service;
    }

    public function getDefaultServiceObjetivo()
    {
        $service = new Default_Service_Objetivo();
        return $service;
    }

    public function getDefaultServiceAcao()
    {
        $service = new Default_Service_Acao();
        return $service;
    }

    /**
     * Retorna Ata servico
     *
     * @return Default_Service_Ata
     */
    public function getDefaultServiceAta()
    {
        $service = new Default_Service_Ata(
            new Default_Model_Mapper_Ata()
        );

        return $service;
    }

    /**
     * Retorna Ata servico
     *
     * @return Default_Service_Ata
     */
    public function getDefaultServiceMudanca()
    {
        $service = new Default_Service_Mudanca(
            new Default_Model_Mapper_Mudanca()
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
     * Retorna PChart2 servico
     *
     * @return Default_Service_PChart2
     */
    public function getDefaultServicePChart2()
    {
        $service = new Default_Service_PChart2();

        return $service;
    }

    /**
     * Retorna Upload Servico
     *
     * @return Default_Service_Upload
     */
    public function getDefaultServiceUpload()
    {
        $service = new Default_Service_Upload();

        return $service;
    }

    /**
     * Retorna Funcionalidade Service
     *
     * @return Default_Service_Funcionalidade
     */
    public function getDefaultServiceFuncionalidade()
    {
        $service = new Default_Service_Funcionalidade();

        return $service;
    }

    /**
     * Retorna Email Service
     *
     * @return Default_Service_Email
     */
    public function getDefaultServiceEmail()
    {
        $service = new Default_Service_Email();

        return $service;
    }
}

?>
