<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Default_Form_Mudanca extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbPessoa = new Default_Model_Mapper_TbPessoa();
        $mapperTbTipomudanca = new Default_Model_Mapper_TbTipomudanca();
        $this
            ->setOptions(array(
                "method" => "post",
                "elements" => array(
                    'idmudanca' => array('hidden', array()),
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
                    'nomsolicitante' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(),
                        )
                    ),
                    'datsolicitacao' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4))),
                            'attribs' => array(),
                        )
                    ),
                    'datdecisao' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4))),
                            'attribs' => array(),
                        )
                    ),
                    'flaaprovada' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'desmudanca' => array(
                        'textarea',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, -1))),
                            'attribs' => array('rows' => 24, 'cols' => 80),
                        )
                    ),
                    'desjustificativa' => array(
                        'textarea',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, -1))),
                            'attribs' => array('rows' => 24, 'cols' => 80),
                        )
                    ),
                    'despareceregp' => array(
                        'textarea',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, -1))),
                            'attribs' => array('rows' => 24, 'cols' => 80),
                        )
                    ),
                    'desaprovadores' => array(
                        'textarea',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, -1))),
                            'attribs' => array('rows' => 24, 'cols' => 80),
                        )
                    ),
                    'despareceraprovadores' => array(
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
                    'idtipomudanca' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => true,
                            'multiOptions' => $mapperTbTipomudanca->fetchPairs(),
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

