<?php

class App_Validate_PasswordStrength extends Zend_Validate_Abstract
{
    const LENGTH = 'length';
    const UPPER = 'upper';
    const LOWER = 'lower';
    const DIGIT = 'digit';
    const ESPECIAL = 'digit';

    protected $_messageTemplates = array(
        self::LENGTH => "'%value%' must be at least 8 characters in length",
        self::UPPER => "'%value%' must contain at least one uppercase letter",
        self::LOWER => "'%value%' must contain at least one lowercase letter",
        self::DIGIT => "'%value%' must contain at least one digit character",
        self::ESPECIAL => "'%value%' must contain at least one character especial [-!@#$%^&*()_=+[{]}]",
    );

    public function isValid($value)
    {
        $this->_setValue($value);

        $isValid = true;

        if (strlen($value) < 8) {
            $this->_error(self::LENGTH);
            $isValid = false;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $this->_error(self::UPPER);
            $isValid = false;
        }

        if (!preg_match('/[a-z]/', $value)) {
            $this->_error(self::LOWER);
            $isValid = false;
        }

        if (!preg_match('/\d/', $value)) {
            $this->_error(self::DIGIT);
            $isValid = false;
        }
        //// make sure "-" is first
        if (!preg_match('/[' . preg_quote('-!@#$%^&*()_=+[{]}') . ']/', $value)) {
            $this->_error(self::ESPECIAL);
            $isValid = false;
        }

        return $isValid;
    }
}