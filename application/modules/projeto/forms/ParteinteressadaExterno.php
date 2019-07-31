<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Projeto_Form_ParteinteressadaExterno extends App_Form_FormAbstract
{

    public function init()
    {
        $service = new Projeto_Service_ParteInteressada();
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-parte-externo",
                "elements" => array(
                    'idprojetoexterno' => array('hidden', array()),
                    'nomparteinteressadaexterno' => array(
                        'text',
                        array(
                            'label' => 'Nome',
                            'maxlength' => 100,
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'data-rule-required' => true,
                                'maxlength' => '100',
                                'data-rule-maxlength' => 100,
                                'class' => 'span3',
                                'style' => 'width:190px',
                            ),
                        )
                    ),
                    'idparteinteressadaexterno' => array(
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
                    'idpessoainterna' => array(
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
                    'idparteinteressadafuncaoexterno' => array(
                        'select',
                        array(
                            'label' => 'Função no projeto',
                            'required' => true,
                            'multiOptions' => $service->getFuncaoProjeto(false),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'dessituacaoexterno' => array(
                        'text',
                        array(
                            'label' => 'Situação',
                            'maxlength' => 50,
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'data-rule-required' => false,
                                'maxlength' => '50',
                                'data-rule-maxlength' => 50,
                            ),
                        )
                    ),
                    'destelefoneexterno' => array(
                        'text',
                        array(
                            'label' => 'Telefone',
                            'maxlength' => 50,
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'data-rule-required' => true,
                                'class' => 'mask-tel span2',
                                'maxlength' => '50',
                                'data-rule-maxlength' => 50,
                            ),
                        )
                    ),
                    'desemailexterno' => array(
                        'text',
                        array(
                            'label' => 'Email',
                            'maxlength' => 50,
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(
                                'EmailAddress',
                                array('StringLength', false, array(0, 50))
                            ),
                            'attribs' => array(
                                'class' => 'span3',
                                'data-rule-email' => true,
                                'data-rule-required' => true,
                                'maxlength' => '50',
                                'data-rule-maxlength' => 50,
                            ),
                        )
                    ),
                    'observacaoexterno' => array(
                        'textarea',
                        array(
                            'label' => 'Observação',
                            'maxlength' => 200,
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(
                                array('StringLength', false, array(0, 200))
                            ),
                            'attribs' => array(
                                'class' => 'span3',
                                'cols' => '60',
                                'rows' => '6',
                                'data-rule-maxlength' => 200,
                            ),
                        )
                    ),
                    'domnivelinfluenciaexterno' => array(
                        'select',
                        array(
                            'label' => 'Nível de Influência',
                            'required' => false,
                            'multiOptions' => array(
                                '' => 'Selecione',
                                'Baixo' => 'Baixo',
                                'Médio' => 'Médio',
                                'Alto' => 'Alto'
                            ),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'data-rule-required' => false,
                                'class' => 'span2',
                                'maxlength' => '10',
                                'data-rule-maxlength' => 10,
                            ),
                        )
                    ),
                    'idcadastradorexterno' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => false,
                            'multiOptions' => array(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'datcadastroexterno' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
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
                            //'label' => 'Limpar',
                            'escape' => true,
                            'attribs' => array(
                                'class' => 'pessoa-button',
                                'type' => 'button',
                            )
                        )
                    ),
                    'adicionarexterno' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Adicionar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'btn-adicionar-externo',
                                'type' => 'button',
                                'class' => 'btn'
                            ),
                        )
                    ),
                )
            ));

        $this->getElement('nomparteinteressadaexterno')
            //->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('adicionarexterno')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}

