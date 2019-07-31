<?php

class Diagnostico_Form_Diagnostico extends App_Form_FormAbstract
{

    public function init()
    {
        /** Chamada para Default Service */
        $serviceDiagnostico = new Diagnostico_Service_Diagnostico();
        $servicePessoa = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $fetchPairPessoa = $servicePessoa->fetchPairs();

        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-gerencia",
                "elements" => array(
                    'iddiagnostico' => array('hidden', array()),
                    'idcadastrador' => array('hidden', array()),
                    'idchefedaunidade' => array('hidden', array()),

                    'qualificacaoChefedaUnidade' => array(
                        'hidden',
                        array(
                            'value' => 1
                        )
                    ),
                    'idpontofocal' => array('hidden', array()),
                    'qualificacaoPontoFocal' => array(
                        'hidden',
                        array(
                            'value' => 2
                        )
                    ),
                    'idunidade' => array('hidden', array()),
                    'idequipe' => array('hidden', array()),
                    'qualificacaoEquipe' => array(
                        'hidden',
                        array(
                            'value' => 3
                        )
                    ),

                    'dsdiagnostico' => array(
                        'text',
                        array(
                            'label' => 'Nome do Diagnóstico',
                            'required' => true,
                            'maxlength' => '100',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span3',
                                'data-rule-required' => true,
                                'readonly' => true
                            ),
                        )
                    ),

                    'idunidadeprincipal' => array(
                        'select',
                        array(
                            'label' => 'Unidade Principal',
                            'required' => true,
                            'multiOptions' => array('' => 'Selecione') + $serviceDiagnostico->getListarUnidadePrincipalFetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => false,
                            ),
                        )
                    ),

                    'idpessoa' => array(
                        'hidden',
                        array(
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'readonly' => true,
                                'data-rule-required' => true,
                            ),
                        )
                    ),

                    'chefedaunidade' => array(
                        'text',
                        array(
                            'label' => 'Chefe da Unidade Diagnosticada',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'readonly' => true,
                                'class' => 'span2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'pontofocal' => array(
                        'text',
                        array(
                            'label' => 'Ponto Focal da Unidade Diagnosticada',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'readonly' => true,
                                'class' => 'span2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),

                    'dtinicio' => array(
                        'text',
                        array(
                            'label' => 'Data Início',
                            'required' => true,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'span2 datepicker datemask-BR',
                                'data-rule-required' => true,
                                'data-rule-dateITA' => true,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                    'dtencerramento' => array(
                        'text',
                        array(
                            'label' => 'Data Fim',
                            'required' => true,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'span2 datepicker datemask-BR',
                                'data-rule-required' => true,
                                'data-rule-dateITA' => true,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                    'incluir' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Incluir Pessoas Interessadas',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'incluirbutton',
                            ),
                        )
                    ),
                    'pessoas' => array(
                        'select',
                        array(
                            'label' => 'Selecionar Pessoas',
                            'required' => false,
                            'multiOptions' => array(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => false,
                                'size' => 7,
                                'multiple' => 'multiple'
                            ),
                        )
                    ),
                    'pessoasEquipe' => array(
                        'select',
                        array(
                            'label' => 'Equipe do Diagnóstico',
                            'required' => true,
                            'multiOptions' => array(),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span4',
                                'data-rule-required' => false,
                                'size' => 7,
                                'multiple' => 'multiple'
                            ),
                        )
                    ),

                    'enviar' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Salvar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'enviarbutton',
                                'type' => 'button',
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
                                'type' => 'button',
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

        $this->getElement('chefedaunidade')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('enviar')
            ->removeDecorator('label')
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

        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}
