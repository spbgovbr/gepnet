<?php

class Projeto_Form_DiarioBordoPesquisar extends App_Form_FormAbstract
{

    public function init()
    {

        $arrReferencias = array(
            '' => 'Todas',
            'Observação' => 'Observação',
            'Ponto de Atenção' => 'Ponto de Atenção',
            'Reunião' => 'Reunião'
        );
        $arrSemaforo = array(
            '' => 'Todas',
            '1' => 'Vermelho',
            '2' => 'Amarelo',
            '3' => 'Verde'
        );

        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-diario-pesquisar',
            'elements' => array(
                'iddiariobordo' => array(
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
                'domreferencia' => array(
                    'select',
                    array(
                        'label' => 'Referência',
                        'multiOptions' => $arrReferencias,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '20',
                            'data-rule-maxlength' => 20,
                            'id' => 'domreferencia_pesquisa',
                        ),
                    )
                ),
                'datdiariobordo' => array(
                    'text',
                    array(
                        'label' => 'Período',
                        //'readonly' => 'readonly',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'datdiariobordoinicio',
                            //evita conflito do datepicker em campos com mesmo id em forms diferentes
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
                        ),
                    )
                ),
                'datdiariobordofim' => array(
                    'text',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'datdiariobordofim',
                            //evita conflito do datepicker em campos com mesmo id em forms diferentes
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
                        ),
                    )
                ),
                'domsemafaro' => array(
                    'select',
                    array(
                        'label' => 'Status',
                        'multiOptions' => $arrSemaforo,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'data-rule-hora' => true,
                            'id' => 'domsemafaro_pesquisa',
                        ),
                    )
                ),
                'desdiariobordo' => array(
                    'textarea',
                    array(
                        'label' => 'Descrição',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span10',
                            'maxlength' => '100',
                            'data-rule-maxlength' => 100,
                            'rows' => 8,
                            'placeholder' => 'Descrição do Diário',
                            'desdiariobordo' => 'domsemafaro_pesquisa',
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
