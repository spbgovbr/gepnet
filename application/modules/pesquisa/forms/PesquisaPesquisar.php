<?php

class Pesquisa_Form_PesquisaPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $arrTipo = array(
            '' => 'Selecione',
            Pesquisa_Model_Questionario::PUBLICADO_COM_SENHA => 'Publicado com senha',
            Pesquisa_Model_Questionario::PUBLICADO_SEM_SENHA => 'Publicado sem senha',
        );

        $arrSituacao = array(
            '' => 'Selecione',
            Pesquisa_Model_Pesquisa::PUBLICADO => 'Publicada',
            Pesquisa_Model_Pesquisa::ENCERRADO => 'Encerrada',
        );


        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-pesquisa-pesquisar',
            'elements' => array(
                'idpesquisa' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'nomquestionario' => array(
                    'text',
                    array(
                        'label' => 'Nome da pesquisa',
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
                'tipoquestionario' => array(
                    'select',
                    array(
                        'label' => 'Tipo',
                        'multiOptions' => $arrTipo,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'tipoquestionario_pesquisar',
                        ),
                    )
                ),
                'situacao' => array(
                    'select',
                    array(
                        'label' => 'Situação',
                        'multiOptions' => $arrSituacao,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'situacao_pesquisar',
                        ),
                    )
                ),
                'datcadastroinicio' => array(
                    'text',
                    array(
                        'label' => 'Data cadastro',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'datcadastroinicio',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
                        ),
                    )
                ),
                'datcadastrofim' => array(
                    'text',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'datcadastrofim',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
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
