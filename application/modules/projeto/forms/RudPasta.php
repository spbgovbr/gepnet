<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Projeto_Form_RudPasta extends App_Form_FormAbstract
{

    public function init()
    {
//        $mapperPessoa               = new Default_Model_Mapper_Pessoa();
//        $serviceStatusReport        = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
//        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "id" => "form-rud-pasta",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "elements" => array(
                    'idprojeto' => array('hidden', array()),
                    'pasta' => array(
                        'text',
                        array(
                            'label' => 'Nome da Pasta',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'submit' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Criar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'submitbuttonpasta',
                                'type' => 'submit',
                                'name' => 'submitbuttonpasta',
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
                                'id' => 'resetbuttonpasta',
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
