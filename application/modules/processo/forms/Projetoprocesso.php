<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Processo_Form_Projetoprocesso extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbProcesso = new Processo_Model_Mapper_Processo();
        //$mapperTbPessoa          = new Default_Model_Mapper_Pessoa();
        $mapperTbProjetoprocesso = new Processo_Model_Mapper_Projetoprocesso();
        $this->setOptions(array(
            "method" => "post",
            "id" => "form-projetoprocesso",
            "elements" => array(
                'idprojetoprocesso' => array('hidden', array()),
                'idprocesso' => array(
                    'select',
                    array(
                        'label' => 'Processo',
                        'required' => true,
                        'multiOptions' => $mapperTbProcesso->fetchPairs(),
                        'filters' => array('StringTrim', 'StripTags'),
//                        'validators'   => array(),
//                        'attribs'      => array(),
                    )
                ),
                'numano' => array(
                    'text',
                    array(
                        'label' => 'Ano',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span1',
                        ),
                    )
                ),
                'domsituacao' => array(
                    'select',
                    array(
                        'label' => 'Situação',
                        'required' => true,
                        'multiOptions' => $mapperTbProjetoprocesso->fetchSituacao(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'datsituacao' => array(
                    'text',
                    array(
                        'label' => 'Data Situação',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'datepicker span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idresponsavel' => array(
                    'hidden',
                    array(
                        'label' => 'Responsável',
                        'required' => true,
                        //'multiOptions' => $mapperTbPessoa->fetchPairs(),
                        'filters' => array('StringTrim', 'StripTags'),
                        //'validators' => array('NotEmpty'),
                        'attribs' => array(),
                    )
                ),
                'nomresponsavel' => array(
                    'text',
                    array(
                        'label' => 'Responsável',
                        'required' => true,
                        //'multiOptions' => $mapperTbPessoa->fetchPairs(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span3',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'desprojetoprocesso' => array(
                    'textarea',
                    array(
                        'label' => 'Descrição',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span12',
                            'rows' => '10',
                            'data-rule-maxlength' => 100,
                        ),
                    )
                ),
                'datinicioprevisto' => array(
                    'text',
                    array(
                        'label' => 'Data Início Previsto',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'datepicker span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'datterminoprevisto' => array(
                    'text',
                    array(
                        'label' => 'Data Término Previsto',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'datepicker span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'vlrorcamento' => array(
                    'text',
                    array(
                        'label' => 'Orçamento Estimado',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idcadastrador' => array(
                    'text',
                    array(
                        'label' => '',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(),
                    )
                ),
                'codprojeto' => array(
                    'text',
                    array(
                        'label' => '',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(),
                    )
                ),
                'datcadastro' => array(
                    'text',
                    array(
                        'label' => '',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 4))),
                        'attribs' => array(),
                    )
                ),
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
                'pessoabutton' => array(
                    'button',
                    array(
                        'label' => '',
                        'ignore' => true,
                        'icon' => 'user',
                        'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                        //'label' => 'Limpar',
                        'escape' => true,
                        'attribs' => array(
                            'class' => 'pessoa-button',
                            'type' => 'button',
                        )
                    )
                ),
            )
        ));
        $this->getElement('nomresponsavel')
            //->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');


        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('reset')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}

