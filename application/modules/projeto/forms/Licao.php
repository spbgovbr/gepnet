<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_Licao extends App_Form_FormAbstract
{

    public function init()
    {

        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-licao",
                "elements" => array(
                    'idprojeto' => array('hidden', array()),
                    'idlicao' => array('hidden', array()),

                    'idassociada' => array(
                        'select',
                        array(
                            'label' => 'Associada a',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'multioptions' => array(),
                            'validators' => array(),
                            'attribs' => array('class' => 'span4', 'data-rule-required' => false),
                        )
                    ),

                    'identrega' => array(
                        'select',
                        array(
                            'label' => 'Entrega',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'multioptions' => array(),
                            'validators' => array(),
                            'attribs' => array('class' => 'span5', 'data-rule-required' => true),
                        )
                    ),
                    'desresultadosobtidos' => array(
                        'textarea',
                        array(
                            'label' => 'Resultados Obtidos',
                            'required' => false,
                            'maxlength' => '1000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 1000))),
                            'attribs' => array(
                                'class' => 'span10',
                                'rows' => '3',
                                'data-rule-required' => false,
                                'maxlength' => '1000'
                            ),
                        )
                    ),
                    'despontosfortes' => array(
                        'textarea',
                        array(
                            'label' => 'Pontos Fortes',
                            'required' => false,
                            'maxlength' => '1000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 1000))),
                            'attribs' => array(
                                'class' => 'span10',
                                'rows' => '3',
                                'data-rule-required' => false,
                                'maxlength' => '1000'
                            ),
                        )
                    ),
                    'despontosfracos' => array(
                        'textarea',
                        array(
                            'label' => 'Pontos Fracos / Dificuldades Encontradas',
                            'required' => false,
                            'maxlength' => '1000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 1000))),
                            'attribs' => array(
                                'class' => 'span10',
                                'rows' => '3',
                                'data-rule-required' => false,
                                'maxlength' => '1000'
                            ),
                        )
                    ),
                    'dessugestoes' => array(
                        'textarea',
                        array(
                            'label' => 'Sugestões para um projeto semelhante',
                            'required' => false,
                            'maxlength' => '1000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 1000))),
                            'attribs' => array(
                                'class' => 'span10',
                                'rows' => '3',
                                'data-rule-required' => false
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
                            'icon' => 'th',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
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

