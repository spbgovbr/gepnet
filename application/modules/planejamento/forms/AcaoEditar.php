<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Planejamento_Form_AcaoEditar extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbPessoa = new Default_Model_Mapper_Pessoa();
        $mapperTbEscritorio = new Default_Model_Mapper_Escritorio();
        $mapperTbObjetivo = new Planejamento_Model_Mapper_Objetivo();
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-acao",
                "elements" => array(
                    'idacao' => array('hidden', array()),
                    'idobjetivo' => array(
                        'select',
                        array(
                            'ignore' => true,
                            'label' => 'Objetivo',
                            'required' => false,
                            'multiOptions' => $mapperTbObjetivo->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span6'
                            ),
                        )
                    ),
                    'nomacao' => array(
                        'text',
                        array(
                            'label' => 'Nome',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span6'
                            ),
                        )
                    ),
                    'idcadastrador' => array(
                        'select',
                        array(
                            'label' => 'Cadastrador',
                            'required' => false,
                            'multiOptions' => $mapperTbPessoa->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'datcadastro' => array(
                        'text',
                        array(
                            'label' => 'Data Cadastro',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(),
                        )
                    ),
                    'flaativo' => array(
                        'select',
                        array(
                            'label' => 'Ativo',
                            'required' => true,
                            'multiOptions' => $mapperTbObjetivo->fetchFlaativo(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2'
                            ),
                        )
                    ),
                    'desacao' => array(
                        'textarea',
                        array(
                            'label' => 'Descrição',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span11',
                                'rows' => '10',
                            ),
                        )
                    ),
                    'idescritorio' => array(
                        'select',
                        array(
                            'label' => 'Escritório',
                            'required' => false,
                            'multiOptions' => $mapperTbEscritorio->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2'
                            ),
                        )
                    ),
                    'numseq' => array(
                        'text',
                        array(
                            'label' => 'numseq',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                )
            ));
    }

}

