<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_AtividadeCronogramaMarco extends App_Form_FormAbstract
{

    public function init()
    {
        $this
            ->setOptions(array(
                "method" => "post",
                "elements" => array(
                    'idatividadecronograma' => array('hidden', array()),
                    'idprojeto' => array('hidden', array()),
                    'nomatividadecronograma' => array(
                        'text',
                        array(
                            'label' => 'Nome da Atividade',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                            'attribs' => array(),
                        )
                    ),
                    'idgrupo' => array(
                        'text',
                        array(
                            'label' => 'Grupo',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numpercentualconcluido' => array(
                        'text',
                        array(
                            'label' => 'Percentual concluído',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'flacancelada' => array(
                        'select',
                        array(
                            'label' => 'Cancelada?',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 3))),
                            'attribs' => array(),
                        )
                    ),
                    'flaaquisicao' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 3))),
                            'attribs' => array(),
                        )
                    ),
                    'idpredecessora' => array(
                        'text',
                        array(
                            'label' => 'Predecessora',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'numfolga' => array(
                        'text',
                        array(
                            'label' => 'Dias de Espera',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'datiniciobaseline' => array(
                        'text',
                        array(
                            'label' => 'Início(base line)',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4))),
                            'attribs' => array(),
                        )
                    ),
                    'datfimbaseline' => array(
                        'text',
                        array(
                            'label' => 'Fim (Base line)',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4))),
                            'attribs' => array(),
                        )
                    ),
                    'datinicio' => array(
                        'text',
                        array(
                            'label' => 'Início',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4))),
                            'attribs' => array(),
                        )
                    ),
                    'datfim' => array(
                        'text',
                        array(
                            'label' => 'Fim',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4))),
                            'attribs' => array(),
                        )
                    ),
                    'vlratividadebaseline' => array(
                        'text',
                        array(
                            'label' => 'Custo(Base line)',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'vlratividade' => array(
                        'text',
                        array(
                            'label' => 'Custo',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'idelementodespesa' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => false,
                            'multiOptions' => array(),//$mapperTbElementodespesa->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'flainformatica' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 3))),
                            'attribs' => array(),
                        )
                    ),
                    'idresponsavel' => array(
                        'select',
                        array(
                            'label' => 'Responsável',
                            'required' => false,
                            'multiOptions' => array(),// $mapperTbPessoa->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'domtipoatividade' => array('hidden', array()),
                    'desobs' => array(
                        'textarea',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, -1))),
                            'attribs' => array('rows' => 24, 'cols' => 80),
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
                     */
                    'nomresponsavel' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs' => array(),
                        )
                    ),
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

