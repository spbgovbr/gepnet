<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_ClonarProjeto extends App_Form_FormAbstract
{

    public function init()
    {
        //Chamada para Default Service
        $serviceEscritorio = new Default_Service_Escritorio();
        $fetchPairEscritorio = $serviceEscritorio->fetchPairs();
        //Chamada para Gerencias Service
        $serviceGerencia = new Projeto_Service_Gerencia();
        //Preparando Combos para seleção
        $arrayEscritorio = $serviceGerencia->initCombo($fetchPairEscritorio, "Selecione");

        $this
            ->setOptions(array(
                "method" => "post",
                "id" => 'form-clonarprojeto',
                "name" => 'form-clonarprojeto',
                "elements" => array(
                    'idprojeto' => array('hidden', array()),

                    'nomprojeto' => array(
                        'text',
                        array(
                            'label' => 'Titulo do Projeto',
                            'required' => true,
                            'maxlength' => '100',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span4',
                                'maxlength' => '100',
                                'data-rule-required' => true,
                            ),
                        )
                    ),

                    'ano' => array(
                        'text',
                        array(
                            'label' => 'Ano',
                            'required' => true,
                            'maxlength' => '4',
                            'filters' => array('Digits', 'StringTrim', 'StripTags'),
                            'validators' => array('Digits', array('NotEmpty', 'StringLength', false, array(0, 4))),
                            'attribs' => array(
                                'data-rule-number' => true,
                                'maxlength' => '4',
                                'class' => 'span1',
                                'data-rule-required' => true,
                            ),
                        )
                    ),

                    'idescritorio' => array(
                        'select',
                        array(
                            'label' => 'Escritório Responsável',
                            'required' => true,
                            'multiOptions' => $arrayEscritorio,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
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
    }

}

