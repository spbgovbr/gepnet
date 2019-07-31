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
class App_Validate_Cnpj extends Zend_Validate_Abstract
{
    /**
     * Validation failure message key for when the value contains non-digit characters
     */
    const NOT_DIGITS = 'notDigits';

    /**
     * Validation failure message key for when the value is an empty string
     */
    const STRING_EMPTY = 'stringEmpty';

    const CNPJ_INVALIDO = 'cpfInvalido';
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
        self::STRING_LENGTH => "'%value%' o CNPJ deve possuir 14 digitos",
        self::CNPJ_INVALIDO => "'%value%' não é um CNPJ válido"
    );

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if $value only contains digit characters
     *
     * @param string $value
     * @return boolean
     */
    public function isValid($cnpj)
    {
        $cnpj = (string)$cnpj;

        $this->_setValue($cnpj);

        if ('' === $cnpj) {
            $this->_error(self::STRING_EMPTY);
            return false;
        }

        $cnpj = preg_replace("/[\.\-\/]/", "", $cnpj);
        //$cnpj = strstr($cnpj, ".-/", "");//preg_replace("/[\.-]/", "", $cnpj); 

        if (null === self::$_filter) {
            /**
             * @see Zend_Filter_Digits
             */
            require_once 'Zend/Filter/Digits.php';
            self::$_filter = new Zend_Filter_Digits();
        }

        if ($cnpj !== self::$_filter->filter($cnpj)) {
            $this->_error(self::NOT_DIGITS);
            return false;
        }

        $b = array(6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2);
        $cnpj = str_split($cnpj);

        if (count($cnpj) != 14) {
            $this->_error(self::STRING_LENGTH);
            return false;
        }

        for ($i = 0, $n = 0; $i < 12; $n += $cnpj[$i] * $b[++$i]) {
            ;
        }

        if ($cnpj[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            $this->_error(self::CNPJ_INVALIDO);
            return false;
        }

        for ($i = 0, $n = 0; $i <= 12; $n += $cnpj[$i] * $b[$i++]) {
            ;
        }

        if ($cnpj[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            $this->_error(self::CNPJ_INVALIDO);
            return false;
        }

        return true;
    }
}
