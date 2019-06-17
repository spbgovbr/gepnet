<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Default_Form_Eventoavaliacao extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbEvento = new Default_Model_Mapper_TbEvento();
        $mapperTbPessoa = new Default_Model_Mapper_TbPessoa();
        $this
            ->setOptions(array(
                "method" => "post",
                "elements" => array(
                    'ideventoavaliacao' => array('hidden', array()),
                    'idevento' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => true,
                            'multiOptions' => $mapperTbEvento->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),
                    'desdestaqueservidor' => array(
                        'textarea',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, -1))),
                            'attribs' => array('rows' => 24, 'cols' => 80),
                        )
                    ),
                    'desobs' => array(
                        'textarea',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, -1))),
                            'attribs' => array('rows' => 24, 'cols' => 80),
                        )
                    ),
                    'idavaliador' => array(
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
                    'idavaliado' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
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
                    'numpontualidade' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numordens' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numrespeitochefia' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numrespeitocolega' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numurbanidade' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numequilibrio' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numcomprometimento' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numesforco' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numtrabalhoequipe' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numauxiliouequipe' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numaceitousugestao' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numconhecimentonorma' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numalternativaproblema' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numiniciativa' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numtarefacomplexa' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'domtipoavaliacao' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 60))),
                            'attribs' => array(),
                        )
                    ),
                    'numnotaavaliador' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'nummedia' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'nummediafinal' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numtotalavaliado' => array(
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

