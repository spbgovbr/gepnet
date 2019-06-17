<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Agenda_Form_Agenda extends App_Form_FormAbstract
{

    public function init()
    {
//        $mapperTbPessoa = new Default_Model_Mapper_TbPessoa();
        $mapperTbEscritorio = new Default_Model_Mapper_Escritorio();
        $this->setOptions(array(
            "id" => "form-agenda",
            "method" => "post",
            "elements" => array(
                'idagenda' => array('hidden', array()),
                'idcadastrador' => array('hidden', array()),
                'desassunto' => array(
                    'text',
                    array(
                        'label' => 'Assunto',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'data-rule-required' => true,
                            'class' => 'span5',
                        ),
                    )
                ),
                'datagenda' => array(
                    'text',
                    array(
                        'label' => 'Data',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span2 datepicker datemask-BR',
                            'data-rule-required' => true,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                        ),
                    )
                ),
                'desagenda' => array(
                    'textarea',
                    array(
                        'label' => 'Descrição',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span11',
                            'rows' => 5,
                            'cols' => 80
                        ),
                    )
                ),
                /*'idcadastrador' => array('select', array(
                        'label'        => '',
                        'required'     => true,
                        'multiOptions' => $mapperTbPessoa->fetchPairs(),
                        'filters'      => array('StringTrim','StripTags'),
                        'validators'   => array(),
                        'attribs'      => array(),
                    )),*/
                'datcadastro' => array(
                    'text',
                    array(
                        'label' => '',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(array('StringLength', false, array(0, 4))),
                        'attribs' => array(
                            'data-rule-required' => false,
                        ),
                    )
                ),
                'hragendada' => array(
                    'text',
                    array(
                        'label' => 'Hora',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 8))),
                        'attribs' => array(
                            'class' => 'span2 mask-hora',
                            'maxlength' => '8',
                            'data-rule-maxlength' => 8,
                            'data-rule-minlength' => 8,
                            'data-rule-hora' => true,
                            'placeholder' => 'HH24:MM:SS',
                        ),
                    )
                ),
                'deslocal' => array(
                    'text',
                    array(
                        'label' => 'Local',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 30))),
                        'attribs' => array(
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'flaenviaemail' => array(
                    'text',
                    array(
                        'label' => 'Enviou Email',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(),
                    )
                ),
                'idescritorio' => array(
                    'select',
                    array(
                        'label' => 'Escritório',
                        'required' => true,
                        'multiOptions' => $mapperTbEscritorio->fetchPairs(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
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

