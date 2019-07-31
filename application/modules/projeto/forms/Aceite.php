<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated at "" @ 16-05-2013 17:21
 */
class Projeto_Form_Aceite extends App_Form_FormAbstract
{

    public function init()
    {

        $serviceLogin = new Default_Service_Login();
        $usuario = $serviceLogin->retornaUsuarioLogado();

        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-aceite",
                "elements" => array(
                    'idaceite' => array('hidden', array()),
                    'idprojeto' => array('hidden', array()),
                    'nomresponsavel' => array('hidden', array()),
                    'idcadastrador' => array('hidden', array('value' => $usuario->idpessoa)),
                    'identrega' => array(
                        'select',
                        array(
                            'label' => 'Entrega Associada',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'multioptions' => array(),
                            'validators' => array(),
                            'attribs' => array('class' => 'span5', 'data-rule-required' => true),
                        )
                    ),
                    'idmarco' => array(
                        'select',
                        array(
                            'label' => 'Marco da Entrega',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'multioptions' => array(),
                            'validators' => array(),
                            'attribs' => array('class' => 'span5', 'data-rule-required' => false),
                        )
                    ),
                    'desprodutoservico' => array(
                        'textarea',
                        array(
                            'label' => 'Produto ou Serviço Entregue',
                            'required' => true,
                            'maxlength' => '4000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'class' => 'span8',
                                'rows' => '3',
                                'data-rule-required' => true,
                                'maxlength' => '4000'

                            ),
                        )
                    ),
                    'desparecerfinal' => array(
                        'textarea',
                        array(
                            'label' => 'Parecer Final',
                            'required' => true,
                            'maxlength' => '4000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'class' => 'span8',
                                'rows' => '3',
                                'data-rule-required' => true,
                                'maxlength' => '4000'
                            ),
                        )
                    ),
                    'datcadastro' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags')
                        )
                    ),
                    'flaaceite' => array(
                        'select',
                        array(
                            'label' => 'Aceita a Entrega?',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'multioptions' => array('' => 'Selecione', 'S' => 'Sim', 'N' => 'Não'),
                            'validators' => array(),
                            'attribs' => array('style' => 'width: 100px;', 'data-rule-required' => true),
                        )
                    ),
                    'back' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Voltar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'backbutton',
                                'type' => 'button',
                            ),
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


                )
            ));

        $this->getElement('back')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }


}

