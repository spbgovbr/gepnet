<?php

class Diagnostico_Form_Questionario extends App_Form_FormAbstract
{

    public function init()
    {
        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-questionario",
                "elements" => array(
                    'idquestionariodiagnostico' => array('hidden', array()),
                    'dtrespondido' => array('hidden', array()),
                    'respondido' => array('hidden', array()),

                    'nomquestionario' => array(
                        'text',
                        array(
                            'label' => 'Nome do Questionário',
                            'required' => true,
                            'maxlength' => '100',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span3',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'tipo' => array(
                        'select',
                        array(
                            'label' => 'Tipo',
                            'required' => true,
                            'multiOptions' => array('' => 'Selecione', '2' => 'Cidadão', '1' => 'Servidor'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'select2',
                                'id' => 'tpquestionario',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'observacao' => array(
                        'textarea',
                        array(
                            'label' => 'Observações',
                            'maxlength' => 4000,
                            'wrap' => 'hard',
                            'style' => 'word-wrap: break-word; word-break: break-all;',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span5',
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 4000,
                                'rows' => 4,
                                'placeholder' => 'Observações',
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
                                'type' => 'submit',
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
