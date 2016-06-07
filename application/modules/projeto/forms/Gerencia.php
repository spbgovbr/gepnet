<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:21
 */
class Projeto_Form_Gerencia extends App_Form_FormAbstract {

    public function init() {


        //Chamada para Default Service
        $serviceEscritorio = App_Service_ServiceAbstract::getService('Default_Service_Escritorio');
        $fetchPairEscritorio = $serviceEscritorio->fetchPairs();
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
        $arrayPrograma = $serviceGerencia->initCombo($fetchPairPrograma, "Selecione");
        $arraySetor = $serviceGerencia->initCombo($fetchPairSetor, "Selecione");
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
                        'codprojeto' => array('text', array(
                                'label' => 'Código do Projeto',
                                'value' => 'Código será gerado automáticamente',
                                'required' => false,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(array('StringLength', false, array(0, 100))),
                                'attribs' => array(
                                    'disabled' => 'disabled',
                                    'class' => 'span'
                                )
                            )),
                        'nomcodigo' => array('text', array(
                            'label' => 'Código',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'class' =>  'span2',
                                'readonly' => true,
                                'data-rule-required' => false,
                            ),
                        )),
                        'nomprojeto' => array('text', array(
                                'label' => 'Título do Projeto',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(array('NotEmpty','StringLength', false, array(0, 100))),
                                'attribs' => array(
                                    'class' => 'span4',
                                    'data-rule-required' => true,
                                ),
                            )),

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
                        'idacao' => array('select', array(
                                'label' => 'Ação Estratégica',
                                'required' => true,
                                'multiOptions' => $arrayAcao,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty'),
                                'attribs' => array(
                                    'class' => 'select2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'idprograma' => array('select', array(
                                'label' => 'Programa',
                                'required' => true,
                                'multiOptions' => $arrayPrograma,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty'),
                                'attribs' => array(
                                    'class' => 'select2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'idobjetivo' => array('select', array(
                                'label' => 'Objetivo Institucional',
                                'required' => true,
                                'multiOptions' => $fetchPairObjetivo,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty'),
                                'attribs' => array(
                                    'class' => 'select2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'idnatureza' => array('select', array(
                                'label' => 'Natureza',
                                'required' => true,
                                'multiOptions' => $arrayNatureza,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty'),
                                'attribs' => array(
                                    'class' => 'select2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'iddemandante' => array('hidden', array(
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(),
                                'attribs' => array(
                                    'readonly' => true,
                                    'data-rule-required' => true,
                                ),
                            )),
                        'nomdemandante' => array('text', array(
                                'label' => 'Demandante',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(array('NotEmpty','StringLength', false, array(0, 100))),
                                'attribs' => array(
                                    'readonly' => true,
                                    'class' =>  'span2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'ano' => array('text', array(
                                'label' => 'Ano',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(array('NotEmpty','StringLength', false, array(0, 4))),
                                'attribs' => array(
                                    'class' =>  'span1',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'idsetor' => array('select', array(
                                'label' => 'Área Executora',
                                'required' => true,
                                'multiOptions' => $fetchPairSetor,
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'select2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'idescritorio' => array('select', array(
                                'label' => 'Escritório Responsável',
                                'required' => true,
                                'multiOptions' => $arrayEscritorio,
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'select2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'idpatrocinador' => array('hidden', array(
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(),
                                'attribs' => array(
                                    'readonly' => true,
                                    'data-rule-required' => true,
                                ),
                            )),
                        'nompatrocinador' => array('text', array(
                                'label' => 'Patrocinador',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(array('StringLength', false, array(0, 100))),
                                'attribs' => array(
                                    'readonly' => true,
                                    'class' =>  'span2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'idgerenteprojeto' => array('hidden', array(
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(),
                                'attribs' => array(
                                    'readonly' => true,
                                    'data-rule-required' => true,
                                    'data-rule-notequal' => "#idgerenteadjunto",
                                ),
                            )),
                        'nomgerenteprojeto' => array('text', array(
                                'label' => 'Gerente do Projeto',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(array('StringLength', false, array(0, 100))),
                                'attribs' => array(
                                    'readonly' => true,
                                    'class' =>  'span2',
                                    'data-rule-required' => true,
                                    'data-rule-notequal' => "#nomgerenteadjunto",
                                ),
                            )),
                        'idgerenteadjunto' => array('hidden', array(
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(),
                                'attribs' => array(
                                    'readonly' => true,
                                    'data-rule-required' => true,
                                    'data-rule-notequal' => "#idgerenteprojeto",
                                ),
                            )),
                        'nomgerenteadjunto' => array('text', array(
                                'label' => 'Gerente Adjunto',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(array('StringLength', false, array(0, 100))),
                                'attribs' => array(
                                    'readonly' => true,
                                    'class' =>  'span2',
                                    'data-rule-required' => true,
                                    'data-rule-notequal' => "#nomgerenteprojeto",
                                ),
                            )),
                        'vlrorcamentodisponivel' => array('text', array(
                                'label' => 'Orçamento Previsto',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(array('StringLength', false, array(0, 100))),
                                'attribs' => array(
                                    'data-rule-required' => true,
                                    'class' => 'span2',
                                    'style' => 'text-align:right',
                                ),
                            )),
                        'datinicio' => array('text', array(
                                'label' => 'Início do Projeto',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'span2 datepicker',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'datfim' => array('text', array(
                                'label' => 'Fim do Projeto',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'span2 datepicker',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'datinicioplano' => array('text', array(
                                'label' => 'Início do Plano Projeto',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'span2 datepicker',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'datfimplano' => array('text', array(
                                'label' => 'Fim do Plano Projeto',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'attribs' => array(
                                    'class' => 'span2 datepicker',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'desjustificativa' => array('textarea', array(
                                'label' => 'Justificativa do Projeto',
                                'required' => false,
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
                            )),
                        'desprojeto' => array('textarea', array(
                                'label' => 'Objeto do Projeto (O que será feito?)',
                                'required' => false,
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
                            )),
                        'desobjetivo' => array('textarea', array(
                                'label' => 'Objetivo do Projeto (Para que será feito?)',
                                'required' => false,
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
                            )),
                        'desconsideracaofinal' => array('textarea', array(
                                'label' => 'Considerações Finais',
                                'required' => false,
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
                            )),
                        'desescopo' => array('textarea', array(
                                'label' => 'Escopo Resumido (Principais Entregas)',
                                'required' => false,
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
                            )),
                        'desnaoescopo' => array('textarea', array(
                                'label' => 'Não Escopo (O que não será feito)',
                                'required' => false,
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
                            )),
                        'despremissa' => array('textarea', array(
                                'label' => 'Premissas',
                                'required' => false,
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
                            )),
                        'desrestricao' => array('textarea', array(
                                'label' => 'Restrições',
                                'required' => false,
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
                            )),
                        'incluir' => array('button', array(
                                'ignore' => true,
                                'label' => 'Incluir Pessoas Interessadas',
                                'escape' => false,
                                'attribs' => array(
                                    'id' => 'incluirbutton',
                                ),
                            )),
                        'domtipoprojeto' => array('select', array(
                                'label' => 'Tipo do Projeto',
                                'required' => true,
                                'multiOptions' =>  array(
                                    '' => 'Selecione',
                                    'Normal' => 'Normal',
                                    'Estratégico' => 'Estratégico',
                                ),
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty'),
                                'attribs' => array(
                                    'class' => 'select2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'flacopa' => array('select', array(
                                'label' => 'Grandes Eventos',
                                'required' => true,
                                'multiOptions' => array(
                                    '' => 'Selecione',
                                    'S' => 'Sim',
                                    'N' => 'Não',
                                ),
                                'attribs' => array(
                                    'data-rule-required' => true,
                                ),
                            )),
                        'numperiodicidadeatualizacao' => array('text', array(
                                'label' => 'Periodicidade de Atualização(Dias)',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(array('StringLength', false, array(0, 100))),
                                'attribs' => array(
                                    'data-rule-required' => true,
                                ),
                            )),
                        'numcriteriofarol' => array('text', array(
                                'label' => 'Critério Farol Prazo(Dias)',
                                'required' => true,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array(array('StringLength', false, array(0, 100))),
                                'attribs' => array(
                                    'data-rule-required' => true,
                                ),
                            )),
                        'flapublicado' => array('select', array(
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
                            )),
                        'flaaprovado' => array('select', array(
                                'label' => 'Aprovado?(TAP assinado)',
                                'required' => true,
                                'multiOptions' => array(
                                    '' => 'Selecione',
                                    'S' => 'Sim',
                                    'N' => 'Não',
                                ),
                                'attribs' => array(
                                    'data-rule-required' => true,
                                ),
                            )),
                        'idinterno' => array('select', array(
                                'label' => 'Selecione o interessado',
                                'required' => false,
                                'multiOptions' => $fetchPairPessoa,
                                'filters' => array('StringTrim', 'StripTags'),
                                'validators' => array('NotEmpty'),
                                'attribs' => array(
                                    'class' => 'select2',
                                    'data-rule-required' => true,
                                ),
                            )),
                        'idportfolio' => array('select', array(
                            'label' => 'Portfólio do Projeto',
                            'required' => true,
                            'multiOptions' => $arrayPortfolio,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                            ),
                        )),
                        'submit' => array('button', array(
                                'ignore' => true,
                                'label' => 'Salvar',
                                'escape' => false,
                                'attribs' => array(
                                    'id' => 'submitbutton',
                                    'type' => 'submit',
                                ),
                            )),
                        'reset' => array('button', array(
                                'ignore' => true,
                                'label' => 'Limpar',
                                'escape' => false,
                                'attribs' => array(
                                    'id' => 'resetbutton',
                                    'type' => 'reset',
                                ),
                            )),
                        'pessoabutton' => array('button', array(
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
                            )),
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
    }

}

