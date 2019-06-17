<?php

class Pesquisa_Form_PerguntaPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $arrTipoResposta = array(
            '' => 'Selecione',
            Pesquisa_Model_Frase::UMA_ESCOLHA => 'Uma-escolha',
            Pesquisa_Model_Frase::MULTIPLA_ESCOLHA => 'Multipla-escolha',
            Pesquisa_Model_Frase::DESCRITIVO => 'Descritivo (em várias linhas)',
            Pesquisa_Model_Frase::TEXTO => 'Texto (em uma linha)',
            Pesquisa_Model_Frase::NUMERO => 'Número',
            Pesquisa_Model_Frase::DATA => 'Data',
            Pesquisa_Model_Frase::UF => 'UF',
        );
        $arrSituacao = array(
            '' => 'Selecione',
            Pesquisa_Model_Frase::ATIVO => 'Ativo',
            Pesquisa_Model_Frase::INATIVO => 'Inativo',
        );


        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-pergunta-pesquisar',
            'elements' => array(
                'idpergunta' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'desfrase' => array(
                    'text',
                    array(
                        'label' => 'Pergunta',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'id' => 'desfrase_pesquisar',
                            'placeholder' => 'Informe a pergunta.'
                        ),
                    )
                ),
                'domtipofrase' => array(
                    'select',
                    array(
                        'label' => 'Tipo de Resposta da Pergunta',
                        'multiOptions' => $arrTipoResposta,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'domtipofrase_pesquisar',
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
