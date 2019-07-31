<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_AtividadeCronogramaPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => 'ac_atividade_pesquisar',
                "elements" => array(
                    'idprojeto_pesq' => array('hidden', array()),
                    /*
                    'nomatividadecronograma' => array('text', array(
                            'label'      => 'Nome',
                            'required'   => false,
                            'filters'    => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                            'attribs'    => array(
                                'class'               => 'span4',
                                'data-rule-minlength' => 2,
                                'data-rule-maxlength' => 255,
                            ),
                        )),
                    */
                    'status' => array(
                        'select',
                        array(
                            'label' => 'Status',
                            'multiOptions' => array(
                                '' => 'Todos',
                                100 => 'Concluído',
                                50 => 'Em andamento'
                            ),
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2',
                                'style' => 'width:114px;'
                            ),
                        )
                    ),
                    'percentualinicio' => array(
                        'select',
                        array(
                            'label' => 'De',
                            'multiOptions' => array(
                                1 => '1%',
                                10 => '10%',
                                20 => '20%',
                                30 => '30%',
                                40 => '40%',
                                50 => '50%',
                                60 => '60%',
                                70 => '70%',
                                80 => '80%',
                                90 => '90%',
                                99 => '99%'
                            ),
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2',
                                'style' => 'width:80px;'
                            ),
                        )
                    ),
                    'percentualfim' => array(
                        'select',
                        array(
                            'label' => 'Até',
                            'multiOptions' => array(
                                1 => '1%',
                                10 => '10%',
                                20 => '20%',
                                30 => '30%',
                                40 => '40%',
                                50 => '50%',
                                60 => '60%',
                                70 => '70%',
                                80 => '80%',
                                90 => '90%',
                                99 => '99%'
                            ),
                            'value' => 99,
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2',
                                'style' => 'width:80px;'

                            ),
                        )
                    ),
                    'inicial_dti' => array(
                        'text',
                        array(
                            'label' => 'Início',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker mask-date',
                                'data-input' => '#datinicio',
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                                'placeholder' => 'DD/MM/AAAA',
                                'style' => 'width:80px !important;',
                            ),
                        )
                    ),
                    'inicial_dtf' => array(
                        'text',
                        array(
                            'label' => 'Fim',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker mask-date',
                                'data-input' => '#datfim',
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                                'placeholder' => 'DD/MM/AAAA',
                                'style' => 'width:80px !important;',
                            ),
                        )
                    ),
                    'final_dti' => array(
                        'text',
                        array(
                            'label' => 'Início',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker mask-date',
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                                'placeholder' => 'DD/MM/AAAA',
                                'style' => 'width:80px !important;',
                            ),
                        )
                    ),
                    'final_dtf' => array(
                        'text',
                        array(
                            'label' => 'Fim',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker mask-date',
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                                'placeholder' => 'DD/MM/AAAA',
                                'style' => 'width:80px !important;',
                            ),
                        )
                    ),
                    'idparteinteressada_pesq' => array(
                        'select',
                        array(
                            'label' => 'Responsável',
                            'required' => false,
                            'multiOptions' => array(), // $mapperTbPessoa->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => false,
                                'style' => 'width:250px;'
                            ),
                        )
                    ),
                    'domtipoatividade_pesq' => array(
                        'select',
                        array(
                            'label' => 'Tipo',
                            'required' => false,
                            'multiOptions' => array(
                                '' => 'Todos',
                                Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_COMUM => 'Atividade',
                                Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO => 'Marco',
                                Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_ENTREGA => 'Entrega',
                                //Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_GRUPO   => 'Grupo',
                            ),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2',
                                'data-rule-required' => false,
                                'style' => 'width:114px;'
                            ),
                        )
                    ),
                    'submit_pesq' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Pesquisar',
                            'icon' => 'filter',
                            'whiteIcon' => false,
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'submitbutton_pesq',
                                'type' => 'submit',
                                'class' => 'btn'
                            ),
                        )
                    ),
                    'reset_pesq' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'icon' => 'th',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                            'label' => 'Limpar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'resetbutton_pesq',
                                'type' => 'reset',
                            ),
                        )
                    ),
                    'close_pesq' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'icon' => 'arrow-right',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                            'label' => 'Fechar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'closebutton_pesq',
                                'type' => 'button',
                            ),
                        )
                    ),
                )
            ));


        $this->getElement('submit_pesq')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('reset_pesq')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('close_pesq')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}

