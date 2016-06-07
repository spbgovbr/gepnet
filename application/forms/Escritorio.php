<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Default_Form_Escritorio extends App_Form_FormAbstract
{

    public function init()
    {
        //$servicePessoa         = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $serviceTipoEscritorio = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $serviceEscritorio     = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        //$fetchPairsPessoa      = $servicePessoa->fetchPairs();
        $fetchPairMapa         = $serviceEscritorio->mapaFetchPairs();



        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method"   => "post",
                "enctype"  => Zend_Form::ENCTYPE_URLENCODED,
                "id"       => "form-escritorio",
                "elements" => array(
                    'idescritorio'    => array('hidden', array()),
                    'nomescritorio'   => array('text', array(
                            'label'      => 'Sigla',
                            'required'   => true,
                            'filters'    => array('StringTrim', 'StripTags', 'StringToUpper'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs'    => array(
                                'maxlength'           => 100,
                                'size'                => 50,
                                'data-rule-required'  => true,
                                'data-rule-maxlength' => 100,
                                'data-rule-minlength' => 1,
                            ),
                        )),
                    'idcadastrador'   => array('hidden', array()),
                    'datcadastro'     => array('text', array(
                            'label'      => '',
                            'required'   => false,
                            'filters'    => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4))),
                            'attribs'    => array(),
                        )),
                    'flaativo'        => array('select', array(
                            'label'        => 'Ativo?',
                            'required'     => true,
                            'multiOptions' => array(
                                'S' => 'Sim',
                                'N' => 'Não',
                            ),
                            'filters'      => array('StringTrim', 'StripTags'),
                            'validators'   => array(),
                            'attribs'      => array(),
                        )),
                    'idresponsavel1'  => array('hidden', array(
                            'label'      => 'Responsável-1',
                            'required'   => true,
                            // 'multiOptions' => $fetchPairsPessoa,
                            'filters'    => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs'    => array(
                                'class'              => 'select2',
                                'data-rule-required' => true,
                            ),
                        )),
                    'nomresponsavel1' => array('text', array(
                            'label'      => 'Responsável-1',
                            'required'   => true,
                            //'multiOptions' => $fetchPairsPessoa,
                            'filters'    => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs'    => array(
                                'class'              => 'select2',
                                'data-rule-required' => true,
                            ),
                        )),
                    'idresponsavel2'  => array('hidden', array(
                            'label'      => 'Responsavel-2',
                            'required'   => true,
                            //'multiOptions' => $fetchPairsPessoa,
                            'filters'    => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs'    => array(
                                'class'              => 'select2',
                                'data-rule-required' => true,
                            ),
                        )),
                    'nomresponsavel2' => array('text', array(
                            'label'      => 'Responsavel-2',
                            'required'   => true,
                            // 'multiOptions' => $fetchPairsPessoa,
                            'filters'    => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs'    => array(
                                'class'              => 'select2',
                                'data-rule-required' => true,
                            ),
                        )),
                    'idescritoriope'  => array('select', array(
                            'label'        => 'Mapa',
                            'required'     => false,
                            'multiOptions' => $fetchPairMapa,
                            'filters'      => array('StringTrim', 'StripTags'),
                            'validators'   => array('NotEmpty'),
                            'attribs'      => array(
                                'class'              => 'select2',
                                'data-rule-required' => true,
                            ),
                        )),
                    'nomescritorio2'  => array('text', array(
                            'label'      => 'Nome',
                            'required'   => true,
                            'filters'    => array('StringTrim', 'StripTags', 'StringToUpper'),
                            'validators' => array(
                                'NotEmpty',
                                array('StringLength', false, array(0, 100)),
                                array('Db_NoRecordExists', false, array(
                                        'table'    => 'tb_escritorio',
                                        'field'    => 'nomescritorio2',
                                        'schema'   => 'agepnet200',
                                    /*
                                        'messages' => array(
                                            'recordFound' => 'Nome escritorio já existe'
                                        )
                                     */
                                    )
                                ),
                            ),
                            'attribs'    => array(
                                'attribs' => array(
                                    'class'              => 'select2',
                                    'data-rule-required' => true,
                                ),
                            )
                        )),
                    'submit'          => array('button', array(
                            'ignore'  => true,
                            'label'   => 'Salvar',
                            'escape'  => false,
                            'attribs' => array(
                                'id'   => 'submitbutton',
                                'type' => 'submit',
                            ),
                        )),
                    'reset'           => array('button', array(
                            'ignore'  => true,
                            'label'   => 'Limpar',
                            'escape'  => false,
                            'attribs' => array(
                                'id'   => 'resetbutton',
                                'type' => 'reset',
                            ),
                        )),
                    'desemail' => array('text', array(
                        'label'        => 'Email',
                        'required'     => false,
                        'filters'      => array('StringTrim','StripTags'),
                        'validators'   => array('NotEmpty'),
                        'attribs'      => array('data-rule-required' => false),
                    )),
                    'numfone' => array('text', array(
                        'label'        => 'Telefone',
                        'required'     => false,
                        'filters'      => array('StringTrim','StripTags'),
                        'validators'   => array('NotEmpty'),
                        'attribs'      => array('data-rule-required' => false,
                                                'max-length' => 15),
                    )),
                    'pessoabutton'    => array('button', array(
                            'label'        => '',
                            'ignore'       => true,
                            'icon'         => 'user',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                            //'label' => 'Limpar',
                            'escape'       => true,
                            'attribs'      => array(
                                'class' => 'pessoa-button',
                                'type'  => 'button',
                            )
                        )),
                )
        ));

        $this->getElement('nomresponsavel1')
          //->removeDecorator('label')
          ->removeDecorator('HtmlTag')
          ->removeDecorator('Wrapper');
        $this->getElement('nomresponsavel2')
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

