<?php

/**
 * Automatically generated data model
 *
 * This class has been automatically generated16-05-2013 17:22
 */
class Default_Form_PessoaManual extends App_Form_FormAbstract
{

    public function init()
    {
        //        $serviceCargo = App_Service_ServiceAbstract::getService('Default_Service_Cargo');
//        $serviceUnidade = App_Service_ServiceAbstract::getService('Default_Service_Unidade');
//        $lotacao = $serviceUnidade->fetchPairs();
//        $lotacao = array("" => "Selecione") + array_filter($lotacao);
        $lotacao = array("" => "Selecione");
//        $cargo = $serviceCargo->fetchPairs();
//        $cargo = array("" => "Selecione") + array_filter($cargo) + array("OUTROS" => "OUTROS");
        $cargo = array("" => "Selecione");

        $this
            ->setOptions(array(
                "method" => "post",
                "id" => "form-pessoa",
                "elements" => array(
                    'nompessoa' => array(
                        'text',
                        array(
                            'label' => 'Nome',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'maxlength' => 100,
                                'size' => 50,
                                'class' => 'span6',
                                'data-rule-required' => true,
                                'data-rule-maxlength' => 100,
                                'data-rule-minlength' => 3,
                            ),
                        )
                    ),
                    'nummatricula' => array(
                        'text',
                        array(
                            'label' => 'Matr�cula',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(
                                'Digits',
                                array('NotEmpty', 'StringLength', false, array(0, 10))
                            ),
                            'attribs' => array(
                                'maxlength' => 10,
                                'size' => 15,
                                'data-rule-required' => false,
                                'data-rule-maxlength' => 10,
                                'data-rule-minlength' => 3,
                                'data-rule-number' => true,
                            ),
                        )
                    ),
                    'numcpf' => array(
                        'text',
                        array(
                            'label' => 'CPF',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags', 'Digits'),
                            'validators' => array(
                                'NotEmpty',
                                'Cpf',
                                array('StringLength', false, array(0, 11)),
                                array(
                                    'Db_NoRecordExists',
                                    false,
                                    array(
                                        'table' => 'tb_pessoa',
                                        'field' => 'numcpf',
                                        'schema' => 'agepnet200',
                                    )
                                )
                            ),
                            'attribs' => array(
                                'maxlength' => 14,
                                'size' => 15,
                                'class' => 'span2 mask-cpf cpf',
                                'data-rule-required' => true,
                                'data-rule-maxlength' => 14,
                                'data-rule-minlength' => 11,
                                'data-rule-cpf' => true,
                            ),
                        )
                    ),
                    'numfone' => array(
                        'text',
                        array(
                            'label' => 'Telefone fixo',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags', 'Digits'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 16))),
                            'attribs' => array(
                                'class' => 'span2 mask-tel',
                                'data-rule-required' => true,
                                'data-rule-maxlength' => 16,
                                'data-rule-minlength' => 11,
                            ),
                        )
                    ),
                    'numcelular' => array(
                        'text',
                        array(
                            'label' => 'Telefone Celular',
                            //'required' => true,
                            'filters' => array('StringTrim', 'StripTags', 'Digits'),
                            //'validators' => array('NotEmpty', array('StringLength', false, array(0, 16))),
                            'attribs' => array(
                                'class' => 'span2 mask-cel',
                                //'data-rule-required' => true,
                                //'data-rule-maxlength' => 16,
                                //'data-rule-minlength' => 11,
                            ),
                        )
                    ),
                    'desemail' => array(
                        'text',
                        array(
                            'label' => 'E-mail',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(
                                'NotEmpty',
                                'EmailAddress',
                                array('StringLength', false, array(0, 100)),
                                array(
                                    'Db_NoRecordExists',
                                    false,
                                    array(
                                        'table' => 'tb_pessoa',
                                        'field' => 'desemail',
                                        'schema' => 'agepnet200',
                                    )
                                )
                            ),
                            'attribs' => array(
                                'class' => 'span2',
                                'maxlength' => 100,
                                'size' => 50,
                                'data-rule-required' => true,
                                'data-rule-maxlength' => 100,
                                'data-rule-minlength' => 3,
                                'data-rule-email' => 3,
                            ),
                        )
                    ),
//                    'domcargo' => array(
//                        'select',
//                        array(
//                            'label' => 'Cargo',
//                            'multiOptions' => $cargo,
//                            'required' => true,
//                            'style' => 'width: 150px;',
//                            'filters' => array('StringTrim', 'StripTags'),
//                            'validators' => array(array('StringLength', false, array(0, 10))),
//                            'attribs' => array(
//                                'class' => 'span1',
//                                'data-rule-required' => true,
//                            ),
//                        )
//                    ),
//                    'id_unidade' => array(
//                        'select',
//                        array(
//                            'label' => 'Lotação',
//                            'multiOptions' => $lotacao,
//                            'required' => true,
//                            'filters' => array('StringTrim', 'StripTags'),
//                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
//                            'attribs' => array(
//                                'class' => 'span3 select2',
//                                'data-rule-required' => true,
//                            ),
//                        )
//                    ),
                    'desfuncao' => array(
                        'text',
                        array(
                            'label' => 'Função',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 50))),
                            'attribs' => array(
                                'class' => 'span2',
                            ),
                        )
                    ),
                    'flaagenda' => array(
                        'select',
                        array(
                            'label' => 'Agenda Escrit�rio',
                            'multiOptions' => array('' => 'Selecione', 'S' => 'Sim', 'N' => 'N�o'),
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 3))),
                            'attribs' => array(
                                'data-rule-required' => true,
                                'class' => 'span2',
                            ),
                        )
                    ),
                    'desobs' => array(
                        'textarea',
                        array(
                            'label' => 'Observa��o',
                            'required' => false,
                            'filters' => array(
                                'StringTrim',
                                'StripTags',
                                array('HtmlEntities', array('quotestyle' => ENT_QUOTES))
                            ),
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
                    'submit' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Enviar',
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
                    'id_servidor' => array(
                        'hidden',
                        array(
                            'filters' => array('StringTrim', 'Digits'),
                            //'validators' => array(
                            //    array('Db_NoRecordExists', false, array(
                            //            'table'  => 'tb_pessoa',
                            //            'field'  => 'id_servidor',
                            //            'schema' => 'agepnet200',
                            //        )
                            //    )
                            //),
                        )
                    ),
                    'idpessoa' => array('hidden', array()),
                    'idcadastrador' => array('hidden', array()),
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
    }

}

/**/