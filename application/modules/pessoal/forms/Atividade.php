<?php

class Pessoal_Form_Atividade extends App_Form_FormAbstract
{

    public function init()
    {
        $serviceLogin = new Default_Service_Login();
        $usuario = $serviceLogin->retornaUsuarioLogado();
        $perfilAtivo = $serviceLogin->retornaPerfilAtivo();
        $service = new Pessoal_Service_Atividade();
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-atividade",
                "elements" => array(
                    'idatividade' => array('hidden', array()),
                    'idescritorio' => array('hidden', array('value' => $perfilAtivo->idescritorio)),
                    'idcadastrador' => array('hidden', array('value' => $usuario->idpessoa)),
                    'idresponsavel' => array('hidden', array()),
                    'nomatividade' => array(
                        'text',
                        array(
                            'label' => 'Nome da Atividade',
                            'required' => true,
                            'maxlength' => '100',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span5',
                                'data-rule-required' => true,
                            )
                        )
                    ),
                    'desatividade' => array(
                        'textarea',
                        array(
                            'label' => 'Descrição',
                            'required' => true,
                            'maxlength' => '2000',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 2000))),
                            'attribs' => array(
                                'class' => 'span10',
                                'rows' => '10',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'nomcadastrador' => array(
                        'text',
                        array(
                            'label' => 'Demandante',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'value' => $usuario->nome,
                            'attribs' => array('readonly' => true),
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
                    'datcadastro' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(),
                        )
                    ),
                    'datatualizacao' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(),
                        )
                    ),
                    'datinicio' => array(
                        'text',
                        array(
                            'label' => 'Início',
                            'required' => true,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker date-maskBR datemask-BR',
                                'data-rule-required' => true,
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                            ),
                        )
                    ),
                    'datfimmeta' => array(
                        'text',
                        array(
                            'label' => 'Fim Meta',
                            'required' => false,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker date-maskBR datemask-BR',
                                'data-rule-required' => false,
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                            ),
                        )
                    ),
                    'datfimreal' => array(
                        'text',
                        array(
                            'label' => 'Fim Real',
                            'required' => false,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker date-maskBR datemask-BR',
                                'data-rule-required' => false,
                                'data-rule-minlength' => 10,
                                'data-rule-maxlength' => 10,
                                'data-rule-dateITA' => true,
                            ),
                        )
                    ),
                    'flacontinua' => array(
                        'select',
                        array(
                            'label' => 'Contínua?',
                            'required' => true,
                            'multiOptions' => array(2 => 'Não', 1 => 'Sim'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array('class' => 'span1'),
                        )
                    ),
                    'numpercentualconcluido' => array(
                        'select',
                        array(
                            'label' => 'Percentual Concluído %',
                            'required' => true,
                            'multiOptions' => $service->fetchPairsPercentual(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array('class' => 'span1'),
                        )
                    ),
                    'flacancelada' => array(
                        'select',
                        array(
                            'label' => 'Cancelada?',
                            'required' => true,
                            'multiOptions' => array(2 => 'Não', 1 => 'Sim'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array('class' => 'span1'),
                        )
                    ),
                    'nomescritorio' => array(
                        'text',
                        array(
                            'label' => 'Escritório de Projetos',
                            'required' => true,
                            'value' => $perfilAtivo->nomescritorio,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array('readonly' => true),
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

        $this->getElement('nomcadastrador')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('nomresponsavel')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }


}
