<?php

class Relatorio_Form_LicaoPesquisar extends App_Form_FormAbstract
{

    public function init()
    {

        $escritorioMapper = new Default_Model_Mapper_Escritorio();
        $escritorio = $escritorioMapper->fetchPairs();

        $naturezaMapper = new Default_Model_Mapper_Natureza();
        $natureza = $naturezaMapper->fetchPairs();

        $servicePortfolio = new Planejamento_Service_Portfolio();
        $fetchPairPortfolio = $servicePortfolio->fetchPairs();

        $servicePrograma = App_Service_ServiceAbstract::getService('Default_Service_Programa');
        $fetchPairPrograma = $servicePrograma->fetchPairs();

        //Chamada para Gerencias Service
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');

        $gerenciaMapper = new Projeto_Model_Mapper_Gerencia();
        $projeto = $gerenciaMapper->fetchPairsProjeto();

        $arrayPortfolio = $serviceGerencia->initCombo($fetchPairPortfolio, "Selecione");
        $arrayPrograma = $serviceGerencia->initCombo($fetchPairPrograma, "Selecione");

        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-licao-pesquisar',
            'elements' => array(
                'idlicao' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'idescritorio' => array(
                    'select',
                    array(
                        'label' => 'Escritório de Projeto (EGPS)',
                        'required' => false,
                        'multiOptions' => array('' => 'Selecione') + $escritorio,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'idescritorio_pesquisar',
                            'style' => 'width:265px',
                        ),
                    )
                ),
                'idprojeto' => array(
                    'select',
                    array(
                        'label' => 'Projeto',
                        'multiOptions' => array('' => 'Selecione') + $projeto,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                        'attribs' => array(
                            'class' => 'span3',
                            'maxlength' => '20',
                            'data-rule-maxlength' => 20,
                            'id' => 'idprojeto_pesquisa',
                            'style' => 'width:265px',
                        ),
                    )
                ),
                'identrega' => array(
                    'select',
                    array(
                        'label' => 'Entrega',
                        'required' => false,
                        'multiOptions' => array('' => 'Selecione'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 9999))),
                        'attribs' => array(
                            'class' => 'span3',
                            //'maxlength' => '20',
                            //'data-rule-maxlength' => 20,
                            'id' => 'identrega_pesquisar',
                            'style' => 'width:265px',
                        ),
                    )
                ),
                'idnatureza' => array(
                    'select',
                    array(
                        'label' => 'Natureza',
                        'required' => false,
                        'multiOptions' => array('' => 'Selecione') + $natureza,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'idnatureza_pesquisar',
                            'style' => 'width:265px',
                        ),
                    )
                ),
                'idportfolio' => array(
                    'select',
                    array(
                        'label' => 'Portfólio do Projeto',
                        'required' => false,
                        'multiOptions' => $arrayPortfolio,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'select2',
                            'data-rule-required' => false,
                        ),
                    )
                ),
                'idprograma' => array(
                    'select',
                    array(
                        'label' => 'Programa',
                        'required' => false,
                        'multiOptions' => $arrayPrograma,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty'),
                        'attribs' => array(
                            'class' => 'select2',
                            'data-rule-required' => false,
                        ),
                    )
                ),
                'desresultadosobtidos' => array(
                    'text',
                    array(
                        'label' => 'Resultados Obtidos',
                        'required' => false,
                        'maxlength' => '255',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'placeholder' => 'Informe os resultados obtidos',
                        ),
                    )
                ),
                'despontosfortes' => array(
                    'text',
                    array(
                        'label' => 'Ponto Fortes',
                        'required' => false,
                        'maxlength' => '255',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'placeholder' => 'Informe os pontos fortes',
                        ),
                    )
                ),
                'despontosfracos' => array(
                    'text',
                    array(
                        'label' => 'Ponto Fracos',
                        'required' => false,
                        'maxlength' => '255',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'placeholder' => 'Informe os pontos fracos',
                        ),
                    )
                ),
                'dessugestoes' => array(
                    'text',
                    array(
                        'label' => 'Sugestões',
                        'required' => false,
                        'maxlength' => '255',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'placeholder' => 'Informe as sugestões',
                        ),
                    )
                ),
                'datcadastro' => array(
                    'text',
                    array(
                        'label' => 'Data cadastro',
                        'required' => false,
                        'maxlength' => '10',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'datcadastroinicio', //evita conflito preechimento datepicker com o form do cadastro
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
                        ),
                    )
                ),
                'datcadastrofim' => array(
                    'text',
                    array(
                        'required' => false,
                        'maxlength' => '10',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'datcadastrofim', //evita conflito preechimento datepicker com o form do cadastro
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
                        ),
                    )
                ),
                'btnpesquisar' => array(
                    'button',
                    array(
                        'ignore' => true,
                        'label' => 'Pesquisar',
                        'icon' => 'filter',
                        'whiteIcon' => false,
                        'escape' => false,
                        'attribs' => array(
                            'id' => 'btnpesquisar',
                            'type' => 'button',
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
        $this->getElement('btnpesquisar')
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
