<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Default_Form_Aceite extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbAtividadecronograma = new Default_Model_Mapper_TbAtividadecronograma();
        $mapperTbPessoa = new Default_Model_Mapper_TbPessoa();
        $this
            ->setOptions(array(
                "method" => "post",
                "elements" => array(
                    'idaceite' => array('hidden', array()),
                    'identrega' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => true,
                            'multiOptions' => $mapperTbAtividadecronograma->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),
                    'idprojeto' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),
                    'desprodutoservico' => array(
                        'textarea',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, -1))),
                            'attribs' => array('rows' => 24, 'cols' => 80),
                        )
                    ),
                    'desparecerfinal' => array(
                        'textarea',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, -1))),
                            'attribs' => array('rows' => 24, 'cols' => 80),
                        )
                    ),
                    'idcadastrador' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => true,
                            'multiOptions' => $mapperTbPessoa->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),
                    'datcadastro' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 4))),
                            'attribs' => array(),
                        )
                    ),

                )
            ));
    }


}

