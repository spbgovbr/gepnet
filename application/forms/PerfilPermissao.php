<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Default_Form_PerfilPermissao extends App_Form_FormAbstract
{

    public function init()
    {
        $servicePerfil = new Default_Service_Perfil();
        $serviceRecurso = new Default_Service_Recurso();
        $servicePermissao = new Default_Service_Permissao();
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-perfil-permissao",
                "elements" => array(
                    'idperfil' => array(
                        'select',
                        array(
                            'label' => 'Perfil',
                            'required' => false,
                            'multiOptions' => $servicePerfil->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'class' => 'select2 span5',
                            ),
                        )
                    ),
                    'idrecurso' => array(
                        'select',
                        array(
                            'label' => 'Recurso',
                            'required' => false,
                            'multiOptions' => $serviceRecurso->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'class' => 'select2 span5',
                            ),
                        )
                    ),
                    'idpermissao' => array(
                        'select',
                        array(
                            'label' => 'Permissão',
                            'required' => false,
                            'multiOptions' => $servicePermissao->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'class' => 'select2',
                                'style' => 'width:283px'
                            ),
                        )
                    ),
                    'ds_permissao' => array(
                        'text',
                        array(
                            'label' => 'Descrição da permissão',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'class' => 'select2 span3',
                            ),
                        )
                    ),
                    'submit' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Pesquisar',
                            'icon' => 'filter',
                            'whiteIcon' => false,
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'submitbutton',
                                'type' => 'submit',
                                'class' => 'btn'
                            ),
                        )
                    ),
                    'reset' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'icon' => 'th',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                            'label' => 'Limpar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'resetbutton',
                                'type' => 'reset',
                            ),
                        )
                    ),
                    'close' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'icon' => 'arrow-right',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                            'label' => 'Fechar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'closebutton',
                                'type' => 'button',
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
        $this->getElement('close')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}

