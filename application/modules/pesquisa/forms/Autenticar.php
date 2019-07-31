<?php

class Pesquisa_Form_Autenticar extends App_Form_FormAbstract
{

    public function init()
    {


        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-autenticar',
            'elements' => array(
                'username' => array(
                    'text',
                    array(
                        'label' => 'Usuário do email',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 40))),
                        'description' => 'Ex: santanna.hssj',
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '40',
                            'data-rule-maxlength' => 40,
                            'data-rule-required' => true,
                            'placeholder' => 'Informe seu usuário'

                        ),
                    )
                ),
                'password' => array(
                    'password',
                    array(
                        'label' => 'Senha do email',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'data-rule-required' => true,
                            'placeholder' => 'Informe a senha'
                        ),
                    )
                ),
                'enviar' => array(
                    'button',
                    array(
                        'ignore' => true,
                        'label' => 'Enviar',
                        //'icon' => 'filter',
                        'whiteIcon' => false,
                        'escape' => false,
                        'attribs' => array(
                            'id' => 'enviar',
                            'type' => 'submit',
                            'class' => 'btn'
                        ),
                    )
                ),
            )
        ));
        $this->getElement('enviar')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}
