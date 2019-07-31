<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated26-03-2013 12:46
 */
class Default_Form_AssociarPerfil extends App_Form_FormAbstract
{

    public function init()
    {

        $servicePerfilpessoa = new Default_Service_Perfilpessoa();
        $serviceLogin = new Default_Service_Login();
        $serviceEscritorio = new Default_Service_Escritorio;
        $servicePerfil = new Default_Service_Perfil;
        $usuario = $serviceLogin->retornaUsuarioLogado();
        $escritorios = $servicePerfilpessoa->initCombo($serviceEscritorio->fetchPairs(), 'Selecione');
        $auth = Zend_Auth::getInstance();
        $identiti = $auth->getIdentity()->perfilAtivo->idperfil;
        $perfis = $servicePerfilpessoa->initCombo($servicePerfil->authfetchPairs($identiti), 'Selecione');

        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "associar-perfil",
                "elements" => array(
                    'idcadastrador' => array('hidden', array('value' => $usuario->idpessoa)),
                    'idpessoa' => array(
                        'hidden',
                        array(
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'readonly' => true,
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'nompessoa' => array(
                        'text',
                        array(
                            'label' => 'Pessoa',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'readonly' => true,
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'idperfil' => array(
                        'select',
                        array(
                            'label' => 'Perfil',
                            'required' => true,
                            'multiOptions' => $perfis,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'idescritorio' => array(
                        'select',
                        array(
                            'label' => 'Escritório de Projetos',
                            'required' => true,
                            'multiOptions' => $escritorios,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array('data-rule-required' => true,),
                        )
                    ),
                    'pessoabutton' => array(
                        'button',
                        array(
                            'label' => '',
                            'ignore' => true,
                            'icon' => 'user',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                            'escape' => true,
                            'attribs' => array(
                                'class' => 'pessoa-button',
                                'type' => 'button',
                            )
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
                )
            ));
        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('nompessoa')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

    }

}

