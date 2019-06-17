<?php

class Default_Service_Email extends App_Service_ServiceAbstract
{

    protected $_form;
    protected $_mapper;

    public function init()
    {
        //$this->_mapper = new Default_Model_Mapper_Documento();
    }

    /**
     * Servico de envio de email
     *
     * @param array $params ['to'], $params['subject'], $params['body'], $params['from'],
     * @return boolean
     */
    public function enviaEmail($params)
    {
        $params['from'] = !empty($params['from']) ? $params['from'] : "From: GEPNet - Gestor de Escritórios de Projetos <gepnet@no-reply.com>";
        try {
            if (empty($params['to'])) {
                return false;
            }
            mail($params['to'], $params['subject'], $params['body'], $params['from']);
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }
}

?>