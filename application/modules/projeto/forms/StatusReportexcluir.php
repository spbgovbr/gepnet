<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Projeto_Form_Statusreportexcluir extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperPessoa = new Default_Model_Mapper_Pessoa();
        $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-status-report-excluir",
                "elements" => array(
                    'idstatusreport' => array('hidden', array()),
                    'idprojeto' => array('hidden', array()),
                    'domstatusprojeto' => array(
                        'select',
                        array(
                            'label' => 'Status do Projeto',
                            'required' => true,
                            'multiOptions' => $serviceStatusReport->getStatusProjeto(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'readonly' => 'true',
                            ),
                        )
                    ),
                    'datacompanhamento' => array(
                        'text',
                        array(
                            'label' => 'Data Acompanhamento',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'data-rule-required' => false,
                                'readonly' => 'true',
                            ),
                            'value' => date("d/m/Y"),
                        )
                    ),
                    'numpercentualconcluido' => array(
                        'text',
                        array(
                            'label' => 'Percentual Concluído',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'data-rule-required' => false,
                                'readonly' => 'true',
                            ),
                        )
                    ),
                    'numpercentualprevisto' => array(
                        'text',
                        array(
                            'label' => 'Percentual Previsto',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'data-rule-required' => false,
                                'readonly' => 'true',
                            ),
                        )
                    ),
                    'idmarco' => array(
                        'select',
                        array(
                            'label' => 'Próximo Marco',
                            'required' => false,
                            'multiOptions' => array(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'readonly' => 'true',
                            ),
                        )
                    ),
                    'datfimprojetotendencia' => array(
                        'text',
                        array(
                            'label' => 'Tendência do Projeto',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 10))),
                            'attribs' => array(
                                'data-rule-required' => false,
                                'readonly' => 'true',
                            ),
                        )
                    ),
                    'domcorrisco' => array(
                        'select',
                        array(
                            'label' => 'Risco Atual do Projeto',
                            'required' => false,
                            'multiOptions' => $serviceGerencia->getRiscoProjeto(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'readonly' => 'true',
                            ),
                        )
                    ),

                    'desatividadeconcluida' => array(
                        'textarea',
                        array(
                            'label' => 'Atividades Concluídas no Período',
                            'required' => false,
                            'maxlength' => 4000,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'readonly' => 'true',
                                'maxlength' => 4000,
                            ),
                        )
                    ),
                    'desatividadeandamento' => array(
                        'textarea',
                        array(
                            'label' => 'Atividades em Andamento no Período',
                            'required' => false,
                            'maxlength' => 4000,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'readonly' => 'true',
                                'maxlength' => 4000,
                            ),
                        )
                    ),
                    'desmotivoatraso' => array(
                        'textarea',
                        array(
                            'label' => 'Motivo de Atraso no Prazo Final do Projeto',
                            'required' => false,
                            'maxlength' => 4000,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'readonly' => 'true',
                                'maxlength' => 4000,
                            ),
                        )
                    ),
                    'descontramedida' => array(
                        'textarea',
                        array(
                            'label' => 'Contramedidas (R3G)',
                            'required' => false,
                            'maxlength' => 4000,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'readonly' => 'true',
                                'maxlength' => 4000,
                            ),
                        )
                    ),
                    'desirregularidade' => array(
                        'textarea',
                        array(
                            'label' => 'Irregularidades',
                            'required' => false,
                            'maxlength' => 4000,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'readonly' => 'true',
                                'maxlength' => 4000,
                            ),
                        )
                    ),
                    'desrisco' => array(
                        'textarea',
                        array(
                            'label' => 'Riscos',
                            'required' => false,
                            'maxlength' => 4000,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'readonly' => 'true',
                                'maxlength' => 4000,
                            ),
                        )
                    ),
                )
            ));

        /*$this->addElement('file', 'descaminho', array(
            'label' => 'Arquivo',
            'required' => false,
            'validators' => array(
                array('Count', false, 1), // 100K
                array('Size', false, 102400), // 100K
                array('Extension', false, 'doc,docx,pdf')
            ),
            //'MultiFile' => 3,
            'destination' => APPLICATION_PATH . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "arquivos",
            'attribs' => array(
                'class' => 'span4',
                'data-rule-required' => false,
            ),
        ));*/
    }

}
