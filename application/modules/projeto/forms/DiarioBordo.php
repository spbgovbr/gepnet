<?php

class Projeto_Form_DiarioBordo extends App_Form_FormAbstract
{

    public function init()
    {

        $arrReferencias = array(
            '' => 'Selecione',
            'Observação' => 'Observação',
            'Ponto de Atenção' => 'Ponto de Atenção',
            'Reunião' => 'Reunião'
        );
        $arrSemaforo = array(
            '' => 'Selecione',
            '1' => 'Vermelho',
            '2' => 'Amarelo',
            '3' => 'Verde'
        );

        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-diario',
            'elements' => array(
                'iddiariobordo' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'idprojeto' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'domreferencia' => array(
                    'select',
                    array(
                        'label' => 'Referência',
                        'required' => true,
                        'multiOptions' => $arrReferencias,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '20',
                            'data-rule-required' => true,
                            'data-rule-maxlength' => 20,
                        ),
                    )
                ),
                'datdiariobordo' => array(
                    'text',
                    array(
                        'label' => 'Data do Diário',
                        'required' => true,
                        'maxlength' => '10',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 datepicker mask-date datemask-BR ',
                            'data-rule-maxlength' => 10,
                            'data-rule-required' => true,
                            'data-rule-dateITA' => true,
                        ),
                    )
                ),
                'domsemafaro' => array(
                    'select',
                    array(
                        'label' => 'Status',
                        'required' => true,
                        'multiOptions' => $arrSemaforo,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                            'data-rule-hora' => true,
                        ),
                    )
                ),
                'desdiariobordo' => array(
                    'textarea',
                    array(
                        'label' => 'Descrição',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span10',
                            'maxlength' => '4000',
                            'data-rule-maxlength' => 4000,
                            'data-rule-required' => true,
                            'rows' => 8,
                            'placeholder' => 'Descrição do Diário',
                        ),
                    )
                ),
            )
        ));
    }
}
