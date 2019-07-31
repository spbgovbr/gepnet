<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Processo_Form_PAcaoPesquisar extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbProjetoprocesso = new Processo_Model_Mapper_Projetoprocesso();
        $mapperTbPAcao = new Processo_Model_Mapper_PAcao();
        $mapperTbSetor = new Default_Model_Mapper_Setor();
        $mapperTbPessoa = new Default_Model_Mapper_Pessoa();
        $this->setOptions(array(
            "method" => "post",
            "id" => "form-pacao-pesquisar",
            "elements" => array(
                'id_p_acao' => array('hidden', array()),
                'numseq' => array('hidden', array()),
                'idprojetoprocesso' => array('hidden', array()),
                'nom_p_acao' => array(
                    'text',
                    array(
                        'label' => 'Ação',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                        'attribs' => array(
                            'class' => 'span9',
                        ),
                    )
                ),
                'des_p_acao' => array(
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
                'datinicioprevisto' => array(
                    'text',
                    array(
                        'label' => 'Data Início Previsto',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'datepicker',
                        ),
                    )
                ),
                'datinicioreal' => array(
                    'text',
                    array(
                        'label' => 'Data Início Real',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'datepicker',
                        ),
                    )
                ),
                'datterminoprevisto' => array(
                    'text',
                    array(
                        'label' => 'Data Término Previsto',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'datepicker',
                        ),
                    )
                ),
                'datterminoreal' => array(
                    'text',
                    array(
                        'label' => 'Data Término Real',
                        'required' => false,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'datepicker',
                        ),
                    )
                ),
                'idsetorresponsavel' => array(
                    'select',
                    array(
                        'label' => 'Setor Responsável',
                        'required' => false,
                        'multiOptions' => $mapperTbSetor->fetchPairs(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(),
                    )
                ),
                'flacancelada' => array(
                    'select',
                    array(
                        'label' => 'Cancelada?',
                        'required' => true,
                        'multiOptions' => $mapperTbPAcao->fetchCancelada(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(),
                    )
                ),
                'idcadastrador' => array(
                    'text',
                    array(
                        'label' => '',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(),
                    )
                ),
                'datcadastro' => array(
                    'text',
                    array(
                        'label' => 'Data Cadastro',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(),
                    )
                ),
                'idresponsavel' => array(
                    'select',
                    array(
                        'label' => 'Responsável',
                        'required' => false,
                        'multiOptions' => $mapperTbPessoa->fetchPairs(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
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

