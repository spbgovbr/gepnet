<?php

class Pessoal_Form_AtividadeRelatorio extends App_Form_FormAbstract
{

    public function init()
    {
        $service = new Pessoal_Service_Atividade;
        $serviceEscritorio = new Default_Service_Escritorio;
        $fetchPairEscritorio = $serviceEscritorio->fetchPairs();

        $arrayEscritorio = $service->initCombo($fetchPairEscritorio, "Todos");


        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-atividade-relatorio",
                "elements" => array(
                    'idresponsavel' => array('hidden', array()),
                    'nomatividade' => array(
                        'text',
                        array(
                            'label' => 'Nome da Atividade',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array('class' => 'span3')
                        )
                    ),
                    'nomresponsavel' => array(
                        'text',
                        array(
                            'label' => 'Responsável',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array('class' => 'span3',),
                        )
                    ),
                    'inicioperiodo' => array(
                        'text',
                        array(
                            'label' => 'Início',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker',
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10
                            ),
                        )
                    ),
                    'fimperiodo' => array(
                        'text',
                        array(
                            'label' => 'Fim',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker',
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10
                            ),
                        )
                    ),
                    'concluida' => array(
                        'select',
                        array(
                            'label' => 'Concluída?',
                            'required' => false,
                            'multiOptions' => array('T' => 'Todas', 2 => 'Não', 1 => 'Sim'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array('class' => 'span1'),
                        )
                    ),
                    'flacontinua' => array(
                        'select',
                        array(
                            'label' => 'Contínua?',
                            'required' => false,
                            'multiOptions' => array('T' => 'Todas', 2 => 'Não', 1 => 'Sim'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array('class' => 'span1'),
                        )
                    ),
                    'flacancelada' => array(
                        'select',
                        array(
                            'label' => 'Cancelada?',
                            'required' => false,
                            'multiOptions' => array('T' => 'Todas', 2 => 'Não', 1 => 'Sim'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array('class' => 'span1'),
                        )
                    ),
                    'idescritorio' => array(
                        'select',
                        array(
                            'label' => 'Escritório de Projetos',
                            'required' => false,
                            'multiOptions' => $arrayEscritorio,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
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

        $this->getElement('nomresponsavel')
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
        $this->getElement('close')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

    }


}
