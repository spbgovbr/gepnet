<?php

class Projeto_Form_Gantt extends App_Form_FormAbstract
{

    public function init()
    {
        $arrTipoExibicao = array(
            '1' => 'Anos/Meses',
            '2' => 'Anos/Meses/Semanas',
            '3' => 'Anos/Meses/Dias',
            '4' => 'Anos/Meses/Semanas/Dias',
        );
        $arrFormatoGantt = array(
            'png' => 'IMAGE/PNG',
            'gif' => 'IMAGE/GIF',
            'jpg' => 'IMAGE/JPEG',
        );
        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-gantt',
            'elements' => array(
                'idprojeto' => array('hidden', array()),
                'idgrupo_cons' => array('hidden', array()),
                'identrega_cons' => array('hidden', array()),
                'idatividadecronograma_cons' => array('hidden', array()),
                'idatividademarco_cons' => array('hidden', array()),
                'tipoexibicao' => array(
                    'select',
                    array(
                        'label' => 'Escala Temporal',
                        'multiOptions' => $arrTipoExibicao,
                        'value' => 3,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '20',
                            'data-rule-maxlength' => 20,
                        ),
                    )
                ),
                'idgrupo' => array(
                    'select',
                    array(
                        'label' => 'Grupo',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '20',
                            'data-rule-maxlength' => 255,
                        ),
                    )
                ),
                'identrega' => array(
                    'select',
                    array(
                        'label' => 'Entrega',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '20',
                            'data-rule-maxlength' => 255,
                        ),
                    )
                ),
                'idatividadecronograma' => array(
                    'select',
                    array(
                        'label' => 'Atividade',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '20',
                            'data-rule-maxlength' => 255,
                        ),
                    )
                ),
                'idatividademarco' => array(
                    'select',
                    array(
                        'label' => 'Marco',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '20',
                            'data-rule-maxlength' => 255,
                            'style' => 'display: none;',
                        ),
                    )
                ),
                'tipoformato' => array(
                    'select',
                    array(
                        'label' => 'Formato:',
                        'required' => false,
                        'multiOptions' => $arrFormatoGantt,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 40))),
                        'attribs' => array(
                            'class' => 'select2',
                            'style' => 'width: 120px;font-size: 11px;',
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
                            'type' => 'submit',
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
                            'type' => 'button',
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
