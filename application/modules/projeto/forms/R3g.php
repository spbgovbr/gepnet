<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Projeto_Form_R3g extends App_Form_FormAbstract
{

    public function init()
    {
        $serviceR3g = App_Service_ServiceAbstract::getService('Projeto_Service_R3g');
        $arrayTipo = $serviceR3g->getTipo();
        $arrayPrazoProjeto = $serviceR3g->getPrazoProjeto();
        $arrayStatusContramedida = $serviceR3g->getStatusContramedida();
        $arrayContramedidaEfetiva = $serviceR3g->getEfetiva();

        $this->setOptions(array(
            "method" => "post",
            "id" => "form-r3g",
            "elements" => array(
                'idr3g' => array('hidden', array()),
                'idprojeto' => array(
                    'hidden',
                    array(
                        'label' => '',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(),
                    )
                ),
                'datdeteccao' => array(
                    'text',
                    array(
                        'label' => 'Data Detecção',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span2 datepicker datemask-BR',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'domtipo' => array(
                    'select',
                    array(
                        'label' => 'Ação',
                        'required' => true,
                        'multiOptions' => $arrayTipo,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'select2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'desplanejado' => array(
                    'textarea',
                    array(
                        'label' => 'Planejado',
                        'required' => true,
                        'maxlength' => '4000',
                        'wrap' => 'hard',
                        'style' => 'word-wrap: break-word; word-break: break-all;',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                        'attribs' => array(
                            'rows' => 5,
                            'cols' => 80,
                            'class' => 'span5',
                            'data-rule-required' => true,
                            'data-rule-maxlength' => 4000,
                        ),
                    )
                ),
                'desrealizado' => array(
                    'textarea',
                    array(
                        'label' => 'Realizado',
                        'required' => true,
                        'maxlength' => '4000',
                        'wrap' => 'hard',
                        'style' => 'word-wrap: break-word; word-break: break-all;',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                        'attribs' => array(
                            'rows' => 5,
                            'cols' => 80,
                            'class' => 'span5',
                            'data-rule-required' => true,
                            'data-rule-maxlength' => 4000,
                        ),
                    )
                ),
                'descausa' => array(
                    'textarea',
                    array(
                        'label' => 'Causa',
                        'required' => true,
                        'maxlength' => '4000',
                        'wrap' => 'hard',
                        'style' => 'word-wrap: break-word; word-break: break-all;',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                        'attribs' => array(
                            'rows' => 5,
                            'cols' => 80,
                            'class' => 'span5',
                            'data-rule-required' => true,
                            'data-rule-maxlength' => 4000,
                        ),
                    )
                ),
                'desconsequencia' => array(
                    'textarea',
                    array(
                        'label' => 'Consequência',
                        'required' => true,
                        'maxlength' => '4000',
                        'wrap' => 'hard',
                        'style' => 'word-wrap: break-word; word-break: break-all;',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                        'attribs' => array(
                            'rows' => 5,
                            'cols' => 80,
                            'class' => 'span5',
                            'data-rule-required' => true,
                            'data-rule-maxlength' => 4000,
                        ),
                    )
                ),
                'descontramedida' => array(
                    'textarea',
                    array(
                        'label' => 'Contramedida',
                        'required' => false,
                        'maxlength' => '4000',
                        'wrap' => 'hard',
                        'style' => 'word-wrap: break-word; word-break: break-all;',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                        'attribs' => array(
                            'rows' => 5,
                            'cols' => 80,
                            'class' => 'span10',
                            'data-rule-maxlength' => 4000,
                        ),
                    )
                ),
                'datprazocontramedida' => array(
                    'text',
                    array(
                        'label' => 'Prazo',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span2 datepicker datemask-BR',
                            'data-rule-required' => false,
                        ),
                    )
                ),
                'datprazocontramedidaatraso' => array(
                    'text',
                    array(
                        'label' => 'Tendência/Real',
                        'required' => false,
                        'maxlength' => '10',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span2 datepicker datemask-BR',
                            'data-rule-required' => false,
                        ),
                    )
                ),
                'domcorprazoprojeto' => array(
                    'select',
                    array(
                        'label' => 'Prazo Projeto',
                        'required' => true,
                        'multiOptions' => $arrayPrazoProjeto,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'select2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'domstatuscontramedida' => array(
                    'select',
                    array(
                        'label' => 'Status Contramedida',
                        'required' => false,
                        'multiOptions' => $arrayStatusContramedida,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'select2',
                            'data-rule-required' => false,
                        ),
                    )
                ),
                'flacontramedidaefetiva' => array(
                    'select',
                    array(
                        'label' => 'Efetiva?',
                        'required' => false,
                        'multiOptions' => $arrayContramedidaEfetiva,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'select2',
                            'data-rule-required' => false,
                        ),
                    )
                ),
                'idcadastrador' => array(
                    'text',
                    array(
                        'label' => '',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(),
                    )
                ),
                'idresponsavel' => array(
                    'hidden',
                    array(
                        'label' => 'Responsável',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(),
                    )
                ),
                'datcadastro' => array(
                    'text',
                    array(
                        'label' => 'Data de Cadastro',
                        'required' => false,
                        'maxlength' => '10',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                        'attribs' => array(),
                    )
                ),
                'desresponsavel' => array(
                    'text',
                    array(
                        'label' => 'Responsável',
                        'required' => false,
                        'maxlength' => '100',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span2',
                        ),
                    )
                ),
                'desobs' => array(
                    'textarea',
                    array(
                        'label' => 'Obs',
                        'required' => false,
                        'maxlength' => '100',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array('rows' => 24, 'cols' => 80),
                    )
                ),
                'pessoabutton' => array(
                    'button',
                    array(
                        'label' => '',
                        'ignore' => true,
                        'icon' => 'user',
                        'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                        'escape' => true,
                        'attribs' => array(
                            'class' => 'pessoa-button',
                            'type' => 'button',
                        )
                    )
                ),
//                'submit' => array('button', array(
//                    'ignore' => true,
//                    'label' => 'Salvar',
//                    'escape' => false,
//                    'attribs' => array(
//                        'id' => 'submitbutton',
//                        'type' => 'submit',
//                    ),
//                )),


            )

        ));
        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
//        $this->getElement('submit')
//            ->removeDecorator('label')
//            ->removeDecorator('HtmlTag')
//            ->removeDecorator('Wrapper');

    }


}

