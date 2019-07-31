<?php

class App_Generator_Php_Form_Element_Text extends App_Generator_Php_Form_Element_Abstract
{
    const format = "'%s' => array('%s', array(
        'label'        => '%s',
        'required'     => %s,
        'filters'      => array(%s),
        'validators'   => array(%s),
        'attribs'      => array(%s),
    )),\n";

    public function __toString()
    {
        return sprintf(self::format,
            $this->prop->fieldName,
            $this->prop->fieldType,
            $this->prop->label,
            $this->prop->required,
            $this->prop->filters,
            $this->prop->validators,
            $this->prop->attribs);
    }
}