<?php

use Michelf\Markdown;

class Default_Service_Versao extends App_Service_ServiceAbstract
{
    /**
     * @var Default_Service_Pessoa
     */
    protected $servicePessoa;

    protected $path = null;
    protected $rootLocal = true;
    private $arquivo;
    private $html;

    public function init()
    {
        $this->servicePessoa = new Default_Service_Pessoa();
    }

    /**
     * Define o diretorio que o arquivo deve ser encontrado
     * return string
     */
    private function setDiretorio()
    {
        $this->path = APPLICATION_PATH . '/../';
    }

    public function mostraUltimaVersao()
    {
        $servicoLogin = new Default_Service_Login();
        $usuarioLogado = $servicoLogin->retornaUsuarioLogado();
        return $this->validarVersaoPorUsuario($usuarioLogado->idpessoa);
    }

    public function mostrarTodasVersos()
    {
        $retorno = new stdClass();
        $this->setDiretorio();
        $this->setArquivo();
        $this->transformarToHTML();
        $retorno->corpoHTML = $this->retornTodasVersoes();
        $retorno->resposta = 'visualizado';
        return $retorno;
    }

    /**
     * Verificar se a ultima versao cadastrada no arquivo changelog.md eh
     * a versao ja visualizada pelo usuario.
     * @params $params
     * @return array
     */
    public function validarVersaoPorUsuario($params)
    {

        $dados = array();
        $retorno = new stdClass();
        $dados['idpessoa'] = $params;
        $this->setDiretorio();
        $this->setArquivo();
        $this->transformarToHTML();
        $nrultimaVersao = $this->retornaNumeroUltimaVersao();
        $dados['versaosistema'] = $nrultimaVersao;
        $resultado = $this->servicePessoa->verificaVersaoByIdPessoa($dados);
        if ($resultado == false) {
            $retorno->versao = $nrultimaVersao;
            $retorno->corpoHTML = $this->retornarUltimaVersao();
            $retorno->resposta = 'visualizar';
            $this->atualizaVersao($dados);
        }
        return $retorno;
    }

    private function retornarUltimaVersao()
    {
        $loadHtml = explode("<h1>", $this->html);
        $corpo = explode("</h1>", $loadHtml[1]);
        return $corpo[1];
    }

    private function retornTodasVersoes()
    {
        $loadHtml = '';
        $loadHtml = explode("<h1>", $this->html);
        return $loadHtml;
    }

    /**
     * Retorna o numero da ultima versao cadastrada no arquivo
     * @return string
     */
    private function retornaNumeroUltimaVersao()
    {
        $tag = explode("<h1>", $this->html);
        $versao = explode("</h1>", $tag[1]);
        return $versao[0];
    }

    /**
     * Carrega o arquivo changelog.md localizado na raiz do projeto
     */
    private function setArquivo()
    {
        $loadFile = file_get_contents($this->path . 'changelog.md');
        $this->arquivo = $loadFile;
    }

    private function transformarToHTML()
    {
        $markdow = new  Markdown();
        $markdow->no_entities = true;
        $html = $markdow->transform($this->arquivo);
        $this->html = $html;
    }

    public function atualizaVersao($params)
    {
        return $this->servicePessoa->atualizaVersao($params);
    }

}