<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Default_Form_Elementodespesa extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbPessoa = new Default_Model_Mapper_TbPessoa();
        $this
            ->setOptions(array(
                "method" => "post",
                "elements" => array(
                    'idelementodespesa' => array('hidden', array()),
                    'idoficial' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),
                    'nomelementodespesa' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(),
                        )
                    ),
                    'idcadastrador' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => false,
                            'multiOptions' => $mapperTbPessoa->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'datcadastro' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4))),
                            'attribs' => array(),
                        )
                    ),
                    'numseq' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),

                )
            ));
    }


}

