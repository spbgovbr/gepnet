<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Projeto_Form_Parteinteressada extends App_Form_FormAbstract
{

    public function init()
    {
        $service = new Projeto_Service_ParteInteressada();
        $mapperTbPessoa = new Default_Model_Mapper_Pessoa();
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-parte",
                "elements" => array(
                    'idprojeto' => array('hidden', array()),
                    'nomparteinteressada' => array(
                        'text',
                        array(
                            'label' => 'Nome',
                            'required' => true,
                            'maxlength' => 100,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'data-rule-required' => true,
                                'maxlength' => '100',
                                'data-rule-maxlength' => 100,
                                'readonly' => true,
                                'class' => 'span2',
                                'style' => 'width:190px'
                            ),
                        )
                    ),
                    'idparteinteressada' => array(
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
                    'idparteinteressadafuncao' => array(
                        'select',
                        array(
                            'label' => 'Função no projeto',
                            'required' => true,
                            'multiOptions' => $service->getFuncaoProjeto(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'tppermissao' => array(
                        'select',
                        array(
                            'label' => 'Permissão',
                            'required' => true,
                            'multiOptions' => $service->getPermicoes(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'destelefone' => array(
                        'text',
                        array(
                            'label' => 'Telefone',
                            'required' => false,
                            'maxlength' => '50',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'data-rule-required' => false,
                                'class' => 'mask-tel',
                                'maxlength' => '50',
                                'data-rule-maxlength' => 50,
                            ),
                        )
                    ),
                    'desemail' => array(
                        'text',
                        array(
                            'label' => 'Email',
                            'required' => false,
                            'maxlength' => 50,
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
                    'observacao' => array(
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
                                'maxlength' => 200,
                                'data-rule-maxlength' => 200,
                            ),
                        )
                    ),
                    'domnivelinfluencia' => array(
                        'select',
                        array(
                            'label' => 'Nível de Influência',
                            'required' => false,
                            'maxlength' => '10',
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
                                'class' => 'span2',
                                'data-rule-maxlength' => 10,
                            ),
                        )
                    ),
                    'idcadastrador' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => false,
                            'multiOptions' => $mapperTbPessoa->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'datcadastro' => array(
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
                    'adicionar' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Adicionar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'btn-adicionar',
                                'type' => 'button',
                                'class' => 'btn span1'
                            ),
                        )
                    ),
                )
            ));

        $this->getElement('nomparteinteressada')
            //->removeDecorator('label')
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

