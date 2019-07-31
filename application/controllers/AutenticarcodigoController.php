<?php

/**
 * Created by PhpStorm.
 * User: Wendell
 * Date: 02/10/2018
 * Time: 18:42
 */

class AutenticarcodigoController extends Zend_Controller_Action
{

    public function init()
    {

    }

    public function indexAction()
    {
        try {
            $service = new Default_Service_AutenticarCodigo();
            $form = $service->getForm();
            $dadosRecebidos = array();


            if ($this->_request->isPost()) {
                $params = $this->_getAllParams();
                $dadosRecebidos = $service->validaCodigo($params);

                if (is_array($dadosRecebidos) && (count($dadosRecebidos) > 0)) {
                    $this->view->dadosrecebidos = $dadosRecebidos;

                } else {
                    return $this->_helper->_redirector->gotoSimpleAndExit('index');
                }
            }
            $this->view->layout()->setLayout('login');
            $this->view->dadosrecebidos = $dadosRecebidos;
            $this->view->form = $form;

        } catch (Exception $exception) {
            $this->view->message = $exception->getMessage();
            $this->_helper->_flashMessenger->addMessage(array(
                'status' => 'error',
                'message' => $exception->getMessage()
            ));
            return $this->_helper->_redirector->gotoSimpleAndExit('index');
        }
    }


}

