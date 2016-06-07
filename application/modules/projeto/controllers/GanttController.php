<?php

class Projeto_GanttController extends Zend_Controller_Action
{

    const ano_meses = 1;
    const ano_meses_semanas = 2;
    const ano_meses_dias = 3;
    const ano_meses_semanas_dias = 4;

    public function visualizarAction()
    {
        set_time_limit(180);
        require APPLICATION_PATH . '/../library/App/Gantt/gantti.php';
        
        $service = App_Service_ServiceAbstract::getService('Projeto_Service_Gantt');
        $request = $this->getRequest();
        $idprojeto = $request->getParam('idprojeto');
        $result = $service->montaDadosGantt(array('idprojeto' => $idprojeto));
        $form = $service->getFormGantt();
        $this->view->form = $form;
        $this->view->idprojeto = $idprojeto;
        $this->_helper->layout()->setLayout('gantt'); //seta o layout especifico evitando travamento dos scripts js

        if ($result) {
            if ( $request->isPost() ) {
                 $gantt = new Gantti($result, array(
                    'title' => 'GANTT',
                    'cellwidth' => 40,
                    'cellheight' => 35,
                    'today' => true,
                    'show_header_type' => $request->getPost('tipoexibicao'),
                ));
                $this->view->htmlGantt = $gantt;

            } else {
                //exibicao default
                $gantt = new Gantti($result, array(
                    'title' => 'GANTT',
                    'cellwidth' => 40,
                    'cellheight' => 35,
                    'today' => true,
                    'show_header_type' => self::ano_meses_dias,
                ));

                $this->view->htmlGantt = $gantt;
            }
        } else {
            $this->view->htmlGantt = 'Nenhum resultado encotrado.';
        }
    }

}
