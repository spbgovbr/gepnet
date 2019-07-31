<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Default_Form_PessoaPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "pessoa-pesquisar",
                "elements" => array(
                    'nompessoa' => array(
                        'text',
                        array(
                            'label' => 'Nome',
                            'required' => false,
                            'maxlength' => '100',
                            'continue_if_empty' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'placeholder' => 'Nome',
                            'attribs' => array(
                                'class' => 'span3'
                            ),
                        )
                    ),
                    'numcpf' => array(
                        'text',
                        array(
                            'label' => 'CPF',
                            'required' => false,
                            'maxlength' => '14',
                            'continue_if_empty' => true,
                            'filters' => array('StringTrim', 'StripTags', 'Digits'),
                            'validators' => array('NotEmpty', 'Cpf', array('StringLength', false, array(11, 14))),
                            'placeholder' => 'CPF',
                            'attribs' => array(
                                'class' => 'span2 mask-cpf cpf',
                                'data-rule-maxlength' => 14,
                                'data-rule-minlength' => 11,
                                'data-rule-cpf' => true,
                            ),
                        )
                    ),
                    'submit' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Pesquisar',
                            'icon' => 'filter',
                            'whiteIcon' => false,
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'submitbutton',
                                'type' => 'submit',
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
        $this->getElement('submit')
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

