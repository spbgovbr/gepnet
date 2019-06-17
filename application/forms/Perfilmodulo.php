<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Default_Form_Perfilmodulo extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbPerfil = new Default_Model_Mapper_TbPerfil();
        $mapperTbModulo = new Default_Model_Mapper_TbModulo();
        $this
            ->setOptions(array(
                "method" => "post",
                "elements" => array(
                    'idperfil' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => true,
                            'multiOptions' => $mapperTbPerfil->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),
                    'idmodulo' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => true,
                            'multiOptions' => $mapperTbModulo->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),

                )
            ));
    }


}

