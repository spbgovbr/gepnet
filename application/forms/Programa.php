<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Default_Form_Programa extends App_Form_FormAbstract
{

    public function init()
    {

        $servicePessoa = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $fetchPairsPessoa = $servicePessoa->fetchPairs();

        $servicePortfolio = new Planejamento_Service_Portfolio();
        $fetchPairPortfolio = $servicePortfolio->fetchPairs();
        $arrayPortfolio = $servicePortfolio->initCombo($fetchPairPortfolio, "Selecione");


        // $mapperTbPessoa = new Default_Model_Mapper_Pessoa();
        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                    "method" => "post",
                    "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                    "id" => "form-programa",
                    "elements" => array(
                        'idprograma' => array('hidden', array()),
                        'nomprograma' => array(
                            'text',
                            array(
                                'label' => 'Nome',
                                'required' => true,
                                'maxlength' => '100',
                                'filters' => array('StringTrim', 'StripTags', 'StringToUpper'),
                                'validators' => array(
                                    'NotEmpty',
                                    array('StringLength', false, array(0, 100)),
                                    array(
                                        'Db_NoRecordExists',
                                        true,
                                        array(
                                            'table' => 'agepnet200.tb_programa',
                                            'field' => 'nomprograma',
                                            'messages' => array(
                                                'recordFound' => 'Nome programa já existe'
                                            )
                                        )
                                    ),
                                ),
                                'attribs' => array(
                                    'data-rule-required' => true,
                                    'data-rule-maxlength' => 100,
                                    'data-rule-minlength' => 1,
                                ),
                            )
                        ),
                        'desprograma' => array(
                            'textarea',
                            array(
                                'label' => 'Descrição',
                                'required' => true,
                                'maxlength' => '500',
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', array('StringLength', false, array(0, 500))),
                                'attribs' => array(
                                    'rows' => 24,
                                    'cols' => 30,
                                    'class' => 'span8 textarea_obs',
                                    'data-rule-required' => true,
                                    'data-rule-maxlength' => 500,
                                    'data-rule-minlength' => 1,
                                ),
                            )
                        ),
                        'datcadastro' => array(
                            'text',
                            array(
                                'label' => '',
                                'required' => false,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', array('StringLength', false, array(0, 4))),
                                'attribs' => array(),
                            )
                        ),
                        'flaativo' => array(
                            'select',
                            array(
                                'label' => 'Ativo?',
                                'required' => true,
                                'multiOptions' => array(
                                    'S' => 'Sim',
                                    'N' => 'Não',
                                )
                            )
                        ),
                        'idresponsavel' => array(
                            'hidden',
                            array(
                                'label' => 'Responsável',
                                'required' => true,
                                //'multiOptions' => $fetchPairsPessoa,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty'),
                                'attribs' => array(
                                    'data-rule-required' => true,
                                ),
                            )
                        ),
                        'nomresponsavel' => array(
                            'text',
                            array(
                                'label' => 'Responsável',
                                'required' => true,
                                //'multiOptions' => $fetchPairsPessoa,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty'),
                                'attribs' => array(
                                    'readonly' => true,
                                    'data-rule-required' => true,
                                ),
                            )
                        ),
                        'idsimpr' => array(
                            'text',
                            array(
                                'label' => 'Código do Programa(PROJETO) no SIMPR',
                                'required' => true,
                                'filters' => array('Digits', 'StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', 'Digits'),
                                'attribs' => array(
                                    'data-rule-number' => true,
                                    'data-rule-required' => true,
                                    'data-rule-maxlength' => 10,
                                    'data-rule-minlength' => 1,
                                ),
                            )
                        ),
                        'idsimpreixo' => array(
                            'text',
                            array(
                                'label' => 'Código do Eixo no SIMPR',
                                'required' => true,
                                'filters' => array('Digits', 'StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty', 'Digits'),
                                'attribs' => array(
                                    'data-rule-number' => true,
                                    'data-rule-required' => true,
                                    'data-rule-maxlength' => 10,
                                    'data-rule-minlength' => 1,
                                ),
                            )
                        ),
                        'idsimprareatematica' => array(
                            'text',
                            array(
                                'label' => 'Código Área Temática no SIMPR',
                                'required' => true,
                                'filters' => array('Digits', 'StringTrim', 'StripTags'),
                                'validators' => array('Digits', 'NotEmpty'),
                                'attribs' => array(
                                    'data-rule-number' => true,
                                    'data-rule-required' => true,
                                    'data-rule-maxlength' => 10,
                                    'data-rule-minlength' => 1,
                                ),
                            )
                        ),
                        'idcadastrador' => array('hidden', array()),
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
                )
            );

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

