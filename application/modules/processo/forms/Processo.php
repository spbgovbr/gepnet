<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Processo_Form_Processo extends App_Form_FormAbstract
{

    public function init()
    {
        $serviceProcesso = App_Service_ServiceAbstract::getService('Processo_Service_Processo');
        $ArrayProcesso = $serviceProcesso->initCombo($serviceProcesso->fetchPairs(), 'Selecione');
        $servicePessoa = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $serviceSetor = App_Service_ServiceAbstract::getService('Default_Service_Setor');

        $this->setOptions(array(
            "method" => "post",
            "id" => "form-processo",
            "elements" => array(
                'idprocesso' => array('hidden', array()),
                'idprocessopai' => array(
                    'select',
                    array(
                        'label' => 'Processo Pai',
                        'required' => true,
                        //'multiOptions' => $serviceProcesso->fetchPairs(),
                        'multiOptions' => $ArrayProcesso,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span3 select2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'nomprocesso' => array(
                    'text',
                    array(
                        'label' => 'Nome do Processo',
                        'required' => true,
                        'maxlength' => 100,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span9',
                            'data-rule-required' => true,
                            'data-rule-maxlength' => 100,
                        ),
                    )
                ),
                'nomcodigo' => array(
                    'text',
                    array(
                        'label' => 'Código',
                        'required' => true,
                        'maxlength' => 20,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                        'attribs' => array(
                            'data-rule-required' => true,
                            'data-rule-maxlength' => 20,
                        ),
                    )
                ),
                'idsetor' => array(
                    'select',
                    array(
                        'label' => 'Unidade',
                        'required' => true,
                        'multiOptions' => $serviceSetor->fetchPairs(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span3 select2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'desprocesso' => array(
                    'textarea',
                    array(
                        'label' => 'Descrição',
                        'required' => false,
                        'maxlength' => 1000,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1000))),
                        'attribs' => array(
                            'class' => 'span10',
                            'rows' => '10',
                            'data-rule-maxlength' => 1000,
                        ),
                    )
                ),
                'iddono' => array(
                    'hidden',
                    array(
                        //'label'      => 'Dono',
                        'required' => true,
                        //'append'    => $pessoaText,
                        //'multiOptions' => $pessoaFetchPairs,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(
                            'readonly' => true,
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'nomdono' => array(
                    'text',
                    array(
                        'label' => 'Dono',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(
                            'readonly' => true,
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idexecutor' => array(
                    'hidden',
                    array(
                        //'label'        => 'Executor',
                        'required' => true,
                        //'append' => $pessoaButton,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(
                            'readonly' => true,
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'nomexecutor' => array(
                    'text',
                    array(
                        'label' => 'Executor',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(
                            'readonly' => true,
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idgestor' => array(
                    'hidden',
                    array(
                        // 'label'        => 'Gestor',
                        'required' => true,
                        //'append' => $pessoaButton,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(
                            'readonly' => true,
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'nomgestor' => array(
                    'text',
                    array(
                        'label' => 'Gestor',
                        'required' => true,
                        //'append' => $pessoaButton,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(
                            'readonly' => true,
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idconsultor' => array(
                    'hidden',
                    array(
                        //'label'        => 'Consultor',
                        'required' => true,
                        //'append' => $pessoaButton,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(
                            'readonly' => true,
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'nomconsultor' => array(
                    'text',
                    array(
                        'label' => 'Consultor',
                        'required' => true,
                        // 'append' => $pessoaButton,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(
                            'readonly' => true,
                            'class' => 'span2',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'numvalidade' => array(
                    'text',
                    array(
                        'label' => 'Validade ( meses )',
                        'required' => true,
                        'maxlength' => 8,
                        'filters' => array('Digits', 'StringTrim', 'StripTags'),
                        //'validators' => array('Digits', 'NotEmpty'),
                        'validators' => array('NotEmpty', array('Digits')),
                        'attribs' => array(
                            'data-rule-number' => true,
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'datatualizacao' => array(
                    'text',
                    array(
                        'label' => 'Data da Última Atualização',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                        'attribs' => array(),
                    )
                ),
                'idcadastrador' => array(
                    'text',
                    array(
                        'label' => '',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(),
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
                        //'label' => 'Limpar',
                        'escape' => true,
                        'attribs' => array(
                            'class' => 'pessoa-button',
                            'type' => 'button',
                        )
                    )
                ),
            )
        ));


        $this->getElement('nomdono')
            //->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('nomgestor')
            //->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('nomconsultor')
            //->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('nomexecutor')
            //->removeDecorator('label')
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

