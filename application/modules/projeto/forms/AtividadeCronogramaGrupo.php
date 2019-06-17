<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_AtividadeCronogramaGrupo extends App_Form_FormAbstract
{

    public function init()
    {
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => 'ac-grupo',
                "elements" => array(
                    'idatividadecronograma' => array('hidden', array()),
                    'idprojeto' => array('hidden', array()),
                    'domtipoatividade' => array('hidden', array()),
                    'datinicio' => array('hidden', array()),
                    'datfim' => array('hidden', array()),
                    'datiniciobaseline' => array('hidden', array()),
                    'datfimbaseline' => array('hidden', array()),
                    'nomatividadecronograma' => array(
                        'text',
                        array(
                            'label' => 'Nome do Grupo',
                            'required' => true,
                            'maxlength' => 255,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                            'maxlength' => '255',
                            'attribs' => array(
                                'data-rule-required' => true,
                                'data-rule-maxlength' => 255,
                                'data-rule-minlength' => 2,
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

