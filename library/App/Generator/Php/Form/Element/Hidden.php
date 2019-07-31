<?php

class App_Generator_Php_Form_Element_Hidden extends App_Generator_Php_Form_Element_Abstract
{
    const format = "'%s' => array('%s', array()),\n";

    public function __toString()
    {
        return sprintf(self::format,
            $this->prop->fieldName,
            $this->prop->fieldType);
    }
}