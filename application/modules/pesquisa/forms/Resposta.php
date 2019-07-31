<?php

class Pesquisa_Form_Resposta extends App_Form_FormAbstract
{

    public function init()
    {

        $arrSituacao = array(
            '' => 'Selecione',
            Pesquisa_Model_Resposta::ATIVO => 'Ativo',
            Pesquisa_Model_Resposta::INATIVO => 'Inativo',
        );

        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-resposta',
            'elements' => array(
                'idresposta' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'idfrase' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'numordem' => array(
                    'text',
                    array(
                        'label' => 'Ordem',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(
                            array(
                                'NotEmpty',
                                false,
                                array('messages' => array('isEmpty' => 'Campo de preenchimento obrigatório.'))
                            ),
                            array('StringLength', false, array(0, 2)),
                            array('Digits'),
                        ),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '2',
                            'data-rule-maxlength' => 2,
                            'data-rule-required' => true,
                            'data-rule-number' => true,
                            'placeholder' => 'Informe a ordem'
                        ),
                    )
                ),
                'desresposta' => array(
                    'text',
                    array(
                        'label' => 'Resposta',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'data-rule-required' => true,
                            'placeholder' => 'Informe a resposta'
                        ),
                    )
                ),
                'flaativo' => array(
                    'select',
                    array(
                        'label' => 'Situação',
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
