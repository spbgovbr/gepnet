<?php

class Pesquisa_Form_HistoricoPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $arrSituacao = array(
            '' => 'Selecione',
            Pesquisa_Model_Pesquisa::PUBLICADO => 'Publicada',
            Pesquisa_Model_Pesquisa::ENCERRADO => 'Encerrada',
        );


        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-historico-pesquisar',
            'elements' => array(
                'nomquestionario' => array(
                    'text',
                    array(
                        'label' => 'Nome da pesquisa',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'placeholder' => 'Informe o nome da pesquisa'
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
                        ),
                    )
                ),
                'nome_publicou' => array(
                    'text',
                    array(
                        'label' => 'Publicado por',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'placeholder' => 'Informe quem publicou'
                        ),
                    )
                ),
                'nome_encerrou' => array(
                    'text',
                    array(
                        'label' => 'Encerrado por',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'placeholder' => 'Informe quem encerrou'
                        ),
                    )
                ),
                'datpublicacao' => array(
                    'text',
                    array(
                        'label' => 'Data publicação',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'datpublicacao',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
                        ),
                    )
                ),
                'datencerramento' => array(
                    'text',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'label' => 'Data encerramento',
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'datencerramento',
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
