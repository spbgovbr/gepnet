<?php

class Pesquisa_Form_QuestionarioFrasePesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $obrigatoriedade = array('' => 'Selecione', 'N' => 'Não', 'S' => 'Sim');

        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-questionario-frase-pesquisar',
            'elements' => array(
                'idquestionario' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'idfrase' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'numordempergunta' => array(
                    'text',
                    array(
                        'label' => 'Ordem',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => 3,
                            'data-rule-number' => true,
                            'data-rule-maxlength' => 3,
                            'id' => 'numordempergunta_pesquisar',
                        ),
                    )
                ),
                'desfrase' => array(
                    'text',
                    array(
                        'label' => 'Pergunta',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'id' => 'desfrase_pesquisar',
                            'placeholder' => 'Informe a pergunta.'
                        ),
                    )
                ),
                'obrigatoriedade' => array(
                    'select',
                    array(
                        'label' => 'Obrigatório',
                        'multiOptions' => $obrigatoriedade,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'obrigatoriedade_pesquisar',
                        ),
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
