<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated26-03-2013 12:47
 */
class Admin_Form_Usuario extends Twitter_Bootstrap_Form_Vertical
{
    public function init()
    {
        $mapperLotacao = new Admin_Model_Mapper_Lotacao();
        $lotacoes = $mapperLotacao->fetchPairs(true);

        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "usuario",
                "elements" => array(
                    'cd_lotacao' => array(
                        'select',
                        array(
                            'label' => 'Lotação',
                            'multiOptions' => $lotacoes,
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 30))),
                            'attribs' => array(
                                'style' => 'width:350px;',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'ds_usuario' => array(
                        'text',
                        array(
                            'label' => 'Descrição',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 30))),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => true,
                                'data-rule-maxlength' => 30,
                                'data-rule-minlength' => 5,
                            ),
                        )
                    ),
                    'nr_nivel' => array(
                        'select',
                        array(
                            'label' => 'Nível',
                            'MultiOptions' => array(1 => 'Nível 1', 2 => 'Nível 2', 3 => 'Nível 3'),
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 22))),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'ds_senha' => array(
                        'password',
                        array(
                            'label' => 'Senha',
                            'required' => true,
                            'description' => 'Inserir senha padrão',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 30))),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => true,
                                'data-rule-maxlength' => 30,
                                'data-rule-minlength' => 5,
                            ),
                        )
                    ),
                    'cd_pessoa' => array('hidden', array()),
                )
            ));
    }
}

