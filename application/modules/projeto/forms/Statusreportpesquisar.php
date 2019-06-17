<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Projeto_Form_Statusreportpesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $servicePessoa = new Default_Service_Pessoa();
        $serviceEscritorio = new Default_Service_Escritorio();
        $servicePrograma = new Default_Service_Programa();
        $serviceStatusReport = new Projeto_Service_StatusReport();
        $serviceSituacao = new Projeto_Service_SituacaoProjeto();
        $serviceObjetivo = new Default_Service_Objetivo();
        $serviceAcao = new Default_Service_Acao();
        $serviceNatureza = new Default_Service_Natureza();
        $serviceSetor = new Default_Service_Setor();
        $serviceGerencia = new Projeto_Service_Gerencia();

        //Zend_Debug::dump($serviceSituacao->retornaSituacaoAtivo());die;

        //Chamada para Gerencias Service
        $escritorios = $serviceEscritorio->fetchPairs();
        $arrayEscritorio = $serviceEscritorio->initComboEscritorio($escritorios, "Todos");
        $arrayPrograma = $serviceGerencia->initCombo($servicePrograma->fetchPairs(), "Todos");

        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-pesquisar",
                "elements" => array(
                    'idstatusreport' => array('hidden', array()),
                    'idescritorio' => array(
                        'select',
                        array(
                            'label' => 'Escritório',
                            'required' => false,
                            'multiOptions' => $arrayEscritorio,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                            ),
                        )
                    ),
                    'idprograma ' => array(
                        'select',
                        array(
                            'label' => 'Programa',
                            'required' => false,
                            'multiOptions' => $arrayPrograma,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                            ),
                        )
                    ),
//                        'fladesatualizado' => array('checkbox', array(
//                                'label' => 'Cronograma Desatualizado',
//                                'required' => false,
//                                'filters' => array(),
                    'domstatusprojeto' => array(
                        'select',
                        array(
                            'label' => 'Status',
                            'required' => false,
                            'multiOptions' => $serviceSituacao->retornaNomeSituacaoAtivo(),
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'select2',
                            ),
                        )
                    ),
//                                'validators' => array(),
//                                'attribs' => array(),
//                            )),
                    'codobjetivo' => array(
                        'select',
                        array(
                            'label' => 'Alinhamento Estratégico',
                            'required' => false,
                            'multiOptions' => $serviceStatusReport->getOptionsObejetivo(),
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'select2',
                            ),
                        )
                    ),
                    'codacao' => array(
                        'select',
                        array(
                            'label' => '',
                            'required' => false,
                            'multiOptions' => $serviceAcao->fetchPairs(),
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'select2',
                            ),
                        )
                    ),
//                        'codnatureza' => array('select', array(
//                                'label' => 'Natureza',
//                                'required' => false,
//                                'multiOptions' => array('Todos',$serviceNatureza->fetchPairs()),
//                                'filters' => array(),
//                                'validators' => array(),
//                                'attribs' => array(),
//                            )),
//                        'codsetor' => array('select', array(
//                                'label' => 'Setor',
//                                'required' => false,
//                                'multiOptions' => array('Todos'=> '',$serviceSetor->fetchPairs()),
//                                'filters' => array(),
//                                'validators' => array(),
//                                'attribs' => array(),
//                            )),
//                        'flacopa' => array('select', array(
//                                'label' => 'Copa?',
//                                'required' => false,
//                                'multiOptions' => $serviceStatusReport->getFlaCopa(),
//                                'filters' => array(),
//                                'validators' => array(),
//                                'attribs' => array(),
//                            )),
                    'nomprojeto' => array(
                        'text',
                        array(
                            'label' => 'Título do Projeto',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'data-rule-required' => false,
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

