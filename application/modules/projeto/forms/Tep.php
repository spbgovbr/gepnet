<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_Tep extends App_Form_FormAbstract
{

    public function init()
    {

        $serviceObjetivo = App_Service_ServiceAbstract::getService('Default_Service_Objetivo');
        $fetchPairObjetivo = $serviceObjetivo->fetchPairs();

        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-tep",
                "elements" => array(
                    'idprojeto' => array('hidden', array()),
                    'desprojeto' => array(
                        'textarea',
                        array(
                            'label' => 'Objeto do Projeto',
                            'required' => false,
                            'readonly' => 'readyonly',
                            //TODO: Abitilitar o htmlentities depois que o problema de conversão de dados no banco for resolvido
//                                'filters' => array('StringTrim', 'StripTags', array('HtmlEntities', array('quotestyle' => ENT_QUOTES))),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 4000))),
                            'maxlength' => '4000',
                            'attribs' => array(
                                'rows' => 24,
                                'cols' => 30,
                                'class' => 'span8 textarea_obs',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4000,
                            ),
                        )
                    ),
                    'desobjetivo' => array(
                        'textarea',
                        array(
                            'label' => 'Objetivo do Projeto',
                            'required' => false,
                            'readonly' => 'readyonly',
                            'maxlength' => '4000',
                            //TODO: Abitilitar o htmlentities depois que o problema de conversão de dados no banco for resolvido
//                                'filters' => array('StringTrim', 'StripTags', array('HtmlEntities', array('quotestyle' => ENT_QUOTES))),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 24,
                                'cols' => 30,
                                'class' => 'span8 textarea_obs',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4000,
                            ),
                        )
                    ),
                    'desconsideracaofinal' => array(
                        'textarea',
                        array(
                            'label' => 'Considerações Finais',
                            'required' => false,
                            'maxlength' => '4000',
                            //TODO: Abitilitar o htmlentities depois que o problema de conversão de dados no banco for resolvido
//                                'filters' => array('StringTrim', 'StripTags', array('HtmlEntities', array('quotestyle' => ENT_QUOTES))),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 24,
                                'cols' => 30,
                                'class' => 'span8 textarea_obs',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4000,
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
    }

}

