<?php

class Projeto_Form_Validaassinatura extends App_Form_FormAbstract
{

    public function init()
    {

        $this->setOptions(array(
            "method" => "post",
            "id" => 'form-assinaDoc',
            "elements" => array(
                'numcpf' => array(
                    'text',
                    array(
                        'label' => 'CPF do Usuário',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags', 'Digits'),
                        'validators' => array(
                            'NotEmpty',
                            'Cpf',
                            array('StringLength', false, array(0, 11)),
                        ),
                        'attribs' => array(
                            'maxlength' => 14,
                            'size' => 15,
                            'class' => 'span2 mask-cpf cpf',
                            'data-rule-required' => true,
                            'data-rule-maxlength' => 14,
                            'data-rule-minlength' => 11,
                            'data-rule-cpf' => true,
                        ),
                    )
                ),
                'senha' => array(
                    'password',
                    array(
                        'Label' => 'Senha SISEG',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'required' => 'required',
                            'id' => 'senha'
                        ),
                    )
                ),
                'submit' => array(
                    'button',
                    array(
                        'ignore' => true,
                        'label' => 'Assinar',
                        'escape' => false,
                        'attribs' => array(
                            'id' => 'submitbutton',
                            'type' => 'button',
                        ),
                    )
                ),
                'reset' => array(
                    'button',
                    array(
                        'ignore' => true,
                        'label' => 'Limpar',
                        'escape' => false,
                        'attribs' => array(
                            'id' => 'resetbutton',
                            'type' => 'reset',
                        ),
                    )
                )
            )
        ));

        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('reset')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}
