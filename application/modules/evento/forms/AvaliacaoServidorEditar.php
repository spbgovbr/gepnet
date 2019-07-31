<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Evento_Form_AvaliacaoServidorEditar extends App_Form_FormAbstract
{

    public function init()
    {
        $serviceEvento = new Evento_Service_Grandeseventos();
        $serviceLogin = new Default_Service_Login();
        $service = new Evento_Service_Avaliacaoservidor();
        $comboEventos = $serviceEvento->fetchPairs();
        $comboTipoAvaliacao = $service->fetchPairsTipoAvaliacao();
        $usuario = $serviceLogin->retornaUsuarioLogado();

        $this
            ->setOptions(array(
                "name" => "form-avaliacao-editar",
                "id" => "form-avaliacao-editar",
                "method" => "post",
                "elements" => array(
                    'ideventoavaliacao' => array('hidden', array()),
                    'idavaliado' => array('hidden', array()),
                    'idavaliador' => array('hidden', array('value' => $usuario->idpessoa)),
                    'idevento' => array(
                        'select',
                        array(
                            'label' => 'Evento',
                            'required' => true,
                            'multioptions' => $serviceEvento->initCombo($comboEventos, 'Selecione'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 3))),
                            'attribs' => array(
                                'class' => 'span3',
                                'data-rule-required' => true
                            ),
                        )
                    ),
                    'desdestaqueservidor' => array(
                        'textarea',
                        array(
                            'label' => 'O servidor destacou-se positivamente no cumprimento de alguma tarefa/missão? Em caso positivo, qual?',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 1000))),
                            'attribs' => array(
                                'class' => 'span10',
                                'rows' => 5,
                                'data-rule-required' => false
                            ),
                        )
                    ),
                    'desobs' => array(
                        'textarea',
                        array(
                            'label' => 'Observações',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 1000))),
                            'attribs' => array(
                                'class' => 'span10',
                                'rows' => 5,
                                'data-rule-required' => false
                            ),
                        )
                    ),
                    'nomavaliado' => array(
                        'text',
                        array(
                            'label' => 'Servidor Avaliado',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'data-rule-required' => true,
                                'readonly' => 'readonly'
                            ),
                        )
                    ),
                    'numnotaavaliador' => array(
                        'select',
                        array(
                            'label' => 'Qual a NOTA GERAL que você daria para o Servidor Avaliado? ',
                            'required' => true,
                            'multioptions' => array(
                                0 => '0',
                                1 => '1',
                                2 => '2',
                                3 => '3',
                                4 => '4',
                                5 => '5',
                                6 => '6',
                                7 => '7',
                                8 => '8',
                                9 => '9',
                                10 => '10'
                            ),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array('data-rule-required' => true),
                        )
                    ),
                    'idtipoavaliacao' => array(
                        'select',
                        array(
                            'label' => 'Tipo de Avaliação',
                            'required' => true,
                            'multioptions' => $serviceEvento->initCombo($comboTipoAvaliacao, 'Selecione'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 3))),
                            'attribs' => array(
                                'class' => 'span3',
                                'data-rule-required' => true
                            ),
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


        $this->getElement('nomavaliado')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('voltar')
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
        $this->getElement('close')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }


}

