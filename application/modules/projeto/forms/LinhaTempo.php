<?php

class Projeto_Form_LinhaTempo extends App_Form_FormAbstract
{

    public function init()
    {
        $service = new Projeto_Service_ParteInteressada();
        $linhaTempo = new Projeto_Service_LinhaTempo();
        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-linhatempo',
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
                'nompessoa' => array(
                    'text',
                    array(
                        'label' => 'Usuário',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'nompessoa_pesquisar',
                        ),
                    )
                ),
                'dsfuncaoprojeto' => array(
                    'select',
                    array(
                        'label' => 'Função no Projeto',
                        'multiOptions' => array('' => 'Selecione') + $service->getFuncaoProjeto(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'dsfuncaoprojeto_pesquisar',
                        ),
                    )
                ),
                'descricao' => array(
                    'select',
                    array(
                        'label' => 'Funcionalidade',
                        'multiOptions' => array('' => 'Selecione') + $linhaTempo->getDescricaoRecurso(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                            'id' => 'descricao_pesquisar',
                        ),
                    )
                ),
                'dtacaoinicial' => array(
                    'text',
                    array(
                        'label' => 'Período Inicial',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span3 mask-date datepicker success',
                            'id' => 'dtacaoinicial_pesquisar',
                            //evita conflito preechimento datepicker com o form do cadastro
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA'
                        ),
                    )
                ),
                'dtacaofinal' => array(
                    'text',
                    array(
                        'label' => 'Período Final',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span3 mask-date datepicker success',
                            'id' => 'dtacaofinal_pesquisar',
                            //evita conflito preechimento datepicker com o form do cadastro
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