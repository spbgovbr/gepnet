<?php

class Projeto_Form_ComunicacaoPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $this->setOptions(array(
            "method" => "post",
            "id" => "form-comunicacao-pesquisar",
            "elements" => array(
                'idcomunicacaopesquisar' => array(
                    'text',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 15))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '15',
                            'data-rule-maxlength' => 15,
                        ),
                    )
                ),
                'idprojetopesquisar' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 15))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '15',
                            'data-rule-maxlength' => 15,
                        ),
                    )
                ),
                'desinformacaopesquisar' => array(
                    'text',
                    array(
                        'label' => 'O que será informado?',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                        ),
                    )
                ),
                'desinformadopesquisar' => array(
                    'text',
                    array(
                        'label' => 'Quem será informado?',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                        ),
                    )
                ),
                'desorigempesquisar' => array(
                    'text',
                    array(
                        'label' => 'Qual a origem?',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                        ),
                    )
                ),
                'desfrequenciapesquisar' => array(
                    'text',
                    array(
                        'label' => 'Qual a frequência?',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                        ),
                    )
                ),
                'destransmissaopesquisar' => array(
                    'text',
                    array(
                        'label' => 'Como será transmitida?',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                        ),
                    )
                ),
                'desarmazenamentopesquisar' => array(
                    'text',
                    array(
                        'label' => 'Onde será armazenada?',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '100',
                            'data-rule-maxlength' => 100,
                        ),
                    )
                ),
//                'nomresponsavelpesquisar' => array('text', array(
//                        'label' => 'Responsável:',
//                        'filters' => array('StringTrim', 'StripTags'),
//                        'validators' => array('NotEmpty'),
//                        'attribs' => array(
//                            'class' => 'span3',
//                        ),
//                    )),
                'idresponsavelpesquisar' => array(
                    'select',
                    array(
                        'label' => 'Responsável:',
                        'required' => false,
                        'multiOptions' => array(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'select2, span3',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'btnfiltrar' => array(
                    'button',
                    array(
                        'ignore' => true,
                        'label' => 'Pesquisar',
                        'icon' => 'filter',
                        'whiteIcon' => false,
                        'escape' => false,
                        'attribs' => array(
                            'id' => 'btnfiltrar',
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
        $this->getElement('btnfiltrar')
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
