<?php

/**
 * Created by PhpStorm.
 * User: Wendell
 * Date: 02/10/2018
 * Time: 17:54
 */
class Default_Service_AutenticarCodigo extends App_Service_ServiceAbstract
{
    protected $_form;

    /** @var Projeto_Service_Assinadocumento */
    protected $_service;

    public function init()
    {
        $this->_service = new Projeto_Service_Assinadocumento();
    }

    /**
     * @return Default_Form_AutenticarCodigo
     */
    public function getForm()
    {
        return $this->_getForm('Default_Form_AutenticarCodigo');
    }

    /**
     * Valida o hash e retorna os dados de assinatura
     * @param $params
     * @return array|bool
     */

    public function validaCodigo($params)
    {

        if (count($params) <= 0) {
            return false;
        }

        try {
            $dadoAssinatura = $this->_service->validaCodigo($params);
            return $dadoAssinatura;
        } catch (Exception $exception) {
//            $exception->getMessage();
            return false;
        }
    }

}