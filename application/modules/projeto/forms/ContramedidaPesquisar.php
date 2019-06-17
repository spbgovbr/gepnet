<?php

class Projeto_Form_ContramedidaPesquisar extends App_Form_FormAbstract
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
            'id' => 'form-contramedida-pesquisar',
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
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '100',
                            'data-rule-maxlength' => 100,
                            'placeholder' => 'Título da contramedida',
                            'id' => 'nocontramedida_pesquisar',
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
                            'class' => 'span3',
                            'rows' => 4,
                            'placeholder' => 'Descrição da Proposição/Contramedida',
                            'id' => 'descontramedida_pesquisar',
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
                            'class' => 'span3 mask-date',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'id' => 'datprazopesquisar',
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
                            'class' => 'span3 mask-date',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'id' => 'datatrasopesquisar',
                        ),
                    )
                ),
                'flacontramedidaefetiva' => array(
                    'select',
                    array(
                        'label' => 'Contramedida Efetiva?',
                        'multiOptions' => array('' => 'Selecione', '1' => 'Sim', '2' => 'Não'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'flacontramedidaefetivapesquisar',
                        ),
                    )
                ),
                'idtipocontramedida' => array(
                    'select',
                    array(
                        'label' => 'Tipo Contramedida',
                        'multiOptions' => $tipoContramedida->fetchPairs(true),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'descontramedida_pesquisar',
                        ),
                    )
                ),
                'domstatuscontramedida' => array(
                    'select',
                    array(
                        'label' => 'Status Contramedida',
                        'multiOptions' => $arrStatusContramedida,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
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
                'btnpesquisar' => array(
                    'button',
                    array(
                        'ignore' => true,
                        'label' => 'Pesquisar',
                        'icon' => 'filter',
                        'whiteIcon' => false,
                        'escape' => false,
                        'attribs' => array(
                            'id' => 'btnpesquisar',
                            'type' => 'button',
                            'class' => 'btn'
                        ),
                    )
                ),
                'reset' => array(
                    'button',
                    array(
                        'ignore' => true,
                        'icon' => 'th',
                        'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                        'label' => 'Limpar',
                        'escape' => false,
                        'attribs' => array(
                            'id' => 'resetbutton',
                            'type' => 'reset',
                        ),
                    )
                ),
                'close' => array(
                    'button',
                    array(
                        'ignore' => true,
                        'icon' => 'arrow-right',
                        'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                        'label' => 'Fechar',
                        'escape' => false,
                        'attribs' => array(
                            'id' => 'closebutton',
                            'type' => 'button',
                        ),
                    )
                ),
            )
        ));
        $this->getElement('btnpesquisar')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('reset')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('close')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }
}
