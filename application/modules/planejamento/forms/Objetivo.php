<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Planejamento_Form_Objetivo extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbPessoa = new Default_Model_Mapper_Pessoa();
        $mapperTbEscritorio = new Default_Model_Mapper_Escritorio();
        $mapperTbObjetivo = new Planejamento_Model_Mapper_Objetivo();
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-objetivo",
                "elements" => array(
                    'idobjetivo' => array('hidden', array()),
                    'nomobjetivo' => array(
                        'text',
                        array(
                            'label' => 'Nome',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span6',
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
                    'desobjetivo' => array(
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
                    'codescritorio' => array(
                        'select',
                        array(
                            'label' => 'Escritório',
                            'required' => false,
                            'multiOptions' => $mapperTbEscritorio->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
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
                    'submit' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Salvar',
                            'whiteIcon' => false,
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
        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('reset')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}

