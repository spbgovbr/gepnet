<?php

/**
 * Class Security
 * @package Application\Services
 */
class Default_Service_Security extends App_Service_ServiceAbstract
{
    static private $cipher = 'aes-192-cbc';

    /**
     * @param $stringToEncrypt
     * @return mixed|string
     * @throws Zend_Exception
     */
    public static function encrypt($stringToEncrypt)
    {
        $secretKey = self::getSecretKey();
        $secretCode = self::getIv();
        $encrypted = openssl_encrypt(
            $stringToEncrypt,
            self::$cipher,
            $secretKey,
            OPENSSL_RAW_DATA,
            $secretCode
        );
        return self::urlSafeBase64Encode($encrypted);
    }

    /**
     * @param $stringToDecrypt
     * @return string
     * @throws Zend_Exception
     */
    public static function decrypt($stringToDecrypt)
    {
        $stringToDecrypt = self::urlSafeB64Decode($stringToDecrypt);
        $secretKey = self::getSecretKey();
        $secretCode = self::getIv();
        $decrypted = openssl_decrypt(
            $stringToDecrypt,
            self::$cipher,
            $secretKey,
            OPENSSL_RAW_DATA,
            $secretCode
        );
        return trim($decrypted);
    }

    /**
     * @param $string
     * @return mixed|string
     */
    public static function urlSafeBase64Encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', '.'), $data);
        return $data;
    }

    /**
     * @param $string
     * @return string
     */
    public static function urlSafeB64Decode($string)
    {
        $data = str_replace(array('-', '_', '.'), array('+', '/', '='), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /**
     * @param $arrayObject
     * @return mixed|string
     * @throws Zend_Exception
     */
    public static function encryptArrayObject($arrayObject)
    {
        return self::encrypt(json_encode($arrayObject));
    }

    /**
     * @param $stringEncrypted
     * @return mixed
     * @throws Zend_Exception
     */
    public static function decryptArrayObject($stringEncrypted)
    {
        return json_decode(self::decrypt($stringEncrypted), true);
    }

    /**
     * @return bool|string
     * @throws Zend_Exception
     */
    private static function getSecretKey()
    {
        $config = Zend_Registry::get('config');
        $secretKey = sha1($config->resources->db->params->password);
        $secretKey = substr(base_convert($secretKey, 16, 32), 0, 12);
        return $secretKey;
    }

    /**
     * @return bool|string
     * @throws Zend_Exception
     */
    private static function getSecretCode()
    {
        $config = Zend_Registry::get('config');
        $secretKey = md5($config->resources->db->params->host);
        $secretKey = substr(base_convert($secretKey, 16, 32), 0, 8);
        return $secretKey;
    }

    private static function getIv()
    {
        $ivLen = openssl_cipher_iv_length(self::$cipher);
        $code = self::getSecretCode();
        $ivCode = str_pad($code, $ivLen, $code);
        return substr($ivCode, 0, $ivLen);
    }
}
