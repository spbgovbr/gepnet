<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Processo_Form_PAcao extends App_Form_FormAbstract
{

    public function init()
    {
        $serviceProjetoprocesso = new Processo_Service_Projetoprocesso();
        $mapperTbPAcao = new Processo_Model_Mapper_PAcao();
        $mapperTbSetor = new Default_Model_Mapper_Setor();
        //$mapperTbPessoa          = new Default_Model_Mapper_Pessoa();
        $this->setOptions(array(
            "method" => "post",
            "id" => "form-pacao",
            "elements" => array(
                'id_p_acao' => array('hidden', array()),
                'numseq' => array('hidden', array()),
                'idprojetoprocesso' => array(
                    'select',
                    array(
                        'label' => 'Projeto de Processo',
                        'required' => true,
                        'multiOptions' => $serviceProjetoprocesso->fetchPairs(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array(),
                        'attribs' => array(),
                    )
                ),
                'nom_p_acao' => array(
                    'text',
                    array(
                        'label' => 'Ação',
                        'required' => true,
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
                        'required' => true,
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
                        'required' => true,
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
                        'required' => true,
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
                        'required' => true,
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
                'nomresponsavel' => array(
                    'text',
                    array(
                        'label' => 'Responsável',
                        'required' => false,
                        //'multiOptions' => $mapperTbPessoa->fetchPairs(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(),
                    )
                ),
                'idresponsavel' => array(
                    'hidden',
                    array(
                        //'label'        => 'Responsável',
                        'required' => false,
                        //'multiOptions' => $mapperTbPessoa->fetchPairs(),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
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
        $this->getElement('nomresponsavel')
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

