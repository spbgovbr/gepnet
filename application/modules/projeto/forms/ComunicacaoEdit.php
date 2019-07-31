<?php

class Projeto_Form_ComunicacaoEdit extends App_Form_FormAbstract
{

    public function init()
    {
        $this->setOptions(array(
            "method" => "post",
            "id" => "form-comunicacao-edit",
            "elements" => array(
                'idcomunicacao' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'idprojeto' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'desinformacao' => array(
                    'text',
                    array(
                        'label' => 'O que será informado?',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span4',
                            'maxlength' => '255',
                            'data-rule-required' => true,
                            'data-rule-minlength' => 2,
                            'data-rule-maxlength' => 255,
                            'placeholder' => 'Ex: Apresentação do andamento do projeto',
                        ),
                    )
                ),
                'desinformado' => array(
                    'text',
                    array(
                        'label' => 'Quem será informado?',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '255',
                            'data-rule-required' => true,
                            'data-rule-minlength' => 2,
                            'data-rule-maxlength' => 255,
                            'placeholder' => 'Ex: EGPS, GP, EGPE, Patrocinador',
                        ),
                    )
                ),
                'desorigem' => array(
                    'text',
                    array(
                        'label' => 'Qual a origem?',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span2',
                            'maxlength' => '255',
                            'data-rule-required' => true,
                            'data-rule-minlength' => 2,
                            'data-rule-maxlength' => 255,
                            'placeholder' => 'Ex:GEPnet',
                        ),
                    )
                ),
                'desfrequencia' => array(
                    'text',
                    array(
                        'label' => 'Qual a frequência?',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span2',
                            'maxlength' => '255',
                            'data-rule-required' => true,
                            'data-rule-minlength' => 2,
                            'data-rule-maxlength' => 255,
                            'placeholder' => 'Ex:mensal',
                        ),
                    )
                ),
                'destransmissao' => array(
                    'text',
                    array(
                        'label' => 'Como será transmitida?',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span2',
                            'maxlength' => '255',
                            'data-rule-required' => true,
                            'data-rule-minlength' => 2,
                            'data-rule-maxlength' => 255,
                            'placeholder' => 'Ex:Reunião presencial',
                        ),
                    )
                ),
                'desarmazenamento' => array(
                    'text',
                    array(
                        'label' => 'Onde será armazenada?',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span2',
                            'maxlength' => '100',
                            'data-rule-required' => true,
                            'data-rule-minlength' => 2,
                            'data-rule-maxlength' => 100,
                            'placeholder' => 'Ex:Gepnet',
                        ),
                    )
                ),
                'nomresponsavel' => array(
                    'text',
                    array(
                        'label' => 'Responsável:',
                        'required' => true,
                        'readonly' => 'readyonly',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span3',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idresponsavel' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
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
        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}
