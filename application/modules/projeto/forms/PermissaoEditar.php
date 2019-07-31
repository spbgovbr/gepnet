<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Projeto_Form_PermissaoEditar extends App_Form_FormAbstract
{

    public function init()
    {
        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-permissao",
                "elements" => array(
                    'idprojeto' => array('hidden', array()),
                    'idparteinteressada' => array('hidden', array()),
                    'idpessoa' => array('hidden', array()),
                    'nomparteinteressada' => array(
                        'text',
                        array(
                            'label' => 'Nome',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'data-rule-required' => true,
                                'maxlength' => '100',
                                'data-rule-maxlength' => 100,
                                'readonly' => 'readonly',
                            ),
                        )
                    ),
                    'emailparteinteressada' => array(
                        'hidden',
                        array(
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'readonly' => true,
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                ),
                "titles" => array(
                    'TAP' => 'TAP - Termo de Abertura do Projeto',
                    'EAP' => 'EAP - Estrutura Analítica do Projeto',
                    'CRONOGRAMA' => 'Cronograma',
                    'GANTT' => 'GANTT',
                    'RH' => 'RH - Recursos Humanos',
                    'COMUNICACAO' => 'Comunicação',
                    'RISCO' => 'Riscos',
                    'RISCOS' => 'Riscos',
                    'ATAREUNIAO' => 'Ata',
                    'ATA' => 'Ata',
                    'R3G' => 'R3G - Imprevistos e Contramedidas',
                    'DIARIO' => 'Diário',
                    'SOLICITACAOMUDANCA' => 'Mudanças',
                    'MUDANCAS' => 'Mudanças',
                    'RUD' => 'RUD - Repositório Único de Documentos',
                    'RELATORIO' => 'Relatório',
                    'TERMOACEITE' => 'Aceite',
                    'ACEITE' => 'Aceite',
                    'LICAO' => 'Lições',
                    'LICOES' => 'Lições',
                    'TEP' => 'TEP - Termo de Encerramento do Projeto',
                )
            ));

        $this->getElement('nomparteinteressada')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

    }

}

