<?php

class Diagnostico_Form_SugestaoMelhoria extends App_Form_FormAbstract
{

    public function init()
    {
        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-sugestaomelhoria',
            'elements' => array(
                'idmelhoria' => array(
                    'text',
                    array(
                        'label' => 'Código',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'class' => 'span1',
                            'maxlength' => '30',
                            'data-rule-maxlength' => 30,
                            'data-rule-required' => false,
                            'readonly' => true
                        ),
                    )
                ),
                'idunidadeprincipal' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'unidadegestora' => array(
                    'text',
                    array(
                        'label' => 'Unidade Gestora',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '50',
                            'data-rule-maxlength' => 50,
                            'data-rule-required' => false,
                            'readonly' => true
                        ),
                    )
                ),
                'matriculaproponente' => array(
                    'text',
                    array(
                        'label' => 'Matrícula do Proponente',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits', array('NotEmpty', 'StringLength', false, array(0, 4))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '9',
                            'data-rule-number' => true,
                            'class' => 'span2',
                            'data-rule-maxlength' => 9,
                            'data-rule-required' => false,
                            'placeholder' => 'Informe a matrícula do proponente'
                        ),
                    )
                ),
                'datmelhoria' => array(
                    'text',
                    array(
                        'label' => 'Data',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'autocomplete' => 'off',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idmacroprocessotrabalho' => array(
                    'select',
                    array(
                        'label' => 'Macroprocesso de Trabalho',
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
                'idmacroprocessomelhorar' => array(
                    'select',
                    array(
                        'label' => 'Macroprocesso Melhorar',
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
                'idunidaderesponsavelproposta' => array(
                    'select',
                    array(
                        'label' => 'Unidade Responsável pela Proposta',
                        'required' => true,
                        'multiOptions' => array('' => 'Selecione'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'flaabrangencia' => array(
                    'select',
                    array(
                        'label' => 'Abrangência',
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
                'idunidaderesponsavelimplantacao' => array(
                    'select',
                    array(
                        'label' => 'Unidade Responsável pela Implantação',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idobjetivoinstitucional' => array(
                    'select',
                    array(
                        'label' => 'Objetivo Institucional',
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
                'iddiagnostico' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'desmelhoria' => array(
                    'textarea',
                    array(
                        'label' => 'Descrição da Melhoria',
                        'maxlength' => 4000,
                        'wrap' => 'hard',
                        'style' => 'word-wrap: break-word; word-break: break-all;',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span5',
                            'data-rule-minlength' => 10,
                            'data-rule-maxlength' => 4000,
                            'rows' => 3,
                            'placeholder' => 'Informe a descrição da melhoria',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'tratamento' => array(
                    'select',
                    array(
                        'label' => 'Objetivo Institucional',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span2',
                        ),
                    )
                ),
                'idacaoestrategica' => array(
                    'select',
                    array(
                        'label' => 'Ação Estratégica',
                        'required' => true,
                        'multiOptions' => array('' => 'Selecione'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idareamelhoria' => array(
                    'select',
                    array(
                        'label' => 'Área de Melhoria',
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
                'idsituacao' => array(
                    'select',
                    array(
                        'label' => 'Situação',
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
            )
        ));
    }
}
