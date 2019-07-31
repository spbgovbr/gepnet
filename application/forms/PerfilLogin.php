<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Default_Form_PerfilLogin extends App_Form_FormAbstract
{

    public function init()
    {
        $service = new Default_Service_Perfil();
        $serviceLogin = new Default_Service_Login();
        $usuario = $serviceLogin->retornaUsuarioLogado();
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-perfil",
                "elements" => array(
                    'idperfil' => array(
                        'select',
                        array(
                            'label' => 'Perfil',
                            'required' => false,
                            'multiOptions' => $service->retornaPorPessoa(array('idpessoa' => $usuario->idpessoa)),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'style' => 'width: 300px;'
                            ),
                        )
                    ),
                    'submit' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Entrar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'submitbutton',
                                'type' => 'submit',
                            ),
                        )
                    ),
                )
            ));
        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}

