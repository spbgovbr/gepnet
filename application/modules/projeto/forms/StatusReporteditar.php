<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Projeto_Form_Statusreporteditar extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperPessoa = new Default_Model_Mapper_Pessoa();
        $serviceStatusReport = App_Service_ServiceAbstract::getService('Projeto_Service_StatusReport');
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "id" => "form-status-report-editar",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "elements" => array(
                    'idstatusreport' => array('hidden', array()),
                    'idprojeto' => array('hidden', array()),

                    'flaaprovado' => array(
                        'select',
                        array(
                            'label' => 'TAP assinado?',
                            'required' => true,
                            'multiOptions' => $serviceStatusReport->getTapAssinado(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span1'
                            ),
                        )
                    ),
                    'pgpassinado' => array(
                        'select',
                        array(
                            'label' => 'PGP assinado?',
                            'required' => true,
                            'multiOptions' => $serviceStatusReport->getPgpAssinado(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span1'
                            ),
                        )
                    ),
                    'tepassinado' => array(
                        'select',
                        array(
                            'label' => 'TEP assinado?',
                            'required' => true,
                            'multiOptions' => $serviceStatusReport->getTepAssinado(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'class' => 'span1'
                            ),
                        )
                    ),
                    'desandamentoprojeto' => array(
                        'textarea',
                        array(
                            'label' => 'Considerações gerais sobre o andamento do projeto',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 4000))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4000,
                            ),
                        )
                    ),

                    'domstatusprojeto' => array(
                        'select',
                        array(
                            'label' => 'Status do Projeto',
                            'required' => true,
                            'multiOptions' => $serviceStatusReport->getStatusProjeto(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'datacompanhamento' => array(
                        'text',
                        array(
                            'label' => 'Data Acompanhamento',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'data-rule-required' => true,
                                'disabled' => true,
                            ),
                            'value' => date("d/m/Y"),
                        )
                    ),
                    'numpercentualconcluido' => array(
                        'text',
                        array(
                            'label' => 'Percentual Concluído',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'numpercentualprevisto' => array(
                        'text',
                        array(
                            'label' => 'Percentual Previsto',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'data-rule-required' => true,
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
                            //'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'data-rule-required' => false,
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
                            'required' => true,
                            'multiOptions' => $serviceGerencia->getRiscoProjeto(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'data-rule-required' => true,
                            ),
                        )
                    ),

                    'desatividadeconcluida' => array(
                        'textarea',
                        array(
                            'label' => '',
                            'required' => false,
                            'maxlength' => 4000,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 4200))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4200,
                                'maxlength' => 4000,
                            ),
                        )
                    ),
                    'desatividadeandamento' => array(
                        'textarea',
                        array(
                            'label' => '',
                            'required' => false,
                            'maxlength' => 4000,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 4200))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4200,
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
                            'validators' => array(array('StringLength', false, array(0, 4200))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4200,
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
                            'validators' => array(array('StringLength', false, array(0, 4200))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4200,
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
                            'validators' => array(array('StringLength', false, array(0, 4200))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4200,
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
                            'validators' => array(array('StringLength', false, array(0, 4200))),
                            'attribs' => array(
                                'rows' => 5,
                                'class' => 'span5',
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 4200,
                                'maxlength' => 4000,
                            ),
                        )
                    ),
                )
            ));

        $config = Zend_Registry::get('config');
        $uploadArquivosDir = $config->resources->cachemanager->default->backend->options->arquivos_dir;

        $this->addElement('file', 'descaminho', array(
            'label' => 'Arquivo',
            'required' => false,
            'validators' => array(
                array('Count', false, 1), // 100K
                array('Size', false, 1024000000), // 100K
//                array('Extension', false, 'doc,docx,pdf')
            ),
            //'MultiFile' => 3,
            'destination' => $uploadArquivosDir,
            'attribs' => array(
                'class' => 'span4',
                'data-rule-required' => false,
            ),
        ));
    }

}
