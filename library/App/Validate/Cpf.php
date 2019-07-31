<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Digits.php 8064 2008-02-16 10:58:39Z thomas $
 */


/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';


/**
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class App_Validate_Cpf extends Zend_Validate_Abstract
{
    /**
     * Validation failure message key for when the value contains non-digit characters
     */
    const NOT_DIGITS = 'notDigits';

    /**
     * Validation failure message key for when the value is an empty string
     */
    const STRING_EMPTY = 'stringEmpty';
    const CPF_INVALIDO = 'cpfInvalido';
    const STRING_LENGTH = 'stringLength';

    /**
     * Digits filter used for validation
     *
     * @var Zend_Filter_Digits
     */
    protected static $_filter = null;

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::NOT_DIGITS => "'%value%' contains not only digit characters",
        self::STRING_EMPTY => "'%value%' is an empty string",
        self::STRING_LENGTH => "'%value%' o CPF deve possuir 11 digitos",
        self::CPF_INVALIDO => "'%value%' não é um CPF válido"
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value only contains digit characters
     *
     * @param string $value
     * @return boolean
     */
    public function isValid($cpf)
    {
        $cpf = (string)$cpf;

        $this->_setValue($cpf);

        if ('' === $cpf) {
            $this->_error(self::STRING_EMPTY);
            return false;
        }

        $cpf = preg_replace("/[\.-]/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        if (null === self::$_filter) {
            /**
             * @see Zend_Filter_Digits
             */
            require_once 'Zend/Filter/Digits.php';
            self::$_filter = new Zend_Filter_Digits();
        }

        if ($cpf !== self::$_filter->filter($cpf)) {
            $this->_error(self::NOT_DIGITS);
            return false;
        }

        for ($i = 0; $i <= 9; $i++) {
            if ($cpf == str_repeat($i, 11)) {
                $this->_error(self::CPF_INVALIDO);
                return false;
            }
        }

        if (strlen($cpf) != 11) {
            $this->_error(self::STRING_LENGTH);
            return false;
        }

        $res = self::soma(10, $cpf);
        $dig1 = self::pega_digito($res);
        $res2 = self::soma(11, $cpf . $dig1);
        $dig2 = self::pega_digito($res2);

        if ($cpf{9} != $dig1 || $cpf{10} != $dig2) {
            $this->_error(self::CPF_INVALIDO);
            return false;
        }

        return true;
    }


    public function soma($num, $cpf)
    {
        $j = 0;
        $res = "";
        for ($i = $num; $i >= 2; $i--) {
            $res += ($i * $cpf{$j});
            $j++;
        }
        return $res;
    }

    public function pega_digito($res)
    {
        $dig = $res % 11;
        $dig = $dig < 2 ? $dig = 0 : $dig = 11 - $dig;
        return $dig;
    }

}
