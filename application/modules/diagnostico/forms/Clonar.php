<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Diagnostico_Form_Clonar extends App_Form_FormAbstract
{

    public function init()
    {
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => 'form-clonar',
                "name" => 'form-clonar',
                "elements" => array(
                    'idquestionariodiagnostico' => array('hidden', array()),
                    'observacao' => array('hidden', array()),
                    'nomquestionario' => array(
                        'text',
                        array(
                            'label' => 'Nome do Questionário',
                            'required' => true,
                            'maxlength' => '180',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 180))),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'tipo' => array(
                        'select',
                        array(
                            'label' => 'Tipo',
                            'required' => true,
                            'multiOptions' => array('' => 'Selecione', '2' => 'Cidadão', '1' => 'Servidor'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                                'disabled' => true,
                            ),
                        )
                    ),
                    'observacao' => array(
                        'textarea',
                        array(
                            'label' => 'Observações',
                            'maxlength' => 4000,
                            'wrap' => 'hard',
                            'style' => 'word-wrap: break-word; word-break: break-all;',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span5',
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 4000,
                                'rows' => 4,
                                'placeholder' => 'Observações',
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
                                'id' => 'submitbuttonClonar',
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
                    ),
                )
            ));
    }

}

