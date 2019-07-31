<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Default_Form_Perfilpessoa extends App_Form_FormAbstract
{

    public function init()
    {

        $servicePerfilpessoa = new Default_Service_Perfilpessoa();
        $serviceEscritorio = new Default_Service_Escritorio;
        $servicePerfil = new Default_Service_Perfil;
        //$usuario = $serviceLogin->retornaUsuarioLogado();

        $escritorios = $servicePerfilpessoa->initCombo($serviceEscritorio->fetchPairs(), 'Selecione');
        $perfis = $servicePerfilpessoa->initCombo($servicePerfil->fetchPairs(), 'Selecione');

        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "perfilpessoa-pesquisa",
                "elements" => array(
                    'idperfilpessoa' => array('hidden', array()),
                    'idpessoa' => array('hidden', array()),
                    'nompessoa' => array(
                        'text',
                        array(
                            'label' => 'Pessoa',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(),

                        )
                    ),
                    'idperfil' => array(
                        'select',
                        array(
                            'label' => 'Perfil',
                            'required' => false,
                            'multiOptions' => $perfis,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),
                    'idescritorio' => array(
                        'select',
                        array(
                            'label' => 'Escritório de Projetos',
                            'required' => false,
                            'multiOptions' => $escritorios,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),
                    'flaativo' => array(
                        'select',
                        array(
                            'label' => 'Situação',
                            'required' => false,
                            'multiOptions' => array('' => 'Todas', 'S' => "Ativo", 'N' => 'Inativo'),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array('class' => 'span1'),
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

