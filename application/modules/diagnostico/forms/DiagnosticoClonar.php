<?php

/**
 * Created by PhpStorm.
 * User: wendell.wlfl
 * Date: 25/09/2019
 * Time: 08:34
 */
class Diagnostico_Form_DiagnosticoClonar extends App_Form_FormAbstract
{

    public function init()
    {
        /** Chamada para Default Service */
        $serviceDiagnostico = new Diagnostico_Service_Diagnostico();

        $this
            ->setAttrib('enctype', 'multipart/form-data')
            ->setOptions(array(
                "method" => "post",
                "enctype" => Zend_Form::ENCTYPE_URLENCODED,
                "id" => "form-clonar-diagnostico",
                "elements" => array(
                    'idDiagnosticoAnterior' => array('hidden', array()),
                    'iddiagnostico' => array('hidden', array()),
                    'dsdiagnostico' => array(
                        'text',
                        array(
                            'label' => 'Nome do Diagnóstico',
                            'required' => true,
                            'maxlength' => '100',
                            'filters' => array('StringTrim', 'StripTags'),
                            'validators' => array(array('NotEmpty', 'StringLength', false, array(0, 100))),
                            'attribs' => array(
                                'class' => 'span3',
                                'data-rule-required' => true,
                                'readonly' => true,
                            ),
                        )
                    ),
                    'idunidadeprincipal' => array(
                        'select',
                        array(
                            'label' => 'Unidade Principal',
                            'required' => true,
                            'multiOptions' => array('' => 'Selecione') + $serviceDiagnostico->getListarUnidadePrincipalFetchPairs(),
                            'filters' => array('StringTrim', 'StripTags'),
                            'attribs' => array(
                                'class' => 'select2',
                                'data-rule-required' => true,
                                'id' => 'idunidadeprincipal'
                            ),
                        )
                    ),
                    'clonar' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Clonar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'enviarButton',
                                'type' => 'submit',
                            ),
                        )
                    ),
                    'cancelar' => array(
                        'button',
                        array(
                            'ignore' => true,
                            'label' => 'Cancelar',
                            'escape' => false,
                            'attribs' => array(
                                'id' => 'backButton',
                                'type' => 'button',
                            ),
                        )
                    ),
                )
            ));

        $this->getElement('cancelar')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');

        $this->getElement('clonar')
            ->removeDecorator('label')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Wrapper');


    }

}
