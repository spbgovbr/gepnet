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
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Date.php 24594 2012-01-05 21:27:01Z matthew $
 */

/**
 * @see Zend_Validate_Abstract
 */
require_once 'Zend/Validate/Abstract.php';

/**
 * @category   Zend
 * @package    Zend_Validate
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class App_Validate_DateRange extends Zend_Validate_Abstract
{
    const INVALID = 'dateInvalid';
    const INVALID_DATE = 'dateInvalidDate';
    const FALSEFORMAT = 'dateFalseFormat';
    const NOT_BETWEEN = 'dateOutInterval';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = array(
        self::INVALID => "Invalid type given. String, integer, array or Zend_Date expected",
        self::INVALID_DATE => "'%value%' does not appear to be a valid date",
        self::FALSEFORMAT => "'%value%' does not fit the date format '%format%'",
        self::NOT_BETWEEN => "'%value%' deve estar entre '%min%' and '%max%'",
    );

    /**
     * @var array
     */
    protected $_messageVariables = array(
        'format' => '_format',
        'min' => '_min',
        'max' => '_max',
    );

    /**
     * Optional format
     *
     * @var string|null
     */
    protected $_format;

    /**
     * Minimum value
     *
     * @var mixed
     */
    protected $_min;

    /**
     * Maximum value
     *
     * @var mixed
     */
    protected $_max;

    /**
     * Whether to do inclusive comparisons, allowing equivalence to min and/or max
     *
     * If false, then strict comparisons are done, and the value may equal neither
     * the min nor max options
     *
     * @var boolean
     */
    protected $_inclusive;

    /**
     * Optional locale
     *
     * @var string|Zend_Locale|null
     */
    protected $_locale;

    /**
     * Sets validator options
     *
     * @param string|Zend_Config $options OPTIONAL
     * @return void
     */
    public function __construct($options = array())
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } else {
            if (!is_array($options)) {
                $options = func_get_args();

                $temp['min'] = array_shift($options);
                if (!empty($options)) {
                    $temp['max'] = array_shift($options);
                }

                if (!empty($options)) {
                    $temp['inclusive'] = array_shift($options);
                }

                $temp['format'] = array_shift($options);
                if (!empty($options)) {
                    $temp['locale'] = array_shift($options);
                }

                $options = $temp;
            }
        }

        if (array_key_exists('format', $options)) {
            $this->setFormat($options['format']);
        }

        if (!array_key_exists('locale', $options)) {
            require_once 'Zend/Registry.php';
            if (Zend_Registry::isRegistered('Zend_Locale')) {
                $options['locale'] = Zend_Registry::get('Zend_Locale');
            }
        }

        if (array_key_exists('locale', $options)) {
            $this->setLocale($options['locale']);
        }

        if (!array_key_exists('min', $options) || !array_key_exists('max', $options)) {
            require_once 'Zend/Validate/Exception.php';
            throw new Zend_Validate_Exception("Missing option. 'min' and 'max' has to be given");
        }

        if (!array_key_exists('inclusive', $options)) {
            $options['inclusive'] = true;
        }

        $this->setMin($options['min'])
            ->setMax($options['max'])
            ->setInclusive($options['inclusive']);
    }

    /**
     * Returns the locale option
     *
     * @return string|Zend_Locale|null
     */
    public function getLocale()
    {
        return $this->_locale;
    }

    /**
     * Sets the locale option
     *
     * @param string|Zend_Locale $locale
     * @return Zend_Validate_Date provides a fluent interface
     */
    public function setLocale($locale = null)
    {
        require_once 'Zend/Locale.php';
        $this->_locale = Zend_Locale::findLocale($locale);
        return $this;
    }

    /**
     * Returns the locale option
     *
     * @return string|null
     */
    public function getFormat()
    {
        return $this->_format;
    }

    /**
     * Sets the format option
     *
     * @param string $format
     * @return Zend_Validate_Date provides a fluent interface
     */
    public function setFormat($format = null)
    {
        $this->_format = $format;
        return $this;
    }

    /**
     * Returns the min option
     *
     * @return mixed
     */
    public function getMin()
    {
        return $this->_min;
    }

    /**
     * Sets the min option
     *
     * @param mixed $min
     * @return Zend_Validate_Between Provides a fluent interface
     */
    public function setMin($min)
    {
        $this->_min = $min;
        return $this;
    }

    /**
     * Returns the max option
     *
     * @return mixed
     */
    public function getMax()
    {
        return $this->_max;
    }

    /**
     * Sets the max option
     *
     * @param mixed $max
     * @return Zend_Validate_Between Provides a fluent interface
     */
    public function setMax($max)
    {
        $this->_max = $max;
        return $this;
    }

    /**
     * Returns the inclusive option
     *
     * @return boolean
     */
    public function getInclusive()
    {
        return $this->_inclusive;
    }

    /**
     * Sets the inclusive option
     *
     * @param boolean $inclusive
     * @return Zend_Validate_Between Provides a fluent interface
     */
    public function setInclusive($inclusive)
    {
        $this->_inclusive = $inclusive;
        return $this;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if $value is a valid date of the format YYYY-MM-DD
     * If optional $format or $locale is set the date format is checked
     * according to Zend_Date, see Zend_Date::isDate()
     *
     * @param string|array|Zend_Date $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (!is_string($value) && !is_int($value) && !is_float($value) &&
            !is_array($value) && !($value instanceof Zend_Date)) {
            $this->_error(self::INVALID);
            return false;
        }

        $this->_setValue($value);

        require_once 'Zend/Date.php';
        if (!Zend_Date::isDate($value, $this->_format, $this->_locale)) {
            $this->_error(self::INVALID_DATE);
            return false;
        }

        if (!Zend_Date::isDate($this->_min, $this->_format, $this->_locale)) {
            $this->_error(self::INVALID_DATE);
            return false;
        }

        if (!Zend_Date::isDate($this->_max, $this->_format, $this->_locale)) {
            $this->_error(self::INVALID_DATE);
            return false;
        }

        $min = new Zend_Date($this->_min, null, $this->_locale);
        $max = new Zend_Date($this->_max, null, $this->_locale);
        $date = new Zend_Date($value, null, $this->_locale);

        if ($date->isEarlier($min) || $date->isLater($max)) {
            $this->_error(self::NOT_BETWEEN);
            return false;
        }

        return true;
    }

    /**
     * Check if the given date fits the given format
     *
     * @param string $value Date to check
     * @return boolean False when date does not fit the format
     */
    private function _checkFormat($value)
    {
        try {
            require_once 'Zend/Locale/Format.php';
            $parsed = Zend_Locale_Format::getDate($value, array(
                'date_format' => $this->_format,
                'format_type' => 'iso',
                'fix_date' => false
            ));
            if (isset($parsed['year']) and ((strpos(strtoupper($this->_format), 'YY') !== false) and
                    (strpos(strtoupper($this->_format), 'YYYY') === false))) {
                $parsed['year'] = Zend_Date::getFullYear($parsed['year']);
            }
        } catch (Exception $e) {
            // Date can not be parsed
            return false;
        }

        if (((strpos($this->_format, 'Y') !== false) or (strpos($this->_format, 'y') !== false)) and
            (!isset($parsed['year']))) {
            // Year expected but not found
            return false;
        }

        if ((strpos($this->_format, 'M') !== false) and (!isset($parsed['month']))) {
            // Month expected but not found
            return false;
        }

        if ((strpos($this->_format, 'd') !== false) and (!isset($parsed['day']))) {
            // Day expected but not found
            return false;
        }

        if (((strpos($this->_format, 'H') !== false) or (strpos($this->_format, 'h') !== false)) and
            (!isset($parsed['hour']))) {
            // Hour expected but not found
            return false;
        }

        if ((strpos($this->_format, 'm') !== false) and (!isset($parsed['minute']))) {
            // Minute expected but not found
            return false;
        }

        if ((strpos($this->_format, 's') !== false) and (!isset($parsed['second']))) {
            // Second expected  but not found
            return false;
        }

        // Date fits the format
        return true;
    }
}
