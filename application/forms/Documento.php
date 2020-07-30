<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Default_Form_Documento extends App_Form_FormAbstract
{

    public function init()
    {
        $serviceTipoDocumento = App_Service_ServiceAbstract::getService('Default_Service_TipoDocumento');
        $this
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_MULTIPART,
                "id" => "form-documento",
                "elements" => array(
                    'iddocumento' => array('hidden', array()),
                    'idescritorio' => array('hidden', array()),
                    'nomdocumento' => array(
                        'text',
                        array(
                            'label' => 'Nome',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'maxlength' => 100,
                                'size' => 50,
                                'class' => 'span6',
                                'data-rule-required' => true,
                                'data-rule-maxlength' => 100,
                                'data-rule-minlength' => 3,
                            ),
                        )
                    ),
                    'idtipodocumento' => array(
                        'select',
                        array(
                            'label' => 'Tipo',
                            'required' => true,
                            'multiOptions' => $serviceTipoDocumento->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span4 select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    /*
                      'descaminho' => array('file', array(
                      'label'    => 'Arquivo',
                      'required' => false,
                      'filters'  => array('StringTrim', 'StripTags'),
                      'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                      'attribs' => array(),
                      )),
                     */
                    'datdocumento' => array(
                        'text',
                        array(
                            'label' => 'Data',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2 datepicker datemask-BR',
                                'data-rule-required' => true,
                                'data-rule-dateITA' => true,
                                'placeholder' => 'DD/MM/AAAA',
                            ),
                        )
                    ),
                    'desobs' => array(
                        'textarea',
                        array(
                            'label' => 'Observação',
                            'required' => false,
                            'filters' => array(
                                'StringTrim',
                                'StripTags',
                                array('HtmlEntities', array('quotestyle' => ENT_QUOTES))
                            ),
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
                    'flaativo' => array(
                        'select',
                        array(
                            'label' => 'Ativo',
                            'multiOptions' => array('S' => 'Sim', 'N' => 'Não'),
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span2',
                                'data-rule-required' => true,
                            ),
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
                )
            ));
        /*
        */
        $this->addElement('file', 'descaminho', array(
            'label' => 'Arquivo',
            'required' => true,
            'validators' => array(
                array('Count', false, 1), // 100K
                array(
                    'Size',
                    false,
                    array(
                        'max' => 32768000,
                        'messages' => array(
                            Zend_Validate_File_Size::TOO_BIG => 'Tamanho de arquivo inválido.',
                        ),
                    )
                ), // 1M
                array(
                    'Extension',
                    false,
                    array(
                        'pattern' => 'doc,docx,pdf,pptx',
                        'messages' => array(
                            Zend_Validate_File_Extension::FALSE_EXTENSION => 'Extensão de arquivo inválida.',
                        )
                    )
                ),

            ),
            //'MultiFile' => 3,
            'destination' => APPLICATION_PATH . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "arquivos",
            'description' => 'São aceitas as extensões: doc,docx,pdf. Tamanho Máximo: 1MB   ',
            'attribs' => array(
                'class' => 'span4',
                'data-rule-required' => true,
            ),
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

