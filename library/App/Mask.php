<?php

class App_Mask
{

    public static $masks = array(
        /**
         * DDDDD-DDD
         */
        'cep' => array(
            'pattern' => '/([\d]{5})([\d]{3})/',
            'replacement' => '$1-$2'
        ),
        /**
         *  DD.DDD.DDD/DDDD-DD
         */
        'cnpj' => array(
            'pattern' => '/([\d]{2})([\d]{3})([\d]{3})([\d]{4})([\d]{5})/',
            'replacement' => '$1.$2.$3/$4-$5'
        ),
        /**
         * DDD.DDD.DDD-DD
         */
        'cpf' => array(
            'pattern' => '/([\d]{3})([\d]{3})([\d]{3})([\d]{2})/',
            'replacement' => '$1.$2.$3-$4'
        ),
        /**
         * AA.TR.NNNNN-D
         */
        'processo10' => array(
            'pattern' => '/([\d]{2})([\d]{2})([\d]{5})([\d]{1})/',
            'replacement' => '$1.$2.$3-$4'
        ),
        /**
         * AAAA.RE.OR.NNNNNN-D
         */
        'processo15' => array(
            'pattern' => '/([\d]{4})([\d]{2})([\d]{2})([\d]{6})([\d]{1})/',
            'replacement' => '$1.$2.$3.$4-$5'
        ),
        /**
         * NNNNNNN-DD.AAAA.J.TR.OOOO
         */
        'processo20' => array(
            'pattern' => '/([\d]{7})([\d]{2})([\d]{4})([\d]{1})([\d]{2})([\d]{4})/',
            'replacement' => '$1-$2.$3.$4.$5.$6'
        ),
    );

    private function __construct()
    {

    }

    public static function cpf($cpf)
    {
        $cpf = str_pad($cpf, 11, "0", STR_PAD_LEFT);
        $filtros = new Zend_Filter();
        $filtros
            ->addFilter(new Zend_Filter_Digits());
        $cpf = $filtros->filter($cpf);
        return preg_replace(self::$masks['cpf']['pattern'], self::$masks['cpf']['replacement'], $cpf);
    }

    public static function cep($cep)
    {
        $filtros = new Zend_Filter();
        $filtros->addFilter(new Zend_Filter_Digits());
        $cep = $filtros->filter($cep);
        return preg_replace(self::$masks['cep']['pattern'], self::$masks['cep']['replacement'], $cep);
    }

    public static function cnpj($cnpj)
    {
        $filtros = new Zend_Filter();
        $filtros->addFilter(new Zend_Filter_Digits());
        $cnpj = $filtros->filter($cnpj);
        return preg_replace(self::$masks['cnpj']['pattern'], self::$masks['cnpj']['replacement'], $cnpj);
    }

    public static function processo($processo)
    {
        $filtros = new Zend_Filter();
        $filtros->addFilter(new Zend_Filter_Digits());
        $processo = $filtros->filter($processo);
        $len = strlen($processo);
        //echo $len;
        //echo $processo;
        if ($len > 15 && $len < 20) {
            $strpad = 20;
        } elseif ($len > 10 && $len < 15) {
            $strpad = 15;
        } else {
            $strpad = 10;
        }

        $processo = str_pad($processo, $strpad, '0', STR_PAD_LEFT);
        $len = strlen($processo);
        $key = __FUNCTION__ . $len;
        if (!array_key_exists($key, self::$masks)) {
            throw new Exception('Não existe essa mascara[' . $key . ']');
        }
        return preg_replace(self::$masks[$key]['pattern'], self::$masks[$key]['replacement'], $processo);
    }

    public static function formatMoney($numero)
    {
        $filtros = new Zend_Filter();
        $filtros->addFilter(new Zend_Filter_Digits());
        $numero = $filtros->filter($numero);
        $thousandsSep = '.';
        $decimalPoint = ',';
        //$numero = self::sanitize($numero);
        $centavos = substr($numero, -2);
        $valor = substr($numero, 0, -2);
        $s = str_split($valor);

        for ($i = count($s) - 3; $i > 0; $i -= 3) {
            $s[$i - 1] = $s[$i - 1] . $thousandsSep;
        }

        $valor = implode($s);
        $valor = $valor . $decimalPoint . $centavos;
        return (string)$valor;
    }

    public static function bytes($bytes)
    {
        $orig = $bytes;
        $ext = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $unitCount = 0;

        for (; $bytes > 1024; $unitCount++) {
            $bytes /= 1024;
        }
        $human1 = sprintf("%s %s", round($bytes, 2), $ext[$unitCount]);
        //$human2 = sprintf("%s %s", $orig, $ext[0]);
        //return $human1 == $human2 ? $human1 : sprintf("%s (%s)", $human1, $human2);  
        return $human1; /* == $human2 ? $human1 : sprintf("%s (%s)", $human1, $human2); */
    }

}

?>