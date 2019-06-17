<?php

class Diagnostico_Form_PadronizacaoMelhoria extends App_Form_FormAbstract
{

    public function init()
    {
        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-sugestaomelhoria',
            'elements' => array(
                'idpadronizacaomelhoria' => array('hidden', array()),
                'idmelhoria' => array('hidden', array()),
                'desrevisada' => array(
                    'textarea',
                    array(
                        'label' => 'Descrição Revisada',
                        'maxlength' => 4000,
                        'wrap' => 'hard',
                        'style' => 'word-wrap: break-word; word-break: break-all;',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span8',
                            'data-rule-minlength' => 10,
                            'data-rule-maxlength' => 4000,
                            'rows' => 3,
                            'placeholder' => 'Descrição revisada',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'iddiagnostico' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'idprazo' => array(
                    'select',
                    array(
                        'label' => 'Prazo',
                        'required' => true,
                        'multiOptions' => array(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 2))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idimpacto' => array(
                    'select',
                    array(
                        'label' => 'Impacto',
                        'required' => true,
                        'multiOptions' => array(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 2))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idesforco' => array(
                    'select',
                    array(
                        'label' => 'Esforço',
                        'required' => true,
                        'multiOptions' => array(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'numpontuacao' => array(
                    'text',
                    array(
                        'label' => 'Pontuação',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'class' => 'span1',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-required' => false,
                            'readonly' => true
                        ),
                    )
                ),
                'numincidencia' => array(
                    'text',
                    array(
                        'label' => 'Incidência',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'class' => 'span1',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-required' => false,
                            'readonly' => true
                        ),
                    )
                ),
                'numvotacao' => array(
                    'text',
                    array(
                        'label' => 'Votação',
                        'required' => true,
                        'maxlength' => '6',
                        'filters' => array('Digits', 'StringTrim', 'StripTags'),
                        'validators' => array('Digits', array('NotEmpty', 'StringLength', false, array(0, 4))),
                        'attribs' => array(
                            'data-rule-number' => true,
                            'maxlength' => '4',
                            'class' => 'span1',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'flaagrupadora' => array(
                    'select',
                    array(
                        'label' => 'Agrupadora',
                        'required' => true,
                        'multiOptions' => array(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'destitulogrupo' => array(
                    'text',
                    array(
                        'label' => 'Título do Grupo',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '50',
                            'data-rule-maxlength' => 50,
                            'data-rule-required' => true,
                            'placeholder' => 'Informe o título do grupo'
                        ),
                    )
                ),
                'desmelhoriaagrupadora' => array(
                    'select',
                    array(
                        'label' => 'Melhoria Agrupadora',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'desinformacoescomplementares' => array(
                    'textarea',
                    array(
                        'label' => 'Informações Complementares',
                        'maxlength' => 4000,
                        'wrap' => 'hard',
                        'style' => 'word-wrap: break-word; word-break: break-all;',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span5',
                            'data-rule-minlength' => 10,
                            'data-rule-maxlength' => 4000,
                            'rows' => 3,
                            'placeholder' => 'Descrição das informações complementares',
                            'data-rule-required' => false,
                        ),
                    )
                ),
            )
        ));
    }
}
