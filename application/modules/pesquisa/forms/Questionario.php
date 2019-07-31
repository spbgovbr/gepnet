<?php

class Pesquisa_Form_Questionario extends App_Form_FormAbstract
{

    public function init()
    {
        $arrSituacao = array(
            Pesquisa_Model_Questionario::PUBLICADO_COM_SENHA => 'Publicado com senha',
            Pesquisa_Model_Questionario::PUBLICADO_SEM_SENHA => 'Publicado sem senha',
        );


        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-questionario',
            'elements' => array(
                'idquestionario' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'nomquestionario' => array(
                    'text',
                    array(
                        'label' => 'Nome do Questionário',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'data-rule-required' => true,
                            'placeholder' => 'Informe o nome questionário.'
                        ),
                    )
                ),
                'desobservacao' => array(
                    'textarea',
                    array(
                        'label' => 'Observação',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 800))),
                        'attribs' => array(
                            'class' => 'span',
                            'data-rule-maxlength' => 800,
                            'rows' => 10,
                            'placeholder' => 'Observações do questionário.'
                        ),
                    )
                ),
                'tipoquestionario' => array(
                    'select',
                    array(
                        'label' => 'Tipo',
                        'required' => true,
                        'multiOptions' => $arrSituacao,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'data-rule-required' => true,
                        ),
                    )
                ),
            )
        ));
    }
}
