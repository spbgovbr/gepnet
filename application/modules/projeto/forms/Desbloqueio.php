<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_Desbloqueio extends App_Form_FormAbstract
{

    public function init()
    {
        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-desbloqueio",
                "elements" => array(
                    'idprojeto' => array('hidden', array()),
                    'desjustificativa' => array(
                        'textarea',
                        array(
                            'label' => 'Justificativa desbloqueio',
                            'required' => false,
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
                )
            ));
    }

}

