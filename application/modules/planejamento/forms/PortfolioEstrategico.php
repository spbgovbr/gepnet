<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Planejamento_Form_PortfolioEstrategico extends App_Form_FormAbstract
{

    public function init()
    {

        //Chamada para Default Service
        $serviceEscritorio = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $fetchPairEscritorio = $serviceEscritorio->fetchPairs();
        $servicePrograma = App_Service_ServiceAbstract::getService('Default_Service_Programa');
        $fetchPairPrograma = $servicePrograma->fetchPairs();
        $serviceNatureza = App_Service_ServiceAbstract::getService('Default_Service_Natureza');
        $fetchPairNatureza = $serviceNatureza->fetchPairs();
        $serviceSetor = App_Service_ServiceAbstract::getService('Default_Service_Setor');
        $fetchPairSetor = $serviceSetor->fetchPairs();
        $serviceObjetivo = App_Service_ServiceAbstract::getService('Default_Service_Objetivo');
        $fetchPairObjetivo = $serviceObjetivo->fetchPairs();
        $serviceAcao = App_Service_ServiceAbstract::getService('Default_Service_Acao');
        $fetchPairAcao = $serviceAcao->fetchPairs();

        //Chamada para Projeto Service
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');

        //Preparando Combo
        //$arrayEscritorio = $serviceGerencia->initCombo($fetchPairEscritorio, "Todos");
        $arrayPrograma = $serviceGerencia->initCombo($fetchPairPrograma, "Todos");
        $arraySetor = $serviceGerencia->initCombo($fetchPairSetor, "Todos");
        $arrayAcao = $serviceGerencia->initCombo($fetchPairAcao, "Todos");
        $arrayObjetivo = $serviceGerencia->initCombo($fetchPairObjetivo, "Todos");
        $arrayNatureza = $serviceGerencia->initCombo($fetchPairNatureza, "Todos");


        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-pesquisar-projeto",
                "name" => "form-pesquisar-projeto",
                "elements" => array(
                    'idescritorio' => array(
                        'select',
                        array(
                            'label' => 'Escritório',
                            'required' => false,
                            'multiOptions' => array('Todos', $fetchPairEscritorio),
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
                            'multiOptions' => array(
                                '' => 'Todos',
                                'Proposta' => 'Proposta',
                                'Em Andamento' => 'Em Andamento',
                                'Paralisado' => 'Paralisado',
                                'Cancelado' => 'Cancelado',
                                'Concluido' => 'Concluido',
                            ),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'cronogramadesatualizado' => array(
                        'checkbox',
                        array(
                            'label' => 'Cronograma Desatualizado:',
                            'required' => false,
                            'setCheckedValue' => 'S',
                            'setUncheckedValue' => 'N'
                        )
                    ),
                    'idsetor' => array(
                        'select',
                        array(
                            'label' => 'Escritorio',
                            'required' => false,
                            'multiOptions' => $arraySetor,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2 span2',
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
                    'idobjetivo' => array(
                        'select',
                        array(
                            'label' => 'Alinhamento Estratégico',
                            'required' => false,
                            'multiOptions' => $arrayObjetivo,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'idacao' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => false,
                            'multiOptions' => $arrayAcao,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'idnatureza' => array(
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
                    'flacopa' => array(
                        'select',
                        array(
                            'label' => 'Copa',
                            'required' => false,
                            'multiOptions' => array('' => 'Todos', 'S' => 'Sim', 'N' => 'Não'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2 span2',
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
                    'voltar' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Voltar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'voltar',
                                'type' => 'button',
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
        $this->getElement('voltar')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('close')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}

