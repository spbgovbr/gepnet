<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Projeto_Form_ParteinteressadaPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $this->setOptions(array(
            "method" => "post",
            "id" => "form-parte-pesquisar",
            "elements" => array(
                'idprojeto' => array('hidden', array()),
                'nomparteinteressadapesquisar' => array(
                    'text',
                    array(
                        'label' => 'Nome',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'maxlength' => '100',
                            'data-rule-maxlength' => 100,
                            'class' => 'span3',
                        ),
                    )
                ),
                'idparteinteressadapesquisar' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'readonly' => true,
                        ),
                    )
                ),
                'nomfuncaopesquisar' => array(
                    'text',
                    array(
                        'label' => 'Função no projeto',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'maxlength' => '50',
                            'data-rule-maxlength' => 50,
                            'class' => 'span3',
                        ),
                    )
                ),
                'dessituacaopesquisar' => array(
                    'text',
                    array(
                        'label' => 'Situação',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'maxlength' => '50',
                            'data-rule-maxlength' => 50,
                        ),
                    )
                ),
                'destelefonepesquisar' => array(
                    'text',
                    array(
                        'label' => 'Telefone',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'class' => 'mask-tel span3',
                            'maxlength' => '50',
                            'data-rule-maxlength' => 50,
                        ),
                    )
                ),
                'desemailpesquisar' => array(
                    'text',
                    array(
                        'label' => 'Email',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(
                            'EmailAddress',
                            array('StringLength', false, array(0, 50))
                        ),
                        'attribs' => array(
                            'class' => 'span3',
                            'data-rule-email' => true,
                            'maxlength' => '50',
                            'data-rule-maxlength' => 50,
                        ),
                    )
                ),
                'domnivelinfluenciapesquisar' => array(
                    'select',
                    array(
                        'label' => 'Nível de Influência',
                        'multiOptions' => array(
                            '' => 'Selecione',
                            'Baixo' => 'Baixo',
                            'Médio' => 'Médio',
                            'Alto' => 'Alto'
                        ),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                        ),
                    )
                ),
                'idcadastradorpesquisar' => array(
                    'select',
                    array(
                        'label' => '',
                        'multiOptions' => array(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(),
                    )
                ),
                'datcadastropesquisar' => array(
                    'text',
                    array(
                        'label' => '',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(),
                    )
                ),
                'pessoabutton' => array(
                    'button',
                    array(
                        'label' => '',
                        'ignore' => true,
                        'icon' => 'user',
                        'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                        'escape' => true,
                        'attribs' => array(
                            'class' => 'pessoa-button',
                            'type' => 'button',
                        )
                    )
                ),
                'btnpesquisar' => array(
                    'button',
                    array(
                        'ignore' => true,
                        'label' => 'Pesquisar',
                        'icon' => 'filter',
                        //'iconPosition'  => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
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

        $this->getElement('nomparteinteressadapesquisar')
            //->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

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

