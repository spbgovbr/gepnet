<?php

class Relatorio_Form_DiariobordoPesquisar extends App_Form_FormAbstract
{

    public function init()
    {

        $escritorioMapper = new Default_Model_Mapper_Escritorio();
        $escritorio = $escritorioMapper->fetchPairs();

        $naturezaMapper = new Default_Model_Mapper_Natureza();
        $natureza = $naturezaMapper->fetchPairs();

        $gerenciaMapper = new Projeto_Model_Mapper_Gerencia();
        $projeto = $gerenciaMapper->fetchPairsProjeto();

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
                'idescritorio' => array(
                    'select',
                    array(
                        'label' => 'Escritório de Projeto (EGPS)',
                        'required' => false,
                        'multiOptions' => array('' => 'Selecione') + $escritorio,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'idescritorio_pesquisar',
                            'style' => 'width:265px',
                        ),
                    )
                ),
                'idprojeto' => array(
                    'select',
                    array(
                        'label' => 'Projeto',
                        'multiOptions' => array('' => 'Selecione') + $projeto,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '20',
                            'data-rule-maxlength' => 20,
                            'id' => 'idprojeto_pesquisa',
                            'style' => 'width:265px',
                        ),
                    )
                ),
                'idnatureza' => array(
                    'select',
                    array(
                        'label' => 'Natureza',
                        'required' => false,
                        'multiOptions' => array('' => 'Selecione') + $natureza,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'idnatureza_pesquisar',
                            'style' => 'width:265px',
                        ),
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
                            'style' => 'width:265px',
                        ),
                    )
                ),
                'datdiariobordo' => array(
                    'text',
                    array(
                        'label' => 'Período',
                        'maxlength' => 10,
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
                        'maxlength' => 10,
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
                            'style' => 'width:265px',
                        ),
                    )
                ),
                'desdiariobordo' => array(
                    'text',
                    array(
                        'label' => 'Descrição',
                        'maxlength' => 100,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span10',
                            'maxlength' => '100',
                            'data-rule-maxlength' => 100,
                            'placeholder' => 'Informe a descrição do Diário de Bordo',
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

