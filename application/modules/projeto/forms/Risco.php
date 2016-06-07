<?php

class Projeto_Form_Risco extends App_Form_FormAbstract
{

    public function init()
    {
        $origemRisco = new Projeto_Model_Mapper_Origemrisco();
        $etapa       = new Projeto_Model_Mapper_Etapa();
        $tipoRisco   = new Projeto_Model_Mapper_Tiporisco();
        
        $arrTratamento = array(
            ''  => 'Selecione',     
            '1' =>'Conviver',
            '2' =>'Mitigar (Reduzir efeitos)',
            '3' =>'Neutralizar',
            '4' =>'Potencializar',
            '5' =>'Tranferir',
        );
        $arrProbabilidade = array(
            ''  => 'Selecione',
            '1' => 'Alta',
            '2' => 'Media',
            '3' => 'Baixa',
            );
        $arrImpacto = array(
            ''  => 'Selecione',
            '1' => 'Alta',
            '2' => 'Media',
            '3' => 'Baixa',
            );
        
        $this->setOptions(array(
            'method' => 'post',
            'id'     => 'form-risco',
            'elements'=>array(
                        'idrisco' => array('hidden', array(                                
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('Digits'),
                            )),
                        'idprojeto' => array('hidden', array(
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('Digits'),
                            )),
                        'norisco' => array('text', array(
                                'label' => 'Título Risco',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', array('StringLength', false, array(0, 150))),
                                'attribs' => array(
                                    'class' => 'span3',
                                    'maxlength' => '150',
                                    'data-rule-maxlength' => 150,
                                    'data-rule-required' => true,
                                    'placeholder' => 'Informe o título do risco'
                                ),
                            )),
                        'datdeteccao' => array('text', array(
                                'label' => 'Detecção',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('Date'),
                                'attribs' => array(
                                    'class' => 'span2 mask-date',
                                    'maxlength' => '10',
                                    'data-rule-maxlength' => 10,
                                    'data-rule-required' => true,
                                    'data-rule-dateITA' => true,
                                    'placeholder' => 'DD/MM/AAAA'
                                ),
                            )),
                        'idorigemrisco' => array('select', array(
                                'label' => 'Origem',
                                'required' => true,
                                'multiOptions' => $origemRisco->fetchPairs(true),
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                                'attribs' => array(
                                    'class' => 'span2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'idetapa' => array('select', array(
                                'label' => 'Etapa',
                                'required' => true,
                                'multiOptions' => $etapa->fetchPairs(true),
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                                'attribs' => array(
                                    'class' => 'span3',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'idtiporisco' => array('select', array(
                                'label' => 'Tipo',
                                'required' => true,
                                'multiOptions' => $tipoRisco->fetchPairs(true),
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                                'attribs' => array(
                                    'class' => 'span3',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'domcorprobabilidade' => array('select', array(
                                'label' => 'Probabilidade',
                                'required' => true,
                                'multiOptions' => $arrProbabilidade,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                                'attribs' => array(
                                    'class' => 'span2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'domcorimpacto' => array('select', array(
                                'label' => 'Impacto',
                                'required' => true,
                                'multiOptions' => $arrImpacto,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                                'attribs' => array(
                                    'class' => 'span2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'domcorrisco' => array('hidden', array(
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('Digits'),
                            )),    
                        'desrisco' => array('textarea', array(
                                'label' => 'Descrição',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty'),
                                'attribs' => array(
                                    'class' => 'span10',
                                    'data-rule-required' => true,
                                    'data-rule-minlength' => 10,
                                    'rows' => 3,
                                    'placeholder'=>'Descrição do risco',
                                ),
                            )),                        
                        'descausa' => array('textarea', array(
                                'label' => 'Causa',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty'),
                                'attribs' => array(
                                    'class' => 'span5',
                                    'data-rule-required' => true,
                                    'data-rule-minlength' => 10,
                                    'rows' => 3,
                                    'placeholder'=>'Descrição da causa',
                                ),
                            )),                        
                        'desconsequencia' => array('textarea', array(
                                'label' => 'Consequência',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty'),
                                'attribs' => array(
                                    'class' => 'span5',
                                    'data-rule-required' => true,
                                    'data-rule-minlength' => 10,
                                    'rows' => 3,
                                    'placeholder'=>'Descrição da consequência',
                                ),
                            )),
                        'domtratamento' => array('select', array(
                                'label' => 'Tratamento',
                                'required' => true,
                                'multiOptions' => $arrTratamento,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                                'attribs' => array(
                                    'class' => 'span2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'flariscoativo' => array('select', array(
                                'label' => 'Ativo?',
                                'required' => true,
                                'multiOptions' => array(''=>'Selecione', '1'=>'Sim', '2'=>'Não'),
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                                'attribs' => array(
                                    'class' => 'span2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'datencerramentorisco' => array('text', array(
                                'label' => 'Data encerramento',
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('Date'),
                                'attribs' => array(
                                    'class' => 'span2 mask-date',
                                    'maxlength' => '10',
                                    'data-rule-maxlength' => 10,
                                    'data-rule-dateITA' => true,
                                    'placeholder' => 'DD/MM/AAAA'
                                ),
                            )),
                        'flaaprovado' => array('select', array(
                                'label' => 'Risco aprovado pelo GP?',
                                'required' => true,
                                'multiOptions' => array(''=>'Selecione', '1'=>'Sim', '2'=>'Não'),
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                                'attribs' => array(
                                    'class' => 'span2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'datinatividade' => array('text', array(
                                'label' => 'Data Inatividade',
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('Date'),
                                'attribs' => array(
                                    'class' => 'span2 mask-date',
                                    'maxlength' => '10',
                                    'data-rule-maxlength' => 10,
                                    'data-rule-dateITA' => true,
                                    'placeholder' => 'DD/MM/AAAA'
                                ),
                            )),
            )
        ));
    }
}
