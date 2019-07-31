<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Evento_Form_Evento extends App_Form_FormAbstract
{

    public function init()
    {
        $service = new Evento_Service_Grandeseventos();
        $serviceLogin = new Default_Service_Login();
        $usuario = $serviceLogin->retornaUsuarioLogado();

        $this
            ->setOptions(array(
                "name" => "form-evento",
                "id" => "form-evento",
                "method" => "post",
                "elements" => array(
                    'idevento' => array('hidden', array()),
                    'idresponsavel' => array('hidden', array()),
                    'idcadastrador' => array('hidden', array('value' => $usuario->idpessoa)),
                    'nomevento' => array(
                        'text',
                        array(
                            'label' => 'Nome do Evento',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span8',
                                'data-rule-required' => true
                            ),
                        )
                    ),
                    'desevento' => array(
                        'textarea',
                        array(
                            'label' => 'Descrição do Evento',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 1000))),
                            'attribs' => array(
                                'class' => 'span10',
                                'rows' => 5,
                                'data-rule-required' => false
                            ),
                        )
                    ),
                    'desobs' => array(
                        'textarea',
                        array(
                            'label' => 'Observações',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 1000))),
                            'attribs' => array(
                                'class' => 'span10',
                                'rows' => 5,
                                'data-rule-required' => false
                            ),
                        )
                    ),
                    'datinicio' => array(
                        'text',
                        array(
                            'label' => 'Início',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker data',
                                'data-rule-required' => true,
                                'data-rule-dateITA' => true,
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10
                            ),
                        )
                    ),
                    'datfim' => array(
                        'text',
                        array(
                            'label' => 'Fim',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker data',
                                'data-rule-required' => true,
                                'data-rule-dateITA' => true,
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10
                            ),
                        )
                    ),
                    'uf' => array(
                        'select',
                        array(
                            'label' => 'UF',
                            'required' => true,
                            'multioptions' => $service->getUfs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 2))),
                            'attribs' => array(
                                'style' => 'width: 100px',
                                'data-rule-required' => true
                            ),
                        )
                    ),
                    'nomresponsavel' => array(
                        'text',
                        array(
                            'label' => 'Responsável',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array('data-rule-required' => true),
                        )
                    ),
                    'submit' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Salvar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'submitbutton',
                                'type' => 'submit',
                            ),
                        )
                    ),
                    'voltar' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Voltar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'voltar',
                                'type' => 'button',
                            ),
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
                    'pesquisar' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Pesquisar',
                            'icon' => 'filter',
                            'whiteIcon' => false,
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'submitbutton',
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


        $this->getElement('nomresponsavel')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('voltar')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('pesquisar')
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
        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }


}

