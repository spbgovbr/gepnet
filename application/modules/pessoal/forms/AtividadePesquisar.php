<?php

class Pessoal_Form_AtividadePesquisar extends App_Form_FormAbstract
{

    public function init()
    {

        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "atividade-pesquisar",
                "elements" => array(
                    'idatividade' => array('hidden', array()),
                    'nomatividade' => array(
                        'text',
                        array(
                            'label' => 'Nome da Atividade',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span3',
                                'data-rule-required' => true,
                            )
                        )
                    ),
                    'concluida' => array(
                        'select',
                        array(
                            'label' => 'Concluída?',
                            'required' => false,
                            'multiOptions' => array(2 => 'Não', 1 => 'Sim', 'T' => 'Todas'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array('class' => 'span1'),
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
