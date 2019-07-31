<?php

use Default_Service_Log as Log;

class Projeto_Service_Assinadocumento extends App_Service_ServiceAbstract
{

    /**
     *
     * @var Projeto_Model_Mapper_Assinadocumento
     */
    protected $_mapper;


    public function init()
    {
        $this->_mapper = new Projeto_Model_Mapper_Assinadocumento();
    }


    /**
     * @return Projeto_Form_Validaassinatura
     */
    public function getFormTap()
    {
        $form = $this->_getForm('Projeto_Form_Validaassinatura');
        return $form;
    }

    /**
     * Verifica se o login é de servidor ou colaborador
     * @param array $params
     * @return array || boolean
     */
    public function verificarTipoPessoa($params)
    {
        $servicePessoa = new Default_Service_Pessoa();
        $params['numcpf'] = preg_replace('#[^0-9]#', '', $params['numcpf']);
        $pessoa = $servicePessoa->retornaUsuario($params);
        return $pessoa;
    }

    /**
     * Autentica o usuário
     * @param array $pessoa
     * @return boolean
     */
    public function autenticar($pessoa)
    {
        $serviceLogin = new Default_Service_login();
        $result = false;
        $dadosLDAP = array(
            'cpf' => $pessoa['numcpf'],
            'password' => $pessoa['token']
        );

        $result = $serviceLogin->autenticarUsuarioLDAP($dadosLDAP);

        return $result;

    }

    public function retornaAceiteAssinado($params)
    {
        return $this->_mapper->retornaAceiteAssinado($params);
    }

    public function validaCodigo($params)
    {
        return $this->_mapper->validaCodigo($params);
    }

    public function retornaTodosAceitesAssinadosPorProjeto($params)
    {
        return $this->_mapper->retornaTodosAceitesAssinadosPorProjeto($params);
    }

    /**
     * Cadastra a assinatura do documento
     * @param array $params
     * @return boolean
     */
    public function assinarDocumento($params)
    {

        $retorno = false;
        $assinatura = false;

        if (is_array($params['tipodoc']) && count($params['tipodoc']) == 1) {
            $tipo = array_shift($params['tipodoc']);
            unset($params['tipodoc']);
            $params['tipodoc'] = (int)$tipo;
            switch ($tipo) {
                case 1:
                case 2:
                case 4:
                    $assinatura = $this->isAssinouDocumento($params);
                    break;
                case 3:
                    $assinatura = $this->isAssinouAceite($params);
                    break;
            }

            if ($assinatura) {
                $inserir = $this->inativaAssinatura($assinatura);
            }

            $params['hashdoc'] = $this->gerarHashDocumento($params);
            $resultado = $this->_mapper->inserir($params);
            if ($resultado) {
                $retorno = true;
            }

        } elseif (is_array($params['tipodoc']) && count($params['tipodoc']) > 1) {
            $tipos = $params['tipodoc'];
            unset($params['tipodoc']);
            foreach ($tipos as $tipo) {
                $params['tipodoc'] = (int)$tipo;

                $assinatura = $this->isAssinouDocumento($params);

                if ($assinatura) {
                    $inserir = $this->inativaAssinatura($assinatura);
                }

                $params['hashdoc'] = $this->gerarHashDocumento($params);
                $resultado = $this->_mapper->inserir($params);
                if ($resultado) {
                    $retorno = true;
                }
            }
        }
        return $retorno;
    }

    public function isAssinouAceite($params)
    {
        return $this->_mapper->isAssinouAceite($params);
    }

    public function inativaAssinatura($params)
    {
        return $this->_mapper->inativaAssinatura($params);
    }

    /**
     * Gera hash do documento
     * @param array $params
     * @return string
     */
    public function gerarHashDocumento($params)
    {
        $data = Date('Y-m-d H:m:s');
        $hash = sha1($params['numcpf'] . $data);
        return $hash;
    }

    public function isAssinouDocumento($params)
    {
        return $this->_mapper->isAssinouDocumento($params);
    }

    /**
     * Retorna lista de assinaturas por projeto
     * @param array $params
     * @return array
     */
    public function retornaAssinaturaPorProjeto($params)
    {
        return $this->_mapper->retornaAssinaturaPorProjeto($params);
    }

    /**
     * Retorna lista de assinaturas por projeto e tipo de documento
     * @param array $params
     * @return array
     */
    public function retornaAssinaturaPorTipoEProjeto($params)
    {
        return $this->_mapper->retornaAssinaturaPorTipoEProjeto($params);
    }
}