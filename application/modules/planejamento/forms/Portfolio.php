<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Planejamento_Form_Portfolio extends App_Form_FormAbstract
{

    public function init()
    {


        $service = new Planejamento_Service_Portfolio();
        $serviceEscritorio = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $servicePrograma = App_Service_ServiceAbstract::getService('Default_Service_Programa');

        $fetchPairEscritorio = $serviceEscritorio->fetchPairs();
        $arrayEscritorio = $service->initCombo($fetchPairEscritorio, "Selecione");
        //$fetchPairPrograma = $servicePrograma->fetchPairsProgramaSemPortfolio();
        // $arrayPrograma = $service->initCombo($fetchPairPrograma, "Selecione");
        $fetchPairPortfolio = $service->fetchPairs();
        $arrayPortfolio = $service->initCombo($fetchPairPortfolio, "Selecione");

        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-portfolio",
                "name" => "form-portfolio",
                "elements" => array(
                    'idportfolio' => array('hidden', array()),
                    'idresponsavel' => array('hidden', array()),
                    'noportfolio' => array(
                        'text',
                        array(
                            'label' => 'Nome do Portfólio',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'data-rule-required' => true,
                                'class' => 'span5'
                            ),
                        )
                    ),
                    'idportfoliopai' => array(
                        'select',
                        array(
                            'label' => 'Portfólio Pai',
                            'required' => false,
                            'multiOptions' => $arrayPortfolio,
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
                            'required' => true,
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
                        'multiselect',
                        array(
                            'label' => 'Programa',
                            'required' => true,
                            'multiOptions' => array(),//$arrayPrograma,
                            'filters' => array('StringTrim', 'StripTags'),
                            //'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'style' => 'width: 460px; height: 150px',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'tipo' => array(
                        'select',
                        array(
                            'label' => 'Tipo',
                            'required' => true,
                            'multiOptions' => array(
                                '' => 'Selecione',
                                1 => 'Normal',
                                2 => 'Estratégico',
                            ),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'ativo' => array(
                        'select',
                        array(
                            'label' => 'Ativo',
                            'required' => true,
                            'multiOptions' => array(
                                '' => 'Selecione',
                                'S' => 'Sim',
                                'N' => 'Não'
                            ),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'nomresponsavel' => array(
                        'text',
                        array(
                            'label' => 'Responsável',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array('data-rule-required' => true),
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
                    'pesquisar' => array(
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
                    'reset' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Limpar',
                            'escape' => false,
                            'icon' => 'th',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
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

        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('pesquisar')
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

