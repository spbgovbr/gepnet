<?php

class Projeto_Form_AtaPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-ata-pesquisar',
            'elements' => array(
                'idata' => array(
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
                'desassunto' => array(
                    'text',
                    array(
                        'label' => 'Assunto',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '100',
                            'data-rule-minlength' => 5,
                            'data-rule-maxlength' => 100,
                            'id' => 'desassunto_pesquisar',
                            'placeholder' => 'Informe o assunto do projeto',
                        ),
                    )
                ),
                'datata' => array(
                    'text',
                    array(
                        'label' => 'Data Ata',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span3 mask-date',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'id' => 'datatapesquisar',
                            //evita conflito do datepicker em campos com mesmo id em forms diferentes
                            'placeholder' => 'DD/MM/AAAA',
                        ),
                    )
                ),
                'hrreuniao' => array(
                    'text',
                    array(
                        'label' => 'Hora da Reunião',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 8))),
                        'attribs' => array(
                            'class' => 'span3 mask-hora',
                            'maxlength' => '8',
                            'data-rule-maxlength' => 8,
                            'data-rule-minlength' => 8,
                            'data-rule-hora' => true,
                            'placeholder' => 'HH24:MM:SS',
                            'id' => 'hrreuniao_pesquisar',
                        ),
                    )
                ),
                'deslocal' => array(
                    'text',
                    array(
                        'label' => 'Local',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '100',
                            'data-rule-maxlength' => 100,
                            'placeholder' => 'Local da reunião',
                            'id' => 'deslocal_pesquisar',
                        ),
                    )
                ),
                'desparticipante' => array(
                    'textarea',
                    array(
                        'label' => 'Participantes',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'rows' => '6',
                            'placeholder' => 'Favor informar: Nome Completo, Cargo, Posição na Organização, Telefone e e-mail.',
                            'id' => 'desparticipante_pesquisar',
                        ),
                    )
                ),
                'despontodiscutido' => array(
                    'textarea',
                    array(
                        'label' => 'Ponto discutido',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'rows' => '6',
                            'placeholder' => 'Informe dos pontos discutidos.',
                            'id' => 'despontodiscutido_pesquisar',
                        ),
                    )
                ),
                'desdecisao' => array(
                    'textarea',
                    array(
                        'label' => 'Decisão',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'rows' => '6',
                            'placeholder' => 'Informe qual foi decisão da reunião.',
                            'id' => 'desdecisao_pesquisar',
                        ),
                    )
                ),
                'despontoatencao' => array(
                    'textarea',
                    array(
                        'label' => 'Ponto de atenção',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'rows' => '6',
                            'placeholder' => 'Informe sobre os pontos de atenção.',
                            'id' => 'despontoatencao_pesquisar',
                        ),
                    )
                ),
                'desproximopasso' => array(
                    'textarea',
                    array(
                        'label' => 'Próximo passo',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span3',
                            'rows' => '6',
                            'placeholder' => 'Favor informar: Data, Nome do Responsável e descrever o próximo passo.',
                            'id' => 'desproximopasso_pesquisar',
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
