<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Projeto_Form_Configurar extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbPessoa = new Default_Model_Mapper_Pessoa();
        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-configurar",
                "elements" => array(
                    'idprojeto' => array('hidden', array()),
                    'nomparteinteressada' => array(
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
                    'nomfuncao' => array(
                        'text',
                        array(
                            'label' => 'Posição na Organização',
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
                    'destelefone' => array(
                        'text',
                        array(
                            'label' => 'Telefone',
                            'required' => false,
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
                    'domnivelinfluencia' => array(
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
                )
            ));

        $this->getElement('nomparteinteressada')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

    }

}

