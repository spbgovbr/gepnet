<?php

class Pesquisa_Form_Pergunta extends App_Form_FormAbstract
{

    public function init()
    {
        $arrTipoResposta = array(
            '' => 'Selecione',
            Pesquisa_Model_Frase::UMA_ESCOLHA => 'Uma-escolha',
            Pesquisa_Model_Frase::MULTIPLA_ESCOLHA => 'Multipla-escolha',
            Pesquisa_Model_Frase::DESCRITIVO => 'Descritivo (em várias linhas)',
            Pesquisa_Model_Frase::TEXTO => 'Texto (em uma linha)',
            Pesquisa_Model_Frase::NUMERO => 'Número',
            Pesquisa_Model_Frase::DATA => 'Data',
            Pesquisa_Model_Frase::UF => 'UF',
        );
        $arrSituacao = array(
            '' => 'Selecione',
            Pesquisa_Model_Frase::ATIVO => 'Ativo',
            Pesquisa_Model_Frase::INATIVO => 'Inativo',
        );


        $this->setOptions(array(
            'method' => 'post',
            'id' => 'form-pergunta',
            'elements' => array(
                'idfrase' => array(
                    'hidden',
                    array(
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('Digits'),
                    )
                ),
                'desfrase' => array(
                    'text',
                    array(
                        'label' => 'Pergunta',
                        'required' => true,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 255))),
                        'attribs' => array(
                            'class' => 'span',
                            'maxlength' => '255',
                            'data-rule-maxlength' => 255,
                            'data-rule-required' => true,
                            'placeholder' => 'Informe a pergunta.'
                        ),
                    )
                ),
                'domtipofrase' => array(
                    'select',
                    array(
                        'label' => 'Tipo de Resposta da Pergunta',
                        'required' => true,
                        'multiOptions' => $arrTipoResposta,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'data-rule-required' => true,
                        ),
                    )
                ),
                'flaativo' => array(
                    'select',
                    array(
                        'label' => 'Situação',
                        'required' => true,
                        'multiOptions' => $arrSituacao,
                        'filters' => array('StringTrim', 'StripTags'),
                        'validators' => array('NotEmpty', array('StringLength', false, array(0, 1))),
                        'attribs' => array(
                            'class' => 'span',
                            'data-rule-required' => true,
                        ),
                    )
                ),
            )
        ));
    }
}
