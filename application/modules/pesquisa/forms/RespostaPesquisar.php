<?php

class Pesquisa_Form_RespostaPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $arrSituacao = array(
            '' => 'Selecione',
            Pesquisa_Model_Resposta::ATIVO => 'Ativo',
            Pesquisa_Model_Resposta::INATIVO => 'Inativo',
        );


        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-resposta-pesquisar',
            'elements' => array(
                'idresposta' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'desresposta' => array(
                    'text',
                    array(
                        'label' => 'Resposta',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'id' => 'desresposta_pesquisar',
                            'placeholder' => 'Informe a resposta'
                        ),
                    )
                ),
                'numordem' => array(
                    'text',
                    array(
                        'label' => 'Ordem',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '2',
                            'data-rule-maxlength' => 2,
                            'data-rule-number' => true,
                            'id' => 'numordem_pesquisar',
                            'placeholder' => 'Informe a ordem'
                        ),
                    )
                ),

                'flaativo' => array(
                    'select',
                    array(
                        'label' => 'Situação',
                        'multiOptions' => $arrSituacao,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'flaativo_pesquisar',
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
