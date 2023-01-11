<?php

/**
 * @see Zend_Auth_Adapter_Interface
 */
require_once 'Zend/Auth/Adapter/Interface.php';
require_once 'Zend/Rest/Client.php';
require_once 'Zend/Http/Client/Adapter/Curl.php';
require_once 'Zend/Uri/Http.php';
require_once 'Zend/Http/Client.php';
require_once 'Token.php';

require_once __DIR__ . '/../../../../vendor/autoload.php';

class App_Auth_Adapter_Siseg3 extends App_Auth_Adapter_Gepnet
{

    /**
     * Performs an authentication attempt
     *
     * @return Zend_Auth_Result
     * @throws Zend_Auth_Adapter_Exception If authentication cannot
     *                                     be performed
     */
    public function authenticate()
    {
        $keyclockIni    = Zend_Registry::get('config')->keycloak;

        $provider = new pviojo\OAuth2\Client\Provider\Keycloak([
            'authServerUrl' => $keyclockIni->auth_server_url,
            'realm' => $keyclockIni->realm,
            'clientId' => $keyclockIni->client_id,
            'clientSecret' => $keyclockIni->client_secret,
            'redirectUri' => $keyclockIni->redirect_uri,
            'verify' => "" != trim($keyclockIni->verify),
        ]);

//        Zend_Debug::dump($keyclockIni->auth_server_url,"URL DE AUTENTICACAO => ");

        if (!isset($_GET['code'])) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: ' . $authUrl);
            exit;
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

            unset($_SESSION['oauth2state']);
            $authUrl = $provider->getAuthorizationUrl();
            header('Location: ' . $authUrl);
            exit;
        } else {
            // Try to get an access token (using the authorization coe grant)
            try {
                $token = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);
            } catch (Exception $e) {
                exit('Failed to get access token: ' . $e->getMessage());
            }

            try {
                $user = $provider->getResourceOwner($token);
            } catch (Exception $e) {
                exit('Failed to get resource owner: ' . $e->getMessage());
            }
            unset($this->_params['helper']);

            $arUser = $user->toArray();

            $retorno = $this->retornaToken();

//            Zend_Debug::dump($retorno,"SEGUNDO TOKEN GERADO => ");

            $dadosUser = $this->buscarDadosCorporativo($arUser['preferred_username'],$retorno['access_token']);
//            Zend_Debug::dump($dadosUser,"RETORNO DADOS CORPORATIVO => ");die;
            $this->_params['token'] = $token->getToken();
            $this->_params['cpf'] = $dadosUser['dadosPessoais']['cpfCnpj'];
            $this->_params['nome'] = $arUser['given_name'];

            $this->_authenticateSetup();
            $resultIdentities = $this->validaCpf();
            $authResult = $this->_authenticateValidateResultset($resultIdentities);

            if ($authResult instanceof Zend_Auth_Result) {
                return $authResult;
            }

            $authResult = $this->_authenticateValidateResult(array_shift($resultIdentities));
//            Zend_Debug::dump($authResult,"RETORNO DA VALIDAÇÃO => ");die;
            return $authResult;
        }
    }

    /**
     * Faz uma nova requisição para recuperar um novo token
     * @return mixed
     * @throws Zend_Exception
     */
    private function retornaToken () {
        $tokenIni       = Zend_Registry::get('config')->token;

        try{
            # Our new data
            $data = array(
                'client_id'     => $tokenIni->client_id,
                'client_secret' => $tokenIni->client_secret,
                'grant_type'    => $tokenIni->grant_type,
                'username'      => $tokenIni->username,
                'password'      => $tokenIni->password,
            );
            # Create a connection
            $url = $tokenIni->auth_server_url;
            $ch = curl_init($url);
            # Form data string
            $postString = http_build_query($data, '', '&');

            # Setting our options
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            curl_setopt($ch, CURLOPT_VERBOSE, true);
            $verbose = fopen('php://temp', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);

            # Get the response
            $response = curl_exec($ch);

            curl_close($ch);



        } catch (Exception $e) {
            exit('Failed to get access token-2: ' .  $e->getMessage() );
        }
        return json_decode($response, true);
    }

    /**
     * Retorna dos dados dos servidor a partir do corporativo.
     * @param $login
     * @param $token
     * @return mixed
     */
    private function buscarDadosCorporativo($login, $token){
        $tokenIni       = Zend_Registry::get('config')->token;

        define(URL_CORPORATIVO,$tokenIni->url_verify_login);

        $headers = array(
            "Content-type: application/json; charset=utf-8",
            "Accept: application/json",
            "Authorization: bearer " . $token
        );

        # Create a connection
        $ch = curl_init();

        # Setting our options
        curl_setopt($ch, CURLOPT_URL, URL_CORPORATIVO.$login);
//        curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

//        Zend_Debug::dump(URL_CORPORATIVO.$login,"URL DADOS CORPORATIVO => ");

        # Get the response
        $response = curl_exec($ch);

//        Zend_Debug::dump(curl_exec($ch),"EXECUÇÃO => ");

        if (!curl_exec($ch)) {
            Zend_Debug::dump('ERRO CORPORATIVO: "' . curl_error($ch) . '" - CODIGO DO ERRO: ' . curl_errno($ch));
            exit();
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Method responsável por validar o token no banco
     * @param type $token
     * @return type
     */
    protected function validaCpf()
    {    
        //hotfix#51373 - Problema de validação com CPF que iniciam com zeros.
//        $restulado = array();
//        $numcpf = preg_replace("/\D+/", "", $this->_params['cpf']);
        $numcpf = $this->_params['cpf'];
        
        $sql = "SELECT 1 AS zend_auth_credential_match 
                FROM agepnet200.tb_pessoa pes
                WHERE pes.numcpf IN(:numcpf) ";
               
        $resultado = $this->_zendDb->fetchAll($sql, array('numcpf' => $numcpf));
               
        if(count($resultado) == 0){             
            $cpf = str_pad($numcpf, 11, '0', STR_PAD_LEFT);
            $resultado = $this->_zendDb->fetchAll($sql, array('numcpf' => $cpf));             
        }               
                
        foreach ($resultado as $key => $item) {
            $item['ZEND_AUTH_CREDENTIAL_MATCH'] = $item['zend_auth_credential_match'];
            $resultado[$key] = $item;
        }
       
        return $resultado;
    }
}