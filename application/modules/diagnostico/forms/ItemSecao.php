<?php

class Diagnostico_Form_ItemSecao extends App_Form_FormAbstract
{

    public function init()
    {
        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-itemsecao",
                "elements" => array(
                    'id_item' => array('hidden', array()),
                    'id_secao' => array('hidden', array()),
                    'idquestionariodiagnostico' => array('hidden', array()),
                    'tpquestionario' => array('hidden', array()),

                    'ds_item' => array(
                        'select',
                        array(
                            'label' => 'Selecionar Seções',
                            'required' => true,
                            'multiOptions' => array(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => false,
                                'size' => 7,
                                'multiple' => 'multiple'
                            ),
                        )
                    ),
                    'id_secao' => array(
                        'select',
                        array(
                            'label' => 'Seções do Questionário',
                            'required' => true,
                            'multiOptions' => array(),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => false,
                                'size' => 7,
                                'multiple' => 'multiple'
                            ),
                        )
                    ),

                    'enviar' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Salvar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'enviarbutton',
                                'type' => 'button',
                            ),
                        )
                    ),
                    'submit' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Salvar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'submitbutton',
                                'type' => 'button',
                            ),
                        )
                    ),
                    'reset' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Limpar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'resetbutton',
                                'type' => 'reset',
                            ),
                        )
                    ),
                    'pessoabutton' => array(
                        'button',
                        array(
                            'label' => '',
                            'ignore' => true,
                            'icon' => 'user',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                            'escape' => true,
                            'attribs' => array(
                                'class' => 'pessoa-button',
                                'type' => 'button',
                            )
                        )
                    ),

                )
            ));

        $this->getElement('enviar')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('reset')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}
