<?php

class Diagnostico_Form_SugestaoMelhoriaPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-sugestaomelhoria-pesquisar',
            'elements' => array(
                'datmelhoria' => array(
                    'text',
                    array(
                        'label' => 'Data',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span3 mask-date',
                            'id' => 'datteccao-pesquisar',
                            //evita conflito preechimento datepicker com o form do cadastro
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA'
                        ),
                    )
                ),
                'idmacroprocessotrabalho' => array(
                    'select',
                    array(
                        'label' => 'Macroprocesso de Trabalho',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '50',
                            'data-rule-maxlength' => 50,
                            'placeholder' => 'Informe o título do risco',
                            'id' => 'noreisco_pesquisar',
                        ),
                    )
                ),
                'idmacroprocessomelhorar' => array(
                    'select',
                    array(
                        'label' => 'Macroprocesso a Melhorar',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '50',
                            'data-rule-maxlength' => 50,
                            'placeholder' => 'Informe o título do risco',
                            'id' => 'idmacroprocessomelhorar_pesquisar',
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
