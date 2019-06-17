<?php

class Relatorio_Form_RiscoPesquisar extends App_Form_FormAbstract
{

    public function init()
    {

        $origemRisco = new Projeto_Model_Mapper_Origemrisco();

        $etapa = new Projeto_Model_Mapper_Etapa();

        $tipoRisco = new Projeto_Model_Mapper_Tiporisco();

        $escritorioMapper = new Default_Model_Mapper_Escritorio();
        $escritorio = $escritorioMapper->fetchPairs();

        $gerenciaMapper = new Projeto_Model_Mapper_Gerencia();
        $projeto = $gerenciaMapper->fetchPairsProjeto();

        $naturezaMapper = new Default_Model_Mapper_Natureza();
        $natureza = $naturezaMapper->fetchPairs();

        $servicePortfolio = new Planejamento_Service_Portfolio();
        $fetchPairPortfolio = $servicePortfolio->fetchPairs();

        $servicePrograma = App_Service_ServiceAbstract::getService('Default_Service_Programa');
        $fetchPairPrograma = $servicePrograma->fetchPairs();

        //Chamada para Gerencias Service
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');

        $arrayPortfolio = $serviceGerencia->initCombo($fetchPairPortfolio, "Selecione");
        $arrayPrograma = $serviceGerencia->initCombo($fetchPairPrograma, "Selecione");

        $arrProbabilidade = array(
            '' => 'Selecione',
            '1' => 'Alta',
            '2' => 'Media',
            '3' => 'Baixa',
        );
        $arrImpacto = array(
            '' => 'Selecione',
            '1' => 'Alta',
            '2' => 'Media',
            '3' => 'Baixa',
        );
        $arrTratamento = array(
            '' => 'Selecione',
            '1' => 'Conviver',
            '2' => 'Mitigar (Reduzir efeitos)',
            '3' => 'Neutralizar',
            '4' => 'Potencializar',
            '5' => 'Tranferir',
        );

        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-risco-pesquisar',
            'elements' => array(
                'idrisco' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'datdeteccao' => array(
                    'text',
                    array(
                        'label' => 'Data Detecção',
                        'required' => false,
                        'maxlength' => '10',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'datdeteccaoinicio', //evita conflito preechimento datepicker com o form do cadastro
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
                        ),
                    )
                ),
                'datdeteccaofim' => array(
                    'text',
                    array(
                        'required' => false,
                        'maxlength' => '10',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'datdeteccaofim', //evita conflito preechimento datepicker com o form do cadastro
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
                        ),
                    )
                ),
                'norisco' => array(
                    'text',
                    array(
                        'label' => 'Título Risco',
                        'required' => false,
                        'maxlength' => '50',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '50',
                            'data-rule-maxlength' => 50,
                            'placeholder' => 'Informe o título do risco',
                            'id' => 'noreisco_pesquisar',
                        ),
                    )
                ),
                'idorigemrisco' => array(
                    'select',
                    array(
                        'label' => 'Origem',
                        'multiOptions' => $origemRisco->fetchPairs(true),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'idorigemrisco_pesquisar',
                        ),
                    )
                ),
                'idetapa' => array(
                    'select',
                    array(
                        'label' => 'Etapa',
                        'multiOptions' => $etapa->fetchPairs(true),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'idetapa_pesquisar',
                        ),
                    )
                ),
                'idtiporisco' => array(
                    'select',
                    array(
                        'label' => 'Tipo',
                        'multiOptions' => $tipoRisco->fetchPairs(true),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'idtiporisco_pesquisar',
                        ),
                    )
                ),
                'domcorprobabilidade' => array(
                    'select',
                    array(
                        'label' => 'Probabilidade',
                        'multiOptions' => $arrProbabilidade,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'domcorprobabilidadepesquisar',
                        ),
                    )
                ),
                'domcorimpacto' => array(
                    'select',
                    array(
                        'label' => 'Impacto',
                        'multiOptions' => $arrImpacto,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'domcorimpactopesquisar',
                        ),
                    )
                ),
                'flariscoativo' => array(
                    'select',
                    array(
                        'label' => 'Ativo?',
                        'multiOptions' => array('' => 'Selecione', '1' => 'Sim', '2' => 'Não'),
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'flariscoativo_pesquisar',
                        ),
                    )
                ),
                'datencerramentorisco' => array(
                    'text',
                    array(
                        'label' => 'Data encerramento',
                        'required' => false,
                        'maxlength' => '10',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'datencerramentoinicio',
                            //evita conflito preechimento datepicker com o form do cadastro
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
                        ),
                    )
                ),
                'datencerramentoriscofim' => array(
                    'text',
                    array(
                        'required' => false,
                        'maxlength' => '10',
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                        'attribs' => array(
                            'class' => 'span2 mask-date',
                            'id' => 'datencerramentofim',
                            //evita conflito preechimento datepicker com o form do cadastro
                            'maxlength' => '10',
                            'data-rule-maxlength' => 10,
                            'data-rule-dateITA' => true,
                            'placeholder' => 'DD/MM/AAAA',
                            'style' => 'width:85px',
                        ),
                    )
                ),
                'domtratamento' => array(
                    'select',
                    array(
                        'label' => 'Tratamento',
                        'required' => false,
                        'multiOptions' => $arrTratamento,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'domtratamento_pesquisar',
                        ),
                    )
                ),
                'desrisco' => array(
                    'text',
                    array(
                        'label' => 'Risco',
                        'required' => false,
                        'maxlength' => '255',
                        'filters' => array('StringTrim', 'StripTags'),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                        ),
                    )
                ),
                'idescritorio' => array(
                    'select',
                    array(
                        'label' => 'Escritório de Projeto (EGPS)',
                        'required' => false,
                        'multiOptions' => array('' => 'Selecione') + $escritorio,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'idescritorio_pesquisar',
                        ),
                    )
                ),
                'idprojeto' => array(
                    'select',
                    array(
                        'label' => 'Projeto',
                        'required' => false,
                        'multiOptions' => array('' => 'Selecione') + $projeto,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'idprojeto_pesquisar',
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
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'id' => 'idnatureza_pesquisar',
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
