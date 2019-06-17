<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_AtividadeCronograma extends App_Form_FormAbstract
{

    public function init()
    {


        $this
            ->setOptions(array(
                "method" => "post",
                "id" => 'ac-atividade',
                "elements" => array(
                    'idatividadecronograma' => array('hidden', array()),
                    'maior_valor' => array('hidden', array()),
                    'listaPredecessoras' => array('hidden', array()),
                    'idprojeto' => array('hidden', array()),
                    'numcriteriofarol' => array('hidden', array()),
                    'datInicioHidden' => array('hidden', array('attribs' => array('id' => 'datInicioHidden'))),
                    'nomatividadecronograma' => array(
                        'text',
                        array(
                            'label' => 'Nome da Atividade',
                            'required' => true,
                            'maxlength' => 255,
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
                    'idgrupo' => array(
                        'select',
                        array(
                            'label' => 'Entrega',
                            'required' => true,
                            'multiOptiosn' => array(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'numpercentualconcluido' => array(
                        'select',
                        array(
                            'label' => 'Percentual Concluído(%)',
                            'multiOptions' => array(
                                0 => '0%',
                                10 => '10%',
                                20 => '20%',
                                30 => '30%',
                                40 => '40%',
                                50 => '50%',
                                60 => '60%',
                                70 => '70%',
                                80 => '80%',
                                90 => '90%',
                                100 => '100%'
                            ),
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'flacancelada' => array(
                        'select',
                        array(
                            'label' => 'Cancelada?',
                            'required' => true,
                            'multiOptions' => array('N' => 'Não', 'S' => 'Sim'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 3))),
                            'attribs' => array(
                                'class' => 'span2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'flaaquisicao' => array(
                        'select',
                        array(
                            'label' => 'Aquisição?',
                            'required' => true,
                            'multiOptions' => array('N' => 'Não', 'S' => 'Sim'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 3))),
                            'attribs' => array(
                                'class' => 'span2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'predecessora' => array(
                        'select',
                        array(
                            'label' => 'Predecessora',
                            'required' => false,
                            'multiOptions' => array(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => false,
                                'size' => 7,
                                'multiple' => 'multiple'
                            ),
                        )
                    ),
                    'predecessorasAtividade' => array(
                        'select',
                        array(
                            'label' => 'Predecessoras Selecionadas',
                            'required' => false,
                            'multiOptions' => array(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => false,
                                'size' => 7,
                                'multiple' => 'multiple'
                            ),
                        )
                    ),
                    'numfolga' => array(
                        'text',
                        array(
                            'label' => 'Dias de Espera',
                            'required' => false,
                            'maxlength' => 10,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2',
                                'disabled' => true,
                                'data-rule-number' => true,
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'numdiasrealizados' => array(
                        'text',
                        array(
                            'label' => 'Duração',
                            'required' => true,
                            'maxlength' => 5,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 5))),
                            'attribs' => array(
                                'data-rule-required' => true,
                                'maxlength' => '5',
                                'style' => 'width:40px;',
                                'data-rule-number' => true,
                                'data-rule-maxlength' => 5,
                            ),
                        )
                    ),
                    'numdiasbaseline' => array(
                        'text',
                        array(
                            'label' => 'Duração',
                            'required' => true,
                            'maxlength' => 5,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 5))),
                            'attribs' => array(
                                'data-rule-number' => true,
                                'data-rule-required' => true,
                                'maxlength' => '5',
                                'style' => 'width:40px;',
                                'data-rule-maxlength' => 5,
                            ),
                        )
                    ),
                    'datiniciobaseline' => array(
                        'text',
                        array(
                            'label' => 'Início(base line)',
                            'required' => true,
                            'maxlength' => 10,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker datemask-BR',
                                'data-input' => '#datinicio',
                                'data-rule-required' => true,
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                                'data-rule-dataatividadeferiado' => true,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                    'datfimbaseline' => array(
                        'text',
                        array(
                            'label' => 'Fim (Base line)',
                            'required' => true,
                            'maxlength' => 10,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker datemask-BR',
                                'data-input' => '#datfim',
                                'data-rule-required' => true,
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                                'data-rule-dataatividadeferiado' => true,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                    'datinicio' => array(
                        'text',
                        array(
                            'label' => 'Início',
                            'required' => true,
                            'maxlength' => 10,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker datemask-BR',
                                'data-rule-required' => true,
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                                'data-rule-dataatividadeferiado' => true,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                    'datfim' => array(
                        'text',
                        array(
                            'label' => 'Fim',
                            'required' => true,
                            'maxlength' => 10,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker datemask-BR',
                                'data-rule-required' => true,
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                                'data-rule-dataatividadeferiado' => true,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                    'vlratividadebaseline' => array(
                        'text',
                        array(
                            'label' => 'Custo',
                            'required' => true,
                            'maxlength' => 25,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'money',
                                'data-rule-required' => true,
                                //'data-rule-custoInformatica' => false,
                            ),
                        )
                    ),
                    'vlratividade' => array(
                        'text',
                        array(
                            'label' => 'Custo',
                            'required' => false,
                            'maxlength' => 25,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'money',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'idelementodespesa' => array(
                        'select',
                        array(
                            'label' => 'Elemento de Despesa',
                            'required' => false,
                            'multiOptions' => array(), //$mapperTbElementodespesa->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span4',
                                //'data-rule-custo' => true,
                                //'data-rule-required'  => false,
                            ),
                        )
                    ),
                    'flainformatica' => array(
                        'select',
                        array(
                            'label' => 'Material de Informática?',
                            'required' => false,
                            'multiOptions' => array(
                                'N' => 'Não',
                                'S' => 'Sim'
                            ),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 3))),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => false,
                                //'data-rule-custoInformatica' => true,
                                //'data-rule-flgInformatica' => true,
                            ),
                        )
                    ),
                    'idparteinteressada' => array(
                        'select',
                        array(
                            'label' => 'Responsável',
                            'required' => true,
                            'multiOptions' => array(), // $mapperTbPessoa->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'domtipoatividade' => array(
                        'select',
                        array(
                            'label' => 'Marco ?',
                            'required' => true,
                            'multiOptions' => array(
                                Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_COMUM => 'Não',
                                Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO => 'Sim'
                            ),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'desobs' => array(
                        'textarea',
                        array(
                            'label' => 'Observação',
                            'required' => false,
                            'maxlength' => 4000,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 2,
                                'cols' => 200,
                                'class' => 'span8',
                                'style' => 'height:30px !important',
                                //'data-rule-custo' => true,
                                //'data-rule-required'  => false,
                                'data-rule-maxlength' => 4000,
                            ),
                        )
                    ),
                    'idmarcoanterior' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    /*
                      'numdias'                => array('text', array(
                      'label'      => '',
                      'required'   => false,
                      'filters'    => array('StringTrim', 'StripTags'),
                      'validators' => array('NotEmpty'),
                      'attribs'    => array(),
                      )),
                      'nomresponsavel'         => array('text', array(
                      'label'      => 'Nome (não vinculado ao Órgão)',
                      'required'   => false,
                      'filters'    => array('StringTrim', 'StripTags'),
                      'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                      'attribs'    => array(
                      'class' => 'span4'
                      ),
                      )),
                     */
                    /*
                      'descriterioaceitacao'   => array('textarea', array(
                      'label'      => '',
                      'required'   => false,
                      'filters'    => array('StringTrim', 'StripTags'),
                      'validators' => array('NotEmpty', array('StringLength', false, array(0, -1))),
                      'attribs'    => array('rows' => 24, 'cols' => 80),
                      )),
                      'idpredecessora2'        => array('text', array(
                      'label'      => '',
                      'required'   => false,
                      'filters'    => array('StringTrim', 'StripTags'),
                      'validators' => array('NotEmpty', array('StringLength', false, array(0, 300))),
                      'attribs'    => array(),
                      )),
                     */
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

