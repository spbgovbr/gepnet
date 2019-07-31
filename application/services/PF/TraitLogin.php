<?php

trait Default_Service_PF_TraitLogin
{
    public function autenticarUsuarioLDAP($params)
    {
        $cpf = $params['cpf'];
        $password = $params['password'];
        $result = false;
        //Recupera as informações de conexão do LDAP
        $config = Zend_Registry::get('config');

        $options = $config->ldap->toArray();
        unset($options['log_path']);

        try {
            $conexaoLDAP = $this->conectaLDAP();

            $atributos = array(
                "cn",
                "mail",
                "matriculadpf",
                "matriculasiape",
                "cpf",
                "physicaldeliveryofficename",
                "telephonenumber",
                "employeetype",
                "accountstatus",
                "phpgwaccountstatus",
                "createtimestamp",
                "phpgwLastPasswdChange",
                "uidnumber"
            );

            // PESQUISA - verifica se a conta esta ATIVA e a senha NAO esta EXPIRADA
            $filtro = "(&(cpf=$cpf)(phpgwAccountStatus=A)(!(phpgwLastPasswdChange=0)))";
            $retorno = ldap_search($conexaoLDAP, $options['baseDn'], $filtro, $atributos);
            $retorno_quant = ldap_count_entries($conexaoLDAP, $retorno);
            // PESQUISA - verifica se a senha esta EXPIRADA
            $filtro_senhaexp = "(&(cpf=$cpf)(phpgwLastPasswdChange=0))";
            $retorno_senhaexp = @ldap_search($conexaoLDAP, $options['baseDn'], $filtro_senhaexp);
            $retorno_senhaexp_quant = ldap_count_entries(
                $conexaoLDAP,
                $retorno_senhaexp
            );

            if ($retorno_quant > 0 && $retorno_senhaexp_quant == 0) {
                $entradas = @ldap_get_entries($conexaoLDAP, $retorno);

                // RECEBE O DN PARA AUTENTICACAO
                $dn_usuario = $entradas[0]['dn'];

                // TENTA AUTENTICAR COM A SENHA RECEBIDA
                $autenticacao = @ldap_bind($conexaoLDAP, $dn_usuario, $password);

                if ($autenticacao == '1') {
                    $result = true;
                }
            }
            ldap_close($conexaoLDAP);
            return $result;
        } catch (Zend_Ldap_Exception $exc) {
            ldap_close($conexaoLDAP);
            Log::info(array("LINE: " . __LINE__, "FILE: " . __FILE__, $exc->getTrace()));
            throw $exc;
            return false;
        }
    }


    private function conectaLDAP()
    {
        $config = Zend_Registry::get('config');
        $options = $config->ldap->toArray();
        $ip_servidor_ldap = $options['host'];
        $porta_ldap = $options['port'];
        $super_usuario_ldap = $options['username'];
        $senha_ldap = $options['password'];
        try {
            $conexao_ldap = ldap_connect($ip_servidor_ldap, $porta_ldap);
            ldap_set_option($conexao_ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_bind($conexao_ldap, $super_usuario_ldap, $senha_ldap);
            return $conexao_ldap;
        } catch (Exception $exc) {
            var_dump($exc);
            ldap_close($conexao_ldap);
            throw $exc;
        }
    }


    public function autenticaLdap($params)
    {
        $username = $params['username'];
        $password = $params['password'];

        $auth = Zend_Auth::getInstance();

        try {
            //Recupera as informações de conexão do LDAP
            $config = Zend_Registry::get('config');
            $log_path = $config->ldap->log_path;
            $options = $config->ldap->toArray();
            unset($options['log_path']);

            //Cria o adaptador do LDAP
            $adapter = new Zend_Auth_Adapter_Ldap($options, $username, $password);

            //Cria um namespace difrente na sessao do auth
            $auth->setStorage(new Zend_Auth_Storage_Session('ldap_pesquisa'));

            //Autentica os usuario/adapter
            $result = $auth->authenticate($adapter);

            if ($result->isValid()) {
                //faz a busca dos dados do usuario
                $teste = $adapter->getLdap()->search("uid=$username");
                foreach ($teste as $item) {
                    $dataUser['data_user'] = $item;
                }

                //armazena os dados na sessao
                $auth->getStorage()->write($dataUser);
                $data = $auth->getStorage()->read();

                if ($data['data_user']['phpgwaccountstatus'][0] == self::LDAP_CONTA_ATIVA
                    && $data['data_user']['phpgwlastpasswdchange'][0] == self::LDAP_SENHA_ATIVA) {
                    return true;
                } elseif ($data['data_user']['phpgwaccountstatus'][0] == self::LDAP_CONTA_BLOQUEADA) {
                    $this->errors = "Conta bloqueada";
                    return false;
                } elseif ($data['data_user']['phpgwaccountstatus'][0] != self::LDAP_CONTA_BLOQUEADA
                    && $data['data_user']['phpgwaccountstatus'][0] != self::LDAP_CONTA_ATIVA) {
                    $this->errors = "Conta inativa";
                    return false;
                } elseif ($data['data_user']['phpgwlastpasswdchange'][0] != self::LDAP_SENHA_EXPIRADA) {
                    $this->errors = "Senha expirada";
                    return false;
                }
            } else {
                $this->errors = "Usúario e/ou senha inválidos.";
                return false;
            }

            if ($log_path) {
                $messages = $result->getMessages();

                $logger = new Zend_Log();
                $logger->addWriter(new Zend_Log_Writer_Stream($log_path));
                $filter = new Zend_Log_Filter_Priority(Zend_Log::DEBUG);
                $logger->addFilter($filter);

                foreach ($messages as $i => $message) {
                    if ($i-- > 1) { // $messages[2] and up are log messages
                        $message = str_replace("\n", "\n  ", $message);
                        $logger->log("Ldap: $i: $message", Zend_Log::DEBUG);
                    }
                }
            }
        } catch (Exception $exc) {
            throw $exc;
        }
    }

    public function logoutLdap()
    {
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('ldap_pesquisa'));
        $auth->clearIdentity();
    }
}