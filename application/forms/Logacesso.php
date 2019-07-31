<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Default_Form_Logacesso extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbPerfilpessoa = new Default_Model_Mapper_TbPerfilpessoa();
        $this
            ->setOptions(array(
                "method" => "post",
                "elements" => array(
                    'idmodulo' => array('hidden', array()),
                    'idperfilpessoa' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => false,
                            'multiOptions' => $mapperTbPerfilpessoa->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'datacesso' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),

                )
            ));
    }


}

