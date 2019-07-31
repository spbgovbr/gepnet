<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Projeto_Form_Rud extends App_Form_FormAbstract
{

    public function init()
    {

        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "id" => "form-rud",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "elements" => array(
                    'idprojeto' => array('hidden', array()),
                    'nompasta' => array(
                        'select',
                        array(
                            'label' => 'Selecione a pasta',
                            'required' => true,
                            'multiOptions' => array('' => 'Selecione'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
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
                                'class' => 'btn btn-primary',
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

        $config = Zend_Registry::get('config');
        $uploadArquivosDir = $config->resources->cachemanager->default->backend->options->arquivos_dir;

        $this->addElement('file', 'arquivo1', array(
            'label' => '',
            'required' => false,
            'validators' => array(
                array('Count', false, 1),
                array('Size', false, 1024000000)
            ),
            //'MultiFile' => 3,
            'destination' => $uploadArquivosDir,
            'attribs' => array(
                'class' => 'span4',
                'data-rule-required' => false,
            ),
        ));

        $this->addElement('file', 'arquivo2', array(
            'label' => '',
            'required' => false,
            'validators' => array(
                array('Count', false, 1), // 100K
                array('Size', false, 1024000000)
            ),
            //'MultiFile' => 3,
            'destination' => $uploadArquivosDir,
            'attribs' => array(
                'class' => 'span4',
                'data-rule-required' => false,
            ),
        ));

        $this->addElement('file', 'arquivo3', array(
            'label' => '',
            'required' => false,
            'validators' => array(
                array('Count', false, 1), // 100K
                array('Size', false, 1024000000), // 100K
//                array('Extension', false, 'txt,doc,docx,pdf')
            ),
            //'MultiFile' => 3,
            'destination' => $uploadArquivosDir,
            'attribs' => array(
                'class' => 'span4',
                'data-rule-required' => false,
            ),
        ));

        $this->addElement('file', 'arquivo4', array(
            'label' => '',
            'required' => false,
            'validators' => array(
                array('Count', false, 1), // 100K
                array('Size', false, 1024000000), // 100K
//                array('Extension', false, 'txt,doc,docx,pdf')
            ),
            //'MultiFile' => 3,
            'destination' => $uploadArquivosDir,
            'attribs' => array(
                'class' => 'span4',
                'data-rule-required' => false,
            ),
        ));

        $this->addElement('file', 'arquivo5', array(
            'label' => '',
            'required' => false,
            'validators' => array(
                array('Count', false, 1), // 100K
                array('Size', false, 1024000000), // 100K
//                array('Extension', false, 'txt,doc,docx,pdf')
            ),
            //'MultiFile' => 3,
            'destination' => $uploadArquivosDir,
            'attribs' => array(
                'class' => 'span4',
                'data-rule-required' => false,
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
