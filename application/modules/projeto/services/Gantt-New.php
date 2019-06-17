<?php

class Projeto_Service_Gantt extends App_Service_ServiceAbstract
{
    public $_mapper = null;
    protected $_form = null;
    protected $auth = null;

    /**
     * @var array
     */
    public $errors = array();

    public function init()
    {
        $login = App_Service_ServiceAbstract::getService('Default_Service_Login');
        $this->auth = $login->retornaUsuarioLogado();
        $this->_mapper = new Projeto_Model_Mapper_Atividadecronograma();
    }

    public function getFormGantt()
    {
        $this->_form = new Projeto_Form_Gantt();
        return $this->_form;
    }

    /**
     * Monta os array para exibicao do gantt
     * @param integer $idprojeto - identificador do projeto
     * @return array
     */
    public function montaDadosGantt($params)
    {
        $atividadePredecessora = new Projeto_Model_Mapper_Atividadepredecessora();
        $resultAtividades = $this->_mapper->retornaAtividadeGantt($params);
        $itemNivel1 = '';
        $itemNivel2 = '';
        $itemNivel3 = '';
        $data = array();

        $cont = 0;
        foreach ($resultAtividades as $atividade) {

            //monta atividades nivel 1
            if ($itemNivel1 != $atividade['nv1_idatividadecronograma']) {

                $data[] = array(
                    'id' => $atividade['nv1_idatividadecronograma'],
                    'orderId' => $cont,
                    'parentId' => null,
                    'start' => $atividade['nv1_datinicio'],
                    'end' => $atividade['nv1_datfim'],
                    'title' => $atividade['nv1_nomatividadecronograma'],
                    'percentComplete' => (int)$atividade['nv1_numpercentualconcluido'],
                    'summary' => false,
                    'expanded' => true
                    //'node'  => 'nivel1',
                    //'class' => 'success'
                );
                $itemNivel1 = $atividade['nv1_idatividadecronograma'];
                $cont++;
            }

            //monta atividades nivel 2
            if ($atividade['nv2_idatividadecronograma'] != "" && $atividade['nv2_idatividadecronograma'] != $itemNivel2) {
                $data[] = array(
                    'id' => $atividade['nv2_idatividadecronograma'],
                    'orderId' => $cont,
                    'parentId' => null,
                    'start' => $atividade['nv2_datinicio'],
                    'end' => $atividade['nv2_datfim'],
                    'title' => $atividade['nv2_nomatividadecronograma'],
                    'percentComplete' => (int)$atividade['nv2_numpercentualconcluido'],
                    'summary' => false,
                    'expanded' => true
                    //'node'  => 'nivel2',
                    //'class' => 'important'
                );
                $itemNivel2 = $atividade['nv2_idatividadecronograma'];
                $cont++;
            }

            //monta atividades nivel 3
            if ($atividade['nv3_idatividadecronograma'] != "" && $atividade['nv3_idatividadecronograma'] != $itemNivel3) {
                if ($atividade['nv3_domtipoatividade'] == Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO) {
                    $predecessoraMarco = $atividadePredecessora->retornaPorAtividadeProjeto(array(
                        'idprojeto' => $params['idprojeto'],
                        'idatividade' => $atividade['nv3_idatividadecronograma']
                    ));

                    if (count($predecessoraMarco) > 0) {
                        foreach ($predecessoraMarco as $pred) {
                            $predecessora = $pred['idatividadepredecessora'];
                        }
                    }

                    $data[] = array(
                        'id' => $atividade['nv3_idatividadecronograma'],
                        'orderId' => $cont,
                        'parentId' => $predecessora,
                        'start' => $atividade['nv3_datinicio'],
                        'end' => $atividade['nv3_datfim'],
                        'title' => $atividade['nv3_nomatividadecronograma'],
                        'percentComplete' => (int)$atividade['nv3_numpercentualconcluido'],
                        'summary' => false,
                        'expanded' => false
                        //'class'     => 'urgent',
                        //'node'      => 'nivel3',
                        //'tipoAtividade' => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO,
                        // 'idpredecessora' => @$predecessoraMarco,
                    );
                    $cont++;
                } else {
                    $predecessoras = $atividadePredecessora->retornaPorAtividadeProjeto(array(
                        'idprojeto' => $params['idprojeto'],
                        'idatividade' => $atividade['nv3_idatividadecronograma']
                    ));
                    if (count($predecessoras) > 0) {
                        foreach ($predecessoras as $pred) {
                            $predecessora = $pred['idatividadepredecessora'];
                        }
                    }
                    $data[] = array(

                        'id' => $atividade['nv3_idatividadecronograma'],
                        'orderId' => $cont,
                        'parentId' => $predecessora,
                        'start' => $atividade['nv3_datinicio'],
                        'end' => $atividade['nv3_datfim'],
                        'title' => $atividade['nv3_nomatividadecronograma'],
                        'percentComplete' => (int)$atividade['nv3_numpercentualconcluido'],
                        'summary' => false,
                        'expanded' => false
                        //'idatividade'=>$atividade['nv3_idatividadecronograma'],
                        //'label' => $atividade['nv3_nomatividadecronograma'],
                        //'start' => $atividade['nv3_datinicio'],
                        //'end'   => $atividade['nv3_datfim'],
                        //'progress' => $atividade['nv3_numpercentualconcluido'],
                        //'node'  => 'nivel3',
                        //'idpredecessora' => @$predecessora,
                    );
                    $cont++;
                }

                $itemNivel3 = $atividade['nv3_idatividadecronograma'];
            }

        }

        /*foreach ( $resultAtividades as $atividade) {

            //monta atividades nivel 1
            if($itemNivel1 != $atividade['nv1_idatividadecronograma']) {
                $data[] = array(
                    'idatividade'=>$atividade['nv1_idatividadecronograma'],
                    'label' => $atividade['nv1_nomatividadecronograma'],
                    'start' => $atividade['nv1_datinicio'],
                    'end'   => $atividade['nv1_datfim'],
                    'node'  => 'nivel1',
                    'class' => 'success'
                );
                $itemNivel1 = $atividade['nv1_idatividadecronograma'];
            }

            //monta atividades nivel 2
            if($atividade['nv2_idatividadecronograma'] != "" && $atividade['nv2_idatividadecronograma'] != $itemNivel2) {
                $data[] = array(
                    'idatividade'=>$atividade['nv2_idatividadecronograma'],
                    'label' => $atividade['nv2_nomatividadecronograma'],
                    'start' => $atividade['nv2_datinicio'],
                    'end'   => $atividade['nv2_datfim'],
                    'node'  => 'nivel2',
                    'class' => 'important'
                );
                $itemNivel2 = $atividade['nv2_idatividadecronograma'];
            }

            //monta atividades nivel 3
            if($atividade['nv3_idatividadecronograma'] != "" && $atividade['nv3_idatividadecronograma'] != $itemNivel3) {
                if ($atividade['nv3_domtipoatividade'] == Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO) {
                    $predecessoraMarco =  $atividadePredecessora->retornaPorAtividadeProjeto(array('idprojeto'=>$params['idprojeto'], 'idatividade'=>$atividade['nv3_idatividadecronograma']));
                    $data[] = array(
                        'idatividade'=>$atividade['nv3_idatividadecronograma'],
                        'label'     => $atividade['nv3_nomatividadecronograma'],
                        'start'     => $atividade['nv3_datinicio'],
                        'end'       => $atividade['nv3_datfim'],
                        'class'     => 'urgent',
                        'node'      => 'nivel3',
                        'tipoAtividade' => Projeto_Model_Atividadecronograma::TIPO_ATIVIDADE_MARCO,
                        'idpredecessora' => @$predecessoraMarco,
                    );
                } else {
                    $predecessora =  $atividadePredecessora->retornaPorAtividadeProjeto(array('idprojeto'=>$params['idprojeto'], 'idatividade'=>$atividade['nv3_idatividadecronograma']));
                    $data[] = array(
                        'idatividade'=>$atividade['nv3_idatividadecronograma'],
                        'label' => $atividade['nv3_nomatividadecronograma'],
                        'start' => $atividade['nv3_datinicio'],
                        'end'   => $atividade['nv3_datfim'],
                        'progress' => $atividade['nv3_numpercentualconcluido'],
                        'node'  => 'nivel3',
                        'idpredecessora' => @$predecessora,
                    );
                }

                $itemNivel3 = $atividade['nv3_idatividadecronograma'];
            }
        }*/

        return $data;
    }
}
