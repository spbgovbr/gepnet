<?php

class Relatorio_Form_AceitePesquisar extends App_Form_FormAbstract
{

    public function init()
    {

        $gerenciaMapper = new Projeto_Model_Mapper_Gerencia();
        $projeto = $gerenciaMapper->fetchPairsProjeto();

        $escritorioMapper = new Default_Model_Mapper_Escritorio();
        $escritorio = $escritorioMapper->fetchPairs();

        $naturezaMapper = new Default_Model_Mapper_Natureza();
        $natureza = $naturezaMapper->fetchPairs();

        $arrEntrega = array('' => 'Todas');

        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-aceite-pesquisar',
            'elements' => array(
                'idaceite' => array(
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
                        'required' => false,
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
                'identrega' => array(
                    'select',
                    array(
                        'label' => 'Entrega',
                        'required' => false,
                        'multiOptions' => array('' => 'Selecione'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 9999))),
                        'attribs' => array(
                            'class' => 'span3',
                            //'maxlength' => '20',
                            //'data-rule-maxlength' => 20,
                            'id' => 'identrega_pesquisar',
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
                'flagaceito' => array(
                    'select',
                    array(
                        'label' => 'Aceito?',
                        'multiOptions' => array('' => 'Selecione', 'S' => 'Sim', 'N' => 'Não'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'flagaceito_pesquisar',
                            'style' => 'width:265px',
                        ),
                    )
                ),
                'dataceitacao' => array(
                    'text',
                    array(
                        'label' => 'Data Aceitação',
                        'maxlength' => 10,
                        //'readonly' => 'readonly',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'dataceitacaoinicio',
                            //evita conflito do datepicker em campos com mesmo id em forms diferentes
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
                        ),
                    )
                ),
                'dataceitacaofim' => array(
                    'text',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'maxlength' => 10,
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'dataceitacaofim',
                            //evita conflito do datepicker em campos com mesmo id em forms diferentes
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
                        ),
                    )
                ),
                'noatividade' => array(
                    'text',
                    array(
                        'label' => 'Nome Atividade',
                        'required' => false,
                        'maxlength' => '50',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '50',
                            'data-rule-maxlength' => 50,
                            'placeholder' => 'Informe o nome da atividade',
                            'id' => 'noreisco_pesquisar',
                            'style' => 'width:249px',
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



