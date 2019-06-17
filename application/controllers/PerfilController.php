<?php

class PerfilController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $service = new Default_Service_Perfil();
        $this->view->dados = $service->fetchAll();
    }

    public function cadastrarAction()
    {
        $service = new Default_Service_Perfil();

        if ($this->_request->isPost()) {
            if ($service->inserir($this->_request->getPost())) {
                $this->_helper->flashMessenger(array(
                    'message' => 'Registro inserido com sucesso!',
                    'status' => 'success'
                ));
                $this->_helper->redirector->gotoRouteAndExit(array(), 'perfil');
            } else {
                $this->_helper->flashMessenger(array(
                    'message' => 'Erro ao tentar inserir o registro',
                    'status' => 'error'
                ));
                $this->_helper->redirector->gotoRouteAndExit(array(), 'perfil-cadastrar');
                //Zend_Debug::dump('teste');exit;
            }
        }
        $this->view->form = $service->getForm();
    }

    public function delAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        $service = new Default_Service_Perfil();
        //if ( $this->_request->isPost() ) {
        if ($service->delete($this->_request->getParams())) {
            $this->_helper->flashMessenger(array('message' => 'Registro excluido com sucesso!', 'status' => 'success'));
            $this->_helper->redirector->gotoRouteAndExit(array(), 'perfil');
        } else {
            $this->_helper->flashMessenger(array(
                'message' => 'Erro ao tentar excluir o registro',
                'status' => 'error'
            ));
            $this->_helper->redirector->gotoRouteAndExit(array(), 'perfil');
            //Zend_Debug::dump('teste');exit;
        }
        //} 
    }

    public function editarAction()
    {
        $service = new Default_Service_Perfil();
        $form = $service->getForm();
        if ($this->_request->isPost()) {
            if ($service->editar($this->_request->getPost())) {
                $this->_helper->flashMessenger(array(
                    'message' => 'Registro atualizado com sucesso!',
                    'status' => 'success'
                ));
                $this->_helper->redirector->gotoRouteAndExit(array(), 'perfil');
            } else {
                $this->_helper->flashMessenger(array(
                    'message' => 'Erro ao tentar atualizar o registro',
                    'status' => 'error'
                ));
                $this->_helper->redirector->gotoRouteAndExit(array(), 'perfil-editar');
                //Zend_Debug::dump('teste');exit;
            }
        } else {
            $row = $service->find($this->_request->getParam('id'));
            $form->populate($row);
            // Zend_Debug::dump($row);exit;
        }
        $this->view->form = $form;
    }

    public function consultaAction()
    {

        //Instancia o formulario de login
        $form = new Default_Form_ConsultaOS();
        //Define o metodo: post ou get
        $form->setMethod('post');
        //Monta o fomulario na view
        $this->view->form = $form;

        //Verifica se foi submetido via POST
        if ($this->getRequest()->isPost()) {
            //Obtém os dados passados via POST
            $formData = $this->getRequest()->getPost();

            //var_dump($form->getValues('os_id'));
            //Verifica se os dados sao validos
            if ($form->isValid($formData)) {
                //var_dump($this->_request->getPost());
                //Passa os valores dos campos para a função cons
                $os = new Application_Model_OS();
                $resultado = $os->cons($form->getValues());
                $os->cons($form->getValues());
                $count = count($resultado);
                //$this->view->resultado = $resultado;

                if ($count == 0) {
                    $this->view->msg = 'Solicitacao não encontrada';
                } else {
                    $this->view->count = $count;
                    $this->view->resultado = $resultado;
                    //$this->view->form2 = $form2;
                    //Zend_Debug::dump($resultado);
                    //var_dump ($resultado);
                }
            } else {

            }
        }
    }

    public function updateAction()
    {

        $os = new Application_Model_OS();
        $resultado = $os->cons($this->_getParam('id'));
        $os->cons($this->_getParam('id'));
        //$count = count($resultado);
        $this->view->resultado = $resultado;

        //Instancia o formulario de login
        $form = new Default_Form_UpdateOS();
        //Define o metodo: post ou get
        $form->setMethod('post');
        $form->populate($resultado[0]);
        //Monta o fomulario na view
        $this->view->form = $form;
        //Zend_Debug::dump($resultado);
        //var_dump ($resultado);

        if ($this->getRequest()->isPost()) {
            //Obtém os dados passados via POST
            $formData = $this->getRequest()->getPost();

            //Verifica se os dados sao validos
            if ($form->isValid($formData)) {
                //var_dump($this->_request->getPost());
                //Passa os valores dos campos para a função cons
                $os = new Application_Model_OS();
                $os->upd($form->getValues(), $this->_getParam('id'));
                //$this->_redirect('/os/lista');
                $this->view->msg = 'Ordem de serviço finalizada!';
            }
        }
    }

    public function deleteAction()
    {

        //Instancia o formulario de login
        $form = new Default_Form_DeleteUsuario();
        //Define o metodo: post ou get
        $form->setMethod('post');
        //Monta o fomulario na view
        $this->view->form = $form;

        //Verifica se foi submetido via POST
        if ($this->getRequest()->isPost()) {
            //Obtém os dados passados via POST
            $formData = $this->getRequest()->getPost();

            //Verifica se os dados sao validos
            if ($form->isValid($formData)) {
                //var_dump($this->_request->getPost());
                //Passa os valores dos campos para a função add
                $usuarios = new Application_Model_Usuario();
                $usuarios->del($form->getValues());

                //$this->_redirect('/usuario/consulta');
            } else {

            }
        }
    }

    public function listaAction()
    {

        $os = new Application_Model_OS();
        $resultado = $os->lista();
        $count = count($resultado);
        $this->view->resultado = $resultado;
        $this->view->count = $count;

        //Paginator:
        $pagina = intval($this->_getParam('page', 1));
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($resultado));
        //$paginator = Zend_Paginator::factory($resultado);
        // Seta a quantidade de registros por página
        $paginator->setItemCountPerPage(15);
        // numero de paginas que serão exibidas
        $paginator->setPageRange(10);
        // Seta a página atual
        $paginator->setCurrentPageNumber($pagina);
        $this->view->paginator = $paginator;
    }

    public function lista2Action()
    {

        $os = new Application_Model_OS();
        $usuario = Zend_Auth::getInstance()->getIdentity();
        $resultado = $os->lista2($usuario->usu_id);
        $count = count($resultado);
        $this->view->resultado = $resultado;
        $this->view->count = $count;

        //Paginator:
        $pagina = intval($this->_getParam('page', 1));
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($resultado));
        //$paginator = Zend_Paginator::factory($resultado);
        // Seta a quantidade de registros por página
        $paginator->setItemCountPerPage(15);
        // numero de paginas que serão exibidas
        $paginator->setPageRange(10);
        // Seta a página atual
        $paginator->setCurrentPageNumber($pagina);
        $this->view->paginator = $paginator;
    }

}

?>