<?php

class Acordocooperacao_Form_Acordoeditar extends App_Form_FormAbstract
{

    public function init()
    {
        $mapperTbAcordo = new Acordocooperacao_Model_Mapper_Acordo();
        $mapperTbTipoacordo = new Acordocooperacao_Model_Mapper_Tipoacordo();
        $mapperTbPessoa = new Default_Model_Mapper_Pessoa();
        $mapperTbSetor = new Default_Model_Mapper_Setor();
//        $mapperEntidadeExterna  = new Default_Model_Mapper_Entidadeexterna();
        $serviceAcordo = App_Service_ServiceAbstract::getService('Acordocooperacao_Service_Acordo');
        $serviceEntidadeexterna = App_Service_ServiceAbstract::getService('Acordocooperacao_Service_Entidadeexterna');
        $this
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_MULTIPART,
                "id" => "form-acordo-editar",
                "elements" => array(
                    'idacordo' => array('hidden', array()),
                    'idacordopai' => array(
                        'select',
                        array(
                            'label' => 'Instrumento Principal',
                            'required' => false,
                            'multiOptions' => $mapperTbAcordo->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'span7'
                            ),
                        )
                    ),
//                    'idtipoacordo'                  => array('select', array(
//                            'label'        => '',
//                            'required'     => false,
//                            'multiOptions' => $mapperTbTipoacordo->fetchPairs(),
//                            'filters'      => array('StringTrim', 'StripTags'),
//                            'validators'   => array('NotEmpty'),
//                            'attribs'      => array(),
//                        )),
                    'nomacordo' => array(
                        'text',
                        array(
                            'label' => 'Nome do Acordo',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span11',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'numsiapro' => array(
                        'text',
                        array(
                            'label' => 'Núm. SIAPRO',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 25))),
                            'attribs' => array(
                                'class' => 'span2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'idresponsavelinterno' => array(
                        'hidden',
                        array(
//                        'label' => 'Responsável Interno',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(
                                'readonly' => true,
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'nomresponsavelinterno' => array(
                        'text',
                        array(
                            'label' => 'Responsável Interno',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'readonly' => true,
                                'class' => 'span2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'destelefoneresponsavelinterno' => array(
                        'text',
                        array(
                            'label' => 'Telefone do Responsável Interno',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 30))),
                            'attribs' => array(
                                'class' => 'span2 mask-cel',
                            ),
                        )
                    ),
                    'idsetor' => array(
                        'select',
                        array(
                            'label' => 'Setor Interno',
                            'required' => true,
                            'multiOptions' => $mapperTbSetor->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'entidadeexterna' => array(
                        'select',
                        array(
                            'label' => 'Entidade Externa',
                            'required' => false,
//                        'multiOptions'  => $mapperEntidadeExterna->fetchPairs(),
                            'multiOptions' => $serviceEntidadeexterna->fetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),
                    'destelefonefiscal' => array(
                        'text',
                        array(
                            'label' => 'Telefone do Fiscal',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 30))),
                            'attribs' => array(
                                'class' => 'span2 mask-cel',
                            ),
                        )
                    ),
                    'despalavrachave' => array(
                        'text',
                        array(
                            'label' => 'Palavra Chave',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span2',
                            ),
                        )
                    ),
                    'desobjeto' => array(
                        'textarea',
                        array(
                            'label' => 'Objeto',
                            'required' => true,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 255))),
                            'attribs' => array(
                                'rows' => 4,
                                'cols' => 80,
                                'class' => 'span8',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'desobservacao' => array(
                        'textarea',
                        array(
                            'label' => 'Observações',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 255))),
                            'attribs' => array(
                                'rows' => 4,
                                'cols' => 80,
                                'class' => 'span8'
                            ),
                        )
                    ),
                    'numprazovigencia' => array(
                        'text',
                        array(
                            'label' => 'Prazo (dias)',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'mask-num span1',
                                'data-rule-number' => true,
                                'data-rule-maxlength' => 3,
                                'maxlength' => 3,
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'datassinatura' => array(
                        'text',
                        array(
                            'label' => 'Data Assinatura',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'span2 datepicker datemask-BR',
                                'data-rule-required' => false,
                                'data-rule-dateITA' => true,
                                'placeholder' => 'DD/MM/AAAA',
                            ),
                        )
                    ),
                    'datiniciovigencia' => array(
                        'text',
                        array(
                            'label' => 'Data Início Vigência',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'span2 datepicker datemask-BR',
                                'data-rule-required' => false,
                                'data-rule-dateITA' => true,
                                'placeholder' => 'DD/MM/AAAA',
                            ),
                        )
                    ),
                    'datfimvigencia' => array(
                        'text',
                        array(
                            'label' => 'Data Fim Vigência',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'span2 datepicker datemask-BR',
                                'data-rule-required' => false,
                                'data-rule-dateITA' => true,
                                'placeholder' => 'DD/MM/AAAA',
                            ),
                        )
                    ),
                    'datatualizacao' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),
                    'datcadastro' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),
                    'datpublicacao' => array(
                        'text',
                        array(
                            'label' => 'Data Publicação',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(
                                'class' => 'span2 datepicker datemask-BR',
                                'data-rule-required' => false,
                                'data-rule-dateITA' => true,
                                'placeholder' => 'DD/MM/AAAA',
                            ),
                        )
                    ),
                    'idcadastrador' => array(
                        'text',
                        array(
                            'label' => '',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty'),
                            'attribs' => array(),
                        )
                    ),
                    'flarescindido' => array(
                        'select',
                        array(
                            'label' => 'Rescindido?',
                            'required' => true,
                            'multiOptions' => $serviceAcordo->retornaRescindido(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                            'attribs' => array(
                                'class' => 'span2',
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'flasituacaoatual' => array(
                        'select',
                        array(
                            'label' => 'Situação',
                            'required' => false,
                            'multiOptions' => $serviceAcordo->retornaSituacao(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(),
                            'attribs' => array(),
                        )
                    ),

                    'descontatoexterno' => array(
                        'textarea',
                        array(
                            'label' => 'Contato Externo (Informar no mínimo Nome, E-mail e Telefone.)',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 255))),
                            'attribs' => array(
                                'rows' => 5,
                                'cols' => 80,
                                'class' => 'span8'
                            ),
                        )
                    ),
                    'idfiscal' => array(
                        'hidden',
                        array(
                            'required' => true,
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'readonly' => true,
                                'data-rule-required' => true,
                            ),
                        )
                    ),
                    'nomfiscal' => array(
                        'text',
                        array(
                            'label' => 'Fiscal',
                            'required' => true,
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'readonly' => true,
                                'class' => 'span2',
                                'data-rule-required' => true,
                                //'data-rule-notequal' => "#nomfiscal1",
                            ),
                        )
                    ),
                    'idfiscal2' => array(
                        'hidden',
                        array(
                            'required' => false,
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'readonly' => true,
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'nomfiscal2' => array(
                        'text',
                        array(
                            'label' => 'Fiscal 2',
                            'required' => false,
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'readonly' => true,
                                'class' => 'span2',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'idfiscal3' => array(
                        'hidden',
                        array(
                            'required' => false,
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'readonly' => true,
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'nomfiscal3' => array(
                        'text',
                        array(
                            'label' => 'Fiscal 3',
                            'required' => false,
                            'filters' => array(),
                            'validators' => array(),
                            'attribs' => array(
                                'readonly' => true,
                                'class' => 'span2',
                                'data-rule-required' => false,
                            ),
                        )
                    ),
                    'descargofiscal' => array(
                        'text',
                        array(
                            'label' => 'Cargo do Fiscal',
                            'required' => false,
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span5'
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
                    'voltar' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Voltar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'voltar',
                                'type' => 'button',
                            ),
                        )
                    ),

                    'pesquisar' => array(
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
                    'adicionar' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Adicionar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'btn-adicionar',
                                'type' => 'button',
                                'class' => 'btn',
                                'style' => 'margin-top: 22px;'
                            ),
                        )
                    ),
                )
            ));

        /*$this->addElement('file', 'descaminho', array(
            'label' => 'Arquivo',
            'ignore' => true,
            'required' => false,
            'validators' => array(
//                array('Count', false, 0), // 100K
//                array('Size', false, 1024000000), // 1GB
//                array('Extension', false, 'txt,doc,docx,pdf')
            ),
            //'MultiFile' => 3,
            'destination' => APPLICATION_PATH . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR .'acordo',
            'attribs' => array(
                'class' => 'span4',
                'data-rule-required' => false,
            ),
        ));*/
        $config = Zend_Registry::get('config');
        $uploadDir = $config->resources->cachemanager->default->backend->options->upload_dir;
        $this->addElement('file', 'descaminho', array(
            'label' => 'Arquivo',
            'required' => false,
            'validators' => array(
                array('Count', false, 1), // 100K
                array(
                    'Size',
                    false,
                    array(
                        'max' => 32768000,
                        'messages' => array(
                            Zend_Validate_File_Size::TOO_BIG => 'Tamanho de arquivo inválido.',
                        ),
                    )
                ), // 1M
                array(
                    'Extension',
                    false,
                    array(
                        'pattern' => 'doc,docx,pdf,ppt,pptx',
                        'messages' => array(
                            Zend_Validate_File_Extension::FALSE_EXTENSION => 'Extensão de arquivo inválida.',
                        )
                    )
                ),

            ),
            //'MultiFile' => 3,
            'destination' => $uploadDir,
            'description' => 'São aceitas as extensões: doc,docx,pdf,ppt,pptx. Tamanho Máximo: 32MB   ',
            'attribs' => array(
                'class' => 'span4',
                'data-rule-required' => false,
            ),
        ));

        $this->getElement('nomresponsavelinterno')
            //->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('submit')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('voltar')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('pesquisar')
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
        $this->getElement('pessoabutton')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
        $this->getElement('adicionar')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');
    }

}

