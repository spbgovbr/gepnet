<?php

class Pesquisa_Form_QuestionarioPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $arrSituacao = array(
            '' => 'Selecione',
            Pesquisa_Model_QuestionarioPesquisa::PUBLICADO_COM_SENHA => 'Publicado com senha',
            Pesquisa_Model_QuestionarioPesquisa::PUBLICADO_SEM_SENHA => 'Publicado sem senha',
        );


        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-questionario-pesquisar',
            'elements' => array(
                'idquestionario' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'nomquestionario' => array(
                    'text',
                    array(
                        'label' => 'Nome do Questionário',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'id' => 'nomquestionario_pesquisar',
                            'placeholder' => 'Informe o nome questionário.'
                        ),
                    )
                ),
                'desobservacao' => array(
                    'textarea',
                    array(
                        'label' => 'Observação',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span',
                            'rows' => 10,
                            'id' => 'desobservacao_pesquisar',
                            'placeholder' => 'Observações do questionário.'
                        ),
                    )
                ),
                'tipoquestionario' => array(
                    'select',
                    array(
                        'label' => 'Tipo',
                        'multiOptions' => $arrSituacao,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'tipoquestionario_pesquisar',
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
