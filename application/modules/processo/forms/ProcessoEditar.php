<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Processo_Form_ProcessoEditar extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbProcesso = App_Service_ServiceAbstract::getService('Processo_Service_Processo');
        $mapperTbPessoa = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $mapperTbSetor = App_Service_ServiceAbstract::getService('Default_Service_Setor');
        $fetchpairspessoa = $mapperTbPessoa->fetchPairs();
//         $fetchpairspessoa = array();
        $this->setOptions(array(
            "method" => "post",
            "id" => "form-processo-editar",
            "elements" => array(
                'idprocesso' => array('hidden', array()),
                'idprocessopai' => array(
                    'select',
                    array(
                        'label' => 'Processo Pai',
                        'required' => false,
                        'multiOptions' => $mapperTbProcesso->fetchPairs(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(),
                    )
                ),
                'nomcodigo' => array(
                    'text',
                    array(
                        'label' => 'Código',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                        'attribs' => array(
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'nomprocesso' => array(
                    'text',
                    array(
                        'label' => 'Nome do Processo',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'idsetor' => array(
                    'select',
                    array(
                        'label' => 'Unidade',
                        'required' => true,
                        'multiOptions' => $mapperTbSetor->fetchPairs(),
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
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span10',
                            'rows' => '10',
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
                        'label' => 'Validade',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags', 'Digits'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'span1',
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
                        'label' => 'Enviar',
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

