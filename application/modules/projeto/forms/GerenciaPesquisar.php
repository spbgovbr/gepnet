<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_GerenciaPesquisar extends App_Form_FormAbstract
{

    public function init()
    {

        //Chamada para Default Service
        $serviceEscritorio = new Default_Service_Escritorio();
        $fetchPairEscritorio = $serviceEscritorio->fetchPairs();
        $servicePrograma = App_Service_ServiceAbstract::getService('Default_Service_Programa');
        $fetchPairPrograma = $servicePrograma->fetchPairs();
        $serviceNatureza = App_Service_ServiceAbstract::getService('Default_Service_Natureza');
        $fetchPairNatureza = $serviceNatureza->fetchPairs();
        $serviceSetor = App_Service_ServiceAbstract::getService('Default_Service_Setor');
        $fetchPairSetor = $serviceSetor->fetchPairs();
        $serviceStatusReport = new Projeto_Service_StatusReport();
        $fetchPairObjetivo = $serviceStatusReport->getOptionsObejetivo();

        //Chamada para Projeto Service
        $serviceGerencia = new Projeto_Service_Gerencia();
        $serviceSituacao = new Projeto_Service_SituacaoProjeto();

        //Preparando Combo
        $arrayEscritorio = $serviceGerencia->initComboEscritorio($fetchPairEscritorio, "Todos");
        $arrayPrograma = $serviceGerencia->initCombo($fetchPairPrograma, "Todos");
        $arrayNomeSituacaoAtivo = $serviceSituacao->retornaNomeSituacaoAtivo();
        $arraySetor = $serviceGerencia->initCombo($fetchPairSetor, "Todos");
        $arrayObjetivo = $serviceGerencia->initCombo($fetchPairObjetivo, "Todos");
        $arrayNatureza = $serviceGerencia->initCombo($fetchPairNatureza, "Todos");

        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-pesquisar",
                "elements" => array(
                    'idescritorio' => array(
                        'multiselect',
                        array(
                            'label' => 'Escritório',
                            'required' => false,
                            'multiOptions' => $arrayEscritorio,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
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
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'domstatusprojeto' => array(
                        'select',
                        array(
                            'label' => 'Status',
                            'required' => false,
                            'multiOptions' => $arrayNomeSituacaoAtivo,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'nomareaexecutora' => array(
                        'select',
                        array(
                            'label' => 'Área Executora',
                            'required' => false,
                            'multiOptions' => $arraySetor,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'nomprojeto' => array(
                        'text',
                        array(
                            'label' => 'Título do projeto',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(),
                        )
                    ),
                    'codobjetivo' => array(
                        'select',
                        array(
                            'label' => 'Alinhamento Estratégico',
                            'required' => false,
                            'multiOptions' => $arrayObjetivo,
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'select2',
                                'id' => 'codobjetivo',
                            ),
                        )
                    ),
                    'codacao' => array(
                        'multiselect',
                        array(
                            'label' => 'Ação Estratégica',
                            'required' => false,
                            //multiOptions' => $serviceAcao->fetchPairs(),
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'select2',
                                'id' => 'codacao',
                            ),
                        )
                    ),

                    'acompanhamento' => array(
                        'select',
                        array(
                            'label' => 'Último Acompanhamento',
                            'required' => false,
                            //'multiOptions' => $arrayAcompanhamento,
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'select2',
                                'id' => 'acompanhamento',
                            ),
                        )
                    ),

                    'nomnatureza' => array(
                        'select',
                        array(
                            'label' => 'Natureza',
                            'required' => false,
                            'multiOptions' => $arrayNatureza,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
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

