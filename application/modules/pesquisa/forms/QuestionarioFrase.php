<?php

class Pesquisa_Form_QuestionarioFrase extends App_Form_FormAbstract
{

    public function init()
    {
        $obrigatoriedade = array('N' => 'Não', 'S' => 'Sim');

        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-questionario-frase',
            'elements' => array(
                'idquestionario' => array(
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
                'numordempergunta' => array(
                    'text',
                    array(
                        'label' => 'Ordem',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', 'Digits', array('StringLength', false, array(0, 3))),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => 3,
                            'data-rule-number' => true,
                            'data-rule-maxlength' => 3,
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'obrigatoriedade' => array(
                    'select',
                    array(
                        'label' => 'Obrigatório',
                        'required' => true,
                        'multiOptions' => $obrigatoriedade,
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
