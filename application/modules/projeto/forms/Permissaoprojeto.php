<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated08-07-2013 13:45
 */
class Projeto_Form_Permissaoprojeto extends App_Form_FormAbstract
{

    public function init()
    {
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-permissaoprojeto",
                "elements" => array(
                    'idpermissao' => array('hidden', array()),
                    'idparteinteressada' => array('hidden', array()),
                    'idprojeto' => array('hidden', array()),
                    'idpessoa' => array('hidden', array()),
                    'data' => array(
                        'text',
                        array(
                            'label' => 'Data',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'maxlength' => 10,
                                'size' => 10,
                                'class' => 'span6',
                                'data-rule-required' => true,
                                'data-rule-maxlength' => 10,
                                'data-rule-minlength' => 10,
                            ),
                        )
                    ),
                    'ativo' => array(
                        'text',
                        array(
                            'label' => 'Situação',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                            'attribs' => array(
                                'maxlength' => 1,
                                'size' => 1,
                                'class' => 'span6',
                                'data-rule-required' => true,
                                'data-rule-maxlength' => 1,
                                'data-rule-minlength' => 1,
                            ),
                        )
                    ),

                )
            ));
    }

}

