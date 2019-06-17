<?php

class Default_Service_Pessoa extends App_Service_ServiceAbstract
{

    /**
     * @var array
     */
    public $errors = array();
    protected $_form;
    /**
     *
     * @var Default_Model_Mapper_Pessoa
     */
    protected $_mapper;
    protected $usuarioLogado;

    public function init()
    {
        $this->_mapper = new Default_Model_Mapper_Pessoa();
        $login = new Default_Service_Login();
        $this->usuarioLogado = $login->retornaUsuarioLogado();
    }

    /**
     * @return Default_Form_PessoaPesquisar
     */
    public function getFormPesquisar()
    {
        return $this->_getForm('Default_Form_PessoaPesquisar');
    }

    public function inserir($dados)
    {
        if (($dados['idpessoa'] > 0) OR ($dados['id_servidor'] > 0)) {
            $form = $this->getForm();
            if ($form->isValid($dados)) {
                $model = new Default_Model_Pessoa($form->getValues());
                $retorno = $this->_mapper->insert($model);
                return $retorno;
            } else {
                $this->errors = $form->getMessages();
            }
            return false;

        } else {
            $pessoa = $this->getPessoaById(array('nompessoa' => $dados['nompessoa']));
            $form = $this->getFormManual();
            if ($form->isValid($dados)) {
                $model = new Default_Model_Pessoa($form->getValues());
                $retorno = $this->_mapper->insert($model);
                return $retorno;
            } else {
                $this->errors = $form->getMessages();
            }
            return false;
        }
    }

    /**
     * @return Default_Form_Pessoa
     */
    public function getForm()
    {
        return $this->_getForm('Default_Form_Pessoa');
    }

    public function getPessoaById($dados)
    {
        return $this->_mapper->getPessoaById($dados);
    }

    //put your code here

    public function getFormManual()
    {
        return $this->_getForm('Default_Form_PessoaManual');
    }

    /**
     * @param $params
     * @return bool|Zend_Mail
     */
    public function sendEmail($params, $password)
    {
        try {
            $baseUrl = new Zend_View_Helper_ServerUrl();
            $url = $baseUrl->serverUrl() . Zend_View_Helper_Url::url();

            $config = Zend_Registry::get('config');
            $host = $config->smtp->host;

            $configMail = array(
                'port' => $config->smtp->port,
                'auth' => 'login',
                'ssl' => $config->smtp->ssl,
                'email' => $config->smtp->email,
                'username' => $config->smtp->username,
                'password' => $config->smtp->password
            );

            $mailTransport = new Zend_Mail_Transport_Smtp($host, $configMail);
            Zend_Mail::setDefaultTransport($mailTransport);

            $mail = new Zend_Mail('utf-8');
            $mail->setBodyText(
                "Você foi cadastrado no sistema GEPNET.\n" .
                "Seu nome de usuário é: " . $params['desemail'] . ".\n" .
                "Sua primeira senha: $password.\n\n" .
                "No primeiro acesso você deverá alterar sua senha.\n" .
                "Para acessar o sistema clique no link abaixo: $url"
            );
            $mail->setFrom($configMail['email'], $config->project->sistema);
            $mail->addTo($params['desemail'], $params['desemail']);
            $mail->setSubject('Você foi cadastrado no sistema GEPNET');
            return $mail->send();
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param $dados
     * @return bool|Default_Model_Pessoa
     * @throws Zend_Form_Exception
     */
    public function update($dados)
    {
        $form = $this->getFormEditar();
        if ($form->isValid($dados)) {
            $pessoa = $this->getById(array('idpessoa' => $form->getValue('idpessoa')));
            if ($form) {
                $model = new Default_Model_Pessoa($form->getValues());
                $retorno = $this->_mapper->update($model);
                return $retorno;
            }
            $this->errors[] = "Um registro com o email {$form->getValue('desmail')} já existe no banco de dados.";
            return false;
        } else {
            $this->errors = $form->getMessages();
            return false;
        }
    }

    /**
     * @return Default_Form_PessoaEditar
     */
    public function getFormEditar()
    {
        $form = $this->_getForm('Default_Form_PessoaEditar');
        return $form;
    }

    public function getById($dados)
    {
        return $this->_mapper->getById($dados);
    }

    /**
     *
     * @param array $dados
     */
    public function excluir($dados)
    {
        try {
            //$model = new Default_Model_Documento($dados);
            return $this->_mapper->excluir($dados);
        } catch (Exception $exc) {
            $this->errors[] = $exc->getMessage();
            return false;
        }
    }

    public function retornaPorId($dados)
    {
        return $this->_mapper->retornaPorId($dados);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function pesquisar($params, $paginator)
    {
        $dados = $this->_mapper->pesquisar($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function pesquisarSemUnidade($params, $paginator)
    {
        $dados = $this->_mapper->pesquisarSemUnidade($params, $paginator);
        if ($paginator) {
            $service = new App_Service_JqGrid();
            $service->setPaginator($dados);
            //$service->toJqgrid($paginator);
            return $service;
        }
        return $dados;
    }

    public function buscar($params, $paginator)
    {
        if ($params['tipo'] == 0) {
            $paginator = $this->buscarServidor($params, $paginator);
        } else {
            $paginator = $this->buscarColaborador($params, $paginator);
        }
        $service = new App_Service_JqGrid();
        $service->setPaginator($paginator);
        //$service->toJqgrid($paginator);
        return $service;
    }

    /**
     *
     * @param array $params
     * @param boolean $paginator
     * @return \Default_Service_JqGrid | array
     */
    public function buscarServidor($params, $paginator)
    {
        return $this->_mapper->buscarServidor($params, $paginator);
    }

    public function buscarColaborador($params, $paginator)
    {
        return $this->_mapper->buscarColaborador($params, $paginator);
    }

    public function importar($params)
    {
        if ($params['tipo'] == 0) {
            return $this->importarServidor($params);
        } else {
            return $this->importarColaborador($params);
        }
    }

    /**
     * Retorna um registro da view pessoa do owner comum
     * @param array $params
     * @return array
     */
    public function importarServidor($params)
    {
        $pessoa = $this->_mapper->getServidorById($params);
        $pessoa->idcadastrador = $this->usuarioLogado->idpessoa;
        $pessoa->numcelular = '0000000000';
        $id_servidor = array('id_servidor' => $pessoa->id_servidor);
        $response = new stdClass();
        $response->dados = null;
        $response->success = false;
        $date = date('d/m/Y H:i:s');
        $response->dados = $pessoa->formPopulate();
        $response->msg = "Usuario importado: {$pessoa->nompessoa} - {$pessoa->getNumcpfMascarado()} em: {$date}.";
        $response->success = true;
        return $response;
    }

    /**
     * Retorna um registro da view pessoa do owner comum
     * @param array $params
     * @return array
     */
    public function importarColaborador($params)
    {
        $pessoa = $this->_mapper->getColaboradorById($params);
        $pessoa->idcadastrador = $this->usuarioLogado->idpessoa;
        $pessoa->numcelular = '0000000000';
        $response = new stdClass();
        $response->dados = null;
        $response->success = false;
        $date = date('d/m/Y H:i:s');
        $response->dados = $pessoa->formPopulate();
        $response->msg = "Usuario importado: {$pessoa->nompessoa} - {$pessoa->getNumcpfMascarado()} em: {$date}.";
        $response->success = true;
        return $response;
    }

    public function validaUsuario($params)
    {
        return $this->_mapper->validaUsuario($params);
    }

    public function validaServidor($params)
    {
        return $this->_mapper->validaServidor($params);
    }

    public function getServidorById($params)
    {
        return $this->_mapper->getServidorById($params);
    }

    public function delete($id)
    {
        return $this->_mapper->delete($id);
    }

    public function fetchPairs()
    {
        return $this->_mapper->fetchPairs();
    }

    public function getByCpf($dados)
    {
        return $this->_mapper->getByCpf($dados);
    }

    public function retornaUsuario($dados)
    {
        return $this->_mapper->retornaUsuario($dados);
    }

    public function getPessoaOracle($dados)
    {
        return $this->_mapper->getPessoaOracle($dados);
    }

    // Retorna pessoa por email
    public function getByEmail($dados)
    {
        return $this->_mapper->getByEmail($dados);
    }

    public function getTokenByEmail($dados)
    {
        return $this->_mapper->getTokenByEmail($dados);
    }

    public function verificaVersaoByIdPessoa($params)
    {
        return $this->_mapper->verificaVersaoByIdPessoa($params);
    }

    public function atualizaVersao($params)
    {
        $model = $this->retornaPessoaProjeto($params);
        $model->versaosistema = $params['versaosistema'];
        return $this->_mapper->update($model);
    }

    public function retornaPessoaProjeto($dados)
    {
        return $this->_mapper->retornaPessoaProjeto($dados);
    }

    /**
     * @param $newToken
     * @return Default_Model_Pessoa
     */
    public function updatePassword($newToken)
    {
        try {
            $model = new Default_Model_Pessoa($this->usuarioLogado);
            $model->setToken($newToken);
            $model->setPrimeiroAcesso('false');
            return $this->_mapper->update($model);
        } catch (Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}
