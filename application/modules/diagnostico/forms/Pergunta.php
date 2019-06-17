<?php

class Diagnostico_Form_Pergunta extends App_Form_FormAbstract
{

    public function init()
    {
        $this
            ->setOptions(array(
                    "method" => "post",
                    "id" => "form-pergunta",
                    "elements" => array(
                        'idpergunta' => array('hidden', array()),
                        'posicaocad' => array('hidden', array()),
                        'idquestionario' => array('hidden', array()),
                        'dspergunta' => array(
                            'text',
                            array(
                                'label' => 'Descrição da Pergunta',
                                'required' => false,
                                'maxlength' => '250',
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 250))),
                                'attribs' => array(
                                    'class' => 'span4',
                                    'data-rule-required' => false,
                                    'autocomplete' => 'off',
                                    'placeholder' => 'Descreva Pergunta'
                                ),
                            )
                        ),
                        'dstitulo' => array(
                            'text',
                            array(
                                'label' => 'Enunciado da Pergunta',
                                'required' => true,
                                'maxlength' => '200',
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 200))),
                                'attribs' => array(
                                    'class' => 'span5',
                                    'data-rule-required' => true,
                                    'autocomplete' => 'off',
                                    'placeholder' => 'Enunciado da pergunta no questionário'
                                ),
                            )
                        ),
                        'id_secao' => array(
                            'select',
                            array(
                                'label' => 'Seção',
                                'required' => true,
                                'multiOptions' => array('' => 'Selecione'),
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'span2',
                                    'data-rule-required' => true,
                                ),
                            )
                        ),
                        'posicao' => array(
                            'text',
                            array(
                                'label' => 'Ordem',
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
                        'tiporegistro' => array(
                            'select',
                            array(
                                'label' => 'Tipo de Registro',
                                'required' => true,
                                'multiOptions' => array('' => 'Selecione', '1' => 'Numérico', '2' => 'Textual'),
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'span2',
                                    'data-rule-required' => true,
                                ),
                            )
                        ),
                        'ativa' => array(
                            'select',
                            array(
                                'label' => 'Obrigatória?',
                                'required' => true,
                                'multiOptions' => array('' => 'Selecione', '1' => 'Sim', '0' => 'Não'),
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'span2',
                                    'data-rule-required' => true,
                                ),
                            )
                        ),
                        'tipopergunta' => array(
                            'select',
                            array(
                                'label' => 'Tipo de Resposta',
                                'required' => true,
                                'multiOptions' => array(
                                    '' => 'Selecione',
                                    '1' => 'Descritiva',
                                    '2' => 'Múltipla Escolha',
                                    '3' => 'Única Escolha'
                                ),
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'span2',
                                    'data-rule-required' => true,
                                ),
                            )
                        ),
                    )
                )
            );
    }

}
