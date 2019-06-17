<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_AtividadeCronogramaEntrega extends App_Form_FormAbstract
{

    public function init()
    {

        $this
            ->setOptions(array(
                "method" => "post",
                "id" => 'ac-entrega',
                "elements" => array(
                    'idatividadecronograma' => array('hidden', array()),
                    'idprojeto' => array('hidden', array()),
                    'domtipoatividade' => array('hidden', array()),
                    'datinicio' => array('hidden', array()),
                    'datfim' => array('hidden', array()),
                    'datiniciobaseline' => array('hidden', array()),
                    'datfimbaseline' => array('hidden', array()),
                    'idgrupo' => array(
                        'select',
                        array(
                            'label' => 'Grupo',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => true
                            ),
                        )
                    ),
                    'nomatividadecronograma' => array(
                        'text',
                        array(
                            'label' => 'Nome da Entrega',
                            'required' => true,
                            'maxlength' => '255',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => true,
                                'data-rule-minlength' => 2,
                                'data-rule-maxlength' => 255,
                            ),
                        )
                    ),
                    'idparteinteressada' => array(
                        'select',
                        array(
                            'label' => 'Responsável pela entrega',
                            'required' => false,
                            'multiOptions' => array(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'idresponsavel' => array(
                        'select',
                        array(
                            'label' => 'Responsável pela aceitação',
                            'required' => false,
                            'multiOptions' => array(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'desobs' => array(
                        'textarea',
                        array(
                            'label' => 'Descrição da Entrega',
                            'required' => false,
                            'maxlength' => '4000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 3,
                                'cols' => 200,
                                'class' => 'span8',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4000,
                            ),
                        )
                    ),
                    'descriterioaceitacao' => array(
                        'textarea',
                        array(
                            'label' => 'Critério de Aceitação / Desvio Aceitável (Qualidade)',
                            'required' => false,
                            'maxlength' => '4000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 3,
                                'cols' => 200,
                                'class' => 'span8',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4000,
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
                    ),
                )
            ));
    }

}

