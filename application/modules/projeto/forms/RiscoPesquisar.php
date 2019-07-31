<?php

class Projeto_Form_RiscoPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $origemRisco = new Projeto_Model_Mapper_Origemrisco();
        $etapa = new Projeto_Model_Mapper_Etapa();
        $tipoRisco = new Projeto_Model_Mapper_Tiporisco();

        $arrTratamento = array(
            '' => 'Selecione',
            '1' => 'Conviver',
            '2' => 'Mitigar (Reduzir efeitos)',
            '3' => 'Neutralizar',
            '4' => 'Potencializar',
            '5' => 'Tranferir',
        );
        $arrProbabilidade = array(
            '' => 'Selecione',
            '1' => 'Alta',
            '2' => 'Media',
            '3' => 'Baixa',
        );
        $arrImpacto = array(
            '' => 'Selecione',
            '1' => 'Alta',
            '2' => 'Media',
            '3' => 'Baixa',
        );

        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-risco-pesquisar',
            'elements' => array(
                'idrisco' => array(
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
                'norisco' => array(
                    'text',
                    array(
                        'label' => 'Título Risco',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '50',
                            'data-rule-maxlength' => 50,
                            'placeholder' => 'Informe o título do risco',
                            'id' => 'noreisco_pesquisar',
                        ),
                    )
                ),
                'datdeteccao' => array(
                    'text',
                    array(
                        'label' => 'Detecção',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span3 mask-date',
                            'id' => 'datteccao-pesquisar',
                            //evita conflito preechimento datepicker com o form do cadastro
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA'
                        ),
                    )
                ),
                'idorigemrisco' => array(
                    'select',
                    array(
                        'label' => 'Origem',
                        'multiOptions' => $origemRisco->fetchPairs(true),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'idorigemrisco_pesquisar',
                        ),
                    )
                ),
                'idetapa' => array(
                    'select',
                    array(
                        'label' => 'Etapa',
                        'multiOptions' => $etapa->fetchPairs(true),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'idetapa_pesquisar',
                        ),
                    )
                ),
                'idtiporisco' => array(
                    'select',
                    array(
                        'label' => 'Tipo',
                        'multiOptions' => $tipoRisco->fetchPairs(true),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'idtiporisco_pesquisar',
                        ),
                    )
                ),
                'domcorprobabilidade' => array(
                    'select',
                    array(
                        'label' => 'Probabilidade',
                        'multiOptions' => $arrProbabilidade,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'domcorprobabilidadepesquisar',
                        ),
                    )
                ),
                'domcorimpacto' => array(
                    'select',
                    array(
                        'label' => 'Impacto',
                        'multiOptions' => $arrImpacto,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'domcorimpactopesquisar',
                        ),
                    )
                ),
                'domcorrisco' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'desrisco' => array(
                    'textarea',
                    array(
                        'label' => 'Descrição',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span10',
                            'rows' => 3,
                            'placeholder' => 'Descrição do risco',
                            'id' => 'desrisco_pesquisar',
                        ),
                    )
                ),
                'descausa' => array(
                    'textarea',
                    array(
                        'label' => 'Causa',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span5',
                            'rows' => 3,
                            'placeholder' => 'Descrição da causa',
                            'id' => 'descausa_pesquisar',
                        ),
                    )
                ),
                'desconsequencia' => array(
                    'textarea',
                    array(
                        'label' => 'Consequência',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span5',
                            'rows' => 3,
                            'placeholder' => 'Descrição da consequência',
                            'id' => 'desconsequencia_pesquisar',
                        ),
                    )
                ),
                'domtratamento' => array(
                    'select',
                    array(
                        'label' => 'Tratamento',
                        'multiOptions' => $arrTratamento,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'domtratamento_pesquisar',
                        ),
                    )
                ),
                'flariscoativo' => array(
                    'select',
                    array(
                        'label' => 'Ativo?',
                        'multiOptions' => array('' => 'Selecione', '1' => 'Sim', '2' => 'Não'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'flariscoativo_pesquisar',
                        ),
                    )
                ),
                'datencerramentorisco' => array(
                    'text',
                    array(
                        'label' => 'Data encerramento',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span3 mask-date',
                            'id' => 'datencerramentoriscopesquisar',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA'
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
