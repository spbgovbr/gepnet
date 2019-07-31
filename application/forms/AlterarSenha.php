<?php

class Default_Form_AlterarSenha extends App_Form_FormAbstract
{

    public function init()
    {
        $this->setOptions(array(
            "method" => "post",
            "elements" => array(
                'token_atual' => array(
                    'password',
                    array(
                        'Label' => 'Senha Atual',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 30))),
                        'attribs' => array('required' => 'required'),
                    )
                ),
                'token' => array(
                    'password',
                    array(
                        'Label' => 'Nova Senha',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 30))),
                        'attribs' => array('required' => 'required'),
                    )
                ),
                'token_confirm' => array(
                    'password',
                    array(
                        'Label' => 'Confirmar Nova Senha',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(
                            array('StringLength', false, array(0, 30)),
                            array('Identical', false, array('token' => 'token')),
                        ),
                        'attribs' => array('required' => 'required'),
                    )
                ),
                'submit' => array(
                    'button',
                    array(
                        'ignore' => true,
                        'label' => 'Confirmar',
                        'escape' => false,
                        'attribs' => array(
                            'id' => 'submitbutton',
                            'type' => 'submit',
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
