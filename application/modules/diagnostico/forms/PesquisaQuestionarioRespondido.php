<?php

class Diagnostico_Form_PesquisaQuestionarioRespondido extends App_Form_FormAbstract
{
    public function init()
    {
        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-pesquisar-questionario_resp",
                "elements" => array(
                    'iddiagnostico' => array('hidden', array()),
                    'tpquestionario' => array('hidden', array()),
                    'nomquestionario' => array(
                        'text',
                        array(
                            'label' => 'Nome Questionário',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'placeholder' => 'Nome do Questionário',
                            'attribs' => array(
                                'class' => 'span3',
                                'id' => 'nomquestionario'
                            ),
                        )
                    ),
                    'dt_resposta' => array(
                        'text',
                        array(
                            'label' => 'Data',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 10))),
                            'placeholder' => 'Data',
                            'attribs' => array(
                                'class' => 'span2',
                                'id' => 'dt_resposta'
                            ),
                        )
                    ),
                    'numero' => array(
                        'text',
                        array(
                            'label' => 'Número',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'placeholder' => 'Número do questionário',
                            'attribs' => array(
                                'class' => 'span2',
                                'id' => 'numero'

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
                                'id' => 'subPesq',
                                'type' => 'submit',
                                'class' => 'btn'
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