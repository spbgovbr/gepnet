<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_Gerencia extends App_Form_FormAbstract
{

    public function init()
    {

        //Chamada para Default Service
        $serviceEscritorio = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $fetchPairEscritorio = $serviceEscritorio->getEscritorioTAP();
        $servicePrograma = App_Service_ServiceAbstract::getService('Default_Service_Programa');
        $fetchPairPrograma = $servicePrograma->fetchPairs();
        $serviceSetor = App_Service_ServiceAbstract::getService('Default_Service_Setor');
        $fetchPairSetor = $serviceSetor->fetchPairs();
        $serviceObjetivo = App_Service_ServiceAbstract::getService('Default_Service_Objetivo');
        $fetchPairObjetivo = $serviceObjetivo->fetchPairs();
        $serviceNaturezas = App_Service_ServiceAbstract::getService('Default_Service_Natureza');
        $fetchPairNatureza = $serviceNaturezas->fetchPairs();
        $serviceAcao = App_Service_ServiceAbstract::getService('Default_Service_Acao');
        $fetchPairAcao = $serviceAcao->fetchPairs();
        $servicePessoa = App_Service_ServiceAbstract::getService('Default_Service_Pessoa');
        $fetchPairPessoa = $servicePessoa->fetchPairs();
        $servicePortfolio = new Planejamento_Service_Portfolio();
        $fetchPairPortfolio = $servicePortfolio->fetchPairs();

        //Chamada para Gerencias Service
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');

        //Preparando Combos para seleção
        $arrayEscritorio = $serviceGerencia->initCombo($fetchPairEscritorio, "Selecione");
        $arrayPrograma = $fetchPairPrograma;

        $arrayAcao = $serviceGerencia->initCombo($fetchPairAcao, "Selecione");
        $arrayObjetivo = $serviceGerencia->initCombo($fetchPairObjetivo, "Selecione");
        $arrayNatureza = $serviceGerencia->initCombo($fetchPairNatureza, "Selecione");
        $arrayPessoa = $serviceGerencia->initCombo($fetchPairPessoa, "Selecione");
        $arrayPortfolio = $serviceGerencia->initCombo($fetchPairPortfolio, "Selecione");


        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-gerencia",
                "elements" => array(
                    'idprojeto' => array('hidden', array()),
                    'idcadastrador' => array('hidden', array()),
                    'idtipoiniciativa' => array('hidden', array()),
                    'ano' => array('hidden', array()),
                    'codprojeto' => array(
                        'text',
                        array(
                            'label' => 'Código do Projeto',
                            'value' => 'Código será gerado automáticamente',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'disabled' => 'disabled',
                                'class' => 'span'
                            )
                        )
                    ),
                    'nomcodigo' => array(
                        'text',
                        array(
                            'label' => 'Código',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'span2',
                                'readonly' => true,
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'nomprojeto' => array(
                        'text',
                        array(
                            'label' => 'Título do Projeto',
                            'required' => true,
                            'maxlength' => '100',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span3',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'numprocessosei' => array(
                        'text',
                        array(
                            'label' => 'Nº do processo',
                            'required' => false,
                            'maxlength' => '30',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 20))),
                            'attribs' => array(
                                'class' => 'span2',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 20,
                            ),
                        )
                    ),
                    /*'nomecodigo' => array('text', array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'class' =>  'span2',
                                'readonly' => true,
                                'data-rule-required' => false,
                            ),
                        )),*/
                    'idacao' => array(
                        'select',
                        array(
                            'label' => 'Ação Estratégica',
                            'required' => true,
                            'multiOptions' => $arrayAcao,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
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
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'idobjetivo' => array(
                        'select',
                        array(
                            'label' => 'Objetivo Institucional',
                            'required' => true,
                            //'multiOptions' => $fetchPairObjetivo,
                            'multiOptions' => $arrayObjetivo,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'idnatureza' => array(
                        'select',
                        array(
                            'label' => 'Natureza',
                            'multiOptions' => $arrayNatureza,
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'iddemandante' => array(
                        'hidden',
                        array(
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'readonly' => true,
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'nomdemandante' => array(
                        'text',
                        array(
                            'label' => 'Demandante',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'readonly' => true,
                                'class' => 'span2',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'idsetor' => array(
                        'select',
                        array(
                            'label' => 'Área Executora',
                            'required' => true,
                            'multiOptions' => $fetchPairSetor,
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'idescritorio' => array(
                        'select',
                        array(
                            'label' => 'Escritório Responsável',
                            'required' => true,
                            'multiOptions' => $arrayEscritorio,
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'idproponente' => array(
                        'hidden',
                        array(
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'readonly' => true,
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'nomproponente' => array(
                        'text',
                        array(
                            'label' => 'Responsável',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'readonly' => false,
                                'class' => 'span2',
                                'data-rule-required' => false,
                                'style' => 'width:160px;',
                            ),
                        )
                    ),
                    'idpatrocinador' => array(
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
                    'nompatrocinador' => array(
                        'text',
                        array(
                            'label' => 'Patrocinador',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'readonly' => true,
                                'class' => 'span2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'idgerenteprojeto' => array(
                        'hidden',
                        array(
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'readonly' => true,
                                'data-rule-required' => true,
                                'data-rule-notequal' => "#idgerenteadjunto",
                            ),
                        )
                    ),
                    'nomgerenteprojeto' => array(
                        'text',
                        array(
                            'label' => 'Gerente de Projeto',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'readonly' => true,
                                'class' => 'span2',
                                'data-rule-required' => true,
                                'data-rule-notequal' => "#nomgerenteadjunto",
                            ),
                        )
                    ),
                    'idgerenteadjunto' => array(
                        'hidden',
                        array(
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'readonly' => true,
                                'data-rule-required' => false,
                                'data-rule-notequal' => "#idgerenteprojeto",
                                'id' => 'idgerenteadjunto',
                            ),
                        )
                    ),
                    'nomgerenteadjunto' => array(
                        'text',
                        array(
                            'label' => 'Gerente Adjunto',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'readonly' => true,
                                'class' => 'span2',
                                'data-rule-required' => false,
                                'data-rule-notequal' => "#nomgerenteprojeto",
                            ),
                        )
                    ),
                    'vlrorcamentodisponivel' => array(
                        'text',
                        array(
                            'label' => 'Orçamento Previsto',
                            'required' => false,
                            'maxlength' => '100',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'data-rule-required' => false,
                                'class' => 'span2',
                                'style' => 'text-align:right',
                            ),
                        )
                    ),
                    'datinicio' => array(
                        'text',
                        array(
                            'label' => 'Início do Projeto',
                            'required' => true,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'span2 datepicker datemask-BR',
                                'data-rule-required' => true,
                                'data-rule-dateITA' => true,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                    'datfim' => array(
                        'text',
                        array(
                            'label' => 'Fim do Projeto',
                            'required' => true,
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('Date', 'StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'class' => 'span2 datepicker datemask-BR',
                                'data-rule-required' => true,
                                'data-rule-dateITA' => true,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                    'datinicioplano' => array(
                        'text',
                        array(
                            'label' => 'Início do Plano Projeto',
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'span2 datepicker datemask-BR',
                                'data-rule-dateITA' => true,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                    'datfimplano' => array(
                        'text',
                        array(
                            'label' => 'Fim do Plano Projeto',
                            'maxlength' => '10',
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'span2 datepicker datemask-BR',
                                'data-rule-dateITA' => true,
                                'autocomplete' => 'off',
                            ),
                        )
                    ),
                    'desjustificativa' => array(
                        'textarea',
                        array(
                            'label' => 'Justificativa do Projeto',
                            'required' => false,
                            'maxlength' => '4000',
                            //TODO: Abitilitar o htmlentities depois que o problema de conversão de dados no banco for resolvido
//                                'filters' => array('StringTrim', 'StripTags', array('HtmlEntities', array('quotestyle' => ENT_QUOTES))),
                            'filters' => array('StringTrim', 'StripTags'),
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
                    'desprojeto' => array(
                        'textarea',
                        array(
                            'label' => 'Objeto do Projeto (O que será feito?)',
                            'required' => false,
                            'maxlength' => '4000',
                            //TODO: Abitilitar o htmlentities depois que o problema de conversão de dados no banco for resolvido
//                                'filters' => array('StringTrim', 'StripTags', array('HtmlEntities', array('quotestyle' => ENT_QUOTES))),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 24,
                                'cols' => 30,
                                'class' => 'span8 textarea_obs',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4000,
                            ),
                        )
                    ),
                    'desobjetivo' => array(
                        'textarea',
                        array(
                            'label' => 'Objetivo do Projeto (Para que será feito?)',
                            'required' => false,
                            'maxlength' => '4000',
                            //TODO: Abitilitar o htmlentities depois que o problema de conversão de dados no banco for resolvido
//                                'filters' => array('StringTrim', 'StripTags', array('HtmlEntities', array('quotestyle' => ENT_QUOTES))),
                            'filters' => array('StringTrim', 'StripTags'),
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
                    'desconsideracaofinal' => array(
                        'textarea',
                        array(
                            'label' => 'Considerações Finais',
                            'required' => false,
                            'maxlength' => '4000',
                            //TODO: Abitilitar o htmlentities depois que o problema de conversão de dados no banco for resolvido
//                                'filters' => array('StringTrim', 'StripTags', array('HtmlEntities', array('quotestyle' => ENT_QUOTES))),
                            'filters' => array('StringTrim', 'StripTags'),
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
                    'desescopo' => array(
                        'textarea',
                        array(
                            'label' => 'Escopo Resumido (Principais Entregas)',
                            'required' => false,
                            'maxlength' => '4000',
                            //TODO: Abitilitar o htmlentities depois que o problema de conversão de dados no banco for resolvido
//                                'filters' => array('StringTrim', 'StripTags', array('HtmlEntities', array('quotestyle' => ENT_QUOTES))),
                            'filters' => array('StringTrim', 'StripTags'),
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
                    'desnaoescopo' => array(
                        'textarea',
                        array(
                            'label' => 'Não Escopo (O que não será feito)',
                            'required' => false,
                            'maxlength' => '4000',
                            //TODO: Abitilitar o htmlentities depois que o problema de conversão de dados no banco for resolvido
//                                'filters' => array('StringTrim', 'StripTags', array('HtmlEntities', array('quotestyle' => ENT_QUOTES))),
                            'filters' => array('StringTrim', 'StripTags'),
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
                    'despremissa' => array(
                        'textarea',
                        array(
                            'label' => 'Premissas',
                            'required' => false,
                            'maxlength' => '4000',
                            //TODO: Abitilitar o htmlentities depois que o problema de conversão de dados no banco for resolvido
//                                'filters' => array('StringTrim', 'StripTags', array('HtmlEntities', array('quotestyle' => ENT_QUOTES))),
                            'filters' => array('StringTrim', 'StripTags'),
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
                    'desrestricao' => array(
                        'textarea',
                        array(
                            'label' => 'Restrições',
                            'required' => false,
                            'maxlength' => '4000',
                            //TODO: Abitilitar o htmlentities depois que o problema de conversão de dados no banco for resolvido
//                                'filters' => array('StringTrim', 'StripTags', array('HtmlEntities', array('quotestyle' => ENT_QUOTES))),
                            'filters' => array('StringTrim', 'StripTags'),
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
                    'incluir' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Incluir Pessoas Interessadas',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'incluirbutton',
                            ),
                        )
                    ),
                    'domtipoprojeto' => array(
                        'select',
                        array(
                            'label' => 'Tipo do Projeto',
                            'required' => false,
                            'multiOptions' => array(
                                '' => 'Selecione',
                                'Normal' => 'Normal',
                                'Estratégico' => 'Estratégico',
                            ),
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'numperiodicidadeatualizacao' => array(
                        'text',
                        array(
                            'label' => 'Periodicidade de Atualização(Dias)',
                            'required' => true,
                            'maxlength' => '100',
                            'filters' => array('Digits', 'StringTrim', 'StripTags'),
                            'validators' => array('Digits', 'NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'data-rule-number' => true,
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'numcriteriofarol' => array(
                        'text',
                        array(
                            'label' => 'Critério Farol Prazo(Dias)',
                            'required' => true,
                            'maxlength' => '100',
                            'filters' => array('Digits', 'StringTrim', 'StripTags'),
                            'validators' => array('Digits', 'NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'data-rule-number' => true,
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'flapublicado' => array(
                        'select',
                        array(
                            'label' => 'Publicar o Projeto',
                            'required' => true,
                            'multiOptions' => array(
                                '' => 'Selecione',
                                'S' => 'Sim',
                                'N' => 'Não',
                            ),
                            'attribs' => array(
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'flaaprovado' => array(
                        'select',
                        array(
                            'label' => 'Aprovado?(TAP assinado)',
                            'required' => false,
                            'multiOptions' => array(
                                '' => 'Selecione',
                                'S' => 'Sim',
                                'N' => 'Não',
                            ),
                            'attribs' => array(
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'idinterno' => array(
                        'select',
                        array(
                            'label' => 'Selecione o interessado',
                            'required' => false,
                            'multiOptions' => $fetchPairPessoa,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'enviar' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Salvar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'enviarbutton',
                                'type' => 'button',
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
                    'pessoabutton' => array(
                        'button',
                        array(
                            'label' => '',
                            'ignore' => true,
                            'icon' => 'user',
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                            //'label' => 'Limpar',
                            'escape' => true,
                            'attribs' => array(
                                'class' => 'pessoa-button',
                                'type' => 'button',
                            )
                        )
                    ),
                    'pessoabuttonDeman' => array(
                        'button',
                        array(
                            'label' => '',
                            'ignore' => true,
                            'icon' => 'remove', //icon-remove
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                            //'label' => 'Limpar',
                            'escape' => true,
                            'attribs' => array(
                                'type' => 'button',
                                'id' => 'resetDemandate',
                                'title' => 'Limpar campo',
                            )
                        )
                    ),
                    'pessoabuttonAdjunto' => array(
                        'button',
                        array(
                            'label' => '',
                            'ignore' => true,
                            'icon' => 'remove', //icon-remove
                            'iconPosition' => Twitter_Bootstrap_Form_Element_Button::ICON_POSITION_LEFT,
                            'escape' => true,
                            'attribs' => array(
                                'id' => 'resetAdjunto',
                                'type' => 'button',
                                'title' => 'Limpar campo',
                            )
                        )
                    ),
                )
            ));

        $this->getElement('nomdemandante')
            //->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('nompatrocinador')
            //->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('nomgerenteprojeto')
            //->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('nomgerenteadjunto')
            //->removeDecorator('label')\
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('enviar')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('reset')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('pessoabuttonDeman')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('pessoabuttonAdjunto')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}

