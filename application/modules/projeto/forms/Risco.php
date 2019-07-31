<?php

class Projeto_Form_Risco extends App_Form_FormAbstract
{

    public function init()
    {
        $origemRisco = new Projeto_Model_Mapper_Origemrisco();
        $tipoRisco = new Projeto_Model_Mapper_Tiporisco();

        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-risco',
            'elements' => array(
                'idrisco' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'contramedidaefetiva' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'idcontramedida' => array(
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
                'trat' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'domtratamentooriginal' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'pgpass' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'norisco' => array(
                    'text',
                    array(
                        'label' => 'Título Risco',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '50',
                            'data-rule-maxlength' => 50,
                            'data-rule-required' => true,
                            'placeholder' => 'Informe o título do risco'
                        ),
                    )
                ),
                'datdeteccao' => array(
                    'text',
                    array(
                        'label' => 'Detecção',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'autocomplete' => 'off'
                        ),
                    )
                ),
                'desrisco' => array(
                    'textarea',
                    array(
                        'label' => 'Descrição',
                        'maxlength' => 4000,
                        'wrap' => 'hard',
                        'style' => 'word-wrap: break-word; word-break: break-all;',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span10',
                            'data-rule-minlength' => 10,
                            'data-rule-maxlength' => 4000,
                            'rows' => 3,
                            'placeholder' => 'Descrição do risco',
                        ),
                    )
                ),
                'idorigemrisco' => array(
                    'select',
                    array(
                        'label' => 'Origem',
                        'required' => false,
                        'multiOptions' => $origemRisco->fetchPairs(true),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 2))),
                        'attribs' => array(
                            'class' => 'span2',
                        ),
                    )
                ),
                'idetapa' => array(
                    'select',
                    array(
                        'label' => 'Etapa Em Que Foi Detectado',
                        'required' => false,
                        'multiOptions' => array('' => 'Selecione'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span3',
                        ),
                    )
                ),
                'idtiporisco' => array(
                    'select',
                    array(
                        'label' => 'Tipo',
                        'required' => true,
                        'multiOptions' => $tipoRisco->fetchPairs(true),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'domcorprobabilidade' => array(
                    'select',
                    array(
                        'label' => 'Probabilidade',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'domcorimpacto' => array(
                    'select',
                    array(
                        'label' => 'Impacto',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'domcorrisco' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'descausa' => array(
                    'textarea',
                    array(
                        'label' => 'Causa',
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
                            'rows' => 3,
                            'placeholder' => 'Descrição da causa',
                        ),
                    )
                ),
                'desconsequencia' => array(
                    'textarea',
                    array(
                        'label' => 'Consequência Para os Objetivos do Projeto',
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
                            'rows' => 3,
                            'placeholder' => 'Descrição da consequência',

                        ),
                    )
                ),
                'tratamento' => array(
                    'select',
                    array(
                        'label' => 'Tratamento',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span2',
                        ),
                    )
                ),
                'descontramedida' => array(
                    'textarea',
                    array(
                        'label' => 'Descrição do Tratamento / Contramedida',
                        'maxlength' => 4000,
                        'wrap' => 'hard',
                        'style' => 'word-wrap: break-word; word-break: break-all;',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span5',
                            'data-rule-minlength' => 10,
                            'data-rule-maxlength' => 4000,
                            'rows' => 3,
                            'placeholder' => 'Descrição do Tratamento / Contramedida',
                        ),
                    )
                ),
                'flariscoativo' => array(
                    'select',
                    array(
                        'label' => 'Ativo?',
                        'required' => true,
                        'multiOptions' => array('2' => 'Não', '1' => 'Sim'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'datencerramentorisco' => array(
                    'text',
                    array(
                        'label' => 'Data encerramento',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'autocomplete' => 'off'
                        ),
                    )
                ),
                'flaaprovado' => array(
                    'select',
                    array(
                        'label' => 'Risco aprovado pelo GP?',
                        'required' => false,
                        'multiOptions' => array('' => 'Selecione', '1' => 'Sim', '2' => 'Não'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'datinatividade' => array(
                    'text',
                    array(
                        'label' => 'Data Inatividade',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'autocomplete' => 'off'
                        ),
                    )
                ),
                //Campos novos
                'domstatuscontramedida' => array(
                    'select',
                    array(
                        'label' => 'Status Contramedida',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => false,
                        ),
                    )
                ),
                'datprazocontramedida' => array(
                    'text',
                    array(
                        'label' => 'Data Limite da Contramedida',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-required' => false,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'autocomplete' => 'off'
                        ),
                    )
                ),
                'datprazocontramedidaatraso' => array(
                    'text',
                    array(
                        'label' => 'Data Real / Tendência da Contramedida',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Date'),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-required' => false,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'autocomplete' => 'off'
                        ),
                    )
                ),
                'desresponsavel' => array(
                    'select',
                    array(
                        'label' => 'Responsável Pela Contramedida',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'multiOptions' => array('' => 'Selecione'),
                        'attribs' => array(
                            'class' => 'span3',
                        ),
                    )
                ),
                'flacontramedidaefetiva' => array(
                    'select',
                    array(
                        'label' => 'Contramedida Efetiva?',
                        'required' => false,
                        'multiOptions' => array('' => 'Selecione', '1' => 'Sim', '2' => 'Não'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span2',
                            'data-rule-required' => false,
                        ),
                    )
                ),
            )
        ));
    }
}
