<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated26-03-2013 12:46
 */
class Admin_Form_UsuarioPesquisar extends Twitter_Bootstrap_Form_Vertical
{
    public function init()
    {
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "usuario",
                "elements" => array(
                    'cd_matricula' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 15))),
                            'placeholder' => 'Matrícula',
                            'attribs' => array(
                                'class' => 'span2'
                            ),
                        )
                    ),
                    'no_pessoa' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                            'placeholder' => 'Nome',
                            'attribs' => array(
                                'class' => 'span4'
                            ),
                        )
                    ),
                    'submit' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Pesquisar',
                            //'buttonType'    => Twitter_Bootstrap_Form_Element_Submit::BUTTON_SUCCESS,
                            'icon' => 'filter',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                            //'whiteIcon'     => true,
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'submitbutton',
                                'type' => 'submit',
                                //'class' => "btn-large",
                            ),
                        )
                    ),
                )
            ));
        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('no_pessoa')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('cd_matricula')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}

