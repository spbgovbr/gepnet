<?php

class Relatorio_Form_RiscoPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $escritorioMapper =  new Default_Model_Mapper_Escritorio();
        $escritorio =  $escritorioMapper->fetchPairs();
        
        $gerenciaMapper =  new Projeto_Model_Mapper_Gerencia();
        $projeto = $gerenciaMapper->fetchPairsProjeto();
        
        $naturezaMapper =  new Default_Model_Mapper_Natureza();
        $natureza = $naturezaMapper->fetchPairs();
        
        $this->setOptions(array(
            'method' => 'post',
            'id'     => 'form-risco-pesquisar',
            'elements'=>array(
                        'idrisco' => array('hidden', array(                                
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('Digits'),
                            )),
                        'desrisco' => array('text', array(
                                'label' => 'Risco',
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'span',
                                    'maxlength' => '255',
                                    'data-rule-maxlength' => 255,
                                ),
                            )),
                        'idescritorio' => array('select', array(
                                'label' => 'Escritório de Projeto (EGPS)',
                                'multiOptions' => array(''=>'Todos') + $escritorio,
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'span',
                                ),
                            )),
                        'idprojeto' => array('select', array(
                                'label' => 'Projeto',
                                'multiOptions' => array(''=>'Todos') + $projeto,
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'span',
                                ),
                            )), 
                        'idnatureza' => array('select', array(
                                'label' => 'Natureza',
                                'multiOptions' => array(''=>'Todos') + $natureza,
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'span',
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
                                    'type' => 'button',
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
