<?php

class Default_Form_LogarUsuario extends App_Form_FormAbstract
{

    public function init()
    {

        $this->setOptions(array(
            "method" => "post",
            "elements" => array(
                'desemail' => array(
                    'text',
                    array(
                        'label' => 'E-mail do usuário',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 100)), 'EmailAddress'),
                        'attribs' => array('required' => 'required'),
                    )
                ),
                'token' => array(
                    'password',
                    array(
                        'Label' => 'Senha do usuário',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 100))),
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
