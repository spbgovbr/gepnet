<?php

class Pessoal_Form_AtividadeEditar extends App_Form_FormAbstract
{

    public function init()
    {
        $service = new Pessoal_Service_Atividade();
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-atividade-editar",
                "elements" => array(
                    'idatividade' => array('hidden', array()),
                    'idescritorio' => array('hidden', array()),
                    'idcadastrador' => array('hidden', array()),
                    'idresponsavel' => array('hidden', array()),
                    'nomatividade' => array(
                        'text',
                        array(
                            'label' => 'Nome da Atividade',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => true
                            )
                        )
                    ),
                    'desatividade' => array(
                        'textarea',
                        array(
                            'label' => 'Descrição',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 2000))),
                            'attribs' => array(
                                'class' => 'span10',
                                'rows' => '10',
                                'data-rule-required' => true
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
                            'attribs' => array('readonly' => true),
                        )
                    ),
                    'datcadastro' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
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
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(),
                        )
                    ),
                    'datinicio' => array(
                        'text',
                        array(
                            'label' => 'Início',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'datepicker',
                                'data-rule-required' => true
                            ),
                        )
                    ),
                    'datfimmeta' => array(
                        'text',
                        array(
                            'label' => 'Fim Meta',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array('class' => 'datepicker'),
                        )
                    ),
                    'datfimreal' => array(
                        'text',
                        array(
                            'label' => 'Fim Real',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array('class' => 'datepicker'),
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
