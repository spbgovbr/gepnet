<?php

/**
 * Description of TimeInterval
 *
 * @author Wilton Barbosa da Silva Júnior
 */

/**
 * Include needed Date classes
 */
//require_once 'Zend/Date/DateObject.php';

class App_DateTime extends DateTime
{
    public function __construct($data, $format)
    {
        parent::__construct();
        return DateTime::createFromFormat($format, $data);
    }

    public function toString($format)
    {
        return $this->format($format);
    }
}