<?php

class App_Generator_Php_Form_Element_Textarea extends App_Generator_Php_Form_Element_Abstract
{
    const format = "'%s' => array('%s', array(
        'label'        => '%s',
        'required'     => %s,
        'filters'      => array(%s),
        'validators'   => array(%s),
        'attribs'      => array(%s),
    )),\n";

    /**
     * @var int
     */
    public $rows = 24;

    /**
     * @var int
     */
    public $cols = 80;

    public function __toString()
    {
        if ($this->prop->attribs != '') {
            $this->prop->attribs .= ", ";
        }
        return sprintf(self::format,
            $this->prop->fieldName,
            $this->prop->fieldType,
            $this->prop->label,
            $this->prop->required,
            $this->prop->filters,
            $this->prop->validators,
            $this->prop->attribs . "'rows' => 24, 'cols' => 80");
    }
}