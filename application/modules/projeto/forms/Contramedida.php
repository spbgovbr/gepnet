<?php

class Projeto_Form_Contramedida extends App_Form_FormAbstract
{

    public function init()
    {
        $tipoContramedida = new Projeto_Model_Mapper_Tipocontramedida();

        $arrStatusContramedida = array(
            "" => "Selecione",
            "1" => "Atrasada",
            "2" => "Cancelada",
            "3" => "Concluída",
            "4" => "Em Andamento",
            "5" => "Não Iniciada",
            "6" => "Paralisada",
        );

        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-contramedida',
            'elements' => array(
                'idcontramedida' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'idrisco' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'nocontramedida' => array(
                    'text',
                    array(
                        'label' => 'Título Contramedida',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span5',
                            'maxlength' => '100',
                            'data-rule-maxlength' => 100,
                            'data-rule-required' => true,
                            'placeholder' => 'Título da contramedida'
                        ),
                    )
                ),
                'descontramedida' => array(
                    'textarea',
                    array(
                        'label' => 'Descrição da Proposição/Contramedida',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span10',
                            'rows' => 4,
                            'placeholder' => 'Descrição da Proposição/Contramedida',
                        ),
                    )
                ),
                'datprazocontramedida' => array(
                    'text',
                    array(
                        'label' => 'Prazo',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA'
                        ),
                    )
                ),
                'datprazocontramedidaatraso' => array(
                    'text',
                    array(
                        'label' => 'Tendência/Real',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA'
                        ),
                    )
                ),
                'flacontramedidaefetiva' => array(
                    'select',
                    array(
                        'label' => 'Contramedida Efetiva?',
                        'required' => true,
                        'multiOptions' => array('' => 'Selecione', '1' => 'Sim', '2' => 'Não'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idtipocontramedida' => array(
                    'select',
                    array(
                        'label' => 'Tipo Contramedida',
                        'required' => true,
                        'multiOptions' => $tipoContramedida->fetchPairs(true),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'domstatuscontramedida' => array(
                    'select',
                    array(
                        'label' => 'Status Contramedida',
                        'required' => true,
                        'multiOptions' => $arrStatusContramedida,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'desresponsavel' => array(
                    'text',
                    array(
                        'label' => 'Responsável',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '100',
                            'data-rule-maxlength' => 100,
                            'placeholder' => 'Informe o responsável'
                        ),
                    )
                ),
            )
        ));
    }
}
