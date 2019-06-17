<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_RelatorioCronograma extends App_Form_FormAbstract
{

    public function init()
    {


        //Chamada para Default Service
        $serviceEscritorio = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $fetchPairEscritorio = $serviceEscritorio->fetchPairs();
        $servicePrograma = App_Service_ServiceAbstract::getService('Default_Service_Programa');
        $fetchPairPrograma = $servicePrograma->fetchPairs();
        $serviceElementoDespesa = new Default_Service_ElementoDespesa();
        $fetchPairElementoDespesa = $serviceElementoDespesa->fetchPairs();
        $serviceNaturezas = App_Service_ServiceAbstract::getService('Default_Service_Natureza');
        $fetchPairNatureza = $serviceNaturezas->fetchPairs();

        //Chamada para Gerencias Service
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $serviceStatusReport = new Projeto_Service_StatusReport();

        //Preparando Combos para seleção
        $arrayEscritorio = $serviceGerencia->initCombo($fetchPairEscritorio, "Todos");
        $arrayPrograma = $serviceGerencia->initCombo($fetchPairPrograma, "Todos");

        $arrayNatureza = $serviceGerencia->initCombo($fetchPairNatureza, "Todos");

        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "rel-cronograma",
                "elements" => array(
                    'idprojeto' => array('hidden', array()),
                    'idresponsavel' => array('hidden', array()),
                    'nomprojeto' => array(
                        'text',
                        array(
                            'label' => 'Nome do Projeto',
                            'required' => false,
                            'maxlength' => '100',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span3',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'idprograma' => array(
                        'select',
                        array(
                            'label' => 'Programa',
                            'required' => false,
                            'multiOptions' => $arrayPrograma,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'idescritorio' => array(
                        'select',
                        array(
                            'label' => 'Escritório',
                            'required' => false,
                            'multiOptions' => $arrayEscritorio,
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'idprojetos' => array(
                        'select',
                        array(
                            'label' => 'Projetos',
                            'required' => false,
                            'multiOptions' => array(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'multiple' => 'multiple',
                                'class' => 'select2',
                                'data-rule-required' => false,
                                'style' => "width: 390px; height: 140px;"

                            ),
                        )
                    ),
                    'idnaturezas' => array(
                        'select',
                        array(
                            'label' => 'Natureza',
                            'required' => false,
                            'multiOptions' => $arrayNatureza,
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'multiple' => 'multiple',
                                'class' => 'select2',
                                'data-rule-required' => false,
                                'style' => "width: 390px; height: 140px;"

                            ),
                        )
                    ),
                    'inicial_dti' => array(
                        'text',
                        array(
                            'label' => 'Início',
                            'required' => false,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker date-maskBR datemask-BR',
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                            ),
                        )
                    ),
                    'inicial_dtf' => array(
                        'text',
                        array(
                            'label' => 'Fim',
                            'required' => false,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker date-maskBR datemask-BR',
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                            ),
                        )
                    ),
                    'final_dti' => array(
                        'text',
                        array(
                            'label' => 'Início',
                            'required' => false,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker date-maskBR datemask-BR',
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                            ),
                        )
                    ),
                    'final_dtf' => array(
                        'text',
                        array(
                            'label' => 'Fim',
                            'required' => false,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker date-maskBR datemask-BR',
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                            ),
                        )
                    ),
                    'domstatusprojeto' => array(
                        'select',
                        array(
                            'label' => 'Status do Projeto',
                            'required' => false,
                            'multiOptions' => $serviceStatusReport->getStatus(),
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'span2',
                            ),
                        )
                    ),
                    'tipoatividade' => array(
                        'checkbox',
                        array(
                            'label' => 'Atividade',
                            'required' => false,
                            'setCheckedValue' => 1,
                            'setUncheckedValue' => 0,
                            'attribs' => array(
                                'class' => 'span1',
                                'data-rule-required' => false,
                                'checked' => 'checked'
                            ),
                        )
                    ),
                    'tipomarco' => array(
                        'checkbox',
                        array(
                            'label' => 'Marco',
                            'required' => false,
                            'setCheckedValue' => 1,
                            'setUncheckedValue' => 0,
                            'attribs' => array(
                                'class' => 'span1',
                                'data-rule-required' => false,
                                'checked' => 'checked'
                            ),
                        )
                    ),
                    'tipogrupo' => array(
                        'checkbox',
                        array(
                            'label' => 'Grupo',
                            'required' => false,
                            'setCheckedValue' => 1,
                            'setUncheckedValue' => 0,
                            'attribs' => array(
                                'class' => 'span1',
                                'data-rule-required' => false,
                                'checked' => 'checked'
                            ),
                        )
                    ),
                    'tipoentrega' => array(
                        'checkbox',
                        array(
                            'label' => 'Entrega',
                            'required' => false,
                            'setCheckedValue' => 1,
                            'setUncheckedValue' => 0,
                            'attribs' => array(
                                'class' => 'span1',
                                'data-rule-required' => false,
                                'checked' => 'checked'
                            ),
                        )
                    ),
                    'statusatividade' => array(
                        'select',
                        array(
                            'label' => 'Status da Atividade',
                            'multiOptions' => array(
                                'T' => 'Todas',
                                'A' => 'Atrasada',
                                100 => 'Concluído',
                                50 => 'Em andamento',
                                'C' => 'Cancelada'
                            ),
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2',
                                'style' => 'width:164px;'
                            ),
                        )
                    ),
                    'nomresponsavel' => array(
                        'text',
                        array(
                            'label' => 'Responsável pela Atividade',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'data-rule-required' => false,
                                'disabled' => 'disabled'
                            ),
                        )
                    ),
                    'idelementodespesa' => array(
                        'select',
                        array(
                            'label' => 'Elemento de Despesa da Atividade',
                            'required' => false,
                            'multiOptions' => $fetchPairElementoDespesa,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span3',
                                //'data-rule-custo' => true,
                                //'data-rule-required'  => false,
                            ),
                        )
                    ),
                    'submit' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Pesquisar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'submitbutton',
                                'name' => 'submitbutton',
                                'type' => 'submit',
                            ),
                        )
                    ),
                    'reset' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'icon' => 'th',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
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
                            'escape' => true,
                            'attribs' => array(
                                'class' => 'pessoa-button',
                                'type' => 'button',
                            )
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
        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}

