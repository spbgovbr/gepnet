<?php

class Projeto_Form_Gantt extends App_Form_FormAbstract
{

    public function init()
    {
        
        $arrTipoExibicao = array('1'=>'Anos/Meses', 
                                 '2'=>'Anos/Meses/Semanas', 
                                 '3'=>'Anos/Meses/Dias',
                                 '4'=>'Anos/Meses/Semanas/Dias',
        );
        
        $this->setOptions(array(
            'method' => 'post',
            'id'     => 'form-gantt',
            'elements'=>array(
                        'idprojeto' => array('hidden', array(
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('Digits'),
                            )),
                        'tipoexibicao' => array('select', array(
                                'label' => 'Referência',
                                'multiOptions' => $arrTipoExibicao,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                                'attribs' => array(
                                    'class' => 'span3',
                                    'maxlength' => '20',
                                    'data-rule-maxlength' => 20,
                                ),
                            )),                        
                        'btnpesquisar' => array('button', array(
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
                                )),
                        'reset' => array('button', array(
                                'ignore' => true,
                                'icon' => 'th',
                                'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                                'label' => 'Limpar',
                                'escape' => false,
                                'attribs' => array(
                                    'id' => 'resetbutton',
                                    'type' => 'reset',
                                ),
                            )),
                        'close' => array('button', array(
                                'ignore' => true,
                                'icon' => 'arrow-right',
                                'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                                'label' => 'Fechar',
                                'escape' => false,
                                'attribs' => array(
                                    'id' => 'closebutton',
                                    'type' => 'button',
                                ),
                            )),
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
