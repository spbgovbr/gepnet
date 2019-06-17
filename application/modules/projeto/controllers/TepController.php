<?php

class Projeto_TepController extends Zend_Controller_Action
{

    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('editar', 'json')
            ->initContext();
        $servicePerfilPessoa = new Default_Service_Perfilpessoa();
        $dadosEntrada = array(
            "idprojeto" => $this->_request->getParam('idprojeto'),
            "controller" => strtolower($this->_request->getControllerName()),
            "action" => strtolower($this->_request->getActionName()),
        );
        if (!$servicePerfilPessoa->isValidaControllerAction($dadosEntrada)) {
            $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => 'Acesso negado...'));
            $this->_helper->_redirector->gotoSimpleAndExit('forbidden', 'error', 'projeto');
        }
    }

    public function indexAction()
    {
    }

    public function editarAction()
    {
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Tep');
        $serviceGerencia = App_Service_ServiceAbstract::getService('Projeto_Service_Gerencia');
        $form = $service->getFormTep();
        $serviceAssinatura = new Projeto_Service_Assinadocumento();
        $formAssinatura = $serviceAssinatura->getFormTap();
        $success = false;
        $msgError = "";
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $gerencia = $service->update($dados);

            if ($gerencia) {
                $success = true; ###### AUTENTICATION SUCCESS
                /** Cadastra na linha do tempo (auditoria). */
                $serviceLinhaTempo = new Projeto_Service_LinhaTempo();
                $dados["idrecurso"] = $serviceLinhaTempo->getRecurso($this->_request->getControllerName())["idrecurso"]; // Identifica o registro dos controles  de modulos.
                $dados['tpacao'] = 'A'; // Tipo de ação executada na funcionalidade: N - Novo, A - Alteração ou E - Exclusão.
                $dados['idprojeto'] = $dados["idprojeto"]; // Projeto que sofreu a ação.
                $serviceLinhaTempo->inserir($dados);
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = App_Service_ServiceAbstract::ERRO_GENERICO;
                $msgError = $service->getErrors();
            }
        } else {
            $gerencia = $serviceGerencia->retornaProjetoPorId(array('idprojeto' => $this->_request->getParam('idprojeto')));
            $form->populate($gerencia->formPopulate());
            $this->view->gerencia = $gerencia;
            $this->view->form = $form;
            $this->view->formAssinatura = $formAssinatura;
        }

        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msgError = $msgError;
                $this->view->msg = array(
                    'text' => (is_array($msg)) ? array_shift($msg) : $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('edit', 'gerencia', 'default');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }
}