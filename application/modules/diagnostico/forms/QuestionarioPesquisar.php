<?php

class Diagnostico_Form_QuestionarioPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $diagnostico = new Diagnostico_Model_Mapper_Diagnostico();

        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-pesquisar",
                "elements" => array(
                    'iddiagnostico' => array('hidden', array()),
                    'nomquestionario' => array(
                        'text',
                        array(
                            'label' => 'Nome Questionário',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'placeholder' => 'Nome do Questionário',
                            'attribs' => array(
                                'class' => 'span3'
                            ),
                        )
                    ),
                    'tipo' => array(
                        'select',
                        array(
                            'label' => 'Tipo de Questionário',
                            'required' => false,
                            'multiOptions' => array('' => 'Todos', 2 => 'Cidadão', 1 => 'Servidor'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span3 select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'iddiagnostico' => array(
                        'select',
                        array(
                            'label' => 'Diagnóstico',
                            'required' => false,
                            'multiOptions' => array('' => 'Todos') + $diagnostico->getAll(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span3 select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'submit' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Pesquisar',
                            'icon' => 'filter',
                            'whiteIcon' => false,
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'submitbutton',
                                'type' => 'submit',
                                'class' => 'btn'
                            ),
                        )
                    ),
                    'dtcadastro' => array(
                        'text',
                        array(
                            'label' => 'Data de Cadastro',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('Date'),
                            'attribs' => array(
                                'class' => 'span3 mask-date',
                                'maxlength' => '10',
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                                'placeholder' => 'DD/MM/AAAA'
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
                    'close' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'icon' => 'arrow-right',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                            'label' => 'Fechar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'closebutton',
                                'type' => 'button',
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
        $this->getElement('close')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }
}
