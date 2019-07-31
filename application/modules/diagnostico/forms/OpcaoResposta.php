<?php

class Diagnostico_Form_OpcaoResposta extends App_Form_FormAbstract
{
    public function init()
    {
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-opcao-resposta",
                "elements" => array(
                    'idpergunta' => array('hidden', array()),
                    'idquestionario' => array('hidden', array()),
                    'idresposta' => array('hidden', array()),
                    'posicaoat' => array('hidden', array()),
                    'posicaocad' => array('hidden', array()),
                    'desresposta' => array(
                        'text',
                        array(
                            'label' => 'Descrição da Resposta',
                            'required' => true,
                            'maxlength' => '300',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 110))),
                            'attribs' => array(
                                'class' => 'span6',
                                'data-rule-required' => false,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                    'escala' => array(
                        'text',
                        array(
                            'label' => 'Escala',
                            'required' => false,
                            'maxlength' => '2',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'span1',
                                'data-rule-required' => false,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                    'ordenacao' => array(
                        'text',
                        array(
                            'label' => 'Ordenação',
                            'required' => true,
                            'maxlength' => '3',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'span1',
                                'data-rule-required' => true,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                )
            ));
    }
}