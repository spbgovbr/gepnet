<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Projeto_Form_Mudanca extends App_Form_FormAbstract
{

    public function init()
    {

        $service = new Projeto_Service_Mudanca();
        $serviceLogin = new Default_Service_Login();
        $usuario = $serviceLogin->retornaUsuarioLogado();

        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-mudanca",
                "name" => "form-mudanca",
                "elements" => array(
                    'idmudanca' => array('hidden', array()),
                    'idcadastrador' => array('hidden', array('value' => $usuario->idpessoa)),
                    'idprojeto' => array('hidden', array()),
                    'nomsolicitante' => array(
                        'text',
                        array(
                            'label' => 'Solicitante',
                            'required' => true,
                            'maxlength' => '100',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array('class' => 'span5', 'data-rule-required' => true),
                        )
                    ),
                    'datsolicitacao' => array(
                        'text',
                        array(
                            'label' => 'Data da Solicitação',
                            'required' => true,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker data',
                                'data-rule-dateITA' => true,
                                'data-rule-required' => true,
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                            ),
                        )
                    ),
                    'datdecisao' => array(
                        'text',
                        array(
                            'label' => 'Data da Decisão',
                            'required' => false,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker data',
                                'data-rule-dateITA' => true,
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10
                            ),
                        )
                    ),
                    'flaaprovada' => array(
                        'select',
                        array(
                            'label' => 'Aprovada?',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'multioptions' => array('' => 'Selecione', 'S' => 'Sim', 'N' => 'Não'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'desmudanca' => array(
                        'textarea',
                        array(
                            'label' => 'Descrição da Mudança',
                            'required' => true,
                            'maxlength' => '4000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'class' => 'span5',
                                'rows' => '3',
                                'data-rule-required' => true,
                                'data-rule-maxlength' => 4000,
                            ),
                        )
                    ),
                    'desjustificativa' => array(
                        'textarea',
                        array(
                            'label' => 'Justificativa',
                            'required' => true,
                            'maxlength' => '4000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'class' => 'span5',
                                'rows' => '3',
                                'data-rule-required' => true,
                                'data-rule-maxlength' => 4000,
                            ),
                        )
                    ),
                    'despareceregp' => array(
                        'textarea',
                        array(
                            'label' => 'Parecer do CIGE',
                            'required' => false,
                            'maxlength' => '4000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(
                                'NotEmpty',
                                array('StringLength', false, array(0, 4000))
                            ),
                            'attribs' => array(
                                'class' => 'span5',
                                'rows' => '3',
                                'data-rule-maxlength' => 4000,
                            ),
                        )
                    ),
                    'desaprovadores' => array(
                        'textarea',
                        array(
                            'label' => '',
                            'required' => false,
                            'maxlength' => '4000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'class' => 'span5',
                                'rows' => '3'
                            ),
                        )
                    ),
                    'despareceraprovadores' => array(
                        'textarea',
                        array(
                            'label' => 'Parecer dos Aprovadores',
                            'required' => false,
                            'maxlength' => '4000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'class' => 'span5',
                                'rows' => '3',
                            )
                        ,
                        )
                    ),
                    'idtipomudanca' => array(
                        'select',
                        array(
                            'label' => 'Tipo da Mudança',
                            'required' => true,
                            'multiOptions' => $service->fetchPairsTipoMudanca(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array('class' => 'span2'),
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
                            )
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
                )
            ));

        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('voltar')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

    }


}

