<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Default_Form_Acordoentidadeexterna extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbAcordo = new Default_Model_Mapper_TbAcordo();
        $mapperTbEntidadeexterna = new Default_Model_Mapper_TbEntidadeexterna();
        $this
            ->setOptions(array(
                "method" => "post",
                "elements" => array(
                    'idacordo' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => true,
                            'multiOptions' => $mapperTbAcordo->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),
                    'identidadeexterna' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => true,
                            'multiOptions' => $mapperTbEntidadeexterna->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),

                )
            ));
    }


}

