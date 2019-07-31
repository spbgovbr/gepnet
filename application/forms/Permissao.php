<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated08-07-2013 13:45
 */
class Default_Form_Permissao extends App_Form_FormAbstract
{

    public function init()
    {
        $this
            ->setOptions(array(
                "method"   => "post",
                "id" => "form-permissao",
                "elements" => array(
                    'idpermissao'  => array('hidden', array()),
                    'ds_permissao' => array('text', array(
                            'label'      => 'Descrição',
                            'required'   => true,
                            'filters'    => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs'    => array(
                                'maxlength'           => 50,
                                'size'                => 2,
                                'class'               => 'span6',
                                'data-rule-required'  => true,
                                'data-rule-maxlength' => 50,
                                'data-rule-minlength' => 3,
                            ),
                        )),
                    
                )
        ));
    }

}

