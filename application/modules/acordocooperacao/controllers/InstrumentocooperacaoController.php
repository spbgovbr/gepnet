<?php

class Acordocooperacao_InstrumentocooperacaoController extends Zend_Controller_Action
{
    public function init()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('detalhar', 'json')
            ->addActionContext('editar', 'json')
            ->addActionContext('add', 'json')
            ->initContext();
    }

    public function indexAction()
    {
        $service = new Acordocooperacao_Service_Acordo();
        $form = $service->getFormPesquisar();
        $this->view->form = $form;

    }

    public function detalharAction()
    {
        $service = new Acordocooperacao_Service_Acordo();
        $this->view->acordo = $service->getByIdDetalhar($this->_request->getParams());
//        Zend_Debug::dump($this->view->acordo); exit;
        if ($this->view->acordo->descaminho) {
            $this->view->nomeArquivo = $this->view->acordo->descaminho;
//            $this->view->anexo = $service->retornaAnexo($this->view->acordo);
        }
    }

    public function addAction()
    {
        $service = new Acordocooperacao_Service_Acordo();
        $form = $service->getForm();
        $success = false;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $dados['file'] = $_FILES;
            $instrumento = $service->inserir($dados);

            if ($instrumento) {
                    $success = true;
                    $msg = App_Service_ServiceAbstract::REGISTRO_CADASTRADO_COM_SUCESSO;
            } else {
                    $msg = $service->getErrors();
            }

            if ($this->_request->isXmlHttpRequest()) {

                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {

                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('add', 'instrumentocooperacao', 'acordocooperacao');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('index', 'instrumentocooperacao', 'acordocooperacao');
            }
        }else {
            $this->view->form = $form;
        }
    }

    public function editarAction()
    {
        $service = new Acordocooperacao_Service_Acordo();
        $serviceEntidadeExterna = new Acordocooperacao_Service_Entidadeexterna();
        $form = $service->getFormEditar();
        $success = false;
        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            $dados['file'] = $_FILES;
            $instrumento = $service->update($dados);
            if ($instrumento) {
                $success = true;
                $msg = App_Service_ServiceAbstract::REGISTRO_ALTERADO_COM_SUCESSO;
            } else {
                $msg = $service->getErrors();
            }

        } else {
            $instrumento = $service->getById($this->_request->getParams());
            $entidades = $serviceEntidadeExterna->retornaEntidadesExternas($this->_request->getParams());

            if ($instrumento['descaminho']) {
                $this->view->nomeArquivo = $instrumento["descaminho"];
            }
            $form->populate($instrumento);
            $this->view->situacaoatual = $instrumento['flasituacaoatual'];
            $this->view->entidades = $entidades;
        }

        $this->view->form = $form;
        if ($this->_request->isPost()) {
            if ($this->_request->isXmlHttpRequest()) {
                $this->view->success = $success;
                $this->view->msg = array(
                    'text' => $msg,
                    'type' => ($success) ? 'success' : 'error',
                    'hide' => true,
                    'closer' => true,
                    'sticker' => false
                );
            } else {
                if ($success) {
                    $this->_helper->_redirector->gotoSimpleAndExit('index', 'instrumentocooperacao',
                        'acordocooperacao');
                }
                $this->_helper->_flashMessenger->addMessage(array('status' => 'error', 'message' => $msg));
                $this->_helper->_redirector->gotoSimpleAndExit('in', 'log', 'default');
            }
        }
    }

    public function pesquisarjsonAction()
    {
        $service = new Acordocooperacao_Service_Acordo();
        $dados = $service->pesquisar($this->_request->getParams(), true);
        $resultado = $service->getFiles($dados);
//        $this->_helper->json->sendJson($resultado->toJqgrid());
        $this->_helper->json->sendJson($resultado);
    }


    public function downloadAction()
    {
        $dados = $this->_request->getParams();
//        print "<PRE>";
        $project = strstr($dados['file'],'-',true);
       // var_dump($dados['file']);die;
//        var_dump($project);

//        $dados['file'] = str_replace(":!","/",$dados['file']);
//        $dados['file'] = base64_decode($dados['file']);

//        Zend_Debug::dump($dados['file']); exit;

        $service = new Acordocooperacao_Service_Acordo();
        $file = $service->getDownloadConfig($dados);

//        var_dump($dados);
        $filename = str_replace(" ", "-", basename($file->path));
//        var_dump(str_replace(" ","-",basename($file->path)));
//        var_dump(mime_content_type ($file->path)); exit;

        header('Content-Description: File Transfer');
        header('Content-Type: ' . mime_content_type($file->path));
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file->path));
        ob_clean();
        flush();
        echo readfile($file->path);
        exit;

    }
}
