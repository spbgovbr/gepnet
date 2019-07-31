<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_GerarGantt extends App_Form_FormAbstract
{

    public function init()
    {

        $arrayFormato = array(
            "png" => "Image / png",
            "jpg" => "Image / jpeg",
            "gif" => "Image / gif",
        );

        $this
            ->setOptions(array(
                "method" => "post",
                "id" => 'form-gerar-gantt',
                "name" => 'form-gerar-gantt',
                "elements" => array(
                    'idprojeto' => array('hidden', array()),
                    'idgrupo' => array('hidden', array()),
                    'identrega' => array('hidden', array()),
                    'idatividadecronograma' => array('hidden', array()),
                    'idatividademarco' => array('hidden', array()),
                    'nomprojeto' => array(
                        'text',
                        array(
                            'label' => 'Titulo do Projeto',
                            //'required' => true,
                            'readonly' => 'readonly',
                            'maxlength' => '100',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span4',
                                'maxlength' => '100',
                                'data-rule-required' => true,
                                'readonly' => true,
                            ),
                        )
                    ),
                    'formato' => array(
                        'select',
                        array(
                            'label' => 'Formato',
                            'required' => false,
                            'multiOptions' => $arrayFormato,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'tpabertura' => array(
                        'checkbox',
                        array(
                            'label' => 'Baixar a imagem gerada (download)',
                            'required' => false,
                            'setCheckedValue' => 1,
                            'setUncheckedValue' => 0,
                            'attribs' => array(
                                'data-rule-required' => false,
                                'checked' => 'checked'
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

