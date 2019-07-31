<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Agenda_Form_PessoaAgenda extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbAgenda = new Agenda_Model_Mapper_Agenda();
        $mapperTbPessoa = new Default_Model_Mapper_Pessoa();
        $this->setOptions(array(
            "method" => "post",
            "id" => "form-participante",
            "elements" => array(
                'idagenda' => array('hidden'),
                /*'idagenda' => array('select', array(
                    'label'        => '',
                    'required'     => true,
                    'multiOptions' => $mapperTbAgenda->fetchPairs(),
                    'filters'      => array('StringTrim','StripTags'),
                    'validators'   => array(),
                    'attribs'      => array(),
                )),*/
                'nomparticipante' => array(
                    'text',
                    array(
                        'label' => 'Nome',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'data-rule-required' => true,
                            'maxlength' => '100',
                            'data-rule-maxlength' => 100,
                            'readonly' => 'readonly',
                        ),
                    )
                ),
                'idpessoa' => array(
                    'hidden',
                    array(
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'readonly' => true,
                            'data-rule-required' => false,
                        ),
                    )
                ),
                /*'idpessoa' => array('select', array(
                    'label'        => '',
                    'required'     => true,
                    'multiOptions' => $mapperTbPessoa->fetchPairs(),
                    'filters'      => array('StringTrim','StripTags'),
                    'validators'   => array(),
                    'attribs'      => array(),
                )),*/
                'pessoabutton' => array(
                    'button',
                    array(
                        'label' => '',
                        'ignore' => true,
                        'icon' => 'user',
                        'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                        //'label' => 'Limpar',
                        'escape' => true,
                        'attribs' => array(
                            'class' => 'pessoa-button',
                            'type' => 'button',
                        )
                    )
                ),
                'adicionar' => array(
                    'button',
                    array(
                        'ignore' => true,
                        'label' => 'Adicionar',
                        'escape' => false,
                        'attribs' => array(
                            'id' => 'btn-adicionar',
                            'type' => 'button',
                            'class' => 'btn'
                        ),
                    )
                ),

            )
        ));

        $this->getElement('nomparticipante')
//            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('adicionar')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }


}

