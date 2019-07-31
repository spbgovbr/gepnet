<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated26-03-2013 12:47
 */
class Admin_Form_UsuarioAlterarSenha extends Twitter_Bootstrap_Form_Vertical
{
    public function init()
    {
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "usuario-senha",
                "elements" => array(
                    'ds_senha' => array(
                        'password',
                        array(
                            'label' => 'Senha',
                            'required' => true,
                            'description' => 'Inserir senha padrão',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(
                                'NotEmpty',
                                array('StringLength', false, array(0, 30))
                            ),
                            'attribs' => array(
                                'class' => 'span4'
                            ),
                        )
                    ),
                    'ds_senha_repeat' => array(
                        'password',
                        array(
                            'label' => 'Repita a senha',
                            'required' => true,
                            'description' => 'Insira o mesmo valor do campo senha.',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(
                                'NotEmpty',
                                array('StringLength', false, array(0, 30)),
                                array('Identical', false, array('token' => 'ds_senha')),
                            ),
                            'attribs' => array(
                                'class' => 'span4'
                            ),
                        )
                    ),
                    'cd_lotacao' => array('hidden', array()),
                    'cd_pessoa' => array('hidden', array()),
                )
            ));
    }
}

